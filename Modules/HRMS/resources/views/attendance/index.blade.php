 @section('title', 'Attendance List')
@section('description', 'Attendance List')
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
                                        {{ trans('menu.daily-attendances-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('hrm.attendances.create'))
                                    <a href="{{ route('hrm.attendances.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                                <a href="{{ route('hrm.attendances.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('hrm.attendances.index') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block" style="margin-left: 5px;">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a> 
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <style>
                .nav-icon la la-cart-arrow-down{
                    font-size: 26px;
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.daily-attendances-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%">
                                                <select name="employee_id" id="employee_id" class="tom-select  input-sm"
                                                    data-placeholder="Select Employee">
                                                    <option value=""></option>
                                                    @foreach ($employees as $key => $value)
                                                        <option {{ request('employee_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
 
                                            <td width="30%">
                                                 <div class="input-daterange input-group">  
                                                    <input type="text" class="form-control flatdate" value="{{ old('from', date('Y-m-d')) }}"
                                                        name="from" id="from" placeholder="From">

                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>
 
                                                    <input type="text" class="form-control flatdate"  value="{{ old('to', date('Y-m-d')) }}"
                                                        name="to" id="to" placeholder="To">
                                                </div>
                                            </td>          
  
                                            <td width="20%">
                                                <select name="department_id" id="department_id" class="tom-select  input-sm"
                                                    data-placeholder="Select Department">
                                                    <option value="">Select Department</option>
                                                    @foreach ($departments as $key => $department)
                                                        <option {{ request('department_id') == $department->id ? 'selected' : '' }}
                                                            value="{{ $department->id }}">
                                                            {{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="20%">
                                                <select name="branch_id" id="branch_id" class="tom-select  input-sm"
                                                    data-placeholder="Select Branch">
                                                    <option value="">Select Branch</option>
                                                    @foreach ($branches as $key => $branch)
                                                        <option {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                                                            value="{{ $branch->id }}">
                                                            {{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>


                                            <td width="10%" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                        Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td> 
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
              
                <div class="col-md-12">
                   <!-- Employee Card --> 
                   
                    @foreach($employees->where('status', 1) as $employee)
                    <div class="card border mb-2">
                        <!-- Employee Header -->
                        <div class="card-header d-flex align-items-center justify-content-between employee-header" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#attendance-{{ $employee->id }}" 
                            style="cursor: pointer;">
                            
                            <div class="row col-md-12 my-2 align-items-center">
                                <!-- Avatar -->
                                <div class="col-md-1">
                                    <img src="{{ $employee->photograph ?? '' }}" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                </div>

                                <!-- Name & Card No -->
                                <div class="col-md-2">
                                    <strong>{{ $employee->full_name }}</strong><br>
                                    <small>{{ $employee->employementDetail->card_no }}</small>
                                </div>

                                <!-- Designation & Department & Branch -->
                                <div class="col-md-8">
                                    <strong>{{ $employee->employementDetail->designation->name ?? '' }}</strong><br>
                                    <small>{{ $employee->employementDetail->department->name ?? '' }}</small><br>
                                    <small>{{ $employee->employementDetail->branch->name ?? '' }}</small>
                                </div>

                                <!-- Toggle Icon -->
                                <div class="col-md-1 text-end">
                                    <i class="fas fa-plus toggle-icon" style="font-size:20px;"></i>
                                </div>
                            </div>
                           
                        </div>

                        <!-- Collapsible Attendance Table -->
                        <div id="attendance-{{ $employee->id }}" class="collapse">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Attendance Date</th>
                                                <th>Flag</th>
                                                <th>In Time</th>
                                                <th>In Time Remarks</th>
                                                <th>Out Time</th>
                                                <th>Out Time Remarks</th>
                                                <th>Working Hour</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            @foreach ($period as $date) 
                                                @php
                                                    $d = $date->format('Y-m-d');
                                                    $attendance = $attendancesByEmployee[$employee->id][$d] ?? null; 
                                                @endphp 
                                                <tr>
                                                    <td>{{ $date->format('D') }}, {{ $date->format('d-m-Y') }}</td>

                                                    <td id="flag">{{ $attendance->flag  ?? 'A'}}</td>

                                                    <td> 
                                                        <input type="time" class="form-control intimepicker" name="check_in_time"   data-touched="{{ optional($attendance)->check_in_time ? 'true' : 'false' }}" value="{{ $attendance->check_in_time ?? '' }}">

                                                        <input type="hidden" class="form-control" name="check_in_date" id="check_in_date" value="{{ $attendance->check_in_date ?? $date->format('Y-m-d') }}"> 
                                                        <input type="hidden" class="form-control" name="check_in_latitude" id="check_in_latitude" value="{{ $attendance->check_in_latitude ?? 0 }}">
                                                        <input type="hidden" class="form-control" name="check_in_longitude" id="check_in_longitude" value="{{ $attendance->check_in_longitude ?? 0 }}">
                                                        <input type="hidden" class="form-control" name="check_out_date" id="check_out_date" value="{{ $attendance->check_out_date ?? $date->format('Y-m-d') }}">
                                                        <input type="hidden" class="form-control" name="check_out_latitude" id="check_out_latitude" value="{{ $attendance->check_out_latitude ?? 0 }}">
                                                        <input type="hidden" class="form-control" name="check_out_longitude" id="check_out_longitude" value="{{ $attendance->check_out_longitude ?? 0 }}">
                                                        <input type="hidden" class="form-control" name="employee_id" id="employee_id" value="{{ $employee->id }}">
                                                        <input type="hidden" class="form-control" name="date" id="date" value="{{ $attendance->date ?? $date->format('Y-m-d') }}"> 
                                                        <input type="hidden" class="form-control" name="attendance_id" id="attendance_id" value="{{ $attendance->id ?? '' }}">
                                                         
                                                    </td>

                                                    <td>
                                                        <input type="text" class="form-control" name="check_in_remarks" id="check_in_remarks" placeholder="In Remarks" value="{{ $attendance->check_in_remarks ?? '' }}"> 
                                                    </td>

                                                    <td>
                                                        <input type="time" class="form-control outtimepicker" name="check_out_time" id="check_out_time"   data-touched="{{ optional($attendance)->check_out_time ? 'true' : 'false' }}" value="{{ $attendance->check_out_time ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <input type="text" class="form-control" name="check_out_remarks" id="check_out_remarks" placeholder="Out Remarks" value="{{ $attendance->check_out_remarks ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-secondary">0:0</span>
                                                    </td> 

                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group"
                                                            aria-label="Small button group">
                                                            @php
                                                                $attendanceId = optional($attendance)->id;
                                                            @endphp
                                                            @if (hasPermission('hrm.attendances.create') && empty($attendanceId))
                                                                <button type="button" 
                                                                class="btn btn-sm btn-success create-attendance"><i class="far fa-save"></i></button>
                                                            @endif

                                                            @if (hasPermission('hrm.attendances.update') && !empty($attendanceId))
                                                                <button type="button" 
                                                                class="btn btn-sm btn-success update-attendance" style="{{ !empty($attendanceId) ? '' : 'display:none;' }}" ><i class="far fa-edit"></i></button>
                                                            @endif

                                                            @if (hasPermission('hrm.attendances.destroy')) 
                                                                <button type="button" 
                                                                class="btn btn-sm btn-danger delete-attendance" ><i class="far fa-trash-alt"></i></button>
                                                                 
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
 
 
        $(document).ready(function() { 

            $('.intimepicker,.outtimepicker').on('click', function() {
                $(this).data('touched', true);
            }); 
           

           
            $('.employee-header').click(function() {
                var icon = $(this).find('.toggle-icon');
                var target = $(this).data('bs-target');

                // Close all other collapses except the one clicked
                $('.collapse').not(target).collapse('hide');

                // Reset icons of other headers
                $('.employee-header').not(this).find('.toggle-icon')
                    .removeClass('fa-minus')
                    .addClass('fa-plus');

                // Toggle the clicked one
                $(target).collapse('toggle');

                // Change icon after collapse finishes
                $(target).off('shown.bs.collapse hidden.bs.collapse'); // remove previous handlers
                $(target).on('shown.bs.collapse', function() {
                    icon.removeClass('fa-plus').addClass('fa-minus');
                });
                $(target).on('hidden.bs.collapse', function() {
                    icon.removeClass('fa-minus').addClass('fa-plus');
                });
            });


            flatpickr(".intimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",  // 12-hour format with AM/PM
                time_24hr: false,     // false → 12-hour clock
                minuteIncrement: 1,   // 1-minute steps
                defaultDate: "09:00 AM"
            });

            flatpickr(".outtimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",  // 12-hour format with AM/PM
                time_24hr: false,     // false → 12-hour clock
                minuteIncrement: 1,   // 1-minute steps
                defaultDate: "05:00 PM"
            });

            //save attendance data
            $('.create-attendance').click(function() {
                var $btn = $(this);
                var $row = $btn.closest('tr'); 
               
                // Collect input values for this row
                var employee_id = $row.find('input[name="employee_id"]').val();
                var date = $row.find('input[name="date"]').val();
 
                var check_in_date = $row.find('input[name="check_in_time"]').data('touched') ?$row.find('input[name="check_in_date"]').val() : null;   
                var check_in_time = $row.find('input[name="check_in_time"]').data('touched') ?$row.find('input[name="check_in_time"]').val() : null; 
                var check_in_remarks = $row.find('input[name="check_in_remarks"]').val();
                var check_in_latitude = $row.find('input[name="check_in_latitude"]').val();
                var check_in_longitude = $row.find('input[name="check_in_longitude"]').val();
  
                var check_out_date = $row.find('input[name="check_out_time"]').data('touched') ?$row.find('input[name="check_out_date"]').val() : null;  
                var check_out_time = $row.find('input[name="check_out_time"]').data('touched') ?$row.find('input[name="check_out_time"]').val() : null; 
                var check_out_remarks = $row.find('input[name="check_out_remarks"]').val();
                var check_out_latitude = $row.find('input[name="check_out_latitude"]').val();
                var check_out_longitude = $row.find('input[name="check_out_longitude"]').val();

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
               

                // Show spinner 
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
 

                $.ajax({
                    url: "{{ route('hrm.attendances.store') }}", 
                    method:  'POST',
                    data: {
                        _token: "{{ csrf_token() }}",  
                        employee_id: employee_id,
                        date: date,

                        check_in_date: check_in_date,
                        check_in_time: check_in_time,
                        check_in_remarks: check_in_remarks,
                        check_in_latitude: check_in_latitude,
                        check_in_longitude: check_in_longitude, 

                        check_out_date: check_out_date,
                        check_out_time: check_out_time, 
                        check_out_remarks: check_out_remarks,
                        check_out_latitude: check_out_latitude,
                        check_out_longitude: check_out_longitude
                       
                    },
                    success: function(response) {
                        if(response.status === 'success'){
                            // SweetAlert success
                            Swal.fire({
                                icon: 'success',
                                title:  'Saved!',
                                text: 'Attendance for ' + date + ' has been saved.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Hide Save button
                            $btn.hide(); 
                            $row.find('.update-attendance').show();

                          
                            $row.find('input[name="attendance_id"]').val(response.attendance_id);
                            $row.find('#flag').text(response.flag);
                            
                            
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                timer: 1500
                            });
                        }
                        $btn.html('<i class="far fa-save"></i>');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving!',
                            timer: 1500
                        });
                       $btn.html('<i class="far fa-save"></i>');
                    }
                });
            }); 


            //update attendance data
            $('.update-attendance').click(function() {
                var $btn = $(this);
                var $row = $btn.closest('tr'); 
               
                // Collect input values for this row
                var attendance_id = $row.find('input[name="attendance_id"]').val(); 
                var employee_id = $row.find('input[name="employee_id"]').val();
                var date = $row.find('input[name="date"]').val();

                var check_in_date = $row.find('input[name="check_in_time"]').data('touched') ?$row.find('input[name="check_in_date"]').val() : null;   
                var check_in_time = $row.find('input[name="check_in_time"]').data('touched') ?$row.find('input[name="check_in_time"]').val() : null; 
                var check_in_remarks = $row.find('input[name="check_in_remarks"]').val();
                var check_in_latitude = $row.find('input[name="check_in_latitude"]').val();
                var check_in_longitude = $row.find('input[name="check_in_longitude"]').val();
  
                var check_out_date = $row.find('input[name="check_out_time"]').data('touched') ?$row.find('input[name="check_out_date"]').val() : null;  
                var check_out_time = $row.find('input[name="check_out_time"]').data('touched') ?$row.find('input[name="check_out_time"]').val() : null; 
                var check_out_remarks = $row.find('input[name="check_out_remarks"]').val();
                var check_out_latitude = $row.find('input[name="check_out_latitude"]').val();
                var check_out_longitude = $row.find('input[name="check_out_longitude"]').val();

               
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
               
                // Show spinner 
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
  
                var updateRouteTemplate = "{{ route('hrm.attendances.update', ':id') }}"; 
                var url = updateRouteTemplate.replace(':id', attendance_id);
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}", 
                        _method: "PUT", 
                        employee_id: employee_id,
                        id: attendance_id,
                        date: date,

                        check_in_date: check_in_date,
                        check_in_time: check_in_time,
                        check_in_remarks: check_in_remarks,
                        check_in_latitude: check_in_latitude,
                        check_in_longitude: check_in_longitude, 

                        check_out_date: check_out_date,
                        check_out_time: check_out_time, 
                        check_out_remarks: check_out_remarks,
                        check_out_latitude: check_out_latitude,
                        check_out_longitude: check_out_longitude
                       
                    },
                    success: function(response) {
                        if(response.status === 'success'){
                            // SweetAlert success
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Attendance for ' + date + ' has been updated.',
                                timer: 1500,
                                showConfirmButton: false
                            });
 
                            // If it was a new save, set the attendance_id for future updates
                            if(response.attendance_id && !attendance_id){
                                $row.find('input[name="attendance_id"]').val(response.attendance_id);
                            }
                            $row.find('#flag').text(response.flag);
                            
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                        $btn.html('<i class="far fa-edit"></i>');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving!'
                        });
                       $btn.html('<i class="far fa-edit"></i>');
                    }
                });

                $btn.prop('disabled', false).html('<i class="far fa-edit"></i>');
            }); 


            $('.delete-attendance').click(function() {
                var $btn = $(this);
                var $row = $btn.closest('tr'); 
               
                // Collect input values for this row
                var attendance_id = $row.find('input[name="attendance_id"]').val(); 
               
                // Show spinner
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
  
                var updateRouteTemplate = "{{ route('hrm.attendances.destroy', ':id') }}"; 
                var url = updateRouteTemplate.replace(':id', attendance_id);
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}", 
                        _method: "PUT",  
                        id: attendance_id
                       
                    },
                    success: function(response) {
                        if(response.status === 'success'){
                            // SweetAlert success
                            Swal.fire({
                                icon: 'success',
                                title: 'Delete!',
                                text: 'Attendance for ' + date + ' has been deleted.',
                                timer: 2500,
                                showConfirmButton: false
                            });
 
                            $row.find('input[name="attendance_id"]').val('');
                            
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                        $btn.html('<i class="far fa-edit"></i>');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting!'
                        });
                       $btn.html('<i class="far fa-edit"></i>');
                    }
                }); 
            }); 

        }); 
 

           


   


         
    </script>
@endSection
