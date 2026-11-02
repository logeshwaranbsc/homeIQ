<?php
declare(strict_types=1);

/**
 * @var array $customer
 * @var array $orders
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0">Customer: <?= e($customer['name']) ?></h4>
    <small class="text-muted">Account ID: <?= e($customer['id']) ?> • Registered <?= date('d M Y', strtotime($customer['created_at'])) ?></small>
  </div>
  <a href="<?= url('admin/customers') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Customers
  </a>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-6">
    <div class="admin-form-card shadow-sm h-100">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Profile & Contact</h6>
      <div class="small">
        <div class="mb-2"><strong>Name:</strong> <?= e($customer['name']) ?></div>
        <div class="mb-2"><strong>Email:</strong> <?= e($customer['email']) ?></div>
        <div class="mb-2"><strong>Phone:</strong> <?= e($customer['phone'] ?? 'N/A') ?></div>
        <div><strong>Status:</strong> <span class="badge bg-<?= !empty($customer['is_active']) ? 'success' : 'danger' ?>"><?= !empty($customer['is_active']) ? 'Active' : 'Deactivated' ?></span></div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="admin-form-card shadow-sm h-100">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Saved Addresses (<?= count($customer['addresses'] ?? []) ?>)</h6>
      <div class="small">
        <?php if (empty($customer['addresses'])): ?>
          <span class="text-muted">No saved delivery address recorded.</span>
        <?php else: ?>
          <?php foreach ($customer['addresses'] as $addr): ?>
            <div class="p-2 border rounded mb-2 bg-light">
              <?= e($addr['street'] ?? '') ?>, <?= e($addr['city'] ?? '') ?>, <?= e($addr['state'] ?? '') ?> - <?= e($addr['pincode'] ?? '') ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Customer Orders History -->
<div class="admin-table-card shadow-sm">
  <div class="table-header-bar">
    <h6 class="fw-bold m-0">Order History (<?= count($orders) ?>)</h6>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order Number</th>
          <th>Date</th>
          <th>Total</th>
          <th>Payment</th>
          <th>Status</th>
          <th style="width: 100px; text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">No orders found for this customer.</td></tr>
        <?php else: ?>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong><?= e($o['order_number']) ?></strong></td>
              <td><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></td>
              <td><strong><?= format_price($o['total_amount']) ?></strong></td>
              <td><span class="badge bg-secondary"><?= e($o['payment_status']) ?></span></td>
              <td><span class="badge bg-primary"><?= e($o['status']) ?></span></td>
              <td class="text-end">
                <a href="<?= url('admin/orders/view/' . $o['id']) ?>" class="btn-action-icon">
                  <i class="bi bi-arrow-right"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

