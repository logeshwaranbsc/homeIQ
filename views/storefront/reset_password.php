<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var string $token
 * @var string $email
 * @var string|null $redirectTarget
 */
$breadcrumbs = [
    ['label' => 'Customer Login', 'url' => 'login'],
    ['label' => 'Set New Password', 'url' => null]
];
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        <div class="text-center mb-4">
          <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, rgba(254,107,37,0.12) 0%, rgba(254,74,31,0.18) 100%); color: #FE6B25; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(254,107,37,0.2);">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
          <h2 class="h3 fw-bold m-0 text-dark">Create New Password</h2>
          <p class="text-muted small mt-2">Enter your new secure password for <strong><?= e($email) ?></strong>.</p>
        </div>

        <form action="<?= url('reset-password') ?>" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="token" value="<?= e($token) ?>">
          <input type="hidden" name="email" value="<?= e($email) ?>">
          <?php if (!empty($redirectTarget)): ?>
            <input type="hidden" name="redirect" value="<?= e($redirectTarget) ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">New Password *</label>
            <div class="input-group">
              <input type="password" name="password" id="inputNewPassword" class="form-control" placeholder="Minimum 6 characters" required minlength="6" autofocus>
              <button class="btn btn-outline-secondary" type="button" onclick="const p = document.getElementById('inputNewPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('bi-eye'); this.querySelector('i').classList.toggle('bi-eye-slash');">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Confirm New Password *</label>
            <div class="input-group">
              <input type="password" name="password_confirmation" id="inputConfirmPassword" class="form-control" placeholder="Re-type your password" required minlength="6">
              <button class="btn btn-outline-secondary" type="button" onclick="const p = document.getElementById('inputConfirmPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('bi-eye'); this.querySelector('i').classList.toggle('bi-eye-slash');">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-hiq-primary w-100 py-2 fw-bold mb-3">
            <i class="bi bi-check2-circle me-1"></i> Update Password & Sign In
          </button>

          <div class="text-center small text-muted">
            <a href="<?= url('login') . (!empty($redirectTarget) ? '?redirect=' . urlencode($redirectTarget) : '') ?>" class="text-primary fw-semibold text-decoration-none">
              Cancel & Return to Sign In
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

