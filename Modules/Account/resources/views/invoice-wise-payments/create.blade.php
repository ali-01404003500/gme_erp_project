@extends('layout.app')
@section('title', "Invoice Wise Payments")
@section('description', "Invoice Wise Payments")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Invoice Wise Payments') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-12 mt-4">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('Invoice Wise Payments') }}</h4>
                    </div>
                    <div class="col-md-12 mt-4">
                        <x-error-alart />
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payment_to_type">{{ trans('Payment Type') }} *</label>
                                        <select name="payment_to_type" id="payment_to_type" class="form-control tom-select" required>
                                            <option value="">--{{ trans('Select Payment Type') }}--</option>
                                            <option value="supplier" {{ request('payment_to_type') == 'supplier' ? 'selected' : '' }}>{{ trans('Supplier Payment') }}</option>
                                            <option value="vendor" {{ request('payment_to_type') == 'vendor' ? 'selected' : '' }}>{{ trans('Vendor Payment') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payment_to_id">{{ trans('Payment To') }} *</label>
                                        <select name="payment_to_id" id="payment_to_id" class="form-control tom-select" required>
                                            <option value="">--{{ trans('Select Payment To') }}--</option>
                                            @if(request('payment_to_type') == 'supplier')
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ request('payment_to_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->company_name }}
                                                    </option>
                                                @endforeach
                                            @elseif(request('payment_to_type') == 'vendor')
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ request('payment_to_id') == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->company_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_from">{{ trans('Date From') }}</label>
                                        <input type="text" name="date_from" id="date_from" class="form-control flatdate" value="{{ request('date_from') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_to">{{ trans('Date To') }}</label>
                                        <input type="text" name="date_to" id="date_to" class="form-control flatdate" value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100">{{ trans('Search') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($paymentTo)
        <!-- Payment Form -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.payments.invoice-wise-payments.store') }}" method="post">
                            @csrf
                            
                            @php
                                // Determine the full class name based on payment type
                                $paymentToType = request('payment_to_type') == 'supplier' 
                                    ? 'Modules\Purchase\Models\Supplier' 
                                    : 'Modules\Purchase\Models\Vendor';
                            @endphp

                            <input type="hidden" name="payment_to_type" value="{{ $paymentToType }}">
                            <input type="hidden" name="payment_to_id" value="{{ request('payment_to_id') }}">

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>{{ trans('Due Invoices for') }} {{ $paymentTo->company_name }}</h5>
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
                                        @forelse($paymentTo->dueInvoices as $invoice)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" class="invoice-checkbox" data-due="{{ $invoice->due_amount }}">
                                                <input type="hidden" name="invoice_types[]" value="{{ $invoice->invoice_type }}">
                                            </td>
                                            <td>{{ $invoice->date ?? $invoice->invoice_date }}</td>
                                            <td>{{ $invoice->invoice_no ?? $invoice->requisition_no }}</td>
                                            <td>৳ {{ number_format($invoice->net_amount) }}</td>
                                            <td>৳ {{ number_format($invoice->paid_amount) }}</td>
                                            <td>৳ {{ number_format($invoice->due_amount) }}</td>
                                            <td>
                                                <input type="number" name="pay_amount[]" class="form-control pay-amount" step="0.01" min="0" max="{{ $invoice->due_amount }}" value="0" disabled>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">{{ trans('No due invoices found') }}</td>
                                        </tr>
                                        @endforelse
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

                            @if($paymentTo->dueInvoices->count() > 0)
                            <div class="col-md-12 my-2">
                                <h4>{{ trans('Payment Information') }}</h4>
                            </div>
                            <div class="col-md-12">
                                @include('Account::payments.make-payments.payments-details', ['payments' => []])
                            </div>

                            <div class="col-md-12 mt-4">
                                <input type="hidden" name="status" id="status">
                                <div class="form-group gap-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="save">{{ trans('Save') }}</button>
                                    @if(hasPermission('account.payments.invoice-wise-payments.verify'))
                                    <button type="submit" class="btn btn-warning" id="save-verify">{{ trans('Save and Verify') }}</button>
                                    @endif
                                    @if(hasPermission('account.payments.invoice-wise-payments.approve'))
                                    <button type="submit" class="btn btn-success" id="save-approved">{{ trans('Save and Approved') }}</button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
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

    // Handle payment type change
    $('#payment_to_type').change(function () {
        var type = $(this).val();
        var tomSelect = $('#payment_to_id').prop('tomselect');
        
        if (tomSelect) {
            tomSelect.clear();
            tomSelect.clearOptions();
            
            if (type === 'supplier') {
                @foreach($suppliers as $supplier)
                    tomSelect.addOption({value: '{{ $supplier->id }}', text: '{{ $supplier->company_name }}'});
                @endforeach
            } else if (type === 'vendor') {
                @foreach($vendors as $vendor)
                    tomSelect.addOption({value: '{{ $vendor->id }}', text: '{{ $vendor->company_name }}'});
                @endforeach
            }
            tomSelect.refreshOptions();
        }
    });

    // Select All Invoices Checkbox
    $('#select-all-invoices').change(function() {
        var isChecked = $(this).is(':checked');
        $('.invoice-checkbox').each(function() {
            $(this).prop('checked', isChecked);
            var payAmountInput = $(this).closest('tr').find('.pay-amount');
            if (isChecked) {
                payAmountInput.prop('disabled', false);
                payAmountInput.val($(this).data('due'));
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
            payAmountInput.val($(this).data('due'));
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
        
        $('.invoice-checkbox:checked').each(function () {
            var payAmount = parseFloat($(this).closest('tr').find('.pay-amount').val()) || 0;
            totalDue += payAmount;
        });
        
        $('#total-payable span').text(totalDue.toFixed());
        $('input[name="payments_payable_amount"]').val(totalDue.toFixed());
        
        // Auto-fill the first payment amount
        if ($('input[name="payments_amount[]"]').length > 0) {
            $('input[name="payments_amount[]"]').first().val(totalDue.toFixed());
            // $('input[name="payments_total_amount"]').val(totalDue.toFixed());
        }
        
        updateDue();
    }

    // function updateDue() {
    //     const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
    //     const total = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
    //     const difference = total - payable;

    //     if (difference > 0) {
    //         $('#total-due span').text("0.00");
    //         $('input[name="payments_due_amount"]').val("0.00");
    //         $('#total-advance span').text(difference.toFixed());
    //         $('input[name="payments_advance_amount"]').val(difference.toFixed());
    //     } else {
    //         const due = Math.abs(difference);
    //         $('#total-due span').text(due.toFixed());
    //         $('#input-amount').val(due.toFixed() || '0.00');

    //         $('input[name="payments_due_amount"]').val(due.toFixed());
    //         $('#total-advance span').text("0.00");
    //         $('input[name="payments_advance_amount"]').val("0.00");
    //     }
    // }

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
    });

    // Initialize totals on page load
    updateTotals();
});
</script>
@stack('script')
@endsection