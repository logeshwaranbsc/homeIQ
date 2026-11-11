<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $categories */
$breadcrumbs = [
    ['label' => 'Product Categories', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="mb-5 text-center max-w-700 mx-auto">
    <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Product Scope</span>
    <h1 class="display-6 fw-bold">Smart Door Lock Categories</h1>
    <p class="text-muted">Browse our exclusive collections of biometric, face recognition, app-controlled and heavy duty mortise door locks.</p>
  </div>

  <div class="row g-3 g-md-4">
    <?php $catColClass = count($categories) === 1 ? 'col-lg-5 col-md-7 col-12 mx-auto' : 'col-lg-3 col-md-4 col-sm-6 col-12'; ?>
    <?php foreach ($categories as $cat): ?>
      <div class="<?= $catColClass ?>">
        <a href="<?= url('category/' . $cat['slug']) ?>" class="category-card">
          <div class="cat-img-wrapper">
            <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
          </div>
          <h5 class="cat-title"><?= e($cat['name']) ?></h5>
          <p class="cat-desc"><?= e($cat['description']) ?></p>
          <span class="btn btn-outline-dark btn-sm w-100">Browse Collection</span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

