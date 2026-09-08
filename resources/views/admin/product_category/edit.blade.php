@extends('layout.main')

@section('title', 'Create Product Category')

@section('content')

    @include('admin.product_category._form', [
        'action' => route('admin.update_product_category', ['id' => Crypt::encrypt($categoryData->category_id)]),
        'formType' => 'edit',
        'is_edit_form' => true,
        'isReadOnly' => false,
        'categoryData' => $categoryData
    ])

@endsection
