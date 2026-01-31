@section('title', 'Monthly Attendance Report')
@section('description', 'Monthly Attendance Report')
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
                                        {{ trans('Monthly Attendance Report') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                <a href="{{ route('hrm.reports.monthly-attendance-report') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('hrm.reports.monthly-attendance-report') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Monthly Attendance Report') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                           <div class="filter-section mb-4">
                                <form method="GET" action="{{ route('hrm.reports.monthly-attendance-report') }}">
                                    <div class="row">
                                        <table class="table table-bordered">
                                            <tr>
                                                
                                                <td>
                                                    <select name="branch[]" class="form-control multi-select" multiple>
                                                        <option value="">Select Branch</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}"  {{ collect(request('branch'))->contains($branch->id) ? 'selected' : '' }}>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                           
                                                <td>
                                                    <select name="department[]" class="form-control multi-select" multiple>
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ collect(request('department'))->contains($department->id) ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                           
                                                <td>
                                                    <select name="designation[]" class="form-control multi-select" multiple>
                                                        <option value="">Select Designation</option>
                                                        @foreach($designations as $designation)
                                                            <option value="{{ $designation->id }}" {{ collect(request('designation'))->contains($designation->id) ? 'selected' : '' }}>
                                                                {{ $designation->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="employee_id[]" id="employee_id" class="multi-select form-control" multiple
                                                        data-placeholder="Select Employee">
                                                        <option value=""></option>
                                                        @foreach ($employees as $key => $value)
                                                            <option {{ collect(request('employee_id'))->contains($value->id) ? 'selected' : '' }}
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
                                               
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control datePicker" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control datePicker" name="to"
                                                            value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                    </div>
                                                </td>
                                                <td colspan="3" class="text-right">
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
                                $groupedData = $groupedStats->groupBy(fn($item) =>
                                    optional(optional($item['employee']->employementDetail)->branch)->name ?? 'N/A'
                                )->map(function ($items) {
                                    return $items->groupBy(fn($item) =>
                                        optional(optional($item['employee']->employementDetail)->department)->name ?? 'N/A'
                                    );
                                });
                            @endphp

                            @foreach($groupedData as $branchName => $departments)
                                <table class="table table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Branch: {{ $branchName }}</th>
                                        </tr>
                                        <tr>
                                            <th>No.</th>
                                            <th>Emp ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Present Days</th>
                                            <th>Absent Days</th>
                                            <th>Late Days</th>
                                            <th>Leave Days</th>
                                            <th>Holiday Days</th>
                                            <th>Total Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $departmentName => $employees)
                                            <tr>
                                                <td colspan="10">Department: {{ $departmentName }}</td>
                                            </tr>
                                            @foreach($employees as $index => $report)
                                                @php
                                                    $employee = $report['employee'];
                                                    $employment = $employee->employementDetail;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $employment->card_no ?? 'N/A' }}</td>
                                                    <td>{{ $employee->full_name }}</td>
                                                    <td>{{ $employment->designation->name ?? 'N/A' }}</td>
                                                    <td>{{ $report['present_days'] }}</td>
                                                    <td>{{ $report['absent_days'] }}</td>
                                                    <td>{{ $report['late_days'] }}</td>
                                                    <td>{{ $report['leave_days'] }}</td>
                                                    <td>{{ $report['holy_days'] }}</td>
                                                    <td>{{ $report['total_days'] + $report['late_days'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
       $(".multi-select").each(function () {
            new TomSelect(this, {
                    plugins: ['remove_button'],
            });
        });
        
       
    </script>
      <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endSection
