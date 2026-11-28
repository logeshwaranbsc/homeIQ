<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $banners
 * @var array $categories
 * @var array $bestSellers
 * @var array $featuredProducts
 * @var array $recentReviews
 * @var array $videoTestimonials
 * @var array $settings
 */
?>

<!-- ==========================================================================
     SECTION 1: HERO BANNER SLIDER
     ========================================================================== -->
<section class="hero-slider-section" id="home">
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
          $hasMobileBanner = !empty($banner['mobile_image']);
        ?>
        <div class="carousel-item carousel-item-hero <?= $idx === 0 ? 'active' : '' ?> <?= $hasMobileBanner ? 'has-mobile-banner' : 'no-mobile-banner' ?>">
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
     SECTION 3: SHOP BY CATEGORY
     ========================================================================== -->
<?php if (!empty($categories)): ?>
<section class="hiq-shop-category-section py-3 py-md-4" id="categories">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
      <h2 class="hiq-section-heading m-0">Shop by Category</h2>
      <a href="<?= url('categories') ?>" class="hiq-view-all-link">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <!-- Category Grid -->
    <div class="hiq-category-grid">
      <?php 
        $pastelClasses = ['pastel-peach', 'pastel-lavender', 'pastel-mint', 'pastel-sky'];

        foreach ($categories as $cIdx => $cat): 
          $pastelClass = $pastelClasses[$cIdx % 4];
      ?>
        <div class="hiq-category-col">
          <a href="<?= url('category/' . $cat['slug']) ?>" class="hiq-category-card <?= $pastelClass ?>" title="<?= e($cat['name']) ?>">
            <div class="hiq-category-img-box">
              <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="<?= e($cat['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
            </div>
            <div class="hiq-category-pill">
              <span class="hiq-cat-pill-name"><?= e($cat['name']) ?></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 4: BEST SELLERS (SEPARATE SECTION AFTER SHOP BY CATEGORY)
     ========================================================================== -->
<?php if (!empty($bestSellers)): ?>
<section class="hiq-bestsellers-section py-4 py-md-5" id="store">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <h2 class="hiq-section-heading m-0">Best Sellers</h2>
        <!-- Desktop Header Slider Arrows -->
        <div class="hiq-products-header-nav d-none d-md-flex align-items-center gap-1">
          <button type="button" class="hiq-prod-header-btn" id="bestHeaderPrev" aria-label="Previous best sellers" title="Previous best sellers">
            <i class="bi bi-chevron-left"></i>
          </button>
          <button type="button" class="hiq-prod-header-btn" id="bestHeaderNext" aria-label="Next best sellers" title="Next best sellers">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
      <a href="<?= url('shop') ?>" class="hiq-view-all-link">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <!-- Best Sellers Slider Wrap (Single Horizontal Track with 5 Products Visible + Left/Right Navigation) -->
    <div class="hiq-products-slider-wrap position-relative">
      <button type="button" class="hiq-prod-slider-btn prev" id="bestSliderPrev" aria-label="Previous best sellers" title="Previous best sellers">
        <i class="bi bi-chevron-left"></i>
      </button>

      <div class="hiq-products-grid" id="bestProductsTrack">
        <?php foreach ($bestSellers as $product): 
          $mrp = (float)($product['price'] ?? 0);
          $salePrice = (float)($product['sale_price'] ?? $mrp);
          $cleanTitle = preg_replace('/^HOMEIQ\s+/i', '', (string)$product['name']);
          $rating = number_format((float)($product['rating'] ?? 4.8), 1);
          $reviewsCount = !empty($product['reviews_count']) ? (int)$product['reviews_count'] : 48;
          $isBestSeller = !empty($product['is_bestseller']) || !empty($product['best_seller']);
        ?>
          <div class="hiq-product-col">
            <div class="hiq-product-card">
              <a href="<?= url('product/' . $product['slug']) ?>" class="hiq-prod-img-wrap position-relative" title="<?= e($product['name']) ?>">
                <?php if ($isBestSeller): ?>
                  <span class="modern-bestseller-badge position-absolute" style="top: 8px; left: 8px; z-index: 5;">
                    <i class="bi bi-star-fill"></i> Best Seller
                  </span>
                <?php endif; ?>
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

      <button type="button" class="hiq-prod-slider-btn next" id="bestSliderNext" aria-label="Next best sellers" title="Next best sellers">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 5: FEATURED PRODUCTS
     ========================================================================== -->
