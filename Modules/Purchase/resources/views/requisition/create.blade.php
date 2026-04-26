@section('title', 'Purchase Requisition Create')
@section('description', 'Purchase Requisition Create')
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
                                        {{ trans('menu.create-requisition-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('purchase.requisitions.index'))
                            <a href="{{ route('purchase.requisitions.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-requisition-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.requisitions.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="supplier_id">Supplier Name</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control tom-select">
                                            <option value="">Choose Supplier Name</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="warehouse_id">Invoice To<span class="text-danger">*</span></label>
                                        <select name="branch_id" id="warehouse_id" class="form-control tom-select required" required>
                                            <option value="">Choose Invoice To</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('branch_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="customer_id">Purchase For</label>
                                        <select name="customer_id" id="customer_id" class="form-control tom-select">
                                            <option value="">Choose Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null) ({{ $customer->area->area }}) @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                    <div class="col-md-4 mt-4">
                                        <label for="invoice_date">Invoice Date<span class="text-danger">*</span></label>
                                        <input type="date" name="invoice_date" class="form-control"
                                            id="invoice_date" placeholder="Invoice Date"
                                            value="{{ old('invoice_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="image_upload" class="color-dark fs-14 fw-500 align-center">File Upload : </label>
                                            <x-file-uploader multiple name="file_uploads"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="3" class="form-control"
                                            placeholder="Transfer Description">{{ old('description') }}</textarea>
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
                                                            <th style="width: 15%">S. Price</th>
                                                            <th style="width: 15%">Amount</th>
                                                            <th style="width: 8%">
                                                                <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                                    <i class="fa fa-plus"></i> Add
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="product_table_body">
                                                        <tr class="product-row">
                                                            <td>
                                                                <select name="product_ids[]" class="form-control product_ids">
                                                                    <option value="">Choose Product</option>
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="quantity[]" class="form-control quantity" placeholder="Quantity" min="1" value="1">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="price[]" class="form-control price" placeholder="Price" step="0.01" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="sales_price[]" class="form-control sales_price" placeholder="Sales Price" step="0.01" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center amount" name="amount[]" readonly value="0">
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-danger btn-xs remove-row" disabled>
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                                            <td>
                                                                <input type="number" class="form-control text-center" id="discount" name="discount" value="0" step="0.01" min="0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>Net Amount:</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 my-2">
                                    <h4>Make Payment Information:</h4>
                                </div>
                                <div class="col-md-12">
                                    @include('Account::payments.make-payments.payments-details', ['payments' =>[]])
                                </div>

                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
       const initialRow = $("#product_info_table tbody tr:first-child").clone();
    initialRow.find('input').val('');
    initialRow.find('tom-select option:selected').removeAttr('selected');                   //table er first er row k dhora 
    initialRow.find('#remove_row').removeClass('disabled').removeAttr('disabled');

    $("#add_row").click(function() {
        const newRow = initialRow.clone();
        newRow.find('.tom-select').each(function() {     //TOm select apply korar jonno
            new TomSelect(this, {});
        });
        $("#product_info_table tbody").append(newRow);
    });

    $(document).ready(function() {
 
    // Event delegation for dynamic elements
    $("#product_info_table tbody").on("keyup change" , "#quantity, #price, .product_ids", function() {
        const row = $(this).closest('tr');
        calculateTotalPrice(row);
        calculateTotalAmount();
        calculateNetAmount();
    });
    $("#product_info_table tbody").on("click", "#remove_row", function() {
        $(this).closest('tr').remove();
        calculateTotalAmount();
        calculateNetAmount();
        
    });

        // Function to calculate total amount
        function calculateTotalAmount() {
            let total = 0;
            $('.product-row').each(function() {
                let amount = parseFloat($(this).find('.amount').val()) || 0;
                total += amount;
            });
            $('#total_amount').val(total.toFixed(2));
            calculateNetAmount();
        }

        // Function to calculate net amount
        function calculateNetAmount() {
            let total = parseFloat($('#total_amount').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;
            if (discount > total) {
                discount = total;
                $('#discount').val(discount.toFixed(2));
            }
            let netAmount = total - discount;
            $('#net_amount').val(netAmount.toFixed(2));
            if (typeof updatePayable === 'function') {
                updatePayable(netAmount);
            }
        }

        // Function to add new row
        function addNewRow() {
            let newRow = `
                <tr class="product-row">
                    <td>
                        <select name="product_ids[]" class="form-control product_ids">
                            <option value="">Choose Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control quantity" placeholder="Quantity" min="1" value="1">
                    </td>
                    <td>
                        <input type="number" name="price[]" class="form-control price" placeholder="Price" step="0.01" readonly>
                    </td>
                    <td>
                        <input type="number" name="sales_price[]" class="form-control sales_price" placeholder="Sales Price" step="0.01" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center amount" name="amount[]" readonly value="0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-xs remove-row">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#product_table_body').append(newRow);
            updateRemoveButtons();
            
            // Initialize tom-select for new row if needed
            if (typeof TomSelect !== 'undefined') {
                $('.product_ids').last().each(function() {
                    if (!this.tomselect) {
                        new TomSelect(this, {});
                    }
                });
            }
        }

        // Function to update remove buttons (disable first row's remove button)
        function updateRemoveButtons() {
            let rowCount = $('.product-row').length;
            $('.product-row').each(function(index) {
                let removeBtn = $(this).find('.remove-row');
                if (index === 0) {
                    removeBtn.prop('disabled', true);
                } else {
                    removeBtn.prop('disabled', false);
                }
            });
        }

        // Function to check and add new row automatically
        function checkAndAddNewRow() {
            let lastRow = $('.product-row').last();
            let productId = lastRow.find('.product_ids').val();
            let quantity = lastRow.find('.quantity').val();
            
            if (productId && productId !== '' && quantity && parseFloat(quantity) > 0) {
                addNewRow();
            }
        }

        // Function to get product price
        async function getProductPrice(selectElement) {
            let $select = $(selectElement);
            let productId = $select.val();
            let $row = $select.closest('tr');
            let $priceInput = $row.find('.price');
            let $salesPriceInput = $row.find('.sales_price');
            
            if (productId && productId !== '') {
                // Check for duplicate product
                let duplicate = false;
                $('.product_ids').each(function() {
                    if ($(this).val() == productId && $(this).closest('tr')[0] !== $row[0]) {
                        duplicate = true;
                    }
                });
                
                if (duplicate) {
                    toastr.warning('This product is already selected!');
                    $select.val('');
                    if ($select[0].tomselect) {
                        $select[0].tomselect.clear();
                    }
                    return;
                }
                
                try {
                    let response = await $.ajax({
                        url: '{{ route('purchase.get.product.list') }}',
                        method: 'GET',
                        data: { id: productId }
                    });
                    let product = response[0];
                    if (product) {
                        $priceInput.val(product.mrp);
                        $salesPriceInput.val(product.mrp);
                        calculateRowAmount($row);
                        calculateTotalAmount();
                        checkAndAddNewRow();
                    }
                } catch (error) {
                    console.error(error);
                    toastr.error('Error fetching product details');
                }
            } else {
                $priceInput.val('');
                $salesPriceInput.val('');
                calculateRowAmount($row);
                calculateTotalAmount();
            }
        }

        // Event: Add row button click
        $('#add_row').click(function() {
            addNewRow();
        });

        // Event: Product change
        $(document).on('change', '.product_ids', function() {
            getProductPrice(this);
        });

        // Event: Quantity change
        $(document).on('keyup change', '.quantity', function() {
            let $row = $(this).closest('tr');
            calculateRowAmount($row);
            calculateTotalAmount();
            
            let isLastRow = $row.index() === $('.product-row').length - 1;
            let productId = $row.find('.product_ids').val();
            let quantity = $row.find('.quantity').val();
            
            if (isLastRow && productId && productId !== '' && quantity && parseFloat(quantity) > 0) {
                checkAndAddNewRow();
            }
        });

        // Event: Price change
        $(document).on('keyup change', '.price', function() {
            let $row = $(this).closest('tr');
            calculateRowAmount($row);
            calculateTotalAmount();
        });

        // Event: Remove row
        $(document).on('click', '.remove-row', function() {
            if ($('.product-row').length > 1) {
                $(this).closest('tr').remove();
                calculateTotalAmount();
                updateRemoveButtons();
            }
        });

        // Event: Discount change
        $('#discount').on('keyup change', function() {
            calculateNetAmount();
        });

        // Initialize tom-select for existing selects
        if (typeof TomSelect !== 'undefined') {
            $('.product_ids').each(function() {
                if (!this.tomselect) {
                    new TomSelect(this, {});
                }
            });
        }

        // Initial calculations
        $('.product-row').each(function() {
            calculateRowAmount($(this));
        });
        calculateTotalAmount();
        updateRemoveButtons();
    });
</script>
@stack('script')
@endsection