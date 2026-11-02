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
          <th>Preview</th>
          <th>Headline & Subheading</th>
          <th>Offer Badge</th>
          <th>CTA Button</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($banners as $b): ?>
          <tr>
            <td><strong>#<?= $b['display_order'] ?? 1 ?></strong></td>
            <td>
              <img src="<?= upload_url($b['image'] ?? '') ?>" alt="" style="width: 70px; height: 40px; object-fit: cover;" class="rounded border">
            </td>
            <td>
              <strong class="d-block text-dark"><?= $b['headline'] ?></strong>
              <small class="text-muted"><?= e($b['subheading'] ?? '') ?></small>
            </td>
            <td>
              <?php if (!empty($b['offer_badge'])): ?>
                <span class="badge bg-info text-dark"><?= e($b['offer_badge']) ?></span>
              <?php else: ?>
                <span class="text-muted small">—</span>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge bg-light text-dark border"><?= e($b['cta_label'] ?? 'SHOP') ?></span>
              <div class="text-muted small" style="font-size: 0.7rem;"><?= e($b['cta_url'] ?? 'shop') ?></div>
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

