@section('title', 'Edit Sales Return')
@section('description', 'Edit Sales Return')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Edit Sales Return') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('sales.sales-returns.index'))
                                <a href="{{ route('sales.sales-returns.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit Sales Return') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.sales-returns.update', $salesReturn->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')                                  
                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <label for="return_id">Return Id<span class="text-danger">*</span></label>
                                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ $salesReturn->invoice_no }}" readonly>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                        <input type="hidden" name="reference_invoice" id="reference_invoice" class="form-control" value="{{ $salesReturn->reference_invoice }}">
                                        <input type="hidden" name="customer_id" id="customer_id" class="form-control" value="{{ $salesReturn->customer_id }}">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ optional($salesReturn->customer)->company_name }}" readonly>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label for="customer_phone">Customer Phone</label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ optional($salesReturn->customer)->phone }}" readonly>

                                    </div>

                                    <div class="col-md-4">
                                        <label for="return_date">Return Date<span class="text-danger">*</span></label>
                                        <input type="text" name="return_date" class="form-control flatdate" id="return_date" placeholder="Return Date" value="{{ old('return_date', $salesReturn->return_date) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Product Information</h3>
                                            <table class="table table-bordered" id="product_info_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 26%">Product Name</th>
                                                        <th style="width: 10%">Remaining Qty</th>
                                                        <th style="width: 10%">Return Qty</th>
                                                        <th style="width: 10%">Price</th>
                                                        <th style="width: 10%">Unit Dis</th>
                                                        <th style="width: 10%">Discount</th>                                                        
                                                        <th style="width: 16%">Amount</th>
                                                        <th style="width: 8%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salesReturn->salesReturnDetails as $key => $detail)
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                                                                <input type="text" name="product_name[]" value="{{ $detail->product->name }}" class="form-control" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="delivered_quantity[]" value="{{ $detail->salesReturn->salesOrder->delivery->deliveryDetails->flatMap->deliveryStocks->sum('quantity') ?? 0 }}" class="form-control" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="quantity[]" value="{{ numberFormat($detail->quantity,0) }}" class="form-control" placeholder="Quantity">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="price[]" value="{{ numberFormat($detail->price) }}" class="form-control" placeholder="Price" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" name="unit_discount[]" value="{{ numberFormat($detail->unit_discount) }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" name="total_discount[]" value="{{ numberFormat($detail->total_discount) }}" readonly> 
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" name="amount[]" value="{{ numberFormat($detail->amount) }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="checks[{{ $key }}]" class="form-check-input" value="1" checked>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;">Main Inv Discount</td>
                                                        <td colspan="2">
                                                            <input type="text" class="form-control text-center" name="discount" value="{{ numberFormat($salesReturn->discount) }}" readonly>
                                                        </td>
                                                        <td colspan="2" style="text-align: right;">Total Amount</td>
                                                        <td>
                                                            <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" style="text-align: right;">Net Amount</td>
                                                        <td><input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount', $salesReturn->net_amount) }}"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-12 pt-25">
                                            <div class="form-group">
                                                <textarea name="remarks" id="remarks" class="form-control w-100" placeholder="Remarks">{{ old('remarks', $salesReturn->remarks) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 my-2">
                                            <h4>Refund Payments Information:</h4>
                                        </div>
                                        <div class="col-md-12">
                                            @include('Account::payments.make-payments.payments-details', ['payments' => $salesReturn->paymentDetails ?? []])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
    $(document).ready(function() {
        // Initialize all checkboxes as checked and make quantity fields editable
        $('.form-check-input').prop('checked', true);
        $('input[name="quantity[]"]').prop('readonly', false);
        
        // Calculate all amounts on page load
        calculateAllAmounts();

        // Checkbox change handler
        $('.form-check-input').on('change', function() {
            var checkbox = $(this);
            var quantityInput = checkbox.closest('tr').find('input[name="quantity[]"]');
            var deliveredQuantityInput = checkbox.closest('tr').find('input[name="delivered_quantity[]"]');

            if (checkbox.is(':checked')) {
                quantityInput.prop('readonly', false);
            } else {
                quantityInput.prop('readonly', true);
            }
            calculateAllAmounts();
        });

        // Quantity input change handler
        $(document).on('keyup', 'input[name="quantity[]"]', function() {
            var row = $(this).closest('tr');
            var quantityValue = parseFloat($(this).val()) || 0;
            var deliveredQuantityValue = parseFloat(row.find('input[name="delivered_quantity[]"]').val()) || 0;

            if (quantityValue > deliveredQuantityValue) {
                toastr.error('Quantity cannot be greater than delivered quantity');
                $(this).val(deliveredQuantityValue);
            }
            calculateRowAmount(row);
            calculateAllAmounts();
        });

        // Calculate amount for a single row
        function calculateRowAmount(row) {
            const qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
            const price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            const unitDiscount = parseFloat(row.find('input[name="unit_discount[]"]').val()) || 0;
            
            const amount = qty * price;
            const totalDiscount = unitDiscount * qty;
            
            row.find('input[name="amount[]"]').val(amount.toFixed());
            row.find('input[name="total_discount[]"]').val(totalDiscount.toFixed());
        }

        // Calculate total amount and discount
        function calculateTotalAmount() {
            let totalAmount = 0;
            let totalDiscount = 0;
            
            $("#product_info_table tbody tr").each(function() {
                if ($(this).find('.form-check-input').is(':checked')) {
                    const amount = parseFloat($(this).find('input[name="amount[]"]').val()) || 0;
                    const discount = parseFloat($(this).find('input[name="total_discount[]"]').val()) || 0;
                    
                    totalAmount += amount;
                    totalDiscount += discount;
                }
            });
            
            $("#total_amount").val(totalAmount.toFixed());
            $('input[name="discount"]').val(totalDiscount.toFixed());
        }

        // Calculate net amount
        function calculateNetAmount() {
            const totalAmount = parseFloat($("#total_amount").val()) || 0;
            const discount = parseFloat($('input[name="discount"]').val()) || 0;
            const netAmount = totalAmount - discount;
            
            $("#net_amount").val(netAmount.toFixed(0));
            updatePayable(netAmount);
            // $('input[name="net_amount"]').val(netAmount.toFixed(0));
        }

        // Calculate all amounts (row, total, and net)
        function calculateAllAmounts() {
            $("#product_info_table tbody tr").each(function() {
                calculateRowAmount($(this));
            });
            calculateTotalAmount();
            calculateNetAmount();
        }
    });
</script>
@stack('script')
@endsection