<?php
declare(strict_types=1);

/** @var array $reviews */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Customer Reviews & Testimonials (<?= count($reviews) ?>)</h4>
    <small class="text-muted">Create, edit, approve, or categorize ratings into Site Testimonials (Homepage Slider) vs Product Reviews (Under Product Only).</small>
  </div>
  <a href="<?= url('admin/reviews/create') ?>" class="btn btn-primary btn-sm fw-bold shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Add Review / Testimonial
  </a>
</div>

<div class="admin-table-card shadow-sm">
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Review Placement</th>
          <th>Product / Context</th>
          <th>Reviewer</th>
          <th>Rating</th>
          <th>Headline & Quote</th>
          <th>Verified</th>
          <th>Status</th>
          <th style="width: 170px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($reviews)): ?>
          <tr><td colspan="8" class="text-center py-4 text-muted">No submitted reviews at this time. Click "Add Review / Testimonial" above to add one.</td></tr>
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
              <td><strong><?= e($rev['product_name'] ?? 'HOMEIQ Smart Locks') ?></strong></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <?php if (!empty($rev['avatar'])): ?>
                    <img src="<?= upload_url($rev['avatar']) ?>" alt="Avatar" class="rounded-circle border flex-shrink-0" style="width: 34px; height: 34px; object-fit: cover;">
                  <?php else: ?>
                    <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold small flex-shrink-0 border" style="width: 34px; height: 34px;">
                      <?= strtoupper(substr($rev['reviewer_name'] ?? 'U', 0, 1)) ?>
                    </div>
                  <?php endif; ?>
                  <div>
                    <strong class="d-block text-dark"><?= e($rev['reviewer_name']) ?></strong>
                    <div class="text-muted small"><?= !empty($rev['created_at']) ? date('d M Y', strtotime($rev['created_at'])) : '' ?></div>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-warning">
                  <?= str_repeat('★', (int)($rev['rating'] ?? 5)) ?>
                </span>
                <span class="small fw-bold ms-1">(<?= $rev['rating'] ?? 5 ?>/5)</span>
              </td>
              <td style="max-width: 280px;">
                <?php if (!empty($rev['title'])): ?>
                  <strong class="d-block text-dark mb-1"><?= e($rev['title']) ?></strong>
                <?php endif; ?>
                <small class="text-muted">"<?= e($rev['comment'] ?? '') ?>"</small>
              </td>
              <td>
                <?php if (!empty($rev['verified_purchase'])): ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-patch-check-fill me-1"></i>Verified Buyer
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
                <!-- Edit Review -->
                <a href="<?= url('admin/reviews/edit/' . $rev['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2 me-1" title="Edit Review / Testimonial">
                  <i class="bi bi-pencil"></i>
                </a>

                <!-- Toggle Site / Product Review Placement -->
                <a href="<?= url('admin/reviews/toggle-site/' . $rev['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2 me-1" title="<?= $isSite ? 'Change to Product-Only Review' : 'Promote to Site Testimonial (Homepage)' ?>">
                  <i class="bi bi-arrow-left-right"></i>
                </a>

                <!-- Approve if pending -->
                <?php if (empty($rev['is_approved'])): ?>
                  <a href="<?= url('admin/reviews/approve/' . $rev['id']) ?>" class="btn btn-sm btn-success py-0 px-2 me-1" title="Approve Review">
                    <i class="bi bi-check-lg"></i>
                  </a>
                <?php endif; ?>

                <!-- Delete Review -->
                <a href="<?= url('admin/reviews/delete/' . $rev['id']) ?>" class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Review" data-confirm="Are you sure you want to delete this review?">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
