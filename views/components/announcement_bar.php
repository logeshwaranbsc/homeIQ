<?php
declare(strict_types=1);

use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();

if (empty($settings['announcement_bar_enabled'])) {
    return;
}

$announcements = $settings['announcements'] ?? [
    'Free Doorstep Installation on All Smart Locks',
    'Complimentary Express Shipping on Orders Above ₹5,000',
    '2-Year Comprehensive Manufacturer Warranty',
    '24/7 Security Concierge Support',
];
?>
<div class="announcement-bar">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <span class="badge-live">CONCIERGE ACTIVE</span>
        <span class="d-none d-md-inline text-secondary opacity-50">|</span>
        <span class="highlight-cyan"><i class="bi bi-tools me-1"></i><?= e($announcements[0] ?? 'Free Doorstep Installation') ?></span>
        <span class="d-none d-lg-inline text-secondary opacity-50">✦</span>
        <span class="d-none d-lg-inline"><i class="bi bi-shield-check me-1 text-warning"></i><?= e($announcements[2] ?? '2-Year Official Manufacturer Warranty') ?></span>
        <span class="d-none d-xl-inline text-secondary opacity-50">✦</span>
        <span class="d-none d-xl-inline highlight-gold"><i class="bi bi-truck me-1"></i><?= e($announcements[1] ?? 'Insured 24h Dispatch') ?></span>
      </div>
      <div class="d-none d-md-flex align-items-center gap-3 small text-secondary">
        <span><i class="bi bi-telephone-fill me-1 text-info"></i><?= e($settings['support_phone'] ?? '+91 98765 43210') ?></span>
        <span>•</span>
        <a href="<?= url('order-tracking') ?>" class="text-light text-decoration-none hover-cyan"><i class="bi bi-geo-alt me-1"></i>Track Order</a>
      </div>
    </div>
  </div>
</div>
