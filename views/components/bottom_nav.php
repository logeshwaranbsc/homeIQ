<?php
declare(strict_types=1);

$currentPath = (new \App\Core\Request())->getPath();
$isHome = ($currentPath === '/' || $currentPath === '');
$isShop = str_starts_with($currentPath, '/shop') || str_starts_with($currentPath, '/product') || str_starts_with($currentPath, '/category');
$isReels = str_starts_with($currentPath, '/video-testimonials');
$isSupport = str_starts_with($currentPath, '/contact') || str_starts_with($currentPath, '/faq');
$isAccount = str_starts_with($currentPath, '/account') || str_starts_with($currentPath, '/login') || str_starts_with($currentPath, '/register');

$customer = auth_customer();
?>
<nav class="hiq-bottom-nav d-md-none" aria-label="Mobile Navigation">
  <div class="hiq-bottom-nav-inner">
    <a href="<?= url('/') ?>" class="hiq-bottom-nav-item <?= $isHome ? 'active' : '' ?>">
      <div class="hiq-bottom-nav-icon-wrap">
        <i class="bi <?= $isHome ? 'bi-house-fill' : 'bi-house' ?>"></i>
      </div>
      <span class="hiq-bottom-nav-label">Home</span>
    </a>

    <a href="<?= url('shop') ?>" class="hiq-bottom-nav-item <?= $isShop ? 'active' : '' ?>">
      <div class="hiq-bottom-nav-icon-wrap">
        <i class="bi <?= $isShop ? 'bi-bag-fill' : 'bi-bag' ?>"></i>
      </div>
      <span class="hiq-bottom-nav-label">Shop</span>
    </a>

    <a href="<?= url('video-testimonials') ?>" class="hiq-bottom-nav-item <?= $isReels ? 'active' : '' ?>">
      <div class="hiq-bottom-nav-icon-wrap">
        <i class="bi <?= $isReels ? 'bi-play-circle-fill' : 'bi-play-circle' ?>"></i>
      </div>
      <span class="hiq-bottom-nav-label">Reels</span>
    </a>

    <a href="<?= url('contact') ?>" class="hiq-bottom-nav-item <?= $isSupport ? 'active' : '' ?>">
      <div class="hiq-bottom-nav-icon-wrap">
        <i class="bi bi-headset"></i>
      </div>
      <span class="hiq-bottom-nav-label">Support</span>
    </a>

    <a href="<?= $customer ? url('account') : url('login') ?>" class="hiq-bottom-nav-item <?= $isAccount ? 'active' : '' ?>">
      <div class="hiq-bottom-nav-icon-wrap">
        <i class="bi <?= $isAccount ? 'bi-person-fill' : 'bi-person' ?>"></i>
      </div>
      <span class="hiq-bottom-nav-label">Account</span>
    </a>
  </div>
</nav>

