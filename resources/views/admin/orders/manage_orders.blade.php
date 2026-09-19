@extends('layout.main')

@section('title', 'Orders Management')

@section('content')

    @include('layout.shop.breadcrumbs', [
        'moduleName' => 'Orders',
        'route' => route('admin.manage_orders'),
        'subModuleName' => 'Orders Management',
    ])

    <div class="admin-content">
        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-2-4" style="flex:0 0 20%;max-width:20%">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="ri-file-list-line"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="sTotal">5</div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2-4" style="flex:0 0 20%;max-width:20%">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="ri-time-line"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="sPending">2</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2-4" style="flex:0 0 20%;max-width:20%">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="ri-settings-3-line"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="sProcessing">1</div>
                        <div class="stat-label">Processing</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2-4" style="flex:0 0 20%;max-width:20%">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="ri-truck-line"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="sShipped">1</div>
                        <div class="stat-label">Shipped</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2-4" style="flex:0 0 20%;max-width:20%">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="ri-checkbox-circle-line"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="sDelivered">1</div>
                        <div class="stat-label">Delivered</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="table-header">
                <h6>All Orders</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" name="search_string" class="form-control form-control-sm" id="orderSearch"
                        placeholder="Search by ID, name…" style="width:200px" />
                    <select class="form-select form-select-sm" id="statusFilter" style="width:auto">
                        <option value="">All Status</option>
                        <option value="{{ ORDER_STATUS_PENDING }}">Pending</option>
                        <option value="{{ ORDER_STATUS_PROCESSING }}">Processing</option>
                        <option value="{{ ORDER_STATUS_SHIPPER }}">Shipped</option>
                        <option value="{{ ORDER_STATUS_CANCELLED }}">Cancelled</option>
                    </select>
                    <input type="date" class="form-control form-control-sm" id="dateFilter" style="width:auto" />
                    <button type="button" class="btn btn-sm btn-primary btnSearch" onclick="ApplySearch()">Search</button>
                    <a href="{{ route('admin.manage_orders') }}" class="btn bt-sm btn-danger">Clear</a>
                </div>
            </div>
            <div class="admin-table-wrap">
                <table class="table admin-table" id="tblOrders">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @forelse ($orders as $key => $value)
                            <tr></tr>
                            <td class="fw-700 text-primary">{{ $value->customer_order_id }}</td>
                            <td>
                                <div class="fw-700 small">{{ $value->getOrderBy->full_name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $value->getOrderBy->email_address }}
                                </div>
                            </td>
                            <td width="100"><span class="tag-chip">{{ count($value->getOrderLineItems) }} items</span>
                            </td>
                            <td class="fw-700">Rs. {{ number_format($value->total_amount) }}</td>
                            <td class="small text-muted">Cash on Delivery</td>
                            <td>
                                @if ($value->order_status == ORDER_STATUS_PENDING)
                                    <span class="badge-status pending">Pending</span>
                                @elseif($value->order_status == ORDER_STATUS_PROCESSING)
                                    <span class="badge-status processing">Processing</span>
                                @elseif($value->order_status == ORDER_STATUS_SHIPPER)
                                    <span class="badge-status delivered">Delivered</span>
                                @elseif($value->order_status == ORDER_STATUS_PROCESSED)
                                    <span class="badge-status processed">Processed</span>
                                @elseif($value->order_status == ORDER_STATUS_CANCELLED)
                                    <span class="badge-status cancell">Cancelled</span>
                                @endif
                            </td>
                            <td width="200" class="small text-muted">{{ $value->created_at->toDateTimeString() }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button data-bs-toggle="modal" data-bs-target="#orderModal"
                                        class="btn-action view view-order-btn" data-id="{{ $value->customer_order_id }}"
                                        title="View"><i class="ri-eye-line"></i></button>
                                    <button data-bs-toggle="modal" data-bs-target="#orderModal"
                                        class="btn-action delete delete-order-btn"
                                        data-id="{{ $value->customer_order_id }}" title="Delete"><i
                                            class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Pending Orders Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-2 border-top">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-file-list-3-line me-2"></i>Order <span
                            id="modalOrderId">ORD-1789493808219</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="orderModalBody">

                </div>
                <div class="modal-footer">
                    <div class="d-flex align-items-center gap-2 flex-wrap w-100">
                        <label class="fw-700 small me-1">Update Status:</label>
                        <select class="form-select ordStatus form-select-sm" id="statusUpdateSelect" style="width:auto">
                            <option value="{{ ORDER_STATUS_PENDING }}">Pending</option>
                            <option value="{{ ORDER_STATUS_PROCESSING }}">Processing</option>
                            <option value="{{ ORDER_STATUS_PROCESSED }}">Delivered</option>
                            <option value="{{ ORDER_STATUS_CANCELLED }}">Cancelled</option>
                            <option value="{{ ORDER_STATUS_SHIPPER }}">Shipped</option>
                        </select>
                        <button class="btn-primary-custom ms-auto" id="updateStatusBtn" style="padding:.45rem 1.1rem">
                            <i class="ri-save-line me-1"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $(".btnSearch").on("click", function() {
                ApplySearch();
            });

            $("#updateStatusBtn").click(function(){
                $.ajax({
                    url:"{{ route('admin.update_order_status') }}",
                    type:"POST",
                    data:{
                        order_id: $(this).data("product-id"),
                        order_status: $(".ordStatus").val()
                    },
                    success:function(resp){
                        if(resp.status == 1){
                            alert(resp.message);
                        }
                    },
                    error: function(){
                        alert("An Internal Error Occured While Processing The Request. Please Try Again Later.")
                    }
                });
            });

            $(".view-order-btn").click(function(e) {
                let baseRoute = "{{ route('admin.order_detail', ['orderID' => 'PLACEHOLDER_ID']) }}";
                let viewOrderRoute = baseRoute.replace("PLACEHOLDER_ID", $(this).data("id"));

                $.ajax({
                    url: viewOrderRoute,
                    type: "GET",
                    beforeSend: function(resp) {
                        $("#modalOrderId").html("");
                        $(".modal-body").html("Fetching Details.. Please Wait.")
                    },
                    success: function(resp) {
                        $("#modalOrderId").html(resp.orderID);
                        $(".modal-body").html(resp.view);
                        $("#statusUpdateSelect").val(String(resp.orderStatus));
                        $("#updateStatusBtn").attr("data-product-id", resp.orderID);
                    },
                    error: function() {
                        $(".modal-body").html(
                            "An Error Occured While Processing The Request. Please Try Again Later."
                        );
                    }
                });
            });

            function ApplySearch() {
                let baseRoute = "{{ route('admin.manage_orders') }}";

                let url = new URL(baseRoute, window.location.origin);

                url.searchParams.set("search", $("#orderSearch").val() || "");
                url.searchParams.set("status", $("#statusFilter").val() || "");
                url.searchParams.set("date", $("#dateFilter").val() || "");

                window.location.href = url.toString();
            }
        });
    </script>
@endpush
