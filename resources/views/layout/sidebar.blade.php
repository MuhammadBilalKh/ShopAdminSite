<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">{{ APPLICATION_NAME }} <small
            style="font-size:.6rem;opacity:.5;display:block;margin-top:-4px">Admin Panel</small></div>
    <nav class="sidebar-nav">
        <div class="sidebar-section-title">Main</div>
        <a class="sidebar-link @if (Route::currentRouteName() == 'admin.show_admin_dashboard') active @endif" href="{{ route('admin.show_admin_dashboard') }}"><i
                class="ri-dashboard-line"></i> Dashboard</a>
        <a class="sidebar-link @if (Route::currentRouteName() == 'admin.product_category_lists') active @endif"
            href="{{ route('admin.product_category_lists') }}"><i class="ri-grid-line"></i> Categories</a>
        <a class="sidebar-link @if (Route::currentRouteName() == 'admin.show_product_lists') active @endif"
            href="{{ route('admin.show_product_lists') }}"><i class="ri-store-line"></i> Products</a>
        <a class="sidebar-link @if (Route::currentRouteName() == '') active @endif" href="customers.html"><i
                class="ri-group-line"></i> Customers</a>
        <a class="sidebar-link @if (Route::currentRouteName() == '') active @endif" href="orders.html"><i
                class="ri-file-list-3-line"></i> Orders <span class="sidebar-badge" id="pendingBadge">
                @if (isset($pending_orders))
                    {{ $pending_orders }}
                @else
                    {{ '0' }}
                @endif
            </span></a>
        <div class="sidebar-section-title mt-2">Store</div>
        <a class="sidebar-link" href="../index.html" target="_blank"><i class="ri-external-link-line"></i> View
            Store</a>
        <a class="sidebar-link" href="../index.html" target="_blank"><i class="ri-coupon-line"></i> Coupons</a>
        <div class="sidebar-section-title mt-2">Account</div>
        <a class="sidebar-link" href="#" id="logoutLink"><i class="ri-logout-box-line"></i> Logout</a>
    </nav>
</aside>
