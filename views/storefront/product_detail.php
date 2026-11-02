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

$breadcrumbs = [
    ['label' => 'Shop Collection', 'url' => 'shop'],
    ['label' => $product['category'] ?? 'Smart Locks', 'url' => 'category/' . ($product['category_slug'] ?? '')],
    ['label' => $product['name'], 'url' => null]
];
?>

<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-5 mb-5 align-items-start">
    <!-- Gallery Column (Studio Pedestal) -->
    <div class="col-lg-6">
      <div class="product-gallery-main">
        <img id="productMainImage" src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name']) ?>" class="img-fluid">
      </div>

      <div class="thumbnail-row">
        <div class="thumbnail-item active" data-img-src="<?= upload_url($product['main_image'] ?? '') ?>">
          <img src="<?= upload_url($product['main_image'] ?? '') ?>" alt="Primary view">
        </div>
        <!-- Thumbnail variant previews -->
        <div class="thumbnail-item" data-img-src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>">
          <img src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>" alt="Chassis Profile">
        </div>
        <div class="thumbnail-item" data-img-src="<?= upload_url('products/mortise-lock.svg') ?>">
          <img src="<?= upload_url('products/mortise-lock.svg') ?>" alt="Mortise Detail">
        </div>
        <div class="thumbnail-item" data-img-src="<?= upload_url('products/video-door-lock.svg') ?>">
          <img src="<?= upload_url('products/video-door-lock.svg') ?>" alt="Display Module">
        </div>
      </div>

      <!-- Quick Technical Highlights Bar -->
      <div class="mt-4 p-3 bg-white border rounded-3 d-flex align-items-center justify-content-around text-center small text-secondary">
        <div>
          <i class="bi bi-shield-lock-fill text-info fs-5 d-block mb-1"></i>
          <span class="fw-bold text-dark">Grade C Cylinder</span>
        </div>
        <div class="vr"></div>
        <div>
          <i class="bi bi-cpu text-info fs-5 d-block mb-1"></i>
          <span class="fw-bold text-dark">0.3s Biometrics</span>
        </div>
        <div class="vr"></div>
        <div>
          <i class="bi bi-cloud-check text-info fs-5 d-block mb-1"></i>
          <span class="fw-bold text-dark">Tuya Smart IoT</span>
        </div>
        <div class="vr"></div>
        <div>
          <i class="bi bi-battery-charging text-info fs-5 d-block mb-1"></i>
          <span class="fw-bold text-dark">Emergency USB-C</span>
        </div>
      </div>
    </div>

    <!-- Product Purchase Column -->
    <div class="col-lg-6">
      <div class="d-flex align-items-center gap-2 mb-2">
        <span class="badge bg-dark text-info text-uppercase letter-spacing-1 fw-bold px-2 py-1 small">
          <?= e($product['category'] ?? 'Digital Smart Lock') ?>
        </span>
        <span class="text-muted small">SKU: <?= e($product['sku']) ?></span>
      </div>

      <h1 class="h2 fw-extrabold text-dark mb-3" style="letter-spacing: -0.03em;">
        <?= e($product['name']) ?>
      </h1>

      <!-- Rating & Customer Reviews -->
      <div class="d-flex align-items-center gap-3 mb-4">
        <span class="rating-pill">
          <i class="bi bi-star-fill text-warning"></i>
          <strong><?= number_format((float)($product['rating'] ?? 4.8), 1) ?> / 5.0</strong>
        </span>
        <span class="text-muted">|</span>
        <a href="#reviewsTab" class="text-decoration-none text-muted small hover-cyan" onclick="bootstrap.Tab.getOrCreateInstance(document.getElementById('reviews-tab')).show();">
          <i class="bi bi-chat-left-text me-1"></i><?= (int)($product['reviews_count'] ?? count($reviews)) ?> Verified Reviews
        </a>
        <span class="text-muted">|</span>
        <span class="text-success small fw-medium"><i class="bi bi-patch-check-fill me-1"></i>Official Certified</span>
      </div>

      <!-- Luxury Price Card -->
      <div class="p-3 mb-4 bg-white border rounded-3 shadow-xs">
        <div class="d-flex align-items-baseline gap-3">
          <span class="fs-2 fw-extrabold text-dark" style="font-family: var(--hiq-font-heading);"><?= format_price($salePrice) ?></span>
          <?php if ($mrp > $salePrice): ?>
            <span class="fs-5 text-muted text-decoration-line-through"><?= format_price($mrp) ?></span>
            <span class="badge-discount-luxury position-static"><?= $discount ?>% PRIVILEGE SAVING</span>
          <?php endif; ?>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top text-secondary small">
          <span>Inclusive of all taxes & insurance</span>
          <span class="text-primary fw-medium"><i class="bi bi-credit-card me-1"></i>EMI starting from ₹1,299/mo</span>
        </div>
      </div>

      <!-- Short Description -->
      <p class="text-secondary mb-4 leading-relaxed">
        <?= e($product['short_description']) ?>
      </p>

      <!-- Stock Availability Indicator -->
      <div class="mb-4">
        <?php if ($inStock): ?>
          <div class="badge-stock-live fs-6 mb-0">
            In Stock & Ready for Priority Dispatch (<?= $stock ?> units available)
          </div>
        <?php else: ?>
          <div class="badge-stock-out fs-6 mb-0">
            Currently Out of Stock • Contact concierge for priority allotment
          </div>
        <?php endif; ?>
      </div>

      <!-- Add to Cart Form -->
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="mb-4">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">

          <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
              <label class="form-label small fw-bold text-muted mb-0">Quantity:</label>
            </div>
            <div class="col-auto">
              <div class="input-group" style="width: 130px;">
                <button type="button" class="btn btn-outline-secondary btn-qty-minus"><i class="bi bi-dash"></i></button>
                <input type="number" name="quantity" class="form-control text-center input-qty" value="1" min="1" max="<?= $stock ?>">
                <button type="button" class="btn btn-outline-secondary btn-qty-plus"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>

          <div class="d-flex gap-3">
            <button type="submit" class="btn btn-hiq-primary flex-grow-1 py-3 fs-6">
              <i class="bi bi-bag-plus"></i> Add to Cart
            </button>
            <button type="submit" name="buy_now" value="1" class="btn btn-hiq-accent flex-grow-1 py-3 fs-6">
              <i class="bi bi-lightning-charge-fill"></i> Instant Checkout
            </button>
            <button type="button" class="btn btn-outline-secondary btn-wishlist-toggle px-3 position-static <?= $isWishlisted ? 'active' : '' ?>" data-product-id="<?= e($product['id']) ?>" title="Save to Wishlist" style="width: 52px; height: 52px;">
              <i class="bi bi-heart<?= $isWishlisted ? '-fill' : '' ?> fs-5"></i>
            </button>
          </div>
        </form>
      <?php else: ?>
        <div class="alert alert-warning py-3 rounded-3 mb-4">
          <i class="bi bi-info-circle me-1"></i> This model is currently sold out. Please contact our concierge desk for reservation in the next batch.
        </div>
      <?php endif; ?>

      <!-- PIN Code Delivery Checker -->
      <div class="card border bg-white p-3 mb-4 rounded-3 shadow-xs">
        <label for="pincodeCheckInput" class="form-label fw-bold small text-dark d-flex align-items-center gap-2 mb-2">
          <i class="bi bi-geo-alt-fill text-info"></i> Delivery & Certified Installation Availability
        </label>
        <div class="input-group">
          <input type="text" id="pincodeCheckInput" class="form-control" placeholder="Enter your 6-digit PIN code" maxlength="6">
          <button type="button" id="btnCheckPincode" class="btn btn-dark fw-bold px-4">Verify</button>
        </div>
        <div id="pincodeResult"></div>
      </div>

      <!-- Reassurance Badges 4-Grid -->
      <div class="reassurance-grid">
        <div class="reassurance-item">
          <i class="bi bi-tools"></i>
          <div>
            <div class="title">Doorstep Installation</div>
            <div class="desc"><?= e($product['installation'] ?? 'Free Door Installation by Certified Tech') ?></div>
          </div>
        </div>
        <div class="reassurance-item">
          <i class="bi bi-shield-check"></i>
          <div>
            <div class="title">2-Year Full Warranty</div>
            <div class="desc"><?= e($product['warranty'] ?? 'Comprehensive Parts & Electronics Protection') ?></div>
          </div>
        </div>
        <div class="reassurance-item">
          <i class="bi bi-arrow-repeat"></i>
          <div>
            <div class="title">7-Day Replacement</div>
            <div class="desc">Assured quality or doorstep replacement</div>
          </div>
        </div>
        <div class="reassurance-item">
          <i class="bi bi-headset"></i>
          <div>
            <div class="title">Dedicated Onboarding</div>
            <div class="desc">Live phone & video technician assistance</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Tabs Section (Showroom Technical Specifications) -->
  <div class="card border rounded-3 mb-5 shadow-xs bg-white">
    <div class="card-header bg-white border-bottom p-0">
      <ul class="nav nav-tabs card-header-tabs m-0 px-3 border-0" id="productTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active py-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descTab" type="button" role="tab">Overview & Features</button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specsTab" type="button" role="tab">Technical Specs</button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3" id="included-tab" data-bs-toggle="tab" data-bs-target="#includedTab" type="button" role="tab">In the Box</button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviewsTab" type="button" role="tab">Customer Reviews (<?= count($reviews) ?>)</button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faqTab" type="button" role="tab">Installation FAQs</button>
        </li>
      </ul>
    </div>

    <div class="card-body p-4 p-md-5">
      <div class="tab-content" id="productTabsContent">
        <!-- 1. Description Tab -->
        <div class="tab-pane fade show active" id="descTab" role="tabpanel">
          <h4 class="fw-bold mb-3">Architectural Overview</h4>
          <div class="text-secondary leading-relaxed mb-5" style="white-space: pre-line; line-height: 1.8;">
            <?= e($product['full_description']) ?>
          </div>

          <?php if (!empty($product['features'])): ?>
            <h5 class="fw-bold mb-3">Hardware Features & Security Capabilities</h5>
            <div class="row g-3">
              <?php foreach ($product['features'] as $feature): ?>
                <div class="col-md-6 d-flex align-items-center gap-2">
                  <i class="bi bi-check-circle-fill text-info"></i>
                  <span class="text-dark fw-medium"><?= e($feature) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- 2. Specifications Tab -->
        <div class="tab-pane fade" id="specsTab" role="tabpanel">
          <h4 class="fw-bold mb-4">Engineering Specifications</h4>
          <div class="table-responsive">
            <table class="specs-table">
              <tbody>
                <tr><th>Brand</th><td><?= e($product['specifications']['Brand'] ?? 'HOMEIQ') ?></td></tr>
                <tr><th>Lock Mortise Type</th><td><?= e($product['lock_type'] ?? 'High-Security Multi-Point Mortise') ?></td></tr>
                <tr><th>Chassis Finish</th><td><?= e($product['color'] ?? 'Black Titanium Obsidian') ?></td></tr>
                <tr><th>Outer Dimensions</th><td><?= e($product['dimensions'] ?: '40 × 17 × 9 cm') ?></td></tr>
                <tr><th>Net Chassis Weight</th><td><?= e($product['weight'] ?: '1.5 kg') ?></td></tr>
                <tr><th>Door Thickness Suitability</th><td><?= e($product['specifications']['Door Thickness Suitability'] ?? '35mm to 90mm (Universal Wooden / Metal Doors)') ?></td></tr>
                <?php foreach (($product['specifications'] ?? []) as $key => $val): ?>
                  <?php if (!in_array($key, ['Brand', 'Dimensions', 'Weight', 'Door Thickness Suitability'])): ?>
                    <tr><th><?= e($key) ?></th><td><?= e($val) ?></td></tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3. What's Included Tab -->
        <div class="tab-pane fade" id="includedTab" role="tabpanel">
          <h4 class="fw-bold mb-4">What's in the Box</h4>
          <div class="row g-3">
            <?php foreach (($product['whats_included'] ?? []) as $item): ?>
              <div class="col-md-6 col-lg-4">
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                  <i class="bi bi-box2-fill text-info fs-4"></i>
                  <span class="text-dark fw-semibold small"><?= e($item) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- 4. Reviews Tab -->
        <div class="tab-pane fade" id="reviewsTab" role="tabpanel">
          <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
              <h4 class="fw-bold mb-0">Verified Homeowner Reviews</h4>
              <small class="text-muted"><?= count($reviews) ?> verified owner reviews published</small>
            </div>
            <button class="btn btn-hiq-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#writeReviewForm">
              <i class="bi bi-pencil-square me-1"></i> Write a Review
            </button>
          </div>

          <!-- Write Review Form (Collapsible) -->
          <div class="collapse mb-4" id="writeReviewForm">
            <div class="card p-4 border bg-light rounded-3">
              <h5 class="fw-bold mb-3">Share Your Ownership Experience</h5>
              <form action="<?= url('product/review') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Your Name *</label>
                    <input type="text" name="reviewer_name" class="form-control" required value="<?= e(auth_customer()['name'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Overall Rating *</label>
                    <select name="rating" class="form-select">
                      <option value="5">5 Stars — Excellent Biometric Performance</option>
                      <option value="4">4 Stars — Very Good</option>
                      <option value="3">3 Stars — Average</option>
                      <option value="2">2 Stars — Below Expectations</option>
                      <option value="1">1 Star — Poor</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold">Review Headline</label>
                  <input type="text" name="title" class="form-control" placeholder="e.g. Ultra-fast recognition & rock solid lock body">
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold">Detailed Review *</label>
                  <textarea name="comment" rows="4" class="form-control" placeholder="Share your experience regarding doorstep installation, daily sensor responsiveness, app control, battery durability..." required></textarea>
                </div>

                <button type="submit" class="btn btn-hiq-primary">Submit Review for Moderation</button>
              </form>
            </div>
          </div>

          <!-- Reviews List -->
          <?php if (empty($reviews)): ?>
            <div class="text-center py-5">
              <i class="bi bi-chat-heart fs-1 text-muted mb-2"></i>
              <p class="text-muted">No published reviews yet for this model. Be the first verified customer to share your thoughts!</p>
            </div>
          <?php else: ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($reviews as $rev): ?>
                <div class="card p-4 border rounded-3 shadow-xs bg-white">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="rating-pill">
                      <i class="bi bi-star-fill text-warning"></i>
                      <span><?= (int)($rev['rating'] ?? 5) ?>.0 / 5.0</span>
                    </div>
                    <?php if (!empty($rev['verified_purchase'])): ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle small fw-medium">
                        <i class="bi bi-patch-check-fill me-1"></i>Verified Purchaser
                      </span>
                    <?php endif; ?>
                  </div>
                  <h6 class="fw-bold mb-1 text-dark">"<?= e($rev['title'] ?? '') ?>"</h6>
                  <p class="text-secondary small mb-3" style="line-height: 1.6;">"<?= e($rev['comment'] ?? '') ?>"</p>
                  <div class="small text-muted d-flex align-items-center gap-2">
                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.7rem;">
                      <?= strtoupper(substr($rev['reviewer_name'] ?? 'H', 0, 1)) ?>
                    </div>
                    <strong><?= e($rev['reviewer_name'] ?? 'Homeowner') ?></strong>
                    <span>•</span>
                    <span><?= e(explode(' ', $rev['created_at'] ?? '')[0]) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- 5. FAQs Tab -->
        <div class="tab-pane fade" id="faqTab" role="tabpanel">
          <h4 class="fw-bold mb-4">Frequently Asked Installation Questions</h4>
          <div class="accordion" id="lockFaqAccordion">
            <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
                  What happens if the internal rechargeable battery runs out completely?
                </button>
              </h2>
              <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-secondary" style="line-height: 1.7;">
                  You will never be locked out. HOMEIQ smart locks feature an external USB-C emergency power jumpstart port that powers the sensor instantly when plugged into any standard mobile power bank. Additionally, two computerized mechanical override keys are included in the package.
                </div>
              </div>
            </div>

            <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                  How does the complimentary doorstep installation appointment work?
                </button>
              </h2>
              <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-secondary" style="line-height: 1.7;">
                  Upon order dispatch, a certified HOMEIQ hardware technician contacts you via phone to schedule an installation slot at your convenience. They bring the necessary precision mortise templates, routing chisels, and align the lock perfectly before walking you through app pairing.
                </div>
              </div>
            </div>

            <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                  Can this lock be installed on existing wooden or metal doors?
                </button>
              </h2>
              <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#lockFaqAccordion">
                <div class="accordion-body text-secondary" style="line-height: 1.7;">
                  Yes. HOMEIQ mortise locks are engineered to fit wooden, solid core, flush, composite, and metal doors with thicknesses between 35mm and 90mm.
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
    <div class="mb-5">
      <div class="d-flex align-items-end justify-content-between mb-4">
        <div>
          <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Complementary Models</span>
          <h3 class="fw-bold mb-0">Related Smart Door Locks</h3>
        </div>
        <a href="<?= url('shop') ?>" class="small fw-bold text-dark text-decoration-none">
          View All <i class="bi bi-arrow-right text-primary"></i>
        </a>
      </div>
      <div class="row g-4">
        <?php foreach ($related as $rel): ?>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <?= View::component('product_card', ['product' => $rel]) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
