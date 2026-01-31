@extends('layout.app')

@section('title', 'KPI Template Details')
@section('description', 'View KPI Template Information')

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
                                    <li class="breadcrumb-item active" aria-current="page">KPI Template Details</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">

                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('hrm.kpis.kpi-templates.index'))
                                    <a href="{{ route('hrm.kpis.kpi-templates.index') }}"
                                        class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                        <i class="fa fa-list"></i> List
                                    </a>
                                @endif
                                @if (hasPermission('hrm.kpis.kpi-templates.edit'))
                                    <a href="{{ route('hrm.kpis.kpi-templates.edit', $kpiTemplate->id) }}"
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
                <h4 class="text-capitalize breadcrumb-title">KPI Template Details</h4>
            </div>

            <div class="card mb-50">
                <div class="card-body p-30">
                    <!-- Basic Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="fw-bold">Department</h6>
                            <p>{{ $kpiTemplate->department->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Designation</h6>
                            <p>{{ $kpiTemplate->designation->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Status</h6>
                            <span
                                class="badge badge-round bg-{{ $kpiTemplate->status == 'Active' ? 'success' : 'danger' }}">
                                {{ $kpiTemplate->status }}
                            </span>
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
                                    <th>Frequency (times of doing the job)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kpiTemplate->responsibilities as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->responsibilityEntry->code ?? '' }}</strong> -
                                            {{ $item->responsibilityEntry->description ?? '' }}
                                        </td>
                                        <td>{{ $item->weight }}</td>
                                        <td>{{ $item->time }}</td>
                                        <td>{{ $item->frequency }}</td>
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
                        <a href="{{ route('hrm.kpis.kpi-templates.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
