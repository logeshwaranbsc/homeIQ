<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $banners
 * @var array $categories
 * @var array $featuredProducts
 * @var array $recentReviews
 * @var array $videoTestimonials
 * @var array $settings
 */
?>

<!-- ==========================================================================
     SECTION 1: HERO BANNER SLIDER
     ========================================================================== -->
<section class="hero-slider-section">
  <div id="homeHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500">
    <!-- Carousel Dots Indicators -->
    <?php if (count($banners) > 1): ?>
      <div class="carousel-indicators hiq-dots-indicators">
        <?php foreach ($banners as $idx => $banner): ?>
          <button type="button" 
                  data-bs-target="#homeHeroCarousel" 
                  data-bs-slide-to="<?= $idx ?>" 
                  class="<?= $idx === 0 ? 'active' : '' ?>" 
                  aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" 
                  aria-label="Slide <?= $idx + 1 ?>"></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Slides (Content is inside the image graphic) -->
    <div class="carousel-inner">
      <?php foreach ($banners as $idx => $banner): ?>
        <?php
          $ctaUrl = $banner['cta_url'] ?? 'shop';
          $bannerTitle = !empty($banner['title']) ? $banner['title'] : ($banner['headline'] ?? 'HOMEIQ Smart Locks');
        ?>
        <div class="carousel-item carousel-item-hero <?= $idx === 0 ? 'active' : '' ?>">
          <div class="hero-bg-wrapper">
            <?php if (!empty($banner['image']) || !empty($banner['mobile_image'])): ?>
              <picture class="hero-bg-picture">
                <?php if (!empty($banner['mobile_image'])): ?>
                  <source media="(max-width: 767.98px)" srcset="<?= upload_url($banner['mobile_image']) ?>">
                <?php endif; ?>
                <img src="<?= upload_url(!empty($banner['image']) ? $banner['image'] : $banner['mobile_image']) ?>"
                     alt="<?= e(strip_tags((string)$bannerTitle)) ?>"
                     class="hero-bg-image"
                     loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>">
              </picture>
            <?php else: ?>
              <div class="hero-bg-placeholder"></div>
            <?php endif; ?>
          </div>

          <?php if (!empty($ctaUrl)): ?>
            <a href="<?= url($ctaUrl) ?>" class="hero-banner-hitbox" aria-label="<?= e(strip_tags((string)$bannerTitle)) ?>"></a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <?php if (count($banners) > 1): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    <?php endif; ?>
  </div>
</section>

<!-- ==========================================================================
     SECTION 2: FEATURE HIGHLIGHTS STRIP
     ========================================================================== -->
<section class="hiq-features-strip-section d-none d-md-block">
  <div class="container">
    <div class="hiq-features-strip">
      <div class="hiq-feature-item">
        <div class="hiq-feat-icon"><i class="bi bi-fingerprint"></i></div>
        <span class="hiq-feat-text">Fingerprint</span>
      </div>
      <div class="hiq-feat-divider"></div>
      <div class="hiq-feature-item">
        <div class="hiq-feat-icon"><i class="bi bi-phone"></i></div>
        <span class="hiq-feat-text">App Control</span>
      </div>
      <div class="hiq-feat-divider"></div>
      <div class="hiq-feature-item">
        <div class="hiq-feat-icon"><i class="bi bi-shield-check"></i></div>
        <span class="hiq-feat-text">High Security</span>
      </div>
      <div class="hiq-feat-divider"></div>
      <div class="hiq-feature-item">
        <div class="hiq-feat-icon"><i class="bi bi-tools"></i></div>
        <span class="hiq-feat-text">Easy Installation</span>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 3: SHOP BY CATEGORY
     ========================================================================== -->
<?php if (!empty($categories)): ?>
<section class="hiq-shop-category-section py-4 py-md-5">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
      <h2 class="hiq-section-heading m-0">Shop by Category</h2>
      <a href="<?= url('shop') ?>" class="hiq-view-all-link">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <!-- Category Grid (4 Pastel Cards) -->
    <div class="hiq-category-grid">
      <?php 
        $pastelClasses = ['pastel-peach', 'pastel-lavender', 'pastel-mint', 'pastel-sky'];
        $cornerIcons = ['bi-fingerprint', 'bi-grid-3x3-gap-fill', 'bi-fingerprint', 'bi-wifi'];
        foreach ($categories as $cIdx => $cat): 
          $pastelClass = $pastelClasses[$cIdx % 4];
          $cornerIcon = $cornerIcons[$cIdx % 4];
      ?>
        <div class="hiq-category-col">
          <a href="<?= url('category/' . $cat['slug']) ?>" class="hiq-category-card <?= $pastelClass ?>" title="<?= e($cat['name']) ?>">
            <span class="hiq-cat-corner-icon"><i class="bi <?= $cornerIcon ?>"></i></span>
            <div class="hiq-category-img-box">
              <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="<?= e($cat['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
            </div>
            <div class="hiq-category-pill">
              <span class="hiq-cat-pill-name"><?= e($cat['name']) ?></span>
              <span class="hiq-cat-pill-arrow"><i class="bi bi-arrow-right"></i></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 4: FEATURED PRODUCTS
     ========================================================================== -->
