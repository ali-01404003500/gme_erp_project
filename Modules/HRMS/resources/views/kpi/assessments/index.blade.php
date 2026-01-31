@extends('layout.app')
@section('title', 'KPI Assessments')
@section('description', 'Employee KPI Assessment List')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">KPI Assessments</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('hrm.kpis.assessments.create'))
                                <a href="{{ route('hrm.kpis.assessments.create') }}" class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Header -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h4 class="text-capitalize breadcrumb-title">Assessment List</h4>
                    <x-error-alart />
                </div>

                 <div class="col-md-12 my-4">
                    <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td style="width: 50%">
                                                    <select name="employee_id" class="form-control tom-select">
                                                        <option value="">-- Select Employee --</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}"
                                                                {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                                {{ @$employee->employementDetail->card_no }}: {{ $employee->full_name }} ({{ @$employee->employementDetail->designation->name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td colspan="5" class="text-right">
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


                <!-- Assessment Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                   data-page='@include("utils.table_paginate", ["data" => $assessments])'
                                   style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Employee</th>
                                        <th>Period</th>
                                        <th>Total Weight</th>
                                        <th>Total Mark</th>
                                        <th>Overall Score</th>
                                        <th>Status</th>
                                        <th class="no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assessments as $assessment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $assessment->employee->full_name ?? 'N/A' }}</td>
                                            <td>{{ $assessment->from_date }} to {{ $assessment->to_date }}</td>
                                            <td>{{ $assessment->total_weight }}</td>
                                            <td>{{ $assessment->total_mark }}</td>
                                            <td>{{ $assessment->overall_score }}%</td>
                                            <td>
                                                @if ($assessment->status == 'draft')
                                                    <span class="badge bg-warning badge-round text-dark">Draft</span>
                                                @elseif ($assessment->status == 'submitted')
                                                    <span class="badge badge-round bg-success">Submitted</span>
                                          
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    
                                                    @if (hasPermission('hrm.kpis.assessments.update'))
                                                        <a href="{{ route('hrm.kpis.assessments.edit', $assessment->id) }}"
                                                           class="btn btn-outline-warning" title="Edit"><i class="far fa-edit"></i></a>
                                                    @endif
                                                    @if (hasPermission('hrm.kpis.assessments.destroy'))
                                                        <button type="button" class="btn btn-outline-danger delete-confirm"
                                                                data-action="{{ route('hrm.kpis.assessments.destroy', $assessment->id) }}">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Hidden Delete Form -->
                            <div class="d-none">
                                <form class="delete-form" method="POST" action="">
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

@endsection
