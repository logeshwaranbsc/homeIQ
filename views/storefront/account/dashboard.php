<?php
declare(strict_types=1);

use App\Core\View;

/**
 * @var array $customer
 * @var array $recentOrders
 * @var int $totalOrders
 */
$breadcrumbs = [
    ['label' => 'My Account', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-4">
    <!-- Sidebar Account Nav -->
    <div class="col-lg-3">
      <div class="card border rounded-3 p-3 shadow-sm">
        <div class="text-center py-3 border-bottom mb-3">
          <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
            <?= strtoupper(substr($customer['name'] ?? 'U', 0, 1)) ?>
          </div>
          <h6 class="fw-bold m-0"><?= e($customer['name']) ?></h6>
          <small class="text-muted"><?= e($customer['email']) ?></small>
        </div>

        <div class="list-group list-group-flush small">
          <a href="<?= url('account') ?>" class="list-group-item list-group-item-action active fw-bold">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
          </a>
          <a href="<?= url('account/orders') ?>" class="list-group-item list-group-item-action">
            <i class="bi bi-box-seam me-2"></i>My Orders (<?= $totalOrders ?>)
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

    <!-- Main Content -->
    <div class="col-lg-9">
      <!-- Welcome Card -->
      <div class="card border rounded-3 p-4 shadow-sm mb-4 bg-light">
        <h4 class="fw-bold mb-1">Welcome back, <?= e($customer['name']) ?>!</h4>
        <p class="text-muted small m-0">From your HOMEIQ dashboard you can check your orders, update contact addresses, and view invoices.</p>
      </div>

      <!-- Quick Metrics -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card border p-3 shadow-sm text-center">
            <span class="text-muted small text-uppercase fw-semibold">Total Orders</span>
            <h3 class="fw-bold my-1 text-primary"><?= $totalOrders ?></h3>
            <a href="<?= url('account/orders') ?>" class="small text-decoration-none">View All Orders</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border p-3 shadow-sm text-center">
            <span class="text-muted small text-uppercase fw-semibold">Saved Addresses</span>
            <h3 class="fw-bold my-1 text-dark"><?= count($customer['addresses'] ?? []) ?></h3>
            <a href="<?= url('account/profile') ?>" class="small text-decoration-none">Manage Address</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border p-3 shadow-sm text-center">
            <span class="text-muted small text-uppercase fw-semibold">Warranty Support</span>
            <h3 class="fw-bold my-1 text-success"><i class="bi bi-shield-check"></i></h3>
            <a href="<?= url('contact') ?>" class="small text-decoration-none">Contact Support</a>
          </div>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="card border rounded-3 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold m-0">Recent Orders</h6>
          <a href="<?= url('account/orders') ?>" class="small text-decoration-none">View All</a>
        </div>
        <?php if (empty($recentOrders)): ?>
          <div class="p-4 text-center text-muted">
            You haven't placed any orders yet. Ready to secure your home?
            <div class="mt-2"><a href="<?= url('shop') ?>" class="btn btn-sm btn-dark">Shop Smart Locks</a></div>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle mb-0 small">
              <thead class="table-light">
                <tr>
                  <th>Order #</th>
                  <th>Date</th>
                  <th>Total</th>
                  <th>Payment</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentOrders as $order): ?>
                  <tr>
                    <td><strong><?= e($order['order_number']) ?></strong></td>
                    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    <td><?= format_price($order['total_amount']) ?></td>
                    <td><span class="badge bg-secondary"><?= e($order['payment_status']) ?></span></td>
                    <td><span class="badge bg-primary"><?= e($order['status']) ?></span></td>
                    <td>
                      <a href="<?= url('account/orders/' . $order['order_number']) ?>" class="btn btn-sm btn-outline-dark">
                        Details
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

