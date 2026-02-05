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
                            <a href="{{ route('sales.quotations.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
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
                            <form action="{{ route('sales.quotations.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_name" id="customer_id"
                                                class="form-control customer-select">
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->company_name }}"
                                                        data-id="{{ $customer->id }}"
                                                        {{ old('customer_name') == $customer->company_name ? 'selected' : '' }}>
                                                        {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null)
                                                            ({{ $customer->area->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="address">Area Name</label>
                                            <input type="text" name="area" class="form-control" id="address" value="{{ old('area') }}"
                                                placeholder="Area Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="date">Expiry Date</label>
                                            <input type="text" name="date" class="form-control flatdate" 
                                                id="date" placeholder="Date" value="{{ old('date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_phone">Customer Phone</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                                id="contact_person_phone" placeholder="Contact Person Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_type_id"> Customer Type</label>
                                            <select name="customer_type" id="customer_type" class="form-control tom-select">
                                                <option value="">Choose Customer Type</option>
                                                @foreach ($customerTypes as $customerType)
                                                    <option value="{{ $customerType->id }}"
                                                        {{ old('customer_type') == $customerType->id ? 'selected' : '' }}>
                                                        {{ $customerType->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group formElement-editor">
                                            <label for="full_address">Address</label>
                                            <textarea name="address" id="full_address" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                placeholder="Full Address">{{ old('address') }}</textarea>
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
                                                                                class="form-control product_ids to-select">
                                                                                <option value="">Choose Product</option>
                                                                                @foreach ($products as $product)
                                                                                    <option value="{{ $product->id }}"
                                                                                        {{ old('product_ids')[$key] == $product->id ? 'selected' : '' }}>
                                                                                        {{ $product->name }}</option>
                                                                                @endforeach
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
                                                                                id="unit_discount" class="form-control"
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
                                                                            class="form-control product_ids to-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
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
                                                                            id="unit_discount" class="form-control"
                                                                            placeholder="Unit Discount"></td>
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


                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Percentage %
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" id="percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="additional_percentage" name="percentage"
                                                                        value="{{ old('percentage', 0) }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" style="text-align: right;">Net Amount
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

                                            <fieldset class="border p-2">
                                                <legend class="float-none w-auto p-2">
                                                    Terms & Conditions
                                                    <input type="checkbox" name="termsConfirm" id="termsConfirm"
                                                        tabindex="1015" {{ old('termsConfirm', false) ? 'checked' : '' }}>
                                                </legend>
                                                <div class="row termsConditions" id="termsConditions" style="display: none">
                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="quotation_to">Quotation to</label>
                                                            <input type="text" name="quotation_to"
                                                                class="form-control" id="quotation_to"
                                                                placeholder="Quotation to"
                                                                value="{{ old('quotation_to') ?? 'Director' }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="text" name="email" class="form-control"
                                                                id="email" placeholder="Email"
                                                                value="{{ old('email') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="attn">ATTN</label>
                                                            <input type="text" name="attn" class="form-control"
                                                                id="attn" placeholder="ATTN"
                                                                value="{{ old('attn') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mt-4">
                                                        <div class="form-group">
                                                            <label for="attn_cell">ATTN Cell</label>
                                                            <input type="text" name="attn_cell" class="form-control"
                                                                id="attn_cell" placeholder="ATTN Cell"
                                                                value="{{ old('attn_cell') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor ">
                                                            <label for="payment">Payment</label>
                                                            <textarea name="payment" id="payment" class="form-control trumbowyg " rows="1"
                                                                placeholder="Payment Conditions">{{ old('payment') ?? '100% Advance' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="payment_method">Payment Method</label>
                                                            <textarea name="payment_method" id="payment_method" class="form-control trumbowyg" rows="1"
                                                                placeholder="Payment Method Conditions">{{ old('payment_method') ?? 'To be paid by Cheque, Cash or Mobile Banking(bKash). In favor of Global Medical Engineering (BD) Ltd.' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="tax_vat">TAX & VAT</label>
                                                            <textarea name="tax_vat" id="tax_vat" class="form-control trumbowyg" rows="1"
                                                                placeholder="TAX & VAT Conditions">{{ old('tax_vat') ?? 'All Prices Excluding TAX & VAT.' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="installation">Installation</label>
                                                            <textarea name="installation" id="installation" class="form-control trumbowyg" rows="1"
                                                                placeholder="Installation Conditions">{{ old('installation') ?? 'Shall be installed by our Foreign Trained Engineer on prior appointment with your concern person(s) at your recommended site on <strong>OUR COST</strong>.' }}</textarea>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="training">Training</label>
                                                            <textarea name="training" id="training" class="form-control trumbowyg" rows="1"
                                                                placeholder="Training Conditions">{{ old('training') ?? 'Necessary trainging will be imparted to your designated personnel at site on operation & maintenance of the Equipment on <strong>FREE OF CHARGE</strong>.' }}
                                                        </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="warranty">Warranty</label>
                                                            <textarea name="warranty" id="warranty" class="form-control trumbowyg" rows="1"
                                                                placeholder="Warranty Conditions">{{ old('warranty') ?? '01 (One) Year standard warranty is offered including servicing, replacement of faulty parts, repair etc. from the date of delivery of Goods.Consumables are not covered under this warranty.Warranty does not cover any Electric Burn & Physical Damaged.' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="buyers_responsibility formElement-editor">Buyer’s
                                                                Responsibility</label>
                                                            <textarea name="buyers_responsibility" id="buyers_responsibility"
                                                                class="form-control trumbowyg" rows="1" placeholder="Buyer’s Responsibility">{{ old('buyers_responsibility') ?? 'To use <strong> Air-conditioned dust free room and Stabilized & Noise Free power supply </strong>.' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="validity">Validity</label>
                                                            <textarea name="validity" id="validity" class="form-control trumbowyg" rows="1"
                                                                placeholder="Validity Conditions">{{ old('validity') ?? '20 Days after submitted quotation.' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group formElement-editor">
                                                            <label for="delivery_info">Delivery Info</label>
                                                            <textarea name="delivery_info" id="delivery_info" class="form-control trumbowyg" rows="1"
                                                                placeholder="Delivery Info">{{ old('delivery_info') ?? 'All products will be delivered From Ready Stock or Within 60-90 days from the date of order with advance.' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <div class="col-md-12">
                                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    
                                                    <a href="{{ request()->url() }}" class="btn btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
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
            $('#termsConfirm').change(function() {
                if ($(this).is(':checked')) {
                    $('#termsConditions').show();
                } else {
                    $('#termsConditions').hide();
                }
            });
        });

        $(document).ready(function() {
            $('#customer_id').change(getCustomerSettings);

        });

        function getCustomerSettings() {
            var id = $("#customer_id option:selected").data("id");
            clearFields();
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
            $("#full_address").val("");
            $("#customer_type").find('option').prop('selected', false);
            $("#customer_type").each(function() {
                this.tomselect.sync();
            })

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
            else
            {
                $("#add_row").click();
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
            // var previousProductId = selectElement.getAttribute('data-previous-value');
            // if (previousProductId && previousProductId !== productId) {
            //     selectedProductIds = selectedProductIds.filter(id => id !== previousProductId);
            // }

            if (productId.trim() !== '') {
                // Check if the product is already selected
                // if (selectedProductIds.includes(productId)) {
                //     showToast('warning', 'You have already selected this product.');
                //     selectElement.value = ""; 
                //     selectElement.tomselect.clear();
                //     priceInput.value = '';
                //     qtyInput.value = '';
                //     return;
                // }

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
                    $(selectElement).closest('tr').find("#unit_discount").val(0);
                    // selectedProductIds.push(productId);

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
    </script>
@endsection
