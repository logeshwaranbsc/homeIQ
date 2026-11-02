<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $banners
 * @var array $categories
 * @var array $featuredProducts
 * @var array $recentReviews
 * @var array $settings
 */
?>

<!-- Hero Banner Slider -->
<section class="hero-slider-section">
  <div id="homeHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
    <!-- Indicators -->
    <div class="carousel-indicators mb-4">
      <?php foreach ($banners as $idx => $banner): ?>
        <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="<?= $idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>" aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
      <?php endforeach; ?>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <?php foreach ($banners as $idx => $banner): ?>
        <div class="carousel-item carousel-item-hero <?= $idx === 0 ? 'active' : '' ?>">
          <div class="container py-5">
            <div class="row align-items-center g-5">
              <!-- Text Column -->
              <div class="col-lg-7 text-center text-lg-start">
                <?php if (!empty($banner['offer_badge'])): ?>
                  <div class="hero-slide-badge">
                    <i class="bi bi-shield-check"></i> <?= e($banner['offer_badge']) ?>
                  </div>
                <?php endif; ?>

                <h1 class="hero-headline">
                  <?= $banner['headline'] ?>
                </h1>

                <p class="hero-subhead">
                  <?= e($banner['subheading']) ?>
                </p>

                <?php if (!empty($banner['supporting_text'])): ?>
                  <div class="text-secondary small fw-medium mb-4 d-flex align-items-center gap-2 justify-content-center justify-content-lg-start flex-wrap">
                    <?= e($banner['supporting_text']) ?>
                  </div>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-3 justify-content-center justify-content-lg-start">
                  <a href="<?= url($banner['cta_url'] ?? 'shop') ?>" class="btn btn-hiq-accent py-3 px-4 fs-6 fw-bold">
                    <?= e($banner['cta_label'] ?? 'SHOP NOW') ?> <i class="bi bi-arrow-right ms-2"></i>
                  </a>
                  <a href="<?= url('about') ?>" class="btn btn-outline-light py-3 px-4 fs-6 fw-semibold">
                    Why HOMEIQ
                  </a>
                </div>
              </div>

              <!-- Visual Image Column -->
              <div class="col-lg-5 text-center">
                <div class="p-3">
                  <img src="<?= upload_url($banner['image'] ?? '') ?>" alt="<?= e($banner['headline']) ?>" class="img-fluid rounded-4 shadow-lg" style="max-height: 480px; width: 100%; object-fit: cover;">
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<!-- Trust Value Strip -->
<section class="py-4 bg-white border-bottom">
  <div class="container">
    <div class="row g-4 text-center text-md-start">
      <div class="col-md-3 col-6 d-flex align-items-center gap-3">
        <i class="bi bi-tools fs-2 text-primary"></i>
        <div>
          <h6 class="mb-0 fw-bold">Free Installation</h6>
          <small class="text-muted">On eligible smart locks</small>
        </div>
      </div>
      <div class="col-md-3 col-6 d-flex align-items-center gap-3">
        <i class="bi bi-truck fs-2 text-primary"></i>
        <div>
          <h6 class="mb-0 fw-bold">Express Shipping</h6>
          <small class="text-muted">Safe dispatch within 24h</small>
        </div>
      </div>
      <div class="col-md-3 col-6 d-flex align-items-center gap-3">
        <i class="bi bi-shield-lock fs-2 text-primary"></i>
        <div>
          <h6 class="mb-0 fw-bold">2-Year Warranty</h6>
          <small class="text-muted">Manufacturer backed protection</small>
        </div>
      </div>
      <div class="col-md-3 col-6 d-flex align-items-center gap-3">
        <i class="bi bi-headset fs-2 text-primary"></i>
        <div>
          <h6 class="mb-0 fw-bold">Dedicated Support</h6>
          <small class="text-muted">Direct technical assistance</small>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Shop by Category -->
<section class="py-5 bg-light">
  <div class="container py-3">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Product Scope</span>
        <h2 class="mb-0 fw-bold">Explore Smart Door Lock Categories</h2>
      </div>
      <a href="<?= url('categories') ?>" class="text-decoration-none fw-semibold">View All Categories <i class="bi bi-arrow-right"></i></a>
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
            <span class="btn btn-outline-dark btn-sm w-100">View Products</span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Brand Editorial Story Section -->
<section class="brand-intro-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="text-info fw-bold small text-uppercase letter-spacing-2">Security Philosophy</span>
        <h2 class="mt-2 mb-4 fw-extrabold">A NEW ERA OF SMART HOME SECURITY</h2>
        <p class="fs-5 text-light opacity-90 mb-4">
          HOMEIQ — where intelligent technology meets everyday living.
        </p>
        <p class="mb-4">
          We’re reimagining how you secure your home with smart locks designed for greater convenience, control, and peace of mind. Crafted from aviation-grade alloys and powered by rapid semiconductor sensors, our locks integrate effortlessly into modern architecture.
        </p>
        <p class="fs-5 text-info fw-semibold mb-4">
          Smarter Access. Safer Living.
        </p>
        <div class="d-flex gap-3">
          <a href="<?= url('shop') ?>" class="btn btn-info text-dark fw-bold px-4 py-2">
            Explore All Models
          </a>
          <a href="<?= url('about') ?>" class="btn btn-outline-light px-4 py-2">
            Read Our Story
          </a>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <div class="p-3 bg-dark bg-opacity-50 rounded-4 border border-secondary border-opacity-25 shadow-lg">
          <img src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>" alt="HOMEIQ Biometric Architecture" class="img-fluid rounded-3" style="max-height: 440px;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Slider / Grid -->
