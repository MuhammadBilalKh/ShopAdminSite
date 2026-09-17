@extends("layout.main")

@section('title', "Create New Shipping Method")

@section('content')

@include("layout.shop.breadcrumbs", [
    'moduleName' => "Shipping Method(s)",
    'route' => route('admin.shipping_method'),
    'subModuleName' => "Create New Shipping Method"
])

@include('admin.shipping_method._form', [
    'action' => route('admin.submit_shipping_method'),
    'formType' => "edit",
    'shippingMethodData' => null
])


@endsection