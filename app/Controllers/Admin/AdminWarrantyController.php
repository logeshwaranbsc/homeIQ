<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\WarrantyRegistrationRepository;
use App\Repositories\WarrantyPolicyRepository;
use App\Services\WarrantyEmailService;
use App\Services\WarrantyAuditService;
use Throwable;

class AdminWarrantyController
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

        $configFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'warranty-config.php';
        $this->config = file_exists($configFile) ? require $configFile : [];
    }

    /**
     * Dashboard & Listing View
     */
    public function index(Request $request): void
    {
        $status = strtoupper((string)$request->query('status', 'ALL'));
        $category = (string)$request->query('category', 'ALL');
        $q = trim((string)$request->query('q', ''));
        $startDate = (string)$request->query('start_date', '');
        $endDate = (string)$request->query('end_date', '');
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 15;

        $filters = [
            'status' => $status,
            'category' => $category,
            'q' => $q,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $filteredRegistrations = $this->repo->filter($filters);
        $totalItems = count($filteredRegistrations);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $offset = ($page - 1) * $perPage;
        $pagedItems = array_slice($filteredRegistrations, $offset, $perPage);

        $counts = $this->repo->getCounts();
        $categories = $this->config['categories'] ?? [];

        echo View::render('admin/warranty_registrations/index', [
            'title' => 'Warranty Registrations | HOMEIQ Admin',
            'registrations' => $pagedItems,
            'counts' => $counts,
            'categories' => $categories,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'per_page' => $perPage,
            ],
        ], 'admin');
    }

    /**
     * Detailed View
     */
    public function show(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $registration = $this->repo->find($id);

        if (!$registration) {
            flash('error', "Warranty registration '{$id}' was not found.");
            redirect('admin/warranty-registrations');
            return;
        }

        $auditLogs = $this->auditService->getForRegistration($id);
        $emailLogs = $this->emailService->getForRegistration($id);

        // Pre-calculate suggested warranty policy if currently PENDING
        $suggestedWarranty = null;
        if (($registration['status'] ?? '') === 'PENDING') {
            $cat = $registration['product']['category'] ?? '';
            $pDate = $registration['purchase']['purchase_date'] ?? date('Y-m-d');
            $suggestedWarranty = $this->policyRepo->calculateWarrantyDates($pDate, $cat);
        }

        $rejectionReasons = $this->config['rejection_reasons'] ?? [
            'Invalid Invoice',
            'Incorrect Serial Number',
            'Product Details Mismatch',
            'Invalid Order Details',
            'Purchase Details Could Not Be Verified',
            'Other',
        ];

        echo View::render('admin/warranty_registrations/view', [
            'title' => "Warranty #{$id} | HOMEIQ Admin",
            'registration' => $registration,
            'auditLogs' => $auditLogs,
            'emailLogs' => $emailLogs,
            'suggestedWarranty' => $suggestedWarranty,
            'rejectionReasons' => $rejectionReasons,
        ], 'admin');
    }

    /**
     * Approve Warranty Registration
     */
    public function approve(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $registration = $this->repo->find($id);

        if (!$registration) {
            flash('error', "Warranty registration '{$id}' was not found.");
            redirect('admin/warranty-registrations');
            return;
        }

        $admin = auth_admin();
        $adminName = $admin['name'] ?? ($admin['email'] ?? 'Administrator');

        $notes = trim((string)$request->input('notes', 'Verified genuine purchase invoice and valid serial credentials.'));
        $purchaseDate = $registration['purchase']['purchase_date'] ?? date('Y-m-d');
        $cat = $registration['product']['category'] ?? '';
        $model = $registration['product']['model'] ?? '';

        // Calculate warranty dates
        $durationMonths = (int)$request->input('duration_months', 0);
        if ($durationMonths <= 0) {
            $policy = $this->policyRepo->getPolicyForCategory($cat, $model);
            $durationMonths = (int)($policy['duration_months'] ?? 12);
        }

        $startDate = (string)$request->input('start_date', '');
        if ($startDate === '') {
            $startDate = $purchaseDate;
        }

        $endDate = (string)$request->input('end_date', '');
        if ($endDate === '') {
            $endDate = date('Y-m-d', strtotime("+{$durationMonths} months", strtotime($startDate)));
        }

        $warrantyData = [
            'duration_months' => $durationMonths,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'approved_at' => date('c'),
        ];

        $reviewData = [
            'notes' => $notes,
            'rejection_reason' => null,
        ];

        $success = $this->repo->updateStatus($id, 'APPROVED', $reviewData, $warrantyData, $adminName);

        if ($success) {
            // Record in audit log
            $this->auditService->record(
                $id,
                'APPROVED',
                $adminName,
                "Approved warranty coverage for {$durationMonths} months (Ends: {$endDate}). Notes: {$notes}"
            );

            // Send notification email to customer
            $updatedReg = $this->repo->find($id);
            try {
                $emailResult = $this->emailService->sendApproved($updatedReg);
                if (!empty($emailResult['success'])) {
                    flash('success', "Warranty {$id} APPROVED successfully! Official approval confirmation email sent to customer.");
                } else {
                    flash('success', "Warranty {$id} APPROVED successfully. (Email notification was skipped or recorded in logs).");
                }
            } catch (Throwable $e) {
                error_log("Approval email error for {$id}: " . $e->getMessage());
                flash('success', "Warranty {$id} APPROVED successfully.");
            }
        } else {
            flash('error', "Failed to approve warranty registration {$id}.");
        }

        redirect("admin/warranty-registrations/view/{$id}");
    }

    /**
     * Reject Warranty Registration
     */
    public function reject(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $registration = $this->repo->find($id);

        if (!$registration) {
            flash('error', "Warranty registration '{$id}' was not found.");
            redirect('admin/warranty-registrations');
            return;
        }

        $admin = auth_admin();
        $adminName = $admin['name'] ?? ($admin['email'] ?? 'Administrator');

        $reason = trim((string)$request->input('rejection_reason', ''));
        $notes = trim((string)$request->input('notes', ''));

        if ($reason === '') {
            flash('error', 'A rejection reason is required.');
            redirect("admin/warranty-registrations/view/{$id}");
            return;
        }

        $reviewData = [
            'rejection_reason' => $reason,
            'notes' => $notes,
        ];

        $success = $this->repo->updateStatus($id, 'REJECTED', $reviewData, null, $adminName);

        if ($success) {
            // Record audit log
            $this->auditService->record(
                $id,
                'REJECTED',
                $adminName,
                "Rejected registration. Reason: {$reason}. Notes: {$notes}"
            );

            // Send rejection email to customer
            $updatedReg = $this->repo->find($id);
            try {
                $emailResult = $this->emailService->sendRejected($updatedReg);
                if (!empty($emailResult['success'])) {
                    flash('success', "Warranty {$id} marked as REJECTED. Rejection notification email dispatched to customer.");
                } else {
                    flash('success', "Warranty {$id} marked as REJECTED. (Email notification skipped or recorded in logs).");
                }
            } catch (Throwable $e) {
                error_log("Rejection email error for {$id}: " . $e->getMessage());
                flash('success', "Warranty {$id} marked as REJECTED.");
            }
        } else {
            flash('error', "Failed to reject warranty registration {$id}.");
        }

        redirect("admin/warranty-registrations/view/{$id}");
    }

    /**
     * Stream protected invoice file securely to authenticated admin
     */
    public function invoice(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $registration = $this->repo->find($id);

        if (!$registration) {
            http_response_code(404);
            echo 'Warranty registration record not found.';
            exit;
        }

        $fullPath = $this->repo->getInvoiceFullPath($registration);
        if (!$fullPath || !file_exists($fullPath)) {
            http_response_code(404);
            echo 'Invoice file does not exist on protected server storage.';
            exit;
        }

        $mime = $registration['purchase']['invoice_mime'] ?? 'application/octet-stream';
        $originalName = $registration['purchase']['invoice_original_name'] ?? basename($fullPath);
        $download = (bool)$request->query('download', false);

        // Record audit inspection
        $admin = auth_admin();
        $adminName = $admin['name'] ?? 'Administrator';
        $this->auditService->record(
            $id,
            'INVOICE_ACCESSED',
            $adminName,
            "Admin accessed invoice ({$originalName})."
        );

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($fullPath));
        $disposition = $download ? 'attachment' : 'inline';
        header('Content-Disposition: ' . $disposition . '; filename="' . rawurlencode($originalName) . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        readfile($fullPath);
        exit;
    }

    /**
     * Retry sending failed email
     */
    public function resendEmail(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $logId = (string)$request->input('log_id', '');

        if ($logId === '') {
            flash('error', 'Invalid email log reference.');
            redirect("admin/warranty-registrations/view/{$id}");
            return;
        }

        $result = $this->emailService->resend($logId);
        if (!empty($result['success'])) {
            flash('success', 'Email notification was re-sent successfully!');
        } else {
            flash('error', 'Email retry failed: ' . ($result['message'] ?? 'Unknown error.'));
        }

        redirect("admin/warranty-registrations/view/{$id}");
    }

    /**
     * Warranty Policies Management
     */
    public function policies(Request $request): void
    {
        $policies = $this->policyRepo->all();
        $categories = $this->config['categories'] ?? [];

        echo View::render('admin/warranty_registrations/policies', [
            'title' => 'Warranty Policies & Terms | HOMEIQ Admin',
            'policies' => $policies,
            'categories' => $categories,
        ], 'admin');
    }

    /**
     * Update Warranty Policies
     */
    public function updatePolicies(Request $request): void
    {
        $rawPolicies = $request->input('policies', []);
        $updated = [];

        if (is_array($rawPolicies)) {
            foreach ($rawPolicies as $key => $pData) {
                if (empty($pData['product_category'])) {
                    continue;
                }
                $updated[] = [
                    'id' => $pData['id'] ?? ('pol_' . bin2hex(random_bytes(4))),
                    'product_category' => trim((string)$pData['product_category']),
                    'product_model' => !empty($pData['product_model']) ? trim((string)$pData['product_model']) : null,
                    'duration_months' => max(1, (int)($pData['duration_months'] ?? 12)),
                    'description' => trim((string)($pData['description'] ?? '')),
                    'active' => !empty($pData['active']),
                ];
            }
        }

        if (!empty($updated)) {
            $this->policyRepo->savePolicies($updated);
            $admin = auth_admin();
            $adminName = $admin['name'] ?? 'Administrator';
            $this->auditService->record(
                'SYSTEM',
                'POLICIES_UPDATED',
                $adminName,
                'Updated product warranty policies and coverage durations.'
            );
            flash('success', 'Warranty policies updated successfully.');
        } else {
            flash('error', 'No valid policies were submitted to update.');
        }

        redirect('admin/warranty-registrations/policies');
    }
}
