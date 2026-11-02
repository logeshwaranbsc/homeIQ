<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var string|null $redirectTarget
 */
$breadcrumbs = [
    ['label' => 'Customer Login', 'url' => 'login'],
    ['label' => 'Forgot Password', 'url' => null]
];
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        <div class="text-center mb-4">
          <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, rgba(254,107,37,0.12) 0%, rgba(254,74,31,0.18) 100%); color: #FE6B25; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(254,107,37,0.2);">
            <i class="bi bi-key-fill"></i>
          </div>
          <h2 class="h3 fw-bold m-0 text-dark">Forgot Password?</h2>
          <p class="text-muted small mt-2">Enter your registered email address and we will generate a secure link to reset your password.</p>
        </div>

        <form action="<?= url('forgot-password') ?>" method="POST">
          <?= csrf_field() ?>
          <?php if (!empty($redirectTarget)): ?>
            <input type="hidden" name="redirect" value="<?= e($redirectTarget) ?>">
          <?php endif; ?>

          <div class="mb-4">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email', '')) ?>" autofocus>
          </div>

          <button type="submit" class="btn btn-hiq-primary w-100 py-2 fw-bold mb-3">
            <i class="bi bi-envelope-paper me-1"></i> Send Password Reset Link
          </button>

          <div class="text-center small text-muted">
            Remembered your password? <a href="<?= url('login') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">Back to Sign In</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

