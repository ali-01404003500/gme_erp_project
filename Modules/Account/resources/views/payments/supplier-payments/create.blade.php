@section('title', 'Supplier Payments')
@section('description', 'Supplier Payments for Supplier')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Supplier Payments') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Supplier Payments') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="" >
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{-- <label for="customerSelect" class="mr-2">Select Customer</label> --}}
                                            <select class="form-control tom-select" id="supplierSelect" name="supplier_id" required >
                                                <option value="" disabled selected>Select a Supplier</option>
                                                @foreach($suppliers as $item)
                                                    <option value="{{ $item->id }}" {{ old('supplier_id', request('supplier_id')) == $item->id ? 'selected' : '' }}>{{ $item->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group d-inline-block ml-2">
                                            <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @if($supplier)
                                <form action="{{ route('account.payments.supplier-payments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                    {{-- <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}"> --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>  </h2>
                                        </div>
                                        {{-- @dd($supplier->receives) --}}
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="selectAll" class="form-check-input checkbox" checked>
                                                        </th>
                                                        <th>Receipt Date</th>
                                                        <th>Receipt No</th>
                                                        <th>Inv.Amount</th>
                                                        <th>Paid</th>
                                                        <th>Due Amount</th>
                                                        <th>Pay Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="invoiceTable">
                                                    @foreach($supplier->receives as $index => $receive)
                                                        @if($receive->receive_due > 0)
                                                            <tr data-receive="{{ $receive }}">
                                                                <td>
                                                                    <input type="checkbox" name="receive_ids[{{$index}}]" class="form-check-input checkbox" value="{{ $receive->id }}" checked>
                                                             
                                                                </td>
                                                                <td>{{ $receive->created_at->format('d-m-Y') }}</td>
                                                                <td>{{ $receive->po_receive_number }}</td>
                                                                <td>{{ $receive->net_landed_cost }}</td>
                                                                <td><input type="text" name="paid[]" class="form-control" value="{{ $receive->paid_amount??0 }}" readonly></td>
                                                                <td><input type="text" name="payable_amounts[]" class="form-control payable-amount" readonly value="{{ $receive->receive_due }}"></td>
                                                                <td><input type="text" name="pay_amount[]"  class="form-control pay-amount" width="192px"></td>
                                                           
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                 
                                                    {{-- <tr>
                                                        <th colspan="3" class="text-end">Total</th>
                                                        <th>{{ $supplier->receives->where('receive_due', '>', 0)->sum('net_landed_cost') }}</th>
                                                        <th>{{ $supplier->receives->where('receive_due', '>', 0)->sum('paid') }}</th>
                                                        <th></th>
                                                        <th>
                                                        </th>
                                                    </tr> --}}
                                                    <tr>
                                                        <th colspan="3" class="text-end">Select Account</th>
                                                        <th>
                                                            <select name="account_id" id="account_id" class="form-control tom-select">
                                                                <option value="">Select Account</option>
                                                                @foreach($receiver_accounts as $receiver_account)
                                                                    <option value="{{ $receiver_account->id }}" {{ old('account_id') == $receiver_account->id ? 'selected' : ($receiver_account->name == 'Cash' ? 'selected' : '')}}>{{ $receiver_account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                    </tr>
                                                  
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Previous Advance 
                                                        </th>
                                                        <th>
                                                            {{-- @dd() --}}
                                                            <input type="text" id="customer_balance" readonly class="form-control" name="previous_advance" value="{{$supplier->advance_balance}}">
                                                        </th>                                                            
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Net Due
                                                        </th>
                                                        <th>
                                                            <input type="text" id="netDue" readonly class="form-control" name="due_amount">
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Advance
                                                        </th>
                                                        <th>
                                                            <input type="text" id="advanceAmount" readonly class="form-control" name="advance_amount" >
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Enter Amount
                                                        </th>
                                                        <th>
                                                            <input type="text" id="paymentAmount" class="form-control" name="total_amount">
                                                        </th>
                                                    </tr>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary submitBtn">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @section('page_scripts')
        <script>
            function calculateAmounts(){

                var paymentAmount = parseFloat($('#paymentAmount').val());
                console.log({paymentAmount});
                paymentAmount += parseFloat($('#customer_balance').val())||0;
                let totalCheckedPayable = 0;
                let totalVat = 0;
                $("#invoiceTable tr").each(function () {
                    if ($(this).find(".checkbox").is(":checked")) {
                        totalCheckedPayable += parseFloat($(this).find(".payable-amount").val()) || 0;
                    }
                });
                
                $("#invoiceTable tr").each(function () {
                    if($(this).find(".checkbox").is(":checked") ) {
                        const rowPayable = parseFloat($(this).find(".payable-amount").val());
                        if(paymentAmount > 0) {
                                if(paymentAmount-rowPayable < 0){
                                    $(this).find(".pay-amount").val(paymentAmount);
                                }else{
                                    $(this).find(".pay-amount").val(rowPayable);
                                }
                                paymentAmount -= rowPayable;
                        }else{
                                $(this).find(".pay-amount").val("");
                                
                        }
                    }else{
                        $(this).find(".pay-amount").val("");
                        
                    }
                });
                if(paymentAmount > 0) {
                    $("#advanceAmount").val(paymentAmount);
                }else{
                    $("#advanceAmount").val(0);
                }

                $("#netDue").val(totalCheckedPayable);
                $("#total_vat").val(totalVat);
            }
            $(document).ready(function () {
                calculateAmounts();
            })

            $(document).on('input', '#paymentAmount, .checkbox', function () {
                calculateAmounts();
            });

            // modal open

         



            // toggle check all checkbox
            $(document).on('change', '.checkbox', function (evnt) {
                const target = $(evnt.target);
                const selectAll = $('#selectAll');
                const checkboxes = $('.checkbox');
                if (target.is(selectAll)) {
                    checkboxes.prop('checked', selectAll.is(':checked'));
                } else if (target.is(checkboxes)) {
                    if (selectAll.is(':checked')) {
                        selectAll.prop('checked', false);
                    }
                }
                $(".checked").trigger('change');
            });

            //checked quantity inputs

            $(document).on('input', '.modal-body .quantity', function (evnt) {
                const rowInvoiceQty = $(this).closest("tr").find("td .invoice_quantity").val();
                const quantity = $(this).val();
                if (rowInvoiceQty < quantity ) {
                    evnt.preventDefault();
                    toastr.warning('Quantity should not be greater than receive quantity! ');
                    $(this).val(rowInvoiceQty);
                } 
                if( quantity < 0 ){
                    evnt.preventDefault();
                    toastr.warning('Invalid Quantity! ');
                    $(this).val(0);
                }
                const unitPrice = parseFloat($(this).closest("tr").find("td .unit_price").val());
                const amount =  parseFloat($(this).val()) * unitPrice;
                $(this).closest("tr").find("td .amount").val(amount.toFixed());
                let totalInvoicePrice = 0;
                $(".modal-body  .unit_price").each(function () {
                    totalInvoicePrice += parseFloat($(this).val()) * parseFloat($(this).closest("tr").find("td .invoice_quantity").val());
                })
                console.log({totalInvoicePrice});

                const invoiceVat = parseFloat($("#receive_details #invoice_vat").val())||0;
                
                let total = 0 ;
                $(".modal-body  .amount").each(function () {
                    total += parseFloat($(this).val());
                });
                let totalVat = invoiceVat * total / totalInvoicePrice;
                $("#receive_details #total_net_amount").val(total);
                $("#receive_details #total_vat").val(totalVat.toFixed());
            });



            $(document).ready(function () {
                //submit validation 
                $('.submitBtn').on("click",function () {
                    const rows = $("#invoiceTable tr");

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                           
                        const payableAmount = $(row).find('input[name^="payable_amounts"]').val();
                        const payAmount = $(row).find('input[name^="pay_amount"]').val() || 0;
                        const isChecked = $(row).find('input.checkbox').is(':checked');

                        console.log({payableAmount, payAmount, isChecked});
                        
                        if (isChecked && (parseInt(payAmount) < 1)) {
                            toastr.error(' Pay amount should be greater than 0 for checked rows');
                            return false
                        }
                    }
                    return true
                });
            });
            
        </script>
    @endsection
