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
                      <div class="mb-3 d-flex align-items-center gap-2 flex-wrap <?= $textPos === 'center' ? 'justify-content-center' : ($textPos === 'right' ? 'justify-content-end' : '') ?>">
                        <div class="hero-supporting-pill">
                          <i class="bi bi-check2-circle brand-icon"></i>
                          <span><?= e($banner['supporting_text']) ?></span>
                        </div>
                      </div>
                    <?php endif; ?>

                    <?php if ($hasCta): ?>
                      <div class="d-flex align-items-center gap-2 gap-sm-3 flex-wrap <?= $textPos === 'center' ? 'justify-content-center' : ($textPos === 'right' ? 'justify-content-end' : '') ?>">
                        <a href="<?= url($banner['cta_url'] ?? 'shop') ?>" class="btn btn-hiq-accent py-2 px-4 fs-6 fw-bold">
                          <?= e($banner['cta_label']) ?> <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="<?= url('about') ?>" class="btn btn-hiq-glass py-2 px-4 fs-6 fw-semibold">
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

<?php if (!empty($categories)): ?>
<!-- Smart Lock Collection Showcase Section (Categories) -->
<section class="hiq-collection-showcase-section">
  <div class="container">
    <div class="hiq-collection-header text-center">
      <div class="hiq-collection-eyebrow">FEATURED LOCKS</div>
      <h2 class="hiq-collection-title">Our Smart Lock Collection</h2>
      <p class="hiq-collection-subtitle">A handpicked range of smart locks for your home and office.</p>
    </div>

    <div class="hiq-collection-slider-container position-relative" id="collectionSliderContainer">
      <button type="button"
              class="product-slider-arrow arrow-prev hiq-collection-arrow"
              id="collectionSidePrev"
              aria-label="Previous Categories"
              title="Previous Categories"
              hidden>
        <i class="bi bi-chevron-left"></i>
      </button>

      <div class="hiq-collection-track" id="collectionCategoryTrack">
        <?php foreach ($categories as $cat): ?>
          <div class="hiq-collection-slide">
            <a href="<?= url('category/' . $cat['slug']) ?>" class="hiq-collection-tile" title="<?= e($cat['name']) ?>">
              <div class="hiq-collection-tile-media">
                <img src="<?= upload_url($cat['image'] ?? '') ?>"
                     alt="<?= e($cat['name']) ?>"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
              </div>
              <div class="hiq-collection-tile-body">
                <h3 class="hiq-collection-tile-title"><?= e($cat['name']) ?></h3>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>

      <button type="button"
              class="product-slider-arrow arrow-next hiq-collection-arrow"
              id="collectionSideNext"
              aria-label="Next Categories"
              title="Next Categories"
              hidden>
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <div class="text-center hiq-collection-cta-wrap">
      <a href="<?= url('shop') ?>" class="hiq-collection-cta">
        Explore All Products <i class="bi bi-arrow-right ms-2"></i>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Featured Products Showcase (Creative Modern Slider Carousel with Arrow Controls) -->
