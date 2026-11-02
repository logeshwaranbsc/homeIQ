<?php
declare(strict_types=1);

use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();
?>
<footer class="footer-hiq">
  <div class="container">
    <div class="row g-4 mb-5">
      <!-- Col 1: Brand Info -->
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <i class="bi bi-shield-lock-fill brand-icon fs-3"></i>
          <span class="fs-4 fw-bold text-light brand-font letter-spacing-1">HOMEIQ</span>
        </div>
        <p class="text-secondary small pe-lg-4 mb-4">
          Where intelligent engineering meets architectural peace of mind. Next-generation biometric digital door locks and smart door access hardware.
        </p>
        <div class="d-flex gap-3 text-secondary">
          <a href="#" class="fs-5" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="fs-5" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="fs-5" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="#" class="fs-5" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <!-- Col 2: Shop Digital Locks -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Digital Locks</h6>
        <ul class="list-unstyled d-flex flex-direction-column flex-column gap-2 small">
          <li><a href="<?= url('category/ai-face-recognition-smart-locks') ?>">AI Face Recognition</a></li>
          <li><a href="<?= url('category/fingerprint-smart-locks') ?>">Fingerprint Locks</a></li>
          <li><a href="<?= url('category/palm-vein-recognition-smart-locks') ?>">Palm Vein Locks</a></li>
          <li><a href="<?= url('category/wifi-and-app-controlled-smart-locks') ?>">Wi-Fi Smart Locks</a></li>
          <li><a href="<?= url('category/video-door-smart-locks') ?>">Video Door Locks</a></li>
          <li><a href="<?= url('category/smart-lock-accessories') ?>">Accessories</a></li>
        </ul>
      </div>

      <!-- Col 3: Customer Care & Policies -->
      <div class="col-lg-2 col-md-3 col-6">
        <h6>Customer Care</h6>
        <ul class="list-unstyled d-flex flex-direction-column flex-column gap-2 small">
          <li><a href="<?= url('order-tracking') ?>">Track Your Order</a></li>
          <li><a href="<?= url('shipping-policy') ?>">Shipping & Delivery</a></li>
          <li><a href="<?= url('returns-policy') ?>">Return & Refund</a></li>
          <li><a href="<?= url('faq') ?>">Support FAQs</a></li>
          <li><a href="<?= url('privacy-policy') ?>">Privacy Policy</a></li>
          <li><a href="<?= url('terms') ?>">Terms & Conditions</a></li>
        </ul>
      </div>

      <!-- Col 4: Newsletter & Direct Contact -->
      <div class="col-lg-4 col-md-6">
        <h6>Security Insights & Offers</h6>
        <p class="text-secondary small mb-3">
          Subscribe for smart security tips, firmware advisories, and VIP promotional offers.
        </p>
        <form action="<?= url('newsletter/subscribe') ?>" method="POST" class="mb-4">
          <?= csrf_field() ?>
          <div class="input-group">
            <input type="email" name="email" class="form-control bg-dark border-secondary text-light small" placeholder="Enter your email" required>
            <button class="btn btn-info text-dark fw-bold px-3" type="submit">Join</button>
          </div>
        </form>
        <div class="small text-secondary">
          <div><i class="bi bi-telephone text-info me-2"></i><?= e($settings['support_phone'] ?? '+91 98765 43210') ?></div>
          <div class="mt-1"><i class="bi bi-envelope text-info me-2"></i><?= e($settings['support_email'] ?? 'support@homeiq-security.com') ?></div>
          <div class="mt-1"><i class="bi bi-geo-alt text-info me-2"></i><?= e($settings['address'] ?? 'Bengaluru, India') ?></div>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between text-secondary">
      <div>
        &copy; <?= date('Y') ?> HOMEIQ Technologies Pvt Ltd. All rights reserved.
      </div>
      <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
        <span class="small"><i class="bi bi-shield-check text-success me-1"></i>256-Bit SSL Secured Checkout</span>
        <a href="<?= url('admin/login') ?>" class="text-secondary small ms-2"><i class="bi bi-lock me-1"></i>Staff Portal</a>
      </div>
    </div>
  </div>
</footer>

