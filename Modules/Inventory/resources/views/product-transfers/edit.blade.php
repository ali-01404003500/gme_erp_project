@section('title', 'Edit Product Transfer')
@section('description', 'Edit Product Transfer')
@extends('layout.app')
@section('content')
<style>
    
</style>
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
                                        {{ trans('menu.edit-product-transfer-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 row">
                                @if (hasPermission('inv.product-transfers.index'))
                                <a href="{{ route('inv.product-transfers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                @endif

                                @if (hasPermission('inv.product-transfers.create'))
                                    <a class="btn btn-xs btn-primary me-1 btn-sm" style="margin-left: 5px;" href="{{ route('inv.product-transfers.create') }}">
                                        Add New
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-product-transfer-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('inv.product-transfers.update', $productTransfer->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h3>Transfer Details</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transfer_date">Transfer Date</label>
                                            <input type="date" name="transfer_date" class="form-control"
                                                id="transfer_date" placeholder="Transfer Date"
                                                value="{{ date('Y-m-d', strtotime($productTransfer->transfer_date)) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="source_warehouse_id">Source Warehouse</label>
                                            <select name="source_warehouse_id" id="source_warehouse_id"
                                                class="form-control tom-select">
                                                <option value="">Choose Source Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ old('source_warehouse_id', $productTransfer->source_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="destination_warehouse_id">Destination Warehouse</label>
                                            <select name="destination_warehouse_id" id="destination_warehouse_id"
                                                class="form-control tom-select">
                                                <option value="">Choose Destination Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ old('destination_warehouse_id', $productTransfer->destination_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="transfer_description">Transfer Description</label>
                                            <textarea name="transfer_description" id="transfer_description" cols="30" rows="3" class="form-control"
                                                placeholder="Transfer Description">{{ $productTransfer->transfer_description }}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Product Type</th>
                                                            <th style="width: 25%">Product Name</th>
                                                            <th style="width: 15%">Unit Type</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            <th style="width: 5%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(old('product_ids'))
                                                            @foreach(old('product_ids') as $key => $productId)
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_type_id[]" class="form-control tom-select">
                                                                            <option value="">Choose Product Type</option>
                                                                            @foreach ($productTypes as $productType)
                                                                                <option value="{{ $productType->id }}" {{ old('product_type_id.'.$key) == $productType->id ? 'selected' : '' }}>
                                                                                    {{ $productType->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="product_ids[]" class="tom-select form-control product_id">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="unit_type_id[]" class="form-control tom-select">
                                                                            <option value="">Choose Unit Type</option>
                                                                            @foreach ($units as $unit)
                                                                                <option value="{{ $unit->id }}" {{ old('unit_type_id.'.$key) == $unit->id ? 'selected' : '' }}>
                                                                                    {{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="quantity[]" value="{{ old('quantity.'.$key) }}"
                                                                            class="form-control" placeholder="Quantity">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-xs" id="remove_row"
                                                                            onclick="removeRow(this)">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @foreach ($productTransfer->productTransferDetails as $key => $productTransferDetail)   
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_type_id[]" id="product_type_1"
                                                                            class="form-control tom-select">
                                                                            <option value="">Choose Product Type</option>
                                                                            @foreach ($productTypes as $productType)
                                                                                <option value="{{ $productType->id }}" {{ $productTransferDetail->productCatalog->product_type_id == $productType->id ? 'selected' : '' }}>
                                                                                    {{ $productType->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="product_ids[]" class="tom-select form-control product_id">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}" {{ $productTransferDetail->product_id == $product->id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                
                                                                        </select>
                                                                    </td>
                                                     
                                                                    <td>
                                                                        <select name="unit_type_id[]" id="unit_type_1"
                                                                            class="form-control tom-select">
                                                                            <option value="">Choose Unit Type</option>
                                                                            @foreach ($units as $unit)
                                                                                <option value="{{ $unit->id }}" {{ $productTransferDetail->productCatalog->unit_type_id == $unit->id ? 'selected' : '' }}> 
                                                                                    {{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="quantity[]" id="quantity_1" value="{{ $productTransferDetail->quantity }}"
                                                                            class="form-control" placeholder="Quantity">
                                                                    </td>
                                                                
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-xs" id="remove_row"
                                                                            onclick="removeRow(this)">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                                    <i class="fa fa-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
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
        const row =$("#product_info_table tbody tr:first-child").clone();
        row.find('input').val('');
        row.find('select option:selected').removeAttr('selected');
        row.find('#remove_row').removeClass('disabled');
        row.find('#remove_row').removeAttr('disabled');

        $("#add_row").click(function() {
            $("#product_info_table tbody").append(row.clone());
        });

        function removeRow(row) {
            $(row).closest('tr').remove();
        }
    </script>

@endsection
