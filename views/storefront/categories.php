<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $categories */
$breadcrumbs = [
    ['label' => 'Hardware Collections', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="mb-5 text-center mx-auto" style="max-width: 680px;">
    <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Scope of Engineering</span>
    <h1 class="display-6 fw-extrabold text-dark mt-1">Smart Door Lock Collections</h1>
    <p class="text-muted">Browse our collections of 3D face recognition, contactless palm vein, semiconductor fingerprint, and heavy-duty architectural mortise locks.</p>
  </div>

  <div class="row g-4">
    <?php foreach ($categories as $cat): ?>
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="<?= url('category/' . $cat['slug']) ?>" class="category-card">
          <div class="cat-img-wrapper">
            <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
          </div>
          <h5 class="cat-title"><?= e($cat['name']) ?></h5>
          <p class="cat-desc"><?= e($cat['description']) ?></p>
          <span class="cat-action-link">
            Explore Series <i class="bi bi-arrow-right"></i>
          </span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
