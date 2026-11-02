<?php
declare(strict_types=1);

use App\Core\Session;

$success = Session::getFlash('success');
$error = Session::getFlash('error');
$info = Session::getFlash('info');
$warning = Session::getFlash('warning');
?>
<?php if ($success || $error || $info || $warning): ?>
<div class="container mt-3">
  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #10b981;">
      <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
      <div class="fw-medium"><?= e($success) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #ef4444;">
      <i class="bi bi-exclamation-triangle-fill text-danger me-3 fs-5"></i>
      <div class="fw-medium"><?= e($error) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #0284c7;">
      <i class="bi bi-info-circle-fill text-info me-3 fs-5"></i>
      <div class="fw-medium"><?= e($info) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($warning): ?>
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #f59e0b;">
      <i class="bi bi-exclamation-circle-fill text-warning me-3 fs-5"></i>
      <div class="fw-medium"><?= e($warning) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
