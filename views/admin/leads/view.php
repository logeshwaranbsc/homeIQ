<?php
declare(strict_types=1);

/**
 * @var array $lead
 */

$isBulk = ($lead['inquiry_type'] ?? 'general') === 'bulk';
$status = $lead['status'] ?? 'unread';

$statusColors = [
    'unread' => 'danger',
    'contacted' => 'info text-dark',
    'in_progress' => 'warning text-dark',
    'closed' => 'success',
];
$statusBadge = $statusColors[$status] ?? 'secondary';

$cleanPhone = preg_replace('/\D/', '', (string)($lead['phone'] ?? ''));
if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 11) {
    $cleanPhone = '91' . substr($cleanPhone, 1);
} elseif (strlen($cleanPhone) === 10) {
    $cleanPhone = '91' . $cleanPhone;
}
?>

<!-- Header & Back Navigation -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <div class="d-flex align-items-center gap-2 mb-1">
      <a href="<?= url('admin/leads') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Leads
      </a>
      <span class="badge bg-<?= $statusBadge ?> px-3 py-1 fs-6">
        <?= ucfirst(str_replace('_', ' ', $status)) ?>
      </span>
      <?php if ($isBulk): ?>
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 fs-6">
          <i class="bi bi-box-seam me-1"></i> Bulk B2B Order Lead
        </span>
      <?php else: ?>
        <span class="badge bg-light text-dark border px-3 py-1 fs-6">
          <i class="bi bi-chat-text me-1"></i> General Storefront Inquiry
        </span>
      <?php endif; ?>
    </div>
    <h4 class="fw-bold m-0"><?= e($lead['name']) ?> <?= !empty($lead['company']) ? '— ' . e($lead['company']) : '' ?></h4>
    <small class="text-muted">Inquiry ID: <code><?= e($lead['id']) ?></code> • Received on <?= !empty($lead['created_at']) ? date('d F Y \a\t h:i A', strtotime($lead['created_at'])) : '—' ?></small>
  </div>

  <div class="d-flex gap-2">
    <?php if (!empty($cleanPhone)): ?>
      <a href="https://wa.me/<?= e($cleanPhone) ?>?text=<?= urlencode('Hello ' . $lead['name'] . ', thank you for reaching out to HOMEIQ Smart Security. We are following up on your inquiry.') ?>" 
         target="_blank" 
         class="btn btn-success">
        <i class="bi bi-whatsapp me-1"></i> Open in WhatsApp
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4">
  <!-- Left Column: Details & Message -->
  <div class="col-lg-8">
    <?php if ($isBulk): ?>
      <!-- Bulk Order Specifications Card -->
      <div class="admin-form-card shadow-sm mb-4 border-top border-warning border-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
          <span><i class="bi bi-boxes text-warning me-2"></i>Commercial / Bulk Project Requirements</span>
          <span class="badge bg-dark fs-6 px-3 py-2">
            Estimated Quantity: <?= e($lead['quantity'] ?? 'N/A') ?> Units
          </span>
        </h5>

        <div class="row g-3 py-2 bg-light rounded p-3 mb-3">
          <div class="col-sm-6">
            <span class="text-muted small d-block">Company / Organization:</span>
            <strong class="fs-6 text-dark"><i class="bi bi-building me-1 text-primary"></i><?= e($lead['company'] ?? 'Not specified') ?></strong>
          </div>

          <div class="col-sm-6">
            <span class="text-muted small d-block">Project Type / Sector:</span>
            <strong class="fs-6 text-dark"><i class="bi bi-tag me-1 text-info"></i><?= e($lead['project_type'] ?? 'General') ?></strong>
          </div>

          <div class="col-sm-6">
            <span class="text-muted small d-block">Delivery / Site Location:</span>
            <strong class="fs-6 text-dark"><i class="bi bi-geo-alt me-1 text-danger"></i><?= e($lead['location'] ?? 'Not specified') ?></strong>
          </div>

          <div class="col-sm-6">
            <span class="text-muted small d-block">Procurement Timeline:</span>
            <strong class="fs-6 text-dark"><i class="bi bi-calendar-event me-1 text-success"></i><?= e($lead['timeline'] ?? 'Not specified') ?></strong>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Message Body Card -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2 d-flex align-items-center justify-content-between">
        <span><i class="bi bi-envelope-open me-2 text-primary"></i>Message Details</span>
        <span class="badge bg-light text-secondary border"><?= e($lead['subject'] ?? 'Inquiry') ?></span>
      </h6>

      <div class="p-3 bg-light rounded mb-3 border">
        <div class="text-muted small mb-1 fw-bold">Subject:</div>
        <div class="fw-semibold text-dark mb-3"><?= e($lead['subject'] ?? 'No Subject') ?></div>

        <div class="text-muted small mb-1 fw-bold">Message Content:</div>
        <div class="lead-message-text" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6; color: #212529;">
          <?= nl2br(e($lead['message'] ?? 'No message provided.')) ?>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between small text-muted">
        <div><i class="bi bi-pc-display me-1"></i>Origin IP: <code><?= e($lead['ip'] ?? 'Unknown') ?></code></div>
        <div><i class="bi bi-clock-history me-1"></i>Last Updated: <?= !empty($lead['updated_at']) ? date('d M Y, h:i A', strtotime($lead['updated_at'])) : '—' ?></div>
      </div>
    </div>

    <!-- Client / Inquirer Contact Information -->
    <div class="admin-form-card shadow-sm">
      <h6 class="fw-bold mb-3 border-bottom pb-2">
        <i class="bi bi-person-lines-fill me-2 text-secondary"></i>Contact Person
      </h6>

      <div class="row g-3">
        <div class="col-sm-4">
          <small class="text-muted d-block">Full Name</small>
          <strong class="text-dark"><?= e($lead['name']) ?></strong>
        </div>

        <div class="col-sm-4">
          <small class="text-muted d-block">Phone / WhatsApp</small>
          <?php if (!empty($lead['phone'])): ?>
            <a href="tel:<?= e($lead['phone']) ?>" class="text-decoration-none fw-bold text-primary">
              <i class="bi bi-telephone me-1"></i><?= e($lead['phone']) ?>
            </a>
          <?php else: ?>
            <span class="text-muted">—</span>
          <?php endif; ?>
        </div>

        <div class="col-sm-4">
          <small class="text-muted d-block">Email Address</small>
          <?php if (!empty($lead['email'])): ?>
            <a href="mailto:<?= e($lead['email']) ?>" class="text-decoration-none fw-bold text-primary">
              <i class="bi bi-envelope me-1"></i><?= e($lead['email']) ?>
            </a>
          <?php else: ?>
            <span class="text-muted">Not provided</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Status & Follow-up Actions -->
  <div class="col-lg-4">
    <!-- Status Management Form -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">
        <i class="bi bi-sliders me-2 text-primary"></i>Status & Lead Follow-up
      </h6>

      <form action="<?= url('admin/leads/status/' . urlencode($lead['id'])) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label small fw-bold">Inquiry Status</label>
          <select name="status" class="form-select">
            <option value="unread" <?= $status === 'unread' ? 'selected' : '' ?>>🔴 Unread / New Lead</option>
            <option value="contacted" <?= $status === 'contacted' ? 'selected' : '' ?>>🔵 Contacted (Called / Messaged)</option>
            <option value="in_progress" <?= $status === 'in_progress' ? 'selected' : '' ?>>🟡 In Progress (Quote Sent / Discussion)</option>
            <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>🟢 Closed / Fulfilled</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Internal Admin Notes</label>
          <textarea name="admin_notes" rows="4" class="form-control form-control-sm" 
                    placeholder="Log conversation notes, quote amounts, callback times, or follow-up status..."><?= e($lead['admin_notes'] ?? '') ?></textarea>
          <small class="text-muted" style="font-size: 0.75rem;">Visible to admin team only.</small>
        </div>

        <?php if (!empty($lead['last_handled_at'])): ?>
          <div class="small text-muted mb-3">
            <i class="bi bi-pencil-square me-1"></i>Last note update: <?= date('d M Y, h:i A', strtotime($lead['last_handled_at'])) ?>
          </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-check2-circle me-1"></i> Save Status & Notes
        </button>
      </form>
    </div>

    <!-- Quick Communication Shortcuts -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">
        <i class="bi bi-telephone-outbound me-2 text-success"></i>Quick Direct Actions
      </h6>

      <div class="d-grid gap-2">
        <?php if (!empty($cleanPhone)): ?>
          <a href="https://wa.me/<?= e($cleanPhone) ?>?text=<?= urlencode('Hello ' . $lead['name'] . ', this is HOMEIQ Smart Security regarding your inquiry.') ?>" 
             target="_blank" 
             class="btn btn-outline-success text-start">
            <i class="bi bi-whatsapp me-2 text-success"></i> Send WhatsApp Message
          </a>

          <a href="tel:<?= e($lead['phone']) ?>" class="btn btn-outline-primary text-start">
            <i class="bi bi-telephone-fill me-2 text-primary"></i> Call <?= e($lead['phone']) ?>
          </a>
        <?php endif; ?>

        <?php if (!empty($lead['email'])): ?>
          <a href="mailto:<?= e($lead['email']) ?>?subject=<?= urlencode('Re: ' . ($lead['subject'] ?? 'HOMEIQ Inquiry')) ?>" 
             class="btn btn-outline-secondary text-start">
            <i class="bi bi-envelope-fill me-2 text-secondary"></i> Send Email Reply
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Danger Zone: Delete -->
    <div class="admin-form-card shadow-sm border border-danger border-opacity-25 bg-danger bg-opacity-10">
      <h6 class="fw-bold text-danger mb-2">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Danger Zone
      </h6>
      <p class="small text-muted mb-3">Permanently delete this inquiry and all associated admin notes. This action cannot be undone.</p>
      <a href="<?= url('admin/leads/delete/' . urlencode($lead['id'])) ?>" 
         class="btn btn-sm btn-danger w-100" 
         onclick="return confirm('Are you sure you want to permanently delete this inquiry?');">
        <i class="bi bi-trash me-1"></i> Delete Lead Permanently
      </a>
    </div>
  </div>
</div>
