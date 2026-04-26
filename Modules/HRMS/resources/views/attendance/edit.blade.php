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
                                        {{ trans('menu.edit-daily-attendances-menu-title') }}</li>
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-daily-attendances-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.attendances.singleUpdate', $attendance->id) }}" method="POST" enctype="multipart/form-data">
                                    @method("PUT")
                                    @csrf

                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="employee_id" class="color-dark fs-14 fw-500 align-center">Employee
                                                    Name</label>
                                                <select name="employee_id" id="employee_id" class="form-control tom-select">
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}" @if ($employee->id == $attendance->employee_id) selected @endif>{{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('employee_id'))
                                                    <p class="text-danger">{{ $errors->first('employee_id') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mb-25">
                                                <label for="date"
                                                    class="color-dark fs-14 fw-500 align-center">Date</label>
                                                <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime($attendance->date)) }}"
                                                    name="date" id="date" placeholder="Date" readonly>
                                                @if ($errors->has('date'))
                                                    <p class="text-danger">{{ $errors->first('date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row g-3"> 
                                            <!-- CHECK-IN -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-4 bg-white h-100">

                                                    <h5 class="mb-3">Check-in</h5>
                                                    <input type="hidden" class="form-control" name="check_in_date" id="check_in_date"  value="{{ $attendance->check_in_date ? date('Y-m-d', strtotime($attendance->check_in_date)) : '' }}" > 
                                            
                                                    <!-- Date + Location -->
                                                    <div class="row g-3 align-items-end">

                                                         <!-- Time -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Time</label>
                                                            {{-- <input type="text" class="form-control flattime"
                                                                name="check_in_time" id="check_in_time"> --}} 
                                                            <div class="input-group">
                                                                <input type="text"  class="form-control" name="check_in_time"     id="check_in_time" value="{{ !empty($attendance->check_in_time) ? date('h:i A', strtotime($attendance->check_in_time)) : '' }}" readonly> 
                                                                <button type="button" class="btn btn-danger" id="clear_in_time">  ✕ </button>
                                                            </div>
                                                        </div>

                                                        <!-- Location -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Location</label>

                                                            <div class="d-flex"> 
                                                                <input type="text" class="form-control me-1"
                                                                    id="check_in_latitude" name="check_in_latitude" placeholder="Lat" value="{{ $attendance->check_in_latitude }}" readonly>

                                                                <input type="text" class="form-control me-1"
                                                                    id="check_in_longitude" name="check_in_longitude" placeholder="Long" value="{{ $attendance->check_in_longitude }}" readonly>

                                                                <button class="btn btn-outline-primary"
                                                                    type="button" id="check_in_geolocate">
                                                                    <i class="fa fa-map-marker-alt"></i>
                                                                </button>

                                                            </div>
                                                        </div>



                                                    </div>

                                                    
 

                                                    <!-- Remarks -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Remarks</label>
                                                        <textarea name="check_in_remarks" class="form-control" rows="2">{{ $attendance->check_in_remarks }}</textarea>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- CHECK-OUT -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-4 bg-white h-100">

                                                    <h5 class="mb-3">Check-out</h5>
                                                    <input type="hidden" class="form-control" name="check_out_date" id="check_out_date" value="{{ $attendance->check_out_date ? date('Y-m-d', strtotime($attendance->check_out_date)) : '' }}"  >  
                                        
                                                    <!-- Date + Location -->
                                                    <div class="row g-3 align-items-end">

                                                        <!-- Time -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Time</label>
                                                            {{-- <input type="text" class="form-control flattime"
                                                                name="check_out_time" id="check_out_time"> --}}

                                                            <div class="input-group">
                                                                <input type="text"  class="form-control" name="check_out_time"     id="check_out_time" value="{{ !empty($attendance->check_out_time) ? date('h:i A', strtotime($attendance->check_out_time)) : '' }}" readonly> 
                                                                <button type="button" class="btn btn-danger" id="clear_out_time">  ✕ </button>
                                                            </div>

                                                            
                                                        </div>

                                                        <!-- Location -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Location</label>

                                                            <div class="d-flex">

                                                                <input type="text" class="form-control me-1"
                                                                    id="check_out_latitude"  name="check_out_latitude" placeholder="Lat" value="{{ $attendance->check_out_latitude }}" readonly>

                                                                <input type="text" class="form-control me-1"
                                                                    id="check_out_longitude" name="check_out_longitude" placeholder="Long" value="{{ $attendance->check_out_longitude }}" readonly>

                                                                <button class="btn btn-outline-primary"
                                                                    type="button" id="check_out_geolocate">
                                                                    <i class="fa fa-map-marker-alt"></i>
                                                                </button>

                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Remarks -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Remarks</label>
                                                        <textarea name="check_out_remarks" class="form-control" rows="2">{{ $attendance->check_out_remarks }}</textarea>
                                                    </div>

                                                </div>
                                            </div>

                                        </div> 


                                    </div>
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm" id="btnSubmit">Update</button>
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

        $(document).ready(function(){

            // click korle current time set
            $('#check_in_time').on('click', function(){
               if ($(this).val() === '') {

                let now = new Date();

                let hours = now.getHours();
                let minutes = now.getMinutes().toString().padStart(2, '0');

                let ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12; // 0 হলে 12 করবে

                hours = hours.toString().padStart(2, '0');

                let time = hours + ':' + minutes + ' ' + ampm;

                $(this).val(time);
            }
            });

            $('#check_out_time').on('click', function(){
               if ($(this).val() === '') {

                let now = new Date();

                let hours = now.getHours();
                let minutes = now.getMinutes().toString().padStart(2, '0');

                let ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12; // 0 হলে 12 করবে

                hours = hours.toString().padStart(2, '0');

                let time = hours + ':' + minutes + ' ' + ampm;

                $(this).val(time);
            }
            });


            // clear button
            $('#clear_in_time').on('click', function(){
                $('#check_in_time').val('');
            });

              // clear button
            $('#clear_out_time').on('click', function(){
                $('#check_out_time').val('');
            });


            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $('#check_in_latitude').val(lat);
                    $('#check_in_longitude').val(lon);
                })
            }
        

       
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $('#check_out_latitude').val(lat);
                    $('#check_out_longitude').val(lon);
                })
            }

            $('#btnSubmit').on('click', function(){ 
                let check_in_time = $('#check_in_time').val();
                if(!check_in_time)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'In Time can not be null.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    return false;
                }
            });

        });

    </script>

@endSection
