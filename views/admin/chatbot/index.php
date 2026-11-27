<?php
declare(strict_types=1);

/**
 * @var array $items
 */
?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h1 class="h3 fw-bold mb-1 text-dark">AI Chatbot Knowledge Base</h1>
    <p class="text-muted small mb-0">Manage customer support questions, automated responses, and grounded knowledge entries used by the website AI assistant.</p>
  </div>
  <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addEntryModal">
    <i class="bi bi-plus-lg me-1"></i> Add Knowledge Entry
  </button>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light text-uppercase fs-7 text-secondary">
        <tr>
          <th style="width: 20%;">Category</th>
          <th style="width: 25%;">Question / Trigger</th>
          <th style="width: 35%;">Answer / Response</th>
          <th style="width: 10%;">Status</th>
          <th style="width: 10%; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
          <tr>
            <td colspan="5" class="text-center py-4 text-muted">No knowledge entries found. Add your first entry above.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $entry): ?>
            <tr>
              <td>
                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">
                  <?= e($entry['category'] ?? 'General') ?>
                </span>
                <?php if (!empty($entry['keywords'])): ?>
                  <div class="text-muted fs-8 mt-1 text-truncate" style="max-width: 180px;" title="<?= e(implode(', ', (array)$entry['keywords'])) ?>">
                    <i class="bi bi-tags me-1"></i><?= e(implode(', ', (array)$entry['keywords'])) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="fw-bold text-dark">
                <?= e($entry['question'] ?? '') ?>
              </td>
              <td class="small text-secondary">
                <div style="max-height: 80px; overflow-y: auto; line-height: 1.4;">
                  <?= nl2br(e($entry['answer'] ?? '')) ?>
                </div>
                <?php if (!empty($entry['link_url'])): ?>
                  <div class="mt-1">
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 fs-8">
                      <i class="bi bi-link-45deg"></i> <?= e($entry['link_label'] ?: $entry['link_url']) ?>
                    </span>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= url('admin/chatbot/toggle/' . urlencode($entry['id'])) ?>" class="badge text-decoration-none <?= !empty($entry['active']) ? 'bg-success' : 'bg-secondary' ?>" title="Click to toggle status">
                  <?= !empty($entry['active']) ? 'Active' : 'Disabled' ?>
                </a>
              </td>
              <td style="text-align: right;">
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal<?= e($entry['id']) ?>" 
                        title="Edit Entry">
                  <i class="bi bi-pencil"></i>
                </button>
                <a href="<?= url('admin/chatbot/delete/' . urlencode($entry['id'])) ?>" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to delete this chatbot entry?');" 
                   title="Delete Entry">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= e($entry['id']) ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <form method="POST" action="<?= url('admin/chatbot/update/' . urlencode($entry['id'])) ?>">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <div class="modal-header">
                      <h5 class="modal-title fw-bold">Edit Knowledge Entry</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label fw-bold">Category</label>
                          <input type="text" name="category" class="form-control" value="<?= e($entry['category'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fw-bold">Keywords (Comma separated)</label>
                          <input type="text" name="keywords" class="form-control" value="<?= e(implode(', ', (array)($entry['keywords'] ?? []))) ?>">
                        </div>
                        <div class="col-12">
                          <label class="form-label fw-bold">Question / Topic</label>
                          <input type="text" name="question" class="form-control" value="<?= e($entry['question'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                          <label class="form-label fw-bold">Bot Answer (Markdown supported)</label>
                          <textarea name="answer" class="form-control" rows="4" required><?= e($entry['answer'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fw-bold">Optional Link URL (e.g. /shop, /warranty-registration)</label>
                          <input type="text" name="link_url" class="form-control" value="<?= e($entry['link_url'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fw-bold">Optional Link Label</label>
                          <input type="text" name="link_label" class="form-control" value="<?= e($entry['link_label'] ?? '') ?>">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="<?= url('admin/chatbot/store') ?>">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Add New Knowledge Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Category</label>
              <select name="category" class="form-select">
                <option value="Product Information">Product Information</option>
                <option value="Warranty Information">Warranty Information</option>
                <option value="Installation & Technical Support">Installation & Technical Support</option>
                <option value="Orders & Purchases">Orders & Purchases</option>
                <option value="Customer Support">Customer Support</option>
                <option value="Company Information">Company Information</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Keywords (Comma separated)</label>
              <input type="text" name="keywords" class="form-control" placeholder="e.g. bluetooth, lock pin, battery life">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">Question / Topic</label>
              <input type="text" name="question" class="form-control" placeholder="e.g. Can I unlock the door using Bluetooth?" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">Bot Answer (Markdown supported)</label>
              <textarea name="answer" class="form-control" rows="4" placeholder="Enter clear, helpful answer..." required></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Optional Link URL</label>
              <input type="text" name="link_url" class="form-control" placeholder="/shop or /contact">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Optional Link Label</label>
              <input type="text" name="link_label" class="form-control" placeholder="View Products">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary fw-bold">Add Entry</button>
        </div>
      </form>
    </div>
  </div>
</div>
