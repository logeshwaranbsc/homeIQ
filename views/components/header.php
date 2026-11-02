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
        <span class="brand-logo-icon">
          <i class="bi bi-shield-lock-fill"></i>
        </span>
        <span class="text-white">HOME<span style="color: #38bdf8;">IQ</span></span>
      </div>
      <div class="brand-tag">ARCHITECTURAL SMART ACCESS</div>
    </a>

    <!-- Mobile Toggler -->
    <button class="navbar-toggler border-0 text-light p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-2 text-white"></i>
    </button>

    <!-- Nav Items -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('/') ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('shop') ?>">Digital Locks</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-hiq dropdown-toggle" href="<?= url('categories') ?>" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Collections
          </a>
          <ul class="dropdown-menu dropdown-menu-dark shadow border-0" aria-labelledby="categoriesDropdown">
            <li><a class="dropdown-item py-2" href="<?= url('categories') ?>"><i class="bi bi-grid me-2 text-info"></i>All Collections</a></li>
            <li><hr class="dropdown-divider"></li>
            <?php foreach ($navCategories as $cat): ?>
              <li><a class="dropdown-item py-2" href="<?= url('category/' . $cat['slug']) ?>"><i class="bi bi-lock me-2 text-secondary"></i><?= e($cat['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('about') ?>">Why HOMEIQ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-hiq" href="<?= url('contact') ?>">Concierge</a>
        </li>
      </ul>

      <!-- Luxury Search Bar -->
      <form class="search-form-hiq me-lg-3 my-2 my-lg-0" action="<?= url('shop') ?>" method="GET">
        <i class="bi bi-search search-icon"></i>
        <input class="form-control" type="search" name="q" placeholder="Search smart locks..." aria-label="Search" value="<?= e($_GET['q'] ?? '') ?>">
      </form>

      <!-- User Action Icons -->
      <div class="d-flex align-items-center gap-2">
        <!-- Account -->
        <?php if ($customer): ?>
          <div class="dropdown">
            <a class="nav-action-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" title="Account">
              <i class="bi bi-person-check-fill text-info"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-0">
              <li class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                <span class="small text-muted d-block">Signed in as</span>
                <strong class="text-white small"><?= e($customer['name']) ?></strong>
              </li>
              <li><a class="dropdown-item mt-1" href="<?= url('account') ?>"><i class="bi bi-speedometer2 me-2 text-info"></i>My Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= url('account/orders') ?>"><i class="bi bi-box-seam me-2 text-info"></i>My Orders</a></li>
              <li><a class="dropdown-item" href="<?= url('account/profile') ?>"><i class="bi bi-person-gear me-2 text-info"></i>Profile & Security</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= url('login') ?>" class="nav-action-btn" title="Sign In">
            <i class="bi bi-person"></i>
          </a>
        <?php endif; ?>

        <!-- Wishlist -->
        <a href="<?= url('wishlist') ?>" class="nav-action-btn" title="Saved Wishlist">
          <i class="bi bi-heart"></i>
          <?php if ($wishlistCount > 0): ?>
            <span class="badge-counter"><?= $wishlistCount ?></span>
          <?php endif; ?>
        </a>

        <!-- Shopping Cart -->
        <a href="<?= url('cart') ?>" class="nav-action-btn" title="Shopping Bag">
          <i class="bi bi-bag"></i>
          <span class="badge-counter" id="headerCartCount"><?= $cartCount ?></span>
        </a>
      </div>
    </div>
  </div>
</nav>
