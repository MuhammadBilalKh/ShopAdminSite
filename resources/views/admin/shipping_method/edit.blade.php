@extends("layout.main")

@section('title', "Update Shipping Method Detail")

@section('content')

@include("layout.shop.breadcrumbs", [
    'moduleName' => "Shipping Method(s)",
    'route' => route('admin.shipping_method'),
    'subModuleName' => "Update Shipping Method Detail"
])

@include('admin.shipping_method._form', [
    'action' => route('admin.update_shipping_method', ['id' => $shippingMethodData->shipping_method_id]),
    'formType' => "edit",
    'shippingMethodData' => $shippingMethodData
])

@endsection