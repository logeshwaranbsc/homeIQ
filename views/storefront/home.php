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

<!-- Hero Banner Slider (Full-Bleed Background Image) -->
<section class="hero-slider-section">
  <div id="homeHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500">
    <!-- Indicators -->
    <div class="carousel-indicators mb-4">
      <?php foreach ($banners as $idx => $banner): ?>
        <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="<?= $idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>" aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
      <?php endforeach; ?>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <?php foreach ($banners as $idx => $banner): ?>
        <?php
          $textPos = $banner['text_position'] ?? 'left';
          $overlayStyle = $banner['overlay_style'] ?? 'gradient';
          $hasHeadline = !empty(trim((string)($banner['headline'] ?? '')));
          $hasSubhead = !empty(trim((string)($banner['subheading'] ?? '')));
          $hasBadge = !empty(trim((string)($banner['offer_badge'] ?? '')));
          $hasSupporting = !empty(trim((string)($banner['supporting_text'] ?? '')));
          $hasCta = !empty(trim((string)($banner['cta_label'] ?? '')));
        ?>
        <div class="carousel-item carousel-item-hero <?= $idx === 0 ? 'active' : '' ?>">
          <!-- Full Background Image covering whole hero section -->
          <div class="hero-bg-wrapper">
            <?php if (!empty($banner['image'])): ?>
              <img src="<?= upload_url($banner['image']) ?>" alt="<?= e(strip_tags((string)($banner['headline'] ?? 'HOMEIQ Smart Locks'))) ?>" class="hero-bg-image">
            <?php else: ?>
              <div class="hero-bg-placeholder"></div>
            <?php endif; ?>
            <div class="hero-overlay hero-overlay-<?= e($overlayStyle) ?>"></div>
          </div>

          <!-- Modifiable Hero Content on top of background -->
          <?php if ($textPos !== 'none' && ($hasHeadline || $hasSubhead || $hasCta)): ?>
            <div class="container hero-content-container">
              <div class="row w-100 <?= $textPos === 'center' ? 'justify-content-center text-center' : ($textPos === 'right' ? 'justify-content-end text-end' : '') ?>">
                <div class="col-lg-8 col-xl-7">
                  <div class="hero-content-box <?= $textPos === 'center' ? 'mx-auto' : '' ?>">
                    <?php if ($hasBadge): ?>
                      <div class="hero-slide-badge">
                        <i class="bi bi-shield-check"></i> <?= e($banner['offer_badge']) ?>
                      </div>
                    <?php endif; ?>

                    <?php if ($hasHeadline): ?>
                      <h1 class="hero-headline">
                        <?= $banner['headline'] ?>
                      </h1>
                    <?php endif; ?>

                    <?php if ($hasSubhead): ?>
                      <p class="hero-subhead">
                        <?= e($banner['subheading']) ?>
                      </p>
                    <?php endif; ?>

                    <?php if ($hasSupporting): ?>
                      <div class="mb-4 d-flex align-items-center gap-2 flex-wrap <?= $textPos === 'center' ? 'justify-content-center' : ($textPos === 'right' ? 'justify-content-end' : '') ?>">
                        <div class="hero-supporting-pill">
                          <i class="bi bi-check2-circle text-info"></i>
                          <span><?= e($banner['supporting_text']) ?></span>
                        </div>
                      </div>
                    <?php endif; ?>

                    <?php if ($hasCta): ?>
                      <div class="d-flex align-items-center gap-3 flex-wrap <?= $textPos === 'center' ? 'justify-content-center' : ($textPos === 'right' ? 'justify-content-end' : '') ?>">
                        <a href="<?= url($banner['cta_url'] ?? 'shop') ?>" class="btn btn-hiq-accent py-3 px-4 fs-6 fw-bold">
                          <?= e($banner['cta_label']) ?> <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="<?= url('about') ?>" class="btn btn-hiq-glass py-3 px-4 fs-6 fw-semibold">
                          Why HOMEIQ
                        </a>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php else: ?>
            <a href="<?= url($banner['cta_url'] ?? 'shop') ?>" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 4;" aria-label="Explore Smart Locks"></a>
          <?php endif; ?>
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

