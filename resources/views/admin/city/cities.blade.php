@extends('layout.main')

@section('title', 'Cities Management')

@section('content')
    <div class="admin-table-card mt-3">
        <div class="table-header">
            <h6 id="modalHeader">Cities Management</h6>
            <div class="row">
                <div class="col-sm-12">
                    {{ html()->a(route('admin.export_cities'))->class('btn-info-custom btn-sm')->id('btnExportCities')->text('Export To CSV')->style('padding:.45rem 1rem; border-radius: 8px') }}
                    {{ html()->button('Add City')->class('btn-primary-custom btn-sm')->style('padding:.45rem 1rem; border-radius: 8px')->attributes(['data-bs-toggle' => 'modal', 'data-bs-target' => '#cityModal'])->id('btnAddCity') }}
                </div>
            </div>
        </div>

        <div class="admin-table-wrap table-responsive">
            <table class="table admin-table" id="tblCities">
                <thead>
                    <tr>
                        <th>City Name</th>
                        <th>Iata Code</th>
                        <th class="text-center">Action</th>
                    </tr>
                    <tr>
                        <th>{{ html()->text('city_name')->class(TEXTBOX_CLASS)->id('txtCityName') }}</th>
                        <th>{{ html()->text('iata_code')->class(TEXTBOX_CLASS)->id('txtIataCode') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="modal fade" id="cityModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="productModalTitle">Add City</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                $(document).ready(function() {
                    const tblCities = $("#tblCities").DataTable({
                        ordering: false,
                        searching: false,
                        processing: true,
                        serverSide: true,
                        dom: '<"row"<"col-md-12 d-flex justify-content-between mb-3"<"dataTables_info"i><"dataTables_length"l>>>t<"row"<"col-md-12 mt-3"p>>',
                        searching: false,
                        ajax: {
                            url: "{{ route('admin.cities') }}",
                            type: "GET",
                            data: function(req) {
                                req.city_name = $("#txtCityName").val();
                                req.iata_code = $("#txtIataCode").val();
                            }
                        },
                        orderCellsTop: true,
                        destroy: true,
                        order: [],
                        autoWidth: false,
                        columns: [{
                                data: "city_name",
                                name: "city_name"
                            },
                            {
                                data: "iata_code",
                                name: "iata_code"
                            },
                            {
                                data: "actions",
                                name: "actions"
                            }
                        ]
                    });

                    $("label[for^='dt-length-']").addClass("mx-2");

                    $(document).on("keydown", "input", function(e) {
                        if (e.key === "Enter") {
                            e.preventDefault();
                            tblCities.ajax.reload();
                        }
                    });

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });

                    $(document).on("click", "#btnSubmit", function(e) {

                        e.preventDefault();

                        const form = document.getElementById("frmCity");
                        console.log(form)
                        const formData = new FormData(form);
                        const actionUrl = formData.get('form_action_route');

                        $.ajax({
                            url: actionUrl,
                            type: "POST",
                            contentType: false,
                            processData: false,
                            data: formData,
                            beforeSend: function() {
                                $("#btnSubmit").attr("disabled", "disabled");
                                $("#btnSubmit").text("Submitting..");
                            },
                            success: function(resp) {
                                if (resp.status == 1) {
                                    $(".modal-body").html(
                                        "<h5 class='text-center text-success'>" + resp.message +
                                        "</h5>"
                                    );

                                    setTimeout(() => {
                                        $(".btn-close").trigger("click");
                                        tblCities.ajax.reload();
                                    }, 1500);

                                } else {
                                    $("#btnSubmit").removeAttr("disabled");
                                    $("#btnSubmit").text("Submit");
                                    $(".modal-body").html(
                                        "<h3 class='text-center text-danger'>An Error Occured While Processing Your Request. Please Try Again Later</h3>"
                                    );
                                }
                            },
                            error: function(err) {
                                if (err.status === 422) {
                                    let errors = err.responseJSON.errors;
                                    let errorHtml =
                                        '<div class="p-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">';

                                    $.each(errors, function(key, value) {
                                        errorHtml +=
                                            '<div class="error-item" style="color: red;">• ' +
                                            value[0] + '</div>';
                                    });

                                    errorHtml += '</div>';

                                    $('#error-container').html(errorHtml).fadeIn();

                                    $("#btnSubmit").removeAttr("disabled");
                                    $("#btnSubmit").text("Submit");

                                    return false;
                                }
                                $(this).removeAttr("disabled");
                                $(this).text("Submit");
                                $(".modal-body").html(
                                    "<h3 class='text-center text-danger'>An Error Occured While Processing Your Request. Please Try Again Later</h3>"
                                );
                            }
                        });
                    });

                    $("#btnAddCity").on("click", function() {
                        $("#productModalTitle").text("Add City");
                        $.ajax({
                            url: "{{ route('admin.create_city') }}",
                            type: "GET",
                            beforeSend: function() {
                                $(".modal-body").html(
                                    "<h3 class='text-center'><i class='ri-loader-2-line'></i></h3>");
                            },
                            success: function(resp) {
                                $(".modal-body").delay(5000);
                                $(".modal-body").html(resp);
                            }
                        });
                    });

                    $(document).on("click", ".edit", function() {
                        let cityID = $(this).attr("id");
                        $("#productModalTitle").text("Edit Product Detail");

                        let baseRoute = "{{ route('admin.edit_city', ['id' => 'PLACEHOLDER_ID']) }}";
                        let editProductURL = baseRoute.replace('PLACEHOLDER_ID', cityID);

                        $.ajax({
                            url: editProductURL,
                            type: "GET",
                            beforeSend: function() {
                                $(".modal-body").html(
                                    "<h3 class='text-center'><i class='ri-loader-2-line'></i></h3>");
                            },
                            success: function(resp) {
                                $(".modal-body").delay(5000);
                                $(".modal-body").html(resp);
                            }
                        });
                    });
                });
            </script>
        @endpush
