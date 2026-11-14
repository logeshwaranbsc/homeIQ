<?php
declare(strict_types=1);

/** @var array $settings */
$announcementsText = is_array($settings['announcements'] ?? null) ? implode("\n", $settings['announcements']) : '';
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Store & Homepage Settings</h4>
    <small class="text-muted">Brand identity, contact info, shipping rules, and customizable homepage editorial sections.</small>
  </div>
  <div>
    <a href="<?= url('/') ?>" target="_blank" class="btn btn-outline-dark btn-sm">
      <i class="bi bi-box-arrow-up-right me-1"></i> View Live Storefront
    </a>
  </div>
</div>

<form action="<?= url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <!-- Settings Nav Tabs -->
  <ul class="nav nav-pills mb-4 gap-2" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-bold px-3 py-2" id="tab-about-hero-btn" data-bs-toggle="pill" data-bs-target="#tab-about-hero" type="button" role="tab">
        <i class="bi bi-info-circle me-1 text-primary"></i> About Page Hero Section
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold px-3 py-2" id="tab-promo-btn" data-bs-toggle="pill" data-bs-target="#tab-promo" type="button" role="tab">
        <i class="bi bi-tag-fill me-1 text-primary"></i> VIP Promo Campaign Banner
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold px-3 py-2" id="tab-store-btn" data-bs-toggle="pill" data-bs-target="#tab-store" type="button" role="tab">
        <i class="bi bi-shop me-1 text-primary"></i> Store Identity & Shipping
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold px-3 py-2" id="tab-ann-btn" data-bs-toggle="pill" data-bs-target="#tab-ann" type="button" role="tab">
        <i class="bi bi-megaphone me-1 text-primary"></i> Top Announcement Bar
      </button>
    </li>
  </ul>

  <div class="tab-content" id="settingsTabContent">

    <!-- ========================================== -->
    <!-- TAB 1: ABOUT PAGE HERO SECTION             -->
    <!-- ========================================== -->
    <div class="tab-pane fade show active" id="tab-about-hero" role="tabpanel" aria-labelledby="tab-about-hero-btn">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom flex-wrap gap-2">
          <div>
            <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
              <span class="badge bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-info-circle fs-6"></i></span>
              About Page Editorial Hero Section
            </h5>
            <small class="text-muted">Customize the featured editorial hero banner on the About page (/about) with headline, orange highlight, feature chips, buttons, and showcase hardware.</small>
          </div>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="about_hero_enabled" value="1" id="aboutHeroEnabledSwitch" <?= !empty($settings['about_hero_enabled'] ?? ($settings['story_enabled'] ?? true)) ? 'checked' : '' ?>>
            <label class="form-check-label fs-6 fw-bold" for="aboutHeroEnabledSwitch">Display on About Page</label>
          </div>
        </div>

        <div class="row g-4">
          <!-- Left: Texts & Copy -->
          <div class="col-lg-7">
            <!-- Badge & Icon -->
            <div class="row g-3 mb-3">
              <div class="col-md-7">
                <label class="form-label fw-bold small text-uppercase">Section Badge Tag</label>
                <input type="text" name="about_hero_badge" class="form-control" value="<?= e($settings['about_hero_badge'] ?? ($settings['story_badge'] ?? 'Architectural Security')) ?>" placeholder="e.g. Architectural Security">
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold small text-uppercase">Badge Icon</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi <?= e($settings['about_hero_badge_icon'] ?? ($settings['story_badge_icon'] ?? 'bi-shield-lock-fill')) ?>"></i></span>
                  <input type="text" name="about_hero_badge_icon" class="form-control" value="<?= e($settings['about_hero_badge_icon'] ?? ($settings['story_badge_icon'] ?? 'bi-shield-lock-fill')) ?>" placeholder="e.g. bi-shield-lock-fill">
                </div>
              </div>
            </div>

            <!-- Headline: Main Text + Orange Highlight -->
            <div class="row g-3 mb-3">
              <div class="col-md-7">
                <label class="form-label fw-bold small text-uppercase">Headline Prefix *</label>
                <input type="text" name="about_hero_title" class="form-control fw-bold" value="<?= e($settings['about_hero_title'] ?? 'Engineered for Security.') ?>" required placeholder="e.g. Engineered for Security.">
                <small class="text-muted">Standard bold white headline text.</small>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold small text-uppercase">Headline Highlight</label>
                <input type="text" name="about_hero_title_highlight" class="form-control fw-bold text-primary" value="<?= e($settings['about_hero_title_highlight'] ?? 'Designed for Living.') ?>" placeholder="e.g. Designed for Living.">
                <small class="text-muted">Rendered with vibrant orange gradient accent.</small>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase">Hero Subtitle / Description Paragraph</label>
              <textarea name="about_hero_description" rows="3" class="form-control" placeholder="Describe the mission, security philosophy, and architectural vision..."><?= e($settings['about_hero_description'] ?? ($settings['story_description'] ?? 'HOMEIQ was conceived to bring uncompromising biometric door security and refined architectural elegance to modern residences.')) ?></textarea>
            </div>

            <!-- 3 Feature Chips -->
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center justify-content-between">
              <span><i class="bi bi-tag-fill text-primary me-1"></i> 3 Feature Highlight Chips</span>
              <small class="text-muted fw-normal" style="font-size: 11px;">Displayed beneath hero description</small>
            </h6>
            <div class="row g-2 mb-4">
              <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 bg-light h-100">
                  <div class="fw-bold small text-primary mb-2 d-flex align-items-center gap-1">
                    <i class="bi bi-1-circle-fill"></i> Chip 1
                  </div>
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Feature Label</label>
                  <input type="text" name="about_hero_chip1_text" class="form-control form-control-sm fw-bold mb-2" placeholder="3D AI Facial Biometrics" value="<?= e($settings['about_hero_chip1_text'] ?? '3D AI Facial Biometrics') ?>">
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Icon Class</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi <?= e($settings['about_hero_chip1_icon'] ?? 'bi-cpu-fill text-warning') ?>"></i></span>
                    <input type="text" name="about_hero_chip1_icon" class="form-control form-control-sm text-muted" placeholder="bi-cpu-fill text-warning" value="<?= e($settings['about_hero_chip1_icon'] ?? 'bi-cpu-fill text-warning') ?>">
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 bg-light h-100">
                  <div class="fw-bold small text-primary mb-2 d-flex align-items-center gap-1">
                    <i class="bi bi-2-circle-fill"></i> Chip 2
                  </div>
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Feature Label</label>
                  <input type="text" name="about_hero_chip2_text" class="form-control form-control-sm fw-bold mb-2" placeholder="304 Stainless Steel Mortise" value="<?= e($settings['about_hero_chip2_text'] ?? '304 Stainless Steel Mortise') ?>">
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Icon Class</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi <?= e($settings['about_hero_chip2_icon'] ?? 'bi-shield-check text-success') ?>"></i></span>
                    <input type="text" name="about_hero_chip2_icon" class="form-control form-control-sm text-muted" placeholder="bi-shield-check text-success" value="<?= e($settings['about_hero_chip2_icon'] ?? 'bi-shield-check text-success') ?>">
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 bg-light h-100">
                  <div class="fw-bold small text-primary mb-2 d-flex align-items-center gap-1">
                    <i class="bi bi-3-circle-fill"></i> Chip 3
                  </div>
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Feature Label</label>
                  <input type="text" name="about_hero_chip3_text" class="form-control form-control-sm fw-bold mb-2" placeholder="Bank-Grade Local AES-256" value="<?= e($settings['about_hero_chip3_text'] ?? 'Bank-Grade Local AES-256') ?>">
                  <label class="form-label small text-muted mb-1" style="font-size: 11px;">Icon Class</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi <?= e($settings['about_hero_chip3_icon'] ?? 'bi-fingerprint text-info') ?>"></i></span>
                    <input type="text" name="about_hero_chip3_icon" class="form-control form-control-sm text-muted" placeholder="bi-fingerprint text-info" value="<?= e($settings['about_hero_chip3_icon'] ?? 'bi-fingerprint text-info') ?>">
                  </div>
                </div>
              </div>
            </div>

            <!-- Call to Action Buttons -->
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
              <i class="bi bi-hand-index-thumb-fill text-primary me-1"></i> Call to Action Buttons
            </h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Primary Button Text</label>
                <input type="text" name="about_hero_btn1_text" class="form-control mb-2" value="<?= e($settings['about_hero_btn1_text'] ?? ($settings['story_btn1_text'] ?? 'Explore Locks')) ?>">
                <label class="form-label small fw-bold mb-1">Primary Button URL</label>
                <input type="text" name="about_hero_btn1_url" class="form-control" value="<?= e($settings['about_hero_btn1_url'] ?? ($settings['story_btn1_url'] ?? 'shop')) ?>" placeholder="e.g. shop or /categories">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Secondary Button Text</label>
                <input type="text" name="about_hero_btn2_text" class="form-control mb-2" value="<?= e($settings['about_hero_btn2_text'] ?? ($settings['story_btn2_text'] ?? 'Get in Touch')) ?>">
                <label class="form-label small fw-bold mb-1">Secondary Button URL</label>
                <input type="text" name="about_hero_btn2_url" class="form-control" value="<?= e($settings['about_hero_btn2_url'] ?? ($settings['story_btn2_url'] ?? 'contact')) ?>" placeholder="e.g. contact or /about">
              </div>
            </div>
          </div>

          <!-- Right: Visual Media & Showcase Hardware Card -->
          <div class="col-lg-5">
            <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
              <div>
                <h6 class="fw-bold text-dark mb-3">
                  <i class="bi bi-image text-primary me-1"></i> Right Showcase Hardware Card
                </h6>

                <!-- Live WYSIWYG Hardware Card Preview -->
                <div class="mb-3">
                  <label class="form-label small fw-bold text-uppercase text-muted d-flex align-items-center justify-content-between mb-2">
                    <span>Live Hardware Card Preview</span>
                    <span class="badge bg-success-subtle text-success small"><i class="bi bi-check2 me-1"></i>100% Cover + Overlaid Metrics</span>
                  </label>
                  
                  <div class="admin-showcase-preview-wrapper" style="position: relative; width: 100%; height: 260px; border-radius: 20px; overflow: hidden; border: 1px solid rgba(254, 107, 37, 0.35); box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35); display: flex; flex-direction: column; justify-content: space-between; padding: 14px 16px; background: #0d0e12;">
                    <!-- 100% Full-Cover Image -->
                    <img id="adminShowcaseImg" src="<?= upload_url($settings['about_hero_image'] ?? ($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg')) ?>" alt="Showcase Preview" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; z-index: 1; transition: transform 0.4s ease;" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
                    
                    <!-- Gradient Scrim for Readability -->
                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10, 11, 14, 0.72) 0%, rgba(10, 11, 14, 0.05) 30%, rgba(10, 11, 14, 0.1) 65%, rgba(10, 11, 14, 0.82) 100%); z-index: 2; pointer-events: none;"></div>
                    
                    <!-- Top Bar Badges Overlaid -->
                    <div class="d-flex align-items-center justify-content-between gap-2" style="position: relative; z-index: 3;">
                      <span id="adminPreviewSeriesChip" style="background: rgba(11, 12, 16, 0.72); border: 1px solid rgba(254, 107, 37, 0.35); color: #ffffff; border-radius: 50px; padding: 5px 12px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; backdrop-filter: blur(8px); max-width: 65%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <i class="bi bi-patch-check-fill text-warning"></i> <span><?= e($settings['about_hero_card_badge'] ?? 'Flagship Biometric Series') ?></span>
                      </span>
                      <span id="adminPreviewStatusPill" style="font-size: 0.72rem; font-weight: 700; color: #10b981; display: inline-flex; align-items: center; gap: 6px; background: rgba(11, 12, 16, 0.72); border: 1px solid rgba(16, 185, 129, 0.3); padding: 5px 10px; border-radius: 50px; backdrop-filter: blur(8px);">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; box-shadow: 0 0 6px #10b981; display: inline-block;"></span> <span><?= e($settings['about_hero_card_status'] ?? 'Live Biometrics') ?></span>
                      </span>
                    </div>

                    <!-- Bottom Footer Durability Pill Overlaid -->
                    <div class="text-center" style="position: relative; z-index: 3;">
                      <small id="adminPreviewFooterPill" style="background: rgba(11, 12, 16, 0.75); border: 1px solid rgba(255, 255, 255, 0.14); padding: 5px 14px; border-radius: 50px; backdrop-filter: blur(8px); color: #ffffff; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-check-circle-fill text-success"></i> <span><?= e($settings['about_hero_card_footer'] ?? ($settings['story_pill_text'] ?? '500,000 Cycle Durability • IP65 Weatherproof')) ?></span>
                      </small>
                    </div>
                  </div>

                  <div class="d-flex align-items-center justify-content-between mt-2 px-1">
                    <small class="text-muted" style="font-size: 11px;">Current: <code class="text-primary"><?= e($settings['about_hero_image'] ?? ($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg')) ?></code></small>
                    <small class="text-success" style="font-size: 11px;"><i class="bi bi-eye me-1"></i>Matches live storefront</small>
                  </div>
                </div>

                <!-- Card Top Badges Inputs -->
                <div class="row g-2 mb-3">
                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Top Series Chip Text</label>
                    <input type="text" id="inputCardBadge" name="about_hero_card_badge" class="form-control form-control-sm" value="<?= e($settings['about_hero_card_badge'] ?? 'Flagship Biometric Series') ?>" placeholder="e.g. Flagship Biometric Series">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Top Status Pill Text</label>
                    <input type="text" id="inputCardStatus" name="about_hero_card_status" class="form-control form-control-sm" value="<?= e($settings['about_hero_card_status'] ?? 'Live Biometrics') ?>" placeholder="e.g. Live Biometrics">
                  </div>
                </div>

                <!-- Footer Pill Input -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Footer Durability / Spec Text</label>
                  <input type="text" id="inputCardFooter" name="about_hero_card_footer" class="form-control form-control-sm" value="<?= e($settings['about_hero_card_footer'] ?? ($settings['story_pill_text'] ?? '500,000 Cycle Durability • IP65 Weatherproof')) ?>" placeholder="e.g. 500,000 Cycle Durability • IP65 Weatherproof">
                </div>

                <!-- Upload New Image -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Upload New Lock Image</label>
                  <input type="file" id="inputCardImageFile" name="about_hero_image_file" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted" style="font-size: 11px;">Accepts PNG, JPG, WEBP, or SVG (Max 500MB). Replaces image on preview immediately.</small>
                </div>

                <!-- Or Specify Relative Path -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Or Relative Image Path / URL</label>
                  <input type="text" id="inputCardImagePath" name="about_hero_image" class="form-control form-control-sm" value="<?= e($settings['about_hero_image'] ?? ($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg')) ?>">
                </div>

                <!-- See HOMEIQ in Action Video Demo URL -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">See HOMEIQ in Action — Video Demo URL / Path</label>
                  <input type="text" name="brand_video_url" class="form-control form-control-sm" value="<?= e($settings['brand_video_url'] ?? 'https://res.cloudinary.com/uep3k36v/video/upload/v1794397919/Untitled_video_gwr_video_mvp.mp4') ?>" placeholder="https://... or video_testimonials/demo.mp4">
                  <small class="text-muted" style="font-size: 11px;">Video displayed in the "See HOMEIQ in Action" spotlight section on the homepage.</small>
                </div>
              </div>

              <div class="p-3 bg-white rounded border mt-3">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                  <i class="bi bi-eye-fill"></i> Storefront Location
                </div>
                <div class="text-muted" style="font-size: 12px;">
                  This editorial hero section is featured exclusively at the top of <a href="<?= url('about') ?>" target="_blank" class="text-primary fw-semibold text-decoration-none">/about (About Us)</a>. Updates take effect immediately.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB: VIP PROMOTIONAL CAMPAIGN BANNER       -->
    <!-- ========================================== -->
    <div class="tab-pane fade" id="tab-promo" role="tabpanel" aria-labelledby="tab-promo-btn">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom flex-wrap gap-2">
          <div>
            <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
              <span class="badge bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-tag-fill fs-6"></i></span>
              VIP Privilege Campaign & Promotional Banner
            </h5>
            <small class="text-muted">Customize the promotional campaign banner with coupon discounts, doorstep installation notices, and featured lock image.</small>
          </div>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="promo_enabled" value="1" id="promoEnabledSwitch" <?= !empty($settings['promo_enabled']) ? 'checked' : '' ?>>
            <label class="form-check-label fs-6 fw-bold" for="promoEnabledSwitch">Display Banner on Homepage</label>
          </div>
        </div>

        <div class="row g-4">
          <!-- Left: Texts & Copy -->
          <div class="col-lg-7">
            <!-- Visual Theme Style -->
            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Banner Visual Theme</label>
              <select name="promo_theme" class="form-select">
                <option value="dark" <?= ($settings['promo_theme'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>
                  Obsidian Carbon & Orange Glow (Dark High-Tech — Recommended)
                </option>
                <option value="light" <?= ($settings['promo_theme'] ?? '') === 'light' ? 'selected' : '' ?>>
                  Clean Studio Light (Warm Titanium & Orange Accents)
                </option>
              </select>
              <small class="text-muted">High-contrast obsidian black or clean titanium studio light.</small>
            </div>

            <!-- Badge & Title -->
            <div class="row g-3 mb-3">
              <div class="col-md-7">
                <label class="form-label fw-bold small text-uppercase">Banner Badge Tag</label>
                <input type="text" name="promo_badge" class="form-control" value="<?= e($settings['promo_badge'] ?? 'VIP PRIVILEGE CAMPAIGN') ?>" placeholder="e.g. VIP PRIVILEGE CAMPAIGN">
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold small text-uppercase">Badge Icon</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi <?= e($settings['promo_badge_icon'] ?? 'bi-tag-fill') ?>"></i></span>
                  <input type="text" name="promo_badge_icon" class="form-control" value="<?= e($settings['promo_badge_icon'] ?? 'bi-tag-fill') ?>" placeholder="e.g. bi-tag-fill, bi-gift">
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Campaign Headline *</label>
              <input type="text" name="promo_title" class="form-control fw-bold" value="<?= e($settings['promo_title'] ?? 'Upgrade to Keyless Living with Coupon WELCOME10') ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Highlight Coupon Code</label>
              <input type="text" name="promo_coupon_code" class="form-control fw-bold" value="<?= e($settings['promo_coupon_code'] ?? 'WELCOME10') ?>" placeholder="e.g. WELCOME10">
              <small class="text-muted">Customers can click on this code directly on the homepage to copy it.</small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase">Promotional Description</label>
              <textarea name="promo_description" rows="3" class="form-control" placeholder="Describe the offer benefits, discounts, or installation perks..."><?= e($settings['promo_description'] ?? '') ?></textarea>
            </div>

            <!-- Call to Action Button & Guarantee Note -->
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">CTA Button Text</label>
                <input type="text" name="promo_btn_text" class="form-control mb-2" value="<?= e($settings['promo_btn_text'] ?? 'Claim Offer & Shop Now') ?>">
                <label class="form-label small fw-bold mb-1">CTA Destination URL</label>
                <input type="text" name="promo_btn_url" class="form-control" value="<?= e($settings['promo_btn_url'] ?? 'shop') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Benefit Note / Security Check</label>
                <textarea name="promo_note" rows="3" class="form-control" placeholder="e.g. Valid across all AI face, palm vein & fingerprint series."><?= e($settings['promo_note'] ?? 'Valid across all AI face, palm vein & fingerprint series.') ?></textarea>
              </div>
            </div>
          </div>

          <!-- Right: Visual Media & Showcase -->
          <div class="col-lg-5">
            <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
              <div>
                <h6 class="fw-bold text-dark mb-3">
                  <i class="bi bi-image text-primary me-1"></i> Promotional Lock Showcase
                </h6>

                <!-- Current Image Preview -->
                <div class="text-center p-3 mb-3 bg-white border rounded-3 shadow-sm position-relative">
                  <img src="<?= upload_url($settings['promo_image'] ?? 'products/video-door-lock.svg') ?>" alt="Promo Preview" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
                  <div class="small text-muted mt-2">
                    Current: <code><?= e($settings['promo_image'] ?? 'products/video-door-lock.svg') ?></code>
                  </div>
                </div>

                <!-- Upload New Image -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Upload New Lock Image</label>
                  <input type="file" name="promo_image_file" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted">Accepts PNG, JPG, WEBP, or SVG (Max 500MB).</small>
                </div>

                <!-- Or Specify Relative Path -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Or Relative Image Path / URL</label>
                  <input type="text" name="promo_image" class="form-control form-control-sm" value="<?= e($settings['promo_image'] ?? 'products/video-door-lock.svg') ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold">Floating Privilege Pill Text</label>
                  <input type="text" name="promo_pill_text" class="form-control" value="<?= e($settings['promo_pill_text'] ?? '10% Instant Privilege Discount') ?>" placeholder="e.g. 10% Instant Privilege Discount">
                </div>
              </div>

              <div class="p-3 bg-white rounded border mt-3">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                  <i class="bi bi-shield-check"></i> Clean High-Tech UI
                </div>
                <div class="text-muted" style="font-size: 12px;">
                  Styled in high-tech obsidian carbon with radiant orange highlights and zero dark blue.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB: STORE IDENTITY & SHIPPING             -->
    <!-- ========================================== -->
    <div class="tab-pane fade" id="tab-store" role="tabpanel" aria-labelledby="tab-store-btn">
      <div class="row g-4">
        <!-- Brand & Contact -->
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Brand & Store Identity</h6>

            <div class="mb-3">
              <label class="form-label">Store Brand Name *</label>
              <input type="text" name="store_name" class="form-control" required value="<?= e($settings['store_name'] ?? 'HOMEIQ') ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Brand Tagline</label>
              <input type="text" name="tagline" class="form-control" value="<?= e($settings['tagline'] ?? '') ?>">
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Support Email *</label>
                <input type="email" name="support_email" class="form-control" required value="<?= e($settings['support_email'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Support Phone Hotline *</label>
                <input type="tel" name="support_phone" class="form-control" required value="<?= e($settings['support_phone'] ?? '') ?>">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Headquarters / Experience Centre Address</label>
              <textarea name="address" rows="2" class="form-control"><?= e($settings['address'] ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <!-- Shipping & Checkout Rules -->
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Shipping & Payment Rules</h6>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Free Shipping Minimum (₹) *</label>
                <input type="number" step="0.01" name="free_shipping_min" class="form-control" required value="<?= e($settings['free_shipping_min'] ?? 5000) ?>">
                <small class="text-muted">Carts at or above this value get free shipping.</small>
              </div>
              <div class="col-md-6">
                <label class="form-label">Flat Shipping Rate (₹) *</label>
                <input type="number" step="0.01" name="flat_shipping_rate" class="form-control" required value="<?= e($settings['flat_shipping_rate'] ?? 250) ?>">
                <small class="text-muted">Applied when cart is below threshold.</small>
              </div>
            </div>

            <div class="form-check form-switch pt-2">
              <input class="form-check-input" type="checkbox" name="enable_cod" value="1" id="enableCodCheck" <?= !empty($settings['enable_cod']) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold" for="enableCodCheck">Enable Cash on Delivery (COD)</label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: ANNOUNCEMENT BAR                    -->
    <!-- ========================================== -->
    <div class="tab-pane fade" id="tab-ann" role="tabpanel" aria-labelledby="tab-ann-btn">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Top Announcement Bar (Header Banner)</h6>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="announcement_bar_enabled" value="1" id="annBarEnabled" <?= !empty($settings['announcement_bar_enabled']) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="annBarEnabled">Display Announcement Bar at Top of Website</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Announcements (One per line)</label>
          <textarea name="announcements" rows="5" class="form-control" placeholder="Free Installation on Eligible Products&#10;Free Shipping on Orders Above ₹5,000&#10;2-Year Manufacturer Warranty&#10;24/7 Technical Support"><?= e($announcementsText) ?></textarea>
          <small class="text-muted">Each line rotates automatically on the announcement bar.</small>
        </div>
      </div>
    </div>

  </div>

  <!-- Bottom Floating Save Bar -->
  <div class="p-3 bg-white rounded-4 border shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 sticky-bottom" style="bottom: 16px; z-index: 100;">
    <div class="small text-muted d-none d-md-block">
      <i class="bi bi-shield-check text-success me-1"></i> Changes will immediately take effect on your live storefront.
    </div>
    <button type="submit" class="btn btn-hiq-primary px-5 py-2 fw-bold shadow-sm ms-auto">
      <i class="bi bi-check2-circle me-1"></i> Save All Settings
    </button>
  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function activateHashTab() {
    if (window.location.hash) {
      const targetTabBtn = document.querySelector(`button[data-bs-target="${window.location.hash}"]`);
      if (targetTabBtn) {
        const tab = new bootstrap.Tab(targetTabBtn);
        tab.show();
      }
    }
  }
  activateHashTab();
  window.addEventListener('hashchange', activateHashTab);

  // Live Hardware Card Preview Updates
  const badgeInput = document.getElementById('inputCardBadge');
  const statusInput = document.getElementById('inputCardStatus');
  const footerInput = document.getElementById('inputCardFooter');
  const imgInput = document.getElementById('inputCardImageFile');
  const imgPathInput = document.getElementById('inputCardImagePath');
  
  const previewSeries = document.getElementById('adminPreviewSeriesChip');
  const previewStatus = document.getElementById('adminPreviewStatusPill');
  const previewFooter = document.getElementById('adminPreviewFooterPill');
  const previewImg = document.getElementById('adminShowcaseImg');

  if (badgeInput && previewSeries) {
    badgeInput.addEventListener('input', function () {
      const textSpan = previewSeries.querySelector('span');
      if (textSpan) textSpan.textContent = this.value || 'Flagship Biometric Series';
    });
  }

  if (statusInput && previewStatus) {
    statusInput.addEventListener('input', function () {
      const textSpan = previewStatus.querySelector('span');
      if (textSpan) textSpan.textContent = this.value || 'Live Biometrics';
    });
  }

  if (footerInput && previewFooter) {
    footerInput.addEventListener('input', function () {
      const textSpan = previewFooter.querySelector('span');
      if (textSpan) textSpan.textContent = this.value || '500,000 Cycle Durability • IP65 Weatherproof';
    });
  }

  if (imgInput && previewImg) {
    imgInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (imgPathInput && previewImg) {
    imgPathInput.addEventListener('input', function () {
      if (this.value) {
        previewImg.src = this.value.startsWith('http') ? this.value : '<?= url('uploads') ?>/' + this.value.replace(/^uploads\//, '');
      }
    });
  }
});
</script>
