<?php
declare(strict_types=1);

use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();
?>
<footer class="footer-hiq">
  <div class="container">
    <div class="row g-4 mb-5">
      <!-- Col 1: Brand Architecture -->
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="brand-logo-icon" style="width: 32px; height: 32px; font-size: 1rem;">
            <i class="bi bi-shield-lock-fill"></i>
          </span>
          <span class="fs-4 fw-bold text-light brand-font letter-spacing-1">HOME<span style="color: #38bdf8;">IQ</span></span>
        </div>
        <p class="text-secondary small pe-lg-4 mb-4" style="line-height: 1.7;">
          Where architectural elegance meets uncompromising biometric security. Engineered with aerospace-grade alloys, rapid semiconductor sensors, and bank-grade encryption for modern residences.
        </p>
        <div class="d-flex gap-2">
          <a href="#" class="footer-social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="footer-social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="footer-social-btn" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="#" class="footer-social-btn" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <!-- Col 2: Digital Lock Collections -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Hardware Series</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small">
          <li><a href="<?= url('category/ai-face-recognition-smart-locks') ?>">AI Face Recognition</a></li>
          <li><a href="<?= url('category/fingerprint-smart-locks') ?>">Biometric Fingerprint</a></li>
          <li><a href="<?= url('category/palm-vein-recognition-smart-locks') ?>">Palm Vein Detection</a></li>
          <li><a href="<?= url('category/wifi-and-app-controlled-smart-locks') ?>">Wi-Fi & Tuya Smart</a></li>
          <li><a href="<?= url('category/video-door-smart-locks') ?>">Video Intercom Locks</a></li>
          <li><a href="<?= url('category/smart-lock-accessories') ?>">Batteries & RFID Tags</a></li>
        </ul>
      </div>

      <!-- Col 3: Customer Concierge & Policies -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Concierge & Support</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small">
          <li><a href="<?= url('order-tracking') ?>">Track Your Order</a></li>
          <li><a href="<?= url('shipping-policy') ?>">Doorstep Delivery</a></li>
          <li><a href="<?= url('returns-policy') ?>">Warranty & Returns</a></li>
          <li><a href="<?= url('faq') ?>">Installation FAQs</a></li>
          <li><a href="<?= url('privacy-policy') ?>">Privacy & Encryption</a></li>
          <li><a href="<?= url('terms') ?>">Terms of Service</a></li>
        </ul>
      </div>

      <!-- Col 4: VIP Newsletter & Contact -->
      <div class="col-lg-4 col-md-6">
        <h6>Security Dispatch & Updates</h6>
        <p class="text-secondary small mb-3">
          Subscribe for firmware updates, architecture design guides, and private VIP promotional privileges.
        </p>
        <form action="<?= url('newsletter/subscribe') ?>" method="POST" class="mb-4">
          <?= csrf_field() ?>
          <div class="input-group">
            <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-50 text-light small" placeholder="Enter your email address" required style="border-radius: 8px 0 0 8px;">
            <button class="btn btn-hiq-accent px-4" type="submit" style="border-radius: 0 8px 8px 0;">Join</button>
          </div>
        </form>
        <div class="small text-secondary">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-headset text-info"></i>
            <span>Concierge Hotline: <strong><?= e($settings['support_phone'] ?? '+91 98765 43210') ?></strong></span>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-envelope text-info"></i>
            <span>Executive Desk: <?= e($settings['support_email'] ?? 'support@homeiq-security.com') ?></span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-shield-check text-success"></i>
            <span>Experience Center: <?= e($settings['address'] ?? 'Bengaluru, India') ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Security & Payment Partners Strip -->
    <div class="py-3 my-4 border-top border-bottom border-secondary border-opacity-10 d-flex flex-wrap align-items-center justify-content-between gap-3 text-secondary small">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <span class="text-white fw-bold"><i class="bi bi-shield-lock-fill text-info me-1"></i>Security Standards:</span>
        <span>• AES-128 Encryption</span>
        <span>• Grade-C Lock Cylinder</span>
        <span>• Tuya IoT Certified</span>
        <span>• 100k Cycle Mechanical Mortise</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">Accepted Gateways:</span>
        <span class="badge bg-dark border border-secondary text-light">UPI / Cards</span>
        <span class="badge bg-dark border border-secondary text-light">NetBanking</span>
        <span class="badge bg-dark border border-secondary text-light">Cash on Delivery</span>
      </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between text-secondary">
      <div>
        &copy; <?= date('Y') ?> HOMEIQ Technologies Pvt Ltd. All architectural rights reserved.
      </div>
      <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
        <span class="small d-flex align-items-center gap-1">
          <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
          256-Bit SSL Encrypted Checkout
        </span>
        <a href="<?= url('admin/login') ?>" class="text-secondary small ms-2"><i class="bi bi-lock me-1"></i>Staff Portal</a>
      </div>
    </div>
  </div>
</footer>
