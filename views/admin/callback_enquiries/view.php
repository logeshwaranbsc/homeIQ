<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $lead
 * @var array $auditTrail
 * @var array $staffMembers
 * @var array $matchedOrders
 */

$leadId = $lead['lead_id'] ?? '';
$name = $lead['customer_name'] ?? 'N/A';
$mobile = $lead['mobile_number'] ?? '';
$interest = $lead['product_interest'] ?? 'General';
$pageUrl = $lead['product_page_url'] ?? '';
$status = $lead['status'] ?? 'NEW';
$notes = $lead['admin_notes'] ?? '';
$assigned = $lead['assigned_to'] ?? '';
$followUp = $lead['follow_up_date'] ?? '';
$createdAt = strtotime($lead['created_at'] ?? 'now');
$updatedAt = strtotime($lead['updated_at'] ?? 'now');
$history = $lead['history'] ?? [];

function getStatusBadgeLarge(string $status): string {
    return match (strtoupper($status)) {
        'NEW' => '<span class="badge bg-primary fs-6 px-3 py-2"><i class="bi bi-star-fill me-1"></i>New Enquiry</span>',
        'CONTACTED' => '<span class="badge bg-info text-dark fs-6 px-3 py-2"><i class="bi bi-telephone-outbound me-1"></i>Contacted</span>',
        'FOLLOW-UP REQUIRED', 'FOLLOW_UP' => '<span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-clock-history me-1"></i>Follow-up Required</span>',
        'CONVERTED' => '<span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-bag-check-fill me-1"></i>Converted Customer</span>',
        'NOT INTERESTED', 'NOT_INTERESTED' => '<span class="badge bg-secondary fs-6 px-3 py-2"><i class="bi bi-slash-circle me-1"></i>Not Interested</span>',
        'UNREACHABLE' => '<span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-telephone-x me-1"></i>Unreachable</span>',
        default => '<span class="badge bg-light text-dark border fs-6 px-3 py-2">' . htmlspecialchars($status) . '</span>'
    };
}
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-1 small">
        <li class="breadcrumb-item"><a href="<?= url('admin/callback-enquiries') ?>" class="text-decoration-none">Callback Enquiries</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($leadId) ?></li>
      </ol>
    </nav>
    <div class="d-flex align-items-center gap-3 flex-wrap">
      <h2 class="h4 fw-bold text-dark mb-0">
        <?= e($leadId) ?> &mdash; <?= e($name) ?>
      </h2>
      <?= getStatusBadgeLarge($status) ?>
    </div>
  </div>
  <div class="d-flex gap-2">
    <a href="tel:<?= e($mobile) ?>" class="btn btn-success fw-bold px-3">
      <i class="bi bi-telephone-fill me-1"></i> Call Customer
    </a>
    <a href="<?= url('admin/callback-enquiries') ?>" class="btn btn-outline-secondary px-3">
      <i class="bi bi-arrow-left me-1"></i> Back
    </a>
  </div>
</div>

