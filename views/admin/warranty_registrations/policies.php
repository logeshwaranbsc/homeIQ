<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var array $policies
 * @var array $categories
 */
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-1 small">
        <li class="breadcrumb-item"><a href="<?= url('admin/warranty-registrations') ?>" class="text-decoration-none">Warranty Registrations</a></li>
        <li class="breadcrumb-item active" aria-current="page">Policy Management</li>
      </ol>
    </nav>
    <h2 class="h4 fw-bold text-dark mb-1">
      <i class="bi bi-sliders text-warning me-2"></i>Warranty Policy & Coverage Rules
    </h2>
    <p class="text-muted small mb-0">Configure default warranty durations by product category. When an admin approves a registration, coverage end dates are calculated automatically using verified purchase dates.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= url('admin/warranty-registrations') ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
      <i class="bi bi-arrow-left me-1"></i> Back to Registrations
    </a>
  </div>
</div>

<div class="card shadow-sm border-0 rounded-3 mb-4">
  <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-check text-success me-2"></i>Product Category Policies</h6>
    <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" id="addPolicyBtn">
      <i class="bi bi-plus-circle me-1"></i> Add Custom Policy
    </button>
  </div>
  <div class="card-body p-4">
    <form action="<?= url('admin/warranty-registrations/policies') ?>" method="POST" id="policyForm">
      <?= csrf_field() ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-4" id="policiesTable">
          <thead class="table-light">
            <tr>
              <th style="width: 25%;">Product Category <span class="text-danger">*</span></th>
              <th style="width: 20%;">Model Specific (Optional)</th>
              <th style="width: 18%;">Duration (Months) <span class="text-danger">*</span></th>
              <th style="width: 25%;">Description / Terms</th>
              <th style="width: 7%;" class="text-center">Status</th>
              <th style="width: 5%;" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody id="policiesTableBody">
            <?php foreach ($policies as $idx => $p): ?>
              <tr>
                <td>
                  <input type="hidden" name="policies[<?= $idx ?>][id]" value="<?= e($p['id'] ?? ('pol_' . $idx)) ?>">
                  <select name="policies[<?= $idx ?>][product_category]" class="form-select form-select-sm" required>
                    <option value="">Select Category...</option>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?= e($cat) ?>" <?= ($p['product_category'] ?? '') === $cat ? 'selected' : '' ?>>
                        <?= e($cat) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <input type="text" 
                         name="policies[<?= $idx ?>][product_model]" 
                         class="form-control form-control-sm" 
                         placeholder="Applies to all models"
                         value="<?= e($p['product_model'] ?? '') ?>">
                  <small class="text-muted" style="font-size:0.75rem;">Leave empty for entire category</small>
                </td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="number" 
                           name="policies[<?= $idx ?>][duration_months]" 
                           class="form-control" 
                           min="1" 
                           max="120" 
                           required 
                           value="<?= (int)($p['duration_months'] ?? 12) ?>">
                    <span class="input-group-text">Months</span>
                  </div>
                </td>
                <td>
                  <input type="text" 
                         name="policies[<?= $idx ?>][description]" 
                         class="form-control form-control-sm" 
                         placeholder="e.g. Standard 2-year warranty"
                         value="<?= e($p['description'] ?? '') ?>">
                </td>
                <td class="text-center">
                  <div class="form-check form-switch d-inline-block">
                    <input class="form-check-input" 
                           type="checkbox" 
                           role="switch" 
                           name="policies[<?= $idx ?>][active]" 
                           value="1" 
                           <?= !empty($p['active']) ? 'checked' : '' ?>>
                  </div>
                </td>
                <td class="text-end">
                  <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn" title="Remove row">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
        <div class="text-muted small">
          <i class="bi bi-info-circle me-1"></i>
          Active policies will automatically calculate warranty periods upon registration approval.
        </div>
        <button type="submit" class="btn btn-primary px-4 fw-bold">
          <i class="bi bi-check-circle me-1"></i> Save Policies
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Storage and Architecture Information -->
<div class="card shadow-sm border-0 rounded-3">
  <div class="card-header bg-light py-3 border-bottom">
    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-hdd-network text-info me-2"></i>JSON File-Based Policy Engine</h6>
  </div>
  <div class="card-body p-4">
    <div class="row g-3 small">
      <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-2">Storage Location</h6>
        <p class="text-muted mb-2">
          Stored securely in <code class="text-primary">storage/warranty/settings/warranty-policies.json</code> with atomic file writes and server-side file locking.
        </p>
        <p class="text-muted mb-0">
          No external database tables or SQL migrations are required.
        </p>
      </div>
      <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-2">Calculation Logic</h6>
        <ul class="text-muted ps-3 mb-0">
          <li><strong>Start Date:</strong> Verified customer purchase date.</li>
          <li><strong>End Date:</strong> Verified purchase date + applicable policy duration in months.</li>
          <li><strong>Fallback:</strong> Default 12 months if no matching policy is configured.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const tableBody = document.getElementById('policiesTableBody');
  const addBtn = document.getElementById('addPolicyBtn');
  let rowCount = <?= count($policies) ?>;

  const categories = <?= json_encode($categories) ?>;

  if (addBtn && tableBody) {
    addBtn.addEventListener('click', function () {
      rowCount++;
      const tr = document.createElement('tr');
      
      let catOptions = '<option value="">Select Category...</option>';
      categories.forEach(function (cat) {
        catOptions += `<option value="${cat}">${cat}</option>`;
      });

      tr.innerHTML = `
        <td>
          <input type="hidden" name="policies[${rowCount}][id]" value="pol_${Date.now()}">
          <select name="policies[${rowCount}][product_category]" class="form-select form-select-sm" required>
            ${catOptions}
          </select>
        </td>
        <td>
          <input type="text" 
                 name="policies[${rowCount}][product_model]" 
                 class="form-control form-control-sm" 
                 placeholder="Applies to all models">
          <small class="text-muted" style="font-size:0.75rem;">Leave empty for entire category</small>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="number" 
                   name="policies[${rowCount}][duration_months]" 
                   class="form-control" 
                   min="1" 
                   max="120" 
                   required 
                   value="12">
            <span class="input-group-text">Months</span>
          </div>
        </td>
        <td>
          <input type="text" 
                 name="policies[${rowCount}][description]" 
                 class="form-control form-control-sm" 
                 placeholder="e.g. Standard warranty">
        </td>
        <td class="text-center">
          <div class="form-check form-switch d-inline-block">
            <input class="form-check-input" 
                   type="checkbox" 
                   role="switch" 
                   name="policies[${rowCount}][active]" 
                   value="1" 
                   checked>
          </div>
        </td>
        <td class="text-end">
          <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn" title="Remove row">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;

      tableBody.appendChild(tr);
    });
  }

  // Row removal delegation
  if (tableBody) {
    tableBody.addEventListener('click', function (e) {
      const btn = e.target.closest('.remove-row-btn');
      if (btn) {
        const row = btn.closest('tr');
        if (row && tableBody.querySelectorAll('tr').length > 1) {
          row.remove();
        } else {
          alert('At least one policy rule must remain configured.');
        }
      }
    });
  }
});
</script>
