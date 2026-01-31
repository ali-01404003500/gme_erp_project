@section('title', 'Daily Attendances')
@section('description', 'Daily Attendances')
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
                                        {{ trans('menu.create-daily-attendances-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.attendances.index'))
                            <a href="{{ route('hrm.attendances.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-daily-attendances-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.attendances.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="employee_id" class="color-dark fs-14 fw-500 align-center">Employee
                                                    Name</label>
                                                <select name="employee_id" id="employee_id" class="form-control tom-select">
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('employee_id'))
                                                    <p class="text-danger">{{ $errors->first('employee_id') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="date"
                                                    class="color-dark fs-14 fw-500 align-center">Date</label>
                                                <input type="text" class="form-control flatdate"  value="{{ old('date', date('Y-m-d')) }}"
                                                    name="date" id="date" placeholder="Date" readonly>
                                                @if ($errors->has('date'))
                                                    <p class="text-danger">{{ $errors->first('date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="date"
                                                    class="color-dark fs-14 fw-500 align-center">Shift</label>
                                                <select name="shift_id" id="shift_id" class="form-control tom-select">
                                                    <option value="">Select Shift</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift->id }}">{{ $shift->shift_name }}({{ date('h:i A', strtotime($shift->in_time)) }}-{{ date('h:i A', strtotime($shift->out_time)) }})</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('shift_id'))
                                                    <p class="text-danger">{{ $errors->first('shift_id') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5>Check-in</h5>
                                        </div>

                                        <div class="col-md-6">
                                            <h5>Check-out</h5>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="check_in_date"
                                                    class="color-dark fs-14 fw-500 align-center">Date</label>
                                                <input type="text" class="form-control flatdate"
                                                value="{{ old('check_in_date', date('Y-m-d')) }}" name="check_in_date" id="check_in_date"
                                                    placeholder=" Check-in Date">
                                                @if ($errors->has('check_in_date'))
                                                    <p class="text-danger">{{ $errors->first('check_in_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="check_in_time"
                                                    class="color-dark fs-14 fw-500 align-center">Time</label>
                                                <input type="text" class="form-control flattime" name="check_in_time"
                                                    id="check_in_time" placeholder="Check-in Time">
                                                @if ($errors->has('check_in_time'))
                                                    <p class="text-danger">{{ $errors->first('check_in_time') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="check_out_date"
                                                    class="color-dark fs-14 fw-500 align-center">Date</label>
                                                <input type="text" class="form-control datePicker"
                                                value="{{ old('check_out_date') }}" name="check_out_date" id="check_out_date"
                                                    placeholder="Check-out Date">
                                                @if ($errors->has('check_out_date'))
                                                    <p class="text-danger">{{ $errors->first('check_out_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-25">
                                                <label for="check_out_time"
                                                    class="color-dark fs-14 fw-500 align-center">Time</label>
                                                <input type="text" class="form-control flattime" name="check_out_time"
                                                    id="check_out_time" placeholder="Check-out Time">
                                                @if ($errors->has('check_out_time'))
                                                    <p class="text-danger">{{ $errors->first('check_out_time') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="location">Location</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value=""
                                                    name="check_in_latitude" id="check_in_latitude"
                                                    placeholder="Latitude">
                                                <input type="text" class="form-control" value=""
                                                    name="check_in_longitude" id="check_in_longitude"
                                                    placeholder="Longitude">
                                                    <span class="input-group-text">

                                                <button class="btn" type="button" id="check_in_geolocate"
                                                    title="Get current location">
                                                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>                                                </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="location">Location</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value=""
                                                    name="check_out_latitude" id="check_out_latitude"
                                                    placeholder="Latitude">
                                                <input type="text" class="form-control" value=""
                                                    name="check_out_longitude" id="check_out_longitude"
                                                    placeholder="Longitude">
                                                    <span class="input-group-text">
                                                        <button class="btn" type="button"
                                                        id="check_out_geolocate" title="Get current location">
                                                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i>                                                </button>
                                                    </button>
                                                    </span>
                                              
                                            </div>

                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control"
                                                placeholder="Remarks..">{{ old('remarks') }}</textarea>
                                        </div>


                                    </div>
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                    </div>
                                </form>
                            </div>
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
            flatpickr('.datePicker', {
                enableTime: false,
                dateFormat: "Y-m-d",
            })
        })

        $('#check_in_geolocate').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $('#check_in_latitude').val(lat);
                    $('#check_in_longitude').val(lon);
                })
            }
        })

        $('#check_out_geolocate').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $('#check_out_latitude').val(lat);
                    $('#check_out_longitude').val(lon);
                })
            }
        })
    </script>

@endSection
