<?php
declare(strict_types=1);

namespace App\Repositories;

class AuditLogRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('audit_logs.json', 'log_');
    }

    public function record(string $action, string $user, string $details = '', string $ip = ''): array
    {
        return $this->create([
            'action' => $action,
            'user' => $user,
            'details' => $details,
            'ip' => $ip,
        ]);
    }

    public function getRecent(int $limit = 100): array
    {
        $logs = $this->all();
        usort($logs, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return array_slice($logs, 0, $limit);
    }
}

