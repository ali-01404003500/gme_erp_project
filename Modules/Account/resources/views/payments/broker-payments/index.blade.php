@extends('layout.app')
@section('title', 'Broker Payments List')
@section('description', 'Broker Payments List')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Broker Payments List') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.payments.broker-payments.create'))
                                <a href="{{ route('account.payments.broker-payments.create') }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Payment Create
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Broker Payments List') }}</h4>
                </div>
            </div>

            <!-- Table Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $brokerPayments])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Broker</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Bank Info</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sl = 1; @endphp
                                    @foreach ($brokerPayments as $payment)
                                        <tr>
                                            <td class="text-center">{{ ($brokerPayments->currentPage() - 1) * $brokerPayments->perPage() + $loop->iteration  }}</td>
                                            <td>{{ optional($payment->salesCommission->broker)->broker_name }}</td>
                                            <td>{{ $payment->salesCommission->salesOrder->customer->company_name ?? '-' }}
                                            </td>
                                            <td>{{ numberFormat($payment->payment_amount) }}</td>
                                            <td>
                                                {{ optional($payment->brokerPaymentBank)->account_nos }}
                                                - {{ optional($payment->brokerPaymentBank)->bank_name }}
                                            </td>
                                            <td>{{ $payment->remarks }}</td>
                                            <td>
                                                @if ($payment->status == 'pending')
                                                    <span class="badge badge-round bg-warning">Pending</span>
                                                @elseif($payment->status == 'Verified')
                                                    <span class="badge badge-round bg-info">Verified</span>
                                                @elseif($payment->status == 'Approved')
                                                    <span class="badge badge-round bg-success">Approved</span>
                                                @elseif($payment->status == 'Rejected')
                                                    <span class="badge badge-round bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">

                                                    @if (hasPermission('account.payments.broker-payments.verify') && $payment->status == 'pending')
                                                        <a href="{{ route('account.payments.broker-payments.verify', $payment->id) }}"
                                                            title="Verify"
                                                            class="btn btn-xs btn-outline-success verify-confirm-payment"
                                                            data-action="{{ route('account.payments.broker-payments.verify', $payment->id) }}"
                                                            data-confirm-title="Verify Approval?"
                                                            data-confirm-message="Are you sure you want to Checked this?"
                                                            data-confirm-icon="success"
                                                            data-confirm-text="Yes, Approve it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="{{ route('account.payments.broker-payments.deny', $payment->id) }}"
                                                            class="btn btn-xs btn-outline-danger reject-confirm-payment"
                                                            title="Reject"
                                                            data-action="{{ route('account.payments.broker-payments.deny', $payment->id) }}"
                                                            data-confirm-title="Reject Payment?"
                                                            data-confirm-message="Are you sure you want to reject this?"
                                                            data-confirm-icon="warning" data-confirm-text="Yes, Reject it!">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('account.payments.broker-payments.approve') && $payment->status == 'Verified')
                                                        <a href="{{ route('account.payments.broker-payments.approve', $payment->id) }}"
                                                            title="Approver"
                                                            class="btn btn-xs btn-outline-success approval-confirm-payment"
                                                            data-action="{{ route('account.payments.broker-payments.approve', $payment->id) }}"
                                                            data-confirm-title="Approve Approval?"
                                                            data-confirm-message="Are you sure you want to approve this?"
                                                            data-confirm-icon="success"
                                                            data-confirm-text="Yes, Approve it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="{{ route('account.payments.broker-payments.deny', $payment->id) }}"
                                                            class="btn btn-xs btn-outline-danger reject-confirm-payment"
                                                            title="Reject"
                                                            data-action="{{ route('account.payments.broker-payments.deny', $payment->id) }}"
                                                            data-confirm-title="Reject Payment?"
                                                            data-confirm-message="Are you sure you want to reject this?"
                                                            data-confirm-icon="warning" data-confirm-text="Yes, Reject it!">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    @endif
                                                    @if ($payment->status == 'Approved')
                                                        <i class="fa fa-check-circle text-success"></i>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

        function verifyConfirm(e) {
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
            $(".verify-confirm-payment").on("click", verifyConfirm);
            $(".approval-confirm-payment").on("click", approvalConfirm);
            $(".reject-confirm-payment").on("click", rejectConfirm);
        });
    </script>
@endsection
