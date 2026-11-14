<?php
declare(strict_types=1);

/** @var array $banners */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Hero Banner Slides Management</h4>
    <small class="text-muted">Configure storefront hero carousel banners, headlines, offer badges, and CTA links.</small>
  </div>
  <a href="<?= url('admin/banners/create') ?>" class="btn btn-sm btn-dark">
    <i class="bi bi-plus-lg me-1"></i> Add Hero Slide
  </a>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Banners (Desktop / Mobile)</th>
          <th>Banner Reference</th>
          <th>Destination Link</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($banners as $b): ?>
          <tr>
            <td><strong>#<?= $b['display_order'] ?? 1 ?></strong></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <?php if (!empty($b['image'])): ?>
                  <div class="position-relative" title="Desktop Banner: <?= e($b['image']) ?>">
                    <img src="<?= upload_url($b['image']) ?>" alt="Desktop Banner" style="width: 68px; height: 38px; object-fit: cover;" class="rounded border">
                    <span class="badge bg-dark position-absolute bottom-0 end-0 p-1 lh-1" style="font-size: 0.58rem;" title="Desktop Banner"><i class="bi bi-display"></i></span>
                  </div>
                <?php endif; ?>

                <?php if (!empty($b['mobile_image'])): ?>
                  <div class="position-relative" title="Mobile Screen Banner: <?= e($b['mobile_image']) ?>">
                    <img src="<?= upload_url($b['mobile_image']) ?>" alt="Mobile Banner" style="width: 58px; height: 24px; object-fit: cover;" class="rounded border border-warning">
                    <span class="badge bg-warning text-dark position-absolute bottom-0 end-0 p-1 lh-1" style="font-size: 0.58rem;" title="Mobile Screen Banner"><i class="bi bi-phone"></i></span>
                  </div>
                <?php else: ?>
                  <span class="badge bg-light text-muted border py-1 px-1 text-center" style="font-size: 0.65rem; width: 42px; height: 24px; display: inline-flex; align-items: center; justify-content: center;" title="No dedicated mobile banner (uses desktop banner on mobile)">
                    <i class="bi bi-phone opacity-50"></i>
                  </span>
                <?php endif; ?>
              </div>
            </td>
            <td>
              <strong class="d-block text-dark"><?= e(!empty($b['title']) ? $b['title'] : ($b['headline'] ?? 'Hero Slide')) ?></strong>
              <small class="text-muted font-monospace" style="font-size: 0.72rem;">ID: <?= e($b['id']) ?></small>
            </td>
            <td>
              <?php if (!empty($b['cta_url'])): ?>
                <a href="<?= url($b['cta_url']) ?>" target="_blank" class="badge bg-light text-dark border text-decoration-none font-monospace">
                  <i class="bi bi-link-45deg me-1"></i><?= e($b['cta_url']) ?>
                </a>
              <?php else: ?>
                <span class="text-muted small">None (Static Display)</span>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge-status <?= !empty($b['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                <?= !empty($b['is_active']) ? 'Active' : 'Disabled' ?>
              </span>
            </td>
            <td class="text-end">
              <a href="<?= url('admin/banners/edit/' . $b['id']) ?>" class="btn-action-icon me-1" title="Edit Banner">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="<?= url('admin/banners/delete/' . $b['id']) ?>" class="btn-action-icon danger" title="Delete Banner" data-confirm="Are you sure you want to delete this hero slide?">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

