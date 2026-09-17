@extends('layout.main')

@section('title', 'Dashboard')

@section('content')

    <div class="p-4 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
        style="background:linear-gradient(135deg,var(--dark),var(--primary));color:#fff">
        <div>
            <h5 class="fw-800 mb-1">Welcome , <span id="welcomeName">{{ Auth::user()->name }}</span>! 👋</h5>
            <p class="mb-0 small" style="opacity:.75">Here's what's happening in your store today.</p>
        </div>
        <a href="{{ route('admin.show_product_lists') }}" class="btn btn-light btn-sm fw-700 rounded-pill">
            <i class="ri-add-line me-1"></i>Manage Products
        </a>
    </div>

    <div class="row g-3 mb-4 p-3">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="ri-store-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="statsCategories">{{ $totalProducts }}</div>
                    <div class="stat-label">Products</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="ri-grid-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="statsCategories">{{ $totalCategories }}</div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="ri-file-list-3-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="statsOrders">{{ $totalOrders }}</div>
                    <div class="stat-label">Orders</div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('additional_section')
    <div class="card mt-3">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-lg-7">
                    <div class="admin-table-card">
                        <div class="table-header">
                            <h6>Recent Orders</h6>
                        </div>
                        <div class="admin-table-wrap table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="recentOrdersBody">
                                    @forelse($recentOrders as $key => $value)
                                        <tr>
                                            <td>{{ $value->customer_order_id }}</td>
                                            <td>{{ $value->getOrderBy->full_name }}</td>
                                            <td width="150">Rs. {{ number_format($value->total_amount )}}</td>
                                            <td>
                                                @if ($value->status == ORDER_STATUS_PENDING)
                                                    <span class="badge-status pending">Pending</span>
                                                @elseif($value->status == ORDER_STATUS_PROCESSING)
                                                    <span class="badge-status processing">Processing</span>
                                                @elseif($value->status == ORDER_STATUS_SHIPPER)
                                                    <span class="badge-status text-success">Shipped</span>
                                                @elseif($value->status == ORDER_STATUS_PROCESSED)
                                                    <span class="badge-status text-info">Processed</span>
                                                @endif
                                            </td>
                                            <td>{{ $value->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Recent Orders Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="chart-card">
                        <div class="chart-title">
                            <h6 class="d-inline">Recent Activities</h6>
                            {{ html()->a(route('admin.view_recent_activities'))->class('float-end text-white btn btn-sm btn-info')->text('View All Activities')->target('_blank') }}
                        </div>

                        <div id="activityFeed">
                            @forelse ($recentActivties as $key => $value)
                                <div class="activity-item">

                                    <div class="activity-icon" style="background:#ede9ff;">
                                        <i class="ri-window-2-fill" style="color:var(--primary);"></i>
                                    </div>

                                    <div class="activity-content">
                                        <div class="activity-description" style="font-size: 15px;">
                                            {{ $value->activity_description }}
                                        </div>

                                        <div class="text-muted activity-date" style="font-size: 13px;">
                                            {{ $value->created_at->format('M j, Y, h:i A') }}
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="text-muted">
                                    No Recent Activities Found
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endpush