<?php if (!empty($featuredProducts)): ?>
<section class="hiq-featured-section py-4 py-md-5">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <h2 class="hiq-section-heading m-0">Featured Products</h2>
        <!-- Desktop Header Slider Arrows -->
        <div class="hiq-products-header-nav d-none d-md-flex align-items-center gap-1">
          <button type="button" class="hiq-prod-header-btn" id="prodHeaderPrev" aria-label="Previous products" title="Previous products">
            <i class="bi bi-chevron-left"></i>
          </button>
          <button type="button" class="hiq-prod-header-btn" id="prodHeaderNext" aria-label="Next products" title="Next products">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
      <a href="<?= url('shop') ?>" class="hiq-view-all-link d-md-none">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
      <div class="hiq-filter-pills d-flex align-items-center gap-2">
        <a href="<?= url('shop') ?>" class="hiq-filter-pill active">All</a>
        <?php foreach ($categories as $fCat): ?>
          <a href="<?= url('category/' . $fCat['slug']) ?>" class="hiq-filter-pill"><?= e($fCat['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Products Slider Wrap (Single Horizontal Track with 5 Products Visible + Left/Right Navigation) -->
    <div class="hiq-products-slider-wrap position-relative">
      <button type="button" class="hiq-prod-slider-btn prev" id="prodSliderPrev" aria-label="Previous products" title="Previous products">
        <i class="bi bi-chevron-left"></i>
      </button>

      <div class="hiq-products-grid" id="homeProductsTrack">
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

      <button type="button" class="hiq-prod-slider-btn next" id="prodSliderNext" aria-label="Next products" title="Next products">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  function setupProductSlider(trackId, prevBtnId, nextBtnId, headerPrevId, headerNextId) {
    var track = document.getElementById(trackId);
    var prevBtn = document.getElementById(prevBtnId);
    var nextBtn = document.getElementById(nextBtnId);
    var headerPrev = document.getElementById(headerPrevId);
    var headerNext = document.getElementById(headerNextId);

    if (!track) return;

    function getStep() {
      var col = track.querySelector('.hiq-product-col');
      if (!col) return 240;
      var style = window.getComputedStyle(track);
      var gap = parseFloat(style.gap) || 16;
      return col.offsetWidth + gap;
    }

    function updateArrowStates() {
      var maxScroll = track.scrollWidth - track.clientWidth;
      if (maxScroll <= 4) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (headerPrev) headerPrev.style.display = 'none';
        if (headerNext) headerNext.style.display = 'none';
        return;
      }
      if (prevBtn) prevBtn.style.display = '';
      if (nextBtn) nextBtn.style.display = '';
      if (headerPrev) headerPrev.style.display = '';
      if (headerNext) headerNext.style.display = '';

      var atStart = track.scrollLeft <= 6;
      var atEnd = track.scrollLeft >= maxScroll - 6;

      if (prevBtn) {
        if (atStart) prevBtn.classList.add('is-disabled');
        else prevBtn.classList.remove('is-disabled');
      }
      if (nextBtn) {
        if (atEnd) nextBtn.classList.add('is-disabled');
        else nextBtn.classList.remove('is-disabled');
      }
      if (headerPrev) {
        headerPrev.disabled = atStart;
        headerPrev.style.opacity = atStart ? '0.35' : '1';
      }
      if (headerNext) {
        headerNext.disabled = atEnd;
        headerNext.style.opacity = atEnd ? '0.35' : '1';
      }
    }

    function scrollPrev() {
      track.scrollBy({ left: -getStep(), behavior: 'smooth' });
    }

    function scrollNext() {
      track.scrollBy({ left: getStep(), behavior: 'smooth' });
    }

    if (prevBtn) prevBtn.addEventListener('click', scrollPrev);
    if (nextBtn) nextBtn.addEventListener('click', scrollNext);
    if (headerPrev) headerPrev.addEventListener('click', scrollPrev);
    if (headerNext) headerNext.addEventListener('click', scrollNext);

    track.addEventListener('scroll', updateArrowStates, { passive: true });
    window.addEventListener('resize', updateArrowStates, { passive: true });
    setTimeout(updateArrowStates, 100);

    // Mouse Drag Scrolling for Desktop
    var isDown = false;
    var startX = 0;
    var startScrollLeft = 0;
    var hasDragged = false;

    track.addEventListener('mousedown', function (e) {
      if (e.button !== 0) return;
      if (e.target.closest('.hiq-prod-cart-btn') || e.target.closest('button')) return;
      isDown = true;
      hasDragged = false;
      track.classList.add('is-dragging');
      startX = e.pageX - track.offsetLeft;
      startScrollLeft = track.scrollLeft;
    });

    window.addEventListener('mouseup', function () {
      if (isDown) {
        isDown = false;
        track.classList.remove('is-dragging');
        setTimeout(function () { hasDragged = false; }, 60);
      }
    });

    track.addEventListener('mousemove', function (e) {
      if (!isDown) return;
      e.preventDefault();
      var x = e.pageX - track.offsetLeft;
      var walk = (x - startX) * 1.3;
      track.scrollLeft = startScrollLeft - walk;
      if (Math.abs(x - startX) > 6) {
        hasDragged = true;
      }
    });

    // Prevent link click when dragging
    track.addEventListener('click', function (e) {
      if (hasDragged) {
        e.preventDefault();
        e.stopPropagation();
        hasDragged = false;
      }
    }, true);
  }

  // Initialize both horizontal sliders
  setupProductSlider('bestProductsTrack', 'bestSliderPrev', 'bestSliderNext', 'bestHeaderPrev', 'bestHeaderNext');
  setupProductSlider('homeProductsTrack', 'prodSliderPrev', 'prodSliderNext', 'prodHeaderPrev', 'prodHeaderNext');
});
</script>
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
      </div>
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
<section class="hiq-reels-section py-4 py-md-5" id="reels-videos">
  <div class="container">
    <div class="text-center mb-3 mb-md-4">
      <h2 class="hiq-section-heading m-0 justify-content-center">Customer Reels</h2>
    </div>

    <!-- Reels Track (Vertical Cards with Native Swipe and Drag Scrolling) -->
    <div class="hiq-reels-track-wrap position-relative">
      <?php 
        $displayReels = !empty($videoTestimonials) ? $videoTestimonials : [];
      ?>

      <div class="hiq-reels-scroll-track" id="homeReelsTrack">
        <?php foreach ($displayReels as $rIdx => $vid): 
          $vidSource = !empty($vid['video_path']) ? upload_url($vid['video_path']) : ($vid['video_url'] ?? '');
          $thumbSource = !empty($vid['thumbnail_path']) ? upload_url($vid['thumbnail_path']) : '';
          $name = !empty($vid['customer_name']) ? $vid['customer_name'] : '';
        ?>
          <div class="hiq-reel-item" data-index="<?= $rIdx ?>">
            <div class="hiq-mockup-reel-card" role="button" tabindex="0" title="Click to play">
              <video class="hiq-reel-video" autoplay muted playsinline loop preload="metadata" <?= !empty($thumbSource) ? 'poster="' . e($thumbSource) . '"' : '' ?>>
                <source src="<?= e($vidSource) ?>" type="video/mp4">
              </video>
              <div class="hiq-reel-scrim"></div>
              <button class="hiq-reel-mute-btn" type="button" aria-label="Sound off. Click to unmute." title="Sound off (Click to unmute)">
                <i class="bi bi-volume-mute-fill"></i>
              </button>
              <button class="hiq-reel-play-circle" type="button" aria-label="Play Reel">
                <i class="bi bi-play-fill"></i>
              </button>
              <?php if (!empty($name)): ?>
                <div class="hiq-reel-footer-caption">
                  <span class="hiq-reel-author"><?= e($name) ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var track = document.getElementById('homeReelsTrack');

  // Mouse Drag Left-to-Right / Right-to-Left scrolling on desktop
  var isDragging = false;
  var startX = 0;
  var startScrollLeft = 0;
  var hasDragged = false;

  if (track) {
    track.addEventListener('mousedown', function (e) {
      isDragging = true;
      hasDragged = false;
      track.classList.add('is-dragging');
      startX = e.pageX - track.offsetLeft;
      startScrollLeft = track.scrollLeft;
    });

    window.addEventListener('mouseup', function () {
      if (isDragging) {
        isDragging = false;
        track.classList.remove('is-dragging');
      }
    });

    track.addEventListener('mouseleave', function () {
      if (isDragging) {
        isDragging = false;
        track.classList.remove('is-dragging');
      }
    });

    track.addEventListener('mousemove', function (e) {
      if (!isDragging) return;
      e.preventDefault();
      var currentX = e.pageX - track.offsetLeft;
      var distance = (currentX - startX) * 1.5;
      if (Math.abs(currentX - startX) > 6) {
        hasDragged = true;
      }
      track.scrollLeft = startScrollLeft - distance;
    });
  }

  // Autoplay reels when visible & handle play/pause/mute toggle
  var reelCards = document.querySelectorAll('.hiq-mockup-reel-card');
  reelCards.forEach(function (card) {
    var video = card.querySelector('video');
    if (!video) return;

    var muteBtn = card.querySelector('.hiq-reel-mute-btn');
    var muteIcon = muteBtn ? muteBtn.querySelector('i') : null;

    function setMuteState(isMuted) {
      video.muted = isMuted;
      if (muteIcon) {
        muteIcon.className = isMuted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
      }
      if (muteBtn) {
        muteBtn.setAttribute('aria-label', isMuted ? 'Sound off. Click to unmute.' : 'Sound on. Click to mute.');
        muteBtn.setAttribute('title', isMuted ? 'Sound off (Click to unmute)' : 'Sound on (Click to mute)');
      }
    }
    card._setMuteState = setMuteState;

    // Track play state for styling
    video.addEventListener('play', function () {
      card.classList.add('is-playing');
    });
    video.addEventListener('pause', function () {
      card.classList.remove('is-playing');
    });

    // Start muted autoplay initially (default off)
    setMuteState(true);
    var playPromise = video.play();
    if (playPromise !== undefined) {
      playPromise.then(function () {
        card.classList.add('is-playing');
      }).catch(function () {});
    }

    // Toggle sound on speaker icon click
    if (muteBtn) {
      muteBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var willBeMuted = !video.muted;
        if (!willBeMuted) {
          // Turning sound ON: mute all other reels so only one audio stream plays
          reelCards.forEach(function (otherCard) {
            if (otherCard !== card && typeof otherCard._setMuteState === 'function') {
              otherCard._setMuteState(true);
            }
          });
          setMuteState(false);
          // If paused, start/resume playback
          if (video.paused) {
            document.querySelectorAll('.hiq-mockup-reel-card video').forEach(function (v) {
              if (v !== video) v.pause();
            });
            video.play().catch(function () {});
            card.classList.add('is-playing');
          }
        } else {
          // Turning sound OFF (default off)
          setMuteState(true);
        }
      });
    }

    // Card click toggles play/pause (ignoring speaker button)
    card.addEventListener('click', function (e) {
      if (hasDragged) return;
      if (e.target.closest('.hiq-reel-mute-btn')) return;

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

    card.addEventListener('keydown', function (e) {
      if (e.target.closest('.hiq-reel-mute-btn')) return;
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  // Autoplay / Pause with IntersectionObserver when scrolled in/out of view
  if ('IntersectionObserver' in window) {
    var reelObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var video = entry.target.querySelector('video');
        if (!video) return;
        if (entry.isIntersecting) {
          if (video.paused) {
            video.play().catch(function () {});
          }
        } else {
          video.pause();
          // Reset sound to default off when scrolled out of view
          if (typeof entry.target._setMuteState === 'function') {
            entry.target._setMuteState(true);
          }
        }
      });
    }, { threshold: 0.25 });

    reelCards.forEach(function (card) {
      reelObserver.observe(card);
    });
  }
});
</script>
<?php endif; ?>

