@extends('layout.app')

@section('title', 'KPI Assignment List')
@section('description', 'List of KPI Templates Assigned to Employees')

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
                                <li class="breadcrumb-item active" aria-current="page">KPI Assignment List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('hrm.kpis.kpi-assignments.create'))
                            <a href="{{ route('hrm.kpis.kpi-assignments.create') }}"
                                class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Assign New
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 m-2">
            <h4 class="text-capitalize breadcrumb-title">{{ __('KPI Template Assign to Employee List') }}</h4>
            <x-error-alart />
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%"  data-page='@include('utils.table_paginate', ['data' => $kpiTemplateAssignEmployees])'>
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Preparation Date</th>
                                    <th>Total Responsibilities</th>
                                    <th class="no-content">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpiTemplateAssignEmployees as $assignment)
                                    <tr>
                                        <td>{{ ($kpiTemplateAssignEmployees->currentPage() - 1) * $kpiTemplateAssignEmployees->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $assignment->employee->full_name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->employee->employementDetail->department->name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->employee->employementDetail->designation->name ?? 'N/A' }}</td>
                                        <td>{{ date('d M, Y', strtotime($assignment->start_date)) }}</td>
                                        <td>{{ date('d M, Y', strtotime($assignment->end_date)) }}</td>
                                        <td>{{ date('d M, Y', strtotime($assignment->preparation_date)) }}</td>
                                        <td>{{ $assignment->details->count() }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if (hasPermission('hrm.kpis.kpi-assignments.update'))
                                                    <a href="{{ route('hrm.kpis.kpi-assignments.edit', $assignment->id) }}"
                                                        class="btn btn-outline-warning" title="Edit">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('hrm.kpis.kpi-assignments.show'))
                                                    <a href="{{ route('hrm.kpis.kpi-assignments.show', $assignment->id) }}"
                                                        class="btn btn-outline-info" title="View">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('hrm.kpis.kpi-assignments.destroy'))
                                                    <button type="button" class="btn btn-outline-danger delete-confirm"
                                                        data-action="{{ route('hrm.kpis.kpi-assignments.destroy', $assignment->id) }}"
                                                        title="Delete">
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
