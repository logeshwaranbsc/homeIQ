<?php
declare(strict_types=1);

use App\Core\App;
use App\Middleware\CsrfMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminAuthMiddleware;

use App\Controllers\Storefront\HomeController;
use App\Controllers\Storefront\ProductController;
use App\Controllers\Storefront\CartController;
use App\Controllers\Storefront\CheckoutController;
use App\Controllers\Storefront\CustomerAuthController;
use App\Controllers\Storefront\CustomerAccountController;
use App\Controllers\Storefront\WishlistController;
use App\Controllers\Storefront\TrackingController;
use App\Controllers\Storefront\PageController;

use App\Controllers\Admin\AdminAuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\AdminProductController;
use App\Controllers\Admin\AdminCategoryController;
use App\Controllers\Admin\AdminBannerController;
use App\Controllers\Admin\AdminOrderController;
use App\Controllers\Admin\AdminCustomerController;
use App\Controllers\Admin\AdminInventoryController;
use App\Controllers\Admin\AdminCouponController;
use App\Controllers\Admin\AdminReviewController;
use App\Controllers\Admin\AdminReportController;
use App\Controllers\Admin\AdminSeoController;
use App\Controllers\Admin\AdminStaffController;
use App\Controllers\Admin\AdminSettingsController;

$router = App::getRouter();

// ==========================================
// STOREFRONT ROUTES
// ==========================================
$router->get('/', [HomeController::class, 'index']);
$router->post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'], [CsrfMiddleware::class]);

