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

        $created = $this->categoryRepo->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
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

        $imagePath = $category['image'] ?? null;
        $imageFile = $request->file('image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'categories');
            if ($upload['success']) {
                FileUploader::delete($category['image'] ?? null);
                $imagePath = $upload['filename'];
            }
        }

        $this->categoryRepo->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
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
        $this->categoryRepo->delete($id);

        $admin = auth_admin();
        $this->auditRepo->record('category_deleted', $admin['username'] ?? 'Admin', "Deleted category {$category['name']}");

        flash('success', "Category '{$category['name']}' deleted.");
        redirect('admin/categories');
    }
}

