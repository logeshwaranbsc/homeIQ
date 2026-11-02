<?php
declare(strict_types=1);

/**
 * @var array $products
 * @var array $categories
 * @var array $filters
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Digital Locks Catalog (<?= count($products) ?>)</h4>
    <small class="text-muted">Manage all digital lock models, regular MRP, sale prices, and stock inventory.</small>
  </div>
  <a href="<?= url('admin/products/create') ?>" class="btn btn-sm btn-dark">
    <i class="bi bi-plus-lg me-1"></i> Add Digital Lock
  </a>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
  <form action="<?= url('admin/products') ?>" method="GET" class="row g-2 align-items-center">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Search product name or SKU..." value="<?= e($filters['q'] ?? '') ?>">
    </div>
    <div class="col-md-3">
      <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= e($cat['slug']) ?>" <?= ($filters['category'] ?? '') === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="1" <?= ($filters['status'] ?? '') === '1' ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= ($filters['status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <div class="col-md-3 text-end">
      <button type="submit" class="btn btn-sm btn-secondary me-1">Filter</button>
      <a href="<?= url('admin/products') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
  </form>
</div>

<!-- Product Table -->
<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Product / Model</th>
          <th>SKU</th>
          <th>Category</th>
          <th>Regular MRP</th>
          <th>Sale Price</th>
          <th>Stock</th>
          <th>Status</th>
          <th style="width: 140px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
          <tr><td colspan="8" class="text-center py-4 text-muted">No products match your search criteria.</td></tr>
        <?php else: ?>
          <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <img src="<?= upload_url($p['main_image']) ?>" alt="" style="width: 44px; height: 44px; object-fit: contain;" class="img-thumbnail">
                  <div>
                    <a href="<?= url('product/' . $p['slug']) ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                      <?= e($p['name']) ?>
                    </a>
                    <?php if (!empty($p['is_featured'])): ?>
                      <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Featured</span>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
              <td><code><?= e($p['sku']) ?></code></td>
              <td><?= e($p['category']) ?></td>
              <td class="text-muted"><?= format_price($p['price']) ?></td>
              <td><strong><?= format_price($p['sale_price'] ?? $p['price']) ?></strong></td>
              <td>
                <?php if ($p['stock'] <= 0): ?>
                  <span class="badge bg-danger">0 Out of Stock</span>
                <?php elseif ($p['stock'] <= 5): ?>
                  <span class="badge bg-warning text-dark"><?= $p['stock'] ?> Low Stock</span>
                <?php else: ?>
                  <span class="badge bg-success"><?= $p['stock'] ?> units</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= url('admin/products/status/' . $p['id']) ?>" class="badge-status <?= !empty($p['is_active']) ? 'badge-active' : 'badge-inactive' ?> text-decoration-none" title="Click to toggle active status">
                  <?= !empty($p['is_active']) ? 'Active' : 'Inactive' ?>
                </a>
              </td>
              <td class="text-end">
                <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="btn-action-icon me-1" title="Edit Product">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= url('admin/products/delete/' . $p['id']) ?>" class="btn-action-icon danger" title="Delete Product" data-confirm="Are you sure you want to permanently delete '<?= e($p['name']) ?>'?">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

