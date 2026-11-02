<?php
declare(strict_types=1);

/** @var array $items [ [label => string, url => string|null] ] */
if (empty($items)) return;
?>
<nav aria-label="breadcrumb" class="my-3">
  <ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i>Home</a></li>
    <?php foreach ($items as $index => $item): ?>
      <?php if (!empty($item['url']) && $index < count($items) - 1): ?>
        <li class="breadcrumb-item"><a href="<?= url($item['url']) ?>" class="text-decoration-none text-muted"><?= e($item['label']) ?></a></li>
      <?php else: ?>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page"><?= e($item['label']) ?></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ol>
</nav>

