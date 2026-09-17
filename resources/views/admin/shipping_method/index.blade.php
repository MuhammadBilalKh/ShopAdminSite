@extends('layout.main')

@section('title', 'Shipping Methods')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        <span>{{ session()->get('success') }}</span>
    </div>
@endif

<div class="admin-table-card mt-3">
        <div class="table-header">
            <h6 id="modalHeader">All Shipping Methods</h6>
            <div class="row">
                <div class="col-sm-12">
                    {{ html()->a(route('admin.create_shipping_method'))->text('Add New Shipping Method')->class('btn-primary-custom btn-sm')->id('btnAddProduct')->style('padding:.45rem 1rem; border-radius: 8px')->id('btnAddShippingMethod') }}
                </div>
            </div>
        </div>

        <div class="admin-table-wrap table-responsive">
            <table class="table admin-table" id="tblShippingMethods">
                <thead>
                    <tr>
                        <th>Shipping Method</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th>
                            {{ html()->text('shipping_method_name')->class(TEXTBOX_CLASS)->id('txtShippingMethodName') }}
                        </th>
                        <th>{{ html()->number('cost')->class(TEXTBOX_CLASS)->id('txtCost') }}</th>
                        <th>{{ html()->select('shipping_method_status', [
                                '' => 'All',
                                STATUS_ACTIVE => 'Active',
                                STATUS_INACTIVE => 'In-Active',
                            ])->id('slctShippingMethodStatus')->class(TEXTBOX_CLASS) }}
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $(".alert").delay(2500).fadeOut();

            const tblShippingMethods = $("#tblShippingMethods").DataTable({
                ordering: false,
                searching: false,
                processing: true,
                serverSide: true,
                dom: '<"row"<"col-md-12 d-flex justify-content-between mb-3"<"dataTables_info"i><"dataTables_length"l>>>t<"row"<"col-md-12 mt-3"p>>',
                searching: false,
                ajax: {
                    url: "{{ route('admin.shipping_method') }}",
                    type: "GET",
                    data: function(req) {
                        req.shipping_method_name = $("#txtShippingMethodName").val();
                        req.cost = $("#txtCost").val();
                        req.shipping_method_status = $("#slctShippingMethodStatus").val();
                    }
                },
                orderCellsTop: true,
                destroy: true,
                order: [],
                autoWidth: false,
                columns: [{
                        data: "shipping_method_name",
                        name: "shipping_method_name"
                    },
                    {
                        data: "cost",
                        name: "cost",
                        width: 150
                    },
                    {
                        data: "status",
                        name: "status",
                        width: 150,
                        render: function(val){
                            if(val == 1){
                                return "<span class='badge bg-success'>Active</span>";
                            } else if(val == 0){
                                return "<span class='badge bg-danger'>In-Active</span>";
                            }
                        }
                    },
                    {
                        data: "actions",
                        name: "actions"
                    }
                ]
            });

            $("label[for^='dt-length-']").addClass("mx-2");

            $(document).on("change", "select", function() {
                tblShippingMethods.ajax.reload();
            });

            $(document).on("keydown", "input", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    tblShippingMethods.ajax.reload();
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
        });
    </script>
@endpush
