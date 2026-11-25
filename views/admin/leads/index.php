<?php
declare(strict_types=1);

/**
 * @var array $leads
 * @var array $stats
 * @var string $currentType
 * @var string $currentStatus
 * @var string $q
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0"><i class="bi bi-chat-square-quote-fill text-primary me-2"></i>Leads & Bulk Orders</h4>
    <small class="text-muted">Manage corporate bulk order inquiries, client sales leads, and storefront messages.</small>
  </div>
</div>

<!-- Quick Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-primary border-4">
      <div class="metric-label text-primary">Total Inquiries</div>
      <div class="metric-value text-dark"><?= $stats['total'] ?></div>
      <small class="text-muted">All customer contact & bulk records</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-warning border-4">
      <div class="metric-label text-warning">Bulk / B2B Orders</div>
      <div class="metric-value text-dark"><?= $stats['bulk'] ?></div>
      <small class="text-muted">Commercial & volume purchase leads</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-danger border-4">
      <div class="metric-label text-danger">Unread Leads</div>
      <div class="metric-value text-dark"><?= $stats['unread'] ?></div>
      <small class="text-muted">Awaiting sales team response</small>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="metric-card shadow-sm border-start border-success border-4">
      <div class="metric-label text-success">Closed Inquiries</div>
      <div class="metric-value text-dark"><?= $stats['closed'] ?></div>
      <small class="text-muted">Completed & fulfilled conversations</small>
    </div>
  </div>
</div>

<!-- Type Filter Tabs & Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 border-bottom pb-3">
    <!-- Type Filter Tabs -->
    <ul class="nav nav-pills gap-1">
      <li class="nav-item">
        <a class="nav-link btn-sm <?= ($currentType === 'all') ? 'active bg-dark text-white' : 'text-secondary' ?>" 
           href="<?= url('admin/leads?type=all&status=' . urlencode($currentStatus) . '&q=' . urlencode($q)) ?>">
          <i class="bi bi-grid-fill me-1"></i> All Inquiries
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn-sm <?= ($currentType === 'bulk') ? 'active bg-warning text-dark fw-bold' : 'text-secondary' ?>" 
           href="<?= url('admin/leads?type=bulk&status=' . urlencode($currentStatus) . '&q=' . urlencode($q)) ?>">
          <i class="bi bi-box-seam-fill me-1"></i> Bulk / B2B Orders (<?= $stats['bulk'] ?>)
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn-sm <?= ($currentType === 'general') ? 'active bg-primary text-white' : 'text-secondary' ?>" 
           href="<?= url('admin/leads?type=general&status=' . urlencode($currentStatus) . '&q=' . urlencode($q)) ?>">
          <i class="bi bi-chat-text-fill me-1"></i> General Inquiries
        </a>
      </li>
    </ul>

    <!-- Quick Status Badge Legend -->
    <div class="d-flex align-items-center gap-2 small text-muted">
      <span>Status:</span>
      <span class="badge bg-danger">Unread</span>
      <span class="badge bg-info text-dark">Contacted</span>
      <span class="badge bg-warning text-dark">In Progress</span>
      <span class="badge bg-success">Closed</span>
    </div>
  </div>

  <!-- Search & Filter Form -->
  <form action="<?= url('admin/leads') ?>" method="GET" class="row g-2 align-items-center">
    <input type="hidden" name="type" value="<?= e($currentType) ?>">

    <div class="col-md-5">
      <input type="text" name="q" class="form-control form-control-sm" 
             placeholder="Search by client name, email, phone, company, or message..." 
             value="<?= e($q) ?>">
    </div>

    <div class="col-md-3">
      <select name="status" class="form-select form-select-sm">
        <option value="all" <?= $currentStatus === 'all' ? 'selected' : '' ?>>Filter by Status (All)</option>
        <option value="unread" <?= $currentStatus === 'unread' ? 'selected' : '' ?>>Unread Only</option>
        <option value="contacted" <?= $currentStatus === 'contacted' ? 'selected' : '' ?>>Contacted</option>
        <option value="in_progress" <?= $currentStatus === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="closed" <?= $currentStatus === 'closed' ? 'selected' : '' ?>>Closed</option>
      </select>
    </div>

    <div class="col-md-4 d-flex gap-2">
      <button type="submit" class="btn btn-sm btn-primary px-3">
        <i class="bi bi-search me-1"></i> Filter
      </button>
      <a href="<?= url('admin/leads') ?>" class="btn btn-sm btn-outline-secondary">
        Reset
      </a>
    </div>
  </form>
</div>

<!-- Table Card -->
<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width: 140px;">Type</th>
          <th>Lead / Contact</th>
          <th>Inquiry & Requirement</th>
          <th>Date</th>
          <th>Status</th>
          <th style="width: 150px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
              No leads or inquiries match your filter criteria.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($leads as $lead): ?>
            <?php
              $isBulk = ($lead['inquiry_type'] ?? 'general') === 'bulk';
              $status = $lead['status'] ?? 'unread';
              $statusColors = [
                  'unread' => 'danger',
                  'contacted' => 'info text-dark',
                  'in_progress' => 'warning text-dark',
                  'closed' => 'success',
              ];
              $statusClass = $statusColors[$status] ?? 'secondary';
              $cleanPhone = preg_replace('/\D/', '', (string)($lead['phone'] ?? ''));
              if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 11) {
                  $cleanPhone = '91' . substr($cleanPhone, 1);
              } elseif (strlen($cleanPhone) === 10) {
                  $cleanPhone = '91' . $cleanPhone;
              }
            ?>
            <tr class="<?= $status === 'unread' ? 'table-warning bg-opacity-10' : '' ?>">
              <td>
                <?php if ($isBulk): ?>
                  <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                    <i class="bi bi-box-seam me-1"></i> Bulk B2B
                  </span>
                <?php else: ?>
                  <span class="badge bg-light text-dark border px-2 py-1">
                    <i class="bi bi-chat-text me-1"></i> General
                  </span>
                <?php endif; ?>
                <div class="text-muted small mt-1 font-monospace" style="font-size: 0.7rem;">
                  <?= e($lead['id']) ?>
                </div>
              </td>

              <td>
                <div class="fw-bold text-dark"><?= e($lead['name']) ?></div>
                <?php if (!empty($lead['company'])): ?>
                  <div class="text-primary small fw-semibold">
                    <i class="bi bi-building me-1"></i><?= e($lead['company']) ?>
                  </div>
                <?php endif; ?>
                <div class="small text-muted">
                  <?php if (!empty($lead['phone'])): ?>
                    <span><i class="bi bi-telephone me-1"></i><?= e($lead['phone']) ?></span>
                  <?php endif; ?>
                  <?php if (!empty($lead['email'])): ?>
                    <span class="ms-2"><i class="bi bi-envelope me-1"></i><?= e($lead['email']) ?></span>
                  <?php endif; ?>
                </div>
              </td>

              <td>
                <?php if ($isBulk): ?>
                  <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge bg-dark text-white fw-bold">
                      Qty: <?= e($lead['quantity'] ?? 'N/A') ?> Units
                    </span>
                    <?php if (!empty($lead['project_type'])): ?>
                      <span class="badge bg-light text-dark border">
                        <?= e($lead['project_type']) ?>
                      </span>
                    <?php endif; ?>
                    <?php if (!empty($lead['location'])): ?>
                      <span class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i><?= e($lead['location']) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
                <div class="fw-semibold text-secondary small"><?= e($lead['subject'] ?? 'No Subject') ?></div>
                <div class="text-muted small text-truncate" style="max-width: 380px;">
                  <?= e($lead['message'] ?? '') ?>
                </div>
              </td>

              <td class="text-nowrap small text-muted">
                <?= !empty($lead['created_at']) ? date('d M Y, h:i A', strtotime($lead['created_at'])) : '—' ?>
              </td>

              <td>
                <span class="badge bg-<?= $statusClass ?>">
                  <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </span>
                <?php if (!empty($lead['admin_notes'])): ?>
                  <div class="text-muted small text-truncate mt-1" style="max-width: 150px;" title="<?= e($lead['admin_notes']) ?>">
                    <i class="bi bi-sticky me-1"></i><?= e($lead['admin_notes']) ?>
                  </div>
                <?php endif; ?>
              </td>

              <td style="text-align: right;">
                <div class="d-inline-flex gap-1">
                  <!-- WhatsApp Direct -->
                  <?php if (!empty($cleanPhone)): ?>
                    <a href="https://wa.me/<?= e($cleanPhone) ?>?text=<?= urlencode('Hello ' . $lead['name'] . ', thank you for contacting HOMEIQ regarding your inquiry.') ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-success px-2 py-1" 
                       title="Chat on WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  <?php endif; ?>

                  <!-- View Full Details -->
                  <a href="<?= url('admin/leads/view/' . urlencode($lead['id'])) ?>" 
                     class="btn btn-sm btn-primary px-2 py-1" 
                     title="View Details & Update Status">
                    <i class="bi bi-eye"></i> View
                  </a>

                  <!-- Delete -->
                  <a href="<?= url('admin/leads/delete/' . urlencode($lead['id'])) ?>" 
                     class="btn btn-sm btn-outline-danger px-2 py-1" 
                     onclick="return confirm('Are you sure you want to permanently delete this lead record?');" 
                     title="Delete Lead">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
