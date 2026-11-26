<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'About HOMEIQ', 'url' => null]
];
?>
<div class="container py-3 py-md-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <!-- 1. Hero Section -->
  <div class="about-hero-card mb-5">
    <!-- Full-bleed background image -->
    <img src="<?= asset('assets/images/Modern HomeIQ Residence at Dusk.png') ?>" alt="HOMEIQ Luxury Smart Living" class="about-hero-bg-img">

    <!-- Foreground Content -->
    <div class="about-hero-content">
      <div class="mb-3">
        <span class="about-badge-pill">
          <i class="bi bi-geo-alt-fill text-warning"></i> ABOUT HOMEIQ
        </span>
      </div>
      <h1 class="about-hero-heading text-white mb-3">
        Building a Smarter<br>
        <span class="text-orange">Way of Living.</span>
      </h1>
      <p class="about-hero-sub text-white-50 mb-4">
        At HOMEIQ, we believe technology should make everyday living simpler, safer and more convenient.
      </p>
      <div class="d-flex flex-wrap gap-2">
        <span class="about-hero-tag">
          <i class="bi bi-lock-fill text-warning"></i> Secure Homes
        </span>
        <span class="about-hero-tag">
          <i class="bi bi-layers-fill text-warning"></i> Smart Living
        </span>
        <span class="about-hero-tag">
          <i class="bi bi-lightning-charge-fill text-warning"></i> Everyday Convenience
        </span>
      </div>
    </div>
  </div>

  <!-- 2. Our Story & Our Journey -->
  <div class="row g-4 mb-5 align-items-stretch">
    <!-- Left: Our Story -->
    <div class="col-lg-5 d-flex flex-column justify-content-center">
      <div class="about-section-dash mb-2">Our Story</div>
      <h2 class="about-section-heading mb-3">From Coimbatore to<br>Homes Across India</h2>
      <p class="text-secondary small leading-relaxed mb-3">
        Born in Coimbatore, Tamil Nadu, HOMEIQ began with a simple vision: to make smart home technology more accessible, practical and reliable for Indian households.
      </p>
      <p class="text-secondary small leading-relaxed mb-4">
        Today, we are taking that vision beyond our beginnings, reaching customers across India through our growing product portfolio and online presence.
      </p>
      <div>
        <a href="#what-we-do" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-semibold about-journey-btn">
          Our Journey <i class="bi bi-arrow-right ms-2 text-warning"></i>
        </a>
      </div>
    </div>

    <!-- Right: Our Journey Timeline & Map Card -->
    <div class="col-lg-7">
      <div class="about-journey-card p-4 p-md-4 shadow-sm h-100">
        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
          <i class="bi bi-flag-fill text-warning"></i> Our Journey
        </h5>
        <div class="row g-3 align-items-center">
          <div class="col-md-7">
            <div class="about-timeline">
              <div class="about-timeline-item">
                <div class="about-timeline-dot"></div>
                <div class="about-timeline-content">
                  <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Started in Coimbatore</h6>
                  <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Began with a focus on home automation and entrance automation.</p>
                </div>
              </div>
              <div class="about-timeline-item">
                <div class="about-timeline-dot"></div>
                <div class="about-timeline-content">
                  <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Expanded Our Offerings</h6>
                  <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Added smart locks, gate systems, video door phones, retrofit modules, smart switches and home control solutions.</p>
                </div>
              </div>
              <div class="about-timeline-item last-item">
                <div class="about-timeline-dot"></div>
                <div class="about-timeline-content">
                  <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Across India</h6>
                  <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Now an e-commerce enabled smart home brand, serving customers across India.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5 text-center d-flex align-items-center justify-content-center">
            <?= View::component('india_journey_map') ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Our Journey in Numbers -->
  <div class="mb-5">
    <div class="d-flex align-items-baseline justify-content-between flex-wrap gap-2 mb-3">
      <div>
        <div class="about-section-dash mb-1">Our Journey in Numbers</div>
      </div>
      <div class="text-secondary small" style="max-width: 580px; font-size: 0.82rem;">
        These milestones represent the experience we have gained, the projects we have completed and the trust customers have placed in HOMEIQ.
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="about-number-card p-3 shadow-sm d-flex align-items-center gap-3">
          <div class="about-number-icon-box">
            <i class="bi bi-door-closed text-warning fs-3"></i>
          </div>
          <div>
            <div class="about-number-val text-warning fw-extrabold" data-counter="4000" data-suffix="+">4,000+</div>
            <div class="about-number-lbl text-dark small fw-semibold">Gate Motor Installations</div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="about-number-card p-3 shadow-sm d-flex align-items-center gap-3">
          <div class="about-number-icon-box">
            <i class="bi bi-shield-lock text-warning fs-3"></i>
          </div>
          <div>
            <div class="about-number-val text-warning fw-extrabold" data-counter="2000" data-suffix="+">2,000+</div>
            <div class="about-number-lbl text-dark small fw-semibold">Smart Lock Installations</div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="about-number-card p-3 shadow-sm d-flex align-items-center gap-3">
          <div class="about-number-icon-box">
            <i class="bi bi-house-gear text-warning fs-3"></i>
          </div>
          <div>
            <div class="about-number-val text-warning fw-extrabold" data-counter="300" data-suffix="+">300+</div>
            <div class="about-number-lbl text-dark small fw-semibold">Complete Home Automation Projects</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. What We Do (6 Horizontal Icon Cards matching mockup) -->
  <div id="what-we-do" class="mb-5">
    <div class="about-section-dash mb-1">What We Do</div>
    <p class="text-secondary small mb-3">A complete range of smart home solutions designed for modern Indian homes.</p>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
      <!-- 1. Smart Door Locks -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              <circle cx="12" cy="16" r="1" fill="#FE6B25"></circle>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Smart Door Locks</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Keyless entry solutions designed to provide convenience, security and smarter access to your home.</p>
          </div>
        </div>
      </div>

      <!-- 2. Video Door Phones -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
              <circle cx="8.5" cy="8.5" r="1.5" fill="#FE6B25"></circle>
              <path d="M6 13c0-1.3 1.1-2 2.5-2s2.5.7 2.5 2"></path>
              <path d="M14 8h4M14 11h3"></path>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Video Door Phones</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Smart visitor monitoring and communication solutions that help you see and communicate with visitors before opening the door.</p>
          </div>
        </div>
      </div>

      <!-- 3. Home Automation -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
              <polyline points="2 17 12 22 22 17"></polyline>
              <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Home Automation</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Control lighting, curtains and connected devices with intelligent automation designed around your lifestyle.</p>
          </div>
        </div>
      </div>

      <!-- 4. Retrofit Smart Modules -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="2"></rect>
              <rect x="14" y="3" width="7" height="7" rx="2"></rect>
              <rect x="14" y="14" width="7" height="7" rx="2"></rect>
              <rect x="3" y="14" width="7" height="7" rx="2"></rect>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Retrofit Smart Modules</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Upgrade existing electrical switches and appliances with smart control technology without completely changing your existing setup.</p>
          </div>
        </div>
      </div>

      <!-- 5. Smart Touch Switches -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="26" height="24" viewBox="0 0 28 20" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="24" height="14" rx="7"></rect>
              <circle cx="19" cy="10" r="4" fill="#FE6B25"></circle>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Smart Touch Switches</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Modern touch-based controls that combine elegant design with intelligent functionality.</p>
          </div>
        </div>
      </div>

      <!-- 6. Gate Automation -->
      <div class="col">
        <div class="about-service-card p-3 p-md-4 shadow-sm h-100 d-flex flex-row align-items-start gap-3">
          <div class="about-service-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FE6B25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="1.5"></rect>
              <line x1="6" y1="5" x2="6" y2="19"></line>
              <line x1="10" y1="5" x2="10" y2="19"></line>
              <line x1="14" y1="5" x2="14" y2="19"></line>
              <line x1="18" y1="5" x2="18" y2="19"></line>
              <line x1="2" y1="12" x2="22" y2="12"></line>
            </svg>
          </div>
          <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Gate Automation</h6>
            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">Automated entrance solutions that bring convenience and controlled access to residential properties.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 5. Our Mission & Our Vision -->
  <div class="row g-4 mb-5">
    <!-- Our Mission (Dark Card) -->
    <div class="col-md-6">
      <div class="about-mission-card p-4 p-md-4 h-100 shadow-sm text-white">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="about-vision-icon-box">
            <i class="bi bi-bullseye text-warning fs-3"></i>
          </div>
          <h4 class="fw-bold m-0 text-white">Our Mission</h4>
        </div>
        <p class="text-white-50 m-0 leading-relaxed" style="font-size: 0.92rem;">
          To make smart home technology accessible to every Indian household by offering practical, reliable and innovative products that improve everyday living.
        </p>
      </div>
    </div>

    <!-- Our Vision (Light Card) -->
    <div class="col-md-6">
      <div class="about-vision-card p-4 p-md-4 h-100 shadow-sm">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="about-vision-icon-box bg-warning bg-opacity-10">
            <i class="bi bi-eye-fill text-warning fs-3"></i>
          </div>
          <h4 class="fw-bold m-0 text-dark">Our Vision</h4>
        </div>
        <p class="text-secondary m-0 leading-relaxed" style="font-size: 0.92rem;">
          To become one of India's most trusted and recognised smart home technology brands, helping households across the country experience a safer, smarter and more connected lifestyle.
        </p>
      </div>
    </div>
  </div>

  <!-- 6. What Makes HOMEIQ Different? -->
  <div class="mb-5">
    <div class="about-section-dash mb-3">What Makes HOMEIQ Different?</div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-4">
      <div class="col">
        <div class="about-diff-item">
          <div class="about-diff-icon mb-2">
            <i class="bi bi-sliders text-warning fs-3"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Practical Technology</h6>
          <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.45;">Products that solve real everyday problems.</p>
        </div>
      </div>

      <div class="col">
        <div class="about-diff-item">
          <div class="about-diff-icon mb-2">
            <i class="bi bi-star text-warning fs-3"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Experience That Matters</h6>
          <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.45;">Hands-on experience across thousands of installations and hundreds of projects.</p>
        </div>
      </div>

      <div class="col">
        <div class="about-diff-item">
          <div class="about-diff-icon mb-2">
            <i class="bi bi-house-check text-warning fs-3"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Solutions for Indian Homes</h6>
          <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.45;">Products selected with the needs of Indian households in mind.</p>
        </div>
      </div>

      <div class="col">
        <div class="about-diff-item">
          <div class="about-diff-icon mb-2">
            <i class="bi bi-people text-warning fs-3"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Customer-Focused Approach</h6>
          <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.45;">We believe support and guidance are just as important as the product itself.</p>
        </div>
      </div>

      <div class="col">
        <div class="about-diff-item">
          <div class="about-diff-icon mb-2">
            <i class="bi bi-bar-chart-line text-warning fs-3"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Growing Product Ecosystem</h6>
          <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.45;">We continuously expand our range of smart home solutions.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- 7. Our Future — Smarter Homes Across India -->
  <div class="about-future-card p-4 p-md-5 mb-5 shadow-sm position-relative overflow-hidden">
    <div class="about-future-bg-layer" style="background-image: url('<?= asset('assets/images/about/about-future-livingroom.jpg') ?>');"></div>
    <div class="position-relative" style="z-index: 3;">
      <div class="about-future-content" style="max-width: 480px;">
        <div class="about-section-dash mb-3" style="font-size: 1.15rem; font-weight: 700; color: #0F172A;">Our Future — Smarter Homes Across India</div>
        <p class="mb-3" style="font-size: 0.84rem; line-height: 1.6; color: #475569;">
          We see a future where smart home technology becomes a natural part of everyday living. HOMEIQ is working towards building a strong pan-India presence through its e-commerce platform, expanding product portfolio and better customer experience.
        </p>
        <p class="mb-3" style="font-size: 0.84rem; line-height: 1.6; color: #475569;">
          Our goal is to make intelligent home products easier to discover, purchase and use, whether a customer is upgrading a single door lock or planning a completely automated home.
        </p>
        <p class="mb-0" style="font-size: 0.84rem; line-height: 1.6; color: #475569;">
          We are committed to growing responsibly, improving continuously and earning the trust of customers across India.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  function initNumberCounters() {
    var counters = document.querySelectorAll('.about-number-val[data-counter]');
    if (!counters.length) return;

    function animate(el) {
      if (el.dataset.animated === 'true') return;
      el.dataset.animated = 'true';

      var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
      var suffix = el.getAttribute('data-suffix') || '';
      var prefix = el.getAttribute('data-prefix') || '';
      var duration = 1800; // 1.8 seconds duration
      var startTime = null;

      function step(now) {
        if (!startTime) startTime = now;
        var progress = Math.min((now - startTime) / duration, 1);
        // Smooth easeOutExpo curve for realistic deceleration
        var ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        var current = Math.floor(target * ease);

        el.textContent = prefix + current.toLocaleString('en-IN') + suffix;

        if (progress < 1) {
          requestAnimationFrame(step);
        } else {
          el.textContent = prefix + target.toLocaleString('en-IN') + suffix;
          el.classList.add('counter-completed');
        }
      }

      requestAnimationFrame(step);
    }

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function(entries, obs) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            animate(entry.target);
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.25 });

      counters.forEach(function(el) {
        // Set initial display to 0+ until entered viewport
        el.textContent = '0' + (el.getAttribute('data-suffix') || '');
        observer.observe(el);
      });
    } else {
      counters.forEach(animate);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNumberCounters);
  } else {
    initNumberCounters();
  }
})();
</script>
