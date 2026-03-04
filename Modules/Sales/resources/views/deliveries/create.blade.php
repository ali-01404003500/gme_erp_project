@section('title', 'Make Delivery')
@section('description', 'Make Delivery for')
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
                                        {{ trans('Delivery Details') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Delivery Details') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.deliveries.update', request()->delivery_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="sales_type" class="sales_type"
                                    value="{{ @$source->sales_type }}">
                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="sales_order_id">Sales Order ID</label>
                                            <input type="text" name="sales_order_id" class="form-control"
                                                id="sales_order_id" readonly value="{{ $source->sales_order_id }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_name">Customer Name</label>
                                            <input type="text" name="customer_name" class="form-control" id="customer_name"
                                                readonly value="{{ $source->customer->company_name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" id="address" readonly
                                                value="{{ $source->customer->address }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="phone">Phone No</label>
                                            <input type="text" name="phone" class="form-control" id="phone" readonly
                                                value="{{ $source->customer->phone }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="service_unique_id">Service ID</label>
                                            <input type="text" name="service_unique_id" class="form-control"
                                                id="service_unique_id" readonly
                                                value="{{ @$source->service->service_unique_id }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date</label>
                                            <input type="text" name="invoice_date" class="form-control" id="invoice_date"
                                                readonly value="{{ $source->invoice_date }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="sold_by">Sold By</label>
                                            <input type="text" name="sold_by" class="form-control" id="sold_by" readonly
                                                value="{{ $source->createdBy->name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="arranged_by">Arranged By</label>
                                            <select name="arranged_by" id="arranged_by" class="form-control tom-select">
                                                <option value="">{{ __('Select Employee') }}</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="checked_by">Checked By</label>
                                            <select name="checked_by" id="checked_by" class="form-control tom-select">
                                                <option value="">{{ __('Select Employee') }}</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="carton_no">Carton No</label>
  
                                            <input type="text" name="carton_no" class="form-control" id="carton_no"
                                                value="0" required>
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
                                                    <th>Model</th>
                                                    {{-- <th>Unit Price</th> --}}
                                                    <th>Sales Quantity</th>
                                                    <th>Remaining Quantity</th>
                                                    <th>Quantity</th>
                                                    {{-- <th>Amount</th> --}}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productTable">
                                                @foreach ($source->details as $key => $detail)
                                                    @php
                                                        // dd($detail->quantity, $previousDeliveries);
                                                        $deliveredQty = $previousDeliveries
                                                            ->flatMap(function ($delivery) {
                                                                return $delivery->deliveryDetails;
                                                            })
                                                            ->where('product_id', $detail->product_id)
                                                            ->sum('quantity');

                                                        $remainingQty = $detail->quantity - $deliveredQty;
                                                    @endphp
                                                    <tr id="product_{{ $detail->product_id }}">
                                                        <td>
                                                            {{ $detail->product->name }}
                                                            <input type="hidden" name="product_id[]"
                                                                value="{{ $detail->product_id }}">
                                                            @if (old('serial_no')[$detail->product_id] ?? null)
                                                                @foreach (old('serial_no')[$detail->product_id] as $value)
                                                                    <input type="hidden" name="serial_no[{{ $detail->product_id }}][]"
                                                                        value="{{ $value }}">
                                                                @endforeach
                                                            @endif
                                                            @if (old('lot_no')[$detail->product_id] ?? null)
                                                                @foreach (old('lot_no')[$detail->product_id] as $value)
                                                                    <input type="hidden" name="lot_no[{{ $detail->product_id }}][]"
                                                                        value="{{ $value }}">
                                                                @endforeach
                                                            @endif
                                                            @if (old('lots_quantity')[$detail->product_id] ?? null)
                                                                @foreach (old('lots_quantity')[$detail->product_id] as $value)
                                                                    <input type="hidden"
                                                                        name="lots_quantity[{{ $detail->product_id }}][]"
                                                                        value="{{ $value }}">
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->product->model }}</td>
                                                        <td>
                                                            {{ numberFormat($detail->quantity) }}
                                                            <input type="hidden" name="sales_quantity[]"
                                                                value="{{ $detail->quantity }}">
                                                        </td>
                                                        <td>
                                                            {{ numberFormat($remainingQty) }}
                                                            <input type="hidden" name="remaining_quantity[]"
                                                                value="{{ $remainingQty }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control"
                                                                value="{{ old('quantity')[$key] ?? '' }}" readonly>
                                                        </td>
                                                        {{-- <td>{{ numberFormat($detail->amount) }}</td> --}}
                                                        <td>
                                                            {{-- add button --}}
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-secondary btn-xs btn-add"
                                                                    data-bs-toggle="modal"
                                                                    data-limit="{{ numberFormat($detail->quantity) }}"
                                                                    data-bs-target="#select-product-stock-modal"
                                                                    data-url="{{ route('sales.sales-order-deliveries.select-stock', $detail->product_id) }}">
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
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Make Delivery</button>
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
                        <button type="button" id="save" data-bs-dismiss="modal"
                            class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
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
                inputsQuantity.each(function () {
                    totalQuantity += parseInt($(this).val() != '' ? $(this).val() : 0);
                })

                return totalQuantity
            }

            $(document).ready(function () {
                // select all
                $(document).on('click', '.btn-add', function () {
                    const currentRow = $(this).closest('tr');
                    $("#select-product-stock-modal").find('.modal-body').loadWithSpinner($(this).data('url'),
                        function () {
                            // select checked after load
                            const selectedSerials = currentRow.find('input[name^="serial_no"]');

                            selectedSerials.each(function () {
                                const value = $(this).val();
                                $("#select-product-stock-modal .serial_no[value='" + value + "']")
                                    .each(function () {
                                        $(this).closest('tr').find('input[name="stock_id"]')
                                            .prop('checked', true);
                                    })
                            });
                            const selectedLots = currentRow.find('input[name^="lot_no"]');
                            selectedLots.each(function () {
                                const value = $(this).val();
                                console.log({
                                    selectLots: value
                                });
                                $("#select-product-stock-modal .lot_no[value='" + value + "']")
                                    .each(function () {
                                        // console.log({currentRowQ:currentRow.find('input[name^="quantity"]')});
                                        $(this).closest('tr').find('input[name="quantity"]')
                                            .val(currentRow.find('input[name^="lots_quantity"]')
                                                .val());
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
                        toastr.warning('Only ' + limit + ' products can be selected');
                    }
                });

                $(document).on("input", "#select-product-stock-modal input[name='quantity']", function () {
                    var totalQuantity = calculateTotalLotQuantity();
                    var limit = Number($("#select-product-stock-modal").data("limit"));
                    var inputQuantity = Number($(this).val());
                    var previousQuantity = totalQuantity - inputQuantity;
                    console.log({
                        totalQuantity,
                        limit,
                        previousQuantity
                    });
                    var availableQuantity = $(this).closest('tr').find('input[name="available_stock"]').val();
                    if (limit - previousQuantity < inputQuantity) {
                        $(this).val(limit - previousQuantity);
                        toastr.warning('Only ' + limit + ' products can be selected');
                    }

                    console.log({
                        inputQuantity,
                        availableQuantity
                    });
                    if (inputQuantity > availableQuantity) {
                        $(this).val(availableQuantity);
                        toastr.warning('Only ' + availableQuantity + ' products can be selected');
                    }
                });




                $(document).on('click', '#select-product-stock-modal #save', function () {
                    var selectedProducts = $('input[name="stock_id"]:checked');
                    var inputsQuantity = $('#select-product-stock-modal input[name="quantity"]');
                    var serial_no = [];

                    var lot_no = [];
                    var quantities = [];
                    var product_ids = [];
                    if (selectedProducts.length != 0) {
                        selectedProducts.each(function () {
                            serial_no.push($(this).closest('tr').find('input.serial_no').val());
                            product_ids.push($(this).val());
                        });
                    }
                    if (inputsQuantity.length != 0) {
                        inputsQuantity.each(function () {
                            if ($(this).val() != 0) {
                                product_ids.push($(this).closest('tr').find('input.product_id').val());
                                lot_no.push($(this).closest('tr').find('input.lot_no').val());
                                quantities.push(Number($(this).val()));
                            }
                        });
                    }
                    console.log(serial_no.length + ' products selected');
                    console.log({
                        product_ids,
                        serial_no,
                        lot_no,
                        quantities
                    });
                    $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(0);
                    //remove previous serial_no
                    $(`#productTable tr#product_${product_ids[0]} td:first`).find('input[name^="serial_no"]')
                        .remove();
                    $(`#productTable tr#product_${product_ids[0]} td:first`).find('input[name^="lot_no"]')
                        .remove();
                    $(`#productTable tr#product_${product_ids[0]} td:first`).find(
                        'input[name^="lots_quantity"]').remove();
                    if (selectedProducts.length != 0) {
                        product_ids.forEach((product_id, index) => {
                            $(`#productTable tr#product_${product_id} td:first`).append(
                                `<input type="hidden" name="serial_no[${product_id}][]" value="${serial_no[index]}">`
                            );
                        });
                        $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(
                            selectedProducts.length);
                        $(`#productTable tr#product_${product_ids[0]} .btn-add`).removeClass('btn-secondary')
                            .addClass('btn-success')
                    }

                    if (inputsQuantity.length != 0) {
                        product_ids.forEach((product_id, index) => {
                            $(`#productTable tr#product_${product_id} td:first`).append(
                                `<input type="hidden" name="lot_no[${product_id}][]" value="${lot_no[index]}">`
                            );
                            $(`#productTable tr#product_${product_id} td:first`).append(
                                `<input type="hidden" name="lots_quantity[${product_id}][]" value="${quantities[index]}">`
                            );
                        });
                        $(`#productTable tr#product_${product_ids[0]} input[name="quantity[]"]`).val(quantities
                            .reduce((a, b) => a + b, 0));
                        $(`#productTable tr#product_${product_ids[0]} .btn-add`).removeClass('btn-secondary')
                            .addClass('btn-success')
                    }
                    // dissmiss modal
                    $("#select-product-stock-modal").modal('hide');
                });


                //submit validation
                $('#submitBtn').on("click", function (e) {
                    // e.preventDefault();
                    const rows = $("#productTable tr");
                    const salesType = $('.sales_type').val();
                    let isValid = true;
                    let totalQty = 0;

                    rows.each(function () {
                        const row = $(this);
                        const sqty = parseInt(row.find('input[name^="sales_quantity"]').val()) || 0;
                        const remainingQty = parseInt(row.find('input[name^="remaining_quantity"]').val()) || 0;
                        const qty = parseInt(row.find('input[name^="quantity"]').val()) || 0;

                        // Clear previous states
                        row.find('input[name^="quantity"]').removeClass('is-invalid is-valid');

                        // Validate that quantity is not greater than remaining quantity
                        // console.log({qty, remainingQty});
                        const is_partial = salesType == 'partial_sales' ? true : false;

                        if ((!is_partial && qty != remainingQty) || (is_partial && (qty > remainingQty))) {
                            row.find('input[name^="quantity"]').addClass('is-invalid');
                            toastr.error('Quantity should be less than or equal to Remaining Quantity');
                            isValid = false;
                            return false; // break the loop
                        }
                        totalQty += qty;

                        row.find('input[name^="quantity"]').addClass('is-valid');
                        console.log({ salesType, qty, remainingQty })
                    });
                    if (totalQty == 0) {
                        isValid = false;
                        toastr.error('Quantity should be greater than 0');
                    }
                    // console.log('isValid: ' , isValid);

                    return isValid;
                });

            });
        </script>

    @endsection