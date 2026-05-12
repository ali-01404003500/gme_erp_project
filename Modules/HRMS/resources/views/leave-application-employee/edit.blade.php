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
                                        {{ trans('menu.edit-leave-application-employees-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.leave-application-employees.index'))
                            <a href="{{ route('hrm.leave-application-employees.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-leaves-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.leave-application-employees.update', $leave->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="employee_id" class="color-dark fs-14 fw-500 align-center">Employee
                                                    Name <span class="text-danger">*</span></label>
                                                <select name="employee_id" id="employee_id" class="form-control tom-select required" required>
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}" {{ $employee->id == $leave->employee_id ? 'selected' : '' }}>{{ $employee->full_name }}
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('employee_id'))
                                                    <p class="text-danger">{{ $errors->first('employee_id') }}</p>
                                                @endif
                                            </div>
                                            <input type="hidden" name="leaveTypeWiseTotalLeave" id="leaveTypeWiseTotalLeave" value="0" >
                                            <input type="hidden" name="halfDayLeave" id="halfDayLeave" value="0" >
                                            <input type="hidden" name="simultaneouslyLimit" id="simultaneouslyLimit" value="0" >
                                            <input type="hidden" name="leaveBalance" id="leaveBalance" value="0" >
                                

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="leave_type" class="color-dark fs-14 fw-500 align-center">Leave Type<span class="text-danger">*</span></label>
                                                <select name="leave_type_id" id="leave_type" onchange="loadResponse()" class="form-control tom-select required" required>
                                                    <option value="">Select Leave Type</option>
                                                    @foreach ($leaveTypes as $leave_type)
                                                        <option value="{{ $leave_type->id }}" {{ $leave_type->id == $leave->leave_type_id ? 'selected' : '' }}>{{ $leave_type->leave_type_name }}
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
                                                    class="color-dark fs-14 fw-500 align-center">From Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatdate"  value="{{ old('from_date', date('Y-m-d', strtotime($leave->from_date))) }}"
                                                    name="from_date" id="from_date" placeholder="Date" onchange="getTotalDays()" required>
                                                @if ($errors->has('from_date'))
                                                    <p class="text-danger">{{ $errors->first('from_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="to_date"
                                                    class="color-dark fs-14 fw-500 align-center">To Date<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatdate
                                                "
                                                    value="{{ old('to_date', date('Y-m-d', strtotime($leave->to_date))) }}" name="to_date" id="to_date"
                                                    placeholder="To Date" onchange="getTotalDays()" required>
                                                @if ($errors->has('to_date'))
                                                    <p class="text-danger">{{ $errors->first('to_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                      

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="from_date_leave_count"
                                                    class="color-dark fs-14 fw-500 align-center">From date leave count for<span class="text-danger">*</span></label>
                                                    <select name="from_date_leave_count" id="from_date_leave_count" class="form-control tom-select" required> 
                                                        <option value="first_half" {{ $leave->from_date_leave_count == 'first_half' ? 'selected' : '' }}>First Half</option>
                                                        <option value="second_half" {{ $leave->from_date_leave_count == 'second_half' ? 'selected' : '' }}>Second Half</option>
                                                    </select>
                                                @if ($errors->has('from_date_leave_count'))
                                                    <p class="text-danger">{{ $errors->first('from_date_leave_count') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="to_date_leave_count"
                                                    class="color-dark fs-14 fw-500 align-center">To date leave count for<span class="text-danger">*</span></label>
                                                    <select name="to_date_leave_count" id="to_date_leave_count" class="form-control tom-select required" required>
                                                        <option value="first_half" {{ $leave->to_date_leave_count == 'first_half' ? 'selected' : '' }}>First Half</option>
                                                        <option value="second_half" {{ $leave->to_date_leave_count == 'second_half' ? 'selected' : '' }}>Second Half</option>
                                                    </select>
                                                @if ($errors->has('to_date_leave_count'))
                                                    <p class="text-danger">{{ $errors->first('to_date_leave_count') }}</p>
                                                @endif
                                            </div>
                                        </div> 


                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="total_days"
                                                    class="color-dark fs-14 fw-500 align-center">Total Days</label>
                                                <input type="text" class="form-control"  value="{{ old('total_days', $leave->total_days) }}"
                                                    name="day_count" id="total_days" placeholder="Total Days" readonly>
                                                @if ($errors->has('total_days'))
                                                    <p class="text-danger">{{ $errors->first('total_days') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image_upload" class="color-dark fs-14 fw-500 align-center">File Uploads </label>
                                                <x-file-uploader multiple :value="$leave->file_uploads" name="file_uploads" />
                                            </div>
                                        </div>  
                                        <div class="form-group col-md-12">
                                            <label for="remarks">Remarks:<span class="text-danger">*</span></label>
                                            <textarea name="remarks" id="remarks" class="form-control"
                                                placeholder="Remarks.." required>{{ old('remarks', $leave->remarks) }}</textarea>
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
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        // Initial load
        loadResponse();

        // Event listeners
        $('#from_date, #to_date').on('change', getTotalDays);
        $('#from_date, #to_date, #employee_id, #leave_type').on('change', loadResponse);

        // Hide typeWiseData initially
        $('.typeWiseData').hide();

        // Trigger change event on leave_type
        $('select[name="leave_type"]').change(loadResponse).trigger('change');
    });

    function getTotalDays() {
        // Inputs
        let start = $('#from_date').val();
        let end = $('#to_date').val();

        let leaveBalance = Number($('#leaveBalance').val()) || 0;
        let simultaneouslyLimit = Number($('#simultaneouslyLimit').val()) || 0;
        let halfDayLeave = Number($('#halfDayLeave').val()) || 0;
        let leaveTypeWiseTotalLeave = Number($('#leaveTypeWiseTotalLeave').val()) || 0;

        let from_type = $('#from_date_leave_count').val(); 
        let to_type = $('#to_date_leave_count').val();


        if (start !== '' && end !== '') {

            let startDate = new Date(start);
            let endDate = new Date(end);

            let diffDays = Math.floor((endDate - startDate) / 86400000) + 1;

            let leave_days = 0;

            // Same Day Leave
            if (diffDays === 1) {

                if (from_type === 'first_half' && to_type === 'first_half') {
                    leave_days = 0.5;
                }
                else if (from_type === 'first_half' && to_type === 'second_half') {
                    leave_days = 1;
                }
                else if (from_type === 'second_half' && to_type === 'first_half') {
                    leave_days = 0;
                }
                else if (from_type === 'second_half' && to_type === 'second_half') {
                    leave_days = 0.5;
                }
                else
                {
                    leave_days = 0;
                }

            }

            // Multiple Days Leave
            else {

                let middleDays = diffDays - 2;
                if (middleDays < 0) middleDays = 0;

                let from_value = 1;
                let to_value = 1;

                

                if (from_type === 'first_half' && to_type === 'first_half') {
                    from_value = 0.5;
                }
                if (from_type === 'first_half' && to_type === 'second_half') {
                    from_value = 1;
                }
                
                if (from_type === 'second_half' && to_type === 'second_half') {
                    from_value = 0.5;
                }


                leave_days = middleDays + from_value + to_value;

            }

            $("#total_days").val(leave_days);


            // Validation: Leave Balance
            if (leaveBalance < leave_days) {
                showToast('warning', 'Please apply to Leave is less than ' + leaveBalance + ' days');
                $("#total_days").val(0);
                $("#to_date").val('');
                return;
            }

            // Validation: Simultaneously Limit
            if (simultaneouslyLimit < leave_days) {
                showToast('warning', 'Simultaneously Limit exceed. Please apply to Leave is less than ' + simultaneouslyLimit + ' days');
                $("#total_days").val(0);
                $("#to_date").val('');
                return;
            }

            // Validation: Leave Type Wise Total Leave
            if (leaveTypeWiseTotalLeave < leave_days) {
                showToast('warning', 'Total leave amount is exceed. Please apply to this leave type is less than ' + leaveTypeWiseTotalLeave + ' days');
                $("#total_days").val(0);
                $("#to_date").val('');
                return;
            } 

        } else {

            $("#total_days").val(0);

        } 
        
    }

    function showToast(type, message) {
        if (type === 'warning') {
            toastr.warning(message);
        } else if (type === 'error') {
            toastr.error(message);
        }
    }

   

    function loadResponse() {
        let employee = $('#employee_id').val();
        let leave_type = $('#leave_type').val();

        if (employee != '' && leave_type != '') {
            $.get('{{ route('hrm.get.leave.response') }}?employee=' + employee + '&leave_type=' + leave_type, function(res) {    
                $("#leaveTypeWiseTotalLeave").val(res.leaveTypeWiseBalance.remaining_balance); 
                $("#halfDayLeave").val(res.leaveTypeWiseBalance.half_day); 
                $("#simultaneouslyLimit").val(res.leaveTypeWiseBalance.continuous_sanction);
                $("#leaveBalance").val(res.leaveBalance);
                getTotalDays(); // Recalculate total days after loading response
            });
        }
    }
    
</script>
@endSection
