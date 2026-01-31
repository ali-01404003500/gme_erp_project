@extends('layout.app')
@section('title', 'Product Settings')
@section('description', 'Product Settings')
@section('page-head')
    <style>
        .card-body {
            margin-right: 7vh;
            margin-left: 7vh;
        }

        .row {
            padding-right: 1vh;
            padding-left: 1vh;
        }
    </style>
@endsection
@section('content')

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.edit-product-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="align-items-center user-member__title mb-30">
                        <h4 class="text-capitalize">{{ trans('menu.edit-product-menu-title') }}</h4>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <div class="mt-40 mb-50">
                                        <form action="{{ route('inv.products.update', $product->id) }}" enctype="multipart/form-data"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <h2 class="mb-3">Product Unit Information</h2>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="dm-tab tab-horizontal">
                                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab"
                                                                    href="{{ route('inv.products.edit', $product->id) }}#product_unit_sales" role="tab">
                                                                    Basic & Sales Discount Settings
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab"
                                                                    href="{{ route('inv.products.edit', $product->id) }}#product_settings_sales" role="tab">
                                                                    Product Settings for Sales
                                                                </a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a href="{{ route('inv.products.edit', $product->id) }}#product_settings_purchase" class="nav-link"
                                                                    data-bs-toggle="tab">
                                                                    Product Settings For Purchase
                                                                </a>
                                                            </li>
                                                        </ul>


                                                        <div class="tab-content">
                                                            <div class="tab-pane fade show active" id="product_unit_sales"
                                                                role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="product_catalog_id">Product
                                                                                Name:</label>
                                                                            <select class="form-control tom-select"
                                                                                id="product_catalog_id"
                                                                                name="product_catalog_id"
                                                                                @if (request()->has('product_catalog_id')) readonly @endif>
                                                                                <option value="">Select Product Catalog</option>
                                                                                @foreach ($product_catalogs ?? [] as $product_catalog)
                                                                                    <option value="{{ $product_catalog->id }}"
                                                                                        @if (old('product_catalog_id',$product->product_catalog_id?? request()->input('product_catalog_id')) == $product_catalog->id) selected @endif
                                                                                        data-mrp={{ $product_catalog->mrp }}>
                                                                                        {{ $product_catalog->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="dollar_price">Dollar Price:</label>
                                                                            <input type="text" id="dollar_price" name="dollar_price"
                                                                                class="form-control numberOnly" value="{{ old('dollar_price',numberFormat($product->dollar_price)) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="hs_code">HS Code:</label>
                                                                            <input type="text" id="hs_code" name="hs_code"
                                                                                class="form-control" value="{{ old('hs_code',$product->hs_code) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="last_cost_price">Last Cost Price:</label>
                                                                            <input type="number" step="0.01"
                                                                                class="form-control" id="last_cost_price"
                                                                                name="last_cost_price"
                                                                                value="{{ old('last_cost_price',$product->last_cost_price) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="reminder_quantity">Reminder
                                                                                Quantity:</label>
                                                                            <input type="text" class="form-control numberOnly"
                                                                                id="reminder_quantity"
                                                                                name="remainder_quantity"
                                                                                value="{{ old('remainder_quantity',$product->remainder_quantity) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="product_status">Product
                                                                                Settings:</label>
                                                                            <div class="input-group">
                                                                                {{-- Product Status Radio Group --}}
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="product_status"
                                                                                        id="product_status_active"
                                                                                        value="active"
                                                                                        @checked(old('product_status', $product->product_status) === 'active')>
                                                                                    <label class="form-check-label" for="product_status_active">
                                                                                        Active
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="product_status"
                                                                                        id="product_status_inactive"
                                                                                        value="inactive"
                                                                                        @if(old('product_status', $product->product_status) == 'inactive') checked @endif>
                                                                                    <label class="form-check-label" for="product_status_inactive">
                                                                                        Inactive
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <h3>Discount Information</h3>
                                                                    </div>
                                                                    {{-- @dd($product) --}}

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label>Discount Type:</label>
                                                                            <div class="form-group" id="discount_type">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="discount_type"
                                                                                        id="discount_type_NA"
                                                                                        value="NA" @if($product->discount_type == 'NA') checked  @endif>
                                                                                    <label class="form-check-label" for="discount_type_NA">
                                                                                        N/A
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="discount_type"
                                                                                        id="discount_type_Fixed"
                                                                                        value="Fixed" @if($product->discount_type == 'Fixed') checked  @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="discount_type_Fixed">
                                                                                        Fixed
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="discount_type"
                                                                                        id="discount_type_Percentage"
                                                                                        value="Percentage" @if($product->discount_type == 'Percentage') checked  @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="discount_type_Percentage">
                                                                                        Percentage
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        <div class="form-group me-6" id="discount_fixed">
                                                                            <div class="input-group mb-3">
                                                                                <label for="min_discount" class="input-group-text"> Min</label>
                                                                                <input type="number" class="form-control"
                                                                                    id="min_discount"
                                                                                    name="min_discount"
                                                                                    value="{{ old('min_discount',$product->min_discount) }}">
                                                                                <label for="max_discount"  class="input-group-text" > Max</label>
                                                                                <input type="number" class="form-control"
                                                                                    id="max_discount"
                                                                                    name="max_discount"
                                                                                    value="{{ old('max_discount',$product->max_discount) }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label>Product Tag:</label>
                                                                            <select class="form-control tom-select"
                                                                                name="product_tag_id" required>
                                                                                <option value="">Select Product Tag
                                                                                </option>
                                                                                @foreach ($tags as $tag)
                                                                                    <option value="{{ $tag->id }}" @if(old('product_tag_id', $product->product_tag_id) == $tag->id) selected @endif>
                                                                                        {{ $tag->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" id="active_tab" name="active_tab" value="">

                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="product_settings_sales"
                                                                role="tabpanel">

                                                                <div class="row">

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="max_sales_quantity">Maximus Sales
                                                                                Quantity:</label>
                                                                            <input type="number"
                                                                                class="form-control numberOnly"
                                                                                id="max_sales_quantity"
                                                                                name="max_sales_quantity"
                                                                                value="{{ old('max_sales_quantity',$product->max_sales_quantity) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="total_sales_qty">Total Sales
                                                                                Qty:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="total_sales_qty"
                                                                                name="total_sales_qty"
                                                                                value="{{ old('total_sales_qty',$product->total_sales_qty) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="applied_type">Applied Type:</label>
                                                                            <select name="applied_type" id="applied_type" class="form-control">
                                                                                <option value="">Select</option>
                                                                                <option value="once_in_time" @if(old("applied_type",$product->applied_type) == "once_in_time") selected @endif>Once in time</option>
                                                                                <option value="daily" @if(old("applied_type",$product->applied_type) == "daily") selected @endif>Daily</option>
                                                                                <option value="weekly" @if(old("applied_type",$product->applied_type) == "weekly") selected @endif>Weekly</option>
                                                                                <option value="monthly" @if(old("applied_type",$product->applied_type) == "monthly") selected @endif>Monthly</option>
                                                                                <option value="yearly" @if(old("applied_type",$product->applied_type) == "yearly") selected @endif>Yearly</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="inv_no">Inv No:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="inv_no" name="inv_no"
                                                                                value="{{ old('inv_no',$product->inv_no) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="status">Status:</label>
                                                                            <div class="status-radio">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status"
                                                                                        id="running" value="running"
                                                                                        @if(old('status',$product->status) == 'running') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="running">Running</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status"
                                                                                        id="stop" value="stop" @if(old('status',$product->status) == 'stop') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="stop">Stop</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="stock_info">Sales Stock Info:</label>
                                                                            <div class="stock-radio">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="stock_info"
                                                                                        id="available" value="available"
                                                                                        @if(old('stock_info',$product->stock_info) == 'available') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="available">Available</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="stock_info"
                                                                                        id="stock_out" value="stock_out" @if(old('stock_info',$product->stock_info) == 'stock_out') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="stock_out">Stock Out</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <div class="tab-pane fade" id="product_settings_purchase"
                                                                role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="max_purchase_quantity">Maximus
                                                                                Purchase Quantity:</label>
                                                                            <input type="number"
                                                                                class="form-control numberOnly"
                                                                                id="max_purchase_quantity"
                                                                                name="max_purchase_quantity"
                                                                                value="{{ old('max_purchase_quantity',$product->max_purchase_quantity) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="total_purchase_qty">Total Purchase
                                                                                Qty:</label>
                                                                            <input type="number"
                                                                                class="form-control numberOnly"
                                                                                id="total_purchase_qty"
                                                                                name="total_purchase_qty"
                                                                                value="{{ old('total_purchase_qty',$product->total_purchase_qty) }}">
                                                                        </div>
                                                                    </div>


                                                                    {{-- <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="dollar_price">Dollar Price:</label>
                                                                            <input type="text" id="dollar_price" name="dollar_price"
                                                                                class="form-control numberOnly" value="{{ old('dollar_price', $product->dollar_price) }}">
                                                                        </div>
                                                                    </div> --}}
                                                                    {{-- <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="hs_code">HS Code:</label>
                                                                            <input type="text" id="hs_code" name="hs_code"
                                                                                class="form-control" value="{{ old('hs_code', $product->hs_code) }}">
                                                                        </div>
                                                                    </div> --}}

                                                                    <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="last_purchase_price">Last Purchase
                                                                                Price:</label>
                                                                            <input type="number"
                                                                                class="form-control numberOnly"
                                                                                id="last_purchase_price"
                                                                                name="last_purchase_price"
                                                                                value="{{ old('last_purchase_price',$product->last_purchase_price) }}">
                                                                        </div>
                                                                    </div>

                                                                    {{-- <div class="col-md-4 mb-2">
                                                                        <div class="form-group">
                                                                            <label for="stock_info">Stock Info:</label>
                                                                            <div class="form-group">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="stock_info"
                                                                                        id="available" value="available" @if(old('stock_info',$product->stock_info) == 'available') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="available">Available</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="stock_info"
                                                                                        id="stock_out" value="stock_out" @if(old('stock_info',$product->stock_info) == 'stock_out') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="stock_out">Stock Out</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> --}}
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <div
                                                class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <a href="{{ route('inv.product-catalogs.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                                <button type="submit"
                                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });


            $("#product_catalog_id").change(function() {
                // console.log($(this).find('option:selected').data("mrp"));

                $("#mrp").val($(this).find('option:selected').data("mrp"));
            });


            $(".tom-select[readonly]").each(function() {
                this.tomselect.lock();
            });

            if('Fixed' == $('#discount_type').find('input:checked').val()){
                $('#discount_fixed').show();
            }else{
                $('#discount_fixed').hide();
            }
            $('#discount_type').on("change", function() {
                console.log($(this).find('input:checked').val());
                if('Fixed' == $(this).find('input:checked').val()){
                    $('#discount_fixed').show();
                }else{
                    $('#discount_fixed').hide();
                }
            });

        });

        function calculateTotalPrice() {
            var mrp = $("#mrp").val() ? $("#mrp").val() : 0;
            var landed_price = $("#landed_price").val() ? $("#landed_price").val() : 0;
            var transportation_cost = $("#transportation_cost").val() ? $("#transportation_cost").val() : 0;
            var vat = $("#vat").val() ? $("#vat").val() : 0; //percebtage
            var tax = $("#tax").val() ? $("#tax").val() : 0; //percentage

            var miscellaneousAmount = $("#misc").val() ? $("#misc").val() : 0; //percentage

            var total = parseFloat(mrp) + parseFloat(landed_price) + parseFloat(transportation_cost) + (parseFloat(
                mrp) * parseFloat(vat) / 100) + (parseFloat(mrp) * parseFloat(tax) / 100) + parseFloat(
                miscellaneousAmount);
            $("#total_price").val(total);
        }

        $("#mrp, #landed_price, #transportation_cost, #vat, #tax, #misc").on("keyup",
            function() {
                calculateTotalPrice();
            });


        function addBarcodeRow() {
            var sl = $('#barcodeTable tbody tr').length + 1;
            var barcode = $('#barcode').val();

            const html = `
            <tr>
                <td>${sl}
                    <input type="hidden" name="barcodes[]" value="${barcode}">
                    </td>
                <td>${barcode}</td>
                <td>
                    <button type="button"
                        class="btn btn-xs btn-danger"
                        onclick="removeBarcodeRow(this)"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            `;
            if ($('#barcode').val() != '')
                $('#barcodeTable tbody').append(html);

            $('#barcode').val('');

        }

        function removeBarcodeRow(button) {
            $(button).closest('tr').remove();
        }

        // Activate tab based on hash
        $(document).ready(function() {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');
            // Update active_tab hidden input on tab click
            $('ul.nav-tabs a').on('shown.bs.tab', function (e) {
                $('#active_tab').val(e.target.hash);
            });
        });
    </script>
@endsection
