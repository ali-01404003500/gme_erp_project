@section('title', 'Receive Payments')
@section('description', 'Receive Payments for Customer')
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
                                        {{ trans('menu.create-customer-payments-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-customer-payments-menu-title') }}</h4>
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
                                            <select class="form-control tom-select" id="customerSelect" name="customer_id" required >
                                                <option value="" disabled selected>Select a customer</option>
                                                @foreach($customers as $item)
                                                    <option value="{{ $item->id }}" {{ old('customer_id', optional($customer)->id ) == $item->id ? 'selected' : '' }}>{{ $item->company_name }}</option>
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
                            @if($customer)
                                <form action="{{ route('account.payments.customer-payments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    {{-- <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}"> --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>  </h2>
                                        </div>
                                        {{-- @dd($customer->invoices) --}}
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="selectAll" class="form-check-input checkbox" checked>
                                                        </th>
                                                        <th>Invoice Date</th>
                                                        <th>Invoice No</th>
                                                        <th>Inv.Amount</th>
                                                        <th>Paid</th>
                                                        <th>Due Amount</th>
                                                        <th>Select</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="invoiceTable">
                                                    @foreach($customer->invoices as $index => $invoice)
                                                        @if($invoice->invoice_due > 0)
                                                            <tr data-invoice="{{ $invoice }}">
                                                                <td>
                                                                    <input type="checkbox" name="invoice_ids[{{$index}}]" class="form-check-input checkbox" value="{{ $invoice->id }}" checked>
                                                                    {{-- {{ $invoice->paid_vat}} --}}
                                                                    @foreach ($invoice->invoiceDetails as $key => $invoiceDetail)
                                                                        <input type="hidden" name="product_ids[{{$invoice->id}}][]" value="{{ $invoiceDetail->product_id }}" data-product="{{ $invoiceDetail->product }}">
                                                                        <input type="hidden" name="invoice_qtys[{{$invoice->id}}][]" value="{{ $invoiceDetail->quantity - $invoiceDetail->paid_quantity }}">
                                                                        <input type="hidden" name="prices[{{$invoice->id}}][]" value="{{  $invoiceDetail->price }}">
                                                                        <input type="hidden" name="unit_discount[{{$invoice->id}}][]" value="{{ $invoiceDetail->unit_discount }}">
                                                                        <input type="hidden" name="quantities[{{$invoice->id}}][]" value="{{ $invoiceDetail->quantity - $invoiceDetail->paid_quantity  }}">
                                                                    @endforeach
                                                                    {{-- @dd($invoice->vat) --}}
                                                                    <input type="hidden" name="invoice_vat[{{$invoice->id}}]" value="{{$invoice->vat - $invoice->paid_vat}}">
                                                                    <input type="hidden" name="vat[{{$invoice->id}}]" value="{{$invoice->vat - $invoice->paid_vat}}">
                                                                </td>
                                                                <td>{{ $invoice->invoice_date }}</td>
                                                                <td>{{ $invoice->invoice_id }}</td>
                                                                <td>{{ $invoice->net_amount }}</td>
                                                                <td><input type="text" name="paid[]" class="form-control" value="{{ $invoice->paid_amount??0 }}" readonly></td>
                                                                <td><input type="text" name="payable_amounts[]" class="form-control payable-amount" readonly value="{{ $invoice->invoice_due }}"></td>
                                                                <td><input type="text" name="pay_amount[]" class="form-control pay-amount" width="192px"></td>
                                                                <td>
                                                                    <div class="btn-group btn-group-xs" role="group">
                                                                        <button type="button" class="btn btn-secondary btn-xs" onclick="showInvoiceDetails(this)">
                                                                            <i class="fas fa-list"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                 
                                                    <tr>
                                                        <th colspan="3" class="text-end">Total</th>
                                                        <th>{{ $customer->invoices->where('invoice_due', '>', 0)->sum('net_amount') }}</th>
                                                        <th>{{ $customer->invoices->where('invoice_due', '>', 0)->sum('paid_amount') }}</th>
                                                        <th></th>
                                                        {{-- <th>{{ $customer->invoices->where('invoice_due', '>', 0)->sum('invoice_due') }}</th> --}}
                                                        <th>
                                                            {{-- <input type="text" id="paymentAmount" class="form-control" name="total_amount"> --}}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="3" class="text-end">Select Account</th>
                                                        {{-- @dd($receiver_accounts) --}}
                                                        <th>
                                                            <select name="account_id" id="account_id" class="form-control tom-select selected">
                                                                <option value="">Select Account</option>
                                                                @foreach($receiver_accounts as $receiver_account)
                                                                    <option value="{{ $receiver_account->id }}" {{ old('account_id') == $receiver_account->id ? 'selected' : ($receiver_account->name == 'Cash' ? 'selected' : '')}}>{{ $receiver_account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Total Vat 
                                                        </th>
                                                        <th>
                                                            <input type="text" id="total_vat" readonly class="form-control" name="total_vat" value="0">
                                                        </th>                                                            
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" class="text-end">
                                                            Previous Advance 
                                                        </th>
                                                        <th>
                                                            {{-- @dd() --}}
                                                            <input type="text" id="customer_balance" readonly class="form-control" name="previous_advance" value="{{$customer->advance_balance}}">
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

        <!-- EDIT MODAL -->
        <div class="modal fade inputForm-modal" id="invoice_details" tabindex="-1" role="dialog"
            aria-labelledby="invoice_details" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 90% !important;">
                <div class="modal-content">

                    <div class="modal-header" id="editModalLabel">
                        <h5 class="modal-title"><span id="invoice_id"></span> Invoice Details  </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Invoice Qty</th>
                                            <th>Price</th>
                                            <th>Unit Discount</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice_details_table">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-end">Total</th>
                                            <th>
                                                <input id="total_net_amount" value="0" readonly class="form-control">
                                            </th> 
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-end">Vat</th>
                                            <th>
                                                <input id="invoice_vat" value="0" type="hidden">
                                                <input id="total_vat" value="0" readonly class="form-control">
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save" data-bs-dismiss="modal" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
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
                        $(this).find("input[name^='vat']").each(function () {
                            totalVat += parseFloat(this.value) || 0;
                            console.log({varInput: this});
                        });
                    }
                });
                
                $("#invoiceTable tr").each(function () {
                    if($(this).find(".checkbox").is(":checked") ) {
                        const rowPayable = parseFloat($(this).find(".payable-amount").val());
                        if(paymentAmount > 0) {
                                if(paymentAmount-rowPayable < 0){
                                    $(this).find(".pay-amount").val(paymentAmount);
                                    $(this).find(".pay-amount").addClass('is-invalid');
                                }else{
                                    $(this).find(".pay-amount").val(rowPayable);
                                    $(this).find(".pay-amount").removeClass('is-invalid');
                                }
                                paymentAmount -= rowPayable;
                        }else{
                                $(this).find(".pay-amount").val("");
                                $(this).find(".pay-amount").removeClass('is-invalid');
                        }
                    }else{
                        $(this).find(".pay-amount").val("");
                        $(this).find(".pay-amount").removeClass('is-invalid');
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

            function showInvoiceDetails(elem) {
                const row = $(elem).closest('tr');
                const invoiceId = row.data('invoice').invoice_id;
                $("#invoice_details").find("#invoice_id").html(invoiceId);
                $("#invoice_details").data('invoice_id', row.data('invoice').id);
                $("#invoice_details").find("#invoice_details_table").empty();
                row.find("td:first input[name^='product_ids']").each(function (index) {
                    // const index = $(this).index();
                    const product = $(this).data('product');
                    console.log({ index});
                    
                    
                    const invoiceQuantity = $(this).closest("tr").find("td input[name^='invoice_qtys']").eq(index).val();
                    const quantity = $(this).closest("tr").find("td input[name^='quantities']").eq(index).val();
                    const price = $(this).closest("tr").find("td input[name^='prices']").eq(index).val();
                    const discount = $(this).closest("tr").find("td input[name^='unit_discount']").eq(index).val();
                    const invoiceVat = $(this).closest("tr").find("td input[name^='invoice_vat']").val();

                    
                    $("#invoice_details").find("#invoice_details_table").append(`
                        <tr>
                            <td>
                                ${product.name}
                                <input type="hidden" class="product_id" value="${product.id}">
                            </td>
                            <td><input type="number" class="form-control invoice_quantity" value="${invoiceQuantity}" readonly></td>
                            <td><input type="number" class="form-control unit_price" value="${price}" readonly></td>
                            <td><input type="number" class="form-control unit_discount" value="${discount}" readonly></td>
                            <td><input type="number" class="form-control quantity" value="${quantity}"></td>   
                            <td><input type="number" class="form-control amount" value="${price*quantity}" readonly></td>
                        </tr>
                    `);

                    $("#invoice_details").find("#invoice_vat").val(invoiceVat);
                })
                $("#invoice_details").modal('show');
                $("#invoice_details").find(".quantity:first").trigger('input');
            }


            function reverseInvoiceDetails() {
              const invoiceId = $("#invoice_details").find("#invoice_id").html();
              const invoiceDetailsTable = $("#invoice_details").find("#invoice_details_table");
              const rows = invoiceDetailsTable.find("tr");
            
              rows.each(function() {
                const product = $(this).find("td:first input.product_id").data('product');
                const quantity = $(this).find("td input.quantity").val();
                const price = $(this).find("td input.unit_price").val();
                const discount = $(this).find("td input.unit_discount").val();
                const vat = $("#invoice_details").find("#total_vat").val();
            
                const originalRow = $(`tr[data-invoice-id="${invoiceId}"]`);
                originalRow.find("td input[name^='product_ids']").each(function() {
                  if ($(this).data('product').id === product.id) {
                    $(this).closest("tr").find("td input[name^='quantities']").val(quantity);
                    $(this).closest("tr").find("td input[name^='prices']").val(price);
                    $(this).closest("tr").find("td input[name^='discounts']").val(discount);
                    $(this).closest("tr").find("td input[name^='vat']").val(vat);
                  }
                });
              });
            
              invoiceDetailsTable.empty();
              $("#invoice_details").modal('hide');
            }


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
                    toastr.warning('Quantity should not be greater than invoice quantity! ');
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

                const invoiceVat = parseFloat($("#invoice_details #invoice_vat").val())||0;
                
                let total = 0 ;
                $(".modal-body  .amount").each(function () {
                    total += parseFloat($(this).val())||0;
                });
                let totalVat = invoiceVat * total / totalInvoicePrice;
                $("#invoice_details #total_net_amount").val(total);
                $("#invoice_details #total_vat").val(totalVat.toFixed());
            });


            $(document).on('click', '#invoice_details #save', function (evnt) {
                const total_net=$("#invoice_details #total_net_amount").val();
                const total_vat=$("#invoice_details #total_vat").val();
                const invoiceId = $("#invoice_details").data('invoice_id');
                const invoiceDetailsTable = $("#invoice_details").find("#invoice_details_table");
                const rows = invoiceDetailsTable.find("tr");
                const invoiceRow = $("input[name^='invoice_ids'][value='" + invoiceId + "']").closest("tr");
                console.log("input[name^='invoice_ids'][value='" + invoiceId + "']");
                
                rows.each(function() {
                   const index = $(this).index();
                   const quantity = $(this).find("td input.quantity").val();
                   const amount = $(this).find("td input.amount").val();
                   console.log(invoiceRow);
                   
                   console.log(invoiceRow.find("input[name^='quantities[" + invoiceId + "]']").get(index));
                   invoiceRow.find("input[name^='quantities[" + invoiceId + "]']").get(index).value = quantity;
                   
                });

                console.log(invoiceRow.find(".payable-amount"));
                
                invoiceRow.find(".payable-amount").val(parseFloat(total_net)+parseFloat(total_vat));
                invoiceRow.find("input[name^='vat']").val(total_vat);

                calculateAmounts();

                {{--
                /**
                 * Recreate firs 
                 *      <input type="hidden" name="product_ids[{{$invoice->id}}][]" value="{{  $invoiceDetail->product_id }}" data-product="{{  $invoiceDetail->product }}">
                 *      <input type="hidden" name="invoice_qtys[{{$invoice->id}}][]" value="{{  $invoiceDetail->quantity }}">
                 *      <input type="hidden" name="prices[{{$invoice->id}}][]" value="{{  $invoiceDetail->price }}">
                 *      <input type="hidden" name="unit_discount[{{$invoice->id}}][]" value="{{  $invoiceDetail->unit_discount }}">
                 *      <input type="hidden" name="quantities[{{$invoice->id}}][]" value="{{  $invoiceDetail->quantity }}">
                 *  <input type="hidden" name="vat[{{$invoice->id}}]" value="{{$invoice->vat}}">
                 */ --}}
                // $("#invoice_details").find("#invoice_details_table").find("tr").each(function () {
                //     $(this).find(".product_id")
                // })
            });

            $(document).ready(function () {
                //submit validation 
                $('.submitBtn').on("click",function () {
                    const rows = $("#invoiceTable tr");

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                           
                        const payableAmount = $(row).find('input[name^="payable_amounts"]').val();
                        const payAmount = $(row).find('input[name^="pay_amount"]').val();
                        const isChecked = $(row).find('input.checkbox').is(':checked');

                        console.log({payableAmount, payAmount, isChecked});
                        
                        if (isChecked && (parseInt(payableAmount) != parseInt(payAmount))) {
                            toastr.error('Payable amount and pay amount should be equal for checked rows');
                            return false
                        }
                    }
                    return true
                });
            });
            
        </script>
    @endsection
