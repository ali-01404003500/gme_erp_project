@section('title', 'Approval')
@section('description', 'Approval')
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
                                        {{ trans('Approval') }}</li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Approval') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.quotations.approveStore', $quotation->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <input type="text" name="customer_name" class="form-control" id="customer_id"
                                                value="{{ old('customer_name', $quotation->customer_name) }}" readonly>
                                            <input type="hidden" name="status" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="address">Area Name</label>
                                            <input type="text" name="area" value="{{ old('area', $quotation->area) }}"
                                                class="form-control" id="address" placeholder="Area Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="date">Expiry Date</label>
                                            <input type="text" name="date" class="form-control datePicker"
                                                id="date" placeholder="Date"
                                                value="{{ old('date', date('Y-m-d', strtotime($quotation->date))) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_phone">Customer Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                id="contact_person_phone" value="{{ old('phone', $quotation->phone) }}"
                                                placeholder="Contact Person Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_type_id"> Customer Type</label>
                                            <select name="customer_type" id="customer_type" class="form-control tom-select">
                                                <option value="">Choose Customer Type</option>
                                                @foreach ($customerTypes as $customerType)
                                                    <option value="{{ $customerType->id }}"
                                                        {{ old('customer_type', $quotation->customer_type) == $customerType->id ? 'selected' : '' }}>
                                                        {{ $customerType->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="full_address">Address</label>
                                            <textarea name="address" id="full_address" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                placeholder="Full Address">{{ old('address', $quotation->address) }}</textarea>
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
                                                            @foreach ($quotation->quotationDetails as $key => $quotationDetail)
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids to-select">  
                                                                            <option value="{{ $quotationDetail->product_id }}" selected>
                                                                                {{ $quotationDetail->product->name }}
                                                                            </option>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]"
                                                                            id="quantity"
                                                                            value="{{ numberFormat($quotationDetail->quantity) }}"
                                                                            class="form-control" placeholder="Quantity">
                                                                    </td>
                                                                    <td><input type="text" name="price[]"
                                                                            id="price"
                                                                            value="{{ numberFormat($quotationDetail->price) }}"
                                                                            class="form-control" placeholder="Price"
                                                                            readonly>
                                                                    </td>
                                                                    <td><input type="text" name="unit_discount[]"
                                                                            value="{{ numberFormat($quotationDetail->unit_discount) }}"
                                                                            id="unit_discount" class="form-control"
                                                                            placeholder="Unit Discount"></td>
                                                                    <td><input type="text" name="total_discount[]"
                                                                            value="{{ numberFormat($quotationDetail->total_discount) }}"
                                                                            id="total_discount" class="form-control"
                                                                            placeholder="Total Discount" readonly></td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control text-center"
                                                                            value="{{ numberFormat($quotationDetail->amount) }}"
                                                                            id="amount" name="amount[]" readonly
                                                                            value="0">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs"
                                                                            id="remove_row" onclick="removeRow(this)">
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
                                                                        value="{{ old('total_amount', numberFormat($quotation->total)) }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Discount
                                                                </td>
                                                                <td><input type="text" class="form-control text-center"
                                                                        id="discount" name="discount"
                                                                        value="{{ old('discount', numberFormat($quotation->discount) ?? 0) }}"
                                                                        readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Percentage %
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" id="percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="additional_percentage" name="percentage"
                                                                        value="{{ old('percentage', numberFormat($quotation->percentage) ?? 0) }}">
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total" name="total" readonly
                                                                        value="{{ old('total', numberFormat($quotation->total)) }}">

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
                                                            value="{{ old('remarks', $quotation->remarks) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset class="border p-2">
                                                {{-- @dd( ) --}}
                                                <legend class="float-none w-auto p-2">
                                                    Terms & Conditions
                                                    <input type="checkbox" name="termsConfirm" id="termsConfirm"
                                                        tabindex="1015" {{ $quotation->quotationDetails?->count() > 0 ? 'checked' : '' }}>
                                                </legend>
                                                <div class="row termsConditions" id="termsConditions" style="display: none">
                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="quotation_to">Quotation to</label>
                                                            <input type="text" name="quotation_to"
                                                                class="form-control" id="quotation_to"
                                                                placeholder="Quotation to"
                                                                value="{{ old('quotation_to', $quotation->quotationTerms->quotation_to)??'' }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="text" name="email" class="form-control"
                                                                id="email" placeholder="Email"
                                                                value="{{ old('email', $quotation->quotationTerms->email) }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="attn">ATTN</label>
                                                            <input type="text" name="attn" class="form-control"
                                                                id="attn" placeholder="ATTN"
                                                                value="{{ old('attn', $quotation->quotationTerms->attn) }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="attn_cell">ATTN Cell</label>
                                                            <input type="text" name="attn_cell" class="form-control"
                                                                id="attn_cell" placeholder="ATTN Cell"
                                                                value="{{ old('attn_cell', $quotation->quotationTerms->attn_cell) }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="payment">Payment</label>
                                                            <textarea name="payment" id="payment" class="form-control trumbowyg" rows="1" 
                                                                placeholder="Payment Conditions">{{ old('payment', $quotation->quotationTerms->payment) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="payment_method">Payment Method</label>
                                                            <textarea name="payment_method" id="payment_method" class="form-control trumbowyg" rows="1" 
                                                                placeholder="Payment Method Conditions">{{ old('payment_method', $quotation->quotationTerms->payment_method) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="tax_vat">TAX & VAT</label>
                                                            <textarea name="tax_vat" id="tax_vat" class="form-control trumbowyg" rows="1" 
                                                                placeholder="TAX & VAT Conditions">{{ old('tax_vat', $quotation->quotationTerms->tax_vat)  }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="installation">Installation</label>
                                                            <textarea name="installation" id="installation" class="form-control trumbowyg" rows="1" 
                                                                placeholder="Installation Conditions">{{ old('installation', $quotation->quotationTerms->installation)  }}</textarea>
                                                            </textarea>
                                                            </d``iv>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group formElement-editor">
                                                                <label for="training">Training</label>
                                                                <textarea name="training" id="training" class="form-control trumbowyg" rows="1" 
                                                                    placeholder="Training Conditions">{{ old('training', $quotation->quotationTerms->training)  }}</textarea>
                                                        </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group formElement-editor">
                                                                <label for="warranty">Warranty</label>
                                                                <textarea name="warranty" id="warranty" class="form-control trumbowyg" rows="1" 
                                                                    placeholder="Warranty Conditions">{{ old('warranty', $quotation->quotationTerms->warranty) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group formElement-editor">
                                                                <label for="buyers_responsibility">Buyer’s
                                                                    Responsibility</label>
                                                                <textarea name="buyers_responsibility" id="buyers_responsibility"
                                                                    class="form-control trumbowyg" rows="1"  placeholder="Buyer’s Responsibility">{{ old('buyers_responsibility', $quotation->quotationTerms->buyers_responsibility) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group formElement-editor">
                                                                <label for="validity">Validity</label>
                                                                <textarea name="validity" id="validity" class="form-control trumbowyg" rows="1" 
                                                                    placeholder="Validity Conditions">{{ old('validity', $quotation->quotationTerms->validity) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group formElement-editor">
                                                                <label for="delivery_info">Delivery Info</label>
                                                                <textarea name="delivery_info" id="delivery_info" class="form-control trumbowyg" rows="1" 
                                                                    placeholder="Delivery Info">{{ old('delivery_info', $quotation->quotationTerms->delivery_info) }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </fieldset>

                                            <div class="col-md-12">
                                                <div
                                                    class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    <button type="submit" class="btn btn-primary">Approve</button>
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
            $('#termsConfirm').change(function() {
                if ($(this).is(':checked')) {
                    $('#termsConditions').show();
                } else {
                    $('#termsConditions').hide();
                }
            });

            $('#termsConfirm').trigger('change');
        });

        $(document).ready(function() {
            $('#customer_id').change(getCustomerSettings);
        });

        function getCustomerSettings() {
            var id = $("#customer_id option:selected").data("id");
            if (id) {
                $.ajax({
                    url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                    success: function(data) {
                        console.log(data);

                        if (data && data.customers && data.customers.customer) {
                            var customer_type = data.customers.customer.customer_type;
                            var customer_type_id = customer_type.id;
                            var customer_type_name = customer_type.name;


                            $("#customer_type").find('option').each(function() {
                                if ($(this).val() == customer_type_id) {
                                    $(this).prop('selected', true);
                                }
                            });
                            $("#customer_type").each(function() {
                                this.tomselect.sync();
                            })
                            $("#address").val(data.customers.customer.area.area);
                            $("#contact_person_name").val(data.customers.customer.company_name);
                            $("#contact_person_phone").val(data.customers.customer.phone);
                            $("#full_address").val(data.customers.customer.address);

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

            function updateUnitDiscounts() {
                let additionalPercentage = parseFloat($("#additional_percentage").val()) || 0;
                $("#product_info_table tbody tr").each(function() {
                    let price = parseFloat($(this).find("#price").val()) || 0;
                    let unitDiscount = (price * additionalPercentage) / 100;
                    $(this).find("#unit_discount").val(unitDiscount);
                });
            }

            function calculateTotalForPercentage() {
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
                if (totalAmount > 0 && totalDiscount > 0) {
                    $("#additional_percentage").val((parseFloat((totalDiscount / totalAmount) * 100).toFixed()) ??
                        0);
                } else {
                    $("#additional_percentage").val(0);
                }           
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
    </script>



    <script type="text/javascript">
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
            rowTemplate.find('.product_ids option').remove(); 

            $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
                new TomSelect(this, {});
            });
           
            $("#add_row").click(function () {
                const newRow = rowTemplate.clone();
                
                // Reset discount_type field in new row
                newRow.find('.discount_type_input').val('');
                $("#product_info_table tbody").append(newRow);

                prouctAutocompleteLoad(newRow);
            });

            $("#product_info_table").on("keyup change", "#quantity, #price, #unit_discount", function() {
                calculateTotals();
                calculateTotalForPercentage();

            });

            $("#additional_percentage").on("keyup change", function() {
                updateUnitDiscounts();
                calculateTotals();

            });

            $("#product_info_table").on("click", "#remove_row", function() {
                var row = $(this).closest('tr');
                var selectElement = row.find('.product_ids')[0];
                var previousProductId = selectElement.getAttribute('data-previous-value');
                if (previousProductId) {
                    selectedProductIds = selectedProductIds.filter(id => id !== previousProductId);
                }
                row.remove();
                calculateTotals();
            });

            // Initial calculation for existing rows
            calculateTotals();
        });
    </script>

    <script>
       $(document).on('change', '.product_ids', async function() {
            await getProductPrice(this);
            updateUnitDiscounts();
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

    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
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