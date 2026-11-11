<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $order */
$breadcrumbs = [
    ['label' => 'My Account', 'url' => 'account'],
    ['label' => 'My Orders', 'url' => 'account/orders'],
    ['label' => 'Order #' . $order['order_number'], 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Order Details #<?= e($order['order_number']) ?></h3>
      <span class="text-muted small">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
        <i class="bi bi-printer me-1"></i> Print Tax Invoice
      </a>
      <a href="<?= url('account/orders') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders
      </a>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card border p-3 h-100 shadow-sm">
        <h6 class="fw-bold text-muted small text-uppercase">Shipping Address</h6>
        <div class="small mt-2">
          <strong><?= e($order['customer_name']) ?></strong><br>
          <?= e($order['shipping_address']['street'] ?? '') ?><br>
          <?= e($order['shipping_address']['city'] ?? '') ?>, <?= e($order['shipping_address']['state'] ?? '') ?> - <?= e($order['shipping_address']['pincode'] ?? '') ?><br>
          Phone: <?= e($order['customer_phone']) ?>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border p-3 h-100 shadow-sm">
        <h6 class="fw-bold text-muted small text-uppercase">Payment & Fulfillment</h6>
        <div class="small mt-2">
          <div><strong>Payment Method:</strong> <?= strtoupper($order['payment_method']) ?></div>
          <div><strong>Payment Status:</strong> <span class="badge bg-secondary"><?= e($order['payment_status']) ?></span></div>
          <div><strong>Order Status:</strong> <span class="badge bg-primary"><?= e($order['status']) ?></span></div>
          <?php if (!empty($order['tracking']['courier_name'])): ?>
            <div class="mt-2 text-info">
              <strong>Courier:</strong> <?= e($order['tracking']['courier_name']) ?> (Tracking: <?= e($order['tracking']['tracking_number'] ?? 'N/A') ?>)
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Ordered Products -->
  <div class="card border rounded-3 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <h6 class="fw-bold m-0">Ordered Smart Locks & Accessories</h6>
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th class="text-end">Line Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <img src="<?= upload_url($item['image']) ?>" alt="" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: contain;" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
                  <div>
                    <strong class="small d-block"><?= e($item['name']) ?></strong>
                    <span class="text-muted small">SKU: <?= e($item['sku']) ?></span>
                  </div>
                </div>
              </td>
              <td><?= format_price($item['price']) ?></td>
              <td><?= $item['quantity'] ?></td>
              <td class="text-end fw-bold"><?= format_price($item['line_total']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot class="border-top small">
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

  <!-- Order Activity History Timeline -->
  <?php if (!empty($order['history'])): ?>
    <div class="card border rounded-3 p-3 p-sm-4 shadow-sm">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Status History & Updates</h6>
      <ul class="list-group list-group-flush small">
        <?php foreach ($order['history'] as $h): ?>
          <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2 px-0">
            <div>
              <strong><?= e($h['status']) ?></strong> — <?= e($h['note'] ?? '') ?>
              <div class="text-muted" style="font-size: 0.75rem;">Updated by: <?= e($h['by'] ?? 'System') ?></div>
            </div>
            <span class="text-muted"><?= date('d M Y, h:i A', strtotime($h['timestamp'])) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>

