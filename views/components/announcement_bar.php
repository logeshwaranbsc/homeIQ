<?php
declare(strict_types=1);

use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();

if (empty($settings['announcement_bar_enabled'])) {
    return;
}

$rawAnnouncements = $settings['announcements'] ?? [
    'Free Installation on Eligible Products',
    'Free Express Shipping on Orders Above ₹5,000',
    '2-Year Official Manufacturer Warranty Protection',
    '24/7 Dedicated Smart Security Technical Support',
];

$announcements = array_values(array_filter((array)$rawAnnouncements, fn($a) => trim((string)$a) !== ''));
if (empty($announcements)) {
    $announcements = [
        'Free Installation on Eligible Products',
        'Free Express Shipping on Orders Above ₹5,000',
        '2-Year Official Manufacturer Warranty Protection',
        '24/7 Dedicated Smart Security Technical Support',
    ];
}
?>
<div class="announcement-bar marquee-bar" role="region" aria-label="Announcements">
  <div class="container-fluid px-3 px-xl-5" style="max-width: 1540px;">
    <div class="announcement-bar-inner">
      <!-- Fixed Verified Badge on Left -->
      <div class="announcement-fixed-left">
        <span class="badge-live"><i class="bi bi-shield-check me-1"></i>HOMEIQ VERIFIED</span>
        <span class="marquee-divider">|</span>
      </div>

      <!-- Scrolling Marquee for Remaining Announcements -->
      <div class="marquee-wrapper">
        <div class="marquee-track">
          <?php for ($set = 0; $set < 2; $set++): ?>
            <div class="marquee-set" <?= $set > 0 ? 'aria-hidden="true"' : '' ?>>
              <?php for ($rep = 0; $rep < 3; $rep++): ?>
                <div class="marquee-group">
                  <?php foreach ($announcements as $item): ?>
                    <span class="marquee-item"><?= e($item) ?></span>
                    <span class="marquee-sparkle" aria-hidden="true">✦</span>
                  <?php endforeach; ?>
                </div>
              <?php endfor; ?>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</div>
