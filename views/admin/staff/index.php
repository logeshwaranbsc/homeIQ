<?php
declare(strict_types=1);

/**
 * @var array $admins
 * @var array $currentAdmin
 * @var array $roles
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Staff & Role Permissions Management</h4>
    <small class="text-muted">Manage store managers, assign granular operational roles, and enforce server-side security.</small>
  </div>
  <a href="<?= url('admin/staff/create') ?>" class="btn btn-sm btn-dark">
    <i class="bi bi-person-plus me-1"></i> Add Staff Account
  </a>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Staff Name</th>
          <th>Username</th>
          <th>Email Address</th>
          <th>Role Permission</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $adm): ?>
          <tr>
            <td>
              <strong><?= e($adm['name']) ?></strong>
              <?php if (($adm['id'] ?? '') === ($currentAdmin['id'] ?? '')): ?>
                <span class="badge bg-info text-dark ms-1" style="font-size: 0.65rem;">You</span>
              <?php endif; ?>
            </td>
            <td><code><?= e($adm['username']) ?></code></td>
            <td><?= e($adm['email']) ?></td>
            <td>
              <span class="badge bg-dark"><?= e($adm['role'] ?? 'Product Manager') ?></span>
            </td>
            <td>
              <span class="badge-status <?= !empty($adm['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                <?= !empty($adm['is_active']) ? 'Active' : 'Disabled' ?>
              </span>
            </td>
            <td class="text-end">
              <a href="<?= url('admin/staff/edit/' . $adm['id']) ?>" class="btn-action-icon me-1" title="Edit Staff">
                <i class="bi bi-pencil"></i>
              </a>
              <?php if (($adm['id'] ?? '') !== ($currentAdmin['id'] ?? '')): ?>
                <a href="<?= url('admin/staff/delete/' . $adm['id']) ?>" class="btn-action-icon danger" title="Delete Staff Account" data-confirm="Are you sure you want to delete staff account '<?= e($adm['username']) ?>'?">
                  <i class="bi bi-trash"></i>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

