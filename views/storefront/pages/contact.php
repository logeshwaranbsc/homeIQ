<?php
declare(strict_types=1);

use App\Core\View;
use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();

$breadcrumbs = [
    ['label' => 'Contact Us', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-4 g-lg-5">
    <div class="col-lg-5">
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Direct Assistance</span>
      <h1 class="h2 fw-bold mb-3">Speak With a Smart Security Consultant</h1>
      <p class="text-muted leading-relaxed mb-4">
        Have questions regarding door mortise dimensions, multi-property lock synchronization, or warranty claims? Our technical specialists are here to guide you.
      </p>

      <div class="d-flex flex-column gap-3 mb-4 text-dark">
        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-geo-alt-fill"></i></div>
          <div>
            <strong>Corporate Experience Centre</strong><br>
            <span class="text-muted small"><?= e($settings['address'] ?? '402 Smart Tech Park, Cyber City, Bengaluru 560100') ?></span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-telephone-fill"></i></div>
          <div>
            <strong>Technical Support Hotline</strong><br>
            <span class="text-muted small"><?= e($settings['support_phone'] ?? '+91 98765 43210') ?> (Mon - Sat, 9am - 8pm)</span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-envelope-fill"></i></div>
          <div>
            <strong>Email Support</strong><br>
            <span class="text-muted small"><?= e($settings['support_email'] ?? 'support@homeiq-security.com') ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="col-lg-7">
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        <h4 class="fw-bold mb-4">Send an Inquiry</h4>
        <form action="<?= url('contact') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Your Name *</label>
              <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Phone Number</label>
              <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile">
            </div>
            <div class="col-md-6">
              <label class="form-label">Subject</label>
              <select name="subject" class="form-select">
                <option value="Pre-Purchase Compatibility">Pre-Purchase Door Compatibility</option>
                <option value="Installation Inquiry">Doorstep Installation Request</option>
                <option value="Technical Support">Technical & App Support</option>
                <option value="Bulk / Villa Project Order">Bulk / Architect / Villa Project</option>
                <option value="Other">Other Inquiry</option>
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Message Details *</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Describe your door type, thickness, or specific question..." required></textarea>
          </div>

          <button type="submit" class="btn btn-hiq-primary px-4 py-2">
            Send Inquiry <i class="bi bi-send ms-2"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

