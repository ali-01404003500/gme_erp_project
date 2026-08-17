@extends('layout.app')

@section('title', 'Loan Collection')
@section('description', 'Loan Collection')

@section('page-head')

<style>

    .summary-card {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        background: #fff;
    }

    .summary-title {
        font-size: 13px;
        color: #777;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 600;
    }

    .amount-link {
        cursor: pointer;
        font-weight: 600;
    }

    .custom-modal {
        max-width: 60%;
    }

</style>

@endsection


@section('content')

<div class="container-fluid">

    <div class="social-dash-wrap">


        {{-- Breadcrumb --}}

        <div class="row">

            <div class="col-lg-12">

                <div class="breadcrumb-main">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="/">
                                    <i class="las la-home"></i>
                                    Home
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Loan Collection
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- Title --}}

        <div class="row">

            <div class="col-md-12"
                 style="padding-bottom:20px">

                <h4 class="breadcrumb-title">
                    Loan Collection
                </h4>

            </div>

        </div>


        {{-- Summary --}}

        <div class="row">

            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Pending Collection
                    </div>

                    <div class="summary-value">

                        {{ number_format($pendingAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Submitted
                    </div>

                    <div class="summary-value">

                        {{ number_format($submittedAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Checked
                    </div>

                    <div class="summary-value">

                        {{ number_format($checkedAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Approved
                    </div>

                    <div class="summary-value">

                        {{ number_format($approvedAmount, 2) }}

                    </div>

                </div>

            </div>

        </div>


        <br>


        {{-- Filter --}}

        <div class="card">

            <div class="card-body">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-4">

                            <label>
                                Employee
                            </label>

                            <select name="employee_id"
                                    class="form-control tom-select">

                                <option value="">
                                    All Employees
                                </option>

                                @foreach($employees as $employee)

                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>

                                        {{ $employee->full_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>
                        <div class="col-md-2">

                            <label>Month</label>

                            <input type="month"
                                name="month"
                                class="form-control"
                                value="{{ request('month', $month) }}">

                        </div>


                        <div class="col-md-2">

                            <label>
                                Status
                            </label>

                            <select name="status" class="form-control">
                                <option value=""> All </option>
                                <option value="pending"  {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>
                                    Submitted
                                </option>
                                <option value="checked" {{ request('status') == 'checked' ? 'selected' : '' }}>
                                    Checked
                                </option>
                                <option value="approved"  {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 pt-3" >
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Search
                                </button>

                                <a href="{{ request()->url() }}" class="btn btn-warning">
                                    <i class="fa fa-refresh"></i> Refresh
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>


        {{-- Collection Table --}}

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"> SL </th>
                                <th>Employee</th>
                                <th>Installment  </th>
                                <th>Due Date</th>
                                <th class="text-right">Amount  </th>
                                <th class="text-right"> Paid</th>
                                <th>Status </th>
                                <th class="text-center"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $payment->employee->full_name ?? '' }}
                                    </td>
                                    <td>
                                        #{{ $payment->installment_no }}
                                    </td>
                                    <td>
                                        {{ $payment->due_date?->format('Y-m-d') }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($payment->paid_amount, 2) }}
                                    </td>
                                    <td>
                                        @if($payment->status == 'pending')
                                            <span class="px-1 badge-warning">
                                                Pending
                                            </span>
                                        @elseif($payment->status == 'submitted')
                                            <span class="px-1 badge-info">
                                                Submitted
                                            </span>
                                        @elseif($payment->status == 'checked')
                                            <span class="px-1 badge-primary">
                                                Checked
                                            </span>
                                        @elseif($payment->status == 'approved')
                                            <span class="px-1 badge-success">
                                                Approved
                                            </span>
                                        @elseif($payment->status == 'paid')
                                            <span class="px-1 badge-success">
                                                Paid
                                            </span>
                                        @elseif($payment->status == 'rejected')
                                            <span class="px-1 badge-danger">
                                                Rejected
                                            </span> 
                                        
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{-- Pending --}}
                                        @if($payment->status == 'pending')
                                            <button type="button" class="btn btn-xs btn-primary collect-btn"
                                                    data-id="{{ $payment->id }}"
                                                    data-employee="{{ $payment->employee->full_name ?? '' }}"
                                                    data-installment="{{ $payment->installment_no }}"
                                                    data-due-date="{{ $payment->due_date?->format('Y-m-d') }}"
                                                    data-amount="{{ $payment->amount }}"
                                                    data-paid="{{ $payment->paid_amount }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Collect
                                            </button>

                                        {{-- Submitted --}} 
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        No loan collection found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




{{-- Collection Modal (EMI-style rich payment modal) --}}

<div class="modal fade"
     id="collectionModal"
     tabindex="-1"
     aria-labelledby="collectionModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-xl custom-modal"
         role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="collectionModalLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>Loan Collection
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        id="closeCollectionModal">
                </button>

            </div>

            <div class="modal-body">

                <form id="collectionForm">

                    @csrf

                    <input type="hidden" id="modal-loan-payment-id" name="loan_payment_id">
                    <input type="hidden" id="modal-due-amount" name="due_amount" value="0">

                    {{-- Loan Installment Info --}}
                    <div class="details-section mb-4 border p-3">

                        <h6 class="mb-3 text-primary">Installment Information</h6>

                        <div class="row">

                            <div class="col-md-3 mb-2">
                                <label class="form-label">Employee</label>
                                <input type="text" id="modal_employee" class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label">Installment</label>
                                <input type="text" id="modal_installment" class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label">Due Date</label>
                                <input type="text" id="modal_due_date" class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label">Due Amount</label>
                                <input type="text" id="modal_due_amount" class="form-control" readonly>
                            </div>

                        </div>

                    </div>

                    {{-- Payment Section --}}
                    <div class="payment-section table-responsive border p-3">

                        <h6 class="mb-3 text-primary">
                            <i class="fas fa-credit-card me-2"></i>Payment Details
                        </h6>

                        <div class="row mb-3">

                            <div class="col-md-3 mb-2">
                                <label for="input-pay-mode" class="form-label">
                                    Pay Mode <span class="text-danger">*</span>
                                </label>
                                <select id="input-pay-mode" class="form-select tom-select">
                                    <option value="">Select pay mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online Deposit">Online Deposit</option>
                                    <option value="bKash">bKash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Card Payment">Card Payment</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2 pay-field account-field d-none">
                                <label for="input-account" class="form-label">Account</label>
                                <select id="input-account" class="form-select tom-select">
                                    <option value="">Select Account</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2 pay-field bank-field d-none">
                                <label for="input-bank" class="form-label">Bank</label>
                                <select id="input-bank" class="form-select tom-select">
                                    <option value="">Select Bank</option>
                                    @php
                                        if (!isset($banks) || !$banks->count()) {
                                            $banks = \Modules\Account\Models\Bank::all();
                                        }
                                    @endphp
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-2 pay-field branch-field d-none">
                                <label for="input-branch" class="form-label">Branch</label>
                                <select id="input-branch" class="form-select tom-select">
                                    <option value="">Select branch</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2 pay-field txn-field d-none">
                                <label for="input-txn" class="form-label">Transaction ID</label>
                                <input type="text" id="input-txn" class="form-control" placeholder="Transaction ID">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="input-date" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="input-date" value="{{ date('Y-m-d') }}" class="form-control flatdate">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="input-amount" class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="input-amount" class="form-control" placeholder="Amount" step="0.01" min="0">
                            </div>

                            <div class="col-md-3">
                                <label for="input-file" class="form-label">File</label>
                                <input type="file" id="input-file" class="form-control">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-9 mb-2">
                                <label for="input-remark" class="form-label">
                                    Remark <span class="text-danger">*</span>
                                </label>
                                <textarea id="input-remark" class="form-control" rows="2" placeholder="Enter remark here"></textarea>
                            </div>

                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="button" id="add-payment" class="btn btn-success w-100">
                                    <i class="fa fa-plus"></i> Add Payment
                                </button>
                            </div>

                        </div>

                        {{-- Payment Table --}}
                        <table class="table table-bordered" id="payment-table">

                            <thead class="table-light">
                                <tr>
                                    <th>Pay Mode</th>
                                    <th>Collection Point (Bank)</th>
                                    <th>Number (Branch)</th>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>File</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="payment-body"></tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Total:</td>
                                    <td colspan="3" class="fw-bold text-primary" id="total-display">
                                        ৳ <span>0.00</span>
                                        <input type="hidden" name="payments_total_amount" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Payable:</td>
                                    <td colspan="3" class="fw-bold text-primary" id="total-payable">
                                        ৳ <span>0.00</span>
                                        <input type="hidden" name="payments_payable_amount" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Due:</td>
                                    <td colspan="3" class="fw-bold text-danger" id="total-due">
                                        ৳ <span>0.00</span>
                                        <input type="hidden" name="payments_due_amount" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Advance:</td>
                                    <td colspan="3" class="fw-bold text-success" id="total-advance">
                                        ৳ <span>0.00</span>
                                        <input type="hidden" name="payments_advance_amount" value="0.00">
                                    </td>
                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        id="closeCollectionModalFooter">
                    <i class="fas fa-times me-2"></i>Close
                </button>

                <button type="button" class="btn btn-success" id="save-collection">
                    <i class="fas fa-save me-2"></i>Save Collection
                </button>

            </div>

        </div>

    </div>

</div>

{{-- Image Preview Modal --}}
<div class="modal fade" id="full-screen-modal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullScreenModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="Payment Image" class="img-fluid" id="full-screen-image">
            </div>
        </div>
    </div>
</div>

@endsection



@section('page_scripts')

<script>

    $(document).ready(function () {

        // Init TomSelect
        initializeTomSelect();

        // Init date picker
        $('.flatdate').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        // Open modal from "Collect" button
        $(document).on('click', '.collect-btn', function () {

            let id = $(this).data('id');
            let employee = $(this).data('employee');
            let installment = $(this).data('installment');
            let dueDate = $(this).data('due-date');
            let amount = parseFloat($(this).data('amount')) || 0;
            let paid = parseFloat($(this).data('paid')) || 0;
            let dueAmount = amount - paid;

            $('#modal-loan-payment-id').val(id);
            $('#modal_employee').val(employee);
            $('#modal_installment').val('#' + installment);
            $('#modal_due_date').val(dueDate);
            $('#modal_due_amount').val(numberFormat(dueAmount));
            $('#modal-due-amount').val(dueAmount);

            updatePayable(dueAmount);
            resetModalInputs();

            $('#collectionModal').modal('show');
        });

        // Reset on modal close
        $('#collectionModal').on('hidden.bs.modal', function () {
            resetModalInputs();
            $('#payment-body').empty();
            updateTotal();
        });

        // Pay mode change
        $('#input-pay-mode').on('change', function () {
            const paymentMode = $(this).val();
            const accountSelect = $('#input-account').prop('tomselect');
            accountSelect.clear();
            accountSelect.clearOptions();
            accountSelect.addOption({ value: '', text: 'Select Account' });

            if (paymentMode) {
                $.ajax({
                    url: '{{ route('account.account-setup.bank-accounts.get-accounts') }}',
                    type: 'GET',
                    data: { payment_mode: paymentMode },
                    success: function (response) {
                        if (response && response.length) {
                            response.forEach(account => {
                                accountSelect.addOption({ value: account.id, text: account.account_name });
                            });
                            if (response.length === 1) {
                                accountSelect.setValue(response[0].id);
                            }
                        }
                    },
                    error: function () {
                        toastr.error('Failed to load accounts.');
                    }
                });
            }
            toggleFormFields(paymentMode);
        });

        // Bank change
        $('#input-bank').on('change', function () {
            const bankVal = $(this).val();
            const branchSelect = $('#input-branch').prop('tomselect');
            branchSelect.clear();
            branchSelect.clearOptions();
            branchSelect.addOption({ value: '', text: 'Select branch' });

            if (bankVal) {
                $.ajax({
                    url: '{{ route('account.account-setup.ajax.bank-branches') }}',
                    method: 'GET',
                    data: { bank_id: bankVal },
                    success: function (data) {
                        data.forEach(branch => {
                            branchSelect.addOption({ value: branch.id, text: branch.name });
                        });
                    },
                    error: function () {
                        toastr.error('Failed to load branches.');
                    }
                });
            }
        });

        // Add payment
        $('#add-payment').on('click', function (e) {
            e.preventDefault();
            addPaymentRow();
        });

        // Remove row
        $(document).on('click', '.remove-row', function () {
            const row = $(this).closest('tr');
            if (row.find('.attachments').val()) {
                deleteFile(row.find('.attachments').val());
            }
            row.remove();
            updateTotal();
        });

        // Save collection
        $('#save-collection').on('click', function () {
            if (validateForm()) {
                saveLoanCollection();
            }
        });

    });

    // TomSelect init
    function initializeTomSelect() {
        ['#input-pay-mode', '#input-account', '#input-bank', '#input-branch'].forEach(selector => {
            if ($(selector).length && !$(selector).prop('tomselect')) {
                new TomSelect(selector, {
                    create: false,
                    sortField: { field: 'text', direction: 'asc' }
                });
            }
        });
    }

    // Toggle form fields based on payment mode
    function toggleFormFields(type) {
        const container = $('#collectionModal');
        container.find('.pay-field').addClass('d-none');
        const txnId = '#input-txn';
        const txnField = container.find('.txn-field');

        switch (type) {
            case 'Cash':
            case 'Online Deposit':
                container.find('.account-field').removeClass('d-none');
                break;
            case 'Cheque':
                container.find('.bank-field, .branch-field, .txn-field').removeClass('d-none');
                txnField.find('.form-label').text('Cheque No');
                $(txnId).attr('placeholder', 'Cheque Number');
                break;
            case 'bKash':
            case 'Nagad':
            case 'Rocket':
            case 'Card Payment':
                container.find('.account-field, .txn-field').removeClass('d-none');
                txnField.find('.form-label').text('Transaction ID');
                $(txnId).attr('placeholder', 'Transaction ID');
                break;
        }
    }

    // Add a payment row
    function addPaymentRow() {
        const payMode = $('#input-pay-mode').val();
        const bankName = $('#input-bank option:selected').text() || '';
        const bankId = $('#input-bank').val() || '';
        const accountName = $('#input-account option:selected').text() || '';
        const accountId = $('#input-account').val() || '';
        const branchName = $('#input-branch option:selected').text() || '';
        const branchId = $('#input-branch').val() || '';
        const txn = $('#input-txn').val() || '';
        const date = $('#input-date').val();
        const amount = parseFloat($('#input-amount').val()) || 0;
        const fileInput = $('#input-file')[0];
        const file = fileInput.files[0];
        const remark = $('#input-remark').val();

        if (!payMode || !date || amount <= 0 || !remark) {
            toastr.error('Please fill all required fields correctly.');
            return;
        }

        const payableAmount = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
        const currentTotal = parseFloat($('input[name="payments_total_amount"]').val()) || 0;

        if (currentTotal + amount > payableAmount) {
            $('#input-amount').val(payableAmount - currentTotal);
            toastr.error(`Payment exceeds payable. Max: ৳${(payableAmount - currentTotal).toFixed(2)}`);
            return;
        }

        const row = $(`
            <tr>
                <td>${payMode}<input type="hidden" name="payments_pay_mode[]" value="${payMode}"></td>
                <td>${accountId ? accountName : bankName}<input type="hidden" name="payments_bank_id[]" value="${accountId || bankId}"></td>
                <td>${branchName}<input type="hidden" name="payments_branch_id[]" value="${branchId}"></td>
                <td>${txn}<input type="hidden" name="payments_transaction_id[]" value="${txn}"></td>
                <td>${date}<input type="hidden" name="payments_date[]" value="${date}"></td>
                <td class="amount-value">${numberFormat(amount)}<input type="hidden" name="payments_amount[]" value="${amount}"></td>
                <td>
                    <span class="file_name"></span>
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <input type="hidden" name="payments_attachments[]" class="attachments">
                    <input type="hidden" name="payments_verified[]" class="verified" value="0">
                </td>
                <td>${remark}<input type="hidden" name="payments_remark[]" value="${remark}"></td>
                <td><button type="button" class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>
        `);

        $('#payment-body').append(row);

        if (file) {
            uploadFile(file).then(res => {
                row.find('.spinner-border').hide();
                if (res.path) {
                    row.find('.attachments').val(res.path);
                    row.find('.file_name').html(`<button type="button" onclick="showImage('${res.path}')" class="btn btn-outline-primary btn-sm"><i class="fa fa-eye"></i> preview</button>`);
                }
            }).catch(() => {
                row.find('.spinner-border').hide();
                toastr.error('File upload failed.');
            });
        } else {
            row.find('.spinner-border').hide();
        }

        updateTotal();
        resetModalInputs();
    }

    // Reset modal inputs (keeps info section untouched)
    function resetModalInputs() {
        const payModeSelect = $('#input-pay-mode').prop('tomselect');
        const accountSelect = $('#input-account').prop('tomselect');
        const bankSelect = $('#input-bank').prop('tomselect');
        const branchSelect = $('#input-branch').prop('tomselect');

        if (payModeSelect) { payModeSelect.clear(); payModeSelect.sync(); }
        if (accountSelect) {
            accountSelect.clear();
            accountSelect.clearOptions();
            accountSelect.addOption({ value: '', text: 'Select Account' });
            accountSelect.sync();
        }
        if (bankSelect) { bankSelect.clear(); bankSelect.sync(); }
        if (branchSelect) {
            branchSelect.clear();
            branchSelect.clearOptions();
            branchSelect.addOption({ value: '', text: 'Select branch' });
            branchSelect.sync();
        }

        $('#input-txn').val('');
        $('#input-date').val(new Date().toISOString().split('T')[0]);
        $('#input-amount').val('');
        $('#input-file').val('');
        $('#input-remark').val('');

        $('#collectionModal').find('.pay-field').addClass('d-none');
    }

    // Update total
    function updateTotal() {
        let total = 0;
        $('#payment-body .amount-value').each(function () {
            total += parseFloat($(this).text().replace(/,/g, '')) || 0;
        });
        $('#total-display span').text(numberFormat(total));
        $('input[name="payments_total_amount"]').val(total);
        updateDue();
    }

    // Update due / advance
    function updateDue() {
        const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
        const total = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
        const difference = total - payable;

        if (difference > 0) {
            $('#total-due span').text('0.00');
            $('input[name="payments_due_amount"]').val(0);
            $('#total-advance span').text(numberFormat(difference));
            $('input[name="payments_advance_amount"]').val(difference);
        } else {
            $('#total-due span').text(numberFormat(Math.abs(difference)));
            $('input[name="payments_due_amount"]').val(Math.abs(difference));
            $('#total-advance span').text('0.00');
            $('input[name="payments_advance_amount"]').val(0);
        }
    }

    // Update payable amount
    function updatePayable(payable) {
        $('#total-payable span').text(numberFormat(payable));
        $('input[name="payments_payable_amount"]').val(payable);
        updateDue();
    }

    // Validate before save
    function validateForm() {
        const rows = $('#payment-body tr').length;
        if (rows === 0) {
            toastr.error('Add at least one payment.');
            return false;
        }
        return true;
    }

    // Save Loan Collection
    function saveLoanCollection() {

        const formData = new FormData();

        formData.append('_token', $('input[name="_token"]').val());
        formData.append('loan_payment_id', $('#modal-loan-payment-id').val());
        formData.append('due_amount', $('#modal-due-amount').val());

        formData.append('payments_total_amount', $('input[name="payments_total_amount"]').val());
        formData.append('payments_payable_amount', $('input[name="payments_payable_amount"]').val());
        formData.append('payments_due_amount', $('input[name="payments_due_amount"]').val());
        formData.append('payments_advance_amount', $('input[name="payments_advance_amount"]').val());

        $('#payment-body tr').each(function (index, row) {
            const $row = $(row);
            formData.append(`payments[${index}][pay_mode]`, $row.find('input[name="payments_pay_mode[]"]').val());
            formData.append(`payments[${index}][bank_id]`, $row.find('input[name="payments_bank_id[]"]').val());
            formData.append(`payments[${index}][branch_id]`, $row.find('input[name="payments_branch_id[]"]').val());
            formData.append(`payments[${index}][transaction_id]`, $row.find('input[name="payments_transaction_id[]"]').val());
            formData.append(`payments[${index}][date]`, $row.find('input[name="payments_date[]"]').val());
            formData.append(`payments[${index}][amount]`, $row.find('input[name="payments_amount[]"]').val());
            formData.append(`payments[${index}][attachments]`, $row.find('input[name="payments_attachments[]"]').val());
            formData.append(`payments[${index}][remark]`, $row.find('input[name="payments_remark[]"]').val());
        });

        let id = $('#modal-loan-payment-id').val();
        let url = "{{ route('account.loan-collections.collect', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Loan collection saved successfully!');
                    $('#collectionModal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Error saving loan collection.');
                }
            },
            error: function (xhr) {
                console.error('Loan Collection Error:', xhr.responseText);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('Validation failed. Please check your inputs.');
                    }
                } else {
                    toastr.error('Error saving loan collection. Please try again.');
                }
            }
        });
    }

    // Show image preview
    function showImage(url) {
        $('#full-screen-image').attr('src', url);
        $('#full-screen-modal').modal('show');
    }

    // Number formatting (2 decimals)
    function numberFormat(number) {
        number = parseFloat(number);
        if (isNaN(number)) number = 0;
        return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // File upload
    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('file', file);
        const response = await fetch("{{ route('upload_file') }}", {
            method: 'POST',
            body: formData
        });
        if (response.ok) {
            toastr.success("File uploaded successfully");
        }
        return await response.json();
    }

    // File delete
    async function deleteFile(url) {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        if (data.message) {
            toastr.success(data.message);
        }
        return data;
    }

</script>

@endsection