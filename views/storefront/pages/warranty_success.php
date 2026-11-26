<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $registration
 */
$regId = $registration['registration_id'] ?? '';
$customer = $registration['customer'] ?? [];
$product = $registration['product'] ?? [];
$purchase = $registration['purchase'] ?? [];
?>
<div class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm text-center">
        <!-- Success Icon -->
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.12); color: #10B981;">
          <i class="bi bi-shield-check display-5"></i>
        </div>

        <h2 class="fw-extrabold text-dark mb-1">Registration Submitted Successfully!</h2>
        <p class="text-secondary mb-4" style="font-size: 0.95rem;">
          Thank you, <strong class="text-dark"><?= e($customer['full_name'] ?? 'Customer') ?></strong>! Your product registration details have been securely recorded.
        </p>

        <!-- Unique Registration ID Box -->
        <div class="p-4 rounded-4 mb-4 text-start position-relative border" style="background: #F8FAFC;">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
              <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.75rem; letter-spacing: 0.5px;">Official Registration ID</span>
              <div class="d-flex align-items-center gap-2 mt-1">
                <span class="fs-4 fw-extrabold text-dark font-monospace" id="regIdText"><?= e($regId) ?></span>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="navigator.clipboard.writeText('<?= e($regId) ?>'); this.innerHTML='<i class=\'bi bi-check2\'></i> Copied!';" title="Copy Registration ID">
                  <i class="bi bi-clipboard"></i> Copy
                </button>
              </div>
            </div>
            <div>
              <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">
                <i class="bi bi-clock me-1"></i> PENDING VERIFICATION
              </span>
            </div>
          </div>

          <!-- Summary Grid -->
          <div class="row g-2 pt-2 border-top small">
            <div class="col-sm-6">
              <span class="text-muted">Product:</span>
              <span class="fw-bold text-dark ms-1"><?= e($product['model'] ?? 'N/A') ?> (<?= e($product['category'] ?? 'N/A') ?>)</span>
            </div>
            <div class="col-sm-6">
              <span class="text-muted">Serial No:</span>
              <span class="fw-bold text-dark ms-1"><?= !empty($product['serial_number']) ? e($product['serial_number']) : 'Unavailable (Notes Recorded)' ?></span>
            </div>
            <div class="col-sm-6">
              <span class="text-muted">Platform / Store:</span>
              <span class="fw-bold text-dark ms-1"><?= e($purchase['platform'] ?? 'N/A') ?></span>
            </div>
            <div class="col-sm-6">
              <span class="text-muted">Order ID:</span>
              <span class="fw-bold text-dark ms-1"><?= e($purchase['order_id'] ?? 'N/A') ?></span>
            </div>
            <div class="col-sm-6">
              <span class="text-muted">Contact Mobile:</span>
              <span class="fw-bold text-dark ms-1">+91 <?= e($customer['mobile_number'] ?? 'N/A') ?></span>
            </div>
            <div class="col-sm-6">
              <span class="text-muted">Email Address:</span>
              <span class="fw-bold text-dark ms-1"><?= !empty($customer['email']) ? e($customer['email']) : 'Not provided' ?></span>
            </div>
          </div>
        </div>

        <!-- Next Steps Timeline -->
        <div class="text-start bg-light p-3 p-md-4 rounded-3 mb-4">
          <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle text-primary me-2"></i>What Happens Next?</h6>
          <ol class="mb-0 text-secondary small ps-3" style="line-height: 1.6;">
            <li><strong>Invoice Verification:</strong> Our warranty administration team will cross-verify your purchase invoice and serial details within <strong>24 to 48 business hours</strong>.</li>
            <li><strong>Activation Notice:</strong> Once approved, your warranty dates will be calculated from your verified purchase date, and an official confirmation notification will be issued.</li>
            <li><strong>Doorstep Service Support:</strong> Keep your Registration ID handy whenever contacting HOMEIQ customer support for service or replacement inquiries.</li>
          </ol>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <button type="button" class="btn btn-outline-dark px-4 py-2 fw-semibold" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print / Save Receipt
          </button>
          <a href="<?= url('/') ?>" class="btn btn-warning px-4 py-2 fw-bold text-dark">
            <i class="bi bi-house me-1"></i> Back to Homepage
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
