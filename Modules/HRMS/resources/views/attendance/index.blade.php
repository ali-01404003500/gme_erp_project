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
                                            <td width="20%" class="text-right">
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $attendances])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Shift</th>
                                        <th>Work Duration</th>
                                        <th>Attended Type</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $shift = \Modules\HRMS\Models\Settings\Shift::where('id', 10000)->first()
                                    @endphp

                                    @foreach ($attendances as $value)
                                        <tr>
                                            <td class="text-center">{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration  }}</td>
                                            <td>
                                            {{ @$value->employee->full_name }}
                                            </td>
                                            <td>{{ $value->date }}</td>
                                            <td>
                                                @if ($value->check_in_date && $value->check_in_time)
                                                    {{ date('M. d, Y, g:i A', strtotime($value->check_in_date . ' ' . $value->check_in_time)) }}</td>
                                                @else
                                                    N/A
                                                @endif
                                            <td>
                                                @if($value->check_out_date && $value->check_out_time)
                                                    {{ date('M. d, Y, g:i A', strtotime($value->check_out_date . ' ' . $value->check_out_time)) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->shift)
                                                {{ @$value->shift->shift_name }} ({{ date('h:i A', strtotime(@$value->shift->in_time)) }}-{{ date('h:i A', strtotime(@$value->shift->out_time)) }})
                                                @else
                                                 {{ @$shift->shift_name }} ({{ date('h:i A', strtotime(@$shift->in_time)) }}-{{ date('h:i A', strtotime(@$shift->out_time)) }})
                                                @endif
                                            </td>
                                            @php
                                                $work_duration = 'N/A';
                                                $late = 0;

                                                if($value->check_in_time && $value->check_out_time){
                                                    $checkIn = \Carbon\Carbon::parse($value->check_in_time);
                                                    $checkOut = \Carbon\Carbon::parse($value->check_out_time);
                                                    $work_duration_hours = $checkIn->diffInHours($checkOut);
                                                    $work_duration_minutes = $checkIn->diffInMinutes($checkOut) % 60;
                                                    $work_duration = $work_duration_hours . ' Hours ' . $work_duration_minutes . ' Minutes';

                                                    $shiftInTime = \Carbon\Carbon::parse($value->shift->in_time ?? $shift->in_time);
                                                    $graceTime = $value->shift->grace_time ?? $shift->grace_time;
                                                    $difference = $checkIn->diffInMinutes($shiftInTime);

                                                    $late = max(0, $difference - $graceTime);
                                                }
                                            @endphp
                                            <td> {{$work_duration}} </td> 

                                            <td>
                                                @if($late > 0) 
                                                <span class="badge badge-round badge-warning">
                                                    Late 
                                                </span>
                                                @elseif ($value->attendance_type == 'Present')
                                                <span class="badge badge-round badge-success">
                                                    {{ $value->attendance_type }}   
                                                </span>
                                                @elseif ($value->attendance_type == 'Absent')
                                                <span class="badge badge-round badge-danger">
                                                    {{ $value->attendance_type }}
                                                </span>
                                                @elseif ($value->attendance_type == 'Holiday')
                                                <span class="badge badge-round badge-info">
                                                    {{ $value->attendance_type }}
                                                </span>
                                                @elseif ($value->attendance_type == 'Leave')
                                                <span class="badge badge-round badge-primary">
                                                    {{ $value->attendance_type }}
                                                </span>
                                                @elseif ($value->attendance_type == 'Weekend')
                                                <span class="badge badge-round badge-secondary">
                                                    {{ $value->attendance_type }}
                                                </span>
                                                @else
                                                <span class="badge badge-round badge-dark">
                                                    {{ $value->attendance_type }}
                                                @endif
                                            </td>
                                                 
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                   
                                                    @if (hasPermission('hrm.attendances.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('hrm.attendances.edit', $value->id) }}" title="Edit"title="Edit">
                                                                <i class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('hrm.attendances.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('hrm.attendances.destroy', $value->id) }}"
                                                        class="btn btn-outline-danger delete-confirm" title="Delete" title="Delete"><i
                                                            class="far fa-trash-alt"></i></button>
                                                    @endif
                                                   
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
