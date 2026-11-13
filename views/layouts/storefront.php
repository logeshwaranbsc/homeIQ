<?php
declare(strict_types=1);

use App\Core\View;
use App\Core\App;
use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$siteSettings = $settingsRepo->all();
$pageTitle = $title ?? ($siteSettings['meta_title'] ?? 'HOMEIQ | Digital Smart Locks');
$metaDesc = $siteSettings['meta_description'] ?? 'Biometric digital smart door locks.';

$rawUri = $_SERVER['REQUEST_URI'] ?? '';
$parsedPath = trim((string)parse_url($rawUri, PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if (!empty($scriptDir) && str_starts_with($parsedPath, $scriptDir)) {
    $parsedPath = trim(substr($parsedPath, strlen($scriptDir)), '/');
}
$isCartPage = ($isCartPage ?? false) || ($bodyClass ?? '') === 'page-cart' || $parsedPath === 'cart' || str_ends_with($parsedPath, '/cart');
$bodyClasses = trim(($bodyClass ?? '') . ' ' . ($isCartPage ? 'page-cart' : ''));
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

  <!-- Google Fonts: Manrope (Headings) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS & Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Storefront CSS -->
  <link rel="stylesheet" href="<?= asset('css/storefront.css') ?>">

  <script>
    window.HOMEIQ_BASE_URL = '<?= rtrim(App::getBaseUrl(), '/') ?>';
  </script>
</head>
<body class="d-flex flex-column min-vh-100 <?= e($bodyClasses) ?>" data-page="<?= $isCartPage ? 'cart' : '' ?>">

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

  <!-- Slide-out Cart Drawer -->
  <?= View::component('cart_drawer', ['isCartPage' => $isCartPage]) ?>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Storefront JS -->
  <script src="<?= asset('js/storefront.js') ?>"></script>
</body>
</html>

