<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Create Account', 'url' => null]
];
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        <div class="text-center mb-4">
          <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
          <h2 class="h3 fw-bold mt-2">Create HOMEIQ Account</h2>
          <p class="text-muted small">Register to track orders and manage warranty registrations.</p>
        </div>

        <form action="<?= url('register') ?>" method="POST">
          <?= csrf_field() ?>
          <?php 
          $redirectTarget = $redirect ?? (App\Core\Session::get('redirect_after_login') ?? ($_GET['redirect'] ?? '')); 
          $isCheckoutRedirect = ($redirectTarget === 'checkout' || str_contains((string)$redirectTarget, 'checkout'));
          ?>
          <?php if (!empty($redirectTarget)): ?>
            <input type="hidden" name="redirect" value="<?= e($redirectTarget) ?>">
            <?php if ($isCheckoutRedirect): ?>
              <div class="alert alert-warning py-3 px-3 mb-3 border-2 rounded-3" style="border-color: #FE6B25 !important; background: #fff8f5;">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <i class="bi bi-person-plus-fill text-primary fs-5"></i>
                  <strong class="text-dark">Create Account to Complete Purchase</strong>
                </div>
                <div class="small text-muted">
                  Create your HOMEIQ account to proceed with your smart lock order, free installation scheduling, and warranty tracking.
                </div>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" required value="<?= e(old('name', '')) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email', '')) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Mobile Number *</label>
            <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" required value="<?= e(old('phone', '')) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Create Password *</label>
            <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required minlength="6">
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password" required minlength="6">
          </div>

          <button type="submit" class="btn btn-hiq-primary w-100 py-2 fw-bold mb-3">
            Create Account
          </button>

          <div class="text-center small text-muted">
            Already registered? <a href="<?= url('login') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">Sign In</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

