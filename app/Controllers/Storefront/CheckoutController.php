<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Core\Session;
use App\Services\CartService;
use App\Services\OrderService;
use App\Repositories\OrderRepository;

class CheckoutController
{
    private CartService $cartService;
    private OrderService $orderService;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
        $this->orderRepo = new OrderRepository();
    }

    public function index(): void
    {
        $cart = $this->cartService->getCartDetails();

        if (empty($cart['items'])) {
            flash('info', 'Your cart is empty. Add products before proceeding to checkout.');
            redirect('shop');
        }

        $customer = auth_customer();

        echo View::render('storefront/checkout', [
            'title' => 'Checkout | HOMEIQ Secure Ordering',
            'cart' => $cart,
            'customer' => $customer,
        ]);
    }

    public function process(Request $request): void
    {
        $cart = $this->cartService->getCartDetails();
        if (empty($cart['items'])) {
            flash('error', 'Your shopping cart is empty.');
            redirect('shop');
        }

        // Validate form inputs
        $name = trim((string)$request->post('name', ''));
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        $phone = trim((string)$request->post('phone', ''));
        $address = trim((string)$request->post('address', ''));
        $city = trim((string)$request->post('city', ''));
        $state = trim((string)$request->post('state', ''));
        $pincode = trim((string)$request->post('pincode', ''));
        $paymentMethod = (string)$request->post('payment_method', 'cod');
        $notes = trim((string)$request->post('notes', ''));

        // Save old input in case of validation failure
        Session::setFlash('old_input', $request->post());

        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($pincode)) {
            flash('error', 'Please fill in all required shipping and contact details.');
            redirect('checkout');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            redirect('checkout');
        }

        if (!preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $phone))) {
            flash('error', 'Please enter a valid 10-digit mobile number.');
            redirect('checkout');
        }

        if (!preg_match('/^[1-9][0-9]{5}$/', $pincode)) {
            flash('error', 'Please enter a valid 6-digit Indian PIN code.');
            redirect('checkout');
        }

        if (!in_array($paymentMethod, ['cod', 'online'], true)) {
            flash('error', 'Please choose a valid payment method.');
            redirect('checkout');
        }

        $customer = auth_customer();
        $customerData = [
            'customer_id' => $customer['id'] ?? null,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'billing_different' => (bool)$request->post('billing_different', false),
            'billing_address' => $request->post('billing_address'),
            'billing_city' => $request->post('billing_city'),
            'billing_state' => $request->post('billing_state'),
            'billing_pincode' => $request->post('billing_pincode'),
            'notes' => $notes,
        ];

        $orderResult = $this->orderService->createOrder($customerData, $cart, $paymentMethod);

        if (!$orderResult['success']) {
            flash('error', $orderResult['error']);
            redirect('checkout');
        }

        $order = $orderResult['order'];

        // If online payment simulation/gateway
        if ($paymentMethod === 'online') {
            $paymentRef = 'SIM-PAY-' . strtoupper(bin2hex(random_bytes(4)));
            $this->orderService->updatePaymentStatus($order['id'], 'Paid', $paymentRef, 'Payment Gateway');
            $this->orderService->updateStatus($order['id'], 'Confirmed', 'Payment verified online.', 'Payment Gateway');
        }

        // Clear cart after successful order creation
        $this->cartService->clear();

        // Redirect to order confirmation
        Session::set('last_order_number', $order['order_number']);
        redirect('order/confirmation/' . $order['order_number']);
    }

    public function confirmation(Request $request, array $params): void
    {
        $orderNumber = $params['orderNumber'] ?? '';
        $order = $this->orderRepo->findByOrderNumber($orderNumber);

        if (!$order) {
            http_response_code(404);
            echo View::render('storefront/404', ['title' => 'Order Not Found']);
            return;
        }

        echo View::render('storefront/order_confirmation', [
            'title' => "Order Confirmed #{$order['order_number']} | HOMEIQ",
            'order' => $order,
        ]);
    }

    public function invoice(Request $request, array $params): void
    {
        $orderNumber = $params['orderNumber'] ?? '';
        $order = $this->orderRepo->findByOrderNumber($orderNumber);

        if (!$order) {
            http_response_code(404);
            echo "Order not found.";
            return;
        }

        // Security check: If customer logged in, verify ownership unless admin
        if (!is_admin_logged_in()) {
            $customer = auth_customer();
            if (!$customer || ($order['customer_id'] && $order['customer_id'] !== $customer['id'])) {
                // Check if session has last_order_number (just placed order)
                if (Session::get('last_order_number') !== $orderNumber) {
                    flash('error', 'Unauthorized access to invoice.');
                    redirect('login');
                    return;
                }
            }
        }

        // Render printable invoice without main layout
        echo View::render('storefront/invoice', [
            'order' => $order,
        ], null);
    }
}

