<?php
declare(strict_types=1);
?>
<div class="card border-0 rounded-4 shadow-lg p-4 p-md-5" style="background: #141720; border: 1px solid rgba(255,255,255,0.08) !important;">
  <div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark p-3 text-info mb-2 border border-secondary border-opacity-25">
      <i class="bi bi-shield-lock-fill fs-2"></i>
    </div>
    <h3 class="fw-bold text-white mb-1 brand-font" style="letter-spacing: 2px;">HOMEIQ</h3>
    <span class="badge bg-secondary text-light small text-uppercase">Management Portal</span>
  </div>

  <form action="<?= url('admin/login') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label text-light small fw-bold">Admin Username or Email</label>
      <div class="input-group">
        <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-person"></i></span>
        <input type="text" name="identifier" class="form-control bg-dark border-secondary text-white" placeholder="admin" required autofocus>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label text-light small fw-bold">Password</label>
      <div class="input-group">
        <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-key"></i></span>
        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
      </div>
    </div>

    <button type="submit" class="btn btn-info text-dark w-100 py-2 fw-bold">
      <i class="bi bi-box-arrow-in-right me-1"></i> Authenticate & Enter
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="<?= url('/') ?>" class="text-secondary small text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Return to Storefront</a>
  </div>
</div>

