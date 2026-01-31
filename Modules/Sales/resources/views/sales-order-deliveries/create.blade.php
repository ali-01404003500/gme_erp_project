@section('title', 'Sales Order Delivery')
@section('description', 'Sales Order Delivery for')
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
                                        {{ trans('menu.update-sales-order-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
            {{-- @dd( $salesOrder) --}}
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-sales-order-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.sales-order-deliveries.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}">
                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div>
                                            Customer name: {{ $salesOrder->customer->company_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div>
                                            Phone No: {{ $salesOrder->additional_phone }}
                                        </div>
                                    </div>
    
                                    <div class="col-md-4 mt-4">
                                        <div>
                                            Invoice Date: {{ $salesOrder->invoice_date }}
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <h4>Products</h4>
                                    </div>
                                    <div class="col-md-12 mt-4 t">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Unit Price</th>
                                                    <th>Sales Quantity</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productTable">
                                                @foreach ($salesOrder->salesOrderDetails as $salesOrderProduct)
                                                    <tr id="product_{{ $salesOrderProduct->product_id }}">
                                                        <td>
                                                            {{ $salesOrderProduct->product->name }}
                                                            <input type="hidden" name="product_id[]" value="{{ $salesOrderProduct->product_id }}">
                                                        </td>
                                                        <td>{{ numberFormat($salesOrderProduct->price) }}</td>
                                                        <td>{{ numberFormat($salesOrderProduct->quantity) }}</td>
                                                        <td><input type="number" name="quantity[]" class="form-control" readonly></td>
                                                        <td>{{ numberFormat($salesOrderProduct->amount) }}</td>
                                                        <td>
                                                            {{-- add button --}}
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-secondary btn-xs btn-add" data-bs-toggle="modal" data-limit="{{numberFormat($salesOrderProduct->quantity)}}" data-bs-target="#select-product-stock-modal" data-url="{{ route('sales.sales-order-deliveries.select-stock', $salesOrderProduct->product_id) }}">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12 mt-4 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
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
    <script>
        //calculate total selected quantity
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

        $(document).ready(function () {
            // select all
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

                console.log({inputQuantity, availableQuantity});
                if(inputQuantity > availableQuantity){
                   $( this ).val( availableQuantity );
                   toastr. warning('Only ' + availableQuantity + ' products can be selected');
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
        });
    </script>

    @endsection
