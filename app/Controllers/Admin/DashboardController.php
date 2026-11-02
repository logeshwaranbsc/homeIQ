<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ReviewRepository;
use App\Services\ReportService;

class DashboardController
{
    private ProductRepository $productRepo;
    private OrderRepository $orderRepo;
    private CustomerRepository $customerRepo;
    private ReviewRepository $reviewRepo;
    private ReportService $reportService;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->orderRepo = new OrderRepository();
        $this->customerRepo = new CustomerRepository();
        $this->reviewRepo = new ReviewRepository();
        $this->reportService = new ReportService();
    }

    public function index(): void
    {
        $allProducts = $this->productRepo->all();
        $activeProducts = $this->productRepo->getActive();
        $lowStock = $this->productRepo->getLowStock(5);
        $outOfStock = $this->productRepo->getOutOfStock();

        $allOrders = $this->orderRepo->all();
        $pendingOrders = $this->orderRepo->getCountByStatus('Pending');
        $deliveredOrders = $this->orderRepo->getCountByStatus('Delivered');
        $totalRevenue = $this->orderRepo->getTotalRevenue();

        $recentOrders = $this->orderRepo->getRecent(6);
        $totalCustomers = count($this->customerRepo->all());
        $pendingReviewsCount = count($this->reviewRepo->getPending());

        $analytics = $this->reportService->getAnalytics('30days');

        echo View::render('admin/dashboard', [
            'title' => 'Dashboard | HOMEIQ Admin',
            'stats' => [
                'total_products' => count($allProducts),
                'active_products' => count($activeProducts),
                'low_stock' => count($lowStock),
                'out_of_stock' => count($outOfStock),
                'total_orders' => count($allOrders),
                'pending_orders' => $pendingOrders,
                'completed_orders' => $deliveredOrders,
                'total_revenue' => $totalRevenue,
                'total_customers' => $totalCustomers,
                'pending_reviews' => $pendingReviewsCount,
            ],
            'recentOrders' => $recentOrders,
            'topProducts' => $analytics['top_products'],
        ], 'admin');
    }
}

