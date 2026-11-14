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

// Active filter count calculation
$activeFilterCount = 0;
if (!empty($criteria['category'])) $activeFilterCount++;
if (!empty($criteria['access_method'])) $activeFilterCount++;
if (!empty($criteria['connectivity'])) $activeFilterCount++;
if (!empty($criteria['color'])) $activeFilterCount++;
if (!empty($criteria['in_stock'])) $activeFilterCount++;
if (!empty($criteria['min_price']) || !empty($criteria['max_price'])) $activeFilterCount++;
if (!empty($criteria['min_rating'])) $activeFilterCount++;
if (!empty($criteria['sort']) && $criteria['sort'] !== 'newest') $activeFilterCount++;
$hasActiveFilters = $activeFilterCount > 0 || !empty($criteria['q']);

// Helper to get access method icon
$getMethodIcon = function (string $method): string {
    $lower = strtolower($method);
    if (str_contains($lower, 'fingerprint')) return 'bi-fingerprint';
    if (str_contains($lower, 'face')) return 'bi-person-bounding-box';
    if (str_contains($lower, 'palm') || str_contains($lower, 'vein')) return 'bi-hand-index-thumb';
    if (str_contains($lower, 'pin') || str_contains($lower, 'keypad') || str_contains($lower, 'passcode')) return 'bi-grid-3x3-gap-fill';
    if (str_contains($lower, 'app') || str_contains($lower, 'remote') || str_contains($lower, 'mobile')) return 'bi-phone';
    if (str_contains($lower, 'card') || str_contains($lower, 'rfid') || str_contains($lower, 'nfc')) return 'bi-credit-card-2-front';
    if (str_contains($lower, 'key') || str_contains($lower, 'mechanical')) return 'bi-key';
    return 'bi-shield-check';
};