<div class="row g-4">
  
  <!-- Left Column: Details & Follow-up Form -->
  <div class="col-lg-8">
    
    <!-- Customer & Enquiry Information Cards -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
      <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-lines-fill text-primary me-2"></i>Customer &amp; Enquiry Details</h6>
        <span class="text-muted small">Submitted <?= date('d M Y, h:i A', $createdAt) ?></span>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          
          <div class="col-sm-6">
            <label class="text-muted small text-uppercase fw-bold">Customer Name</label>
            <div class="fs-5 fw-bold text-dark mt-1"><?= e($name) ?></div>
          </div>

          <div class="col-sm-6">
            <label class="text-muted small text-uppercase fw-bold">Mobile Number</label>
            <div class="mt-1 d-flex align-items-center gap-2">
              <a href="tel:<?= e($mobile) ?>" class="fs-5 fw-bold text-success text-decoration-none">
                <i class="bi bi-telephone-outbound me-1"></i><?= e($mobile) ?>
              </a>
              <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="navigator.clipboard.writeText('<?= e($mobile) ?>'); alert('Mobile number copied!');" title="Copy Number">
                <i class="bi bi-clipboard"></i>
              </button>
            </div>
          </div>

          <div class="col-sm-6">
            <label class="text-muted small text-uppercase fw-bold">Product of Interest</label>
            <div class="mt-1">
              <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-1 border border-primary border-opacity-25">
                <?= e($interest) ?>
              </span>
            </div>
          </div>

          <div class="col-sm-6">
            <label class="text-muted small text-uppercase fw-bold">Enquiry Date &amp; Time</label>
            <div class="text-dark fw-semibold mt-1">
              <i class="bi bi-calendar3 me-1 text-muted"></i><?= date('D, d M Y', $createdAt) ?> 
              <span class="text-muted ms-1">(<?= date('h:i A', $createdAt) ?>)</span>
            </div>
          </div>

          <div class="col-12">
            <label class="text-muted small text-uppercase fw-bold">Product Page Visited</label>
            <div class="mt-1">
              <?php if (!empty($pageUrl)): ?>
                <a href="<?= e($pageUrl) ?>" target="_blank" class="btn btn-sm btn-outline-dark text-decoration-none text-truncate d-inline-flex align-items-center" style="max-width: 100%;">
                  <i class="bi bi-box-arrow-up-right me-2 text-primary"></i>
                  <span class="text-truncate"><?= e($pageUrl) ?></span>
                </a>
              <?php else: ?>
                <span class="text-muted">Not captured</span>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Verified Order Conversion Verification -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
      <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark">
          <i class="bi bi-bag-check text-success me-2"></i>Order Conversion Verification
        </h6>
        <?php if (!empty($matchedOrders)): ?>
          <span class="badge bg-success-subtle text-success border border-success fw-bold px-2 py-1">
            <i class="bi bi-check-circle-fill me-1"></i>Order Verified
          </span>
        <?php else: ?>
          <span class="badge bg-light text-muted border px-2 py-1">No Orders Detected</span>
        <?php endif; ?>
      </div>
      <div class="card-body p-4">
        <?php if (!empty($matchedOrders)): ?>
          <div class="alert alert-success border-0 bg-success bg-opacity-10 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div>
              <strong>Genuine Order Found!</strong> Customer placed <?= count($matchedOrders) ?> online order<?= count($matchedOrders) === 1 ? '' : 's' ?> using mobile <strong><?= e($mobile) ?></strong>.
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Order #</th>
                  <th>Order Date</th>
                  <th>Amount</th>
                  <th>Payment Status</th>
                  <th>Order Status</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($matchedOrders as $ord): ?>
                  <tr>
                    <td class="fw-bold text-dark"><?= e($ord['order_number'] ?? '') ?></td>
                    <td><?= date('d M Y', strtotime($ord['created_at'] ?? 'now')) ?></td>
                    <td class="fw-bold text-success"><?= format_price((float)($ord['total_amount'] ?? 0)) ?></td>
                    <td>
                      <span class="badge bg-info-subtle text-info border"><?= e($ord['payment_status'] ?? 'Pending') ?></span>
                    </td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary border"><?= e($ord['status'] ?? 'Processing') ?></span>
                    </td>
                    <td class="text-end">
                      <a href="<?= url('admin/orders/view/' . ($ord['id'] ?? '')) ?>" class="btn btn-xs btn-outline-primary py-0 px-2" target="_blank">
                        View Order <i class="bi bi-arrow-up-right"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-muted small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            No online store orders have been placed using mobile <strong><?= e($mobile) ?></strong> yet. When the customer completes a purchase, it will appear here automatically for order verification.
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sales Team Follow-up & Status Update Form -->
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Update Follow-up &amp; Sales Status</h6>
      </div>
      <div class="card-body p-4">
        <form action="<?= url("admin/callback-enquiries/update/{$leadId}") ?>" method="POST">
          <?= csrf_field() ?>

          <div class="row g-3 mb-3">
            
            <!-- Status Dropdown -->
            <div class="col-md-6">
              <label for="statusSelect" class="form-label fw-bold small text-muted">Lead Status <span class="text-danger">*</span></label>
              <select name="status" id="statusSelect" class="form-select" required>
                <option value="NEW" <?= $status === 'NEW' ? 'selected' : '' ?>>NEW (Uncontacted)</option>
                <option value="CONTACTED" <?= $status === 'CONTACTED' ? 'selected' : '' ?>>CONTACTED (Spoke with customer)</option>
                <option value="FOLLOW-UP REQUIRED" <?= in_array($status, ['FOLLOW-UP REQUIRED', 'FOLLOW_UP']) ? 'selected' : '' ?>>FOLLOW-UP REQUIRED (Call scheduled)</option>
                <option value="CONVERTED" <?= $status === 'CONVERTED' ? 'selected' : '' ?>>CONVERTED (Order placed)</option>
                <option value="NOT INTERESTED" <?= in_array($status, ['NOT INTERESTED', 'NOT_INTERESTED']) ? 'selected' : '' ?>>NOT INTERESTED (Declined)</option>
                <option value="UNREACHABLE" <?= $status === 'UNREACHABLE' ? 'selected' : '' ?>>UNREACHABLE (Ringing/Switched off)</option>
              </select>
            </div>

            <!-- Assigned Representative -->
            <div class="col-md-6">
              <label for="assignedSelect" class="form-label fw-bold small text-muted">Assigned Representative</label>
              <select name="assigned_to" id="assignedSelect" class="form-select">
                <option value="">Unassigned</option>
                <?php foreach ($staffMembers as $staff): ?>
                  <?php $sName = $staff['name'] ?? ($staff['username'] ?? ''); ?>
                  <option value="<?= e($sName) ?>" <?= $assigned === $sName ? 'selected' : '' ?>>
                    <?= e($sName) ?> (<?= e($staff['role'] ?? 'Staff') ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Next Follow-up Date -->
            <div class="col-md-6">
              <label for="followUpDate" class="form-label fw-bold small text-muted">Next Follow-up Date</label>
              <input type="date" name="follow_up_date" id="followUpDate" class="form-control" value="<?= e($followUp) ?>">
              <small class="text-muted" style="font-size: 0.75rem;">Set a date to trigger dashboard follow-up reminders.</small>
            </div>

            <!-- Order Reference Note (Optional for Converted) -->
            <div class="col-md-6">
              <label for="orderRef" class="form-label fw-bold small text-muted">Order Reference (Optional)</label>
              <input type="text" name="order_reference" id="orderRef" class="form-control" placeholder="e.g. HIQ-20261126-A1B2" value="<?= !empty($matchedOrders) ? e($matchedOrders[0]['order_number'] ?? '') : '' ?>">
              <small class="text-muted" style="font-size: 0.75rem;">Reference invoice/order number when marking as converted.</small>
            </div>

            <!-- Admin Follow-up Notes -->
            <div class="col-12">
              <label for="adminNotes" class="form-label fw-bold small text-muted">Sales Follow-up Notes</label>
              <textarea name="admin_notes" 
                        id="adminNotes" 
                        rows="3" 
                        class="form-control" 
                        placeholder="Add notes from phone conversation, customer requirements, quotation sent, preferred model..."><?= e($notes) ?></textarea>
            </div>

          </div>

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
            <span class="text-muted small">
              <i class="bi bi-clock me-1"></i>Last updated: <?= date('d M Y, h:i A', $updatedAt) ?>
            </span>
            <button type="submit" class="btn btn-primary px-4 fw-bold">
              <i class="bi bi-check2-circle me-1"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>

  <!-- Right Column: Timeline & Audit History -->
  <div class="col-lg-4">
    
    <!-- Status & Action Timeline -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history text-info me-2"></i>Status History</h6>
      </div>
      <div class="card-body p-3">
        <?php if (empty($history)): ?>
          <p class="text-muted small mb-0">No status changes recorded yet.</p>
        <?php else: ?>
          <ul class="list-unstyled mb-0 position-relative ps-3" style="border-left: 2px solid #E2E8F0;">
            <?php foreach (array_reverse($history) as $h): ?>
              <li class="mb-3 position-relative">
                <span class="position-absolute translate-middle p-1 bg-primary border border-light rounded-circle" style="left: -17px; top: 8px;"></span>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold small text-dark"><?= e($h['status'] ?? 'UPDATE') ?></span>
                  <span class="text-muted" style="font-size: 0.7rem;"><?= date('d M, h:i A', strtotime($h['changed_at'] ?? 'now')) ?></span>
                </div>
                <div class="text-secondary small mt-1" style="font-size: 0.78rem;">
                  <?= e($h['notes'] ?? '') ?>
                </div>
                <div class="text-muted" style="font-size: 0.7rem;">By: <?= e($h['changed_by'] ?? 'System') ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- Audit Log Card -->
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-text text-secondary me-2"></i>Audit Trail</h6>
      </div>
      <div class="card-body p-3">
        <?php if (empty($auditTrail)): ?>
          <p class="text-muted small mb-0">No audit events logged.</p>
        <?php else: ?>
          <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
            <table class="table table-sm table-borderless small mb-0">
              <tbody>
                <?php foreach ($auditTrail as $a): ?>
                  <tr class="border-bottom">
                    <td class="ps-0 py-2">
                      <span class="badge bg-light text-dark border"><?= e($a['action'] ?? '') ?></span>
                      <div class="text-muted mt-1" style="font-size: 0.72rem;"><?= e($a['details'] ?? '') ?></div>
                      <div class="text-muted" style="font-size: 0.68rem;"><?= e($a['actor'] ?? '') ?> &bull; <?= date('d M, h:i A', strtotime($a['created_at'] ?? 'now')) ?></div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

</div>
