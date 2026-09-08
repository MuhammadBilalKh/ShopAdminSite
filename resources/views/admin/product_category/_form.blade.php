{{ html()->form('POST')->action($action)->open() }}

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            {{ html()->label('Category Name: ') }} {{ html()->span("*")->class("text-danger") }}
            {{ html()->text('category_name')->id('txtCategoryName')->class(TEXTBOX_CLASS)->required(true)->value($categoryData ? $categoryData->category_name : old('category_name')) }}
            @error('category_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-8">
        <div class="form-group">
            {{ html()->label('Category Description: ') }}
            {{ html()->text('category_description')->id('txtCategoryName')->class(TEXTBOX_CLASS)->value($categoryData ? $categoryData->category_description : old('category_description')) }}
            @error('category_description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-3 mt-3">
        <div class="form-group">
            {{ html()->label('Status: ') }} {{ html()->span("*")->class("text-danger") }}
            {{ html()->select('category_status', [
                    STATUS_ACTIVE => 'Active',
                    STATUS_INACTIVE => 'In-Active',
                ])->id('slctCategoryStatus')->class(TEXTBOX_CLASS)->placeholder("Select")->value($categoryData ? $categoryData->status : old('status'))}}
            @error('category_status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    @if ($isReadOnly == false)
        <div class="col-sm-3 mt-3">
            <div class="form-group mt-3">
                @if ($formType == 'create')
                    {{ html()->submit('Submit')->id('btnSubmit')->class('btn btn-success') }}
                @else
                    {{ html()->submit('Update')->id('btnUpdate')->class('btn btn-primary') }}
                @endif
                {{ html()->a(route('admin.product_category_lists'))->class('btn btn-danger')->text('Cancel') }}
            </div>
        </div>
    @endif

</div>

{{ html()->form()->close() }}
