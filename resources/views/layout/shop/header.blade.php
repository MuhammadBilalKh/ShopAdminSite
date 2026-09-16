<div id="navbarPlaceholder">
    <nav class="navbar navbar-main navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">{{ APPLICATION_NAME }}</span></a>
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('shopping.index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link " href="/">Products</a></li>

                    @auth('customer')
                        <li class="nav-item"><a class="nav-link " href="{{ route('shopping.customer_orders') }}">My Orders</a></li>
                    @endauth
                </ul>
                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    @auth('customer')
                        <a href="/wishlist" class="wishlist-icon-wrap">
                            <button class="btn btn-light btn-sm rounded-circle" title="Wishlist"
                                style="width:38px;height:38px">
                                <i class="ri-heart-line"></i>
                            </button>
                            <span class="badge-count wishlist-count" id="customerWishlist"></span>
                        </a>
                        <a href="{{ route('shopping.view_cart') }}" class="cart-badge">
                            <button class="btn btn-light btn-sm rounded-circle" title="Cart"
                                style="width:38px;height:38px">
                                <i class="ri-shopping-cart-line"></i>
                            </button>
                            <span class="badge-count wishlist-count" id="customerCart"></span>
                        </a>
                    @endauth

                    @if (Auth::guard('customer')->user())
                        <div class="dropdown">
                            <button class="btn btn-primary-custom btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ri-user-line me-1"></i>{{ Auth::guard('customer')->user()->full_name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="orders.html"><i class="ri-file-list-line me-2"></i>My
                                        Orders</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i
                                            class="ri-logout-box-line me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('shopping.customer_sign_in') }}" class="btn btn-primary-custom btn-sm">
                            <i class="ri-user-line me-1"></i>Login
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </nav>
</div>
