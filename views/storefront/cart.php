<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $cart */
$breadcrumbs = [
    ['label' => 'Shopping Cart', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <h1 class="h2 fw-bold mb-4">Your Shopping Cart</h1>

  <?php if (empty($cart['items'])): ?>
    <div class="card border p-4 p-md-5 text-center my-4 shadow-sm">
      <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
      <h3 class="fw-bold">Your Cart is Currently Empty</h3>
      <p class="text-muted">Discover our state-of-the-art AI face recognition and biometric digital locks to secure your home.</p>
      <div class="mt-3">
        <a href="<?= url('shop') ?>" class="btn btn-hiq-primary px-4 py-2">
          Explore Digital Locks
        </a>
      </div>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <!-- Items Table -->
      <div class="col-lg-8">
        <div class="card border rounded-3 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold m-0">Cart Items (<?= $cart['total_items'] ?>)</h6>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0 cart-items-table">
              <thead class="table-light small text-uppercase">
                <tr>
                  <th style="width: 45%;">Product</th>
                  <th style="width: 20%;">Price</th>
                  <th style="width: 20%;">Quantity</th>
                  <th style="width: 15%; text-align: right;">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cart['items'] as $item): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="<?= upload_url($item['image']) ?>" alt="<?= e($item['name']) ?>" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: contain;">
                        <div>
                          <a href="<?= url('product/' . $item['slug']) ?>" class="fw-bold text-dark text-decoration-none small d-block">
                            <?= e($item['name']) ?>
                          </a>
                          <span class="text-muted small">SKU: <?= e($item['sku']) ?></span>
                          <div>
                            <a href="<?= url('cart/remove/' . $item['id']) ?>" class="text-danger small text-decoration-none">
                              <i class="bi bi-trash me-1"></i>Remove
                            </a>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="fw-semibold"><?= format_price($item['price']) ?></div>
                      <?php if ($item['mrp'] > $item['price']): ?>
                        <small class="text-muted text-decoration-line-through"><?= format_price($item['mrp']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td>
                      <form action="<?= url('cart/update') ?>" method="POST" class="d-flex align-items-center gap-1">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= e($item['id']) ?>">
                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" class="form-control form-control-sm text-center" style="width: 70px;" onchange="this.form.submit()">
                      </form>
                    </td>
                    <td class="text-end fw-bold">
                      <?= format_price($item['line_total']) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <a href="<?= url('shop') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
          </a>
        </div>
      </div>

      <!-- Order Summary Card -->
      <div class="col-lg-4">
        <!-- Coupon Card -->
        <div class="card border rounded-3 p-3 shadow-sm mb-4">
          <label class="form-label fw-bold small text-uppercase letter-spacing-1">Promotional Coupon</label>
          <?php if (!empty($cart['applied_coupon'])): ?>
            <div class="alert alert-success d-flex align-items-center justify-content-between py-2 px-3 m-0">
              <div>
                <strong><i class="bi bi-check-circle-fill me-1"></i><?= e($cart['applied_coupon']['code']) ?></strong> Applied
              </div>
              <a href="<?= url('cart/coupon/remove') ?>" class="btn btn-sm btn-outline-danger py-0 px-2">Remove</a>
            </div>
          <?php else: ?>
            <form action="<?= url('cart/coupon') ?>" method="POST">
              <?= csrf_field() ?>
              <div class="input-group">
                <input type="text" name="code" class="form-control form-control-sm text-uppercase" placeholder="e.g. WELCOME10" required>
                <button type="submit" class="btn btn-dark btn-sm">Apply</button>
              </div>
            </form>
          <?php endif; ?>
        </div>

        <!-- Summary Totals -->
        <div class="card border rounded-3 p-4 shadow-sm">
          <h5 class="fw-bold mb-3 border-bottom pb-2">Order Summary</h5>

          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal (<?= $cart['total_items'] ?> items):</span>
            <span class="fw-semibold"><?= format_price($cart['subtotal']) ?></span>
          </div>

          <?php if ($cart['discount'] > 0): ?>
            <div class="d-flex justify-content-between mb-2 text-success">
              <span>Coupon Discount:</span>
              <span>- <?= format_price($cart['discount']) ?></span>
            </div>
          <?php endif; ?>

          <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">Estimated Shipping:</span>
            <?php if ($cart['shipping'] == 0): ?>
              <span class="text-success fw-bold">FREE</span>
            <?php else: ?>
              <span class="fw-semibold"><?= format_price($cart['shipping']) ?></span>
            <?php endif; ?>
          </div>

          <!-- Free Shipping Progress -->
          <?php if ($cart['shipping_shortfall'] > 0): ?>
            <div class="alert alert-light border small py-2 mb-3">
              <i class="bi bi-info-circle text-primary me-1"></i> Add <strong><?= format_price($cart['shipping_shortfall']) ?></strong> more to unlock <strong>Free Express Shipping</strong>!
            </div>
          <?php endif; ?>

          <hr class="my-3">

          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <span class="fs-5 fw-bold">Total Amount:</span>
            <span class="fs-4 fw-extrabold text-dark"><?= format_price($cart['grand_total']) ?></span>
          </div>

          <?php if (!auth_customer()): ?>
            <div class="alert alert-warning py-2 px-3 small mb-3 border-2 rounded-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: #FE6B25 !important; background: #fff8f5;">
              <span><i class="bi bi-shield-lock-fill text-primary me-1"></i> Sign-in required to checkout</span>
              <a href="<?= url('login') ?>?redirect=checkout" class="fw-bold text-primary text-decoration-none">Sign In / Register &rarr;</a>
            </div>
          <?php endif; ?>

          <a href="<?= url('checkout') ?>" class="btn btn-hiq-accent w-100 py-3 fw-bold fs-6">
            Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
          </a>

          <div class="text-center mt-3 small text-muted">
            <i class="bi bi-shield-check text-success me-1"></i> Guaranteed Safe & Secure Checkout
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

