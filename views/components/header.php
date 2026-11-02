<?php
declare(strict_types=1);

use App\Core\Session;
use App\Services\CartService;
use App\Repositories\CategoryRepository;

$cartService = new CartService();
$cartDetails = $cartService->getCartDetails(false);
$cartCount = $cartDetails['total_items'];

$wishlist = Session::get('wishlist', []);
$wishlistCount = count($wishlist);

$customer = auth_customer();

$catRepo = new CategoryRepository();
$navCategories = $catRepo->getActive();
?>
<nav class="navbar navbar-expand-lg navbar-hiq">
  <div class="container">
    <!-- Brand Logo -->
    <a class="navbar-brand" href="<?= url('/') ?>">
      <div class="brand-logo">
        <i class="bi bi-shield-lock-fill brand-icon"></i>
        <span>HOMEIQ</span>
      </div>
      <div class="brand-tag">DIGITAL SMART LOCKS</div>
    </a>

    <!-- Mobile Toggler -->
    <button class="navbar-toggler border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-2"></i>
    </button>

    <!-- Nav Items -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('/') ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('shop') ?>">Shop Digital Locks</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-hiq dropdown-toggle" href="<?= url('categories') ?>" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu dropdown-menu-dark shadow border-secondary" aria-labelledby="categoriesDropdown">
            <li><a class="dropdown-item py-2" href="<?= url('categories') ?>"><strong>All Categories</strong></a></li>
            <li><hr class="dropdown-divider border-secondary"></li>
            <?php foreach ($navCategories as $cat): ?>
              <li><a class="dropdown-item py-2" href="<?= url('category/' . $cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('about') ?>">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('contact') ?>">Contact</a>
        </li>
      </ul>

      <!-- Search Bar -->
      <form class="d-flex me-lg-3 my-2 my-lg-0" action="<?= url('shop') ?>" method="GET">
        <div class="input-group input-group-sm">
          <input class="form-control bg-dark text-light border-secondary" type="search" name="q" placeholder="Search locks, SKU..." aria-label="Search" value="<?= e($_GET['q'] ?? '') ?>">
          <button class="btn btn-hiq-accent text-white" type="submit" aria-label="Search">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>

      <!-- User Action Icons -->
      <div class="d-flex align-items-center gap-2">
        <!-- Account -->
        <?php if ($customer): ?>
          <div class="dropdown">
            <a class="nav-action-btn dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-check-fill text-info"></i>
              <span class="d-none d-xl-inline text-light small"><?= e(explode(' ', $customer['name'])[0]) ?></span>
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
          <a href="<?= url('login') ?>" class="nav-action-btn" title="Sign In">
            <i class="bi bi-person"></i>
          </a>
        <?php endif; ?>

        <!-- Wishlist -->
        <a href="<?= url('wishlist') ?>" class="nav-action-btn" title="Wishlist">
          <i class="bi bi-heart"></i>
          <?php if ($wishlistCount > 0): ?>
            <span class="badge-counter"><?= $wishlistCount ?></span>
          <?php endif; ?>
        </a>

        <!-- Shopping Cart -->
        <a href="<?= url('cart') ?>" class="nav-action-btn" title="Shopping Cart">
          <i class="bi bi-bag"></i>
          <span class="badge-counter" id="headerCartCount"><?= $cartCount ?></span>
        </a>
      </div>
    </div>
  </div>
</nav>

