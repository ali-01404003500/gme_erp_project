@extends('layout.app')
@section('title', 'Create Invoice Wise Collection')

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Create Invoice Wise Collection</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('account.collections.invoice-wise-collections.index') }}">Invoice
                                        Wise Collections</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <x-error-alart />
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Select Customer to View Due Invoices</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('account.collections.invoice-wise-collections.create') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id">Customer</label>
                                        <select name="customer_id" id="customer_id" class="form-control tom-select"
                                            onchange="this.form.submit()">
                                            <option value="">Select a Customer</option>
                                            @foreach ($customers as $cust)
                                                <option value="{{ $cust->id }}"
                                                    {{ request('customer_id') == $cust->id ? 'selected' : '' }}>
                                                    {{ $cust->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end ">
                                    <span>Balance : </span>
                                    <span id="balance"></span>
                                </div>
                            </div>
                        </form> 
                            
                    </div>
                </div>

                @if ($customer)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Due Invoices for: {{ $customer->company_name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('account.collections.invoice-wise-collections.store') }}"
                                id="form"
                                method="POST">
                                @csrf
                                <input type="hidden" name="collection_from" value="{{ $customer->id }}">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable-config" data-paging="0">
                                        <thead>
                                            <tr>
                                                <th class="no-content">
                                                    <input type="checkbox" id="checkAll" class="form-check-input" checked>
                                                </th>
                                                <th>Invoice ID</th>
                                                <th>Date</th>
                                                <th class="text-right">Total Amount</th>
                                                <th class="text-right">Paid Amount</th>
                                                <th class="text-right">Due Amount</th>
                                                <th class="text-right no-content" style="width: 20%;" >Pay Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($customer->dueInvoices) --}}
                                            @forelse ($customer->dueInvoices as $invoice)
                                                {{-- @dd($invoice); --}}
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="checked_invoices[]" value="{{ $invoice->id }}" class="form-check-input invoice-checkbox" checked>
                                                    </td>
                                                    <td>
                                                        {{ $invoice->sales_order_id }}
                                                        <input type="hidden" name="sales_order_ids[]"
                                                            value="{{ $invoice->id }}">
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($invoice->net_amount) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($invoice->paid_amount) }}
                                                    </td>
                                                    <td class="text-right due-amount" data-due="{{ $invoice->due_amount }}">{{ number_format($invoice->due_amount) }}
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="pay_amount[{{ $invoice->id }}]"
                                                            class="form-control text-right pay-amount numberOnly"
                                                            value="{{ $invoice->due_amount }}">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No due invoices found for this
                                                        customer.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-right font-weight-bold">Total Paying Amount
                                                </td>                                                
                                                <td>
                                                    <input type="text" id="total_paying_amount"
                                                        class="form-control text-right font-weight-bold" readonly>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5 class="text-uppercase">Collection Information</h5>
                                        @include('Services::service-my-task.paymets', ['payments' => null])
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">Create Collection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            function calculateTotals() {
                let totalPaying = 0;
                $('.invoice-checkbox:checked').each(function() {
                    const row = $(this).closest('tr');
                    const payAmount = parseFloat(row.find('.pay-amount').val()) || 0;
                    totalPaying += payAmount;
                });
                $('#total_paying_amount').val(totalPaying.toFixed());
                updatePayable(Number(totalPaying.toFixed()));
            }

            function handleCheckboxChange() {
                const row = $(this).closest('tr');
                const payAmountInput = row.find('.pay-amount');
                if ($(this).is(':checked')) {
                    const dueAmount = parseFloat(row.find('.due-amount').data('due')) || 0;
                    payAmountInput.val(dueAmount.toFixed());
                } else {
                    payAmountInput.val('');
                }
                calculateTotals();
            }

            // Initial calculation
            calculateTotals();

            // Recalculate when a checkbox is changed
            $(document).on('change', '.invoice-checkbox', handleCheckboxChange);
            $(document).on('keyup change', '.pay-amount', calculateTotals);

            // Check/uncheck all functionality
            $('#checkAll').on('change', function() {
                $('.invoice-checkbox').prop('checked', $(this).is(':checked'));
                calculateTotals();
            });

            // Validate that total paying amount matches total payment amount on form submission
            $('form#form').on('submit', function(e) {
                const totalPayingAmount = parseFloat($('#total_paying_amount').val()) || 0;
                // The 'payments_total_amount' input is inside the included payment partial
                const paymentsTotalAmount = parseFloat($('input[name="payments_total_amount"]').val()) || 0;

                const roundedTotalPayingAmount = Math.round(totalPayingAmount);
                const roundedPaymentsTotalAmount = Math.round(paymentsTotalAmount);

                if (roundedTotalPayingAmount !== roundedPaymentsTotalAmount) {
                    e.preventDefault();
                    toastr.error('Total Payment amount must be equal to the Total Paying Amount.');
                }
            });
            const customerId = new URLSearchParams(window.location.search).get('customer_id'); 
            if(customerId)
            {
                $.ajax({
                    url: `{{ route('account.get-ballance') }}?account_id=${customerId}&type=customer`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            console.log(data);
                            let currentDate = new Date().toISOString().slice(0, 10); 
                            const balanceLink = `{{ route('account.report.customer-ledger') }}?account_id=${data.id}&from=2021-10-05&to=${currentDate}`;
                            $('#balance').html('<a href="'+balanceLink+'" target="_blank">'+data.balance+'</a>'); 
                            // Populate additional details based on the response
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load details. Please check the console for errors.');
                        console.error(xhr.responseText);
                    }
                });
            }
           
        });
    </script>
    @stack('script')
@endsection

{{-- @push('script')

@endpush --}}