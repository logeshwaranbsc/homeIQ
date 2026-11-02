<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Customer Login', 'url' => null]
];
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        <div class="text-center mb-4">
          <i class="bi bi-person-circle fs-1 text-primary"></i>
          <h2 class="h3 fw-bold mt-2">Sign In to HOMEIQ</h2>
          <p class="text-muted small">Manage your smart lock orders, addresses, and warranties.</p>
        </div>

        <form action="<?= url('login') ?>" method="POST">
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
                  <i class="bi bi-shield-lock-fill text-primary fs-5"></i>
                  <strong class="text-dark">Sign In Required to Checkout</strong>
                </div>
                <div class="small text-muted">
                  Please sign in to your HOMEIQ account to complete your purchase. Your shopping cart is saved and ready.
                </div>
              </div>
            <?php else: ?>
              <div class="alert alert-info py-2 px-3 small mb-3">
                <i class="bi bi-info-circle me-1"></i> Please sign in to continue.
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email', '')) ?>">
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between">
              <label class="form-label">Password *</label>
            </div>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
          </div>

          <button type="submit" class="btn btn-hiq-primary w-100 py-2 fw-bold mb-3">
            Sign In
          </button>

          <div class="text-center small text-muted">
            Don't have an account yet? <a href="<?= url('register') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">Create Account</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

