@include('admin.city._form', [
    'action' => route('admin.update_city', Crypt::encrypt($cityData->city_id)),
    'cityData' => $cityData,
    'formType' => 'edit',
])
