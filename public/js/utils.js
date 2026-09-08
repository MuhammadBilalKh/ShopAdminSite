/* ============================================================
   ShopZone – UI Utilities & Shared Components
   ============================================================ */

/* ── Currency Format ── */
function formatCurrency(amount) {
  const s = Settings.get();
  return s.currency + parseFloat(amount || 0).toFixed(2);
}

/* ── Date Format ── */
function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
}
function formatDateTime(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleString('en-US', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' });
}

/* ── Toast Notifications ── */
let _toastContainer;
function getToastContainer() {
  if (!_toastContainer) {
    _toastContainer = $('<div class="toast-container-custom" id="toastContainer"></div>');
    $('body').append(_toastContainer);
  }
  return _toastContainer;
}

function showToast(msg, type = 'success', duration = 3200) {
  const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
  const toast = $(`
    <div class="custom-toast ${type}">
      <span class="toast-icon">${icons[type] || '✅'}</span>
      <span class="toast-msg">${msg}</span>
      <button class="toast-close">✕</button>
    </div>
  `);
  getToastContainer().append(toast);
  toast.find('.toast-close').on('click', () => dismissToast(toast));
  setTimeout(() => dismissToast(toast), duration);
}

function dismissToast(toast) {
  toast.css({ animation: 'slideOutRight .3s ease forwards' });
  setTimeout(() => toast.remove(), 300);
}

/* ── Generate Star HTML ── */
function starsHTML(rating) {
  let html = '';
  for (let i = 1; i <= 5; i++) {
    html += `<i class="${i <= Math.round(rating) ? 'ri-star-fill' : 'ri-star-line'}"></i>`;
  }
  return `<span class="rating-stars">${html}</span> <small class="text-muted">(${rating})</small>`;
}

/* ── Stock Badge ── */
function stockBadge(stock) {
  if (stock > 10)  return '<span class="badge-status instock">In Stock</span>';
  if (stock > 0)   return `<span class="badge-status lowstock">Low Stock (${stock})</span>`;
  return '<span class="badge-status outstock">Out of Stock</span>';
}

/* ── Order Status Badge ── */
function statusBadge(status) {
  return `<span class="badge-status ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
}

/* ── Update Navbar Cart Count ── */
function updateCartBadge() {
  const count = Cart.count();
  $('.badge-count.cart-count').text(count);
  if (count > 0) $('.badge-count.cart-count').show(); else $('.badge-count.cart-count').hide();
}

/* ── Update Navbar Wishlist Count ── */
function updateWishlistBadge() {
  const count = Wishlist.count();
  $('.badge-count.wishlist-count').text(count);
  if (count > 0) $('.badge-count.wishlist-count').show(); else $('.badge-count.wishlist-count').hide();
}

/* ── Render Product Card HTML ── */
function renderProductCard(product) {
  const finalPrice = product.salePrice && product.salePrice < product.price ? product.salePrice : product.price;
  const discount   = product.salePrice ? Math.round((1 - product.salePrice / product.price) * 100) : 0;
  const wished     = Wishlist.has(product.id);

  return `
  <div class="col-6 col-md-4 col-lg-3">
    <div class="product-card" data-id="${product.id}">
      <div class="card-img-wrap">
        ${discount > 0 ? `<span class="badge-discount">-${discount}%</span>` : (product.isNew ? '<span class="badge-new">NEW</span>' : '')}
        <img src="${product.image || 'https://placehold.co/400x400/6c63ff/ffffff?text=' + encodeURIComponent(product.name)}"
             alt="${product.name}" loading="lazy">
        <div class="quick-view-overlay">
          <a href="product-detail.html?id=${product.id}" class="btn">Quick View</a>
        </div>
        <button class="wishlist-btn ${wished ? 'active' : ''}" data-pid="${product.id}" title="Add to Wishlist">
          <i class="${wished ? 'ri-heart-fill' : 'ri-heart-line'}"></i>
        </button>
      </div>
      <div class="card-body">
        <div class="product-category">${product.category}</div>
        <div class="product-name">${product.name}</div>
        <div class="mb-1">${starsHTML(product.rating || 4.2)}</div>
        <div class="price-wrap">
          <span class="price-current">${formatCurrency(finalPrice)}</span>
          ${discount > 0 ? `<span class="price-old">${formatCurrency(product.price)}</span>` : ''}
        </div>
        <button class="btn-add-cart" data-pid="${product.id}" ${product.stock === 0 ? 'disabled' : ''}>
          <i class="ri-shopping-cart-add-line me-1"></i>
          ${product.stock === 0 ? 'Out of Stock' : 'Add to Cart'}
        </button>
      </div>
    </div>
  </div>`;
}

/* ── Bind shared product card events ── */
function bindProductCardEvents(container) {
  $(container).on('click', '.btn-add-cart', function(e) {
    e.preventDefault(); e.stopPropagation();
    const pid = $(this).data('pid');
    const product = Products.byId(pid);
    if (!product) return;
    Cart.add(product);
    showToast(`<strong>${product.name}</strong> added to cart!`, 'success');
    updateCartBadge();
  });

  $(container).on('click', '.wishlist-btn', function(e) {
    e.preventDefault(); e.stopPropagation();
    const pid  = $(this).data('pid');
    const added = Wishlist.toggle(pid);
    $(this).toggleClass('active', added);
    $(this).find('i').toggleClass('ri-heart-fill', added).toggleClass('ri-heart-line', !added);
    showToast(added ? 'Added to wishlist ❤️' : 'Removed from wishlist', added ? 'success' : 'info');
    updateWishlistBadge();
  });
}

/* ── Render Navbar (injected into pages) ── */
function renderNavbar(activePage = '') {
  const user = Users.current();
  const cartCount    = Cart.count();
  const wishCount    = Wishlist.count();

  return `
  <div class="promo-banner">
    🎉 Free shipping on orders over $50! <a href="index.html">Shop Now</a>
  </div>
  <nav class="navbar navbar-main navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.html">Shop<span>Zone</span></a>
      <div class="navbar-search d-none d-md-flex me-3 ms-auto ms-lg-0">
        <form class="d-flex" id="navSearchForm" onsubmit="return doNavSearch(event)">
          <input class="form-control" type="search" id="navSearchInput" placeholder="Search products…" style="width:240px">
          <button class="btn" type="submit"><i class="ri-search-line"></i></button>
        </form>
      </div>
      <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <i class="ri-menu-line fs-4"></i>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
          <li class="nav-item"><a class="nav-link ${activePage==='home'?'active':''}" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link ${activePage==='shop'?'active':''}" href="index.html#products">Shop</a></li>
          <li class="nav-item"><a class="nav-link ${activePage==='orders'?'active':''}" href="orders.html">My Orders</a></li>
        </ul>
        <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
          <a href="index.html#wishlist" class="wishlist-icon-wrap">
            <button class="btn btn-light btn-sm rounded-circle" title="Wishlist" style="width:38px;height:38px">
              <i class="ri-heart-line"></i>
            </button>
            <span class="badge-count wishlist-count" ${wishCount===0?'style="display:none"':''}>${wishCount}</span>
          </a>
          <a href="cart.html" class="cart-badge">
            <button class="btn btn-light btn-sm rounded-circle" title="Cart" style="width:38px;height:38px">
              <i class="ri-shopping-cart-line"></i>
            </button>
            <span class="badge-count cart-count" ${cartCount===0?'style="display:none"':''}>${cartCount}</span>
          </a>
          ${user ?
            `<div class="dropdown">
               <button class="btn btn-primary-custom btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                 <i class="ri-user-line me-1"></i>${user.name.split(' ')[0]}
               </button>
               <ul class="dropdown-menu dropdown-menu-end shadow">
                 <li><a class="dropdown-item" href="orders.html"><i class="ri-file-list-line me-2"></i>My Orders</a></li>
                 <li><hr class="dropdown-divider"></li>
                 <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i class="ri-logout-box-line me-2"></i>Logout</a></li>
               </ul>
             </div>`
            :
            `<a href="login.html" class="btn btn-primary-custom btn-sm">
               <i class="ri-user-line me-1"></i>Login
             </a>`
          }
          ${isAdmin() ? `<a href="admin/dashboard.html" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="ri-shield-user-line me-1"></i>Admin</a>` : ''}
        </div>
      </div>
    </div>
  </nav>`;
}

function doNavSearch(e) {
  e.preventDefault();
  const q = $('#navSearchInput').val().trim();
  if (q) window.location.href = 'index.html?search=' + encodeURIComponent(q);
  return false;
}

function logoutUser() {
  Users.logout();
  Cart.clear();
  showToast('Logged out successfully.', 'info');
  setTimeout(() => window.location.href = 'index.html', 1000);
}

/* ── Render Footer ── */
function renderFooter() {
  return `
  <footer class="footer-main">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand mb-2">Shop<span>Zone</span></div>
          <p style="font-size:.88rem;color:rgba(255,255,255,.55);max-width:280px">Your one-stop destination for quality products at unbeatable prices.</p>
          <div class="social-links mt-3">
            <a href="#"><i class="ri-facebook-fill"></i></a>
            <a href="#"><i class="ri-twitter-fill"></i></a>
            <a href="#"><i class="ri-instagram-line"></i></a>
            <a href="#"><i class="ri-youtube-fill"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-6">
          <h6>Quick Links</h6>
          <a href="index.html">Home</a>
          <a href="index.html#products">Shop</a>
          <a href="cart.html">Cart</a>
          <a href="orders.html">My Orders</a>
        </div>
        <div class="col-lg-2 col-6">
          <h6>Categories</h6>
          <a href="index.html?cat=Electronics">Electronics</a>
          <a href="index.html?cat=Fashion">Fashion</a>
          <a href="index.html?cat=Home & Living">Home & Living</a>
          <a href="index.html?cat=Sports">Sports</a>
        </div>
        <div class="col-lg-4 col-md-6">
          <h6>Newsletter</h6>
          <p style="font-size:.82rem;color:rgba(255,255,255,.55)">Get deals & updates straight to your inbox.</p>
          <div class="input-group mt-2">
            <input type="email" class="form-control" placeholder="Your email…" style="border-radius:8px 0 0 8px!important">
            <button class="btn" style="background:var(--primary);color:#fff;border-radius:0 8px 8px 0" onclick="showToast('Subscribed! 🎉','success')">Subscribe</button>
          </div>
          <div class="mt-3 d-flex gap-2 flex-wrap">
            <img src="https://img.shields.io/badge/Secure-Checkout-green?style=flat-square" alt="secure">
            <img src="https://img.shields.io/badge/Free-Returns-blue?style=flat-square" alt="returns">
            <img src="https://img.shields.io/badge/24%2F7-Support-purple?style=flat-square" alt="support">
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© ${new Date().getFullYear()} ShopZone. All rights reserved.</span>
        <span>Made with ❤️ using Bootstrap 5</span>
      </div>
    </div>
  </footer>
  <button id="backToTop" title="Back to top"><i class="ri-arrow-up-line"></i></button>`;
}

/* ── Back to top ── */
$(window).on('scroll', function() {
  if ($(this).scrollTop() > 300) $('#backToTop').addClass('show');
  else $('#backToTop').removeClass('show');
});
$(document).on('click', '#backToTop', function() {
  $('html, body').animate({ scrollTop: 0 }, 500);
});

/* ── Cart / Wishlist realtime badge sync ── */
$(document).on('cart:updated wishlist:updated', function() {
  updateCartBadge();
  updateWishlistBadge();
});
