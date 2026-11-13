<?php
declare(strict_types=1);

$isCartPage = $isCartPage ?? false;
if (!$isCartPage) {
    $rawUri = $_SERVER['REQUEST_URI'] ?? '';
    $parsedPath = trim((string)parse_url($rawUri, PHP_URL_PATH), '/');
    $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if (!empty($scriptDir) && str_starts_with($parsedPath, $scriptDir)) {
        $parsedPath = trim(substr($parsedPath, strlen($scriptDir)), '/');
    }
    $isCartPage = ($parsedPath === 'cart' || str_ends_with($parsedPath, '/cart'));
}
?>
<!-- Slide-out Cart Drawer (Right Side) -->
<div class="offcanvas offcanvas-end hiq-cart-drawer" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel" data-bs-scroll="true">
  <!-- Drawer Header -->
  <div class="offcanvas-header hiq-drawer-header d-flex align-items-center justify-content-between w-100">
    <div class="d-flex align-items-center gap-3">
      <div class="drawer-header-icon">
        <i class="bi bi-bag-check"></i>
      </div>
      <div>
        <h5 class="offcanvas-title fw-bold m-0 text-white" id="cartDrawerLabel">Shopping Cart</h5>
        <div class="drawer-header-sub"><span id="cartDrawerCount">0</span> items selected</div>
      </div>
    </div>
    <button type="button" class="btn-close-drawer ms-auto" data-bs-dismiss="offcanvas" aria-label="Close" title="Close Cart">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <!-- Free Shipping Progress Meter -->
  <div class="hiq-drawer-shipping-meter" id="cartDrawerShippingMeter">
    <div class="shipping-meter-text" id="cartDrawerShippingText">
      <i class="bi bi-truck me-1"></i> Add items to unlock <strong>FREE Express Shipping</strong>
    </div>
    <div class="progress shipping-meter-progress">
      <div class="progress-bar progress-bar-striped progress-bar-animated" id="cartDrawerShippingBar" role="progressbar" style="width: 0%"></div>
    </div>
  </div>

  <!-- Drawer Body -->
  <div class="offcanvas-body hiq-drawer-body p-0">
    <!-- Empty Cart State -->
    <div class="cart-drawer-empty py-5 px-4 text-center d-none" id="cartDrawerEmpty">
      <div class="empty-icon-circle mb-3">
        <i class="bi bi-bag-x"></i>
      </div>
      <h6 class="fw-bold text-dark mb-2">Your Shopping Cart is Empty</h6>
      <p class="text-muted small mb-4">Discover our flagship biometric and keyless digital smart locks built for ultimate architectural security.</p>
      <a href="<?= url('shop') ?>" class="btn btn-hiq-primary py-2 px-4 fs-7" data-bs-dismiss="offcanvas">
        Explore Smart Locks <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <!-- Filled Cart Items List -->
    <div class="cart-drawer-items" id="cartDrawerItems">
      <!-- Items dynamically populated via JavaScript -->
    </div>
  </div>

  <!-- Drawer Footer: Subtotal & Actions -->
  <div class="offcanvas-footer hiq-drawer-footer border-top p-3 p-md-4 d-none" id="cartDrawerFooter">
    <div class="drawer-summary mb-3">
      <div class="d-flex justify-content-between align-items-center text-secondary small mb-1">
        <span>Subtotal</span>
        <span class="fw-bold text-dark" id="cartDrawerSubtotal">₹0</span>
      </div>
      <div class="d-flex justify-content-between align-items-center text-secondary small mb-1">
        <span>Express Delivery</span>
        <span class="fw-bold text-success" id="cartDrawerShipping">FREE</span>
      </div>
      <div class="d-flex justify-content-between align-items-baseline pt-2 mt-2 border-top">
        <div>
          <span class="fw-bold text-dark fs-6">Estimated Total</span>
          <div class="text-muted small" style="font-size: 0.72rem;">All taxes & GST included</div>
        </div>
        <span class="fs-4 fw-extrabold text-dark" id="cartDrawerGrandTotal">₹0</span>
      </div>
    </div>

    <div class="d-grid gap-2">
      <a href="<?= url('checkout') ?>" class="btn btn-drawer-checkout">
        <span>Proceed to Checkout</span>
        <i class="bi bi-arrow-right"></i>
      </a>
      <?php if (!$isCartPage): ?>
        <a href="<?= url('cart') ?>" class="btn btn-drawer-view-cart">
          View Full Cart Page
        </a>
      <?php endif; ?>
    </div>

    <div class="drawer-trust-badge text-center text-muted small mt-3">
      <i class="bi bi-shield-check text-success me-1"></i> 256-Bit SSL Encrypted Checkout • Free Doorstep Installation
    </div>
  </div>
</div>

<?php if (!$isCartPage): ?>
<!-- Floating Cart Navigation Button (Appears when side popup closes) -->
<a href="<?= url('cart') ?>" class="floating-cart-btn" id="floatingCartBtn" aria-label="Navigate to shopping cart" title="View Shopping Cart">
  <div class="floating-cart-icon-wrap">
    <i class="bi bi-bag-check-fill"></i>
    <span class="floating-cart-count" id="floatingCartCount">0</span>
  </div>
  <div class="floating-cart-label">
    <span class="floating-cart-title">View Cart</span>
    <span class="floating-cart-total" id="floatingCartTotal">₹0</span>
  </div>
  <i class="bi bi-arrow-right"></i>
</a>
<?php endif; ?>

