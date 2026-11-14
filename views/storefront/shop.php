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
 */

$breadcrumbs = [
    ['label' => 'Shop All Digital Locks', 'url' => null]
];

// Active filter count calculation
$activeFilterCount = 0;
if (!empty($criteria['access_method'])) $activeFilterCount++;
if (!empty($criteria['in_stock'])) $activeFilterCount++;
if (!empty($criteria['min_price']) || !empty($criteria['max_price'])) $activeFilterCount++;
if (!empty($criteria['sort']) && $criteria['sort'] !== 'newest') $activeFilterCount++;
$hasActiveFilters = $activeFilterCount > 0 || !empty($criteria['q']);
?>

<div class="container py-3 py-md-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="d-flex align-items-sm-center justify-content-between mb-3 mb-md-4 flex-column flex-sm-row gap-3">
    <div>
      <h1 class="h2 fw-bold mb-0">Shop All Digital Smart Locks</h1>
    </div>

    <!-- Sorting (Desktop quick sort; on mobile, sorting is inside the floating filter drawer) -->
    <div class="d-none d-lg-flex align-items-center justify-content-sm-start gap-2 shop-sort-bar">
      <label for="sortSelect" class="form-label mb-0 text-muted small fw-medium text-nowrap">Sort:</label>
      <select id="sortSelect" class="form-select form-select-sm" style="min-width: 170px;" onchange="location = this.value;">
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'newest', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_asc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'price_desc', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      </select>
    </div>
  </div>

  <!-- Floating Mobile Filter Trigger Button (Bottom-Left on Mobile Screens, Hidden on Desktop >=992px) -->
  <button type="button" 
          class="hiq-floating-filter-btn d-lg-none" 
          data-bs-toggle="offcanvas" 
          data-bs-target="#shopFilterOffcanvas" 
          aria-controls="shopFilterOffcanvas" 
          aria-label="Open Filter Drawer">
    <i class="bi bi-sliders2-vertical"></i>
    <span>Filters</span>
    <?php if ($activeFilterCount > 0): ?>
      <span class="badge bg-warning text-dark rounded-pill fw-bold ms-1"><?= $activeFilterCount ?></span>
    <?php endif; ?>
  </button>

  <div class="row g-4">
    <!-- Responsive Filter Sidebar: Offcanvas Drawer on Mobile (<992px) & Persistent Column on Desktop (>=992px) -->
    <div class="col-lg-3">
      <div class="offcanvas-lg offcanvas-start shop-filter-offcanvas" tabindex="-1" id="shopFilterOffcanvas" aria-labelledby="shopFilterOffcanvasLabel">
        
        <!-- Mobile Offcanvas Header -->
        <div class="offcanvas-header border-bottom d-lg-none px-3 py-3">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-sliders2-vertical text-primary fs-5"></i>
            <h5 class="offcanvas-title fw-bold m-0" id="shopFilterOffcanvasLabel">Filter & Sort</h5>
            <?php if ($activeFilterCount > 0): ?>
              <span class="badge bg-primary rounded-pill small"><?= $activeFilterCount ?> active</span>
            <?php endif; ?>
          </div>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" data-bs-target="#shopFilterOffcanvas" aria-label="Close"></button>
        </div>

        <!-- Filter Content / Form -->
        <div class="offcanvas-body p-3 p-lg-0">
          <div class="card border border-lg-1 shadow-sm p-3 p-lg-3 sticky-lg-top" style="top: 90px; z-index: 10;">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
              <h6 class="fw-bold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-funnel-fill text-primary"></i>Filters
                <?php if ($activeFilterCount > 0): ?>
                  <span class="badge bg-primary rounded-pill small"><?= $activeFilterCount ?></span>
                <?php endif; ?>
              </h6>
              <?php if ($hasActiveFilters): ?>
                <a href="<?= url('shop') ?>" class="text-danger small text-decoration-none fw-semibold">Clear All</a>
              <?php endif; ?>
            </div>

            <form id="shopFilterForm" action="<?= url('shop') ?>" method="GET">
              <!-- Search query persistence -->
              <?php if (!empty($criteria['q'])): ?>
                <input type="hidden" name="q" value="<?= e($criteria['q']) ?>">
              <?php endif; ?>

              <!-- 1. Sort By -->
              <div class="mb-3 pb-3 border-bottom">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 mb-2">Sort By</label>
                <div class="d-flex flex-wrap gap-2">
                  <label class="shop-filter-chip <?= ($criteria['sort'] ?? 'newest') === 'newest' ? 'active' : '' ?>">
                    <input type="radio" name="sort" value="newest" <?= ($criteria['sort'] ?? 'newest') === 'newest' ? 'checked' : '' ?> onclick="selectSortChip(this)">
                    <i class="bi bi-stars text-warning me-1"></i>
                    <span>Newest Arrivals</span>
                  </label>
                  <label class="shop-filter-chip <?= ($criteria['sort'] ?? '') === 'price_asc' ? 'active' : '' ?>">
                    <input type="radio" name="sort" value="price_asc" <?= ($criteria['sort'] ?? '') === 'price_asc' ? 'checked' : '' ?> onclick="selectSortChip(this)">
                    <i class="bi bi-sort-numeric-down me-1"></i>
                    <span>Price: Low to High</span>
                  </label>
                  <label class="shop-filter-chip <?= ($criteria['sort'] ?? '') === 'price_desc' ? 'active' : '' ?>">
                    <input type="radio" name="sort" value="price_desc" <?= ($criteria['sort'] ?? '') === 'price_desc' ? 'checked' : '' ?> onclick="selectSortChip(this)">
                    <i class="bi bi-sort-numeric-up-alt me-1"></i>
                    <span>Price: High to Low</span>
                  </label>
                </div>
              </div>

              <!-- 2. Unlock Method -->
              <div class="mb-3 pb-3 border-bottom">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center mb-2">
                  <span>Unlock Method</span>
                  <?php if (!empty($criteria['access_method'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearRadio('access_method')">Clear</a>
                  <?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  <?php 
                    $unlockMethods = [
                      ['val' => 'Fingerprint', 'label' => 'Fingerprint', 'icon' => 'bi-fingerprint'],
                      ['val' => 'Face Recognition', 'label' => 'Face Unlock', 'icon' => 'bi-person-bounding-box'],
                      ['val' => 'Palm Vein', 'label' => 'Palm Vein', 'icon' => 'bi-hand-index-thumb'],
                      ['val' => 'Mobile App', 'label' => 'Mobile App', 'icon' => 'bi-phone'],
                    ];
                    foreach ($unlockMethods as $um): 
                      $isSelected = ($criteria['access_method'] ?? '') === $um['val'];
                  ?>
                    <label class="shop-filter-chip <?= $isSelected ? 'active' : '' ?>">
                      <input type="radio" name="access_method" value="<?= e($um['val']) ?>" <?= $isSelected ? 'checked' : '' ?> onclick="toggleRadioChip(this)">
                      <i class="bi <?= $um['icon'] ?> me-1"></i>
                      <span><?= e($um['label']) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- 3. Budget (1-Tap Price Brackets) -->
              <div class="mb-3 pb-3 border-bottom">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center mb-2">
                  <span>Budget</span>
                  <?php if (!empty($criteria['min_price']) || !empty($criteria['max_price'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearBudget()">Clear</a>
                  <?php endif; ?>
                </label>
                <input type="hidden" name="min_price" id="filterMinPrice" value="<?= e($criteria['min_price'] ?? '') ?>">
                <input type="hidden" name="max_price" id="filterMaxPrice" value="<?= e($criteria['max_price'] ?? '') ?>">
                
                <div class="d-flex flex-wrap gap-2">
                  <?php 
                    $isUnder15k = empty($criteria['min_price']) && ($criteria['max_price'] ?? '') === '15000';
                    $is15kTo20k = ($criteria['min_price'] ?? '') === '15000' && ($criteria['max_price'] ?? '') === '20000';
                    $isAbove20k = ($criteria['min_price'] ?? '') === '20000' && empty($criteria['max_price']);
                  ?>
                  <label class="shop-filter-chip <?= $isUnder15k ? 'active' : '' ?>">
                    <input type="radio" name="budget_range" value="under_15k" <?= $isUnder15k ? 'checked' : '' ?> onclick="toggleBudgetChip(this, '', '15000')">
                    <span>Under ₹15,000</span>
                  </label>
                  <label class="shop-filter-chip <?= $is15kTo20k ? 'active' : '' ?>">
                    <input type="radio" name="budget_range" value="15k_20k" <?= $is15kTo20k ? 'checked' : '' ?> onclick="toggleBudgetChip(this, '15000', '20000')">
                    <span>₹15,000 - ₹20,000</span>
                  </label>
                  <label class="shop-filter-chip <?= $isAbove20k ? 'active' : '' ?>">
                    <input type="radio" name="budget_range" value="above_20k" <?= $isAbove20k ? 'checked' : '' ?> onclick="toggleBudgetChip(this, '20000', '')">
                    <span>Above ₹20,000</span>
                  </label>
                </div>
              </div>

              <!-- 4. Availability / In-Stock Only -->
              <div class="form-check form-switch mb-3 p-2 bg-light rounded-3 d-flex align-items-center justify-content-between">
                <label class="form-check-label small fw-semibold cursor-pointer m-0 ps-1" for="inStockOnly">
                  <i class="bi bi-check-circle-fill text-success me-1"></i> In-Stock Only
                </label>
                <input class="form-check-input m-0 cursor-pointer" type="checkbox" name="in_stock" value="1" id="inStockOnly" <?= !empty($criteria['in_stock']) ? 'checked' : '' ?>>
              </div>

              <!-- Action Buttons -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark btn-sm py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                  <i class="bi bi-check2-circle"></i> Apply Filters
                </button>
                <?php if ($hasActiveFilters): ?>
                  <a href="<?= url('shop') ?>" class="btn btn-outline-danger btn-sm py-1.5">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All
                  </a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Grid Column -->
    <div class="col-lg-9">
      <!-- Active Filter Badges Bar (Shows when filters are active) -->
      <?php if ($hasActiveFilters): ?>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-light rounded-3">
          <span class="small text-muted fw-semibold me-1"><i class="bi bi-funnel me-1"></i>Active:</span>

          <?php if (!empty($criteria['sort']) && $criteria['sort'] !== 'newest'): 
            $sortLabel = match ($criteria['sort']) {
                'price_asc' => 'Price: Low to High',
                'price_desc' => 'Price: High to Low',
                default => 'Newest',
            };
          ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'newest', 'page' => 1]))) ?>" class="active-filter-badge">
              Sort: <?= e($sortLabel) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['access_method'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['access_method' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Unlock: <?= e($criteria['access_method']) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['min_price']) || !empty($criteria['max_price'])): 
            $priceLabel = '';
            if (!empty($criteria['min_price']) && !empty($criteria['max_price'])) {
                $priceLabel = '₹' . number_format((float)$criteria['min_price']) . ' - ₹' . number_format((float)$criteria['max_price']);
            } elseif (!empty($criteria['max_price'])) {
                $priceLabel = 'Under ₹' . number_format((float)$criteria['max_price']);
            } else {
                $priceLabel = 'Above ₹' . number_format((float)$criteria['min_price']);
            }
          ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['min_price' => null, 'max_price' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Budget: <?= e($priceLabel) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['in_stock'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['in_stock' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              In-Stock Only <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <a href="<?= url('shop') ?>" class="text-danger small ms-auto text-decoration-none fw-semibold">Clear all</a>
        </div>
      <?php endif; ?>

      <?php if (empty($products)): ?>
        <div class="card border p-4 p-md-5 text-center my-4">
          <i class="bi bi-search fs-1 text-muted mb-3"></i>
          <h4 class="fw-bold">No Matching Smart Locks Found</h4>
          <p class="text-muted">Try adjusting your filters, clearing budget criteria, or searching for other unlock methods.</p>
          <div class="mt-2">
            <a href="<?= url('shop') ?>" class="btn btn-hiq-primary">Clear All Filters</a>
          </div>
        </div>
      <?php else: ?>
        <div class="row g-2 g-md-4">
          <?php foreach ($products as $p): ?>
            <div class="col-6 col-md-6 col-lg-4">
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

<script>
// Switch sort chip
function selectSortChip(radio) {
  const form = radio.form;
  const sameNameRadios = form.querySelectorAll('input[name="sort"]');
  sameNameRadios.forEach(r => {
    const lbl = r.closest('.shop-filter-chip');
    if (lbl) lbl.classList.remove('active');
  });
  const label = radio.closest('.shop-filter-chip');
  if (label) label.classList.add('active');
}

// Allow unlock method chips to toggle off on second click
function toggleRadioChip(radio) {
  if (radio.dataset.wasChecked === 'true') {
    radio.checked = false;
    radio.dataset.wasChecked = 'false';
    const label = radio.closest('.shop-filter-chip');
    if (label) label.classList.remove('active');
  } else {
    const form = radio.form;
    const sameNameRadios = form.querySelectorAll('input[name="' + radio.name + '"]');
    sameNameRadios.forEach(r => {
      r.dataset.wasChecked = 'false';
      const lbl = r.closest('.shop-filter-chip');
      if (lbl) lbl.classList.remove('active');
    });
    radio.dataset.wasChecked = 'true';
    const label = radio.closest('.shop-filter-chip');
    if (label) label.classList.add('active');
  }
}

// Clear radio group
function clearRadio(name) {
  const form = document.getElementById('shopFilterForm');
  if (!form) return;
  const radios = form.querySelectorAll('input[name="' + name + '"]');
  radios.forEach(r => {
    r.checked = false;
    r.dataset.wasChecked = 'false';
    const lbl = r.closest('.shop-filter-chip');
    if (lbl) lbl.classList.remove('active');
  });
}

// Budget chips toggle logic (1-tap price bracket)
function toggleBudgetChip(radio, min, max) {
  const minInput = document.getElementById('filterMinPrice');
  const maxInput = document.getElementById('filterMaxPrice');
  
  if (radio.dataset.wasChecked === 'true') {
    radio.checked = false;
    radio.dataset.wasChecked = 'false';
    if (minInput) minInput.value = '';
    if (maxInput) maxInput.value = '';
    const label = radio.closest('.shop-filter-chip');
    if (label) label.classList.remove('active');
  } else {
    document.querySelectorAll('input[name="budget_range"]').forEach(r => {
      r.dataset.wasChecked = 'false';
      const lbl = r.closest('.shop-filter-chip');
      if (lbl) lbl.classList.remove('active');
    });
    radio.dataset.wasChecked = 'true';
    if (minInput) minInput.value = min;
    if (maxInput) maxInput.value = max;
    const label = radio.closest('.shop-filter-chip');
    if (label) label.classList.add('active');
  }
}

// Clear budget
function clearBudget() {
  const minInput = document.getElementById('filterMinPrice');
  const maxInput = document.getElementById('filterMaxPrice');
  if (minInput) minInput.value = '';
  if (maxInput) maxInput.value = '';
  document.querySelectorAll('input[name="budget_range"]').forEach(r => {
    r.checked = false;
    r.dataset.wasChecked = 'false';
    const lbl = r.closest('.shop-filter-chip');
    if (lbl) lbl.classList.remove('active');
  });
}

// Initialize checked states on load
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('shopFilterForm');
  if (form) {
    form.querySelectorAll('input[type="radio"]:checked').forEach(r => {
      r.dataset.wasChecked = 'true';
      const label = r.closest('.shop-filter-chip');
      if (label) label.classList.add('active');
    });
  }
});
</script>
