<?php
declare(strict_types=1);

/**
 * @var array $customers
 * @var string $q
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Customer Profiles (<?= count($customers) ?>)</h4>
    <small class="text-muted">Registered homeowner accounts, contact records, and order history.</small>
  </div>
</div>

<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
  <form action="<?= url('admin/customers') ?>" method="GET" class="row g-2 align-items-center">
    <div class="col-md-6">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Search by customer name, email, or phone..." value="<?= e($q) ?>">
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-sm btn-secondary me-1">Search</button>
      <a href="<?= url('admin/customers') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
  </form>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Customer Name</th>
          <th>Email Address</th>
          <th>Phone Number</th>
          <th>Registered On</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">No customer accounts found.</td></tr>
        <?php else: ?>
          <?php foreach ($customers as $c): ?>
            <tr>
              <td><strong><?= e($c['name']) ?></strong></td>
              <td><?= e($c['email']) ?></td>
              <td><?= e($c['phone'] ?? '—') ?></td>
              <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
              <td>
                <span class="badge-status <?= !empty($c['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                  <?= !empty($c['is_active']) ? 'Active' : 'Deactivated' ?>
                </span>
              </td>
              <td class="text-end">
                <a href="<?= url('admin/customers/view/' . $c['id']) ?>" class="btn-action-icon me-1" title="View Customer">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="<?= url('admin/customers/status/' . $c['id']) ?>" class="btn-action-icon" title="Toggle active status" data-confirm="Are you sure you want to change account status for <?= e($c['email']) ?>?">
                  <i class="bi bi-arrow-repeat"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

