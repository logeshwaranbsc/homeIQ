<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\WarrantyRegistrationRepository;
use App\Repositories\WarrantyPolicyRepository;
use App\Services\WarrantyEmailService;
use App\Services\WarrantyAuditService;
use Throwable;

class WarrantyRegistrationController
{
    private WarrantyRegistrationRepository $repo;
    private WarrantyPolicyRepository $policyRepo;
    private WarrantyEmailService $emailService;
    private WarrantyAuditService $auditService;
    private array $config;

    public function __construct()
    {
        $this->repo = new WarrantyRegistrationRepository();
        $this->policyRepo = new WarrantyPolicyRepository();
        $this->emailService = new WarrantyEmailService();
        $this->auditService = new WarrantyAuditService();

        $base = \App\Core\App::getBasePath() ?: dirname(__DIR__, 3);
        $configFile = $base . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'warranty-config.php';
        $this->config = file_exists($configFile) ? require $configFile : [];
    }

    public function index(Request $request): void
    {
        $categories = $this->config['categories'] ?? [
            'Smart Door Lock',
            'VDP',
            'Retrofit Smart Module',
            'Smart Touch Switch',
            'Other',
        ];

        $platforms = $this->config['platforms'] ?? [
            'HOMEIQ Website',
            'Amazon',
            'Flipkart',
            'Other',
        ];

        echo View::render('storefront/pages/warranty_registration', [
            'title' => 'Register Your HOMEIQ Product | Official Warranty Registration',
            'categories' => $categories,
            'platforms' => $platforms,
            'declarationText' => $this->config['declaration_text'] ?? '',
            'errors' => flash('errors') ?? [],
            'old' => flash('old') ?? [],
        ]);
    }

    public function submit(Request $request): void
    {
        $fullName = trim((string)$request->input('full_name', ''));
        $mobileNumber = trim((string)$request->input('mobile_number', ''));
        $email = trim((string)$request->input('email', ''));

        $category = trim((string)$request->input('category', ''));
        $model = trim((string)$request->input('model', ''));
        $serialNumber = trim((string)$request->input('serial_number', ''));
        $serialUnavailable = (bool)$request->input('serial_unavailable', false);
        $alternativeNotes = trim((string)$request->input('alternative_notes', ''));

        $platform = trim((string)$request->input('platform', ''));
        $purchaseDate = trim((string)$request->input('purchase_date', ''));
        $orderId = trim((string)$request->input('order_id', ''));
        $declaration = (bool)$request->input('declaration_accepted', false);
        $marketingConsent = (bool)$request->input('marketing_consent', false);

        $errors = [];

        // 1. Customer Validation
        if ($fullName === '' || mb_strlen($fullName) < 2) {
            $errors['full_name'] = 'Full Name is required (minimum 2 characters).';
        }

        // Mobile validation (10 digits Indian phone format)
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobileNumber);
        if (str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 12) {
            $cleanPhone = substr($cleanPhone, 2);
        }
        if (strlen($cleanPhone) !== 10 || !preg_match('/^[6-9]\d{9}$/', $cleanPhone)) {
            $errors['mobile_number'] = 'Please enter a valid 10-digit Indian mobile number.';
        }

        // Email validation (optional, but must be valid if entered)
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        // 2. Product Validation
        $allowedCategories = $this->config['categories'] ?? [];
        if ($category === '' || (!empty($allowedCategories) && !in_array($category, $allowedCategories, true))) {
            $errors['category'] = 'Please select a valid product category.';
        }

        if ($model === '') {
            $errors['model'] = 'Product model is required.';
        }

        if (!$serialUnavailable && $serialNumber === '') {
            $errors['serial_number'] = 'Product serial number is required, or check "Serial Number Unavailable".';
        }

        if ($serialUnavailable && $alternativeNotes === '') {
            $errors['alternative_notes'] = 'Please provide alternative identification notes (e.g. description, color, lock type, batch markings).';
        }

        // 3. Purchase Validation
        $allowedPlatforms = $this->config['platforms'] ?? [];
        if ($platform === '' || (!empty($allowedPlatforms) && !in_array($platform, $allowedPlatforms, true))) {
            $errors['platform'] = 'Please select your purchase platform.';
        }

