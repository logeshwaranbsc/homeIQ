<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Return and Refund Policy', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <h1 class="h2 fw-bold mb-4">Return & Refund Policy</h1>
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm text-secondary leading-relaxed">
        <h5 class="fw-bold text-dark">1. 7-Day Return Window</h5>
        <p>We stand behind the engineering excellence of HOMEIQ smart locks. If you discover that the purchased model is physically incompatible with your door profile, you may initiate a return or exchange request within 7 calendar days from the date of recorded delivery.</p>

        <h5 class="fw-bold text-dark mt-4">2. Return Eligibility Conditions</h5>
        <ul>
          <li>The lock must remain unmounted and free of scratches, chisel alterations, or cosmetic blemishes.</li>
          <li>All original box contents—including internal mortise, keys, battery, screws, and RFID cards—must be returned intact.</li>
          <li>Original invoice and packaging materials must accompany the return consignment.</li>
        </ul>

        <h5 class="fw-bold text-dark mt-4">3. Refund Processing</h5>
        <p>Following reverse pickup and technical inspection at our quality assurance centre, eligible refunds are credited to the original mode of payment within 5 to 7 business days. For Cash on Delivery orders, refunds are issued via direct NEFT/IMPS bank transfer.</p>

        <h5 class="fw-bold text-dark mt-4">4. Defective or Damaged Products</h5>
        <p>In the rare event of transit damage or manufacturing defects, contact our helpline within 48 hours for immediate replacement under our 2-Year Official Warranty program.</p>
      </div>
    </div>
  </div>
</div>

