<?php
declare(strict_types=1);

namespace App\Repositories;

use RuntimeException;
use InvalidArgumentException;

class CallbackLeadRepository
{
    private string $storageBase;
    private string $leadsDir;
    private string $lockFile;
    private OrderRepository $orderRepo;

    public function __construct(?string $storageBase = null)
    {
        if ($storageBase === null) {
            $this->storageBase = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'callback-leads';
        } else {
            $this->storageBase = $storageBase;
        }

        $this->leadsDir = $this->storageBase . DIRECTORY_SEPARATOR . 'leads';
        $this->lockFile = $this->storageBase . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'sequence.lock';
        $this->orderRepo = new OrderRepository();

        $this->ensureDirectories();
    }

    private function ensureDirectories(): void
    {
        if (!is_dir($this->leadsDir)) {
            @mkdir($this->leadsDir, 0775, true);
            @chmod($this->leadsDir, 0775);
        }
        $settingsDir = dirname($this->lockFile);
        if (!is_dir($settingsDir)) {
            @mkdir($settingsDir, 0775, true);
            @chmod($settingsDir, 0775);
        }
        if (!file_exists($this->lockFile)) {
            @file_put_contents($this->lockFile, '0');
            @chmod($this->lockFile, 0664);
        }
    }

    /**
     * Safely generate sequential lead ID: CB-HIQ-000001
     * Uses server-side flock to prevent duplicate IDs during simultaneous submissions.
     */
    public function generateNextId(): string
    {
        $this->ensureDirectories();
        $prefix = 'CB-HIQ-';
        $padding = 6;

        $lockFp = @fopen($this->lockFile, 'c+');
        if ($lockFp && flock($lockFp, LOCK_EX)) {
            try {
                // Read persistent counter from sequence file
                rewind($lockFp);
                $content = stream_get_contents($lockFp);
                $seqVal = 0;
                if ($content !== false && trim($content) !== '') {
                    $seqVal = (int)trim($content);
                }

                // Also inspect highest number from existing JSON files
                $maxFileNum = 0;
                $files = glob($this->leadsDir . DIRECTORY_SEPARATOR . $prefix . '*.json');
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

                // Persist updated sequence counter atomically
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

        // Resilient fallback if sequence.lock is locked/restricted on hosting
        $maxNum = 0;
        $files = glob($this->leadsDir . DIRECTORY_SEPARATOR . $prefix . '*.json') ?: [];
        foreach ($files as $file) {
            $numPart = substr(basename($file, '.json'), strlen($prefix));
            if (is_numeric($numPart)) {
                $maxNum = max($maxNum, (int)$numPart);
            }
        }
        $nextNum = $maxNum + 1;
        return sprintf('%s%0' . $padding . 'd', $prefix, $nextNum);
    }

    public function getFilePath(string $id): string
    {
        $safeId = preg_replace('/[^A-Za-z0-9_\-]/', '', $id);
        return $this->leadsDir . DIRECTORY_SEPARATOR . $safeId . '.json';
    }

    /**
     * Atomically write JSON using temporary file and rename
     */
    private function writeAtomicJson(string $destination, array $data): void
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
            @chmod($dir, 0775);
        }

        $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($jsonString === false) {
            throw new RuntimeException('Failed to encode callback lead data to JSON: ' . json_last_error_msg());
        }

        $tempFile = $dir . DIRECTORY_SEPARATOR . 'tmp_' . uniqid('', true) . '.json';
        $fp = @fopen($tempFile, 'wb');
        if ($fp) {
            flock($fp, LOCK_EX);
            fwrite($fp, $jsonString);
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);

            @chmod($tempFile, 0664);

            if (@rename($tempFile, $destination)) {
                @chmod($destination, 0664);
                return;
            }
            @unlink($tempFile);
        }

