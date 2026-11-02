<?php
declare(strict_types=1);

/** @var array|null $category */
$isEdit = !empty($category);
$action = $isEdit ? url('admin/categories/update/' . $category['id']) : url('admin/categories/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Category: ' . e($category['name']) : 'Create Category' ?></h4>
    <small class="text-muted">Digital door locks categorization.</small>
  </div>
  <a href="<?= url('admin/categories') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Categories
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="admin-form-card shadow-sm">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Category Name *</label>
          <input type="text" name="name" id="inputName" class="form-control" required placeholder="e.g. AI Face Recognition Smart Locks" value="<?= e($category['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">URL Slug</label>
          <input type="text" name="slug" id="inputSlug" class="form-control" placeholder="auto-generated-if-blank" value="<?= e($category['slug'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" rows="3" class="form-control" placeholder="Brief summary of lock features in this category..."><?= e($category['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Category Image / Vector</label>
          <?php if (!empty($category['image'])): ?>
            <div class="mb-2 p-2 bg-light border text-center rounded">
              <img src="<?= upload_url($category['image']) ?>" alt="" style="max-height: 80px; object-fit: contain;">
            </div>
          <?php endif; ?>
          <input type="file" name="image" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp,image/svg+xml">
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="<?= e($category['display_order'] ?? 1) ?>">
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="catActiveCheck" <?= !empty($category['is_active'] ?? true) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold" for="catActiveCheck">Visible on Storefront</label>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Save Category
        </button>
      </form>
    </div>
  </div>
</div>

