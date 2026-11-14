<?php
declare(strict_types=1);

/** @var array|null $category */
$isEdit = !empty($category);
$action = $isEdit ? url('admin/categories/update/' . $category['id']) : url('admin/categories/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Category: ' . e($category['name']) : 'Create Category' ?></h4>
    <small class="text-muted">Digital door locks categorization and page hero banner settings.</small>
  </div>
  <a href="<?= url('admin/categories') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Categories
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="admin-form-card shadow-sm p-4 bg-white rounded-3 border">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Basic Information -->
        <div class="row g-3 mb-4">
          <div class="col-md-7">
            <label class="form-label fw-bold">Category Name *</label>
            <input type="text" name="name" id="inputName" class="form-control" required placeholder="e.g. Smart Locks" value="<?= e($category['name'] ?? '') ?>">
          </div>
          <div class="col-md-5">
            <label class="form-label fw-bold">URL Slug</label>
            <input type="text" name="slug" id="inputSlug" class="form-control" placeholder="auto-generated-if-blank" value="<?= e($category['slug'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" rows="2" class="form-control" placeholder="Brief summary of lock features in this category..."><?= e($category['description'] ?? '') ?></textarea>
          </div>
        </div>

        <!-- Category Thumbnail / Icon (For Homepage "Shop by Category" Grid) -->
        <div class="card border mb-4 bg-light p-3 rounded-3 shadow-none">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <label class="form-label fw-bold text-dark m-0 d-flex align-items-center gap-1">
              <i class="bi bi-grid text-primary"></i> Category Card Thumbnail / Icon
            </label>
            <span class="badge bg-secondary-subtle text-secondary small">Homepage "Shop by Category" Card</span>
          </div>
          <p class="text-muted small mb-3">Square or vertical PNG/WebP lock graphic displayed on homepage category cards.</p>

          <div class="row align-items-center g-3">
            <?php if (!empty($category['image'])): ?>
              <div class="col-auto">
                <div class="p-2 bg-white border rounded text-center" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
                  <img src="<?= upload_url($category['image']) ?>" alt="" style="max-height: 75px; max-width: 75px; object-fit: contain;">
                </div>
              </div>
            <?php endif; ?>
            <div class="col">
              <input type="file" name="image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
              <small class="text-muted mt-1 d-block">PNG, WebP, JPG, or SVG (transparent background recommended).</small>
            </div>
          </div>
        </div>

        <!-- ==========================================================================
             CATEGORY FULL-IMAGE HERO BANNER (Desktop & Mobile Responsive Art Direction)
             ========================================================================== -->
        <div class="card border mb-4 bg-light p-3 rounded-3 shadow-none">
          <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2 flex-wrap gap-2">
            <div>
              <h6 class="fw-bold m-0 text-dark"><i class="bi bi-images me-2 text-primary"></i> Category Page Hero Banners (Full Image Hero Section)</h6>
              <small class="text-muted">Displays as a full-image hero banner on the category page (just like the homepage hero banner). Upload dedicated artwork for desktop and mobile screens.</small>
            </div>
            <span class="badge bg-white text-dark border"><i class="bi bi-phone me-1 text-primary"></i> Responsive Art Direction</span>
          </div>

          <div class="row g-3">
            <!-- Desktop Screen Hero Banner -->
            <!-- Desktop Screen Hero Banner -->
            <div class="col-md-7">
              <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-bold text-dark m-0 d-flex align-items-center gap-1">
                      <i class="bi bi-display text-primary"></i> Desktop Screen Hero Banner
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

                  <?php if (!empty($category['banner_image'])): ?>
                    <div class="mb-3 position-relative rounded-2 overflow-hidden border shadow-xs" style="width: 100%; aspect-ratio: 16 / 5; background: #07080a;">
                      <img src="<?= upload_url($category['banner_image']) ?>" alt="Current desktop banner preview" style="width: 100%; height: 100%; object-fit: contain;">
                      <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 start-0 m-2">Active Desktop Banner (16:5 Preview)</span>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="delete_banner_image" value="1" id="deleteBannerImg">
                      <label class="form-check-label text-danger small" for="deleteBannerImg">
                        <i class="bi bi-trash me-1"></i> Remove desktop banner
                      </label>
                    </div>
                  <?php else: ?>
                    <div class="mb-3 p-3 text-center rounded-2 border border-dashed bg-light text-muted small" style="min-height: 90px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                      <i class="bi bi-image fs-3 text-muted mb-1 opacity-50"></i>
                      <span class="fw-semibold">No custom desktop banner uploaded</span>
                      <small class="opacity-75">Default storefront banner is displayed</small>
                    </div>
                  <?php endif; ?>
                </div>

                <div>
                  <input type="file" name="banner_image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted mt-1 d-block">PNG, JPG, WebP, SVG. Recommended: <strong>1920×600 px</strong> (exact 16:5 ratio).</small>
                </div>
              </div>
            </div>

            <!-- Mobile Screen Hero Banner -->
            <div class="col-md-5">
              <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-bold text-dark m-0 d-flex align-items-center gap-1">
                      <i class="bi bi-phone text-warning"></i> Mobile Screen Hero Banner
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
                      <span class="text-secondary">Mobile viewports (&le; 768px)</span>
                    </div>
                  </div>

                  <?php if (!empty($category['banner_mobile_image'])): ?>
                    <div class="mb-2 position-relative rounded-3 overflow-hidden border shadow-xs mx-auto" style="width: 100%; max-width: 280px; aspect-ratio: 2.5 / 1; background: #07080a;">
                      <img src="<?= upload_url($category['banner_mobile_image']) ?>" alt="Current mobile banner preview" style="width: 100%; height: 100%; object-fit: contain;">
                      <span class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 start-50 translate-middle-x mb-1" style="font-size: 0.65rem;">Active Mobile Banner (2.5:1 Preview)</span>
                    </div>
                    <div class="form-check mb-2 text-center">
                      <input class="form-check-input float-none me-1" type="checkbox" name="delete_banner_mobile_image" value="1" id="deleteMobileBannerImg">
                      <label class="form-check-label text-danger small" for="deleteMobileBannerImg">
                        <i class="bi bi-trash me-1"></i> Remove mobile banner
                      </label>
                    </div>
                  <?php else: ?>
                    <div class="mb-3 p-3 text-center rounded-2 border border-dashed bg-light text-muted small" style="min-height: 90px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                      <i class="bi bi-phone fs-3 text-muted mb-1 opacity-50"></i>
                      <span class="fw-semibold">No separate mobile banner</span>
                      <small class="opacity-75">Desktop banner (1920×600) is used as responsive 16:5 fallback</small>
                    </div>
                  <?php endif; ?>
                </div>

                <div>
                  <input type="file" name="banner_mobile_image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted mt-1 d-block">Optional. Recommended: <strong>1024×409 px</strong> (2.5:1 ratio). PNG, JPG, WebP.</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label fw-bold">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="<?= e($category['display_order'] ?? 1) ?>">
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="catActiveCheck" <?= !empty($category['is_active'] ?? true) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold" for="catActiveCheck">Visible on Storefront</label>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
            <i class="bi bi-save me-1"></i> Save Category
          </button>
          <a href="<?= url('admin/categories') ?>" class="btn btn-outline-secondary px-4 py-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
