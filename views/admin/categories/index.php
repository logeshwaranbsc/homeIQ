<?php
declare(strict_types=1);

/**
 * @var array $categories
 * @var array $categoryCounts
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Lock Categories Management</h4>
    <small class="text-muted">Digital door locks and accessories category structure &amp; page hero banners.</small>
  </div>
  <a href="<?= url('admin/categories/create') ?>" class="btn btn-sm btn-dark">
    <i class="bi bi-plus-lg me-1"></i> Add Category
  </a>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Category Name</th>
          <th>Hero Banner (Desktop / Mobile)</th>
          <th>Slug</th>
          <th>Description</th>
          <th class="text-center">Products</th>
          <th class="text-center">Display Order</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="" style="width: 36px; height: 36px; object-fit: contain;" class="img-thumbnail">
                <strong><?= e($cat['name']) ?></strong>
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center gap-1">
                <?php if (!empty($cat['banner_image'])): ?>
                  <div class="position-relative" title="Desktop Banner">
                    <img src="<?= upload_url($cat['banner_image']) ?>" alt="Desktop Banner" style="width: 58px; height: 28px; object-fit: cover;" class="rounded border">
                    <span class="badge bg-dark position-absolute bottom-0 end-0 p-1 lh-1" style="font-size: 0.55rem;" title="Desktop"><i class="bi bi-display"></i></span>
                  </div>
                <?php endif; ?>
                <?php if (!empty($cat['banner_mobile_image'])): ?>
                  <div class="position-relative" title="Mobile Screen Banner">
                    <img src="<?= upload_url($cat['banner_mobile_image']) ?>" alt="Mobile Banner" style="width: 44px; height: 24px; object-fit: cover;" class="rounded border border-warning">
                    <span class="badge bg-warning text-dark position-absolute bottom-0 end-0 p-1 lh-1" style="font-size: 0.55rem;" title="Mobile"><i class="bi bi-phone"></i></span>
                  </div>
                <?php endif; ?>
                <?php if (empty($cat['banner_image']) && empty($cat['banner_mobile_image'])): ?>
                  <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">Default Banner</span>
                <?php endif; ?>
              </div>
            </td>
            <td><code><?= e($cat['slug']) ?></code></td>
            <td class="text-muted small" style="max-width: 200px;"><?= e($cat['description']) ?></td>
            <td class="text-center">
              <span class="badge bg-light text-dark border"><?= $categoryCounts[$cat['slug']] ?? 0 ?></span>
            </td>
            <td class="text-center"><?= $cat['display_order'] ?? 0 ?></td>
            <td>
              <span class="badge-status <?= !empty($cat['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                <?= !empty($cat['is_active']) ? 'Active' : 'Hidden' ?>
              </span>
            </td>
            <td class="text-end">
              <a href="<?= url('admin/categories/edit/' . $cat['id']) ?>" class="btn-action-icon me-1" title="Edit Category &amp; Hero Banners">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="<?= url('admin/categories/delete/' . $cat['id']) ?>" class="btn-action-icon danger" title="Delete Category" data-confirm="Are you sure you want to delete '<?= e($cat['name']) ?>'?">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
