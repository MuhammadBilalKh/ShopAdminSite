@extends('layout.main')

@section('title', "Product Category Management")

@section('content')

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ html()->span(session()->get('success')) }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="admin-table-card">
        <div class="table-header">
            <h6>Categories</h6>
            <div class="row">
                <div class="col-sm-12">
                    {{ html()->a(route('admin.export_product_category'))->class("btn btn-info btn-sm")->text("Export to CSV") }}
                    {{ html()->a(route('admin.create_category'))->id("btnAddCategory")->class("btn btn-primary btn-sm")->text("Add Product Category") }}
                </div>
            </div>
        </div>

        <div class="admin-table text-sm">
            <table class="table admin-table" id="tblProductCategories">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>No. Of Product(s)</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->category_name }}</td>
                            <td>{{ html()->span($value->products_count)->class("badge text-lg bg-info") }}</td>
                            <td>
                                @if($value->status == STATUS_ACTIVE)
                                    {{ html()->span("Active")->class("badge bg-success") }}
                                @else
                                    {{ html()->span("In-Active")->class("badge bg-danger") }}
                                @endif
                            </td>
                            <td>{{ $value->getCreatedBy->name }} ({{ strtoupper($value->getCreatedBy->user_role) }})</td>
                            <td>
                                @if($value->getUpdatedBy)
                                    {{ $value?->getUpdatedBy?->name }} ({{ strtoupper($value?->getUpdatedBy?->user_role) }})
                                @endif
                            </td>
                            <td>
                                {!!  html()->a(route('admin.edit_product_category', ['id' => Crypt::encrypt($value->category_id)]))->class("btn-action edit edit-btn")->html("<i class='ri-pencil-line'></i>") !!}
                                {!!  html()->a(route('admin.delete_product_category', ['id' => Crypt::encrypt($value->category_id)]))->class("btn-action delete delete-btn")->html("<i class='ri-delete-bin-line'></i>") !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5" class="text-center">No Records Found</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $categories->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(".alert").delay(2500).fadeOut();

            $(document).on("click", "#btnSubmit", function(e){
                e.preventDefault();
                alert("Form Submitted")
            });
        });
    </script>
@endpush