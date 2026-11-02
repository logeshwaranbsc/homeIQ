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
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="bi bi-check-circle-fill me-2 fs-5"></i>
      <div><?= e($success) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <div><?= e($error) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="bi bi-info-circle-fill me-2 fs-5"></i>
      <div><?= e($info) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($warning): ?>
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
      <div><?= e($warning) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>

