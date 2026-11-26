<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $registration
 * @var array $auditLogs
 * @var array $emailLogs
 * @var ?array $suggestedWarranty
 * @var array $rejectionReasons
 */
$regId = $registration['registration_id'] ?? '';
$status = strtoupper((string)($registration['status'] ?? 'PENDING'));
$customer = $registration['customer'] ?? [];
$product = $registration['product'] ?? [];
$purchase = $registration['purchase'] ?? [];
$warranty = $registration['warranty'] ?? [];
$adminReview = $registration['admin_review'] ?? [];
$statusHistory = $registration['status_history'] ?? [];

$statusBadge = match($status) {
  'APPROVED' => 'bg-success text-white',
  'REJECTED' => 'bg-danger text-white',
  default => 'bg-warning text-dark',
};
?>

<!-- Breadcrumb & Top Actions -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <a href="<?= url('admin/warranty-registrations') ?>" class="text-decoration-none text-muted small fw-bold">
      <i class="bi bi-arrow-left me-1"></i> Back to Warranty Registrations
    </a>
    <h2 class="h4 fw-bold text-dark mt-1 mb-0">
      Registration <span class="text-primary font-monospace"><?= e($regId) ?></span>
    </h2>
  </div>

  <div class="d-flex align-items-center gap-2">
    <?php if ($status === 'PENDING'): ?>
      <button type="button" class="btn btn-success fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal">
        <i class="bi bi-check2-circle me-1"></i> Approve Warranty
      </button>
      <button type="button" class="btn btn-outline-danger fw-semibold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="bi bi-x-circle me-1"></i> Reject Registration
      </button>
    <?php elseif ($status === 'REJECTED'): ?>
      <button type="button" class="btn btn-success fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal">
        <i class="bi bi-arrow-repeat me-1"></i> Re-evaluate &amp; Approve
      </button>
    <?php elseif ($status === 'APPROVED'): ?>
      <button type="button" class="btn btn-outline-danger btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="bi bi-slash-circle me-1"></i> Revoke / Reject
      </button>
    <?php endif; ?>
  </div>
</div>

