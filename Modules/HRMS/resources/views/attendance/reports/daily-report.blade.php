@section('title', 'Attendance Report')
@section('description', 'Attendance Report')
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
                                        {{ trans('Daily Attendance Report') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                <a href="{{ route('hrm.reports.daily-attendance-report') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('hrm.reports.daily-attendance-report') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Daily Attendance Report') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                           <div class="filter-section mb-4">
                                <form method="GET" action="{{ route('hrm.reports.daily-attendance-report') }}">
                                    <div class="row">
                                        <table class="table table-bordered">
                                            <tr>
                                                
                                                <td>
                                                    <select name="branch" class="form-control">
                                                        <option value="">Select Branch</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                           
                                                <td>
                                                    <select name="department" class="form-control">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                           
                                                <td>
                                                    <select name="designation" class="form-control">
                                                        <option value="">Select Designation</option>
                                                        @foreach($designations as $designation)
                                                            <option value="{{ $designation->id }}" {{ request('designation') == $designation->id ? 'selected' : '' }}>
                                                                {{ $designation->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
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
                                                <td>
                                                    <select name="attendance_type" class="form-control">
                                                        <option value="">All Statuses</option>
                                                        <option value="Present" {{ request('attendance_type') == 'Present' ? 'selected' : '' }}>Present</option>
                                                        <option value="Absent" {{ request('attendance_type') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                                        <option value="Late" {{ request('attendance_type') == 'Late' ? 'selected' : '' }}>Late</option>
                                                        <option value="Holiday" {{ request('attendance_type') == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                                                        <option value="Leave" {{ request('attendance_type') == 'Leave' ? 'selected' : '' }}>Leave</option>
                                                        <option value="Weekend" {{ request('attendance_type') == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="date" class="form-control flatdate" value="{{ request('date') }}" placeholder="Date">
                                                </td>
                                            
                                                <td colspan="2" class="text-right">
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
                </div>
                
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                @php
                                $groupedData = $attendanceReports->groupBy([
                                    function ($item) {
                                        return $item->employee->employementDetail->branch->name ?? 'N/A';
                                    },
                                    function ($item) {
                                        return $item->employee->employementDetail->department->name ?? 'N/A';
                                    }
                                ]);
                                @endphp

                                @foreach($groupedData as $branchName => $departments)
                                <table class="table table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th colspan="10">
                                                Branch Name : {{ $branchName }} ({{ request('date')?? \Carbon\Carbon::now()->format('d-m-Y') }})
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    @foreach($departments as $departmentName => $attendances)
                                        <tbody>
                                            <tr>
                                                <td colspan="10">
                                                    Department Name : {{ $departmentName }}
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <th>SL</th>
                                                <th>Emp ID</th>
                                                <th>Employee Name</th>
                                                <th>Designation</th>
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Late</th>
                                                <th>Status</th>
                                                <th>Entry By</th>
                                                <th>Remarks</th>
                                            </tr>
                                            
                                            @foreach($attendances as $key => $attendance)
                                            @php
                                                $shift = \Modules\HRMS\Models\Settings\Shift::where('id', 10000)->first();
                                                $work_duration = 'N/A';
                                                $late = 0;

                                                if($attendance->check_in_time && $attendance->check_out_time){
                                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                    $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                                    $work_duration_hours = $checkIn->diffInHours($checkOut);
                                                    $work_duration_minutes = $checkIn->diffInMinutes($checkOut) % 60;
                                                    $work_duration = $work_duration_hours . ' Hours ' . $work_duration_minutes . ' Minutes';

                                                    $shiftInTime = \Carbon\Carbon::parse($attendance->shift->in_time ?? $shift->in_time);
                                                    $graceTime = $attendance->shift->grace_time ?? $shift->grace_time;
                                                    $difference = $checkIn->diffInMinutes($shiftInTime);

                                                    $late = max(0, $difference - $graceTime);
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ @$attendance->employee->employementDetail->card_no }}</td>
                                                <td>{{ @$attendance->employee->full_name }}</td>
                                                <td>{{ $attendance->employee->employementDetail->designation->name ?? '' }}</td>
                                                <td> @if ($attendance->check_in_date && $attendance->check_in_time)
                                                        {{ date('M. d, Y, g:i A', strtotime($attendance->check_in_date . ' ' . $attendance->check_in_time)) }}</td>
                                                    @else
                                                        N/A
                                                    @endif</td>
                                                    <td>@if($attendance->check_out_date && $attendance->check_out_time)
                                                        {{ date('M. d, Y, g:i A', strtotime($attendance->check_out_date . ' ' . $attendance->check_out_time)) }}
                                                    @else
                                                        N/A
                                                    @endif</td>
                                                <td>{{ $late }}</td>
                                                <!-- Status Display (no changes needed) -->
                                                <td>
                                                    @php
                                                        $isLate = false;
                                                        if ($attendance->check_in_time) {
                                                            $shift = $attendance->shift ?? \Modules\HRMS\Models\Settings\Shift::find(10000);
                                                            if ($shift) {
                                                                $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                                $shiftInTime = \Carbon\Carbon::parse($shift->in_time);
                                                                $graceTime = $shift->grace_time ?? 0;
                                                                $isLate = $checkIn->diffInMinutes($shiftInTime) > $graceTime;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($isLate)
                                                        <span class="badge badge-round badge-warning">Late</span>
                                                    @elseif ($attendance->attendance_type == 'Present')
                                                        <span class="badge badge-round badge-success">{{ $attendance->attendance_type }}</span>
                                                    @else
                                                        <span class="badge badge-round badge-{{ $attendance->attendance_type == 'Absent' ? 'danger' : ($attendance->attendance_type == 'Holiday' ? 'info' : ($attendance->attendance_type == 'Leave' ? 'primary' : ($attendance->attendance_type == 'Weekend' ? 'secondary' : 'dark'))) }}">{{ $attendance->attendance_type }}</span>
                                                    @endif
                                                </td>
                                                
                                                <td>{{ @$attendance->entryBy->name }}</td>
                                                <td>{{ $attendance->remarks }}</td>
                                            </tr>
                                            @endforeach
                                            
                                            <tr>
                                                <td colspan="10">
                                                    <div class="d-flex justify-content-md-end justify-content-start">
                                                        Department Total: {{ count($attendances) }}

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                    
                                    <tfoot>
                                        <tr>
                                            <td colspan="10">
                                                <div class="d-flex justify-content-md-end justify-content-start">
                                                    Branch Total: {{ $departments->flatten()->count() }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @endforeach
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
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
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endSection
