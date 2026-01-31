@section('title', 'Holiday Create')
@section('description', 'Holiday Create')
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Holiday Create') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.settings.holidays.index'))
                            <a href="{{ route('hrm.settings.holidays.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mb-50">
                        <div class="card-header">
                            <h4 class="text-capitalize breadcrumb-title text-center">{{ trans('menu.create-leaves-menu-title') }}</h4>
                        </div>
                        <div class="card-body">
                            <x-error-alart />
                            <form action="{{ route('hrm.settings.holidays.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group row mb-3">
                                            <label for="inputError" class="col-sm-3 col-form-label bolder">
                                                Holiday Day Type
                                            </label>
                                            <div class="col-sm-9">
                                                <div class="radio">
                                                    <label class="me-3">
                                                        <input name="holiday_day_type" type="radio" value="1" id="day_to_day"
                                                            {{ old('holiday_day_type') == 2 ? 'checked' : '' }}
                                                            class="ace">
                                                        <span class="lbl"> Day To Day</span>
                                                    </label>
                                                    <label>
                                                        <input name="holiday_day_type" type="radio" value="2"
                                                            id="weekly_holiday"
                                                            {{ old('holiday_day_type') == 3 ? 'checked' : '' }}
                                                            class="ace">
                                                        <span class="lbl"> Weekly Holiday</span>
                                                    </label>
                                                </div>
                                                @error('holiday_day_type')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label bolder" for="form-field-1-1">
                                                Holiday Title
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" id="form-field-1-1" name="name"
                                                    value="{{ old('name') }}" placeholder="Holiday Title"
                                                    class="form-control" />
                                                @error('name')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3" id="day_to_day_value" style="display: none;">
                                            <label for="inputError" class="col-sm-3 col-form-label bolder">
                                                Date To Date
                                            </label>
                                            <div class="col-sm-9">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate" name="start_date"
                                                        value="{{ old('start_date') }}" autocomplete="off"
                                                        placeholder="Start Date" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>
                                                    <input type="text" class="form-control flatdate" name="end_date"
                                                        value="{{ old('end_date') }}" autocomplete="off"
                                                        placeholder="End Date" />
                                                </div>
                                                @error('start_date')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                                @error('end_date')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3" id="day_value" style="display: none;">
                                            <label for="inputError" class="col-sm-3 col-form-label bolder">
                                                Day
                                            </label>
                                            <div class="col-sm-9">
                                                <select class="tom-select form-control" multiple name="day[]"
                                                    data-placeholder="Choose Weekly Holiday...">
                                                    <option></option>
                                                    @foreach ($days as $key => $day)
                                                        <option {{ collect(old('day'))->contains($key) ? 'selected' : '' }}
                                                            value="{{ $day }}">{{ $day }}</option>
                                                    @endforeach
                                                </select>
                                                @error('day')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">Repeat Yearly</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="every_year" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0" selected>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
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
<script type="text/javascript">
    $(document).ready(function() {
        // Hide/show fields based on the selected radio button
        $('input[name="holiday_day_type"]').change(function() {
            if ($(this).val() == 1) {
                $("#day_to_day_value").show();
                $("#day_value").hide();
            } else if ($(this).val() == 2) {
                $("#day_value").show();
                $("#day_to_day_value").hide();
            }
        });

        // Trigger the change event on page load if a radio button is already selected
        $('input[name="holiday_day_type"]:checked').trigger('change');
    });
</script>
@endsection