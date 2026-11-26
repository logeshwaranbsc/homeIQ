<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $settings
 */

$enabled = (bool)($settings['enabled'] ?? true);
$delay = (int)($settings['trigger_delay_seconds'] ?? 60);
$heading = $settings['heading'] ?? 'Need Help Before You Buy?';
$desc = $settings['description'] ?? 'Let our product expert help you choose the right product.';
$btnText = $settings['button_text'] ?? 'REQUEST A CALLBACK';
$options = $settings['product_options'] ?? [
    'Smart Door Lock',
    'Video Door Phone (VDP)',
    'Retrofit Smart Module',
    'Other Products'
];
$optionsText = implode("\n", $options);
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-1 small">
        <li class="breadcrumb-item"><a href="<?= url('admin/callback-enquiries') ?>" class="text-decoration-none">Callback Enquiries</a></li>
        <li class="breadcrumb-item active" aria-current="page">Popup Settings</li>
      </ol>
    </nav>
    <h2 class="h4 fw-bold text-dark mb-1">
      <i class="bi bi-sliders text-primary me-2"></i>Callback Popup Configuration
    </h2>
    <p class="text-muted small mb-0">Configure the customer-facing presales callback popup timing, content, and product interest options.</p>
  </div>
  <div>
    <a href="<?= url('admin/callback-enquiries') ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
      <i class="bi bi-arrow-left me-1"></i> Back to Enquiries
    </a>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 rounded-3 mb-4">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-gear-wide-connected text-primary me-2"></i>Popup Behaviour &amp; Content</h6>
      </div>
      <div class="card-body p-4">
        <form action="<?= url('admin/callback-enquiries/settings') ?>" method="POST">
          <?= csrf_field() ?>

          <!-- Feature Toggle Switch -->
          <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark">Enable Callback Popup</div>
              <small class="text-muted">When enabled, the popup appears automatically after active browsing time.</small>
            </div>
            <div class="form-check form-switch fs-4 mb-0">
              <input class="form-check-input" 
                     type="checkbox" 
                     role="switch" 
                     name="enabled" 
                     id="enabledSwitch" 
                     value="1" 
                     <?= $enabled ? 'checked' : '' ?>>
            </div>
          </div>

          <!-- Trigger Delay -->
          <div class="mb-3">
            <label for="triggerDelay" class="form-label fw-bold small text-muted">
              Trigger Delay (Seconds of Active Browsing) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="number" 
                     name="trigger_delay_seconds" 
                     id="triggerDelay" 
                     class="form-control" 
                     min="5" 
                     max="600" 
                     required 
                     value="<?= $delay ?>">
              <span class="input-group-text">Seconds</span>
            </div>
            <small class="text-muted">Default is 60 seconds. The timer automatically counts active tab browsing time.</small>
          </div>

          <!-- Heading -->
          <div class="mb-3">
            <label for="popupHeading" class="form-label fw-bold small text-muted">
              Popup Heading <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   name="heading" 
                   id="popupHeading" 
                   class="form-control" 
                   maxlength="100" 
                   required 
                   value="<?= e($heading) ?>">
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label for="popupDesc" class="form-label fw-bold small text-muted">
              Popup Subtitle / Description <span class="text-danger">*</span>
            </label>
            <textarea name="description" 
                      id="popupDesc" 
                      rows="2" 
                      class="form-control" 
                      required><?= e($desc) ?></textarea>
          </div>

          <!-- Submit Button Text -->
          <div class="mb-4">
            <label for="btnText" class="form-label fw-bold small text-muted">
              Submit Button Text <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   name="button_text" 
                   id="btnText" 
                   class="form-control" 
                   maxlength="60" 
                   required 
                   value="<?= e($btnText) ?>">
          </div>

          <!-- Product Options (one per line) -->
          <div class="mb-4">
            <label for="optionsText" class="form-label fw-bold small text-muted">
              Product Dropdown Options (One per line) <span class="text-danger">*</span>
            </label>
            <textarea name="product_options_text" 
                      id="optionsText" 
                      rows="5" 
                      class="form-control font-monospace small" 
                      required><?= e($optionsText) ?></textarea>
            <small class="text-muted">Enter the choices customers can select under "What are you looking for?".</small>
          </div>

          <div class="d-flex justify-content-between align-items-center pt-2 border-top">
            <span class="text-muted small">
              <i class="bi bi-info-circle me-1"></i>Saved in <code>storage/callback-leads/settings/callback-settings.json</code>
            </span>
            <button type="submit" class="btn btn-primary px-4 fw-bold">
              <i class="bi bi-check2-circle me-1"></i> Save Popup Settings
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <!-- Live Preview Card -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-eye text-info me-2"></i>Popup Preview</h6>
      </div>
      <div class="card-body p-3">
        <div class="p-3 bg-light rounded-3 text-center border">
          <div class="hiq-cb-icon-badge" style="width: 44px; height: 44px; margin: 0 auto 10px; background: rgba(254,107,37,0.12); color: #FE6B25; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="bi bi-headset"></i>
          </div>
          <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;"><?= e($heading) ?></h5>
          <p class="text-muted small mb-3"><?= e($desc) ?></p>

          <div class="text-start mb-2">
            <span class="small fw-bold text-muted">Category:</span>
            <span class="badge bg-white border text-dark ms-1">Smart Door Lock</span>
          </div>
          <div class="text-start mb-2">
            <span class="small fw-bold text-muted">Name:</span>
            <span class="badge bg-white border text-dark ms-1">Customer Name</span>
          </div>
          <div class="text-start mb-3">
            <span class="small fw-bold text-muted">Mobile:</span>
            <span class="badge bg-white border text-dark ms-1">+91 9876543210</span>
          </div>

          <button type="button" class="btn btn-dark w-100 rounded-pill py-2 fw-bold" style="background:#0F172A; border-color:#FE6B25; font-size:0.85rem;">
            <?= e($btnText) ?>
          </button>
        </div>
      </div>
    </div>

    <!-- Exclusion Rules Info Card -->
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-lock text-success me-2"></i>Automatic Suppression Rules</h6>
      </div>
      <div class="card-body p-3 small text-muted">
        <ul class="ps-3 mb-0">
          <li class="mb-2"><strong>Once per Session:</strong> If dismissed or submitted, the popup will not show again during that browser visit.</li>
          <li class="mb-2"><strong>Checkout &amp; Payment:</strong> Never shown on <code>/checkout</code>, payment, or order confirmation screens.</li>
          <li class="mb-2"><strong>Active Tab Only:</strong> 60-second timer pauses when the user switches tabs or minimizes browser.</li>
          <li><strong>Honeypot Trap:</strong> Hidden bot trap silently absorbs automated spam submissions.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
