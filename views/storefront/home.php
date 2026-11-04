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
                      <div class="d-flex align-items-center gap-3 flex-wrap <?= $textPos === 'center' ? 'justify-content-center' : ($textPos === 'right' ? 'justify-content-end' : '') ?>">
                        <a href="<?= url($banner['cta_url'] ?? 'shop') ?>" class="btn btn-hiq-accent py-2.5 px-4 fs-6 fw-bold">
                          <?= e($banner['cta_label']) ?> <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="<?= url('about') ?>" class="btn btn-hiq-glass py-2.5 px-4 fs-6 fw-semibold">
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

<!-- Creative Smart Lock Collections Showcase Section -->
<?php
// Function mapping intelligent category metadata, badges, and technology accents
$getCategoryMeta = function(array $cat, int $index): array {
    $slug = strtolower((string)($cat['slug'] ?? ''));
    $name = strtolower((string)($cat['name'] ?? ''));

    // Base default metadata
    $meta = [
        'code' => sprintf('SYS // %02d', $index + 1),
        'icon' => 'bi-shield-lock',
        'tag' => 'Active Defense',
        'group' => 'security',
        'badge' => 'High Security',
        'chips' => ['Architectural', 'Keyless Access', 'Emergency Key'],
        'accent' => '#FE6B25',
        'accent_end' => '#FE4A1F',
        'glow' => 'rgba(254, 107, 37, 0.25)',
        'border' => 'rgba(254, 107, 37, 0.4)',
        'badge_bg' => 'rgba(254, 107, 37, 0.1)',
        'badge_color' => '#FE6B25',
        'badge_border' => 'rgba(254, 107, 37, 0.22)',
        'stage_glow' => 'rgba(254, 107, 37, 0.22)',
    ];

    if (str_contains($slug, 'face') || str_contains($name, 'face')) {
        $meta['icon'] = 'bi-person-bounding-box';
        $meta['tag'] = '3D Infrared Scan';
        $meta['group'] = 'biometric';
        $meta['badge'] = 'Flagship 3D AI';
        $meta['chips'] = ['Hands-Free Unlock', '3D Light Camera', '< 0.4s Instant'];
        $meta['accent'] = '#FE6B25';
        $meta['accent_end'] = '#FE4A1F';
        $meta['glow'] = 'rgba(254, 107, 37, 0.3)';
        $meta['border'] = 'rgba(254, 107, 37, 0.45)';
        $meta['badge_bg'] = 'rgba(254, 107, 37, 0.12)';
        $meta['badge_color'] = '#FE6B25';
        $meta['badge_border'] = 'rgba(254, 107, 37, 0.25)';
        $meta['stage_glow'] = 'rgba(254, 107, 37, 0.25)';
    } elseif (str_contains($slug, 'fingerprint') || str_contains($name, 'fingerprint')) {
        $meta['icon'] = 'bi-fingerprint';
        $meta['tag'] = 'Biometric Touch';
        $meta['group'] = 'biometric';
        $meta['badge'] = '0.3s Sub-Second';
        $meta['chips'] = ['Handle Sensor', '100 Fingerprints', 'Semiconductor'];
        $meta['accent'] = '#10B981';
        $meta['accent_end'] = '#059669';
        $meta['glow'] = 'rgba(16, 185, 129, 0.3)';
        $meta['border'] = 'rgba(16, 185, 129, 0.45)';
        $meta['badge_bg'] = 'rgba(16, 185, 129, 0.12)';
        $meta['badge_color'] = '#059669';
        $meta['badge_border'] = 'rgba(16, 185, 129, 0.25)';
        $meta['stage_glow'] = 'rgba(16, 185, 129, 0.25)';
    } elseif (str_contains($slug, 'vein') || str_contains($name, 'vein')) {
        $meta['icon'] = 'bi-hand-index-thumb';
        $meta['tag'] = 'Vascular IR';
        $meta['group'] = 'biometric';
        $meta['badge'] = 'Military-Grade';
        $meta['chips'] = ['Contactless Palm', 'Zero Duplication', 'Subcutaneous IR'];
        $meta['accent'] = '#8B5CF6';
        $meta['accent_end'] = '#6D28D9';
        $meta['glow'] = 'rgba(139, 92, 246, 0.3)';
        $meta['border'] = 'rgba(139, 92, 246, 0.45)';
        $meta['badge_bg'] = 'rgba(139, 92, 246, 0.12)';
        $meta['badge_color'] = '#7C3AED';
        $meta['badge_border'] = 'rgba(139, 92, 246, 0.25)';
        $meta['stage_glow'] = 'rgba(139, 92, 246, 0.25)';
    } elseif (str_contains($slug, 'wifi') || str_contains($name, 'wifi') || str_contains($slug, 'app') || str_contains($name, 'app')) {
        $meta['icon'] = 'bi-wifi';
        $meta['tag'] = 'Tuya Smart IoT';
        $meta['group'] = 'connected';
        $meta['badge'] = 'Real-Time App';
        $meta['chips'] = ['Remote One-Time OTP', 'Access Logs', 'Smart Life Sync'];
        $meta['accent'] = '#0284C7';
        $meta['accent_end'] = '#0369A1';
        $meta['glow'] = 'rgba(2, 132, 199, 0.3)';
        $meta['border'] = 'rgba(2, 132, 199, 0.45)';
        $meta['badge_bg'] = 'rgba(2, 132, 199, 0.12)';
        $meta['badge_color'] = '#0284C7';
        $meta['badge_border'] = 'rgba(2, 132, 199, 0.25)';
        $meta['stage_glow'] = 'rgba(2, 132, 199, 0.25)';
    } elseif (str_contains($slug, 'video') || str_contains($name, 'video') || str_contains($slug, 'camera') || str_contains($name, 'camera')) {
        $meta['icon'] = 'bi-camera-video';
        $meta['tag'] = 'Visual Intercom';
        $meta['group'] = 'connected';
        $meta['badge'] = '1080p HD Video';
        $meta['chips'] = ['Two-Way Audio', 'IR Night Vision', 'Instant Doorbell'];
        $meta['accent'] = '#E11D48';
        $meta['accent_end'] = '#BE123C';
        $meta['glow'] = 'rgba(225, 29, 72, 0.3)';
        $meta['border'] = 'rgba(225, 29, 72, 0.45)';
        $meta['badge_bg'] = 'rgba(225, 29, 72, 0.12)';
        $meta['badge_color'] = '#E11D48';
        $meta['badge_border'] = 'rgba(225, 29, 72, 0.25)';
        $meta['stage_glow'] = 'rgba(225, 29, 72, 0.25)';
    } elseif (str_contains($slug, 'pin') || str_contains($slug, 'rfid') || str_contains($name, 'pin') || str_contains($name, 'rfid')) {
        $meta['icon'] = 'bi-keypad';
        $meta['tag'] = 'Digital Access';
        $meta['group'] = 'security';
        $meta['badge'] = 'Anti-Peep PIN';
        $meta['chips'] = ['Virtual Scramble', 'Encrypted Smart RFID', 'Backlit Touch'];
        $meta['accent'] = '#D97706';
        $meta['accent_end'] = '#B45309';
        $meta['glow'] = 'rgba(217, 119, 6, 0.3)';
        $meta['border'] = 'rgba(217, 119, 6, 0.45)';
        $meta['badge_bg'] = 'rgba(217, 119, 6, 0.12)';
        $meta['badge_color'] = '#D97706';
        $meta['badge_border'] = 'rgba(217, 119, 6, 0.25)';
        $meta['stage_glow'] = 'rgba(217, 119, 6, 0.25)';
    } elseif (str_contains($slug, 'mortise') || str_contains($name, 'mortise')) {
        $meta['icon'] = 'bi-safe2';
        $meta['tag'] = 'Heavy Mortise';
        $meta['group'] = 'security';
        $meta['badge'] = '304 Stainless';
        $meta['chips'] = ['Multi-Point Deadbolt', 'Anti-Pry Mortise', 'Main Door Armor'];
        $meta['accent'] = '#475569';
        $meta['accent_end'] = '#334155';
        $meta['glow'] = 'rgba(71, 85, 105, 0.3)';
        $meta['border'] = 'rgba(71, 85, 105, 0.45)';
        $meta['badge_bg'] = 'rgba(71, 85, 105, 0.12)';
        $meta['badge_color'] = '#334155';
        $meta['badge_border'] = 'rgba(71, 85, 105, 0.25)';
        $meta['stage_glow'] = 'rgba(71, 85, 105, 0.25)';
    } elseif (str_contains($slug, 'accessories') || str_contains($name, 'accessories')) {
        $meta['icon'] = 'bi-plug';
        $meta['tag'] = 'Power & Parts';
        $meta['group'] = 'security';
        $meta['badge'] = 'OEM Hardware';
        $meta['chips'] = ['Lithium Battery', 'Gateway Hubs', 'RFID Key Fobs'];
        $meta['accent'] = '#0D9488';
        $meta['accent_end'] = '#0F766E';
        $meta['glow'] = 'rgba(13, 148, 136, 0.3)';
        $meta['border'] = 'rgba(13, 148, 136, 0.45)';
        $meta['badge_bg'] = 'rgba(13, 148, 136, 0.12)';
        $meta['badge_color'] = '#0F766E';
        $meta['badge_border'] = 'rgba(13, 148, 136, 0.25)';
        $meta['stage_glow'] = 'rgba(13, 148, 136, 0.25)';
    }

    return $meta;
};
?>
<section class="hiq-collections-section">
  <div class="container position-relative" style="z-index: 2;">
    <!-- Section Header -->
    <div class="row align-items-end justify-content-between mb-2 g-3">
      <div class="col-lg-8">
        <span class="cat-sec-badge">
          <span class="cat-pulse-dot"></span> Architectural Access Systems
        </span>
        <h2 class="cat-sec-title">
          Explore Smart Door <span class="hiq-accent-gradient">Lock Collections</span>
        </h2>
        <p class="cat-sec-subtitle">
          From hands-free 3D facial biometrics to hardened 304 stainless steel mortises — precision security hardware tailored to your architectural entrance.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?= url('categories') ?>" class="cat-view-all-pill">
          <span>View All Collections</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- Interactive Category Filter Tabs -->
    <div class="cat-filter-wrapper">
      <div class="cat-filter-nav" id="catFilterNav">
        <button type="button" class="cat-filter-btn active" data-filter="all">
          <span>All Systems</span>
          <span class="cat-count-pill"><?= count($categories) ?></span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="biometric">
          <i class="bi bi-person-bounding-box text-primary"></i>
          <span>Biometric &amp; AI</span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="connected">
          <i class="bi bi-wifi text-info"></i>
          <span>Smart &amp; Connected</span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="security">
          <i class="bi bi-shield-shaded text-warning"></i>
          <span>Core Hardware &amp; Parts</span>
        </button>
      </div>
    </div>

    <!-- Category Cards Grid -->
    <div class="row g-4" id="catCardsGrid">
      <?php foreach ($categories as $idx => $cat): ?>
        <?php $meta = $getCategoryMeta($cat, $idx); ?>
        <div class="col-xl-3 col-lg-4 col-sm-6 cat-col-item" data-category-group="<?= e($meta['group']) ?>">
          <a href="<?= url('category/' . $cat['slug']) ?>" class="hiq-cat-card" style="
            --cat-accent-color: <?= $meta['accent'] ?>;
            --cat-accent-color-end: <?= $meta['accent_end'] ?>;
            --cat-accent-glow: <?= $meta['glow'] ?>;
            --cat-accent-border: <?= $meta['border'] ?>;
            --cat-badge-bg: <?= $meta['badge_bg'] ?>;
            --cat-badge-color: <?= $meta['badge_color'] ?>;
            --cat-badge-border: <?= $meta['badge_border'] ?>;
            --cat-stage-glow: <?= $meta['stage_glow'] ?>;
          ">
            <!-- Top HUD Row -->
            <div class="cat-hud-row">
              <span class="cat-code"><?= e($meta['code']) ?></span>
              <span class="cat-hud-badge">
                <i class="bi <?= e($meta['icon']) ?>"></i> <?= e($meta['tag']) ?>
              </span>
            </div>

            <!-- Hardware Showcase Stage -->
            <div class="cat-hardware-stage">
              <div class="cat-stage-radar"></div>
              <img src="<?= upload_url($cat['image'] ?? '') ?>" alt="<?= e($cat['name']) ?>" class="cat-lock-visual" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
              <span class="cat-stage-tag">
                <i class="bi bi-patch-check-fill"></i> <?= e($meta['badge']) ?>
              </span>
            </div>

            <!-- Card Body Content -->
            <div class="cat-body-content">
              <h3 class="cat-title-text"><?= e($cat['name']) ?></h3>
              
              <!-- Spec Chips -->
              <div class="cat-chips-row">
                <?php foreach ($meta['chips'] as $chip): ?>
                  <span class="cat-spec-chip"><?= e($chip) ?></span>
                <?php endforeach; ?>
              </div>

              <!-- Category Description -->
              <p class="cat-summary-text"><?= e($cat['description']) ?></p>

              <!-- Action Footer -->
              <div class="cat-action-row">
                <span class="cat-action-label">Explore Models</span>
                <span class="cat-action-circle">
                  <i class="bi bi-arrow-right"></i>
                </span>
              </div>
            </div>

            <!-- Bottom Glowing Line -->
            <div class="cat-bottom-line"></div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Architectural Security Engineering Pillars -->
    <div class="cat-bottom-pillars">
      <div class="row g-4 align-items-center">
        <div class="col-lg-3 col-sm-6">
          <div class="cat-pillar-item">
            <div class="cat-pillar-icon" style="background: rgba(254, 107, 37, 0.1); color: #FE6B25;">
              <i class="bi bi-cpu-fill"></i>
            </div>
            <div>
              <div class="cat-pillar-title">3D AI Structured Light</div>
              <div class="cat-pillar-sub">Contactless instant unlocking</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="cat-pillar-item">
            <div class="cat-pillar-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
              <i class="bi bi-fingerprint"></i>
            </div>
            <div>
              <div class="cat-pillar-title">&lt; 0.4s Bio Recognition</div>
              <div class="cat-pillar-sub">Live semiconductor scanner</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="cat-pillar-item">
            <div class="cat-pillar-icon" style="background: rgba(2, 132, 199, 0.1); color: #0284C7;">
              <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
              <div class="cat-pillar-title">304 Stainless Mortise</div>
              <div class="cat-pillar-sub">Multi-point anti-drill deadbolts</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="cat-pillar-item">
            <div class="cat-pillar-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
              <i class="bi bi-tools"></i>
            </div>
            <div>
              <div class="cat-pillar-title">Certified Installation</div>
              <div class="cat-pillar-sub">Free door check &amp; chisel routing</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Category Filtering Vanilla JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const filterNav = document.getElementById('catFilterNav');
  const cardsGrid = document.getElementById('catCardsGrid');
  if (!filterNav || !cardsGrid) return;

  const buttons = filterNav.querySelectorAll('.cat-filter-btn');
  const items = cardsGrid.querySelectorAll('.cat-col-item');

  buttons.forEach(btn => {
    btn.addEventListener('click', function () {
      const filter = this.getAttribute('data-filter');

      // Update active button state
      buttons.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      // Filter category items
      items.forEach(item => {
        const group = item.getAttribute('data-category-group');
        if (filter === 'all' || group === filter) {
          item.classList.remove('is-hidden');
          item.style.opacity = '0';
          item.style.transform = 'translateY(12px) scale(0.97)';
          requestAnimationFrame(() => {
            item.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0) scale(1)';
          });
        } else {
          item.classList.add('is-hidden');
        }
      });
    });
  });
});
</script>


