<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Terms and Conditions', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <h1 class="h2 fw-bold mb-4">Terms and Conditions of Sale</h1>
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm text-secondary leading-relaxed">
        <h5 class="fw-bold text-dark">1. General Overview</h5>
        <p>By accessing the HOMEIQ e-commerce website or placing an order for our digital smart locks and accessories, you agree to comply with and be bound by these Terms and Conditions.</p>

        <h5 class="fw-bold text-dark mt-4">2. Product Specifications & Pricing</h5>
        <p>All digital smart locks, mortises, and security accessories sold on this website are accurately represented based on verified factory specifications. Prices are listed in Indian Rupees (INR) and are inclusive of applicable GST taxes unless noted otherwise.</p>

        <h5 class="fw-bold text-dark mt-4">3. Installation Responsibility</h5>
        <p>Where complimentary professional installation is included, it is the homeowner's responsibility to grant authorized technicians reasonable access to the property and ensure the door frame is structurally sound for mortise fitting.</p>

        <h5 class="fw-bold text-dark mt-4">4. Manufacturer Warranty</h5>
        <p>HOMEIQ warrants all electronic components and mechanical mortise locks against defects in materials and workmanship for a period of two (2) years from the date of purchase. Physical damage resulting from vandalism, extreme weather submersion, or unauthorized lock tampering is not covered.</p>
      </div>
    </div>
  </div>
</div>

