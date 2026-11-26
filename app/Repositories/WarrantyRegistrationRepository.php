<?php
declare(strict_types=1);

namespace App\Repositories;

use RuntimeException;
use InvalidArgumentException;

class WarrantyRegistrationRepository
{
    private array $config;
    private string $registrationsDir;
    private string $invoicesDir;
    private string $lockFile;

    public function __construct(?array $config = null)
    {
        if ($config === null) {
            $configFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'warranty-config.php';
            $this->config = file_exists($configFile) ? require $configFile : [];
        } else {
            $this->config = $config;
        }

        $this->registrationsDir = $this->config['storage']['registrations'] ?? (dirname(__DIR__, 2) . '/storage/warranty/registrations');
        $this->invoicesDir = $this->config['storage']['invoices'] ?? (dirname(__DIR__, 2) . '/storage/warranty/invoices');
        $this->lockFile = $this->config['storage']['sequence_lock_file'] ?? (dirname(__DIR__, 2) . '/storage/warranty/settings/sequence.lock');

        $this->ensureDirectories();
    }

    private function ensureDirectories(): void
    {
        if (!is_dir($this->registrationsDir)) {
            @mkdir($this->registrationsDir, 0750, true);
        }
        if (!is_dir($this->invoicesDir)) {
            @mkdir($this->invoicesDir, 0750, true);
        }
        $settingsDir = dirname($this->lockFile);
        if (!is_dir($settingsDir)) {
            @mkdir($settingsDir, 0750, true);
        }
    }

    /**
     * Safely generate sequential Registration ID using server-side locking (HIQ-WR-000001)
     */
    public function generateNextId(): string
    {
        $this->ensureDirectories();
        $lockFp = fopen($this->lockFile, 'c+');
        if (!$lockFp) {
            throw new RuntimeException('Unable to open sequence lock file for warranty registration.');
        }

        if (!flock($lockFp, LOCK_EX)) {
            fclose($lockFp);
            throw new RuntimeException('Unable to acquire exclusive lock for registration sequence.');
        }

        try {
            $prefix = $this->config['id_prefix'] ?? 'HIQ-WR-';
            $padding = (int)($this->config['id_padding'] ?? 6);

            // Read persistent counter from sequence lock file
            rewind($lockFp);
            $content = stream_get_contents($lockFp);
            $seqVal = 0;
            if ($content !== false && trim($content) !== '') {
                $seqVal = (int)trim($content);
            }

            // Also check highest number among existing registration files
            $maxFileNum = 0;
            $files = glob($this->registrationsDir . DIRECTORY_SEPARATOR . $prefix . '*.json');
            if ($files) {
                foreach ($files as $file) {
                    $basename = basename($file, '.json');
                    if (str_starts_with($basename, $prefix)) {
                        $numPart = substr($basename, strlen($prefix));
                        if (is_numeric($numPart)) {
                            $val = (int)$numPart;
                            if ($val > $maxFileNum) {
                                $maxFileNum = $val;
                            }
                        }
                    }
                }
            }

            $currentMax = max($seqVal, $maxFileNum);
            $nextNum = $currentMax + 1;

            // Persist updated sequence number atomically in locked file
            ftruncate($lockFp, 0);
            rewind($lockFp);
            fwrite($lockFp, (string)$nextNum);
            fflush($lockFp);

            return sprintf('%s%0' . $padding . 'd', $prefix, $nextNum);
        } finally {
            flock($lockFp, LOCK_UN);
            fclose($lockFp);
        }
    }

    /**
     * Atomically write registration data to JSON file
     */
    public function create(array $data): array
    {
        $regId = $data['registration_id'] ?? null;
        if (empty($regId)) {
            $regId = $this->generateNextId();
            $data['registration_id'] = $regId;
        }

        $now = date('c');
        $data['created_at'] = $data['created_at'] ?? $now;
        $data['updated_at'] = $now;
        $data['status'] = strtoupper((string)($data['status'] ?? 'PENDING'));

        if (!isset($data['status_history']) || !is_array($data['status_history'])) {
            $data['status_history'] = [
                [
                    'status' => $data['status'],
                    'changed_at' => $now,
                    'changed_by' => 'Customer Submission',
                    'notes' => 'Registration submitted via storefront form.'
                ]
            ];
        }

        $filePath = $this->getFilePath($regId);
        $this->writeAtomicJson($filePath, $data);

        return $data;
    }

