@section('title', 'Backup/Challan Edit')
@section('description', 'Backup/Challan Edit')
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
                                        {{ trans('menu.update-backup-challan-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex">
                            @if (hasPermission('sales.backup-challans.create'))
                            <a href="{{ route('sales.backup-challans.create') }}" class="btn btn-sm btn-primary ">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ route('sales.backup-challans.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm ms-2"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-backup-challan-menu-title') }} ({{$backupChallan->invoice_id}})</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.backup-challans.update', $backupChallan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method("PUT")
                                @csrf

                                <div class="row mb-4">

                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="remaining_date">Remaining Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="remaining_date" class="form-control flatdate"
                                                id="remaining_date" placeholder="Remaining Date"
                                                value="{{ old('remaining_date', $backupChallan->remaining_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="invoice_date" class="form-control flatdate"
                                                id="invoice_date" placeholder="Invoice Date"
                                                value="{{ old('invoice_date', $backupChallan->invoice_date) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="Backup" {{ old('type', $backupChallan->type) == 'Backup' ? 'selected' : '' }}>Backup</option>
                                                <option value="Challan" {{ old('type', $backupChallan->type) == 'Challan' ? 'selected' : '' }}>Challan</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                <option value="{{ $backupChallan->customer->id }}" selected>
                                                    {{ $backupChallan->customer->company_name }} - {{ $backupChallan->customer->area->area }}
                                                    
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_no">Invoice No</label>
                                            <input type="text" name="invoice_no" class="form-control" id="invoice_no"
                                                placeholder="Invoice No" value="{{ old('invoice_no', $backupChallan->invoice_no) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <div class="table-responsive-md">
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
                                                        @if ($backupChallan->backupChallanDetails->count() > 0)
                                                            @foreach ($backupChallan->backupChallanDetails as $key => $backupChallanDetail)
                                                            <tr>
                                                                <td>
                                                                    <select name="product_ids[]"
                                                                        class="form-control product_ids to-select">
                                                                        <option value="{{ $backupChallanDetail->product_id}}" selected>
                                                                            {{ $backupChallanDetail->product->name }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]"
                                                                        id="quantity" class="form-control"
                                                                        placeholder="Quantity"
                                                                        value="{{ $backupChallanDetail->quantity }}">
                                                                </td>
                                                                <td><input type="text" name="price[]"
                                                                        id="price" class="form-control"
                                                                        placeholder="Price" readonly
                                                                        value="{{ $backupChallanDetail->price }}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control text-center"
                                                                        id="amount" name="amount[]"
                                                                        readonly
                                                                        value="{{ $backupChallanDetail->amount }}">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-xs"
                                                                        id="remove_row" 
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
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
                                                                        class="btn btn-danger btn-xs"
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
                                                                        value="{{ old('total_amount', $backupChallan->total_amount) }}">
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
                                                            value="{{ old('remarks', $backupChallan->remarks) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="border p-2">
                                                        <legend class="float-none w-auto p-2">
                                                            Shipment Information
                                                            <input type="hidden" name="is_shipment" value="0">
                                                            <input type="checkbox" name="is_shipment" value="1" @if($backupChallan->is_shipment == 1) checked @endif id="shipmentConfirm" tabindex="1015">
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <select name="courier_id" id="courier_id" class="form-select tom-select" disabled>
                                                                        <option value="" selected>Select Courier</option>
                                                                        @foreach ($couriers as $courier)
                                                                            <option value="{{ $courier->id }}" {{ $courier->id == optional($backupChallan->backupChallanShipments->first())->courier_id ? 'selected' : '' }}>
                                                                                {{ $courier->courier_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select name="area_id" id="area_id" class="form-select tom-select" disabled>
                                                                    <option value="0" selected>New Address</option>
                                                                    @foreach ($areas as $area)
                                                                        <option value="{{ $area->id }}" {{ optional($backupChallan->backupChallanShipments->first())->area_id == $area->id ? 'selected' : '' }}>
                                                                            {{ $area->area }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="address" class="form-control" id="address" value="{{ old('address', optional($backupChallan->backupChallanShipments->first())->address ?? '') }}" placeholder="Shipping Address" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_name" value="{{ old('contact_person_name', optional($backupChallan->backupChallanShipments->first())->contact_person_name ?? '') }}" class="form-control" id="contact_person_name" placeholder="Contact Person Name" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <input type="text" name="contact_person_number" value="{{ old('contact_person_number', optional($backupChallan->backupChallanShipments->first())->contact_person_number ?? '') }}" class="form-control" id="contact_person_phone" placeholder="Contact Person Phone" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div
                                                    class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
                    if (field.hasClass('tom-select')) {
                        field[0].tomselect.enable();
                    }
                } else {
                    field.attr('disabled', true);
                    if (field.hasClass('tom-select')) {
                        field[0].tomselect.disable();
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

        handleCustomerSelection();
        handleShipmentConfirm();
    });
</script>



<script>
    $(document).ready(function() {
        $('#customer_id').change(getCustomerSettings);

        $('#area_id').on('change', function() {
            var value = $(this).val(); 
            if (value === '0') {
                clearFields();
            } else {
                getCustomerSettings(); 
            }
        });  
    });

    function getCustomerSettings() {
        var id = $("#customer_id").val(); 
        if (id) {
            $.ajax({
                url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                success: function(data) {
                    console.log(data);

                    if (data && data.customers && data.customers.customer) {
                        var area = data.customers.customer.area;
                        var area_id = area ? area.id : "0";
                        var area_name = area ? area.area : "New Address";

                        // Update the fields if the area is not "New Address"
                        if (area_id !== '0') {
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
        function clearRow(row){
                $(row).find('input').val('');
                $(row).find('select').val('');
                $(row).find('select').each(function() {
                    this.tomselect?.clear();
                })
        }
        const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
        rowTemplate.find('input').val('');
        rowTemplate.find('.to-select option:selected').removeAttr('selected');
        rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');
        rowTemplate.find('.product_ids option').remove(); 

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
 

        $("#add_row").click(function () {
            const newRow = rowTemplate.clone();
            
            // Reset discount_type field in new row
            newRow.find('.discount_type_input').val('');
            $("#product_info_table tbody").append(newRow);

            prouctAutocompleteLoad(newRow);
        });

        $("#product_info_table tbody").on("keyup change", "#quantity", function() {
            calculateTotals();
        });

        $("#product_info_table").on("click", "#remove_row", function() {
            if($(this).closest('tbody').find('tr').length == 1){
                clearRow($(this).closest('tbody tr'));
            }else{
                $(this).closest('tr').remove();
            }
            calculateTotals();
        });

        calculateTotals();

        function prouctAutocompleteLoad(row){
            const p = $(row).find(".product_ids");
            const productSelect = new TomSelect(p[0], {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('product_ids'))
                productSelect.addOption({
                    id: "{{ request('product_ids') }}",
                    text: "{{ request('product_ids') }}"
                });
                productSelect.setValue("{{ request('product_ids') }}");
            @endif
        }
        
    </script>

    <script>
        $(document).on('change', '.product_ids', async function() {
            const product_id = $(this).val();
            if(product_id == "") {
                return false;
            }
            if(product_id !="" && $(".product_ids [value='" + product_id + "']:selected").length > 1) {
                //warning and clear 
                clearRow($(this).closest('tr'));
                toastr.warning('This Product is already selected');
                return false;
            }
            else
            {
                $("#add_row").click();
            }
            
            console.log('product changed');
            $(this).closest('tr').find('#quantity').val(1);
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
