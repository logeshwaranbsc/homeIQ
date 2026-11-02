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

  <div class="p-4 p-md-5 rounded-4 bg-dark text-white mb-5" style="background: linear-gradient(135deg, #0e1014 0%, #1e222d 100%);">
    <div class="row align-items-center g-4">
      <div class="col-lg-8">
        <span class="badge bg-primary text-white mb-2">Category</span>
        <h1 class="display-6 fw-bold mb-3"><?= e($category['name']) ?></h1>
        <p class="text-secondary fs-6 mb-0"><?= e($category['description']) ?></p>
      </div>
      <div class="col-lg-4 text-center">
        <img src="<?= upload_url($category['image'] ?? '') ?>" alt="<?= e($category['name']) ?>" class="img-fluid rounded-3" style="max-height: 180px;">
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

