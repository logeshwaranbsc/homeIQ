<?php
declare(strict_types=1);

/** @var array $testimonials */
/** @var int $count */
/** @var int $maxVideos */
/** @var bool $canAdd */
/** @var int $remainingSlots */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
  <div>
    <div class="d-flex align-items-center gap-2 mb-1">
      <h4 class="fw-bold m-0">Customer Video Reels</h4>
      <span class="badge <?= $count >= $maxVideos ? 'bg-danger' : 'bg-success' ?> rounded-pill px-3 py-1">
        <?= $count ?> / <?= $maxVideos ?> Slots Used
      </span>
    </div>
    <small class="text-muted">Upload up to 4 vertical video reels with thumbnail poster, name, and a single line description.</small>
  </div>

  <div class="d-flex align-items-center gap-2">
    <?php if ($canAdd): ?>
      <a href="<?= url('admin/video-testimonials/create') ?>" class="btn btn-sm btn-dark px-3 py-2 fw-semibold">
        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Video (<?= $remainingSlots ?> <?= $remainingSlots === 1 ? 'Slot' : 'Slots' ?> Left)
      </a>
    <?php else: ?>
      <button class="btn btn-sm btn-secondary px-3 py-2 fw-semibold" disabled title="4 of 4 video limit reached">
        <i class="bi bi-lock-fill me-1"></i> Limit Reached (4 / 4)
      </button>
    <?php endif; ?>
  </div>
</div>

<!-- Info Alert / Policy Banner -->
<div class="alert <?= $count >= $maxVideos ? 'alert-warning' : 'alert-info' ?> d-flex align-items-start gap-3 shadow-xs border mb-4 rounded-3">
  <i class="bi <?= $count >= $maxVideos ? 'bi-exclamation-triangle-fill text-warning' : 'bi-info-circle-fill text-primary' ?> fs-4 mt-1"></i>
  <div class="small flex-grow-1">
    <strong class="d-block text-dark mb-1">
      <?= $count >= $maxVideos ? 'Maximum 4 Videos Uploaded' : 'Storefront Video Reels Section' ?>
    </strong>
    <?php if ($count >= $maxVideos): ?>
      You have uploaded the maximum allowed <strong>4 video reels</strong>. To upload a different video, please edit or delete one of the existing records below.
    <?php else: ?>
      You can upload <strong><?= $remainingSlots ?> more <?= $remainingSlots === 1 ? 'video' : 'videos' ?></strong>. Active videos will be displayed in a vertical reel carousel on the homepage with navigation arrows.
    <?php endif; ?>
  </div>
  <span class="badge bg-white text-dark border px-2 py-1 align-self-center">Limit: Max 4 Videos</span>
</div>

<div class="admin-table-card shadow-sm bg-white rounded-3 border">
  <?php if (empty($testimonials)): ?>
    <div class="text-center py-5 px-3">
      <div class="mb-3">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(254, 107, 37, 0.1); color: #FE6B25; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem;">
          <i class="bi bi-camera-video"></i>
        </div>
      </div>
      <h5 class="fw-bold mb-1">No Video Reels Uploaded Yet</h5>
      <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
        Upload vertical customer videos with name and single line description like Instagram reels / shorts.
      </p>
      <a href="<?= url('admin/video-testimonials/create') ?>" class="btn btn-dark px-4 py-2 fw-bold">
        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Your First Video (1 of 4)
      </a>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table align-middle">
        <thead>
          <tr>
            <th style="width: 60px;">Order</th>
            <th style="width: 100px;">Reel Poster</th>
            <th style="width: 180px;">Name</th>
            <th>Single Line Description</th>
            <th style="width: 140px;">Video File</th>
            <th style="width: 100px;">Status</th>
            <th style="width: 110px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($testimonials as $t): 
            $desc = $t['description'] ?? ($t['review_text'] ?? ($t['title'] ?? ''));
          ?>
            <tr>
              <td>
                <span class="badge bg-light text-dark border fw-bold fs-6">#<?= (int)($t['display_order'] ?? 1) ?></span>
              </td>
              <td>
                <div class="position-relative rounded-2 overflow-hidden border bg-dark" style="width: 65px; height: 100px;">
                  <?php if (!empty($t['thumbnail_path'])): ?>
                    <img src="<?= upload_url($t['thumbnail_path']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary" style="background: #111827;">
                      <i class="bi bi-camera-reels fs-4"></i>
                    </div>
                  <?php endif; ?>
                  <span class="position-absolute top-50 start-50 translate-middle badge rounded-circle bg-dark bg-opacity-75 p-2 text-white">
                    <i class="bi bi-play-fill" style="font-size: 0.75rem;"></i>
                  </span>
                </div>
              </td>
              <td>
                <strong class="d-block text-dark"><?= e($t['customer_name'] ?? 'Homeowner') ?></strong>
              </td>
              <td>
                <div class="text-secondary small fw-medium text-truncate" style="max-width: 380px;">
                  <?= e($desc) ?>
                </div>
              </td>
              <td>
                <?php if (!empty($t['video_path'])): ?>
                  <a href="<?= upload_url($t['video_path']) ?>" target="_blank" class="small text-decoration-none d-inline-flex align-items-center gap-1">
                    <i class="bi bi-file-earmark-play-fill text-success fs-5"></i>
                    <span>Preview</span>
                  </a>
                <?php elseif (!empty($t['video_url'])): ?>
                  <a href="<?= e($t['video_url']) ?>" target="_blank" class="small text-decoration-none d-inline-flex align-items-center gap-1">
                    <i class="bi bi-link-45deg text-primary fs-5"></i>
                    <span>Link</span>
                  </a>
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= url('admin/video-testimonials/status/' . $t['id']) ?>" class="text-decoration-none" title="Click to toggle active status">
                  <span class="badge-status <?= !empty($t['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                    <i class="bi <?= !empty($t['is_active']) ? 'bi-check-circle-fill' : 'bi-dash-circle' ?> me-1"></i>
                    <?= !empty($t['is_active']) ? 'Active' : 'Disabled' ?>
                  </span>
                </a>
              </td>
              <td class="text-end">
                <a href="<?= url('admin/video-testimonials/edit/' . $t['id']) ?>" class="btn-action-icon me-1" title="Edit Reel">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= url('admin/video-testimonials/delete/' . $t['id']) ?>" class="btn-action-icon danger" title="Delete Reel" data-confirm="Are you sure you want to delete this video reel?">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
