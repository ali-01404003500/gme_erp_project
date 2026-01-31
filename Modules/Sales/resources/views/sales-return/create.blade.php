@section('title', 'Sales Return')
@section('description', 'Sales Return')
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
                                        {{ trans('Sales Return') }}</li>
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Return') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="invoice_id">Invoice Id<span class="text-danger">*</span></label>
                                        <select name="invoice_id" id="invoice_id" class="form-control tom-select">
                                            <option value="">Choose Invoice Id</option>
                                            @foreach ($invoices as $invoice)
                                                <option value="{{ $invoice->id }}"
                                                    {{ old('invoice_id', request()->invoice_id) == $invoice->id ? 'selected' : '' }}>
                                                    {{ $invoice->sales_order_id}}-{{ optional($invoice->customer)->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="invoice_id"></label>
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i>
                                            Show</button>

                                    </div>
                                    <div class="col-md-1">
                                        <label for="invoice_id"></label>

                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('sales.sales-returns.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">

                                    <div class="col-md-4 mt-4">
                                        <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                        <input type="hidden" name="reference_invoice" id="reference_invoice" class="form-control" @if (request()->has('invoice_id')) value="{{ optional($products->first())->salesOrder->sales_order_id ?? '' }}" @endif>
                                        <input type="hidden" name="customer_id" id="customer_id" class="form-control"  @if (request()->has('invoice_id')) value="{{ optional($products->first())->salesOrder->customer_id ?? '' }}" @endif>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                                            @if (request()->has('invoice_id')) value="{{ optional($products->first())->salesOrder->customer->company_name ?? '' }}" @endif
                                            readonly>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label for="customer_phone">Customer Phone</label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control" @if (request()->has('invoice_id')) value="{{ optional($products->first())->salesOrder->customer->phone ?? '' }}" @endif readonly placeholder="Customer Phone">
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <label for="return_date">Return Date<span class="text-danger">*</span></label>
                                        <input type="text" name="return_date" class="form-control flatdate"
                                            id="return_date" placeholder="Return Date"
                                            value="{{ old('return_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4 mt-4"></div>
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
                                                        <th style="width: 8%">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (old('product_ids'))
                                                        @foreach (old('product_ids') as $key => $product_id)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]"
                                                                        value="{{ $product_id }}">
                                                                    <input type="text" name="product_name[]"
                                                                        value="{{ old('product_name')[$key] }}"
                                                                        class="form-control" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="delivered_quantity[]"
                                                                        id="delivered_quantity"
                                                                        value="{{ old('delivered_quantity')[$key] }}"
                                                                        class="form-control" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" id="quantity"
                                                                        value="{{ old('quantity')[$key] }}"
                                                                        class="form-control" placeholder="Quantity">
                                                                </td>
                                                                <td><input type="text" name="price[]" id="price"
                                                                        value="{{ old('price')[$key] }}"
                                                                        class="form-control" placeholder="Price"> </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        value="{{ old('amount')[$key] }}" id="amount"
                                                                        name="amount[]" readonly value="0">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="unit_discount" name="unit_discount[]"
                                                                        value="{{ old('unit_discount')[$key] ?? 0 }}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_discount" name="total_discount[]"
                                                                        value="{{ old('total_discount')[$key] ?? 0 }}">
                                                                </td>

                                                                <td>
                                                                    <input type="checkbox" name="checks[]"
                                                                        class="form-check-input" id="check"
                                                                        value="1" @if (array_key_exists($key, old('checks') ?? []) && old('checks')[$key] == 1) checked @endif>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @elseif(request()->has('invoice_id'))
                                                        @foreach ($products as $key => $product)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]"
                                                                        value="{{ $product->product->id }}">
                                                                    <input type="text" name="product_name[]"
                                                                        id="product_name"
                                                                        value="{{ $product->product->name }}"
                                                                        class="form-control" placeholder="Product Name"
                                                                        readonly>
                                                                </td>
                                                              @php
                                                                

                                                                // Get Sales Order ID
                                                                $salesOrderId = $product->sales_order_id ?? ($product->salesOrder->id ?? null);

                                                                // Calculate total returned quantity
                                                                $OldReturnQty = 0;
                                                                if ($salesOrderId) {
                                                                    $OldReturnQty = Modules\Sales\Models\SalesReturn::where('sales_order_id', $salesOrderId)
                                                                        ->where('status', 'Returned')
                                                                        ->whereHas('salesReturnDetails', function ($query) use ($product) {
                                                                            $query->where('product_id', $product->product_id);
                                                                        })
                                                                        ->with(['salesReturnDetails' => function ($query) use ($product) {
                                                                            $query->where('product_id', $product->product_id);
                                                                        }])
                                                                        ->get()
                                                                        ->pluck('salesReturnDetails')
                                                                        ->flatten()
                                                                        ->sum('quantity');
                                                                }

                                                                // Get Delivery Details
                                                                $deliveryDetails = $product->salesOrder->delivery->deliveryDetails ?? collect();

                                                                // Check if product is serial based
                                                                $isSerial = $product->product->is_serial === 'yes';

                                                                // Calculate delivered quantity
                                                                $deliveredQty = $deliveryDetails
                                                                    ->where('product_id', $product->product_id)
                                                                    ->pluck('deliveryStocks')
                                                                    ->flatten();

                                                                $deliveredQty = $isSerial ? $deliveredQty->count() : $deliveredQty->sum('quantity');

                                                                // Final remaining quantity
                                                                $remainingQty = $deliveredQty - $OldReturnQty;
                                                            @endphp


                                                                
                                                                <td>
                                                                    <input type="text" name="delivered_quantity[]" id="delivered_quantity"
                                                                        value="{{ $remainingQty }}"
                                                                        class="form-control" placeholder="Delivered Quantity" readonly>

                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]"
                                                                        id="quantity" value="{{ $remainingQty ?? 0 }}"
                                                                        class="form-control" placeholder="Quantity"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="price[]" id="price"
                                                                        value="{{ numberFormat($product->price) }}"
                                                                        class="form-control" placeholder="Price" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="unit_discount" name="unit_discount[]"
                                                                        value="{{ numberFormat($product->unit_discount) ?? 0 }}" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_discount" name="total_discount[]"
                                                                        value="{{ numberFormat($product->total_discount) ?? 0 }}" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="amount" name="amount[]"
                                                                        value="{{ numberFormat($product->amount) }}" readonly
                                                                        value="0">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox"
                                                                        name="checks[{{ $key }}]" 
                                                                        class="form-check-input" id="check" 
                                                                        {{-- @if($product->stocks()->get()->sum('avaible_stock') <= 0)  disabled @endif --}}
                                                                        value="1">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;">Main Inv Discount
                                                        </td>
                                                        <td colspan="2">
                                                            <input type="hidden" name="deliveries_id" id="deliveries_id" value="{{ optional($delivery)->id }}">

                                                            <input type="hidden" id="sales_order_id" name="sales_order_id" value="{{ optional($products->first())->sales_order_id }}">
                                                            <input type="text" class="form-control text-center"
                                                                value="{{ numberFormat(optional(optional($products->first())->salesOrder)->discount) }}"
                                                                id="discount" name="discount" readonly>
                                                        </td>
                                                        <td colspan="2" style="text-align: right;">
                                                            Total Amount
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control text-center"
                                                                id="total_amount" name="total_amount" readonly>
                                                        </td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <td colspan="6" style="text-align: right;">Net Amount</td>
                                                        <td><input type="text" class="form-control text-center"
                                                                id="net_amount" name="net_amount" readonly
                                                                value="{{ old('net_amount') }}"></td>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-12 pt-25">
                                            <div class="form-group">
                                                <textarea name="remarks" id="remarks" class="form-control w-100" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>

                                         <div class="col-md-12 my-2">
                                            <h4>Refund Payments Information:</h4>
                                        </div>
                                        <div class="col-md-12">
                                            @include('Account::payments.make-payments.payments-details', ['payments' => []])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
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
            $('.form-check-input').on('change', function() {
                var checkbox = $(this);
                var quantityInput = checkbox.closest('tr').find('#quantity');
                var deliveredQuantityInput = checkbox.closest('tr').find('#delivered_quantity');

                if (checkbox.is(':checked')) {
                    quantityInput.prop('readonly', false);
                    quantityInput.on('keyup', function() {
                        var quantityValue = parseInt(quantityInput.val());
                        var deliveredQuantityValue = parseInt(deliveredQuantityInput.val());
                        console.log(deliveredQuantityValue, quantityValue);


                        if (quantityValue > deliveredQuantityValue) {
                            toastr.error('Quantity cannot be greater than delivered quantity');
                            quantityInput.val(deliveredQuantityInput.val());
                        }
                    });
                } else {
                    quantityInput.prop('readonly', true);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function calculateRowAmount(row) {
                const qty = parseFloat(row.find("#quantity").val()) || 0;
                const price = parseFloat(row.find("#price").val()) || 0;
                const amount = qty * price;
                const unitDiscount = parseFloat(row.find("#unit_discount").val()) || 0;
                const totalDiscount = unitDiscount * qty; // Assuming total discount is unit discount multiplied by quantity
                row.find("#total_discount").val(totalDiscount.toFixed()); // Set total discount
                row.find("#amount").val(amount);
            }
    
            function calculateTotalAmount() {
                let totalAmount = 0;
                let totalDiscount = 0;
                $("#product_info_table tbody tr").each(function() {
                    if ($(this).find('.form-check-input').is(':checked')) {
                        const amount = parseFloat($(this).find("#amount").val()) || 0;
                        totalAmount += amount;
                        const discount = parseFloat($(this).find("#total_discount").val()) || 0;
                        totalDiscount += discount;

                    }
                });
                $("#total_amount").val(totalAmount);
                $("#discount").val(totalDiscount); // Set total discount
                calculateNetAmount(); // Call calculateNetAmount here
            }
    
            function calculateNetAmount() {
                const totalAmount = parseFloat($("#total_amount").val()) || 0;
                const discount = parseFloat($("#discount").val()) || 0;
                const netAmount = totalAmount - discount;
                $("#net_amount").val(Math.round(netAmount));
                updatePayable(Math.round(netAmount));
            }
    
            // Calculate amount for each row on page load
            $("#product_info_table tbody tr").each(function() {
                calculateRowAmount($(this));
            });
            calculateTotalAmount();
    
            $(document).on('change', '.form-check-input', function() {
                calculateTotalAmount();
            });
    
            $(document).on('keyup', '#quantity', function() {
                const row = $(this).closest('tr');
                calculateRowAmount(row);
                calculateTotalAmount();
            });
    
            $("#discount").on("keyup", function() {
                calculateNetAmount();
            });
        });
    </script>
    @stack('script')
@endsection
