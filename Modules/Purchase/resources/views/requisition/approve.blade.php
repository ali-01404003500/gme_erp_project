@section('title', 'Purchase Requisition Approval')
@section('description', 'Purchase Requisition Approval')
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
                                        {{ trans('Requisition Approval') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Requisition Approval') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.requisitions.approveStore', $requisition->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row mb-4">

                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="supplier_id">Supplier Name  :</label>
                                            <input type="hidden" name="supplier_id" value="{{ $requisition->supplier_id }}">
                                            {{ @$requisition->supplier->company_name }}
                                        </div>
                                    </div>
                                    @if ($requisition->customer)
                                        <div class="col-md-6 mt-4">
                                            <div class="form-group">
                                                <label for="customer_id">Purchase For : </label>
                                                <input type="hidden" name="customer_id" value="{{ $requisition->customer_id }}">
                                                {{ optional($requisition->customer)->company_name }}
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warehouse_id">Invoice To : </label>
                                            <input type="hidden" name="branch_id" value="{{ $requisition->branch_id }}">
                                            {{ $requisition->warehouse->name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date : </label>
                                            <input type="hidden" name="invoice_date" value="{{ $requisition->invoice_date }}">
                                            {{ date('d F, Y', strtotime($requisition->invoice_date)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description :</label>
                                            <input type="hidden" name="description" value="{{ $requisition->description }}">
                                            {{ $requisition->description }}
                                        </div>
                                    </div>


                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Product Information</h3>
                                            <table class="table table-bordered" id="product_info_table">
                                                <thead>
                                                  <tr>
                                                    <th style="width: 25%">Product Name</th>
                                                    <th style="width: 15%">Quantity</th>
                                                    <th style="width: 15%">Price</th>
                                                    <th style="width: 15%">S. Price</th>
                                                    <th style="width: 15%">Amount</th>
                                                    <th style="width: 8%; text-align: right;">
                                                      <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                        <i class="fa fa-plus"></i> Add
                                                      </button>
                                                    </th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  @foreach ($requisition->requisitionDetails as $key => $item)
                                                    <tr>
                                                      <td>
                                                        <select name="product_ids[]" class="form-control product_ids tom-select" onchange="getProductPrice(this)">
                                                          <option value="">Choose Product</option>
                                                          @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                              {{ $product->name }}
                                                            </option>
                                                          @endforeach
                                                        </select>
                                                      </td>
                                                      <td>
                                                        <input type="text" name="quantity[]" value="{{ $item->quantity }}" class="form-control quantity" placeholder="Quantity">
                                                      </td>
                                                      <td>
                                                        <input type="text" name="price[]" value="{{ $item->price }}" class="form-control price" placeholder="Price">
                                                      </td>
                                                      <td>
                                                        <input type="text" name="sales_price[]" value="{{ $item->sales_price }}" class="form-control sales_price" placeholder="Sales Price">
                                                      </td>
                                                      <td>
                                                        <input type="text" name="amount[]" class="form-control text-center amount" readonly value="{{ old('amount', $item->amount) }}">
                                                      </td>
                                                      <td style="text-align: right;">
                                                        <button type="button" class="btn btn-danger btn-xs" id="remove_row"
                                                                    onclick="removeRow(this)">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                      </td>
                                                    </tr>
                                                  @endforeach
                                                </tbody>
                                                <tfoot>
                                                  <tr>
                                                    <td colspan="4" style="text-align: right;">Total Amount</td>
                                                    <td>
                                                      <input type="text" class="form-control text-center" id="total_amount" name="total_amount" readonly value="{{ old('total_amount', $requisition->total_amount) }}">
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" style="text-align: right;">Discount</td>
                                                    <td>
                                                      <input type="text" class="form-control text-center" id="discount" name="discount" value="{{ old('discount', $requisition->discount) }}">
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" style="text-align: right;">Net Amount</td>
                                                    <td>
                                                      <input type="text" class="form-control text-center" id="net_amount" name="net_amount" readonly value="{{ old('net_amount', $requisition->net_amount) }}">
                                                    </td>
                                                  </tr>
                                                </tfoot>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" name="status" value="1"  class="btn btn-success btn-sm">Approve</button>
                                        <button type="submit" name="status" value="2" class="btn btn-danger btn-sm">Reject</button>
                                        <a href="{{ route('purchase.requisitions.index') }}" class="btn btn-primary btn-sm">Back</a>
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
    const initialRow = $("#product_info_table tbody tr:first-child").clone();
    initialRow.find('input').val(''); // Clear input fields
    initialRow.find('.tom-select option:selected').removeAttr('selected');
    initialRow.find('#remove_row').removeClass('disabled').removeAttr('disabled'); // Enable remove button

    $("#add_row").click(function() {
        const newRow = initialRow.clone();
        newRow.find('.tom-select').each(function() {
            new TomSelect(this, {}); // Initialize tom-select for new rows
        });
        $("#product_info_table tbody").append(newRow);
    });

    $(document).ready(function() {
        // Event delegation for dynamic elements
        $("#product_info_table tbody").on("keyup change", ".quantity, .price, .product_ids", function() {
            const row = $(this).closest('tr');
            calculateTotalPrice(row);
            calculateTotalAmount();
            calculateNetAmount();
        });

        $("#product_info_table tbody").on("click", "#remove_row", function() {
            $(this).closest('tr').remove();
            calculateTotalAmount();
            calculateNetAmount();
        });

        // Remove row function
        function removeRow(button) {
            $(button).closest('tr').remove();
            calculateTotalAmount();
            calculateNetAmount();
        }

        // Calculate total price for a row
        function calculateTotalPrice(row) {
            const qty = parseFloat(row.find(".quantity").val()) || 0;
            const price = parseFloat(row.find(".price").val()) || 0;
            const total = qty * price;
            row.find(".amount").val(total.toFixed());
        }

        // Calculate total amount for all rows
        function calculateTotalAmount() {
            let totalAmount = 0;
            $("#product_info_table tbody tr").each(function() {
                const amount = parseFloat($(this).find(".amount").val()) || 0;
                totalAmount += amount;
            });
            $("#total_amount").val(totalAmount.toFixed());
        }

        // Calculate net amount after discount
        function calculateNetAmount() {
        const totalAmount = parseFloat($("#total_amount").val()) || 0;
        let discount = parseFloat($("#discount").val()) || 0;
        if (discount > totalAmount) {
            discount = totalAmount;
            $("#discount").val(discount);
        }
        const netAmount = totalAmount - discount;
        $("#net_amount").val(netAmount);
    }

        // Fetch product price and update fields
        async function getProductPrice(selectElement) {
    const productId = selectElement.value.trim();
    const row = selectElement.closest('tr');
    const priceInput = row.querySelector('input[name="price[]"]');
    const salespriceInput = row.querySelector('input[name="sales_price[]"]');
    const qtyInput = row.querySelector('input[name="quantity[]"]');

    // If the product was already loaded in this row, do nothing.
    if (row.dataset.loadedProductId && row.dataset.loadedProductId === productId) {
        return;
    }
    
    // If an old price already exists, skip the ajax call.
    if (priceInput.value.trim() !== "") {
        return;
    }

    if (productId !== '') {
        // Count how many times this product is selected across rows.
        let count = 0;
        $(".product_ids").each(function() {
            if ($(this).val() === productId) count++;
        });
        if (count > 1) {
            // Warn user but do not remove the already loaded data.
            showToast('warning', 'You have already selected this product.');
            // Clear the current select field.
            $(selectElement).val('');
            if (selectElement.tomselect) {
                selectElement.tomselect.clear();
            }
            // Clear any loaded data for this row.
            delete row.dataset.loadedProductId;
            priceInput.value = '';
            salespriceInput.value = '';
            qtyInput.value = '';
            return;
        }

        try {
            const response = await $.ajax({
                url: '{{ route('purchase.get.product.list') }}',
                method: 'GET',
                data: { id: productId }
            });
            const product = response[0];
            if (!product) {
                showToast('error', 'Price not found.');
                priceInput.value = '';
                salespriceInput.value = '';
                return;
            }

            priceInput.value = product.mrp;
            salespriceInput.value = product.mrp;
            qtyInput.value = 1;

            // Mark this row as having loaded this product.
            row.dataset.loadedProductId = productId;
        } catch (error) {
            console.error(error);
            priceInput.value = '';
            salespriceInput.value = '';
            showToast('error', 'An error occurred while fetching product details.');
        }
    } else {
        // If the select is cleared, clear the price fields and remove the loaded flag.
        priceInput.value = '';
        salespriceInput.value = '';
        delete row.dataset.loadedProductId;
    }
}


        // Display toast messages
        function showToast(type, message) {
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        }

        // Event delegation for product selection change
        $(document).on('change', '.product_ids', async function() {
            await getProductPrice(this);
            calculateTotalPrice($(this).closest('tr'));
            calculateTotalAmount();
            calculateNetAmount();
        });

        // Event delegation for discount change
        $("#discount").on("keyup", function() {
            calculateNetAmount();
        });

        // Initial calculations for existing rows on page load
        $("#product_info_table tbody tr").each(function() {
            const row = $(this);
            calculateTotalPrice(row);
            const productId = row.find('.product_ids').val();
            if (productId) {
                getProductPrice(row.find('.product_ids')[0]);
            }
        });

        calculateTotalAmount();
        calculateNetAmount();
    });
</script>



@endsection
