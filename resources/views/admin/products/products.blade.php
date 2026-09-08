@extends('layout.main')

@section('title', 'Products Management')

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="ri-store-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="totalProductsStat">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="ri-checkbox-circle-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="inStockStat">{{ $in_stock_products }}</div>
                    <div class="stat-label">In Stock</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="ri-error-warning-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="lowStockStat">{{ $out_stock_products }}</div>
                    <div class="stat-label">Low Stock</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon pink"><i class="ri-close-circle-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value" id="outStockStat">{{ $zero_stock_products }}</div>
                    <div class="stat-label">Out of Stock</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('additional_section')
    <div class="admin-table-card mt-3">
        <div class="table-header">
            <h6>All Products</h6>
            <div class="row">
                <div class="col-sm-12">
                    {{ html()->a('')->class('btn-info-custom btn-sm')->id('btnExportProducts')->text('Export To CSV') }}
                    {{ html()->button('Add Product')->class('btn-primary-custom btn-sm')->id('btnAddProduct')->style('padding:.45rem 1rem; border-radius: 8px')->attributes(['data-bs-toggle' => 'modal', 'data-bs-target' => '#productModal'])->id('btnAddProduct') }}
                </div>
            </div>
        </div>

        <div class="admin-table-wrap table-responsive">
            <table class="table admin-table" id="tblProducts">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Quantity</th>
                        <th>Stock Status</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th>
                            {{ html()->text('product_name')->class(TEXTBOX_CLASS)->id('txtProductName') }}
                        </th>
                        <th>{{ html()->select('category_name', $categories)->class(TEXTBOX_CLASS)->id('slctCategory')->placeholder('Select') }}
                        </th>
                        <th>{{ html()->number('price')->class(TEXTBOX_CLASS)->id('txtProductPrice') }}</th>
                        <th>{{ html()->number('quantity')->class(TEXTBOX_CLASS)->id('txtQuantity') }}</th>
                        <th>{{ html()->select('stock_status', [
                                '' => 'All',
                                'in-stock' => 'In Stock',
                                'low-stock' => 'Low Stock',
                                'out-of-stock' => 'Out of Stock',
                            ])->id('slctStockStatus')->class(TEXTBOX_CLASS) }}
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="productModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const tblProducts = $("#tblProducts").DataTable({
                ordering: false,
                searching: false,
                processing: true,
                serverSide: true,
                dom: '<"row"<"col-md-12 d-flex justify-content-between mb-3"<"dataTables_info"i><"dataTables_length"l>>>t<"row"<"col-md-12 mt-3"p>>',
                searching: false,
                ajax: {
                    url: "{{ route('admin.show_product_lists') }}",
                    type: "GET",
                    data: function(req) {
                        req.product_name = $("#txtProductName").val();
                        req.category = $("#slctCategory").val();
                        req.price = $("#txtPrice").val();
                        req.quantity = $("#txtQuantity").val();
                        req.stock_status = $("#slctStockStatus").val();
                    }
                },
                orderCellsTop: true,
                destroy: true,
                order: [],
                autoWidth: false,
                columns: [{
                        data: "product_name",
                        name: "product_name"
                    },
                    {
                        data: "category",
                        name: "category"
                    },
                    {
                        data: "regular_price",
                        name: "regular_price",
                        width: 120
                    },
                    {
                        data: "quantity",
                        name: "quantity",
                        width: 150
                    },
                    {
                        data: "stock_status",
                        name: "stock_status",
                        width: 150
                    },
                    {
                        data: "actions",
                        name: "actions"
                    }
                ]
            });

            $("label[for^='dt-length-']").addClass("mx-2");

            $(document).on("click", "#btnSubmit", function(e) {

                $.ajax({
                    url: "{{ route('admin.submit_create_product') }}",
                    type: "POST",
                    data: $("#frmProduct").serialize(),
                    beforeSend: function() {
                        $(this).attr("disabled", "disabled");
                        $(this).text("Submitting..");
                    },
                    success: function(resp) {
                        if (resp.status == 1) {
                            $(".modal-body").html(
                                "<h3 class='text-center text-success'>Product Have Been Created Successfully</h3>"
                                );
                        } else {
                            $(this).removeAttr("disabled");
                            $(this).text("Submit");
                            $(".modal-body").html(
                                "<h3 class='text-center text-danger'>An Error Occured While Processing Your Request. Please Try Again Later</h3>"
                                );
                        }
                    },
                    error: function(err, ar) {
                        $(this).removeAttr("disabled");
                        $(this).text("Submit");
                        $(".modal-body").html(
                            "<h3 class='text-center text-danger'>An Error Occured While Processing Your Request. Please Try Again Later</h3>"
                            );
                    }
                });
            });

            $("#btnAddProduct").on("click", function() {
                $.ajax({
                    url: "{{ route('admin.create_product') }}",
                    type: "GET",
                    beforeSend: function() {
                        $(".modal-body").html(
                            "<h3 class='text-center'><i class='ri-loader-2-line'></i></h3>");
                    },
                    success: function(resp) {
                        $(".modal-body").delay(5000)
                        $(".modal-body").html(resp);
                    }
                })
            });
        });
    </script>
@endpush
