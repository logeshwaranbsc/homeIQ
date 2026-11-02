<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $products
 * @var int $totalItems
 * @var int $currentPage
 * @var int $totalPages
 * @var array $categories
 * @var array $criteria
 * @var array $availableMethods
 * @var array $availableConnectivity
 * @var array $availableColors
 */

$breadcrumbs = [
    ['label' => 'Shop All Digital Locks', 'url' => null]
];

$hasActiveFilters = !empty($criteria['q']) || !empty($criteria['category']) || !empty($criteria['access_method']) || !empty($criteria['connectivity']) || !empty($criteria['color']) || !empty($criteria['in_stock']) || !empty($criteria['min_price']) || !empty($criteria['max_price']);
?>

<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Page Header -->
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Catalog Scope</span>
      <h1 class="h2 fw-extrabold mb-1 text-dark" style="letter-spacing: -0.03em;">Shop All Digital Smart Locks</h1>
      <p class="text-muted small m-0">Displaying <?= count($products) ?> of <?= $totalItems ?> certified architectural security hardware products</p>
    </div>

    <!-- Sorting -->
    <div class="d-flex align-items-center gap-2">
      <label for="sortSelect" class="form-label mb-0 text-muted small fw-bold text-uppercase letter-spacing-1">Sort By:</label>
      <select id="sortSelect" class="form-select form-select-sm" style="width: 210px; font-weight: 600;" onchange="location = this.value;">
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'newest', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_asc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_desc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'rating', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Top Customer Rating</option>
      </select>
    </div>
  </div>

  <!-- Active Filter Badges -->
  <?php if ($hasActiveFilters): ?>
    <div class="d-flex align-items-center gap-2 mb-4 flex-wrap p-2 px-3 bg-white border rounded-3 small">
      <span class="text-muted fw-bold">Active Filters:</span>
      <?php if (!empty($criteria['q'])): ?>
        <span class="badge bg-dark text-light p-2">Search: <?= e($criteria['q']) ?> <a href="<?= url('shop?' . http_build_query(array_diff_key($criteria, ['q' => '']))) ?>" class="text-light ms-1"><i class="bi bi-x"></i></a></span>
      <?php endif; ?>
      <?php if (!empty($criteria['category'])): ?>
        <span class="badge bg-info text-dark p-2">Category: <?= e($criteria['category']) ?> <a href="<?= url('shop?' . http_build_query(array_diff_key($criteria, ['category' => '']))) ?>" class="text-dark ms-1"><i class="bi bi-x"></i></a></span>
      <?php endif; ?>
      <?php if (!empty($criteria['access_method'])): ?>
        <span class="badge bg-secondary text-light p-2"><?= e($criteria['access_method']) ?> <a href="<?= url('shop?' . http_build_query(array_diff_key($criteria, ['access_method' => '']))) ?>" class="text-light ms-1"><i class="bi bi-x"></i></a></span>
      <?php endif; ?>
      <?php if (!empty($criteria['in_stock'])): ?>
        <span class="badge bg-success text-light p-2">In-Stock Only <a href="<?= url('shop?' . http_build_query(array_diff_key($criteria, ['in_stock' => '']))) ?>" class="text-light ms-1"><i class="bi bi-x"></i></a></span>
      <?php endif; ?>
      <a href="<?= url('shop') ?>" class="text-danger fw-bold ms-auto text-decoration-none">Reset All</a>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <!-- Filter Sidebar Column -->
    <div class="col-lg-3">
      <div class="card border rounded-3 p-4 shadow-xs bg-white sticky-top" style="top: 85px; z-index: 10;">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
          <h6 class="fw-bold m-0 text-dark"><i class="bi bi-sliders2 me-2 text-primary"></i>Filter Catalog</h6>
          <?php if ($hasActiveFilters): ?>
            <a href="<?= url('shop') ?>" class="text-danger small text-decoration-none fw-semibold">Clear</a>
          <?php endif; ?>
        </div>

        <form action="<?= url('shop') ?>" method="GET">
          <!-- Keyword -->
          <?php if (!empty($criteria['q'])): ?>
            <input type="hidden" name="q" value="<?= e($criteria['q']) ?>">
          <?php endif; ?>
          <input type="hidden" name="sort" value="<?= e($criteria['sort'] ?? 'newest') ?>">

          <!-- Category Filter -->
          <div class="mb-4">
            <label class="form-label fw-bold small text-uppercase letter-spacing-1 text-muted">Series / Category</label>
            <div class="d-flex flex-column gap-2">
              <div class="form-check small">
                <input class="form-check-input" type="radio" name="category" value="" id="catAll" <?= empty($criteria['category']) ? 'checked' : '' ?> onchange="this.form.submit()">
                <label class="form-check-label fw-medium text-dark" for="catAll">All Series</label>
              </div>
              <?php foreach ($categories as $cat): ?>
                <div class="form-check small">
                  <input class="form-check-input" type="radio" name="category" value="<?= e($cat['slug']) ?>" id="cat_<?= e($cat['slug']) ?>" <?= ($criteria['category'] ?? '') === $cat['slug'] ? 'checked' : '' ?> onchange="this.form.submit()">
                  <label class="form-check-label fw-medium text-dark" for="cat_<?= e($cat['slug']) ?>"><?= e($cat['name']) ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Price Range Filter -->
          <div class="mb-4">
            <label class="form-label fw-bold small text-uppercase letter-spacing-1 text-muted">Price Range (₹)</label>
            <div class="row g-2 mb-2">
              <div class="col-6">
                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min ₹" value="<?= e($criteria['min_price'] ?? '') ?>">
              </div>
              <div class="col-6">
                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max ₹" value="<?= e($criteria['max_price'] ?? '') ?>">
              </div>
            </div>
          </div>

          <!-- Access Method Filter -->
          <?php if (!empty($availableMethods)): ?>
          <div class="mb-4">
            <label class="form-label fw-bold small text-uppercase letter-spacing-1 text-muted">Access Technology</label>
            <select name="access_method" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">All Access Technologies</option>
              <?php foreach ($availableMethods as $method): ?>
                <option value="<?= e($method) ?>" <?= ($criteria['access_method'] ?? '') === $method ? 'selected' : '' ?>><?= e($method) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>

          <!-- Connectivity Filter -->
          <?php if (!empty($availableConnectivity)): ?>
          <div class="mb-4">
            <label class="form-label fw-bold small text-uppercase letter-spacing-1 text-muted">Network Connectivity</label>
            <select name="connectivity" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">All Connectivity Protocols</option>
              <?php foreach ($availableConnectivity as $conn): ?>
                <option value="<?= e($conn) ?>" <?= ($criteria['connectivity'] ?? '') === $conn ? 'selected' : '' ?>><?= e($conn) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>

          <!-- In Stock Only Switch -->
          <div class="mb-4 pt-2 border-top">
            <div class="form-check form-switch small">
              <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockCheck" <?= !empty($criteria['in_stock']) ? 'checked' : '' ?> onchange="this.form.submit()">
              <label class="form-check-label fw-bold text-dark" for="inStockCheck">In-Stock Only</label>
            </div>
          </div>

          <button type="submit" class="btn btn-hiq-primary btn-sm w-100">
            <i class="bi bi-funnel me-1"></i> Apply Parameters
          </button>
        </form>
      </div>
    </div>

    <!-- Product Grid Column -->
    <div class="col-lg-9">
      <?php if (empty($products)): ?>
        <div class="card border rounded-3 p-5 text-center my-4 shadow-xs bg-white">
          <i class="bi bi-search fs-1 text-muted mb-3"></i>
          <h4 class="fw-bold text-dark">No Matching Smart Locks Found</h4>
          <p class="text-muted">Try adjusting your filters, clearing price criteria, or searching for alternative access methods.</p>
          <div class="mt-2">
            <a href="<?= url('shop') ?>" class="btn btn-hiq-primary">Clear All Filters</a>
          </div>
        </div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($products as $p): ?>
            <div class="col-md-4 col-sm-6">
              <?= View::component('product_card', ['product' => $p]) ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <nav class="mt-5" aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <?php if ($currentPage > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= url('shop?' . http_build_query(array_merge($criteria, ['page' => $currentPage - 1]))) ?>">Previous</a>
                </li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                  <a class="page-link" href="<?= url('shop?' . http_build_query(array_merge($criteria, ['page' => $i]))) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= url('shop?' . http_build_query(array_merge($criteria, ['page' => $currentPage + 1]))) ?>">Next</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
