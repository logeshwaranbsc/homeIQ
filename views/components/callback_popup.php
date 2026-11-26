<?php
declare(strict_types=1);

use App\Repositories\CallbackSettingsRepository;

$cbSettingsRepo = new CallbackSettingsRepository();
$cbSettings = $cbSettingsRepo->get();

// If disabled by admin, do not render markup
if (empty($cbSettings['enabled'])) {
    return;
}

$rawUri = $_SERVER['REQUEST_URI'] ?? '';
$currentPath = parse_url($rawUri, PHP_URL_PATH) ?: '/';

// Exclude checkout, payment, confirmation, and admin pages
$excludedPaths = ['/checkout', '/order/confirmation', '/order/invoice', '/admin'];
foreach ($excludedPaths as $ex) {
    if (str_starts_with($currentPath, $ex)) {
        return;
    }
}

$triggerDelay = max(5, (int)($cbSettings['trigger_delay_seconds'] ?? 60));
$heading = $cbSettings['heading'] ?? 'Need Help Before You Buy?';
$desc = $cbSettings['description'] ?? 'Let our product expert help you choose the right product.';
$btnText = $cbSettings['button_text'] ?? 'REQUEST A CALLBACK';
$productOptions = $cbSettings['product_options'] ?? [
    'Smart Door Lock',
    'Video Door Phone (VDP)',
    'Retrofit Smart Module',
    'Other Products'
];
?>

<!-- HOMEIQ Callback Popup Modal -->
<div id="hiqCallbackModal" class="hiq-cb-overlay" aria-hidden="true" role="dialog" aria-labelledby="hiqCbTitle" aria-modal="true" style="display: none;">
  <div class="hiq-cb-backdrop" id="hiqCbBackdrop"></div>
  <div class="hiq-cb-dialog">
    <div class="hiq-cb-card">
      
      <!-- Top Decorative Bar & Close Button -->
      <div class="hiq-cb-header-bar">
        <span class="hiq-cb-brand-tag">
          <i class="bi bi-shield-check text-warning me-1"></i> HOMEIQ Expert Assistance
        </span>
        <button type="button" class="hiq-cb-close-btn" id="hiqCbCloseBtn" aria-label="Close popup">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Main Form State -->
      <div id="hiqCbFormContainer" class="hiq-cb-body">
        <div class="hiq-cb-icon-badge">
          <i class="bi bi-headset"></i>
        </div>
        <h3 id="hiqCbTitle" class="hiq-cb-heading"><?= e($heading) ?></h3>
        <p class="hiq-cb-desc"><?= e($desc) ?></p>

        <form id="hiqCallbackForm" novalidate>
          <input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="product_page_url" id="hiqCbPageUrl" value="">

          <!-- Honeypot field for bot protection (hidden from humans) -->
          <div style="position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0; pointer-events: none;" aria-hidden="true">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
          </div>

          <!-- Field 1: What are you looking for? -->
          <div class="hiq-cb-group mb-3 text-start">
            <label for="hiqCbProductInterest" class="hiq-cb-label">
              What are you looking for? <span class="text-danger">*</span>
            </label>
            <div class="hiq-cb-input-wrap">
              <i class="bi bi-grid hiq-cb-input-icon"></i>
              <select id="hiqCbProductInterest" name="product_interest" class="hiq-cb-select" required>
                <option value="">Select a product category...</option>
                <?php foreach ($productOptions as $opt): ?>
                  <option value="<?= e($opt) ?>"><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="hiq-cb-error" id="hiqCbErrorInterest"></div>
          </div>

          <!-- Field 2: Your Name -->
          <div class="hiq-cb-group mb-3 text-start">
            <label for="hiqCbCustomerName" class="hiq-cb-label">
              Your Name <span class="text-danger">*</span>
            </label>
            <div class="hiq-cb-input-wrap">
              <i class="bi bi-person hiq-cb-input-icon"></i>
              <input type="text" 
                     id="hiqCbCustomerName" 
                     name="customer_name" 
                     class="hiq-cb-input" 
                     placeholder="Enter your name" 
                     maxlength="80" 
                     required>
            </div>
            <div class="hiq-cb-error" id="hiqCbErrorName"></div>
          </div>

          <!-- Field 3: Mobile Number -->
          <div class="hiq-cb-group mb-4 text-start">
            <label for="hiqCbMobileNumber" class="hiq-cb-label">
              Mobile Number <span class="text-danger">*</span>
            </label>
            <div class="hiq-cb-input-wrap">
              <span class="hiq-cb-country-code">+91</span>
              <input type="tel" 
                     id="hiqCbMobileNumber" 
                     name="mobile_number" 
                     class="hiq-cb-input hiq-cb-input-phone" 
                     placeholder="Enter your 10-digit mobile number" 
                     maxlength="10" 
                     pattern="[6-9][0-9]{9}" 
                     required>
            </div>
            <div class="hiq-cb-error" id="hiqCbErrorMobile"></div>
            <small class="hiq-cb-hint text-muted">We respect your privacy. No spam guaranteed.</small>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="hiq-cb-submit-btn" id="hiqCbSubmitBtn">
            <span class="hiq-cb-btn-text">
              <i class="bi bi-telephone-outbound me-2"></i><?= e($btnText) ?>
            </span>
            <span class="hiq-cb-btn-loading d-none">
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              Submitting...
            </span>
          </button>
        </form>
      </div>

      <!-- Success State (Shown after submission) -->
      <div id="hiqCbSuccessContainer" class="hiq-cb-body hiq-cb-success-body d-none">
        <div class="hiq-cb-success-icon">
          <i class="bi bi-check-circle-fill"></i>
        </div>
        <h3 class="hiq-cb-heading text-success">Thank You for Choosing HOMEIQ!</h3>
        <p class="hiq-cb-desc mt-2">
          Our product specialist will contact you shortly to assist with your enquiry.
        </p>
        <div class="hiq-cb-lead-badge mt-3 mb-4" id="hiqCbSuccessRef">
          Enquiry ID: <strong id="hiqCbLeadIdDisplay">CB-HIQ-000000</strong>
        </div>
        <button type="button" class="btn btn-dark w-100 py-2 fw-bold rounded-pill" id="hiqCbSuccessCloseBtn">
          Close &amp; Continue Browsing
        </button>
      </div>

    </div>
  </div>
