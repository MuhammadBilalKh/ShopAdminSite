{{ html()->form('POST')->action($action)->id("frmMajorArea")->open() }}

{{ html()->hidden('form_action_route')->value($action) }}

{{ html()->div()->class('row')->addChildren([
        html()->div()->class('col-sm-5')->addChildren([
                html()->label('Enter Major Area Name: '),
                html()->text('major_area_name')->class(TEXTBOX_CLASS)->value($majorAreaData?->major_area_name),
            ]),
        html()->div()->class('col-sm-4')->addChildren([
                html()->label('Select City: '),
                html()->select('major_area_city', $cities)->class(TEXTBOX_CLASS)->placeholder('Select')->value($majorAreaData?->city_id),
            ]),
        html()->div()->class('col-sm-3 mt-3')->addChildren([
                html()->submit('Save')->id('btnSubmit')->addClass('btn btn-success m-2'),
                html()->reset('Reset')->class('btn btn-secondary'),
            ]),
    ]) }}

{{ html()->form()->close() }}
