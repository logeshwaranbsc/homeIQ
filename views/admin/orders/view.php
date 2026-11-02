<?php
declare(strict_types=1);

/**
 * @var array $order
 * @var array $statuses
 * @var array $paymentStatuses
 */
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold m-0">Order Management: #<?= e($order['order_number']) ?></h4>
    <small class="text-muted">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></small>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn btn-sm btn-dark">
      <i class="bi bi-printer me-1"></i> Print Tax Invoice
    </a>
    <a href="<?= url('admin/orders') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> All Orders
    </a>
  </div>
</div>

<div class="row g-4">
  <!-- Left Column: Items, Customer, History -->
  <div class="col-lg-8">
    <!-- Customer & Destination Card -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Customer & Delivery Information</h6>
      <div class="row g-3 small">
        <div class="col-md-6">
          <label class="text-muted fw-bold">CUSTOMER DETAILS</label>
          <div class="fs-6 fw-bold text-dark mt-1"><?= e($order['customer_name']) ?></div>
          <div><i class="bi bi-envelope me-1 text-muted"></i><?= e($order['customer_email']) ?></div>
          <div><i class="bi bi-telephone me-1 text-muted"></i><?= e($order['customer_phone']) ?></div>
        </div>
        <div class="col-md-6">
          <label class="text-muted fw-bold">SHIPPING ADDRESS</label>
          <div class="mt-1">
            <?= e($order['shipping_address']['street'] ?? '') ?><br>
            <?= e($order['shipping_address']['city'] ?? '') ?>, <?= e($order['shipping_address']['state'] ?? '') ?> - <?= e($order['shipping_address']['pincode'] ?? '') ?><br>
            <?= e($order['shipping_address']['country'] ?? 'India') ?>
          </div>
        </div>
      </div>
      <?php if (!empty($order['notes'])): ?>
        <div class="alert alert-light border small mt-3 mb-0">
          <strong>Customer Note:</strong> <?= e($order['notes']) ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Items Table -->
    <div class="admin-table-card shadow-sm mb-4">
      <div class="table-header-bar">
        <h6 class="fw-bold m-0">Ordered Hardware Items</h6>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Item</th>
              <th>SKU</th>
              <th>Unit Price</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Line Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order['items'] as $item): ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <img src="<?= upload_url($item['image']) ?>" alt="" style="width: 40px; height: 40px; object-fit: contain;" class="img-thumbnail">
                    <strong class="text-dark"><?= e($item['name']) ?></strong>
                  </div>
                </td>
                <td><code><?= e($item['sku']) ?></code></td>
                <td><?= format_price($item['price']) ?></td>
                <td class="text-center"><?= $item['quantity'] ?></td>
                <td class="text-end fw-bold"><?= format_price($item['line_total']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-end text-muted small">Subtotal:</td>
              <td class="text-end fw-semibold"><?= format_price($order['subtotal']) ?></td>
            </tr>
            <?php if ($order['discount'] > 0): ?>
              <tr class="text-success">
                <td colspan="4" class="text-end small">Coupon Discount (<?= e($order['coupon_code'] ?? '') ?>):</td>
                <td class="text-end">- <?= format_price($order['discount']) ?></td>
              </tr>
            <?php endif; ?>
            <tr>
              <td colspan="4" class="text-end text-muted small">Shipping Amount:</td>
              <td class="text-end"><?= $order['shipping_amount'] == 0 ? 'FREE' : format_price($order['shipping_amount']) ?></td>
            </tr>
            <tr class="table-light">
              <td colspan="4" class="text-end fw-bold">Grand Total:</td>
              <td class="text-end fw-bold fs-6 text-dark"><?= format_price($order['total_amount']) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Status History Audit Log -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Audit Log & State Transitions</h6>
      <ul class="list-group list-group-flush small">
        <?php foreach ($order['history'] ?? [] as $h): ?>
          <li class="list-group-item d-flex justify-content-between align-items-start px-0">
            <div>
              <span class="badge bg-secondary"><?= e($h['status']) ?></span>
              <span class="ms-2"><?= e($h['note'] ?? '') ?></span>
              <div class="text-muted" style="font-size: 0.725rem;">Actor: <?= e($h['by'] ?? 'System') ?></div>
            </div>
            <span class="text-muted"><?= date('d M Y, h:i A', strtotime($h['timestamp'])) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <!-- Right Column: Status & Tracking Modifiers -->
  <div class="col-lg-4">
    <!-- Fulfillment Status Updater -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Update Order Status</h6>
      <form action="<?= url('admin/orders/status/' . $order['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Current Status</label>
          <select name="status" class="form-select">
            <?php foreach ($statuses as $st): ?>
              <option value="<?= e($st) ?>" <?= ($order['status'] ?? '') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
            <?php endforeach; ?>
          </select>
          <small class="text-muted">Setting status to 'Cancelled' automatically restocks inventory.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Status Note / Audit Reason</label>
          <input type="text" name="note" class="form-control form-control-sm" placeholder="e.g. Dispatched via BlueDart express">
        </div>

        <button type="submit" class="btn btn-dark w-100 btn-sm">Update Status</button>
      </form>
    </div>

    <!-- Payment Status Updater -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Payment Verification</h6>
      <form action="<?= url('admin/orders/payment/' . $order['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-2">
          <div class="d-flex justify-content-between small text-muted">
            <span>Payment Method:</span>
            <strong class="text-dark"><?= strtoupper($order['payment_method']) ?></strong>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-select">
            <?php foreach ($paymentStatuses as $pst): ?>
              <option value="<?= e($pst) ?>" <?= ($order['payment_status'] ?? '') === $pst ? 'selected' : '' ?>><?= e($pst) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Payment Reference / Transaction ID</label>
          <input type="text" name="payment_reference" class="form-control form-control-sm" placeholder="e.g. PAY-UPI-982348" value="<?= e($order['payment_reference'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-outline-dark w-100 btn-sm">Record Payment Status</button>
      </form>
    </div>

    <!-- Shipment Tracking Details -->
    <div class="admin-form-card shadow-sm mb-4">
      <h6 class="fw-bold mb-3 border-bottom pb-2">Shipment Tracking Info</h6>
      <form action="<?= url('admin/orders/tracking/' . $order['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Courier Name</label>
          <input type="text" name="courier_name" class="form-control form-control-sm" placeholder="e.g. BlueDart Express" value="<?= e($order['tracking']['courier_name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Tracking / AWB Number</label>
          <input type="text" name="tracking_number" class="form-control form-control-sm" placeholder="e.g. BLU-894729183" value="<?= e($order['tracking']['tracking_number'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Tracking URL</label>
          <input type="url" name="tracking_url" class="form-control form-control-sm" placeholder="https://..." value="<?= e($order['tracking']['tracking_url'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-outline-primary w-100 btn-sm">Save Tracking Info</button>
      </form>
    </div>
  </div>
</div>

