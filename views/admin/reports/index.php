<?php
declare(strict_types=1);

/**
 * @var string $range
 * @var array $analytics
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Sales Reports & Financial Analytics</h4>
    <small class="text-muted">Business performance metrics, fulfillment volumes, and top revenue drivers.</small>
  </div>
  <div class="d-flex gap-2">
    <!-- Date Range Picker -->
    <select class="form-select form-select-sm" style="width: 170px;" onchange="location = '<?= url('admin/reports?range=') ?>' + this.value;">
      <option value="today" <?= $range === 'today' ? 'selected' : '' ?>>Today</option>
      <option value="7days" <?= $range === '7days' ? 'selected' : '' ?>>Last 7 Days</option>
      <option value="30days" <?= $range === '30days' ? 'selected' : '' ?>>Last 30 Days</option>
      <option value="year" <?= $range === 'year' ? 'selected' : '' ?>>Last 1 Year</option>
      <option value="all" <?= $range === 'all' ? 'selected' : '' ?>>All Time</option>
    </select>
    <a href="<?= url('admin/reports/export?range=' . urlencode($range)) ?>" class="btn btn-sm btn-dark">
      <i class="bi bi-download me-1"></i> Export CSV
    </a>
  </div>
</div>

<!-- Metrics Strip -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="metric-card shadow-sm border-start border-primary border-4">
      <div class="metric-label text-primary">Revenue (<?= strtoupper($range) ?>)</div>
      <div class="metric-value"><?= format_price($analytics['total_revenue']) ?></div>
      <small class="text-muted">Paid & fulfilled orders</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="metric-card shadow-sm border-start border-info border-4">
      <div class="metric-label text-info">Orders Recorded</div>
      <div class="metric-value"><?= $analytics['total_orders'] ?></div>
      <small class="text-muted"><?= $analytics['delivered_orders'] ?> delivered successfully</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="metric-card shadow-sm border-start border-warning border-4">
      <div class="metric-label text-warning">Pending Fulfillment</div>
      <div class="metric-value"><?= $analytics['pending_orders'] ?></div>
      <small class="text-muted">Orders awaiting dispatch</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="metric-card shadow-sm border-start border-danger border-4">
      <div class="metric-label text-danger">Cancelled Orders</div>
      <div class="metric-value"><?= $analytics['cancelled_orders'] ?></div>
      <small class="text-muted">Inventory returned to stock</small>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Top Products -->
  <div class="col-lg-6">
    <div class="admin-table-card shadow-sm">
      <div class="table-header-bar">
        <h6 class="fw-bold m-0"><i class="bi bi-trophy text-warning me-2"></i>Product Performance Ranking</h6>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Digital Lock Model</th>
              <th class="text-center">Units Sold</th>
              <th class="text-end">Revenue Generated</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($analytics['top_products'])): ?>
              <tr><td colspan="3" class="text-center py-4 text-muted">No sales logged in this timeframe.</td></tr>
            <?php else: ?>
              <?php foreach ($analytics['top_products'] as $top): ?>
                <tr>
                  <td><strong><?= e($top['name']) ?></strong></td>
                  <td class="text-center"><?= $top['quantity'] ?></td>
                  <td class="text-end fw-bold"><?= format_price($top['revenue']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Order Status Breakdown -->
  <div class="col-lg-6">
    <div class="admin-table-card shadow-sm p-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-pie-chart text-primary me-2"></i>Order Breakdown</h6>
      <div class="d-flex flex-column gap-3">
        <div>
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span>Delivered Orders</span>
            <span class="text-success"><?= $analytics['delivered_orders'] ?></span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-success" style="width: <?= $analytics['total_orders'] > 0 ? ($analytics['delivered_orders'] / $analytics['total_orders']) * 100 : 0 ?>%"></div>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span>Pending Orders</span>
            <span class="text-warning"><?= $analytics['pending_orders'] ?></span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-warning" style="width: <?= $analytics['total_orders'] > 0 ? ($analytics['pending_orders'] / $analytics['total_orders']) * 100 : 0 ?>%"></div>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span>Cancelled Orders</span>
            <span class="text-danger"><?= $analytics['cancelled_orders'] ?></span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-danger" style="width: <?= $analytics['total_orders'] > 0 ? ($analytics['cancelled_orders'] / $analytics['total_orders']) * 100 : 0 ?>%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

