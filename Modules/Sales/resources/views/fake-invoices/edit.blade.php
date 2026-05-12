@section('title', 'Fake Invoice Edit')
@section('description', 'Fake Invoice Edit')
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
                                        {{ trans('Fake Invoice Edit') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('sales.fake-invoices.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            <a href="{{ route('sales.fake-invoices.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Fake Invoice Edit') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.fake-invoices.update', $fakeInvoice->id) }}" method="POST"
                                enctype="multipart/form-data" id="updateForm">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="invoice_number">Invoice Number</label>
                                            <input type="text" name="invoice_number" class="form-control"
                                                id="invoice_number" placeholder="Invoice Number"
                                                value="{{ old('invoice_number', $fakeInvoice->invoice_number) }}" readonly>
                                            <input type="hidden" name="sales_order_id" value="{{ $fakeInvoice->sales_order_id }}">

                                        </div>
                                    </div>
                                   
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Sales Date</label>
                                            <input type="date" name="invoice_date" class="form-control flatdate" id="invoice_date"
                                                placeholder="Invoice Date" value="{{$fakeInvoice->invoice_date}}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select">
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id', $fakeInvoice->customer_id) == $customer->id ? 'selected' : '' }}>

                                                        {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null)
                                                            ({{ $customer->area->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                                        {{-- @dd($fakeInvoice->details) --}}
                                                        <tbody>
                                                            @foreach ($fakeInvoice->details as $detail)
                                                                <tr>
                                                                    <td>
                                                                        {{-- <input type="text" name="product_ids[]" id="product_id" class="form-control product_ids" placeholder="Product Name"> --}}
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids to-select"> 
                                                                            <option value="{{ $detail->product_id }}" selected>
                                                                                {{ $detail->product->name }}
                                                                            </option>

                                                                        </select>
                                                                        <input type="hidden" name="fake_invoice_detail_id[]" value="{{ $detail->id }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]" id="quantity"
                                                                            class="form-control" placeholder="Quantity" value="{{ numberFormat($detail->quantity) }}">
                                                                    </td>
                                                                    <td><input type="text" name="price[]" id="price"
                                                                            class="form-control" placeholder="Price"  value="{{ numberFormat($detail->price) }}">
                                                                    </td>
                                                                    <td><input type="text" name="unit_discount[]"
                                                                            id="unit_discount" class="form-control" value="{{ numberFormat($detail->unit_discount) }}"
                                                                            placeholder="Unit Discount"></td>
                                                                    <td><input type="text" name="total_discount[]"
                                                                            id="total_discount" class="form-control" value="{{ numberFormat($detail->total_discount) }}"
                                                                            placeholder="Total Discount" readonly></td>
                                                                    <td>
                                                                        <input type="text" class="form-control text-center"
                                                                            id="amount" name="amount[]" readonly
                                                                            value="{{ numberFormat($detail->amount) }}">
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
                                                                        value="{{ old('total_amount', numberFormat($fakeInvoice->total_amount)) }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Discount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="discount" name="discount"
                                                                        value="{{ old('discount', numberFormat($fakeInvoice->discount)) }}" readonly></td>
                                                            </tr>
                                                          
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total" name="total" readonly
                                                                        value="{{ old('total',  numberFormat($fakeInvoice->total)) }}"></td>
                                                            </tr>
                                                           

                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Net Amount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="net_amount" name="net_amount" readonly
                                                                        value="{{ old('net_amount', numberFormat($fakeInvoice->net_amount)) }}"></td>

                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <input type="text" name="remarks" class="form-control"
                                                            id="remarks" placeholder="Remarks"
                                                            value="{{ old('remarks', $fakeInvoice->remarks) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i>
                                                            Update
                                                        </button>

                                                    
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

                    if (data.customers.vat_status == 1) {
                        $('#vat_percentage').val(.05);
                    } else {
                        $('#vat_percentage').val(0);
                    }
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
        rowTemplate.find('.product_ids option').remove(); 

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
  
        $("#add_row").click(function () {
            const newRow = rowTemplate.clone();
            
            // Reset discount_type field in new row 
            $("#product_info_table tbody").append(newRow);

            prouctAutocompleteLoad(newRow);
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

    function prouctAutocompleteLoad(row){
        const p = $(row).find(".product_ids");
        const productSelect = new TomSelect(p[0], {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        productSelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        @if(request('product_ids'))
            productSelect.addOption({
                id: "{{ request('product_ids') }}",
                text: "{{ request('product_ids') }}"
            });
            productSelect.setValue("{{ request('product_ids') }}");
        @endif
    }
    
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
