/**
 * HOMEIQ Storefront Client Script
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. PIN code availability checker
  const pinInput = document.getElementById('pincodeCheckInput');
  const pinBtn = document.getElementById('btnCheckPincode');
  const pinResult = document.getElementById('pincodeResult');

  if (pinBtn && pinInput && pinResult) {
    pinBtn.addEventListener('click', async () => {
      const pin = pinInput.value.trim();
      if (!pin || !/^[1-9][0-9]{5}$/.test(pin)) {
        pinResult.innerHTML = '<span class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i>Please enter a valid 6-digit Indian PIN code.</span>';
        return;
      }

      pinBtn.disabled = true;
      pinBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Checking...';

      try {
        const response = await fetch(`${window.HOMEIQ_BASE_URL || ''}/api/check-pincode?pincode=${encodeURIComponent(pin)}`);
        const data = await response.json();

        if (data.available) {
          pinResult.innerHTML = `
            <div class="alert alert-success py-2 px-3 small mt-2 mb-0">
              <strong><i class="bi bi-check-circle-fill me-1"></i>Delivery & Installation Available to ${data.pincode}</strong><br>
              ${data.delivery_estimate} • Free Door Installation
            </div>`;
        } else {
          pinResult.innerHTML = `<span class="text-danger small mt-2 d-block"><i class="bi bi-x-circle me-1"></i>${data.message}</span>`;
        }
      } catch (err) {
        pinResult.innerHTML = '<span class="text-danger small mt-2 d-block">Error verifying PIN code. Please try again.</span>';
      } finally {
        pinBtn.disabled = false;
        pinBtn.innerHTML = 'Check';
      }
    });
  }

  // 2. Product Gallery Thumbnail Click
  const mainImage = document.getElementById('productMainImage');
  const thumbnails = document.querySelectorAll('.thumbnail-item');

  if (mainImage && thumbnails.length > 0) {
    thumbnails.forEach(thumb => {
      thumb.addEventListener('click', () => {
        thumbnails.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        const newSrc = thumb.getAttribute('data-img-src');
        if (newSrc && mainImage.getAttribute('src') !== newSrc) {
          mainImage.style.opacity = '0.5';
          mainImage.src = newSrc;
          mainImage.onload = () => {
            mainImage.style.opacity = '1';
          };
          setTimeout(() => { mainImage.style.opacity = '1'; }, 200);
        }
      });
    });
  }

  // 3. Wishlist AJAX Toggle
  document.querySelectorAll('.btn-wishlist-toggle').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const productId = btn.getAttribute('data-product-id');
      if (!productId) return;

      try {
        const formData = new FormData();
        formData.append('product_id', productId);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
          formData.append('_csrf_token', csrfToken);
        }

        const res = await fetch(`${window.HOMEIQ_BASE_URL || ''}/wishlist/toggle`, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
          btn.classList.toggle('active', data.added);
          const icon = btn.querySelector('i');
          if (icon) {
            if (data.added) {
              icon.classList.remove('bi-heart');
              icon.classList.add('bi-heart-fill');
            } else {
              icon.classList.remove('bi-heart-fill');
              icon.classList.add('bi-heart');
            }
          }
        }
      } catch (err) {
        console.error('Wishlist error:', err);
      }
    });
  });

  // 4. Quantity Adjusters
  document.querySelectorAll('.btn-qty-minus').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.input-group')?.querySelector('.input-qty');
      if (input) {
        let val = parseInt(input.value, 10) || 1;
        if (val > 1) {
          input.value = val - 1;
          input.dispatchEvent(new Event('change'));
        }
      }
    });
  });

  document.querySelectorAll('.btn-qty-plus').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.input-group')?.querySelector('.input-qty');
      if (input) {
        let val = parseInt(input.value, 10) || 1;
        const max = parseInt(input.getAttribute('max'), 10) || 99;
        if (val < max) {
          input.value = val + 1;
          input.dispatchEvent(new Event('change'));
        }
      }
    });
  });

  // 5. Featured Products Horizontal Carousel / Slider
  const track = document.getElementById('featuredProductsTrack');
  const prevBtns = [
    document.getElementById('featuredSidePrev'),
    document.getElementById('featuredHeaderPrev')
  ].filter(Boolean);
  const nextBtns = [
    document.getElementById('featuredSideNext'),
    document.getElementById('featuredHeaderNext')
  ].filter(Boolean);

  if (track && (prevBtns.length > 0 || nextBtns.length > 0)) {
    const getScrollStep = () => {
      const slide = track.querySelector('.product-slider-slide');
      if (slide) {
        const gap = parseFloat(window.getComputedStyle(track).gap) || 24;
        return slide.getBoundingClientRect().width + gap;
      }
      return 300;
    };

    nextBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (track.scrollLeft >= maxScroll - 15) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          track.scrollBy({ left: step, behavior: 'smooth' });
        }
      });
    });

    prevBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (track.scrollLeft <= 15) {
          track.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
          track.scrollBy({ left: -step, behavior: 'smooth' });
        }
      });
    });

    // Mouse drag-to-scroll support for desktop
    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let hasDragged = false;

    track.addEventListener('mousedown', (e) => {
      if (e.target.closest('button') || e.target.closest('a')) return;
      isDown = true;
      hasDragged = false;
      track.classList.add('is-dragging');
      startX = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
    });

    window.addEventListener('mouseup', () => {
      if (!isDown) return;
      isDown = false;
      track.classList.remove('is-dragging');
    });

    track.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - track.offsetLeft;
      const walk = (x - startX) * 1.4;
      if (Math.abs(walk) > 5) {
        hasDragged = true;
      }
      track.scrollLeft = scrollLeft - walk;
    });

    // Prevent navigation if user dragged
    track.addEventListener('click', (e) => {
      if (hasDragged) {
        e.preventDefault();
        e.stopPropagation();
        hasDragged = false;
      }
    }, true);
  }
});