<section class="pt-4 pt-md-5 pb-2 pb-md-3 bg-white position-relative featured-products-section">
  <div class="container pt-2 pt-md-4 pb-1 pb-md-2">
    <!-- Section Header -->
    <div class="row align-items-end justify-content-between mb-3 mb-md-4 g-3">
      <div class="col-lg-7">
        <span class="cat-sec-badge">
          <span class="cat-pulse-dot"></span> Curated Hardware Lineup
        </span>
        <h2 class="cat-sec-title">
          Featured Digital <span class="hiq-accent-gradient">Smart Locks</span>
        </h2>
        <p class="cat-sec-subtitle">
          Engineered for biometric velocity, architectural elegance, and impenetrable multi-point deadbolt protection.
        </p>
      </div>
    </div>

    <!-- Product Slider Carousel Viewport with Gutter Side Arrows (Never Overlapping Cards) -->
    <?php $hasMultipleFeatured = count($featuredProducts) > 1; ?>
    <div class="product-slider-container position-relative<?= $hasMultipleFeatured ? '' : ' product-slider-container--single' ?>">
      <?php if ($hasMultipleFeatured): ?>
        <!-- Side Floating Left Arrow (Positioned in gutter channel) -->
        <button type="button" class="product-slider-arrow arrow-prev" id="featuredSidePrev" aria-label="Scroll Left" title="Scroll Left">
          <i class="bi bi-chevron-left"></i>
        </button>
      <?php endif; ?>

      <!-- Scrollable Products Track -->
      <div class="product-slider-track" id="featuredProductsTrack">
        <?php foreach ($featuredProducts as $product): ?>
          <div class="product-slider-slide">
            <?= View::component('product_card', ['product' => $product]) ?>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($hasMultipleFeatured): ?>
        <!-- Side Floating Right Arrow (Positioned in gutter channel) -->
        <button type="button" class="product-slider-arrow arrow-next" id="featuredSideNext" aria-label="Scroll Right" title="Scroll Right">
          <i class="bi bi-chevron-right"></i>
        </button>
      <?php endif; ?>
    </div>

    <!-- Custom Architecture Callout Banner -->
    <div class="featured-custom-banner mt-4 mt-md-5 mb-2 mb-md-3">
      <div class="row align-items-center g-3">
        <div class="col-lg-8">
          <div class="d-flex align-items-start align-items-sm-center gap-3">
            <div class="banner-icon-badge">
              <i class="bi bi-shield-check"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">Need Custom Mortise Sizing or Villa Multi-Door Provisioning?</h6>
              <p class="text-muted small mb-0">Our certified security technicians offer architectural door measurements and bespoke master-keying support.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="<?= url('contact') ?>" class="btn btn-hiq-primary py-2 px-4 fs-7 fw-bold">
            Request Architectural Consultation <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($videoTestimonials)): ?>
