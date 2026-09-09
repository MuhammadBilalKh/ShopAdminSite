{{ html()->form('POST')->action($action)->id("frmCity")->open() }}

<div id="error-container"
    style="display: none; color: red; background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
</div>

{{ html()->hidden('form_action_route')->value($action) }}

{{ html()->div()->class('row')->addChild([
        html()->div()->class('col-sm-5')->addChildren([
                html()->div()->class('form-group')->addCHildren([
                        html()->label('City Name: '),
                        html()->text('city_name')->class(TEXTBOX_CLASS)->value($cityData?->city_name),
                    ]),
            ]),
        html()->div()->class('col-sm-4')->addChildren([
                html()->div()->class('form-group')->addCHildren([
                        html()->label('Iata Code: '),
                        html()->text('iata_code')->class(TEXTBOX_CLASS)->value($cityData?->iata_code),
                    ]),
            ]),
        html()->div()->class('col-sm-3 mt-2')->addChildren([
                html()->submit('Save')->class('btn btn-success m-2')->id('btnSubmit'),
                html()->reset('Reset')->class('btn btn-secondary'),
            ]),
    ]) }}

{{ html()->form()->close() }}
