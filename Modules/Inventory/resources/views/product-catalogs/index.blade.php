@section('title', 'Product Catalog List')
@section('description', 'Product Catalog List')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
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
                                        {{ trans('menu.product-catalog-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('inv.product-catalogs.create'))
                                    <a class="btn btn-xs btn-primary btn-sm"
                                        href="{{ route('inv.product-catalogs.create') }}">
                                        Add New
                                    </a>
                                @endif
                                <button type="button" class="btn btn-xs btn-primary btn-sm mx-2" data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    Import
                                </button>
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-catalog-list-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="text-center">
                                                    <select name="name" id="name" class="form-control"
                                                        data-placeholder="Select Product Name">
                                                        <option value=""></option> 
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <select name="model" id="model" class="form-control "
                                                        data-placeholder="Select Model Name">
                                                        <option value=""></option> 
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <select name="product_brand_id" id="brand"
                                                        class="form-control tom-select"
                                                        data-placeholder="Select Brand Name">
                                                        <option value="">Select Brand</option>
                                                        @foreach ($productBrands as $productBrand)
                                                            <option
                                                                {{ request('product_brand_id') == $productBrand->id ? 'selected' : '' }}
                                                                value="{{ $productBrand->id }}">
                                                                {{ $productBrand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                {{-- <td class="text-center">
                                                    <input type="text" class="form-control" name="present_address"
                                                        value="{{ request('present_address') }}" autocomplete="off"
                                                        placeholder="Search by Address">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="personal_mobile"
                                                        value="{{ request('personal_mobile') }}" autocomplete="off"
                                                        placeholder="Search by Mobile">
                                                </td> --}}
                                                <td colspan="5" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $productCatalogs])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center d-none">Product Code</th>
                                        <th class="text-center">Model</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Mrp</th> 
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($productCatalogs as $key => $productCatalog)
                                        <tr>
                                            <td class="text-center">{{ ($productCatalogs->currentPage() - 1) * $productCatalogs->perPage() + $loop->iteration }}</td>
                                            <td class="text-left">
                                                <a href="{{ route('inv.product-catalogs.show', $productCatalog->id) }}">{{ $productCatalog->withoutModelSuffix()->name }}</a>
                                            </td>
                                            <td class="text-center  d-none">{{ $productCatalog->product_code }}</td>
                                            <td class="text-left">{{ $productCatalog->model }}</td>
                                            <td class="text-left">{{ optional($productCatalog->brand)->name }}</td>
                                            <td class="text-end">{{ $productCatalog->mrp }}</td> 
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">

                                                    @if (hasPermission('inv.product-catalogs.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('inv.product-catalogs.edit', $productCatalog->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('inv.product-catalogs.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('inv.product-catalogs.show', $productCatalog->id) }}"><i
                                                                class="fas
                                                            fa-eye"></i></a>
                                                    @endif

                                                    @if (hasPermission('inv.product-catalogs.settings'))
                                                        <a class="btn btn-outline-secondary"
                                                            href="{{ route('inv.products.create', ['product_catalog_id' => $productCatalog->id]) }}"><i
                                                                class="fas fa-cog"></i></a>
                                                    @endif


                                                    @if (hasPermission('inv.product-catalogs.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.product-catalogs.destroy', $productCatalog->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Add Product Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('inv.product-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control"
                                                placeholder=" Code *" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control"
                                                placeholder=" Name *" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Status</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="radio">
                                                <label>
                                                    <input name="status" type="radio" value="1" class="ace"
                                                        checked>
                                                    <span class="lbl"> Active</span>
                                                </label>
                                                <label>
                                                    <input name="status" type="radio" value="0" class="ace">
                                                    <span class="lbl"> In active</span>
                                                </label>
                                            </div>

                                            @error('status')
                                                <span class="text-danger">
                                                    {{ message }}
                                                </span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="code" class="col-sm-12 col-form-label">Code</label>
                            <div class="col-sm-12">
                                <input name="code" id="code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="importForm" action="{{ route('inv.product-catalogs.import') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Product Catalogs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Upload CSV File</label>
                            <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv">
                            <div class="text-danger" id="file_error"></div>
                        </div>
                        <a href="{{ route('inv.product-catalogs.download.sample.csv') }}" class="btn btn-info">Download
                            Sample CSV</a>
                        <p class="mt-2"><b>Note:</b> Ensure the import process on the server is optimized for large
                            files.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                console.log($(this).data('name'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>

    <script>
        // Client-side validation for the import form
        $(document).ready(function() {
            $('#importForm').submit(function(e) {
                // Reset error message
                $('#file_error').text('');

                var fileInput = $('#csv_file')[0];
                if (fileInput.files.length === 0) {
                    $('#file_error').text('Please upload a CSV file.');
                    e.preventDefault(); // Prevent form submission
                } else {
                    var fileName = fileInput.files[0].name;
                    var fileExtension = fileName.slice((fileName.lastIndexOf(".") - 1 >>> 0) + 2);
                    if (fileExtension !== "csv") {
                        $('#file_error').text('Please upload a CSV file.');
                        e.preventDefault(); // Prevent form submission
                    }
                }
            });
            const productSelect = new TomSelect("#name", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('inv.product-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.label, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('name'))
                productSelect.addOption({
                    id: "{{ request('name') }}",
                    text: "{{ request('name') }}"
                });
                productSelect.setValue("{{ request('name') }}");
            @endif


            const arrangebySelect = new TomSelect("#model", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('inv.product-autocomplete.model') }}",
                        type: "GET", 
                        data: { search: query },
                        success: function(res) {
                            arrangebySelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('model'))
                arrangebySelect.addOption({
                    id: "{{ request('model') }}",
                    text: "{{ request('model') }}"
                });
                arrangebySelect.setValue("{{ request('model') }}");
            @endif

            
        });
    </script>
@endsection