<!-- Trust Value Strip (Architectural Security Pillars) -->
<section class="trust-strip-section">
  <div class="container">
    <div class="row g-3">
      <div class="col-lg-3 col-sm-6">
        <div class="trust-pillar-card">
          <div class="trust-pillar-icon">
            <i class="bi bi-tools"></i>
          </div>
          <div>
            <div class="trust-pillar-title">White-Glove Installation</div>
            <div class="trust-pillar-desc">Door check & chisel routing by certified technicians</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6">
        <div class="trust-pillar-card">
          <div class="trust-pillar-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <div>
            <div class="trust-pillar-title">2-Year Full Warranty</div>
            <div class="trust-pillar-desc">Comprehensive mechanical & biometric electronics coverage</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6">
        <div class="trust-pillar-card">
          <div class="trust-pillar-icon">
            <i class="bi bi-truck"></i>
          </div>
          <div>
            <div class="trust-pillar-title">Insured 24h Dispatch</div>
            <div class="trust-pillar-desc">Shockproof sealed packaging & doorstep delivery</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6">
        <div class="trust-pillar-card">
          <div class="trust-pillar-icon">
            <i class="bi bi-headset"></i>
          </div>
          <div>
            <div class="trust-pillar-title">VIP Concierge Support</div>
            <div class="trust-pillar-desc">Direct phone & video smart lock onboarding</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Shop by Category -->
<section class="py-5" style="background-color: #f8fafc;">
  <div class="container py-4">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Product Scope</span>
        <h2 class="mb-0 fw-bold">Explore Smart Door Lock Collections</h2>
      </div>
      <a href="<?= url('categories') ?>" class="text-decoration-none fw-bold small text-dark d-flex align-items-center gap-1">
        View All Collections <i class="bi bi-arrow-right text-primary"></i>
      </a>
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
              Explore Models <i class="bi bi-arrow-right"></i>
            </span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Brand Editorial Story Section -->
<section class="brand-intro-section">
  <div class="container position-relative" style="z-index: 2;">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="hero-slide-badge mb-3">
          <i class="bi bi-cpu"></i> HARDWARE PHILOSOPHY
        </span>
        <h2 class="fw-extrabold text-white">
          A NEW STANDARD IN ARCHITECTURAL DEFENSE
        </h2>
        <p class="fs-5 text-light opacity-90 mb-4">
          HOMEIQ — where intelligent engineering meets residential peace of mind.
        </p>
        <p class="mb-4">
          We’re reimagining how discerning homeowners secure their properties. Crafted from aerospace-grade aluminum alloys and hardened 304 stainless steel mortises, each lock pairs impenetrable mechanical deadbolts with lightning-fast semiconductor biometrics.
        </p>
        
        <!-- Spec Metrics Bento Grid -->
        <div class="row g-3 mb-4">
          <div class="col-6 col-sm-3">
            <div class="spec-metric-card text-center">
              <div class="spec-metric-val">&lt;0.3s</div>
              <div class="spec-metric-label">Sensor Scan</div>
            </div>
          </div>
          <div class="col-6 col-sm-3">
            <div class="spec-metric-card text-center">
              <div class="spec-metric-val">9-in-1</div>
              <div class="spec-metric-label">Access Modes</div>
            </div>
          </div>
          <div class="col-6 col-sm-3">
            <div class="spec-metric-card text-center">
              <div class="spec-metric-val">304</div>
              <div class="spec-metric-label">Steel Mortise</div>
            </div>
          </div>
          <div class="col-6 col-sm-3">
            <div class="spec-metric-card text-center">
              <div class="spec-metric-val">AES</div>
              <div class="spec-metric-label">128-Bit Safe</div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 flex-wrap">
          <a href="<?= url('shop') ?>" class="btn btn-hiq-accent px-4 py-2 fw-bold">
            Explore All Hardware
          </a>
          <a href="<?= url('about') ?>" class="btn btn-hiq-glass px-4 py-2">
            Read Security Briefing
          </a>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <div class="hero-img-pedestal">
          <img src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>" alt="HOMEIQ Biometric Architecture" class="img-fluid rounded-4 shadow-lg" style="max-height: 460px;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Showcase -->
<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Curated Selection</span>
        <h2 class="mb-0 fw-bold">Featured Digital Smart Locks</h2>
      </div>
      <a href="<?= url('shop') ?>" class="text-decoration-none fw-bold small text-dark d-flex align-items-center gap-1">
        Browse Full Catalog (<?= count($featuredProducts) ?> Models) <i class="bi bi-arrow-right text-primary"></i>
      </a>
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

