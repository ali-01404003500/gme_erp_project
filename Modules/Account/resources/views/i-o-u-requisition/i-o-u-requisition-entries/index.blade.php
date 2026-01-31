<!-- resources/views/i-o-u-requisition/i-o-u-requisition-entries/index.blade.php -->
@section('title', 'IOU Requisition List')
@section('description', 'List of IOU requisitions')

@extends('layout.app')
@section('page-head')
    <style>
    .otp-input-container {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin: 20px 0;
    }

    .otp-input {
        width: 48px;
        height: 48px;
        padding: 4px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        transition: border-color 0.3s ease;
    }

    .otp-input:focus {
        outline: none;
        border-color: #007bff;
    }

    .otp-input-container .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.iou-requisition-list') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.create') }}" class="btn btn-primary btn-sm px-3">
                                <i class="las la-plus"></i> {{ trans('menu.create-iou-requisition') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.iou-requisition-list') }}</h4>
            </div>

            <!-- Filters -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%">
                                            <input type="text" name="from_to" class="form-control flatdaterange" value="{{ request('from_to') }}" placeholder="Date Range">
                                        </td>
                                        <td width="20%">
                                            <select name="type" class="form-control tom-select" data-placeholder="Type">
                                                <option value=""></option>
                                                <option value="Expense" {{ request('type') == 'Expense' ? 'selected' : '' }}>Expense</option>
                                                <option value="Advance" {{ request('type') == 'Advance' ? 'selected' : '' }}>Advance</option>
                                            </select>
                                        </td>
                                        <td width="20%">
                                            <select name="status" class="form-control tom-select" data-placeholder="Status">
                                                <option value=""></option>
                                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Denied" {{ request('status') == 'Denied' ? 'selected' : '' }}>Denied</option>
                                                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                                            </select>
                                        </td>
                                        <td width="40%" class="text-right">
                                            <div class="btn-group btn-corner">
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Reset</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table class="table dt-table-hover" data-page='@include("utils.table_paginate", ["data" => $iOURequisitionEntrys])' id="zero-config">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Employee Name</th>
                                    <th>Type</th>
                                    <th>Remarks</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($iOURequisitionEntrys as $entry)
                                    <tr>
                                        <td>{{ ($iOURequisitionEntrys->currentPage() - 1) * $iOURequisitionEntrys->perPage() + $loop->iteration }}</td>
                                        <td>{{ $entry->i_o_u_requition_entry_id }}</td>
                                        <td>{{ $entry->date->format('d M, Y') }}</td>
                                        <td>{{ $entry->employee->full_name }}</td>
                                        <td><span class="badge badge-round bg-primary">{{ $entry->type }}</span></td>
                                        <td>{{ Str::limit($entry->remarks, 50) }}</td>
                                        <td>৳{{ number_format($entry->request_amount) }}</td>
                                        <td>
                                            <span class="badge badge-round badge-{{
                                                match($entry->status){
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'verified' => 'info',
                                                    'denied' => 'danger',
                                                    default => 'secondary',
                                                } }} badge-lg">
                                                {{ $entry->status }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Small button group">
                                                @if ($entry->status != 'approved' && $entry->status != 'paid' && $entry->status != 'returned' && hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.update'))
                                                    <a class="btn btn-outline-warning"
                                                        href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.edit', $entry->id) }}"
                                                        data-bs-toggle="tooltip" title="Update">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if ($entry->status == 'pending' && hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.verify'))
                                                    <a class="btn btn-outline-info"
                                                        href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.edit', $entry->id) }}?form_type=verify"
                                                        data-bs-toggle="tooltip" title="Verify">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                @endif

                                                @if ($entry->status == 'verified' && hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.approve'))
                                                    <a class="btn btn-outline-success"
                                                        href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.edit', $entry->id) }}?form_type=approve"
                                                        data-bs-toggle="tooltip" title="Approve">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                @endif
                                                
                                                @if (hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.show'))
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.show', $entry->id) }}"
                                                        data-bs-toggle="tooltip" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if ($entry->status != 'approved' && $entry->status != 'paid' && $entry->status != 'returned' && hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.destroy', $entry->id) }}"
                                                        class="btn btn-outline-danger delete-confirm"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                @endif

                                                @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.pay') && $entry->status === 'approved')
                                                    <button type="button"
                                                            class="btn btn-outline-success pay-button"
                                                            data-id="{{ $entry->id }}"
                                                            data-employee-name="{{ $entry->employee->full_name }}"
                                                            data-amount="{{ $entry->request_amount }}"
                                                            data-employee-id="{{ $entry->employee_id }}"
                                                            data-bs-toggle="tooltip" title="Pay">
                                                        <i class="fa fa-money-bill-wave"></i>
                                                    </button>
                                                @endif

                                                @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.return') && $entry->status === 'paid')
                                                    <button type="button"
                                                            class="btn btn-outline-danger return-button"
                                                            data-id="{{ $entry->id }}"
                                                            data-employee-name="{{ $entry->employee->full_name }}"
                                                            data-amount="{{ $entry->request_amount }}"
                                                            data-employee-id="{{ $entry->employee_id }}"
                                                            data-bs-toggle="tooltip" title="Return">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.show', $entry) }}" class="btn btn-outline-info" data-bs-toggle="tooltip" title="View"><i class="las la-eye"></i></a>
                                                @if($entry->status === 'Pending' && ($entry->employee_id == auth()->user()->employee_id || hasPermission('iou.update')))
                                                    <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.edit', $entry) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Edit"><i class="las la-pen"></i></a>
                                                @endif
                                                @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.delete') && $entry->status !== 'approved')
                                                    <button type="button" class="btn btn-outline-danger delete-confirm" data-action="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.destroy', $entry) }}" data-bs-toggle="tooltip" title="Delete"><i class="las la-trash"></i></button>
                                                @endif
                                                @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.pay') && $entry->status === 'approved')
                                                    <button type="button"
                                                            class="btn btn-outline-success pay-button"
                                                            data-id="{{ $entry->id }}"
                                                            data-employee-name="{{ $entry->employee->full_name }}"
                                                            data-amount="{{ $entry->request_amount }}"
                                                            data-employee-id="{{ $entry->employee_id }}">
                                                        <i class="fa fa-money-bill-wave"></i>
                                                    </button>
                                                @endif

                                            </div>
                                        </td> --}}
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

@push("modals")
<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h5 >IOU Request Details</h5>
                    <table class="table table-sm table-bordered mt-2">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span id="employeeName"></span></td>
                                <td>৳<span id="paymentAmount"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="otpSection" style="display: none;">
                    <h6 class="text-center mb-3">Enter OTP</h6>
                    <div class="d-flex justify-content-center">
                        <div class="otp-input-container">
                            <input type="text" class="form-control text-center otp-input" id="otp1" maxlength="1" placeholder="">
                            <input type="text" class="form-control text-center otp-input" id="otp2" maxlength="1" placeholder="">
                            <input type="text" class="form-control text-center otp-input" id="otp3" maxlength="1" placeholder="">
                            <input type="text" class="form-control text-center otp-input" id="otp4" maxlength="1" placeholder="">
                            <input type="text" class="form-control text-center otp-input" id="otp5" maxlength="1" placeholder="">
                            <input type="text" class="form-control text-center otp-input" id="otp6" maxlength="1" placeholder="">
                        </div>
                    </div>
                    <p class="text-center mt-2 text-muted" id="otpTimer" style="display: none;">Resend OTP in <span id="countdown">60</span>s</p>
                </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <div class="text-center" id="paySection">
                    <button type="button" class="btn btn-primary" id="sendOtpBtn">
                        <i class="fa fa-envelope"></i> Send OTP to Employee
                    </button>
                    <button type="button" class="btn btn-success d-none" id="verifyOtpBtn">
                        <i class="fa fa-check"></i> Verify OTP
                    </button>
                </div>

                <div class="text-center d-none" id="confirmPaySection">
                    <button type="button" class="btn btn-success" id="confirmPayBtn">
                        <i class="fa fa-credit-card"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">IOU Bill Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6>IOU Request Details</h6>
                    <table class="table table-sm table-bordered mt-2">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span id="returnEmployeeName"></span></td>
                                <td>৳<span id="returnAmount"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form id="returnForm">
                    <div class="form-group mb-3">
                        <label for="returnBankAccount" class="form-label">Select Bank Account</label>
                        <select id="returnBankAccount" name="bank_account_id" class="form-control tom-select" required>
                            <option value="">Select Bank Account</option>
                            @foreach($bankAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->account_name }} - {{ $account->bankBranch->branch_name ?? '' }} ({{ $account->bank->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="returnRemarks" class="form-label">Return Remarks</label>
                        <textarea id="returnRemarks" name="remarks" class="form-control" rows="3" placeholder="Enter return remarks"></textarea>
                    </div>

                    <input type="hidden" id="returnEntryId" name="entry_id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReturnBtn">
                    <i class="fa fa-undo"></i> Confirm Return
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection

@section('page_scripts')
<!-- Payment Modal JavaScript -->
<script>
let currentEntryId = null;
let otpTimer = null;
let countdownInterval = null;

$(document).ready(function() {
    // Handle pay button click
    $('.pay-button').on('click', function() {
        currentEntryId = $(this).data('id');
        const employeeName = $(this).data('employee-name');
        const amount = $(this).data('amount');
        const employeeId = $(this).data('employee-id');

        $('#employeeName').text(employeeName);
        $('#paymentAmount').text(amount);

        // Reset modal state
        resetModalState();

        $('#paymentModal').modal('show');
    });

    // Handle send OTP button
    $('#sendOtpBtn').on('click', function() {
        const button = $(this);
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

        $.ajax({
            url: '{{ route("account.i-o-u-requisition.i-o-u-requisition-entries.send-otp") }}',
            method: 'POST',
            data: {
                entry_id: currentEntryId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                button.prop('disabled', false).html('<i class="fa fa-envelope"></i> Send OTP to Employee');

                if (response.success) {
                    showSuccessToast('OTP sent successfully!');
                    showOtpSection();
                    startOtpTimer();
                } else {
                    showErrorToast(response.message || 'Failed to send OTP');
                }
            },
            error: function(xhr) {
                button.prop('disabled', false).html('<i class="fa fa-envelope"></i> Send OTP to Employee');
                showErrorToast('Error sending OTP');
            }
        });
    });

    // Handle OTP input fields
    $('.otp-input').on('input', function() {
        const value = $(this).val();
        if (value.length === 1) {
            const next = $(this).next('.otp-input');
            if (next.length) {
                next.focus();
            }
        }

        // Enable verify button if all fields are filled
        const allFilled = $('.otp-input').toArray().every(input => $(input).val().length === 1);
        $('#verifyOtpBtn').toggleClass('d-none', !allFilled);
    });

    // Handle backspace in OTP fields
    $('.otp-input').on('keydown', function(e) {
        if (e.key === 'Backspace' && !$(this).val()) {
            const prev = $(this).prev('.otp-input');
            if (prev.length) {
                prev.focus();
            }
        }
    });

    // Handle verify OTP button
    $('#verifyOtpBtn').on('click', function() {
        const otp = $('.otp-input').map(function() {
            return $(this).val();
        }).get().join('');

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying...');

        $.ajax({
            url: '{{ route("account.i-o-u-requisition.i-o-u-requisition-entries.verify-otp") }}',
            method: 'POST',
            data: {
                entry_id: currentEntryId,
                otp: otp,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#verifyOtpBtn').prop('disabled', false).html('<i class="fa fa-check"></i> Verify OTP');

                if (response.success) {
                    showSuccessToast('OTP verified successfully!');
                    showPaymentSection();
                    $('#confirmPayBtn').data('otp', otp);
                    $('#confirmPayBtn').prop('disabled', false).html('<i class="fa fa-credit-card"></i> Confirm Payment');
                    $('#verifyOtpBtn').addClass('d-none');
                } else {
                    showErrorToast(response.message || 'Invalid OTP');
                    resetOtpFields();
                }
            },
            error: function(xhr) {
                $('#verifyOtpBtn').prop('disabled', false).html('<i class="fa fa-check"></i> Verify OTP');
                showErrorToast('Error verifying OTP');
                resetOtpFields();
            }
        });
    });

    // Handle confirm payment button
    $('#confirmPayBtn').on('click', function() {
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        const otp = $(this).data('otp');

        $.ajax({
            url: '{{ route("account.i-o-u-requisition.i-o-u-requisition-entries.confirm-payment") }}',
            method: 'POST',
            data: {
                entry_id: currentEntryId,
                otp: otp,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#confirmPayBtn').prop('disabled', false).html('<i class="fa fa-credit-card"></i> Confirm Payment');

                if (response.success) {
                    showSuccessToast('Payment completed successfully!');
                    $('#paymentModal').modal('hide');
                    location.reload(); // Refresh to show updated status
                } else {
                    showErrorToast(response.message || 'Payment failed');
                    resetModalState();
                }
            },
            error: function(xhr) {
                $('#confirmPayBtn').prop('disabled', false).html('<i class="fa fa-credit-card"></i> Confirm Payment');
                showErrorToast('Error processing payment');
                resetModalState();
            }
        });
    });
});

function resetModalState() {
    $('#otpSection').hide();
    $('#paySection').show();
    $('#confirmPaySection').addClass('d-none');
    $('#sendOtpBtn').removeClass('d-none');
    $('#verifyOtpBtn').addClass('d-none');
    $('#confirmPayBtn').removeClass('d-none').prop('disabled', false);
    resetOtpFields();
    stopOtpTimer();
}

function showOtpSection() {
    $('#otpSection').show();
    $('#sendOtpBtn').addClass('d-none');
}

function showPaymentSection() {
    $('#confirmPaySection').removeClass('d-none');
    $('#otpSection').hide();
}

function resetOtpFields() {
    $('.otp-input').val('');
    $('#otp1').focus();
    $('#verifyOtpBtn').addClass('d-none');
}

function startOtpTimer() {
    clearInterval(countdownInterval);
    let countdown = 120;
    $('#countdown').text(countdown);
    $('#otpTimer').show();

    countdownInterval = setInterval(function() {
        countdown--;
        $('#countdown').text(countdown);

        if (countdown <= 0) {
            stopOtpTimer();
            $('#sendOtpBtn').removeClass('d-none');
        }
    }, 1000);
}

function stopOtpTimer() {
    clearInterval(countdownInterval);
    $('#otpTimer').hide();
}

function showSuccessToast(message) {
    toastr.success(message);
}

function showErrorToast(message) {
    toastr.error(message);
}

// Return Modal JavaScript
$(document).ready(function() {
    let currentReturnEntryId = null;

    // Handle return button click
    $('.return-button').on('click', function() {
        currentReturnEntryId = $(this).data('id');
        const employeeName = $(this).data('employee-name');
        const amount = $(this).data('amount');

        $('#returnEmployeeName').text(employeeName);
        $('#returnAmount').text(amount);
        $('#returnEntryId').val(currentReturnEntryId);

        $('#returnModal').modal('show');
    });

    // Handle confirm return button
    $('#confirmReturnBtn').on('click', function() {
        const bankAccountId = $('#returnBankAccount').val();
        const remarks = $('#returnRemarks').val();

        if (!bankAccountId) {
            showErrorToast('Please select a bank account');
            return;
        }

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing Return...');

        $.ajax({
            url: '{{ route("account.i-o-u-requisition.i-o-u-requisition-entries.return") }}',
            method: 'POST',
            data: {
                entry_id: currentReturnEntryId,
                bank_account_id: bankAccountId,
                remarks: remarks,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#confirmReturnBtn').prop('disabled', false).html('<i class="fa fa-undo"></i> Confirm Return');

                if (response.success) {
                    showSuccessToast('IOU return processed successfully!');
                    $('#returnModal').modal('hide');
                    location.reload(); // Refresh to show updated status
                } else {
                    showErrorToast(response.message || 'Return failed');
                }
            },
            error: function(xhr) {
                $('#confirmReturnBtn').prop('disabled', false).html('<i class="fa fa-undo"></i> Confirm Return');
                showErrorToast('Error processing return');
            }
        });
    });
});
</script>
@endsection