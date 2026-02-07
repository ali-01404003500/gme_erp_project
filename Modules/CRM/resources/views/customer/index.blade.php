@section('title', 'Customer List')
@section('description', 'Customer List')
@extends('layout.app')

@section('content')
    <style>
        /* Modern Mesh Gradient Background */
        body {
            background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 212, 255, 0.12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(95, 99, 242, 0.08) 0px, transparent 50%),
                #f8fafc !important;
            min-height: 100vh;
        }

        /* Vertical Title Aesthetic */
        /* .vertical-title {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-weight: 800;
            color: #1e293b;
            border-left: 4px solid #5f63f2;
            padding-right: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 1.1rem;
            margin-top: 20px;
            display: flex;
            align-items: center;
        } */

        /* Glassmorphism Card Style */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.7) !important;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            border-radius: 16px !important;
        }

        /* BOLD & MEDIUM-BIG Table Headers */
        .table thead th {
            background-color: rgba(95, 99, 242, 0.08) !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            font-size: 0.95rem !important;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #5f63f2 !important;
            padding: 18px 15px !important;
            vertical-align: middle;
            text-align: center;
        }

        .table-bordered {
            border: 2px solid #e2e8f0 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 2px solid #e2e8f0 !important;
            /* Internal cell borders */
        }

        .table tbody td {
            padding: 15px !important;
            vertical-align: middle !important;
            color: #334155;
            background: transparent;
        }

        .table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Action Buttons Group (Document Entry Style) */
        .action-btn-group .btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            margin: 0 3px;
            border-radius: 8px !important;
            transition: all 0.2s;
            padding: 6px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn-group .btn:hover {
            background: #5f63f2;
            color: white !important;
            border-color: #5f63f2;
            transform: translateY(-2px);
        }

        /* Filter Area */
        .filter-card {
            background: rgba(255, 255, 255, 0.6) !important;
        }

        .form-control,
        .tom-select {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* Status Badges */
        .badge-round {
            padding: 6px 12px !important;
            border-radius: 8px !important;
            font-weight: 700;
            font-size: 0.75rem;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="social-dash-wrap">
            {{-- Header Section --}}
            <div class="row align-items-center mb-4">
                <div class="col-lg-12">
                    <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                        <div class="breadcrumb-action d-flex align-items-start gap-4">
                            {{-- <h4 class="vertical-title">{{ trans('Customers') }}</h4> --}}
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="#" class="text-muted"><i class="las la-home"></i>
                                            Home</a></li>
                                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                                        {{ trans('Customer List') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="breadcrumb-main__wrapper mt-sm-0 mt-3">
                            <div class="action-btn d-flex align-items-center gap-2">
                                @if (hasPermission('crm.customers.create'))
                                    <a href="{{ route('crm.customers.create') }}"
                                        class="btn btn-primary btn-sm px-4 shadow-sm border-0"
                                        style="border-radius: 10px; background: linear-gradient(90deg, #5f63f2, #7928ca);">
                                        <i class="las la-plus fs-16 me-1"></i> Add New
                                    </a>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-white btn-sm d-flex align-items-center border shadow-sm px-3"
                                    style="border-radius: 10px; background: white;">
                                    <i class="las la-file-pdf fs-18 mr-1 text-danger"></i> PDF
                                </a>
                                <button class="btn btn-white btn-sm d-flex align-items-center border shadow-sm px-3"
                                    data-bs-toggle="modal" data-bs-target="#importModal"
                                    style="border-radius: 10px; background: white;">
                                    <i class="las la-file-import fs-18 mr-1 text-success"></i> Import CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card filter-card border-0">
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row align-items-end g-3">
                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label fw-bold text-muted small">NAME</label>
                                        <select name="company_name" id="company_name" class="form-control tom-select"
                                            data-placeholder="Select Customer">
                                            <option value=""></option>
                                            @foreach ($customersearch as $value)
                                                <option {{ request('company_name') == $value->company_name ? 'selected' : '' }}
                                                    value="{{ $value->company_name }}">{{ $value->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label fw-bold text-muted small">PHONE</label>
                                        <input type="text" class="form-control" name="phone" value="{{ request('phone') }}"
                                            placeholder="Phone..." autocomplete="off">
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label fw-bold text-muted small">EMAIL</label>
                                        <input type="text" class="form-control" name="email" value="{{ request('email') }}"
                                            placeholder="Email..." autocomplete="off">
                                    </div>
                                    <div class="col-lg-3 col-md-6 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary px-4 w-100" style="height: 44px;"><i
                                                class="fa fa-search me-2"></i>Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-light border px-4 w-100"
                                            style="height: 44px; display: flex; align-items: center; justify-content: center;"><i
                                                class="fa fa-refresh me-2"></i>Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="row">
                <div class="col-md-12">
                    <x-error-alart />
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="table-responsive table-container">
                                <table id="zero-config" class="table table-bordered mb-0"
                                    data-page='@include('utils.table_paginate', ['data' => $customers])'>
                                    <thead>
                                        <tr>
                                            <th width="50">SL</th>
                                            <th>ID</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Company Place</th>
                                            <th>Phone Number</th>
                                            <th>Customer Type</th>
                                            <th>Status</th>
                                            <th width="200">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td class="text-center fw-bold text-muted small">
                                                    {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('crm.customers.show', $customer->id) }}"
                                                        class="  text-primary border">
                                                        {{ $customer->customer_id }}
                                                    </a>
                                                </td>
                                                <td class="fw-bold text-dark">{{ $customer->company_name }}</td>
                                                <td class="small text-muted">{{ $customer->address }}</td>
                                                <td class="text-center">{{ $customer->area?->area }}</td>
                                                <td class="text-center fw-600">{{ $customer->phone }}</td>
                                                <td class="text-center"><span
                                                        class="  text-info border px-2 py-1 small">{{ optional($customer->customerType)->name }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClasses = [1 => 'warning', 2 => 'success', 0 => 'danger'];
                                                        $statusLabels = [1 => 'Pending', 2 => 'Active', 0 => 'Deny'];
                                                        $class = $statusClasses[$customer->status] ?? 'secondary';
                                                        $label = $statusLabels[$customer->status] ?? 'Unknown';
                                                    @endphp
                                                    <span class="badge badge-round badge-{{ $class }}">{{ $label }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-btn-group shadow-sm">
                                                        @if(hasPermission('crm.customers.approve') && $customer->status == 1)
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm approval-confirm-customer"
                                                                data-action="{{ route('crm.customers.approve', $customer->id) }}"
                                                                data-confirm-title="Approve Customer?"
                                                                data-confirm-message="Are you sure you want to approve this customer?"
                                                                data-confirm-icon="success" data-confirm-text="Yes, Approve it!"
                                                                title="Approve"><i class="fas fa-check text-success"></i></a>
                                                            <a href="javascript:void(0)" class="btn btn-sm reject-confirm-customer"
                                                                data-action="{{ route('crm.customers.deny', $customer->id) }}"
                                                                data-confirm-title="Reject Customer?"
                                                                data-confirm-message="Are you sure you want to reject this customer?"
                                                                data-confirm-icon="warning" data-confirm-text="Yes, Reject it!"
                                                                title="Reject"><i class="fas fa-times text-danger"></i></a>
                                                        @endif
                                                        @if (hasPermission('crm.customers.update'))
                                                            <a class="btn btn-sm"
                                                                href="{{ route('crm.customers.edit', $customer->id) }}"
                                                                title="Edit"><i class="lar la-edit text-warning"></i></a>
                                                        @endif
                                                        @if (hasPermission('crm.customers.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('crm.customers.destroy', $customer->id) }}"
                                                                class="btn btn-sm delete-confirm " title="Delete"><i
                                                                    class="lar la-trash-alt text-danger"></i></button>
                                                        @endif
                                                        @if (hasPermission('crm.customers.show'))
                                                            <a class="btn btn-sm"
                                                                href="{{ route('crm.customers.show', $customer->id) }}"
                                                                title="View"><i class="fas fa-eye text-primary"></i></a>
                                                        @endif
                                                        @if (hasPermission('crm.customers.settings'))
                                                            <a class="btn btn-sm"
                                                                href="{{ route('crm.customers.settings', $customer->id) }}"
                                                                title="Settings"><i class="fas fa-cog text-info"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">@csrf @method('DELETE')</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">Import from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('crm.customers-insert') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">UPLOAD CSV FILE</label>
                            <input type="file" name="csv_file" class="form-control shadow-sm" required>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('crm.customers-download') }}" class="btn btn-info btn-sm text-white px-4"
                                style="border-radius: 8px;"><i class="las la-download me-1"></i>Download Sample CSV</a>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Process
                            Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        function approvalConfirm(e) {
            e.preventDefault();
            const el = $(this);
            const url = el.data("action");
            Swal.fire({
                title: el.data("confirm-title"),
                text: el.data("confirm-message"),
                icon: el.data("confirm-icon"),
                showCancelButton: true,
                confirmButtonColor: "#5f63f2",
                cancelButtonColor: "#d33",
                confirmButtonText: el.data("confirm-text")
            }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
        }

        function rejectConfirm(e) {
            e.preventDefault();
            const el = $(this);
            const url = el.data("action");
            Swal.fire({
                title: el.data("confirm-title"),
                text: el.data("confirm-message"),
                icon: el.data("confirm-icon"),
                showCancelButton: true,
                confirmButtonColor: "#5f63f2",
                cancelButtonColor: "#d33",
                confirmButtonText: el.data("confirm-text")
            }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
        }

        $(document).ready(function () {
            $(".approval-confirm-customer").on("click", approvalConfirm);
            $(".reject-confirm-customer").on("click", rejectConfirm);
        });
    </script>
@endsection