</div>

<style>
/* HOMEIQ Callback Popup Styles */
.hiq-cb-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 10050;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
.hiq-cb-overlay.hiq-cb-active {
  opacity: 1;
  visibility: visible;
}
.hiq-cb-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.72);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
}
.hiq-cb-dialog {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: 440px;
  margin: auto;
  transform: translateY(20px) scale(0.96);
  transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
}
.hiq-cb-overlay.hiq-cb-active .hiq-cb-dialog {
  transform: translateY(0) scale(1);
}
.hiq-cb-card {
  background: #FFFFFF;
  border-radius: 22px;
  overflow: hidden;
  box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(226, 232, 240, 0.8);
}
.hiq-cb-header-bar {
  background: #0F172A;
  padding: 12px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 2px solid #FE6B25;
}
.hiq-cb-brand-tag {
  color: #F8FAFC;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
.hiq-cb-close-btn {
  background: rgba(255, 255, 255, 0.12);
  border: none;
  color: #CBD5E1;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.9rem;
}
.hiq-cb-close-btn:hover {
  background: #FE6B25;
  color: #FFFFFF;
  transform: rotate(90deg);
}
.hiq-cb-body {
  padding: 26px 28px;
  text-align: center;
}
.hiq-cb-icon-badge {
  width: 54px;
  height: 54px;
  margin: 0 auto 14px;
  background: rgba(254, 107, 37, 0.12);
  color: #FE6B25;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
}
.hiq-cb-heading {
  font-size: 1.35rem;
  font-weight: 800;
  color: #0F172A;
  margin-bottom: 6px;
  letter-spacing: -0.3px;
  line-height: 1.3;
}
.hiq-cb-desc {
  font-size: 0.88rem;
  color: #64748B;
  margin-bottom: 20px;
  line-height: 1.45;
}
.hiq-cb-label {
  display: block;
  font-size: 0.82rem;
  font-weight: 700;
  color: #334155;
  margin-bottom: 6px;
}
.hiq-cb-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.hiq-cb-input-icon {
  position: absolute;
  left: 14px;
  color: #94A3B8;
  font-size: 1.05rem;
  pointer-events: none;
}
.hiq-cb-country-code {
  position: absolute;
  left: 14px;
  font-size: 0.9rem;
  font-weight: 700;
  color: #475569;
  border-right: 1px solid #CBD5E1;
  padding-right: 8px;
  line-height: 1;
  pointer-events: none;
}
.hiq-cb-input,
.hiq-cb-select {
  width: 100%;
  height: 46px;
  padding: 8px 14px 8px 42px;
  font-size: 0.92rem;
  color: #0F172A;
  background-color: #F8FAFC;
  border: 1.5px solid #E2E8F0;
  border-radius: 12px;
  transition: all 0.2s ease;
  font-family: inherit;
}
.hiq-cb-input-phone {
  padding-left: 56px;
  letter-spacing: 0.5px;
}
.hiq-cb-input:focus,
.hiq-cb-select:focus {
  background-color: #FFFFFF;
  border-color: #FE6B25;
  box-shadow: 0 0 0 3px rgba(254, 107, 37, 0.15);
  outline: none;
}
.hiq-cb-error {
  font-size: 0.75rem;
  color: #DC2626;
  font-weight: 600;
  margin-top: 4px;
  display: none;
}
.hiq-cb-error.hiq-cb-show {
  display: block;
}
.hiq-cb-hint {
  font-size: 0.72rem;
  display: block;
  margin-top: 4px;
}
.hiq-cb-submit-btn {
  width: 100%;
  height: 48px;
  background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
  color: #FFFFFF;
  border: 1.5px solid #FE6B25;
  border-radius: 50px;
  font-size: 0.95rem;
  font-weight: 800;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.2);
}
.hiq-cb-submit-btn:hover {
  background: #FE6B25;
  border-color: #FE6B25;
  color: #FFFFFF;
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(254, 107, 37, 0.35);
}
.hiq-cb-submit-btn:active {
  transform: translateY(0);
}
.hiq-cb-submit-btn:disabled {
  opacity: 0.75;
  cursor: not-allowed;
  transform: none;
}

