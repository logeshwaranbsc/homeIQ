<?php
declare(strict_types=1);

/** @var array|null $review */
/** @var array $products */
$isEdit = !empty($review);
$action = $isEdit ? url('admin/reviews/update/' . $review['id']) : url('admin/reviews/store');
$isSiteReview = $isEdit ? (!empty($review['is_site_review']) || ($review['review_type'] ?? '') === 'site') : true;
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit Customer Review / Testimonial' : 'Add New Customer Review / Testimonial' ?></h4>
    <small class="text-muted">Create or modify customer reviews, homepage testimonials, star ratings, and verified buyer badges.</small>
  </div>
  <a href="<?= url('admin/reviews') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Reviews List
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-form-card shadow-sm p-4 bg-white rounded-3 border">
      <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-3 mb-3">
          <div class="col-md-7">
            <label class="form-label fw-bold">Customer / Reviewer Name <span class="text-danger">*</span></label>
            <input type="text" name="reviewer_name" class="form-control" placeholder="e.g. Vikramaditya S. or Dr. Meera Nambiar" required value="<?= e($review['reviewer_name'] ?? '') ?>">
          </div>
          <div class="col-md-5">
            <label class="form-label fw-bold">Star Rating <span class="text-danger">*</span></label>
            <select name="rating" class="form-select">
              <?php $curRating = (int)($review['rating'] ?? 5); ?>
              <option value="5" <?= $curRating === 5 ? 'selected' : '' ?>>★★★★★ (5 Stars - Excellent)</option>
              <option value="4" <?= $curRating === 4 ? 'selected' : '' ?>>★★★★☆ (4 Stars - Good)</option>
              <option value="3" <?= $curRating === 3 ? 'selected' : '' ?>>★★★☆☆ (3 Stars - Average)</option>
              <option value="2" <?= $curRating === 2 ? 'selected' : '' ?>>★★☆☆☆ (2 Stars - Below Average)</option>
              <option value="1" <?= $curRating === 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 Star - Poor)</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Review Headline / Title <small class="text-muted fw-normal">(Optional)</small></label>
          <input type="text" name="title" class="form-control" placeholder="e.g. Exceptional face unlock speed and build quality" value="<?= e($review['title'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Review Placement / Display Target <span class="text-danger">*</span></label>
          <div class="d-flex gap-3 flex-wrap">
            <div class="form-check border rounded-3 p-3 flex-grow-1 bg-light-subtle">
              <input class="form-check-input ms-0 me-2" type="radio" name="review_type" id="typeSite" value="site" <?= $isSiteReview ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold d-block cursor-pointer" for="typeSite">
                <i class="bi bi-house-heart-fill text-primary me-1"></i> Site Review (Homepage Slider)
                <span class="d-block text-muted small fw-normal mt-1">Featured in "What Our Customers Say" testimonial track on the homepage.</span>
              </label>
            </div>
            <div class="form-check border rounded-3 p-3 flex-grow-1 bg-light-subtle">
              <input class="form-check-input ms-0 me-2" type="radio" name="review_type" id="typeProduct" value="product" <?= !$isSiteReview ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold d-block cursor-pointer" for="typeProduct">
                <i class="bi bi-box-seam text-secondary me-1"></i> Product Review Only
                <span class="d-block text-muted small fw-normal mt-1">Appears exclusively on the selected product detail page under customer reviews.</span>
              </label>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Associated Product</label>
          <select name="product_id" class="form-select">
            <option value="" <?= empty($review['product_id']) ? 'selected' : '' ?>>General Brand Testimonial (HOMEIQ Smart Locks)</option>
            <?php foreach ($products as $prod): ?>
              <option value="<?= e($prod['id']) ?>" <?= ($review['product_id'] ?? '') === $prod['id'] ? 'selected' : '' ?>>
                <?= e($prod['name']) ?> (₹<?= number_format((float)($prod['price'] ?? 0)) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <small class="text-muted">If "Site Review" is selected, linking to a product will still highlight the product name context.</small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Testimonial Quote / Review Comment <span class="text-danger">*</span></label>
          <textarea name="comment" rows="4" class="form-control" placeholder="Enter customer feedback or review comment..." required><?= e($review['comment'] ?? '') ?></textarea>
        </div>

        <!-- Customer Avatar Upload -->
        <div class="mb-4 p-3 border rounded-3 bg-light-subtle">
          <label class="form-label fw-bold d-block">Customer Photo / Avatar</label>
          <div class="d-flex align-items-center gap-3">
            <?php if (!empty($review['avatar'])): ?>
              <div class="position-relative" style="width: 60px; height: 60px;">
                <img src="<?= upload_url($review['avatar']) ?>" alt="Avatar" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
              </div>
            <?php else: ?>
              <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold border" style="width: 60px; height: 60px; font-size: 1.5rem;">
                <?= !empty($review['reviewer_name']) ? strtoupper(substr($review['reviewer_name'], 0, 1)) : '?' ?>
              </div>
            <?php endif; ?>
            <div class="flex-grow-1">
              <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp,image/svg+xml">
              <small class="text-muted d-block mt-1">Upload a real customer photo (JPG, PNG, WEBP). If left empty, initial letters circle will be used automatically.</small>
              <?php if (!empty($review['avatar'])): ?>
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="removeAvatarCheck">
                  <label class="form-check-label text-danger small fw-semibold" for="removeAvatarCheck">
                    <i class="bi bi-trash me-1"></i> Remove existing photo (revert to initials)
                  </label>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Switches / Options -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="form-check form-switch p-3 border rounded-3 h-100 bg-light-subtle">
              <input class="form-check-input ms-0 me-3" type="checkbox" name="verified_purchase" value="1" id="verifiedCheck" <?= !isset($review['verified_purchase']) || !empty($review['verified_purchase']) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold cursor-pointer" for="verifiedCheck">
                <i class="bi bi-patch-check-fill text-success me-1"></i> Verified Buyer Badge
                <span class="d-block text-muted small fw-normal mt-1">Displays the orange/green "Verified Buyer" checkmark on the card.</span>
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check form-switch p-3 border rounded-3 h-100 bg-light-subtle">
              <input class="form-check-input ms-0 me-3" type="checkbox" name="is_approved" value="1" id="approvedCheck" <?= !isset($review['is_approved']) || !empty($review['is_approved']) ? 'checked' : '' ?>>
              <label class="form-check-label fw-bold cursor-pointer" for="approvedCheck">
                <i class="bi bi-check-circle-fill text-primary me-1"></i> Approved & Published
                <span class="d-block text-muted small fw-normal mt-1">Review is live and immediately visible to visitors on the website.</span>
              </label>
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
          <a href="<?= url('admin/reviews') ?>" class="btn btn-outline-secondary px-4">
            Cancel
          </a>
          <button type="submit" class="btn btn-primary px-4 fw-bold">
            <i class="bi bi-check2-circle me-1"></i> <?= $isEdit ? 'Save Changes' : 'Create Review / Testimonial' ?>
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

