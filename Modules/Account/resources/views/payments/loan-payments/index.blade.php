@extends('layout.app')
@section('title', 'Loan Payment List')
@section('description', 'List of Employee Loans or Advances')

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
                                    <li class="breadcrumb-item active" aria-current="page">Loan/Advance List</li>
                                </ol>
                            </nav>
                        </div>
                    
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="row">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('hrm.loans.index') }}">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="width: 50%">
                                                <select name="employee_id" class="form-control tom-select">
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}"
                                                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                            {{ @$employee->employementDetail->card_no }}:
                                                            {{ $employee->full_name }}
                                                            ({{ @$employee->employementDetail->designation->name }})
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
            </div>

            <!-- Loan/Advance Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $loans])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Employee</th>
                                        <th>Amount</th>
                                        <th>Duration (Months)</th>
                                        <th>Monthly Reduction</th>
                                        <th>Remaining Balance</th>
                                        <th>Start From</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th class="no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td class="text-center">{{ ($loans->currentPage() - 1) * $loans->perPage() + $loop->iteration  }}</td>
                                            <td>{{ $loan->employee->full_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($loan->amount) }}</td>
                                            <td>{{ $loan->duration }}</td>
                                            <td>{{ number_format($loan->monthly_reduction) }}</td>
                                            <td>{{ number_format($loan->remaining_balance) }}</td>
                                            <td>{{ $loan->start_month }}</td>
                                            <td>{{ $loan->payment_date ? \Carbon\Carbon::parse($loan->payment_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                @if ($loan->status == 'pending')
                                                    <span class="badge badge-round  badge-warning">Pending</span>
                                                @elseif ($loan->status == 'approved')
                                                    <span class="badge badge-round  badge-success">Approved</span>
                                                @elseif ($loan->status == 'processing')
                                                    <span class="badge badge-round  badge-success">Processing</span>
                                                @elseif ($loan->status == 'verify deny')
                                                    <span class="badge badge-round  badge-danger">Verify Deny</span>
                                                @elseif ($loan->status == 'deny')
                                                    <span class="badge badge-round badge-danger">Denied</span>
                                                @elseif ($loan->status == 'paid')
                                                    <span class="badge badge-round badge-primary">Paid</span>
                                                @else
                                                    <span class="badge badge-round  badge-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if (hasPermission('account.payments.loan-payment.payment') && $loan->status == 'approved')
                                                        <a href="{{ route('account.payments.loan-payment.payment', $loan->id) }}"
                                                            class="btn btn-xs btn-outline-success approval-confirm-loan"
                                                            data-action="{{ route('account.payments.loan-payment.payment', $loan->id) }}"
                                                            data-confirm-title="Payment Loan?"
                                                            data-confirm-message="Are you sure you want to payment this Loan?"
                                                            data-confirm-icon="success"
                                                            data-confirm-text="Yes, Payment it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                
                                                
                                                    @if (hasPermission('hrm.loans.show'))
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-primary view-loan-details"
                                                            data-id="{{ $loan->id }}" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
    <!-- Modal HTML -->
    <div class="modal fade" id="loanDetailModal" tabindex="-1" role="dialog" aria-labelledby="loanDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="loanDetailModalLabel">
                        <i class="fas fa-eye"></i> View Loan List Details
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="loanModalContent">
                    {{-- Will be injected by AJAX --}}
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('page_scripts')
    <script>
    $(document).on('click', '.view-loan-details', function () {
        const loanId = $(this).data('id');
        const modal = $('#loanDetailModal');
        const modalContent = $('#loanModalContent');

        modalContent.html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');

        $.get("{{ url('hrm/loans/ajax-details') }}/" + loanId, function (data) {
            modalContent.html(data);
            modal.modal('show');
        }).fail(function () {
            modalContent.html('<div class="text-danger text-center">Failed to load loan details.</div>');
        });
    });
    </script>

    <script>
        $(".delete-confirm").on("click", function() {
            const url = $(this).data("action");

            Swal.fire({
                title: "Are you sure?",
                text: "This record will be permanently deleted.",
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

        $(document).ready(function() {
            $(".approval-confirm-loan").on("click", approvalConfirm);
            $(".reject-confirm-loan").on("click", rejectConfirm);
        });
    </script>
@endsection