<!-- Exclusive Promotional Campaign Banner -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #090c12 0%, #161b26 100%); border-top: 1px solid rgba(255, 255, 255, 0.08); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
  <div class="container py-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="badge bg-info text-dark px-3 py-2 text-uppercase letter-spacing-1 fw-bold mb-3">VIP Privilege Campaign</span>
        <h2 class="display-5 fw-extrabold text-white mb-3">Upgrade to Keyless Living with Coupon WELCOME10</h2>
        <p class="text-secondary fs-5 mb-4" style="line-height: 1.7;">
          Enjoy an extra 10% privilege discount on smart locks above ₹10,000. Complete with complimentary doorstep installation by certified security technicians.
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <a href="<?= url('shop') ?>" class="btn btn-hiq-accent btn-lg px-4 fw-bold">
            Claim Offer & Shop Now <i class="bi bi-arrow-right ms-2"></i>
          </a>
          <span class="text-secondary small d-flex align-items-center gap-1">
            <i class="bi bi-check-circle-fill text-success"></i> Valid across all AI face, palm vein & fingerprint series.
          </span>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <div class="hero-img-pedestal">
          <img src="<?= upload_url('products/video-door-lock.svg') ?>" alt="HOMEIQ Promotional Lock" class="img-fluid rounded-4 shadow" style="max-height: 330px;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Hardware Engineering Highlights (Why Modern Homes Trust HOMEIQ) -->
<section class="py-5" style="background-color: #f8fafc;">
  <div class="container py-4">
    <div class="text-center mx-auto mb-5" style="max-width: 680px;">
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Engineering Excellence</span>
      <h2 class="fw-bold mt-1">Why Modern Homes Trust HOMEIQ</h2>
      <p class="text-muted">Precision security engineering built specifically for executive residences, luxury apartments, and modern villas.</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-fingerprint"></i>
          </div>
          <h5 class="fw-bold">Multi-Modal Access</h5>
          <p class="text-muted small m-0">Unlock via 3D AI face scan, contactless palm vein, rapid 0.3s fingerprint, anti-peep PIN passcode, encrypted RFID, mobile app, or backup mechanical key.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-phone"></i>
          </div>
          <h5 class="fw-bold">Tuya IoT Smartphone App</h5>
          <p class="text-muted small m-0">Generate temporary one-time visitor passwords, review real-time entry logs, receive door tamper alarms, and grant remote access from anywhere.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-shield-check"></i>
          </div>
          <h5 class="fw-bold">Automated Deadbolt Lock</h5>
          <p class="text-muted small m-0">Sensor-guided stainless steel deadbolts automatically engage within seconds of door closure, eliminating accidental unlock vulnerabilities.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-battery-charging"></i>
          </div>
          <h5 class="fw-bold">Emergency USB-C Override</h5>
          <p class="text-muted small m-0">High-capacity rechargeable lithium packs last up to 8 months. External USB-C ports allow instant power bank jumpstart, accompanied by physical key overrides.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-shield-slash"></i>
          </div>
          <h5 class="fw-bold">Anti-Tamper & Anti-Pry Alarm</h5>
          <p class="text-muted small m-0">Heavy-gauge stainless steel mortises trigger high-decibel audible sirens and push instant mobile alerts if forceful prying or incorrect attempts are detected.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-award"></i>
          </div>
          <h5 class="fw-bold">2-Year Official Warranty</h5>
          <p class="text-muted small m-0">Backed by comprehensive official manufacturer warranty and trained technician support nationwide with door pre-check assurance.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Verified Customer Reviews Section -->
<?php if (!empty($recentReviews)): ?>
<section class="py-5 bg-white border-top">
  <div class="container py-4">
    <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Verified Homeowners</span>
        <h2 class="mb-0 fw-bold">Client Experiences & Endorsements</h2>
      </div>
      <div class="text-muted small d-flex align-items-center gap-1">
        <i class="bi bi-patch-check-fill text-success fs-5"></i>
        <span>100% Verified Purchaser Reviews</span>
      </div>
    </div>

    <div class="row g-4">
      <?php foreach ($recentReviews as $rev): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border p-4 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rating-pill">
                <i class="bi bi-star-fill text-warning"></i>
                <span><?= (int)($rev['rating'] ?? 5) ?>.0 / 5.0</span>
              </div>
              <?php if (!empty($rev['verified_purchase'])): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-medium">
                  <i class="bi bi-patch-check-fill me-1"></i>Verified Homeowner
                </span>
              <?php endif; ?>
            </div>
            <h6 class="fw-bold text-dark mb-2 fs-6">"<?= e($rev['title'] ?? 'Exceptional Security') ?>"</h6>
            <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">"<?= e($rev['comment'] ?? '') ?>"</p>
            <div class="border-top pt-3 text-secondary small d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
                  <?= strtoupper(substr($rev['reviewer_name'] ?? 'H', 0, 1)) ?>
                </div>
                <strong><?= e($rev['reviewer_name'] ?? 'Homeowner') ?></strong>
              </div>
              <span class="text-muted"><?= e(explode(' ', $rev['created_at'] ?? '')[0]) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
