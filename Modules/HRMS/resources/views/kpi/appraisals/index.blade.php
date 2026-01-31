@extends('layout.app')
@section('title', 'Appraisal List')
@section('description', 'List of Employee Appraisals')

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
                                <li class="breadcrumb-item active" aria-current="page">Appraisal List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('hrm.kpis.appraisals.create'))
                        <a href="{{ route('hrm.kpis.appraisals.create') }}" class="btn px-20 btn-primary btn-sm">
                            <i class="las la-plus fs-16"></i> Add New
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="row">
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('hrm.kpis.appraisals.index') }}">
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
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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

        <!-- Appraisal Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" 
                               data-page='@include("utils.table_paginate", ["data" => $appraisals])' 
                               style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee</th>
                                    <th>Increment (%)</th>
                                    <th>Increment (Amount)</th>
                                    <th>New Salary</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th class="no-content">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appraisals as $appraisal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $appraisal->employee->full_name ?? 'N/A' }}</td>
                                    <td>{{ $appraisal->increment_percent }}%</td>
                                    <td>{{ number_format($appraisal->increment_amount) }}</td>
                                    <td>{{ number_format($appraisal->new_salary) }}</td>
                                    <td>{{ $appraisal->remarks ?? '-' }}</td>
                                    <td>
                                        @if ($appraisal->status === 'draft')
                                            <span class="badge bg-warning badge-round text-dark">Draft</span>
                                        @elseif ($appraisal->status === 'submitted')
                                            <span class="badge badge-round bg-info">Submitted</span>
                                        @elseif ($appraisal->status === 'approved')
                                            <span class="badge badge-round bg-success">Approved</span>
                                        @elseif ($appraisal->status === 'rejected')
                                            <span class="badge badge-round bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if (hasPermission('hrm.kpis.appraisals.approve') && $appraisal->status === 'submitted')
                                            <a href="{{ route('hrm.kpis.appraisals.edit', $appraisal->id) }}?status=approved" class="btn btn-outline-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            @endif

                                        @if($appraisal->status === 'draft')
                                            @if (hasPermission('hrm.kpis.appraisals.update'))
                                            <a href="{{ route('hrm.kpis.appraisals.edit', $appraisal->id) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            @endif
                                            @if (hasPermission('hrm.kpis.appraisals.destroy'))
                                            <button type="button" class="btn btn-outline-danger delete-confirm" data-action="{{ route('hrm.kpis.appraisals.destroy', $appraisal->id) }}" title="Delete">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                            @endif
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
<script>
    $(".delete-confirm").on("click", function () {
        const url = $(this).data("action");

        Swal.fire({
            title: "Are you sure?",
            text: "This appraisal will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $(".delete-form");
                form.attr("action", url);
                form.submit();
            }
        });
    });
</script>
@endsection
