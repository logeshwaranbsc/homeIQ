<?php
declare(strict_types=1);

/** @var array|null $banner */
$isEdit = !empty($banner);
$action = $isEdit ? url('admin/banners/update/' . $banner['id']) : url('admin/banners/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Hero Banner Slide' : 'Create Hero Slide' ?></h4>
    <small class="text-muted">Home page hero carousel slide configuration.</small>
  </div>
  <a href="<?= url('admin/banners') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Slides
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-form-card shadow-sm">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Headline (Supports HTML like &lt;br/&gt;) *</label>
          <input type="text" name="headline" class="form-control" required placeholder="e.g. Advanced Security.<br/>Elegant Design." value="<?= e($banner['headline'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Subheading *</label>
          <input type="text" name="subheading" class="form-control" placeholder="e.g. Give your home the smart upgrade it deserves." value="<?= e($banner['subheading'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Supporting Text / Benefits Line</label>
          <input type="text" name="supporting_text" class="form-control" placeholder="e.g. Free Installation | Technical Support | Warranty Protection" value="<?= e($banner['supporting_text'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Offer Badge Pill</label>
            <input type="text" name="offer_badge" class="form-control" placeholder="e.g. UP TO 40% OFF or STARTS FROM ₹9,499" value="<?= e($banner['offer_badge'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="<?= e($banner['display_order'] ?? 1) ?>">
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">CTA Button Label</label>
            <input type="text" name="cta_label" class="form-control" value="<?= e($banner['cta_label'] ?? 'SHOP SMART LOCKS') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">CTA Target URL</label>
            <input type="text" name="cta_url" class="form-control" value="<?= e($banner['cta_url'] ?? 'shop') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Slide Visual Banner Graphic</label>
          <?php if (!empty($banner['image'])): ?>
            <div class="mb-2 p-2 bg-light border text-center rounded">
              <img src="<?= upload_url($banner['image']) ?>" alt="" style="max-height: 100px; width: 100%; object-fit: cover;">
            </div>
          <?php endif; ?>
          <input type="file" name="image" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp,image/svg+xml">
        </div>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="bannerActiveCheck" <?= !empty($banner['is_active'] ?? true) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="bannerActiveCheck">Active Slide on Homepage Carousel</label>
        </div>

        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Save Hero Slide
        </button>
      </form>
    </div>
  </div>
</div>

