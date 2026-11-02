<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Repositories\ProductRepository;
use App\Repositories\CouponRepository;

class CartService
{
    private ProductRepository $productRepo;
    private CouponRepository $couponRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->couponRepo = new CouponRepository();
    }

    /**
     * Get raw cart items array from session: [product_id => quantity]
     */
    public function getRawCart(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Add product to cart with quantity
     */
    public function addItem(string $productId, int $quantity = 1): array
    {
        if ($quantity <= 0) {
            return ['success' => false, 'error' => 'Quantity must be at least 1.'];
        }

        $product = $this->productRepo->find($productId);
        if (!$product || empty($product['is_active'])) {
            return ['success' => false, 'error' => 'Product is unavailable.'];
        }

        $cart = $this->getRawCart();
        $currentQty = $cart[$productId] ?? 0;
        $newQty = $currentQty + $quantity;

        $availableStock = (int)($product['stock'] ?? 0);
        if ($availableStock < $newQty) {
            return [
                'success' => false,
                'error' => "Only {$availableStock} unit(s) available in stock."
            ];
        }

        $cart[$productId] = $newQty;
        Session::set('cart', $cart);

        return ['success' => true, 'cart' => $this->getCartDetails()];
    }

    /**
     * Update product quantity in cart
     */
    public function updateQuantity(string $productId, int $quantity): array
    {
        $cart = $this->getRawCart();

        if ($quantity <= 0) {
            unset($cart[$productId]);
            Session::set('cart', $cart);
            return ['success' => true, 'cart' => $this->getCartDetails()];
        }

        $product = $this->productRepo->find($productId);
        if (!$product || empty($product['is_active'])) {
            unset($cart[$productId]);
            Session::set('cart', $cart);
            return ['success' => false, 'error' => 'Product is no longer available.'];
        }

        $availableStock = (int)($product['stock'] ?? 0);
        if ($quantity > $availableStock) {
            return [
                'success' => false,
                'error' => "Only {$availableStock} unit(s) available in stock."
            ];
        }

        $cart[$productId] = $quantity;
        Session::set('cart', $cart);

        return ['success' => true, 'cart' => $this->getCartDetails()];
    }

    /**
     * Remove product from cart
     */
    public function removeItem(string $productId): array
    {
        $cart = $this->getRawCart();
        unset($cart[$productId]);
        Session::set('cart', $cart);

        return ['success' => true, 'cart' => $this->getCartDetails()];
    }

    /**
     * Clear all items
     */
    public function clear(): void
    {
        Session::remove('cart');
        Session::remove('applied_coupon');
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(string $code): array
    {
        $coupon = $this->couponRepo->findByCode($code);
        if (!$coupon || empty($coupon['is_active'])) {
            return ['success' => false, 'error' => 'Invalid or expired coupon code.'];
        }

        // Check expiry date
        if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
            return ['success' => false, 'error' => 'This coupon has expired.'];
        }

        $cartDetails = $this->getCartDetails(false);
        if ($cartDetails['subtotal'] < ($coupon['min_order_amount'] ?? 0)) {
            $minVal = format_price($coupon['min_order_amount']);
            return ['success' => false, 'error' => "This coupon requires a minimum cart value of {$minVal}."];
        }

        Session::set('applied_coupon', $coupon['code']);
        return ['success' => true, 'message' => "Coupon '{$coupon['code']}' applied successfully!"];
    }

    /**
     * Remove coupon
     */
    public function removeCoupon(): void
    {
        Session::remove('applied_coupon');
    }

    /**
     * Calculate and return detailed cart with recalculated verified prices
     */
    public function getCartDetails(bool $includeCoupon = true): array
    {
        $rawCart = $this->getRawCart();
        $items = [];
        $subtotal = 0.0;
        $totalItems = 0;

        foreach ($rawCart as $productId => $qty) {
            $product = $this->productRepo->find((string)$productId);
            if (!$product || empty($product['is_active'])) {
                continue;
            }

            $price = (float)($product['sale_price'] ?? $product['price'] ?? 0);
            $mrp = (float)($product['price'] ?? $price);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;
            $totalItems += $qty;

            $items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'sku' => $product['sku'] ?? '',
                'image' => $product['main_image'] ?? '',
                'price' => $price,
                'mrp' => $mrp,
                'stock' => (int)($product['stock'] ?? 0),
                'quantity' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        // Shipping calculation
        $freeShippingThreshold = (float)config('shipping.free_shipping_threshold', 5000);
        $flatShipping = (float)config('shipping.flat_rate', 250);
        $shipping = ($subtotal >= $freeShippingThreshold || $subtotal == 0) ? 0.0 : $flatShipping;

        // Discount calculation from coupon
        $discountAmount = 0.0;
        $appliedCoupon = null;

        if ($includeCoupon && Session::has('applied_coupon') && $subtotal > 0) {
            $code = Session::get('applied_coupon');
            $coupon = $this->couponRepo->findByCode($code);
            if ($coupon && !empty($coupon['is_active']) && $subtotal >= ($coupon['min_order_amount'] ?? 0)) {
                $appliedCoupon = $coupon;
                if ($coupon['type'] === 'percentage') {
                    $discountAmount = ($subtotal * (float)$coupon['value']) / 100;
                    if (!empty($coupon['max_discount']) && $discountAmount > (float)$coupon['max_discount']) {
                        $discountAmount = (float)$coupon['max_discount'];
                    }
                } else {
                    $discountAmount = (float)$coupon['value'];
                }
                $discountAmount = min($discountAmount, $subtotal); // Cannot exceed subtotal
            } else {
                Session::remove('applied_coupon');
            }
        }

        $grandTotal = max(0.0, ($subtotal - $discountAmount) + $shipping);

        return [
            'items' => $items,
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'shipping' => $shipping,
            'grand_total' => $grandTotal,
            'free_shipping_threshold' => $freeShippingThreshold,
            'shipping_shortfall' => max(0.0, $freeShippingThreshold - $subtotal),
            'applied_coupon' => $appliedCoupon,
        ];
    }
}

