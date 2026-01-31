@section('title', 'Add Product Catalog')
@section('description', 'Add Product Catalog')
@extends('layout.app')
@section('page-head')
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
                                        {{ trans('menu.product-catalog-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('inv.product-catalogs.index'))
                            <a href="{{ route('inv.product-catalogs.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center user-member__title mb-30">
                        <h4 class="text-capitalize">{{ trans('menu.product-catalog-create-menu-title') }}</h4>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <div class="mt-40 mb-50">
                                        <form action="{{ route('inv.product-catalogs.store') }}"
                                            enctype="multipart/form-data" method="POST">
                                            @csrf



                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dm-tab tab-horizontal">
                                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">

                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="product-information-tab"
                                                                    data-bs-toggle="tab" href="#tab-product-information"
                                                                    role="tab" aria-selected="true">Product
                                                                    Information</a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a class="nav-link" id="image-files-tab"
                                                                    data-bs-toggle="tab" href="#tab-image-files"
                                                                    role="tab" aria-selected="true">Image &amp;
                                                                    Files</a>
                                                            </li>

                                                        </ul>

                                                        <div class="tab-content">

                                                            <div class="tab-pane fade active show"
                                                                id="tab-product-information" role="tabpanel"
                                                                aria-labelledby="product-information-tab">
                                                                <div class="row">
                                                                    <div class="col-md-12 my-4">
                                                                        <h4>Product Catalog</h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="product_type"
                                                                                class="col-form-label">Product Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control" id="product_type"
                                                                                name="product_type_id">
                                                                                <option value="">Select Product Type
                                                                                </option>
                                                                                @foreach ($productTypes as $productType)
                                                                                    <option value="{{ $productType->id }}"
                                                                                        @if (old('product_type_id') == $productType->id) selected @endif>
                                                                                        {{ $productType->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="product_tag"
                                                                                class="col-form-label">Product Tag</label>
                                                                            <select class="form-control" id="product_tag"
                                                                                name="product_tag_id">
                                                                                <option value="">Select Product
                                                                                    Category</option>
                                                                                @foreach ($tags as $tag)
                                                                                    <option value="{{ $tag->id }}"
                                                                                        @if (old('product_tag_id') == $tag->id) selected @endif>
                                                                                        {{ $tag->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="product_brand"
                                                                                class="col-form-label">Product Brand</label>
                                                                            <select class="form-control" id="product_brand"
                                                                                name="product_brand_id">
                                                                                <option value="">Select Product Brand
                                                                                </option>
                                                                                @foreach ($brands as $brand)
                                                                                    <option value="{{ $brand->id }}"
                                                                                        @if (old('product_brand_id') == $brand->id) selected @endif>
                                                                                        {{ $brand->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="product_code"
                                                                                class="col-form-label">Product Code</label>
                                                                            <input type="text" class="form-control"
                                                                                id="product_code" value="{{ old('product_code') }}"
                                                                                name="product_code" placeholder="Product Code">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-8">
                                                                        <div class="form-group mb-3">
                                                                            <label for="name"
                                                                                class="col-form-label">Name <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                id="name" value="{{ old('name') }}"
                                                                                name="name" placeholder="Product Name"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="model"
                                                                                class="col-form-label">Model</label>
                                                                            <input type="text" class="form-control"
                                                                                id="model" name="model"
                                                                                value="{{ old('model') }}"
                                                                                placeholder="Product Model">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="mrp"
                                                                                class="col-form-label">MRP <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text"
                                                                                class="form-control numberOnly num-words"
                                                                                id="mrp" name="mrp"
                                                                                value="{{ old('mrp') }}"
                                                                                placeholder="MRP">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="unit_type"
                                                                                class="col-form-label">Unit Type</label>
                                                                            <select class="form-control" id="unit_type"
                                                                                name="unit_type_id">
                                                                                <option value="">Select Unit Type
                                                                                </option>
                                                                                @foreach ($units as $unit)
                                                                                    <option value="{{ $unit->id }}"
                                                                                        @if (old('unit_type_id') == $unit->id) selected @endif>
                                                                                        {{ $unit->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="product_origin"
                                                                                class="col-form-label">Product
                                                                                Origin</label>
                                                                            <select class="form-control tom-select"
                                                                                id="product_origin" name="product_origin">
                                                                                <option value="">Select Product Origin</option>
                                                                                @foreach (cuntriesNames() as $productOrigin)
                                                                                    <option value="{{ $productOrigin }}"
                                                                                        @if (old('product_origin') == $productOrigin) selected @endif>
                                                                                        {{ $productOrigin }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label class="col-form-label">Status</label>
                                                                            <div class="form-group">
                                                                                <div
                                                                                    class="form-check form-check-inline mr-5">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status"
                                                                                        id="active" value="active"
                                                                                        @if (old('status') == 'active' || old('status') == null) checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="active">Active
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status"
                                                                                        id="inactive" value="inactive"
                                                                                        @if (old('status') == 'inactive') checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="inactive">Inactive
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-12 mb-2 mt-4">
                                                                        <h4>Basic Settings</h4>
                                                                    </div>
                                                                    <div class="card mt-2">

                                                                        {{-- <div class="card-body p-0">
                                                                          <div class="table4 mb-30"> --}}
                                                                        <div class="table-responsive">
                                                                            <table class="table mb-0">
                                                                                <tbody>

                                                                                    <tr>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">Is
                                                                                                    Serial:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="serial_yes"
                                                                                                        name="is_serial" value="yes"
                                                                                                        @if (old('is_serial') == 'yes' || old('is_serial') == null) checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="serial_yes">Yes</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="serial_no"
                                                                                                        name="is_serial" value="no"
                                                                                                        @if (old('is_serial') == 'no') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="serial_no">No</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">Is
                                                                                                    Expire Date:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="expire_yes"
                                                                                                        name="is_expire_date" value="yes"
                                                                                                        @if (old('is_expire_date') == 'yes') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="expire_yes">Yes</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="expire_no"
                                                                                                        name="is_expire_date" value="no"
                                                                                                        @if (old('is_expire_date') == 'no' || old('is_serial') == null) checked @endif>
                                                                                                        
                                                                                                        
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="expire_no">No</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>

                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">Is
                                                                                                    Warranty:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="warranty_yes"
                                                                                                        name="is_warranty" value="yes"
                                                                                                        @if (old('is_warranty') == 'yes') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="warranty_yes">Yes</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="warranty_no"
                                                                                                        name="is_warranty" value="no"
                                                                                                        @if (old('is_warranty') == 'no' || old('is_serial') == null) checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="warranty_no">No</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">Warranty
                                                                                                    Period:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="d-inline-block mr-2">
                                                                                                    <input type="number"
                                                                                                        class="form-control ml-2"
                                                                                                        id="warranty_period_input"
                                                                                                        name="warranty_period_input"
                                                                                                        placeholder="Warranty period"
                                                                                                        value="{{ old('warranty_period_input') }}">
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" name="warranty_period"
                                                                                                        value="day"
                                                                                                        @if (old('warranty_period') == 'day' || old('warranty_period') == null) checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="day">Day</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" name="warranty_period"
                                                                                                        value="month"
                                                                                                        @if (old('warranty_period') == 'month') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="month">Month</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" name="warranty_period"
                                                                                                        value="year"
                                                                                                        @if (old('warranty_period') == 'year') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="year">Year</label>
                                                                                                </div>
                                                                                                
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>


                                                                                    <tr>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">Force
                                                                                                    Barcode Scan:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="barcode_scan_on"
                                                                                                        name="force_barcode_scan"
                                                                                                        value="on"
                                                                                                        @if (old('force_barcode_scan') == 'on') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="barcode_scan_on">ON</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="barcode_scan_off"
                                                                                                        name="force_barcode_scan"
                                                                                                        value="off"
                                                                                                        @if (old('force_barcode_scan') == 'off' || old('force_barcode_scan') == null) checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="barcode_scan_off">OFF</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <label
                                                                                                    class="color-dark fs-14 fw-500 align-center">E-Commerce
                                                                                                    Product:</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="userDatatable-content">
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="ecommerce_yes"
                                                                                                        name="ecommerce_product"
                                                                                                        value="yes"
                                                                                                        @if (old('ecommerce_product') == 'yes' || old('ecommerce_product') == null) checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="ecommerce_yes">Yes</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-inline">
                                                                                                    <input class="form-check-input"
                                                                                                        type="radio" id="ecommerce_no"
                                                                                                        name="ecommerce_product"
                                                                                                        value="no"
                                                                                                        @if (old('ecommerce_product') == 'no') checked @endif>
                                                                                                    <label
                                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                                        for="ecommerce_no">No</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        {{-- </div>
                                                                        </div> --}}
                                                                    </div>

                                                                    

                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group formElement-editor">
                                                                                <div class="col-md-12 mb-2 mt-4">
                                                                                    <h4>Keyword
                                                                                        & Description</h4>
                                                                                </div>
                                                                            <textarea class="form-control trumbowyg" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab-image-files"
                                                                role="tabpanel" aria-labelledby="image-files-tab">

                                                                <div class="col-md-6 mb-2">
                                                                    <div class="form-group">
                                                                        <label for="profile_image_upload"
                                                                            class="color-dark fs-14 fw-500 align-center">Profile Image Upload:</label>
                                                                            <x-file-uploader name="profile_image_upload"/>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 mb-2">
                                                                    <div class="form-group">
                                                                        <label for="image_uploads"
                                                                            class="color-dark fs-14 fw-500 align-center">Catalog
                                                                            File:</label>
                                                                        <x-file-uploader multiple name="image_uploads"/>
                                                                    </div>
                                                                </div>

                                                              

                                                                  <div class="col-md-6 mb-2">
                                                                    <div class="form-group">
                                                                        
                                                                        <label for="price_list_file"
                                                                            class="color-dark fs-14 fw-500 align-center">Price
                                                                            List File:</label>
                                                                        <x-file-uploader name="price_list_file"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                </div>
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
    </div>
@endsection

@section('page_scripts')

    <script>
        function calculateTotalPrice() {
            var mrp = $("#mrp").val() ? $("#mrp").val() : 0;
            var landed_price = $("#landed_price").val() ? $("#landed_price").val() : 0;
            var transportation_per_unit = $("#transportation_per_unit").val() ? $("#transportation_per_unit").val() : 0;
            var vat = $("#vat_percentage").val() ? $("#vat_percentage").val() : 0; //percebtage
            var tax = $("#tax_percentage").val() ? $("#tax_percentage").val() : 0; //percentage

            var miscellaneousAmount = $("#misc_amount").val() ? $("#misc_amount").val() : 0; //percentage

            var total = parseFloat(mrp) + parseFloat(landed_price) + parseFloat(transportation_per_unit) + (parseFloat(
                mrp) * parseFloat(vat) / 100) + (parseFloat(mrp) * parseFloat(tax) / 100) + parseFloat(
                miscellaneousAmount);
            $("#total_price").val(total);


        }

        $("#mrp, #landed_price, #transportation_per_unit,#vat_percentage, #tax_percentage, #misc_amount").on("keyup",
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

        $(document).ready(function() {
    // When is_expire_date is changed
        $('input[name="is_expire_date"]').on('change', function() {
            if ($(this).val() === 'yes') {
                // If expire date is yes, set is_serial to no
                $('input[name="is_serial"][value="no"]').prop('checked', true);
            }
            if($(this).val() === 'no') {
                
                $('input[name="is_serial"][value="yes"]').prop('checked', true);
            }

        });

        // When is_serial is changed
        $('input[name="is_serial"]').on('change', function() {
            if ($(this).val() === 'yes') {
                // If serial is yes, set is_expire_date to no
                $('input[name="is_expire_date"][value="no"]').prop('checked', true);
            }
            if($(this).val() === 'no') {
                
                $('input[name="is_expire_date"][value="yes"]').prop('checked', true);
            }
        });
    });
    </script>
@endsection