        if ($purchaseDate === '') {
            $errors['purchase_date'] = 'Purchase date is required.';
        } else {
            $pTs = strtotime($purchaseDate);
            if ($pTs === false || $pTs > strtotime('+1 day')) {
                $errors['purchase_date'] = 'Please select a valid past or today purchase date.';
            }
        }

        if ($orderId === '') {
            $errors['order_id'] = 'Order ID / Invoice Reference is required.';
        }

        // 4. Invoice File Validation
        $invoiceFile = $_FILES['invoice'] ?? null;
        if (!$invoiceFile || empty($invoiceFile['name']) || ($invoiceFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors['invoice'] = 'Purchase invoice file is required (PDF, JPG, JPEG, or PNG).';
        } else {
            $maxBytes = (int)($this->config['max_invoice_size_bytes'] ?? (5 * 1024 * 1024));
            if (($invoiceFile['size'] ?? 0) > $maxBytes) {
                $errors['invoice'] = 'Invoice file size must not exceed 5 MB.';
            }

            $ext = strtolower(pathinfo($invoiceFile['name'], PATHINFO_EXTENSION));
            $allowedExts = $this->config['allowed_invoice_ext'] ?? ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowedExts, true)) {
                $errors['invoice'] = 'Allowed file formats: PDF, JPG, JPEG, PNG.';
            }
        }

        // 5. Declaration
        if (!$declaration) {
            $errors['declaration_accepted'] = 'You must confirm the genuine purchase declaration to register.';
        }

        if (!empty($errors)) {
            flash('errors', $errors);
            flash('old', $request->all());
            redirect('warranty-registration');
            return;
        }

        try {
            // Generate ID with locking
            $registrationId = $this->repo->generateNextId();

            // Store invoice securely in protected directory
            $invoiceDetails = $this->repo->storeInvoice($registrationId, $invoiceFile);

            // Construct structured JSON payload
            $registrationData = [
                'registration_id' => $registrationId,
                'customer' => [
                    'full_name' => $fullName,
                    'mobile_number' => $cleanPhone,
                    'email' => $email !== '' ? $email : null,
                ],
                'product' => [
                    'category' => $category,
                    'model' => $model,
                    'serial_number' => $serialUnavailable ? null : $serialNumber,
                    'serial_unavailable' => $serialUnavailable,
                    'alternative_notes' => $serialUnavailable ? $alternativeNotes : null,
                ],
                'purchase' => [
                    'platform' => $platform,
                    'purchase_date' => $purchaseDate,
                    'order_id' => $orderId,
                    'invoice_path' => $invoiceDetails['invoice_path'],
                    'invoice_original_name' => $invoiceDetails['invoice_original_name'],
                    'invoice_mime' => $invoiceDetails['invoice_mime'],
                    'invoice_size' => $invoiceDetails['invoice_size'],
                ],
                'declaration_accepted' => true,
                'marketing_consent' => $marketingConsent,
                'status' => 'PENDING',
                'warranty' => [
                    'duration_months' => null,
                    'start_date' => null,
                    'end_date' => null,
                    'policy_id' => null,
                ],
                'admin_review' => [
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'notes' => null,
                    'rejection_reason' => null,
                ],
            ];

            // Save JSON atomically
            $saved = $this->repo->create($registrationData);

            // Record audit log
            $this->auditService->record(
                $registrationId,
                'SUBMITTED',
                $fullName . ' (' . $cleanPhone . ')',
                "New warranty registration created for {$model} via {$platform}."
            );

            // Send acknowledgment email if email was provided
            if ($email !== '') {
                try {
                    $this->emailService->sendReceived($saved);
                } catch (Throwable $e) {
                    error_log('Warranty received email failed: ' . $e->getMessage());
                }
            }

            // Render success page
            echo View::render('storefront/pages/warranty_success', [
                'title' => 'Warranty Registration Submitted | HOMEIQ',
                'registration' => $saved,
            ]);
        } catch (Throwable $e) {
            error_log('Warranty registration error: ' . $e->getMessage());
            flash('errors', ['general' => 'We encountered an error processing your registration: ' . $e->getMessage()]);
            flash('old', $request->all());
            redirect('warranty-registration');
        }
    }
}