<?php if (!empty($featuredProducts)): ?>
<section class="hiq-featured-section py-4 py-md-5">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 flex-wrap gap-2">
      <h2 class="hiq-section-heading m-0">Featured Products</h2>
      <a href="<?= url('shop') ?>" class="hiq-view-all-link d-md-none">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
      <div class="hiq-filter-pills d-none d-md-flex align-items-center gap-2">
        <a href="<?= url('shop') ?>" class="hiq-filter-pill active">All</a>
        <?php foreach (array_slice($categories ?? [], 0, 3) as $fCat): ?>
          <a href="<?= url('category/' . $fCat['slug']) ?>" class="hiq-filter-pill"><?= e($fCat['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Products Grid (5 Column Layout) -->
    <div class="hiq-products-grid">
      <?php foreach ($featuredProducts as $product): 
        $mrp = (float)($product['price'] ?? 0);
        $salePrice = (float)($product['sale_price'] ?? $mrp);
        $cleanTitle = preg_replace('/^HOMEIQ\s+/i', '', (string)$product['name']);
        $rating = number_format((float)($product['rating'] ?? 4.8), 1);
        $reviewsCount = !empty($product['reviews_count']) ? (int)$product['reviews_count'] : 48;
      ?>
        <div class="hiq-product-col">
          <div class="hiq-product-card">
            <a href="<?= url('product/' . $product['slug']) ?>" class="hiq-prod-img-wrap" title="<?= e($product['name']) ?>">
              <img src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
            </a>
            <div class="hiq-prod-info">
              <a href="<?= url('product/' . $product['slug']) ?>" class="hiq-prod-title" title="<?= e($product['name']) ?>">
                <?= e($cleanTitle) ?>
              </a>
              <div class="hiq-prod-rating">
                <i class="bi bi-star-fill text-warning"></i>
                <span class="hiq-rating-val"><?= $rating ?></span>
                <span class="hiq-review-count">(<?= $reviewsCount ?>)</span>
              </div>
              <div class="hiq-prod-bottom">
                <div class="hiq-prod-price"><?= str_replace(' ', '&nbsp;', format_price($salePrice)) ?></div>
                <form action="<?= url('cart/add') ?>" method="POST" class="hiq-prod-cart-form">
                  <?= csrf_field() ?>
                  <input type="hidden" name="product_id" value="<?= e($product['id'] ?? '') ?>">
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" class="hiq-prod-cart-btn btn-action-cart" data-product-id="<?= e($product['id'] ?? '') ?>" title="Add to Cart" aria-label="Add to Cart">
                    <i class="bi bi-cart3"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 5: SEE HOMEIQ IN ACTION (SPOTLIGHT VIDEO CARD)
     ========================================================================== -->
<?php
  $showcaseVideoUrl = !empty($settings['brand_video_url']) 
    ? $settings['brand_video_url'] 
    : (!empty($settings['showcase_video_url']) 
        ? $settings['showcase_video_url'] 
        : 'https://res.cloudinary.com/uep3k36v/video/upload/v1794397919/Untitled_video_gwr_video_mvp.mp4');
  $spotlightPoster = !empty($settings['story_image']) 
    ? upload_url($settings['story_image']) 
    : (!empty($settings['about_hero_image']) ? upload_url($settings['about_hero_image']) : asset('assets/images/placeholder-lock.svg'));
?>
<section class="hiq-spotlight-section py-4 py-md-5">
  <div class="container">
    <div class="hiq-spotlight-card position-relative overflow-hidden">
      <!-- Full Div Background Video -->
      <video id="spotlightShowcaseVideo"
             class="hiq-spotlight-full-video"
             autoplay
             muted
             loop
             playsinline
             preload="auto"
             poster="<?= e($spotlightPoster) ?>">
        <source src="<?= e($showcaseVideoUrl) ?>" type="video/mp4">
      </video>

      <!-- Soft Scrim for Contrast & Perfect Readability -->
      <div class="hiq-spotlight-scrim"></div>

      <!-- Floating Spotlight Content -->
      <div class="hiq-spotlight-content">
        <h2 class="hiq-spotlight-title">See HOMEIQ<br>in Action</h2>
        <div class="mt-3 mt-md-4">
          <button type="button" class="hiq-demo-btn" id="btnPlayProductDemo" data-bs-toggle="modal" data-bs-target="#productDemoModal">
            <span class="hiq-demo-play-circle"><i class="bi bi-play-fill"></i></span>
            <span class="hiq-demo-text">Product Demo - 10 sec</span>
          </button>
        </div>
      </div>

      <!-- Centered Play Circle for Mobile Spotlight Card -->
      <button type="button" class="hiq-spotlight-center-play d-md-none" data-bs-toggle="modal" data-bs-target="#productDemoModal" aria-label="Play Demo">
        <i class="bi bi-play-fill"></i>
      </button>

      <!-- Floating Sound Toggle Button -->
      <button type="button" class="hiq-spotlight-mute-btn" id="spotlightMuteBtn" aria-label="Toggle Sound" title="Toggle Sound">
        <i class="bi bi-volume-mute-fill"></i>
      </button>
    </div>
  </div>
</section>

<!-- Product Demo Lightbox Modal -->
<div class="modal fade" id="productDemoModal" tabindex="-1" aria-labelledby="productDemoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark border-0 rounded-4 overflow-hidden shadow-lg">
      <div class="modal-header border-0 pb-0 pt-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
        <h5 class="modal-title text-light fw-bold fs-6 m-0" id="productDemoModalLabel">
          <i class="bi bi-shield-check text-warning me-2"></i>HOMEIQ Smart Lock — In Action Demo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-2 p-md-3">
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-black">
          <video id="modalProductDemoVideo" controls playsinline preload="metadata">
            <source src="<?= e($showcaseVideoUrl) ?>" type="video/mp4">
          </video>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const spotlightVideo = document.getElementById('spotlightShowcaseVideo');
  const muteBtn = document.getElementById('spotlightMuteBtn');
  if (spotlightVideo && muteBtn) {
    const icon = muteBtn.querySelector('i');
    muteBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      spotlightVideo.muted = !spotlightVideo.muted;
      if (icon) {
        icon.className = spotlightVideo.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
      }
    });
  }

  const demoModal = document.getElementById('productDemoModal');
  const modalVideo = document.getElementById('modalProductDemoVideo');
  if (demoModal && modalVideo) {
    demoModal.addEventListener('shown.bs.modal', function () {
      if (spotlightVideo) spotlightVideo.pause();
      modalVideo.currentTime = 0;
      modalVideo.play().catch(function () {});
    });
    demoModal.addEventListener('hidden.bs.modal', function () {
      modalVideo.pause();
      if (spotlightVideo) spotlightVideo.play().catch(function () {});
    });
  }
});
</script>

