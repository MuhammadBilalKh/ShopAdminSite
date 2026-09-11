@extends('layout.main')

@section('title', 'Major Area(s)')

@section('content')
    <div class="admin-table-card mt-3">
        <div class="table-header">
            <h6 id="modalHeader">Major Areas Management</h6>
            <div class="row">
                <div class="col-sm-12">
                    {{ html()->a(route('admin.export_minor_areas'))->class('btn-info-custom btn-sm')->id('btnExportMinorAreas')->text('Export To CSV')->style('padding:.45rem 1rem; border-radius: 8px') }}
                    {{ html()->button('Create Minor Area')->class('btn-primary-custom btn-sm')->style('padding:.45rem 1rem; border-radius: 8px')->attributes(['data-bs-toggle' => 'modal', 'data-bs-target' => '#minorAreaModal'])->id('btnAddMinorArea') }}
                </div>
            </div>
        </div>

        <div class="admin-table-wrap table-responsive">
            <table class="table admin-table" id="tblMinorAreas">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>Minor Area</th>
                        <th>Major Area</th>
                        <th>Created By</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                    <tr>
                        <th>{{ html()->select('city_name', $cities)->class(TEXTBOX_CLASS)->id('cityDrp')->placeholder('Select') }}
                        <th>{{ html()->text('major_area_name')->class(TEXTBOX_CLASS)->id('txtMajorAreaName') }}</th>
                        <th>{{ html()->text('minor_area_name')->class(TEXTBOX_CLASS)->id('txtMinorAreaName') }}</th>
                        <th></th>
                        {{-- <th></th> --}}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="minorAreaModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const tblMinorAreas = $("#tblMinorAreas").DataTable({
                ordering: false,
                searching: false,
                processing: true,
                serverSide: true,
                dom: '<"row"<"col-md-12 d-flex justify-content-between mb-3"<"dataTables_info"i><"dataTables_length"l>>>t<"row"<"col-md-12 mt-3"p>>',
                searching: false,
                ajax: {
                    url: "{{ route('admin.manage_minor_areas') }}",
                    type: "GET",
                    data: function(req) {
                        req.major_area_name = $("#txtMajorAreaName").val();
                        req.city_name = $("#cityDrp").val();
                        req.minor_area_name = $("#txtMinorAreaName").val();
                    }
                },
                orderCellsTop: true,
                destroy: true,
                order: [],
                autoWidth: false,
                columns: [{
                        data: null,
                        name: null,
                        render: function(val) {
                            return val.get_major_area.get_major_area_city.city_name
                        }
                    },
                    {
                        data: null,
                        name: null,
                        render: function(val) {
                            return val.get_major_area.major_area_name
                        }
                    },
                    {
                        data: "minor_area_name",
                        name: "minor_area_name"
                    },
                    {
                        data: null,
                        name: null,
                        width: 300,
                        render: function(val) {
                            return val.get_created_by.name + " - " + " (" + val.get_created_by
                                .login_id + ")"
                        }
                    },
                    // {
                    //     data: "actions",
                    //     name: "actions"
                    // }
                ]
            });

            $("label[for^='dt-length-']").addClass("mx-2");

            $(document).on("change", "#cityDrp", function() {
                tblMinorAreas.ajax.reload();
            });

            $(document).on("keydown", "input", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    tblMinorAreas.ajax.reload();
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $("#btnAddMinorArea").on("click", function() {
                $("#productModalTitle").text("Add New Minor Area");
                $.ajax({
                    url: "{{ route('admin.create_minor_area') }}",
                    type: "GET",
                    data: {
                        view_form: 1
                    },
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
                let mnID = $(this).attr("id");
                $("#productModalTitle").text("Edit Minor Area Detail");

                let baseRoute = "{{ route('admin.edit_minor_area', ['id' => 'PLACEHOLDER_ID']) }}";
                let editProductURL = baseRoute.replace('PLACEHOLDER_ID', mnID);

                $.ajax({
                    url: editProductURL,
                    type: "GET",
                    data: {
                        view_form: 1
                    },
                    beforeSend: function() {
                        $(".modal-body").html(
                            "<h3 class='text-center'><i class='ri-loader-2-line'></i></h3>");
                    },
                    success: function(resp) {
                        $(".modal-body").delay(5000);
                        $(".modal-body").html(resp);
                        $("#slctCity").trigger("change");
                    }
                });
            });

            $(document).on("click", "#btnSubmit", function(e) {

                e.preventDefault();

                const form = document.getElementById("frmMinorArea");
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
                                tblMinorAreas.ajax.reload();
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
                            $(this).removeAttr("disabled");
                            $(this).text("Submit");
                            return false;
                        }
                        $(".modal-body").html(
                            "<h3 class='text-center text-danger'>An Error Occured While Processing Your Request. Please Try Again Later</h3>"
                        );
                    }
                });
            });

            $(document).on("change", "#slctCity", function() {
                $.ajax({
                    url: "{{ route('admin.get_city_major_area') }}",
                    type: "POST",
                    data: {
                        cityID: $("#slctCity").val()
                    },
                    beforeSend: function() {
                        $("#slctMajorArea").html('');
                    },
                    success: function(resp) {

                        let data = resp.data;

                        if (resp.status == 1) {

                            $("#slctMajorArea").html(
                                "<option value=''>Select</option>"
                            );

                            if (!Array.isArray(data)) {
                                data = Object.values(data);
                            }

                            console.clear();
                            console.dir(resp.data);

                            resp.data.forEach(function(mjArr) {

                                $("#slctMajorArea").append(
                                    "<option value='" + mjArr.major_area_id + "'>" +
                                    mjArr.major_area_name +
                                    "</option>"
                                );

                            });

                        } else {
                            $("#slctMajorArea").html(
                                "<option value=''>No Major Area Found</option>"
                            );
                        }
                    },
                });
            });

        });
    </script>
@endpush
