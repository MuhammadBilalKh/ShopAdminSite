@extends('layout.main')

@section('title', 'Edit Minor Area Details')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $key => $value)
                    <li>{{ ucwords($value) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.city.minor_area._form', [
        'minor_area_data' => $minor_area_data,
        'cities' => $cities,
        'formType' => 'edit',
        'route' => route('admin.update_minor_area', ['id' => Crypt::encrypt($minor_area_data->minor_area_id)]),
    ])
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $("#slctCity").trigger("change");
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
    </script>
@endpush
