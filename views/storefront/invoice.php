<?php
declare(strict_types=1);

/** @var array $order */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tax Invoice — <?= e($order['order_number']) ?> | HOMEIQ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #1e293b; background: #f8fafc; }
    .invoice-card { max-width: 800px; margin: 30px auto; background: #fff; padding: 40px; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    @media print {
      body { background: #fff; }
      .invoice-card { border: none; box-shadow: none; margin: 0; padding: 0; max-width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="container py-3 no-print text-center">
  <button onclick="window.print()" class="btn btn-dark btn-sm me-2"><i class="bi bi-printer me-1"></i> Print Invoice</button>
  <a href="javascript:window.close()" class="btn btn-outline-secondary btn-sm">Close Window</a>
</div>

<div class="invoice-card">
  <!-- Invoice Header -->
  <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
    <div>
      <h2 class="fw-bold tracking-tight mb-1" style="letter-spacing: 2px;">HOMEIQ</h2>
      <div class="small text-secondary">Technologies Private Limited</div>
      <div class="small text-secondary">402 Smart Tech Park, Cyber City, Bengaluru 560100</div>
      <div class="small text-secondary">GSTIN: 29AABCH1234F1Z8 • support@homeiq-security.com</div>
    </div>
    <div class="text-end">
      <h4 class="text-primary fw-bold mb-1">TAX INVOICE</h4>
      <div class="small text-muted"><strong>Invoice No:</strong> INV-<?= substr($order['order_number'], 4) ?></div>
      <div class="small text-muted"><strong>Order Ref:</strong> <?= e($order['order_number']) ?></div>
      <div class="small text-muted"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
    </div>
  </div>

  <!-- Bill To / Ship To -->
  <div class="row mb-4">
    <div class="col-6">
      <h6 class="small text-uppercase text-muted fw-bold">Customer & Shipping Details</h6>
      <div class="small">
        <strong><?= e($order['customer_name']) ?></strong><br>
        <?= e($order['shipping_address']['street'] ?? '') ?><br>
        <?= e($order['shipping_address']['city'] ?? '') ?>, <?= e($order['shipping_address']['state'] ?? '') ?> - <?= e($order['shipping_address']['pincode'] ?? '') ?><br>
        Phone: <?= e($order['customer_phone']) ?><br>
        Email: <?= e($order['customer_email']) ?>
      </div>
    </div>
    <div class="col-6 text-end">
      <h6 class="small text-uppercase text-muted fw-bold">Payment Information</h6>
      <div class="small">
        <strong>Payment Method:</strong> <?= strtoupper($order['payment_method']) ?><br>
        <strong>Payment Status:</strong> <span class="badge bg-secondary"><?= e($order['payment_status']) ?></span><br>
        <?php if (!empty($order['payment_reference'])): ?>
          <strong>Transaction Ref:</strong> <?= e($order['payment_reference']) ?><br>
        <?php endif; ?>
        <strong>Order Status:</strong> <?= e($order['status']) ?>
      </div>
    </div>
  </div>

  <!-- Line Items Table -->
  <table class="table table-bordered align-middle mb-4">
    <thead class="table-light small">
      <tr>
        <th style="width: 50%;">Item & Description</th>
        <th class="text-center" style="width: 15%;">SKU</th>
        <th class="text-center" style="width: 10%;">Qty</th>
        <th class="text-end" style="width: 12%;">Unit Price</th>
        <th class="text-end" style="width: 13%;">Total</th>
      </tr>
    </thead>
    <tbody class="small">
      <?php foreach ($order['items'] as $item): ?>
        <tr>
          <td>
            <strong><?= e($item['name']) ?></strong>
          </td>
          <td class="text-center text-muted"><?= e($item['sku']) ?></td>
          <td class="text-center"><?= $item['quantity'] ?></td>
          <td class="text-end"><?= format_price($item['price']) ?></td>
          <td class="text-end fw-semibold"><?= format_price($item['line_total']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot class="small">
      <tr>
        <td colspan="4" class="text-end text-muted">Subtotal:</td>
        <td class="text-end fw-semibold"><?= format_price($order['subtotal']) ?></td>
      </tr>
      <?php if ($order['discount'] > 0): ?>
        <tr class="text-success">
          <td colspan="4" class="text-end">Coupon Discount (<?= e($order['coupon_code'] ?? '') ?>):</td>
          <td class="text-end">- <?= format_price($order['discount']) ?></td>
        </tr>
      <?php endif; ?>
      <tr>
        <td colspan="4" class="text-end text-muted">Shipping & Handling:</td>
        <td class="text-end"><?= $order['shipping_amount'] == 0 ? 'FREE' : format_price($order['shipping_amount']) ?></td>
      </tr>
      <tr>
        <td colspan="4" class="text-end fw-bold fs-6">Total Payable:</td>
        <td class="text-end fw-bold fs-6 text-dark"><?= format_price($order['total_amount']) ?></td>
      </tr>
    </tfoot>
  </table>

  <!-- Footer Notes -->
  <div class="border-top pt-3 small text-secondary">
    <div><strong>Warranty & Installation:</strong> All HOMEIQ biometric locks include a 2-year official manufacturer warranty. Retain this invoice for warranty verification.</div>
    <div class="mt-1">For support or technical assistance: call <?= config('brand.support_phone') ?> or email <?= config('brand.support_email') ?></div>
  </div>
</div>

</body>
</html>

