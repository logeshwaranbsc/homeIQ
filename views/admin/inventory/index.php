<?php
declare(strict_types=1);

/**
 * @var array $products
 * @var array $movements
 * @var int $lowStockCount
 * @var int $outOfStockCount
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Inventory & Stock Movements</h4>
    <small class="text-muted">Real-time digital lock stock control, warehouse adjustments, and movement audit trail.</small>
  </div>
  <div class="d-flex gap-2">
    <span class="badge bg-warning text-dark p-2 fs-6"><?= $lowStockCount ?> Low Stock Alerts</span>
    <span class="badge bg-danger p-2 fs-6"><?= $outOfStockCount ?> Out of Stock</span>
  </div>
</div>

<div class="row g-4 mb-4">
  <!-- Stock Adjustment Form -->
  <div class="col-lg-4">
    <div class="admin-form-card shadow-sm">
      <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-plus-slash-minus text-primary me-2"></i>Quick Stock Adjustment</h6>
      <form action="<?= url('admin/inventory/adjust') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Select Digital Lock Product *</label>
          <select name="product_id" class="form-select" required>
            <?php foreach ($products as $p): ?>
              <option value="<?= e($p['id']) ?>">
                <?= e($p['name']) ?> (Current Stock: <?= $p['stock'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label">Adjustment Type</label>
            <select name="adjustment_type" class="form-select form-select-sm">
              <option value="Restock">Warehouse Restock (+)</option>
              <option value="Adjustment">Physical Audit Adjustment</option>
              <option value="Return">Customer Return (+)</option>
              <option value="Damaged">Damaged / Written-off (-)</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">Quantity (+ / -)</label>
            <input type="number" name="quantity" class="form-control form-control-sm" placeholder="e.g. +10 or -2" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason / Notes</label>
          <input type="text" name="notes" class="form-control form-control-sm" placeholder="e.g. Shipment PO #4928 from manufacturer">
        </div>

        <button type="submit" class="btn btn-dark w-100 btn-sm">Record Inventory Movement</button>
      </form>
    </div>
  </div>

  <!-- Stock Status Table -->
  <div class="col-lg-8">
    <div class="admin-table-card shadow-sm">
      <div class="table-header-bar">
        <h6 class="fw-bold m-0"><i class="bi bi-box-seam me-2 text-primary"></i>Current Stock Levels</h6>
      </div>
      <div class="table-responsive" style="max-height: 290px; overflow-y: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>SKU</th>
              <th>Category</th>
              <th class="text-center">Current Stock</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <tr>
                <td><strong><?= e($p['name']) ?></strong></td>
                <td><code><?= e($p['sku']) ?></code></td>
                <td><?= e($p['category']) ?></td>
                <td class="text-center fw-bold"><?= $p['stock'] ?></td>
                <td>
                  <?php if ($p['stock'] <= 0): ?>
                    <span class="badge bg-danger">Out of Stock</span>
                  <?php elseif ($p['stock'] <= 5): ?>
                    <span class="badge bg-warning text-dark">Low Stock (≤5)</span>
                  <?php else: ?>
                    <span class="badge bg-success">Healthy</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Stock Movements Log Table -->
<div class="admin-table-card shadow-sm">
  <div class="table-header-bar">
    <h6 class="fw-bold m-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Stock Movements Audit Trail</h6>
    <small class="text-muted">Complete audit log of additions, sales, returns, and manual changes</small>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>Product</th>
          <th>Movement Type</th>
          <th class="text-center">Quantity Change</th>
          <th class="text-center">Before &rarr; After</th>
          <th>Reference / Notes</th>
          <th>User</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($movements)): ?>
          <tr><td colspan="7" class="text-center py-4 text-muted">No stock movements recorded yet.</td></tr>
        <?php else: ?>
          <?php foreach ($movements as $m): ?>
            <tr>
              <td><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></td>
              <td><strong><?= e($m['product_name']) ?></strong></td>
              <td>
                <span class="badge bg-light text-dark border"><?= e($m['type']) ?></span>
              </td>
              <td class="text-center fw-bold <?= ($m['quantity_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                <?= ($m['quantity_change'] ?? 0) >= 0 ? '+' . $m['quantity_change'] : $m['quantity_change'] ?>
              </td>
              <td class="text-center text-muted">
                <?= $m['stock_before'] ?? 0 ?> &rarr; <strong><?= $m['stock_after'] ?? 0 ?></strong>
              </td>
              <td class="small text-muted"><?= e($m['notes'] ?? $m['reference'] ?? '—') ?></td>
              <td><?= e($m['performed_by'] ?? 'System') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

