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
$isBestSeller = !empty($product['is_bestseller']) || !empty($product['best_seller']) || (($product['badge'] ?? '') === 'Best Seller');
?>
<div class="product-card">
  <!-- Hardware Showcase Stage -->
  <a href="<?= url('product/' . $product['slug']) ?>" class="card-thumb-link">
    <!-- Floating Badges Stack (Top Left) -->
    <div class="card-badges-stack">
      <?php if ($discount > 0): ?>
        <span class="modern-discount-badge">
          <i class="bi bi-tag-fill"></i> <?= $discount ?>% OFF
        </span>
      <?php endif; ?>
      <?php if ($isBestSeller): ?>
        <span class="modern-bestseller-badge">
          <i class="bi bi-star-fill"></i> Best Seller
        </span>
      <?php endif; ?>
    </div>

    <!-- Floating Action Stack (Wishlist & Share Top Right) -->
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
  </a>

  <!-- Card Body -->
  <div class="product-card-body">
    <!-- Meta Top: Category & Stock Status -->
    <div class="card-meta-top">
      <span class="modern-cat-pill">
        <span class="cat-dot"></span> <?= e($product['category'] ?? 'DIGITAL SMART LOCKS') ?>
      </span>
      <span class="modern-stock-pill <?= $inStock ? 'in-stock' : 'out-of-stock' ?>">
        <span class="stock-dot"></span> <?= $inStock ? 'In Stock' : 'Out of Stock' ?>
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
      <div class="prices-left">
        <div class="sale-price"><?= format_price($salePrice) ?></div>
        <?php if ($mrp > $salePrice): ?>
          <div class="regular-mrp"><?= format_price($mrp) ?></div>
        <?php endif; ?>
      </div>
      <?php if ($mrp > $salePrice): ?>
        <span class="modern-save-badge">Save <?= format_price($mrp - $salePrice) ?></span>
      <?php endif; ?>
    </div>

    <!-- Streamlined Modern Actions -->
    <div class="modern-card-actions">
      <?php if ($inStock): ?>
        <form action="<?= url('cart/add') ?>" method="POST" class="card-cart-form">
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
