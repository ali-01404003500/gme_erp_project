@section('title', 'Issue Products')
@section('description', 'Issue Products')
@extends('layout.app')
@section('page-head')
    <style>
        .row {
            margin-left: 1vh;
            margin-right: 1vh;
        }

        /* .ts-control,
        .form-control {
            height: 48px !important;
        } */
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.issue-product-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('inv.issue-products.index'))
                            <a href="{{ route('inv.issue-products.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.issue-product-create-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <form action="{{ route('inv.issue-products.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <h4 style="margin-left: -10px; margin-top: 20px;">Issue Products Information</h4>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="issue_date">Issue Date:</label>
                                            <input type="text" class="form-control datePicker" id="issue_date"
                                                name="issue_date" value="{{ old('issue_date') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="branch_id">Branch*:</label>
                                            <select class="form-control tom-select" id="branch_id" name="branch_id"
                                                required>
                                                <option value="">Select Brach</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if (old('branch_id') == $branch->id) selected @endif>
                                                        {{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="purpose_id">Purpose:</label>
                                            <input type="text" class="form-control" id="purpose_id" name="purpose_id">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer:</label>
                                            <select class="form-control tom-select" id="customer_id" name="customer_id">
                                                <option value="">Select</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if (old('customer_id') == $customer->id) selected @endif>
                                                        {{ $customer->company_name }} - {{ $customer->address}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="order_number">Order number:</label>
                                            <input type="text" class="form-control" id="order_number"
                                                value="{{ old('order_number') }}" name="order_number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks:</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="2">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h4 style="margin-left: -10px; margin-top: 20px;">Products</h4>
                                    </div>
                                    

                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="issueProductTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Type*</th>
                                                    <th>Product Catalog*</th>
                                                    {{-- <th>Product Name</th> --}}
                                                    <th>SKU (Stock Keeping Unit)</th>
                                                    <th>Unit Type</th>
                                                    <th>Quantity</th>
                                                    <th>_</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($errors->any())
                                                    @php
                                                        $oldData = old();
                                                        $productTypeIds = $oldData['product_type_id'];
                                                        $productCatalogIds = $oldData['product_catalog_id'];
                                                        // $productNames = $oldData['product_name'];
                                                        $skus = $oldData['sku'];
                                                        $unitTypeIds = $oldData['unit_type_id'];
                                                        $quantities = $oldData['quantity'];
                                                    @endphp
                                                @endif
                                                @if (isset($productCatalogIds))
                                                    @foreach ($productCatalogIds as $index => $productCatalogId)
                                                        <tr>
                                                            <td>
                                                                <select class="form-control" name="product_type_id[]" onchange="getProductCatalogs(this)" required>
                                                                    <option value="">Select Product Type</option>
                                                                    @foreach($productTypes as $productType)
                                                                        <option value="{{ $productType->id }}" {{ $productType->id == $productTypeIds[$index] ? 'selected' : '' }}>{{ $productType->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control tom-select"
                                                                    name="product_catalog_id[]" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($productCatalogs as $productCatalog)
                                                                        <option value="{{ $productCatalog->id }}"
                                                                            {{ $productCatalog->id == $productCatalogId ? 'selected' : '' }}>
                                                                            {{ $productCatalog->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control" name="sku[]"
                                                                    value="{{ $skus[$index] }}"></td>
                                                            <td>
                                                                <select class="form-control tom-select"
                                                                    name="unit_type_id[]">
                                                                    <option value="">Select</option>
                                                                    @foreach ($units as $unit)
                                                                        <option value="{{ $unit->id }}"
                                                                            {{ $unit->id == $unitTypeIds[$index] ? 'selected' : '' }}>
                                                                            {{ $unit->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="number" class="form-control"
                                                                    name="quantity[]" value="{{ $quantities[$index] }}">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-xs remove-row"><i
                                                                        class="fa fa-times"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" name="product_type_id[]" onchange="getProductCatalogs(this)">
                                                                <option value="">Select Product Type</option>
                                                                @foreach($productTypes as $productType)
                                                                    <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control tom-select"
                                                                name="product_catalog_id[]" required>
                                                                <option value="">Select</option>
                                                                @foreach ($productCatalogs as $productCatalog)
                                                                    <option value="{{ $productCatalog->id }}">
                                                                        {{ $productCatalog->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    
                                                        <td><input type="text" class="form-control" name="sku[]">
                                                        </td>
                                                        <td>
                                                            <select class="form-control tom-select" name="unit_type_id[]">
                                                                <option value="">Select</option>
                                                                @foreach ($units as $unit)
                                                                    <option value="{{ $unit->id }}">
                                                                        {{ $unit->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control" name="quantity[]">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger btn-xs remove-row"><i
                                                                    class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary btn-xs" id="addRow">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>


                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start"
                                        style="padding: 20px;">
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
@endsection

@section('page_scripts')
    <script>
        function getProductCatalogs(element) {
            var productTypeId = $(element).find("option:selected").val();
            console.log(productTypeId);
        }

        var html = $('#issueProductTable tbody tr:first').clone();
        $(document).ready(function(e) {
            
            $("#addRow").click(function() {
                html.find('select, input').each(function() {
                    $(this).val('');
                });
                // html.find('.remove-row').remove();
                // html.append('<td><a href="javascript:void(0);" class="remove-row btn btn-danger"><i class="fa fa-minus"></i></a></td>');
                $('tbody').append(html);
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

        });

        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        
    </script>
@endsection
