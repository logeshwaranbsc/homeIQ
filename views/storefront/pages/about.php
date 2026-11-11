<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'About HOMEIQ', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Editorial Hero Section -->
  <div class="editorial-hero-card p-4 p-md-5 mb-5 text-white">
    <div class="row align-items-center g-4 g-lg-5">
      <div class="col-lg-7">
        <div class="mb-3">
          <span class="story-badge-pill">
            <i class="bi bi-shield-lock-fill"></i> Architectural Security
          </span>
        </div>
        <h1 class="display-5 fw-extrabold mb-3 text-white">
          Engineered for Security. <span class="story-title-highlight">Designed for Living.</span>
        </h1>
        <p class="story-subtitle text-light opacity-90 fs-5 mb-4">
          HOMEIQ was conceived to bring uncompromising biometric door security and refined architectural elegance to modern residences.
        </p>

        <!-- Feature Chips Matrix -->
        <div class="d-flex flex-wrap gap-2 mb-4">
          <div class="about-hero-chip">
            <i class="bi bi-cpu-fill text-warning"></i> 3D AI Facial Biometrics
          </div>
          <div class="about-hero-chip">
            <i class="bi bi-shield-check text-success"></i> 304 Stainless Steel Mortise
          </div>
          <div class="about-hero-chip">
            <i class="bi bi-fingerprint text-info"></i> Bank-Grade Local AES-256
          </div>
        </div>

        <!-- Action CTAs -->
        <div class="d-flex flex-wrap gap-2 gap-sm-3">
          <a href="<?= url('shop') ?>" class="btn btn-hiq-primary px-4 py-2 fs-6 fw-bold">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>Explore Locks
          </a>
          <a href="<?= url('contact') ?>" class="btn btn-hiq-glass px-4 py-2 fs-6 fw-semibold">
            <i class="bi bi-headset me-2"></i>Get in Touch
          </a>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="story-showcase-card shadow">
          <div class="story-card-top-bar">
            <span class="story-chip-badge">
              <i class="bi bi-patch-check-fill text-warning"></i> Flagship Biometric Series
            </span>
            <span class="story-chip-status">
              <span class="story-pulse-dot"></span> Live Biometrics
            </span>
          </div>

          <div class="story-card-body">
            <div class="story-halo-glow"></div>
            <img src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>" alt="HOMEIQ AI Face Recognition Smart Door Lock" class="story-lock-img img-fluid">
          </div>

          <div class="story-card-footer text-center">
            <small class="text-white-50 d-inline-flex align-items-center gap-1">
              <i class="bi bi-check-circle-fill text-success"></i> 500,000 Cycle Durability &bull; IP65 Weatherproof
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Philosophy & Principles -->
  <div class="row g-4 g-lg-5 mb-5 align-items-center">
    <div class="col-lg-6">
      <span class="badge bg-warning bg-opacity-10 text-primary border border-warning border-opacity-25 px-3 py-2 rounded-pill small fw-bold text-uppercase mb-2">
        <i class="bi bi-shield-shaded me-1"></i> Our Philosophy
      </span>
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
        <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-1.5 rounded-pill small fw-bold mb-2">
          <i class="bi bi-tools me-1"></i> Pan-India White-Glove Service
        </span>
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
