@extends('layout.shop.main')

@section('title', 'My Orders')

@section('content')
    @include('layout.shop.breadcrumbs', [
        'moduleName' => 'Orders',
        'route' => route('shopping.customer_orders'),
        'subModuleName' => 'Order History',
    ])

    <section class="py-5">
        <div class="container">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <h2 class="section-title mb-0">My Orders</h2>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="text" class="form-control form-control-sm" id="orderSearch" placeholder="Search order ID…"
                        style="width:200px">
                    <select class="form-select form-select-sm" id="statusFilter" style="width:auto">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div id="guestSearchPanel" class="d-none mb-4">
                <div class="bg-white rounded-3 shadow-sm p-4">
                    <h5 class="fw-700 mb-2"><i class="ri-search-eye-line me-2 text-primary"></i>Track Your Order</h5>
                    <p class="text-muted small mb-3">Enter your order ID or email address to find your orders.</p>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="guestOrderId"
                                placeholder="Order ID (e.g. ORD-001)">
                        </div>
                        <div class="col-md-4">
                            <input type="email" class="form-control" id="guestEmail" placeholder="Email address">
                        </div>
                        <div class="col-md-2">
                            <button class="btn-primary-custom w-100 justify-content-center" id="guestSearchBtn"
                                style="height:44px">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ordersList">
                @forelse ($orders as $key => $value)
                    <div class="bg-white rounded-3 shadow-sm mb-3 overflow-hidden">
                        <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-2"
                            style="background:#f8f9fc;border-bottom:1px solid var(--border)">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div>
                                    <div class="fw-700">{{ $value->customer_order_id }}</div>
                                    <div class="text-muted small">{{ $value->created_at->format('M j, Y, h:i A') }}</div>
                                </div>
                                <span class="badge-status delivered">Delivered</span>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="fw-700 fs-5 text-primary">$399.58</div>
                                <button class="btn-action view view-order-btn" data-id="ORD-001" title="View Details">
                                    <i class="ri-eye-line"></i>
                                </button>

                            </div>
                        </div>
                        <div class="p-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <div class="d-flex gap-1">
                                        @foreach ($value as $key => $orderLineItem)
                                            <img src="{{ asset('product_profile_image/' . $orderLineItem->getLineItemProduct->product_profile_image) }}"
                                                style="width:50px;height:50px;object-fit:cover;border-radius:8px"
                                                alt="{{ $orderLineItem->getLineItemProduct->product_name }}" />
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-600 small">{{ count($value) }}</div>
                                    <div class="text-muted" style="font-size:.8rem">
                                        Wireless Noise-Cancelling Headphones,
                                        Smart Watch Series X</div>
                                </div>
                                {{-- <div class="col-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill reorder-btn"
                                        data-id="ORD-001">
                                        <i class="ri-refresh-line me-1"></i>Reorder
                                    </button>
                                </div> --}}
                            </div>
                            <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-checkbox-circle-fill" style="color:var(--accent);font-size:1rem"></i>
                                    <span style="font-size:.72rem;font-weight:700;color:var(--accent)">Order Placed</span>
                                </div>
                                <div style="height:2px;width:24px;background:var(--accent);margin-top:0"></div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-checkbox-circle-fill" style="color:var(--accent);font-size:1rem"></i>
                                    <span style="font-size:.72rem;font-weight:700;color:var(--accent)">Processing</span>
                                </div>
                                <div style="height:2px;width:24px;background:var(--accent);margin-top:0"></div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-checkbox-circle-fill" style="color:var(--accent);font-size:1rem"></i>
                                    <span style="font-size:.72rem;font-weight:700;color:var(--accent)">Shipped</span>
                                </div>
                                <div style="height:2px;width:24px;background:var(--accent);margin-top:0"></div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-checkbox-circle-fill" style="color:var(--accent);font-size:1rem"></i>
                                    <span style="font-size:.72rem;font-weight:700;color:var(--accent)">Delivered</span>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>

        </div>
    </section>
@endsection
