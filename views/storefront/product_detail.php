<?php
declare(strict_types=1);

use App\Core\View;
use App\Core\Session;

/**
 * @var array $product
 * @var array $reviews
 * @var array $related
 */

$mrp = (float)($product['price'] ?? 0);
$salePrice = (float)($product['sale_price'] ?? $mrp);
$discount = discount_percentage($mrp, $salePrice);
$stock = (int)($product['stock'] ?? 0);
$inStock = $stock > 0;

$wishlist = Session::get('wishlist', []);
$isWishlisted = in_array($product['id'], $wishlist, true);
$isBestSeller = !empty($product['is_bestseller']) || !empty($product['best_seller']) || (($product['badge'] ?? '') === 'Best Seller');

$breadcrumbs = [
    ['label' => 'Shop', 'url' => 'shop'],
    ['label' => $product['category'] ?? 'Smart Locks', 'url' => 'category/' . ($product['category_slug'] ?? '')],
    ['label' => $product['name'], 'url' => null]
];
?>

<div class="container py-3 py-md-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-4 g-lg-5 mb-4 mb-lg-5">
    <!-- Gallery Column -->
    <div class="col-lg-6">
      <div class="product-gallery-main position-relative">
        <?php if ($isBestSeller): ?>
          <div class="gallery-badge-stack">
            <span class="modern-bestseller-badge">
              <i class="bi bi-star-fill"></i> Best Seller
            </span>
          </div>
        <?php endif; ?>

        <!-- Floating Action Stack (Wishlist & Share) -->
        <div class="gallery-action-stack">
          <button type="button"
                  class="btn-card-action btn-action-wishlist <?= $isWishlisted ? 'active' : '' ?>"
                  data-product-id="<?= e($product['id'] ?? '') ?>"
                  title="<?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>"
                  aria-label="Wishlist">
            <i class="bi bi-heart<?= $isWishlisted ? '-fill text-danger' : '' ?>"></i>
          </button>

          <button type="button"
                  class="btn-card-action btn-action-share"
                  data-share-url="<?= url('product/' . $product['slug']) ?>"
                  data-share-title="<?= e($product['name'] ?? 'HOMEIQ Smart Lock') ?>"
                  title="Share product"
                  aria-label="Share product">
            <i class="bi bi-send"></i>
          </button>
        </div>

        <img id="productMainImage" src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name']) ?>" class="img-fluid" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
      </div>

      <?php
        $galleryImages = (!empty($product['gallery_images']) && is_array($product['gallery_images']))
            ? array_values(array_filter($product['gallery_images']))
            : [];
        $hasMultipleImages = (!empty($product['main_image']) && count($galleryImages) > 0) || (count($galleryImages) > 1);
      ?>
      <?php if ($hasMultipleImages): ?>
        <div class="thumbnail-row mb-3 mb-md-4">
          <?php if (!empty($product['main_image'])): ?>
            <div class="thumbnail-item active" data-img-src="<?= upload_url($product['main_image']) ?>">
              <img src="<?= upload_url($product['main_image']) ?>" alt="Primary view">
            </div>
          <?php endif; ?>

          <?php foreach ($galleryImages as $idx => $gImg): ?>
            <div class="thumbnail-item <?= (empty($product['main_image']) && $idx === 0) ? 'active' : '' ?>" data-img-src="<?= upload_url($gImg) ?>">
              <img src="<?= upload_url($gImg) ?>" alt="<?= e($product['name']) ?> view <?= $idx + 2 ?>">
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Product Purchase Column -->
    <div class="col-lg-6">
      <div class="mb-2 text-uppercase text-primary fw-bold small letter-spacing-1 d-flex align-items-center gap-2 flex-wrap">
        <?php if ($isBestSeller): ?>
          <span class="product-detail-bestseller-badge">
            <i class="bi bi-star-fill"></i> Best Seller
          </span>
        <?php endif; ?>
        <span><?= e($product['category'] ?? 'Smart Lock') ?> • SKU: <?= e($product['sku']) ?></span>
      </div>

      <h1 class="h2 fw-bold text-dark mb-3"><?= e($product['name']) ?></h1>

      <!-- Rating & Reviews -->
      <div class="d-flex align-items-center flex-wrap gap-2 gap-sm-3 mb-3">
        <div class="d-flex align-items-center text-warning">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <i class="bi bi-star<?= $i <= round((float)($product['rating'] ?? 4.8)) ? '-fill' : '' ?>"></i>
          <?php endfor; ?>
          <span class="ms-2 fw-bold text-dark"><?= number_format((float)($product['rating'] ?? 4.8), 1) ?></span>
        </div>
        <span class="text-muted">|</span>
        <a href="#reviewsTab" class="text-decoration-none text-muted small" onclick="bootstrap.Tab.getOrCreateInstance(document.getElementById('reviews-tab')).show();">
          <?= (int)($product['reviews_count'] ?? count($reviews)) ?> Customer Reviews
        </a>
      </div>

      <!-- Price Hierarchy -->
      <div class="pdp-price-panel mb-3">
        <div class="d-flex align-items-baseline flex-wrap gap-2 gap-sm-3">
          <span class="fs-2 fw-extrabold text-dark"><?= format_price($salePrice) ?></span>
          <?php if ($mrp > $salePrice): ?>
            <span class="fs-5 text-muted text-decoration-line-through"><?= format_price($mrp) ?></span>
            <span class="pdp-discount-badge"><?= $discount ?>% OFF</span>
          <?php endif; ?>
          <span class="text-secondary small ms-sm-auto">Inclusive of all taxes</span>
        </div>
      </div>

      <!-- Short Description -->
      <p class="text-muted mb-3 fs-6 leading-relaxed">
        <?= e($product['short_description']) ?>
      </p>

      <!-- Key Hardware Specifications & Highlights ("About This Lock") -->
      <?php if (!empty($product['features']) || !empty($product['access_methods'])): ?>
        <div class="product-highlights-box">
          <div class="product-highlights-title">
            <span class="d-inline-flex align-items-center gap-2">
              <i class="bi bi-shield-check text-primary"></i> Key Specifications & Highlights
            </span>
            <a href="#descTab" class="product-highlights-link" onclick="bootstrap.Tab.getOrCreateInstance(document.getElementById('desc-tab')).show();">
              Full Details <i class="bi bi-arrow-down-short"></i>
            </a>
          </div>

          <?php if (!empty($product['features'])): ?>
            <div class="product-highlights-list">
              <?php foreach ($product['features'] as $feat): ?>
                <span class="product-highlight-badge">
                  <i class="bi bi-check-circle-fill"></i> <?= e($feat) ?>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($product['access_methods'])): ?>
            <div class="product-access-strip">
              <div class="product-access-label">Supported Unlock Methods:</div>
              <div class="product-access-tags">
                <?php foreach ($product['access_methods'] as $method): ?>
                  <?php
                    $methodIcon = 'bi-shield-lock-fill';
                    $mLower = strtolower($method);
                    if (str_contains($mLower, 'face')) {
                        $methodIcon = 'bi-camera-video-fill';
                    } elseif (str_contains($mLower, 'fingerprint')) {
                        $methodIcon = 'bi-fingerprint';
                    } elseif (str_contains($mLower, 'vein')) {
                        $methodIcon = 'bi-hand-index-thumb-fill';
                    } elseif (str_contains($mLower, 'pin')) {
                        $methodIcon = 'bi-grid-3x3-gap-fill';
                    } elseif (str_contains($mLower, 'card') || str_contains($mLower, 'rfid')) {
                        $methodIcon = 'bi-credit-card-2-front-fill';
                    } elseif (str_contains($mLower, 'key')) {
                        $methodIcon = 'bi-key-fill';
                    } elseif (str_contains($mLower, 'app') || str_contains($mLower, 'mobile')) {
                        $methodIcon = 'bi-phone-fill';
                    } elseif (str_contains($mLower, 'password')) {
                        $methodIcon = 'bi-lock-fill';
                    } elseif (str_contains($mLower, 'auth') || str_contains($mLower, 'dual')) {
                        $methodIcon = 'bi-shield-check';
                    }
                  ?>
                  <span class="product-access-tag">
                    <i class="bi <?= $methodIcon ?>"></i> <?= e($method) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Stock Availability Indicator -->
      <div class="mb-4">
        <?php if ($inStock): ?>
          <div class="text-success fw-semibold small d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> In Stock & Ready for Dispatch (<?= $stock ?> units available)
          </div>
        <?php else: ?>
          <div class="text-danger fw-semibold small d-flex align-items-center gap-2">
            <i class="bi bi-x-circle-fill"></i> Currently Out of Stock
          </div>
        <?php endif; ?>
      </div>

      <!-- Add to Cart Form -->
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="mb-4">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">

          <div class="d-flex align-items-center gap-3 mb-3">
            <label class="form-label small fw-bold text-muted mb-0">Quantity:</label>
            <div class="pdp-qty-stepper input-group">
              <button type="button" class="pdp-qty-btn btn-qty-minus" aria-label="Decrease quantity">
                <i class="bi bi-dash"></i>
              </button>
              <input type="number" name="quantity" class="pdp-qty-input input-qty" value="1" min="1" max="<?= $stock ?>" readonly>
              <button type="button" class="pdp-qty-btn btn-qty-plus" aria-label="Increase quantity">
                <i class="bi bi-plus"></i>
              </button>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 gap-sm-3 product-purchase-actions">
            <button type="submit" class="btn btn-pdp-cart flex-grow-1 py-2 py-md-3">
              <i class="bi bi-bag-plus"></i> Add to Cart
            </button>
            <button type="submit" name="buy_now" value="1" class="btn btn-pdp-buy flex-grow-1 py-2 py-md-3">
              <i class="bi bi-lightning-charge"></i> Buy Now
            </button>
          </div>
        </form>
      <?php else: ?>
        <div class="alert alert-warning py-3">
          This digital smart lock model is currently out of stock. Contact our support team for priority backorder reservation.
        </div>
      <?php endif; ?>

      <!-- Trust Badges -->
      <div class="row g-2 g-sm-3 text-secondary small">
        <div class="col-6 d-flex align-items-center gap-2">
          <i class="bi bi-shield-check text-primary fs-5 flex-shrink-0"></i>
          <span><?= e($product['warranty'] ?? '2-Year Official Warranty') ?></span>
        </div>
        <div class="col-6 d-flex align-items-center gap-2">
          <i class="bi bi-tools text-primary fs-5 flex-shrink-0"></i>
          <span><?= e($product['installation'] ?? 'Free Door Installation') ?></span>
        </div>
        <div class="col-6 d-flex align-items-center gap-2">
          <i class="bi bi-arrow-repeat text-primary fs-5 flex-shrink-0"></i>
          <span>7-Day Return Policy</span>
        </div>
        <div class="col-6 d-flex align-items-center gap-2">
          <i class="bi bi-headset text-primary fs-5 flex-shrink-0"></i>
          <span>Lifetime Tech Support</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Tabs Section -->
  <div class="card border rounded-3 mb-5 shadow-sm">
    <div class="card-header bg-white border-bottom p-0">
      <ul class="nav nav-tabs card-header-tabs m-0 px-2 px-md-3 border-0 flex-nowrap overflow-x-auto text-nowrap" id="productTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-bold py-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descTab" type="button" role="tab">About This Product</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-3" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specsTab" type="button" role="tab">Specifications</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-3" id="included-tab" data-bs-toggle="tab" data-bs-target="#includedTab" type="button" role="tab">What's Included</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-3" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviewsTab" type="button" role="tab">Reviews (<?= count($reviews) ?>)</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-3" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faqTab" type="button" role="tab">FAQs</button>
        </li>
      </ul>
    </div>

    <div class="card-body p-3 p-sm-4 p-md-5">
      <div class="tab-content" id="productTabsContent">
        <!-- 1. Description Tab -->
        <div class="tab-pane fade show active" id="descTab" role="tabpanel">
          <h4 class="fw-bold mb-4">About This Product</h4>
          <div class="text-secondary leading-relaxed mb-5" style="white-space: pre-line;">
            <?= e($product['full_description']) ?>
          </div>

          <?php if (!empty($product['features'])): ?>
            <h5 class="fw-bold mb-3">Key Features & Security Capabilities</h5>
            <div class="row g-3 mb-4">
              <?php foreach ($product['features'] as $feature): ?>
                <div class="col-md-6 d-flex align-items-center gap-2">
                  <i class="bi bi-check-circle-fill text-primary"></i>
                  <span class="text-dark fw-medium"><?= e($feature) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($product['access_methods'])): ?>
            <h5 class="fw-bold mb-3 mt-4">Supported Access & Unlock Methods</h5>
            <div class="row g-2 mb-4">
              <?php foreach ($product['access_methods'] as $acc): ?>
                <div class="col-sm-6 col-md-4">
                  <div class="p-2 px-3 bg-light rounded-2 border d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-primary"></i>
                    <span class="text-dark small fw-semibold"><?= e($acc) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($product['connectivity'])): ?>
            <h5 class="fw-bold mb-3 mt-4">Smart Connectivity & Protocol</h5>
            <div class="d-flex flex-wrap gap-2 mb-2">
              <?php foreach ($product['connectivity'] as $conn): ?>
                <span class="badge bg-light text-dark border px-3 py-2 fs-6 fw-medium d-inline-flex align-items-center gap-2">
                  <i class="bi bi-wifi text-primary"></i> <?= e($conn) ?>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- 2. Specifications Tab -->
        <div class="tab-pane fade" id="specsTab" role="tabpanel">
          <h4 class="fw-bold mb-4">Technical Specifications</h4>
          <div class="table-responsive">
            <table class="specs-table">
              <tbody>
                <tr><th>Brand</th><td><?= e($product['specifications']['Brand'] ?? 'HOMEIQ') ?></td></tr>
                <tr><th>Lock Type</th><td><?= e($product['lock_type'] ?? 'Mortise Lock') ?></td></tr>
                <tr><th>Finish / Colour</th><td><?= e($product['color'] ?? 'Black') ?></td></tr>
                <tr><th>Dimensions</th><td><?= e($product['dimensions'] ?: '40 × 17 × 9 cm') ?></td></tr>
                <tr><th>Weight</th><td><?= e($product['weight'] ?: '1.5 kg') ?></td></tr>
                <tr><th>Door Thickness</th><td><?= e($product['specifications']['Door Thickness Suitability'] ?? '35mm to 90mm') ?></td></tr>
                <?php foreach (($product['specifications'] ?? []) as $key => $val): ?>
                  <?php if (!in_array($key, ['Brand', 'Dimensions', 'Weight'])): ?>
                    <tr><th><?= e($key) ?></th><td><?= e($val) ?></td></tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3. What's Included Tab -->
        <div class="tab-pane fade" id="includedTab" role="tabpanel">
          <h4 class="fw-bold mb-4">Box Contents</h4>
          <div class="row g-3">
            <?php foreach (($product['whats_included'] ?? []) as $item): ?>
              <div class="col-md-6 col-lg-4">
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                  <i class="bi bi-box2-fill text-primary fs-4"></i>
                  <span class="text-dark fw-semibold"><?= e($item) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- 4. Reviews Tab -->
        <div class="tab-pane fade" id="reviewsTab" role="tabpanel">
          <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
              <h4 class="fw-bold mb-0">Verified Customer Reviews</h4>
              <small class="text-muted"><?= count($reviews) ?> verified homeowner reviews published</small>
            </div>
            <button class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#writeReviewForm">
              <i class="bi bi-pencil-square me-1"></i> Write a Review
            </button>
          </div>

          <!-- Write Review Form (Collapsible) -->
          <div class="collapse mb-4" id="writeReviewForm">
            <div class="card p-4 border bg-light">
              <h5 class="fw-bold mb-3">Submit Your Review</h5>
              <form action="<?= url('product/review') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="reviewer_name" class="form-control" required value="<?= e(auth_customer()['name'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Rating *</label>
                    <select name="rating" class="form-select">
                      <option value="5">5 Stars — Excellent Security</option>
                      <option value="4">4 Stars — Very Good</option>
                      <option value="3">3 Stars — Average</option>
                      <option value="2">2 Stars — Below Expectations</option>
                      <option value="1">1 Star — Poor</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Review Headline</label>
                  <input type="text" name="title" class="form-control" placeholder="e.g. Fast face unlock and solid build">
                </div>

                <div class="mb-3">
                  <label class="form-label">Detailed Feedback *</label>
                  <textarea name="comment" rows="4" class="form-control" placeholder="Tell other homeowners about installation, day-to-day access convenience, battery life, etc." required></textarea>
                </div>

                <button type="submit" class="btn btn-dark">Submit for Moderation</button>
              </form>
            </div>
          </div>

          <!-- Reviews List -->
          <?php if (empty($reviews)): ?>
            <p class="text-muted">No published reviews yet for this model. Be the first verified customer to share your thoughts!</p>
          <?php else: ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($reviews as $rev): ?>
                <div class="card p-3 border shadow-sm">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="text-warning small">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= ($rev['rating'] ?? 5) ? '-fill' : '' ?>"></i>
                      <?php endfor; ?>
                    </div>
                    <?php if (!empty($rev['verified_purchase'])): ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle small">
                        <i class="bi bi-patch-check-fill me-1"></i>Verified Purchaser
                      </span>
                    <?php endif; ?>
                  </div>
                  <h6 class="fw-bold mb-1"><?= e($rev['title'] ?? '') ?></h6>
                  <p class="text-muted small mb-2"><?= e($rev['comment'] ?? '') ?></p>
                  <div class="small text-secondary">
                    <?= e($rev['reviewer_name'] ?? 'Homeowner') ?> • <?= e(explode(' ', $rev['created_at'] ?? '')[0]) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- 5. FAQs Tab -->
        <div class="tab-pane fade" id="faqTab" role="tabpanel">
          <h4 class="fw-bold mb-4">Frequently Asked Questions</h4>
          <div class="accordion" id="lockFaqAccordion">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
                  What happens if the battery runs out completely?
                </button>
              </h2>
              <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-muted">
                  You will never be locked out. HOMEIQ locks feature an external USB-C emergency power port that allows you to jump-start the system with any standard power bank. Furthermore, two computerized physical mechanical override keys are included.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                  How does the free installation process work?
                </button>
              </h2>
              <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-muted">
                  Upon order dispatch, a certified HOMEIQ hardware technician contacts you to schedule an installation appointment at your convenience. They bring the necessary mortise templates and tools to ensure perfect door alignment.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                  Can this lock be installed on existing wooden doors?
                </button>
              </h2>
              <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-muted">
                  Yes. HOMEIQ mortise locks are engineered to fit wooden, metal, flush, and composite doors with thicknesses between 35mm and 90mm.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Digital Locks -->
  <?php if (!empty($related)): ?>
    <div class="mb-4 mb-md-5">
      <h3 class="fw-bold mb-4">Related Smart Door Locks</h3>
      <div class="row g-2 g-sm-3 g-md-4">
        <?php foreach ($related as $rel): ?>
          <div class="col-6 col-md-4 col-lg-3">
            <?= View::component('product_card', ['product' => $rel]) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

