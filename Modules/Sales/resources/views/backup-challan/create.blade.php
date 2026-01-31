@section('title', 'Backup/Challan Create')
@section('description', 'Backup/Challan Create')
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
                                        {{ trans('menu.create-backup-challan-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('sales.backup-challans.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-backup-challan-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.backup-challans.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">

                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="remaining_date">Remaining Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="remaining_date" class="form-control flatdate"
                                                id="remaining_date" placeholder="Remaining Date"
                                                value="{{ old('remaining_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="invoice_date" class="form-control flatdate"
                                                id="invoice_date" placeholder="Invoice Date"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="Backup" {{ old('type') == 'Backup' ? 'selected' : '' }}>Backup</option>
                                                <option value="Challan" {{ old('type') == 'Challan' ? 'selected' : '' }}>Challan</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select">
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null)
                                                            ({{ $customer->area->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_no">Invoice No</label>
                                            <input type="text" name="invoice_no" class="form-control" id="invoice_no"
                                                placeholder="Invoice No" value="{{ old('invoice_no') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <div class="">
                                                    <table class="table  table-bordered" id="product_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 25%">Product Name</th>
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
                                                            @if (old('product_ids'))
                                                                @foreach (old('product_ids') as $key => $product_id)
                                                                    <tr>
                                                                        <td>
                                                                            <select name="product_ids[]"
                                                                                class="form-control product_ids to-select">
                                                                                <option value="">Choose Product</option>
                                                                                @foreach ($products as $product)
                                                                                    <option value="{{ $product->id }}"
                                                                                        {{ $product_id == $product->id ? 'selected' : '' }}>
                                                                                        {{ $product->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="quantity[]" value="{{ old('quantity.'.$key) }}"
                                                                                id="quantity" class="form-control"
                                                                                placeholder="Quantity">
                                                                        </td>
                                                                        <td><input type="text" name="price[]" value="{{ old('price.'.$key) }}"
                                                                                id="price" class="form-control"
                                                                                placeholder="Price" readonly>   
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control text-center" value="{{ old('amount.'.$key) }}"
                                                                                id="amount" name="amount[]" readonly
                                                                                value="0">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-danger  btn-xs"
                                                                                id="remove_row" 
                                                                                onclick="removeRow(this)">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            @else
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
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" id="quantity"
                                                                        class="form-control" placeholder="Quantity">
                                                                </td>
                                                                <td><input type="text" name="price[]" id="price"
                                                                        class="form-control" placeholder="Price" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="amount" name="amount[]" readonly
                                                                        value="0">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger  btn-xs"
                                                                        id="remove_row" 
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                                
                                                            @endif
                                                          
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" style="text-align: right;">
                                                                    Total Amount
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="total_amount" name="total_amount" readonly
                                                                        value="{{ old('total_amount') }}">
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <input type="text" name="remarks" class="form-control"
                                                            id="remarks" placeholder="Remarks"
                                                            value="{{ old('remarks') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="border p-2">
                                                        <legend class="float-none w-auto p-2">
                                                            Shipment Information
                                                            <input type="checkbox" name="is_shipment" value="1" value="1" @if(old('is_shipment')) checked @endif
                                                                id="shipmentConfirm" tabindex="1015">
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                <select name="courier_id" id="courier_id"
                                                                    class="form-select tom-select" disabled>
                                                                    <option value="" selected>Select Courier</option>
                                                                    @foreach ($couriers as $courier)
                                                                        <option value="{{ $courier->id }}">
                                                                            {{ $courier->courier_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select name="area_id" id="area_id"
                                                                    class="form-select tom-select" disabled>
                                                                    <option value="0" selected>New Address</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="address"
                                                                        class="form-control" id="address"
                                                                        placeholder="Shipping Address" disabled value="{{ old('address') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_name"
                                                                        class="form-control" id="contact_person_name"
                                                                        placeholder="Contact Person Name" disabled value="{{ old('contact_person_name') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_number"
                                                                        class="form-control" id="contact_person_phone"
                                                                        placeholder="Contact Person Phone" disabled value="{{ old('contact_person_number') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div
                                                    class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
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
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            const customerSelect = $('#customer_id');
            const shipmentConfirmCheckbox = $('#shipmentConfirm');

            const shipmentFields = [
                $('#area_id'),
                $('#courier_id'),
                $('#address'),
                $('#contact_person_name'),
                $('#contact_person_phone')
            ];

            function toggleFields(fields, enable) {
                fields.forEach(field => {
                    if (enable) {
                        field.removeAttr('disabled');
                        if (field.prop('tomselect')) {
                            field.prop('tomselect').enable();
                        }
                    } else {
                        field.attr('disabled', true);
                        if (field.prop('tomselect')) {
                            field.prop('tomselect').disable();
                        }
                    }

                });
            }

            function handleCustomerSelection() {
                const customerSelected = customerSelect.val() !== "";
                shipmentConfirmCheckbox.prop('disabled', !customerSelected);

                if (!customerSelected) {
                    shipmentConfirmCheckbox.prop('checked', false);
                    toggleFields(shipmentFields, false);
                }
            }

            function handleShipmentConfirm() {
                toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
            }

            customerSelect.on('change', handleCustomerSelection);
            shipmentConfirmCheckbox.on('change', handleShipmentConfirm);
            // Initial state
            handleCustomerSelection();
            handleShipmentConfirm();

        });
    </script>




    <script>
        $(document).ready(function() {
            $('#customer_id').change(getCustomerSettings);

            getCustomerSettings();

            // Add event listener for TomSelect change event
            $('#area_id')[0].tomselect.on('change', function(value) {
                if (value === '0') {
                    clearFields();
                }
            });
        });

        function getCustomerSettings() {
            var id = $("#customer_id option:selected").val();
            if (id) {
                $.ajax({
                    url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                    success: function(data) {
                        console.log(data);

                        if (data && data.customers && data.customers.customer) {
                            var area = data.customers.customer.area;
                            var area_id = area ? area.id : "address";
                            var area_name = area ? area.area : "New Address";

                            if ($("#area_id").val() === "address") {
                                // Update the area_id select element with the new option
                                $("#area_id").html(`<option value="${area_id}">${area_name}</option>`);
                                $("#area_id")[0].tomselect.clear();
                                $("#area_id")[0].tomselect.addOption({
                                    value: area_id,
                                    text: area_name
                                });
                                $("#area_id")[0].tomselect.setValue(area_id);
                            }

                            // Update the fields if the area is not "New Address"
                            if (area_id !== 'address') {
                                $("#address").val(area_name);
                                $("#contact_person_name").val(data.customers.customer.company_name);
                                $("#contact_person_phone").val(data.customers.customer.phone);
                            } else {
                                clearFields();
                            }

                            if (data.customers.vat_status == 1) {
                                $('#vat_percentage').val(.05);
                            } else {
                                $('#vat_percentage').val(0);
                            }
                        }
                    }
                });
            }
        }

        function clearFields() {
            $("#address").val("");
            $("#contact_person_name").val("");
            $("#contact_person_phone").val("");
        }
    </script>

    <script type="text/javascript">
        const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
        rowTemplate.find('input').val('');
        rowTemplate.find('.to-select option:selected').removeAttr('selected');
        rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');


        $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
            new TomSelect(this, {});
        });

        function calculateRow(row) {
            const qty = parseFloat(row.find("#quantity").val()) || 0;
            const price = parseFloat(row.find("#price").val()) || 0;
            const amount = qty * price;
            row.find("#amount").val(amount);
            return {
                amount,
            };
        }

        function calculateTotals() {
            let totalAmount = 0;
            $("#product_info_table tbody tr").each(function() {
                const {
                    amount,
                } = calculateRow($(this));
                totalAmount += amount;
            });

            $("#total_amount").val(totalAmount);
        }

        $("#add_row").click(function() {
            const newRow = rowTemplate.clone();
            newRow.find('.to-select').each(function() {
                new TomSelect(this, {});
            });
            $("#product_info_table tbody").append(newRow);
        });

        $("#product_info_table tbody").on("keyup change", "#quantity", function() {
            calculateTotals();
        });

        $("#product_info_table").on("click", "#remove_row", function() {
                if($(this).closest('tbody').find('tr').length == 1){
                    $(this).closest('tbody tr').find('input').val('');
                    $(this).closest('tbody tr').find('select').val('');
                    $(this).closest('tbody tr').find('select').each(function() {
                        this.tomselect?.clear();
                    })
                }else{
                    $(this).closest('tr').remove();
                }
                calculateTotals();
        });

        calculateTotals();
    </script>

    <script>
        $(document).on('change', '.product_ids', async function() {
            await getProductPrice(this);
            calculateTotals();
        });

        var selectedProductIds = [];
        async function getProductPrice(selectElement) {
            var productId = selectElement.value;
            var priceInput = selectElement.closest('tr').querySelector('input[name="price[]"]');
            var qtyInput = selectElement.closest('tr').querySelector('input[name="quantity[]"]');

            var previousProductId = selectElement.getAttribute('data-previous-value');
            if (previousProductId && previousProductId !== productId) {
                selectedProductIds = selectedProductIds.filter(id => id !== previousProductId);
            }

            if (productId.trim() !== '') {
                // Check if the product is already selected
                if (selectedProductIds.includes(productId)) {
                    showToast('warning', 'You have already selected this product.');
                    selectElement.value = "";
                    selectElement.tomselect.clear();
                    priceInput.value = '';
                    qtyInput.value = '';
                    return;
                }

                try {
                    const response = await $.get('{{ route('purchase.get.product.list') }}?id=' + productId);
                    var product = response[0];
                    if (!product) {
                        showToast('error', 'Price not found.');
                        priceInput.value = '';
                        return;
                    }
                    priceInput.value = product.mrp;
                    qtyInput.value = 1;
                    selectedProductIds.push(productId);

                    // Update the previous value data attribute
                    selectElement.setAttribute('data-previous-value', productId);
                } catch (error) {
                    console.error(error);
                    showToast('error', 'An error occurred while fetching product details.');
                }
            } else {
                priceInput.value = '';
                qtyInput.value = '';
                selectElement.removeAttribute('data-previous-value');
            }
        }


        function showToast(type, message) {
            // Display toast message
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        }
    </script>


@endsection
