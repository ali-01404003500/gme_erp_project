@section('title', 'Fake Invoice Create')
@section('description', 'Fake Invoice Create')
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
                                        {{ trans('Fake Invoice Create') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('sales.fake-invoices.index'))
                                <a href="{{ route('sales.fake-invoices.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Fake Invoice Create') }}</h4>
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
                            <form action="{{ route('sales.fake-invoices.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">

                                    <div class="col-md-4 mt-4">
                                        <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" class="form-control" @if (request()->has('invoice_id')) value="{{ optional($salesOrder)->id ?? '' }}" @endif>
                                        <input type="hidden" name="customer_id" id="customer_id" class="form-control"  @if (request()->has('invoice_id')) value="{{ optional($salesOrder)->customer_id ?? '' }}" @endif>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                                            @if (request()->has('invoice_id')) value="{{ optional($salesOrder)->customer->company_name ?? '' }}" @endif
                                            readonly>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label for="customer_address">Customer Address<span class="text-danger">*</span></label>
                                        <input type="text" name="customer_address" id="customer_address" class="form-control" @if (request()->has('invoice_id')) value="{{ optional($salesOrder)->customer->address ?? '' }}" @endif readonly>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <label for="customer_phone">Customer Phone<span class="text-danger">*</span></label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                                            @if (request()->has('invoice_id')) value="{{ optional($salesOrder)->customer->phone ?? '' }}" @endif
                                            readonly>
                                     
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <label for="invoice_date">Fake Invoice Date<span class="text-danger">*</span></label>
                                        <input type="text" name="invoice_date" class="form-control flatdate"
                                            id="invoice_date" placeholder="Return Date"
                                            value="{{ old('invoice_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4 mt-4"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Product Information</h3>
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
                                                        {{-- @dd($salesOrder->salesOrderDetails) --}}
                                                        <tbody>
                                                        @if(request()->has('invoice_id'))

                                                            @foreach ($salesOrder->salesOrderDetails as $salesOrderDetail)
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
                                                                            class="form-control" placeholder="Price" value="{{ numberFormat($salesOrderDetail->price) }}">
                                                                    </td>
                                                                    <td><input type="text" name="unit_discount[]"
                                                                            id="unit_discount" class="form-control" value="{{ numberFormat($salesOrderDetail->unit_discount) }}"
                                                                            placeholder="Unit Discount"></td>
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
                                                            {{-- <tr>
                                                                <td colspan="5" style="text-align: right;">VAT(5)%</td>
                                                                <td>
                                                                    <input type="hidden" id="vat_percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="vat" name="vat" readonly
                                                                        value="{{ old('vat', numberFormat($salesOrder->vat)) }}">
                                                                    </td>
                                                            </tr> --}}

                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Net Amount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="net_amount" name="net_amount" readonly
                                                                        value="{{ old('net_amount', numberFormat($salesOrder->net_amount)) }}"></td>

                                                            </tr>
                                                           @endif

                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <input type="text" name="remarks" class="form-control"
                                                            id="remarks" placeholder="Remarks"
                                                            value="{{ old('remarks', @$salesOrder->remarks) }}">
                                                    </div>
                                                </div>
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
        $('#approve').click(function() {
            $("#status").val("approved");
            return true;
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
            if($(this)?.is(':checked')) {
                // checked in conditioncheckbox
                $(courierConfirm).prop('checked', true);
                //trigger change
                handleCourierConfirm();
            }
            //not checked
            if($(this)?.is(':not(:checked)')) {
                $(courierConfirm).prop('checked', false);
                handleCourierConfirm();
            }
            toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
        }

        function handleCourierConfirm() {
            toggleFields(courierFields, courierConfirmCheckbox.is(':checked'));
            if( $(this).is(':checked')) {
                // checked in couriercheckbox
                $(shipmentConfirm).prop('checked', true);
                //trigger change
                handleShipmentConfirm();
            }
            //not checked
            if($(this).is(':not(:checked)')) {
                $(shipmentConfirm).prop('checked', false);
                handleShipmentConfirm();
            }
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

        // getCustomerSettings();

        $(document).on('change', '#area_id', function() {
            var value = $(this).val();
            if (value === 'address') {
                clearFields();
            } else {
                getCustomerSettings();
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
                    var area = data.customers.customer.area;
                    var area_id = area ? area.id : "address";
                    var area_name = area ? area.area : "New Address";

                    // Update the area_id select element with the new option
                    // $("#area_id").html(`<option value="${area_id}">${area_name}</option>`);
                    // $("#area_id")[0].tomselect.clear();
                    // $("#area_id")[0].tomselect.addOption({ value: area_id, text: area_name });
                    // $("#area_id")[0].tomselect.setValue(area_id);

                    // Update the fields if the area is not "New Address"
                    if (area_id !== 'address') {
                        $("#address").val(area_name);
                        $("#contact_person_name").val(data.customers.customer.company_name);
                        $("#contact_person_phone").val(data.customers.customer.phone);
                    } else {
                        clearFields();
                    }

                    // if (data.customers.vat_status == 1) {
                    //     $('#vat_percentage').val(.05);
                    // } else {
                    //     $('#vat_percentage').val(0);
                    // }
                }
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
            $(row).find('select').val('');
            $(row).find('select').each(function() {
                this.tomselect?.clear();
            });
    }
    $(document).ready(function() {
        const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
        rowTemplate.find('input').val('');
        rowTemplate.find('.to-select option:selected').removeAttr('selected');
        rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');


        $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
                new TomSelect(this, {});
        });


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
            // let vat = $('#vat_percentage').val();

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
            $("#net_amount").val(totalAmount - totalDiscount);
        }

        $("#add_row").click(function() {
            const newRow = rowTemplate.clone();
            newRow.find('.to-select').each(function() {
                new TomSelect(this, {});
            });
            $("#product_info_table tbody").append(newRow);
        });

        $("#product_info_table tbody").on("keyup change", "#quantity, #price, #unit_discount", function() {
            calculateTotals();
        });

        $("#product_info_table").on("click", "#remove_row", function() {
            if($(this).closest('tbody').find('tr').length == 1){
                clearRow($(this).closest('tbody tr'));
            }else{
                $(this).closest('tr').remove();
            }
            calculateTotals();
        });

        // Initial calculation for existing rows
        // calculateTotals();


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
            $(this).closest('tr').find('#quantity').trigger('change');

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
                                    $(element).closest('tr').find("#unit_discount").attr('readonly','readonly');
                                }
                            }
                        }else if(discounts.discount.productPrice){
                            console.log(discounts.discount.productPrice);
                            const discountPrice = discounts.discount.productPrice.sales_amounts;
                            const price = $(element).closest('tr').find("#price").val();
                            if( discountPrice < price){
                                $(element).closest('tr').find("#unit_discount").val(price - discountPrice);
                                $(element).closest('tr').find("#unit_discount").attr('readonly','readonly');
                            }
                        }else if(discounts.discount.discountRange){
                            $(element).closest('tr').find("#unit_discount").data('discount_range',discounts.discount.discountRange);
                            $(element).closest('tr').find("#unit_discount").val(0);
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


@endsection
