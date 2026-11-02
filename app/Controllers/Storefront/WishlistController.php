<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Core\Session;
use App\Repositories\ProductRepository;

class WishlistController
{
    private ProductRepository $productRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
    }

    public function index(): void
    {
        $wishlistIds = Session::get('wishlist', []);
        $products = [];

        foreach ($wishlistIds as $id) {
            $product = $this->productRepo->find($id);
            if ($product && !empty($product['is_active'])) {
                $products[] = $product;
            }
        }

        echo View::render('storefront/wishlist', [
            'title' => 'My Wishlist | HOMEIQ',
            'products' => $products,
        ]);
    }

    public function toggle(Request $request): void
    {
        $productId = (string)$request->post('product_id', $request->query('product_id', ''));
        $product = $this->productRepo->find($productId);

        if (!$product) {
            if ($request->isAjax()) {
                json_response(['success' => false, 'error' => 'Product not found.'], 404);
            }
            flash('error', 'Product not found.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'shop');
        }

        $wishlist = Session::get('wishlist', []);
        $index = array_search($productId, $wishlist, true);

        if ($index !== false) {
            // Remove
            array_splice($wishlist, $index, 1);
            $added = false;
            $msg = 'Removed from wishlist.';
        } else {
            // Add
            $wishlist[] = $productId;
            $added = true;
            $msg = 'Added to wishlist.';
        }

        Session::set('wishlist', array_values($wishlist));

        if ($request->isAjax()) {
            json_response([
                'success' => true,
                'added' => $added,
                'count' => count($wishlist),
                'message' => $msg,
            ]);
        }

        flash('success', $msg);
        redirect($_SERVER['HTTP_REFERER'] ?? 'wishlist');
    }
}

