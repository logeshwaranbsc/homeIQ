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

  // 3. Wishlist AJAX Toggle & Native/Clipboard Share Action
  const copyToClipboard = (text, btn) => {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(() => {
        showShareSuccess(btn);
      }).catch(() => {
        fallbackCopy(text, btn);
      });
    } else {
      fallbackCopy(text, btn);
    }
  };

  const fallbackCopy = (text, btn) => {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
      document.execCommand('copy');
      showShareSuccess(btn);
    } catch (e) {
      alert('Product link: ' + text);
    }
    document.body.removeChild(tempInput);
  };

  const showShareSuccess = (btn) => {
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.classList.add('copied');
    btn.innerHTML = '<i class="bi bi-check2"></i>';
    btn.setAttribute('title', 'Link Copied!');

    let toast = document.getElementById('hiqShareToast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'hiqShareToast';
      toast.className = 'hiq-share-toast';
      document.body.appendChild(toast);
    }
    toast.innerHTML = '<i class="bi bi-check-circle-fill"></i> Product link copied to clipboard!';
    toast.classList.add('show');

    setTimeout(() => {
      btn.classList.remove('copied');
      btn.innerHTML = originalHtml;
      btn.setAttribute('title', 'Share product');
    }, 2000);

    setTimeout(() => {
      toast.classList.remove('show');
    }, 2800);
  };

  document.addEventListener('click', async (e) => {
    const wishlistBtn = e.target.closest('.btn-action-wishlist, .modern-wishlist-btn, .btn-wishlist-toggle');
    const shareBtn = e.target.closest('.btn-action-share');

    if (wishlistBtn) {
      e.preventDefault();
      e.stopPropagation();

      const productId = wishlistBtn.getAttribute('data-product-id');
      if (!productId) return;

      wishlistBtn.disabled = true;
      try {
        const formData = new FormData();
        formData.append('product_id', productId);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) formData.append('_csrf_token', csrfToken);

        const res = await fetch(`${window.HOMEIQ_BASE_URL || ''}/wishlist/toggle`, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
          const isAdded = !!data.added;
          document.querySelectorAll(`[data-product-id="${productId}"]`).forEach(btn => {
            if (btn.classList.contains('btn-action-wishlist') || btn.classList.contains('modern-wishlist-btn') || btn.classList.contains('btn-wishlist-toggle')) {
              btn.classList.toggle('active', isAdded);
              const icon = btn.querySelector('i');
              if (icon) {
                if (isAdded) {
                  icon.className = 'bi bi-heart-fill text-danger';
                } else {
                  icon.className = 'bi bi-heart';
                }
              }
            }
          });

          // Update header counter
          document.querySelectorAll('.nav-action-btn[title="Wishlist"]').forEach(parent => {
            let badge = parent.querySelector('.badge-counter');
            if (typeof data.count !== 'undefined') {
              if (data.count > 0) {
                if (!badge) {
                  badge = document.createElement('span');
                  badge.className = 'badge-counter';
                  parent.appendChild(badge);
                }
                badge.textContent = data.count;
              } else if (badge) {
                badge.remove();
              }
            }
          });
        }
      } catch (err) {
        console.error('Wishlist error:', err);
      } finally {
        wishlistBtn.disabled = false;
      }
    } else if (shareBtn) {
      e.preventDefault();
      e.stopPropagation();

      const rawUrl = shareBtn.getAttribute('data-share-url') || window.location.href;
      const shareTitle = shareBtn.getAttribute('data-share-title') || document.title;
      const fullUrl = rawUrl.startsWith('http') ? rawUrl : `${window.location.origin}${rawUrl}`;

      if (navigator.share) {
        navigator.share({
          title: shareTitle,
          text: `Check out ${shareTitle} on HOMEIQ:`,
          url: fullUrl
        }).catch(() => {
          copyToClipboard(fullUrl, shareBtn);
        });
      } else {
        copyToClipboard(fullUrl, shareBtn);
      }
    }
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

  /**
   * Reusable horizontal slider with arrow navigation, drag-to-scroll,
   * and optional overflow-driven arrow visibility.
   */
  function initHorizontalSlider({ container, track, prevButtons, nextButtons, slideSelector, autoArrows = false }) {
    if (!track) return;

    const getScrollStep = () => {
      const slide = track.querySelector(slideSelector);
      if (slide) {
        const gap = parseFloat(window.getComputedStyle(track).gap) || 20;
        return slide.getBoundingClientRect().width + gap;
      }
      return 300;
    };

    const syncOverflowState = () => {
      if (!autoArrows || !container) return;
      container.classList.remove('has-arrows');
      const needsArrows = track.scrollWidth > track.clientWidth + 4;
      container.classList.toggle('has-arrows', needsArrows);
      [...prevButtons, ...nextButtons].forEach((btn) => {
        btn.hidden = !needsArrows;
      });
    };

    nextButtons.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (maxScroll <= 0) return;
        if (track.scrollLeft >= maxScroll - 15) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          track.scrollBy({ left: step, behavior: 'smooth' });
        }
      });
    });

    prevButtons.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const step = getScrollStep();
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (maxScroll <= 0) return;
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

    if (autoArrows) {
      syncOverflowState();
      window.addEventListener('load', syncOverflowState);
      window.addEventListener('resize', syncOverflowState);
    }
  }

  // 5. Category Collection Horizontal Carousel (Arrows appear automatically when categories overflow)
  initHorizontalSlider({
    container: document.getElementById('collectionSliderContainer'),
    track: document.getElementById('collectionCategoryTrack'),
    prevButtons: [document.getElementById('collectionSidePrev')].filter(Boolean),
    nextButtons: [document.getElementById('collectionSideNext')].filter(Boolean),
    slideSelector: '.hiq-collection-slide',
    autoArrows: true
  });

  // 6. Featured Products Horizontal Carousel / Slider
  initHorizontalSlider({
    container: null,
    track: document.getElementById('featuredProductsTrack'),
    prevButtons: [
      document.getElementById('featuredSidePrev'),
      document.getElementById('featuredHeaderPrev')
    ].filter(Boolean),
    nextButtons: [
      document.getElementById('featuredSideNext'),
      document.getElementById('featuredHeaderNext')
    ].filter(Boolean),
    slideSelector: '.product-slider-slide',
    autoArrows: false
  });

  // 7. Slide-out Cart Drawer & AJAX Add to Cart
  const initCartDrawer = () => {
    const drawerEl = document.getElementById('cartDrawer');
    if (!drawerEl) return;

    const drawerCount = document.getElementById('cartDrawerCount');
    const drawerItems = document.getElementById('cartDrawerItems');
    const drawerEmpty = document.getElementById('cartDrawerEmpty');
    const drawerFooter = document.getElementById('cartDrawerFooter');
    const drawerSubtotal = document.getElementById('cartDrawerSubtotal');
    const drawerShipping = document.getElementById('cartDrawerShipping');
    const drawerGrandTotal = document.getElementById('cartDrawerGrandTotal');
    const shippingMeter = document.getElementById('cartDrawerShippingMeter');
    const shippingText = document.getElementById('cartDrawerShippingText');
    const shippingBar = document.getElementById('cartDrawerShippingBar');

    const floatingBtn = document.getElementById('floatingCartBtn');
    const floatingCount = document.getElementById('floatingCartCount');
    const floatingTotal = document.getElementById('floatingCartTotal');

    const isCartPage = () => {
      const path = (window.location.pathname || '').replace(/\/+$/, '');
      return path.endsWith('/cart') ||
             document.body.classList.contains('page-cart') ||
             document.body.dataset.page === 'cart';
    };

    let collapseTimer = null;

    const clearCollapseTimer = () => {
      if (collapseTimer) {
        clearTimeout(collapseTimer);
        collapseTimer = null;
      }
    };

    const startCollapseTimer = (delay = 3000) => {
      clearCollapseTimer();
      if (!floatingBtn || isCartPage()) return;
      collapseTimer = setTimeout(() => {
        if (floatingBtn && floatingBtn.classList.contains('is-visible') && !floatingBtn.matches(':hover')) {
          floatingBtn.classList.add('is-collapsed');
        }
      }, delay);
    };

    const expandFloatingBtn = () => {
      clearCollapseTimer();
      if (floatingBtn) {
        floatingBtn.classList.remove('is-collapsed');
      }
    };

    if (floatingBtn) {
      if (isCartPage()) {
        floatingBtn.classList.remove('is-visible', 'is-collapsed');
        floatingBtn.style.display = 'none';
      }

      floatingBtn.addEventListener('mouseenter', () => {
        expandFloatingBtn();
      });

      floatingBtn.addEventListener('mouseleave', () => {
        if (floatingBtn.classList.contains('is-visible')) {
          startCollapseTimer(3000);
        }
      });
    }

    const updateHeaderCounters = (count) => {
      const headerDesk = document.getElementById('headerCartCount');
      const headerMobile = document.getElementById('mobileHeaderCartCount');
      if (headerDesk) headerDesk.textContent = count;
      if (headerMobile) headerMobile.textContent = count;
    };

    const renderCartDrawer = (cart) => {
      if (!cart || !cart.items || cart.items.length === 0 || cart.total_items === 0) {
        if (drawerCount) drawerCount.textContent = '0';
        if (drawerItems) drawerItems.innerHTML = '';
        if (drawerEmpty) drawerEmpty.classList.remove('d-none');
        if (drawerFooter) drawerFooter.classList.add('d-none');
        if (shippingMeter) shippingMeter.classList.add('d-none');
        clearCollapseTimer();
        if (floatingBtn) floatingBtn.classList.remove('is-visible', 'is-collapsed');
        updateHeaderCounters(0);
        return;
      }

      const totalItems = cart.total_items || 0;
      if (drawerCount) drawerCount.textContent = totalItems;
      updateHeaderCounters(totalItems);

      if (floatingCount) floatingCount.textContent = totalItems;
      if (floatingTotal) floatingTotal.textContent = cart.formatted_grand_total || cart.formatted_subtotal || ('₹' + Number(cart.grand_total || cart.subtotal).toLocaleString('en-IN'));
      if (floatingBtn) {
        if (isCartPage()) {
          clearCollapseTimer();
          floatingBtn.classList.remove('is-visible', 'is-collapsed');
          floatingBtn.style.display = 'none';
        } else if (!drawerEl.classList.contains('show')) {
          floatingBtn.classList.remove('is-collapsed');
          floatingBtn.classList.add('is-visible');
          floatingBtn.style.display = '';
          startCollapseTimer(3000);
        }
      }

      if (drawerEmpty) drawerEmpty.classList.add('d-none');
      if (drawerFooter) drawerFooter.classList.remove('d-none');
      if (shippingMeter) shippingMeter.classList.remove('d-none');

      if (drawerSubtotal) drawerSubtotal.textContent = cart.formatted_subtotal || ('₹' + Number(cart.subtotal).toLocaleString('en-IN'));
      if (drawerShipping) drawerShipping.textContent = cart.formatted_shipping || (cart.shipping === 0 ? 'FREE' : ('₹' + Number(cart.shipping).toLocaleString('en-IN')));
      if (drawerGrandTotal) drawerGrandTotal.textContent = cart.formatted_grand_total || ('₹' + Number(cart.grand_total).toLocaleString('en-IN'));

      // Free shipping progress
      const threshold = Number(cart.free_shipping_threshold) || 5000;
      const subtotal = Number(cart.subtotal) || 0;
      const percent = Math.min(100, Math.round((subtotal / threshold) * 100));

      if (shippingBar) {
        shippingBar.style.width = percent + '%';
      }
      if (shippingText) {
        if (percent >= 100) {
          shippingText.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> 🎉 You qualify for <strong>FREE Express Shipping</strong>!';
        } else {
          const shortfall = cart.formatted_shipping_shortfall || ('₹' + Number(Math.max(0, threshold - subtotal)).toLocaleString('en-IN'));
          shippingText.innerHTML = `<i class="bi bi-truck me-1"></i> Add <strong>${shortfall}</strong> more to unlock <strong>FREE Express Shipping</strong>!`;
        }
      }

      // Render items
      if (drawerItems) {
        drawerItems.innerHTML = cart.items.map(item => `
          <div class="cart-drawer-item" data-id="${item.id}">
            <a href="${item.product_url || '#'}" class="cart-drawer-item-img">
              <img src="${item.image_url || ''}" alt="${item.name}" onerror="this.onerror=null; this.src='${window.HOMEIQ_BASE_URL || ''}/assets/images/placeholder-lock.svg';">
            </a>
            <div class="cart-drawer-item-info">
              <a href="${item.product_url || '#'}" class="cart-drawer-item-title">${item.name}</a>
              <div class="cart-drawer-item-price">
                <span>${item.formatted_price || ('₹' + Number(item.price).toLocaleString('en-IN'))}</span>
                ${item.mrp && Number(item.mrp) > Number(item.price) ? `<span class="cart-drawer-item-mrp">${item.formatted_mrp || ('₹' + Number(item.mrp).toLocaleString('en-IN'))}</span>` : ''}
              </div>
              <div class="cart-drawer-item-bottom">
                <div class="drawer-qty-control">
                  <button type="button" class="drawer-qty-btn drawer-btn-minus" data-id="${item.id}" data-qty="${item.quantity - 1}" ${item.quantity <= 1 ? 'disabled' : ''} aria-label="Decrease quantity">
                    <i class="bi bi-dash"></i>
                  </button>
                  <span class="drawer-qty-val">${item.quantity}</span>
                  <button type="button" class="drawer-qty-btn drawer-btn-plus" data-id="${item.id}" data-qty="${item.quantity + 1}" ${item.quantity >= item.stock ? 'disabled' : ''} aria-label="Increase quantity">
                    <i class="bi bi-plus"></i>
                  </button>
                </div>
                <button type="button" class="drawer-item-remove" data-id="${item.id}" title="Remove item" aria-label="Remove item">
                  <i class="bi bi-trash3"></i>
                </button>
              </div>
            </div>
          </div>
        `).join('');
      }
    };

    let autoCloseTimer = null;

    const startAutoCloseTimer = () => {
      clearAutoCloseTimer();
      autoCloseTimer = setTimeout(() => {
        closeCartDrawer();
      }, 2000); // 2 seconds auto-close
    };

    const clearAutoCloseTimer = () => {
      if (autoCloseTimer) {
        clearTimeout(autoCloseTimer);
        autoCloseTimer = null;
      }
    };

    const closeCartDrawer = () => {
      if (window.bootstrap && window.bootstrap.Offcanvas) {
        const instance = bootstrap.Offcanvas.getInstance(drawerEl);
        if (instance) {
          instance.hide();
        }
      }
    };

    const openCartDrawer = () => {
      if (window.bootstrap && window.bootstrap.Offcanvas) {
        const instance = bootstrap.Offcanvas.getOrCreateInstance(drawerEl);
        instance.show();
        startAutoCloseTimer();
      }
    };

    const fetchCartData = async () => {
      try {
        const res = await fetch(`${window.HOMEIQ_BASE_URL || ''}/api/cart`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (res.ok) {
          const data = await res.json();
          if (data.cart) {
            renderCartDrawer(data.cart);
          }
        }
      } catch (err) {
        console.error('Error fetching cart:', err);
      }
    };

    // Populate drawer when opened and hide floating button
    drawerEl.addEventListener('show.bs.offcanvas', () => {
      fetchCartData();
      clearCollapseTimer();
      if (floatingBtn) {
        floatingBtn.classList.remove('is-visible', 'is-collapsed');
      }
    });

    // Start 2s countdown when fully shown
    drawerEl.addEventListener('shown.bs.offcanvas', () => {
      startAutoCloseTimer();
    });

    // Pause timer on hover, resume on mouse leave
    drawerEl.addEventListener('mouseenter', () => {
      clearAutoCloseTimer();
    });

    drawerEl.addEventListener('mouseleave', () => {
      if (drawerEl.classList.contains('show')) {
        startAutoCloseTimer();
      }
    });

    // When drawer starts closing, reveal the floating cart button (suppressed on cart page)
    // Full button shows first, then collapses after 3 seconds to just bag icon + count and arrow
    drawerEl.addEventListener('hide.bs.offcanvas', () => {
      clearAutoCloseTimer();
      const count = parseInt(drawerCount ? drawerCount.textContent : '0', 10) || 0;
      if (floatingBtn && count > 0 && !isCartPage()) {
        floatingBtn.classList.remove('is-collapsed');
        floatingBtn.classList.add('is-visible');
        floatingBtn.style.display = '';
        startCollapseTimer(3000);
      }
    });

    // Quantity update & item removal inside drawer
    if (drawerItems) {
      drawerItems.addEventListener('click', async (e) => {
        startAutoCloseTimer();
        const minusBtn = e.target.closest('.drawer-btn-minus');
        const plusBtn = e.target.closest('.drawer-btn-plus');
        const removeBtn = e.target.closest('.drawer-item-remove');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (minusBtn || plusBtn) {
          const btn = minusBtn || plusBtn;
          const productId = btn.dataset.id;
          const quantity = parseInt(btn.dataset.qty, 10);
          if (!productId || isNaN(quantity)) return;

          btn.disabled = true;
          try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            if (csrfToken) formData.append('_csrf_token', csrfToken);

            const res = await fetch(`${window.HOMEIQ_BASE_URL || ''}/cart/update`, {
              method: 'POST',
              body: formData,
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.cart) {
              renderCartDrawer(data.cart);
            }
          } catch (err) {
            console.error('Error updating cart quantity:', err);
          }
        } else if (removeBtn) {
          const productId = removeBtn.dataset.id;
          if (!productId) return;

          removeBtn.disabled = true;
          try {
            const formData = new FormData();
            formData.append('product_id', productId);
            if (csrfToken) formData.append('_csrf_token', csrfToken);

            const res = await fetch(`${window.HOMEIQ_BASE_URL || ''}/cart/remove`, {
              method: 'POST',
              body: formData,
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.cart) {
              renderCartDrawer(data.cart);
            }
          } catch (err) {
            console.error('Error removing cart item:', err);
          }
        }
      });
    }

    // Intercept all "Add to Cart" form submissions site-wide
    document.addEventListener('submit', async (e) => {
      const form = e.target.closest('form');
      if (!form) return;

      const action = form.getAttribute('action') || '';
      if (!action.includes('/cart/add')) return;

      // Allow "Buy Now" buttons to submit normally so user proceeds straight to checkout
      const submitter = e.submitter;
      if (submitter && (submitter.getAttribute('name') === 'buy_now' || submitter.value === 'buy_now')) {
        return;
      }

      e.preventDefault();

      const submitBtn = submitter || form.querySelector('button[type="submit"]');
      const originalHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Adding...';
      }

      try {
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken && !formData.has('_csrf_token')) {
          formData.append('_csrf_token', csrfToken);
        }

        const res = await fetch(action, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });

        const data = await res.json();
        if (data.success && data.cart) {
          renderCartDrawer(data.cart);
          openCartDrawer();
        } else {
          alert(data.error || 'Unable to add item to cart. Please try again.');
        }
      } catch (err) {
        console.error('Cart add error:', err);
        // Fallback to normal form submission if network fails
        form.submit();
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalHtml;
        }
      }
    });

    // Initial silent fetch to populate cart drawer count and items
    fetchCartData();
  };

  initCartDrawer();
});

