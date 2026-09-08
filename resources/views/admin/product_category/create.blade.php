@extends('layout.main')

@section('title', 'Create Product Category')

@section('content')

    @include('admin.product_category._form', [
        'action' => route('admin.submit_create_category'),
        'formType' => 'create',
        'is_edit_form' => false,
        'isReadOnly' => false,
        'categoryData' => null
    ])

@endsection
