@include('admin.products._form', [
    'action' => route('admin.update_product_detail', ['id' => Crypt::encrypt($productData->product_id)]),
    'productData' => $productData,
    'formType' => "edit"
])