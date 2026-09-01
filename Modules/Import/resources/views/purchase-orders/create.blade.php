@section('title', 'Purchase Order Create')
@section('description', 'Purchase Order Create')
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
                                        {{ trans('menu.create-purchase-order-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('purchase.orders.index'))
                            <a href="{{ route('purchase.orders.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-purchase-order-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.orders.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                  
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="po_date">PO Date</label>
                                            <input type="date" name="po_date" class="form-control flatdate"
                                                id="po_date" placeholder="PO Date"
                                                value="{{ old('po_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier_phone">Supplier Phone</label>
                                            <input type="text" name="supplier_phone" class="form-control"
                                                id="supplier_phone" placeholder="Supplier Phone"
                                                value="{{ old('supplier_phone') }}">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_place">Company Place</label>
                                            <input type="text" name="company_place" class="form-control"
                                                id="company_place" placeholder="Company Place"
                                                value="{{ old('company_place') }}">
                                           
                                        </div>

                                    </div>
                                    
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="supplier_address">Supplier Address</label>
                                            <textarea name="supplier_address" id="supplier_address" cols="30" rows="3" class="form-control"
                                                placeholder="Transfer Description">{{ old('supplier_address') }}</textarea>
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
                                                        {{ old('search_by_brand_id') == $brand->id ? 'selected' : '' }}>
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
                                                        @if(old('product_ids'))
                                                          @foreach(old('product_ids') as $i => $pid)
                                                            <tr>
                                                              <td>
                                                                <input type="hidden" name="product_ids[]" class="product_ids" value="{{ $pid }}">
                                                                <input type="text" name="product_model[]" class="form-control product_model"
                                                                       value="{{ old('product_model.'.$i) }}">
                                                              </td>
                                                              <td>
                                                                <input type="text" name="product_description[]" class="form-control product_description"
                                                                       value="{{ old('product_description.'.$i) }}" readonly>
                                                              </td>
                                                              <td>
                                                                <input type="text" name="hs_code[]" class="form-control hs_code"
                                                                       value="{{ old('hs_code.'.$i) }}">
                                                              </td>
                                                              <td>
                                                                <input type="text" name="quantity[]" class="form-control" 
                                                                       value="{{ old('quantity.'.$i) }}">
                                                              </td>
                                                              <td>
                                                                <input type="text" name="price[]" class="form-control" 
                                                                       value="{{ old('price.'.$i) }}">
                                                              </td>
                                                              <td>
                                                                <input type="text" name="amount[]" class="form-control text-center" readonly
                                                                       value="{{ old('amount.'.$i) }}">
                                                              </td>
                                                              <td>
                                                                <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)">
                                                                  <i class="fa fa-times"></i>
                                                                </button>
                                                              </td>
                                                            </tr>
                                                          @endforeach
                                                        @endif
                                                      </tbody>
                                                      
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">
                                                                Total Amount
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="{{ old('total_amount') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;">Transport Title</td>
                                                            <td colspan="3" style="text-align: left;"><input type="text" class="form-control" placeholder="Transport Title" id="transport_title" name="transport_title" value=""></td>
                                                            <td style="text-align: right;">Cost</td>
                                                            <td><input type="text" class="form-control text-center" id="transport_cost" name="transport_cost" value="{{ old('transport_cost',0) }}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Net Amount</td>
                                                            <td><input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount') }}"></td>

                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" cols="10" rows="1" class="form-control"
                                                            placeholder="Remarks">{{ old('remarks') }}</textarea>
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
                                                <option value="LC">LC</option>
                                                <option value="TT">TT</option>
                                                <option value="LC/TT">LC/TT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipping_terms">Shipping Terms</label>
                                            <select name="shipping_terms" id="shipping_terms" class="form-control tom-select">
                                                <option value="By Sea">By Sea</option>
                                                <option value="By Air">By Air</option>
                                                <option value="By Road">By Road</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="po_date">Delivery Date</label>
                                            <input type="date" name="delivery_date" class="form-control"
                                                id="delivery_date" placeholder="Delivery Date"
                                                value="{{ old('delivery_date', date('Y-m-d')) }}">
                                        </div>
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

<script type="text/javascript">
    // === UTILITY: check for duplicate product IDs ===
    function productExists(productId) {
        var exists = false;
        $("#product_info_table tbody .product_ids").each(function() {
            if ($(this).val() == productId) {
                exists = true;
                return false;
            }
        });
        return exists;
    }

    // === CALCULATIONS & ROW REMOVAL ===
    function calculateTotalPrice(row) {
        var qty   = parseFloat(row.find("#quantity").val()) || 0;
        var price = parseFloat(row.find("#price").val())    || 0;
        row.find("#amount").val(qty * price);
    }

    function calculateTotalAmount() {
        var total = 0;
        $("#product_info_table tbody tr").each(function() {
            total += parseFloat($(this).find("#amount").val()) || 0;
        });
        $("#total_amount").val(total);
    }

    function calculateNetAmount() {
        var total     = parseFloat($("#total_amount").val())   || 0;
        var transport = parseFloat($("#transport_cost").val()) || 0;
        $("#net_amount").val(total + transport);
    }

    function removeRow(btn) {
        $(btn).closest("tr").remove();
        calculateTotalAmount();
        calculateNetAmount();
    }

    // === AJAX LOADERS ===
    function getSupplierData() {
        var id = $("#supplier_id").val();
        if (!id) return;
        $.getJSON(`{{ route('purchase.get.supplier-data') }}`, { id: id }, function(data) {
            if (!data.length) return;
            var s = data[0];
            $("#supplier_address").val(s.address);
            $("#supplier_phone").val(s.phone);
            $("#company_place").val(s.company_place);

            $("#product_info_table tbody").empty();

            s.brands.forEach(function(brand) {
                brand.product_catalog.forEach(function(p) {
                    if (productExists(p.id)) {
                        toastr.warning(`Product “${p.name}” already added.`);
                        return;
                    }
                    var row = `<tr>
                        <td>
                            <input type="hidden" name="product_ids[]" class="product_ids" value="${p.id}">
                            <input type="text" name="product_model[]" class="form-control product_model" value="${p.model}">
                        </td>
                        <td>
                            <input type="text" name="product_description[]" class="form-control product_description" value="${p.name}" readonly>
                        </td>
                        <td>
                            <input type="text" name="hs_code[]" class="form-control hs_code" value="${p.product[0]?.hs_code || ''}">
                        </td>
                        <td>
                            <input type="text" name="quantity[]" id="quantity" class="form-control" value="1">
                        </td>
                        <td>
                            <input type="text" name="price[]" id="price" class="form-control" value="${p.mrp || 0}">
                        </td>
                        <td>
                            <input type="text" name="amount[]" id="amount" class="form-control text-center" readonly value="0">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`;
                    $("#product_info_table tbody").append(row);
                    calculateTotalPrice($("#product_info_table tbody tr:last"));
                });
            });

            if (!$("#product_info_table tbody tr").length) {
                addEmptyRow();
            }

            calculateTotalAmount();
            calculateNetAmount();
        });
    }

    function getProductData() {
        var id = $("#search_by_product_id").val();
        if (!id) return;
        $.getJSON(`{{ route('purchase.product-data') }}`, { id: id }, function(data) {
            if (!data.length) return;
            var p = data[0];
            if (productExists(p.id)) {
                toastr.warning(`Product “${p.name}” already added.`);
                return;
            }
            var row = `<tr>
                <td>
                    <input type="hidden" name="product_ids[]" class="product_ids" value="${p.id}">
                    <input type="text" name="product_model[]" class="form-control product_model" value="${p.model}">
                </td>
                <td>
                    <input type="text" name="product_description[]" class="form-control product_description" value="${p.name}" readonly>
                </td>
                <td>
                    <input type="text" name="hs_code[]" class="form-control hs_code" value="${p.product[0]?.hs_code || ''}">
                </td>
                <td>
                    <input type="text" name="quantity[]" id="quantity" class="form-control" value="1">
                </td>
                <td>
                    <input type="text" name="price[]" id="price" class="form-control" value="${p.mrp || 0}">
                </td>
                <td>
                    <input type="text" name="amount[]" id="amount" class="form-control text-center" readonly value="0">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>`;
            $("#product_info_table tbody").append(row);
            calculateTotalPrice($("#product_info_table tbody tr:last"));
            calculateTotalAmount();
            calculateNetAmount();
        });
    }

    function getProductDataBrandWise() {
        var id = $("#search_by_brand_id").val();
        if (!id) return;
        $.getJSON(`{{ route('purchase.product-data.brand.wise') }}`, { id: id }, function(data) {
            $("#product_info_table tbody").empty();
            if (!data.length) {
                addEmptyRow();
            } else {
                data.forEach(function(p) {
                    if (productExists(p.id)) {
                        toastr.warning(`Product “${p.name}” already added.`);
                        return;
                    }
                    var row = `<tr>
                        <td>
                            <input type="hidden" name="product_ids[]" class="product_ids" value="${p.id}">
                            <input type="text" name="product_model[]" class="form-control product_model" value="${p.model}">
                        </td>
                        <td>
                            <input type="text" name="product_description[]" class="form-control product_description" value="${p.name}" readonly>
                        </td>
                        <td>
                            <input type="text" name="hs_code[]" class="form-control hs_code" value="${p.product[0]?.hs_code || ''}">
                        </td>
                        <td>
                            <input type="text" name="quantity[]" id="quantity" class="form-control" value="1">
                        </td>
                        <td>
                            <input type="text" name="price[]" id="price" class="form-control" value="${p.mrp || 0}">
                        </td>
                        <td>
                            <input type="text" name="amount[]" id="amount" class="form-control text-center" readonly value="0">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`;
                    $("#product_info_table tbody").append(row);
                    calculateTotalPrice($("#product_info_table tbody tr:last"));
                });
            }
            calculateTotalAmount();
            calculateNetAmount();
        });
    }

    // === EMPTY ROW TEMPLATE ===
    var rowTemplate;
    function addEmptyRow() {
        if (!rowTemplate) return;
        var newRow = rowTemplate.clone();
        newRow.find("input, select").val("");
        newRow.find("#remove_row").prop("disabled", false);
        $("#product_info_table tbody").append(newRow);
        calculateTotalPrice(newRow);
    }

    // === INITIALIZATION ===
    $(function() {
        // cache the first row as template
        rowTemplate = $("#product_info_table tbody tr:first").clone();
        rowTemplate.find("input, select").val("");
        rowTemplate.find("#remove_row").prop("disabled", false);

        // bind add‑row button
        $("#add_row").on("click", function() {
            addEmptyRow();
            calculateTotalAmount();
            calculateNetAmount();
        });

        // live recalc on qty/price change
        $("#product_info_table tbody")
            .on("keyup change", "#quantity, #price", function() {
                var $r = $(this).closest("tr");
                calculateTotalPrice($r);
                calculateTotalAmount();
                calculateNetAmount();
            });

        // transport cost change
        $("#transport_cost").on("keyup", calculateNetAmount);

        // selects
        $("#supplier_id").on("change", getSupplierData);
        $("#search_by_product_id").on("change", getProductData);
        $("#search_by_brand_id").on("change", getProductDataBrandWise);

        // **only** auto‑load from AJAX if there's **no** old data**
        var hasOld = {{ old('product_ids') ? 'true' : 'false' }};
        if (!hasOld) {
            getSupplierData();
            getProductData();
            getProductDataBrandWise();
        }

        // always do a totals calc
        calculateTotalAmount();
        calculateNetAmount();
    });
</script>



@endsection