<!-- Brand Editorial Story Section (Fully Customizable from Admin Panel) -->
<?php if (!empty($settings['story_enabled'] ?? true)): ?>
  <?php
    $storyTheme = $settings['story_theme'] ?? 'dark';
    $storyBadge = $settings['story_badge'] ?? 'HARDWARE PHILOSOPHY';
    $storyBadgeIcon = $settings['story_badge_icon'] ?? 'bi-cpu';
    $storyTitle = $settings['story_title'] ?? 'A NEW STANDARD IN ARCHITECTURAL DEFENSE';
    $storySubtitle = $settings['story_subtitle'] ?? 'HOMEIQ — where intelligent engineering meets residential peace of mind.';
    $storyDesc = $settings['story_description'] ?? 'We’re reimagining how discerning homeowners secure their properties. Crafted from aerospace-grade aluminum alloys and hardened 304 stainless steel mortises, each lock pairs impenetrable mechanical deadbolts with lightning-fast semiconductor biometrics.';
    $metric1Val = $settings['story_metric1_val'] ?? '<0.3s';
    $metric1Lbl = $settings['story_metric1_label'] ?? 'Sensor Scan';
    $metric2Val = $settings['story_metric2_val'] ?? '9-in-1';
    $metric2Lbl = $settings['story_metric2_label'] ?? 'Access Modes';
    $metric3Val = $settings['story_metric3_val'] ?? '304';
    $metric3Lbl = $settings['story_metric3_label'] ?? 'Steel Mortise';
    $metric4Val = $settings['story_metric4_val'] ?? 'AES';
    $metric4Lbl = $settings['story_metric4_label'] ?? '128-Bit Safe';
    $btn1Text = $settings['story_btn1_text'] ?? 'Explore All Hardware';
    $btn1Url = $settings['story_btn1_url'] ?? 'shop';
    $btn2Text = $settings['story_btn2_text'] ?? 'Read Security Briefing';
    $btn2Url = $settings['story_btn2_url'] ?? 'about';
    $storyImg = $settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg';
    $storyPill = $settings['story_pill_text'] ?? 'Aerospace 6063-T6 • 304 Stainless Steel Mortise';
  ?>
  <section class="brand-intro-section position-relative <?= ($storyTheme === 'light') ? 'theme-light' : '' ?>">
    <div class="container position-relative" style="z-index: 2;">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="mb-3">
            <span class="story-badge-pill">
              <i class="bi <?= e($storyBadgeIcon) ?>"></i> <?= e($storyBadge) ?>
            </span>
          </div>

          <?php
            $titleWords = explode(' ', $storyTitle);
            if (count($titleWords) >= 3) {
                $lastTwo = implode(' ', array_splice($titleWords, -2));
                $formattedTitle = e(implode(' ', $titleWords)) . ' <span class="story-title-highlight">' . e($lastTwo) . '</span>';
            } else {
                $formattedTitle = e($storyTitle);
            }
          ?>
          <h2 class="mb-3">
            <?= $formattedTitle ?>
          </h2>

          <?php if (!empty($storySubtitle)): ?>
            <p class="story-subtitle">
              <?= e($storySubtitle) ?>
            </p>
          <?php endif; ?>

          <?php if (!empty($storyDesc)): ?>
            <p class="story-desc">
              <?= nl2br(e($storyDesc)) ?>
            </p>
          <?php endif; ?>

          <!-- Spec Metrics Bento Grid -->
          <div class="row g-3 mb-4">
            <div class="col-6 col-sm-3">
              <div class="spec-metric-card">
                <div class="spec-metric-val"><?= e($metric1Val) ?></div>
                <div class="spec-metric-label"><?= e($metric1Lbl) ?></div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="spec-metric-card">
                <div class="spec-metric-val"><?= e($metric2Val) ?></div>
                <div class="spec-metric-label"><?= e($metric2Lbl) ?></div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="spec-metric-card">
                <div class="spec-metric-val"><?= e($metric3Val) ?></div>
                <div class="spec-metric-label"><?= e($metric3Lbl) ?></div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="spec-metric-card">
                <div class="spec-metric-val"><?= e($metric4Val) ?></div>
                <div class="spec-metric-label"><?= e($metric4Lbl) ?></div>
              </div>
            </div>
          </div>

          <div class="d-flex gap-3 flex-wrap align-items-center">
            <?php if (!empty($btn1Text)): ?>
              <a href="<?= url($btn1Url) ?>" class="btn btn-hiq-primary px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <span><?= e($btn1Text) ?></span>
                <i class="bi bi-arrow-right"></i>
              </a>
            <?php endif; ?>
            <?php if (!empty($btn2Text)): ?>
              <a href="<?= url($btn2Url) ?>" class="btn btn-outline-light px-4 py-2 fw-semibold" style="<?= ($storyTheme === 'light') ? 'border-color: #ffd0b5; color: #1e293b; background: #ffffff;' : 'background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.2); color: #ffffff;' ?>">
                <i class="bi bi-shield-check text-primary me-1"></i> <?= e($btn2Text) ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="story-showcase-card">
            <div class="story-card-top-bar">
              <span class="story-chip-badge" title="<?= e($storyPill) ?>">
                <i class="bi bi-shield-check text-warning"></i>
                <?= e($storyPill) ?>
              </span>
              <span class="story-chip-status">
                <span class="story-pulse-dot"></span> Active Defense
              </span>
            </div>

            <div class="story-card-body">
              <div class="story-halo-glow"></div>
              <img src="<?= upload_url($storyImg) ?>" alt="<?= e($storyTitle) ?>" class="story-lock-img" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
            </div>

            <div class="story-card-footer">
              <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                <span class="small text-light opacity-90 d-flex align-items-center gap-1">
                  <i class="bi bi-patch-check-fill text-success"></i> HOMEIQ Certified Biometrics
                </span>
                <span class="badge bg-dark border border-secondary text-light small px-2 py-1">
                  Grade 1 Mortise
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Featured Products Showcase (Creative Modern Slider Carousel with Arrow Controls) -->
<section class="py-5 bg-white position-relative featured-products-section">
  <div class="container py-4">
    <!-- Section Header -->
    <div class="row align-items-end justify-content-between mb-3 g-3">
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

      <div class="col-lg-5 text-lg-end d-flex align-items-center justify-content-lg-end gap-3 flex-wrap">
        <a href="<?= url('shop') ?>" class="cat-view-all-pill">
          <span>Explore Catalog (<?= count($featuredProducts) ?> Models)</span>
          <i class="bi bi-arrow-right"></i>
        </a>

        <!-- Header Arrow Controls -->
        <div class="slider-header-controls d-flex align-items-center gap-2">
          <button type="button" class="btn-slider-arrow btn-slider-prev" id="featuredHeaderPrev" aria-label="Previous Locks" title="Previous Locks">
            <i class="bi bi-chevron-left"></i>
          </button>
          <button type="button" class="btn-slider-arrow btn-slider-next" id="featuredHeaderNext" aria-label="Next Locks" title="Next Locks">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Interactive Featured Filter Tabs -->
    <div class="cat-filter-wrapper mb-4">
      <div class="cat-filter-nav" id="featuredFilterNav">
        <button type="button" class="cat-filter-btn active" data-filter="all">
          <span>All Featured</span>
          <span class="cat-count-pill"><?= count($featuredProducts) ?></span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="face">
          <i class="bi bi-person-bounding-box text-primary"></i>
          <span>AI Face Locks</span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="fingerprint">
          <i class="bi bi-fingerprint text-success"></i>
          <span>Biometric Touch</span>
        </button>
        <button type="button" class="cat-filter-btn" data-filter="smart">
          <i class="bi bi-camera-video text-danger"></i>
          <span>Video &amp; Smart IoT</span>
        </button>
      </div>
    </div>

    <!-- Product Slider Carousel Viewport with Gutter Side Arrows (Never Overlapping Cards) -->
    <div class="product-slider-container position-relative">
      <!-- Side Floating Left Arrow (Positioned in gutter channel) -->
      <button type="button" class="product-slider-arrow arrow-prev" id="featuredSidePrev" aria-label="Scroll Left" title="Scroll Left">
        <i class="bi bi-chevron-left"></i>
      </button>

      <!-- Scrollable Products Track -->
      <div class="product-slider-track" id="featuredProductsTrack">
        <?php foreach ($featuredProducts as $product): ?>
          <?php
            $slug = strtolower((string)($product['slug'] ?? ''));
            $catSlug = strtolower((string)($product['category_slug'] ?? $product['category'] ?? ''));
            $pGroup = 'all';
            if (str_contains($slug, 'face') || str_contains($catSlug, 'face')) {
                $pGroup = 'face';
            } elseif (str_contains($slug, 'fingerprint') || str_contains($slug, 'touch') || str_contains($catSlug, 'fingerprint')) {
                $pGroup = 'fingerprint';
            } elseif (str_contains($slug, 'video') || str_contains($slug, 'vision') || str_contains($slug, 'wifi') || str_contains($catSlug, 'video') || str_contains($catSlug, 'wifi')) {
                $pGroup = 'smart';
            }
          ?>
          <div class="product-slider-slide" data-product-group="<?= $pGroup ?>">
            <?= View::component('product_card', ['product' => $product]) ?>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Side Floating Right Arrow (Positioned in gutter channel) -->
      <button type="button" class="product-slider-arrow arrow-next" id="featuredSideNext" aria-label="Scroll Right" title="Scroll Right">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <!-- Custom Architecture Callout Banner -->
    <div class="featured-custom-banner mt-5">
      <div class="row align-items-center g-3">
        <div class="col-lg-8">
          <div class="d-flex align-items-center gap-3">
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

