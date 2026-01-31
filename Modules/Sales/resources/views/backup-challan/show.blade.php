@section('title', 'Backup/Challan Details')
@section('description', 'Details for Backup/Challan')
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
                                    <li class="breadcrumb-item active" aria-current="page">Backup/Challan Details</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary btn-sm" id="customExport" style="margin-right: 5px;">Custom Export</button>
                            <a href="{{ route('sales.backup-challans.show', $backupChallan->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                                @if(hasPermission('sales.backup-challans.index'))
                                <a href="{{ route('sales.backup-challans.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .my-header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }

                
                .my-header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .my-header h1 {
                    margin: 0;
                    font-size: 50px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .my-header p {
                    margin: 5px 0;
                    font-size: 16px;
                }

                .title {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .sales-order-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .sales-order-info .left,
                .sales-order-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .sales-order-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .sales-order-info th,
                .sales-order-info td {
                    padding: 5px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details {
                    margin-bottom: 20px;
                }

                .invoice-details table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                .invoice-details .totals {
                    text-align: right;
                }

                .invoice-details .totals p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }

                footer p {
                    margin: 10px 0;
                    font-size: 14px;
                    width: 45%;
                    text-align: center;
                }
            </style>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Backup/Challan Details</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body" id="printableArea">

                            <header class="my-header">
                                <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo" style="margin-top: -108px;">

                                <div>
                                    <h1>{{ $company_info->company_name }}</h1>
                                    <p>{{ $company_info->company_bio }}</p>
                                    <p>{{ $company_info->company_address }}</p>
                                    <p>Hotline: +88 09678 020555  Mobile: {{ $company_info->company_phone }}</p>
                                    <p>e-mail: {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                                </div>
                            </header>

                            <section class="title" style="margin-top: 40px;">
                                <h2>{{ $backupChallan->type }} Invoice</h2>
                            </section>


                            <section class="sales-order-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Remaining Date</th>
                                            <td>:</td>
                                            <th>{{ $backupChallan->remaining_date }}</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice Date</th>
                                            <td>:</td>
                                            <th> {{ $backupChallan->invoice_date }}</th>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>:</td>
                                            <th> {{ $backupChallan->type }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>:</td>
                                            <th>{{ $backupChallan->customer->company_name }}</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice No</th>
                                            <td>:</td>
                                            <th>{{ $backupChallan->invoice_no }}</th>
                                        </tr>
                                        <tr></tr>
                                    </table>
                                </div>
                            </section>


                            <section class="invoice-details">
                                <table>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($backupChallan->backupChallanDetails as $key => $backupChallanDetail)
                                            {{-- @dd($backupChallanDetail->product_id) --}}
                                            <tr>
                                                <td>
                                                    {{ $backupChallanDetail->product->name }}
                                                    {{-- <select name="product_ids[]"
                                                        class="form-control product_ids to-select">
                                                        <option value="">Choose Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" @if ($backupChallanDetail->product_id == $product->id) selected @endif>
                                                                {{ $product->name }}</option>
                                                        @endforeach

                                                    </select>
                                                    <input type="hidden" name="sales_order_detail_id[]" value="{{ $backupChallanDetail->id }}"> --}}

                                                </td>
                                                <td>
                                                    {{ numberFormat($backupChallanDetail->quantity) }}
                                                </td>
                                                <td>{{ numberFormat($backupChallanDetail->price) }}
                                                </td>
                                                <td>{{ numberFormat($backupChallanDetail->amount) }} </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- <p><strong>IN WORD : Taka Twenty Eight Lac Only</strong></p>
                                                <table>
                                                    <div class="totals">
                                                        <p>Total : <strong>2,800,000.00</strong></p>
                                                        <p>Discount : <strong>0.00</strong></p>
                                                        <p><strong>Grand Total : 2,800,000.00</strong></p>
                                                    </div>
                                                </table> -->

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <table>
                                            {{-- @dd($requisition->net_amount) --}}
                                            <p>IN WORD : {{ convert_number($backupChallan->total_amount) }} Taka Only</p>
                                        </table>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr style="border: none!important;">
                                                <td style="border: none!important;">Total Amount</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ $backupChallan->total_amount }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>

                                <section>
                                    <p><strong>Remarks: </strong>{{ $backupChallan->remarks }}</p>
                                </section>

                                

                                <section class="payment-info" style="width: 40%;">
                                    <h2 style="margin-top: 20px;">Shipment Details</h2>
                                    @foreach ($backupChallan->backupChallanShipments as $key => $backupChallanShipment)
                                    <table style="border: 1px solid black!important; padding: 10px;">
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 10px;">
                                            <th style="width: 20%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">
                                                Courier</th>
                                            <td style="width: 2%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">:</td>
                                            <td style="width: 25%; border: 1px solid black!important; text-align: end; padding: 10px;">
                                                <strong>{{ @$backupChallanShipment->courier->courier_name }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 10px;">
                                            <th style="width: 20%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">
                                                Area</th>
                                            <td style="width: 2%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">:</td>
                                            <td style="width: 25%; border: 1px solid black!important; text-align: end; padding: 10px;">
                                                <strong>{{ $backupChallanShipment->area?->area }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 10px;">
                                            <th style="width: 20%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">
                                                Address</th>
                                            <td style="width: 2%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">:</td>
                                            <td style="width: 25%; border: 1px solid black!important; text-align: end; padding: 10px;">
                                                <strong>{{ $backupChallanShipment->address }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 10px;">
                                            <th style="width: 20%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">
                                                Contact Person Name</th>
                                            <td style="width: 2%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">:</td>
                                            <td style="width: 25%; border: 1px solid black!important; text-align: end; padding: 10px;">
                                                <strong>{{ $backupChallanShipment->contact_person_name }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 10px;">
                                            <th style="width: 20%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">
                                                Contact Person Number</th>
                                            <td style="width: 2%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 10px;">:</td>
                                            <td style="width: 25%; border: 1px solid black!important; text-align: end; padding: 10px;">
                                                <strong>{{ $backupChallanShipment->contact_person_number }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                    @endforeach
                                </section>

                                <div  style="margin-top: 50px; font-family: Arial">
                                    <section>
                                        <p>১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ গ্রহণযোগ্য হবে না।</p>
                                        <p>২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না। আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
                                        <p><strong>৩. খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত রিএজেন্ট অফেরতযোগ্য।</strong></p>
                                        <p>৪.যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                                        <p>৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।</p>
                                        <p><strong>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায় রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ গ্রহণযোগ্য নয় ও এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</strong></p>
                                    </section>
                                </div>
                                <footer style="margin-top: 100px">
                                    <p>Received ___________________________</p>
                                    <p>Authorized ___________________________</p>
                                </footer>
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
                            if (enable) {
                                field.removeAttr('disabled');
                                if (field.prop('tomselect')) {
                                    field.prop('tomselect').enable();
                                }
                            } else {
                                field.attr('disabled', true);
                                if (field.prop('tomselect')) {
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
                        toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
                    }

                    function handleCourierConfirm() {
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

                    // Add event listener for TomSelect change event
                    $('#area_id')[0]?.tomselect.on('change', function(value) {
                        if (value === 'address') {
                            clearFields();
                        }
                    });
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
                                    $("#area_id").html(`<option value="${area_id}">${area_name}</option>`);
                                    $("#area_id")[0]?.tomselect.clear();
                                    $("#area_id")[0]?.tomselect.addOption({
                                        value: area_id,
                                        text: area_name
                                    });
                                    $("#area_id")[0]?.tomselect.setValue(area_id);

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
                        let vat = $('#vat_percentage').val();

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
                        $("#vat").val((totalAmount - totalDiscount) * vat);
                        $("#net_amount").val(totalAmount - totalDiscount + (totalAmount - totalDiscount) * vat);
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
                        $(this).closest('tr').remove();
                        calculateTotals();
                    });

                    // Initial calculation for existing rows
                    // calculateTotals();


                    $(document).on('change', '.product_ids', async function() {
                        await getProductPrice(this);
                        const customer_id = $('#customer_id').val();
                        const product_id = $(this).val();
                        console.log({
                            customer_id,
                            product_id
                        });
                        await getSalesDiscount(customer_id, product_id, this);
                    })

                    $(document).on('keyup', '.discount_range', function() {
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


                async function getSalesDiscount(customerId, productId, element = null) {
                    try {
                        const discounts = await $.get(
                            `{{ route('sales.get-sales-discount') }}?customer_id=${customerId}&product_id=${productId}`);
                        console.log({
                            discount: discounts.discount
                        });
                        $(element).closest('tr').find("#unit_discount").val(0);
                        $(element).closest('tr').find("#unit_discount").removeClass('discount_range');
                        $(element).closest('tr').find("#unit_discount").data('discount_range', null);
                        $(element).closest('tr').find("#unit_discount").removeAttr('readonly');
                        if (discounts.discount) {
                            if (discounts.discount.percentage) {
                                console.log(discounts.discount.percentage);
                                if (discounts.discount.percentage.percentage > 0) {
                                    if (element) {
                                        const percentage = discounts.discount.percentage.percentage;
                                        const price = $(element).closest('tr').find("#price").val();
                                        console.log(element);
                                        $(element).closest('tr').find("#unit_discount").val((percentage * price) / 100);
                                        $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                                    }
                                }
                            } else if (discounts.discount.productPrice) {
                                console.log(discounts.discount.productPrice);
                                const discountPrice = discounts.discount.productPrice.sales_amounts;
                                const price = $(element).closest('tr').find("#price").val();
                                if (discountPrice < price) {
                                    $(element).closest('tr').find("#unit_discount").val(price - discountPrice);
                                    $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                                }
                            } else if (discounts.discount.discountRange) {
                                $(element).closest('tr').find("#unit_discount").data('discount_range', discounts.discount
                                    .discountRange);
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
