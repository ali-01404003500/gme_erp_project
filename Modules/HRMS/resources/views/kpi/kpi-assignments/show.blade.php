@extends('layout.app')

@section('title', 'KPI Assignment Details')
@section('description', 'View KPI Assignment Information')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="las la-home"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">KPI Assignment Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('hrm.kpis.kpi-assignments.index'))
                                <a href="{{ route('hrm.kpis.kpi-assignments.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                            @if (hasPermission('hrm.kpis.kpi-assignments.edit'))
                                <a href="{{ route('hrm.kpis.kpi-assignments.edit', $kpiTemplateAssignEmployee->id) }}"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 m-2">
            <h4 class="text-capitalize breadcrumb-title">KPI Template Assign to Employee Details</h4>
        </div>

        <div class="card mb-50">
            <div class="card-body p-30">
                <!-- Employee Info -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6 class="fw-bold">Employee</h6>
                        <p>{{ $kpiTemplateAssignEmployee->employee->full_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">Department</h6>
                        <p>{{ $kpiTemplateAssignEmployee->employee->employementDetail->department->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">Designation</h6>
                        <p>{{ $kpiTemplateAssignEmployee->employee->employementDetail->designation->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6 class="fw-bold">Supervisor</h6>
                        <p>{{ $kpiTemplateAssignEmployee->employee->employementDetail->supervisorName->full_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">Executing Duration</h6>
                        <p>{{ $kpiTemplateAssignEmployee->start_date }} - {{ $kpiTemplateAssignEmployee->end_date }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">Preparation Date</h6>
                        <p>{{ $kpiTemplateAssignEmployee->preparation_date }}</p>
                    </div>
                </div>

                <!-- Responsibilities Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="responsibilities-table">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Key Areas of Responsibility (KAR)</th>
                                <th>Weight (Out of 100)</th>
                                <th>Target Days</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kpiTemplateAssignEmployee->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->responsibility->code ?? '' }}</strong> -
                                        {{ $detail->responsibility->description ?? '' }}
                                    </td>
                                    <td>{{ $detail->weight }}</td>
                                    <td>{{ $detail->time }}</td>
                                    <td>{{ $detail->frequency }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No responsibilities assigned.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Button -->
                <div class="button-group d-flex justify-content-end pt-25">
                    <a href="{{ route('hrm.kpis.kpi-assignments.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
