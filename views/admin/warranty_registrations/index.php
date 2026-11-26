<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $registrations
 * @var array $counts
 * @var array $categories
 * @var array $filters
 * @var array $pagination
 */
$currStatus = $filters['status'] ?? 'ALL';
$currCategory = $filters['category'] ?? 'ALL';
$q = $filters['q'] ?? '';
$startDate = $filters['start_date'] ?? '';
$endDate = $filters['end_date'] ?? '';
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h2 class="h4 fw-bold text-dark mb-1">
      <i class="bi bi-shield-check text-warning me-2"></i>Product Warranty Registrations
    </h2>
    <p class="text-muted small mb-0">Review customer warranty submissions, verify tax invoices, approve coverage, and track claims.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= url('admin/warranty-registrations/policies') ?>" class="btn btn-outline-dark btn-sm px-3 fw-semibold">
      <i class="bi bi-sliders me-1"></i> Warranty Policies
    </a>
    <a href="<?= url('warranty-registration') ?>" target="_blank" class="btn btn-warning btn-sm px-3 fw-bold text-dark">
      <i class="bi bi-box-arrow-up-right me-1"></i> Public Form
    </a>
  </div>
</div>

<!-- Metric Summary Cards -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/warranty-registrations?status=ALL') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-primary border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-muted small fw-bold text-uppercase">Total Registrations</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['total'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-folder-check fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/warranty-registrations?status=PENDING') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-warning border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-warning small fw-bold text-uppercase">Pending Review</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['pending'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-clock-history fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/warranty-registrations?status=APPROVED') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-success border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-success small fw-bold text-uppercase">Approved & Active</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['approved'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success">
            <i class="bi bi-shield-fill-check fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/warranty-registrations?status=REJECTED') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-danger border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-danger small fw-bold text-uppercase">Rejected Submissions</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['rejected'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger">
            <i class="bi bi-x-circle fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- Filters Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
  <div class="card-body p-3 p-md-4">
    <form method="GET" action="<?= url('admin/warranty-registrations') ?>" class="row g-2 align-items-center">
      <!-- Search -->
      <div class="col-lg-3 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="q" class="form-control border-start-0" placeholder="Search ID, customer, serial, order..." value="<?= e($q) ?>">
        </div>
      </div>

      <!-- Status Filter -->
      <div class="col-lg-2 col-md-3 col-6">
        <select name="status" class="form-select">
          <option value="ALL" <?= $currStatus === 'ALL' ? 'selected' : '' ?>>All Statuses</option>
          <option value="PENDING" <?= $currStatus === 'PENDING' ? 'selected' : '' ?>>Pending (<?= $counts['pending'] ?>)</option>
          <option value="APPROVED" <?= $currStatus === 'APPROVED' ? 'selected' : '' ?>>Approved (<?= $counts['approved'] ?>)</option>
          <option value="REJECTED" <?= $currStatus === 'REJECTED' ? 'selected' : '' ?>>Rejected (<?= $counts['rejected'] ?>)</option>
        </select>
      </div>

      <!-- Category Filter -->
      <div class="col-lg-2 col-md-3 col-6">
        <select name="category" class="form-select">
          <option value="ALL" <?= $currCategory === 'ALL' ? 'selected' : '' ?>>All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= e($cat) ?>" <?= $currCategory === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Date Range -->
      <div class="col-lg-2 col-md-4 col-6">
        <input type="date" name="start_date" class="form-control" value="<?= e($startDate) ?>" placeholder="From Date" title="From Date">
      </div>
      <div class="col-lg-2 col-md-4 col-6">
        <input type="date" name="end_date" class="form-control" value="<?= e($endDate) ?>" placeholder="To Date" title="To Date">
      </div>

      <!-- Actions -->
      <div class="col-lg-1 col-md-4 col-12 d-flex gap-1">
        <button type="submit" class="btn btn-dark w-100 fw-semibold" title="Apply Filter">
          <i class="bi bi-funnel"></i>
        </button>
        <?php if ($q !== '' || $currStatus !== 'ALL' || $currCategory !== 'ALL' || $startDate !== '' || $endDate !== ''): ?>
          <a href="<?= url('admin/warranty-registrations') ?>" class="btn btn-outline-secondary" title="Reset Filters">
            <i class="bi bi-x-lg"></i>
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
  <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="fw-bold text-dark">
      Showing <?= count($registrations) ?> of <?= $pagination['total_items'] ?> Registrations
    </div>
    <div class="btn-group btn-group-sm">
      <a href="<?= url('admin/warranty-registrations?status=ALL') ?>" class="btn <?= $currStatus === 'ALL' ? 'btn-dark' : 'btn-outline-secondary' ?>">All</a>
      <a href="<?= url('admin/warranty-registrations?status=PENDING') ?>" class="btn <?= $currStatus === 'PENDING' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' ?>">
        Pending <span class="badge bg-danger rounded-pill ms-1"><?= $counts['pending'] ?></span>
      </a>
      <a href="<?= url('admin/warranty-registrations?status=APPROVED') ?>" class="btn <?= $currStatus === 'APPROVED' ? 'btn-success' : 'btn-outline-secondary' ?>">Approved</a>
      <a href="<?= url('admin/warranty-registrations?status=REJECTED') ?>" class="btn <?= $currStatus === 'REJECTED' ? 'btn-danger' : 'btn-outline-secondary' ?>">Rejected</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
        <tr>
          <th class="ps-4">Registration ID</th>
          <th>Customer Info</th>
          <th>Product Details</th>
          <th>Purchase Platform</th>
          <th>Purchase Date</th>
          <th>Submitted Date</th>
          <th>Status</th>
          <th class="text-end pe-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($registrations)): ?>
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
              <div class="fw-semibold">No warranty registrations match your filter.</div>
              <small>Try clearing your search query or changing the status filter.</small>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($registrations as $reg): 
            $regId = $reg['registration_id'] ?? '';
            $status = strtoupper((string)($reg['status'] ?? 'PENDING'));
            $statusBadge = match($status) {
              'APPROVED' => 'bg-success text-white',
              'REJECTED' => 'bg-danger text-white',
              default => 'bg-warning text-dark',
            };
            $customer = $reg['customer'] ?? [];
            $product = $reg['product'] ?? [];
            $purchase = $reg['purchase'] ?? [];
          ?>
            <tr>
              <td class="ps-4">
                <a href="<?= url("admin/warranty-registrations/view/{$regId}") ?>" class="fw-bold text-decoration-none font-monospace text-primary">
                  <?= e($regId) ?>
                </a>
              </td>
              <td>
                <div class="fw-bold text-dark"><?= e($customer['full_name'] ?? 'N/A') ?></div>
                <div class="text-muted small">
                  <i class="bi bi-telephone text-secondary me-1"></i>+91 <?= e($customer['mobile_number'] ?? 'N/A') ?>
                </div>
                <?php if (!empty($customer['email'])): ?>
                  <div class="text-muted small" style="font-size: 0.75rem;">
                    <i class="bi bi-envelope text-secondary me-1"></i><?= e($customer['email']) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge bg-light text-dark border mb-1"><?= e($product['category'] ?? 'N/A') ?></span>
                <div class="fw-semibold small text-dark"><?= e($product['model'] ?? 'N/A') ?></div>
                <div class="text-muted font-monospace" style="font-size: 0.75rem;">
                  <?= !empty($product['serial_number']) ? 'SN: ' . e($product['serial_number']) : '<span class="text-warning"><i class="bi bi-exclamation-circle"></i> No Serial</span>' ?>
                </div>
              </td>
              <td>
                <span class="badge bg-secondary bg-opacity-10 text-dark"><?= e($purchase['platform'] ?? 'N/A') ?></span>
                <div class="text-muted font-monospace small" style="font-size: 0.75rem;">Ord: <?= e($purchase['order_id'] ?? 'N/A') ?></div>
              </td>
              <td>
                <span class="small text-dark fw-semibold"><?= !empty($purchase['purchase_date']) ? date('d M Y', strtotime($purchase['purchase_date'])) : 'N/A' ?></span>
              </td>
              <td>
                <span class="small text-muted"><?= !empty($reg['created_at']) ? date('d M Y, h:i A', strtotime($reg['created_at'])) : 'N/A' ?></span>
              </td>
              <td>
                <span class="badge <?= $statusBadge ?> px-2 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                  <?= e($status) ?>
                </span>
                <?php if ($status === 'APPROVED' && !empty($reg['warranty']['end_date'])): ?>
                  <div class="text-success small fw-semibold" style="font-size: 0.7rem;">
                    Till <?= date('d M Y', strtotime($reg['warranty']['end_date'])) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="text-end pe-4">
                <div class="btn-group btn-group-sm">
                  <a href="<?= url("admin/warranty-registrations/view/{$regId}") ?>" class="btn btn-outline-dark" title="View Full Details">
                    <i class="bi bi-eye"></i>
                  </a>
                  <?php if (!empty($purchase['invoice_path'])): ?>
                    <a href="<?= url("admin/warranty-registrations/invoice/{$regId}") ?>" target="_blank" class="btn btn-outline-secondary" title="View Invoice">
                      <i class="bi bi-receipt"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pagination['total_pages'] > 1): ?>
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?> (<?= $pagination['total_items'] ?> total)
      </div>
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <?php if ($pagination['current_page'] > 1): ?>
            <li class="page-item">
              <a class="page-link" href="<?= url('admin/warranty-registrations?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1]))) ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
              <a class="page-link" href="<?= url('admin/warranty-registrations?' . http_build_query(array_merge($filters, ['page' => $i]))) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
            <li class="page-item">
              <a class="page-link" href="<?= url('admin/warranty-registrations?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1]))) ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  <?php endif; ?>
</div>
