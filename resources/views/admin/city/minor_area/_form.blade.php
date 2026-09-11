{{ html()->form('POST')->action($action)->id("frmMinorArea")->open() }}

<div id="error-container"
    style="display: none; color: red; background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
</div>

{{ html()->hidden('form_action_route')->value($action) }}

{{ html()->div()->class('row')->addChildren([
        html()->div()->class("col-sm-4")->children([
            html()->label("Select City:"),
            html()->select("city_name", $cities)->class(TEXTBOX_CLASS)->id('slctCity')->placeholder("Select")->value($minor_area_data?->getMajorArea?->city_id)
        ]),
        html()->div()->class('col-sm-4')->addChildren([
                html()->label('Select Major Area: '),
                html()->select('major_area_name', [])->class(TEXTBOX_CLASS)->placeholder("Select")->value($minor_area_data?->getMajorArea?->major_area_id)->id("slctMajorArea"),
            ]),
        html()->div()->class('col-sm-4')->addChildren([
                html()->label('Enter Minor Area Name: '),
                html()->text('minor_area_name')->class(TEXTBOX_CLASS)->value($minor_area_data?->minor_area_name),
            ]),
        html()->div()->class('col-sm-3 mt-3')->addChildren([
                html()->submit('Save')->id('btnSubmit')->addClass('btn btn-success m-2'),
                html()->reset('Reset')->class('btn btn-secondary'),
            ]),
    ]) }}

{{ html()->form()->close() }}
