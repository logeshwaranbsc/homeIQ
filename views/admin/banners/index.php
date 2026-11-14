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

<!-- Banner Dimension & Aspect Ratio Reference Guide -->
<div class="card border mb-4 p-3 rounded-3 shadow-none bg-white">
  <div class="d-flex align-items-start gap-3">
    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
      <i class="bi bi-aspect-ratio fs-4"></i>
    </div>
    <div class="flex-grow-1">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <div>
          <h6 class="fw-bold m-0 text-dark">Hero Banner Image Dimensions &amp; Aspect Ratio Specifications</h6>
          <small class="text-muted">The storefront displays hero slide images in locked proportional aspect ratios so that no content or products are cut off.</small>
        </div>
        <span class="badge bg-light text-secondary border">Storefront Responsive UI Standard</span>
      </div>
      <div class="row g-2">
        <div class="col-md-6">
          <div class="p-2 bg-light rounded border d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small"><i class="bi bi-display text-primary me-1"></i> Desktop Screen Banner</div>
              <div class="text-muted" style="font-size: 0.78rem;">Size: <strong class="text-dark">1920 × 600 px</strong> &bull; Ratio: <strong class="text-primary font-monospace">16:5 (3.2:1 Landscape)</strong></div>
            </div>
            <span class="badge bg-primary text-white">Full Bleed</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-2 bg-light rounded border d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small"><i class="bi bi-phone text-warning me-1"></i> Mobile Screen Banner (Optional)</div>
              <div class="text-muted" style="font-size: 0.78rem;">Size: <strong class="text-dark">1024 × 409 px</strong> &bull; Ratio: <strong class="text-warning-emphasis font-monospace">2.5:1 (Card)</strong></div>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis border">Optional Fallback</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-light border d-flex align-items-center justify-content-between mb-4 py-2 px-3 flex-wrap gap-2">
  <div class="d-flex align-items-center gap-2">
    <i class="bi bi-info-circle text-primary fs-5"></i>
    <span class="small text-muted">Looking for Category Page hero banners? You can configure dedicated desktop and mobile hero banners directly under <strong>Lock Categories</strong>.</span>
  </div>
  <a href="<?= url('admin/categories') ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-grid me-1"></i>Category Banners</a>
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
