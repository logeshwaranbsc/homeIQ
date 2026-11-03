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
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="category-hero-banner mb-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-8 category-hero-content">
        <div class="category-hero-badge">
          <span class="badge-dot"></span>
          <span>Category Collection</span>
        </div>
        <h1 class="category-hero-title"><?= e($category['name']) ?></h1>
        <p class="category-hero-desc"><?= e($category['description']) ?></p>
        <div class="category-hero-chips">
          <span class="category-chip">
            <i class="bi bi-shield-check"></i> <?= count($products) ?> Available Model<?= count($products) !== 1 ? 's' : '' ?>
          </span>
          <span class="category-chip">
            <i class="bi bi-patch-check"></i> Certified Security Grade
          </span>
          <span class="category-chip">
            <i class="bi bi-truck"></i> Free Express Delivery
          </span>
        </div>
      </div>
      <div class="col-lg-4 text-center">
        <div class="category-hero-showcase">
          <div class="showcase-glow"></div>
          <img src="<?= upload_url($category['image'] ?? '') ?>" alt="<?= e($category['name']) ?>" class="category-hero-img" loading="lazy">
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h3 class="fw-bold mb-0">Products in this Collection</h3>
      <small class="text-muted"><?= count($products) ?> available model(s)</small>
    </div>
    <div>
      <a href="<?= url('shop') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>All Products</a>
    </div>
  </div>

  <?php if (empty($products)): ?>
    <div class="card border p-5 text-center my-4">
      <p class="text-muted m-0">No active products currently found in this category.</p>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($products as $p): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <?= View::component('product_card', ['product' => $p]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

