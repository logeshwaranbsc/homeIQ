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
      <button class="nav-link active fw-bold px-3 py-2" id="tab-story-btn" data-bs-toggle="pill" data-bs-target="#tab-story" type="button" role="tab">
        <i class="bi bi-cpu me-1 text-primary"></i> Brand Story Section
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
    <!-- TAB 1: HOMEPAGE BRAND STORY SECTION        -->
    <!-- ========================================== -->
    <div class="tab-pane fade show active" id="tab-story" role="tabpanel" aria-labelledby="tab-story-btn">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom flex-wrap gap-2">
          <div>
            <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
              <span class="badge bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-cpu fs-6"></i></span>
              Brand Editorial Story & Hardware Philosophy Section
            </h5>
            <small class="text-muted">Customize the featured story section on the homepage with your own headline, specs, buttons, and product image.</small>
          </div>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="story_enabled" value="1" id="storyEnabledSwitch" <?= !empty($settings['story_enabled']) ? 'checked' : '' ?>>
            <label class="form-check-label fs-6 fw-bold" for="storyEnabledSwitch">Display on Homepage</label>
          </div>
        </div>

        <div class="row g-4">
          <!-- Left: Texts & Copy -->
          <div class="col-lg-7">
            <!-- Visual Theme Style -->
            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Section Visual Theme</label>
              <select name="story_theme" class="form-select">
                <option value="dark" <?= ($settings['story_theme'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>
                  Obsidian Carbon & Orange Glow (Dark High-Tech — Recommended)
                </option>
                <option value="light" <?= ($settings['story_theme'] ?? '') === 'light' ? 'selected' : '' ?>>
                  Clean Studio Light (Warm Titanium & Orange Accents)
                </option>
              </select>
              <small class="text-muted">Choose between the high-tech obsidian carbon look or the clean studio light look.</small>
            </div>

            <!-- Badge & Title -->
            <div class="row g-3 mb-3">
              <div class="col-md-7">
                <label class="form-label fw-bold small text-uppercase">Section Badge Tag</label>
                <input type="text" name="story_badge" class="form-control" value="<?= e($settings['story_badge'] ?? 'HARDWARE PHILOSOPHY') ?>" placeholder="e.g. HARDWARE PHILOSOPHY">
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold small text-uppercase">Badge Bootstrap Icon</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi <?= e($settings['story_badge_icon'] ?? 'bi-cpu') ?>"></i></span>
                  <input type="text" name="story_badge_icon" class="form-control" value="<?= e($settings['story_badge_icon'] ?? 'bi-cpu') ?>" placeholder="e.g. bi-cpu, bi-shield-lock">
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Main Headline *</label>
              <input type="text" name="story_title" class="form-control fw-bold" value="<?= e($settings['story_title'] ?? 'A NEW STANDARD IN ARCHITECTURAL DEFENSE') ?>" required>
              <small class="text-muted">High-impact headline displayed in clean, bold typography.</small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase">Subheading / Philosophy Tagline</label>
              <input type="text" name="story_subtitle" class="form-control" value="<?= e($settings['story_subtitle'] ?? 'HOMEIQ — where intelligent engineering meets residential peace of mind.') ?>">
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase">Editorial Story Paragraph</label>
              <textarea name="story_description" rows="4" class="form-control" placeholder="Describe your hardware engineering, materials, and security craftsmanship..."><?= e($settings['story_description'] ?? '') ?></textarea>
            </div>

            <!-- 4 Spec Metric Bento Cards -->
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
              <i class="bi bi-grid-fill text-primary me-1"></i> 4 Spec Metric Cards (Bento Grid)
            </h6>
            <div class="row g-3 mb-4">
              <div class="col-sm-6 col-md-3">
                <div class="p-2 border rounded bg-light">
                  <label class="form-label small fw-bold mb-1">Metric 1</label>
                  <input type="text" name="story_metric1_val" class="form-control form-control-sm fw-bold mb-1" placeholder="<0.3s" value="<?= e($settings['story_metric1_val'] ?? '<0.3s') ?>">
                  <input type="text" name="story_metric1_label" class="form-control form-control-sm" placeholder="Sensor Scan" value="<?= e($settings['story_metric1_label'] ?? 'Sensor Scan') ?>">
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="p-2 border rounded bg-light">
                  <label class="form-label small fw-bold mb-1">Metric 2</label>
                  <input type="text" name="story_metric2_val" class="form-control form-control-sm fw-bold mb-1" placeholder="9-in-1" value="<?= e($settings['story_metric2_val'] ?? '9-in-1') ?>">
                  <input type="text" name="story_metric2_label" class="form-control form-control-sm" placeholder="Access Modes" value="<?= e($settings['story_metric2_label'] ?? 'Access Modes') ?>">
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="p-2 border rounded bg-light">
                  <label class="form-label small fw-bold mb-1">Metric 3</label>
                  <input type="text" name="story_metric3_val" class="form-control form-control-sm fw-bold mb-1" placeholder="304" value="<?= e($settings['story_metric3_val'] ?? '304') ?>">
                  <input type="text" name="story_metric3_label" class="form-control form-control-sm" placeholder="Steel Mortise" value="<?= e($settings['story_metric3_label'] ?? 'Steel Mortise') ?>">
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="p-2 border rounded bg-light">
                  <label class="form-label small fw-bold mb-1">Metric 4</label>
                  <input type="text" name="story_metric4_val" class="form-control form-control-sm fw-bold mb-1" placeholder="AES" value="<?= e($settings['story_metric4_val'] ?? 'AES') ?>">
                  <input type="text" name="story_metric4_label" class="form-control form-control-sm" placeholder="128-Bit Safe" value="<?= e($settings['story_metric4_label'] ?? '128-Bit Safe') ?>">
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
                <input type="text" name="story_btn1_text" class="form-control mb-2" value="<?= e($settings['story_btn1_text'] ?? 'Explore All Hardware') ?>">
                <label class="form-label small fw-bold mb-1">Primary Button URL</label>
                <input type="text" name="story_btn1_url" class="form-control" value="<?= e($settings['story_btn1_url'] ?? 'shop') ?>" placeholder="e.g. shop or /categories">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Secondary Button Text</label>
                <input type="text" name="story_btn2_text" class="form-control mb-2" value="<?= e($settings['story_btn2_text'] ?? 'Read Security Briefing') ?>">
                <label class="form-label small fw-bold mb-1">Secondary Button URL</label>
                <input type="text" name="story_btn2_url" class="form-control" value="<?= e($settings['story_btn2_url'] ?? 'about') ?>" placeholder="e.g. about or /contact">
              </div>
            </div>
          </div>

          <!-- Right: Visual Media & Showcase -->
          <div class="col-lg-5">
            <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
              <div>
                <h6 class="fw-bold text-dark mb-3">
                  <i class="bi bi-image text-primary me-1"></i> Featured Showcase Image
                </h6>

                <!-- Current Image Preview -->
                <div class="text-center p-3 mb-3 bg-white border rounded-3 shadow-sm position-relative">
                  <img src="<?= upload_url($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg') ?>" alt="Showcase Preview" class="img-fluid rounded" style="max-height: 240px; object-fit: contain;" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
                  <div class="small text-muted mt-2">
                    Current: <code><?= e($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg') ?></code>
                  </div>
                </div>

                <!-- Upload New Image -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Upload New Showcase Image</label>
                  <input type="file" name="story_image_file" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                  <small class="text-muted">Accepts PNG, JPG, WEBP, or SVG (Max 5MB).</small>
                </div>

                <!-- Or Specify Relative Path -->
                <div class="mb-3">
                  <label class="form-label small fw-bold">Or Relative Image Path / URL</label>
                  <input type="text" name="story_image" class="form-control form-control-sm" value="<?= e($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg') ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold">Floating Highlight Pill Text</label>
                  <input type="text" name="story_pill_text" class="form-control" value="<?= e($settings['story_pill_text'] ?? 'Aerospace 6063-T6 • 304 Stainless Steel Mortise') ?>" placeholder="e.g. Aerospace Grade • 304 Stainless Steel">
                </div>
              </div>

              <div class="p-3 bg-white rounded border mt-3">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                  <i class="bi bi-palette-fill"></i> Modern UI Applied
                </div>
                <div class="text-muted" style="font-size: 12px;">
                  The storefront section has been updated with a crisp, modern light-luxury aesthetic using brand orange (#FE6B25) accents. Dark blue has been completely removed.
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
                  <small class="text-muted">Accepts PNG, JPG, WEBP, or SVG (Max 5MB).</small>
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
  if (window.location.hash) {
    const targetTabBtn = document.querySelector(`button[data-bs-target="${window.location.hash}"]`);
    if (targetTabBtn) {
      const tab = new bootstrap.Tab(targetTabBtn);
      tab.show();
    }
  }
});
</script>
