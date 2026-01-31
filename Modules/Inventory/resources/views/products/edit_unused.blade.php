@section('title', 'Edit Product')
@section('description', 'Edit Product')
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
                    <div class="d-flex align-items-center user-member__title mb-30">
                        <h4 class="text-capitalize">{{ trans('menu.edit-product-menu-title') }}</h4>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-10">
                                    <div class="mt-40 mb-50">
                                        <form action="{{ route('inv.products.update', $product->id) }}"
                                            enctype="multipart/form-data"
                                            method="POST">
                                            @csrf
                                            @method('PUT')


                                            <div class="row">
                                                <h2>Product Information</h2>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="product_type">Product Type:</label>
                                                        <select class="form-control tom-select" id="product_type_id" name="product_type_id" >
                                                            <option value="">Select Product</option>
                                                            @foreach ($product_types??[] as $types)
                                                                <option value="{{ $types->id }}" @if(old('product_type_id', $product->product_type_id ) == $types->id) selected @endif>{{ $types->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="product_brand">Product Brand:</label>
                                                        <select class="form-control tom-select" id="product_brand"
                                                            name="product_brand_id">
                                                            <option value="">Select Brand</option>
                                                            @foreach ($brands??[] as $brands)
                                                                <option value="{{ $brands->id }}" @if(old('product_brand_id', $product->product_brand_id ) == $brands->id) selected @endif>{{ $brands->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="name">Name:</label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{old("name", $product->name)}}">
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="model">Model:</label>
                                                        <input type="text" class="form-control" id="model"
                                                            name="model" value="{{old("model", $product->model)}}">
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="mrp">MRP:</label>
                                                        <input type="text" class="form-control" id="mrp"
                                                            name="mrp" value="{{old("mrp", $product->mrp)}}">
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="unit_type_id">Unit Type:</label>
                                                        <select class="form-control" id="unit_type_id" name="unit_type_id">
                                                            <!-- Options for Unit Type Dropdown -->
                                                            <option value="">Select Unit</option>
                                                            @foreach ($units??[] as $unit)
                                                                <option value="{{ $unit->id }}" @if(old('unit_type_id', $product->unit_type_id ) == $unit->id) selected @endif>{{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="product_tag">Product Tag:</label>
                                                        <input type="text" class="form-control" id="product_tag"
                                                            name="product_tag" value="{{old("product_tag", $product->product_tag)}}">
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="product_origin">Product Origin:</label>
                                                        <select class="form-control" id="product_origin"
                                                            name="product_origin" value="{{old("product_origin", $product->product_origin)}}">
                                                            <!-- Options for Product Origin Dropdown -->
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="remainder_quantity">Remainder Quantity:</label>
                                                        <input type="text" class="form-control" id="remainder_quantity"
                                                            name="remainder_quantity" value="{{old("remainder_quantity", $product->remainder_quantity)}}">
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label>Status:</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="active"
                                                                name="status" value="{{old("status", $product->status)}}" value="active">
                                                            <label class="form-check-label" for="active">Active</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="inactive"
                                                                name="status" value="{{old("status", $product->status)}}" value="inactive">
                                                            <label class="form-check-label"
                                                                for="inactive">Inactive</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Basic Settings</h2>
                                                <div class="form-group">
                                                    <label>Is Serial:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="serial_yes"
                                                        @if (old("is_serial", $product->is_serial) == "yes") checked @endif
                                                            name="is_serial" value="yes">
                                                        <label class="form-check-label" for="serial_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="serial_no" @if (old("is_serial", $product->is_serial) == "no") checked @endif                                                            
                                                            name="is_serial" value="no">
                                                        <label class="form-check-label" for="serial_no">No</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Is Expire Date:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="expire_yes"
                                                            @if (old("is_expire_date", $product->is_expire_date) == "yes") checked @endif
                                                            name="is_expire_date" value="yes">
                                                        <label class="form-check-label" for="expire_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="expire_no"
                                                            @if (old("is_expire_date", $product->is_expire_date) == "no") checked @endif
                                                            name="is_expire_date" value="no">
                                                        <label class="form-check-label" for="expire_no">No</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Is Warranty:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="warranty_yes"
                                                            @if (old("is_warranty", $product->is_warranty) == "yes") checked @endif
                                                            name="is_warranty" value="yes">
                                                        <label class="form-check-label" for="warranty_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="warranty_no"
                                                            @if (old("is_warranty", $product->is_warranty) == "no") checked @endif
                                                            name="is_warranty" value="no">
                                                        <label class="form-check-label" for="warranty_no">No</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Warranty Period:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="day"
                                                            @if (old("warranty_period", $product->warranty_period) == "day") checked @endif
                                                            name="warranty_period" value="1" value="day">
                                                        <label class="form-check-label" for="day">Day</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="month"
                                                            @if (old("warranty_period", $product->warranty_period) == "month") checked @endif
                                                            name="warranty_period" value="1" value="month">
                                                        <label class="form-check-label" for="month">Month</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="year"
                                                            @if (old("warranty_period", $product->warranty_period) == "year") checked @endif
                                                            name="warranty_period" value="1" value="year">
                                                        <label class="form-check-label" for="year">Year</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Force Barcode Scan:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            id="barcode_scan_on" name="force_barcode_scan" 
                                                            @if (old("force_barcode_scan", $product->force_barcode_scan) == "on") checked @endif
                                                            value="on">
                                                        <label class="form-check-label" for="barcode_scan_on">ON</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            id="barcode_scan_off" name="force_barcode_scan"
                                                            @if (old("force_barcode_scan", $product->force_barcode_scan) == "off") checked @endif
                                                            value="off">
                                                        <label class="form-check-label" for="barcode_scan_off">OFF</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>E-Commerce Product:</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="ecommerce_yes"
                                                            name="ecommerce_product" @if (old("ecommerce_product", $product->ecommerce_product) == "yes") checked @endif
                                                              value="yes">
                                                        <label class="form-check-label" for="ecommerce_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="ecommerce_no"
                                                            name="ecommerce_product" @if (old("ecommerce_product", $product->ecommerce_product) == "no") checked @endif value="no">
                                                        <label class="form-check-label" for="ecommerce_no">No</label>
                                                    </div>
                                                </div>
                                                <!-- Similar structure for the rest of the form -->

                                                <h2>Keyword & Description</h2>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="description">Description:</label>
                                                        <textarea class="form-control" id="description" name="description" rows="4">{{old("description", $product->description)}}</textarea>
                                                    </div>

                                                </div>
                                                <h2>Barcode Section</h2>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="barcode">Barcode:</label>
                                                        <input type="text" class="form-control" id="barcode"
                                                            name="barcode" value="{{old("barcode", $product->barcode)}}">
                                                    </div>
                                                </div>
                                                <h2>Image & File Section</h2>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="image_upload">Upload Image(s):</label>
                                                        <input type="file" class="file-control form-control-file"
                                                            id="image_upload" name="image_upload" multiple>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="catalog_file">Catalog File:</label>
                                                        <input type="file" class="file-control form-control-file"
                                                            id="catalog_file" name="catalog_file" >
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="price_list_file">Price List File:</label>
                                                        <input type="file"
                                                            class="file-control form-control-file file-control"
                                                            id="price_list_file" name="price_list_file">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row text-end">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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

@section('')
