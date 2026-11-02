<?php
declare(strict_types=1);

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Frequently Asked Questions', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="text-center max-w-700 mx-auto mb-5">
    <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Support Knowledge Base</span>
    <h1 class="display-6 fw-bold">Frequently Asked Questions</h1>
    <p class="text-muted">Answers to common homeowner questions regarding smart lock compatibility, installation, biometric reliability, and battery life.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="accordion shadow-sm" id="mainFaqAccordion">
        <!-- Q1 -->
        <div class="accordion-item border mb-2 rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqQ1">
              What happens if the smart lock battery drains completely?
            </button>
          </h2>
          <div id="faqQ1" class="accordion-collapse collapse show" data-bs-parent="#mainFaqAccordion">
            <div class="accordion-body text-secondary small leading-relaxed">
              HOMEIQ smart locks have a dual emergency backup system. First, all models include two physical high-security mechanical override keys that work independently of electronic power. Second, every model features an external USB-C emergency power terminal at the bottom of the exterior escutcheon; simply plug in any standard smartphone power bank for instantaneous power to unlock via fingerprint or PIN.
            </div>
          </div>
        </div>

        <!-- Q2 -->
        <div class="accordion-item border mb-2 rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqQ2">
              Will HOMEIQ smart locks fit my existing main wooden or metal door?
            </button>
          </h2>
          <div id="faqQ2" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
            <div class="accordion-body text-secondary small leading-relaxed">
              Yes. Our mortise locks accommodate door thicknesses ranging from 35mm to 90mm, which covers over 98% of standard residential wooden, teak, veneer, steel security, and composite doors. If you have an unconventional door profile, contact our support team with a photo of your door edge for pre-purchase confirmation.
            </div>
          </div>
        </div>

        <!-- Q3 -->
        <div class="accordion-item border mb-2 rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqQ3">
              How does the free professional doorstep installation work?
            </button>
          </h2>
          <div id="faqQ3" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
            <div class="accordion-body text-secondary small leading-relaxed">
              When your order is shipped, our dispatch team coordinates with a certified installer in your city. The technician contacts you to schedule an installation slot that works for you. They precision-cut the mortise pocket using specialized templates, mount the interior and exterior lock bodies, test all biometric functions, and assist in setting up your smartphone app.
            </div>
          </div>
        </div>

        <!-- Q4 -->
        <div class="accordion-item border mb-2 rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqQ4">
              Can someone spoof the 3D Face Recognition with a photograph?
            </button>
          </h2>
          <div id="faqQ4" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
            <div class="accordion-body text-secondary small leading-relaxed">
              No. HOMEIQ AI Face Recognition locks utilize active 3D structured infrared light that projects tens of thousands of microscopic dots to calculate true three-dimensional facial depth and subcutaneous living skin temperature. Photographs, screen displays, and 3D silicone masks are automatically rejected by the liveness detection algorithm.
            </div>
          </div>
        </div>

        <!-- Q5 -->
        <div class="accordion-item border mb-2 rounded overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqQ5">
              How long does the battery last on a single charge?
            </button>
          </h2>
          <div id="faqQ5" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
            <div class="accordion-body text-secondary small leading-relaxed">
              Our high-capacity lithium-ion models typically deliver 8 to 12 months of operation under normal household usage (15-20 accesses per day). The lock alerts you with audio chimes and smartphone push notifications whenever battery levels dip below 20%.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

