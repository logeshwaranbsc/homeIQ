<?php
declare(strict_types=1);

use App\Core\View;
use App\Core\Request;
use App\Repositories\SettingsRepository;

$settingsRepo = new SettingsRepository();
$settings = $settingsRepo->all();

$initialType = (new Request())->query('type', 'general');
$isBulkInitial = ($initialType === 'bulk');

$breadcrumbs = [
    ['label' => 'Contact Us', 'url' => null]
];
?>
<div class="container py-4">
  <?= View::component('breadcrumbs', ['items' => $breadcrumbs]) ?>

  <div class="row g-4 g-lg-5">
    <div class="col-lg-5">
      <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Direct Assistance</span>
      <h1 class="h2 fw-bold mb-3">Speak With a Smart Security Consultant</h1>
      <p class="text-muted leading-relaxed mb-4">
        Have questions regarding door mortise dimensions, multi-property lock synchronization, or warranty claims? Our technical specialists are here to guide you.
      </p>

      <div class="d-flex flex-column gap-3 mb-4 text-dark">
        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-geo-alt-fill"></i></div>
          <div>
            <strong>Corporate Experience Centre</strong><br>
            <span class="text-muted small"><?= e($settings['address'] ?? '402 Smart Tech Park, Cyber City, Bengaluru 560100') ?></span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-telephone-fill"></i></div>
          <div>
            <strong>Technical Support Hotline</strong><br>
            <span class="text-muted small"><?= e($settings['support_phone'] ?? '+91 98765 43210') ?> (Mon - Sat, 9am - 8pm)</span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3">
          <div class="rounded bg-light p-2 text-primary fs-5 border"><i class="bi bi-envelope-fill"></i></div>
          <div>
            <strong>Email Support</strong><br>
            <span class="text-muted small"><?= e($settings['support_email'] ?? 'support@homeiq-security.com') ?></span>
          </div>
        </div>
      </div>

      <!-- Quick B2B Info Box -->
      <div class="p-3 rounded-3 bg-light border">
        <div class="d-flex align-items-center gap-2 mb-2">
          <i class="bi bi-shield-check text-warning fs-5"></i>
          <strong class="small text-uppercase letter-spacing-1">Enterprise & Commercial Advantage</strong>
        </div>
        <ul class="text-muted small mb-0 ps-3">
          <li>Custom volume pricing tiers for 10+ locks</li>
          <li>Direct developer master keying & Tuya PMS gateway</li>
          <li>Pan-India on-site survey & deployment engineers</li>
        </ul>
      </div>
    </div>

    <!-- Contact Form with Switcher -->
    <div class="col-lg-7">
      <div class="card border rounded-3 p-4 p-md-5 shadow-sm">
        
        <!-- Form Switching Pill Tabs -->
        <div class="d-flex mb-4">
          <div class="contact-form-tabs">
            <button type="button" class="contact-tab-btn <?= !$isBulkInitial ? 'active' : '' ?>" id="tabGeneralInquiry" onclick="switchContactForm('general')">
              <i class="bi bi-chat-left-text me-1"></i> General Inquiry
            </button>
            <button type="button" class="contact-tab-btn <?= $isBulkInitial ? 'active' : '' ?>" id="tabBulkOrder" onclick="switchContactForm('bulk')">
              <i class="bi bi-box-seam me-1"></i> Bulk / B2B Order
            </button>
          </div>
        </div>

        <!-- Header: General Inquiry -->
        <div id="formHeaderGeneral" style="<?= $isBulkInitial ? 'display: none;' : '' ?>">
          <h4 class="fw-bold mb-1">Send an Inquiry</h4>
          <p class="text-muted small mb-4">Have questions about door compatibility or installation? Drop us a note.</p>
        </div>

        <!-- Header: Bulk / B2B Order -->
        <div id="formHeaderBulk" style="<?= !$isBulkInitial ? 'display: none;' : '' ?>">
          <h4 class="fw-bold mb-1">Bulk & Commercial Orders</h4>
          <p class="text-muted small mb-4">Volume discounts, dedicated account management & tailored solutions for builders, hotels, and architects.</p>
        </div>

        <form action="<?= url('contact') ?>" method="POST" id="contactFormMain">
          <?= csrf_field() ?>
          <input type="hidden" name="inquiry_type" id="inquiryTypeInput" value="<?= $isBulkInitial ? 'bulk' : 'general' ?>">

          <!-- 1. GENERAL INQUIRY FIELDS -->
          <div id="generalFieldsContainer" style="<?= $isBulkInitial ? 'display: none;' : '' ?>">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Your Name *</label>
                <input type="text" name="name" id="generalName" class="form-control" placeholder="Full Name" <?= !$isBulkInitial ? 'required' : '' ?>>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" id="generalEmail" class="form-control" placeholder="name@example.com" <?= !$isBulkInitial ? 'required' : '' ?>>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" id="generalPhone" class="form-control" placeholder="10-digit mobile">
              </div>
              <div class="col-md-6">
                <label class="form-label">Subject</label>
                <select name="subject" class="form-select">
                  <option value="Pre-Purchase Compatibility">Pre-Purchase Door Compatibility</option>
                  <option value="Installation Inquiry">Doorstep Installation Request</option>
                  <option value="Technical Support">Technical & App Support</option>
                  <option value="General Inquiry">Other Inquiry</option>
                </select>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label">Message Details *</label>
              <textarea name="message" id="generalMessage" rows="5" class="form-control" placeholder="Describe your door type, thickness, or specific question..." <?= !$isBulkInitial ? 'required' : '' ?>></textarea>
            </div>
          </div>

          <!-- 2. BULK ORDER FIELDS -->
          <div id="bulkFieldsContainer" style="<?= !$isBulkInitial ? 'display: none;' : '' ?>">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Contact Person Name *</label>
                <input type="text" name="bulk_name" id="bulkName" class="form-control" placeholder="Full Name" <?= $isBulkInitial ? 'required' : '' ?>>
              </div>
              <div class="col-md-6">
                <label class="form-label">Company / Business Name *</label>
                <input type="text" name="company" id="bulkCompany" class="form-control" placeholder="e.g. Prestige Group / Urban Living" <?= $isBulkInitial ? 'required' : '' ?>>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Phone / WhatsApp Number *</label>
                <input type="tel" name="bulk_phone" id="bulkPhone" class="form-control" placeholder="10-digit mobile" <?= $isBulkInitial ? 'required' : '' ?>>
              </div>
              <div class="col-md-6">
                <label class="form-label">Project Location (City / State)</label>
                <input type="text" name="location" class="form-control" placeholder="e.g. Bengaluru, Karnataka">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Project Type *</label>
                <select name="project_type" class="form-select">
                  <option value="Residential Apartments">Residential Apartments / Gated Society</option>
                  <option value="Hotels & Hospitality">Hotels, Resorts & Hospitality</option>
                  <option value="Commercial Offices">Commercial & Corporate Offices</option>
                  <option value="Villas & Luxury Homes">Villas & Luxury Communities</option>
                  <option value="Architect & Interior Design">Architect & Interior Design Firm</option>
                  <option value="Retail & Showrooms">Retail & Showrooms</option>
                  <option value="Other Project">Other Commercial Project</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Estimated Quantity *</label>
                <input type="number" name="quantity" id="bulkQuantity" class="form-control" placeholder="e.g. 25" min="1" <?= $isBulkInitial ? 'required' : '' ?>>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Target Implementation Timeline</label>
              <select name="timeline" class="form-select">
                <option value="Immediate (< 15 days)">Immediate (&lt; 15 days)</option>
                <option value="1 - 2 Months">1 – 2 Months</option>
                <option value="3 - 6 Months">3 – 6 Months</option>
                <option value="Planning & Tender Stage">Planning & Tender Stage</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label">Project Requirements & Preferred Lock Features *</label>
              <textarea name="bulk_message" id="bulkMessage" rows="4" class="form-control" placeholder="Specify door thickness/materials, preferred biometric modes (Face Scan, Fingerprint, Palm Vein, Tuya App), installation assistance required..." <?= $isBulkInitial ? 'required' : '' ?>></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-hiq-primary px-4 py-2" id="submitBtn">
            <span id="submitBtnText"><?= $isBulkInitial ? 'Request Bulk Quote' : 'Send Inquiry' ?></span> <i class="bi bi-send ms-2"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function switchContactForm(type) {
  var tabGeneral = document.getElementById('tabGeneralInquiry');
  var tabBulk = document.getElementById('tabBulkOrder');
  var headerGeneral = document.getElementById('formHeaderGeneral');
  var headerBulk = document.getElementById('formHeaderBulk');
  var generalFields = document.getElementById('generalFieldsContainer');
  var bulkFields = document.getElementById('bulkFieldsContainer');
  var inquiryInput = document.getElementById('inquiryTypeInput');
  var submitBtnText = document.getElementById('submitBtnText');

  var genName = document.getElementById('generalName');
  var genEmail = document.getElementById('generalEmail');
  var genMessage = document.getElementById('generalMessage');

  var bulkName = document.getElementById('bulkName');
  var bulkPhone = document.getElementById('bulkPhone');
  var bulkCompany = document.getElementById('bulkCompany');
  var bulkQuantity = document.getElementById('bulkQuantity');
  var bulkMessage = document.getElementById('bulkMessage');

  if (type === 'bulk') {
    tabGeneral.classList.remove('active');
    tabBulk.classList.add('active');

    headerGeneral.style.display = 'none';
    headerBulk.style.display = 'block';

    generalFields.style.display = 'none';
    bulkFields.style.display = 'block';

    inquiryInput.value = 'bulk';
    submitBtnText.textContent = 'Request Bulk Quote';

    // Toggle validation attributes
    if (genName) genName.removeAttribute('required');
    if (genEmail) genEmail.removeAttribute('required');
    if (genMessage) genMessage.removeAttribute('required');

    if (bulkName) bulkName.setAttribute('required', '');
    if (bulkPhone) bulkPhone.setAttribute('required', '');
    if (bulkCompany) bulkCompany.setAttribute('required', '');
    if (bulkQuantity) bulkQuantity.setAttribute('required', '');
    if (bulkMessage) bulkMessage.setAttribute('required', '');
  } else {
    tabBulk.classList.remove('active');
    tabGeneral.classList.add('active');

    headerBulk.style.display = 'none';
    headerGeneral.style.display = 'block';

    bulkFields.style.display = 'none';
    generalFields.style.display = 'block';

    inquiryInput.value = 'general';
    submitBtnText.textContent = 'Send Inquiry';

    // Toggle validation attributes
    if (bulkName) bulkName.removeAttribute('required');
    if (bulkPhone) bulkPhone.removeAttribute('required');
    if (bulkCompany) bulkCompany.removeAttribute('required');
    if (bulkQuantity) bulkQuantity.removeAttribute('required');
    if (bulkMessage) bulkMessage.removeAttribute('required');

    if (genName) genName.setAttribute('required', '');
    if (genEmail) genEmail.setAttribute('required', '');
    if (genMessage) genMessage.setAttribute('required', '');
  }
}
</script>

