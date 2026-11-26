<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $categories
 * @var array $platforms
 * @var string $declarationText
 * @var array $errors
 * @var array $old
 */
?>
<div class="container py-4 py-md-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-decoration-none text-secondary"><i class="bi bi-house-door me-1"></i>Home</a></li>
      <li class="breadcrumb-item active text-dark" aria-current="page">Warranty Registration</li>
    </ol>
  </nav>

  <!-- Hero Header -->
  <div class="row justify-content-center mb-4">
    <div class="col-lg-10 text-center">
      <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(254, 107, 37, 0.1); color: #FE6B25; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.5px;">
        <i class="bi bi-shield-check"></i> OFFICIAL HOMEIQ PROTECTION
      </div>
      <h1 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); letter-spacing: -0.5px;">
        Register Your <span style="color: #FE6B25;">HOMEIQ Product</span>
      </h1>
      <p class="fw-semibold text-secondary mb-2" style="font-size: 1.1rem;">
        Your HOMEIQ product. Your peace of mind.
      </p>
      <p class="text-secondary mx-auto" style="max-width: 680px; font-size: 0.92rem; line-height: 1.6;">
        Thank you for choosing HOMEIQ! Register your product to keep your purchase and warranty details on record and make future service support easier.
      </p>
    </div>
  </div>

  <!-- Trust Badges Strip -->
  <div class="row justify-content-center mb-4">
    <div class="col-lg-10">
      <div class="row g-2 g-md-3 text-center">
        <div class="col-4">
          <div class="p-2 p-md-3 bg-white border rounded-3 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
            <i class="bi bi-patch-check text-warning fs-4 mb-1"></i>
            <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Genuine Warranty</span>
            <span class="text-muted" style="font-size: 0.72rem;">Official manufacturer backing</span>
          </div>
        </div>
        <div class="col-4">
          <div class="p-2 p-md-3 bg-white border rounded-3 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
            <i class="bi bi-clock-history text-warning fs-4 mb-1"></i>
            <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Quick Verification</span>
            <span class="text-muted" style="font-size: 0.72rem;">Reviewed within 24-48 hrs</span>
          </div>
        </div>
        <div class="col-4">
          <div class="p-2 p-md-3 bg-white border rounded-3 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
            <i class="bi bi-headset text-warning fs-4 mb-1"></i>
            <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Priority Service</span>
            <span class="text-muted" style="font-size: 0.72rem;">Dedicated doorstep tech support</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Registration Form Card -->
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center gap-2 mb-4">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <div><?= e($errors['general']) ?></div>
        </div>
      <?php endif; ?>

      <form action="<?= url('warranty-registration') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-4 p-md-5 rounded-4 border shadow-sm" id="warrantyRegistrationForm" novalidate>
        <?= csrf_field() ?>

        <!-- SECTION 1: CUSTOMER DETAILS -->
        <div class="mb-4 pb-3 border-bottom">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge rounded-circle p-2 d-inline-flex align-items-center justify-content-center text-white" style="background: #FE6B25; width: 28px; height: 28px; font-size: 0.85rem;">1</span>
            <h5 class="fw-bold text-dark m-0" style="font-size: 1.1rem;">Customer Details</h5>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="fullName" class="form-label fw-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control border-start-0 <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>" id="fullName" name="full_name" value="<?= e($old['full_name'] ?? '') ?>" placeholder="Enter your full name" required>
                <?php if (!empty($errors['full_name'])): ?>
                  <div class="invalid-feedback d-block"><?= e($errors['full_name']) ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-md-6">
              <label for="mobileNumber" class="form-label fw-semibold text-dark small">Mobile Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 fw-bold text-muted" style="font-size: 0.85rem;">+91</span>
                <input type="tel" class="form-control border-start-0 <?= !empty($errors['mobile_number']) ? 'is-invalid' : '' ?>" id="mobileNumber" name="mobile_number" value="<?= e($old['mobile_number'] ?? '') ?>" placeholder="10-digit mobile number" maxlength="10" pattern="[6-9][0-9]{9}" required>
                <?php if (!empty($errors['mobile_number'])): ?>
                  <div class="invalid-feedback d-block"><?= e($errors['mobile_number']) ?></div>
                <?php endif; ?>
              </div>
              <small class="text-muted" style="font-size: 0.75rem;">Used for OTP service requests and warranty claim SMS.</small>
            </div>

            <div class="col-12">
              <label for="customerEmail" class="form-label fw-semibold text-dark small">Email Address <span class="text-muted fw-normal">(Optional, recommended for digital certificate)</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control border-start-0 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" id="customerEmail" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="name@example.com">
                <?php if (!empty($errors['email'])): ?>
                  <div class="invalid-feedback d-block"><?= e($errors['email']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 2: PRODUCT DETAILS -->
        <div class="mb-4 pb-3 border-bottom">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge rounded-circle p-2 d-inline-flex align-items-center justify-content-center text-white" style="background: #FE6B25; width: 28px; height: 28px; font-size: 0.85rem;">2</span>
            <h5 class="fw-bold text-dark m-0" style="font-size: 1.1rem;">Product Details</h5>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="productCategory" class="form-label fw-semibold text-dark small">Product Category <span class="text-danger">*</span></label>
              <select class="form-select <?= !empty($errors['category']) ? 'is-invalid' : '' ?>" id="productCategory" name="category" required>
                <option value="">-- Select Product Category --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= e($cat) ?>" <?= (($old['category'] ?? '') === $cat) ? 'selected' : '' ?>><?= e($cat) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['category'])): ?>
                <div class="invalid-feedback d-block"><?= e($errors['category']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <label for="productModel" class="form-label fw-semibold text-dark small">Product Model <span class="text-danger">*</span></label>
              <input type="text" class="form-control <?= !empty($errors['model']) ? 'is-invalid' : '' ?>" id="productModel" name="model" value="<?= e($old['model'] ?? '') ?>" placeholder="e.g. HOMEIQ AI Face Lock Black / VDP-100 / Touch 4M" required>
              <?php if (!empty($errors['model'])): ?>
                <div class="invalid-feedback d-block"><?= e($errors['model']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-12">
              <div id="serialNumberGroup">
                <label for="serialNumber" class="form-label fw-semibold text-dark small d-flex justify-content-between align-items-center">
                  <span>Product Serial Number <span class="text-danger">*</span></span>
                  <span class="text-muted fw-normal" style="font-size: 0.75rem;">Located on retail box or rear lock panel</span>
                </label>
                <input type="text" class="form-control text-uppercase <?= !empty($errors['serial_number']) ? 'is-invalid' : '' ?>" id="serialNumber" name="serial_number" value="<?= e($old['serial_number'] ?? '') ?>" placeholder="e.g. HIQ-SL-2026-98124">
                <?php if (!empty($errors['serial_number'])): ?>
                  <div class="invalid-feedback d-block"><?= e($errors['serial_number']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Serial Unavailable Checkbox -->
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="serialUnavailable" name="serial_unavailable" value="1" <?= !empty($old['serial_unavailable']) ? 'checked' : '' ?>>
                <label class="form-check-label small text-secondary" for="serialUnavailable">
                  Serial Number Unavailable on my device / box
                </label>
              </div>

              <!-- Alternative Identification Notes (toggled when unavailable) -->
              <div id="alternativeNotesContainer" class="mt-2 <?= !empty($old['serial_unavailable']) ? '' : 'd-none' ?>">
                <label for="alternativeNotes" class="form-label fw-semibold text-dark small">Alternative Identification Notes <span class="text-danger">*</span></label>
                <textarea class="form-control <?= !empty($errors['alternative_notes']) ? 'is-invalid' : '' ?>" id="alternativeNotes" name="alternative_notes" rows="2" placeholder="Describe the product variant, finish/color, mortise dimension, or batch marks to assist verification..."><?= e($old['alternative_notes'] ?? '') ?></textarea>
                <?php if (!empty($errors['alternative_notes'])): ?>
                  <div class="invalid-feedback d-block"><?= e($errors['alternative_notes']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 3: PURCHASE DETAILS -->
        <div class="mb-4 pb-3 border-bottom">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge rounded-circle p-2 d-inline-flex align-items-center justify-content-center text-white" style="background: #FE6B25; width: 28px; height: 28px; font-size: 0.85rem;">3</span>
            <h5 class="fw-bold text-dark m-0" style="font-size: 1.1rem;">Purchase Details</h5>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="purchasePlatform" class="form-label fw-semibold text-dark small">Purchase Platform <span class="text-danger">*</span></label>
              <select class="form-select <?= !empty($errors['platform']) ? 'is-invalid' : '' ?>" id="purchasePlatform" name="platform" required>
                <option value="">-- Select Platform --</option>
                <?php foreach ($platforms as $plat): ?>
                  <option value="<?= e($plat) ?>" <?= (($old['platform'] ?? '') === $plat) ? 'selected' : '' ?>><?= e($plat) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['platform'])): ?>
                <div class="invalid-feedback d-block"><?= e($errors['platform']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-4">
              <label for="purchaseDate" class="form-label fw-semibold text-dark small">Purchase Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control <?= !empty($errors['purchase_date']) ? 'is-invalid' : '' ?>" id="purchaseDate" name="purchase_date" value="<?= e($old['purchase_date'] ?? date('Y-m-d')) ?>" max="<?= date('Y-m-d') ?>" required>
              <?php if (!empty($errors['purchase_date'])): ?>
                <div class="invalid-feedback d-block"><?= e($errors['purchase_date']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-4">
              <label for="orderId" class="form-label fw-semibold text-dark small">Order ID / Invoice Ref <span class="text-danger">*</span></label>
              <input type="text" class="form-control <?= !empty($errors['order_id']) ? 'is-invalid' : '' ?>" id="orderId" name="order_id" value="<?= e($old['order_id'] ?? '') ?>" placeholder="e.g. 402-1234567-8901234" required>
              <?php if (!empty($errors['order_id'])): ?>
                <div class="invalid-feedback d-block"><?= e($errors['order_id']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Invoice Upload -->
            <div class="col-12">
              <label class="form-label fw-semibold text-dark small">Upload Purchase Invoice <span class="text-danger">*</span></label>
              <div class="p-3 p-md-4 border border-2 border-dashed rounded-3 text-center bg-light <?= !empty($errors['invoice']) ? 'border-danger bg-danger-subtle' : '' ?>" id="invoiceDropZone">
                <i class="bi bi-cloud-arrow-up text-secondary fs-1 mb-2 d-block"></i>
                <div class="fw-semibold text-dark mb-1" id="fileChosenText">Drag & drop your invoice here, or click to browse</div>
                <div class="text-muted small mb-3">Accepted formats: PDF, JPG, JPEG, PNG (Max: 5 MB)</div>
                <input type="file" name="invoice" id="invoiceFileInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required>
                <button type="button" class="btn btn-sm btn-outline-dark px-3 py-1" onclick="document.getElementById('invoiceFileInput').click()">
                  <i class="bi bi-folder2-open me-1"></i> Choose Invoice File
                </button>
              </div>
              <?php if (!empty($errors['invoice'])): ?>
                <div class="text-danger small mt-1 d-block"><?= e($errors['invoice']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- SECTION 4: DECLARATION & CONSENT -->
        <div class="mb-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge rounded-circle p-2 d-inline-flex align-items-center justify-content-center text-white" style="background: #FE6B25; width: 28px; height: 28px; font-size: 0.85rem;">4</span>
            <h5 class="fw-bold text-dark m-0" style="font-size: 1.1rem;">Declaration & Terms</h5>
          </div>

          <!-- Required declaration checkbox -->
          <div class="form-check p-3 rounded-3 border mb-2 <?= !empty($errors['declaration_accepted']) ? 'border-danger bg-danger-subtle' : 'bg-light' ?>">
            <input class="form-check-input ms-0 me-2" type="checkbox" id="declarationAccepted" name="declaration_accepted" value="1" required <?= !empty($old['declaration_accepted']) ? 'checked' : '' ?>>
            <label class="form-check-label text-dark small" for="declarationAccepted" style="line-height: 1.5;">
              <strong>Declaration:</strong> <?= e($declarationText) ?> <span class="text-danger">*</span>
            </label>
            <?php if (!empty($errors['declaration_accepted'])): ?>
              <div class="text-danger small mt-1"><?= e($errors['declaration_accepted']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Optional marketing consent checkbox -->
          <div class="form-check p-3 rounded-3 border bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" id="marketingConsent" name="marketing_consent" value="1" <?= !empty($old['marketing_consent']) ? 'checked' : '' ?>>
            <label class="form-check-label text-secondary small" for="marketingConsent" style="line-height: 1.5;">
              I would like to receive firmware updates, smart security tips, and exclusive offers from HOMEIQ. (Optional)
            </label>
          </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-warning py-3 fw-bold text-dark fs-6 rounded-3 shadow-sm hover-lift" id="submitBtn">
            <i class="bi bi-shield-lock-fill me-2"></i> Submit Product Warranty Registration
          </button>
        </div>

        <div class="text-center mt-3 text-muted small">
          <i class="bi bi-lock me-1"></i> Your personal details and invoice are securely protected under HOMEIQ Privacy Standards.
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const serialUnavailable = document.getElementById('serialUnavailable');
  const serialGroup = document.getElementById('serialNumberGroup');
  const serialInput = document.getElementById('serialNumber');
  const altContainer = document.getElementById('alternativeNotesContainer');
  const altTextarea = document.getElementById('alternativeNotes');

  function toggleSerialState() {
    if (serialUnavailable.checked) {
      serialGroup.style.opacity = '0.5';
      serialInput.required = false;
      altContainer.classList.remove('d-none');
      altTextarea.required = true;
    } else {
      serialGroup.style.opacity = '1';
      serialInput.required = true;
      altContainer.classList.add('d-none');
      altTextarea.required = false;
    }
  }

  serialUnavailable.addEventListener('change', toggleSerialState);
  toggleSerialState();

  // File Dropzone Interaction
  const fileInput = document.getElementById('invoiceFileInput');
  const dropZone = document.getElementById('invoiceDropZone');
  const fileText = document.getElementById('fileChosenText');

  fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
      const file = fileInput.files[0];
      const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
      fileText.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-file-earmark-check me-1"></i> Selected: ${file.name} (${sizeMb} MB)</span>`;
      dropZone.classList.add('border-success', 'bg-success-subtle');
    }
  });

  ['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, function(e) {
      e.preventDefault();
      e.stopPropagation();
      dropZone.classList.add('border-warning', 'bg-warning-subtle');
    }, false);
  });

  ['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, function(e) {
      e.preventDefault();
      e.stopPropagation();
      dropZone.classList.remove('border-warning', 'bg-warning-subtle');
    }, false);
  });

  dropZone.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    if (files.length > 0) {
      fileInput.files = files;
      const file = files[0];
      const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
      fileText.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-file-earmark-check me-1"></i> Selected: ${file.name} (${sizeMb} MB)</span>`;
      dropZone.classList.add('border-success', 'bg-success-subtle');
    }
  });
});
</script>
