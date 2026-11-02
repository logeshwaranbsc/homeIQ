<?php
declare(strict_types=1);

use App\Core\Request;

$req = new Request();
$currentPath = $req->getPath();
$admin = auth_admin();
$role = $admin['role'] ?? 'Super Admin';

if (!function_exists('isActiveAdmin')) {
    function isActiveAdmin(string $path, string $currentPath): string {
        return str_starts_with($currentPath, $path) ? 'active' : '';
    }
}
?>
<aside class="admin-sidebar">
  <div class="sidebar-header">
    <a href="<?= url('admin/dashboard') ?>" class="sidebar-brand">
      <i class="bi bi-shield-lock-fill brand-icon me-2"></i>HOMEIQ
    </a>
    <span class="sidebar-badge"><?= e($role) ?></span>
  </div>

  <ul class="sidebar-menu">
    <li class="sidebar-heading">Operations</li>
    <li>
      <a href="<?= url('admin/dashboard') ?>" class="sidebar-link <?= ($currentPath === '/admin/dashboard' || $currentPath === '/admin') ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="<?= url('admin/orders') ?>" class="sidebar-link <?= isActiveAdmin('/admin/orders', $currentPath) ?>">
        <i class="bi bi-receipt"></i> Orders
      </a>
    </li>
    <li>
      <a href="<?= url('admin/products') ?>" class="sidebar-link <?= isActiveAdmin('/admin/products', $currentPath) ?>">
        <i class="bi bi-box-seam"></i> Products
      </a>
    </li>
    <li>
      <a href="<?= url('admin/categories') ?>" class="sidebar-link <?= isActiveAdmin('/admin/categories', $currentPath) ?>">
        <i class="bi bi-grid"></i> Categories
      </a>
    </li>
    <li>
      <a href="<?= url('admin/inventory') ?>" class="sidebar-link <?= isActiveAdmin('/admin/inventory', $currentPath) ?>">
        <i class="bi bi-boxes"></i> Inventory
      </a>
    </li>

    <li class="sidebar-heading">Marketing & Content</li>
    <li>
      <a href="<?= url('admin/banners') ?>" class="sidebar-link <?= isActiveAdmin('/admin/banners', $currentPath) ?>">
        <i class="bi bi-images"></i> Hero Banners
      </a>
    </li>
    <li>
      <a href="<?= url('admin/coupons') ?>" class="sidebar-link <?= isActiveAdmin('/admin/coupons', $currentPath) ?>">
        <i class="bi bi-ticket-perforated"></i> Coupons
      </a>
    </li>
    <li>
      <a href="<?= url('admin/reviews') ?>" class="sidebar-link <?= isActiveAdmin('/admin/reviews', $currentPath) ?>">
        <i class="bi bi-star"></i> Reviews
      </a>
    </li>
    <li>
      <a href="<?= url('admin/customers') ?>" class="sidebar-link <?= isActiveAdmin('/admin/customers', $currentPath) ?>">
        <i class="bi bi-people"></i> Customers
      </a>
    </li>

    <li class="sidebar-heading">Analytics & Config</li>
    <li>
      <a href="<?= url('admin/reports') ?>" class="sidebar-link <?= isActiveAdmin('/admin/reports', $currentPath) ?>">
        <i class="bi bi-bar-chart"></i> Reports & CSV
      </a>
    </li>
    <li>
      <a href="<?= url('admin/seo') ?>" class="sidebar-link <?= isActiveAdmin('/admin/seo', $currentPath) ?>">
        <i class="bi bi-globe"></i> SEO & Sitemap
      </a>
    </li>
    <?php if ($role === 'Super Admin'): ?>
    <li>
      <a href="<?= url('admin/staff') ?>" class="sidebar-link <?= isActiveAdmin('/admin/staff', $currentPath) ?>">
        <i class="bi bi-person-badge"></i> Staff Management
      </a>
    </li>
    <?php endif; ?>
    <li>
      <a href="<?= url('admin/settings') ?>" class="sidebar-link <?= isActiveAdmin('/admin/settings', $currentPath) ?>">
        <i class="bi bi-gear"></i> Store Settings
      </a>
    </li>
  </ul>

  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-person-circle text-info fs-5"></i>
      <div class="lh-1">
        <div class="text-light small fw-bold"><?= e($admin['name'] ?? 'Admin') ?></div>
        <div class="text-secondary" style="font-size: 0.7rem;"><?= e($admin['username'] ?? '') ?></div>
      </div>
    </div>
    <a href="<?= url('admin/logout') ?>" class="text-secondary" title="Sign Out">
      <i class="bi bi-box-arrow-right fs-5"></i>
    </a>
  </div>
</aside>

