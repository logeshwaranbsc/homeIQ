<?php
declare(strict_types=1);

/** @var array|null $testimonial */
/** @var int $maxVideos */
/** @var int $currentCount */

$isEdit = !empty($testimonial);
$action = $isEdit ? url('admin/video-testimonials/update/' . $testimonial['id']) : url('admin/video-testimonials/store');
$maxUpload = ini_get('upload_max_filesize') ?: '40M';
$postMax = ini_get('post_max_size') ?: '40M';
$currentDesc = $testimonial['description'] ?? ($testimonial['review_text'] ?? ($testimonial['title'] ?? ''));
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Video Reel' : 'Upload Video Reel' ?></h4>
    <small class="text-muted">
      <?= $isEdit ? 'Update video media, thumbnail poster, name, and single line description.' : 'Upload video and thumbnail. Maximum 4 videos allowed.' ?>
    </small>
  </div>
  <a href="<?= url('admin/video-testimonials') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Videos
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-form-card shadow-sm p-4 bg-white rounded-3 border">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Video File Section -->
        <div class="mb-4 p-3 bg-light rounded-3 border">
          <label class="form-label fw-bold text-dark d-flex align-items-center justify-content-between mb-2">
            <span><i class="bi bi-camera-reels-fill me-1 text-primary"></i> 1. Video File <?= $isEdit ? '(Leave empty to keep existing)' : '*' ?></span>
            <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.72rem;">
              MP4, WebM (Max: <?= e($maxUpload) ?>)
            </span>
          </label>

          <?php if ($isEdit && !empty($testimonial['video_path'])): ?>
            <div class="mb-3 p-3 bg-white rounded-2 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-semibold">
                  <i class="bi bi-check-circle-fill me-1"></i>Current Uploaded Video
                </span>
                <a href="<?= upload_url($testimonial['video_path']) ?>" target="_blank" class="small text-decoration-none">
                  <i class="bi bi-box-arrow-up-right me-1"></i>Open video
                </a>
              </div>
              <div class="rounded-2 overflow-hidden bg-dark text-center" style="max-height: 260px;">
                <video controls playsinline style="max-height: 260px; max-width: 100%; object-fit: contain;">
                  <source src="<?= upload_url($testimonial['video_path']) ?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
            </div>
          <?php endif; ?>

          <input type="file" name="video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska" <?= (!$isEdit && empty($testimonial['video_url'])) ? 'required' : '' ?>>
          <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-1">
            <small class="text-muted">Vertical (9:16) or standard MP4 / WebM video.</small>
            <small class="text-secondary font-monospace" style="font-size: 0.72rem;"><i class="bi bi-infinity me-1"></i>No video size limit (Server: <?= e($maxUpload) ?>)</small>
          </div>

          <!-- Alternative Video URL -->
          <div class="mt-3 pt-3 border-top">
            <label class="form-label small fw-bold text-muted mb-1">
              <i class="bi bi-link-45deg me-1"></i>Or Video URL (Optional alternative)
            </label>
            <input type="url" name="video_url" class="form-control form-control-sm" placeholder="https://..." value="<?= e($testimonial['video_url'] ?? '') ?>">
          </div>
        </div>

        <!-- Thumbnail Poster Section -->
        <div class="mb-4 p-3 bg-light rounded-3 border">
          <label class="form-label fw-bold text-dark d-flex align-items-center justify-content-between mb-2">
            <span><i class="bi bi-image me-1 text-primary"></i> 2. Video Thumbnail Poster</span>
            <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.72rem;">JPG, PNG, WebP</span>
          </label>

          <?php if ($isEdit && !empty($testimonial['thumbnail_path'])): ?>
            <div class="mb-3 p-3 bg-white rounded-2 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-semibold">
                  <i class="bi bi-check-circle-fill me-1"></i>Current Thumbnail
                </span>
              </div>
              <div class="rounded-2 overflow-hidden border bg-dark" style="width: 140px; height: 220px;">
                <img src="<?= upload_url($testimonial['thumbnail_path']) ?>" alt="Thumbnail preview" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
            </div>
          <?php endif; ?>

          <input type="file" name="thumbnail" class="form-control" accept="image/png,image/jpeg,image/webp">
          <small class="text-muted mt-1 d-block">
            Upload vertical thumbnail poster (vertical 9:16 portrait recommended, e.g. 720×1280 px).
          </small>
        </div>

        <!-- Name & Single Line Description -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark pb-2 border-bottom mb-3">
            <i class="bi bi-card-text me-1 text-primary"></i> 3. Name & Description
          </h6>

          <div class="mb-3">
            <label class="form-label fw-bold">Customer / Creator Name *</label>
            <input type="text" name="customer_name" class="form-control" placeholder="e.g. Arun & Sneha" value="<?= e($testimonial['customer_name'] ?? '') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Single Line Description *</label>
            <input type="text" name="description" class="form-control" placeholder="e.g. Hands down the smartest investment for our villa security" value="<?= e($currentDesc) ?>" maxlength="120" required>
            <small class="text-muted">A concise one-line description displayed on the video card.</small>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Display Order (1 to 4)</label>
              <select name="display_order" class="form-select">
                <option value="1" <?= ((int)($testimonial['display_order'] ?? 1) === 1) ? 'selected' : '' ?>>1 (First Reel)</option>
                <option value="2" <?= ((int)($testimonial['display_order'] ?? 1) === 2) ? 'selected' : '' ?>>2 (Second Reel)</option>
                <option value="3" <?= ((int)($testimonial['display_order'] ?? 1) === 3) ? 'selected' : '' ?>>3 (Third Reel)</option>
                <option value="4" <?= ((int)($testimonial['display_order'] ?? 1) === 4) ? 'selected' : '' ?>>4 (Fourth Reel)</option>
              </select>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch" <?= !empty($testimonial['is_active'] ?? true) ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold" for="activeSwitch">
                  Active (Visible on Homepage)
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3 pt-3 border-top">
          <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
            <i class="bi bi-cloud-arrow-up-fill me-1"></i>
            <?= $isEdit ? 'Save Changes' : 'Upload Video' ?>
          </button>
          <a href="<?= url('admin/video-testimonials') ?>" class="btn btn-outline-secondary px-4 py-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
