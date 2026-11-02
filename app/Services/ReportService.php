<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;

class ReportService
{
    private OrderRepository $orderRepo;
    private ProductRepository $productRepo;
    private CustomerRepository $customerRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->productRepo = new ProductRepository();
        $this->customerRepo = new CustomerRepository();
    }

    /**
     * Get aggregate sales analytics
     */
    public function getAnalytics(string $range = '30days'): array
    {
        $orders = $this->orderRepo->all();
        $products = $this->productRepo->all();
        $customers = $this->customerRepo->all();

        $startTimestamp = match ($range) {
            'today' => strtotime('today midnight'),
            '7days' => strtotime('-7 days midnight'),
            '30days' => strtotime('-30 days midnight'),
            'year' => strtotime('-1 year midnight'),
            default => 0,
        };

        $filteredOrders = array_filter($orders, function ($o) use ($startTimestamp) {
            if ($startTimestamp === 0) return true;
            return strtotime($o['created_at'] ?? '1970-01-01') >= $startTimestamp;
        });

        $totalRevenue = 0.0;
        $orderCount = count($filteredOrders);
        $deliveredCount = 0;
        $pendingCount = 0;
        $cancelledCount = 0;
        $productSales = [];

        foreach ($filteredOrders as $order) {
            $status = $order['status'] ?? 'Pending';
            $payStatus = $order['payment_status'] ?? 'Pending';

            if ($status === 'Delivered') $deliveredCount++;
            if ($status === 'Pending') $pendingCount++;
            if ($status === 'Cancelled') $cancelledCount++;

            if ($status !== 'Cancelled' && ($payStatus === 'Paid' || $status === 'Delivered')) {
                $totalRevenue += (float)($order['total_amount'] ?? 0);
            }

            foreach ($order['items'] ?? [] as $item) {
                $pid = $item['product_id'] ?? $item['name'];
                if (!isset($productSales[$pid])) {
                    $productSales[$pid] = [
                        'name' => $item['name'],
                        'quantity' => 0,
                        'revenue' => 0.0,
                    ];
                }
                $productSales[$pid]['quantity'] += (int)$item['quantity'];
                $productSales[$pid]['revenue'] += (float)$item['line_total'];
            }
        }

        // Sort top selling products
        usort($productSales, fn($a, $b) => $b['quantity'] <=> $a['quantity']);

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $orderCount,
            'delivered_orders' => $deliveredCount,
            'pending_orders' => $pendingCount,
            'cancelled_orders' => $cancelledCount,
            'total_customers' => count($customers),
            'total_products' => count($products),
            'top_products' => array_slice($productSales, 0, 5),
            'filtered_orders' => array_values($filteredOrders),
        ];
    }

    /**
     * Generate CSV export for orders
     */
    public function exportOrdersCsv(string $range = '30days'): string
    {
        $analytics = $this->getAnalytics($range);
        $orders = $analytics['filtered_orders'];

        $output = fopen('php://temp', 'r+');
        // Add UTF-8 BOM for Excel compatibility
        fputs($output, "\xEF\xBB\xBF");

        // Headers
        fputcsv($output, [
            'Order Number',
            'Date',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'City',
            'State',
            'Items Count',
            'Subtotal (INR)',
            'Discount (INR)',
            'Shipping (INR)',
            'Total (INR)',
            'Payment Method',
            'Payment Status',
            'Order Status'
        ]);

        foreach ($orders as $o) {
            $itemsCount = 0;
            foreach ($o['items'] ?? [] as $item) {
                $itemsCount += (int)($item['quantity'] ?? 1);
            }

            fputcsv($output, [
                $o['order_number'],
                $o['created_at'],
                $o['customer_name'] ?? '',
                $o['customer_email'] ?? '',
                $o['customer_phone'] ?? '',
                $o['shipping_address']['city'] ?? '',
                $o['shipping_address']['state'] ?? '',
                $itemsCount,
                $o['subtotal'] ?? 0,
                $o['discount'] ?? 0,
                $o['shipping_amount'] ?? 0,
                $o['total_amount'] ?? 0,
                strtoupper($o['payment_method'] ?? 'COD'),
                $o['payment_status'] ?? 'Pending',
                $o['status'] ?? 'Pending',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv ?: '';
    }
}

