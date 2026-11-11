<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $products */
$breadcrumbs = [
    ['label' => 'My Wishlist', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <h1 class="h2 fw-bold m-0">Saved Wishlist (<?= count($products) ?>)</h1>
    <?php if (!empty($products)): ?>
      <a href="<?= url('shop') ?>" class="btn btn-outline-secondary btn-sm">Explore More Locks</a>
    <?php endif; ?>
  </div>

  <?php if (empty($products)): ?>
    <div class="card border p-4 p-md-5 text-center my-4 shadow-sm">
      <i class="bi bi-heart fs-1 text-muted mb-3"></i>
      <h3 class="fw-bold">Your Wishlist is Empty</h3>
      <p class="text-muted">Save your favorite AI face recognition and biometric locks here while you compare models.</p>
      <div class="mt-3">
        <a href="<?= url('shop') ?>" class="btn btn-hiq-primary">
          Explore Digital Locks
        </a>
      </div>
    </div>
  <?php else: ?>
    <div class="row g-3 g-md-4">
      <?php foreach ($products as $p): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
          <?= View::component('product_card', ['product' => $p]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

