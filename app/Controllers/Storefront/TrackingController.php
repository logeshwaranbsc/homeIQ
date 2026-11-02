<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\OrderRepository;

class TrackingController
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
    }

    public function index(Request $request): void
    {
        $orderNumber = trim((string)$request->query('order_number', ''));
        $contact = trim((string)$request->query('contact', ''));
        $order = null;
        $error = null;

        if (!empty($orderNumber) && !empty($contact)) {
            $found = $this->orderRepo->findByOrderNumber($orderNumber);
            if ($found) {
                $emailMatch = mb_strtolower($found['customer_email'] ?? '') === mb_strtolower($contact);
                $phoneMatch = preg_replace('/[^0-9]/', '', $found['customer_phone'] ?? '') === preg_replace('/[^0-9]/', '', $contact);

                if ($emailMatch || $phoneMatch) {
                    $order = $found;
                } else {
                    $error = 'Order number found, but the contact detail (email or phone) does not match our records.';
                }
            } else {
                $error = 'No order found with the provided order number.';
            }
        }

        echo View::render('storefront/order_tracking', [
            'title' => 'Track Your Order | HOMEIQ',
            'order' => $order,
            'error' => $error,
            'orderNumber' => $orderNumber,
            'contact' => $contact,
        ]);
    }
}

