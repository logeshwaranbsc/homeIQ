<?php
declare(strict_types=1);

/** @var array|null $banner */
$isEdit = !empty($banner);
$action = $isEdit ? url('admin/banners/update/' . $banner['id']) : url('admin/banners/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Hero Banner Slide' : 'Create Hero Slide' ?></h4>
    <small class="text-muted">Configure full-background image, typography, buttons, and layout for the storefront hero section.</small>
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

        <!-- Slide Background Image (Full Width) -->
        <div class="mb-4 p-3 bg-light rounded-3 border">
          <label class="form-label fw-bold text-dark d-flex align-items-center justify-content-between">
            <span><i class="bi bi-image me-1 text-primary"></i> Whole Background Image (Full-Bleed 16:9 / 1600×650+ recommended)</span>
            <small class="text-muted font-monospace">PNG, JPG, WebP, SVG</small>
          </label>

          <?php if (!empty($banner['image'])): ?>
            <div class="mb-3 position-relative rounded-3 overflow-hidden border shadow-xs" style="max-height: 220px; background: #07080a;">
              <img src="<?= upload_url($banner['image']) ?>" alt="Current background preview" style="width: 100%; height: 220px; object-fit: cover;">
              <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 start-0 m-2">Current Background</span>
            </div>
          <?php endif; ?>

          <input type="file" name="image" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
          <small class="text-muted mt-1 d-block">Upload a high-resolution banner image. This image will expand across the entire whole background of the hero section.</small>
        </div>

        <!-- Typography & Text Overlay Settings -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Text Alignment / Layout</label>
            <select name="text_position" class="form-select">
              <option value="left" <?= ($banner['text_position'] ?? 'left') === 'left' ? 'selected' : '' ?>>Left Aligned (Recommended — hardware visible on right)</option>
              <option value="center" <?= ($banner['text_position'] ?? 'left') === 'center' ? 'selected' : '' ?>>Center Aligned</option>
              <option value="right" <?= ($banner['text_position'] ?? 'left') === 'right' ? 'selected' : '' ?>>Right Aligned</option>
              <option value="none" <?= ($banner['text_position'] ?? 'left') === 'none' ? 'selected' : '' ?>>Hide Text Overlay (Show Full Image Only)</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-bold">Background Overlay Tint / Scrim</label>
            <select name="overlay_style" class="form-select">
              <option value="gradient" <?= ($banner['overlay_style'] ?? 'gradient') === 'gradient' ? 'selected' : '' ?>>Left-to-Right Luxury Gradient (Text Pop + Clear Image)</option>
              <option value="center" <?= ($banner['overlay_style'] ?? 'gradient') === 'center' ? 'selected' : '' ?>>Center Spotlight Vignette</option>
              <option value="subtle" <?= ($banner['overlay_style'] ?? 'gradient') === 'subtle' ? 'selected' : '' ?>>Subtle Soft Tint (Allows more image through)</option>
              <option value="dark" <?= ($banner['overlay_style'] ?? 'gradient') === 'dark' ? 'selected' : '' ?>>Dark Contrast Overlay</option>
              <option value="none" <?= ($banner['overlay_style'] ?? 'gradient') === 'none' ? 'selected' : '' ?>>None (Completely Raw Image)</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Offer Badge Pill (Optional)</label>
          <input type="text" name="offer_badge" class="form-control" placeholder="e.g. FREE SHIPPING or UP TO 40% OFF or STARTS FROM ₹9,499" value="<?= e($banner['offer_badge'] ?? '') ?>">
          <small class="text-muted">Small rounded pill tag displayed above the main headline.</small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Headline (Supports HTML like &lt;br/&gt; or &lt;span class=&quot;text-gradient-cyan&quot;&gt;) *</label>
          <input type="text" name="headline" class="form-control" placeholder="e.g. Your Smart Home Upgrade Starts Here." value="<?= e($banner['headline'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Subheading</label>
          <input type="text" name="subheading" class="form-control" placeholder="e.g. Exclusive deals on HOMEIQ smart locks." value="<?= e($banner['subheading'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Supporting Benefits / Warranty Line</label>
          <input type="text" name="supporting_text" class="form-control" placeholder="e.g. Free express shipping and nationwide door installation support." value="<?= e($banner['supporting_text'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label fw-bold">Primary Button Label</label>
            <input type="text" name="cta_label" class="form-control" value="<?= e($banner['cta_label'] ?? 'SHOP NOW') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Primary Button Link</label>
            <input type="text" name="cta_url" class="form-control" value="<?= e($banner['cta_url'] ?? 'shop') ?>">
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
