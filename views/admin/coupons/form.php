<?php
declare(strict_types=1);

/** @var array|null $coupon */
$isEdit = !empty($coupon);
$action = $isEdit ? url('admin/coupons/update/' . $coupon['id']) : url('admin/coupons/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Coupon: ' . e($coupon['code']) : 'Create Promotional Coupon' ?></h4>
    <small class="text-muted">Set up promotional discount parameters.</small>
  </div>
  <a href="<?= url('admin/coupons') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Coupons
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="admin-form-card shadow-sm">
      <form action="<?= $action ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Coupon Code (Uppercase letters/numbers) *</label>
          <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. WELCOME10" required value="<?= e($coupon['code'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Discount Type *</label>
            <select name="type" class="form-select">
              <option value="percentage" <?= ($coupon['type'] ?? '') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
              <option value="fixed" <?= ($coupon['type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Fixed Flat Amount (₹)</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Discount Value *</label>
            <input type="number" step="0.01" name="value" class="form-control" required placeholder="e.g. 10 (for 10%) or 500 (for ₹500)" value="<?= e($coupon['value'] ?? '') ?>">
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Minimum Cart Value (₹)</label>
            <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="0" value="<?= e($coupon['min_order_amount'] ?? 0) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Maximum Discount Cap (₹)</label>
            <input type="number" step="0.01" name="max_discount" class="form-control" placeholder="Leave empty for no cap" value="<?= e($coupon['max_discount'] ?? '') ?>">
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Expiration Date</label>
            <input type="date" name="expires_at" class="form-control" value="<?= e($coupon['expires_at'] ?? '') ?>">
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="couponActiveCheck" <?= !empty($coupon['is_active'] ?? true) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold" for="couponActiveCheck">Active & Redeemable</label>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Save Coupon
        </button>
      </form>
    </div>
  </div>
</div>