<!-- ==========================================================================
     SECTION 6: CUSTOMER REELS
     ========================================================================== -->
<?php if (!empty($videoTestimonials)): ?>
<section class="hiq-reels-section py-4 py-md-5">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
      <h2 class="hiq-section-heading m-0">Customer Reels</h2>
      <a href="<?= url('video-testimonials') ?>" class="hiq-view-all-link">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <!-- Reels Track (5 Vertical 9:15 Cards) -->
    <div class="hiq-reels-track-wrap position-relative">
      <div class="hiq-reels-scroll-track" id="homeReelsTrack">
        <?php foreach ($videoTestimonials as $vid): 
          $vidSource = !empty($vid['video_path']) ? upload_url($vid['video_path']) : ($vid['video_url'] ?? '');
          $thumbSource = !empty($vid['thumbnail_path']) ? upload_url($vid['thumbnail_path']) : '';
          $name = $vid['customer_name'] ?? 'Homeowner';
        ?>
          <div class="hiq-reel-item">
            <div class="hiq-mockup-reel-card" role="button" tabindex="0" title="Click to play">
              <video class="hiq-reel-video" playsinline loop preload="metadata" <?= !empty($thumbSource) ? 'poster="' . e($thumbSource) . '"' : '' ?>>
                <source src="<?= e($vidSource) ?>" type="video/mp4">
              </video>
              <div class="hiq-reel-scrim"></div>
              <button class="hiq-reel-play-circle" type="button" aria-label="Play Reel">
                <i class="bi bi-play-fill"></i>
              </button>
              <div class="hiq-reel-footer-caption">
                <span class="hiq-reel-author"><?= e($name) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var reelCards = document.querySelectorAll('.hiq-mockup-reel-card');
  reelCards.forEach(function (card) {
    var video = card.querySelector('video');
    if (!video) return;
    card.addEventListener('click', function () {
      if (video.paused) {
        document.querySelectorAll('.hiq-mockup-reel-card video').forEach(function (v) {
          if (v !== video) v.pause();
        });
        video.play().catch(function () {});
        card.classList.add('is-playing');
      } else {
        video.pause();
        card.classList.remove('is-playing');
      }
    });
  });
});
</script>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 7: WHAT OUR CUSTOMERS SAY (TESTIMONIALS)
     ========================================================================== -->
