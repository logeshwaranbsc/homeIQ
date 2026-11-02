<?php
declare(strict_types=1);

/**
 * @var array $orders
 * @var array $filters
 * @var array $statuses
 * @var array $paymentStatuses
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Customer Orders Management (<?= count($orders) ?>)</h4>
    <small class="text-muted">Track order status, record payment verification, and assign courier shipments.</small>
  </div>
  <a href="<?= url('admin/reports/export') ?>" class="btn btn-sm btn-outline-dark">
    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Orders CSV
  </a>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
  <form action="<?= url('admin/orders') ?>" method="GET" class="row g-2 align-items-center">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Order #, customer name, mobile, email..." value="<?= e($filters['q'] ?? '') ?>">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Fulfillment Statuses</option>
        <?php foreach ($statuses as $st): ?>
          <option value="<?= e($st) ?>" <?= ($filters['status'] ?? '') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Payment Statuses</option>
        <?php foreach ($paymentStatuses as $pst): ?>
          <option value="<?= e($pst) ?>" <?= ($filters['payment_status'] ?? '') === $pst ? 'selected' : '' ?>><?= e($pst) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 text-end">
      <button type="submit" class="btn btn-sm btn-secondary me-1">Filter</button>
      <a href="<?= url('admin/orders') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
  </form>
</div>

<!-- Orders Table -->
<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order Number</th>
          <th>Placed On</th>
          <th>Customer Details</th>
          <th>Destination</th>
          <th>Total</th>
          <th>Payment</th>
          <th>Order Status</th>
          <th style="width: 100px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="8" class="text-center py-4 text-muted">No orders match your filter criteria.</td></tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>
                <strong><?= e($order['order_number']) ?></strong>
                <div class="text-muted" style="font-size: 0.725rem;"><?= count($order['items']) ?> items</div>
              </td>
              <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
              <td>
                <strong><?= e($order['customer_name']) ?></strong>
                <div class="text-muted small"><?= e($order['customer_phone']) ?></div>
              </td>
              <td class="small">
                <?= e($order['shipping_address']['city'] ?? '') ?>, <?= e($order['shipping_address']['state'] ?? '') ?>
              </td>
              <td><strong><?= format_price($order['total_amount']) ?></strong></td>
              <td>
                <span class="badge-status <?= $order['payment_status'] === 'Paid' ? 'badge-delivered' : 'badge-pending' ?>">
                  <?= e($order['payment_status']) ?>
                </span>
                <div class="text-muted" style="font-size: 0.7rem;"><?= strtoupper($order['payment_method']) ?></div>
              </td>
              <td>
                <span class="badge-status badge-<?= strtolower($order['status']) ?>">
                  <?= e($order['status']) ?>
                </span>
              </td>
              <td class="text-end">
                <a href="<?= url('admin/orders/view/' . $order['id']) ?>" class="btn-action-icon me-1" title="Manage Order">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn-action-icon" title="View Tax Invoice">
                  <i class="bi bi-printer"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

