<?php
declare(strict_types=1);

/**
 * @var array|null $product
 * @var array $categories
 */

$isEdit = !empty($product);
$action = $isEdit ? url('admin/products/update/' . $product['id']) : url('admin/products/store');

$featuresText = $isEdit && is_array($product['features'] ?? null) ? implode("\n", $product['features']) : '';
$includedText = $isEdit && is_array($product['whats_included'] ?? null) ? implode("\n", $product['whats_included']) : '';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold m-0"><?= $isEdit ? 'Edit ' . e($product['name']) : 'Add New Digital Smart Lock' ?></h4>
    <small class="text-muted">Enter complete hardware, biometric, and pricing specifications.</small>
  </div>
  <a href="<?= url('admin/products') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back to Catalog
  </a>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="row g-4">
    <!-- Left Column: Core Fields -->
    <div class="col-lg-8">
      <!-- General Information -->
      <div class="admin-form-card shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Primary Hardware Details</h5>

        <div class="mb-3">
          <label class="form-label">Product Name *</label>
          <input type="text" name="name" id="inputName" class="form-control" required placeholder="e.g. HOMEIQ Black AI Face Recognition Smart Door Lock" value="<?= e($product['name'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">SKU (Stock Keeping Unit) *</label>
            <input type="text" name="sku" class="form-control text-uppercase" required placeholder="e.g. HIQ-AI-F900-BLK" value="<?= e($product['sku'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">URL Slug</label>
            <input type="text" name="slug" id="inputSlug" class="form-control" placeholder="auto-generated-if-blank" value="<?= e($product['slug'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Short Summary Description *</label>
          <textarea name="short_description" rows="2" class="form-control" placeholder="1-2 sentences highlighting main 9-in-1 features" required><?= e($product['short_description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Full Editorial Description *</label>
          <textarea name="full_description" rows="6" class="form-control" placeholder="Detailed product narrative, materials, security benefits..." required><?= e($product['full_description'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Features & What's Included -->
      <div class="admin-form-card shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Bullet Features & Box Contents</h5>

        <div class="mb-3">
          <label class="form-label">Key Features (One feature per line)</label>
          <textarea name="features" rows="5" class="form-control" placeholder="AI Face Recognition with 3D Structured Light&#10;Semiconductor Fingerprint on Handle&#10;Tuya App Remote OTP&#10;Emergency USB-C Port"><?= e($featuresText) ?></textarea>
          <small class="text-muted">Each new line creates a checkmark feature item on the storefront.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">What's Included in Box (One item per line)</label>
          <textarea name="whats_included" rows="4" class="form-control" placeholder="Front and Back Lock Escutcheons&#10;6068 Stainless Steel Mortise&#10;Rechargeable 5000mAh Lithium Battery&#10;2x RFID Smart Cards&#10;2x Mechanical Emergency Keys"><?= e($includedText) ?></textarea>
        </div>
      </div>

      <!-- Technical Specifications Key-Value Pairs -->
      <div class="admin-form-card shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
          <h5 class="fw-bold m-0">Technical Specifications Table</h5>
          <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddSpecRow">
            <i class="bi bi-plus-lg me-1"></i> Add Spec Row
          </button>
        </div>

        <div id="specsRowsContainer">
          <?php 
            $specs = $product['specifications'] ?? [
              'Fingerprint Sensor' => 'Semiconductor 3D Live Recognition',
              'Recognition Speed' => '< 0.5 seconds',
              'Door Thickness' => '35mm - 90mm',
              'Battery' => 'Rechargeable 4200mAh Lithium-ion',
              'Emergency Charging' => 'USB-C External Port',
            ];
            foreach ($specs as $k => $v): 
          ?>
            <div class="row g-2 mb-2 spec-row">
              <div class="col-5">
                <input type="text" name="spec_key[]" class="form-control form-control-sm" value="<?= e($k) ?>" placeholder="e.g. Battery">
              </div>
              <div class="col-6">
                <input type="text" name="spec_val[]" class="form-control form-control-sm" value="<?= e($v) ?>" placeholder="e.g. 5000mAh Li-ion">
              </div>
              <div class="col-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-spec"><i class="bi bi-x-lg"></i></button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Right Column: Pricing, Category, Image & Visibility -->
    <div class="col-lg-4">
      <!-- Pricing & Inventory -->
      <div class="admin-form-card shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Pricing & Stock</h5>

        <div class="mb-3">
          <label class="form-label">Regular MRP (₹) *</label>
          <input type="number" step="0.01" name="price" class="form-control" required value="<?= e($product['price'] ?? 0) ?>">
          <small class="text-muted">Original manufacturer retail price.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Selling / Sale Price (₹) *</label>
          <input type="number" step="0.01" name="sale_price" class="form-control" required value="<?= e($product['sale_price'] ?? $product['price'] ?? 0) ?>">
          <small class="text-muted">Actual price charged to customers.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Stock Quantity Available *</label>
          <input type="number" name="stock" class="form-control" required min="0" value="<?= e($product['stock'] ?? 0) ?>">
        </div>
      </div>

      <!-- Classification & Image -->
      <div class="admin-form-card shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Classification & Media</h5>

        <div class="mb-3">
          <label class="form-label">Category *</label>
          <select name="category_slug" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= e($cat['slug']) ?>" <?= ($product['category_slug'] ?? '') === $cat['slug'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Main Product Vector / Photo</label>
          <?php if (!empty($product['main_image'])): ?>
            <div class="mb-2 p-2 bg-light border text-center rounded">
              <img src="<?= upload_url($product['main_image']) ?>" alt="" style="max-height: 120px; object-fit: contain;">
            </div>
          <?php endif; ?>
          <input type="file" name="main_image" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp,image/svg+xml">
          <small class="text-muted">Allowed: JPG, PNG, WEBP, SVG (Max 5MB).</small>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label">Chassis Finish</label>
            <input type="text" name="color" class="form-control form-control-sm" placeholder="e.g. Obsidian Black" value="<?= e($product['color'] ?? 'Black') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Mortise Standard</label>
            <input type="text" name="lock_type" class="form-control form-control-sm" placeholder="e.g. Mortise 6068" value="<?= e($product['lock_type'] ?? 'Mortise Lock') ?>">
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label">Dimensions</label>
            <input type="text" name="dimensions" class="form-control form-control-sm" placeholder="40 × 17 × 9 cm" value="<?= e($product['dimensions'] ?? '') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Weight</label>
            <input type="text" name="weight" class="form-control form-control-sm" placeholder="1.5 kg" value="<?= e($product['weight'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Warranty Terms</label>
          <input type="text" name="warranty" class="form-control form-control-sm" value="<?= e($product['warranty'] ?? '2 Years Official Warranty') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Installation Terms</label>
          <input type="text" name="installation" class="form-control form-control-sm" value="<?= e($product['installation'] ?? 'Free Professional Installation Available') ?>">
        </div>
      </div>

      <!-- Visibility Flags & Submit -->
      <div class="admin-form-card shadow-sm mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Status & Publishing</h5>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck" <?= !empty($product['is_active'] ?? true) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="isActiveCheck">Published on Storefront</label>
        </div>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedCheck" <?= !empty($product['is_featured']) ? 'checked' : '' ?>>
          <label class="form-check-label fw-bold" for="isFeaturedCheck">Feature on Homepage</label>
        </div>

        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Save Digital Lock
        </button>
      </div>
    </div>
  </div>
</form>

