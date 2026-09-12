<div id="navbarPlaceholder">
    <nav class="navbar navbar-main navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">Shop<span>Zone</span></a>
            <div class="navbar-search d-none d-md-flex me-3 ms-auto ms-lg-0">
                <form class="d-flex" id="navSearchForm" onsubmit="return doNavSearch(event)">
                    <input class="form-control" type="search" id="navSearchInput" placeholder="Search products…"
                        style="width:240px">
                    <button class="btn" type="submit"><i class="ri-search-line"></i></button>
                </form>
            </div>
            <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav">
                <i class="ri-menu-line fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link " href="products.html">Products</a></li>
                    <li class="nav-item"><a class="nav-link " href="index.html#products">Shop</a></li>
                    <li class="nav-item"><a class="nav-link " href="orders.html">My Orders</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <a href="index.html#wishlist" class="wishlist-icon-wrap">
                        <button class="btn btn-light btn-sm rounded-circle" title="Wishlist"
                            style="width:38px;height:38px">
                            <i class="ri-heart-line"></i>
                        </button>
                        <span class="badge-count wishlist-count" style="display:none">0</span>
                    </a>
                    <a href="cart.html" class="cart-badge">
                        <button class="btn btn-light btn-sm rounded-circle" title="Cart"
                            style="width:38px;height:38px">
                            <i class="ri-shopping-cart-line"></i>
                        </button>
                        <span class="badge-count cart-count" style="display:none">0</span>
                    </a>
                    <a href="login.html" class="btn btn-primary-custom btn-sm">
                        <i class="ri-user-line me-1"></i>Login
                    </a>

                </div>
            </div>
        </div>
    </nav>
</div>
