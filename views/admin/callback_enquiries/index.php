<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $leads
 * @var array $counts
 * @var array $filters
 * @var array $productOptions
 * @var array $staffMembers
 * @var array $pagination
 */
$currStatus = $filters['status'] ?? 'ALL';
$currInterest = $filters['product_interest'] ?? 'ALL';
$currAssigned = $filters['assigned_to'] ?? 'ALL';
$q = $filters['q'] ?? '';
$startDate = $filters['start_date'] ?? '';
$endDate = $filters['end_date'] ?? '';

// Helper for status badge styling
function getStatusBadge(string $status): string {
    return match (strtoupper($status)) {
        'NEW' => '<span class="badge bg-primary px-2 py-1"><i class="bi bi-star-fill me-1"></i>New</span>',
        'CONTACTED' => '<span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-telephone-outbound me-1"></i>Contacted</span>',
        'FOLLOW-UP REQUIRED', 'FOLLOW_UP' => '<span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-clock-history me-1"></i>Follow-up</span>',
        'CONVERTED' => '<span class="badge bg-success px-2 py-1"><i class="bi bi-bag-check-fill me-1"></i>Converted</span>',
        'NOT INTERESTED', 'NOT_INTERESTED' => '<span class="badge bg-secondary px-2 py-1"><i class="bi bi-slash-circle me-1"></i>Not Interested</span>',
        'UNREACHABLE' => '<span class="badge bg-danger px-2 py-1"><i class="bi bi-telephone-x me-1"></i>Unreachable</span>',
        default => '<span class="badge bg-light text-dark border px-2 py-1">' . htmlspecialchars($status) . '</span>'
    };
}
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h2 class="h4 fw-bold text-dark mb-1">
      <i class="bi bi-telephone-inbound text-primary me-2"></i>Callback Enquiries &amp; Leads
    </h2>
    <p class="text-muted small mb-0">Track and manage prospective customers requesting presales assistance from storefront product pages.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= url('admin/callback-enquiries/settings') ?>" class="btn btn-outline-dark btn-sm px-3 fw-semibold">
      <i class="bi bi-gear-fill me-1"></i> Popup Settings
    </a>
  </div>
</div>

