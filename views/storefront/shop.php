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

<div class="container py-3 py-md-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="d-flex align-items-sm-center justify-content-between mb-4 flex-column flex-sm-row gap-3">
    <div>
      <h1 class="h2 fw-bold mb-1">Shop All Digital Smart Locks</h1>
      <p class="text-muted small m-0">Showing <?= count($products) ?> of <?= $totalItems ?> certified door security products</p>
    </div>

    <!-- Sorting -->
    <div class="d-flex align-items-center gap-2 align-self-start align-self-sm-auto">
      <label for="sortSelect" class="form-label mb-0 text-muted small fw-medium text-nowrap">Sort:</label>
      <select id="sortSelect" class="form-select form-select-sm" style="min-width: 170px;" onchange="location = this.value;">
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'newest', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_asc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_desc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'rating', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Customer Rating</option>
      </select>
    </div>
  </div>

  <div class="row g-4">
    <!-- Mobile Filter Toggle Button (Visible only on < 992px) -->
    <div class="col-12 d-lg-none">
      <button class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-between py-2.5 px-3 rounded-3 shadow-sm bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#shopFilterCollapse" aria-expanded="false" aria-controls="shopFilterCollapse">
        <span class="fw-bold d-flex align-items-center">
          <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Products
          <?php if ($hasActiveFilters): ?>
            <span class="badge bg-primary ms-2 rounded-pill small">Active</span>
          <?php endif; ?>
        </span>
        <span class="small text-muted d-flex align-items-center gap-1">
          Tap to toggle <i class="bi bi-chevron-down"></i>
        </span>
      </button>
    </div>

    <!-- Filter Sidebar Column -->
    <div class="col-lg-3">
      <div class="collapse d-lg-block" id="shopFilterCollapse">
        <div class="card border p-3 shadow-sm sticky-top" style="top: 90px; z-index: 10;">
          <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <h6 class="fw-bold m-0"><i class="bi bi-funnel me-1"></i>Filters</h6>
            <?php if ($hasActiveFilters): ?>
              <a href="<?= url('shop') ?>" class="text-danger small text-decoration-none fw-semibold">Clear All</a>
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
              <label class="form-label fw-bold small text-uppercase letter-spacing-1">Category</label>
              <div class="d-flex flex-column gap-1">
                <div class="form-check small">
                  <input class="form-check-input" type="radio" name="category" value="" id="catAll" <?= empty($criteria['category']) ? 'checked' : '' ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="catAll">All Categories</label>
                </div>
                <?php foreach ($categories as $cat): ?>
                  <div class="form-check small">
                    <input class="form-check-input" type="radio" name="category" value="<?= e($cat['slug']) ?>" id="cat_<?= e($cat['slug']) ?>" <?= ($criteria['category'] ?? '') === $cat['slug'] ? 'checked' : '' ?> onchange="this.form.submit()">
                    <label class="form-check-label" for="cat_<?= e($cat['slug']) ?>"><?= e($cat['name']) ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Price Range Filter -->
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase letter-spacing-1">Price Range (₹)</label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?= e($criteria['min_price'] ?? '') ?>">
                </div>
                <div class="col-6">
                  <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?= e($criteria['max_price'] ?? '') ?>">
                </div>
              </div>
            </div>

            <!-- Access Method Filter -->
            <?php if (!empty($availableMethods)): ?>
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase letter-spacing-1">Access Method</label>
              <select name="access_method" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Access Methods</option>
                <?php foreach ($availableMethods as $method): ?>
                  <option value="<?= e($method) ?>" <?= ($criteria['access_method'] ?? '') === $method ? 'selected' : '' ?>><?= e($method) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>

            <!-- Connectivity Filter -->
            <?php if (!empty($availableConnectivity)): ?>
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase letter-spacing-1">Connectivity</label>
              <select name="connectivity" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Connectivity Types</option>
                <?php foreach ($availableConnectivity as $conn): ?>
                  <option value="<?= e($conn) ?>" <?= ($criteria['connectivity'] ?? '') === $conn ? 'selected' : '' ?>><?= e($conn) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>

            <!-- Color Finish -->
            <?php if (!empty($availableColors)): ?>
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase letter-spacing-1">Hardware Finish</label>
              <select name="color" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Finishes</option>
                <?php foreach ($availableColors as $col): ?>
                  <option value="<?= e($col) ?>" <?= ($criteria['color'] ?? '') === $col ? 'selected' : '' ?>><?= e($col) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>

            <!-- In-Stock Only -->
            <div class="form-check form-switch mb-4">
              <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockOnly" <?= !empty($criteria['in_stock']) ? 'checked' : '' ?> onchange="this.form.submit()">
              <label class="form-check-label small fw-semibold" for="inStockOnly">In-Stock Only</label>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-dark btn-sm">Apply Filters</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Product Grid Column -->
    <div class="col-lg-9">
      <?php if (empty($products)): ?>
        <div class="card border p-4 p-md-5 text-center my-4">
          <i class="bi bi-search fs-1 text-muted mb-3"></i>
          <h4 class="fw-bold">No Matching Smart Locks Found</h4>
          <p class="text-muted">Try adjusting your filters, clearing price criteria, or searching for other access methods.</p>
          <div class="mt-2">
            <a href="<?= url('shop') ?>" class="btn btn-hiq-primary">Clear All Filters</a>
          </div>
        </div>
      <?php else: ?>
        <div class="row g-3 g-md-4">
          <?php foreach ($products as $p): ?>
            <div class="col-lg-4 col-sm-6 col-12">
              <?= View::component('product_card', ['product' => $p]) ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <nav class="mt-5" aria-label="Page navigation">
            <ul class="pagination pagination-sm justify-content-center flex-wrap">
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
