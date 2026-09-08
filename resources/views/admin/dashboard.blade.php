@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4 p-3">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="ri-store-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="statsCategories">{{ ($totalProducts )}}</div>
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
                    <div class="stat-value" id="statsOrders">{{ ($totalOrders) }}</div>
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
                        <div class="admin-table-wrap">
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
                                            <td>{{ $value?->getOrderBy?->customer_name }}</td>
                                            <td>{{ $value->total_amount }}</td>
                                            <td>
                                                @if($value->status == ORDER_STATUS_PENDING)
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
            </div>
        </div>
    </div>
@endpush
