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
  <!-- Studio Showcase Thumbnail with Badges -->
  <a href="<?= url('product/' . $product['slug']) ?>" class="card-thumb-link">
    <?php if ($discount > 0): ?>
      <span class="badge-discount-luxury"><?= $discount ?>% OFF</span>
    <?php endif; ?>

    <button type="button" class="btn-wishlist-toggle <?= $isWishlisted ? 'active' : '' ?>" data-product-id="<?= e($product['id']) ?>" title="<?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
      <i class="bi bi-heart<?= $isWishlisted ? '-fill' : '' ?>"></i>
    </button>

    <img src="<?= upload_url($product['main_image'] ?? '') ?>" alt="<?= e($product['name']) ?>" loading="lazy">
  </a>

  <!-- Card Body -->
  <div class="product-card-body">
    <div class="category-label"><?= e($product['category'] ?? 'Digital Smart Lock') ?></div>
    
    <a href="<?= url('product/' . $product['slug']) ?>" class="prod-title" title="<?= e($product['name']) ?>">
      <?= e($product['name']) ?>
    </a>

    <!-- Rating & Review Count -->
    <div class="rating-row">
      <span class="rating-pill">
        <i class="bi bi-star-fill text-warning"></i>
        <span><?= number_format((float)($product['rating'] ?? 4.8), 1) ?></span>
      </span>
      <?php if (!empty($product['reviews_count'])): ?>
        <span class="review-count">(<?= (int)$product['reviews_count'] ?> verified reviews)</span>
      <?php else: ?>
        <span class="review-count">(Certified)</span>
      <?php endif; ?>
    </div>

    <!-- Stock Status with Live Indicator -->
    <?php if ($inStock): ?>
      <div class="badge-stock-live">
        In Stock • 24h Express Dispatch
      </div>
    <?php else: ?>
      <div class="badge-stock-out">
        Temporarily Out of Stock
      </div>
    <?php endif; ?>

    <!-- Price Hierarchy -->
    <div class="price-row">
      <div class="sale-price"><?= format_price($salePrice) ?></div>
      <?php if ($mrp > $salePrice): ?>
        <div class="regular-price"><?= format_price($mrp) ?></div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="card-actions">
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="m-0">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="btn btn-hiq-primary w-100">
            <i class="bi bi-bag-plus"></i> Add to Cart
          </button>
        </form>
      <?php else: ?>
        <button type="button" class="btn btn-secondary w-100 btn-sm disabled" disabled>
          Notify When Available
        </button>
      <?php endif; ?>
      <a href="<?= url('product/' . $product['slug']) ?>" class="btn btn-hiq-outline btn-sm">
        Specifications & Details <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</div>