<!-- Featured Slider & Filter Navigation JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const track = document.getElementById('featuredProductsTrack');
  const prevBtns = [
    document.getElementById('featuredSidePrev'),
    document.getElementById('featuredHeaderPrev')
  ].filter(Boolean);
  const nextBtns = [
    document.getElementById('featuredSideNext'),
    document.getElementById('featuredHeaderNext')
  ].filter(Boolean);

  if (track && (prevBtns.length > 0 || nextBtns.length > 0)) {
    const getVisibleSlides = () => {
      return Array.from(track.querySelectorAll('.product-slider-slide')).filter(s => s.style.display !== 'none');
    };

    const getScrollStep = () => {
      const slides = getVisibleSlides();
      if (slides.length > 0) {
        const gap = parseFloat(window.getComputedStyle(track).gap) || 20;
        return Math.round(slides[0].getBoundingClientRect().width + gap);
      }
      return 280;
    };

    nextBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
        if (track.scrollLeft >= maxScroll - 10) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          const target = Math.min(track.scrollLeft + step, maxScroll);
          track.scrollTo({ left: target, behavior: 'smooth' });
        }
      });
    });

    prevBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
        if (track.scrollLeft <= 10) {
          track.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
          const target = Math.max(track.scrollLeft - step, 0);
          track.scrollTo({ left: target, behavior: 'smooth' });
        }
      });
    });
  }

  // Interactive Filter Tabs on Carousel
  const fNav = document.getElementById('featuredFilterNav');
  if (fNav && track) {
    const fBtns = fNav.querySelectorAll('.cat-filter-btn');
    const fSlides = track.querySelectorAll('.product-slider-slide');

    fBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        const filter = this.getAttribute('data-filter');
        fBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        fSlides.forEach(slide => {
          const group = slide.getAttribute('data-product-group');
          if (filter === 'all' || group === filter) {
            slide.style.display = 'flex';
            slide.style.opacity = '1';
          } else {
            slide.style.display = 'none';
          }
        });
        track.scrollTo({ left: 0, behavior: 'smooth' });
      });
    });
  }
});
</script>


