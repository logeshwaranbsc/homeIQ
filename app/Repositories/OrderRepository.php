<?php
declare(strict_types=1);

namespace App\Repositories;

class OrderRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('orders.json', 'ord_');
    }

    public function findByOrderNumber(string $orderNumber): ?array
    {
        return $this->findBy('order_number', $orderNumber);
    }

    public function findByCustomer(string $customerId): array
    {
        $orders = $this->filter(fn($o) => ($o['customer_id'] ?? '') === $customerId);
        usort($orders, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return $orders;
    }

    public function getRecent(int $limit = 10): array
    {
        $orders = $this->all();
        usort($orders, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return array_slice($orders, 0, $limit);
    }

    public function getTotalRevenue(): float
    {
        $orders = $this->all();
        $revenue = 0.0;
        foreach ($orders as $order) {
            // Count revenue only for eligible paid or delivered orders, excluding cancelled
            $status = $order['status'] ?? '';
            $paymentStatus = $order['payment_status'] ?? '';
            if ($status !== 'Cancelled' && ($paymentStatus === 'Paid' || $status === 'Delivered')) {
                $revenue += (float)($order['total_amount'] ?? 0);
            }
        }
        return $revenue;
    }

    public function getCountByStatus(string $status): int
    {
        $orders = $this->filter(fn($o) => ($o['status'] ?? '') === $status);
        return count($orders);
    }

    public function generateOrderNumber(): string
    {
        return 'HIQ-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }
}

