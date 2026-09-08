@include('admin.products._form', [
    'action' => route('admin.submit_create_product'),
    'productData' => null,
    'formType' => "create"
])