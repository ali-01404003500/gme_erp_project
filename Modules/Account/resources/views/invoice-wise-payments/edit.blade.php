@extends('layout.app')
@section('title', "Edit Invoice Wise Payment")
@section('description', "Edit Invoice Wise Payment")
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
                                <li class="breadcrumb-item"><a href="{{ route('account.payments.invoice-wise-payments.index') }}">Invoice Wise Payments</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Edit Payment') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-12 mt-4">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit Invoice Wise Payment') }}</h4>
                    </div>
                    <div class="col-md-12 mt-4">
                        <x-error-alart />
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.payments.invoice-wise-payments.update', $payment->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('Payment ID') }}</label>
                                        <input type="text" class="form-control" value="{{ $payment->invoice_wise_payment_id }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('Payment Type') }}</label>
                                        <input type="text" class="form-control" value="{{ $payment->payment_to_type === 'Modules\Purchase\Models\Supplier' ? 'Supplier' : 'Vendor' }}" readonly>
                                        <input type="hidden" name="payment_to_type" value="{{ $payment->payment_to_type === 'Modules\Purchase\Models\Supplier' ? 'supplier' : 'vendor' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('Payment To') }}</label>
                                        <input type="text" class="form-control" value="{{ $paymentTo->company_name }}" readonly>
                                        <input type="hidden" name="payment_to_id" value="{{ $payment->payment_to_id }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>{{ trans('Invoice List') }}</h5>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="select-all-invoices">
                                            </th>
                                            <th>{{ trans('Invoice Date') }}</th>
                                            <th>{{ trans('Invoice No.') }}</th>
                                            <th>{{ trans('Invoice Amount') }}</th>
                                            <th>{{ trans('Paid') }}</th>
                                            <th>{{ trans('Due Amount') }}</th>
                                            <th>{{ trans('Pay Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paymentTo->dueInvoices as $invoice)
                                        @php
                                            $isSelected = in_array($invoice->id, $paymentInvoiceIds);
                                            $pivotAmount = 0;
                                            if ($isSelected) {
                                                $pivotRecord = $payment->invoices->where('invoice_id', $invoice->id)->first();
                                                $pivotAmount = $pivotRecord ? $pivotRecord->amount : 0;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" 
                                                    class="invoice-checkbox" data-due="{{ $invoice->due_amount }}" 
                                                    {{ $isSelected ? 'checked' : '' }}>
                                                <input type="hidden" name="invoice_types[]" value="{{ $invoice->invoice_type }}">
                                            </td>
                                            <td>{{ $invoice->date ?? $invoice->invoice_date }}</td>
                                            <td>{{ $invoice->invoice_no ?? $invoice->requisition_no }}</td>
                                            <td>৳ {{ number_format($invoice->net_amount) }}</td>
                                            <td>৳ {{ number_format($invoice->paid_amount) }}</td>
                                            <td>৳ {{ number_format($invoice->due_amount) }}</td>
                                            <td>
                                                <input type="number" name="pay_amount[]" class="form-control pay-amount" 
                                                    step="0.01" min="0" max="{{ $invoice->due_amount }}" 
                                                    value="{{ $isSelected ? $pivotAmount : 0 }}" 
                                                    {{ $isSelected ? '' : 'disabled' }}>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="5" class="text-right">{{ trans('Total:') }}</th>
                                            <th id="total-due-amount">৳ 0.00</th>
                                            <th id="total-pay-amount">৳ 0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-md-12 my-2">
                                <h4>{{ trans('Payment Information') }}</h4>
                            </div>
                            <div class="col-md-12">
                                @include('Account::payments.make-payments.payments-details', ['payments' => $payment->payments])
                            </div>

                            <div class="col-md-12 mt-4">
                                <input type="hidden" name="status" id="status" value="{{ $payment->status }}">
                                <div class="form-group gap-2 d-flex justify-content-end">
                                    @if($payment->status == 'pending')
                                        <button type="submit" class="btn btn-primary" id="save">{{ trans('Update') }}</button>
                                        @if(request('form_type') == 'verify' && hasPermission('account.payments.invoice-wise-payments.verify'))
                                            <button type="submit" class="btn btn-warning" id="save-verify">{{ trans('Verify') }}</button>
                                            <button type="submit" class="btn btn-danger" id="save-denied">{{ trans('Deny') }}</button>

                                        @endif
                                    @elseif($payment->status == 'verified')
                                        @if(request('form_type') == 'approve' && hasPermission('account.payments.invoice-wise-payments.approve'))
                                            <button type="submit" class="btn btn-success" id="save-approved">{{ trans('Approve') }}</button>
                                            <button type="submit" class="btn btn-danger" id="save-denied">{{ trans('Deny') }}</button>
                                        @else
                                            <button type="submit" class="btn btn-primary" id="save">{{ trans('Update') }}</button>
                                        @endif
                                    @endif
                                    <a href="{{ route('account.payments.invoice-wise-payments.index') }}" class="btn btn-secondary">{{ trans('Cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
$(document).ready(function () {
    $('#save').click(function () {
        $("#status").val("pending");
    });

    $('#save-verify').click(function () {
        $("#status").val("verified");
    });

    $('#save-approved').click(function () {
        $("#status").val("approved");
    });

    $('#save-denied').click(function () {
        $("#status").val("denied");
    });

    // Select All Invoices Checkbox
    $('#select-all-invoices').change(function() {
        var isChecked = $(this).is(':checked');
        $('.invoice-checkbox').each(function() {
            $(this).prop('checked', isChecked);
            var payAmountInput = $(this).closest('tr').find('.pay-amount');
            if (isChecked) {
                payAmountInput.prop('disabled', false);
                var currentVal = parseFloat(payAmountInput.val()) || 0;
                if (currentVal == 0) {
                    payAmountInput.val($(this).data('due'));
                }
            } else {
                payAmountInput.prop('disabled', true);
                payAmountInput.val(0);
            }
        });
        updateTotals();
        updatePayable();
    });

    // Handle checkbox change
    $('.invoice-checkbox').change(function () {
        var payAmountInput = $(this).closest('tr').find('.pay-amount');
        if ($(this).is(':checked')) {
            payAmountInput.prop('disabled', false);
            var currentVal = parseFloat(payAmountInput.val()) || 0;
            if (currentVal == 0) {
                payAmountInput.val($(this).data('due'));
            }
        } else {
            payAmountInput.prop('disabled', true);
            payAmountInput.val(0);
        }
        
        // Update select all checkbox
        var allChecked = $('.invoice-checkbox:checked').length === $('.invoice-checkbox').length;
        $('#select-all-invoices').prop('checked', allChecked);
        
        updateTotals();
        updatePayable();
    });

    // Handle pay amount change
    $('.pay-amount').on('input', function () {
        var maxAmount = parseFloat($(this).attr('max'));
        var enteredAmount = parseFloat($(this).val()) || 0;
        
        if (enteredAmount > maxAmount) {
            toastr.error('Pay amount cannot exceed due amount of ৳' + maxAmount.toFixed());
            $(this).val(maxAmount);
        }
        
        updateTotals();
        updatePayable();
    });

    function updateTotals() {
        var totalDue = 0;
        var totalPay = 0;
        
        $('.invoice-checkbox:checked').each(function () {
            var row = $(this).closest('tr');
            var dueAmount = parseFloat($(this).data('due')) || 0;
            var payAmount = parseFloat(row.find('.pay-amount').val()) || 0;
            
            totalDue += dueAmount;
            totalPay += payAmount;
        });
        
        $('#total-due-amount').text('৳ ' + totalDue.toFixed());
        $('#total-pay-amount').text('৳ ' + totalPay.toFixed());
    }

    function updatePayable() {
        var totalDue = 0;
        var isValid = true;
        
        $('.invoice-checkbox:checked').each(function () {
            var payAmount = parseFloat($(this).closest('tr').find('.pay-amount').val()) || 0;
            var dueAmount = parseFloat($(this).data('due'));
            
            if (payAmount > dueAmount) {
                toastr.error('Pay amount cannot exceed due amount');
                isValid = false;
            }
            
            totalDue += payAmount;
        });
        
        $('#total-payable span').text(totalDue.toFixed());
        $('input[name="payments_payable_amount"]').val(totalDue.toFixed());
        updateDue();
    }

    function updateDue() {
        const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
        const total = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
        const difference = total - payable;

        if (difference > 0) {
            $('#total-due span').text("0.00");
            $('input[name="payments_due_amount"]').val("0.00");
            $('#total-advance span').text(difference.toFixed());
            $('input[name="payments_advance_amount"]').val(difference.toFixed());
        } else {
            const due = Math.abs(difference);
            $('#total-due span').text(due.toFixed());
            $('input[name="payments_due_amount"]').val(due.toFixed());
            $('#total-advance span').text("0.00");
            $('input[name="payments_advance_amount"]').val("0.00");
        }
    }

    // Validate before form submission
    $('form').on('submit', function(e) {
        var totalPayAmount = 0;
        var totalPayable = 0;
        var hasError = false;
        
        $('.invoice-checkbox:checked').each(function () {
            var payAmount = parseFloat($(this).closest('tr').find('.pay-amount').val()) || 0;
            var dueAmount = parseFloat($(this).data('due'));
            
            if (payAmount > dueAmount) {
                toastr.error('Pay amount cannot exceed due amount for invoice');
                hasError = true;
                return false;
            }
            
            totalPayAmount += payAmount;
        });
        
        totalPayable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
        var totalPaid = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
        
        if (totalPaid !== totalPayable) {
            e.preventDefault();
            toastr.error('Total payment amount must equal the total payable amount. Please adjust your payments.');
            return false;
        }
        
        if ($('.invoice-checkbox:checked').length === 0) {
            e.preventDefault();
            toastr.error('Please select at least one invoice to pay');
            return false;
        }
    });

    // Initialize on page load
    updateTotals();
    updatePayable();
    
    // Check if all invoices are selected on load
    var allChecked = $('.invoice-checkbox:checked').length === $('.invoice-checkbox').length;
    $('#select-all-invoices').prop('checked', allChecked);
});
</script>
@stack('script')
@endsection