<!-- ==========================================================================
     SECTION 7: WHAT OUR CUSTOMERS SAY (TESTIMONIALS SLIDER)
     ========================================================================== -->
<section class="hiq-testimonials-section py-3 py-md-4" id="testimonials">
  <div class="container">
    <div class="text-center mb-3 mb-md-4">
      <h2 class="hiq-section-heading m-0 justify-content-center">
        What Our <span class="hiq-title-highlight mx-1">Customers</span> Say
      </h2>
      <div class="hiq-google-review-badge mt-2">
        <div class="hiq-google-stars">
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
        </div>
        <span class="hiq-google-score">4.9 / 5</span>
        <span class="hiq-google-label">on Google Reviews</span>
      </div>
    </div>

    <?php 
      $allReviews = !empty($recentReviews) ? $recentReviews : [];
      if (count($allReviews) < 6) {
        $existingNames = array_map(fn($r) => strtolower($r['reviewer_name'] ?? ''), $allReviews);
        $defaultMockReviews = [
          [
            'reviewer_name' => 'Vikramaditya S.',
            'comment' => 'The face recognition works even in low hallway lighting. Hands-free entry when carrying groceries is a game changer. The technician arrived on time for installation.',
            'rating' => 5,
            'verified_purchase' => true,
          ],
          [
            'reviewer_name' => 'Dr. Meera Nambiar',
            'comment' => 'Installed on our main teak wood villa door. The mortise is heavy duty stainless steel. The app sends instant alerts if someone rings the doorbell.',
            'rating' => 5,
            'verified_purchase' => true,
          ],
          [
            'reviewer_name' => 'Arun Kumar',
            'comment' => 'Immediate unlock as you grip the handle. Battery life has been excellent after 4 months of daily use.',
            'rating' => 5,
            'verified_purchase' => true,
          ],
          [
            'reviewer_name' => 'Ananya Sharma',
            'comment' => 'Excellent biometric precision. Both fingerprint and PIN responses are instantaneous. Highly recommended for modern apartments.',
            'rating' => 5,
            'verified_purchase' => true,
          ],
          [
            'reviewer_name' => 'Rajesh K.',
            'comment' => 'Seamless keyless entry and the mobile app setup took less than 2 minutes. Outstanding build quality and long battery life.',
            'rating' => 5,
            'verified_purchase' => true,
          ],
          [
            'reviewer_name' => 'Pooja Mehta',
            'comment' => 'The smart lock looks ultra-sleek on our modern front door. The numeric keypad and auto-lock feature give us complete peace of mind.',
            'rating' => 5,
            'verified_purchase' => true,
          ]
        ];
        foreach ($defaultMockReviews as $dmRev) {
          if (count($allReviews) >= 6) break;
          if (!in_array(strtolower($dmRev['reviewer_name']), $existingNames, true)) {
            $allReviews[] = $dmRev;
          }
        }
      }
    ?>

    <!-- Movable Horizontal Track (Right to Left Slider) -->
    <div class="hiq-testi-slider-wrap position-relative">
      <div class="hiq-testi-track" id="homeTestimonialsTrack">
        <?php foreach ($allReviews as $rIdx => $rev): ?>
          <div class="hiq-testi-slide" data-index="<?= $rIdx ?>">
            <div class="hiq-testi-card position-relative h-100">
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
                    <?php 
                      $rRating = (int)($rev['rating'] ?? 5);
                      for ($s = 1; $s <= 5; $s++): 
                    ?>
                      <i class="bi <?= $s <= $rRating ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php endfor; ?>
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
    </div>

    <!-- Carousel Dots (Movable Slide Indicators) -->
    <div class="d-flex justify-content-center align-items-center gap-2 mt-2 mt-md-3" id="testimonialsDots">
      <span class="hiq-testi-dot active" role="button" aria-label="Slide 1"></span>
      <span class="hiq-testi-dot" role="button" aria-label="Slide 2"></span>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var track = document.getElementById('homeTestimonialsTrack');
  var dotsContainer = document.getElementById('testimonialsDots');
  if (!track || !dotsContainer) return;

  var slides = track.querySelectorAll('.hiq-testi-slide');
  if (slides.length === 0) return;

  var autoSlideInterval = null;
  var currentPage = 0;

  function getItemsPerSlide() {
    if (window.innerWidth < 768) return 1;
    if (window.innerWidth < 992) return 2;
    return 3;
  }

  function goToPage(pageIdx) {
    var itemsPerSlide = getItemsPerSlide();
    var pageCount = Math.ceil(slides.length / itemsPerSlide);
    if (pageCount <= 1) return;
    currentPage = (pageIdx + pageCount) % pageCount;
    var targetIndex = currentPage * itemsPerSlide;
    if (slides[targetIndex]) {
      track.scrollTo({
        left: slides[targetIndex].offsetLeft - track.offsetLeft,
        behavior: 'smooth'
      });
    }
  }

  function startAutoSlide() {
    stopAutoSlide();
    autoSlideInterval = setInterval(function () {
      var itemsPerSlide = getItemsPerSlide();
      var pageCount = Math.ceil(slides.length / itemsPerSlide);
      if (pageCount <= 1) return;
      var nextPage = (currentPage + 1) % pageCount;
      goToPage(nextPage);
    }, 4500);
  }

  function stopAutoSlide() {
    if (autoSlideInterval) {
      clearInterval(autoSlideInterval);
      autoSlideInterval = null;
    }
  }

  function updateDots() {
    dotsContainer.innerHTML = '';
    var itemsPerSlide = getItemsPerSlide();
    var pageCount = Math.ceil(slides.length / itemsPerSlide);

    if (pageCount <= 1) return;

    for (var i = 0; i < pageCount; i++) {
      (function(pageIdx) {
        var dot = document.createElement('span');
        dot.className = 'hiq-testi-dot' + (pageIdx === currentPage ? ' active' : '');
        dot.setAttribute('role', 'button');
        dot.setAttribute('aria-label', 'Go to review slide ' + (pageIdx + 1));
        dot.addEventListener('click', function () {
          goToPage(pageIdx);
          startAutoSlide();
        });
        dotsContainer.appendChild(dot);
      })(i);
    }
  }

  function onScroll() {
    var itemsPerSlide = getItemsPerSlide();
    var scrollLeft = track.scrollLeft;
    var slideWidth = (slides[0] ? slides[0].offsetWidth : 300) + 16;
    var activePage = Math.round(scrollLeft / (slideWidth * itemsPerSlide));
    currentPage = activePage;
    
    var dots = dotsContainer.querySelectorAll('.hiq-testi-dot');
    dots.forEach(function (dot, idx) {
      if (idx === activePage) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  }

  track.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', function () {
    updateDots();
    onScroll();
  });
  updateDots();
  startAutoSlide();

  // Pause auto-switch on user hover / touch
  track.addEventListener('mouseenter', stopAutoSlide);
  track.addEventListener('mouseleave', function () {
    if (!isDown) startAutoSlide();
  });
  track.addEventListener('touchstart', stopAutoSlide, { passive: true });
  track.addEventListener('touchend', function () {
    setTimeout(startAutoSlide, 2000);
  }, { passive: true });

  // Desktop Mouse Drag to move right-to-left
  var isDown = false;
  var startX;
  var startScrollLeft;

  track.addEventListener('mousedown', function (e) {
    stopAutoSlide();
    isDown = true;
    track.classList.add('is-dragging');
    startX = e.pageX - track.offsetLeft;
    startScrollLeft = track.scrollLeft;
  });

  window.addEventListener('mouseup', function () {
    if (isDown) {
      isDown = false;
      track.classList.remove('is-dragging');
      startAutoSlide();
    }
  });

  track.addEventListener('mouseleave', function () {
    if (isDown) {
      isDown = false;
      track.classList.remove('is-dragging');
      startAutoSlide();
    }
  });

  track.addEventListener('mousemove', function (e) {
    if (!isDown) return;
    e.preventDefault();
    var x = e.pageX - track.offsetLeft;
    var walk = (x - startX) * 1.5;
    track.scrollLeft = startScrollLeft - walk;
  });
});
</script>

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
<!-- ==========================================================================
     SECTION 2: FEATURE HIGHLIGHTS STRIP
     ========================================================================== -->
<section class="hiq-features-strip-section">
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
