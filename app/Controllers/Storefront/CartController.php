<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Services\CartService;

class CartController
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function index(): void
    {
        $cart = $this->cartService->getCartDetails();

        echo View::render('storefront/cart', [
            'title' => 'Shopping Cart | HOMEIQ',
            'cart' => $cart,
            'isCartPage' => true,
            'bodyClass' => 'page-cart',
        ]);
    }

    public function add(Request $request): void
    {
        $productId = (string)$request->post('product_id', $request->query('product_id', ''));
        $quantity = max(1, (int)$request->post('quantity', 1));
        $buyNow = (bool)$request->post('buy_now', false);

        $result = $this->cartService->addItem($productId, $quantity);

        if ($request->isAjax()) {
            json_response($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect($_SERVER['HTTP_REFERER'] ?? 'shop');
        }

        if ($buyNow) {
            redirect('checkout');
        }

        flash('success', 'Item successfully added to your cart.');
        redirect('cart');
    }

    public function update(Request $request): void
    {
        $productId = (string)$request->post('product_id', '');
        $quantity = (int)$request->post('quantity', 1);

        $result = $this->cartService->updateQuantity($productId, $quantity);

        if ($request->isAjax()) {
            json_response($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            flash('error', $result['error']);
        } else {
            flash('success', 'Cart updated successfully.');
        }

        redirect('cart');
    }

    public function remove(Request $request, array $params = []): void
    {
        $productId = (string)($params['id'] ?? $request->post('product_id', ''));
        $result = $this->cartService->removeItem($productId);

        if ($request->isAjax()) {
            json_response($result);
        }

        flash('success', 'Item removed from your cart.');
        redirect('cart');
    }

    public function applyCoupon(Request $request): void
    {
        $code = trim((string)$request->post('code', ''));
        if (empty($code)) {
            flash('error', 'Please enter a coupon code.');
            redirect('cart');
        }

        $result = $this->cartService->applyCoupon($code);

        if ($result['success']) {
            flash('success', $result['message']);
        } else {
            flash('error', $result['error']);
        }

        redirect('cart');
    }

    public function removeCoupon(): void
    {
        $this->cartService->removeCoupon();
        flash('info', 'Coupon removed.');
        redirect('cart');
    }

    public function data(): void
    {
        $cart = $this->cartService->getCartDetails();
        json_response([
            'success' => true,
            'cart' => $cart,
        ]);
    }

    public function count(): void
    {
        $cart = $this->cartService->getCartDetails(false);
        json_response([
            'count' => $cart['total_items'],
            'subtotal' => $cart['subtotal'],
            'formatted_subtotal' => format_price($cart['subtotal']),
        ]);
    }
}

