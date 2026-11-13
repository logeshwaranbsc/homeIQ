<?php
declare(strict_types=1);

use App\Core\View;
use App\Repositories\SettingsRepository;

$settings = $settings ?? (new SettingsRepository())->all();
$aboutHeroEnabled = !empty($settings['about_hero_enabled'] ?? ($settings['story_enabled'] ?? true));

$badgeTag = $settings['about_hero_badge'] ?? 'Architectural Security';
$badgeIcon = $settings['about_hero_badge_icon'] ?? 'bi-shield-lock-fill';

$titlePrefix = $settings['about_hero_title'] ?? 'Engineered for Security.';
$titleHighlight = $settings['about_hero_title_highlight'] ?? 'Designed for Living.';

$description = $settings['about_hero_description'] ?? 'HOMEIQ was conceived to bring uncompromising biometric door security and refined architectural elegance to modern residences.';

$chip1Text = $settings['about_hero_chip1_text'] ?? '3D AI Facial Biometrics';
$chip1Icon = $settings['about_hero_chip1_icon'] ?? 'bi-cpu-fill text-warning';

$chip2Text = $settings['about_hero_chip2_text'] ?? '304 Stainless Steel Mortise';
$chip2Icon = $settings['about_hero_chip2_icon'] ?? 'bi-shield-check text-success';

$chip3Text = $settings['about_hero_chip3_text'] ?? 'Bank-Grade Local AES-256';
$chip3Icon = $settings['about_hero_chip3_icon'] ?? 'bi-fingerprint text-info';

$btn1Text = $settings['about_hero_btn1_text'] ?? ($settings['story_btn1_text'] ?? 'Explore Locks');
$btn1Url = $settings['about_hero_btn1_url'] ?? ($settings['story_btn1_url'] ?? 'shop');

$btn2Text = $settings['about_hero_btn2_text'] ?? ($settings['story_btn2_text'] ?? 'Get in Touch');
$btn2Url = $settings['about_hero_btn2_url'] ?? ($settings['story_btn2_url'] ?? 'contact');

$cardBadge = $settings['about_hero_card_badge'] ?? 'Flagship Biometric Series';
$cardStatus = $settings['about_hero_card_status'] ?? 'Live Biometrics';
$cardImage = $settings['about_hero_image'] ?? ($settings['story_image'] ?? 'products/homeiq-ai-face-lock.svg');
$cardFooter = $settings['about_hero_card_footer'] ?? ($settings['story_pill_text'] ?? '500,000 Cycle Durability • IP65 Weatherproof');

