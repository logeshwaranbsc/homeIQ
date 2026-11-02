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
            Already registered? <a href="<?= url('login') ?>" class="text-primary fw-semibold text-decoration-none">Sign In</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

