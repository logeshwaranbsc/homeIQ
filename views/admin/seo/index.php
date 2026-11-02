<?php
declare(strict_types=1);

use App\Core\App;

/** @var array $settings */
$sitemapUrl = App::getBaseUrl() . '/sitemap.xml';
$robotsUrl = App::getBaseUrl() . '/robots.txt';
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">SEO Metadata & Dynamic Sitemap</h4>
    <small class="text-muted">Search engine optimization and indexing settings.</small>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= $sitemapUrl ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-diagram-3 me-1"></i> View sitemap.xml
    </a>
    <a href="<?= $robotsUrl ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-robot me-1"></i> View robots.txt
    </a>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Global Meta Tags Configuration</h6>
      <form action="<?= url('admin/seo/update') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Global Meta Title *</label>
          <input type="text" name="meta_title" class="form-control" value="<?= e($settings['meta_title'] ?? '') ?>" required>
          <small class="text-muted">Recommended length: 50-60 characters.</small>
        </div>

        <div class="mb-4">
          <label class="form-label">Global Meta Description *</label>
          <textarea name="meta_description" rows="3" class="form-control" required><?= e($settings['meta_description'] ?? '') ?></textarea>
          <small class="text-muted">Recommended length: 150-160 characters.</small>
        </div>

        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Update SEO & Regenerate Sitemap
        </button>
      </form>
    </div>

    <!-- Search Snippet Preview -->
    <div class="admin-form-card shadow-sm">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Google Search Snippet Preview</h6>
      <div class="p-3 bg-light border rounded">
        <div class="text-primary fs-5 fw-medium text-truncate"><?= e($settings['meta_title'] ?? 'HOMEIQ | Digital Locks') ?></div>
        <div class="text-success small mb-1"><?= e(App::getBaseUrl()) ?></div>
        <div class="text-muted small"><?= e($settings['meta_description'] ?? '') ?></div>
      </div>
    </div>
  </div>
</div>