$breadcrumbs = [
    ['label' => 'About HOMEIQ', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Editorial Hero Section -->
  <?php if ($aboutHeroEnabled): ?>
  <div class="editorial-hero-card p-4 p-md-5 mb-5 text-white">
    <div class="row align-items-center g-4 g-lg-5">
      <div class="col-lg-7">
        <?php if (!empty($badgeTag)): ?>
          <div class="mb-3">
            <span class="story-badge-pill">
              <?php if (!empty($badgeIcon)): ?><i class="bi <?= e($badgeIcon) ?>"></i><?php endif; ?> <?= e($badgeTag) ?>
            </span>
          </div>
        <?php endif; ?>
        <h1 class="display-5 fw-extrabold mb-3 text-white">
          <?= e($titlePrefix) ?> <?php if (!empty($titleHighlight)): ?><span class="story-title-highlight"><?= e($titleHighlight) ?></span><?php endif; ?>
        </h1>
        <?php if (!empty($description)): ?>
          <p class="story-subtitle text-light opacity-90 fs-5 mb-4">
            <?= nl2br(e($description)) ?>
          </p>
        <?php endif; ?>

        <!-- Feature Chips Matrix -->
        <?php if (!empty($chip1Text) || !empty($chip2Text) || !empty($chip3Text)): ?>
          <div class="d-flex flex-wrap gap-2 mb-4">
            <?php if (!empty($chip1Text)): ?>
              <div class="about-hero-chip">
                <?php if (!empty($chip1Icon)): ?><i class="bi <?= e($chip1Icon) ?>"></i><?php endif; ?> <?= e($chip1Text) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($chip2Text)): ?>
              <div class="about-hero-chip">
                <?php if (!empty($chip2Icon)): ?><i class="bi <?= e($chip2Icon) ?>"></i><?php endif; ?> <?= e($chip2Text) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($chip3Text)): ?>
              <div class="about-hero-chip">
                <?php if (!empty($chip3Icon)): ?><i class="bi <?= e($chip3Icon) ?>"></i><?php endif; ?> <?= e($chip3Text) ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Action CTAs -->
        <?php if (!empty($btn1Text) || !empty($btn2Text)): ?>
          <div class="d-flex flex-wrap gap-2 gap-sm-3">
            <?php if (!empty($btn1Text)): ?>
              <a href="<?= url($btn1Url) ?>" class="btn btn-hiq-primary px-4 py-2 fs-6 fw-bold">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i><?= e($btn1Text) ?>
              </a>
            <?php endif; ?>
            <?php if (!empty($btn2Text)): ?>
              <a href="<?= url($btn2Url) ?>" class="btn btn-hiq-glass px-4 py-2 fs-6 fw-semibold">
                <i class="bi bi-headset me-2"></i><?= e($btn2Text) ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="col-lg-5">
        <div class="story-showcase-card shadow">
          <div class="story-card-top-bar">
            <?php if (!empty($cardBadge)): ?>
              <span class="story-chip-badge">
                <i class="bi bi-patch-check-fill text-warning"></i> <?= e($cardBadge) ?>
              </span>
            <?php endif; ?>
            <?php if (!empty($cardStatus)): ?>
              <span class="story-chip-status">
                <span class="story-pulse-dot"></span> <?= e($cardStatus) ?>
              </span>
            <?php endif; ?>
          </div>

          <div class="story-card-body">
            <div class="story-halo-glow"></div>
            <img src="<?= upload_url($cardImage) ?>" alt="<?= e($titlePrefix) ?>" class="story-lock-img">
          </div>

          <?php if (!empty($cardFooter)): ?>
            <div class="story-card-footer text-center">
              <small class="text-white-50 d-inline-flex align-items-center gap-1">
                <i class="bi bi-check-circle-fill text-success"></i> <?= e($cardFooter) ?>
              </small>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Philosophy & Principles -->
  <div class="row g-4 g-lg-5 mb-5 align-items-center">
    <div class="col-lg-6">
      <div class="mb-3">
        <span class="about-philosophy-badge">
          <i class="bi bi-shield-shaded"></i> Our Philosophy
        </span>
      </div>
      <h2 class="fw-bold mb-3 brand-font">Keyless Access Without Vulnerability</h2>
      <p class="text-secondary leading-relaxed mb-3">
        A smart door lock must first and foremost be an impenetrable mechanical barrier. We never sacrifice physical latch resilience for electronic gimmicks. Every HOMEIQ lock is anchored by hardened 304 stainless steel mortises and anti-tamper deadbolts.
      </p>
      <p class="text-secondary leading-relaxed mb-4">
        On top of this physical foundation, we layer semiconductor biometric sensors, contactless palm vein detection, and 3D infrared face recognition algorithms that work reliably in fractions of a second.
      </p>

      <div class="row g-3">
        <div class="col-sm-4 col-6">
          <div class="about-stat-card">
            <div class="spec-metric-val">&lt; 0.5s</div>
            <div class="spec-metric-label">Biometric Scan</div>
          </div>
        </div>
        <div class="col-sm-4 col-6">
          <div class="about-stat-card">
            <div class="spec-metric-val">9-in-1</div>
            <div class="spec-metric-label">Access Modes</div>
          </div>
        </div>
        <div class="col-sm-4 col-12">
          <div class="about-stat-card">
            <div class="spec-metric-val">100%</div>
            <div class="spec-metric-label">Local Encryption</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="about-commitments-card">
        <h5 class="fw-bold mb-4 brand-font d-flex align-items-center gap-2">
          <i class="bi bi-shield-check text-primary fs-4"></i> Architectural Security Commitments
        </h5>
        <ul class="list-unstyled d-flex flex-column gap-3 text-secondary small m-0">
          <li class="d-flex gap-3 align-items-start">
            <div class="rounded-circle bg-success bg-opacity-10 text-success p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
              <i class="bi bi-check2"></i>
            </div>
            <div>
              <strong class="text-dark d-block mb-1">True Multi-Factor Verification:</strong>
              Combine palm vein or facial recognition with passcodes and NFC cards for maximum perimeter defense.
            </div>
          </li>
          <li class="d-flex gap-3 align-items-start">
            <div class="rounded-circle bg-success bg-opacity-10 text-success p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
              <i class="bi bi-check2"></i>
            </div>
            <div>
              <strong class="text-dark d-block mb-1">Zero Cloud Dependency for Biometrics:</strong>
              Fingerprints and facial biometric vectors are stored encrypted strictly inside the local secure element hardware chip.
            </div>
          </li>
          <li class="d-flex gap-3 align-items-start">
            <div class="rounded-circle bg-success bg-opacity-10 text-success p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
              <i class="bi bi-check2"></i>
            </div>
            <div>
              <strong class="text-dark d-block mb-1">Certified White-Glove Installation:</strong>
              Technicians perform laser door alignment, chisel routing, mortise fitting, and comprehensive mobile pairing before handover.
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Installation and After-Sales -->
  <div class="about-cta-banner mb-5">
    <div class="row g-4 align-items-center">
      <div class="col-lg-8">
        <div class="mb-3">
          <span class="about-service-badge">
            <i class="bi bi-tools"></i> Pan-India White-Glove Service
          </span>
        </div>
        <h3 class="fw-bold mb-2 text-white brand-font">Certified Doorstep Installation & Technical Support</h3>
        <p class="text-light opacity-75 mb-0 fs-6">
          Our dedicated smart lock specialists assist you from door compatibility verification to post-installation onboarding and full 3-year warranty protection.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?= url('contact') ?>" class="btn btn-hiq-primary px-4 py-2 fs-6 fw-bold">
          <i class="bi bi-calendar-check me-2"></i>Schedule Consultation
        </a>
      </div>
    </div>
  </div>
</div>
