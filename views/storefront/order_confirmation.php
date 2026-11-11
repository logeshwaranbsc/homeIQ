<?php
declare(strict_types=1);

/** @var array $order */
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <!-- Success Banner -->
      <div class="card border-0 bg-success bg-opacity-10 rounded-4 p-4 p-md-5 text-center mb-4 border border-success-subtle">
        <div class="mb-3">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
        </div>
        <h1 class="h3 fw-bold text-dark">Thank You! Your Order is Confirmed</h1>
        <p class="text-secondary mb-3">
          We have received your order. A confirmation email and tracking link have been dispatched to <strong><?= e($order['customer_email']) ?></strong>.
        </p>
        <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-2 rounded-3 border mx-auto">
          <span class="text-muted small">Order Reference:</span>
          <strong class="text-dark fs-6"><?= e($order['order_number']) ?></strong>
        </div>
      </div>

      <!-- Order Details Card -->
      <div class="card border rounded-3 p-3 p-sm-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 border-bottom pb-3 mb-3">
          <h5 class="fw-bold m-0">Order Summary</h5>
          <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-printer me-1"></i> Print / Download Invoice
          </a>
        </div>

        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <h6 class="small text-uppercase text-muted fw-bold">Delivery Address</h6>
            <div class="text-dark small">
              <strong><?= e($order['customer_name']) ?></strong><br>
              <?= e($order['shipping_address']['street'] ?? '') ?><br>
              <?= e($order['shipping_address']['city'] ?? '') ?>, <?= e($order['shipping_address']['state'] ?? '') ?> - <?= e($order['shipping_address']['pincode'] ?? '') ?><br>
              Phone: <?= e($order['customer_phone']) ?>
            </div>
          </div>
          <div class="col-md-6">
            <h6 class="small text-uppercase text-muted fw-bold">Payment & Status</h6>
            <div class="small">
              <div><strong>Method:</strong> <?= strtoupper($order['payment_method']) ?></div>
              <div><strong>Payment Status:</strong> <span class="badge bg-secondary"><?= e($order['payment_status']) ?></span></div>
              <div><strong>Order Status:</strong> <span class="badge bg-primary"><?= e($order['status']) ?></span></div>
              <?php if (!empty($order['payment_reference'])): ?>
                <div><strong>Transaction ID:</strong> <?= e($order['payment_reference']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Items Table -->
        <h6 class="small text-uppercase text-muted fw-bold mb-2">Purchased Items</h6>
        <div class="table-responsive mb-3">
          <table class="table table-sm align-middle">
            <thead class="table-light small">
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($order['items'] as $item): ?>
                <tr>
                  <td>
                    <strong><?= e($item['name']) ?></strong>
                    <div class="text-muted small">SKU: <?= e($item['sku']) ?></div>
                  </td>
                  <td><?= $item['quantity'] ?></td>
                  <td class="text-end"><?= format_price($item['price']) ?></td>
                  <td class="text-end fw-semibold"><?= format_price($item['line_total']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="border-top">
              <tr>
                <td colspan="3" class="text-end text-muted">Subtotal:</td>
                <td class="text-end fw-semibold"><?= format_price($order['subtotal']) ?></td>
              </tr>
              <?php if ($order['discount'] > 0): ?>
                <tr class="text-success">
                  <td colspan="3" class="text-end">Discount (<?= e($order['coupon_code'] ?? '') ?>):</td>
                  <td class="text-end">- <?= format_price($order['discount']) ?></td>
                </tr>
              <?php endif; ?>
              <tr>
                <td colspan="3" class="text-end text-muted">Shipping:</td>
                <td class="text-end"><?= $order['shipping_amount'] == 0 ? 'FREE' : format_price($order['shipping_amount']) ?></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end fw-bold fs-6">Grand Total:</td>
                <td class="text-end fw-extrabold fs-5 text-dark"><?= format_price($order['total_amount']) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <a href="<?= url('shop') ?>" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Return to Shop
        </a>
        <a href="<?= url('order-tracking?order_number=' . urlencode($order['order_number']) . '&contact=' . urlencode($order['customer_email'])) ?>" class="btn btn-hiq-primary">
          <i class="bi bi-geo me-1"></i> Track Order Live
        </a>
      </div>
    </div>
  </div>
</div>

