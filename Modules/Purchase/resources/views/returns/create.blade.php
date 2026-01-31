@section('title', 'Purchase Requisition Return')
@section('description', 'Purchase Requisition Return')
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
                                        {{ trans('Purchase Requisition Return') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('purchase.returns.index'))
                                <a href="{{ route('purchase.returns.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Purchase Requisition Return') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="invoice_id">Invoice Id<span class="text-danger">*</span></label>
                                        <select name="invoice_id" id="invoice_id" class="form-control tom-select">
                                            <option value="">Choose Invoice Id</option>
                                            @foreach ($invoices as $invoice)
                                                <option value="{{ $invoice->requisition_id }}"
                                                    {{ old('invoice_id', request()->invoice_id) == $invoice->requisition_id ? 'selected' : '' }}>
                                                    {{ optional($invoice->requisition)->requisition_no }}-{{ optional(optional($invoice->requisition)->supplier)->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="invoice_id"></label>
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i>
                                            Show</button>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="invoice_id"></label>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('purchase.returns.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <label for="supplier_id">Supplier Name<span class="text-danger">*</span></label>
                                        <input type="hidden" name="reference_invoice" id="reference_invoice" class="form-control" @if (request()->has('invoice_id')) value="{{ optional($products->first())->requisition->requisition_no ?? '' }}" @endif>
                                        <input type="hidden" name="supplier_id" id="supplier_id" class="form-control"  @if (request()->has('invoice_id')) value="{{ optional($products->first())->requisition->supplier_id ?? '' }}" @endif>
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control"
                                            @if (request()->has('invoice_id')) value="{{ optional($products->first())->requisition->supplier->company_name ?? '' }}" @endif
                                            readonly>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <label for="return_date">Return Date<span class="text-danger">*</span></label>
                                        <input type="text" name="return_date" class="form-control flatdate"
                                            id="return_date" placeholder="Return Date"
                                            value="{{ old('return_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4 mt-4"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Product Information</h3>
                                            <table class="table table-bordered" id="product_info_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 25%">Product Name</th>
                                                        <th style="width: 15%">Remaining Quantity</th>
                                                        <th style="width: 15%">Return Quantity</th>
                                                        <th style="width: 15%">Price</th>
                                                        <th style="width: 15%">Amount</th>
                                                        <th style="width: 8%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (old('product_ids'))
                                                        @foreach (old('product_ids') as $key => $product_id)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]"
                                                                        value="{{ $product_id }}">
                                                                    <input type="text" name="product_name[]"
                                                                        value="{{ old('product_name')[$key] }}"
                                                                        class="form-control" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="recived_quantity[]"
                                                                        id="recived_quantity"
                                                                        value="{{ old('recived_quantity')[$key] }}"
                                                                        class="form-control" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" id="quantity"
                                                                        value="{{ old('quantity')[$key] }}"
                                                                        class="form-control" placeholder="Quantity">
                                                                </td>
                                                                <td><input type="text" name="price[]" id="price"
                                                                        value="{{ old('price')[$key] }}"
                                                                        class="form-control" placeholder="Price"> </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        value="{{ old('amount')[$key] }}" id="amount"
                                                                        name="amount[]" readonly value="0">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="checks[]"
                                                                        class="form-check-input" id="check"
                                                                        value="1" @if (array_key_exists($key, old('checks') ?? []) && old('checks')[$key] == 1) checked @endif>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @elseif(request()->has('invoice_id'))
                                                        @foreach ($products as $key => $product)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]"
                                                                        value="{{ $product->product->id }}">
                                                                    <input type="text" name="product_name[]"
                                                                        id="product_name"
                                                                        value="{{ $product->product->name }}"
                                                                        class="form-control" placeholder="Product Name"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="recived_quantity[]"
                                                                        id="recived_quantity"
                                                                        value="{{ $product->stocks()->get()->sum('avaible_stock') }}"
                                                                        class="form-control"
                                                                        placeholder="Recived Quantity" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]"
                                                                        id="quantity" value="{{ $product->stocks()->get()->sum('avaible_stock') }}"
                                                                        class="form-control" placeholder="Quantity"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="price[]" id="price"
                                                                        value="{{ $product->price }}"
                                                                        class="form-control" placeholder="Price" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="amount" name="amount[]"
                                                                        value="{{ $product->amount }}" readonly
                                                                        value="0">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox"
                                                                        name="checks[{{ $key }}]" 
                                                                        class="form-check-input" id="check" @if($product->stocks()->get()->sum('avaible_stock') <= 0)  disabled @endif
                                                                        value="1">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" style="text-align: right;">Total Amount</td>
                                                        <td>
                                                            <input type="text" class="form-control text-center"
                                                                id="total_amount" name="total_amount" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;">Main Inv Discount</td>
                                                        <td>
                                                            <input type="hidden" name="requisition_receive_id" id="requisition_receive_id" value="{{ optional($receive)->id }}">
                                                            <input type="hidden" id="requisition_id" name="requisition_id" value="{{ optional($products->first())->requisition_id }}">
                                                            <input type="text" class="form-control text-center"
                                                                value="{{ optional(optional($products->first())->requisition)->discount }}"
                                                                id="main_inv_discount" name="main_inv_discount">
                                                        </td>
                                                        <td style="text-align: right;">Discount</td>
                                                        <td><input type="text" class="form-control text-center"
                                                                id="discount" name="discount"
                                                                value="{{ old('discount', 0) }}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="text-align: right;">Net Amount</td>
                                                        <td><input type="text" class="form-control text-center"
                                                                id="net_amount" name="net_amount" readonly
                                                                value="{{ old('net_amount') }}"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-12 pt-25">
                                            <div class="form-group">
                                                <textarea name="remarks" id="remarks" class="form-control w-100" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Information Section -->
                                <div class="col-md-12 my-4">
                                    <h4>Payment Information (Refund Details):</h4>
                                    <p class="text-muted small">If supplier refunds any amount, fill in the payment details below. Otherwise, the entire amount will be adjusted in Accounts Payable.</p>
                                </div>
                                <div class="col-md-12">
                                    @include('Account::payments.make-payments.payments-details', ['payments' => []])
                                </div>

                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
        $(document).ready(function() {
            $('.form-check-input').on('change', function() {
                var checkbox = $(this);
                var quantityInput = checkbox.closest('tr').find('#quantity');
                var receivedQuantityInput = checkbox.closest('tr').find('#recived_quantity');

                if (checkbox.is(':checked')) {
                    quantityInput.prop('readonly', false);
                    quantityInput.on('keyup', function() {
                        var quantityValue = parseInt(quantityInput.val());
                        var receivedQuantityValue = parseInt(receivedQuantityInput.val());

                        if (quantityValue > receivedQuantityValue) {
                            toastr.error('Quantity cannot be greater than received quantity');
                            quantityInput.val(receivedQuantityInput.val());
                        }
                    });
                } else {
                    quantityInput.prop('readonly', true);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function calculateRowAmount(row) {
                const qty = parseFloat(row.find("#quantity").val()) || 0;
                const price = parseFloat(row.find("#price").val()) || 0;
                const amount = qty * price;
                row.find("#amount").val(amount);
            }
    
            function calculateTotalAmount() {
                let totalAmount = 0;
                $("#product_info_table tbody tr").each(function() {
                    if ($(this).find('.form-check-input').is(':checked')) {
                        const amount = parseFloat($(this).find("#amount").val()) || 0;
                        totalAmount += amount;
                    }
                });
                $("#total_amount").val(totalAmount);
                calculateNetAmount();
            }
    
            function calculateNetAmount() {
                const totalAmount = parseFloat($("#total_amount").val()) || 0;
                const discount = parseFloat($("#discount").val()) || 0;
                const netAmount = totalAmount - discount;
                $("#net_amount").val(netAmount);
                updatePayable(netAmount);
            }
    
            $("#product_info_table tbody tr").each(function() {
                calculateRowAmount($(this));
            });
            calculateTotalAmount();
    
            $(document).on('change', '.form-check-input', function() {
                calculateTotalAmount();
            });
    
            $(document).on('keyup', '#quantity', function() {
                const row = $(this).closest('tr');
                calculateRowAmount(row);
                calculateTotalAmount();
            });
    
            $("#discount").on("keyup", function() {
                calculateNetAmount();
            });
        });
    </script>
    @stack('script')
@endsection