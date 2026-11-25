<?php
declare(strict_types=1);

namespace App\Repositories;

class ContactMessageRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('contact_messages.json', 'msg_');
    }

    /**
     * Get count of unread inquiries
     */
    public function getUnreadCount(): int
    {
        $messages = $this->all();
        $count = 0;
        foreach ($messages as $m) {
            if (($m['status'] ?? 'unread') === 'unread') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Get count of bulk orders
     */
    public function getBulkCount(): int
    {
        $messages = $this->all();
        $count = 0;
        foreach ($messages as $m) {
            if (($m['inquiry_type'] ?? 'general') === 'bulk') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Get count of general inquiries
     */
    public function getGeneralCount(): int
    {
        $messages = $this->all();
        $count = 0;
        foreach ($messages as $m) {
            if (($m['inquiry_type'] ?? 'general') !== 'bulk') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Filter inquiries by type, status, and search query
     *
     * @param string|null $type 'all', 'bulk', 'general'
     * @param string|null $status 'all', 'unread', 'contacted', 'in_progress', 'closed'
     * @param string|null $query
     * @return array
     */
    public function filterLeads(?string $type = null, ?string $status = null, ?string $query = null): array
    {
        $items = $this->all();

        // Sort descending by created_at
        usort($items, function ($a, $b) {
            $tA = strtotime($a['created_at'] ?? '1970-01-01');
            $tB = strtotime($b['created_at'] ?? '1970-01-01');
            return $tB <=> $tA;
        });

        return array_values(array_filter($items, function ($m) use ($type, $status, $query) {
            $mType = $m['inquiry_type'] ?? 'general';
            $mStatus = $m['status'] ?? 'unread';

            // Filter by type
            if ($type && $type !== 'all') {
                if ($type === 'bulk' && $mType !== 'bulk') {
                    return false;
                }
                if ($type === 'general' && $mType === 'bulk') {
                    return false;
                }
            }

            // Filter by status
            if ($status && $status !== 'all') {
                if ($mStatus !== $status) {
                    return false;
                }
            }

            // Filter by search query
            if ($query && trim($query) !== '') {
                $q = mb_strtolower(trim($query));
                $haystack = mb_strtolower(implode(' ', [
                    $m['name'] ?? '',
                    $m['email'] ?? '',
                    $m['phone'] ?? '',
                    $m['company'] ?? '',
                    $m['project_type'] ?? '',
                    $m['location'] ?? '',
                    $m['subject'] ?? '',
                    $m['message'] ?? '',
                ]));
                if (!str_contains($haystack, $q)) {
                    return false;
                }
            }

            return true;
        }));
    }
}
