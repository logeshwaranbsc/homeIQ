<?php
$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';

use App\Core\App;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\BannerRepository;
use App\Repositories\CouponRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\AdminRepository;
use App\Repositories\SettingsRepository;

App::init($baseDir);

$repos = [
    'products' => (new ProductRepository())->all(),
    'categories' => (new CategoryRepository())->all(),
    'banners' => (new BannerRepository())->all(),
    'coupons' => (new CouponRepository())->all(),
    'reviews' => (new ReviewRepository())->all(),
    'orders' => (new OrderRepository())->all(),
    'customers' => (new CustomerRepository())->all(),
    'inventory_logs' => (new InventoryRepository())->allLogs(),
    'staff' => (new AdminRepository())->all(),
    'settings' => (new SettingsRepository())->all(),
];

$adminViews = [
    'admin/dashboard' => ['stats' => ['total_revenue' => 0, 'total_orders' => 0, 'pending_orders' => 0, 'active_products' => 9, 'total_products' => 9, 'low_stock' => 0, 'out_of_stock' => 0], 'recentOrders' => [], 'topProducts' => []],
    'admin/orders/index' => ['orders' => $repos['orders'], 'filters' => []],
    'admin/orders/view' => ['order' => $repos['orders'][0] ?? null],
    'admin/categories/index' => ['categories' => $repos['categories']],
    'admin/categories/form' => ['category' => $repos['categories'][0] ?? null],
    'admin/inventory/index' => ['products' => $repos['products'], 'movements' => $repos['inventory_logs'], 'filters' => []],
    'admin/banners/index' => ['banners' => $repos['banners']],
    'admin/banners/form' => ['banner' => $repos['banners'][0] ?? null],
    'admin/coupons/index' => ['coupons' => $repos['coupons']],
    'admin/coupons/form' => ['coupon' => $repos['coupons'][0] ?? null],
    'admin/reviews/index' => ['reviews' => $repos['reviews'], 'products' => $repos['products'], 'filters' => []],
    'admin/customers/index' => ['customers' => $repos['customers'], 'filters' => []],
    'admin/customers/view' => ['customer' => $repos['customers'][0] ?? null, 'orders' => []],
    'admin/reports/index' => ['stats' => ['total_revenue' => 0, 'total_orders' => 0, 'delivered_orders' => 0, 'cancelled_orders' => 0, 'avg_order_value' => 0], 'recentOrders' => []],
    'admin/seo/index' => ['settings' => $repos['settings'], 'products' => $repos['products'], 'categories' => $repos['categories']],
    'admin/staff/index' => ['admins' => $repos['staff']],
    'admin/staff/form' => ['adminUser' => $repos['staff'][0] ?? null],
    'admin/settings/index' => ['settings' => $repos['settings']],
];

foreach ($adminViews as $view => $data) {
    try {
        $html = View::render($view, $data, 'admin');
        $hasDoctype = str_contains($html, '<!DOCTYPE html>');
        $hasSidebar = str_contains($html, 'admin-sidebar');
        if ($hasDoctype && $hasSidebar) {
            echo "[OK] $view (" . strlen($html) . " bytes)\n";
        } else {
            echo "[WARN] $view missing doctype or sidebar\n";
        }
    } catch (\Throwable $e) {
        echo "[ERROR] $view: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
