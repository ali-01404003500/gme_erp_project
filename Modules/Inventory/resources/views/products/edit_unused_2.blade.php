@section('title', 'Add Product Catalog')
@section('description', 'Add Product Catalog')
@extends('layout.app')
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

        /* Style for all <a> tags */
        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            /* Background color */
            color: #3d3d3d;
            /* Text color */
            border-radius: 5px 5px 0 0;
            /* 5px radius for top-left and top-right corners */
        }

        /* Style for active tab */
        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            /* Background color */
            color: #ffffff;
            /* Text color */
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
                                        {{ trans('menu.product-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center user-member__title mb-30">
                        <h4 class="text-capitalize">{{ trans('menu.product-create-menu-title') }}</h4>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <div class="mt-40 mb-50">
                                        <form action="{{ route('inv.products.store') }}" enctype="multipart/form-data"
                                            method="POST">
                                            @csrf

                                            <h2 class="mb-3">Product Catalog Information</h2>
                                            <div class="row">


                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="product_type">Product Type:</label>
                                                        <select
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 tom-select"
                                                            id="product_type_id" name="product_type_id">
                                                            <option value="">Select Product</option>
                                                            @foreach ($product_types ?? [] as $types)
                                                            <option value="{{ $types->id }}" @if(old('product_type_id', $product->product_type_id ) == $types->id) selected @endif>{{ $types->name }}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="product_brand">Product Catalog Brand:</label>
                                                        <select
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 tom-select"
                                                            id="product_brand" name="product_brand_id">
                                                            <option value="">Select Brand</option>
                                                            @foreach ($brands ?? [] as $brands)
                                                            <option value="{{ $brands->id }}" @if(old('product_brand_id', $product->product_brand_id ) == $brands->id) selected @endif>{{ $brands->name }}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="name">Name:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="name" name="name" value="{{old("name", $product->name)}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="model">Model:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="model" name="model" value="{{ old('model', $product->model) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="batch_no">Batch No:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="batch_no" name="batch_no" value="{{ old('batch_no', $product->batch_no) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="invoice_no">Purchase Reference/Invoice No</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="invoice_no" name="invoice_no" value="{{ old('invoice_no', $product->invoice_no) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="mrp">MRP:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="mrp" name="mrp" value="{{ old('mrp', $product->mrp) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="unit_type_id">Unit Type:</label>
                                                        <select
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="unit_type_id" name="unit_type_id">
                                                            <!-- Options for Unit Type Dropdown -->
                                                            <option value="">Select Unit</option>
                                                            @foreach ($units ?? [] as $unit)
                                                                <option value="{{ $unit->id }}"
                                                                    @if (old('unit_type_id', $product->unit_type_id) == $unit->id) selected @endif>
                                                                    {{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="product_tag">Product Catalog Tag:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="product_tag" name="product_tag" value="{{ old('product_tag', $product->product_tag) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="landed_price">Landed Price:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="landed_price" name="landed_price" value="{{ old('landed_price', $product->landed_price) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="transportation_per_unit">Transportation[per unit]:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="transportation_per_unit" name="transportation_per_unit" value="{{ old('transportation_per_unit', $product->transportation_per_unit) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="vat_percentage">VAT(%):</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="vat_percentage" name="vat_percentage" step="0.01" value="{{ old('vat_percentage', $product->vat_percentage) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="tax_percentage">TAX(%):</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="tax_percentage" name="tax_percentage" step="0.01" value="{{ old('tax_percentage', $product->tax_percentage) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="misc_amount">Miscellaneous Amount:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="misc_amount" name="misc_amount" value="{{ old('misc_amount', $product->misc_amount) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="total_price">Total Price:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="total_price" name="total_price" readonly value="{{ old('total_price', $product->total_price) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="product_origin">Product Origin:</label>
                                                        <select
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="product_origin" name="product_origin">
                                                            <!-- Options for Product Origin Dropdown -->
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label class="color-dark fs-14 fw-500 align-center"
                                                            for="remainder_quantity">Remainder Quantity:</label>
                                                        <input type="text"
                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                            id="remainder_quantity" name="remainder_quantity" value="{{ old('remainder_quantity', $product->remainder_quantity) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label
                                                            class="color-dark fs-14 fw-500 align-center">Status:</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="active"
                                                                name="status" value="active" @if(old('status', $product->status) == 'active') checked @endif>
                                                            <label class="form-check-label" for="active">Active</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="inactive"
                                                                name="status" value="inactive" @if(old('status', $product->status) == 'inactive') checked @endif>
                                                            <label class="form-check-label"
                                                                for="inactive">Inactive</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dm-tab tab-horizontal">
                                                    <ul class="nav nav-tabs vertical-tabs" role="tablist">

                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="tab-v-1-tab"
                                                                data-bs-toggle="tab" href="#tab-v-1" role="tab"
                                                                aria-selected="true">Basic Settings</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab"
                                                                href="#tab-v-2" role="tab"
                                                                aria-selected="false">Image & File Section</a>
                                                        </li>

                                                    </ul>
                                                    <div class="tab-content">



                                                        <div class="tab-pane fade show active" id="tab-v-1"
                                                            role="tabpanel" aria-labelledby="tab-v-1-tab">
                                                            {{-- <h2>Basic Settings</h2> --}}


                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">Is
                                                                            Serial:</label><br>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="serial_yes" name="is_serial"
                                                                                value="yes" @if(old('is_serial', $product->is_serial) == 'yes') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="serial_yes">Yes</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="serial_no" name="is_serial"
                                                                                value="no" @if(old('is_serial', $product->is_serial) == 'no') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="serial_no">No</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">Is
                                                                            Expire Date:</label><br>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="expire_yes" name="is_expire_date"
                                                                                value="yes" @if(old('is_expire_date', $product->is_expire_date) == 'yes') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="expire_yes">Yes</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="expire_no" name="is_expire_date"
                                                                                value="no" @if(old('is_expire_date', $product->is_expire_date) == 'no') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="expire_no">No</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">Is
                                                                            Warranty:</label><br>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="warranty_yes" name="is_warranty"
                                                                                value="yes" @if(old('is_warranty', $product->is_warranty) == 'yes') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="warranty_yes">Yes</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="warranty_no" name="is_warranty"
                                                                                value="no" @if(old('is_warranty', $product->is_warranty) == 'no') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="warranty_no">No</label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">Warranty
                                                                            Period:</label><br>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="warranty_period"
                                                                                        value="day" @if(old('warranty_period', $product->warranty_period) == 'day') checked @endif>
                                                                                    <label
                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                        for="day">Day</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="warranty_period"
                                                                                        value="month" @if(old('warranty_period', $product->warranty_period) == 'month') checked @endif>
                                                                                    <label
                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                        for="month">Month</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="warranty_period"
                                                                                        value="year" @if(old('warranty_period', $product->warranty_period) == 'year') checked @endif>
                                                                                    <label
                                                                                        class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                        for="year">Year</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="number"
                                                                                    class="form-control ml-2"
                                                                                    id="warranty_period_input"
                                                                                    name="warranty_period_input"
                                                                                    placeholder="Warranty period"
                                                                                    min="1">
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">Force
                                                                            Barcode Scan:</label><br>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="barcode_scan_on"
                                                                                name="force_barcode_scan" value="on" @if(old('force_barcode_scan', $product->force_barcode_scan) == 'on') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="barcode_scan_on">ON</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="barcode_scan_off"
                                                                                name="force_barcode_scan" value="off" @if(old('force_barcode_scan', $product->force_barcode_scan) == 'off') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="barcode_scan_off">OFF</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label
                                                                            class="color-dark fs-14 fw-500 align-center">E-Commerce
                                                                            Product:</label><br>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="ecommerce_yes"
                                                                                name="ecommerce_product" value="yes" @if(old('ecommerce_product', $product->ecommerce_product) == 'yes') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="ecommerce_yes">Yes</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="ecommerce_no" name="ecommerce_product"
                                                                                value="no" @if(old('ecommerce_product',) == 'no') checked @endif>
                                                                            <label
                                                                                class="form-check-label color-dark fs-14 fw-500 align-center"
                                                                                for="ecommerce_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>




                                                            <!-- Similar structure for the rest of the form -->


                                                        </div>
                                                        <div class="tab-pane fade" id="tab-v-2" role="tabpanel"
                                                            aria-labelledby="tab-v-2-tab">
                                                            {{-- <h2>Keyword & Description</h2> --}}

                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-group">
                                                                    <label for="description"
                                                                        class="color-dark fs-14 fw-500 align-center">Keyword
                                                                        & Description</label>
                                                                    <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" id="description" name="description"
                                                                        rows="4"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <h2 class="mt-5">Barcode Section</h2>
                                                            </div>

                                                            <div class="col-md-12 table-responsive">

                                                                <table id="barcodeTable" class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2">
                                                                                <input type="text" class="form-control"
                                                                                    id="barcode" name="barcode">
                                                                            </th>
                                                                            <th>
                                                                                <button type="button"
                                                                                    class="btn btn-xs btn-primary"
                                                                                    onclick="addBarcodeRow()"><i
                                                                                        class="fa fa-plus"></i></button>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Barcode</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                             $OldBarcodes = old("barcodes");
                                                                        @endphp
                                                                        @if(isset($OldBarcodes))
                                                                            @foreach ($OldBarcodes as $key => $barcode)
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $key + 1 }}
                                                                                        <input type="hidden" name="barcodes[]" value="{{ $barcode }}">
                                                                                    </td>
                                                                                    <td>{{ $barcode }}</td>
                                                                                    <td><button
                                                                                            type="button"
                                                                                            class="btn btn-xs btn-danger"
                                                                                            onclick="removeBarcodeRow(this)"><i
                                                                                                class="fa fa-trash"></i></button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>


                                                            {{-- <h2>Image & File Section</h2> --}}

                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-group">
                                                                    <label for="image_upload"
                                                                        class="color-dark fs-14 fw-500 align-center">Upload
                                                                        Image(s):</label>
                                                                    <input type="file"
                                                                        class="file-control form-control-file ih-medium ip-gray radius-xs b-light px-15"
                                                                        id="image_upload" name="image_upload" multiple>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-group">
                                                                    <label for="catalog_file"
                                                                        class="color-dark fs-14 fw-500 align-center">Catalog
                                                                        File:</label>
                                                                    <input type="file"
                                                                        class="file-control form-control-file ih-medium ip-gray radius-xs b-light px-15"
                                                                        id="catalog_file" name="catalog_file">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-group">
                                                                    <label for="price_list_file"
                                                                        class="color-dark fs-14 fw-500 align-center">Price
                                                                        List File:</label>
                                                                    <input type="file"
                                                                        class="file-control form-control-file file-control ih-medium ip-gray radius-xs b-light px-15"
                                                                        id="price_list_file" name="price_list_file">
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
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
             if ( $('#barcode').val()!='')
            $('#barcodeTable tbody').append(html);

            $('#barcode').val('');

        }

        function removeBarcodeRow(button) {
            $(button).closest('tr').remove();
        }
    </script>
@endsection
