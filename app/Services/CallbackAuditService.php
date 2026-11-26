<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuditLogRepository;

class CallbackAuditService
{
    private string $filePath;
    private AuditLogRepository $globalAuditRepo;

    public function __construct(?string $filePath = null)
    {
        if ($filePath === null) {
            $this->filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'callback-leads' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'callback-audit.json';
        } else {
            $this->filePath = $filePath;
        }

        $this->globalAuditRepo = new AuditLogRepository();
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

    public function record(
        string $leadId,
        string $action,
        string $actor,
        string $details = '',
        ?string $ip = null
    ): array {
        $ip = $ip ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

        $entry = [
            'id' => 'cbaudit_' . bin2hex(random_bytes(6)),
            'lead_id' => $leadId,
            'action' => strtoupper($action),
            'actor' => $actor,
            'details' => $details,
            'ip' => $ip,
            'created_at' => date('c'),
        ];

        // Safe append with flock
        $fp = fopen($this->filePath, 'c+');
        if ($fp && flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $list = $content ? json_decode($content, true) : [];
            if (!is_array($list)) {
                $list = [];
            }

            array_unshift($list, $entry);

            // Limit local audit log to 5,000 entries
            if (count($list) > 5000) {
                $list = array_slice($list, 0, 5000);
            }

            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        // Also record into system global audit logs
        try {
            $this->globalAuditRepo->record(
                'CALLBACK_' . strtoupper($action),
                $actor,
                "Lead {$leadId}: {$details}",
                $ip
            );
        } catch (\Throwable $e) {}

        return $entry;
    }

    public function getForLead(string $leadId): array
    {
        $all = $this->all();
        return array_values(array_filter($all, fn($item) => ($item['lead_id'] ?? '') === $leadId));
    }

    public function all(int $limit = 500): array
    {
        $content = @file_get_contents($this->filePath);
        if (!$content) {
            return [];
        }
        $list = json_decode($content, true);
        if (!is_array($list)) {
            return [];
        }

        return array_slice($list, 0, $limit);
    }
}