<section class="hiq-reels-section pt-3 pt-md-4 pb-4 pb-md-5 bg-white position-relative">
  <div class="container pt-1 pt-md-2 pb-2 pb-md-4">
    <!-- Section Header (Matching Featured Products Header Alignment & Style) -->
    <div class="row align-items-end justify-content-between mb-3 mb-md-4 g-3">
      <div class="col-lg-7">
        <span class="cat-sec-badge">
          <span class="cat-pulse-dot"></span> Real Customer Experiences
        </span>
        <h2 class="cat-sec-title">
          Customer Video <span class="hiq-accent-gradient">Reels &amp; Reviews</span>
        </h2>
        <p class="cat-sec-subtitle">
          Watch real homeowners share their daily experience with HOMEIQ digital smart locks.
        </p>
      </div>

      <?php if (count($videoTestimonials) > 1): ?>
        <div class="col-lg-5 text-lg-end d-flex align-items-center justify-content-end gap-3 flex-wrap">
          <!-- Header Arrow Controls -->
          <div class="slider-header-controls d-flex align-items-center gap-2">
            <button type="button" class="btn-slider-arrow btn-slider-prev" id="hiqReelHeaderPrev" aria-label="Previous Reels" title="Previous Reels">
              <i class="bi bi-chevron-left"></i>
            </button>
            <button type="button" class="btn-slider-arrow btn-slider-next" id="hiqReelHeaderNext" aria-label="Next Reels" title="Next Reels">
              <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Reels Slider Carousel with Gutter Side Arrows (Matching Product Slider Alignment & Margins) -->
    <div class="hiq-reels-container position-relative">
      <?php if (count($videoTestimonials) > 1): ?>
        <!-- Side Floating Left Arrow (Positioned in gutter channel) -->
        <button type="button" class="product-slider-arrow arrow-prev" id="hiqReelSidePrev" aria-label="Scroll Left" title="Scroll Left">
          <i class="bi bi-chevron-left"></i>
        </button>
      <?php endif; ?>

      <!-- Reels Track -->
      <div class="hiq-reels-track" id="hiqReelsTrack">
        <?php foreach ($videoTestimonials as $vid): 
          $vidSource = !empty($vid['video_path']) ? upload_url($vid['video_path']) : ($vid['video_url'] ?? '');
          $thumbSource = !empty($vid['thumbnail_path']) ? upload_url($vid['thumbnail_path']) : '';
          $name = $vid['customer_name'] ?? 'Homeowner';
          $desc = $vid['description'] ?? ($vid['review_text'] ?? ($vid['title'] ?? ''));
        ?>
          <div class="hiq-reel-slide">
            <div class="hiq-reel-card" role="button" tabindex="0" title="Click to play inline">
              <!-- Direct Inline Video Player -->
              <video class="hiq-reel-video" 
                     playsinline 
                     loop 
                     preload="metadata"
                     <?= !empty($thumbSource) ? 'poster="' . e($thumbSource) . '"' : '' ?>>
                <source src="<?= e($vidSource) ?>" type="video/mp4">
              </video>

              <!-- Gradient Scrim Overlay -->
              <div class="hiq-reel-scrim"></div>

              <!-- Center Play / Pause Indicator Button -->
              <button class="hiq-reel-play-btn" type="button" aria-label="Play or pause video">
                <i class="bi bi-play-fill"></i>
              </button>

              <!-- Bottom Caption (Name & Single Line Description) -->
              <div class="hiq-reel-caption">
                <div class="hiq-reel-name"><?= e($name) ?></div>
                <?php if (!empty($desc)): ?>
                  <div class="hiq-reel-desc" title="<?= e($desc) ?>"><?= e($desc) ?></div>
                <?php endif; ?>
                <div class="hiq-reel-audio-bar">
                  <div class="hiq-reel-audio-label">
                    <i class="bi bi-music-note-beamed"></i>
                    <span>Original Audio</span>
                  </div>
                  <button class="hiq-reel-mute-btn" type="button" aria-label="Toggle sound" title="Sound on/off">
                    <i class="bi bi-volume-mute-fill"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if (count($videoTestimonials) > 1): ?>
        <!-- Side Floating Right Arrow (Positioned in gutter channel) -->
        <button type="button" class="product-slider-arrow arrow-next" id="hiqReelSideNext" aria-label="Scroll Right" title="Scroll Right">
          <i class="bi bi-chevron-right"></i>
        </button>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Brand Hardware Showcase Video Section -->
<?php if (!empty($settings['story_enabled'] ?? true)): ?>
  <section class="brand-intro-section brand-video-section position-relative">
    <div class="brand-video-wrapper">
      <video id="brandShowcaseVideo"
             class="brand-showcase-video"
             autoplay
             muted
             loop
             playsinline
             preload="auto">
        <source src="https://res.cloudinary.com/uep3k36v/video/upload/v1794397919/Untitled_video_gwr_video_mvp.mp4" type="video/mp4">
      </video>
    </div>
  </section>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('brandShowcaseVideo');
    const muteBtn = document.getElementById('brandVideoMuteBtn');
    if (!video || !muteBtn) return;

    const icon = muteBtn.querySelector('i');
    muteBtn.addEventListener('click', function () {
      video.muted = !video.muted;
      if (icon) {
        icon.className = video.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
      }
    });
  });
  </script>
<?php endif; ?>

