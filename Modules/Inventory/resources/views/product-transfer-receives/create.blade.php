@section('title', 'Add Product Transfer Receive')
@section('description', 'Add Product Transfer Receive')
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
                                        {{ trans('menu.product-transfer-receives-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('inv.product-transfer-receives.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-transfer-receives-create-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('inv.product-transfer-receives.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h3>Receive Details</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="receive_date">Receive Date</label>
                                            <input type="date" name="receive_date" class="form-control"
                                                id="receive_date" placeholder="Receive Date"
                                                value="{{ old('receive_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_transfer_id">Product Transfer</label>
                                            <select name="product_transfer_id" id="product_transfer_id"
                                                class="form-control tom-select" onchange="loadTransferDetails(this.value)">
                                                <option value="">Choose Transfer</option>
                                                @foreach (\Modules\Inventory\Models\ProductTransfer::where('status', '!=', 'received')->get() as $transfer)
                                                    <option value="{{ $transfer->id }}" 
                                                        {{ old('product_transfer_id', request('product_transfer_id')) == $transfer->id ? 'selected' : '' }}>
                                                        {{ $transfer->invoice_no }} - {{ $transfer->sourceBranch->name }} to {{ $transfer->destinationBranch->name }}
                                                    </option>
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
                                                        {{ old('destination_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="receive_description">Receive Description</label>
                                            <textarea name="receive_description" id="receive_description" cols="30" rows="3" class="form-control"
                                                placeholder="Receive Description">{{ old('receive_description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Received Product Information</h3>
                                                <table class="table table-bordered" id="productTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Product Name</th>
                                                            <th>Transfer Quantity</th>
                                                            <th>Received Quantity</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
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
        // Load transfer details when transfer is selected
        function loadTransferDetails(transferId) {
            if (!transferId) {
                $('#productTable tbody').html('<tr><td colspan="4" class="text-center">No Product Added</td></tr>');
                return;
            }

            $.ajax({
                url: '{{ route("inv.product-transfers.show", ":id") }}'.replace(':id', transferId),
                type: 'GET',
                success: function(response) {
                    // Parse the response or use API endpoint if available
                    // For now, we'll redirect to a dedicated endpoint
                    window.location.href = '{{ route("inv.product-transfer-receives.create") }}?product_transfer_id=' + transferId;
                }
            });
        }

        function removeRow(row) {
            $(row).closest('tr').remove();
        }

        function calculateTotalSerialQuantity() {
            var totalQuantity = 0;
            var selectedProducts = $('#select-product-stock-modal input[name="stock_id"]:checked');
            totalQuantity = selectedProducts.length;
            return totalQuantity;
        }

        function calculateTotalLotQuantity() {
            var totalQuantity = 0;
            var inputsQuantity = $('#select-product-stock-modal input[name="quantity"]');
            inputsQuantity.each(function(){
                totalQuantity += parseInt($(this).val() != '' ? $(this).val() : 0);
            });
            return totalQuantity;
        }

        $(document).on('click', '.btn-add', function () {
            const currentRow = $(this).closest('tr');
            const limit = $(this).data('limit');
            const url = $(this).data('url');
            
            $("#select-product-stock-modal").find('.modal-body').loadWithSpinner(url, function() {
                const selectedSerials = currentRow.find('input[name^="serial_no"]');
                selectedSerials.each(function(){
                    const value = $(this).val();
                    $("#select-product-stock-modal .serial_no[value='" + value + "']").each(function(){
                        $(this).closest('tr').find('input[name="stock_id"]').prop('checked', true);
                    });
                });

                const selectedLots = currentRow.find('input[name^="lot_no"]');
                selectedLots.each(function(){
                    const value = $(this).val();
                    $("#select-product-stock-modal .lot_no[value='" + value + "']").each(function(){
                        $(this).closest('tr').find('input[name="quantity"]').val(
                            currentRow.find('input[name^="lots_quantity"][value="' + value + '"]').val()
                        );
                    });
                });
            });
            
            $("#select-product-stock-modal").modal('show');

            $('#save').off('click').on('click', function() {
                const totalQuantity = calculateTotalSerialQuantity() || calculateTotalLotQuantity();
                
                if (totalQuantity > limit) {
                    alert('Selected quantity exceeds the transfer quantity!');
                    return;
                }

                currentRow.find('input[name^="serial_no"]').remove();
                currentRow.find('input[name^="lot_no"]').remove();
                currentRow.find('input[name^="lots_quantity"]').remove();

                const selectedSerials = $('#select-product-stock-modal input[name="stock_id"]:checked');
                selectedSerials.each(function() {
                    const serial = $(this).closest('tr').find('.serial_no').text();
                    currentRow.append('<input type="hidden" name="serial_no[' + currentRow.data('product-id') + '][]" value="' + serial + '">');
                });

                const selectedLots = $('#select-product-stock-modal input[name="quantity"]');
                selectedLots.each(function() {
                    const lotNo = $(this).closest('tr').find('.lot_no').text();
                    const lotQty = $(this).val();
                    if (lotQty > 0) {
                        currentRow.append('<input type="hidden" name="lot_no[' + currentRow.data('product-id') + '][]" value="' + lotNo + '">');
                        currentRow.append('<input type="hidden" name="lots_quantity[' + currentRow.data('product-id') + '][]" value="' + lotQty + '">');
                    }
                });

                $("#select-product-stock-modal").modal('hide');
            });
        });

        $(document).ready(function() {
            @if(request('product_transfer_id'))
                loadTransferDetails('{{ request("product_transfer_id") }}');
            @endif
        });
    </script>
@endsection
