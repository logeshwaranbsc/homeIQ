<?php
declare(strict_types=1);

namespace App\Services;

class WarrantyEmailService
{
    private string $filePath;
    private MailService $mailService;

    public function __construct(?string $filePath = null)
    {
        if ($filePath === null) {
            $this->filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'warranty' . DIRECTORY_SEPARATOR . 'email-logs' . DIRECTORY_SEPARATOR . 'email-history.json';
        } else {
            $this->filePath = $filePath;
        }

        $this->mailService = new MailService();
        $this->ensureFile();
    }

    private function ensureFile(): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        if (!file_exists($this->filePath)) {
            @file_put_contents($this->filePath, '[]');
        }
    }

    /**
     * Email 1: Warranty Registration Received
     */
    public function sendReceived(array $registration): array
    {
        $email = trim((string)($registration['customer']['email'] ?? ''));
        if (empty($email)) {
            return ['success' => false, 'skipped' => true, 'message' => 'No customer email provided.'];
        }

        $regId = $registration['registration_id'] ?? '';
        $customerName = $registration['customer']['full_name'] ?? 'Valued Customer';
        $model = $registration['product']['model'] ?? 'HOMEIQ Product';
        $category = $registration['product']['category'] ?? 'Smart Hardware';
        $serial = !empty($registration['product']['serial_number']) ? $registration['product']['serial_number'] : 'Recorded without serial';

        $subject = 'HOMEIQ – Warranty Registration Received';

        $body = $this->renderHtmlTemplate([
            'heading' => 'Warranty Registration Received',
            'subheading' => 'Your warranty submission is now under review.',
            'greeting' => "Hello {$customerName},",
            'badge' => ['text' => 'PENDING VERIFICATION', 'color' => '#EAB308'],
            'intro' => 'Thank you for registering your HOMEIQ product. We have successfully received your purchase and warranty information. Our technical verification team will review your invoice and product details.',
            'details' => [
                'Registration ID' => $regId,
                'Status' => 'PENDING',
                'Product Model' => $model,
                'Category' => $category,
                'Serial Number' => $serial,
                'Submission Date' => date('d M Y, h:i A', strtotime($registration['created_at'] ?? 'now')),
            ],
            'note' => 'Please retain your Registration ID for any future technical support, service inquiries, or warranty claim requests. We will notify you via email once verification is complete.',
        ]);

        return $this->dispatch($regId, $email, $customerName, 'REGISTRATION_RECEIVED', $subject, $body);
    }

    /**
     * Email 2: Warranty Registration Approved
     */
    public function sendApproved(array $registration): array
    {
        $email = trim((string)($registration['customer']['email'] ?? ''));
        if (empty($email)) {
            return ['success' => false, 'skipped' => true, 'message' => 'No customer email provided.'];
        }

        $regId = $registration['registration_id'] ?? '';
        $customerName = $registration['customer']['full_name'] ?? 'Valued Customer';
        $model = $registration['product']['model'] ?? 'HOMEIQ Product';
        $serial = !empty($registration['product']['serial_number']) ? $registration['product']['serial_number'] : 'N/A';
        $purchaseDate = !empty($registration['purchase']['purchase_date']) ? date('d M Y', strtotime($registration['purchase']['purchase_date'])) : 'N/A';
        $startDate = !empty($registration['warranty']['start_date']) ? date('d M Y', strtotime($registration['warranty']['start_date'])) : $purchaseDate;
        $endDate = !empty($registration['warranty']['end_date']) ? date('d M Y', strtotime($registration['warranty']['end_date'])) : 'N/A';
        $durationMonths = $registration['warranty']['duration_months'] ?? null;
        $durationText = $durationMonths ? " ({$durationMonths} Months)" : '';

        $subject = 'HOMEIQ – Your Warranty Registration Has Been Approved!';

        $body = $this->renderHtmlTemplate([
            'heading' => 'Warranty Registration Approved!',
            'subheading' => 'Your official manufacturer warranty is now fully active.',
            'greeting' => "Congratulations {$customerName},",
            'badge' => ['text' => 'APPROVED & ACTIVE', 'color' => '#10B981'],
            'intro' => 'Your purchase invoice and product details have been verified by our team. Your HOMEIQ hardware is now officially protected under our manufacturer warranty program.',
            'details' => [
                'Registration ID' => $regId,
                'Status' => 'APPROVED',
                'Product Model' => $model,
                'Serial Number' => $serial,
                'Purchase Date' => $purchaseDate,
                'Warranty Start Date' => $startDate,
                'Warranty End Date' => $endDate . $durationText,
            ],
            'note' => 'For doorstep service assistance, firmware guidance, or priority technical queries, reach out anytime with your Registration ID.',
        ]);

        return $this->dispatch($regId, $email, $customerName, 'REGISTRATION_APPROVED', $subject, $body);
    }

    /**
     * Email 3: Warranty Registration Rejected
     */
    public function sendRejected(array $registration): array
    {
        $email = trim((string)($registration['customer']['email'] ?? ''));
        if (empty($email)) {
            return ['success' => false, 'skipped' => true, 'message' => 'No customer email provided.'];
        }

        $regId = $registration['registration_id'] ?? '';
        $customerName = $registration['customer']['full_name'] ?? 'Valued Customer';
        $model = $registration['product']['model'] ?? 'HOMEIQ Product';
        $reason = $registration['admin_review']['rejection_reason'] ?? 'Purchase Details Could Not Be Verified';
        $notes = $registration['admin_review']['notes'] ?? 'Please submit a clear copy of your genuine purchase invoice.';

        $subject = 'HOMEIQ – Update Regarding Your Warranty Registration';

        $body = $this->renderHtmlTemplate([
            'heading' => 'Warranty Registration Status Update',
            'subheading' => 'We were unable to approve your warranty registration at this time.',
            'greeting' => "Dear {$customerName},",
            'badge' => ['text' => 'ACTION REQUIRED / REJECTED', 'color' => '#EF4444'],
            'intro' => 'We reviewed your warranty registration for your HOMEIQ product. Unfortunately, your submission could not be verified due to the reason specified below.',
            'details' => [
                'Registration ID' => $regId,
                'Status' => 'REJECTED',
                'Product Model' => $model,
                'Reason for Rejection' => $reason,
                'Admin Reviewer Notes' => $notes,
            ],
            'note' => 'You may resubmit your warranty registration with the correct details and a clear tax invoice at our Warranty Registration portal, or contact our support team at support@homeiq-security.com.',
        ]);

        return $this->dispatch($regId, $email, $customerName, 'REGISTRATION_REJECTED', $subject, $body);
    }

    /**
     * Retry sending an email from log
     */
    public function resend(string $logId): array
    {
        $all = $this->all();
        $targetLog = null;
        $targetIndex = null;

        foreach ($all as $idx => $entry) {
            if (($entry['id'] ?? '') === $logId) {
                $targetLog = $entry;
                $targetIndex = $idx;
                break;
            }
        }

        if (!$targetLog) {
            return ['success' => false, 'message' => 'Email log record not found.'];
        }

        $sendResult = $this->mailService->send(
            $targetLog['to_email'],
            $targetLog['subject'],
            $targetLog['body_html'] ?? '',
            $targetLog['to_name']
        );

        $now = date('c');
        $targetLog['sent_at'] = $now;
        $targetLog['status'] = $sendResult['success'] ? 'SENT' : 'FAILED';
        $targetLog['driver'] = $sendResult['driver'] ?? 'local';
        $targetLog['error'] = $sendResult['success'] ? null : ($sendResult['message'] ?? 'Send failed');
        $targetLog['retry_count'] = ($targetLog['retry_count'] ?? 0) + 1;

        $all[$targetIndex] = $targetLog;
        @file_put_contents($this->filePath, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $sendResult;
    }

    private function dispatch(
        string $registrationId,
        string $toEmail,
        string $toName,
        string $type,
        string $subject,
        string $htmlBody
    ): array {
        // Prevent duplicate automatic emails of the exact same type within 60 seconds
        $recent = $this->getForRegistration($registrationId);
        foreach ($recent as $item) {
            if (($item['email_type'] ?? '') === $type && ($item['status'] ?? '') === 'SENT') {
                $sentTime = strtotime($item['sent_at'] ?? 'now');
                if ((time() - $sentTime) < 60) {
                    return [
                        'success' => true,
                        'message' => 'Email was already sent recently (duplicate avoided).',
                        'duplicate' => true
                    ];
                }
            }
        }

        $sendResult = $this->mailService->send($toEmail, $subject, $htmlBody, $toName);
        $isSent = !empty($sendResult['success']) || !empty($sendResult['outbox_file']);
        $status = $isSent ? 'SENT' : 'FAILED';
        $error = $isSent ? null : ($sendResult['message'] ?? 'Failed to send');

        $logEntry = [
            'id' => 'wmail_' . bin2hex(random_bytes(6)),
            'registration_id' => $registrationId,
            'to_email' => $toEmail,
            'to_name' => $toName,
            'email_type' => $type,
            'subject' => $subject,
            'status' => $status,
            'driver' => $sendResult['driver'] ?? 'outbox',
            'error' => $error,
            'sent_at' => date('c'),
            'body_html' => $htmlBody,
        ];

        $this->appendLog($logEntry);

        return [
            'success' => $isSent,
            'message' => $sendResult['message'] ?? ($isSent ? 'Email dispatched successfully.' : 'Email delivery failed.'),
            'log_id' => $logEntry['id'],
        ];
    }

    private function appendLog(array $entry): void
    {
        $fp = fopen($this->filePath, 'c+');
        if ($fp && flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $list = $content ? json_decode($content, true) : [];
            if (!is_array($list)) {
                $list = [];
            }

            array_unshift($list, $entry);

            if (count($list) > 2000) {
                $list = array_slice($list, 0, 2000);
            }

            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public function getForRegistration(string $registrationId): array
    {
        $all = $this->all();
        return array_values(array_filter($all, fn($item) => ($item['registration_id'] ?? '') === $registrationId));
    }

    public function getByRegistration(string $registrationId): array
    {
        return $this->getForRegistration($registrationId);
    }

    public function getHistoryByRegistration(string $registrationId): array
    {
        return $this->getForRegistration($registrationId);
    }

    public function all(int $limit = 500): array
    {
        $content = @file_get_contents($this->filePath);
        if (!$content) {
            return [];
        }
        $list = json_decode($content, true);
        return is_array($list) ? array_slice($list, 0, $limit) : [];
    }

    private function renderHtmlTemplate(array $params): string
    {
        $heading = htmlspecialchars($params['heading'] ?? '');
        $subheading = htmlspecialchars($params['subheading'] ?? '');
        $greeting = htmlspecialchars($params['greeting'] ?? '');
        $intro = htmlspecialchars($params['intro'] ?? '');
        $note = htmlspecialchars($params['note'] ?? '');
        $badgeText = htmlspecialchars($params['badge']['text'] ?? '');
        $badgeColor = htmlspecialchars($params['badge']['color'] ?? '#FE6B25');

        $rowsHtml = '';
        foreach (($params['details'] ?? []) as $label => $val) {
            $escLabel = htmlspecialchars((string)$label);
            $escVal = htmlspecialchars((string)$val);
            $rowsHtml .= "
              <tr>
                <td style=\"padding: 10px 14px; font-weight: 600; color: #475569; width: 40%; border-bottom: 1px solid #F1F5F9; font-size: 13px;\">{$escLabel}</td>
                <td style=\"padding: 10px 14px; font-weight: 700; color: #0F172A; width: 60%; border-bottom: 1px solid #F1F5F9; font-size: 13px;\">{$escVal}</td>
              </tr>";
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{$heading}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #334155; line-height: 1.5;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
    <!-- Header -->
    <tr>
      <td style="background: #0F172A; padding: 26px 32px; text-align: left; border-bottom: 3px solid #FE6B25;">
        <span style="font-size: 22px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">HOME<span style="color: #FE6B25;">IQ</span></span>
        <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; font-weight: 700; letter-spacing: 1px; margin-top: 4px;">Official Product Warranty Protection</div>
      </td>
    </tr>

    <!-- Body Content -->
    <tr>
      <td style="padding: 32px;">
        <div style="display: inline-block; background-color: {$badgeColor}15; color: {$badgeColor}; border: 1px solid {$badgeColor}40; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 16px;">
          {$badgeText}
        </div>
        <h2 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">{$heading}</h2>
        <p style="font-size: 14px; color: #64748B; margin: 0 0 20px 0;">{$subheading}</p>

        <p style="font-size: 14px; color: #334155; margin: 0 0 14px 0;">{$greeting}</p>
        <p style="font-size: 14px; color: #334155; margin: 0 0 20px 0; line-height: 1.6;">{$intro}</p>

        <!-- Details Card -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0; margin-bottom: 22px;">
          {$rowsHtml}
        </table>

        <!-- Note Box -->
        <div style="background: #FFF7ED; border-left: 4px solid #FE6B25; padding: 14px 16px; border-radius: 6px; margin-bottom: 24px; font-size: 12.5px; color: #9A3412; line-height: 1.5;">
          <strong>Important Note:</strong> {$note}
        </div>

        <p style="font-size: 13px; color: #64748B; margin: 0;">Warm regards,<br><strong style="color: #0F172A;">HOMEIQ Security & Customer Care Team</strong></p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background: #F1F5F9; padding: 18px 32px; text-align: center; font-size: 11px; color: #64748B; border-top: 1px solid #E2E8F0;">
        &copy; 2026 HOMEIQ Technologies. All rights reserved. Registered under HOMEIQ Official Warranty Policy.
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }
}
