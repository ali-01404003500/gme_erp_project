@section('title', 'Issue Products Update')
@section('description', 'Issue Products Update')
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
                                        {{ trans('menu.edit-issue-product-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 row">
                                @if (hasPermission('inv.issue-products.index'))
                                <a href="{{ route('inv.issue-products.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                @endif

                                @if (hasPermission('inv.issue-products.create'))
                                    <a class="btn btn-xs btn-primary me-1 btn-sm" style="margin-left: 5px;"
                                        href="{{ route('inv.issue-products.create') }}">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-issue-product-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <form action="{{ route('inv.issue-products.update', $issueProduct->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="issue_date">Issue Date:</label>
                                            <input type="text" class="form-control datePicker" id="issue_date"
                                                name="issue_date" value="{{ old('issue_date', $issueProduct->issue_date) }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="warehouse_id">Warehouse*:</label>
                                            <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                                                <option value="">Select</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        @if (old('warehouse_id', $issueProduct->warehouse_id) == $warehouse->id) selected @endif>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="purpose_id">Purpose*:</label>
                                            <select class="form-control" id="purpose_id" name="purpose_id" required>
                                                <option value="">Select</option>
                                                <option value="1">Purpose 1</option>
                                                <option value="2">Purpose 2</option>
                                                <option value="3">Purpose 3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer:</label>
                                            <select class="form-control" id="customer_id" name="customer_id">
                                                <option value="">Select</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if (old('customer_id', $issueProduct->customer_id) == $customer->id) selected @endif>
                                                        {{ $customer->company_name }} - {{ $customer->address}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="machine_id">Machine:</label>
                                            <select class="form-control" id="machine_id" name="machine_id">
                                                <option value="">Select</option>
                                                <option value="1">Machine 1</option>
                                                <option value="2">Machine 2</option>
                                                <option value="3">Machine 3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="order_number">Order number:</label>
                                            <input type="text" class="form-control" id="order_number"
                                                value="{{ old('order_number', $issueProduct->order_number) }}"
                                                name="order_number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks:</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="2">{{ old('remarks', $issueProduct->remarks) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h4>Products</h4>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="issueProductTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Catalog*</th>
                                                    <th>Product Name</th>
                                                    <th>SKU (Stock Keeping Unit)</th>
                                                    <th>Unit Type</th>
                                                    <th>Quantity</th>
                                                    <th>_</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($issueProduct->issueProductDetails as $issueProductDetail)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="issue_product_detail_id[]"
                                                                value="{{ $issueProductDetail->id }}">
                                                            <select class="form-control" name="product_catalog_id[]"
                                                                required>
                                                                <option value="">Select</option>
                                                                @foreach ($products as $productCatalog)
                                                                    <option value="{{ $productCatalog->id }}"
                                                                        {{ $productCatalog->id == $issueProductDetail->product_catalog_id ? 'selected' : '' }}>
                                                                        {{ $productCatalog->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="product_name[]"
                                                                value="{{ $issueProductDetail->product_name }}">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="sku[]"
                                                                value="{{ $issueProductDetail->sku }}"></td>
                                                        <td>
                                                            <select class="form-control" name="unit_type_id[]">
                                                                <option value="">Select</option>
                                                                @foreach ($units as $unit)
                                                                    <option value="{{ $unit->id }}"
                                                                        {{ $unit->id == $issueProductDetail->unit_type_id ? 'selected' : '' }}>
                                                                        {{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control" name="quantity[]"
                                                                value="{{ $issueProductDetail->quantity }}"></td>
                                                        <td>
                                                            <button class="btn btn-danger btn-xs remove-row"><i
                                                                    class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
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
        $(document).ready(function(e) {
            var html = $('#issueProductTable tbody tr:first').clone();
            html.find('input').each(function() {
                $(this).val('');
            });
            html.find('select').each(function() {
                $(this).find('option:selected').removeAttr('selected');
            });
            $("#addRow").click(function() {

                html.find('input[name="issue_product_detail_id[]"]').remove();
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
        })
    </script>
@endsection