        // Direct write fallback if temp file creation or rename is restricted on hosting
        if (@file_put_contents($destination, $jsonString, LOCK_EX) === false) {
            throw new RuntimeException("Failed to write callback lead to {$destination}. Check folder permissions.");
        }
        @chmod($destination, 0664);
    }

    /**
     * Create and store a new callback enquiry
     */
    public function create(array $data): array
    {
        $leadId = $data['lead_id'] ?? null;
        if (empty($leadId)) {
            $leadId = $this->generateNextId();
            $data['lead_id'] = $leadId;
        }

        $now = date('c');
        $data['created_at'] = $data['created_at'] ?? $now;
        $data['updated_at'] = $now;
        $data['status'] = strtoupper((string)($data['status'] ?? 'NEW'));
        $data['admin_notes'] = (string)($data['admin_notes'] ?? '');
        $data['follow_up_date'] = $data['follow_up_date'] ?? null;
        $data['assigned_to'] = $data['assigned_to'] ?? null;

        if (!isset($data['history']) || !is_array($data['history'])) {
            $data['history'] = [
                [
                    'status' => $data['status'],
                    'changed_at' => $now,
                    'changed_by' => 'Customer Submission',
                    'notes' => 'Callback requested via storefront popup on ' . ($data['product_page_url'] ?? 'HOMEIQ website') . '.'
                ]
            ];
        }

        $filePath = $this->getFilePath($leadId);
        $this->writeAtomicJson($filePath, $data);

        return $data;
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

        if (!$content) {
            return null;
        }

        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Retrieve all callback enquiries sorted by latest created_at
     */
    public function all(): array
    {
        $this->ensureDirectories();
        $files = glob($this->leadsDir . DIRECTORY_SEPARATOR . 'CB-HIQ-*.json');
        if (!$files) {
            return [];
        }

        $leads = [];
        foreach ($files as $file) {
            $content = @file_get_contents($file);
            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $leads[] = $decoded;
                }
            }
        }

        usort($leads, function (array $a, array $b): int {
            $tA = strtotime($a['created_at'] ?? 'now');
            $tB = strtotime($b['created_at'] ?? 'now');
            return $tB <=> $tA;
        });

        return $leads;
    }

    /**
     * Filter callback enquiries with search, category, status, and date range
     */
    public function filter(array $filters = []): array
    {
        $all = $this->all();

        $q = trim((string)($filters['q'] ?? ''));
        $status = strtoupper(trim((string)($filters['status'] ?? 'ALL')));
        $productInterest = trim((string)($filters['product_interest'] ?? 'ALL'));
        $assignedTo = trim((string)($filters['assigned_to'] ?? 'ALL'));
        $startDate = trim((string)($filters['start_date'] ?? ''));
        $endDate = trim((string)($filters['end_date'] ?? ''));

        return array_values(array_filter($all, function (array $lead) use ($q, $status, $productInterest, $assignedTo, $startDate, $endDate): bool {
            // Text search
            if ($q !== '') {
                $leadId = $lead['lead_id'] ?? '';
                $name = $lead['customer_name'] ?? '';
                $mobile = $lead['mobile_number'] ?? '';
                $notes = $lead['admin_notes'] ?? '';
                $url = $lead['product_page_url'] ?? '';

                $haystack = mb_strtolower("{$leadId} {$name} {$mobile} {$notes} {$url}");
                if (!str_contains($haystack, mb_strtolower($q))) {
                    return false;
                }
            }

            // Status filter
            if ($status !== '' && $status !== 'ALL') {
                if (($lead['status'] ?? '') !== $status) {
                    return false;
                }
            }

            // Product Interest filter
            if ($productInterest !== '' && $productInterest !== 'ALL') {
                if (strcasecmp((string)($lead['product_interest'] ?? ''), $productInterest) !== 0) {
                    return false;
                }
            }

            // Assigned To filter
            if ($assignedTo !== '' && $assignedTo !== 'ALL') {
                if (($lead['assigned_to'] ?? '') !== $assignedTo) {
                    return false;
                }
            }

            // Date Range
            $createdTs = strtotime($lead['created_at'] ?? 'now');
            if ($startDate !== '') {
                $startTs = strtotime($startDate . ' 00:00:00');
                if ($startTs !== false && $createdTs < $startTs) {
                    return false;
                }
            }

            if ($endDate !== '') {
                $endTs = strtotime($endDate . ' 23:59:59');
                if ($endTs !== false && $createdTs > $endTs) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Update lead status, notes, assignment, or follow-up date
     */
    public function update(string $id, array $updates, string $actor = 'Admin', string $note = ''): ?array
    {
        $lead = $this->find($id);
        if (!$lead) {
            return null;
        }

        $now = date('c');
        $oldStatus = $lead['status'] ?? 'NEW';
        $newStatus = isset($updates['status']) ? strtoupper(trim((string)$updates['status'])) : $oldStatus;

        if (isset($updates['status'])) {
            $lead['status'] = $newStatus;
        }

        if (array_key_exists('admin_notes', $updates)) {
            $lead['admin_notes'] = trim((string)$updates['admin_notes']);
        }

        if (array_key_exists('assigned_to', $updates)) {
            $lead['assigned_to'] = !empty($updates['assigned_to']) ? trim((string)$updates['assigned_to']) : null;
        }

        if (array_key_exists('follow_up_date', $updates)) {
            $lead['follow_up_date'] = !empty($updates['follow_up_date']) ? trim((string)$updates['follow_up_date']) : null;
        }

        $lead['updated_at'] = $now;

        if (!isset($lead['history']) || !is_array($lead['history'])) {
            $lead['history'] = [];
        }

        $historyNote = $note;
        if ($historyNote === '') {
            $historyNote = ($oldStatus !== $newStatus)
                ? "Status updated from {$oldStatus} to {$newStatus}."
                : "Lead details updated.";
        }

        $lead['history'][] = [
            'status' => $newStatus,
            'changed_at' => $now,
            'changed_by' => $actor,
            'notes' => $historyNote,
        ];

        $filePath = $this->getFilePath($id);
        $this->writeAtomicJson($filePath, $lead);

        return $lead;
    }

    /**
     * Calculate summary counts dynamically from JSON files
     */
    public function getCounts(): array
    {
        $all = $this->all();
        $counts = [
            'total' => count($all),
            'new' => 0,
            'contacted' => 0,
            'follow_up' => 0,
            'converted' => 0,
            'not_interested' => 0,
            'unreachable' => 0,
        ];

        foreach ($all as $lead) {
            $st = strtoupper($lead['status'] ?? '');
            switch ($st) {
                case 'NEW':
                    $counts['new']++;
                    break;
                case 'CONTACTED':
                    $counts['contacted']++;
                    break;
                case 'FOLLOW-UP REQUIRED':
                case 'FOLLOW_UP':
                    $counts['follow_up']++;
                    break;
                case 'CONVERTED':
                    $counts['converted']++;
                    break;
                case 'NOT INTERESTED':
                case 'NOT_INTERESTED':
                    $counts['not_interested']++;
                    break;
                case 'UNREACHABLE':
                    $counts['unreachable']++;
                    break;
            }
        }

        return $counts;
    }

    public function getNewCount(): int
    {
        return $this->getCounts()['new'] ?? 0;
    }

    public function delete(string $id): bool
    {
        $filePath = $this->getFilePath($id);
        if (file_exists($filePath)) {
            return @unlink($filePath);
        }
        return false;
    }

    /**
     * Check actual orders placed by this customer's mobile number
     * Enables sales reps to verify order conversion against genuine orders.
     */
    public function findMatchingOrders(string $mobileNumber, ?string $customerName = null): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobileNumber);
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        if (empty($cleanPhone)) {
            return [];
        }

        $orders = $this->orderRepo->all();
        $matched = [];

        foreach ($orders as $order) {
            $orderPhone = preg_replace('/[^0-9]/', '', (string)($order['customer_phone'] ?? ($order['shipping_address']['phone'] ?? '')));
            if (strlen($orderPhone) >= 10) {
                $orderPhone = substr($orderPhone, -10);
            }

            if ($orderPhone === $cleanPhone) {
                $matched[] = $order;
            }
        }

        usort($matched, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return $matched;
    }
}
