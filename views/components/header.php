<?php
declare(strict_types=1);

use App\Core\Session;
use App\Services\CartService;

$cartService = new CartService();
$cartDetails = $cartService->getCartDetails(false);
$cartCount = $cartDetails['total_items'];

$wishlist = Session::get('wishlist', []);
$wishlistCount = count($wishlist);

$customer = auth_customer();

$currentPath = (new \App\Core\Request())->getPath();
$isHome = ($currentPath === '/' || $currentPath === '');
$isShop = str_starts_with($currentPath, '/shop') || str_starts_with($currentPath, '/product') || str_starts_with($currentPath, '/category');
$isAbout = str_starts_with($currentPath, '/about');
$isContact = str_starts_with($currentPath, '/contact');
?>
<nav class="navbar navbar-expand-lg navbar-hiq">
  <div class="container-fluid px-2 px-sm-3 px-xl-5 position-relative" style="max-width: 1540px;">
    <!-- Mobile Hamburger Toggler (Left on Mobile) -->
    <button class="navbar-toggler d-lg-none border-0 p-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-2"></i>
    </button>

    <!-- Brand Logo (Centered on mobile, left on desktop) -->
    <a class="navbar-brand me-lg-4" href="<?= url('/') ?>">
      <img src="<?= asset('assets/images/homeiq-logo.png') ?>" alt="HOMEIQ" class="brand-logo-img">
    </a>

    <!-- Mobile Header Quick Action Icons (Search + Cart, Right on Mobile) -->
    <div class="d-flex align-items-center gap-2 d-lg-none ms-auto">
      <!-- Search -->
      <a href="<?= url('shop') ?>" class="nav-action-btn mobile-header-btn" title="Search" aria-label="Search">
        <i class="bi bi-search"></i>
      </a>

      <!-- Shopping Cart -->
      <a href="<?= url('cart') ?>" class="nav-action-btn mobile-header-btn nav-action-cart" title="Shopping Cart" aria-label="Cart" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" role="button">
        <i class="bi bi-cart3"></i>
        <span class="badge-counter badge-counter-orange" id="mobileHeaderCartCount" style="<?= $cartCount > 0 ? '' : 'display:none;' ?>"><?= $cartCount ?></span>
      </a>
    </div>

    <!-- Nav Items Collapse -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <!-- Center Segmented Capsule Dock -->
      <div class="nav-capsule-dock mx-auto my-2 my-lg-0">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a class="nav-link nav-link-hiq <?= $isHome ? 'active' : '' ?>" href="<?= url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-hiq <?= $isShop ? 'active' : '' ?>" href="<?= url('shop') ?>">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-hiq <?= $isAbout ? 'active' : '' ?>" href="<?= url('about') ?>">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-hiq <?= $isContact ? 'active' : '' ?>" href="<?= url('contact') ?>">Contact</a>
          </li>
        </ul>
      </div>

      <!-- Desktop User Action Icons (Visible only on >= 992px) -->
      <div class="d-none d-lg-flex align-items-center gap-2">
        <!-- Account -->
        <?php if ($customer): ?>
          <div class="dropdown">
            <a class="nav-action-btn dropdown-toggle d-flex align-items-center gap-2 px-3" href="#" role="button" data-bs-toggle="dropdown" style="width: auto;">
              <i class="bi bi-person-check-fill" style="color: #FE6B25 !important;"></i>
              <span class="text-light small fw-semibold nav-user-name"><?= e(explode(' ', $customer['name'])[0]) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-secondary">
              <li><a class="dropdown-item" href="<?= url('account') ?>"><i class="bi bi-speedometer2 me-2"></i>My Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= url('account/orders') ?>"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
              <li><a class="dropdown-item" href="<?= url('account/profile') ?>"><i class="bi bi-person-gear me-2"></i>Profile Settings</a></li>
              <li><hr class="dropdown-divider border-secondary"></li>
              <li><a class="dropdown-item text-danger" href="<?= url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= url('login') ?>" class="nav-action-btn" title="Sign In" aria-label="Sign In">
            <i class="bi bi-person"></i>
          </a>
        <?php endif; ?>

        <!-- Wishlist -->
        <a href="<?= url('wishlist') ?>" class="nav-action-btn" title="Wishlist" aria-label="Wishlist">
          <i class="bi bi-heart"></i>
          <?php if ($wishlistCount > 0): ?>
            <span class="badge-counter"><?= $wishlistCount ?></span>
          <?php endif; ?>
        </a>

        <!-- Shopping Cart -->
        <a href="<?= url('cart') ?>" class="nav-action-btn nav-action-cart" title="Shopping Cart" aria-label="Shopping Cart" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" role="button">
          <i class="bi bi-bag"></i>
          <span class="badge-counter" id="headerCartCount"><?= $cartCount ?></span>
        </a>
      </div>
    </div>
  </div>
</nav>
