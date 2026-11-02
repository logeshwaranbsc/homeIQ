<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Core\Session;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;

class CustomerAccountController
{
    private CustomerRepository $customerRepo;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->customerRepo = new CustomerRepository();
        $this->orderRepo = new OrderRepository();
    }

    public function dashboard(): void
    {
        $sessionUser = auth_customer();
        $customer = $this->customerRepo->find($sessionUser['id']);
        $orders = $this->orderRepo->findByCustomer($sessionUser['id']);

        echo View::render('storefront/account/dashboard', [
            'title' => 'My Account | HOMEIQ',
            'customer' => $customer,
            'recentOrders' => array_slice($orders, 0, 5),
            'totalOrders' => count($orders),
        ]);
    }

    public function profile(): void
    {
        $sessionUser = auth_customer();
        $customer = $this->customerRepo->find($sessionUser['id']);

        echo View::render('storefront/account/profile', [
            'title' => 'Account Profile | HOMEIQ',
            'customer' => $customer,
        ]);
    }

    public function updateProfile(Request $request): void
    {
        $sessionUser = auth_customer();
        $customer = $this->customerRepo->find($sessionUser['id']);

        $name = trim((string)$request->post('name', ''));
        $phone = trim((string)$request->post('phone', ''));
        $street = trim((string)$request->post('address', ''));
        $city = trim((string)$request->post('city', ''));
        $state = trim((string)$request->post('state', ''));
        $pincode = trim((string)$request->post('pincode', ''));

        if (empty($name)) {
            flash('error', 'Please enter your name.');
            redirect('account/profile');
        }

        $addresses = $customer['addresses'] ?? [];
        if (!empty($street) && !empty($city)) {
            $addresses = [
                [
                    'street' => $street,
                    'city' => $city,
                    'state' => $state,
                    'pincode' => $pincode,
                    'is_default' => true,
                ]
            ];
        }

        $this->customerRepo->update($customer['id'], [
            'name' => $name,
            'phone' => $phone,
            'addresses' => $addresses,
        ]);

        // Update session
        Session::set('customer', array_merge($sessionUser, ['name' => $name, 'phone' => $phone]));

        flash('success', 'Profile updated successfully.');
        redirect('account/profile');
    }

    public function changePassword(Request $request): void
    {
        $sessionUser = auth_customer();
        $customer = $this->customerRepo->find($sessionUser['id']);

        $currentPassword = (string)$request->post('current_password', '');
        $newPassword = (string)$request->post('new_password', '');
        $confirmPassword = (string)$request->post('new_password_confirmation', '');

        if (!password_verify($currentPassword, $customer['password_hash'] ?? '')) {
            flash('error', 'Current password entered is incorrect.');
            redirect('account/profile');
        }

        if (strlen($newPassword) < 6) {
            flash('error', 'New password must be at least 6 characters long.');
            redirect('account/profile');
        }

        if ($newPassword !== $confirmPassword) {
            flash('error', 'New passwords do not match.');
            redirect('account/profile');
        }

        $this->customerRepo->update($customer['id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        flash('success', 'Password updated successfully.');
        redirect('account/profile');
    }

    public function orders(): void
    {
        $sessionUser = auth_customer();
        $orders = $this->orderRepo->findByCustomer($sessionUser['id']);

        echo View::render('storefront/account/orders', [
            'title' => 'My Orders | HOMEIQ',
            'orders' => $orders,
        ]);
    }

    public function orderDetail(Request $request, array $params): void
    {
        $sessionUser = auth_customer();
        $orderNumber = $params['orderNumber'] ?? '';
        $order = $this->orderRepo->findByOrderNumber($orderNumber);

        // Ownership isolation check!
        if (!$order || ($order['customer_id'] ?? '') !== $sessionUser['id']) {
            flash('error', 'Order not found or you are not authorized to view this order.');
            redirect('account/orders');
            return;
        }

        echo View::render('storefront/account/order_detail', [
            'title' => "Order #{$order['order_number']} | HOMEIQ",
            'order' => $order,
        ]);
    }
}

