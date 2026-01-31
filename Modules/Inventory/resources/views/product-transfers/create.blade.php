@section('title', 'Add Product Transfer')
@section('description', 'Add Product Transfer')
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
                                        {{ trans('menu.product-transfer-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('inv.product-transfers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-transfer-create-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('inv.product-transfers.store') }}" method="POST"
                                enctype="multipart/form-data">
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
                                                value="{{ old('transfer_date', date('Y-m-d')) }}">
                                            <input type="hidden" name="product_transfer_request_id" value="{{ $productTransferRequest->id }}">
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
                                                        {{ old('source_warehouse_id', $productTransferRequest->source_branch_id) == $warehouse->id ? 'selected' : '' }}>
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
                                                        {{ old('destination_warehouse_id', $productTransferRequest->destination_branch_id) == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="transfer_description">Transfer Description</label>
                                            <textarea name="transfer_description" id="transfer_description" cols="30" rows="3" class="form-control"
                                                placeholder="Transfer Description">{{ old('transfer_description') }}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="productTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Product Name</th>
                                                            <th>Request Quantity</th>
                                                            <th>Quantity</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($productTransferRequest->productTransferRequestDetails)
                                                            @foreach($productTransferRequest->productTransferRequestDetails as $key => $productTransferRequestDetail)
                                                                <tr id="product_{{ $productTransferRequestDetail->product_catalog_id }}">
                                                                    <td>
                                                                        {{-- <select name="product_ids[]" class="form-select product_id tom-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}" {{ old('product_ids',$productTransferRequestDetail->product_id) == $product->id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select> --}}
                                                                        {{ $productTransferRequestDetail->productCatalog->productName() }}
                                                                        <input type="hidden" name="product_id[]"  value="{{ $productTransferRequestDetail->product_catalog_id }}">
                                                                    </td>
                                                                    <td>
                                                                        {{ numberFormat($productTransferRequestDetail->quantity) }}
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="quantity[]" class="form-control readonly" readonly
                                                                            placeholder="Quantity" value="{{ old('quantity.'.$key, 0) }}">
                                                                        
                                                                        @if(old('serial_no.'.$productTransferRequestDetail->product_catalog_id))
                                                                            @foreach(old('serial_no.'.$productTransferRequestDetail->product_catalog_id) as $serial)
                                                                                <input type="hidden" name="serial_no[{{ $productTransferRequestDetail->product_catalog_id }}][]" value="{{ $serial }}">
                                                                            @endforeach
                                                                        @endif

                                                                        @if(old('lot_no.'.$productTransferRequestDetail->product_catalog_id))
                                                                            @foreach(old('lot_no.'.$productTransferRequestDetail->product_catalog_id) as $k => $lot)
                                                                                <input type="hidden" name="lot_no[{{ $productTransferRequestDetail->product_catalog_id }}][]" value="{{ $lot }}">
                                                                                <input type="hidden" name="lots_quantity[{{ $productTransferRequestDetail->product_catalog_id }}][]" value="{{ old('lots_quantity.'.$productTransferRequestDetail->product_catalog_id.'.'.$k) }}">
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                            <button type="button" class="btn btn-secondary btn-xs btn-add"  data-limit="{{numberFormat($productTransferRequestDetail->quantity)}}"  data-url="{{ route('sales.sales-order-deliveries.select-stock', $productTransferRequestDetail->product_catalog_id) }}" data-bs-toggle="tooltip" title="Select Stock">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="6" class="text-center">No Product Added</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                    {{-- <tfoot>
                                                        <tr>
                                                            <td colspan="6" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                                    <i class="fa fa-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot> --}}
                                                </table>
                                            </div>
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


    <div class="modal fade inputForm-modal" id="select-product-stock-modal" tabindex="-1" role="dialog"
    aria-labelledby="select-product-stock-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Select Stock </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="save" data-bs-dismiss="modal" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
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

        //select stock
        function calculateTotalSerialQuantity() {
            var totalQuantity = 0;
            var selectedProducts = $('#select-product-stock-modal input[name="stock_id"]:checked');
            totalQuantity = selectedProducts.length;
            return totalQuantity
        }

        function calculateTotalLotQuantity() {
            var totalQuantity = 0;
            var inputsQuantity = $('#select-product-stock-modal input[name="quantity"]');
            inputsQuantity.each(function(){
                totalQuantity += parseInt($(this).val()!=''?$(this).val():0);
            })

            return totalQuantity
        }

        $(document).on('click', '.btn-add', function () {
            const currentRow = $(this).closest('tr');
                $("#select-product-stock-modal").find('.modal-body').loadWithSpinner($(this).data('url'), function() {
                // select checked after load
                const selectedSerials = currentRow.find('input[name^="serial_no"]');
                
                selectedSerials.each(function(){
                    const value = $(this).val();
                    $("#select-product-stock-modal .serial_no[value='" + value + "']").each(function(){
                        $(this).closest('tr').find('input[name="stock_id"]').prop('checked', true);
                    })
                });
//selected lots after load
                const selectedLots = currentRow.find('input[name^="lot_no"]');
                selectedLots.each(function(){
                    const value = $(this).val();
                    console.log({selectLots: value});
                    $("#select-product-stock-modal .lot_no[value='" + value + "']").each(function(){
                        // console.log({currentRowQ:currentRow.find('input[name^="quantity"]')});
                        $(this).closest('tr').find('input[name="quantity"]').val(currentRow.find('input[name^="quantity"]').val());
                    })
                });

            });
            $("#select-product-stock-modal").data("limit", $(this).data('limit'));
            $("#select-product-stock-modal").modal('show');
        });

        $(document).on('click', '#select-product-stock-modal input[name="stock_id"]', function () {
            var totalQuantity = calculateTotalSerialQuantity();
            var limit = $("#select-product-stock-modal").data("limit");
            console.log(totalQuantity + ' ' + limit);
            if (totalQuantity > limit) {
                $(this).prop('checked', false);
                toastr. warning('Only ' + limit + ' products can be selected');
            }
        });

        $(document).on("input", "#select-product-stock-modal input[name='quantity']", function () {
            var totalQuantity = calculateTotalLotQuantity();
            var limit = Number($("#select-product-stock-modal").data("limit"));
            var inputQuantity = Number($(this).val());
            var previousQuantity = totalQuantity - inputQuantity;
            console.log({totalQuantity, limit, previousQuantity});
            var availableQuantity = $(this).closest('tr').find('input[name="available_stock"]').val();
            if ( limit - previousQuantity < inputQuantity) {
                $(this).val( limit - previousQuantity );
                toastr. warning('Only ' + limit + ' products can be selected');
            }
        });




        $(document).on('click', '#select-product-stock-modal #save', function () {
            var selectedProducts = $('input[name="stock_id"]:checked');
            var inputsQuantity = $('#select-product-stock-modal input[name="quantity"]');
            var serial_no = [];

            var lot_no = [];
            var quantities = [];
            var product_ids = [];
            if(selectedProducts.length != 0){
                selectedProducts.each(function(){
                    serial_no.push($(this).closest('tr').find('input.serial_no').val());
                    product_ids.push($(this).val());
                });
            }
            if(inputsQuantity.length != 0){
                inputsQuantity.each(function(){
                    if($(this).val() != 0){
                        product_ids.push($(this).closest('tr').find('input.product_id').val());
                        lot_no.push($(this).closest('tr').find('input.lot_no').val());
                        quantities.push(Number($(this).val()));
                    }
                });
            }
            console.log(serial_no.length + ' products selected');
            console.log({product_ids, serial_no, lot_no, quantities});
            $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(0);
            //remove previous serial_no
            $(`#productTable tr#product_${product_ids[0]} td:first`).find('input[name^="serial_no"]').remove();
            $(`#productTable tr#product_${product_ids[0]} td:first`).find('input[name^="lot_no"]').remove();
            $(`#productTable tr#product_${product_ids[0]} td:first`).find('input[name^="lots_quantity"]').remove();
            if(selectedProducts.length != 0){
                product_ids.forEach((product_id, index) => {
                    $(`#productTable tr#product_${product_id} td:first`).append(`<input type="hidden" name="serial_no[${product_id}][]" value="${serial_no[index]}">`);
                });
                $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(selectedProducts.length);
            }

            if(inputsQuantity.length != 0){
                product_ids.forEach((product_id, index) => {
                    $(`#productTable tr#product_${product_id} td:first`).append(`<input type="hidden" name="lot_no[${product_id}][]" value="${lot_no[index]}">`);
                    $(`#productTable tr#product_${product_id} td:first`).append(`<input type="hidden" name="lots_quantity[${product_id}][]" value="${quantities[index]}">`);
                });
                $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(quantities.reduce((a, b) => a + b, 0));
            }
            // dissmiss modal
            $("#select-product-stock-modal").modal('hide');
        });


    </script>

    

@endsection
