<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'About HOMEIQ', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- Editorial Hero -->
  <div class="p-4 p-md-5 rounded-4 bg-dark text-white mb-5" style="background: linear-gradient(135deg, #0d0e12 0%, #1e222d 100%);">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="text-info fw-bold small text-uppercase letter-spacing-2">Architectural Security</span>
        <h1 class="display-5 fw-extrabold mb-3">Engineered for Security. Designed for Living.</h1>
        <p class="text-light opacity-90 fs-5 mb-4">
          HOMEIQ was conceived to bring uncompromising biometric door security and refined architectural elegance to modern residences.
        </p>
        <div class="d-flex gap-3">
          <a href="<?= url('shop') ?>" class="btn btn-info text-dark fw-bold px-4">Explore Locks</a>
          <a href="<?= url('contact') ?>" class="btn btn-outline-light px-4">Get in Touch</a>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <img src="<?= upload_url('products/homeiq-ai-face-lock.svg') ?>" alt="HOMEIQ Smart Security Lock" class="img-fluid rounded-4 shadow" style="max-height: 380px;">
      </div>
    </div>
  </div>

  <!-- Philosophy & Principles -->
  <div class="row g-5 mb-5 align-items-center">
    <div class="col-lg-6">
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Our Philosophy</span>
      <h2 class="fw-bold mb-3">Keyless Access Without Vulnerability</h2>
      <p class="text-muted leading-relaxed mb-3">
        A smart door lock must first and foremost be an impenetrable mechanical barrier. We never sacrifice physical latch resilience for electronic gimmicks. Every HOMEIQ lock is anchored by hardened 304 stainless steel mortises and anti-tamper deadbolts.
      </p>
      <p class="text-muted leading-relaxed mb-4">
        On top of this physical foundation, we layer semiconductor biometric sensors, contactless palm vein detection, and 3D infrared face recognition algorithms that work reliably in fractions of a second.
      </p>
      <div class="row g-3">
        <div class="col-6">
          <div class="border rounded p-3">
            <h4 class="fw-bold text-primary m-0">&lt; 0.5s</h4>
            <small class="text-muted">Biometric Recognition</small>
          </div>
        </div>
        <div class="col-6">
          <div class="border rounded p-3">
            <h4 class="fw-bold text-primary m-0">9-in-1</h4>
            <small class="text-muted">Flexible Access Methods</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border p-4 bg-light shadow-sm">
        <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Our Security Commitments</h5>
        <ul class="list-unstyled d-flex flex-column gap-3 text-secondary small m-0">
          <li class="d-flex gap-2">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div><strong>True Multi-Factor Verification:</strong> Combine palm vein or facial recognition with passcodes for maximum perimeter defense.</div>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div><strong>Zero Cloud Dependency for Local Access:</strong> Your fingerprints and facial biometric templates are stored encrypted locally on the secure element chip inside the lock body.</div>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div><strong>End-to-End Installation Assurance:</strong> Certified technicians conduct door pre-checks, precision chisel routing, and full mobile pairing before signoff.</div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Installation and After-Sales -->
  <div class="card border rounded-3 p-4 p-md-5 mb-5 bg-white shadow-sm">
    <div class="row g-4 align-items-center">
      <div class="col-md-8">
        <h3 class="fw-bold mb-2">Certified Doorstep Installation & Technical Support</h3>
        <p class="text-muted mb-0">
          Our dedicated smart lock specialists assist you from initial door compatibility verification to post-installation onboarding and warranty registration.
        </p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="<?= url('contact') ?>" class="btn btn-hiq-primary">Schedule Consultation</a>
      </div>
    </div>
  </div>
</div>

