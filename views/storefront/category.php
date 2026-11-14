<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $category
 * @var array $products
 * @var int $totalItems
 * @var array $criteria
 */
$breadcrumbs = [
    ['label' => 'Categories', 'url' => 'categories'],
    ['label' => $category['name'], 'url' => null]
];

$bannerDesktop = !empty($category['banner_image']) ? $category['banner_image'] : 'banners/f488cc9b748609d48f11_1794577055.png';
$bannerMobile = !empty($category['banner_mobile_image']) ? $category['banner_mobile_image'] : 'banners/ea4618d92e96212423f0_1794646027.webp';
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Full-Image Hero Section (Responsive Art Direction) -->
  <div class="category-hero-banner mb-4 <?= !empty($bannerMobile) ? 'has-mobile-banner' : 'no-mobile-banner' ?>">
    <div class="category-hero-media">
      <picture class="category-hero-picture">
        <?php if (!empty($bannerMobile)): ?>
          <source media="(max-width: 767.98px)" srcset="<?= upload_url($bannerMobile) ?>">
        <?php endif; ?>
        <img src="<?= upload_url($bannerDesktop ?: $bannerMobile) ?>" alt="<?= e($category['name']) ?>" class="category-hero-img-full" loading="eager">
      </picture>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h3 class="fw-bold mb-0">Products in this Collection</h3>
    </div>
  </div>

  <?php if (empty($products)): ?>
    <div class="card border p-5 text-center my-4">
      <p class="text-muted m-0">No active products currently found in this category.</p>
    </div>
  <?php else: ?>
    <div class="row g-2 g-md-4">
      <?php foreach ($products as $p): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <?= View::component('product_card', ['product' => $p]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
