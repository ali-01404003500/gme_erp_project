@section('title', 'Quotation Edit')
@section('description', 'Quotation Edit')
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
                                        {{ trans('menu.update-quotation-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('services.quotations.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>

                            <a href="{{ route('services.quotations.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-quotation-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('services.quotations.update', $quotation->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="service_id">Service Token ID <span class="text-danger">*</span></label>
                                            <input type="text" name="service_unique_id" class="form-control" id="service_unique_id"
                                                value="{{ old('service_unique_id', $quotation->service->service_unique_id) }}" readonly>
                                            <input type="hidden" name="service_id" id="service_id"
                                                value="{{ old('service_id', $quotation->service_id) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <input type="text" name="customer_name" class="form-control" id="customer_name"
                                                value="{{ old('customer_name', $quotation->customer->company_name) }}" readonly>
                                            <input type="hidden" name="customer_id" id="customer_id"
                                                value="{{ old('customer_id', $quotation->customer_id) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="date">Sales Date<span class="text-danger">*</span></label>
                                            <input type="text" name="date" id="date"
                                                class="form-control flatdate" placeholder="Sales Date"
                                                value="{{ $quotation->date }}" readonly>
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
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}"
                                                                                    {{ $product->id == $quotationDetail->product_id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach

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
                                                            {{-- <tr>
                                                                <td colspan="5" style="text-align: right;">Percentage %
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" id="percentage" value="">
                                                                    <input type="text" class="form-control text-center"
                                                                        id="additional_percentage" name="percentage"
                                                                        value="{{ old('percentage', numberFormat($quotation->percentage) ?? 0) }}">
                                                                </td>
                                                            </tr> --}}

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

                                            <div class="col-md-12">
                                                <div
                                                    class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
                // calculateTotalForPercentage();

            });

            // $("#additional_percentage").on("keyup change", function() {
            //     updateUnitDiscounts();
            //     calculateTotals();

            // });

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
    </script>
@endsection