<!-- Status Hero Alert -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
  <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: <?= $status === 'APPROVED' ? 'rgba(16, 185, 129, 0.08)' : ($status === 'REJECTED' ? 'rgba(239, 68, 68, 0.08)' : 'rgba(234, 179, 8, 0.08)') ?>;">
    <div class="d-flex align-items-center gap-3">
      <div class="p-3 rounded-circle <?= $status === 'APPROVED' ? 'bg-success text-white' : ($status === 'REJECTED' ? 'bg-danger text-white' : 'bg-warning text-dark') ?>">
        <i class="bi <?= $status === 'APPROVED' ? 'bi-shield-fill-check' : ($status === 'REJECTED' ? 'bi-x-octagon-fill' : 'bi-hourglass-split') ?> fs-3"></i>
      </div>
      <div>
        <div class="d-flex align-items-center gap-2">
          <h5 class="fw-bold text-dark m-0">Status: <?= e($status) ?></h5>
          <span class="badge <?= $statusBadge ?> px-3 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;"><?= e($status) ?></span>
        </div>
        <p class="text-muted small mb-0 mt-1">
          Submitted on <strong><?= !empty($registration['created_at']) ? date('d M Y, h:i A', strtotime($registration['created_at'])) : 'N/A' ?></strong>
          <?php if (!empty($adminReview['reviewed_at'])): ?>
            &bull; Reviewed on <strong><?= date('d M Y, h:i A', strtotime($adminReview['reviewed_at'])) ?></strong> by <strong><?= e($adminReview['reviewed_by'] ?? 'Admin') ?></strong>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <?php if ($status === 'APPROVED'): ?>
      <div class="text-end">
        <span class="text-muted small d-block">Warranty Active Until</span>
        <span class="fs-5 fw-bold text-success"><?= !empty($warranty['end_date']) ? date('d M Y', strtotime($warranty['end_date'])) : 'Active' ?></span>
        <span class="text-muted small d-block"><?= !empty($warranty['duration_months']) ? "({$warranty['duration_months']} Months Coverage)" : '' ?></span>
      </div>
    <?php elseif ($status === 'REJECTED'): ?>
      <div class="text-end">
        <span class="text-danger small fw-bold d-block">Rejection Reason</span>
        <span class="fw-bold text-dark"><?= e($adminReview['rejection_reason'] ?? 'Not Specified') ?></span>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4">
  <!-- Left Column: Details (Customer, Product, Purchase) -->
  <div class="col-lg-8">
    <!-- Customer Details Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-person-badge text-warning me-2"></i>Customer Information</h6>
        <span class="badge bg-light text-secondary">Verified Record</span>
      </div>
      <div class="card-body pt-0">
        <div class="row g-3">
          <div class="col-md-4">
            <span class="text-muted small d-block">Full Name</span>
            <span class="fw-bold text-dark"><?= e($customer['full_name'] ?? 'N/A') ?></span>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Mobile Number</span>
            <div class="d-flex align-items-center gap-2">
              <span class="fw-bold text-dark">+91 <?= e($customer['mobile_number'] ?? 'N/A') ?></span>
              <a href="tel:+91<?= e($customer['mobile_number'] ?? '') ?>" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Call Customer"><i class="bi bi-telephone"></i></a>
              <a href="https://wa.me/91<?= e($customer['mobile_number'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-success py-0 px-2" title="WhatsApp Message"><i class="bi bi-whatsapp"></i></a>
            </div>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Email Address</span>
            <?php if (!empty($customer['email'])): ?>
              <span class="fw-bold text-dark"><?= e($customer['email']) ?></span>
              <a href="mailto:<?= e($customer['email']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2 ms-1" title="Email Customer"><i class="bi bi-envelope"></i></a>
            <?php else: ?>
              <span class="text-muted fst-italic">Not provided by customer</span>
            <?php endif; ?>
          </div>
          <div class="col-md-6">
            <span class="text-muted small d-block">Genuine Purchase Declaration</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i> Confirmed by Customer</span>
          </div>
          <div class="col-md-6">
            <span class="text-muted small d-block">Marketing Consent</span>
            <span class="badge <?= !empty($registration['marketing_consent']) ? 'bg-info-subtle text-info border' : 'bg-light text-muted border' ?>">
              <?= !empty($registration['marketing_consent']) ? 'Opted-In' : 'No Consent' ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Details Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-cpu text-warning me-2"></i>Product Information</h6>
      </div>
      <div class="card-body pt-0">
        <div class="row g-3">
          <div class="col-md-4">
            <span class="text-muted small d-block">Product Category</span>
            <span class="badge bg-primary bg-opacity-10 text-primary fs-6 fw-bold"><?= e($product['category'] ?? 'N/A') ?></span>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Model Name / SKU</span>
            <span class="fw-bold text-dark"><?= e($product['model'] ?? 'N/A') ?></span>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Serial Number</span>
            <?php if (!empty($product['serial_number'])): ?>
              <span class="fw-bold font-monospace text-dark fs-6"><?= e($product['serial_number']) ?></span>
            <?php else: ?>
              <span class="badge bg-warning-subtle text-warning-emphasis border"><i class="bi bi-exclamation-triangle me-1"></i> Serial Unavailable</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($product['serial_unavailable']) && !empty($product['alternative_notes'])): ?>
            <div class="col-12">
              <div class="p-3 bg-light rounded-3 border">
                <span class="text-muted small d-block fw-bold mb-1"><i class="bi bi-sticky me-1"></i>Customer Alternative Identification Notes:</span>
                <p class="mb-0 text-dark small" style="white-space: pre-wrap;"><?= e($product['alternative_notes']) ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Purchase Information & Invoice Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-receipt-cutoff text-warning me-2"></i>Purchase Details &amp; Tax Invoice</h6>
      </div>
      <div class="card-body pt-0">
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <span class="text-muted small d-block">Purchase Platform</span>
            <span class="badge bg-light text-dark border fs-6"><?= e($purchase['platform'] ?? 'N/A') ?></span>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Purchase Date</span>
            <span class="fw-bold text-dark"><?= !empty($purchase['purchase_date']) ? date('d M Y', strtotime($purchase['purchase_date'])) : 'N/A' ?></span>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Order ID / Invoice No</span>
            <span class="fw-bold font-monospace text-dark"><?= e($purchase['order_id'] ?? 'N/A') ?></span>
          </div>
        </div>

        <!-- Invoice Preview Banner -->
        <?php if (!empty($purchase['invoice_path'])): 
          $ext = strtolower(pathinfo((string)$purchase['invoice_original_name'], PATHINFO_EXTENSION));
          $isImage = in_array($ext, ['jpg', 'jpeg', 'png'], true);
        ?>
          <div class="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
              <i class="bi <?= $isImage ? 'bi-file-earmark-image text-primary' : 'bi-file-earmark-pdf text-danger' ?> fs-1"></i>
              <div>
                <div class="fw-bold text-dark"><?= e($purchase['invoice_original_name'] ?? 'Purchase_Invoice') ?></div>
                <small class="text-muted">
                  <?= !empty($purchase['invoice_size']) ? round($purchase['invoice_size'] / (1024 * 1024), 2) . ' MB' : 'Protected File' ?> &bull; <?= strtoupper($ext) ?>
                </small>
              </div>
            </div>

            <div class="d-flex gap-2">
              <a href="<?= url("admin/warranty-registrations/invoice/{$regId}") ?>" target="_blank" class="btn btn-sm btn-dark px-3 fw-semibold">
                <i class="bi bi-eye me-1"></i> Preview Invoice
              </a>
              <a href="<?= url("admin/warranty-registrations/invoice/{$regId}?download=1") ?>" class="btn btn-sm btn-outline-secondary px-3">
                <i class="bi bi-download me-1"></i> Download
              </a>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-warning mb-0 small">No invoice file recorded on this registration.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Status Change History & Audit Logs -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-clock-history text-warning me-2"></i>Status Change History &amp; Audit Trail</h6>
      </div>
      <div class="card-body pt-0">
        <?php if (empty($auditLogs)): ?>
          <div class="text-muted small py-2">No audit log records for this registration.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0 small">
              <thead class="table-light">
                <tr>
                  <th>Timestamp</th>
                  <th>Action</th>
                  <th>Actor</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($auditLogs as $log): ?>
                  <tr>
                    <td class="text-nowrap"><?= date('d M Y, h:i A', strtotime($log['created_at'] ?? 'now')) ?></td>
                    <td>
                      <span class="badge bg-secondary bg-opacity-10 text-dark"><?= e($log['action'] ?? '') ?></span>
                    </td>
                    <td class="fw-semibold text-dark"><?= e($log['actor'] ?? '') ?></td>
                    <td class="text-muted"><?= e($log['details'] ?? '') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Right Column: Admin Decision Card & Email Notifications History -->
  <div class="col-lg-4">
    <!-- Admin Review Summary Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-clipboard-check text-warning me-2"></i>Admin Decision &amp; Notes</h6>
      </div>
      <div class="card-body pt-0">
        <div class="mb-3">
          <span class="text-muted small d-block">Current Status</span>
          <span class="badge <?= $statusBadge ?> px-3 py-1 fs-6 fw-bold"><?= e($status) ?></span>
        </div>

        <?php if ($status === 'APPROVED'): ?>
          <div class="mb-3 p-3 bg-success bg-opacity-10 border border-success-subtle rounded-3">
            <div class="text-success small fw-bold mb-1"><i class="bi bi-shield-check me-1"></i>Active Coverage Dates:</div>
            <div class="small text-dark"><strong>Start:</strong> <?= !empty($warranty['start_date']) ? date('d M Y', strtotime($warranty['start_date'])) : 'N/A' ?></div>
            <div class="small text-dark"><strong>End:</strong> <?= !empty($warranty['end_date']) ? date('d M Y', strtotime($warranty['end_date'])) : 'N/A' ?></div>
            <div class="small text-dark"><strong>Duration:</strong> <?= e((string)($warranty['duration_months'] ?? '12')) ?> Months</div>
          </div>
        <?php elseif ($status === 'REJECTED'): ?>
          <div class="mb-3 p-3 bg-danger bg-opacity-10 border border-danger-subtle rounded-3">
            <div class="text-danger small fw-bold mb-1"><i class="bi bi-exclamation-octagon me-1"></i>Rejection Reason:</div>
            <div class="small fw-bold text-dark"><?= e($adminReview['rejection_reason'] ?? 'N/A') ?></div>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <span class="text-muted small d-block">Admin Reviewer Notes</span>
          <p class="mb-0 text-dark small bg-light p-2 rounded-2 border">
            <?= !empty($adminReview['notes']) ? nl2br(e($adminReview['notes'])) : '<span class="text-muted fst-italic">No review notes provided.</span>' ?>
          </p>
        </div>

        <?php if (!empty($adminReview['reviewed_by'])): ?>
          <div class="text-muted small pt-2 border-top">
            Reviewed by <strong><?= e($adminReview['reviewed_by']) ?></strong> on <?= date('d M Y, h:i A', strtotime($adminReview['reviewed_at'] ?? 'now')) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Email Notification History Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark m-0"><i class="bi bi-envelope-check text-warning me-2"></i>Email Notifications</h6>
        <span class="badge bg-light text-dark border"><?= count($emailLogs) ?></span>
      </div>
      <div class="card-body pt-0">
        <?php if (empty($emailLogs)): ?>
          <div class="text-muted small py-3 text-center">
            <i class="bi bi-envelope-x fs-2 d-block text-secondary opacity-50 mb-1"></i>
            No notification emails recorded yet for this registration.
          </div>
        <?php else: ?>
          <div class="list-group list-group-flush small">
            <?php foreach ($emailLogs as $elog): 
              $isSent = ($elog['status'] ?? '') === 'SENT';
            ?>
              <div class="list-group-item px-0 py-2">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <span class="badge <?= $isSent ? 'bg-success' : 'bg-danger' ?> py-1 px-2" style="font-size: 0.68rem;">
                      <?= e($elog['status'] ?? 'UNKNOWN') ?>
                    </span>
                    <strong class="text-dark ms-1" style="font-size: 0.8rem;"><?= e($elog['email_type'] ?? 'Notification') ?></strong>
                  </div>
                  <small class="text-muted"><?= date('d M, h:i A', strtotime($elog['sent_at'] ?? 'now')) ?></small>
                </div>
                <div class="text-muted mt-1" style="font-size: 0.75rem;">
                  To: <?= e($elog['to_email'] ?? '') ?>
                </div>
                <?php if (!$isSent): ?>
                  <div class="text-danger small mt-1">Error: <?= e($elog['error'] ?? 'Delivery failed') ?></div>
                  <!-- Resend / Retry Button -->
                  <form method="POST" action="<?= url("admin/warranty-registrations/resend-email/{$regId}") ?>" class="mt-2">
                    <?= csrf_field() ?>
                    <input type="hidden" name="log_id" value="<?= e($elog['id'] ?? '') ?>">
                    <button type="submit" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;">
                      <i class="bi bi-arrow-repeat me-1"></i> Retry Sending Email
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     APPROVE MODAL
     ========================================== -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <form method="POST" action="<?= url("admin/warranty-registrations/approve/{$regId}") ?>">
        <?= csrf_field() ?>
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark" id="approveModalLabel">
            <i class="bi bi-shield-check text-success me-2"></i>Approve Warranty Registration
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          <p class="text-muted small">
            Verifying registration for <strong class="text-dark"><?= e($customer['full_name'] ?? '') ?></strong> (<?= e($product['model'] ?? '') ?>).
            Approving will activate the official warranty and email the customer.
          </p>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Warranty Duration (Months)</label>
            <input type="number" name="duration_months" class="form-control" value="<?= e((string)($suggestedWarranty['duration_months'] ?? 12)) ?>" min="1" max="120" required>
            <small class="text-muted">Calculated from verified purchase date: <?= e($purchase['purchase_date'] ?? date('Y-m-d')) ?></small>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold text-dark small">Start Date</label>
              <input type="date" name="start_date" class="form-control" value="<?= e($suggestedWarranty['start_date'] ?? ($purchase['purchase_date'] ?? date('Y-m-d'))) ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold text-dark small">End Date</label>
              <input type="date" name="end_date" class="form-control" value="<?= e($suggestedWarranty['end_date'] ?? date('Y-m-d', strtotime('+12 months'))) ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Admin Verification Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="e.g. Purchase invoice and serial verified against distributor records."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success fw-bold px-4">
            <i class="bi bi-check2 me-1"></i> Confirm &amp; Approve Warranty
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ==========================================
     REJECT MODAL
     ========================================== -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <form method="POST" action="<?= url("admin/warranty-registrations/reject/{$regId}") ?>">
        <?= csrf_field() ?>
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-danger" id="rejectModalLabel">
            <i class="bi bi-x-circle text-danger me-2"></i>Reject Warranty Registration
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          <p class="text-muted small">
            Rejecting this submission will update status to <strong>REJECTED</strong> and notify the customer with instructions on how to resubmit.
          </p>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Rejection Reason <span class="text-danger">*</span></label>
            <select name="rejection_reason" class="form-select" required>
              <option value="">-- Select Rejection Reason --</option>
              <?php foreach ($rejectionReasons as $rReason): ?>
                <option value="<?= e($rReason) ?>"><?= e($rReason) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Specific Admin Explanation / Feedback</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Provide actionable feedback for the customer (e.g. invoice image is blurry / serial number mismatch)."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger fw-bold px-4">
            <i class="bi bi-x-octagon me-1"></i> Confirm Rejection
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
