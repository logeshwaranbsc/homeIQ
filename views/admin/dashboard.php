<?php
declare(strict_types=1);

/**
 * @var array $stats
 * @var array $recentOrders
 * @var array $topProducts
 */
?>

<!-- Metric Summary Cards -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-primary border-4">
      <div class="metric-label text-primary">Verified Revenue</div>
      <div class="metric-value text-dark"><?= format_price($stats['total_revenue']) ?></div>
      <small class="text-muted">From paid & delivered orders</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-info border-4">
      <div class="metric-label text-info">Total Orders</div>
      <div class="metric-value text-dark"><?= $stats['total_orders'] ?></div>
      <small class="text-muted"><?= $stats['pending_orders'] ?> pending fulfillment</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-success border-4">
      <div class="metric-label text-success">Active Products</div>
      <div class="metric-value text-dark"><?= $stats['active_products'] ?></div>
      <small class="text-muted"><?= $stats['total_products'] ?> total catalog SKUs</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-warning border-4">
      <div class="metric-label text-warning">Low Stock Alerts</div>
      <div class="metric-value text-dark"><?= $stats['low_stock'] + $stats['out_of_stock'] ?></div>
      <small class="text-muted"><?= $stats['low_stock'] ?> low / <?= $stats['out_of_stock'] ?> out of stock</small>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Recent Orders Table -->
  <div class="col-lg-8">
    <div class="admin-table-card shadow-sm">
      <div class="table-header-bar">
        <div>
          <h6 class="fw-bold m-0"><i class="bi bi-receipt me-2 text-primary"></i>Recent Customer Orders</h6>
          <small class="text-muted">Live incoming customer orders requiring fulfillment</small>
        </div>
        <a href="<?= url('admin/orders') ?>" class="btn btn-sm btn-outline-dark">View All Orders</a>
      </div>

      <?php if (empty($recentOrders)): ?>
        <div class="p-4 text-center text-muted">No orders placed yet.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $order): ?>
                <tr>
                  <td>
                    <strong><?= e($order['order_number']) ?></strong>
                    <div class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
                  </td>
                  <td>
                    <div><?= e($order['customer_name']) ?></div>
                    <small class="text-muted"><?= e($order['customer_phone']) ?></small>
                  </td>
                  <td><strong><?= format_price($order['total_amount']) ?></strong></td>
                  <td>
                    <span class="badge-status <?= $order['payment_status'] === 'Paid' ? 'badge-delivered' : 'badge-pending' ?>">
                      <?= e($order['payment_status']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge-status badge-<?= strtolower($order['status']) ?>">
                      <?= e($order['status']) ?>
                    </span>
                  </td>
                  <td>
                    <a href="<?= url('admin/orders/view/' . $order['id']) ?>" class="btn-action-icon" title="View details">
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Best-Selling Digital Locks -->
  <div class="col-lg-4">
    <div class="admin-table-card shadow-sm mb-4">
      <div class="table-header-bar">
        <h6 class="fw-bold m-0"><i class="bi bi-trophy text-warning me-2"></i>Top Selling Locks</h6>
      </div>
      <div class="p-3">
        <?php if (empty($topProducts)): ?>
          <p class="text-muted small m-0 text-center py-3">Sales volume data will generate automatically as orders are placed.</p>
        <?php else: ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($topProducts as $top): ?>
              <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                <div>
                  <div class="fw-bold small text-truncate" style="max-width: 170px;"><?= e($top['name']) ?></div>
                  <small class="text-muted"><?= $top['quantity'] ?> units sold</small>
                </div>
                <strong class="text-primary small"><?= format_price($top['revenue']) ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Operations Card -->
    <div class="admin-table-card shadow-sm p-3">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Quick Navigation</h6>
      <div class="d-grid gap-2">
        <a href="<?= url('admin/products/create') ?>" class="btn btn-sm btn-dark text-start">
          <i class="bi bi-plus-circle me-2"></i> Add New Digital Lock
        </a>
        <a href="<?= url('admin/inventory') ?>" class="btn btn-sm btn-outline-secondary text-start">
          <i class="bi bi-boxes me-2"></i> Adjust Stock Inventory
        </a>
        <a href="<?= url('admin/reports') ?>" class="btn btn-sm btn-outline-secondary text-start">
          <i class="bi bi-file-earmark-spreadsheet me-2"></i> Export Sales CSV Report
        </a>
      </div>
    </div>
  </div>
</div>