// Products & Categories
$router->get('/shop', [ProductController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/categories', [ProductController::class, 'categories']);
$router->get('/category/{slug}', [ProductController::class, 'category']);
$router->get('/product/{slug}', [ProductController::class, 'show']);
$router->get('/api/check-pincode', [ProductController::class, 'checkPincode']);
$router->post('/product/review', [ProductController::class, 'submitReview'], [CsrfMiddleware::class]);

// Cart
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add'], [CsrfMiddleware::class]);
$router->post('/cart/update', [CartController::class, 'update'], [CsrfMiddleware::class]);
$router->post('/cart/remove', [CartController::class, 'remove'], [CsrfMiddleware::class]);
$router->get('/cart/remove/{id}', [CartController::class, 'remove']);
$router->post('/cart/coupon', [CartController::class, 'applyCoupon'], [CsrfMiddleware::class]);
$router->get('/cart/coupon/remove', [CartController::class, 'removeCoupon']);
$router->get('/api/cart/count', [CartController::class, 'count']);

// Checkout & Orders (Protected — Customer Sign-In Required)
$router->get('/checkout', [CheckoutController::class, 'index'], [AuthMiddleware::class]);
$router->post('/checkout/process', [CheckoutController::class, 'process'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/order/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation']);
$router->get('/order/invoice/{orderNumber}', [CheckoutController::class, 'invoice']);
$router->get('/order-tracking', [TrackingController::class, 'index']);

// Wishlist
$router->get('/wishlist', [WishlistController::class, 'index']);
$router->post('/wishlist/toggle', [WishlistController::class, 'toggle'], [CsrfMiddleware::class]);
$router->get('/wishlist/toggle', [WishlistController::class, 'toggle']);

// Customer Authentication
$router->get('/login', [CustomerAuthController::class, 'loginForm']);
$router->post('/login', [CustomerAuthController::class, 'login'], [CsrfMiddleware::class]);
$router->get('/register', [CustomerAuthController::class, 'registerForm']);
$router->post('/register', [CustomerAuthController::class, 'register'], [CsrfMiddleware::class]);
$router->get('/logout', [CustomerAuthController::class, 'logout']);

// Customer Account (Protected)
$router->get('/account', [CustomerAccountController::class, 'dashboard'], [AuthMiddleware::class]);
$router->get('/account/profile', [CustomerAccountController::class, 'profile'], [AuthMiddleware::class]);
$router->post('/account/profile', [CustomerAccountController::class, 'updateProfile'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/account/password', [CustomerAccountController::class, 'changePassword'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/account/orders', [CustomerAccountController::class, 'orders'], [AuthMiddleware::class]);
$router->get('/account/orders/{orderNumber}', [CustomerAccountController::class, 'orderDetail'], [AuthMiddleware::class]);

// Static Information Pages
$router->get('/about', [PageController::class, 'about']);
$router->get('/faq', [PageController::class, 'faq']);
$router->get('/contact', [PageController::class, 'contact']);
$router->post('/contact', [PageController::class, 'submitContact'], [CsrfMiddleware::class]);
$router->get('/shipping-policy', [PageController::class, 'shippingPolicy']);
$router->get('/returns-policy', [PageController::class, 'returnsPolicy']);
$router->get('/privacy-policy', [PageController::class, 'privacyPolicy']);
$router->get('/terms', [PageController::class, 'terms']);


// ==========================================
// ADMIN DASHBOARD ROUTES
// ==========================================
$router->get('/admin', function () {
    redirect('admin/dashboard');
});
$router->get('/admin/login', [AdminAuthController::class, 'loginForm']);
$router->post('/admin/login', [AdminAuthController::class, 'login'], [CsrfMiddleware::class]);
$router->get('/admin/logout', [AdminAuthController::class, 'logout']);

$router->get('/admin/dashboard', [DashboardController::class, 'index'], [AdminAuthMiddleware::class]);

// Admin Products
$router->get('/admin/products', [AdminProductController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/products/create', [AdminProductController::class, 'create'], [AdminAuthMiddleware::class]);
$router->post('/admin/products/store', [AdminProductController::class, 'store'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/products/edit/{id}', [AdminProductController::class, 'edit'], [AdminAuthMiddleware::class]);
$router->post('/admin/products/update/{id}', [AdminProductController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/products/delete/{id}', [AdminProductController::class, 'delete'], [AdminAuthMiddleware::class]);
$router->get('/admin/products/status/{id}', [AdminProductController::class, 'toggleStatus'], [AdminAuthMiddleware::class]);

// Admin Categories
$router->get('/admin/categories', [AdminCategoryController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/categories/create', [AdminCategoryController::class, 'create'], [AdminAuthMiddleware::class]);
$router->post('/admin/categories/store', [AdminCategoryController::class, 'store'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/categories/edit/{id}', [AdminCategoryController::class, 'edit'], [AdminAuthMiddleware::class]);
$router->post('/admin/categories/update/{id}', [AdminCategoryController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/categories/delete/{id}', [AdminCategoryController::class, 'delete'], [AdminAuthMiddleware::class]);

// Admin Banners
$router->get('/admin/banners', [AdminBannerController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/banners/create', [AdminBannerController::class, 'create'], [AdminAuthMiddleware::class]);
$router->post('/admin/banners/store', [AdminBannerController::class, 'store'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/banners/edit/{id}', [AdminBannerController::class, 'edit'], [AdminAuthMiddleware::class]);
$router->post('/admin/banners/update/{id}', [AdminBannerController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/banners/delete/{id}', [AdminBannerController::class, 'delete'], [AdminAuthMiddleware::class]);

// Admin Orders
$router->get('/admin/orders', [AdminOrderController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/orders/view/{id}', [AdminOrderController::class, 'show'], [AdminAuthMiddleware::class]);
$router->post('/admin/orders/status/{id}', [AdminOrderController::class, 'updateStatus'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/orders/payment/{id}', [AdminOrderController::class, 'updatePayment'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/orders/tracking/{id}', [AdminOrderController::class, 'updateTracking'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);

// Admin Customers
$router->get('/admin/customers', [AdminCustomerController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/customers/view/{id}', [AdminCustomerController::class, 'show'], [AdminAuthMiddleware::class]);
$router->get('/admin/customers/status/{id}', [AdminCustomerController::class, 'toggleStatus'], [AdminAuthMiddleware::class]);

// Admin Inventory
$router->get('/admin/inventory', [AdminInventoryController::class, 'index'], [AdminAuthMiddleware::class]);
$router->post('/admin/inventory/adjust', [AdminInventoryController::class, 'adjust'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);

// Admin Coupons
$router->get('/admin/coupons', [AdminCouponController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/coupons/create', [AdminCouponController::class, 'create'], [AdminAuthMiddleware::class]);
$router->post('/admin/coupons/store', [AdminCouponController::class, 'store'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/coupons/edit/{id}', [AdminCouponController::class, 'edit'], [AdminAuthMiddleware::class]);
$router->post('/admin/coupons/update/{id}', [AdminCouponController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/coupons/delete/{id}', [AdminCouponController::class, 'delete'], [AdminAuthMiddleware::class]);

// Admin Reviews
$router->get('/admin/reviews', [AdminReviewController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/reviews/approve/{id}', [AdminReviewController::class, 'approve'], [AdminAuthMiddleware::class]);
$router->get('/admin/reviews/reject/{id}', [AdminReviewController::class, 'reject'], [AdminAuthMiddleware::class]);

// Admin Reports
$router->get('/admin/reports', [AdminReportController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/reports/export', [AdminReportController::class, 'export'], [AdminAuthMiddleware::class]);

// Admin SEO
$router->get('/admin/seo', [AdminSeoController::class, 'index'], [AdminAuthMiddleware::class]);
$router->post('/admin/seo/update', [AdminSeoController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);

// Admin Staff
$router->get('/admin/staff', [AdminStaffController::class, 'index'], [AdminAuthMiddleware::class]);
$router->get('/admin/staff/create', [AdminStaffController::class, 'create'], [AdminAuthMiddleware::class]);
$router->post('/admin/staff/store', [AdminStaffController::class, 'store'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/staff/edit/{id}', [AdminStaffController::class, 'edit'], [AdminAuthMiddleware::class]);
$router->post('/admin/staff/update/{id}', [AdminStaffController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/staff/delete/{id}', [AdminStaffController::class, 'delete'], [AdminAuthMiddleware::class]);

// Admin Settings
$router->get('/admin/settings', [AdminSettingsController::class, 'index'], [AdminAuthMiddleware::class]);
$router->post('/admin/settings/update', [AdminSettingsController::class, 'update'], [AdminAuthMiddleware::class, CsrfMiddleware::class]);

