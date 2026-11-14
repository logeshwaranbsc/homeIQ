<?php
declare(strict_types=1);

/** @var array|null $banner */
$isEdit = !empty($banner);
$action = $isEdit ? url('admin/banners/update/' . $banner['id']) : url('admin/banners/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Hero Banner Slide' : 'Create Hero Slide' ?></h4>
    <small class="text-muted">Upload full graphic banners (desktop & mobile) with all artwork and content embedded in the image, and configure the CTA action link.</small>
  </div>
  <a href="<?= url('admin/banners') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Slides
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="admin-form-card shadow-sm p-4 bg-white rounded-3 border">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Slide Reference Name / Title -->
        <div class="mb-4">
          <label class="form-label fw-bold">Banner Reference Title *</label>
          <input type="text" name="title" class="form-control" placeholder="e.g. Smart Home Upgrade / AI Face Recognition Launch" value="<?= e(!empty($banner['title']) ? $banner['title'] : ($banner['headline'] ?? '')) ?>" required>
          <small class="text-muted">An internal title to identify this banner slide in the admin management list.</small>
        </div>

        <!-- Slide Banners (Desktop & Mobile Responsive Art Direction) -->
        <div class="card border mb-4 bg-light p-3 rounded-3 shadow-none">
          <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2 flex-wrap gap-2">
            <div>
              <h6 class="fw-bold m-0 text-dark"><i class="bi bi-images me-2 text-primary"></i> Hero Slide Banners (Graphics with Embedded Content)</h6>
              <small class="text-muted">All typography, offers, and branding are designed into your images. Upload separate artwork for desktop and mobile.</small>
            </div>
            <div class="d-flex gap-2 align-items-center">
              <span class="badge bg-primary text-white"><i class="bi bi-aspect-ratio me-1"></i> Desktop Ratio 16:5 (3.2:1)</span>
              <span class="badge bg-warning text-dark"><i class="bi bi-phone me-1"></i> Mobile Ratio 2.5:1</span>
            </div>
          </div>

          <!-- Dimension & Ratio Specification Banner -->
          <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-dark py-2 px-3 mb-3 rounded-2 small d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
            <div>
              <strong>Storefront Image Specification:</strong> Upload desktop banner in <strong>1920 × 600 px (exact 16:5 / 3.2:1 ratio)</strong>. The storefront UI locks this aspect ratio responsively across all screens (1920px, 1440px, 1366px, 1200px) so your headlines, buttons, and products fit with <strong>zero cropping</strong>.
            </div>
          </div>

          <div class="row g-3">
            <!-- Desktop Screen Banner -->
            <div class="col-md-7">
              <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-bold text-dark m-0 d-flex align-items-center gap-1">
                      <i class="bi bi-display text-primary"></i> Desktop Screen Banner *
                    </label>
                    <span class="badge bg-primary text-white fw-semibold small">1920 × 600 px (16:5 / 3.2:1)</span>
                  </div>
                  <div class="p-2 mb-2 bg-light rounded border small">
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-muted">Required Dimensions:</span>
                      <strong class="text-dark">1920 × 600 px</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-muted">Aspect Ratio:</span>
                      <strong class="text-primary font-monospace">16:5 (3.2:1 Landscape)</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Display Target:</span>
                      <span class="text-secondary">Desktops, Laptops &amp; Tablets (&gt; 768px)</span>
                    </div>
                  </div>

                  <?php if (!empty($banner['image'])): ?>
                    <div class="mb-3 position-relative rounded-2 overflow-hidden border shadow-xs" style="width: 100%; aspect-ratio: 16 / 5; background: #07080a;">
                      <img src="<?= upload_url($banner['image']) ?>" alt="Current desktop banner preview" style="width: 100%; height: 100%; object-fit: contain;">
                      <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 start-0 m-2">Current Desktop Banner (16:5 Preview)</span>
                    </div>
                  <?php endif; ?>
                </div>

                <div>
                  <input type="file" name="image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted mt-1 d-block">PNG, JPG, WebP, SVG. Recommended: <strong>1920×600 px</strong> (exact 16:5 ratio).</small>
                </div>
              </div>
            </div>

            <!-- Mobile Screen Banner -->
            <div class="col-md-5">
              <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-bold text-dark m-0 d-flex align-items-center gap-1">
                      <i class="bi bi-phone text-warning"></i> Mobile Screen Banner
                    </label>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold small">1024 × 409 px (2.5:1)</span>
                  </div>
                  <div class="p-2 mb-2 bg-light rounded border small">
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-muted">Recommended Size:</span>
                      <strong class="text-dark">1024 × 409 px or 1920 × 768 px</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-muted">Aspect Ratio:</span>
                      <strong class="text-warning-emphasis font-monospace">2.5:1 (Horizontal Card)</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Display Target:</span>
                      <span class="text-secondary">Mobile screens (&le; 768px)</span>
                    </div>
                  </div>

                  <?php if (!empty($banner['mobile_image'])): ?>
                    <div class="mb-2 position-relative rounded-3 overflow-hidden border shadow-xs mx-auto" style="width: 100%; max-width: 280px; aspect-ratio: 2.5 / 1; background: #07080a;">
                      <img src="<?= upload_url($banner['mobile_image']) ?>" alt="Current mobile banner preview" style="width: 100%; height: 100%; object-fit: contain;">
                      <span class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 start-50 translate-middle-x mb-1" style="font-size: 0.65rem;">Active Mobile Card (2.5:1 Preview)</span>
                    </div>
                    <div class="form-check mb-2 text-center">
                      <input class="form-check-input float-none me-1" type="checkbox" name="delete_mobile_image" value="1" id="deleteMobileImg">
                      <label class="form-check-label text-danger small" for="deleteMobileImg">
                        <i class="bi bi-trash me-1"></i> Remove mobile banner
                      </label>
                    </div>
                  <?php else: ?>
                    <div class="mb-3 p-3 text-center rounded-2 border border-dashed bg-light text-muted small" style="min-height: 100px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                      <i class="bi bi-phone fs-2 text-muted mb-1 opacity-50"></i>
                      <span class="fw-semibold">No separate mobile banner</span>
                      <small class="opacity-75">Desktop banner (1920×600) is used as responsive 16:5 fallback</small>
                    </div>
                  <?php endif; ?>
                </div>

                <div>
                  <input type="file" name="mobile_image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted mt-1 d-block">Optional. Recommended: <strong>1024×409 px</strong> (2.5:1 horizontal ratio). PNG, JPG, WebP.</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Optional Banner Target Link -->
        <div class="card border mb-4 p-3 rounded-3 shadow-none">
          <h6 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2 border-bottom pb-2">
            <i class="bi bi-link-45deg text-primary"></i> Banner Destination Link (Optional)
          </h6>

          <div>
            <label class="form-label fw-bold">Destination URL / Page Link</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
              <input type="text" name="cta_url" class="form-control font-monospace" placeholder="e.g. shop or product/homeiq-ai-face-lock" value="<?= e($banner['cta_url'] ?? 'shop') ?>">
            </div>
            <small class="text-muted">Optional. Clicking anywhere on the banner will open this page URL (e.g. <code>shop</code> or a product link). Leave blank if you don't want the banner to be a link.</small>
          </div>
        </div>

        <div class="row g-3 mb-4 align-items-center pt-3 border-top">
          <div class="col-md-6">
            <label class="form-label fw-bold">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="<?= e($banner['display_order'] ?? 1) ?>" min="1" max="99">
          </div>
          <div class="col-md-6">
            <div class="form-check form-switch mt-md-4">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="bannerActiveCheck" <?= !empty($banner['is_active'] ?? true) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold" for="bannerActiveCheck">Active Slide on Homepage Carousel</label>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
            <i class="bi bi-save me-1"></i> Save Hero Slide
          </button>
          <a href="<?= url('admin/banners') ?>" class="btn btn-outline-secondary px-4 py-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
