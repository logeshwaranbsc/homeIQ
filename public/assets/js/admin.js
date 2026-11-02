/**
 * HOMEIQ Admin Management Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sidebar Toggle on Mobile
  const sidebar = document.querySelector('.admin-sidebar');
  const toggleBtn = document.getElementById('btnToggleSidebar');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
      if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove('show');
      }
    });
  }

  // 2. Dynamic Specifications Table in Product Form
  const addSpecBtn = document.getElementById('btnAddSpecRow');
  const specContainer = document.getElementById('specsRowsContainer');

  if (addSpecBtn && specContainer) {
    addSpecBtn.addEventListener('click', () => {
      const row = document.createElement('div');
      row.className = 'row g-2 mb-2 spec-row';
      row.innerHTML = `
        <div class="col-5">
          <input type="text" name="spec_key[]" class="form-control form-control-sm" placeholder="e.g. Battery Life">
        </div>
        <div class="col-6">
          <input type="text" name="spec_val[]" class="form-control form-control-sm" placeholder="e.g. 10-12 Months">
        </div>
        <div class="col-1 text-center">
          <button type="button" class="btn btn-sm btn-outline-danger btn-remove-spec"><i class="bi bi-x-lg"></i></button>
        </div>
      `;
      specContainer.appendChild(row);

      row.querySelector('.btn-remove-spec').addEventListener('click', () => {
        row.remove();
      });
    });

    document.querySelectorAll('.btn-remove-spec').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.closest('.spec-row')?.remove();
      });
    });
  }

  // 3. Confirm Delete Actions
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
      const msg = el.getAttribute('data-confirm') || 'Are you sure you want to proceed with this action?';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // 4. Auto Slug Generator
  const nameInput = document.getElementById('inputName');
  const slugInput = document.getElementById('inputSlug');

  if (nameInput && slugInput && !slugInput.value) {
    nameInput.addEventListener('input', () => {
      slugInput.value = nameInput.value
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    });
  }
});

