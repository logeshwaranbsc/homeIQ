<?php
declare(strict_types=1);

/** @var array $settings */
$announcementsText = is_array($settings['announcements'] ?? null) ? implode("\n", $settings['announcements']) : '';
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Store & System Configuration</h4>
    <small class="text-muted">Brand information, customer support contacts, shipping fee thresholds, and announcement bar.</small>
  </div>
</div>

<form action="<?= url('admin/settings/update') ?>" method="POST">
  <?= csrf_field() ?>

  <div class="row g-4">
    <!-- Brand & Contact -->
    <div class="col-lg-6">
      <div class="admin-form-card shadow-sm mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Brand & Store Identity</h6>

        <div class="mb-3">
          <label class="form-label">Store Brand Name *</label>
          <input type="text" name="store_name" class="form-control" required value="<?= e($settings['store_name'] ?? 'HOMEIQ') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Brand Tagline</label>
          <input type="text" name="tagline" class="form-control" value="<?= e($settings['tagline'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Support Email *</label>
            <input type="email" name="support_email" class="form-control" required value="<?= e($settings['support_email'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Support Phone Hotline *</label>
            <input type="tel" name="support_phone" class="form-control" required value="<?= e($settings['support_phone'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Headquarters / Experience Centre Address</label>
          <textarea name="address" rows="2" class="form-control"><?= e($settings['address'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Shipping & Checkout Rules -->
      <div class="admin-form-card shadow-sm mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Shipping & Payment Rules</h6>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Free Shipping Minimum Threshold (₹) *</label>
            <input type="number" step="0.01" name="free_shipping_min" class="form-control" required value="<?= e($settings['free_shipping_min'] ?? 5000) ?>">
            <small class="text-muted">Carts at or above this value get free shipping.</small>
          </div>
          <div class="col-md-6">
            <label class="form-label">Flat Shipping Rate (₹) *</label>
            <input type="number" step="0.01" name="flat_shipping_rate" class="form-control" required value="<?= e($settings['flat_shipping_rate'] ?? 250) ?>">
            <small class="text-muted">Applied when cart is below threshold.</small>
          </div>
        </div>

        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="enable_cod" value="1" id="enableCodCheck" <?= !empty($settings['enable_cod']) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="enableCodCheck">Enable Cash on Delivery (COD)</label>
        </div>
      </div>
    </div>

    <!-- Announcement Bar & Notifications -->
    <div class="col-lg-6">
      <div class="admin-form-card shadow-sm mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Announcement Bar (Homepage Top)</h6>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="announcement_bar_enabled" value="1" id="annBarEnabled" <?= !empty($settings['announcement_bar_enabled']) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="annBarEnabled">Display Announcement Bar</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Announcements (One per line)</label>
          <textarea name="announcements" rows="5" class="form-control" placeholder="Free Installation on Eligible Products&#10;Free Shipping on Orders Above ₹5,000&#10;2-Year Manufacturer Warranty&#10;24/7 Technical Support"><?= e($announcementsText) ?></textarea>
          <small class="text-muted">Edit, reorder, or update announcements directly. Reflected immediately on the storefront.</small>
        </div>
      </div>

      <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">
        <i class="bi bi-save me-1"></i> Save Store Configuration
      </button>
    </div>
  </div>
</form>

