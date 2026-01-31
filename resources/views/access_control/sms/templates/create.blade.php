@section('title', 'SMS Template')
@section('description', 'SMS Template')
@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.create-sms-template-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('sms.templates.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-sms-template-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('sms.templates.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="col-md-10 mt-3">
                                            <div class="form-group">
                                                <label for="template_title"> Template Title<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="template_title" id="template_title"
                                                    placeholder="Enter SMS template title" value="{{ old('template_title') }}" required>
                                                    @if ($errors->has('template_title'))
                                                        <span class="text-danger">{{ $errors->first('template_title') }}</span>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="service_name_id"> Service Name<span class="text-danger">*</span></label>
                                                <select name="service_name_id" id="service_name_id" class="form-control tom-select"
                                                    data-placeholder="Select Service Name">
                                                    <option value=""></option>
                                                    @foreach ($serviceNames as $key => $value)
                                                        <option {{ request('service_name_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}" data-table_name="{{ $value->code }}">
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('service_name_id'))
                                                    <span class="text-danger">{{ $errors->first('service_name_id') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="trigger_name_id"> Trigger Name<span class="text-danger">*</span></label>
                                                <select name="trigger_name_id" id="trigger_name_id" class="form-control tom-select"
                                                    data-placeholder="Select Trigger Name">
                                                    <option value=""></option>
                                                </select>
                                                @if ($errors->has('trigger_name_id'))
                                                    <span class="text-danger">{{ $errors->first('trigger_name_id') }}</span>
                                                @endif
                                              
                                            </div>
                                        </div>
                                        <div class="col-md-10">

                                            <div class="form-group">
                                                <label for="template_body">Template Body<span class="text-danger">*</span></label>
                                                <textarea name="template_body" id="template_body" class="form-control" cols="30" rows="10" required>{{ old('template_body') }}</textarea>
                                                @if ($errors->has('template_body'))
                                                    <span class="text-danger">{{ $errors->first('template_body') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 mt-3" style="border-left: 3px solid #969799">
                                        <div class="col-md-10">
                                            <fieldset class="border" style="height: 500px;">
                                                <legend class="float-none w-auto p-2">
                                                    Select Entity
                                                </legend>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="entity-list" style="display: flex; flex-wrap: wrap;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page_scripts')
<script>
    $(document).ready(function() {
        $('#service_name_id').on('change', function() {
            var service_name_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('sms.service-name-wise-trigger-names') }}',
                data: { service_name_id: service_name_id },
                success: function(data) {
                    $('#trigger_name_id').empty();

                    $('#trigger_name_id').append('<option value="">Choose Trigger Name</option>');

                    $.each(data.triggerNames, function(index, triggerName) {
                        $('#trigger_name_id').append('<option value="' + triggerName.id + '">' + triggerName.name + '</option>');
                    });
                    $('#trigger_name_id').prop('tomselect').clearOptions();
                    $('#trigger_name_id').prop('tomselect')?.sync();
                }
            });
        });
    });
</script>  
<script>
    $(document).ready(function() {
        $('#service_name_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var tableName = selectedOption.data('table_name');
            $('#template_body').val('');
            loadEntityList(tableName);
        });
    });

    function loadEntityList(tableName) {
    console.log(tableName);
    $.ajax({
        type: 'GET',
        url: '{{ route('sms.entity-list') }}',
        data: { table_name: tableName },
        success: function(data) {
            console.log(data);
            $('#entity-list').html('');

            // Calculate the column width based on the number of items
            var numItems = data.length;
            var colWidth = Math.floor(12 / Math.ceil(Math.sqrt(numItems)));

            // Loop through the column names and create selectable items
            $.each(data, function(index, column) {
                var $item = $('<div class="p-2 selectable-item col-md-' + colWidth + '">' + 
                    '<span class="badge badge-round badge-info">' + column + '</span>' + 
                '</div>');
                $item.appendTo('#entity-list');

                // Add a click event to insert the column name into the template_body textarea
                $item.on('click', function() {
                    var textarea = $('#template_body');
                    var cursorPosition = textarea.prop('selectionStart');
                    var text = textarea.val();
                    var newText = text.substring(0, cursorPosition)+ ' $' + column + ' '+ text.substring(cursorPosition);
                    textarea.val(newText);
                    textarea.focus();
                });
            });           
        }
    });
}


</script>                    
@endsection