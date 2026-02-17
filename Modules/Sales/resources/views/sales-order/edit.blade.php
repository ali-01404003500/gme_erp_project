{{-- @dd($salesOrder->source_type    ) --}}
@section('title', 'Sales Order Edit')
@section('description', 'Sales Order Edit')
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
                                        {{ trans('menu.update-sales-order-mqenu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('sales.sales-orders.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            <a href="{{ route('sales.sales-orders.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-sales-order-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.sales-orders.update', $salesOrder->id) }}" method="POST"
                                enctype="multipart/form-data" id="updateForm">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4"  @if(!isset($salesOrder->service_id)) style="display:none;" @endif>
                                        <div class="form-group">
                                            <label for="service_id">Service Token ID <span class="text-danger">*</span></label>
                                           <select name="service_id" id="service_id" class="form-control tom-select"
                                            @if(!isset($selected_service_id)) disabled @endif>
                                            <option value="">Choose Service Token ID</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    data-customer-id="{{ @$service->serviceTokens->first()->customer_id }}"
                                                    data-customer-name="{{ @$service->serviceTokens->first()->customer->company_name }}"
                                                    {{ (old('service_id', $salesOrder->service_id) == $service->id ) ? 'selected' : '' }}>
                                                    {{ $service->service_unique_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    {{-- @dd($salesOrder->source, ) --}}
                                    @if ($salesOrder->source != null  && $salesOrder->source_type == "Modules\Sales\Models\SalesRequisition")
                                         <div class="col-md-6 mt-4">

                                            <div class="form-group">
                                                <label for="invoice_id">Ref. Requisition ID</label>
                                                <input type="text" name="invoice_id" class="form-control" id="invoice_id"
                                                    value="{{ $salesOrder->source->invoice_id }}" readonly>
                                            </div>

                                        </div>
                                    @endif
                                    <div class="col-md-12 text-end">
                                        Balance: <span id="balance"></span>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select">
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->company_name }} - {{ $customer->area->area ?? '' }}
                                                       
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <div class="form-group">
                                            <label for="additional_phone">Additional Phone No</label>
                                            <input type="text" name="additional_phone" class="form-control"
                                                id="additional_phone" placeholder="Additional Phone No"
                                                value="{{ old('additional_phone', $salesOrder->additional_phone) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Sales Date</label>
                                            <input type="date" name="invoice_date" class="form-control flatdate invoice_date_input" id="invoice_date"
                                                placeholder="Invoice Date" value="{{$salesOrder->invoice_date}}"
                                                readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <div class="row col-md-12">
                                                    <div class="col-md-2">
                                                        <div class="checkbox-theme-default custom-checkbox m-4">
                                                            <input class="checkbox sales-type-checkbox" name="sales_type" type="checkbox" id="partial_sales" value="partial_sales" @if($salesOrder->sales_type == 'partial_sales') checked @endif>
                                                            <label for="partial_sales">
                                                                <span class="checkbox-text">Partial Sales</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox-theme-default custom-checkbox m-4">
                                                            <input class="checkbox sales-type-checkbox" name="sales_type" type="checkbox" id="free_sales" value="free_sales" @if($salesOrder->sales_type == 'free_sales') checked @endif>
                                                            <label for="free_sales">
                                                                <span class="checkbox-text">Free Sales</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="checkbox-theme-default custom-checkbox m-4">
                                                            <input class="checkbox" name="is_offer" value="1"
                                                                type="checkbox" id="is_offer" @if($salesOrder->is_offer == 1) checked @endif>
                                                            <label for="is_offer">
                                                                <span class="checkbox-text">
                                                                    Include Offer Product
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="reference_section" style="display: none;">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="reference_no">Reference No </label>
                                                            <select name="reference_id" id="reference_no" class="form-control tom-select">
                                                                <option value="">Choose Reference No</option>
                                                                @foreach ($references as $reference)
                                                                    <option value="{{ $reference->id }}"
                                                                        {{ old('reference_id', $salesOrder->reference_id) == $reference->id ? 'selected' : '' }} data-customer_id="{{ $reference->customer_id }}">
                                                                        {{ $reference->sales_order_id }} ({{  $reference->invoice_date }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="product_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 25%">Product Name</th>
                                                                <th style="width: 15%">Quantity</th>
                                                                <th style="width: 15%">Price</th>
                                                                <th style="width: 15%">Unit Discount</th>
                                                                <th style="width: 15%">Total Discount</th>
                                                                <th style="width: 15%">Amount</th>
                                                                <th style="width: 8%" style="text-align: right;">
                                                                    <button type="button" class="btn btn-info btn-sm"
                                                                        id="add_row">
                                                                        <i class="fa fa-plus"></i> Add</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @dd($salesOrder->salesOrderDetails->where('is_offers_product', false)) --}}
                                                            @foreach ($salesOrder->salesOrderDetails->where('is_offers_product', false) as $salesOrderDetail)
                                                                <tr>
                                                                    <td>
                                                                        {{-- <input type="text" name="product_ids[]" id="product_id" class="form-control product_ids" placeholder="Product Name"> --}}
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids to-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}" @if($salesOrderDetail->product_id == $product->id) selected @endif>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach

                                                                        </select>
                                                                        <input type="hidden" name="sales_order_detail_id[]" value="{{ $salesOrderDetail->id }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]" id="quantity"
                                                                            class="form-control" placeholder="Quantity" value="{{ numberFormat($salesOrderDetail->quantity) }}">
                                                                    </td>
                                                                    <td><input type="text" name="price[]" id="price"
                                                                            class="form-control" placeholder="Price" readonly value="{{ numberFormat($salesOrderDetail->price) }}">
                                                                    </td>
                                                                    <td><input type="text" name="unit_discount[]"
                                                                            id="unit_discount" class="form-control unit_discount_input @if($salesOrderDetail->discount_type == 'range') discount_range @endif" value="{{ numberFormat($salesOrderDetail->unit_discount) }}"
                                                                            placeholder="Unit Discount" @if ( $salesOrder->is_offer != 1 && $salesOrderDetail->discount_type == 'percentage') readonly @endif>
                                                                        <input type="hidden" name="discount_type[]" value="{{ $salesOrderDetail->discount_type }}" class="discount_type_input" /></td>
                                                                    <td><input type="text" name="total_discount[]"
                                                                            id="total_discount" class="form-control" value="{{ numberFormat($salesOrderDetail->total_discount) }}"
                                                                            placeholder="Total Discount" readonly></td>
                                                                    <td>
                                                                        <input type="text" class="form-control text-center"
                                                                            id="amount" name="amount[]" readonly
                                                                            value="{{ numberFormat($salesOrderDetail->amount) }}">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs"
                                                                            id="remove_row"
                                                                            onclick="removeRow(this)">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">
                                                                    Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_amount" name="total_amount" readonly
                                                                        value="{{ old('total_amount', numberFormat($salesOrder->total_amount)) }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Discount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="discount" name="discount"
                                                                        value="{{ old('discount', numberFormat($salesOrder->discount)) }}" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Commission
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="commission" name="commission"
                                                                        value="{{ old('commission',numberFormat( $salesOrder->commission)) }}" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total" name="total" readonly
                                                                        value="{{ old('total',  numberFormat($salesOrder->total)) }}"></td>
                                                            </tr>
                                                            {{-- @dd() --}}
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">VAT(5)%</td>
                                                                <td>
                                                                    <input type="hidden" id="vat_percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="vat" name="vat" readonly
                                                                        value="{{ $salesOrder->vat??old('vat', numberFormat($salesOrder->vat)) }}">
                                                                    </td>
                                                            </tr>

                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Net Amount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="net_amount" name="net_amount" readonly
                                                                        value="{{ old('net_amount', numberFormat($salesOrder->net_amount)) }}"></td>

                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <input type="text" name="remarks" class="form-control"
                                                            id="remarks" placeholder="Remarks"
                                                            value="{{ old('remarks', $salesOrder->remarks) }}">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="row mt-3">
                                                    <div class="col-md-12 mt-3 mb-3">
                                                        <h5 class="text-uppercase">Payment Information</h5>
                                                    </div>
                                                    {{-- @dd($serviceMyTask?->payments) --}}
                                                    <div class="col-md-12">
                                                        @include("Services::service-my-task.paymets", ['payments' => $salesOrder->payments??null])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="border p-2">
                                                        <legend class="float-none w-auto p-2">
                                                            Shipment Information
                                                            <input type="checkbox" name="is_shipment" value='1' @if ($salesOrder->is_shipment == 1) checked @endif
                                                                id="shipmentConfirm" tabindex="1015">
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                {{-- @dd($salesOrder->shipment) --}}
                                                                <select name="area_id" id="area_id" class="form-select tom-select" disabled>
                                                                    <option value="address" selected>New Address</option>
                                                                    @foreach ($areas as $area)
                                                                        <option value="{{ $area->id }}" {{ optional($salesOrder->shipment)->area_id == $area->id ? 'selected' : '' }}>
                                                                            {{ $area->area }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="address"
                                                                        class="form-control" id="address"
                                                                        placeholder="Shipping Address" 
                                                                        value="{{ old('address', optional($salesOrder->shipment)->address) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_name"
                                                                        class="form-control" id="contact_person_name"
                                                                        placeholder="Contact Person Name" value="{{ old('contact_person_name', optional($salesOrder->shipment)->contact_person_name) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_number"
                                                                        class="form-control" id="contact_person_phone"
                                                                        placeholder="Contact Person Phone" value="{{ old('contact_person_number', optional($salesOrder->shipment)->contact_person_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <div class="my-2">
                                                        <div class="form-group">
                                                            <label for="delivery_date">Delivery Date</label>
                                                            <input type="text" name="delivery_date" class="form-control flatdate" id="delivery_date"
                                                                placeholder="Delivery Date" value="{{ old('delivery_date', date('Y-m-d')) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <fieldset class="border p-2">
                                                        <legend class="float-none w-auto p-2">
                                                            Courier Information
                                                            <input type="checkbox" name="is_courier" value='1' @if($salesOrder->shipment?->is_courier == 1) checked @endif
                                                         id="courierConfirm" tabindex="1019">
                                                        </legend>
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <select name="courier_id" id="courier_id"
                                                                    class="form-select tom-select" disabled>
                                                                    <option value="">Search Courier Name</option>
                                                                    @foreach ($couriers as $courier)
                                                                        <option value="{{ $courier->id }}" {{ $salesOrder->shipment?->courier_id == $courier->id ? 'selected' : '' }}>
                                                                            {{ $courier->courier_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row condition_div">
                                                            <div class="col-md-6">
                                                                <div class="input-group align-items-center">
                                                                    <label for="additional_amount"
                                                                        class="input-group-text">Additional Amount</label>
                                                                    <input type="text" name="additional_amount"
                                                                        id="additional_amount" class="form-control"
                                                                        value="{{ $salesOrder->shipment?->additional_amount }}" disabled>
                                                                </div>
                                                            </div>
                                                            {{-- @dd() --}}
                                                            <div class="col-md-6">
                                                                <label for="condition"
                                                                    class="form-label">Condition</label>
                                                                <input type="checkbox" name="condition" id="condition" data-condition="{{$salesOrder->customer?->customerSetting?->first()?->minimum_condition_bill}}"
                                                                    tabindex="1020" {{ $salesOrder->shipment?->condition ? 'checked' : '' }}>
                                                            </div>

                                                        </div>
                                                        <div class="row condition_div">
                                                            <p class="text-danger">(Previous Due Adjust With Condition)</p>
                                                            <div>
                                                                <textarea name="condition_remarks" id="condition_remarks" class="form-control" placeholder="Remarks" {{ $salesOrder->shipment?->condition ? '' : 'disabled' }}>{{ $salesOrder->shipment?->condition_remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <input type="hidden" name="paid_status" id="paid_status" value="{{ old('paid_status', $salesOrder->paid_status ?? 'unpaid') }}">

                                            <input type="hidden" id="credit_limit" value="0">

                                            <div class="col-md-12">
                                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    <input type="hidden" name="status" id="status" value="pending">
                                                    @if(!request()->has('approve'))
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i>
                                                            Update
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.approve') && $salesOrder->status != "approved")
                                                        <button type="submit" id="approve" class="btn btn-success">
                                                            <i class="fa fa-check"></i>
                                                            Update and Approve
                                                        </button>
                                                    @elseif(hasPermission('sales.deliveries.create') && $salesOrder->status == "approved")
                                                        <a class="btn btn-success" type="button" role="button" href="{{ route('sales.deliveries.create', ['delivery_id' => $salesOrder->delivery->id]) }}">
                                                                <i class="fas fa-truck"></i>
                                                                Deliver
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    @include('Sales::sales-order.opt-verification')

                                    {{--Opt veriaficaion area --}}
                                    {{-- @dd($salesOrder->otpVerifications) --}}
                                    @foreach ($salesOrder->otpVerifications as  $otpVerification)
                                        <input type="hidden" name="otp_verifications[]" value="{{ json_encode($otpVerification) }}">
                                    @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @dd($salesOrder->otpVerifications) --}}

@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#approve').click(function() {
                $("#status").val("approved");
                return true;
            });
        });
    </script>
    <script>
    async function calculateDiscount(row = null) {

        let productsWithQty = {};
        $('#product_info_table tbody tr').each(function() {
            let productId = $(this).find('.product_ids').val();
            let quantity = $(this).find('input[name="quantity[]"]').val() || 1;
            console.log({productId, quantity});
            
            if (productId && quantity) {
                    // productsWithQty.push({
                    //     product_id: productId,
                    //     quantity: quantity
                    // });
                productsWithQty[productId] = quantity;
            }
            // product info
            // $(this).find('.unit_discount_input').val(0);
        });

        let invoiceDate = $('#invoice_date').val();
        let isOfferChecked = $('#is_offer').is(':checked');

        if (productsWithQty && isOfferChecked) {
            try {
                const offers = await $.ajax({
                    url: "{{ route('sales.sales-orders.calculate-discount') }}",
                    method: 'GET',
                    data: {
                        products: productsWithQty,
                        invoice_date: invoiceDate
                    }
                });

                // Assuming the response contains 'amount' for discount
                // and 'offer_id', 'discount_type' if needed
                // $('#discount').val(response.amount);
                for (let i = 0; i < offers.length; i++) {
                        const response =  offers[i];
                        if (response.products && response.products.length > 0) {
                            response.products.forEach(function(responseProduct) {
                                // Find the corresponding row in the table
                                $('#product_info_table tbody tr').each(function() {
                                    let rowProductId = $(this).find('.product_ids').val();
                                    if (parseInt(rowProductId) === parseInt(responseProduct.product_id)) {
                                        let unitDiscountInput = $(this).find('.unit_discount_input');
                                        let priceInput = $(this).find('#price');
                                        let quantityInput = $(this).find('input[name="quantity[]"]');
                                        let totalDiscountInput = $(this).find('#total_discount');
                                        let amountInput = $(this).find('#amount');

                                        let price = parseFloat(priceInput.val()) || 0;
                                        let quantity = parseFloat(quantityInput.val()) || 0;

                                        // Calculate unit discount based on discount type
                                        
                                        if(unitDiscountInput.siblings('.discount_type_input').val() === 'offer_percentage' || unitDiscountInput.siblings('.discount_type_input').val() === 'offer_fixed'){
                                            unitDiscountInput.val(0);
                                        }
                                        let unitDiscount = 0;

                                        if(quantity >= responseProduct.required_qty ){
                                            if (response.discount_type === 'percentage') {
                                                unitDiscount = (price * response.discount_value) / 100;
                                                unitDiscountInput.val(unitDiscount.toFixed());
                                                // Set the discount type
                                                unitDiscountInput.siblings('.discount_type_input').val('offer_percentage');

                                            } else if (response.discount_type === 'fixed') {
                                                // For fixed discount, we need to decide how to distributess it.
                                                // For simplicity, let's assume it's per unit.
                                                unitDiscount = parseFloat(response.discount_value);

                                                unitDiscountInput.val(unitDiscount.toFixed());
                                                // Set the discount type
                                                unitDiscountInput.siblings('.discount_type_input').val('offer_fixed');

                                                // let totalDiscount = unitDiscount ;
                                                // totalDiscountInput.val(totalDiscount.toFixed());
                                            }
                                        }
                                        let totalDiscount = unitDiscount * quantity ;
                                        totalDiscountInput.val(totalDiscount.toFixed());
                                        let amount = (price) * quantity;
                                        amountInput.val(amount.toFixed());
                                    }
                                });
                            });
                        }
                    }
                    // You might need to update total_discount for each product row
                    // or a general total discount field.
                    // For now, let's assume a single discount field for the whole order.
                    // If there are multiple products, you'd need to iterate and apply.
                    // For simplicity, applying to the main discount field.
                    calculateGrandTotal(); // Recalculate totals after discount
            } catch (xhr) {
                console.error("Error calculating discount:", xhr);
                // $('#discount').val(0); // Reset discount on error
                calculateGrandTotal();
            }
        } else {
            // $('#discount').val(0); // Reset discount if conditions are not met
            calculateGrandTotal();
        }
    }

    // Function to calculate grand total (assuming it exists or needs to be created)
    function calculateGrandTotal() {
        let totalAmount = 0;
        $('#product_info_table tbody tr').each(function() {
            let amount = parseFloat($(this).find('input[name="amount[]"]').val()) || 0;
            totalAmount += amount;
        });

        let discount = parseFloat($('#discount').val()) || 0;
        let vatPercentage = parseFloat($('#vat_percentage').val()) || 0; // Assuming VAT percentage is available
        let vat = (totalAmount - discount) * (vatPercentage / 100); // Calculate VAT
        let netAmount = totalAmount - discount + vat;

        $('#total_amount').val(totalAmount.toFixed());
        $('#vat').val(vat.toFixed());
        $('#total').val((totalAmount - discount).toFixed()); // Total after discount, before VAT
        $('#net_amount').val(netAmount.toFixed());
    }

    $(document).ready(function() {
        // Initial calculation on page load if needed
        calculateGrandTotal();

        // Event listeners
        $(document).on('change', '.product_ids, input[name="quantity[]"]', function() {
            const is_offer = $('#is_offer').is(':checked');
            if (is_offer) {
                calculateDiscount();
                calculateGrandTotal();
                return;
            }
            setTimeout(async  () => {

                await calculateDiscount();
                calculateTotals();
                calculateGrandTotal();
            }, 50);
        });

        $('#is_offer').on('change', function() {
            calculateDiscount();
            calculateTotals();
            calculateGrandTotal();
        });

        // Also trigger on invoice date change, as it's part of the discount calculation
        $('.invoice_date_input').on('change', function() {
            calculateDiscount();
            calculateTotals();
            calculateGrandTotal();
        });

        // Existing logic for adding rows (ensure new rows also trigger discount calculation)
        $('#add_row').on('click', function() {
            // ... existing add row logic ...
            // After adding a new row, re-initialize tom-select and attach event listeners
            // For example:
            // $(newRow).find('.product_ids').on('change', calculateDiscount);
            // $(newRow).find('input[name="quantity[]"]').on('input', calculateDiscount);
            // For now, assuming the delegated events handle new rows.
        });

        // Existing logic for removing rows
        $(document).on('click', '#remove_row', function() {
            // ... existing remove row logic ...
            // After removing a row, recalculate discount
            calculateDiscount();
            calculateGrandTotal();
        });
    });

    $(document).ready(function () {
        $('.sales-type-checkbox').on('change', function () {
            if ($(this).is(':checked')) {
                $('.sales-type-checkbox').not(this).prop('checked', false);
            }
        });
    });
</script>
    <script>
        $(document).ready(function () {
            function toggleReferenceSection() {
                if ($('#free_sales').is(':checked')) {
                    $('#reference_section').show();
                } else {
                    $('#reference_section').hide();
                }
            }

            // Initial load logic
            toggleReferenceSection();

            // Toggle on checkbox change
            $('#free_sales').on('change', function () {
                toggleReferenceSection();
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const customerSelect = $('#customer_id');
            const referenceSelect = $('#reference_no');
            const referenceTomSelect = referenceSelect[0].tomselect;
            const customerTomSelect = customerSelect[0].tomselect;

            // Store original options
            const originalReferenceOptions = referenceTomSelect.options;

            // Function to filter references

            function filterReferences() {
                const customerId = customerSelect.val();
                const currentRefValue = referenceTomSelect.getValue();

                referenceTomSelect.clearOptions();


                for (const key in originalReferenceOptions) {
                    const option = originalReferenceOptions[key];
                    // console.log({option});
                    
                    if (option.customer_id == customerId) {
                        referenceTomSelect.addOption(option);
                    }
                }

                if (referenceTomSelect.options[currentRefValue]) {
                    referenceTomSelect.setValue(currentRefValue);
                } else {
                    referenceTomSelect.clear();
                }
            }

            customerSelect.on('change', filterReferences);

            // Initial filter on page load
            filterReferences();
        });
    </script>
    <script>
        $(document).ready(function() {
            const customerSelect = $('#customer_id');
            const shipmentConfirmCheckbox = $('#shipmentConfirm');
            const courierConfirmCheckbox = $('#courierConfirm');
            const conditionCheckbox = $('#condition');

            const shipmentFields = [
                $('#area_id'),
                $('#address'),
                $('#contact_person_name'),
                $('#contact_person_phone')
            ];

            const courierFields = [
                $('#courier_id'),
                $('#condition')
            ];

            const conditionFields = [
                $('#additional_amount'),
                $('#condition_remarks')
            ];

            function toggleFields(fields, enable) {
                fields.forEach(field => {
                    if( enable ) {
                        field.removeAttr('disabled');
                        if(field.prop('tomselect')){
                            field.prop('tomselect').enable();
                        }
                    } else {
                        field.attr('disabled', true);
                        if(field.prop('tomselect')){
                            field.prop('tomselect').disable();
                        }
                    }
                });
            }

            function handleCustomerSelection() {
                const customerSelected = customerSelect.val() !== "";
                shipmentConfirmCheckbox.prop('disabled', !customerSelected);
                courierConfirmCheckbox.prop('disabled', !customerSelected);

                if (!customerSelected) {
                    shipmentConfirmCheckbox.prop('checked', false);
                    courierConfirmCheckbox.prop('checked', false);
                    conditionCheckbox.prop('checked', false);
                    toggleFields(shipmentFields, false);
                    toggleFields(courierFields, false);
                    toggleFields(conditionFields, false);
                }
            }

            function handleShipmentConfirm() {
                const isChecked = shipmentConfirmCheckbox.is(':checked');
                if (courierConfirmCheckbox.is(':checked') !== isChecked) {
                    courierConfirmCheckbox.prop('checked', isChecked);
                    handleCourierConfirm(); // Manually call to update fields
                }
                toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
            }

            function handleCourierConfirm() {
                const isChecked = courierConfirmCheckbox.is(':checked');
                if (shipmentConfirmCheckbox.is(':checked') !== isChecked) {
                    shipmentConfirmCheckbox.prop('checked', isChecked);
                    handleShipmentConfirm(); // Manually call to update fields
                }

                toggleFields(courierFields, courierConfirmCheckbox.is(':checked'));
                if (!courierConfirmCheckbox.is(':checked')) {
                    conditionCheckbox.prop('checked', false);
                    toggleFields(conditionFields, false);
                }
            }

            function handleCondition() {
                toggleFields(conditionFields, conditionCheckbox.is(':checked'));
            }

            customerSelect.on('change', handleCustomerSelection);
            shipmentConfirmCheckbox.on('change', handleShipmentConfirm);
            courierConfirmCheckbox.on('change', handleCourierConfirm);
            conditionCheckbox.on('change', handleCondition);

            // Initial state
            handleCustomerSelection();
            handleShipmentConfirm();
            handleCourierConfirm();
            handleCondition();
        });
    </script>




    <script>
    $(document).ready(function() {
        $('#customer_id').change(getCustomerSettings);
        getCustomerSettings();

            $(document).on('change', '#area_id', function() {
                var value = $(this).val();
                if (!window.shipmentsOptions) return;
                // if (value === 'address') {
                //     clearFields();
                // } else {
                //     // Removed redundant customer settings fetch
                // }
                
                const selectedOption = window.shipmentsOptions.find(option => option.area == value);
                console.log({ selectedOption, value, all: window.shipmentsOptions});
                
                if (selectedOption) {
                    $("#address").val(selectedOption.address);
                    $("#address1").val(selectedOption.address);
                    $("#contact_person_name").val(selectedOption.contact_person_name);
                    $("#contact_person_phone").val(selectedOption.phone);
                    $("#contact_person_phone1").val(selectedOption.phone);
                }
            })
        });




    function getCustomerSettings() {
        var id = $("#customer_id option:selected").val();
        if (id) {
            $.ajax({
                url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                success: function(data) {
                    console.log(data);

                    if (data && data.customers && data.customers.customer) {
                        const customerData = data.customers;

                        var area = data.customers.customer.area;
                        var area_id = area ? area.id : "address";
                        var area_name = area ? area.area : "New Address";

                        const credit_limit = customerData.credit_limit;
                        // console.log({customerData, credit_limit});
                        $("#credit_limit").val(credit_limit);

                        window.shipmentsOptions = [
                            {
                                area: area_id,
                                area_name: area_name,
                                address: area_name,
                                phone: data.customers.customer.phone,
                                contact_person_name: data.customers.customer.company_name
                            },
                        
                        ];

                        if (area_id !== 'address') {
                            window.shipmentsOptions = [...window.shipmentsOptions,
                                {
                                    area: "address",
                                    area_name: "New Address",
                                    address: "",
                                    phone: "",
                                    contact_person_name: "",
                                },
                            ];
                        }

                         if(data.latestShipmentAddress && data.latestShipmentAddress.length > 0){
                            window.shipmentsOptions = [
                                ...data.latestShipmentAddress.filter(address => address !== null).map(address => ({
                                    area: address.area_id??address.area_id,
                                    area_name: "(Shiping Address) "+address.address,
                                    address: address.address,
                                    phone: address.contact_person_number,
                                    contact_person_name: address.contact_person_name,
                                    courier_id: address.courier_id
                                })),
                                ...window.shipmentsOptions,
                            ];
                        }
                        console.log({shipmentsOptions: window.shipmentsOptions});


                        const areaVal =  "{{ $salesOrder->shipment->area_id ?? 'address' }}";

                        // Update the area_id select element with the new options
                        $("#area_id")[0].tomselect.clearOptions();
                        $("#area_id")[0].tomselect.addOptions(window.shipmentsOptions.map (option => ({
                            value: option.area,
                            text: option.area_name
                        })));
                        $("#area_id")[0].tomselect.setValue(areaVal);

                        // Update the fields if the area is not "New Address"
                    if(areaVal == 'address'){
                            $("#address").val("{{ $salesOrder->shipment->address??'' }}");
                            $("#address1").val("{{ $salesOrder->shipment->address??'' }}");
                            $("#contact_person_name").val("{{ $salesOrder->shipment->contact_person_name??'' }}");
                            $("#contact_person_phone").val("{{ $salesOrder->shipment->contact_person_number??'' }}");
                            $("#contact_person_phone1").val("{{ $salesOrder->shipment->contact_person_number??'' }}");
                        } else if (area_id != 'address') {
                            const selectedOption = window.shipmentsOptions.find(option => option.area === area_id);
                            if (selectedOption) {
                                $("#address").val(selectedOption.address);
                                $("#address1").val(selectedOption.address);
                                $("#contact_person_name").val(selectedOption.contact_person_name);
                                $("#contact_person_phone").val(selectedOption.phone);
                                $("#contact_person_phone1").val(selectedOption.phone);
                            }
                        } else  {
                            clearFields();
                        }

                        if (data.customers.vat_status == 1) {
                            $('#vat_percentage').val(.05);
                        } else {
                            $('#vat_percentage').val(0);
                        }

                        if(data.customers.is_condition_bill){
                            //show the condition checkbox && codition remarks
                            $(".condition_div").show();
                            $('#condition').data('condition', data.customers.minimum_condition_bill); // full 2 , half 1
                        }else{
                            //hide the condition checkbox && codition remarks
                            $(".condition_div").hide();
                        }
                    }
                }
            });
            $.ajax({
                url: `{{ route('account.get-ballance') }}?account_id=${id}&type=customer`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        console.log(data);
                        let currentDate = new Date().toISOString().slice(0, 10); 
                        const balanceLink = "{{ route('account.report.customer-ledger', ['account_id' => 'AccountId']) }}".replace('AccountId', data.id) + `&from=2021-10-05&to=${currentDate}`;
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
    }

    function clearFields() {
        $("#address").val("");
        $("#contact_person_name").val("");
        $("#contact_person_phone").val("");
    }
    </script>

    

    <script type="text/javascript">
        function clearRow(row){
                $(row).find('input').val('');
                // Specifically reset discount_type field to empty
                $(row).find('.discount_type_input').val('');
                $(row).find('select').val('');
                $(row).find('select').each(function() {
                    this.tomselect?.clear();
                });
        }
          function calculateRow(row) {
                const qty = parseFloat(row.find("#quantity").val()) || 0;
                const price = parseFloat(row.find("#price").val()) || 0;
                const unitDiscount = parseFloat(row.find("#unit_discount").val()) || 0;

                const amount = qty * price;
                const totalDiscount = qty * unitDiscount;

                row.find("#amount").val(amount);
                row.find("#total_discount").val(totalDiscount);

                return {
                    amount,
                    totalDiscount
                };
            }
        function calculateTotals() {
                let totalAmount = 0;
                let totalDiscount = 0;
                let totalVat = 0;
                let vat = $('#vat_percentage').val();
                let flat_vat = vat? (totalAmount - totalDiscount) * vat : (Number($('#vat').val()) || 0);

                $("#product_info_table tbody tr").each(function() {
                    const {
                        amount,
                        totalDiscount: rowDiscount
                    } = calculateRow($(this));
                    totalAmount += amount;
                    totalDiscount += rowDiscount;
                });

                $("#total_amount").val(totalAmount);
                $("#discount").val(totalDiscount);
                $("#total").val(totalAmount - totalDiscount);
                $("#vat").val(flat_vat);
                $("#net_amount").val(totalAmount - totalDiscount + flat_vat);
                updatePayable(totalAmount - totalDiscount + flat_vat);
            }
        $(document).ready(function() {
            const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
            rowTemplate.find('input').val('');
            // Reset discount_type field in template
            rowTemplate.find('.discount_type_input').val('');
            rowTemplate.find('.to-select option:selected').removeAttr('selected');
            rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');


            $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
                    new TomSelect(this, {});
            });


          

            
            
            function updatePaidStatus() {
                // This function will calculate the paid status based on payment information
                // Get the due amount from the payment section if it exists
                let dueAmount = 0;
                let paidAmount = 0;
                
                // Look for payment due amount field in the payment section
                const dueField = $('[name="payments_due_amount"]');
                if(dueField.length > 0) {
                    dueAmount = parseFloat(dueField.val()) || 0;
                }
                
                // Calculate paid amount based on net amount and due amount
                const netAmount = parseFloat($("#net_amount").val()) || 0;
                paidAmount = netAmount - dueAmount;
                
                // Determine the paid status based on the due amount
                let paidStatus = 'unpaid'; // Default status
                const is_condition = $('#condition').is(':checked');

                if(dueAmount <= 0 && paidAmount >= netAmount && netAmount > 0) {
                    paidStatus = 'paid';
                } else if(dueAmount > 0 && paidAmount > 0) {
                    paidStatus = 'due'; // Partially paid
                    if(is_condition) {  
                        paidStatus = 'condition'; // Condition bill
                    }
                } else if(dueAmount > 0 && paidAmount <= 0) {
                    paidStatus = 'unpaid';
                }
                
                // Update the hidden paid_status field
                $("#paid_status").val(paidStatus);
            }


            $("#add_row").click(function() {
                const newRow = rowTemplate.clone();
                newRow.find('.to-select').each(function() {
                    new TomSelect(this, {});
                });
                // Reset discount_type field in new row
                newRow.find('.discount_type_input').val('');
                $("#product_info_table tbody").append(newRow);
            });

            $("#product_info_table tbody").on("keyup change", "#quantity, #price, #unit_discount", function() {
                calculateTotals();
                updatePaidStatus();
            });

            $("#product_info_table").on("click", "#remove_row", function() {
                const product_id = $(this).closest('tr').find('select.product_ids option:selected').text();
                deleteOtpVerification('Discount Changed for '+product_id);
                deleteOtpVerification(" Discount Range Exceeded for "+product_id);
                if($(this).closest('tbody').find('tr').length == 1){
                    clearRow($(this).closest('tbody tr'));
                }else{
                    $(this).closest('tr').remove();
                }
                calculateTotals();
                updatePaidStatus();
            });

            // Initial calculation for existing rows
            calculateTotals();
            updatePaidStatus();

            // Watch for changes in payment fields to update paid status
            $(document).on('input change', '[name="payments_due_amount"], [name="payments_payable_amount"], [name="payments_amount[]"],#condition', function() {
                updatePaidStatus();
            });



            $(document).on('change', '.product_ids',async function() {
                const product_id = $(this).val();
                if(product_id == "") {
                    return false;
                }
                if(product_id !="" && $(".product_ids [value='" + product_id + "']:selected").length > 1) {
                    //warning and clear 
                    clearRow($(this).closest('tr'));
                    toastr.warning('This Product is already selected');
                    return false;
                }
                else
                {
                    $("#add_row").click();
                }
                console.log('product changed');
                $(this).closest('tr').find('#quantity').val(1);
                await getProductPrice(this);
                const customer_id = $('#customer_id').val();
                
                console.log({customer_id, product_id});
                await getSalesDiscount(customer_id, product_id, this);
                // $(this).closest('tr').find('#quantity').trigger('change');
                calculateTotals();

            })

            $(document).on('keyup', '.discount_range',  function() {
                const discount_range = $(this).data('discount_range');
                const discount = $(this).val();
                if (discount < Number(discount_range.min) || discount > Number(discount_range.max)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }

            })
        });
    </script>

        <script>
            var selectedProductIds = []; // Array to store selected product IDs

            async function getProductPrice(selectElement) {
                var productId = selectElement.value;
                var priceInput = selectElement.closest('tr').querySelector('input[name="price[]"]');
                if (productId.trim() !== '') {
                    if (selectedProductIds.includes(productId)) {
                        // Same product selected again
                        showToast('warning', 'You have already selected this product.');
                        return;
                    }
                    try {
                        const response = await $.get('{{ route('purchase.get.product.list') }}?id=' + productId);
                        //  $.ajax({
                        //     url: '{{ route('purchase.get.product.list') }}',
                        //     method: 'GET',
                        //     data: {
                        //         id: productId
                        //     }
                        // });
                        var product = response[0];
                        if (!product) {
                            // Product not found
                            showToast('error', 'Price not found.');
                            priceInput.value = '';
                            salespriceInput.value = '';
                            return;
                        }
                        priceInput.value = product.mrp;
                        selectedProductIds.push(productId); // Add the selected product ID to the array
                    } catch (error) {
                        console.error(error);
                        // Show error message
                        showToast('error', 'An error occurred while fetching product details.');
                    }
                } else {
                    // Clear inputs if no product is selected
                    priceInput.value = '';
                    salespriceInput.value = '';
                }
            }

            function showToast(type, message) {
                // Display toast message
                if (type === 'warning') {
                    toastr.warning(message);
                } else if (type === 'error') {
                    toastr.error(message);
                }
            }


            async function getSalesDiscount(customerId, productId, element= null) {
                    try {
                        const discounts = await $.get(`{{ route('sales.get-sales-discount') }}?customer_id=${customerId}&product_id=${productId}`);
                        console.log({discount:discounts.discount});
                        $(element).closest('tr').find("#unit_discount").val(0);
                        $(element).closest('tr').find("#unit_discount").removeClass('discount_range');
                        $(element).closest('tr').find("#unit_discount").data('discount_range',null);
                        $(element).closest('tr').find("#unit_discount").removeAttr('readonly');
                        if(discounts.discount){
                            if(discounts.discount.percentage){
                                console.log(discounts.discount.percentage);
                                if(discounts.discount.percentage.percentage > 0){
                                    if(element){
                                        const percentage = discounts.discount.percentage.percentage;
                                        const price = $(element).closest('tr').find("#price").val();
                                        console.log(element);
                                        $(element).closest('tr').find("#unit_discount").val((percentage * price) / 100);
                                        // Set the discount type to percentage
                                        $(element).closest('tr').find(".discount_type_input").val('percentage');
                                        $(element).closest('tr').find("#unit_discount").attr('readonly','readonly');
                                    }
                                }
                            }else if(discounts.discount.productPrice){
                                console.log(discounts.discount.productPrice);
                                const discountPrice = discounts.discount.productPrice.sales_amounts;
                                const price = $(element).closest('tr').find("#price").val();
                                if( discountPrice < price){
                                    $(element).closest('tr').find("#unit_discount").val(price - discountPrice);
                                    // Set the discount type to fixed
                                    $(element).closest('tr').find(".discount_type_input").val('fixed');
                                    $(element).closest('tr').find("#unit_discount").attr('readonly','readonly');
                                }
                            }else if(discounts.discount.discountRange){
                                $(element).closest('tr').find("#unit_discount").data('discount_range',discounts.discount.discountRange);
                                $(element).closest('tr').find("#unit_discount").val(0);
                                // Set the discount type to range
                                $(element).closest('tr').find(".discount_type_input").val('range');
                                $(element).closest('tr').find("#unit_discount").addClass('discount_range');
                            }
                        }
                    } catch (error) {
                        console.error(error);
                        // Show error message
                        showToast('error', 'An error occurred while fetching sales discount.');
                    }
            }
        </script>
    <script src="https://cdn.jsdelivr.net/npm/modern-screenshot@4.6.7/dist/index.min.js"></script>
    <script>
        window.pendingCall = [];
        async function htmlToPng(element, options = {}) {
            return await modernScreenshot.domToPng(element, {
                quality: options.quality || 0.95,
                backgroundColor: options.backgroundColor || '#ffffff',
                scale: options.scale || window.devicePixelRatio || 2
            });
        }

        function checkExistingOtpVerifications() {
            const existingVerification = $('input[name="otp_verifications[]"]');
            for (let i = 0; i < existingVerification.length; i++) {
                const verificationData = JSON.parse(existingVerification[i].value);
                if (verificationData.status == "pending") {
                    if (verificationData.title === 'Invoice Date Changed') {
                        $('.invoice_date_input').trigger('change');
                    } else if (verificationData.title.startsWith('Discount Changed')) {
                        const product_id = verificationData?.details_data?.product_id?? verificationData.title.split('Discount Changed for ')[1];
                        const optionOfProduct = [...document.querySelectorAll('option[selected]')].find(option => option.textContent.trim() == product_id.trim());
                        console.log({optionOfProduct});

                        const closesetSelect = $(optionOfProduct).closest('tr').find(".unit_discount_input");
                        $(closesetSelect).trigger('change');
                    }else if( verificationData.title.startsWith(' Discount Range Exceeded for ')){ 
                        const product_id = verificationData?.details_data?.product_id?? verificationData.title.split(' Discount Range Exceeded for ')[1];

                        const optionOfProduct = [...document.querySelectorAll('option[selected]')].find(option => option.textContent.trim() == product_id.trim());
                        const closesetSelect = $(optionOfProduct).closest('tr').find(".unit_discount_input");
                        //add class discount_range class
                        closesetSelect.addClass('discount_range');
                        closesetSelect.data('discount_range', verificationData.details_data);
                        // console.log({"discount range": optionOfProduct.value, product_id});
                        $(closesetSelect).trigger('change');

                    } else if (verificationData.title === 'Remarks Changed') {
                        $('#remarks').trigger('change');
                    }
                }
            }
        }

        async function getOtpAdditionalData() {
            const tableElement = document.getElementById('product_info_table');
            const image = await htmlToPng(tableElement, { quality: 0.95 });
            const credit=$('#otpTableBody').find("#credit").val();
            const payment_mode=$('#otpTableBody').find("#payment_mode").val();
            const payment_date=$('#otpTableBody').find("#payment_date").val();

            const data = {
                image: [image],
                credit:credit,
                payment_mode:payment_mode,
                payment_date:payment_date,
                customer_name: $('#customer_id option:selected').text(),
            };
            console.log({data});
            
            return data;
        }


        function dueAmountChanged() {
            if ($('name["payments_due_amount"]').val() != 0) {
                $('name["payments_due_amount"]').addClass('opt-required');
                $('name["payments_due_amount"]').closest('div').attr("title", 'OPT required');
                const data = {
                    title: 'Due Amount Changed',
                    request_value: $('name["payments_due_amount"]').val(),
                };
                captureProductInfoTable();
                updateOtpVerification(data);
            } else {
                deleteOtpVerification('Due Amount Changed');
                $('name["payments_due_amount"]').removeClass('opt-required');
                $('name["payments_due_amount"]').closest('div').attr("title", '');
            }
        }

        function additionalSubmitPending(){
            // try {
            //     const dueAmount = parseFloat($('[name="payments_due_amount"]').val());
            //     const payableAmount = parseFloat($('[name="payments_payable_amount"]').val());
            //     if (dueAmount < payableAmount * 0.5) {
            //         return true;
            //     }
            //     toastr.error('Due amount should be more than 50% of the payable amount.');
            //     return false;
            // } catch (error) {
            //     console.error(error);
            //     return false;
            // }

            return true;
        }

        $(document).ready(function () {
            async function captureProductInfoTable() {
                const tableElement = document.getElementById('product_info_table');
                const image = await htmlToPng(tableElement, { quality: 0.95 });
                const data = {
                    image: [image],
                };
                updateOtpAdditionalData(data);
            }

            $('.invoice_date_input').on('change', function () {
                if ($('.invoice_date_input').val() != '{{ date('Y-m-d') }}') {
                    $('.invoice_date_input').addClass('opt-required');
                    $('.invoice_date_input').closest('div').attr("title", 'OPT required');
                    const data = {
                        title: 'Invoice Date Changed',
                        request_value: $('.invoice_date_input').val(),
                    };
                    captureProductInfoTable();
                    updateOtpVerification(data);
                } else {
                    deleteOtpVerification('Invoice Date Changed');
                    $('.invoice_date_input').removeClass('opt-required');
                    $('.invoice_date_input').closest('div').attr("title", '');
                }
            });

            // Function to check if OTP verification exists for a specific title
            function checkOtpVerificationExists(title) {
                const existingVerification = $('input[name="otp_verifications[]"]').filter(function() {
                    const existingData = JSON.parse($(this).val());
                    return existingData.title === title;
                });
                return existingVerification.length > 0;
            }

            $(document).on('change', '.unit_discount_input,#quantity', function () {
                const input = $("#discount");
                const unit_discount = $(this).closest('tr').find('.unit_discount_input').first();
                const isQuantityChange = $(this).attr('id') === 'quantity';

                 console.log("unit discount class", unit_discount);

                if (unit_discount.hasClass('discount_range')) {
                    console.log("discount range");

                    const discount_range = unit_discount.data('discount_range');
                    const product_id = unit_discount.closest('tr').find('select.product_ids option:selected').text();
                    const otp_title = " Discount Range Exceeded for "+product_id;

                    // If it's a quantity change and no existing OTP verification for this title, return early
                    if (isQuantityChange && !checkOtpVerificationExists(otp_title)) {
                        return;
                    }

                    if (Number(unit_discount.val()) < Number(discount_range.min) || Number(unit_discount.val()) > Number(discount_range.max)) {
                        unit_discount.addClass('is-invalid');
                        unit_discount.closest('td').attr("title", 'OPT required');

                        const data = {
                            title: otp_title,
                            request_value: unit_discount.val(),
                            details_data: {
                                product_id: product_id,
                                quantity: unit_discount.closest('tr').find('#quantity').val(),
                                price: unit_discount.closest('tr').find('#price').val(),
                                min_discount: discount_range.min,
                                max_discount: discount_range.max,
                                ...discount_range
                            }
                        };
                        // captureProductInfoTable();
                        updateOtpVerification(data);

                    } else {
                        deleteOtpVerification(otp_title);
                        // deleteOtpVerification('Discount Changed');
                        unit_discount.removeClass('is-invalid');
                        // unit_discount.removeClass('opt-required');
                        unit_discount.closest('td').attr("title", '');
                    }
                    return;
                }else{
                    const product_id = unit_discount.closest('tr').find('select.product_ids option:selected').text();
                    const otp_title = "Discount Changed for "+product_id;

                    // If it's a quantity change and no existing OTP verification for this title, return early
                    if (isQuantityChange && !checkOtpVerificationExists(otp_title)) {
                        return;
                    }

                    if (unit_discount.val() != 0) {
                        // console.log("discount changed", input.val());
                        unit_discount.addClass('opt-required');
                        unit_discount.closest('td').attr("title", 'OPT required');
                        const data = {
                            title: otp_title,
                            request_value: unit_discount.val(),
                            details_data: {
                                product_id: product_id,
                                quantity: unit_discount.closest('tr').find('#quantity').val(),
                                price: unit_discount.closest('tr').find('#price').val(),
                            }
                        };
                        // captureProductInfoTable();
                        updateOtpVerification(data);

                        // Set discount type to 'manual' when user manually enters discount
                        unit_discount.siblings('.discount_type_input').val('manual');
                    } else {
                        deleteOtpVerification(otp_title);
                        unit_discount.removeClass('opt-required');
                        unit_discount.closest('td').attr("title", '');
                        // Reset discount type to empty when discount is cleared
                        unit_discount.siblings('.discount_type_input').val('');
                    }
                }


            });

            $('#remarks').on('change', function () {
                if ($('#remarks').val() && $('#remarks').val().length > 3) {
                    $('#remarks').addClass('opt-required');
                    $('#remarks').closest('div').attr("title", 'OPT required');
                    const data = {
                        title: 'Remarks Changed',
                        request_value: $('#remarks').val(),
                    };
                    // captureProductInfoTable();
                    updateOtpVerification(data);
                } else {
                    deleteOtpVerification('Remarks Changed');
                    $('#remarks').removeClass('opt-required');
                    $('#remarks').closest('div').attr("title", '');
                }
            });

            $('input[name="payments_due_amount"], #condition').on('change', async function () {
                const dueAmount = $('input[name="payments_due_amount"]').val();
                console.log("changed due amount: ", dueAmount);
                console.log("changed: ", Number($('#credit_limit').val()));
                const is_condition = $('#condition').is(':checked');
                const is_full_condition = $('#condition').data('condition') == 2;

                console.log("is condition: ", {is_condition, is_full_condition, cod: $('#condition').data('condition'), due: Number(dueAmount) , limit: Number($('#credit_limit').val())});
                if ( is_condition ) {
                    // If is_full_condition is false, dueAmount must be less than 50% of credit_limit
                    const fiftyPercentOfCreditLimit = Number($('#net_amount').val()) * 0.5;
                    if (!is_full_condition  && Number(dueAmount) > fiftyPercentOfCreditLimit) {
                        console.log("hore then haft due");
                        
                        // Due amount exceeds 50% of credit limit - trigger credit limit exceeded
                        window.pendingCall.push(async function creditLimit() {
                            const productsTableElement = document.getElementById('product_info_table');
                            const imageProducts = await htmlToPng(productsTableElement, { quality: 0.95 });
                            const prementsTableElement = document.getElementById('payment-table');
                            const imagePayments = await htmlToPng(prementsTableElement, { quality: 0.95 });
                            const data = {
                                title: 'Credit Limit Exceeded',
                                request_value: dueAmount,
                                details_data:{
                                    credit_limit: dueAmount,
                                    due_amount: dueAmount,
                                    customer_info:{
                                        customer_id: $('#customer_id').val(),
                                        customer_name: $('#customer_id option:selected').text(),
                                        current_balance: dueAmount,
                                        credit_limit: $('#credit_limit').val(),
                                        ad_limit: $('#net_amount').val(),
                                        images: [imageProducts, imagePayments]
                                    }
                                }
                            };
                            // captureProductInfoTable();
                        await updateOtpVerification(data);
                        });
                    } else {
                        // Due amount is acceptable (less than 50% of credit limit)
                        window.pendingCall.push(async function creditLimit() {
                            await deleteOtpVerification('Credit Limit Exceeded');
                            console.log("Credit limit is not exceeded");
                        });
                    }
                } else if (Number(dueAmount) > Number($('#credit_limit').val())) {
                    console.log("check creaditi limit");
                    
                    window.pendingCall.push(async function creditLimit() {
                        const productsTableElement = document.getElementById('product_info_table');
                        const imageProducts = await htmlToPng(productsTableElement, { quality: 0.95 });
                        const prementsTableElement = document.getElementById('payment-table');
                        const imagePayments = await htmlToPng(prementsTableElement, { quality: 0.95 });
                        const data = {
                            title: 'Credit Limit Exceeded',
                            request_value: dueAmount,
                            details_data:{
                                credit_limit: dueAmount,
                                due_amount: dueAmount, 
                                customer_info:{
                                    customer_id: $('#customer_id').val(),
                                    customer_name: $('#customer_id option:selected').text(),
                                    current_balance: dueAmount,
                                    credit_limit: $('#credit_limit').val(),
                                    ad_limit: $('#net_amount').val(),
                                    images: [imageProducts, imagePayments]
                                }
                            }
                        };
                        // captureProductInfoTable();
                    await updateOtpVerification(data);
                    });
                }else{
                    window.pendingCall.push(async function creditLimit() {
                        await deleteOtpVerification('Credit Limit Exceeded');
                        console.log("Credit limit is not exceeded");
                    });
                }
                // dueAmountChanged();
            });
            

            checkExistingOtpVerifications();

            @if(($salesOrder->source_type == "Modules\Sales\Models\Quotation" || $salesOrder->source_type == "Modules\Services\Models\ServiceQuotation" ) && count($salesOrder->otpVerifications) ==  0)
            // $(".unit_discount_input").each(function() {
            //     if($(this).val() != 0) {
            //         $(this).trigger('change');
            //     }
                
            // });
                $('input[name="payments_due_amount"]').trigger('change');
            @endif
        });
    </script>

    @stack('script')
@endsection
