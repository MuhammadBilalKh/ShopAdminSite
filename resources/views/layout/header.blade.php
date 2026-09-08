<div class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="ri-menu-line"></i></button>
        <span class="topbar-title">Dashboard</span>
    </div>
    <div class="topbar-actions">
        Current Time: {{ html()->span(now()->toDateTimeString())->class("text-primary small") }}
        <span class="text-muted small d-none d-md-block" id="topbarDate"></span>
        <div class="dropdown">
            <button class="btn btn-light btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=6c63ff&color=fff&size=32" class="topbar-avatar" alt="Admin">
                <span class="d-none d-md-inline fw-700 small" id="adminName">{{ Auth::user()?->name  }}</span>
                <i class="ri-arrow-down-s-line"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="../index.html" target="_blank"><i class="ri-store-line me-2"></i>View Store</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}" id="logoutLink2"><i class="ri-logout-box-line me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>