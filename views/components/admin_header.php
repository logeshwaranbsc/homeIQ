<?php
declare(strict_types=1);

/** @var string $title */
$admin = auth_admin();
?>
<header class="admin-header">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="btnToggleSidebar">
      <i class="bi bi-list fs-5"></i>
    </button>
    <h5 class="m-0 fw-bold brand-font text-dark"><?= e($title ?? 'Management') ?></h5>
  </div>

  <div class="d-flex align-items-center gap-3">
    <a href="<?= url('/') ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2">
      <i class="bi bi-box-arrow-up-right"></i> View Storefront
    </a>
    <div class="vr mx-1"></div>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
        <span class="badge bg-dark"><?= e($admin['role'] ?? 'Admin') ?></span>
        <span class="small fw-semibold"><?= e($admin['name'] ?? 'Admin') ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border">
        <li><a class="dropdown-item" href="<?= url('admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
      </ul>
    </div>
  </div>
</header>

