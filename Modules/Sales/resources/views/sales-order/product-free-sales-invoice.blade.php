@section('title', 'Product Free Sales Invoice')
@section('description', 'Product Free Sales Invoice')
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
                                    <li class="breadcrumb-item active" aria-current="page">Product Free Sales Invoice</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row gap-1">
                            @if (hasPermission('sales.sales-orders.index'))
                                <a href="{{ route('sales.sales-orders.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                            @if (isset($freeSalesInvoice))
                                <a href="{{ route('sales.sales-orders.product-free-sales-invoice.view', $freeSalesInvoice->id) }}"
                                    class="btn btn-info btn-default btn-squared radius-md shadow2 btn-sm">
                                    View Free Invoice
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Product Free Sales Invoice -
                        {{ $salesOrder->sales_order_id ?? 'New' }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="free-sales-invoice-form"
                                action="{{ route('sales.sales-orders.product-free-sales-invoice.store', $salesOrder->id) }}"
                                method="POST" {{-- The route is always 'store', the service method handles update/create --}} enctype="multipart/form-data">
                                @csrf
                                @isset($freeSalesInvoice)
                                    <input type="hidden" name="free_sales_invoice_id" value="{{ $freeSalesInvoice->id }}">
                                @endisset
                                <div class="row mb-4">
                                    <style>
                                        .payment-table {
                                            display: none;
                                            /* Hide all payment tables by default */
                                        }

                                        .payment-table.active {
                                            display: table;
                                            /* Show the active payment table */
                                        }

                                        .remove-row {
                                            cursor: pointer;
                                        }
                                    </style>

                                    <!-- Customer Information Section -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select"
                                                required disabled>
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null)
                                                            ({{ $customer->area->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="customer_id"
                                                value="{{ old('customer_id', $salesOrder->customer_id ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="invoice_date">Sales Date</label>
                                            <input type="text" name="invoice_date"
                                                class="form-control flatdate invoice_date_input" {{-- Removed `readonly` here to allow editing date --}}
                                                id="invoice_date" placeholder="Invoice Date"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    {{-- @dd( $matchedClearances[0]) --}}
                                    @php
                                        $giftAmount = ($matchedClearances[0]->gift_type ?? 'flat') == 'flat'?($matchedClearances[0]->gift_amount ?? 0): $salesOrder->net_amount * (($matchedClearances[0]->gift_amount ?? 0)/100);
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gift_amount">Gift Amount</label>
                                            <input type="number" name="gift_amount" class="form-control"
                                                value="{{ old('gift_amount', $giftAmount) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>

                                                <div class="table-responsive" style="overflow: visible;">
                                                    <table class="table  table-bordered" id="product_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 40%">Product Name</th>
                                                                <th style="width: 15%">Quantity</th>
                                                                <th style="width: 15%">Price</th>
                                                                <th style="width: 15%">Amount</th>
                                                                <th style="width: 8%" style="text-align: right;">
                                                                    <button type="button" class="btn btn-info btn-sm"
                                                                        id="add_row">
                                                                        <i class="fa fa-plus"></i> Add</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $product_ids = old('product_ids', []);
                                                                $quantities = old('quantity', []);
                                                                $prices = old('price', []);
                                                                $amounts = old('amount', []);
                                                                $detail_ids = old('free_sales_invoice_detail_id', []);
                                                            @endphp
                                                            @forelse ($product_ids as $key => $product_id)
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids to-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}"
                                                                                    {{ (string) $product_id === (string) $product->id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="hidden"
                                                                            name="free_sales_invoice_detail_id[]"
                                                                            value="{{ $detail_ids[$key] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="quantity[]"
                                                                            id="quantity" class="form-control"
                                                                            placeholder="Quantity"
                                                                            value="{{ $quantities[$key] ?? '1' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="price[]" id="price"
                                                                            class="form-control" placeholder="Price"
                                                                            value="{{ $prices[$key] ?? '0' }}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control text-center" id="amount"
                                                                            name="amount[]" readonly
                                                                            value="{{ $amounts[$key] ?? '0' }}">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs"
                                                                            id="remove_row"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_ids[]"
                                                                            class="form-control product_ids to-select">
                                                                            <option value="">Choose Product</option>
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="hidden"
                                                                            name="free_sales_invoice_detail_id[]"
                                                                            value="">
                                                                    </td>
                                                                    <td><input type="text" name="quantity[]"
                                                                            id="quantity" class="form-control"
                                                                            placeholder="Quantity" value="1"></td>
                                                                    <td><input type="text" name="price[]"
                                                                            id="price" class="form-control"
                                                                            placeholder="Price" value="0" readonly>
                                                                    </td>
                                                                    <td><input type="text"
                                                                            class="form-control text-center"
                                                                            id="amount" name="amount[]" readonly
                                                                            value="0"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs"
                                                                            id="remove_row"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" style="text-align: right;">
                                                                    Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_amount" name="total_amount" readonly
                                                                        value="{{!empty($prices) ? round(array_sum($prices)) : 0}}">
                                                                </td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td colspan="3" style="text-align: right;">Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total" name="total" readonly
                                                                        value="0">
                                                                </td>
                                                            </tr> --}}
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="remarks" class="form-control"
                                                        id="remarks" placeholder="Remarks"
                                                        value="{{ old('remarks', $freeSalesInvoice->remarks ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div
                                            class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <input type="hidden" name="status" id="status"
                                                value="{{ old('status', 'pending') }}">
                                            <input type="hidden" name="sales_type" value="free_sales">
                                            {{--                                         <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i>{{ isset($freeSalesInvoice) ? 'Update' : 'Temporary Save' }}
                                            </button> --}}
                                            @if (hasPermission('sales.sales-orders.product-free-sales-invoice.approve'))
                                                
                                                {{-- @dd() --}}
                                                @if($salesOrder->details->where('is_offers_product', 2)->count() > 0)
                                                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('sales.sales-orders.product-free-sales-invoice.view', $freeSalesInvoice->id) }}'">
                                                        <i class="fa fa-eye"></i> Show Invoice
                                                    </button>

                                                @else
                                                    <button type="submit" id="approve" class="btn btn-success">
                                                        <i class="fa fa-check"></i>
                                                        Save and bill
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @include('Sales::sales-order.opt-verification')
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
        window.pendingCall = [];
        $(document).ready(function() {
            //status update by submit
            $('#approve').click(function() {
                $("#status").val("approved");
                return true;
            });

            // Lock customer
            if ($('#customer_id')[0]?.tomselect) {
                $('#customer_id')[0].tomselect.lock();
            }

            const rowTemplate = $('#product_info_table tbody tr:first-child').clone();
            rowTemplate.find('input, select').val('');
            rowTemplate.find('.to-select option:selected').removeAttr('selected');
            rowTemplate.find('input[name="quantity[]"]').val('0');
            rowTemplate.find('input[name="price[]"], input[name="amount[]"]').val('0');
            rowTemplate.find('input[name="free_sales_invoice_detail_id[]"]').val('');

            // $("#product_info_table tbody tr:first-child").find('.to-select').each(function () {
            //     new TomSelect(this, {
            //         create: false,
            //         sortField: { field: 'text', direction: 'asc' }
            //     });
            // });

            $("#add_row").click(function () {
                const newRow = rowTemplate.clone();
                newRow.find('.to-select').each(function () {
                    new TomSelect(this, {
                        create: false,
                        sortField: { field: 'text', direction: 'asc' }
                    });
                    //clear options
                    this.tomselect.clear();
                });
                $("#product_info_table tbody").append(newRow);
            });

            // Calculate single row amount
            function calculateRow(row) {
                const qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
                const price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
                const amount = qty * price;
                row.find('input[name="amount[]"]').val(amount.toFixed());
            }

            // Recalculate total
            function calculateTotals() {
                let total = 0;
                $('#product_info_table tbody tr').each(function() {
                    calculateRow($(this));
                    total += parseFloat($(this).find('input[name="amount[]"]').val()) || 0;
                });
                $('#total_amount').val(total.toFixed());
                $('#total_amount').trigger('change');
            }

            // REMOVE ROW
            $(document).on('click', '#remove_row', function() {
                if ($('#product_info_table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    // Clear last row
                    const row = $(this).closest('tr');
                    row.find('.to-select')[0]?.tomselect?.clear();
                    row.find('input[name="quantity[]"]').val('1');
                    row.find('input[name="price[]"], input[name="amount[]"]').val('0');
                }
                calculateTotals();
            });

            // PRODUCT CHANGE — SAFE, no .trim() on undefined
            $(document).on('change', '.product_ids', function() {
                const select = $(this);
                const row = select.closest('tr');
                const productId = select.val() || '';

                // Reset price & amount
                row.find('input[name="price[]"]').val('0');
                row.find('input[name="amount[]"]').val('0');

                if (!productId) {
                    calculateTotals();
                    return;
                }

                // Prevent duplicate products
                let duplicate = false;
                $('.product_ids').not(select).each(function() {
                    if (this.value === productId) duplicate = true;
                });
                if (duplicate) {
                    toastr.warning('This product is already added.');
                    if(select[0].tomselect) select[0].tomselect.clear();
                    calculateTotals();
                    return;
                }

                // Fetch product MRP
                $.get('{{ route('purchase.get.product.list') }}', {
                        id: productId
                    })
                    .done(function(response) {
                        const product = response[0];
                        const mrp = product?.mrp ? parseFloat(product.mrp) : 0;
                        row.find('input[name="price[]"]').val(mrp.toFixed());
                    })
                    .fail(function() {
                        row.find('input[name="price[]"]').val('0.00');
                    })
                    .always(function() {
                        calculateTotals();
                    });
            });

            // QUANTITY CHANGE
            $(document).on('change', 'input[name="quantity[]"]', function() {
                calculateTotals();
            });

            // QUANTITY CHANGE
            $(document).on('change', '#total_amount', function() {
                let val = this.value.replace(/[^0-9.]/g, '');
                if (val === '' || parseFloat(val) <= 0) val = '1';
                this.value = val;
                // calculateTotals();
                const totalAmount = parseFloat($('#total_amount').val()) || 0;
                const giftAmount = parseFloat($('input[name="gift_amount"]').val()) || 0;

                if (totalAmount > giftAmount) {
                    $('#total_amount').addClass('opt-required');
                    $('#total_amount').closest('div').attr("title",
                        'Total amount cannot exceed the Gift Amount.');
                } else {
                    $('#total_amount').removeClass('opt-required');
                    $('#total_amount').closest('div').attr("title", '');
                }

                async function giftAmountOTP() {

                    if (totalAmount > giftAmount) {
                        // e.preventDefault();
                        toastr.error('Total amount cannot exceed the Gift Amount.');
                        const data = {
                            title: 'Gift Amount Exceeded',
                            request_value: totalAmount,
                        };
                        // captureProductInfoTable();
                        await updateOtpVerification(data);
                    } else {
                        await deleteOtpVerification('Gift Amount Exceeded');
                        $('#total_amount').removeClass('opt-required');
                        $('#total_amount').closest('div').attr("title", '');
                    }
                }

                giftAmountOTP();
            });

            // FORM VALIDATION: Total ≤ Gift Amount
            // $('#free-sales-invoice-form').on('submit', function(e) {

            // });

            // $(document).on('input', 'input[name="quantity[]"]', function() {

            // })

            // Initialize existing rows (edit mode)
            // $('.product_ids').trigger('change');

            // Initial calculation
            // calculateTotals();
        });
    </script>
    @stack('script')
@endsection
