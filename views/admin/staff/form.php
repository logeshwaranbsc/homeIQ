<?php
declare(strict_types=1);

/**
 * @var array|null $adminUser
 * @var array $roles
 */
$isEdit = !empty($adminUser);
$action = $isEdit ? url('admin/staff/update/' . $adminUser['id']) : url('admin/staff/store');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Staff Member: ' . e($adminUser['name']) : 'Create Staff Member' ?></h4>
    <small class="text-muted">Staff authorization and role assignment.</small>
  </div>
  <a href="<?= url('admin/staff') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Staff List
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="admin-form-card shadow-sm">
      <form action="<?= $action ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" required placeholder="e.g. Anand Varma" value="<?= e($adminUser['name'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-control" required placeholder="anand_ops" value="<?= e($adminUser['username'] ?? '') ?>" <?= $isEdit ? 'readonly' : '' ?>>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control" required placeholder="anand@homeiq.com" value="<?= e($adminUser['email'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Assigned Role *</label>
          <select name="role" class="form-select" required>
            <?php foreach ($roles as $r): ?>
              <option value="<?= e($r) ?>" <?= ($adminUser['role'] ?? '') === $r ? 'selected' : '' ?>><?= e($r) ?></option>
            <?php endforeach; ?>
          </select>
          <small class="text-muted">Role permissions are strictly enforced on backend PHP controllers.</small>
        </div>

        <div class="mb-4">
          <label class="form-label"><?= $isEdit ? 'Reset Password (Leave blank to keep existing)' : 'Account Password *' ?></label>
          <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" <?= $isEdit ? '' : 'required' ?> minlength="8">
        </div>

        <?php if ($isEdit): ?>
          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="staffActiveCheck" <?= !empty($adminUser['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label fw-bold" for="staffActiveCheck">Account Enabled</label>
          </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Save Staff Account
        </button>
      </form>
    </div>
  </div>
</div>