<!-- Trust Value Strip (Architectural Security Pillars) -->
<section class="trust-strip-section mt-4 mt-lg-5 py-4">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  var track = document.getElementById('hiqReelsTrack');
  var prevBtns = [
    document.getElementById('hiqReelSidePrev'),
    document.getElementById('hiqReelHeaderPrev')
  ].filter(Boolean);
  var nextBtns = [
    document.getElementById('hiqReelSideNext'),
    document.getElementById('hiqReelHeaderNext')
  ].filter(Boolean);

  if (track && (prevBtns.length > 0 || nextBtns.length > 0)) {
    var getScrollStep = function () {
      var slide = track.querySelector('.hiq-reel-slide');
      if (slide) {
        var gap = parseFloat(window.getComputedStyle(track).gap) || 20;
        return Math.round(slide.getBoundingClientRect().width + gap);
      }
      return 300;
    };

    nextBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var step = getScrollStep();
        var maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
        if (maxScroll <= 0) return;
        if (track.scrollLeft >= maxScroll - 10) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          track.scrollTo({ left: Math.min(track.scrollLeft + step, maxScroll), behavior: 'smooth' });
        }
      });
    });

    prevBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var step = getScrollStep();
        var maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
        if (maxScroll <= 0) return;
        if (track.scrollLeft <= 10) {
          track.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
          track.scrollTo({ left: Math.max(track.scrollLeft - step, 0), behavior: 'smooth' });
        }
      });
    });
  }

  var cards = document.querySelectorAll('.hiq-reel-card');
  cards.forEach(function (card) {
    var video = card.querySelector('video');
    var muteBtn = card.querySelector('.hiq-reel-mute-btn');
    var muteIcon = muteBtn ? muteBtn.querySelector('i') : null;

    if (!video) return;

    // Start video muted so browser allows smooth playback
    video.muted = true;

    var togglePlay = function (e) {
      if (e && e.target && muteBtn && (muteBtn === e.target || muteBtn.contains(e.target))) {
        return;
      }

      if (video.paused) {
        cards.forEach(function (otherCard) {
          if (otherCard !== card) {
            var otherVideo = otherCard.querySelector('video');
            if (otherVideo && !otherVideo.paused) {
              otherVideo.pause();
              otherCard.classList.remove('is-playing');
            }
          }
        });

        var p = video.play();
        if (p !== undefined) {
          p.then(function () {
            card.classList.add('is-playing');
          }).catch(function () {
            video.muted = true;
            if (muteIcon) muteIcon.className = 'bi bi-volume-mute-fill';
            video.play().then(function () {
              card.classList.add('is-playing');
            }).catch(function () {});
          });
        }
      } else {
        video.pause();
        card.classList.remove('is-playing');
      }
    };

    card.addEventListener('click', togglePlay);
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        togglePlay(e);
      }
    });

    if (muteBtn) {
      muteBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        video.muted = !video.muted;
        if (muteIcon) {
          muteIcon.className = video.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
        }
      });
    }

    video.addEventListener('ended', function () {
      card.classList.remove('is-playing');
    });
  });
});
</script>
<?php elseif (auth_admin()): ?>
  <!-- Admin Guidance Banner -->
  <section class="py-4 bg-light border-top">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-white flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(254, 107, 37, 0.12); color: #FE6B25; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-camera-video"></i>
          </div>
          <div>
            <div class="fw-bold text-dark">Video Reels Section Ready</div>
            <small class="text-muted">Upload up to 4 customer reels (video, name, and single line description) in the admin panel.</small>
          </div>
        </div>
        <a href="<?= url('admin/video-testimonials') ?>" class="btn btn-sm btn-dark">
          <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Videos in Admin (<?= count($videoTestimonials ?? []) ?> / 4)
        </a>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Exclusive Promotional Campaign Banner (Fully Customizable from Admin Panel) -->
