{{ html()->form('POST')->action($action)->open() }}

@if ($errors->any())
    {!! html()->div()->class('alert alert-danger')->addChildren(
            collect($errors->all())->map(function ($error) {
                    return html()->div('->' . $error);
                })->toArray(),
        ) !!}
@endif

{{ html()->div()->class('row')->addChildren([
        html()->div()->class('col-sm-6')->addChildren([
                html()->div()->class('form-group')->addChildren([
                        html()->label('Shipping Method Name: '),
                        html()->text('shipping_method_name')->class('form-control')->id('txtShippingMethodName')->value(data_get($shippingMethodData, 'shipping_method_name', old('shipping_method_name'))),
                    ]),
            ]),
        html()->div()->class('col-sm-3')->addChildren([
                html()->div()->class('form-group')->addChildren([
                        html()->label('Cost: '),
                        html()->number('cost')->class('form-control')->id('txtCost')->value(data_get($shippingMethodData, 'cost', old('cost'))),
                    ]),
            ]),
        html()->div()->class('col-sm-3')->addChildren([
                html()->div()->class('form-group')->addChildren([
                        html()->label('Status: '),
                        html()->select('status', [
                                STATUS_ACTIVE => 'Active',
                                STATUS_INACTIVE => 'In-Active',
                            ])->class('form-control')->id('slctShippingMethodStatus')->placeholder('Select')->value(data_get($shippingMethodData, "status", old("status"))),
                    ]),
            ]),
        html()->div()->class("col-sm-12 mt-2")->addChildren([
                html()->label("Description:"),
                html()->text("shipping_method_description")->class("form-control")->id("txtDescription")->value(data_get($shippingMethodData, "description", old("shipping_method_description")))
        ]),
        html()->div()->class('col-sm-6 mt-2')->addChildren([
                html()->submit('Save')->class('btn btn-success m-2')->id('btnSave'),
                html()->a(route('admin.shipping_method'))->text('Cancel')->class('btn btn-danger'),
            ]),
    ]) }}

{{ html()->form()->close() }}
