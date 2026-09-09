@include('admin.city._form', [
    'action' => route('admin.store_city'),
    'cityData' => null,
    'formType' => 'create',
])
