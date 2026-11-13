<?php
declare(strict_types=1);

use App\Core\Session;

/** @var array $product */
$mrp = (float)($product['price'] ?? 0);
$salePrice = (float)($product['sale_price'] ?? $mrp);
$discount = discount_percentage($mrp, $salePrice);
$stock = (int)($product['stock'] ?? 0);
$inStock = $stock > 0;

$wishlist = Session::get('wishlist', []);
$isWishlisted = in_array($product['id'] ?? '', $wishlist, true);

// Stage tag
$primaryBadge = 'Active Defense';
$slug = strtolower((string)($product['slug'] ?? ''));
$cat = strtolower((string)($product['category'] ?? ''));
if (str_contains($slug, 'face') || str_contains($cat, 'face')) {
    $primaryBadge = '3D Face Scan';
} elseif (str_contains($slug, 'touch') || str_contains($slug, 'fingerprint') || str_contains($cat, 'fingerprint')) {
    $primaryBadge = '< 0.4s Touch';
} elseif (str_contains($slug, 'vein') || str_contains($cat, 'vein')) {
    $primaryBadge = 'Vascular IR';
} elseif (str_contains($slug, 'video') || str_contains($slug, 'vision') || str_contains($cat, 'video')) {
    $primaryBadge = '2K HD Video';
} elseif (str_contains($slug, 'wifi') || str_contains($slug, 'app') || str_contains($cat, 'wifi')) {
    $primaryBadge = 'Tuya IoT Sync';
} elseif (!empty($product['lock_type'])) {
    $primaryBadge = (string)$product['lock_type'];
}
?>
<div class="product-card">
  <!-- Hardware Showcase Stage -->
  <a href="<?= url('product/' . $product['slug']) ?>" class="card-thumb-link">
    <div class="thumb-stage-radar"></div>

    <?php if ($discount > 0): ?>
      <span class="modern-discount-badge">
        <i class="bi bi-tag-fill me-1"></i><?= $discount ?>% OFF
      </span>
    <?php endif; ?>

    <!-- Floating Action Stack (Wishlist & Share) -->
    <div class="card-action-stack">
      <button type="button"
              class="btn-card-action btn-action-wishlist <?= $isWishlisted ? 'active' : '' ?>"
              data-product-id="<?= e($product['id'] ?? '') ?>"
              title="<?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>"
              aria-label="Wishlist">
        <i class="bi bi-heart<?= $isWishlisted ? '-fill text-danger' : '' ?>"></i>
      </button>

      <button type="button"
              class="btn-card-action btn-action-share"
              data-share-url="<?= url('product/' . $product['slug']) ?>"
              data-share-title="<?= e($product['name'] ?? 'HOMEIQ Smart Lock') ?>"
              title="Share product"
              aria-label="Share product">
        <i class="bi bi-send"></i>
      </button>
    </div>

    <img src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name'] ?? 'Smart Lock') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">

    <span class="stage-feature-pill">
      <i class="bi bi-shield-check"></i> <?= e($primaryBadge) ?>
    </span>
  </a>

  <!-- Card Body -->
  <div class="product-card-body">
    <!-- Meta Top -->
    <div class="card-meta-top">
      <span class="modern-cat-pill">
        <span class="cat-dot"></span> <?= e($product['category'] ?? 'Digital Lock') ?>
      </span>
      <span class="modern-stock-pill <?= $inStock ? 'in-stock' : 'out-of-stock' ?>">
        <span class="stock-dot"></span> <?= $inStock ? 'In Stock' : 'Sold Out' ?>
      </span>
    </div>

    <!-- Product Title -->
    <a href="<?= url('product/' . $product['slug']) ?>" class="prod-title" title="<?= e($product['name']) ?>">
      <?= e($product['name']) ?>
    </a>

    <!-- Rating & Reviews -->
    <div class="modern-rating-strip">
      <i class="bi bi-star-fill text-warning"></i>
      <span class="rating-val"><?= number_format((float)($product['rating'] ?? 4.8), 1) ?></span>
      <?php if (!empty($product['reviews_count'])): ?>
        <span class="review-label">(<?= (int)$product['reviews_count'] ?> verified)</span>
      <?php else: ?>
        <span class="review-label">(48+ verified)</span>
      <?php endif; ?>
    </div>

    <!-- Price Hierarchy -->
    <div class="price-row">
      <div class="sale-price"><?= format_price($salePrice) ?></div>
      <?php if ($mrp > $salePrice): ?>
        <div class="regular-mrp"><?= format_price($mrp) ?></div>
        <span class="modern-save-badge">Save <?= format_price($mrp - $salePrice) ?></span>
      <?php endif; ?>
    </div>

    <!-- Streamlined Modern Actions -->
    <div class="modern-card-actions">
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="flex-grow-1 m-0" style="min-width: 0;">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= e($product['id'] ?? '') ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="btn-card-primary-cart">
            <i class="bi bi-bag-plus"></i>
            <span>Add to Cart</span>
          </button>
        </form>
      <?php else: ?>
        <button type="button" class="btn-card-disabled" disabled>
          <span>Out of Stock</span>
        </button>
      <?php endif; ?>
      <a href="<?= url('product/' . $product['slug']) ?>" class="btn-card-view-specs" title="View Full Specifications">
        <i class="bi bi-arrow-up-right"></i>
      </a>
    </div>
  </div>

  <!-- Bottom Accent Line -->
  <div class="card-bottom-accent"></div>
</div>
