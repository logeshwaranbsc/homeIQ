<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

class AdminCategoryController
{
    private CategoryRepository $categoryRepo;
    private ProductRepository $productRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
        $this->productRepo = new ProductRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $categories = $this->categoryRepo->all();
        $products = $this->productRepo->all();

        // Calculate product count per category
        $categoryCounts = [];
        foreach ($products as $p) {
            $catSlug = $p['category_slug'] ?? '';
            $categoryCounts[$catSlug] = ($categoryCounts[$catSlug] ?? 0) + 1;
        }

        echo View::render('admin/categories/index', [
            'title' => 'Categories | HOMEIQ Admin',
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
        ], 'admin');
    }

    public function create(): void
    {
        echo View::render('admin/categories/form', [
            'title' => 'Add Category | HOMEIQ Admin',
            'category' => null,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $name = trim((string)$request->post('name', ''));
        $slug = slugify((string)$request->post('slug', '') ?: $name);
        $description = trim((string)$request->post('description', ''));
        $displayOrder = (int)$request->post('display_order', 0);
        $isActive = (bool)$request->post('is_active', true);

        if (empty($name)) {
            flash('error', 'Category name is required.');
            redirect('admin/categories/create');
        }

        if ($this->categoryRepo->findBySlug($slug)) {
            flash('error', 'A category with this slug already exists.');
            redirect('admin/categories/create');
        }

        $imagePath = null;
        $imageFile = $request->file('image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'categories');
            if ($upload['success']) {
                $imagePath = $upload['filename'];
            }
        }

        // Desktop hero banner upload
        $bannerPath = null;
        $bannerFile = $request->file('banner_image');
        if ($bannerFile) {
            $uploadBanner = FileUploader::upload($bannerFile, 'banners');
            if ($uploadBanner['success']) {
                $bannerPath = $uploadBanner['filename'];
            }
        }

        // Mobile screen hero banner upload
        $bannerMobilePath = null;
        $bannerMobileFile = $request->file('banner_mobile_image');
        if ($bannerMobileFile) {
            $uploadBannerMobile = FileUploader::upload($bannerMobileFile, 'banners');
            if ($uploadBannerMobile['success']) {
                $bannerMobilePath = $uploadBannerMobile['filename'];
            }
        }

        $created = $this->categoryRepo->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
            'banner_image' => $bannerPath,
            'banner_mobile_image' => $bannerMobilePath,
            'display_order' => $displayOrder,
            'is_active' => $isActive,
        ]);

        $admin = auth_admin();
        $this->auditRepo->record('category_created', $admin['username'] ?? 'Admin', "Created category {$name}");

        flash('success', "Category '{$name}' created successfully.");
        redirect('admin/categories');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $category = $this->categoryRepo->find($id);

        if (!$category) {
            flash('error', 'Category not found.');
            redirect('admin/categories');
        }

        echo View::render('admin/categories/form', [
            'title' => 'Edit Category ' . $category['name'] . ' | HOMEIQ Admin',
            'category' => $category,
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $category = $this->categoryRepo->find($id);

        if (!$category) {
            flash('error', 'Category not found.');
            redirect('admin/categories');
        }

        $name = trim((string)$request->post('name', ''));
        $slug = slugify((string)$request->post('slug', '') ?: $name);
        $description = trim((string)$request->post('description', ''));
        $displayOrder = (int)$request->post('display_order', 0);
        $isActive = (bool)$request->post('is_active', true);

        // Category thumbnail / icon
        $imagePath = $category['image'] ?? null;
        if ($request->post('delete_image')) {
            FileUploader::delete($category['image'] ?? null);
            $imagePath = null;
        }
        $imageFile = $request->file('image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'categories');
            if ($upload['success']) {
                FileUploader::delete($category['image'] ?? null);
                $imagePath = $upload['filename'];
            }
        }

        // Desktop category hero banner
        $bannerPath = $category['banner_image'] ?? null;
        if ($request->post('delete_banner_image')) {
            FileUploader::delete($category['banner_image'] ?? null);
            $bannerPath = null;
        }
        $bannerFile = $request->file('banner_image');
        if ($bannerFile) {
            $uploadBanner = FileUploader::upload($bannerFile, 'banners');
            if ($uploadBanner['success']) {
                FileUploader::delete($category['banner_image'] ?? null);
                $bannerPath = $uploadBanner['filename'];
            }
        }

        // Mobile screen category hero banner
        $bannerMobilePath = $category['banner_mobile_image'] ?? null;
        if ($request->post('delete_banner_mobile_image')) {
            FileUploader::delete($category['banner_mobile_image'] ?? null);
            $bannerMobilePath = null;
        }
        $bannerMobileFile = $request->file('banner_mobile_image');
        if ($bannerMobileFile) {
            $uploadBannerMobile = FileUploader::upload($bannerMobileFile, 'banners');
            if ($uploadBannerMobile['success']) {
                FileUploader::delete($category['banner_mobile_image'] ?? null);
                $bannerMobilePath = $uploadBannerMobile['filename'];
            }
        }

        $this->categoryRepo->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
            'banner_image' => $bannerPath,
            'banner_mobile_image' => $bannerMobilePath,
            'display_order' => $displayOrder,
            'is_active' => $isActive,
        ]);

        $admin = auth_admin();
        $this->auditRepo->record('category_updated', $admin['username'] ?? 'Admin', "Updated category {$name}");

        flash('success', "Category '{$name}' updated successfully.");
        redirect('admin/categories');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $category = $this->categoryRepo->find($id);

        if (!$category) {
            flash('error', 'Category not found.');
            redirect('admin/categories');
        }

        // Check if products exist in category
        $products = $this->productRepo->filter(fn($p) => ($p['category_slug'] ?? '') === $category['slug']);
        if (!empty($products)) {
            flash('error', "Cannot delete category '{$category['name']}' because it still contains " . count($products) . " product(s). Reassign products first.");
            redirect('admin/categories');
            return;
        }

        FileUploader::delete($category['image'] ?? null);
        FileUploader::delete($category['banner_image'] ?? null);
        FileUploader::delete($category['banner_mobile_image'] ?? null);
        $this->categoryRepo->delete($id);

        $admin = auth_admin();
        $this->auditRepo->record('category_deleted', $admin['username'] ?? 'Admin', "Deleted category {$category['name']}");

        flash('success', "Category '{$category['name']}' deleted.");
        redirect('admin/categories');
    }
}
