<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\AuditLogRepository;

class OrderService
{
    private OrderRepository $orderRepo;
    private ProductRepository $productRepo;
    private InventoryRepository $inventoryRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->productRepo = new ProductRepository();
        $this->inventoryRepo = new InventoryRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    /**
     * Create order from cart data and checkout input
     */
    public function createOrder(array $customerData, array $cartDetails, string $paymentMethod): array
    {
        if (empty($cartDetails['items'])) {
            return ['success' => false, 'error' => 'Your shopping cart is empty.'];
        }

        // Validate stock for all items before placing
        foreach ($cartDetails['items'] as $item) {
            $product = $this->productRepo->find($item['id']);
            if (!$product) {
                return ['success' => false, 'error' => "Product '{$item['name']}' is no longer available."];
            }
            if (($product['stock'] ?? 0) < $item['quantity']) {
                return [
                    'success' => false,
                    'error' => "Product '{$item['name']}' has only {$product['stock']} units left in stock."
                ];
            }
        }

        // Generate unique order number
        $orderNumber = $this->orderRepo->generateOrderNumber();

        $orderItems = [];
        // Deduct inventory atomically
        foreach ($cartDetails['items'] as $item) {
            $product = $this->productRepo->find($item['id']);
            $stockBefore = (int)($product['stock'] ?? 0);
            $stockAfter = max(0, $stockBefore - $item['quantity']);

            // Update product stock
            $this->productRepo->update($item['id'], ['stock' => $stockAfter]);

            // Log movement
            $this->inventoryRepo->logMovement(
                productId: $item['id'],
                productName: $item['name'],
                type: 'Sale',
                quantityChange: -$item['quantity'],
                stockBefore: $stockBefore,
                stockAfter: $stockAfter,
                reference: $orderNumber,
                notes: "Sold via Order #{$orderNumber}",
                performedBy: 'Checkout'
            );

            $orderItems[] = [
                'product_id' => $item['id'],
                'name' => $item['name'],
                'sku' => $item['sku'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['line_total'],
                'image' => $item['image'],
            ];
        }

        $now = date('Y-m-d H:i:s');
        $initialPaymentStatus = ($paymentMethod === 'cod') ? 'Pending' : 'Pending';

        $order = [
            'order_number' => $orderNumber,
            'customer_id' => $customerData['customer_id'] ?? null,
            'customer_name' => $customerData['name'],
            'customer_email' => $customerData['email'],
            'customer_phone' => $customerData['phone'],
            'shipping_address' => [
                'street' => $customerData['address'],
                'city' => $customerData['city'],
                'state' => $customerData['state'],
                'pincode' => $customerData['pincode'],
                'country' => 'India',
            ],
            'billing_address' => !empty($customerData['billing_different']) ? [
                'street' => $customerData['billing_address'] ?? $customerData['address'],
                'city' => $customerData['billing_city'] ?? $customerData['city'],
                'state' => $customerData['billing_state'] ?? $customerData['state'],
                'pincode' => $customerData['billing_pincode'] ?? $customerData['pincode'],
                'country' => 'India',
            ] : null,
            'items' => $orderItems,
            'subtotal' => $cartDetails['subtotal'],
            'discount' => $cartDetails['discount'],
            'coupon_code' => $cartDetails['applied_coupon']['code'] ?? null,
            'shipping_amount' => $cartDetails['shipping'],
            'total_amount' => $cartDetails['grand_total'],
            'payment_method' => $paymentMethod,
            'payment_status' => $initialPaymentStatus, // Pending, Paid, Failed, Refunded
            'payment_reference' => null,
            'status' => 'Pending', // Pending, Confirmed, Processing, Shipped, Delivered, Cancelled, Returned
            'tracking' => [
                'courier_name' => null,
                'tracking_number' => null,
                'tracking_url' => null,
                'shipped_at' => null,
                'delivered_at' => null,
            ],
            'notes' => $customerData['notes'] ?? '',
            'history' => [
                [
                    'status' => 'Pending',
                    'timestamp' => $now,
                    'note' => 'Order placed by customer.',
                    'by' => 'Customer',
                ]
            ],
        ];

        $savedOrder = $this->orderRepo->create($order);
        $this->auditRepo->record('order_created', $customerData['email'], "Placed Order {$orderNumber} for " . format_price($cartDetails['grand_total']));

        return ['success' => true, 'order' => $savedOrder];
    }

    /**
     * Update order status with audit log
     */
    public function updateStatus(string $orderId, string $newStatus, string $note = '', string $updatedBy = 'Admin'): array
    {
        $order = $this->orderRepo->find($orderId);
        if (!$order) {
            return ['success' => false, 'error' => 'Order not found.'];
        }

        $oldStatus = $order['status'] ?? '';
        if ($oldStatus === $newStatus) {
            return ['success' => true, 'order' => $order];
        }

        $history = $order['history'] ?? [];
        $now = date('Y-m-d H:i:s');
        $history[] = [
            'status' => $newStatus,
            'timestamp' => $now,
            'note' => $note ?: "Status updated from {$oldStatus} to {$newStatus}",
            'by' => $updatedBy,
        ];

        $updateData = [
            'status' => $newStatus,
            'history' => $history,
        ];

        // If newly cancelled and wasn't previously cancelled, restore stock
        if ($newStatus === 'Cancelled' && $oldStatus !== 'Cancelled') {
            foreach ($order['items'] as $item) {
                $product = $this->productRepo->find($item['product_id']);
                if ($product) {
                    $stockBefore = (int)($product['stock'] ?? 0);
                    $stockAfter = $stockBefore + (int)$item['quantity'];
                    $this->productRepo->update($item['product_id'], ['stock' => $stockAfter]);

                    $this->inventoryRepo->logMovement(
                        productId: $item['product_id'],
                        productName: $item['name'],
                        type: 'Return',
                        quantityChange: (int)$item['quantity'],
                        stockBefore: $stockBefore,
                        stockAfter: $stockAfter,
                        reference: $order['order_number'],
                        notes: "Restocked due to Order #{$order['order_number']} cancellation",
                        performedBy: $updatedBy
                    );
                }
            }
        }

        $updatedOrder = $this->orderRepo->update($orderId, $updateData);
        $this->auditRepo->record('order_status_update', $updatedBy, "Order {$order['order_number']} status changed to {$newStatus}");

        return ['success' => true, 'order' => $updatedOrder];
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(string $orderId, string $paymentStatus, ?string $reference = null, string $updatedBy = 'Admin'): array
    {
        $order = $this->orderRepo->find($orderId);
        if (!$order) {
            return ['success' => false, 'error' => 'Order not found.'];
        }

        $updateData = ['payment_status' => $paymentStatus];
        if ($reference !== null) {
            $updateData['payment_reference'] = $reference;
        }

        $updatedOrder = $this->orderRepo->update($orderId, $updateData);
        $this->auditRepo->record('payment_status_update', $updatedBy, "Order {$order['order_number']} payment updated to {$paymentStatus}");

        return ['success' => true, 'order' => $updatedOrder];
    }

    /**
     * Update shipping tracking details
     */
    public function updateTracking(string $orderId, array $trackingData, string $updatedBy = 'Admin'): array
    {
        $order = $this->orderRepo->find($orderId);
        if (!$order) {
            return ['success' => false, 'error' => 'Order not found.'];
        }

        $tracking = array_merge($order['tracking'] ?? [], [
            'courier_name' => $trackingData['courier_name'] ?? null,
            'tracking_number' => $trackingData['tracking_number'] ?? null,
            'tracking_url' => $trackingData['tracking_url'] ?? null,
        ]);

        $updatedOrder = $this->orderRepo->update($orderId, ['tracking' => $tracking]);
        $this->auditRepo->record('order_tracking_update', $updatedBy, "Updated tracking for Order {$order['order_number']}");

        return ['success' => true, 'order' => $updatedOrder];
    }
}

