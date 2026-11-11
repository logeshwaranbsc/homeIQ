<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var string $email
 * @var string $token
 * @var string|null $resetUrl
 * @var array|null $mailResult
 * @var string|null $redirectTarget
 */
$breadcrumbs = [
    ['label' => 'Customer Login', 'url' => 'login'],
    ['label' => 'Password Reset', 'url' => null]
];

$effectiveResetUrl = $resetUrl ?? url('reset-password?token=' . urlencode($token) . '&email=' . urlencode($email) . (!empty($redirectTarget) ? '&redirect=' . urlencode($redirectTarget) : ''));
$isSmtpLive = !empty($mailResult['success']) && ($mailResult['driver'] ?? '') === 'smtp';
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-4 p-4 p-md-5 shadow-sm">
        <!-- Top Status Icon -->
        <div class="text-center mb-3">
          <div style="width: 72px; height: 72px; border-radius: 50%; background: #fff5eb; color: #FE6B25; display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; border: 2px solid #ffcca3;">
            <i class="bi bi-envelope-check-fill"></i>
          </div>
          <h2 class="h3 fw-bold mt-3 mb-1 text-dark">Password Reset Ready</h2>
          <p class="text-muted small">
            We processed the password reset request for <br>
            <strong class="text-dark fs-6"><?= e($email) ?></strong>
          </p>
        </div>

        <!-- 1. Direct Instant Reset Action (Guaranteed to work without waiting for external email) -->
        <div class="p-3 p-md-4 rounded-3 border mb-4" style="background: linear-gradient(135deg, #fff9f5 0%, #ffffff 100%); border-color: #ffd8bf !important;">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <span class="small fw-bold text-dark d-flex align-items-center">
              <i class="bi bi-shield-check text-success fs-5 me-2"></i> Instant Reset Access:
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle small">
              Valid for 1 Hour
            </span>
          </div>
          <p class="small text-muted mb-3">
            Since you are testing in local development, you do not need to wait for email delivery. Click below to choose your new password right now:
          </p>
          <a href="<?= e($effectiveResetUrl) ?>" class="btn btn-hiq-accent w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-key-fill"></i> Set New Password Now &rarr;
          </a>
        </div>

        <!-- 2. Local Outbox & Email Preview -->
        <div class="p-3 bg-light rounded-3 border mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <span class="small fw-bold text-secondary">
              <i class="bi bi-inbox-fill me-1 text-primary"></i> Generated Email (Local Outbox)
            </span>
            <span class="badge bg-secondary-subtle text-secondary small">Local Storage</span>
          </div>
          <p class="small text-muted mb-2">
            The full responsive HTML email has been created and archived in <code>storage/emails/</code>. You can preview exactly what your customers see:
          </p>
          <a href="<?= url('email/preview/latest') ?>" target="_blank" class="btn btn-outline-dark btn-sm w-100 fw-semibold">
            <i class="bi bi-eye-fill me-1"></i> Preview Generated HTML Email in Browser <i class="bi bi-box-arrow-up-right ms-1 small"></i>
          </a>
        </div>

        <!-- 3. Why Didn't I Receive Mail in Gmail Inbox? (Accordion / Helper) -->
        <div class="accordion mb-4" id="emailHelpAccordion">
          <div class="accordion-item border rounded-3 overflow-hidden">
            <h2 class="accordion-header" id="headingHelp">
              <button class="accordion-button collapsed py-2 px-3 small fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHelp" aria-expanded="false" aria-controls="collapseHelp">
                <i class="bi bi-question-circle-fill text-warning me-2"></i> Why didn't I receive an email in my Gmail inbox?
              </button>
            </h2>
            <div id="collapseHelp" class="accordion-collapse collapse" aria-labelledby="headingHelp" data-bs-parent="#emailHelpAccordion">
              <div class="accordion-body small text-muted bg-light">
                <p class="mb-2">
                  <strong>Local Environment Restriction:</strong> This application is running on your local machine (<code>localhost:8000</code>). Local PHP / XAMPP does not have a public outgoing mail server, so external email providers (like Google Gmail) block unauthenticated mail.
                </p>
                <div class="fw-bold text-dark mt-3 mb-1">To send real emails to your Gmail inbox:</div>
                <ol class="ps-3 mb-2">
                  <li>Open the <code>.env</code> file in your project folder.</li>
                  <li>Add your Gmail address and a <strong>Google App Password</strong>:</li>
                </ol>
                <pre class="bg-dark text-light p-2 rounded mb-2 overflow-x-auto" style="font-size: 11px;"><code>MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_character_app_password</code></pre>
                <p class="mb-0" style="font-size: 11px;">
                  <em>Tip:</em> Generate the 16-character password in your Google Account: <strong>Security &rarr; 2-Step Verification &rarr; App passwords &rarr; Create "HomeiQ"</strong>.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Links -->
        <div class="text-center small text-muted d-flex flex-wrap justify-content-center gap-3">
          <a href="<?= url('forgot-password') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-muted text-decoration-none">
            <i class="bi bi-arrow-repeat me-1"></i> Request Another Link
          </a>
          <a href="<?= url('login') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">
            <i class="bi bi-box-arrow-in-right me-1"></i> Return to Sign In
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