<?php if (!empty($settings['promo_enabled'] ?? true)): ?>
  <?php
    $promoTheme = $settings['promo_theme'] ?? 'dark';
    $promoBadge = $settings['promo_badge'] ?? 'VIP PRIVILEGE CAMPAIGN';
    $promoBadgeIcon = $settings['promo_badge_icon'] ?? 'bi-tag-fill';
    $promoTitle = $settings['promo_title'] ?? 'Upgrade to Keyless Living with Coupon WELCOME10';
    $promoCoupon = $settings['promo_coupon_code'] ?? 'WELCOME10';
    $promoDesc = $settings['promo_description'] ?? 'Enjoy an extra 10% privilege discount on smart locks above ₹10,000. Complete with complimentary doorstep installation by certified security technicians.';
    $promoBtnText = $settings['promo_btn_text'] ?? 'Claim Offer & Shop Now';
    $promoBtnUrl = $settings['promo_btn_url'] ?? 'shop';
    $promoNote = $settings['promo_note'] ?? 'Valid across all AI face, palm vein & fingerprint series.';
    $promoImage = $settings['promo_image'] ?? 'products/video-door-lock.svg';
    $promoPill = $settings['promo_pill_text'] ?? '10% Instant Privilege Discount';
  ?>
  <section class="promo-campaign-section position-relative <?= ($promoTheme === 'light') ? 'theme-light' : '' ?>">
    <div class="container position-relative" style="z-index: 2;">
      <div class="row align-items-center g-4 g-lg-5">
        <div class="col-lg-7">
          <div class="mb-3">
            <span class="promo-badge-pill">
              <i class="bi <?= e($promoBadgeIcon) ?>"></i> <?= e($promoBadge) ?>
            </span>
          </div>

          <h2 class="mb-3">
            <?php if (!empty($promoCoupon) && str_contains($promoTitle, $promoCoupon)): ?>
              <?= e(str_replace($promoCoupon, '', $promoTitle)) ?>
              <span class="promo-code-highlight" onclick="navigator.clipboard.writeText('<?= e($promoCoupon) ?>'); alert('Coupon code <?= e($promoCoupon) ?> copied to clipboard!');" title="Click to copy coupon code">
                <?= e($promoCoupon) ?> <i class="bi bi-copy fs-6 ms-1"></i>
              </span>
            <?php else: ?>
              <?= e($promoTitle) ?>
            <?php endif; ?>
          </h2>

          <?php if (!empty($promoDesc)): ?>
            <p class="promo-desc">
              <?= nl2br(e($promoDesc)) ?>
            </p>
          <?php endif; ?>

          <div class="d-flex align-items-center gap-3 flex-wrap">
            <?php if (!empty($promoBtnText)): ?>
              <a href="<?= url($promoBtnUrl) ?>" class="btn btn-hiq-primary btn-lg px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <span><?= e($promoBtnText) ?></span>
                <i class="bi bi-arrow-right"></i>
              </a>
            <?php endif; ?>

            <?php if (!empty($promoNote)): ?>
              <span class="promo-note-pill">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <span><?= e($promoNote) ?></span>
              </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="promo-showcase-card">
            <div class="story-card-top-bar">
              <span class="story-chip-badge" title="<?= e($promoPill) ?>">
                <i class="bi bi-gift-fill text-warning"></i>
                <?= e($promoPill) ?>
              </span>
              <span class="story-chip-status">
                <span class="story-pulse-dot"></span> Live Privilege
              </span>
            </div>

            <div class="story-card-body">
              <div class="story-halo-glow"></div>
              <img src="<?= upload_url($promoImage) ?>" alt="<?= e($promoTitle) ?>" class="promo-lock-img" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
            </div>

            <div class="story-card-footer">
              <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                <span class="small text-light opacity-90 d-flex align-items-center gap-1">
                  <i class="bi bi-tools text-primary"></i> Free Certified Installation
                </span>
                <span class="badge bg-dark border border-secondary text-light small px-2 py-1">
                  Official Warranty
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Hardware Engineering Highlights (Why Modern Homes Trust HOMEIQ) -->
<section class="py-4 py-md-5" style="background-color: #f8fafc;">
  <div class="container py-2 py-md-4">
    <div class="text-center mx-auto mb-4 mb-md-5" style="max-width: 680px;">
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
<section class="py-4 py-md-5 bg-white border-top">
  <div class="container py-2 py-md-4">
    <div class="d-flex align-items-start align-items-md-end justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Verified Homeowners</span>
        <h2 class="mb-0 fw-bold">Client Experiences & Endorsements</h2>
      </div>
      <div class="text-muted small d-flex align-items-center gap-1">
        <i class="bi bi-patch-check-fill text-success fs-5"></i>
        <span>100% Verified Purchaser Reviews</span>
      </div>
    </div>

    <div class="row g-3 g-md-4">
      <?php foreach ($recentReviews as $rev): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border p-3 p-sm-4 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
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
                <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(254, 107, 37, 0.12); color: #FE6B25; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
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


