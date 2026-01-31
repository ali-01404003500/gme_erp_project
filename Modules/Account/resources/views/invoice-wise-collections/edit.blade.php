@extends('layout.app')
@section('title', 'Edit Invoice Wise Collection')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Edit Invoice Wise Collection</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('account.collections.invoice-wise-collections.index') }}">Invoice
                                        Wise Collections</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h5>Editing Collection: {{ $collection->invoice_wise_collection_id }}</h5>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ route('account.collections.invoice-wise-collections.update', $collection->id) }}"
                            id="form"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Customer:</strong> {{ $collection->customer->company_name }}</p>
                                    <input type="hidden" name="customer_id" value="{{ $collection->customer_id }}" id="customer_id">
                                    <input type="hidden" name="collection_from" value="{{ $collection->customer_id }}">
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Collection ID:</strong> {{ $collection->invoice_wise_collection_id }}</p>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="pending" @if ($collection->status == 'pending') selected @endif>
                                                Pending</option>
                                            <option value="approved" @if ($collection->status == 'approved') selected @endif>
                                                Approved</option>
                                            <option value="denied" @if ($collection->status == 'denied') selected @endif>
                                                Denied</option>
                                        </select>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered" id="datatable-config" data-paging="0">
                                    <thead>
                                        <tr>
                                            <th class="no-content">
                                                <input type="checkbox" id="checkAll" class="form-check-input">
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
                                        @forelse ($customer->dueInvoices as $invoice)
                                            @php
                                            // dd($collection->salesOrders);
                                                $isChecked = in_array($invoice->id, $collectionSalesOrderIds);
                                                $salesOrderPivot = $collection->salesOrders->firstWhere('id', $invoice->id);
                                                $payAmount = $salesOrderPivot ? $salesOrderPivot->pivot->amount : ($isChecked ? $invoice->due_amount : 0);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="checked_invoices[]" value="{{ $invoice->id }}" class="form-check-input invoice-checkbox" @if($isChecked) checked @endif>
                                                </td>
                                                <td>
                                                    {{ $invoice->sales_order_id }}
                                                    <input type="hidden" name="sales_order_ids[]" value="{{ $invoice->id }}">
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</td>
                                                <td class="text-right">{{ number_format($invoice->net_amount, 0) }}</td>
                                                <td class="text-right">{{ number_format($invoice->paid_amount, 0) }}</td>
                                                <td class="text-right due-amount" data-due="{{ round($invoice->due_amount) }}">{{ number_format($invoice->due_amount, 0) }}</td>
                                                <td>
                                                    <input type="number" name="pay_amount[{{ $invoice->id }}]" class="form-control text-right pay-amount" placeholder="0" value="{{ $isChecked || $salesOrderPivot ? round($payAmount) : '' }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No due invoices found for this customer.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-right font-weight-bold">Total Paying Amount</td>
                                            <td>
                                                <input type="text" id="total_paying_amount" class="form-control text-right font-weight-bold" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="text-uppercase">Payment Information</h5>
                                    @include('Services::service-my-task.paymets', ['payments' => $collection->payments])
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <div class="btn-group">
                                    <input type="hidden" name="status" id="status" value="{{ $collection->status ?? 'pending' }}">

                                    <button type="submit" class="btn btn-sm btn-primary save-btn" >
                                        <i class="fa fa-save"></i> Update
                                    </button>

                                    @if(hasPermission('account.collections.invoice-wise-collections.verify') && $collection->status == "pending")
                                        <button type="submit" class="btn btn-sm btn-warning save-btn" id="action_verify">
                                            <i class="fa fa-check"></i> Update & Verify
                                        </button>
                                    @endif
                                    @if(hasPermission('account.collections.invoice-wise-collections.approve') && $collection->status == "verified")
                                        <button type="submit" class="btn btn-sm btn-success save-btn" id="action_approve">
                                            <i class="fa fa-check"></i> Update & Approve
                                        </button>
                                    @endif
                                    @if(hasPermission('account.collections.invoice-wise-collections.deny') && ($collection->status == 'pending' || $collection->status == 'verified'))
                                        <button type="submit" class="btn btn-sm btn-danger save-btn" id="action_deny">
                                            <i class="fa fa-times"></i> Deny
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
        $(document).ready(function() {
            function calculateTotals() {
                let total = 0;
                $('.invoice-checkbox:checked').each(function() {
                    const row = $(this).closest('tr');
                    const payAmount = parseFloat(row.find('.pay-amount').val()) || 0;
                    total += payAmount;
                });

                $('#total_paying_amount').val(Math.round(total));

                updatePayable(Math.round(total));
            }

            function handleCheckboxChange() {
                const row = $(this).closest('tr');
                const payAmountInput = row.find('.pay-amount');
                if ($(this).is(':checked')) {
                    const dueAmount = parseFloat(row.find('.due-amount').data('due')) || 0;
                    payAmountInput.val(Math.round(dueAmount));
                } else {
                    payAmountInput.val('');
                }
                calculateTotals();
            }

            calculateTotals();

            $(document).on('change', '.invoice-checkbox, #checkAll', handleCheckboxChange);
            $(document).on('keyup change', '.pay-amount', calculateTotals);

            $('#checkAll').on('change', function() { $('.invoice-checkbox').prop('checked', $(this).is(':checked')).trigger('change'); });

            // Validate that total paying amount matches total payment amount on form submission

        });

        $(document).on('submit', 'form#form',function(e) {
            
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
</script>
<script>
    $(document).ready(function() {
        $('#action_verify').click(function() {
            $("#status").val("verified");
        });

        $('#action_approve').click(function() {
            $("#status").val("approved");
        });

        $('#action_deny').click(function() {
            $("#status").val("denied");
        });
    });
</script>
@stack('script')
@endsection