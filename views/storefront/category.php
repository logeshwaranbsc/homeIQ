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
    ['label' => 'Collections', 'url' => 'categories'],
    ['label' => $category['name'], 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Category Showcase Hero -->
  <div class="p-4 p-md-5 rounded-4 bg-dark text-white mb-5 position-relative overflow-hidden" style="background: radial-gradient(circle at 85% 50%, #151824 0%, #08090d 80%); border: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
      <div class="col-lg-8">
        <span class="badge bg-info text-dark fw-bold px-3 py-1 mb-2">COLLECTION OVERVIEW</span>
        <h1 class="display-6 fw-extrabold text-white mb-3"><?= e($category['name']) ?></h1>
        <p class="text-light opacity-80 fs-6 mb-0" style="line-height: 1.7; max-width: 600px;"><?= e($category['description']) ?></p>
      </div>
      <div class="col-lg-4 text-center">
        <div class="hero-img-pedestal">
          <img src="<?= upload_url($category['image'] ?? '') ?>" alt="<?= e($category['name']) ?>" class="img-fluid rounded-3" style="max-height: 200px;">
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h3 class="fw-bold mb-0 text-dark">Certified Hardware Models</h3>
      <small class="text-muted"><?= count($products) ?> available configuration(s) in this series</small>
    </div>
    <div>
      <a href="<?= url('shop') ?>" class="btn btn-hiq-outline btn-sm"><i class="bi bi-arrow-left me-1"></i>All Collections</a>
    </div>
  </div>

  <?php if (empty($products)): ?>
    <div class="card border rounded-3 p-5 text-center my-4 bg-white shadow-xs">
      <i class="bi bi-box-seam fs-1 text-muted mb-2"></i>
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
