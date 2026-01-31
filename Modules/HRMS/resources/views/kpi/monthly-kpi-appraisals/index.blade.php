@extends('layout.app')

@section('title', 'Monthly KPI Appraisals')
@section('description', 'List of Monthly KPI Appraisals')

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
                                    <li class="breadcrumb-item active" aria-current="page">Monthly KPI Appraisals</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.create'))
                                <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.create') }}"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-plus"></i> Create Appraisal
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-2">
                <h4 class="text-capitalize breadcrumb-title">Monthly KPI Appraisals</h4>
                <x-error-alart />
            </div>

            <div class="card mb-50">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $appraisals])'
                            style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Employee</th>
                                    <th>Assessment Month</th>
                                    <th>Performance Score</th>
                                    <th>Succession Score</th>
                                    <th>Behavioral Score</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appraisals as $appraisal)
                                    <tr>
                                        <td>{{ $loop->iteration + ($appraisals->currentPage() - 1) * $appraisals->perPage() }}
                                        </td>
                                        <td>{{ $appraisal->employee->full_name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appraisal->assessment_month)->format('F Y') }}</td>
                                        <td>{{ number_format($appraisal->achieved_performance_score) }}</td>
                                        <td>{{ number_format($appraisal->succession_management_score ?? 0) }}</td>
                                        <td>{{ number_format($appraisal->behavioral_performance_score ?? 0) }}</td>
                                        <td>
                                            @if ($appraisal->status == 'Draft')
                                                <span class="badge badge-round bg-secondary">Draft</span>
                                            @elseif($appraisal->status == 'Submitted')
                                                <span class="badge badge-round  bg-warning">Submitted</span>
                                            @elseif($appraisal->status == 'Approved')
                                                <span class="badge badge-round  bg-success">Approved</span>
                                            @elseif($appraisal->status == 'Rejected')
                                                <span class="badge badge-round  bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.show'))
                                                    <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.show', $appraisal->id) }}"
                                                        class="btn btn-outline-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (
                                                    (hasPermission('hrm.kpis.monthly-kpi-appraisals.update') && $appraisal->status == 'Draft') ||
                                                        $appraisal->status == 'Submitted')
                                                    <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.edit', $appraisal->id) }}"
                                                        class="btn btn-outline-warning" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.approve') && $appraisal->status == 'Submitted')
                                                    <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.approve', $appraisal->id) }}"
                                                        class="btn btn-xs btn-outline-success approval-confirm-appraisal"
                                                        data-action="{{ route('hrm.kpis.monthly-kpi-appraisals.approve', $appraisal->id) }}"
                                                        data-confirm-title="Approve appraisal?"
                                                        data-confirm-message="Are you sure you want to approve this appraisal?"
                                                        data-confirm-icon="success" data-confirm-text="Yes, Approve it!">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endif
                                                @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.reject') && $appraisal->status == 'Submitted')
                                                    <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.reject', $appraisal->id) }}"
                                                        class="btn btn-xs btn-outline-danger reject-confirm-appraisal"
                                                        data-action="{{ route('hrm.kpis.monthly-kpi-appraisals.reject', $appraisal->id) }}"
                                                        data-confirm-title="Reject appraisal?"
                                                        data-confirm-message="Are you sure you want to reject this appraisal?"
                                                        data-confirm-icon="warning" data-confirm-text="Yes, Reject it!">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                @endif


                                                @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.destroy') && $appraisal->status == 'Draft')
                                                    <button type="button" class="btn btn-outline-danger delete-confirm"
                                                        data-action="{{ route('hrm.kpis.monthly-kpi-appraisals.destroy', $appraisal->id) }}">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No appraisals found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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
@endsection
@section('page_scripts')
<script>
    function approvalConfirm(e) {
    e.preventDefault();
    e.stopPropagation();

    const el = $(this);
    const url = el.data("action");
    const confirmTitle = el.data("confirm-title") || "Are you sure?";
    const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
    const confirmIcon = el.data("confirm-icon") || "success";
    const confirmText = el.data("confirm-text") || "Yes, Approve it!";

    Swal.fire({
        title: confirmTitle,
        text: confirmMessage,
        icon: confirmIcon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function rejectConfirm(e) {
    e.preventDefault();
    e.stopPropagation();

    const el = $(this);
    const url = el.data("action");
    const confirmTitle = el.data("confirm-title") || "Are you sure?";
    const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
    const confirmIcon = el.data("confirm-icon") || "warning";
    const confirmText = el.data("confirm-text") || "Yes, Reject it!";

    Swal.fire({
        title: confirmTitle,
        text: confirmMessage,
        icon: confirmIcon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

$(document).ready(function () {
    $(".approval-confirm-appraisal").on("click", approvalConfirm);
    $(".reject-confirm-appraisal").on("click", rejectConfirm);
});

</script>

@endsection