<section class="hiq-testimonials-section py-4 py-md-5">
  <div class="container">
    <h2 class="hiq-section-heading mb-4 text-start">What Our Customers Say</h2>

    <div class="row g-4">
      <?php 
        $displayReviews = !empty($recentReviews) ? array_slice($recentReviews, 0, 2) : [];
        if (empty($displayReviews)) {
          $displayReviews = [
            [
              'reviewer_name' => 'Aditya Sharma',
              'comment' => 'Excellent quality and easy installation. A great addition to our home.',
              'rating' => 5,
            ],
            [
              'reviewer_name' => 'Pooja Hegde',
              'comment' => 'Secure, stylish and very easy to use. Highly recommended!',
              'rating' => 5,
            ]
          ];
        }
        foreach ($displayReviews as $rIdx => $rev):
      ?>
        <div class="col-md-6 <?= $rIdx > 0 ? 'd-none d-md-block' : '' ?>">
          <div class="hiq-testi-card position-relative">
            <span class="hiq-testi-quote-mark" aria-hidden="true">&rdquo;</span>
            <div class="d-flex align-items-start gap-3">
              <div class="hiq-testi-avatar">
                <?php if (!empty($rev['avatar'])): ?>
                  <img src="<?= upload_url($rev['avatar']) ?>" alt="<?= e($rev['reviewer_name']) ?>">
                <?php else: ?>
                  <div class="hiq-testi-initials"><?= strtoupper(substr($rev['reviewer_name'] ?? 'U', 0, 1)) ?></div>
                <?php endif; ?>
              </div>
              <div class="hiq-testi-content">
                <div class="hiq-testi-stars">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
                <p class="hiq-testi-quote">"<?= e($rev['comment'] ?? 'Excellent quality and easy installation. A great addition to our home.') ?>"</p>
                <div class="hiq-testi-verified">
                  <i class="bi bi-check-circle-fill"></i> Verified Buyer
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Carousel Dots -->
    <div class="d-flex justify-content-center align-items-center gap-2 mt-4">
      <span class="hiq-testi-dot active"></span>
      <span class="hiq-testi-dot"></span>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 8: WHY CHOOSE HOMEIQ?
     ========================================================================== -->
<section class="hiq-why-choose-section py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-3">
        <h2 class="hiq-why-title mb-2">Why Choose<br>HOMEIQ?</h2>
        <div class="hiq-why-accent-line"></div>
        <p class="hiq-why-sub mb-0">Reliable security for a smarter lifestyle.</p>
      </div>

      <div class="col-lg-9">
        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-lg-5">
          <div class="col">
            <div class="hiq-why-card">
              <div class="hiq-why-icon"><i class="bi bi-shield-check"></i></div>
              <div class="hiq-why-card-title">High Security</div>
            </div>
          </div>
          <div class="col">
            <div class="hiq-why-card">
              <div class="hiq-why-icon"><i class="bi bi-tools"></i></div>
              <div class="hiq-why-card-title">Easy Installation</div>
            </div>
          </div>
          <div class="col">
            <div class="hiq-why-card">
              <div class="hiq-why-icon"><i class="bi bi-phone"></i></div>
              <div class="hiq-why-card-title">App Control</div>
            </div>
          </div>
          <div class="col">
            <div class="hiq-why-card">
              <div class="hiq-why-icon"><i class="bi bi-truck"></i></div>
              <div class="hiq-why-card-title">Fast Delivery</div>
            </div>
          </div>
          <div class="col">
            <div class="hiq-why-card">
              <div class="hiq-why-icon"><i class="bi bi-star"></i></div>
              <div class="hiq-why-card-title">Trusted by Customers</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 9: BOTTOM BANNER (CTA SECTION) - FULL DIV BACKGROUND
     ========================================================================== -->
<section class="hiq-bottom-banner-section py-4 py-md-5">
  <div class="container">
    <div class="hiq-bottom-banner-card position-relative overflow-hidden">
      <?php 
        $promoBottomImg = !empty($settings['promo_image']) ? $settings['promo_image'] : 'products/video-door-lock.svg';
      ?>
      <!-- Full Div Background Image -->
      <img src="<?= upload_url($promoBottomImg) ?>" alt="Upgrade to a Smarter & Safer Home" class="hiq-bottom-banner-bg-img" loading="lazy">

      <!-- Floating Banner Content -->
      <div class="hiq-bottom-banner-content position-relative">
        <h2 class="hiq-bottom-banner-title mb-4">Upgrade to a<br>Smarter &amp; Safer Home</h2>
        <a href="<?= url('shop') ?>" class="hiq-btn-capsule-orange">
          Shop Now <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</section>
