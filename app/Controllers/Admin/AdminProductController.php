<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

class AdminProductController
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;
    private InventoryRepository $inventoryRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->inventoryRepo = new InventoryRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(Request $request): void
    {
        $q = trim((string)$request->query('q', ''));
        $categorySlug = $request->query('category');
        $status = $request->query('status');

        $products = $this->productRepo->all();

        if (!empty($q)) {
            $needle = mb_strtolower($q);
            $products = array_filter($products, function ($p) use ($needle) {
                return str_contains(mb_strtolower($p['name'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($p['sku'] ?? ''), $needle);
            });
        }

        if (!empty($categorySlug)) {
            $products = array_filter($products, fn($p) => ($p['category_slug'] ?? '') === $categorySlug);
        }

        if ($status !== null && $status !== '') {
            $isActive = $status === '1';
            $products = array_filter($products, fn($p) => (bool)($p['is_active'] ?? false) === $isActive);
        }

        usort($products, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));

        $categories = $this->categoryRepo->all();

        echo View::render('admin/products/index', [
            'title' => 'Product Management | HOMEIQ Admin',
            'products' => array_values($products),
            'categories' => $categories,
            'filters' => [
                'q' => $q,
                'category' => $categorySlug,
                'status' => $status,
            ]
        ], 'admin');
    }

    public function create(): void
    {
        $categories = $this->categoryRepo->getActive();
        echo View::render('admin/products/form', [
            'title' => 'Add New Digital Lock | HOMEIQ Admin',
            'categories' => $categories,
            'product' => null,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $admin = auth_admin();
        $data = $this->extractProductInput($request);

        if (empty($data['name']) || empty($data['sku']) || empty($data['price'])) {
            flash('error', 'Product name, SKU, and regular price are required.');
            redirect('admin/products/create');
        }

        // Check SKU uniqueness
        if ($this->productRepo->findBySku($data['sku'])) {
            flash('error', 'A product with this SKU already exists.');
            redirect('admin/products/create');
        }

        // Handle image upload
        $imageFile = $request->file('main_image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'products');
            if ($upload['success']) {
                $data['main_image'] = $upload['filename'];
            }
        }

        // Handle additional gallery images upload
        $galleryFiles = $request->file('gallery_images');
        $data['gallery_images'] = $galleryFiles ? FileUploader::uploadMultiple($galleryFiles, 'products') : [];

        // Auto slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = slugify($data['name']);
        }

        $created = $this->productRepo->create($data);

        // Initial inventory log
        $initialStock = (int)$data['stock'];
        if ($initialStock > 0) {
            $this->inventoryRepo->logMovement(
                productId: $created['id'],
                productName: $created['name'],
                type: 'Initial',
                quantityChange: $initialStock,
                stockBefore: 0,
                stockAfter: $initialStock,
                reference: 'Initial Setup',
                notes: 'Initial stock intake upon product creation',
                performedBy: $admin['username'] ?? 'Admin'
            );
        }

        $this->auditRepo->record('product_created', $admin['username'] ?? 'Admin', "Created product {$created['name']} (SKU: {$created['sku']})");

        flash('success', "Product '{$created['name']}' created successfully.");
        redirect('admin/products');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $product = $this->productRepo->find($id);

        if (!$product) {
            flash('error', 'Product not found.');
            redirect('admin/products');
        }

        $categories = $this->categoryRepo->all();

        echo View::render('admin/products/form', [
            'title' => 'Edit ' . $product['name'] . ' | HOMEIQ Admin',
            'product' => $product,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $product = $this->productRepo->find($id);

        if (!$product) {
            flash('error', 'Product not found.');
            redirect('admin/products');
        }

        $admin = auth_admin();
        $data = $this->extractProductInput($request);

        // Check SKU uniqueness if changed
        $existingSku = $this->productRepo->findBySku($data['sku']);
        if ($existingSku && $existingSku['id'] !== $id) {
            flash('error', 'Another product is already using this SKU.');
            redirect("admin/products/edit/{$id}");
        }

        // Handle image upload
        $imageFile = $request->file('main_image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'products');
            if ($upload['success']) {
                // Delete old image if not default
                FileUploader::delete($product['main_image'] ?? null);
                $data['main_image'] = $upload['filename'];
            }
        }

        // Handle gallery images
        $existingGallery = (array)($product['gallery_images'] ?? []);
        $deleteGallery = (array)$request->post('delete_gallery_images', []);
        if (!empty($deleteGallery)) {
            foreach ($deleteGallery as $delImg) {
                if (is_string($delImg) && in_array($delImg, $existingGallery, true)) {
                    FileUploader::delete($delImg);
                    $existingGallery = array_values(array_filter($existingGallery, fn($img) => $img !== $delImg));
                }
            }
        }

        $galleryFiles = $request->file('gallery_images');
        if ($galleryFiles) {
            $newGallery = FileUploader::uploadMultiple($galleryFiles, 'products');
            if (!empty($newGallery)) {
                $existingGallery = array_merge($existingGallery, $newGallery);
            }
        }
        $data['gallery_images'] = array_values(array_unique($existingGallery));

        // Stock change tracking
        $oldStock = (int)($product['stock'] ?? 0);
        $newStock = (int)$data['stock'];
        if ($oldStock !== $newStock) {
            $diff = $newStock - $oldStock;
            $this->inventoryRepo->logMovement(
                productId: $id,
                productName: $data['name'],
                type: 'Adjustment',
                quantityChange: $diff,
                stockBefore: $oldStock,
                stockAfter: $newStock,
                reference: 'Manual Adjustment',
                notes: 'Stock updated via product edit form',
                performedBy: $admin['username'] ?? 'Admin'
            );
        }

        $this->productRepo->update($id, $data);
        $this->auditRepo->record('product_updated', $admin['username'] ?? 'Admin', "Updated product {$data['name']}");

        flash('success', "Product '{$data['name']}' updated successfully.");
        redirect('admin/products');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $product = $this->productRepo->find($id);

        if (!$product) {
            flash('error', 'Product not found.');
            redirect('admin/products');
        }

        $admin = auth_admin();
        // Remove image
        FileUploader::delete($product['main_image'] ?? null);
        if (!empty($product['gallery_images']) && is_array($product['gallery_images'])) {
            foreach ($product['gallery_images'] as $delImg) {
                FileUploader::delete($delImg);
            }
        }
        $this->productRepo->delete($id);

        $this->auditRepo->record('product_deleted', $admin['username'] ?? 'Admin', "Deleted product {$product['name']} (SKU: {$product['sku']})");

        flash('success', "Product '{$product['name']}' deleted.");
        redirect('admin/products');
    }

    public function toggleStatus(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $product = $this->productRepo->find($id);

        if ($product) {
            $newStatus = empty($product['is_active']);
            $this->productRepo->update($id, ['is_active' => $newStatus]);
            flash('success', "Product '{$product['name']}' status changed to " . ($newStatus ? 'Active' : 'Inactive') . '.');
        }

        redirect('admin/products');
    }

    private function extractProductInput(Request $request): array
    {
        $featuresRaw = (string)$request->post('features', '');
        $features = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $featuresRaw)))));

        $whatsIncludedRaw = (string)$request->post('whats_included', '');
        $whatsIncluded = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $whatsIncludedRaw)))));

        $accessMethods = (array)$request->post('access_methods', []);
        $connectivity = (array)$request->post('connectivity', []);

        // Specifications key-value pairs
        $specsKeys = (array)$request->post('spec_key', []);
        $specsValues = (array)$request->post('spec_val', []);
        $specifications = [];
        for ($i = 0; $i < count($specsKeys); $i++) {
            $k = trim($specsKeys[$i] ?? '');
            $v = trim($specsValues[$i] ?? '');
            if ($k !== '' && $v !== '') {
                $specifications[$k] = $v;
            }
        }

        $categorySlug = (string)$request->post('category_slug', '');
        $category = $this->categoryRepo->findBySlug($categorySlug);

        return [
            'name' => trim((string)$request->post('name', '')),
            'slug' => slugify((string)$request->post('slug', '') ?: (string)$request->post('name', '')),
            'sku' => strtoupper(trim((string)$request->post('sku', ''))),
            'category' => $category['name'] ?? 'Smart Locks',
            'category_slug' => $categorySlug,
            'price' => (float)$request->post('price', 0),
            'sale_price' => !empty($request->post('sale_price')) ? (float)$request->post('sale_price') : (float)$request->post('price', 0),
            'stock' => max(0, (int)$request->post('stock', 0)),
            'short_description' => trim((string)$request->post('short_description', '')),
            'full_description' => trim((string)$request->post('full_description', '')),
            'features' => $features,
            'whats_included' => $whatsIncluded,
            'access_methods' => $accessMethods,
            'connectivity' => $connectivity,
            'color' => trim((string)$request->post('color', 'Black')),
            'lock_type' => trim((string)$request->post('lock_type', 'Mortise Lock')),
            'specifications' => $specifications,
            'dimensions' => trim((string)$request->post('dimensions', '')),
            'weight' => trim((string)$request->post('weight', '')),
            'warranty' => trim((string)$request->post('warranty', '2 Years Official Warranty')),
            'installation' => trim((string)$request->post('installation', 'Free Professional Installation Available')),
            'is_featured' => (bool)$request->post('is_featured', false),
            'is_active' => (bool)$request->post('is_active', true),
            'rating' => (float)$request->post('rating', 4.8),
            'reviews_count' => (int)$request->post('reviews_count', 0),
        ];
    }
}

