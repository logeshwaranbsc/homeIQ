<?php
declare(strict_types=1);

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();
?>
<footer class="footer-hiq footer-hiq-light">
  <div class="container">
    <div class="row g-4 mb-4 mb-md-5">
      <!-- Col 1: Brand Info with White Logo Badge -->
      <div class="col-lg-4 col-md-6">
        <div class="mb-3">
          <div class="footer-brand-badge">
            <a href="<?= url('/') ?>" class="d-inline-block footer-brand-link" aria-label="HOMEIQ">
              <img src="<?= asset('assets/images/homeiq-logo.png') ?>" alt="HOMEIQ" class="footer-logo-img">
            </a>
          </div>
        </div>
        <p class="small pe-lg-4 mb-3 text-muted">
          Smart door locks for modern homes.
        </p>
        <div class="d-flex gap-3 text-secondary">
          <a href="#" class="fs-5" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="fs-5" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="#" class="fs-5" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="fs-5" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Quick Links</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small">
          <li><a href="<?= url('/') ?>">Home</a></li>
          <li><a href="<?= url('shop') ?>">Categories</a></li>
          <li><a href="<?= url('shop') ?>">Store</a></li>
          <li><a href="<?= url('video-testimonials') ?>">Reels &amp; Videos</a></li>
          <li><a href="<?= url('reviews') ?>">Testimonials</a></li>
        </ul>
      </div>

      <!-- Col 3: Support -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Support</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small">
          <li><a href="<?= url('faq') ?>">Help &amp; FAQs</a></li>
          <li><a href="<?= url('installation-guide') ?>">Installation Guide</a></li>
          <li><a href="<?= url('warranty') ?>">Warranty</a></li>
          <li><a href="<?= url('contact') ?>">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 4: Subscribe -->
      <div class="col-lg-4 col-md-6">
        <h6>Subscribe</h6>
        <p class="small text-muted mb-2">
          Get the latest offers and updates.
        </p>
        <form action="<?= url('newsletter/subscribe') ?>" method="POST" class="footer-subscribe-box">
          <?= csrf_field() ?>
          <input type="email" name="email" class="footer-subscribe-input" placeholder="Enter your email" required>
          <button class="footer-subscribe-btn" type="submit" aria-label="Subscribe">
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 text-muted text-center text-md-start">
      <div>
        &copy; <?= date('Y') ?> HOMEIQ. All Rights Reserved.
      </div>
      <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mt-2 mt-md-0">
        <a href="<?= url('privacy-policy') ?>" class="small text-muted">Privacy Policy</a>
        <span class="mx-1 text-muted">|</span>
        <a href="<?= url('terms') ?>" class="small text-muted">Terms &amp; Conditions</a>
      </div>
    </div>
  </div>
</footer>
