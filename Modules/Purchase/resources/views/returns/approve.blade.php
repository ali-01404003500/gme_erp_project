@extends('layout.app')
@section('title', 'Purchase Return Verification') 
@section('description', 'Purchase Return Verification') 
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
                                        {{ trans('Purchase Return Verification ') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Purchase Return Verification') }}</h4>
                    <x-error-alart />

                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.returns.approve.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_no">Invoice Id : </label>
                                            {{ optional($purchaseReturn)->invoice_no }}
                                            <input type="hidden" name="purchase_return_id"
                                                value="{{ $purchaseReturn->id }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="input-group ">
                                            <label for="reference_invoice">Reference No:</label>
                                            {{ $purchaseReturn->reference_invoice }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_date">Return Date : </label>
                                            {{ date('d F, Y', strtotime($purchaseReturn->return_date)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier_id">Supplier Name :</label>
                                            {{ optional(optional($purchaseReturn)->supplier)->company_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warehouse_id">Warehouse Name : </label>
                                            {{ optional(optional(optional($purchaseReturn)->requisition)->warehouse)->name }}
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="created_by">Created By : </label>
                                            {{ $purchaseReturn->createdBy->name }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" style="width: 5%">Sl</th>
                                                            <th class="text-center" style="width: 25%">Product Name</th>
                                                            <th class="text-center" style="width: 15%">Return Quantity</th>
                                                            <th class="text-center" style="width: 15%">Quantity</th>

                                                            <th class="text-center" style="width: 15%">U. Price</th>
                                                            <th class="text-center" style="width: 15%">Total</th>
                                                            <th class="text-center" style="width: 15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="productTable">
                                                        @foreach ($purchaseReturn->purchaseReturnDetails as $key => $item)
                                                            <tr id="product_{{ $item->product_id }}">
                                                                <td class="text-center">{{ $key + 1 }}</td>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]"
                                                                        value="{{ $item->product_id }}">
                                                                    {{ $item->product->name }}
                                                                    @if(old('serial_no')[$item->product_id]??null)
                                                                    @foreach (old('serial_no')[$item->product_id] as  $value)
                                                                        <input type="hidden" name="serial_no[{{ $item->product_id }}][]" value="{{ $value }}">
                                                                    @endforeach
                                                                @endif
                                                                @if(old('lot_no')[$item->product_id]??null)
                                                                    @foreach (old('lot_no')[$item->product_id] as  $value)
                                                                        <input type="hidden" name="lot_no[{{ $item->product_id }}][]" value="{{ $value }}">
                                                                    @endforeach
                                                                @endif
                                                                @if(old('lots_quantity')[$item->product_id]??null)
                                                                    @foreach (old('lots_quantity')[$item->product_id] as  $value)
                                                                        <input type="hidden" name="lots_quantity[{{ $item->product_id }}][]" value="{{ $value }}">
                                                                    @endforeach
                                                                @endif
                                                                </td>

                                                                <td class="text-center">
                                                                    {{ numberFormat($item->quantity) }}
                                                                    <input type="hidden" name="return_qty[]" value="{{ $item->quantity }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" value="{{ old('quantity')[$key]??"" }}"
                                                                        class="form-control text-center" readonly>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text" name="price[]"
                                                                        id="unit_price{{ $loop->index }}"
                                                                        value="{{ $item->price }}"
                                                                        class="form-control text-center" readonly>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text" name="amount[]"
                                                                        id="total{{ $loop->index }}"
                                                                        value="{{ $item->quantity * $item->price }}"
                                                                        class="form-control text-center" readonly>
                                                                </td>
                                                                <td class="text-center">
                                                                    {{-- add button --}}
                                                                    <div class="btn-group btn-group-sm">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-xs btn-add"
                                                                            data-bs-toggle="modal"
                                                                            data-limit="{{ numberFormat($item->quantity) }}"
                                                                            data-bs-target="#select-product-stock-modal"
                                                                            data-url="{{ route('purchase.returns.select-stock', ['product_id' => $item->product_id, 'requisition_id' => $purchaseReturn->requisition_id]) }}">
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                            <a href="{{ route('purchase.returns.index') }}"
                                                class="btn btn-primary btn-sm">Back</a>
                                        </div>
                                    </div>
                            </form>
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
                        <button type="button" id="save" data-bs-dismiss="modal"
                            class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                    </div>
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
                $(`#productTable tr#product_${product_ids[0]} .btn-add`).removeClass('btn-secondary').addClass('btn-success')
            }

            if(inputsQuantity.length != 0){
                product_ids.forEach((product_id, index) => {
                    $(`#productTable tr#product_${product_id} td:first`).append(`<input type="hidden" name="lot_no[${product_id}][]" value="${lot_no[index]}">`);
                    $(`#productTable tr#product_${product_id} td:first`).append(`<input type="hidden" name="lots_quantity[${product_id}][]" value="${quantities[index]}">`);
                });
                $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(quantities.reduce((a, b) => a + b, 0));
                $(`#productTable tr#product_${product_ids[0]} .btn-add`).removeClass('btn-secondary').addClass('btn-success')
            }
            // dissmiss modal
            $("#select-product-stock-modal").modal('hide');
        });
    });
</script>

@endsection
