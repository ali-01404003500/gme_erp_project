@section('title', 'Leave Application')
@section('description', 'Leave Application')
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
                                        {{ trans('menu.create-leaves-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.leaves.index'))
                            <a href="{{ route('hrm.leaves.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-leaves-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.leaves.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="employee_id" class="color-dark fs-14 fw-500 align-center">Employee
                                                    Name <span class="text-danger">*</span></label>
                                                <select name="employee_id" id="employee_id" class="form-control tom-select required" required>
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

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="leave_type" class="color-dark fs-14 fw-500 align-center">Leave Type<span class="text-danger">*</span></label>
                                                <select name="leave_type_id" id="leave_type" onchange="loadResponse()" class="form-control tom-select required" required>
                                                    <option value="">Select Leave Type</option>
                                                    @foreach ($leaveTypes as $leave_type)
                                                        <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('leave_type'))
                                                    <p class="text-danger">{{ $errors->first('leave_type') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="from_date"
                                                    class="color-dark fs-14 fw-500 align-center">From Date: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control datePicker"  value="{{ old('from_date') }}"
                                                    name="from_date" id="from_date" placeholder="Date" onchange="getTotalDays()" required>
                                                @if ($errors->has('from_date'))
                                                    <p class="text-danger">{{ $errors->first('from_date') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="from_date_leave_count"
                                                    class="color-dark fs-14 fw-500 align-center">From date leave count for:<span class="text-danger">*</span></label>
                                                    <select name="from_date_leave_count" id="from_date_leave_count" class="form-control tom-select" required>
                                                        <option value="full_day">Full Day</option>
                                                        <option value="first_half_day">First Half Day</option>
                                                        <option value="last_half_day">Last Half Day</option>
                                                    </select>
                                                @if ($errors->has('from_date_leave_count'))
                                                    <p class="text-danger">{{ $errors->first('from_date_leave_count') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="to_date"
                                                    class="color-dark fs-14 fw-500 align-center">To Date:<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control datePicker"
                                                    value="{{ old('to_date') }}" name="to_date" id="to_date"
                                                    placeholder="To Date" onchange="getTotalDays()" required>
                                                @if ($errors->has('to_date'))
                                                    <p class="text-danger">{{ $errors->first('to_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="to_date_leave_count"
                                                    class="color-dark fs-14 fw-500 align-center">To date leave count for:<span class="text-danger">*</span></label>
                                                    <select name="to_date_leave_count" id="to_date_leave_count" class="form-control tom-select required" required>
                                                        <option value="full_day">Full Day</option>
                                                        <option value="first_half_day">First Half Day</option>
                                                        <option value="last_half_day">Last Half Day</option>
                                                    </select>
                                                @if ($errors->has('to_date_leave_count'))
                                                    <p class="text-danger">{{ $errors->first('to_date_leave_count') }}</p>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-md-2 typeWiseData">
                                            <div class="form-group mb-25">
                                                <label for="total_days"
                                                    class="color-dark fs-14 fw-500 align-center">Total Days:</label>
                                                <input type="text" class="form-control text-center"  value="{{ old('total_days') }}"
                                                    name="day_count" id="total_days" placeholder="Total Days" readonly>
                                                @if ($errors->has('total_days'))
                                                    <p class="text-danger">{{ $errors->first('total_days') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 typeWiseData">
                                            <div class="form-group mb-25">
                                                <label for="companyTotalLeave"
                                                    class="color-dark fs-14 fw-500 align-center">Total Leave:</label>
                                                <input type="text" class="form-control text-center"  value="{{ old('companyTotalLeave') }}"
                                                    name="companyTotalLeave" id="companyTotalLeave" placeholder="Total Leave" readonly>
                                                @if ($errors->has('companyTotalLeave'))
                                                    <p class="text-danger">{{ $errors->first('companyTotalLeave') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 typeWiseData">
                                            <div class="form-group mb-25">
                                                <label for="LeaveBalance"
                                                    class="color-dark fs-14 fw-500 align-center">Leave Balance:</label>
                                                <input type="text" class="form-control text-center"  value="{{ old('leave_balance') }}"
                                                    name="leave_balance" id="leaveBalance" placeholder="Leave Balance" readonly>
                                                @if ($errors->has('leave_balance'))
                                                    <p class="text-danger">{{ $errors->first('leave_balance') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 typeWiseData">
                                            <div class="form-group mb-25">
                                                <label for="simultaneouslyLimit"
                                                    class="color-dark fs-14 fw-500 align-center">Simultaneously Limit:</label>
                                                <input type="text" class="form-control text-center"  value="{{ old('simultaneouslyLimit') }}"
                                                    name="simultaneouslyLimit" id="simultaneouslyLimit" placeholder="Simultaneously Limit" readonly>
                                                @if ($errors->has('simultaneouslyLimit'))
                                                    <p class="text-danger">{{ $errors->first('simultaneouslyLimit') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="remarks">Remarks:<span class="text-danger">*</span></label>
                                            <textarea name="remarks" id="remarks" class="form-control"
                                                placeholder="Remarks.." required>{{ old('remarks') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image_upload"
                                                    class="color-dark fs-14 fw-500 align-center">File Uploads : </label>
                                                <input type="file"
                                                    class="file-control form-control"
                                                    id="image_upload" name="file_uploads[]"
                                                    multiple>
                                            </div>
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
        $('.datePicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

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

        function getTotalDays() {
            let start = $('#from_date').val();
            let end = $('#to_date').val();
            let leaveBalance = $('#leaveBalance').val();
            let simultaneouslyLimit = $('#simultaneouslyLimit').val();
            console.log( start, end);

            if (start != '' && end != '') {
                let startDay = new Date(start);
                let endDay = new Date(end);

                let millisecondsPerDay = 1000 * 60 * 60 * 24;
                let millisBetween = endDay.getTime() - startDay.getTime();
                let days = millisBetween / millisecondsPerDay;

                let leave_days = Number((days+1) | 0)
                if (leaveBalance < leave_days) {
                    showToast('warning', 'Leave Balance is less than ' + leave_days + ' days');
                    $("#total_days").val(0);
                    $("#to_date").val('');
                } else if (simultaneouslyLimit < leave_days) {
                    showToast('warning', 'Simultaneously Limit is less than ' + leave_days + ' days');
                    $("#total_days").val(0);
                    $("#to_date").val('');
                } else {
                    $("#total_days").val(leave_days);
                }
            }
        }
        
        function showToast(type, message) {
            // Display toast message
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        }
    </script>
    <script>
         function loadResponse() {
            let employee = $('#employee_id').val();
            let leave_type = $('#leave_type').val();

            if (leave_type != '') {
                $.get('{{ route('hrm.get.leave.response') }}?employee=' + employee + '&leave_type=' + leave_type, function(res) {
                        $(".typeWiseData").show();
                        $("#companyTotalLeave").val(res.companyLeaveType.total_day);
                        $("#simultaneouslyLimit").val(res.companyLeaveType.simultaneously_limit);
                        $("#leaveBalance").val(res.leaveBalance);
                    });
            }
        }
        $(document).ready(function() {
            $('.typeWiseData').hide();
            $('select[name="leave_type"]').change(loadResponse).trigger('change')
        })
    </script>

@endSection
