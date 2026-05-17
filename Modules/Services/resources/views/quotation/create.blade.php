@section('title', 'Quotation Create')
@section('description', 'Quotation Create')
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
                                        {{ trans('menu.create-quotation-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('services.quotations.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-quotation-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('services.quotations.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="service_id">Service Token ID <span class="text-danger">*</span></label>
                                           <select name="service_id" id="service_id" class="form-control  ">
                                            <option value="">Choose Service Token ID</option>
                                            {{-- @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    data-customer-id="{{ @$service->serviceTokens->first()->customer_id }}"
                                                    data-customer-name="{{ @$service->serviceTokens->first()->customer->company_name }}"
                                                    {{ (old('service_id') == $service->id || $selected_service_id == $service->id) ? 'selected' : '' }}>
                                                    {{ $service->service_unique_id }}
                                                </option>
                                            @endforeach --}}
                                        </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="customer_name" class="form-control" id="customer_name"
                                                value="{{ old('customer_name') }}" readonly>
                                            <input type="hidden" name="customer_id" id="customer_id"
                                                value="{{ old('customer_id') }}">
                                            <input type="hidden"  id="credit_limit" value="{{ @$service->serviceTokens?->first()?->customer?->customerSetting?->first()->credit_limit??0 }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="date">Sales Date<span class="text-danger">*</span></label>
                                            <input type="text" name="date" id="date"
                                                class="form-control flatdate invoice_date_input" placeholder="Sales Date" value="{{ date('Y-m-d') }}"
                                                value="{{ old('date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>

                                                <div class="table-responsive">
                                                    <table class="table  table-bordered" id="product_info_table">
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
                                                            @if( old('product_ids') )
                                                                @foreach (old('product_ids') as $key => $item)
                                                                    <tr>
                                                                        <td>
                                                                            <select name="product_ids[]"
                                                                                class="form-control product_ids  ">
                                                                                <option value="">Choose Product</option>
                                                                                    <option value="{{ $item->product_id }}" selected>
                                                                                        {{ $item->product->name }}</option>
                                                                      
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="quantity[]"
                                                                                id="quantity" class="form-control" value="{{ old('quantity')[$key] }}"
                                                                                placeholder="Quantity">
                                                                        </td>
                                                                        <td><input type="text" name="price[]"
                                                                                id="price" class="form-control" value="{{ old('price')[$key] }}"
                                                                                placeholder="Price" readonly></td>
                                                                        <td><input type="text" name="unit_discount[]" value="{{ old('unit_discount')[$key] }}"
                                                                                id="unit_discount" class="form-control unit_discount_input"
                                                                                placeholder="Unit Discount"></td>
                                                                        <td><input type="text" name="total_discount[]" value="{{ old('total_discount')[$key] }}"
                                                                                id="total_discount" class="form-control"
                                                                                placeholder="Total Discount" readonly></td>
                                                                        <td>
                                                                            <input type="text" class="form-control text-center" value="{{ old('amount')[$key] }}"
                                                                                id="amount" name="amount[]" readonly>

                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-xs remove_row">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids">
                                                                            <option value="">Choose Product</option> 
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]"
                                                                            id="quantity" class="form-control"
                                                                            placeholder="Quantity">
                                                                    </td>
                                                                    <td><input type="text" name="price[]"
                                                                            id="price" class="form-control"
                                                                            placeholder="Price" readonly></td>
                                                                    <td><input type="text" name="unit_discount[]"
                                                                            id="unit_discount" class="form-control unit_discount_input"
                                                                            placeholder="Unit Discount" value="0"></td>
                                                                    <td><input type="text" name="total_discount[]"
                                                                            id="total_discount" class="form-control"
                                                                            placeholder="Total Discount" readonly></td>
                                                                    <td>
                                                                        <input type="text" class="form-control text-center"
                                                                            id="amount" name="amount[]" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs remove_row" id="remove_row">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">
                                                                    Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_amount" name="total_amount" readonly
                                                                        value="{{ old('total_amount') }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Discount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="discount" name="discount"
                                                                        value="{{ old('discount', 0) }}" readonly></td>
                                                            </tr>


                                                            {{-- <tr>
                                                                <td colspan="5" style="text-align: right;">Percentage %
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" id="percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="additional_percentage" name="percentage"
                                                                        value="{{ old('percentage', 0) }}">
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total" name="total" readonly
                                                                        value="{{ old('total', 0) }}">

                                                                        <input type="hidden" name="net_amount" id="net_amount" value="0">
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <input type="text" name="remarks" class="form-control"
                                                            id="remarks" placeholder="Remarks"
                                                            value="{{ old('remarks') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    
                                                    <input type="hidden" name="status" value="pending" id="status">
                                                    <a href="{{ request()->url() }}" class="btn btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    {{-- <button type="submit"  class="btn btn-primary">Submit</button> --}}
                                                    <button type="submit" id="approve" class="btn btn-success">
                                                            <i class="fa fa-check"></i>
                                                            Submit
                                                        </button>
                                                </div>
                                            </div>
                                        </div>

                                         @include('Sales::sales-order.opt-verification')
                                        {{-- @dd($serviceMyTask->otpVerifications ) --}}
                                        {{-- @foreach ($serviceMyTask->otpVerifications as  $otpVerification)
                                            <input type="hidden" name="otp_verifications[]" value="{{ json_encode($otpVerification) }}">
                                        @endforeach --}}
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
        function setCustomerData() {
 
            let serviceId = $('#service_id').val(); 
            $.ajax({
                url: "{{ route('services.service-customer-id') }}",
                type: "GET",
                data: { service_id: serviceId },
                success: function(res) { 
                    $('#customer_id').val(res.id);
                    $('#customer_name').val(res.name);
                },
                error: function() {
                    $('#customer_id').val('');
                    $('#customer_name').val('');
                }
            });
             
        }

        $('#service_id').on('change', function() {
            setCustomerData();
        });

        // Auto set customer on page load if service_id is pre-selected
        setCustomerData();

        $("#approve").click(function() {
            $("#status").val("approved");
            return true;
        });
    });
</script>


    <script>
        
        function calculateTotals() {
                let totalAmount = 0;
                let totalDiscount = 0;
                let totalUnitDiscount = 0;
                $("#product_info_table tbody tr").each(function() {
                    const {
                        amount,
                        totalDiscount: rowDiscount
                    } = calculateRow($(this));
                    totalAmount += amount;
                    totalDiscount += rowDiscount;
                    totalUnitDiscount += parseFloat($(this).find("#unit_discount").val()) || 0;
                });

                $("#total_amount").val(parseFloat(totalAmount).toFixed());
                $("#discount").val(parseFloat(totalDiscount).toFixed());
                $("#total").val(parseFloat(totalAmount - totalDiscount).toFixed());
                $("#total_unit_discount").val(parseFloat(totalUnitDiscount).toFixed());
              
            }

            // function updateUnitDiscounts() {
            //     let additionalPercentage = parseFloat($("#additional_percentage").val()) || 0;
            //     $("#product_info_table tbody tr").each(function() {
            //         let price = parseFloat($(this).find("#price").val()) || 0;
            //         let unitDiscount = (price * additionalPercentage) / 100;
            //         $(this).find("#unit_discount").val(unitDiscount);
            //     });
            // }

            // function calculateTotalForPercentage() {
            //     let totalAmount = 0;
            //     let totalDiscount = 0;
            //     let totalUnitDiscount = 0;
            //     $("#product_info_table tbody tr").each(function() {
            //         const {
            //             amount,
            //             totalDiscount: rowDiscount
            //         } = calculateRow($(this));
            //         totalAmount += amount;
            //         totalDiscount += rowDiscount;
            //         totalUnitDiscount += parseFloat($(this).find("#unit_discount").val()) || 0;
            //     });
            //     if (totalAmount > 0 && totalDiscount > 0) {
            //         $("#additional_percentage").val((parseFloat((totalDiscount / totalAmount) * 100).toFixed()) ??
            //             0);
            //     } else {
            //         $("#additional_percentage").val(0);
            //     }           
            //  }
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
    </script>



    <script type="text/javascript">
        function clearRow(row){
                $(row).find('input').val('');
                $(row).find('select').val('');
                $(row).find('select').each(function() {
                    this.tomselect?.clear();
                })
        }
        $(document).ready(function() {
            $('.customer-select').each(function() {
                new TomSelect(this, {
                    create: true,
                });
            });

            const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
            rowTemplate.find('input').val('');
            rowTemplate.find('.to-select option:selected').removeAttr('selected');
            rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');

            $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
                new TomSelect(this, {});
            });
           
            $("#add_row").click(function() {
                const newRow = rowTemplate.clone();
                newRow.find('.to-select').each(function() {
                    new TomSelect(this, {});
                });
                $("#product_info_table tbody").append(newRow);
                 prouctAutocompleteLoad(newRow);
            });

            $("#product_info_table").on("keyup change", "#quantity, #price, #unit_discount", function() {
                calculateTotals();

            });

            //$("#additional_percentage").on("keyup change", function() {
              //  updateUnitDiscounts();
            //    calculateTotals();

           //});

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
            });

            // Initial calculation for existing rows
            calculateTotals();
        });
    </script>

    <script>
       $(document).on('change', '.product_ids', async function() {
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
            console.log('product changed');
            $(this).closest('tr').find('#quantity').val(1);
            await getProductPrice(this);
            // updateUnitDiscounts();
            calculateTotals();
        });

        var selectedProductIds = []; // Array to store selected product IDs

        async function getProductPrice(selectElement) {
            var productId = selectElement.value;
            var priceInput = selectElement.closest('tr').querySelector('input[name="price[]"]');
            var qtyInput = selectElement.closest('tr').querySelector('input[name="quantity[]"]');

            // When a product is deselected, remove the previous product ID from selectedProductIds
            var previousProductId = selectElement.getAttribute('data-previous-value');
            if (previousProductId && previousProductId !== productId) {
                selectedProductIds = selectedProductIds.filter(id => id !== previousProductId);
            }

            if (productId.trim() !== '') {
                // Check if the product is already selected
                if (selectedProductIds.includes(productId)) {
                    showToast('warning', 'You have already selected this product.');
                    selectElement.value = ""; 
                    selectElement.tomselect.clear();
                    priceInput.value = '';
                    qtyInput.value = '';
                    return;
                }
                else
                {
                    $("#add_row").click();
                }


                try {
                    const response = await $.get('{{ route('purchase.get.product.list') }}?id=' + productId);
                    var product = response[0];
                    if (!product) {
                        showToast('error', 'Price not found.');
                        priceInput.value = '';
                        return;
                    }
                    priceInput.value = product.mrp;
                    qtyInput.value = 1;
                    selectedProductIds.push(productId);

                    // Update the previous value data attribute
                    selectElement.setAttribute('data-previous-value', productId);
                } catch (error) {
                    console.error(error);
                    showToast('error', 'An error occurred while fetching product details.');
                }
            } else {
                priceInput.value = '';
                qtyInput.value = '';
                selectElement.removeAttribute('data-previous-value');
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

    </script>

    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/modern-screenshot@4.6.7/dist/index.min.js"></script>

    @stack('script')

    <script>
     window.pendingCall = [];

        function checkExistingOtpVerifications() {
            const existingVerification = $('input[name="otp_verifications[]"]');
            for (let i = 0; i < existingVerification.length; i++) {
                const verificationData = JSON.parse(existingVerification[i].value);
                if (verificationData.status == "pending") {
                    if (verificationData.title === 'Invoice Date Changed') {
                        $('.invoice_date_input').trigger('change');
                    } else if (verificationData.title.startsWith('Discount Changed')) {
                        const product_id = verificationData.details_data.product_id;
                        const optionOfProduct = [...document.querySelectorAll('option[selected]')].find(option => option.textContent == product_id);
                        console.log({optionOfProduct});

                        const closesetSelect = $(optionOfProduct).closest('tr').find(".unit_discount_input");
                        $(closesetSelect).trigger('change');
                    }else if( verificationData.title.startsWith(' Discount Range Exceeded for ')){ 
                        const product_id = verificationData.details_data.product_id;

                        const optionOfProduct = [...document.querySelectorAll('option[selected]')].find(option => option.textContent == product_id);
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
            const image = await modernScreenshot.domToPng(tableElement, { quality: 0.95 });
            const data = {
                image: [image],
                customer_name: $('#customer_name').val(),
            }
            return data;
        }

        $(document).ready(function () {
 

            const serviceSelect = new TomSelect("#service_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('services.service-autocomplete.service-id') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            serviceSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

             const productSelect = new TomSelect(".product_ids", {
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

            

            $('.invoice_date_input').on('change', function () {
                if ($('.invoice_date_input').val() != '{{ date('Y-m-d') }}') {
                    $('.invoice_date_input').addClass('opt-required');
                    $('.invoice_date_input').closest('div').attr("title", 'OPT required');
                    const data = {
                        title: 'Invoice Date Changed',
                        request_value: $('.invoice_date_input').val(),
                    };
                    // captureProductInfoTable();
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

            //discount change detection
            $(document).on('change', '.unit_discount_input', function () {
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
                    } else {
                        deleteOtpVerification(otp_title);
                    unit_discount.removeClass('opt-required');
                    unit_discount.closest('td').attr("title", '');
                    }
                }
            });

           

            // checkExistingOtpVerifications();
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

@endsection
