{{ html()->form('POST')->action($action)->acceptsFiles()->id("frmProduct")->open() }}
{{ html()->div()->class('row')->addChild(
        html()->div()->class('col-sm-8')->addChild(
                html()->div()->class('form-group')->addChild([
                        html()->label('Enter Product Name:'),
                        html()->text('product_name')->class(TEXTBOX_CLASS)->value($productData?->product_name),
                    ]),
            ),
    )->addChild(
        html()->div()->class('col-sm-4')->addChild(
                html()->div()->class('form-group')->addChild([
                        html()->label('Select Category: '),
                        html()->select('product_category', $categories)->placeholder('Select')->class(TEXTBOX_CLASS)->value($productData?->category_id),
                    ]),
            ),
    ) }}
{{ html()->div()->class('row mt-3')->addChild([
        html()->div()->class('col-sm-2')->addChild(
                html()->div()->class('form-group')->addChild([
                        html()->label('Regular Price: '),
                        html()->number('product_price')->class(TEXTBOX_CLASS)->value($productData?->regular_price),
                    ]),
            ),
        html()->div()->class('col-sm-2')->addChild([
                html()->div()->class('form-group')->addChild([
                        html()->label('Sales Price (optional): '),
                        html()->number('sale_price')->class(TEXTBOX_CLASS)->value($productData?->sales_price),
                    ]),
            ]),
        html()->div()->class('col-sm-2')->addChild([
                html()->div()->class('form-group')->addChild([
                        html()->label('Stock Quantity: '),
                        html()->number('stock_quantity')->addClass(TEXTBOX_CLASS)->value($productData?->stock_quantity),
                    ]),
            ]),
        html()->div()->class('col-sm-3')->addChild(
                html()->div()->class('form-group mt-4')->addChildren([
                        html()->div()->class('form-check form-switch ms-1 mb-2')->addChild([
                                html()->checkbox('is_featured')->class('form-check-input')->checked($productData?->is_featured),
                                html()->label('Featured')->class('form-check-label fw-700'),
                            ]),
                    ]),
            ),
        html()->div()->class('col-sm-3')->addChild(
                html()->div()->class('form-group mt-4')->addChildren([
                        html()->div()->class('form-check form-switch ms-1 mb-2')->addChild([
                                html()->checkbox('is_new')->class('form-check-input')->checked($productData?->is_new),
                                html()->label('Mark As New')->class('form-check-label fw-700'),
                            ]),
                    ]),
            ),
    ]) }}
{{ html()->div()->class('row mt-3')->addChild([
        html()->div()->class('col-sm-6')->addChild(
                html()->div()->class('form-group')->addChild([
                        html()->label('Product Image: '),
                        html()->file('product_image')->class(TEXTBOX_CLASS)->acceptImage(),
                    ]),
            ),
        html()->div()->class('col-sm-6')->addChild(
                html()->div()->class('form-group')->addChild([
                        html()->label('Tags (seperated by comma): '),
                        html()->text('product_tags')->class(TEXTBOX_CLASS)->value(implode(',', [$productData?->productTags])),
                    ]),
            ),
    ]) }}

{{ html()->div()->class('row mt-3')->addChild(
        html()->div()->class('col-sm-12')->addChildren([
                html()->label('Product Description: '),
                html()->textarea('product_description')->class(TEXTBOX_CLASS)->rows(4)->style('resize: none;')->value($productData?->product_description),
            ]),
    ) }}
{{ html()->div()->class('row mt-3')->addChild(
        html()->div()->class('col-sm-3')->addChild([
                html()->submit('Submit')->class('btn btn-success m-2')->id("btnSubmit"),
                html()->reset('Cancel')->class('btn btn-secondary'),
            ]),
    ) }}
{{ html()->form()->close() }}
