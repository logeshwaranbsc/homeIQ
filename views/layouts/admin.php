<?php
declare(strict_types=1);

use App\Core\View;
use App\Core\App;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Dashboard') ?> | HOMEIQ Admin</title>
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">

  <!-- Google Fonts: Manrope & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS & Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Admin CSS -->
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">

  <script>
    window.HOMEIQ_BASE_URL = '<?= rtrim(App::getBaseUrl(), '/') ?>';
  </script>
</head>
<body>

<div class="admin-layout">
  <!-- Sidebar -->
  <?= View::component('admin_sidebar') ?>

  <!-- Main Container -->
  <div class="admin-main">
    <!-- Header -->
    <?= View::component('admin_header', ['title' => $title ?? 'Administration']) ?>

    <!-- Flash Messages -->
    <?= View::component('flash_messages') ?>

    <!-- Body -->
    <div class="admin-body">
      <?= $content ?>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Admin JS -->
<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>

