<?php
declare(strict_types=1);

use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();

if (empty($settings['announcement_bar_enabled'])) {
    return;
}

$announcements = $settings['announcements'] ?? [
    'Free Installation on Eligible Products',
    'Free Shipping on Orders Above ₹5,000',
    '2-Year Official Manufacturer Warranty',
    '24/7 Smart Security Technical Support',
];
?>
<div class="announcement-bar">
  <div class="container text-center">
    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
      <span class="badge-live"><i class="bi bi-shield-check me-1"></i>HOMEIQ VERIFIED</span>
      <span class="d-none d-md-inline text-light">|</span>
      <span><?= e($announcements[0] ?? 'Free Installation on Eligible Products') ?></span>
      <span class="d-none d-lg-inline text-muted">✦</span>
      <span class="d-none d-lg-inline"><?= e($announcements[1] ?? 'Free Express Shipping Above ₹5,000') ?></span>
      <span class="d-none d-xl-inline text-muted">✦</span>
      <span class="d-none d-xl-inline"><?= e($announcements[2] ?? '2-Year Official Manufacturer Warranty') ?></span>
    </div>
  </div>
</div>

