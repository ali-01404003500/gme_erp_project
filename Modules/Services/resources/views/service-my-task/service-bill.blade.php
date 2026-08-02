{{-- @dd($serviceMyTask->bills, $serviceMyTask->bill_type); --}}

<div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
    <div class="row mt-3 mb-3">
        <div class="col-md-12 mt-3">
            <h5 class="text-uppercase">Service Bill</h5>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="custom-control custom-radio mr-3">
                <input type="radio" id="serviceBill" name="bill_type" class="custom-control-input" value="service_bill"
                    {{ old('bill_type', $serviceMyTask?->bill_type) != 'service_return_bill' ? 'checked' : '' }}>
                <label class="custom-control-label" for="serviceBill">Service Bill</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" id="serviceReturnBill" name="bill_type" class="custom-control-input"
                    value="service_return_bill" {{ old('bill_type', $serviceMyTask?->bill_type) == 'service_return_bill' ? 'checked' : '' }}>
                <label class="custom-control-label" for="serviceReturnBill">Service Return Bill</label>
            </div>
        </div>
    </div>

    <style>
        .mr-3 {
            margin-right: 10px !important;
        }

        /* Hide the return bill div by default */
        #serviceReturnBillDiv {
            display: none;
        }
    </style>

    <div id="serviceBillDiv">
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
                        <th style="width: 8%; text-align: right;">
                            <button type="button" class="btn btn-info btn-sm add_row_btn">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (old('bill_product_ids'))
                        @php
                            $oldProductIds = old('bill_product_ids');
                            $oldQuantities = old('bill_quantity');
                            $oldPrices = old('bill_price');
                            $oldUnitDiscounts = old('bill_unit_discount');
                            $oldTotalDiscounts = old('bill_total_discount');
                            $oldAmounts = old('bill_amount');
                            $count = count($oldProductIds);
                        @endphp
                        @for($i = 0; $i < $count; $i++)
                            <tr>
                                <td>
                                    <select name="bill_product_ids[]" class="form-control product-select">
                                        <option value="">Choose Product</option> 
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="bill_quantity[]" class="form-control quantity"
                                        placeholder="Quantity" value="{{ old('bill_quantity.'.$i, 1) }}">
                                </td>
                                <td>
                                    <input type="text" name="bill_price[]" class="form-control price" placeholder="Price"
                                        value="{{ old('bill_price.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" name="bill_unit_discount[]"
                                        class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                        value="{{ old('bill_unit_discount.'.$i, 0) }}">
                                </td>
                                <td>
                                    <input type="text" name="bill_total_discount[]" class="form-control total_discount"
                                        placeholder="Total Discount" value="{{ old('bill_total_discount.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center amount" name="bill_amount[]"
                                        value="{{ old('bill_amount.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    @elseif(!empty($serviceMyTask) && ($serviceMyTask->bill_type == 'service_bill') && $serviceMyTask->bills && $serviceMyTask->bills->isNotEmpty())
               
                        @foreach($serviceMyTask->bills as $bill)
                            <tr>
                                <td>
                                    <select name="bill_product_ids[]" class="form-control product-select">
                                        <option value="">Choose Product</option> 

                                        <option value="{{ $bill->product_id }}" selected >
                                            {{ $bill->product->name }}
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="bill_quantity[]" class="form-control quantity"
                                        placeholder="Quantity" value="{{ old('bill_quantity.'.$loop->index, intval($bill->quantity)) }}">
                                </td>
                                <td>
                                    <input type="text" name="bill_price[]" class="form-control price" placeholder="Price"
                                        value="{{ old('bill_price.'.$loop->index, $bill->price) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" name="bill_unit_discount[]"
                                        class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                        value="{{ old('bill_unit_discount.'.$loop->index, $bill->unit_discount) }}">
                                </td>
                                <td>
                                    <input type="text" name="bill_total_discount[]" class="form-control total_discount"
                                        placeholder="Total Discount" value="{{ old('bill_total_discount.'.$loop->index, $bill->total_discount) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center amount" name="bill_amount[]"
                                        value="{{ old('bill_amount.'.$loop->index, $bill->amount) }}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                       
                    @elseif (isset($product))
                     
                        <tr>
                            <td>
                                <select name="bill_product_ids[]" class="form-control product-select">
                                    <option value="">Choose Product</option>
                                    <option value="{{  $product->id }}" selected >
                                        {{ $product->name }}
                                    </option>
                                </select>
 
                            

                            </td>
                            <td>
                                <input type="number" name="bill_quantity[]" class="form-control quantity"
                                    placeholder="Quantity" value="1">
                            </td>
                            <td>
                                <input type="text" name="bill_price[]" class="form-control price" placeholder="Price"
                                    value="{{ $product->mrp }}" readonly>
                            </td>
                            <td>
                                <input type="number" name="bill_unit_discount[]" class="form-control unit_discount"
                                    placeholder="Unit Discount" value="0">
                            </td>
                            <td>
                                <input type="text" name="bill_total_discount[]" class="form-control total_discount"
                                    placeholder="Total Discount" value="0" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control text-center amount" name="bill_amount[]"
                                    value="{{ $product->mrp }}" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <select name="bill_product_ids[]" class="form-control product-select  ">
                                    <option value="">Choose Product</option> 
                                </select>
                            </td>
                            <td>
                                <input type="number" name="bill_quantity[]" class="form-control quantity"
                                    placeholder="Quantity" value="1">
                            </td>
                            <td>
                                <input type="text" name="bill_price[]" class="form-control price" placeholder="Price"
                                    readonly>
                            </td>
                            <td>
                                <input type="number" name="bill_unit_discount[]"
                                    class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                    value="0">
                            </td>
                            <td>
                                <input type="text" name="bill_total_discount[]" class="form-control total_discount"
                                    placeholder="Total Discount" readonly value="0">
                            </td>
                            <td>
                                <input type="text" class="form-control text-center amount" name="bill_amount[]" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                   
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Total Discount</td>
                        <td>
                            <input type="text" class="form-control text-center" id="discount_amount_bill"
                                name="bill_discount_amount" readonly>
                        </td>
                        <td></td>
                    </tr>
                     <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Total</td>
                        <td>
                            <input type="text" class="form-control text-center" id="net_amount_bill"
                                name="bill_net_amount" readonly>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            {{-- @dd($serviceMyTask); --}}
                            <div class="form-group">
                                <textarea name="bill_description" class="form-control mt-2" rows="3"
                                    placeholder="Description...">{{
                                        old('bill_description', !empty($serviceMyTask) && ($serviceMyTask->bill_type == 'service_bill') && $serviceMyTask->bill_description ? $serviceMyTask->bill_description : '')
                                    }}</textarea>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div id="serviceReturnBillDiv">
        <div class="table-responsive">
            <table class="table table-bordered" id="product_info_table_return">
                <thead>
                    <tr>
                        <th style="width: 25%">Product Name</th>
                        <th style="width: 15%">Quantity</th>
                        <th style="width: 15%">Price</th>
                        <th style="width: 15%">Unit Discount</th>
                        <th style="width: 15%">Total Discount</th>
                        <th style="width: 15%">Amount</th>
                        <th style="width: 8%; text-align: right;">
                            <button type="button" class="btn btn-info btn-sm return_add_row_btn">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (old('return_bill_product_ids'))
                        @php
                            $oldProductIds = old('return_bill_product_ids');
                            $oldQuantities = old('return_bill_quantity');
                            $oldPrices = old('return_bill_price');
                            $oldUnitDiscounts = old('return_bill_unit_discount');
                            $oldTotalDiscounts = old('return_bill_total_discount');
                            $oldAmounts = old('return_bill_amount');
                            $count = count($oldProductIds);
                        @endphp
                        @for($i = 0; $i < $count; $i++)
                            <tr>
                                <td>
                                    <select name="return_bill_product_ids[]" class="form-control product-select">
                                        <option value="">Choose Product</option> 
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="return_bill_quantity[]" class="form-control quantity"
                                        placeholder="Quantity" value="{{ old('return_bill_quantity.'.$i, 1) }}">
                                </td>
                                <td>
                                    <input type="text" name="return_bill_price[]" class="form-control price" placeholder="Price"
                                        value="{{ old('return_bill_price.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" name="return_bill_unit_discount[]"
                                        class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                        value="{{ old('return_bill_unit_discount.'.$i, 0) }}">
                                </td>
                                <td>
                                    <input type="text" name="return_bill_total_discount[]" class="form-control total_discount"
                                        placeholder="Total Discount" value="{{ old('return_bill_total_discount.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center amount" name="return_bill_amount[]"
                                        value="{{ old('return_bill_amount.'.$i) }}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    @elseif(!empty($serviceMyTask) && $serviceMyTask->returnBills && ($serviceMyTask->bill_type == 'service_return_bill') && $serviceMyTask->returnBills->isNotEmpty())
                        @foreach($serviceMyTask->returnBills as $returnBill)
                            <tr>
                                <td>
                                    <select name="return_bill_product_ids[]" class="form-control product-select">
                                        <option value="">Choose Product</option>
                                        <option value="{{ $returnBill->product_id }}"  selected>
                                            {{ $returnBill->product->name }}
                                        </option> 
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="return_bill_quantity[]" class="form-control quantity"
                                        placeholder="Quantity" value="{{ old('return_bill_quantity.'.$loop->index, intval($returnBill->quantity)) }}">
                                </td>
                                <td>
                                    <input type="text" name="return_bill_price[]" class="form-control price" placeholder="Price"
                                        value="{{ old('return_bill_price.'.$loop->index, $returnBill->price) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" name="return_bill_unit_discount[]"
                                        class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                        value="{{ old('return_bill_unit_discount.'.$loop->index, $returnBill->unit_discount) }}">
                                </td>
                                <td>
                                    <input type="text" name="return_bill_total_discount[]" class="form-control total_discount"
                                        placeholder="Total Discount" value="{{ old('return_bill_total_discount.'.$loop->index, $returnBill->total_discount) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center amount" name="return_bill_amount[]"
                                        value="{{ old('return_bill_amount.'.$loop->index, $returnBill->amount) }}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @elseif (isset($return_product))
            
                        <tr>
                            <td>
                                <select name="return_bill_product_ids[]" class="form-control product-select">
                                    <option value="">Choose Product</option> 
                                    <option value="{{ $return_product->id }}"  selected >
                                        {{ $return_product->name }}
                                    </option> 
                                </select>
                            </td>
                            <td>
                                <input type="number" name="return_bill_quantity[]" class="form-control quantity"
                                    placeholder="Quantity" value="1">
                            </td>
                            <td>
                                <input type="text" name="return_bill_price[]" class="form-control price" placeholder="Price" value={{ $return_product->mrp }}
                                    readonly>
                            </td>
                            <td>
                                <input type="number" name="return_bill_unit_discount[]"
                                    class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                    value="0">
                            </td>
                            <td>
                                <input type="text" name="return_bill_total_discount[]" class="form-control total_discount"
                                    placeholder="Total Discount" value="0" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control text-center amount" name="return_bill_amount[]" value="0"
                                    readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <select name="return_bill_product_ids[]" class="form-control product-select ">
                                    <option value="">Choose Product</option> 
                                </select>
                            </td>
                            <td>
                                <input type="number" name="return_bill_quantity[]" class="form-control quantity"
                                    placeholder="Quantity" value="1">
                            </td>
                            <td>
                                <input type="text" name="return_bill_price[]" class="form-control price" placeholder="Price"
                                    readonly>
                            </td>
                            <td>
                                <input type="number" name="return_bill_unit_discount[]"
                                    class="form-control unit_discount unit_discount_input" placeholder="Unit Discount"
                                    value="0">
                            </td>
                            <td>
                                <input type="text" name="return_bill_total_discount[]" class="form-control total_discount"
                                    placeholder="Total Discount" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control text-center amount" name="return_bill_amount[]"
                                    readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs remove_row_btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Total</td>
                        <td>
                            <input type="text" class="form-control text-center" id="net_amount_return"
                                name="return_bill_net_amount" readonly>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div class="form-group">
                                <textarea name="return_bill_description" class="form-control mt-2" rows="3"
                                    placeholder="Description...">{{ old('return_bill_description', !empty($serviceMyTask) && ($serviceMyTask->bill_type == 'service_return_bill') && $serviceMyTask->return_bill_description ? $serviceMyTask->return_bill_description : '') }}</textarea>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