/* Success Body */
.hiq-cb-success-body {
  padding: 36px 28px;
}
.hiq-cb-success-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 16px;
  color: #10B981;
  font-size: 3.5rem;
  line-height: 1;
}
.hiq-cb-lead-badge {
  display: inline-block;
  background: #F1F5F9;
  border: 1px dashed #CBD5E1;
  border-radius: 8px;
  padding: 6px 16px;
  font-size: 0.85rem;
  color: #334155;
}
.hiq-cb-lead-badge strong {
  color: #0F172A;
}

@media (max-width: 480px) {
  .hiq-cb-body {
    padding: 20px 18px;
  }
  .hiq-cb-heading {
    font-size: 1.2rem;
  }
}
</style>

<script>
(function() {
  const TRIGGER_DELAY = <?= $triggerDelay ?>; // seconds of active browsing
  const STORAGE_KEY_DISMISSED = 'hiq_cb_dismissed';
  const STORAGE_KEY_SUBMITTED = 'hiq_cb_submitted';
  const STORAGE_KEY_ACTIVE_SECS = 'hiq_cb_active_seconds';

  const modal = document.getElementById('hiqCallbackModal');
  const backdrop = document.getElementById('hiqCbBackdrop');
  const closeBtn = document.getElementById('hiqCbCloseBtn');
  const form = document.getElementById('hiqCallbackForm');
  const submitBtn = document.getElementById('hiqCbSubmitBtn');
  const btnText = submitBtn ? submitBtn.querySelector('.hiq-cb-btn-text') : null;
  const btnLoading = submitBtn ? submitBtn.querySelector('.hiq-cb-btn-loading') : null;
  const pageUrlInput = document.getElementById('hiqCbPageUrl');
  const formContainer = document.getElementById('hiqCbFormContainer');
  const successContainer = document.getElementById('hiqCbSuccessContainer');
  const successCloseBtn = document.getElementById('hiqCbSuccessCloseBtn');
  const leadIdDisplay = document.getElementById('hiqCbLeadIdDisplay');

  // Input fields
  const interestInput = document.getElementById('hiqCbProductInterest');
  const nameInput = document.getElementById('hiqCbCustomerName');
  const mobileInput = document.getElementById('hiqCbMobileNumber');

  // Error containers
  const errInterest = document.getElementById('hiqCbErrorInterest');
  const errName = document.getElementById('hiqCbErrorName');
  const errMobile = document.getElementById('hiqCbErrorMobile');

  if (pageUrlInput) {
    pageUrlInput.value = window.location.href;
  }

  // 1. Session check: if already dismissed or submitted during this session, do nothing
  try {
    if (sessionStorage.getItem(STORAGE_KEY_DISMISSED) === '1' || sessionStorage.getItem(STORAGE_KEY_SUBMITTED) === '1') {
      return;
    }
  } catch (e) {}

  // 2. Page context check: do not show on checkout/payment or if checkout modal is active
  function isCheckoutOrPaymentActive() {
    const p = window.location.pathname.toLowerCase();
    if (p.includes('/checkout') || p.includes('/payment') || p.includes('/order/confirmation') || p.includes('/order-tracking')) {
      return true;
    }
    // Check if Bootstrap checkout modal or payment drawer is active
    if (document.querySelector('.modal.show') || document.querySelector('.offcanvas.show')) {
      return true;
    }
    return false;
  }

  // 3. Active Browsing Timer
  let activeSeconds = 0;
  try {
    activeSeconds = parseInt(sessionStorage.getItem(STORAGE_KEY_ACTIVE_SECS) || '0', 10);
  } catch (e) {}

  let timerInterval = null;

  function showPopup() {
    if (!modal) return;
    if (isCheckoutOrPaymentActive()) return;

    modal.style.display = 'flex';
    // Force layout reflow before adding active class for smooth animation
    modal.offsetHeight;
    modal.classList.add('hiq-cb-active');
    modal.setAttribute('aria-hidden', 'false');

    // Pre-fill product interest if browsing specific product page
    try {
      const headingElem = document.querySelector('h1.product-title, .product-single-title, [data-product-category]');
      if (headingElem && interestInput && !interestInput.value) {
        const cat = headingElem.getAttribute('data-product-category') || '';
        if (cat) {
          for (let i = 0; i < interestInput.options.length; i++) {
            if (interestInput.options[i].text.toLowerCase().includes(cat.toLowerCase())) {
              interestInput.selectedIndex = i;
              break;
            }
          }
        }
      }
    } catch (e) {}
  }

  function hidePopup(markDismissed = true) {
    if (!modal) return;
    modal.classList.remove('hiq-cb-active');
    modal.setAttribute('aria-hidden', 'true');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 320);

    if (markDismissed) {
      try {
        sessionStorage.setItem(STORAGE_KEY_DISMISSED, '1');
      } catch (e) {}
    }
  }

  // Start active browsing counter
  function startActiveTimer() {
    if (timerInterval) return;
    timerInterval = setInterval(() => {
      // Only count when window is active and tab is visible
      if (!document.hidden && document.hasFocus && document.hasFocus()) {
        activeSeconds++;
        try {
          sessionStorage.setItem(STORAGE_KEY_ACTIVE_SECS, activeSeconds.toString());
        } catch (e) {}

        if (activeSeconds >= TRIGGER_DELAY) {
          clearInterval(timerInterval);
          timerInterval = null;
          showPopup();
        }
      }
    }, 1000);
  }

  // Initial timer check
  if (activeSeconds >= TRIGGER_DELAY) {
    // Show after slight DOM readiness delay
    setTimeout(showPopup, 1500);
  } else {
    startActiveTimer();
  }

  // Close handlers
  if (closeBtn) closeBtn.addEventListener('click', () => hidePopup(true));
  if (backdrop) backdrop.addEventListener('click', () => hidePopup(true));
  if (successCloseBtn) successCloseBtn.addEventListener('click', () => hidePopup(true));

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('hiq-cb-active')) {
      hidePopup(true);
    }
  });

  // Mobile number input sanitation: strictly allow numbers up to 10 digits
  if (mobileInput) {
    mobileInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
      if (errMobile && errMobile.classList.contains('hiq-cb-show')) {
        errMobile.classList.remove('hiq-cb-show');
        errMobile.textContent = '';
      }
    });
  }

  if (nameInput) {
    nameInput.addEventListener('input', function() {
      if (errName && errName.classList.contains('hiq-cb-show')) {
        errName.classList.remove('hiq-cb-show');
        errName.textContent = '';
      }
    });
  }

  if (interestInput) {
    interestInput.addEventListener('change', function() {
      if (errInterest && errInterest.classList.contains('hiq-cb-show')) {
        errInterest.classList.remove('hiq-cb-show');
        errInterest.textContent = '';
      }
    });
  }

  // Form Submission
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      let hasError = false;

      // Validate Interest
      const interestVal = (interestInput ? interestInput.value : '').trim();
      if (!interestVal) {
        if (errInterest) {
          errInterest.textContent = 'Please select a product category.';
          errInterest.classList.add('hiq-cb-show');
        }
        hasError = true;
      } else if (errInterest) {
        errInterest.classList.remove('hiq-cb-show');
      }

      // Validate Name
      const nameVal = (nameInput ? nameInput.value : '').trim();
      if (!nameVal || nameVal.length < 2) {
        if (errName) {
          errName.textContent = 'Please enter your name (at least 2 letters).';
          errName.classList.add('hiq-cb-show');
        }
        hasError = true;
      } else if (errName) {
        errName.classList.remove('hiq-cb-show');
      }

      // Validate Mobile Number: Exactly 10 digits Indian format [6-9]xxxxxxxxx
      const mobileVal = (mobileInput ? mobileInput.value : '').trim();
      const cleanPhone = mobileVal.replace(/[^0-9]/g, '');
      if (!cleanPhone || cleanPhone.length !== 10) {
        if (errMobile) {
          errMobile.textContent = 'Please enter a valid 10-digit mobile number.';
          errMobile.classList.add('hiq-cb-show');
        }
        hasError = true;
      } else if (!/^[6-9]\d{9}$/.test(cleanPhone)) {
        if (errMobile) {
          errMobile.textContent = 'Mobile number must start with 6, 7, 8, or 9.';
          errMobile.classList.add('hiq-cb-show');
        }
        hasError = true;
      } else if (errMobile) {
        errMobile.classList.remove('hiq-cb-show');
      }

      if (hasError) return;

      // Loading State
      if (submitBtn) submitBtn.disabled = true;
      if (btnText) btnText.classList.add('d-none');
      if (btnLoading) btnLoading.classList.remove('d-none');

      const formData = new FormData(form);
      const postUrl = (window.HOMEIQ_BASE_URL || window.location.origin) + '/api/callback-request';
      const csrfToken = (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '') || (form.querySelector('input[name="_csrf_token"]') ? form.querySelector('input[name="_csrf_token"]').value : '');

      fetch(postUrl, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      })
      .then(async response => {
        let text = '';
        let data = {};
        try {
          text = await response.text();
          data = JSON.parse(text);
        } catch (parseErr) {
          data = {
            success: false,
            message: response.ok 
              ? 'Thank you! Your request has been recorded.' 
              : 'Server communication error (' + response.status + '). Please try again.'
          };
        }
        return {
          status: response.status,
          ok: response.ok,
          data: data
        };
      })
      .then(res => {
        if (res.ok && res.data.success) {
          // Success State
          try {
            sessionStorage.setItem(STORAGE_KEY_SUBMITTED, '1');
            sessionStorage.setItem(STORAGE_KEY_DISMISSED, '1');
          } catch (e) {}

          if (leadIdDisplay && res.data.lead_id) {
            leadIdDisplay.textContent = res.data.lead_id;
          }

          if (formContainer) formContainer.classList.add('d-none');
          if (successContainer) successContainer.classList.remove('d-none');
        } else {
          // Error handling
          if (submitBtn) submitBtn.disabled = false;
          if (btnText) btnText.classList.remove('d-none');
          if (btnLoading) btnLoading.classList.add('d-none');

          const errMsg = (res.data && res.data.message) ? res.data.message : 'Unable to submit your request. Please try again.';
          if (errMobile) {
            errMobile.textContent = errMsg;
            errMobile.classList.add('hiq-cb-show');
          } else {
            alert(errMsg);
          }
        }
      })
      .catch(err => {
        console.error('Callback request network error:', err);
        if (submitBtn) submitBtn.disabled = false;
        if (btnText) btnText.classList.remove('d-none');
        if (btnLoading) btnLoading.classList.add('d-none');

        if (errMobile) {
          errMobile.textContent = 'Connection error. Please check your internet connection.';
          errMobile.classList.add('hiq-cb-show');
        }
      });
    });
  }
})();
</script>
