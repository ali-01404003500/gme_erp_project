@section('title', 'Purchase Requisition Edit')
@section('description', 'Purchase Requisition Edit')
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
                                        {{ trans('menu.update-requisition-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 row">
                                @if (hasPermission('purchase.requisitions.index'))
                                <a href="{{ route('purchase.requisitions.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                @endif

                                @if (hasPermission('purchase.requisitions.create'))
                                    <a href="{{ route('purchase.requisitions.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-requisition-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.requisitions.update', $requisition->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                    <div class="row mb-4">
                                  
                                        <div class="col-md-4">
                                            <label for="supplier_id">Supplier Name</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control">
                                                <option value="">Choose Supplier Name</option>
                                             
                                                    <option value="{{ $requisition->supplier_id }}" selected >
                                                        {{ $requisition->supplier->company_name }}
                                                    </option>
                                             
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="warehouse_id">Invoice To<span class="text-danger">*</span></label>
                                            <select name="branch_id" id="warehouse_id" class="form-control tom-select">
                                                <option value="">Choose Invoice To</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ old('warehouse_id' , $requisition->branch_id) == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customer_id">Purchase For</label>
                                            <select name="customer_id" id="customer_id" class="form-control  ">
                                                <option value="">Choose Customer</option> 
                                                <option value="{{ $requisition->customer_id }}" selected >
                                                    {{ optional($requisition->customer)->company_name }} -  @if (optional($requisition->customer)->area != null) ({{ optional($requisition->customer)->area->area }}) @endif
                                                </option>
                                        
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" class="form-control"
                                                id="invoice_date" placeholder="Invoice Date"
                                                value="{{ old('invoice_date', date('Y-m-d', strtotime($requisition->invoice_date))) }}">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <div class="form-group">
                                                <label for="image_upload"
                                                    class="color-dark fs-14 fw-500 align-center">File Upload : </label>
                                                    <x-file-uploader multiple :value="$requisition->file_uploads" name="file_uploads"/>

                                               
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-12 mt-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="3" class="form-control"
                                                placeholder="Transfer Description">{{ old('description', $requisition->description) }}</textarea>
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
                                                        <th style="width: 8%; text-align: right;">
                                                          <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                            <i class="fa fa-plus"></i> Add
                                                          </button>
                                                        </th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      @foreach ($requisition->requisitionDetails as $key => $item)
                                                        <tr>
                                                          <td>
                                                            <select name="product_ids[]" class="form-control product_ids  " onchange="getProductPrice(this)">  
                                                               
                                                                <option value="{{ $item->product_id }}" selected>
                                                                {{ $item->product->name }}
                                                                </option>
                                                       

                                                            </select>
                                                          </td>
                                                          <td>
                                                            <input type="text" name="quantity[]" value="{{ $item->quantity }}" class="form-control quantity" placeholder="Quantity">
                                                          </td>
                                                          <td>
                                                            <input type="text" name="price[]" value="{{ $item->price }}" class="form-control price" placeholder="Price">
                                                          </td>
                                                          <td>
                                                            <input type="text" name="sales_price[]" value="{{ $item->sales_price }}" class="form-control sales_price" placeholder="Sales Price">
                                                          </td>
                                                          <td>
                                                            <input type="text" name="amount[]" class="form-control text-center amount" readonly value="{{ old('amount', $item->amount) }}">
                                                          </td>
                                                          <td style="text-align: right;">
                                                            <button type="button" class="btn btn-danger btn-xs" id="remove_row"
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                          </td>
                                                        </tr>
                                                      @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                      <tr>
                                                        <td colspan="4" style="text-align: right;">Total Amount</td>
                                                        <td>
                                                          <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="{{ old('total_amount', $requisition->total_amount) }}">
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="4" style="text-align: right;">Discount</td>
                                                        <td>
                                                          <input type="text" class="form-control text-center" id="discount" name="discount" value="{{ old('discount', $requisition->discount) }}">
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="4" style="text-align: right;">Net Amount</td>
                                                        <td>
                                                          <input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount', $requisition->net_amount) }}">
                                                        </td>
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
                                        {{-- @dd($makePayment->paymentDetails ); --}}
                                        @include('Account::payments.make-payments.payments-details', [
                                            'payments' => $requisition->paymentDetails ?? []
                                        ])
                                    </div>
                                    <div class="col-md-12">
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
    initialRow.find('input').val(''); // Clear input fields
    initialRow.find('.tom-select option:selected').removeAttr('selected');
    initialRow.find('#remove_row').removeClass('disabled').removeAttr('disabled'); // Enable remove button

    $("#add_row").click(function() {
        const newRow = initialRow.clone();
        newRow.find('.tom-select').each(function() {
            new TomSelect(this, {}); // Initialize tom-select for new rows
        });
        $("#product_info_table tbody").append(newRow);
        prouctAutocompleteLoad(newRow);
    });

    $(document).ready(function() {

        const supplierSelect = new TomSelect("#supplier_id", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('purchase.purchase-autocomplete.suppliers') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        supplierSelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        @if(request('supplier_id'))
            supplierSelect.addOption({
                id: "{{ request('supplier_id') }}",
                text: "{{ request('supplier_id') }}"
            });
            supplierSelect.setValue("{{ request('supplier_id') }}");
        @endif


        const companySelect = new TomSelect("#customer_id", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('purchase.purchase-autocomplete.customers') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        companySelect.clearOptions(); 
                        callback(res.map(item => ({ id: item.id, text: item.label, phone: item.phone, address: item.address    })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        @if(request('customer_id'))
            companySelect.addOption({
                id: "{{ request('customer_id') }}",
                text: "{{ request('customer_id') }}"
            });
            companySelect.setValue("{{ request('customer_id') }}");
        @endif


        const productSelect = new TomSelect(".product_ids", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('purchase.purchase-autocomplete.products') }}",
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


        // Event delegation for dynamic elements
        $("#product_info_table tbody").on("keyup change", ".quantity, .price, .product_ids", function() {
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
            const qty = parseFloat(row.find(".quantity").val()) || 0;
            const price = parseFloat(row.find(".price").val()) || 0;
            const total = qty * price;
            row.find(".amount").val(total.toFixed());
        }

        // Calculate total amount for all rows
        function calculateTotalAmount() {
            let totalAmount = 0;
            $("#product_info_table tbody tr").each(function() {
                const amount = parseFloat($(this).find(".amount").val()) || 0;
                totalAmount += amount;
            });
            $("#total_amount").val(totalAmount.toFixed());
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
    
    // If an old price already exists, skip the ajax call.
    if (priceInput.value.trim() !== "") {
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

        // Event delegation for product selection change
        $(document).on('change', '.product_ids', async function() {

            const product_id = $(this).val();
            if (product_id == "") {
                return false;
            }
            if (product_id != "" && $(".product_ids [value='" + product_id + "']:selected").length > 1) {
                clearRow($(this).closest('tr'));
                toastr.warning('This Product is already selected');
                return false;
            }
            else
            {
                $("#add_row").click(); 
            }
            
            await getProductPrice(this);
            calculateTotalPrice($(this).closest('tr'));
            calculateTotalAmount();
            calculateNetAmount();
        });

        // Event delegation for discount change
        $("#discount").on("keyup", function() {
            calculateNetAmount();
        });

        // Initial calculations for existing rows on page load
        $("#product_info_table tbody tr").each(function() {
            const row = $(this);
            calculateTotalPrice(row);
            const productId = row.find('.product_ids').val();
            if (productId) {
                getProductPrice(row.find('.product_ids')[0]);
            }
        });

        calculateTotalAmount();
        calculateNetAmount();
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
    @stack('script')

@endsection
