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
                                                <label for="image_upload"
                                                    class="color-dark fs-14 fw-500 align-center">File Upload : </label>
                                                    <x-file-uploader multiple name="file_uploads"/>

                                                {{-- <input type="file"
                                                    class="file-control form-control"
                                                    id="image_upload" name="file_uploads[]"
                                                    multiple> --}}
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
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Product Name</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            <th style="width: 15%">Price</th>
                                                            <th style="width: 15%">S. Price</th>
                                                            <th style="width: 15%">Amount</th>
                                                            <th style="width: 8%" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                                    <i class="fa fa-plus"></i> Add</button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (old('product_ids'))
                                                            @foreach (old('product_ids') as $key => $product_id)
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_ids[]" class="form-control product_ids tom-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}" {{ $product_id == $product->id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
    
                                                                        </select>                                                                    
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]" id="quantity" value="{{ old('quantity')[$key] }}" class="form-control" placeholder="Quantity">
                                                                    </td>
                                                                    <td><input type="text" name="price[]" id="price" value="{{ old('price')[$key] }}" class="form-control" placeholder="Price"> </td>
                                                                    <td><input type="text" name="sales_price[]" id="sales_price" value="{{ old('sales_price')[$key] }}" class="form-control" placeholder="Sales Price"> </td>
                                                                   
                                                                    <td> 
                                                                        <input type="text"
                                                                        class="form-control text-center"  value="{{ old('amount')[$key] }}"
                                                                        id="amount" name="amount[]" readonly value="0"></td>
                                                                    <td style="text-align: right;">
                                                                        <button type="button" class="btn btn-danger disabled btn-xs" id="remove_row" disabled
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>                                                                    
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                        <tr>
                                                            <td>
                                                                {{-- <input type="text" name="product_ids[]" id="product_id" class="form-control product_ids" placeholder="Product Name"> --}}
                                                                <select name="product_ids[]" class="form-control product_ids tom-select">
                                                                    <option value="">Choose Product</option>
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}">
                                                                            {{ $product->name }}</option>
                                                                    @endforeach

                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="quantity[]" id="quantity" 
                                                                    class="form-control" placeholder="Quantity">
                                                            </td>
                                                            <td><input type="text" name="price[]" id="price" class="form-control" placeholder="Price"> </td>
                                                            <td><input type="text" name="sales_price[]" id="sales_price" class="form-control" placeholder="Sales Price"> </td>
                                                           
                                                            <td> 
                                                                <input type="text"
                                                                class="form-control text-center" 
                                                                id="amount" name="amount[]" readonly value="0"></td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger disabled btn-xs" id="remove_row" disabled
                                                                    onclick="removeRow(this)">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                            
                                                        @endif
                                                           
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" style="text-align: right;">
                                                                Total Amount
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="{{ old('total_amount') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" style="text-align: right;">Discount</td>
                                                            <td><input type="text" class="form-control text-center" id="discount" name="discount" value="{{ old('discount',0) }}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" style="text-align: right;">Net Amount</td>
                                                            <td><input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount') }}"></td>

                                                        </tr>
                                                    </tfoot>
                                                </table>
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

    // Remove row function
    function removeRow(button) {
        $(button).closest('tr').remove();
        calculateTotalAmount();
        calculateNetAmount();
    }

    // Calculate total price for a row
    function calculateTotalPrice(row) {
        const qty = parseFloat(row.find("#quantity").val()) || 0;
        const price = parseFloat(row.find("#price").val()) || 0;
        const total = qty * price;
        row.find("#amount").val(total);
    }

    // Calculate total amount for all rows
    function calculateTotalAmount() {
        let totalAmount = 0;
        $("#product_info_table tbody tr").each(function() {
            const amount = parseFloat($(this).find("#amount").val()) || 0;
            totalAmount += amount;
        });
        $("#total_amount").val(totalAmount);
    }

    // Calculate net amount after discount
    function calculateNetAmount() {
        const totalAmount = parseFloat($("#total_amount").val()) || 0;
        let discount = parseFloat($("#discount").val()) || 0;
        if (discount > totalAmount) {
            discount = totalAmount;
            $("#discount").val(discount);
        }
        const netAmount = totalAmount - discount;
        $("#net_amount").val(netAmount);
        updatePayable(netAmount);
    }

    // Initial calculations for existing rows
    $("#product_info_table tbody tr").each(function() {
        calculateTotalPrice($(this));
        calculateTotalAmount();
        calculateNetAmount();
    });

    // Fetch product price and update fields
    async function getProductPrice(selectElement) {
    const productId = selectElement.value.trim();
    const row = selectElement.closest('tr');
    const priceInput = row.querySelector('input[name="price[]"]');
    const salespriceInput = row.querySelector('input[name="sales_price[]"]');
    const qtyInput = row.querySelector('input[name="quantity[]"]');

    // If the product was already loaded in this row, do nothing.
    if (row.dataset.loadedProductId && row.dataset.loadedProductId === productId) {
        return;
    }

    if (productId !== '') {
        // Count how many times this product is selected across rows.
        let count = 0;
        $(".product_ids").each(function() {
            if ($(this).val() === productId) count++;
        });
        if (count > 1) {
            // Warn user but do not remove the already loaded data.
            showToast('warning', 'You have already selected this product.');
             // Clear the current select field.
             $(selectElement).val('');
            if (selectElement.tomselect) {
                selectElement.tomselect.clear();
            }
            // Clear any loaded data for this row.
            delete row.dataset.loadedProductId;
            priceInput.value = '';
            salespriceInput.value = '';
            qtyInput.value = '';
            return;
        }

        try {
            const response = await $.ajax({
                url: '{{ route('purchase.get.product.list') }}',
                method: 'GET',
                data: { id: productId }
            });
            const product = response[0];
            if (!product) {
                showToast('error', 'Price not found.');
                priceInput.value = '';
                salespriceInput.value = '';
                return;
            }

            priceInput.value = product.mrp;
            salespriceInput.value = product.mrp;
            qtyInput.value = 1;

            // Mark this row as having loaded this product.
            row.dataset.loadedProductId = productId;
        } catch (error) {
            console.error(error);
            priceInput.value = '';
            salespriceInput.value = '';
            showToast('error', 'An error occurred while fetching product details.');
        }
    } else {
        // If the select is cleared, clear the price fields and remove the loaded flag.
        priceInput.value = '';
        salespriceInput.value = '';
        delete row.dataset.loadedProductId;
    }
}


    // Display toast messages
    function showToast(type, message) {
        if (type === 'warning') {
            toastr.warning(message);
        } else if (type === 'error') {
            toastr.error(message);
        }
    }

    var selectedProductIds = [];

    // Event delegation for product selection change
    $(document).on('change', '.product_ids', async function() {
        await getProductPrice(this);
        calculateTotalPrice($(this).closest('tr'));
        calculateTotalAmount();
        calculateNetAmount();
    });

    // Event delegation for discount change
    $("#discount").on("keyup", function() {
        calculateNetAmount();
    });

});

</script>
@stack('script')

@endsection
