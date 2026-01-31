@section('title', 'Purchase Order Edit')
@section('description', 'Purchase Order Edit')
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
                                        {{ trans('menu.update-purchase-order-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            @if (hasPermission('purchase.orders.index'))
                            <a href="{{ route('purchase.orders.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                            @if (hasPermission('purchase.orders.create'))
                            <a href="{{ route('purchase.orders.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-purchase-order-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.orders.update', $purchaseOrder->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">
                                  
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="supplier_id">Supplier Name</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control tom-select">
                                                <option value="">Choose Supplier Name</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="po_date">PO Date</label>
                                            <input type="date" name="po_date" class="form-control flatdate"
                                                id="po_date" placeholder="PO Date"
                                                value="{{ date('Y-m-d', strtotime($purchaseOrder->po_date)) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier_phone">Supplier Phone</label>
                                            <input type="text" name="supplier_phone" class="form-control"
                                                id="supplier_phone" placeholder="Supplier Phone"
                                                value="{{optional($purchaseOrder->supplier)->phone }}">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_place">Company Place</label>
                                            <input type="text" name="company_place" class="form-control"
                                                id="company_place" placeholder="Company Place"
                                                value="{{ optional($purchaseOrder->supplier)->company_place }}">
                                           
                                        </div>

                                    </div>
                                    
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="supplier_address">Supplier Address</label>
                                            <textarea name="supplier_address" id="supplier_address" cols="30" rows="3" class="form-control"
                                                placeholder="Transfer Description">{{ old('supplier_address', optional($purchaseOrder->supplier)->address) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="search_by_product_id">Product</label>
                                            <select name="search_by_product_id" id="search_by_product_id" class="form-control tom-select">
                                                <option value="">Choose Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ old('search_by_product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="search_by_brand_id">Brand</label>
                                            <select name="search_by_brand_id" id="search_by_brand_id" class="form-control tom-select">
                                                <option value="">Choose Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ old('search_by_brand_id', $purchaseOrder->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Model</th>
                                                            <th style="width: 15%">Description</th>
                                                            <th style="width: 15%">HS Code</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            <th style="width: 15%">Price</th>
                                                            <th style="width: 15%">Amount</th>
                                                            <th style="width: 8%" style="text-align: right;">
                                                                Action
                                                                {{-- <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                                    <i class="fa fa-plus"></i> Add</button> --}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($purchaseOrder->detailes as $key => $value)
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="product_ids[]" id="product_id" value="{{ $value->product_id }}" class="form-control product_ids" placeholder="Product Name" hidden>
                                                                <input type="text" name="product_model[]" id="product_model" value="{{ $value->product_model }}" class="form-control product_model">
                            
                                                            </td>
                                                            <td> 
                                                                <input type="text" name="product_description[]" id="product_description" value="{{ $value->product_description }}" class="form-control product_description" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="hs_code[]" id="hs_code" value="{{ $value->hs_code }}" class="form-control hs_code"  >
                                                            </td>
                                                            <td>
                                                                <input type="text" name="quantity[]" value="{{ $value->quantity }}" id="quantity"
                                                                    class="form-control" placeholder="Quantity">
                                                            </td>
                                                            <td><input type="text" name="price[]" id="price" value="{{ $value->price }}" class="form-control" placeholder="Price"> </td>
                                                           
                                                            <td> 
                                                                <input type="text"
                                                                class="form-control text-center" 
                                                                id="amount" name="amount[]" readonly value="{{ $value->amount }}"></td>
                                                            <td>
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
                                                            <td colspan="5" style="text-align: right;">
                                                                Total Amount
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="{{ old('total_amount', $purchaseOrder->total_amount) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;">Transport Title</td>
                                                            <td colspan="3" style="text-align: left;"><input type="text" class="form-control" placeholder="Transport Title" id="transport_title" name="transport_title" value="{{ old('transport_title', $purchaseOrder->transport_title) }}"></td>
                                                            <td style="text-align: right;">Cost</td>
                                                            <td><input type="text" class="form-control text-center" id="transport_cost" name="transport_cost" value="{{ old('transport_cost', $purchaseOrder->transport_cost) }}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Net Amount</td>
                                                            <td><input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount', $purchaseOrder->net_amount) }}"></td>

                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" cols="10" rows="1" class="form-control"
                                                            placeholder="Remarks">{{ old('remarks', $purchaseOrder->remarks) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h2>Shippling Information</h2>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipping_method">Shipping Method</label>
                                            <select name="shipping_method" id="shipping_method" class="form-control tom-select">
                                                <option value="LC" {{ old('shipping_method', $purchaseOrder->shipping_method) == 'LC' ? 'selected' : ''}}>LC</option>
                                                <option value="TT" {{ old('shipping_method', $purchaseOrder->shipping_method) == 'TT' ? 'selected' : ''}}>TT</option>
                                                <option value="LC/TT" {{ old('shipping_method', $purchaseOrder->shipping_method) == 'LC/TT' ? 'selected' : ''}}>LC/TT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipping_terms">Shipping Terms</label>
                                            <select name="shipping_terms" id="shipping_terms" class="form-control tom-select">
                                                <option value="By Sea" {{ old('shipping_terms', $purchaseOrder->shipping_terms) == 'By Sea' ? 'selected' : ''}}>By Sea</option>
                                                <option value="By Air" {{ old('shipping_terms', $purchaseOrder->shipping_terms) == 'By Air' ? 'selected' : ''}}>By Air</option>
                                                <option value="By Road" {{ old('shipping_terms', $purchaseOrder->shipping_terms) == 'By Road' ? 'selected' : ''}}>By Road</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="po_date">Delivery Date</label>
                                            <input type="date" name="delivery_date" class="form-control"
                                                id="delivery_date" placeholder="Delivery Date"
                                                value="{{ date('Y-m-d', strtotime($purchaseOrder->delivery_date)) }}">
                                        </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        const row = $("#product_info_table tbody tr:first-child").clone();
        row.find('input').val('');
        row.find('select').val('').trigger('change'); // Reset select options
        row.find('#remove_row').removeClass('disabled').removeAttr('disabled');
        window.row = row;

        $("#add_row").click(function() {
            const newRow = row.clone();
            newRow.find('.tom-select').each(function() {
                new TomSelect(this, {});
            });
            $("#product_info_table tbody").append(newRow);
            
            // Calculate for the new row
            calculateTotalPrice(newRow);
            calculateTotalAmount();
            calculateNetAmount();
        });

        // Handle change events for quantity and price
        $("#product_info_table tbody").on("keyup", "input[type='text']", function() {
            calculateTotalPrice($(this).closest('tr'));
            calculateTotalAmount();
            calculateNetAmount();
        });

        // Remove row function
        // $("#product_info_table tbody").on("click", "#remove_row", function() {
        //     removeRow($(this).closest('tr'));
        // });

        // Initial calculation for existing rows
        $("#product_info_table tbody tr").each(function() {
            calculateTotalPrice($(this));
        });

        // Calculate total amount and net amount
        $("#transport_cost").on("keyup", function() {
            calculateNetAmount();
        });

        // Initial calculations
        calculateTotalAmount();
        calculateNetAmount();
    });

    function calculateTotalPrice(row) {
        var qty = parseFloat($(row).find("#quantity").val()) || 0;
        var price = parseFloat($(row).find("#price").val()) || 0;
        var total = qty * price;
        $(row).find("#amount").val(total);
    }

    function calculateTotalAmount() {
        var totalAmount = 0;
        $("#product_info_table tbody tr").each(function() {
            var amount = parseFloat($(this).find("#amount").val()) || 0;
            totalAmount += amount;
        });
        $("#total_amount").val(totalAmount);
    }

    function calculateNetAmount() {
        var totalAmount = parseFloat($("#total_amount").val()) || 0;
        var transportCost = parseFloat($("#transport_cost").val()) || 0;
        var netAmount = totalAmount + transportCost;
        $("#net_amount").val(netAmount);
    }

    function removeRow(row) {
        $(row).closest('tr').remove();
        calculateTotalAmount();
        calculateNetAmount();
    }
</script>

<script>
    function getSupplierData() {
    var id = $("#supplier_id option:selected").val();
    if (id) {
        $.ajax({
            url: `{{ route('purchase.get.supplier-data') }}?id=${id}`,
            success: function(data) {
                if (data && data.length > 0) {
                    var supplier = data[0]; 
                    $("#supplier_address").val(supplier.address);
                    $("#supplier_phone").val(supplier.phone);
                    $("#company_place").val(supplier.company_place);
                }
                if(supplier.brand[0].product_catalog.length > 0){
                    $('#product_info_table tbody').html('');
                }else{
                    $('#product_info_table tbody').html('');
                    const newRow =  window.row.clone();
                    newRow.find('.tom-select').each(function() {
                        new TomSelect(this, {});
                    });
                    $("#product_info_table tbody").append(newRow);

                }
                supplier.brand[0].product_catalog.forEach(function(product) {
                    console.log(product);
                    var row = `<tr>
                                    <td>
                                        <input type="text" name="product_ids[]" value="${product.id}" id="product_id" class="form-control product_ids" placeholder="Product Name" hidden>
                                        <input type="text" name="product_model[]" value="${product.model}" id="product_model" class="form-control product_model">
    
                                    </td>
                                    <td> 
                                        <input type="text" name="product_description[]" value="${product.name}" id="product_description" class="form-control product_description" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="hs_code[]" value="${product.product[0]?.hs_code??''}" id="hs_code" class="form-control hs_code"  >
                                    </td>
                                    <td>
                                        <input type="text" name="quantity[]" id="quantity" value="1"
                                            class="form-control" placeholder="Quantity">
                                    </td>
                                    <td><input type="text" name="price[]" value="${product.mrp??0}" id="price" class="form-control" placeholder="Price"> </td>
                                    
                                    <td> 
                                        <input type="text"
                                        class="form-control text-center" 
                                        id="amount" name="amount[]" readonly value="0"></td>
                                    <td>
                                        <button type="button" class="btn btn-danger  btn-xs" id="remove_row" 
                                            onclick="removeRow(this)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>`;
                    $('#product_info_table tbody').append(row);
                    calculateTotalPrice($('#product_info_table tbody tr:last'));
                    calculateTotalAmount();
                    calculateNetAmount();

                });
              
            }
        });
    }
}

function getProductData() {
    var id = $("#search_by_product_id option:selected").val();
    if (id) {
        $.ajax({
            url: `{{ route('purchase.product-data') }}?id=${id}`,
            success: function(data) {
                if (data && data.length > 0) {
                    var product = data[0]; 
                // if(data && data.length > 0){
                //     $('#product_info_table tbody').html('');
                // }else{
                //     $('#product_info_table tbody').html('');
                //     const newRow =  window.row.clone();
                //     newRow.find('.tom-select').each(function() {
                //         new TomSelect(this, {});
                //     });
                //     $("#product_info_table tbody").append(newRow);
                // }

                    var row = `<tr>
                                    <td>
                                        <input type="text" name="product_ids[]" value="${product.id}" id="product_id" class="form-control product_ids" placeholder="Product Name" hidden>
                                        <input type="text" name="product_model[]" value="${product.model}" id="product_model" class="form-control product_model">
    
                                    </td>
                                    <td> 
                                        <input type="text" name="product_description[]" value="${product.name}" id="product_description" class="form-control product_description" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="hs_code[]" value="${product.product[0]?.hs_code??''}" id="hs_code" class="form-control hs_code"  >
                                    </td>
                                    <td>
                                        <input type="text" name="quantity[]" id="quantity" value="1"
                                            class="form-control" placeholder="Quantity">
                                    </td>
                                    <td><input type="text" name="price[]" value="${product.mrp??0}" id="price" class="form-control" placeholder="Price"> </td>
                                    
                                    <td> 
                                        <input type="text"
                                        class="form-control text-center" 
                                        id="amount" name="amount[]" readonly value="0"></td>
                                    <td>
                                        <button type="button" class="btn btn-danger  btn-xs" id="remove_row" 
                                            onclick="removeRow(this)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>`;
                    $('#product_info_table tbody').append(row);
                    calculateTotalPrice($('#product_info_table tbody tr:last'));
                    calculateTotalAmount();
                    calculateNetAmount();
                }
            }
        });
    }
}
function getProductDataBrandWise() {
    var id = $("#search_by_brand_id option:selected").val();
    if (id) {
        $.ajax({
            url: `{{ route('purchase.product-data.brand.wise') }}?id=${id}`,
            success: function(data) {
                if (data && data.length > 0) {
                    var product = data[0]; 
                if(data && data.length > 0){
                    $('#product_info_table tbody').html('');
                }else{
                    $('#product_info_table tbody').html('');
                    const newRow =  window.row.clone();
                    newRow.find('.tom-select').each(function() {
                        new TomSelect(this, {});
                    });
                    $("#product_info_table tbody").append(newRow);
                }
                data.forEach(function(product) {
                    var row = `<tr>
                                    <td>
                                        <input type="text" name="product_ids[]" value="${product.id}" id="product_id" class="form-control product_ids" placeholder="Product Name" hidden>
                                        <input type="text" name="product_model[]" value="${product.model}" id="product_model" class="form-control product_model">
    
                                    </td>
                                    <td> 
                                        <input type="text" name="product_description[]" value="${product.name}" id="product_description" class="form-control product_description" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="hs_code[]" value="${product.product[0]?.hs_code??''}" id="hs_code" class="form-control hs_code"  >
                                    </td>
                                    <td>
                                        <input type="text" name="quantity[]" id="quantity" value="1"
                                            class="form-control" placeholder="Quantity">
                                    </td>
                                    <td><input type="text" name="price[]" value="${product.mrp??0}" id="price" class="form-control" placeholder="Price"> </td>
                                    
                                    <td> 
                                        <input type="text"
                                        class="form-control text-center" 
                                        id="amount" name="amount[]" readonly value="0"></td>
                                    <td>
                                        <button type="button" class="btn btn-danger  btn-xs" id="remove_row" 
                                            onclick="removeRow(this)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>`;
                    $('#product_info_table tbody').append(row);
                    calculateTotalPrice($('#product_info_table tbody tr:last'));
                    calculateTotalAmount();
                    calculateNetAmount();
                });
                }
            }
        });
    }
}
$(document).ready(function() {
    $('#supplier_id').change(getSupplierData);
    // getSupplierData();

    $('#search_by_product_id').change(getProductData);
    // getProductData();

    $('#search_by_brand_id').change(getProductDataBrandWise);
    // getProductDataBrandWise();



    calculateTotalPrice(row);
    calculateTotalAmount();
    calculateNetAmount();
});
</script>

@endsection