@push('script')

    <script>
        $(document).ready(function () {
            console.log("Service Bill Script Loaded");


            // --- 1. TOGGLE BETWEEN SERVICE BILL AND RETURN BILL ---
            $('input[name="bill_type"]').on('change', function () {
                if ($(this).val() == 'service_bill') {
                    $('#serviceBillDiv').show();
                    $('#serviceReturnBillDiv').hide();
                } else {
                    $('#serviceBillDiv').hide();
                    $('#serviceReturnBillDiv').show();
                    initTomSelect(this.find('.product-select')[0]);
                }
                // Recalculate total for the visible table
                calculateTotal();
            });

            $('input[name="bill_type"]:checked').trigger('change'); // Trigger change on page load to set initial visibility
            // --- 2. ADD NEW ROW ---
            // Use event delegation to handle clicks on the "Add" button

            $(document).on('click', '.add_row_btn', function () {

                let table = $(this).closest('table');

                let newRow = $(`
                    <tr>
                        <td>
                            <select name="bill_product_ids[]" class="form-control product-select">
                                <option value="">Choose Product</option>
                            </select>
                        </td>
                        <td> <input type="number" name="bill_quantity[]" class="form-control quantity"  placeholder="Quantity" value="1"></td>
                        <td><input type="text" name="bill_price[]" class="form-control price" placeholder="Price"  value="0" readonly></td>
                        <td><input type="number" name="bill_unit_discount[]"  class="form-control unit_discount unit_discount_input" placeholder="Unit Discount" value="0"></td>
                        <td><input type="text" name="bill_total_discount[]" class="form-control total_discount" placeholder="Total Discount" value="0" readonly></td>
                        <td><input type="text" class="form-control text-center amount" name="bill_amount[]"value="0" readonly></td>
                        <td> <button type="button" class="btn btn-danger btn-xs remove_row_btn"><i class="fa fa-times"></i>   </button></td>
                    </tr>
                `);

                table.find('tbody').append(newRow);

                // IMPORTANT: pass DOM element, not string
                initTomSelect(newRow.find('.product-select')[0]);
            });


            $(document).on('click', '.return_add_row_btn', function () {

                let table = $(this).closest('table');

                let newRow = $(`
                    <tr>
                        <td>
                            <select name="return_bill_product_ids[]" class="form-control product-select">
                                <option value="">Choose Product</option>
                            </select>
                        </td>
                        <td> <input type="number" name="return_bill_quantity[]" class="form-control quantity"  placeholder="Quantity" value="1"></td>
                        <td><input type="text" name="return_bill_price[]" class="form-control price" placeholder="Price"  value="0" readonly></td>
                        <td><input type="number" name="return_bill_unit_discount[]"  class="form-control unit_discount unit_discount_input" placeholder="Unit Discount" value="0"></td>
                        <td><input type="text" name="return_bill_total_discount[]" class="form-control total_discount" placeholder="Total Discount" value="0" readonly></td>
                        <td><input type="text" class="form-control text-center amount" name="return_bill_amount[]"value="0" readonly></td>
                        <td> <button type="button" class="btn btn-danger btn-xs remove_row_btn"><i class="fa fa-times"></i>   </button></td>
                    </tr>
                `);

                table.find('tbody').append(newRow);

                // IMPORTANT: pass DOM element, not string
                initTomSelect(newRow.find('.product-select')[0]);
            });
 

            // --- 3. REMOVE ROW ---
            // Use event delegation for the remove button
            $(document).on('click', '.remove_row_btn', function () {

                const product_id = $(this).closest('tr').find('select.product_ids option:selected').text();
                deleteOtpVerification('Discount Changed for ' + product_id);
                deleteOtpVerification(" Discount Range Exceeded for " + product_id);
                var table = $(this).closest('table');
                // Only remove if there is more than one row
                if (table.find('tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    // Optional: Alert the user they can't remove the last row
                    toastr.warning("You cannot remove the last row.");
                }
                calculateTotal(); // Recalculate after removing
            });

            // --- 4. LOAD PRICE & CALCULATE ON CHANGE ---
            // Use event delegation for product selection, quantity, and discount changes
    

            $(document).on('change', '.product-select', function () {

                let select = this.tomselect;
                let selected = select.options[this.value];

                let price = selected?.price || 0;

                let row = $(this).closest('tr');

                row.find('.price').val(price);

                calculateRow(row);
            });


            $(document).on('keyup change', '.quantity, .unit_discount', function () {
                var row = $(this).closest('tr');
                calculateRow(row); // Recalculate when quantity or discount changes
            });

            // --- 5. CALCULATION FUNCTIONS ---

            /**
             * Calculates the total discount and amount for a single row.
             * @param {jQuery} row - The table row element to calculate.
             */
            function calculateRow(row) {
                var quantity = parseFloat(row.find('.quantity').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var unitDiscount = parseFloat(row.find('.unit_discount').val()) || 0;

                var totalDiscount = quantity * unitDiscount;
                var amount = (quantity * price) - totalDiscount;

                // Ensure amount doesn't go below 0
                if (amount < 0) {
                    amount = 0;
                }

                row.find('.total_discount').val(totalDiscount.toFixed(2));
                row.find('.amount').val(amount.toFixed(2));

                // After calculating the row, update the grand total
                calculateTotal();
            }

            /**
             * Calculates the grand total for the currently visible table.
             * Net amount = Total gross amount - Total discounts
             */
            function calculateTotal() {
                var totalGrossAmount = 0;
                var totalDiscount = 0;
                var netAmount = 0;

                // Determine which table is visible and calculate its total
                var visibleTable = $('input[name="bill_type"]:checked').val() === 'service_bill'
                    ? $('#product_info_table')
                    : $('#product_info_table_return');

                visibleTable.find('tbody tr').each(function () {
                    var row = $(this);
                    var quantity = parseFloat(row.find('.quantity').val()) || 0;
                    var price = parseFloat(row.find('.price').val()) || 0;
                    var rowTotalDiscount = parseFloat(row.find('.total_discount').val()) || 0;

                    var rowGrossAmount = quantity * price;  // Gross amount before discount
                    totalGrossAmount += rowGrossAmount;
                    totalDiscount += rowTotalDiscount;
                });

                // Calculate net amount by subtracting total discount from total gross amount
                netAmount = totalGrossAmount - totalDiscount;

                // Update the correct total and discount fields
                if (visibleTable.is('#product_info_table')) {
                    $('#net_amount_bill').val(netAmount.toFixed());
                    $('#discount_amount_bill').val(totalDiscount.toFixed());
                } else {
                    $('#net_amount_return').val(netAmount.toFixed());
                    // Assuming there will be a discount field for return bill as well, if not, this can be removed or adjusted
                    // $('#discount_amount_return').val(totalDiscount.toFixed());
                }

                updatePayable(netAmount);
            }

            // Initial calculation on page load for any pre-filled data
            calculateTotal();

            const productSelect = new TomSelect(".product-select", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('services.service-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {   
                            productSelect.clearOptions();
                            callback(res.map(item => ({  id: item.id, text: item.label, price: item.price })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                },
                onChange: function(value) {

                    let option = this.options[value]; // selected object

                    let price = option?.price || 0;

                    let row = $(this.input).closest('tr');

                    row.find('.price').val(price);
                }
            }); 
 

            

        });

        function initTomSelect(el) {

            if (!el) return;

            if (el.tomselect) {
                el.tomselect.destroy();
            }

            new TomSelect(el, {

                valueField: "id",
                labelField: "text",
                searchField: ["text"],

                load: function(query, callback) {

                    query = query || "";   // 🔥 FIX CRASH

                    if (query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('services.service-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) { 
                            callback(res.map(item => ({
                                id: item.id,
                                text: item.label,
                                price:item.price
                            })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                },
                onChange: function(value) {

                    let option = this.options[value]; // selected object

                    let price = option?.price || 0;

                    let row = $(this.input).closest('tr');

                    row.find('.price').val(price);
                }
            });
        }
    </script>
@endpush