<!-- 4 Callback Dashboard Summary Cards -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/callback-enquiries?status=ALL') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-primary border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-muted small fw-bold text-uppercase">Total Enquiries</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['total'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-telephone-inbound fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/callback-enquiries?status=NEW') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-warning border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-warning small fw-bold text-uppercase">New Enquiries</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['new'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-bell-fill fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/callback-enquiries?status=CONTACTED') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-info border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-info small fw-bold text-uppercase">Contacted</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['contacted'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info">
            <i class="bi bi-chat-dots-fill fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-xl-3 col-sm-6">
    <a href="<?= url('admin/callback-enquiries?status=CONVERTED') ?>" class="text-decoration-none">
      <div class="metric-card shadow-sm border-start border-success border-4 p-3 bg-white rounded-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="metric-label text-success small fw-bold text-uppercase">Converted</div>
            <div class="metric-value fs-3 fw-extrabold text-dark mt-1"><?= $counts['converted'] ?></div>
          </div>
          <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success">
            <i class="bi bi-check2-all fs-4"></i>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- Search & Multi-Criteria Filters -->
<div class="card shadow-sm border-0 mb-4 rounded-3">
  <div class="card-body p-3">
    <form method="GET" action="<?= url('admin/callback-enquiries') ?>" class="row g-2 align-items-end">
      
      <!-- Search Input -->
      <div class="col-lg-3 col-md-6">
        <label class="form-label small fw-semibold text-muted mb-1">Search Leads</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
          <input type="text" 
                 name="q" 
                 class="form-control border-start-0" 
                 placeholder="ID, name, mobile, notes..." 
                 value="<?= e($q) ?>">
        </div>
      </div>

      <!-- Status Filter -->
      <div class="col-lg-2 col-md-3 col-6">
        <label class="form-label small fw-semibold text-muted mb-1">Lead Status</label>
        <select name="status" class="form-select form-select-sm">
          <option value="ALL" <?= $currStatus === 'ALL' ? 'selected' : '' ?>>All Statuses</option>
          <option value="NEW" <?= $currStatus === 'NEW' ? 'selected' : '' ?>>New</option>
          <option value="CONTACTED" <?= $currStatus === 'CONTACTED' ? 'selected' : '' ?>>Contacted</option>
          <option value="FOLLOW-UP REQUIRED" <?= $currStatus === 'FOLLOW-UP REQUIRED' ? 'selected' : '' ?>>Follow-up Required</option>
          <option value="CONVERTED" <?= $currStatus === 'CONVERTED' ? 'selected' : '' ?>>Converted</option>
          <option value="NOT INTERESTED" <?= $currStatus === 'NOT INTERESTED' ? 'selected' : '' ?>>Not Interested</option>
          <option value="UNREACHABLE" <?= $currStatus === 'UNREACHABLE' ? 'selected' : '' ?>>Unreachable</option>
        </select>
      </div>

      <!-- Product Interest Filter -->
      <div class="col-lg-2 col-md-3 col-6">
        <label class="form-label small fw-semibold text-muted mb-1">Product Category</label>
        <select name="product_interest" class="form-select form-select-sm">
          <option value="ALL" <?= $currInterest === 'ALL' ? 'selected' : '' ?>>All Products</option>
          <?php foreach ($productOptions as $opt): ?>
            <option value="<?= e($opt) ?>" <?= $currInterest === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Assigned Staff Filter -->
      <div class="col-lg-2 col-md-4 col-6">
        <label class="form-label small fw-semibold text-muted mb-1">Assigned To</label>
        <select name="assigned_to" class="form-select form-select-sm">
          <option value="ALL" <?= $currAssigned === 'ALL' ? 'selected' : '' ?>>All Representatives</option>
          <?php foreach ($staffMembers as $staff): ?>
            <?php $sName = $staff['name'] ?? ($staff['username'] ?? ''); ?>
            <option value="<?= e($sName) ?>" <?= $currAssigned === $sName ? 'selected' : '' ?>><?= e($sName) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Date Range (Start) -->
      <div class="col-lg-1 col-md-4 col-3">
        <label class="form-label small fw-semibold text-muted mb-1">From</label>
        <input type="date" name="start_date" class="form-control form-control-sm" value="<?= e($startDate) ?>">
      </div>

      <!-- Date Range (End) -->
      <div class="col-lg-1 col-md-4 col-3">
        <label class="form-label small fw-semibold text-muted mb-1">To</label>
        <input type="date" name="end_date" class="form-control form-control-sm" value="<?= e($endDate) ?>">
      </div>

      <!-- Action Buttons -->
      <div class="col-lg-1 col-md-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold" title="Apply Filters">
          Filter
        </button>
        <?php if ($q !== '' || $currStatus !== 'ALL' || $currInterest !== 'ALL' || $currAssigned !== 'ALL' || $startDate !== '' || $endDate !== ''): ?>
          <a href="<?= url('admin/callback-enquiries') ?>" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
            <i class="bi bi-x-lg"></i>
          </a>
        <?php endif; ?>
      </div>

    </form>
  </div>
</div>

<!-- Enquiries Data Table -->
<div class="card shadow-sm border-0 rounded-3">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3" style="width: 14%;">Lead ID</th>
            <th style="width: 18%;">Customer Name</th>
            <th style="width: 14%;">Mobile Number</th>
            <th style="width: 15%;">Product Interest</th>
            <th style="width: 13%;">Product Page</th>
            <th style="width: 10%;">Enquiry Time</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 10%;">Assigned To</th>
            <th class="text-end pe-3" style="width: 6%;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($leads)): ?>
            <tr>
              <td colspan="9" class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-2 text-secondary d-block mb-2"></i>
                <div class="fw-bold">No callback enquiries found.</div>
                <div class="small">Try clearing search filters or wait for prospective customers to request assistance.</div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($leads as $lead): ?>
              <?php
                $leadId = $lead['lead_id'] ?? '';
                $name = $lead['customer_name'] ?? 'N/A';
                $mobile = $lead['mobile_number'] ?? '';
                $interest = $lead['product_interest'] ?? 'General';
                $pageUrl = $lead['product_page_url'] ?? '';
                $status = $lead['status'] ?? 'NEW';
                $assigned = $lead['assigned_to'] ?? 'Unassigned';
                $createdAt = strtotime($lead['created_at'] ?? 'now');
                $followUp = !empty($lead['follow_up_date']) ? strtotime($lead['follow_up_date']) : null;
                $isDue = ($followUp && $followUp <= strtotime(date('Y-m-d')) && in_array($status, ['NEW', 'CONTACTED', 'FOLLOW-UP REQUIRED']));
              ?>
              <tr class="<?= $status === 'NEW' ? 'table-warning table-opacity-10' : '' ?>">
                <!-- Lead ID -->
                <td class="ps-3">
                  <a href="<?= url("admin/callback-enquiries/view/{$leadId}") ?>" class="fw-bold text-dark text-decoration-none">
                    <?= e($leadId) ?>
                  </a>
                  <?php if ($isDue): ?>
                    <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.65rem;" title="Follow-up Due!">DUE</span>
                  <?php endif; ?>
                </td>

                <!-- Customer Name -->
                <td>
                  <span class="fw-bold text-dark"><?= e($name) ?></span>
                </td>

                <!-- Mobile Number -->
                <td>
                  <a href="tel:<?= e($mobile) ?>" class="text-decoration-none text-dark fw-semibold" title="Click to call">
                    <i class="bi bi-telephone-fill text-success me-1"></i><?= e($mobile) ?>
                  </a>
                </td>

                <!-- Product Interest -->
                <td>
                  <span class="badge bg-light text-dark border"><?= e($interest) ?></span>
                </td>

                <!-- Product Page -->
                <td>
                  <?php if (!empty($pageUrl)): ?>
                    <a href="<?= e($pageUrl) ?>" target="_blank" class="text-primary text-decoration-none small text-truncate d-inline-block" style="max-width: 140px;" title="<?= e($pageUrl) ?>">
                      <i class="bi bi-box-arrow-up-right me-1"></i><?= e(basename(parse_url($pageUrl, PHP_URL_PATH) ?: 'page')) ?>
                    </a>
                  <?php else: ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                </td>

                <!-- Enquiry Date & Time -->
                <td>
                  <div class="small fw-semibold"><?= date('d M Y', $createdAt) ?></div>
                  <div class="text-muted" style="font-size: 0.75rem;"><?= date('h:i A', $createdAt) ?></div>
                </td>

                <!-- Status -->
                <td>
                  <?= getStatusBadge($status) ?>
                </td>

                <!-- Assigned To -->
                <td>
                  <span class="small <?= $assigned === 'Unassigned' ? 'text-muted fst-italic' : 'fw-semibold text-dark' ?>">
                    <?= e($assigned) ?>
                  </span>
                </td>

                <!-- Action -->
                <td class="text-end pe-3">
                  <a href="<?= url("admin/callback-enquiries/view/{$leadId}") ?>" class="btn btn-sm btn-outline-primary px-2 py-1" title="View details and update follow-up">
                    <i class="bi bi-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
      <div class="d-flex justify-content-between align-items-center p-3 border-top flex-wrap gap-2">
        <div class="small text-muted">
          Showing page <strong><?= $pagination['current_page'] ?></strong> of <strong><?= $pagination['total_pages'] ?></strong> (<?= $pagination['total_items'] ?> total leads)
        </div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0">
            <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
              <?php
                $qParams = array_merge($filters, ['page' => $p]);
                $pageUrl = url('admin/callback-enquiries?' . http_build_query($qParams));
              ?>
              <li class="page-item <?= $p === $pagination['current_page'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $pageUrl ?>"><?= $p ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>

  </div>
</div>
