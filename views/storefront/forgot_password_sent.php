<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var string $email
 * @var string|null $redirectTarget
 */
$breadcrumbs = [
    ['label' => 'Customer Login', 'url' => 'login'],
    ['label' => 'Password Reset', 'url' => null]
];
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-4 p-4 p-md-5 shadow-sm text-center">
        <!-- Top Status Icon -->
        <div class="mb-3">
          <div style="width: 72px; height: 72px; border-radius: 50%; background: #fff5eb; color: #FE6B25; display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; border: 2px solid #ffcca3; margin: 0 auto;">
            <i class="bi bi-envelope-check-fill"></i>
          </div>
          <h2 class="h3 fw-bold mt-3 mb-2 text-dark">Check Your Email</h2>
          <p class="text-muted small mb-0">
            We have sent a password reset link to:
          </p>
          <div class="fw-bold text-dark fs-6 py-2 px-3 bg-light rounded-3 d-inline-block border my-2 text-break">
            <?= e($email) ?>
          </div>
        </div>

        <!-- Instructions Box -->
        <div class="text-start bg-light p-3 rounded-3 border mb-4 small text-muted">
          <div class="d-flex gap-2 mb-2">
            <i class="bi bi-info-circle-fill text-primary mt-1"></i>
            <div>
              Please check your inbox and click the <strong>Reset My Password</strong> button in the email to set your new password.
            </div>
          </div>
          <div class="d-flex gap-2 mb-2">
            <i class="bi bi-clock-history text-secondary mt-1"></i>
            <div>
              The password reset link is secure and will expire in <strong>1 hour</strong>.
            </div>
          </div>
          <div class="d-flex gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
            <div>
              Can't find the email? Check your <strong>Spam</strong> or <strong>Junk</strong> folder.
            </div>
          </div>
        </div>

        <!-- Return / Action Buttons -->
        <a href="<?= url('login') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="btn btn-hiq-primary w-100 py-2 fw-bold mb-3">
          <i class="bi bi-box-arrow-in-right me-1"></i> Return to Sign In
        </a>

        <div class="text-center small text-muted">
          Didn't receive the email? 
          <a href="<?= url('forgot-password') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">
            Resend Email
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
