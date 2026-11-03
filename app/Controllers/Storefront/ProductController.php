<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\OrderRepository;

class ProductController
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;
    private ReviewRepository $reviewRepo;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->reviewRepo = new ReviewRepository();
        $this->orderRepo = new OrderRepository();
    }

    public function index(Request $request): void
    {
        $criteria = [
            'q' => $request->query('q'),
            'category' => $request->query('category'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'access_method' => $request->query('access_method'),
            'connectivity' => $request->query('connectivity'),
            'color' => $request->query('color'),
            'in_stock' => $request->query('in_stock'),
            'sort' => $request->query('sort', 'newest'),
        ];

        $allFiltered = $this->productRepo->search($criteria);
        $totalItems = count($allFiltered);

        // Pagination
        $perPage = 12;
        $currentPage = max(1, (int)$request->query('page', 1));
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $offset = ($currentPage - 1) * $perPage;
        $products = array_slice($allFiltered, $offset, $perPage);

        $categories = $this->categoryRepo->getActive();

        // Extract available filter values from active products for dynamic filter sidebar
        $activeProducts = $this->productRepo->getActive();
        $accessMethodsList = [];
        $connectivityList = [];
        $colorsList = [];

        foreach ($activeProducts as $p) {
            foreach ($p['access_methods'] ?? [] as $am) {
                $accessMethodsList[trim($am)] = true;
            }
            foreach ($p['connectivity'] ?? [] as $conn) {
                $connectivityList[trim($conn)] = true;
            }
            if (!empty($p['color'])) {
                $colorsList[trim($p['color'])] = true;
            }
        }

        echo View::render('storefront/shop', [
            'title' => 'Shop All Digital Locks | HOMEIQ Smart Door Security',
            'products' => $products,
            'totalItems' => $totalItems,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'criteria' => $criteria,
            'availableMethods' => array_keys($accessMethodsList),
            'availableConnectivity' => array_keys($connectivityList),
            'availableColors' => array_keys($colorsList),
        ]);
    }

    public function category(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $category = $this->categoryRepo->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            echo View::render('storefront/404', ['title' => 'Category Not Found']);
            return;
        }

        $criteria = [
            'category' => $category['slug'],
            'sort' => $request->query('sort', 'newest'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ];

        $products = $this->productRepo->search($criteria);

        echo View::render('storefront/category', [
            'title' => ($category['name'] ?? 'Smart Locks') . ' | HOMEIQ',
            'category' => $category,
            'products' => $products,
            'totalItems' => count($products),
            'criteria' => $criteria,
        ]);
    }

    public function categories(): void
    {
        $categories = $this->categoryRepo->getActive();
        echo View::render('storefront/categories', [
            'title' => 'Smart Lock Categories | HOMEIQ',
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $product = $this->productRepo->findBySlug($slug);

        if (!$product || empty($product['is_active'])) {
            http_response_code(404);
            echo View::render('storefront/404', ['title' => 'Product Not Found - HOMEIQ']);
            return;
        }

        // Get approved reviews
        $reviews = $this->reviewRepo->findByProduct($product['id'], true);

        // Get related products from same category
        $related = $this->productRepo->search([
            'category' => $product['category_slug'] ?? '',
        ]);
        $related = array_filter($related, fn($p) => $p['id'] !== $product['id']);
        $related = array_slice(array_values($related), 0, 4);

        echo View::render('storefront/product_detail', [
            'title' => ($product['name'] ?? 'Smart Lock') . ' | HOMEIQ',
            'product' => $product,
            'reviews' => $reviews,
            'related' => $related,
        ]);
    }

    public function checkPincode(Request $request): void
    {
        $pincode = trim((string)$request->query('pincode', ''));
        
        if (!preg_match('/^[1-9][0-9]{5}$/', $pincode)) {
            json_response(['available' => false, 'message' => 'Please enter a valid 6-digit Indian PIN code.']);
        }

        // Realistic pin code serviceability simulation (Serviceable everywhere in major cities)
        $metroPrefixes = ['11', '12', '20', '38', '40', '41', '50', '56', '60', '70'];
        $prefix = substr($pincode, 0, 2);
        $isMetro = in_array($prefix, $metroPrefixes, true);

        json_response([
            'available' => true,
            'pincode' => $pincode,
            'delivery_estimate' => $isMetro ? 'Express delivery in 2-3 business days' : 'Delivery in 4-6 business days',
            'installation_available' => true,
            'message' => 'Delivery and free professional installation are available at your location!',
        ]);
    }

    public function submitReview(Request $request): void
    {
        $productId = (string)$request->post('product_id', '');
        $product = $this->productRepo->find($productId);

        if (!$product) {
            flash('error', 'Invalid product selected.');
            redirect('shop');
        }

        $name = trim((string)$request->post('reviewer_name', ''));
        $rating = (int)$request->post('rating', 5);
        $title = trim((string)$request->post('title', ''));
        $comment = trim((string)$request->post('comment', ''));

        if (empty($name) || empty($comment)) {
            flash('error', 'Please provide your name and review comments.');
            redirect('product/' . $product['slug']);
        }

        // Verify purchase if customer is logged in
        $verifiedPurchase = false;
        if (is_customer_logged_in()) {
            $customer = auth_customer();
            $customerOrders = $this->orderRepo->findByCustomer($customer['id']);
            foreach ($customerOrders as $order) {
                if ($order['status'] === 'Delivered') {
                    foreach ($order['items'] as $item) {
                        if (($item['product_id'] ?? '') === $productId) {
                            $verifiedPurchase = true;
                            break 2;
                        }
                    }
                }
            }
        }

        $this->reviewRepo->create([
            'product_id' => $productId,
            'product_name' => $product['name'],
            'reviewer_name' => $name,
            'rating' => max(1, min(5, $rating)),
            'title' => $title,
            'comment' => $comment,
            'verified_purchase' => $verifiedPurchase,
            'is_approved' => false, // Requires admin moderation
            'review_type' => 'product', // Shows exclusively under this product
            'is_site_review' => false,
        ]);

        flash('success', 'Thank you! Your review has been submitted and will appear after moderation.');
        redirect('product/' . $product['slug']);
    }
}

