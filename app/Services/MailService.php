<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\App;

class MailService
{
    private string $host;
    private int $port;
    private string $encryption;
    private string $username;
    private string $password;
    private string $fromAddress;
    private string $fromName;
    private string $driver;

    public function __construct()
    {
        $this->driver = (string)env('MAIL_DRIVER', 'smtp');
        $this->host = (string)env('MAIL_HOST', 'smtp.gmail.com');
        $this->port = (int)env('MAIL_PORT', 587);
        $this->encryption = strtolower((string)env('MAIL_ENCRYPTION', 'tls'));
        $this->username = (string)env('MAIL_USERNAME', '');
        $this->password = (string)env('MAIL_PASSWORD', '');
        $this->fromAddress = (string)env('MAIL_FROM_ADDRESS', 'support@homeiq-security.com');
        $this->fromName = (string)env('MAIL_FROM_NAME', 'HOMEIQ Smart Locks');
    }

    /**
     * Send an email
     * @return array [success => bool, driver => string, message => string, outbox_file => string|null]
     */
    public function send(string $toEmail, string $subject, string $htmlBody, ?string $toName = null): array
    {
        $toEmail = trim($toEmail);
        $toName = $toName ? trim($toName) : $toEmail;

        // 1. Always record in local outbox (guarantees local access even without active SMTP)
        $base = App::getBasePath() ?: dirname(__DIR__, 2);
        $outboxDir = $base . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'emails';
        if (!is_dir($outboxDir)) {
            @mkdir($outboxDir, 0755, true);
        }

        $cleanEmail = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $toEmail);
        $filename = 'email_' . time() . '_' . $cleanEmail . '.html';
        $fullPath = $outboxDir . DIRECTORY_SEPARATOR . $filename;
        @file_put_contents($fullPath, $htmlBody);

        @file_put_contents($outboxDir . DIRECTORY_SEPARATOR . 'latest_email.json', json_encode([
            'to_email' => $toEmail,
            'to_name' => $toName,
            'subject' => $subject,
            'sent_at' => date('Y-m-d H:i:s'),
            'file' => $filename,
        ], JSON_PRETTY_PRINT));

        // 2. If SMTP credentials configured, attempt real SMTP delivery
        if ($this->driver === 'smtp' && !empty($this->host) && !empty($this->username) && !empty($this->password)) {
            $smtpResult = $this->sendViaSmtp($toEmail, $toName, $subject, $htmlBody);
            if ($smtpResult['success']) {
                return [
                    'success' => true,
                    'driver' => 'smtp',
                    'message' => 'Email sent directly to your inbox via SMTP.',
                    'outbox_file' => $filename
                ];
            }
        }

        // 3. Fallback to native PHP mail()
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . sprintf('"%s" <%s>', addcslashes($this->fromName, '"'), $this->fromAddress),
            'Reply-To: ' . $this->fromAddress,
            'X-Mailer: HOMEIQ Mailer',
        ];

        $mailSent = @mail($toEmail, $subject, $htmlBody, implode("\r\n", $headers));

        return [
            'success' => $mailSent,
            'driver' => $mailSent ? 'mail' : 'outbox',
            'message' => $mailSent ? 'Dispatched via PHP mail().' : 'Generated and saved to local outbox (configure SMTP in .env for inbox delivery).',
            'outbox_file' => $filename
        ];
    }

    /**
     * Send Password Reset Email Template
     */
    public function sendPasswordResetEmail(string $toEmail, string $customerName, string $resetUrl): array
    {
        $subject = 'Reset Your HOMEIQ Password';

        $html = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Your HOMEIQ Password</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
    <!-- Header -->
    <tr>
      <td style="background: linear-gradient(135deg, #0d1527 0%, #17233d 100%); padding: 32px 36px; text-align: center; border-bottom: 3px solid #FE6B25;">
        <div style="font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 2px;">
          HOMEIQ
        </div>
        <div style="font-size: 11px; color: #FF8528; letter-spacing: 3px; font-weight: 700; text-transform: uppercase; margin-top: 4px;">
          DIGITAL SMART LOCKS
        </div>
      </td>
    </tr>
    <!-- Content -->
    <tr>
      <td style="padding: 36px 36px 28px 36px;">
        <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #0f172a;">Password Reset Request</h2>
        <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #475569;">
          Hello <strong>' . htmlspecialchars($customerName, ENT_QUOTES) . '</strong>,
        </p>
        <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
          We received a request to reset the password for your HOMEIQ account. Click the button below to choose a new secure password:
        </p>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0;">
          <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES) . '" style="background: linear-gradient(135deg, #FE6B25 0%, #FE4A1F 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; font-size: 15px; font-weight: 700; border-radius: 8px; display: inline-block; box-shadow: 0 4px 14px rgba(254, 107, 37, 0.35);">
            Reset My Password &rarr;
          </a>
        </div>

        <p style="margin: 0 0 16px 0; font-size: 13px; line-height: 1.5; color: #64748b;">
          If the button above does not work, copy and paste this link into your browser:<br>
          <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES) . '" style="color: #FE6B25; word-break: break-all; font-size: 12px;">' . htmlspecialchars($resetUrl, ENT_QUOTES) . '</a>
        </p>
        
        <div style="margin: 28px 0 0 0; padding: 14px 16px; background: #fff8f5; border-left: 3px solid #FE6B25; border-radius: 4px;">
          <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
            <strong>Security Notice:</strong> This link is valid for <strong>1 hour</strong>. If you did not make this request, you can safely ignore this email — your existing password remains unchanged.
          </p>
        </div>
      </td>
    </tr>
    <!-- Footer -->
    <tr>
      <td style="background: #f8fafc; padding: 20px 36px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
        &copy; ' . date('Y') . ' HOMEIQ Technologies Pvt Ltd. All rights reserved.<br>
        Direct Doorstep Smart Lock Security with Certified Technicians.
      </td>
    </tr>
  </table>
