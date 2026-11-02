<?php
declare(strict_types=1);

use App\Core\View;
use App\Core\App;
use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$siteSettings = $settingsRepo->all();
$pageTitle = $title ?? ($siteSettings['meta_title'] ?? 'HOMEIQ | Digital Smart Locks');
$metaDesc = $siteSettings['meta_description'] ?? 'Biometric digital smart door locks.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  
  <!-- Open Graph -->
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($metaDesc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e(App::getBaseUrl()) ?>">
  <meta name="theme-color" content="#08090d">

  <!-- Favicon: Architectural Shield Lock -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2338bdf8'><path d='M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 6c1.66 0 3 1.34 3 3v1h1v5H8v-5h1v-1c0-1.66 1.34-3 3-3zm0 2c-.55 0-1 .45-1 1v1h2v-1c0-.55-.45-1-1-1z'/></svg>">

  <!-- Google Fonts: Plus Jakarta Sans, Manrope & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS & Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Storefront CSS -->
  <link rel="stylesheet" href="<?= asset('css/storefront.css') ?>">

  <script>
    window.HOMEIQ_BASE_URL = '<?= rtrim(App::getBaseUrl(), '/') ?>';
  </script>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Announcement Bar -->
  <?= View::component('announcement_bar') ?>

  <!-- Navigation Header -->
  <?= View::component('header') ?>

  <!-- Flash Messages -->
  <?= View::component('flash_messages') ?>

  <!-- Page Content -->
  <main class="flex-grow-1">
    <?= $content ?>
  </main>

  <!-- Footer -->
  <?= View::component('footer') ?>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Storefront JS -->
  <script src="<?= asset('js/storefront.js') ?>"></script>
</body>
</html>

