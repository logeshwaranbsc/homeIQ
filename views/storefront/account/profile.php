<?php
declare(strict_types=1);

use App\Core\View;

/** @var array $customer */
$breadcrumbs = [
    ['label' => 'My Account', 'url' => 'account'],
    ['label' => 'Profile Settings', 'url' => null]
];
$addr = $customer['addresses'][0] ?? [];
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
          <a href="<?= url('account/orders') ?>" class="list-group-item list-group-item-action">
            <i class="bi bi-box-seam me-2"></i>My Orders
          </a>
          <a href="<?= url('account/profile') ?>" class="list-group-item list-group-item-action active fw-bold">
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

    <!-- Main Column -->
    <div class="col-lg-9">
      <!-- Profile Information -->
      <div class="card border rounded-3 p-4 shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Personal & Delivery Details</h5>
        <form action="<?= url('account/profile') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="name" class="form-control" required value="<?= e($customer['name']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address (Read-only)</label>
              <input type="email" class="form-control bg-light" value="<?= e($customer['email']) ?>" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Phone *</label>
              <input type="tel" name="phone" class="form-control" value="<?= e($customer['phone'] ?? '') ?>">
            </div>
          </div>

          <h6 class="fw-bold mt-4 mb-3 border-bottom pb-2">Default Installation & Delivery Address</h6>
          <div class="row g-3 mb-4">
            <div class="col-12">
              <label class="form-label">Street / Apartment / Door No.</label>
              <input type="text" name="address" class="form-control" value="<?= e($addr['street'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-control" value="<?= e($addr['city'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">State</label>
              <input type="text" name="state" class="form-control" value="<?= e($addr['state'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">PIN Code</label>
              <input type="text" name="pincode" class="form-control" maxlength="6" value="<?= e($addr['pincode'] ?? '') ?>">
            </div>
          </div>

          <button type="submit" class="btn btn-hiq-primary">Save Profile Changes</button>
        </form>
      </div>

      <!-- Password Change -->
      <div class="card border rounded-3 p-4 shadow-sm">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Change Password</h5>
        <form action="<?= url('account/password') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label">Current Password *</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">New Password *</label>
              <input type="password" name="new_password" class="form-control" required minlength="6">
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm New Password *</label>
              <input type="password" name="new_password_confirmation" class="form-control" required minlength="6">
            </div>
          </div>

          <button type="submit" class="btn btn-outline-dark">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