    /**
     * Atomically write JSON using temporary file and rename
     */
    private function writeAtomicJson(string $destination, array $data): void
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }

        $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($jsonString === false) {
            throw new RuntimeException('Failed to encode registration data to JSON: ' . json_last_error_msg());
        }

        $tempFile = $dir . DIRECTORY_SEPARATOR . 'tmp_' . uniqid('', true) . '.json';
        $fp = fopen($tempFile, 'wb');
        if (!$fp) {
            throw new RuntimeException('Failed to create temporary file for atomic write.');
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $jsonString);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($tempFile, 0640);

        if (!rename($tempFile, $destination)) {
            @unlink($tempFile);
            throw new RuntimeException("Failed to atomically move temporary file to {$destination}.");
        }
    }

    public function find(string $id): ?array
    {
        $id = trim($id);
        $filePath = $this->getFilePath($id);
        if (!file_exists($filePath)) {
            return null;
        }

        $fp = fopen($filePath, 'rb');
        if (!$fp) {
            return null;
        }

        flock($fp, LOCK_SH);
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($content === false || trim($content) === '') {
            return null;
        }

        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : null;
    }

    public function all(): array
    {
        $this->ensureDirectories();
        $files = glob($this->registrationsDir . DIRECTORY_SEPARATOR . '*.json');
        if (!$files) {
            return [];
        }

        $results = [];
        foreach ($files as $file) {
            $content = @file_get_contents($file);
            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $results[] = $decoded;
                }
            }
        }

        // Sort latest first
        usort($results, function ($a, $b) {
            $tA = strtotime($a['created_at'] ?? 'now');
            $tB = strtotime($b['created_at'] ?? 'now');
            return $tB <=> $tA;
        });

        return $results;
    }

    /**
     * Filter registrations by search query, status, category, date range
     */
    public function filter(array $filters): array
    {
        $all = $this->all();

        $q = trim(strtolower((string)($filters['q'] ?? '')));
        $status = strtoupper(trim((string)($filters['status'] ?? 'ALL')));
        $category = trim((string)($filters['category'] ?? 'ALL'));
        $startDate = !empty($filters['start_date']) ? strtotime($filters['start_date'] . ' 00:00:00') : null;
        $endDate = !empty($filters['end_date']) ? strtotime($filters['end_date'] . ' 23:59:59') : null;

        return array_values(array_filter($all, function ($item) use ($q, $status, $category, $startDate, $endDate) {
            // Status check
            if ($status !== 'ALL' && ($item['status'] ?? '') !== $status) {
                return false;
            }

            // Category check
            if ($category !== 'ALL' && ($item['product']['category'] ?? '') !== $category) {
                return false;
            }

            // Date range check
            if ($startDate !== null || $endDate !== null) {
                $created = strtotime($item['created_at'] ?? 'now');
                if ($startDate !== null && $created < $startDate) {
                    return false;
                }
                if ($endDate !== null && $created > $endDate) {
                    return false;
                }
            }

            // Search query (ID, customer name, mobile, serial number)
            if ($q !== '') {
                $regId = strtolower((string)($item['registration_id'] ?? ''));
                $name = strtolower((string)($item['customer']['full_name'] ?? ''));
                $mobile = strtolower((string)($item['customer']['mobile_number'] ?? ''));
                $serial = strtolower((string)($item['product']['serial_number'] ?? ''));
                $email = strtolower((string)($item['customer']['email'] ?? ''));
                $orderId = strtolower((string)($item['purchase']['order_id'] ?? ''));

                if (
                    !str_contains($regId, $q) &&
                    !str_contains($name, $q) &&
                    !str_contains($mobile, $q) &&
                    !str_contains($serial, $q) &&
                    !str_contains($email, $q) &&
                    !str_contains($orderId, $q)
                ) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Update status to APPROVED or REJECTED with audit tracking and notes
     */
    public function updateStatus(
        string $id,
        string $status,
        array $adminReview,
        ?array $warrantyData = null,
        ?string $actorName = 'Admin'
    ): bool {
        $registration = $this->find($id);
        if (!$registration) {
            return false;
        }

        $now = date('c');
        $status = strtoupper($status);
        $registration['status'] = $status;
        $registration['updated_at'] = $now;

        $registration['admin_review'] = array_merge(
            $registration['admin_review'] ?? [],
            $adminReview,
            [
                'reviewed_by' => $actorName,
                'reviewed_at' => $now,
            ]
        );

        if ($warrantyData !== null) {
            $registration['warranty'] = array_merge($registration['warranty'] ?? [], $warrantyData);
        }

        // Append to status history
        $registration['status_history'] = $registration['status_history'] ?? [];
        $registration['status_history'][] = [
            'status' => $status,
            'changed_at' => $now,
            'changed_by' => $actorName,
            'notes' => $adminReview['notes'] ?? ($status === 'REJECTED' ? ($adminReview['rejection_reason'] ?? 'Rejected') : 'Approved'),
        ];

        $filePath = $this->getFilePath($id);
        $this->writeAtomicJson($filePath, $registration);
        return true;
    }

    /**
     * Get aggregate statistics (Total, Pending, Approved, Rejected)
     */
    public function getCounts(): array
    {
        $all = $this->all();
        $counts = [
            'total' => count($all),
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($all as $item) {
            $status = strtoupper((string)($item['status'] ?? ''));
            if ($status === 'PENDING') {
                $counts['pending']++;
            } elseif ($status === 'APPROVED') {
                $counts['approved']++;
            } elseif ($status === 'REJECTED') {
                $counts['rejected']++;
            }
        }

        return $counts;
    }

    public function getPendingCount(): int
    {
        $counts = $this->getCounts();
        return $counts['pending'];
    }

    /**
     * Validate and securely store uploaded invoice
     * @return array [invoice_path, invoice_original_name, invoice_mime, invoice_size]
     */
    public function storeInvoice(string $registrationId, array $file): array
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new InvalidArgumentException('No valid uploaded invoice file provided.');
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Invoice upload failed with error code: ' . ($file['error'] ?? 0));
        }

        $maxBytes = (int)($this->config['max_invoice_size_bytes'] ?? (5 * 1024 * 1024));
        if ($file['size'] > $maxBytes) {
            throw new InvalidArgumentException('Invoice file size exceeds the 5 MB limit.');
        }

        $originalName = (string)($file['name'] ?? 'invoice');
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExts = $this->config['allowed_invoice_ext'] ?? ['pdf', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowedExts, true)) {
            throw new InvalidArgumentException('Invalid file format. Allowed formats: PDF, JPG, JPEG, PNG.');
        }

        // Verify MIME type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : ($file['type'] ?? '');
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMimes = $this->config['allowed_invoice_mime'] ?? [
            'application/pdf',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-png',
        ];

        if (!in_array($mime, $allowedMimes, true)) {
            throw new InvalidArgumentException('Invalid MIME type detected: ' . htmlspecialchars((string)$mime));
        }

        $targetFileName = $registrationId . '_invoice.' . $ext;
        $targetPath = $this->invoicesDir . DIRECTORY_SEPARATOR . $targetFileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException('Failed to move uploaded invoice to protected storage.');
        }

        @chmod($targetPath, 0640);

        return [
            'invoice_path' => 'invoices/' . $targetFileName,
            'invoice_original_name' => $originalName,
            'invoice_mime' => $mime,
            'invoice_size' => (int)$file['size'],
        ];
    }

    /**
     * Get absolute path for invoice file
     */
    public function getInvoiceFullPath(array $registration): ?string
    {
        $relativePath = $registration['purchase']['invoice_path'] ?? '';
        if (empty($relativePath)) {
            return null;
        }

        $filename = basename($relativePath);
        $fullPath = $this->invoicesDir . DIRECTORY_SEPARATOR . $filename;
        return file_exists($fullPath) ? $fullPath : null;
    }

    public function getFilePath(string $id): string
    {
        $safeId = preg_replace('/[^A-Za-z0-9_\-]/', '', $id);
        return $this->registrationsDir . DIRECTORY_SEPARATOR . $safeId . '.json';
    }

    public function getRegistrationFilePath(string $id): string
    {
        return $this->getFilePath($id);
    }

    public function delete(string $id): bool
    {
        $filePath = $this->getFilePath($id);
        if (file_exists($filePath)) {
            return @unlink($filePath);
        }
        return false;
    }
}
