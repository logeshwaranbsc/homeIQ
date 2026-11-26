<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\CallbackLeadRepository;
use App\Repositories\CallbackSettingsRepository;
use App\Services\CallbackAuditService;

class CallbackController
{
    private CallbackLeadRepository $repo;
    private CallbackSettingsRepository $settingsRepo;
    private CallbackAuditService $auditService;

    public function __construct()
    {
        $this->repo = new CallbackLeadRepository();
        $this->settingsRepo = new CallbackSettingsRepository();
        $this->auditService = new CallbackAuditService();
    }

    /**
     * Provide frontend with dynamic callback popup configuration
     */
    public function config(Request $request): void
    {
        $settings = $this->settingsRepo->get();
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'enabled' => (bool)($settings['enabled'] ?? true),
            'trigger_delay_seconds' => (int)($settings['trigger_delay_seconds'] ?? 60),
            'heading' => $settings['heading'] ?? 'Need Help Before You Buy?',
            'description' => $settings['description'] ?? 'Let our product expert help you choose the right product.',
            'button_text' => $settings['button_text'] ?? 'REQUEST A CALLBACK',
            'product_options' => $settings['product_options'] ?? [
                'Smart Door Lock',
                'Video Door Phone (VDP)',
                'Retrofit Smart Module',
                'Other Products',
            ],
            'csrf_token' => csrf_token(),
        ]);
        exit;
    }

    /**
     * Process customer callback request
     */
    public function requestCallback(Request $request): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        // Check if callback feature is active
        $settings = $this->settingsRepo->get();
        if (empty($settings['enabled'])) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Callback enquiries are currently unavailable. Please contact our support team directly.'
            ]);
            exit;
        }

        // Honeypot spam trap
        $honeypot = trim((string)$request->input('website_url', ''));
        if ($honeypot !== '') {
            // Silently discard spam bots with 200 OK
            echo json_encode([
                'success' => true,
                'lead_id' => 'CB-HIQ-SPAM',
                'heading' => 'Thank You for Choosing HOMEIQ!',
                'message' => 'Our product specialist will contact you shortly to assist with your enquiry.'
            ]);
            exit;
        }

        // Rate limiting check: max 5 requests per IP per 10 minutes
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $rateLimitKey = 'cb_rate_' . md5($ip);
        $attempts = (array)($_SESSION[$rateLimitKey] ?? []);
        $nowTs = time();
        $recentAttempts = array_filter($attempts, fn($ts) => ($nowTs - $ts) < 600);
        if (count($recentAttempts) >= 5) {
            http_response_code(429);
            echo json_encode([
                'success' => false,
                'message' => 'You have submitted multiple requests recently. Our specialist will contact you soon.'
            ]);
            exit;
        }

        // Input extraction & sanitisation
        $productInterest = trim((string)$request->input('product_interest', ''));
        $name = trim((string)$request->input('customer_name', ''));
        $mobile = trim((string)$request->input('mobile_number', ''));
        $pageUrl = trim((string)$request->input('product_page_url', ''));

        if ($pageUrl === '') {
            $pageUrl = (string)($_SERVER['HTTP_REFERER'] ?? (\App\Core\App::getBaseUrl()));
        }

        $errors = [];

        // Validate Product Interest
        if ($productInterest === '') {
            $errors['product_interest'] = 'Please select the product you are interested in.';
        }

        // Validate Name
        if ($name === '') {
            $errors['customer_name'] = 'Please enter your name.';
        } elseif (mb_strlen($name) < 2) {
            $errors['customer_name'] = 'Name must be at least 2 characters.';
        } elseif (mb_strlen($name) > 80) {
            $errors['customer_name'] = 'Name cannot exceed 80 characters.';
        }

        // Validate Mobile Number: Exactly 10 digits, Indian mobile format [6-9]xxxxxxxxx
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobile);
        if ($cleanPhone === '') {
            $errors['mobile_number'] = 'Please enter your 10-digit mobile number.';
        } elseif (strlen($cleanPhone) !== 10) {
            $errors['mobile_number'] = 'Mobile number must be exactly 10 digits.';
        } elseif (!preg_match('/^[6-9]\d{9}$/', $cleanPhone)) {
            $errors['mobile_number'] = 'Please enter a valid 10-digit Indian mobile number.';
        }

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'errors' => $errors,
                'message' => reset($errors)
            ]);
            exit;
        }

        // Record rate limit hit
        $recentAttempts[] = $nowTs;
        $_SESSION[$rateLimitKey] = $recentAttempts;

        try {
            $leadData = [
                'product_interest' => $productInterest,
                'customer_name' => $name,
                'mobile_number' => $cleanPhone,
                'product_page_url' => $pageUrl,
                'status' => 'NEW',
                'admin_notes' => '',
                'follow_up_date' => null,
                'assigned_to' => null,
            ];

            $savedLead = $this->repo->create($leadData);
            $leadId = $savedLead['lead_id'];

            // Record audit log
            $this->auditService->record(
                $leadId,
                'SUBMITTED',
                "Customer: {$name}",
                "Product enquiry submitted for '{$productInterest}' from page {$pageUrl}.",
                $ip
            );

            echo json_encode([
                'success' => true,
                'lead_id' => $leadId,
                'heading' => 'Thank You for Choosing HOMEIQ!',
                'message' => 'Our product specialist will contact you shortly to assist with your enquiry.'
            ]);
            exit;
        } catch (\Throwable $e) {
            error_log('Callback submission error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while saving your request. Please try again or call our helpline.'
            ]);
            exit;
        }
    }
}
