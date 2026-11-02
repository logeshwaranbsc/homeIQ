<?php
declare(strict_types=1);

/** @var array $coupons */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Coupons & Promotional Discounts</h4>
    <small class="text-muted">Manage promotional codes, percentage / fixed discounts, and cart eligibility minimums.</small>
  </div>
  <a href="<?= url('admin/coupons/create') ?>" class="btn btn-sm btn-dark">
    <i class="bi bi-plus-lg me-1"></i> Create Coupon
  </a>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Coupon Code</th>
          <th>Discount Type</th>
          <th>Discount Value</th>
          <th>Min Cart Spend</th>
          <th>Max Discount Cap</th>
          <th>Expires On</th>
          <th>Status</th>
          <th style="width: 120px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($coupons as $cpn): ?>
          <tr>
            <td><code><strong class="fs-6"><?= e($cpn['code']) ?></strong></code></td>
            <td><?= ucfirst($cpn['type']) ?></td>
            <td>
              <strong><?= $cpn['type'] === 'percentage' ? $cpn['value'] . '%' : format_price($cpn['value']) ?></strong>
            </td>
            <td><?= format_price($cpn['min_order_amount'] ?? 0) ?></td>
            <td><?= !empty($cpn['max_discount']) ? format_price($cpn['max_discount']) : 'No Limit' ?></td>
            <td><?= !empty($cpn['expires_at']) ? date('d M Y', strtotime($cpn['expires_at'])) : 'Never' ?></td>
            <td>
              <span class="badge-status <?= !empty($cpn['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                <?= !empty($cpn['is_active']) ? 'Active' : 'Disabled' ?>
              </span>
            </td>
            <td class="text-end">
              <a href="<?= url('admin/coupons/edit/' . $cpn['id']) ?>" class="btn-action-icon me-1" title="Edit Coupon">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="<?= url('admin/coupons/delete/' . $cpn['id']) ?>" class="btn-action-icon danger" title="Delete Coupon" data-confirm="Are you sure you want to delete coupon '<?= e($cpn['code']) ?>'?">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

