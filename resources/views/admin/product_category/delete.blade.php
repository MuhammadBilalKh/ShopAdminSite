@extends('layout.main')

@section('title', 'Delete Product Category')

@section('content')

    {{ html()->form('POST')->action(route('admin.remove_product_category', ['id' => $decID]))->open() }}

    @if ($product_count == 0)
        <span class="text-success">Are Your Sure You Want To Delete This Product Category</span>
        <br />
        <br />
        <input type="submit" value="Delete Product Category" class="btn btn-danger btn-sm" />
        <a href="{{ route('admin.product_category_lists') }}" class="btn btn-secondary btn-sm">Cancel</a>
    @else
        <span class="text-danger">This Product Category Can Not Be Deleted Becaue Product Exists In This Category</span>
    @endif

    {{ html()->form()->close() }}

@endsection
