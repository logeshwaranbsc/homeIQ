<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $cart
 * @var array $customer
 */
if (empty($customer)) {
    redirect('login?redirect=checkout');
    exit;
}

$breadcrumbs = [
    ['label' => 'Shopping Cart', 'url' => 'cart'],
    ['label' => 'Checkout', 'url' => null]
];

$old = old('');
$savedAddresses = (!empty($customer['addresses']) && is_array($customer['addresses'])) ? $customer['addresses'] : [];
$primaryAddress = $savedAddresses[0] ?? null;
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h2 fw-bold m-0 text-dark">Checkout & Secure Ordering</h1>
      <small class="text-muted">Doorstep delivery with certified HOMEIQ technician installation.</small>
    </div>
    <div class="d-flex align-items-center gap-2 text-muted small">
      <i class="bi bi-shield-lock-fill text-success fs-5"></i> 256-Bit SSL Encrypted
    </div>
  </div>

  <!-- Authenticated Customer Status Banner -->
  <div class="card border mb-4 shadow-sm" style="border-radius: 12px; border-left: 5px solid #10b981 !important; background: #f0fdf4;">
    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <div style="width: 44px; height: 44px; border-radius: 50%; background: #10b981; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
          <i class="bi bi-shield-check"></i>
        </div>
        <div>
          <div class="fw-bold text-dark fs-6">Signed in as <?= e($customer['name']) ?></div>
          <div class="small text-muted"><?= e($customer['email']) ?> <?= !empty($customer['phone']) ? '• ' . e($customer['phone']) : '' ?> • <span class="badge bg-success-subtle text-success border border-success-subtle">HOMEIQ Member</span></div>
        </div>
      </div>
      <div>
        <a href="<?= url('logout') ?>?redirect=checkout" class="btn btn-outline-secondary btn-sm" title="Log out or switch account">
          <i class="bi bi-box-arrow-right me-1"></i> Switch Account
        </a>
      </div>
    </div>
  </div>

  <form action="<?= url('checkout/process') ?>" method="POST" id="checkoutForm">
    <?= csrf_field() ?>

    <div class="row g-4 g-lg-5">
      <!-- Shipping & Customer Details -->
      <div class="col-lg-7">
        <!-- 1. Contact Information -->
        <div class="card border rounded-3 p-3 p-sm-4 shadow-sm mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 border-bottom pb-2">
            <h5 class="fw-bold m-0"><i class="bi bi-person me-2 text-primary"></i>1. Contact Information</h5>
            <span class="badge bg-light text-success border border-success-subtle small"><i class="bi bi-check2-circle me-1"></i>Verified Member</span>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="name" id="inputName" class="form-control" required value="<?= e(old('name', $customer['name'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" id="inputEmail" class="form-control" required value="<?= e(old('email', $customer['email'] ?? '')) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Mobile Number (for delivery updates & technician call) *</label>
              <input type="tel" name="phone" id="inputPhone" class="form-control" placeholder="10-digit mobile number" required value="<?= e(old('phone', $customer['phone'] ?? '')) ?>">
            </div>
          </div>
        </div>

        <!-- 2. Shipping Address -->
        <div class="card border rounded-3 p-3 p-sm-4 shadow-sm mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 border-bottom pb-2">
            <h5 class="fw-bold m-0"><i class="bi bi-truck me-2 text-primary"></i>2. Shipping Address</h5>
            <?php if (!empty($savedAddresses)): ?>
              <small class="text-muted"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Saved address loaded</small>
            <?php endif; ?>
          </div>

          <?php if (!empty($savedAddresses) && count($savedAddresses) > 1): ?>
            <!-- Address Quick Selector for Customers with Multiple Addresses -->
            <div class="mb-3 p-3 bg-light rounded border">
              <span class="small fw-bold text-dark d-block mb-2">Choose Saved Delivery Address:</span>
              <div class="d-flex flex-wrap gap-2">
                <?php foreach ($savedAddresses as $aIdx => $addr): ?>
                  <button type="button" class="btn btn-sm btn-outline-secondary bg-white text-start shadow-none" onclick="applySavedAddress(<?= htmlspecialchars(json_encode($addr), ENT_QUOTES) ?>)">
                    <i class="bi bi-geo-alt me-1 text-primary"></i> <strong><?= e($addr['city']) ?></strong>: <?= e($addr['street']) ?> (<?= e($addr['pincode']) ?>)
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Door / Flat No., Building, Street Address *</label>
              <input type="text" name="address" id="inputAddress" class="form-control" placeholder="e.g. Flat 402, Royal Palms, MG Road" required value="<?= e(old('address', $primaryAddress['street'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">City *</label>
              <input type="text" name="city" id="inputCity" class="form-control" required value="<?= e(old('city', $primaryAddress['city'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">State *</label>
              <input type="text" name="state" id="inputState" class="form-control" required value="<?= e(old('state', $primaryAddress['state'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">PIN Code *</label>
              <input type="text" name="pincode" id="inputPincode" class="form-control" maxlength="6" required value="<?= e(old('pincode', $primaryAddress['pincode'] ?? '')) ?>">
            </div>
          </div>

          <div class="mt-3">
            <div class="form-check small">
              <input class="form-check-input" type="checkbox" name="billing_different" id="billingDifferentCheck" value="1" <?= old('billing_different') ? 'checked' : '' ?> onchange="document.getElementById('billingAddressFields').classList.toggle('d-none', !this.checked)">
              <label class="form-check-label" for="billingDifferentCheck">
                Billing address is different from shipping address
              </label>
            </div>
          </div>

          <!-- Billing address fields (hidden by default) -->
          <div id="billingAddressFields" class="row g-3 mt-2 <?= old('billing_different') ? '' : 'd-none' ?>">
            <div class="col-12">
              <label class="form-label">Billing Street Address</label>
              <input type="text" name="billing_address" class="form-control" value="<?= e(old('billing_address', '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Billing City</label>
              <input type="text" name="billing_city" class="form-control" value="<?= e(old('billing_city', '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Billing State</label>
              <input type="text" name="billing_state" class="form-control" value="<?= e(old('billing_state', '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Billing PIN Code</label>
              <input type="text" name="billing_pincode" class="form-control" maxlength="6" value="<?= e(old('billing_pincode', '')) ?>">
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label small text-muted">Special Delivery / Installation Notes (Optional)</label>
            <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="e.g. Please call before arrival. Wooden main door 45mm thickness."><?= e(old('notes', '')) ?></textarea>
          </div>
        </div>

        <!-- 3. Payment Method -->
        <div class="card border rounded-3 p-3 p-sm-4 shadow-sm mb-4">
          <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-credit-card me-2 text-primary"></i>3. Select Payment Method</h5>

          <div class="d-flex flex-column gap-3">
            <div class="border rounded p-3 d-flex align-items-start align-items-sm-center gap-3">
              <input class="form-check-input mt-1 mt-sm-0 flex-shrink-0" type="radio" name="payment_method" id="payOnline" value="online" checked>
              <label class="form-check-label w-100" for="payOnline">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <strong>Online Payment (UPI, Credit/Debit Card, NetBanking)</strong>
                  <span class="badge bg-success small">Instant Confirmation</span>
                </div>
                <small class="text-muted d-block mt-1">Pay securely via verified payment gateway with 256-bit encryption.</small>
              </label>
            </div>

            <div class="border rounded p-3 d-flex align-items-start align-items-sm-center gap-3">
              <input class="form-check-input mt-1 mt-sm-0 flex-shrink-0" type="radio" name="payment_method" id="payCod" value="cod">
              <label class="form-check-label w-100" for="payCod">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <strong>Cash on Delivery (COD)</strong>
                  <span class="badge bg-light text-dark border small">Pay at Doorstep</span>
                </div>
                <small class="text-muted d-block mt-1">Pay when your HOMEIQ digital smart lock is delivered to your address.</small>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Review Sidebar -->
      <div class="col-lg-5">
        <div class="card border rounded-3 p-3 p-sm-4 shadow-sm sticky-top" style="top: 90px;">
          <h5 class="fw-bold mb-3 border-bottom pb-2">Order Review (<?= $cart['total_items'] ?> Items)</h5>

          <!-- Items list -->
          <div class="d-flex flex-column gap-3 mb-3" style="max-height: 280px; overflow-y: auto;">
            <?php foreach ($cart['items'] as $item): ?>
              <div class="d-flex align-items-center justify-content-between gap-2 gap-sm-3">
                <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
                  <img src="<?= upload_url($item['image']) ?>" alt="<?= e($item['name']) ?>" class="img-thumbnail flex-shrink-0" style="width: 48px; height: 48px; object-fit: contain;">
                  <div style="min-width: 0;">
                    <div class="fw-semibold small text-truncate"><?= e($item['name']) ?></div>
                    <small class="text-muted">Qty: <?= $item['quantity'] ?> × <?= format_price($item['price']) ?></small>
                  </div>
                </div>
                <div class="fw-bold small flex-shrink-0"><?= format_price($item['line_total']) ?></div>
              </div>
            <?php endforeach; ?>
          </div>

          <hr class="my-3">

          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Subtotal:</span>
            <span class="fw-semibold"><?= format_price($cart['subtotal']) ?></span>
          </div>

          <?php if ($cart['discount'] > 0): ?>
            <div class="d-flex justify-content-between mb-2 small text-success">
              <span>Coupon Discount (<?= e($cart['applied_coupon']['code'] ?? '') ?>):</span>
              <span>- <?= format_price($cart['discount']) ?></span>
            </div>
          <?php endif; ?>

          <div class="d-flex justify-content-between mb-3 small">
            <span class="text-muted">Shipping:</span>
            <?php if ($cart['shipping'] == 0): ?>
              <span class="text-success fw-bold">FREE</span>
            <?php else: ?>
              <span><?= format_price($cart['shipping']) ?></span>
            <?php endif; ?>
          </div>

          <div class="d-flex justify-content-between align-items-baseline mb-4 pt-2 border-top">
            <span class="fs-5 fw-bold">Payable Total:</span>
            <span class="fs-4 fw-extrabold text-dark"><?= format_price($cart['grand_total']) ?></span>
          </div>

          <button type="submit" class="btn btn-hiq-accent w-100 py-3 fw-bold fs-6">
            <i class="bi bi-shield-lock me-2"></i> Confirm & Place Order
          </button>

          <div class="text-center mt-3 text-secondary" style="font-size: 0.775rem;">
            By placing your order, you agree to HOMEIQ's <a href="<?= url('terms') ?>" class="text-decoration-none">Terms & Conditions</a> and <a href="<?= url('returns-policy') ?>" class="text-decoration-none">Return Policy</a>.
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function applySavedAddress(addr) {
  if (!addr) return;
  if (addr.street) document.getElementById('inputAddress').value = addr.street;
  if (addr.city) document.getElementById('inputCity').value = addr.city;
  if (addr.state) document.getElementById('inputState').value = addr.state;
  if (addr.pincode) document.getElementById('inputPincode').value = addr.pincode;
}
</script>
