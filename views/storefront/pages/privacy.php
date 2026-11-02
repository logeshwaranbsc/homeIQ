<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Privacy Policy', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <h1 class="h2 fw-bold mb-4">Privacy & Biometric Data Policy</h1>
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm text-secondary leading-relaxed">
        <h5 class="fw-bold text-dark">1. Commitment to Homeowner Security</h5>
        <p>HOMEIQ Technologies Pvt Ltd prioritizes the privacy and security of our customers. This policy governs how we collect, process, and protect your personal information.</p>

        <h5 class="fw-bold text-dark mt-4">2. Local Biometric Data Storage</h5>
        <p><strong>Crucial Security Principle:</strong> HOMEIQ smart door locks store biometric feature representations (such as encrypted minutiae points of fingerprints and 3D facial vectors) exclusively on the hardware's secure element chip inside the lock body itself. Your raw fingerprints, palm vein images, and facial scans are never uploaded to our cloud servers.</p>

        <h5 class="fw-bold text-dark mt-4">3. Customer Information We Collect</h5>
        <p>We collect only the information required to process and deliver your orders:</p>
        <ul>
          <li>Name, delivery address, contact email, and mobile telephone number.</li>
          <li>Transactional records and invoice documentation.</li>
          <li>Customer service notes regarding door installation specifications.</li>
        </ul>

        <h5 class="fw-bold text-dark mt-4">4. No Unauthorized Sharing</h5>
        <p>We do not sell, rent, or lease customer data to third-party advertising networks. Data is shared strictly with fulfillment partners (couriers and certified installers) exclusively for service execution.</p>
      </div>
    </div>
  </div>
</div>

