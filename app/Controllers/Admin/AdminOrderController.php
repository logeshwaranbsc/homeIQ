<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\OrderRepository;
use App\Services\OrderService;

class AdminOrderController
{
    private OrderRepository $orderRepo;
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->orderService = new OrderService();
    }

    public function index(Request $request): void
    {
        $q = trim((string)$request->query('q', ''));
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');

        $orders = $this->orderRepo->all();

        if (!empty($q)) {
            $needle = mb_strtolower($q);
            $orders = array_filter($orders, function ($o) use ($needle) {
                return str_contains(mb_strtolower($o['order_number'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($o['customer_name'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($o['customer_phone'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($o['customer_email'] ?? ''), $needle);
            });
        }

        if (!empty($status)) {
            $orders = array_filter($orders, fn($o) => ($o['status'] ?? '') === $status);
        }

        if (!empty($paymentStatus)) {
            $orders = array_filter($orders, fn($o) => ($o['payment_status'] ?? '') === $paymentStatus);
        }

        usort($orders, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));

        echo View::render('admin/orders/index', [
            'title' => 'Order Management | HOMEIQ Admin',
            'orders' => array_values($orders),
            'filters' => [
                'q' => $q,
                'status' => $status,
                'payment_status' => $paymentStatus,
            ],
            'statuses' => ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Returned'],
            'paymentStatuses' => ['Pending', 'Paid', 'Failed', 'Refunded'],
        ], 'admin');
    }

    public function show(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $order = $this->orderRepo->find($id) ?? $this->orderRepo->findByOrderNumber($id);

        if (!$order) {
            flash('error', 'Order not found.');
            redirect('admin/orders');
        }

        echo View::render('admin/orders/view', [
            'title' => "Order #{$order['order_number']} | HOMEIQ Admin",
            'order' => $order,
            'statuses' => ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Returned'],
            'paymentStatuses' => ['Pending', 'Paid', 'Failed', 'Refunded'],
        ], 'admin');
    }

    public function updateStatus(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $newStatus = (string)$request->post('status', '');
        $note = trim((string)$request->post('note', ''));
        $admin = auth_admin();

        $result = $this->orderService->updateStatus($id, $newStatus, $note, $admin['name'] ?? 'Admin');

        if (!$result['success']) {
            flash('error', $result['error']);
        } else {
            flash('success', "Order status successfully updated to {$newStatus}.");
        }

        redirect("admin/orders/view/{$id}");
    }

    public function updatePayment(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $paymentStatus = (string)$request->post('payment_status', 'Pending');
        $reference = trim((string)$request->post('payment_reference', ''));
        $admin = auth_admin();

        $result = $this->orderService->updatePaymentStatus($id, $paymentStatus, $reference, $admin['name'] ?? 'Admin');

        if (!$result['success']) {
            flash('error', $result['error']);
        } else {
            flash('success', "Payment status updated to {$paymentStatus}.");
        }

        redirect("admin/orders/view/{$id}");
    }

    public function updateTracking(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $courierName = trim((string)$request->post('courier_name', ''));
        $trackingNumber = trim((string)$request->post('tracking_number', ''));
        $trackingUrl = trim((string)$request->post('tracking_url', ''));
        $admin = auth_admin();

        $result = $this->orderService->updateTracking($id, [
            'courier_name' => $courierName,
            'tracking_number' => $trackingNumber,
            'tracking_url' => $trackingUrl,
        ], $admin['name'] ?? 'Admin');

        if (!$result['success']) {
            flash('error', $result['error']);
        } else {
            flash('success', 'Shipment tracking information updated.');
        }

        redirect("admin/orders/view/{$id}");
    }
}

