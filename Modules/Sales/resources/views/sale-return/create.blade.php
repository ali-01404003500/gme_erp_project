@section('title', 'Sale Return')
@section('description', 'Sale Return')
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
                                        {{ trans('menu.create-salary-setup-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.employee-salarys.index'))
                            <a href="{{ route('hrm.employee-salarys.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-salary-setup-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12 m-2">
                    <div class="card mb-50">
                        <div class="card-body">
                            <div class="row">
                                <form >
                                    <div class="col-md-6 mb-2">
                                        <label for="invoice_id" class="form-label">Invoice Id</label>
                                        <div class="input-group">
                                            <select class="form-select tom-select" id="invoice_id" name="invoice_id">
                                                <option value="">Select Invoice</option>
                                                @foreach ($sales as $sale)
                                                    <option value="{{ $sale->id }}" @if (old('invoice_id', request()->invoice_id) == $sale->id) selected @endif>{{ $sale->sales_order_id }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-secondary btn-sm" type="submit">Submit</button>
                                        </div>
                                    </div>
    
                                    <div class="col-md-4  mb-2">
                                        <label for="sales_date" class="form-label">Sales Date</label>
                                        <input type="text" class="form-control flatdate" id="sales_date" name="sales_date" disabled>
                                    </div>
                                </form>
                            </div>
                            @if(request()->has('invoice_id'))
                                <form action="{{ route('sales.sale-returns.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        
                                        <div class="col-md-4">
                                            <label for="return_date" class="form-label">Return Date</label>
                                            <input type="text" class="form-control flatdate" id="return_date" name="return_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customer_name" class="form-label">Customer Name</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customer_phone" class="form-label">Customer Phone</label>
                                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" placeholder="Customer Phone">
                                        </div>
                                
                                        

                                        <div class="col-md-12 my-4">
                                            <div class="table-responsive mb-3">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Product</th>
                                                            <th>Quantity</th>
                                                            <th>Unit Price</th>
                                                            <th>Unit Dis</th>
                                                            <th>Discount</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Add table rows dynamically here -->
                                                        @foreach ($sale->details as $detail)
                                                            <tr>
                                                                {{-- @dd($detail) --}}
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $detail->product->name }}</td>
                                                                <td><input type="number" class="form-control quantity" name="quantity[]" value="{{ numberFormat($detail->quantity) }}" min="1"></td>
                                                                <td><input type="number" class="form-control unit-price" name="unit_price[]" value="{{ numberFormat($detail->price) }}" step="0.01"></td>
                                                                <td><input type="number" class="form-control unit-discount" name="unit_discount[]" value="{{ $detail->unit_discount }}" step="0.01"></td>
                                                                <td><input type="number" class="form-control discount" name="discount[]" value="{{$detail->total_discount}}" step="0.01" readonly></td>
                                                                <td><input type="number" class="form-control amount" name="amount[]" value="{{ $detail->amount }}" step="0.01" readonly></td>
                                                                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-end">Main Invoice Discount</td>
                                                            <td><input type="number" class="form-control" name="main_discount" value="0.00" step="0.01"></td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-end">Total Amount</td>
                                                            <td colspan="3"><input type="text" class="form-control" name="total_amount" value="0.00" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-end">Net Amount</td>
                                                            <td colspan="3"><input type="text" class="form-control" name="net_amount" value="0.00" readonly></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                
                                        <div class="col-md-12 mb-4">
                                                <label for="remarks" class="form-label">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="3" maxlength="250" placeholder="Remarks Max(250 Characters)"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <h5>Payment Information</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="payment_mode" class="form-label">Payment Mode</label>
                                                    <select class="form-select form-control tom-select form-control-sm" id="payment_mode" name="payment_mode" required>
                                                        <option value="cash">Cash</option>
                                                        <!-- Add more payment options as needed -->
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="payment_date" class="form-label">Payment Date</label>
                                                    <input type="text" class="form-control flatdate" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="amount" class="form-label">Amount</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" id="amount" name="amount" step="0.01" required>
                                                        <button type="button" class="btn btn-primary btn-xs" id="add_payment">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <strong>Total DUE: <span id="total_due">0.00</span></strong>
                                        </div>
            
                                    </div>
                                </form>
                            @endif
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
    let rowCount = 0;

    // Function to add a new row
    function addRow(product, quantity, unitPrice, unitDiscount) {
        rowCount++;
        const newRow = `
            <tr>
                <td>${rowCount}</td>
                <td>${product}</td>
                <td><input type="number" class="form-control quantity" name="quantity[]" value="${quantity}" min="1"></td>
                <td><input type="number" class="form-control unit-price" name="unit_price[]" value="${unitPrice}" step="0.01"></td>
                <td><input type="number" class="form-control unit-discount" name="unit_discount[]" value="${unitDiscount}" step="0.01"></td>
                <td><input type="number" class="form-control discount" name="discount[]" value="0.00" step="0.01" readonly></td>
                <td><input type="number" class="form-control amount" name="amount[]" value="0.00" step="0.01" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
        `;
        $('tbody').append(newRow);
        calculateRowTotal(rowCount);
    }

    // Function to calculate row total
    function calculateRowTotal(rowIndex) {
        const row = $('tbody tr').eq(rowIndex - 1);
        const quantity = parseFloat(row.find('.quantity').val());
        const unitPrice = parseFloat(row.find('.unit-price').val());
        const unitDiscount = parseFloat(row.find('.unit-discount').val());
        
        const discount = quantity * unitDiscount;
        const amount = (quantity * unitPrice) - discount;
        
        row.find('.discount').val(discount.toFixed());
        row.find('.amount').val(amount.toFixed());
        
        calculateTotalAmount();
    }

    // Function to calculate total amount
    function calculateTotalAmount() {
        let totalAmount = 0;
        $('.amount').each(function() {
            totalAmount += parseFloat($(this).val());
        });
        
        const mainDiscount = parseFloat($('input[name="main_discount"]').val());
        const netAmount = totalAmount - mainDiscount;
        
        $('input[name="total_amount"]').val(totalAmount.toFixed());
        $('input[name="net_amount"]').val(netAmount.toFixed());
    }

    // Event listener for quantity, unit price, and unit discount changes
    $(document).on('input', '.quantity, .unit-price, .unit-discount', function() {
        const rowIndex = $(this).closest('tr').index() + 1;
        calculateRowTotal(rowIndex);
    });

    // Event listener for main discount changes
    $('input[name="main_discount"]').on('input', function() {
        calculateTotalAmount();
    });

    // Event listener for remove row button
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        rowCount--;
        $('tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
        calculateTotalAmount();
    });

    // Event listener for form submission
    // $('form').on('submit', function(e) {
    //     e.preventDefault();
    //     // Add your form submission logic here
    // });

    // Example: Add initial row
    // addRow('Sample Product', 1, 100.00, 0.00);
});

</script>
@endSection
