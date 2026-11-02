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
$isWishlisted = in_array($product['id'], $wishlist, true);
?>
<div class="product-card">
  <!-- Thumbnail with Badges -->
  <a href="<?= url('product/' . $product['slug']) ?>" class="card-thumb-link">
    <?php if ($discount > 0): ?>
      <span class="badge-discount"><?= $discount ?>% OFF</span>
    <?php endif; ?>

    <button type="button" class="btn-wishlist-toggle <?= $isWishlisted ? 'active' : '' ?>" data-product-id="<?= e($product['id']) ?>" title="Add to Wishlist">
      <i class="bi bi-heart<?= $isWishlisted ? '-fill' : '' ?>"></i>
    </button>

    <img src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name']) ?>" loading="lazy">
  </a>

  <!-- Body -->
  <div class="product-card-body">
    <div class="category-label"><?= e($product['category'] ?? 'Smart Lock') ?></div>
    <a href="<?= url('product/' . $product['slug']) ?>" class="prod-title" title="<?= e($product['name']) ?>">
      <?= e($product['name']) ?>
    </a>

    <!-- Rating & Review Count -->
    <div class="rating-row">
      <span>
        <i class="bi bi-star-fill text-warning"></i>
        <strong class="text-dark ms-1"><?= number_format((float)($product['rating'] ?? 4.8), 1) ?></strong>
      </span>
      <?php if (!empty($product['reviews_count'])): ?>
        <span class="review-count">(<?= (int)$product['reviews_count'] ?>)</span>
      <?php endif; ?>
    </div>

    <!-- Stock Status -->
    <div class="mb-2">
      <?php if ($inStock): ?>
        <span class="badge bg-light text-success border border-success-subtle small fw-medium">
          <i class="bi bi-check-circle me-1"></i>In Stock
        </span>
      <?php else: ?>
        <span class="badge bg-light text-danger border border-danger-subtle small fw-medium">
          <i class="bi bi-x-circle me-1"></i>Out of Stock
        </span>
      <?php endif; ?>
    </div>

    <!-- Price Hierarchy -->
    <div class="price-row">
      <div class="sale-price"><?= format_price($salePrice) ?></div>
      <?php if ($mrp > $salePrice): ?>
        <div class="regular-price"><?= format_price($mrp) ?></div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="d-grid gap-2">
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="m-0">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="btn btn-hiq-primary w-100 btn-sm">
            <i class="bi bi-bag-plus"></i> Add to Cart
          </button>
        </form>
      <?php else: ?>
        <button type="button" class="btn btn-secondary w-100 btn-sm" disabled>
          Unavailable
        </button>
      <?php endif; ?>
      <a href="<?= url('product/' . $product['slug']) ?>" class="btn btn-hiq-outline btn-sm">
        View Specifications
      </a>
    </div>
  </div>
</div>

