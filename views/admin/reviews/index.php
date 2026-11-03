<?php
declare(strict_types=1);

/** @var array $reviews */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Customer Reviews Moderation (<?= count($reviews) ?>)</h4>
    <small class="text-muted">Review, approve, or categorize ratings into Site Testimonials (Homepage) vs Product Reviews (Under Product Only).</small>
  </div>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Review Placement</th>
          <th>Product Name</th>
          <th>Reviewer</th>
          <th>Rating</th>
          <th>Headline & Comment</th>
          <th>Verified</th>
          <th>Status</th>
          <th style="width: 150px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($reviews)): ?>
          <tr><td colspan="8" class="text-center py-4 text-muted">No submitted reviews at this time.</td></tr>
        <?php else: ?>
          <?php foreach ($reviews as $rev): ?>
            <?php 
              $isSite = !empty($rev['is_site_review']) || ($rev['review_type'] ?? '') === 'site';
            ?>
            <tr>
              <td>
                <?php if ($isSite): ?>
                  <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 small fw-bold">
                    <i class="bi bi-house-door-fill me-1"></i>Site Review (Homepage)
                  </span>
                <?php else: ?>
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 small fw-bold">
                    <i class="bi bi-box-seam me-1"></i>Product Review Only
                  </span>
                <?php endif; ?>
              </td>
              <td><strong><?= e($rev['product_name'] ?? 'Smart Lock') ?></strong></td>
              <td>
                <strong><?= e($rev['reviewer_name']) ?></strong>
                <div class="text-muted small"><?= date('d M Y', strtotime($rev['created_at'])) ?></div>
              </td>
              <td>
                <span class="text-warning">
                  <?= str_repeat('★', (int)($rev['rating'] ?? 5)) ?>
                </span>
                <span class="small fw-bold ms-1">(<?= $rev['rating'] ?>/5)</span>
              </td>
              <td style="max-width: 260px;">
                <strong class="d-block text-dark"><?= e($rev['title'] ?? '') ?></strong>
                <small class="text-muted">"<?= e($rev['comment'] ?? '') ?>"</small>
              </td>
              <td>
                <?php if (!empty($rev['verified_purchase'])): ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-patch-check-fill me-1"></i>Verified
                  </span>
                <?php else: ?>
                  <span class="text-muted small">Standard</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge-status <?= !empty($rev['is_approved']) ? 'badge-active' : 'badge-pending' ?>">
                  <?= !empty($rev['is_approved']) ? 'Approved' : 'Pending' ?>
                </span>
              </td>
              <td class="text-end">
                <!-- Toggle Site / Product Review Placement -->
                <a href="<?= url('admin/reviews/toggle-site/' . $rev['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2 me-1" title="<?= $isSite ? 'Change to Product-Only Review' : 'Promote to Site Testimonial (Homepage)' ?>">
                  <i class="bi bi-arrow-left-right"></i>
                </a>

                <?php if (empty($rev['is_approved'])): ?>
                  <a href="<?= url('admin/reviews/approve/' . $rev['id']) ?>" class="btn btn-sm btn-success py-0 px-2 me-1" title="Approve Review">
                    <i class="bi bi-check-lg"></i>
                  </a>
                <?php endif; ?>
                <a href="<?= url('admin/reviews/reject/' . $rev['id']) ?>" class="btn btn-sm btn-outline-danger py-0 px-2" title="Reject / Delete" data-confirm="Are you sure you want to reject this review?">
                  <i class="bi bi-x-lg"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