</body>
</html>';

        return $this->send($toEmail, $subject, $html, $customerName);
    }

    /**
     * Native Socket SMTP Client
     */
    private function sendViaSmtp(string $toEmail, string $toName, string $subject, string $htmlBody): array
    {
        $timeout = 10;
        $prefix = ($this->encryption === 'ssl') ? 'ssl://' : '';
        $socket = @stream_socket_client($prefix . $this->host . ':' . $this->port, $errno, $errstr, $timeout);

        if (!$socket) {
            return ['success' => false, 'error' => "Cannot connect to SMTP server: {$errstr} ({$errno})"];
        }

        stream_set_timeout($socket, $timeout);

        $read = function() use ($socket) {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }
            return $response;
        };

        $write = function(string $cmd) use ($socket) {
            fputs($socket, $cmd . "\r\n");
        };

        $initial = $read();
        if (!str_starts_with($initial, '220')) {
            fclose($socket);
            return ['success' => false, 'error' => "Invalid greeting: {$initial}"];
        }

        $write('EHLO ' . ($this->host ?: 'localhost'));
        $read();

        // STARTTLS
        if ($this->encryption === 'tls') {
            $write('STARTTLS');
            $starttls = $read();
            if (!str_starts_with($starttls, '220')) {
                fclose($socket);
                return ['success' => false, 'error' => "STARTTLS failed: {$starttls}"];
            }
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket);
                return ['success' => false, 'error' => 'Crypto negotiation failed.'];
            }
            $write('EHLO ' . ($this->host ?: 'localhost'));
            $read();
        }

        // AUTH LOGIN
        $write('AUTH LOGIN');
        $authResponse = $read();
        if (!str_starts_with($authResponse, '334')) {
            fclose($socket);
            return ['success' => false, 'error' => "AUTH LOGIN rejected: {$authResponse}"];
        }

        $write(base64_encode($this->username));
        $userResponse = $read();
        if (!str_starts_with($userResponse, '334')) {
            fclose($socket);
            return ['success' => false, 'error' => "Username rejected: {$userResponse}"];
        }

        $write(base64_encode($this->password));
        $passResponse = $read();
        if (!str_starts_with($passResponse, '235')) {
            fclose($socket);
            return ['success' => false, 'error' => "Password rejected: {$passResponse}"];
        }

        // MAIL FROM
        $write('MAIL FROM: <' . $this->fromAddress . '>');
        $mailFrom = $read();
        if (!str_starts_with($mailFrom, '250')) {
            fclose($socket);
            return ['success' => false, 'error' => "MAIL FROM failed: {$mailFrom}"];
        }

        // RCPT TO
        $write('RCPT TO: <' . $toEmail . '>');
        $rcptTo = $read();
        if (!str_starts_with($rcptTo, '250')) {
            fclose($socket);
            return ['success' => false, 'error' => "RCPT TO failed: {$rcptTo}"];
        }

        // DATA
        $write('DATA');
        $dataPrompt = $read();
        if (!str_starts_with($dataPrompt, '354')) {
            fclose($socket);
            return ['success' => false, 'error' => "DATA rejected: {$dataPrompt}"];
        }

        $messageId = sprintf('<%s.%s@%s>', time(), bin2hex(random_bytes(8)), parse_url(App::getBaseUrl(), PHP_URL_HOST) ?: 'localhost');
        $headers = [
            'Date: ' . date('r'),
            'To: ' . sprintf('"%s" <%s>', addcslashes($toName, '"'), $toEmail),
            'From: ' . sprintf('"%s" <%s>', addcslashes($this->fromName, '"'), $this->fromAddress),
            'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
            'Message-ID: $messageId',
            'X-Mailer: HOMEIQ Native Mailer',
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
        ];

        $payload = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($htmlBody)) . "\r\n.";
        $write($payload);
        $dataResult = $read();

        $write('QUIT');
        fclose($socket);

        if (!str_starts_with($dataResult, '250')) {
            return ['success' => false, 'error' => "Message rejected: {$dataResult}"];
        }

        return ['success' => true];
    }
}
