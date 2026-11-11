<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $orders */
$breadcrumbs = [
    ['label' => 'My Account', 'url' => 'account'],
    ['label' => 'My Orders', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-4">
    <!-- Sidebar Account Nav -->
    <div class="col-lg-3">
      <div class="card border rounded-3 p-3 shadow-sm">
        <div class="list-group list-group-flush small">
          <a href="<?= url('account') ?>" class="list-group-item list-group-item-action">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
          </a>
          <a href="<?= url('account/orders') ?>" class="list-group-item list-group-item-action active fw-bold">
            <i class="bi bi-box-seam me-2"></i>My Orders (<?= count($orders) ?>)
          </a>
          <a href="<?= url('account/profile') ?>" class="list-group-item list-group-item-action">
            <i class="bi bi-person-gear me-2"></i>Profile & Password
          </a>
          <a href="<?= url('wishlist') ?>" class="list-group-item list-group-item-action">
            <i class="bi bi-heart me-2"></i>My Wishlist
          </a>
          <a href="<?= url('logout') ?>" class="list-group-item list-group-item-action text-danger">
            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
          </a>
        </div>
      </div>
    </div>

    <!-- Main Orders List -->
    <div class="col-lg-9">
      <h4 class="fw-bold mb-4">My Orders History</h4>

      <?php if (empty($orders)): ?>
        <div class="card border p-5 text-center shadow-sm">
          <i class="bi bi-receipt fs-1 text-muted mb-3"></i>
          <h5>No Orders Placed Yet</h5>
          <p class="text-muted small">When you purchase HOMEIQ digital locks, your order records, warranties and tracking information will be recorded here.</p>
          <div><a href="<?= url('shop') ?>" class="btn btn-hiq-primary">Browse Digital Locks</a></div>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($orders as $order): ?>
            <div class="card border rounded-3 shadow-sm p-3 p-sm-4">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3 mb-3">
                <div>
                  <h6 class="fw-bold m-0">Order #<?= e($order['order_number']) ?></h6>
                  <small class="text-muted">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></small>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-primary"><?= e($order['status']) ?></span>
                  <span class="badge bg-secondary"><?= e($order['payment_status']) ?></span>
                </div>
              </div>

              <!-- Items in this order -->
              <div class="d-flex flex-column gap-2 mb-3">
                <?php foreach ($order['items'] as $item): ?>
                  <div class="d-flex align-items-center justify-content-between gap-2 small">
                    <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                      <img src="<?= upload_url($item['image']) ?>" alt="" class="flex-shrink-0" style="width: 36px; height: 36px; object-fit: contain;">
                      <span class="text-truncate"><?= e($item['name']) ?> (×<?= $item['quantity'] ?>)</span>
                    </div>
                    <span class="fw-semibold flex-shrink-0"><?= format_price($item['line_total']) ?></span>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="d-flex justify-content-between align-items-center border-top pt-3 flex-wrap gap-2">
                <div>
                  <span class="text-muted small">Total: </span>
                  <strong class="fs-6"><?= format_price($order['total_amount']) ?></strong>
                </div>
                <div class="d-flex gap-2">
                  <a href="<?= url('account/orders/' . $order['order_number']) ?>" class="btn btn-outline-dark btn-sm">
                    View Order Details
                  </a>
                  <a href="<?= url('order/invoice/' . $order['order_number']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Print Invoice">
                    <i class="bi bi-printer"></i>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