<section class="py-5 bg-white">
  <div class="container py-3">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Curated Selection</span>
        <h2 class="mb-0 fw-bold">Featured Digital Locks</h2>
      </div>
      <a href="<?= url('shop') ?>" class="text-decoration-none fw-semibold">Browse Full Catalog <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
      <?php foreach ($featuredProducts as $product): ?>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <?= View::component('product_card', ['product' => $product]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Promotional Section -->
<section class="py-5 bg-dark text-white" style="background: linear-gradient(135deg, #111317 0%, #1a1e28 100%);">
  <div class="container py-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="badge bg-primary text-white px-3 py-2 text-uppercase letter-spacing-1 mb-3">Limited Time Campaign</span>
        <h2 class="display-5 fw-bold text-white mb-3">Upgrade to Keyless Living with Coupon WELCOME10</h2>
        <p class="text-secondary fs-5 mb-4">
          Enjoy an extra 10% discount on orders above ₹10,000. Complete with complimentary doorstep installation by certified security technicians.
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <a href="<?= url('shop') ?>" class="btn btn-primary btn-lg px-4 fw-bold">
            Claim Offer & Shop Now
          </a>
          <span class="text-muted small">Valid across all AI face, palm vein & fingerprint series.</span>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <img src="<?= upload_url('products/video-door-lock.svg') ?>" alt="HOMEIQ Promotional Lock" class="img-fluid rounded-4 shadow" style="max-height: 320px;">
      </div>
    </div>
  </div>
</section>

<!-- Why Choose HOMEIQ -->
<section class="py-5 bg-light">
  <div class="container py-3">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Hardware Engineering</span>
      <h2 class="fw-bold">Why Modern Homes Trust HOMEIQ</h2>
      <p class="text-muted">Precision security engineering built specifically for residential and executive doors.</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-fingerprint"></i>
          </div>
          <h5 class="fw-bold">Multiple Access Methods</h5>
          <p class="text-muted small m-0">Unlock via AI 3D face scan, palm vein, fingerprint, PIN passcode, RFID keycard, smartphone app, or mechanical backup key.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-phone"></i>
          </div>
          <h5 class="fw-bold">Smartphone-Based Control</h5>
          <p class="text-muted small m-0">Generate temporary visitor passwords, review live entry logs, and receive security alerts wherever you are via the Tuya app.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-lock"></i>
          </div>
          <h5 class="fw-bold">Automatic Locking</h5>
          <p class="text-muted small m-0">Sensor-guided deadbolts automatically engage within seconds of door closure, preventing accidental unlock vulnerabilities.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-battery-charging"></i>
          </div>
          <h5 class="fw-bold">Emergency Power Support</h5>
          <p class="text-muted small m-0">External USB-C ports allow instant power bank jumpstart in case of prolonged battery exhaustion, plus physical key overrides.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-shield-check"></i>
          </div>
          <h5 class="fw-bold">Anti-Tamper Physical Security</h5>
          <p class="text-muted small m-0">Heavy-gauge stainless steel mortises with high-decibel intrusion alarms that trigger if forceful prying is detected.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper text-primary">
            <i class="bi bi-award"></i>
          </div>
          <h5 class="fw-bold">2-Year Official Warranty</h5>
          <p class="text-muted small m-0">Backed by comprehensive official manufacturer warranty and trained technician support nationwide.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Verified Customer Reviews Section -->
<?php if (!empty($recentReviews)): ?>
<section class="py-5 bg-white border-top">
  <div class="container py-3">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Verified Homeowners</span>
        <h2 class="mb-0 fw-bold">Customer Experiences</h2>
      </div>
      <div class="text-muted small">
        <i class="bi bi-check-circle-fill text-success me-1"></i>Verified Purchaser Reviews
      </div>
    </div>

    <div class="row g-4">
      <?php foreach ($recentReviews as $rev): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="text-warning small">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <i class="bi bi-star<?= $i <= ($rev['rating'] ?? 5) ? '-fill' : '' ?>"></i>
                <?php endfor; ?>
              </div>
              <?php if (!empty($rev['verified_purchase'])): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle small">
                  <i class="bi bi-patch-check-fill me-1"></i>Verified Purchase
                </span>
              <?php endif; ?>
            </div>
            <h6 class="fw-bold text-dark mb-2"><?= e($rev['title'] ?? 'Great lock') ?></h6>
            <p class="text-muted small mb-3 flex-grow-1">"<?= e($rev['comment'] ?? '') ?>"</p>
            <div class="border-top pt-3 text-secondary small d-flex justify-content-between">
              <strong><?= e($rev['reviewer_name'] ?? 'Homeowner') ?></strong>
              <span><?= e(explode(' ', $rev['created_at'] ?? '')[0]) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