<!-- Featured Products Filter Vanilla JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fNav = document.getElementById('featuredFilterNav');
  const fGrid = document.getElementById('featuredProductsGrid');
  if (!fNav || !fGrid) return;

  const fBtns = fNav.querySelectorAll('.cat-filter-btn');
  const fItems = fGrid.querySelectorAll('.featured-grid-col');

  fBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const filter = this.getAttribute('data-filter');
      fBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      fItems.forEach(item => {
        const group = item.getAttribute('data-product-group');
        if (filter === 'all' || group === filter) {
          item.classList.remove('is-hidden');
          item.style.opacity = '0';
          item.style.transform = 'translateY(10px) scale(0.98)';
          requestAnimationFrame(() => {
            item.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0) scale(1)';
          });
        } else {
          item.classList.add('is-hidden');
        }
      });
    });
  });
});
</script>


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
      <div class="row align-items-center g-5">
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


<!-- Customer Video Reels Section (Max 4 Videos - Inline Playback) -->
<?php if (!empty($videoTestimonials)): ?>
<section class="hiq-reels-section py-5 border-top">
  <div class="container py-3">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1 d-inline-flex align-items-center gap-1">
          <i class="bi bi-camera-reels-fill text-primary"></i> Real Stories
        </span>
        <h2 class="mb-0 fw-bold">Customer Experiences</h2>
      </div>
      <div class="text-muted small d-none d-sm-flex align-items-center gap-1">
        <i class="bi bi-patch-check-fill text-success fs-5"></i>
        <span>100% Verified Homeowners</span>
      </div>
    </div>

    <!-- Reels Carousel Wrapper -->
    <div class="hiq-reels-wrapper position-relative">
      <!-- Previous Arrow -->
      <button class="hiq-reel-nav-btn prev-btn" id="hiqReelPrev" type="button" aria-label="Previous reels">
        <i class="bi bi-chevron-left"></i>
      </button>

      <!-- Reels Track -->
      <div class="hiq-reels-track" id="hiqReelsTrack">
        <?php foreach ($videoTestimonials as $vid): 
          $vidSource = !empty($vid['video_path']) ? upload_url($vid['video_path']) : ($vid['video_url'] ?? '');
          $thumbSource = !empty($vid['thumbnail_path']) ? upload_url($vid['thumbnail_path']) : '';
          $name = $vid['customer_name'] ?? 'Homeowner';
          $desc = $vid['description'] ?? ($vid['review_text'] ?? ($vid['title'] ?? ''));
        ?>
          <div class="hiq-reel-item">
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

      <!-- Next Arrow -->
      <button class="hiq-reel-nav-btn next-btn" id="hiqReelNext" type="button" aria-label="Next reels">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var track = document.getElementById('hiqReelsTrack');
  var prevBtn = document.getElementById('hiqReelPrev');
  var nextBtn = document.getElementById('hiqReelNext');

  if (track && prevBtn && nextBtn) {
    prevBtn.addEventListener('click', function () {
      track.scrollBy({ left: -320, behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', function () {
      track.scrollBy({ left: 320, behavior: 'smooth' });
    });
  }

  var cards = document.querySelectorAll('.hiq-reel-card');
  cards.forEach(function (card) {
    var video = card.querySelector('video');
    var playBtn = card.querySelector('.hiq-reel-play-btn');
    var muteBtn = card.querySelector('.hiq-reel-mute-btn');
    var muteIcon = muteBtn ? muteBtn.querySelector('i') : null;

    if (!video) return;

    // Start video muted so browser allows smooth playback
    video.muted = true;

    var togglePlay = function (e) {
      // Don't toggle play if sound button was clicked
      if (e && e.target && muteBtn && (muteBtn === e.target || muteBtn.contains(e.target))) {
        return;
      }

      if (video.paused) {
        // Pause any other playing video cards
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
          <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Videos in Admin (0 / 4)
        </a>
      </div>
    </div>
  </section>
<?php endif; ?>