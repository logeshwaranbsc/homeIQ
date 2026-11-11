<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array|null $order
 * @var string|null $error
 * @var string $orderNumber
 * @var string $contact
 */
$breadcrumbs = [
    ['label' => 'Order Tracking', 'url' => null]
];

$allStatuses = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
$currentStatusIdx = $order ? array_search($order['status'], $allStatuses, true) : -1;
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="text-center mb-4">
        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Order Status</span>
        <h1 class="h2 fw-bold">Track Your Smart Lock Shipment</h1>
        <p class="text-muted small">Enter your HOMEIQ Order Number and your registered Email or Mobile Number.</p>
      </div>

      <!-- Search Form -->
      <div class="card border rounded-3 p-4 shadow-sm mb-4">
        <form action="<?= url('order-tracking') ?>" method="GET">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Order Number *</label>
              <input type="text" name="order_number" class="form-control" placeholder="e.g. HIQ-20261029-XXXX" value="<?= e($orderNumber) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Email or 10-Digit Mobile *</label>
              <input type="text" name="contact" class="form-control" placeholder="Email or Mobile used during checkout" value="<?= e($contact) ?>" required>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="submit" class="btn btn-hiq-primary">
              <i class="bi bi-search me-1"></i> Track Order
            </button>
          </div>
        </form>
      </div>

      <!-- Error State -->
      <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
          <div><?= e($error) ?></div>
        </div>
      <?php endif; ?>

      <!-- Order Tracking Details Found -->
      <?php if ($order): ?>
        <div class="card border rounded-3 p-4 shadow-sm mb-4">
          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4 flex-wrap gap-2">
            <div>
              <h5 class="fw-bold m-0">Order #<?= e($order['order_number']) ?></h5>
              <small class="text-muted">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-primary px-3 py-2 fs-6"><?= e($order['status']) ?></span>
              <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-printer"></i>
              </a>
            </div>
          </div>

          <!-- Status Timeline Tracker -->
          <h6 class="small text-uppercase text-muted fw-bold mb-3">Live Progress</h6>
          <div class="d-flex justify-content-between position-relative mb-5 px-2">
            <!-- Progress line -->
            <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-secondary bg-opacity-25" style="height: 4px; z-index: 1;"></div>
            <?php 
              $percent = $currentStatusIdx >= 0 ? ($currentStatusIdx / (count($allStatuses) - 1)) * 100 : 0;
            ?>
            <div class="position-absolute top-50 start-0 translate-middle-y bg-primary" style="height: 4px; width: <?= $percent ?>%; z-index: 2;"></div>

            <?php foreach ($allStatuses as $idx => $st): ?>
              <?php 
                $isDone = $currentStatusIdx !== false && $idx <= $currentStatusIdx;
                $isCurrent = $currentStatusIdx !== false && $idx === $currentStatusIdx;
              ?>
              <div class="text-center position-relative" style="z-index: 3;">
                <div class="rounded-circle timeline-step-circle d-flex align-items-center justify-content-center mx-auto mb-2 <?= $isDone ? 'bg-primary text-white' : 'bg-light text-muted border' ?>" style="width: 36px; height: 36px; font-weight: bold;">
                  <?php if ($isDone): ?>
                    <i class="bi bi-check"></i>
                  <?php else: ?>
                    <?= $idx + 1 ?>
                  <?php endif; ?>
                </div>
                <div class="small timeline-step-label <?= $isCurrent ? 'fw-bold text-primary' : 'text-muted' ?>" style="font-size: 0.75rem;">
                  <?= e($st) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Tracking Courier Details if available -->
          <?php if (!empty($order['tracking']['courier_name']) || !empty($order['tracking']['tracking_number'])): ?>
            <div class="alert alert-info d-flex align-items-center justify-content-between p-3 mb-4">
              <div>
                <strong><i class="bi bi-box-seam me-1"></i>Courier:</strong> <?= e($order['tracking']['courier_name'] ?? 'Express Logistics') ?> |
                <strong>Tracking No:</strong> <?= e($order['tracking']['tracking_number'] ?? 'N/A') ?>
              </div>
              <?php if (!empty($order['tracking']['tracking_url'])): ?>
                <a href="<?= e($order['tracking']['tracking_url']) ?>" target="_blank" class="btn btn-sm btn-info text-dark fw-bold">
                  Track on Courier Site <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <!-- Purchased Items -->
          <h6 class="small text-uppercase text-muted fw-bold mb-2">Items in Shipment</h6>
          <div class="list-group mb-3">
            <?php foreach ($order['items'] as $item): ?>
              <div class="list-group-item d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-3">
                  <img src="<?= upload_url($item['image']) ?>" alt="<?= e($item['name']) ?>" class="img-thumbnail" style="width: 48px; height: 48px; object-fit: contain;" onerror="this.onerror=null; this.src='<?= asset('assets/images/placeholder-lock.svg') ?>';">
                  <div>
                    <h6 class="mb-0 fw-bold small"><?= e($item['name']) ?></h6>
                    <small class="text-muted">SKU: <?= e($item['sku']) ?> • Qty: <?= $item['quantity'] ?></small>
                  </div>
                </div>
                <span class="fw-bold small"><?= format_price($item['line_total']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