// Helper to get color swatch hex
$getColorHex = function (string $color): string {
    $lower = strtolower($color);
    if (str_contains($lower, 'space grey') || str_contains($lower, 'space gray')) return '#4a4d52';
    if (str_contains($lower, 'titanium')) return '#6e7075';
    if (str_contains($lower, 'black') || str_contains($lower, 'matte black')) return '#1a1a1a';
    if (str_contains($lower, 'silver') || str_contains($lower, 'chrome')) return '#d1d5db';
    if (str_contains($lower, 'gold') || str_contains($lower, 'champagne')) return '#d4af37';
    if (str_contains($lower, 'bronze') || str_contains($lower, 'copper')) return '#8c6239';
    return '#333333';
};
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
        <option value="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'rating', 'page' => 1]))) ?>" <?= ($criteria['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Customer Rating</option>
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
            <h5 class="offcanvas-title fw-bold m-0" id="shopFilterOffcanvasLabel">Filter Products</h5>
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
              <!-- Search query & sort persistence -->
              <?php if (!empty($criteria['q'])): ?>
                <input type="hidden" name="q" value="<?= e($criteria['q']) ?>">
              <?php endif; ?>
              <!-- Sort / New Arrivals Filter -->
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Sort By</span>
                </label>
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
                  <label class="shop-filter-chip <?= ($criteria['sort'] ?? '') === 'rating' ? 'active' : '' ?>">
                    <input type="radio" name="sort" value="rating" <?= ($criteria['sort'] ?? '') === 'rating' ? 'checked' : '' ?> onclick="selectSortChip(this)">
                    <i class="bi bi-star-fill text-warning me-1"></i>
                    <span>Customer Rating</span>
                  </label>
                </div>
              </div>

              <!-- Category Filter Chips -->
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Category</span>
                  <?php if (!empty($criteria['category'])): ?>
                    <span class="text-muted font-monospace small" style="font-size: 0.72rem;">1 selected</span>
                  <?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  <label class="shop-filter-chip">
                    <input type="radio" name="category" value="" <?= empty($criteria['category']) ? 'checked' : '' ?> onchange="this.form.submit()">
                    <span>All</span>
                  </label>
                  <?php foreach ($categories as $cat): ?>
                    <label class="shop-filter-chip">
                      <input type="radio" name="category" value="<?= e($cat['slug']) ?>" <?= ($criteria['category'] ?? '') === $cat['slug'] ? 'checked' : '' ?> onchange="this.form.submit()">
                      <span><?= e($cat['name']) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Access Method Filter Chips -->
              <?php if (!empty($availableMethods)): ?>
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Access Method</span>
                  <?php if (!empty($criteria['access_method'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearRadio('access_method')">Clear</a>
                  <?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  <?php foreach ($availableMethods as $method): 
                    $isSelected = ($criteria['access_method'] ?? '') === $method;
                    $icon = $getMethodIcon($method);
                  ?>
                    <label class="shop-filter-chip <?= $isSelected ? 'active' : '' ?>">
                      <input type="radio" name="access_method" value="<?= e($method) ?>" <?= $isSelected ? 'checked' : '' ?> onclick="toggleRadioChip(this)">
                      <i class="bi <?= $icon ?> me-1"></i>
                      <span><?= e($method) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Budget & Price Range Filter -->
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Price Range (₹)</span>
                  <?php if (!empty($criteria['min_price']) || !empty($criteria['max_price'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearPrice()">Clear</a>
                  <?php endif; ?>
                </label>

                <!-- Quick Budget Chips -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-2.5 small budget-quick-btn" onclick="setBudget('', 15000, this)">Under ₹15K</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-2.5 small budget-quick-btn" onclick="setBudget(15000, 20000, this)">₹15K - ₹20K</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-2.5 small budget-quick-btn" onclick="setBudget(20000, 25000, this)">₹20K - ₹25K</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-2.5 small budget-quick-btn" onclick="setBudget(25000, '', this)">₹25K+</button>
                </div>

                <!-- Custom Min & Max Inputs -->
                <div class="row g-2 align-items-center">
                  <div class="col-5">
                    <div class="input-group input-group-sm">
                      <span class="input-group-text bg-light text-muted">₹</span>
                      <input type="number" id="minPriceInput" name="min_price" class="form-control" placeholder="Min" value="<?= e($criteria['min_price'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-2 text-center text-muted small">to</div>
                  <div class="col-5">
                    <div class="input-group input-group-sm">
                      <span class="input-group-text bg-light text-muted">₹</span>
                      <input type="number" id="maxPriceInput" name="max_price" class="form-control" placeholder="Max" value="<?= e($criteria['max_price'] ?? '') ?>">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Connectivity Filter Chips -->
              <?php if (!empty($availableConnectivity)): ?>
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Connectivity</span>
                  <?php if (!empty($criteria['connectivity'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearRadio('connectivity')">Clear</a>
                  <?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  <?php foreach ($availableConnectivity as $conn): 
                    $isSelected = ($criteria['connectivity'] ?? '') === $conn;
                    $cIcon = 'bi-broadcast';
                    if (stripos($conn, 'wi-fi') !== false || stripos($conn, 'wifi') !== false) $cIcon = 'bi-wifi';
                    elseif (stripos($conn, 'bluetooth') !== false) $cIcon = 'bi-bluetooth';
                    elseif (stripos($conn, 'tuya') !== false) $cIcon = 'bi-house-gear';
                  ?>
                    <label class="shop-filter-chip <?= $isSelected ? 'active' : '' ?>">
                      <input type="radio" name="connectivity" value="<?= e($conn) ?>" <?= $isSelected ? 'checked' : '' ?> onclick="toggleRadioChip(this)">
                      <i class="bi <?= $cIcon ?> me-1"></i>
                      <span><?= e($conn) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Color / Hardware Finish Swatch Chips -->
              <?php if (!empty($availableColors)): ?>
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1 d-flex justify-content-between align-items-center">
                  <span>Hardware Finish</span>
                  <?php if (!empty($criteria['color'])): ?>
                    <a href="javascript:void(0)" class="text-muted small text-decoration-none" onclick="clearRadio('color')">Clear</a>
                  <?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  <?php foreach ($availableColors as $col): 
                    $isSelected = ($criteria['color'] ?? '') === $col;
                    $hex = $getColorHex($col);
                  ?>
                    <label class="shop-filter-chip <?= $isSelected ? 'active' : '' ?>">
                      <input type="radio" name="color" value="<?= e($col) ?>" <?= $isSelected ? 'checked' : '' ?> onclick="toggleRadioChip(this)">
                      <span class="color-swatch-dot" style="background-color: <?= $hex ?>;"></span>
                      <span><?= e($col) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Customer Rating Filter -->
              <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase letter-spacing-1">Customer Rating</label>
                <div class="d-flex flex-wrap gap-2">
                  <label class="shop-filter-chip <?= ($criteria['min_rating'] ?? '') === '4' ? 'active' : '' ?>">
                    <input type="radio" name="min_rating" value="4" <?= ($criteria['min_rating'] ?? '') === '4' ? 'checked' : '' ?> onclick="toggleRadioChip(this)">
                    <i class="bi bi-star-fill text-warning me-1"></i>
                    <span>4.0★ & above</span>
                  </label>
                </div>
              </div>

              <!-- Availability / In-Stock Only -->
              <div class="form-check form-switch mb-4 p-2 bg-light rounded-3 d-flex align-items-center justify-content-between">
                <label class="form-check-label small fw-semibold cursor-pointer m-0 ps-1" for="inStockOnly">
                  <i class="bi bi-check-circle-fill text-success me-1"></i> In-Stock Only
                </label>
                <input class="form-check-input m-0 cursor-pointer" type="checkbox" name="in_stock" value="1" id="inStockOnly" <?= !empty($criteria['in_stock']) ? 'checked' : '' ?>>
              </div>

              <!-- Actions -->
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

          <?php if (!empty($criteria['category'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['category' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Category: <?= e($criteria['category']) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['access_method'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['access_method' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Method: <?= e($criteria['access_method']) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['connectivity'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['connectivity' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Connectivity: <?= e($criteria['connectivity']) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['color'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['color' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Color: <?= e($criteria['color']) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['min_price']) || !empty($criteria['max_price'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['min_price' => null, 'max_price' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Price: ₹<?= e($criteria['min_price'] ?? '0') ?> - <?= !empty($criteria['max_price']) ? '₹' . e($criteria['max_price']) : 'Above' ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['min_rating'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['min_rating' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              Rating: 4★+ <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['in_stock'])): ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['in_stock' => null, 'page' => 1]))) ?>" class="active-filter-badge">
              In-Stock Only <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <?php if (!empty($criteria['sort']) && $criteria['sort'] !== 'newest'): 
            $sortLabel = match ($criteria['sort']) {
                'price_asc' => 'Price: Low to High',
                'price_desc' => 'Price: High to Low',
                'rating' => 'Rating',
                default => 'Newest Arrivals',
            };
          ?>
            <a href="<?= url('shop?' . http_build_query(array_merge($criteria, ['sort' => 'newest', 'page' => 1]))) ?>" class="active-filter-badge">
              Sort: <?= e($sortLabel) ?> <i class="bi bi-x-circle-fill"></i>
            </a>
          <?php endif; ?>

          <a href="<?= url('shop') ?>" class="text-danger small ms-auto text-decoration-none fw-semibold">Clear all</a>
        </div>
      <?php endif; ?>

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

// Allow radio chips to be toggled off if clicked when already active
function toggleRadioChip(radio) {
  if (radio.dataset.wasChecked === 'true') {
    radio.checked = false;
    radio.dataset.wasChecked = 'false';
    const label = radio.closest('.shop-filter-chip');
    if (label) label.classList.remove('active');
  } else {
    // Unmark siblings
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

// Clear specific radio filter group
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

// Quick budget setter
function setBudget(min, max, btn) {
  const minInput = document.getElementById('minPriceInput');
  const maxInput = document.getElementById('maxPriceInput');
  if (minInput) minInput.value = min;
  if (maxInput) maxInput.value = max;
  
  // Highlight active budget button
  document.querySelectorAll('.budget-quick-btn').forEach(b => {
    b.classList.remove('btn-dark', 'text-white');
    b.classList.add('btn-outline-secondary');
  });
  if (btn) {
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-dark', 'text-white');
  }
}

// Clear price inputs
function clearPrice() {
  const minInput = document.getElementById('minPriceInput');
  const maxInput = document.getElementById('maxPriceInput');
  if (minInput) minInput.value = '';
  if (maxInput) maxInput.value = '';
  document.querySelectorAll('.budget-quick-btn').forEach(b => {
    b.classList.remove('btn-dark', 'text-white');
    b.classList.add('btn-outline-secondary');
  });
}

// Initialize checked state on load
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
