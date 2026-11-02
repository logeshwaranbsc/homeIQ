<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Shipping & Delivery Policy', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <h1 class="h2 fw-bold mb-4">Shipping & Delivery Policy</h1>
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm text-secondary leading-relaxed">
        <h5 class="fw-bold text-dark">1. Shipping Coverage & Dispatch Timeline</h5>
        <p>HOMEIQ ships across all major metropolitan and tier-1/tier-2 PIN codes throughout India. In-stock digital door locks and smart accessories are packaged in tamper-evident reinforced boxes and dispatched within 24 to 48 business hours from our central distribution hub.</p>

        <h5 class="fw-bold text-dark mt-4">2. Free Express Shipping</h5>
        <p>All orders with a subtotal of ₹5,000 or greater qualify automatically for <strong>Free Express Shipping</strong>. For orders below this threshold, a flat shipping fee of ₹250 is applied at checkout.</p>

        <h5 class="fw-bold text-dark mt-4">3. Estimated Delivery Times</h5>
        <ul>
          <li><strong>Metro Cities (Bengaluru, Mumbai, Delhi-NCR, Chennai, Hyderabad, Kolkata):</strong> 2 to 3 business days.</li>
          <li><strong>Rest of India:</strong> 4 to 6 business days.</li>
        </ul>

        <h5 class="fw-bold text-dark mt-4">4. Doorstep Installation Coordination</h5>
        <p>If your smart lock qualifies for complimentary installation, our customer support concierge connects you with an authorized local installer once transit confirmation is received. Installation occurs within 24 to 48 hours of product delivery.</p>

        <h5 class="fw-bold text-dark mt-4">5. Shipment Tracking</h5>
        <p>Once dispatched, you receive SMS and email updates containing your consignment tracking number. You can also monitor real-time shipping milestones anytime on our <a href="<?= url('order-tracking') ?>">Track Order</a> page.</p>
      </div>
    </div>
  </div>
</div>

