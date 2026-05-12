{{-- @dd( $salesRequisition->shipment)/ --}}
@section('title', 'Sales Requisition Edit')
@section('description', 'Sales Requisition Edit')
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
                                    <li class="breadcrumb-item">
                                        <a href="#"><i class="las la-home"></i>Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.update-sales-requisition-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('sales.sales-requisitions.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>

                            <a href="{{ route('sales.sales-requisitions.create') }}" class="btn px-20 btn-primary btn-sm"
                                style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-sales-requisition-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('sales.sales-requisitions.update', $salesRequisition->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Requisition Date</label>
                                            <input type="date" name="invoice_date" class="form-control flatdate" id="invoice_date"
                                                placeholder="Invoice Date"
                                                value="{{ old('invoice_date', date('Y-m-d', strtotime($salesRequisition->invoice_date))) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="deliver_date">Deliver Date</label>
                                            <input type="text" name="delivery_date" class="form-control datePicker"
                                                id="deliver_date" placeholder="Deliver Date"
                                                value="{{ old('delivery_date', date('Y-m-d', strtotime($salesRequisition->delivery_date))) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                <option value="{{ $salesRequisition->customer_id }}" selected>
                                                {{ $salesRequisition->customer->company_name }} - {{ optional($salesRequisition->customer->area)->area }} 
                                                </option>
                                            </select> 

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_id">Company Address</label>
                                            <input type="text" name="address" class="form-control" id="company_address"
                                                value="{{ old('address', optional($salesRequisition->customer->area)->area) }}"
                                                placeholder="Shipping Address" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_phone">Company Phone</label>
                                            <input type="text" name="contact_person_phone"
                                                value="{{ old('contact_person_phone', $salesRequisition->customer->phone) }}"
                                                class="form-control" id="person_phone" placeholder="Contact Person Phone"
                                                disabled>
                                        </div>
                                    </div>
                                </div> <!-- Added closing div for row mb-4 -->

                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Product Information</h3>
                                            {{-- <div class="row col-md-6">
                                                <div class="col-md-2">
                                                    <div class="checkbox-theme-default custom-checkbox m-4">
                                                        <input class="checkbox" name="partial" value="1"
                                                            type="checkbox" id="partial">
                                                        <label for="partial">
                                                            <span class="checkbox-text">
                                                                Partial
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="checkbox-theme-default custom-checkbox m-4">
                                                        <input class="checkbox" name="is_offer" value="1"
                                                            type="checkbox" id="is_offer">
                                                        <label for="is_offer">
                                                            <span class="checkbox-text">
                                                                Include Offer Product
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Product Name</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            <th style="width: 15%">Price</th>
                                                            <th style="width: 15%">Unit Discount</th>
                                                            <th style="width: 15%">Total Discount</th>
                                                            <th style="width: 15%">Amount</th>
                                                            <th style="width: 8%" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm"
                                                                    id="add_row">
                                                                    <i class="fa fa-plus"></i> Add</button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salesRequisition->salesRequisitionDetails as $key => $value)
                                                            <tr>
                                                                <td>
                                                                    <select name="product_ids[]"
                                                                        class="form-control product_ids to-select">
                                                                        <option value="{{ $value->product_id }}" selected>
                                                                                {{ $value->product->name }}</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]"
                                                                        id="quantity" class="form-control"
                                                                        placeholder="Quantity"
                                                                        value="{{ numberFormat($value->quantity) }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="price[]"
                                                                        id="price"
                                                                        value="{{ numberFormat($value->price) }}"
                                                                        class="form-control" placeholder="Price"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="unit_discount[]"
                                                                        value="{{ numberFormat($value->unit_discount) }}"
                                                                        id="unit_discount" class="form-control"
                                                                        placeholder="Unit Discount"></td>
                                                                <td>
                                                                    <input type="text" name="total_discount[]"
                                                                        value="{{ numberFormat($value->total_discount) }}"
                                                                        id="total_discount" class="form-control"
                                                                        placeholder="Total Discount" readonly></td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control text-center"
                                                                        value="{{ numberFormat($value->amount) }}"
                                                                        id="amount" name="amount[]" readonly
                                                                        value="0">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-xs" id="remove_row"
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">
                                                                Total Amount
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center"
                                                                    id="total_amount" name="total_amount" readonly
                                                                    value="{{ old('total_amount', numberFormat($salesRequisition->total_amount)) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Discount
                                                            </td>
                                                            <td><input type="text" class="form-control text-center"
                                                                    id="discount" name="discount"
                                                                    value="{{ old('discount', numberFormat($salesRequisition->discount)) }}"
                                                                    readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Total Amount
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center"
                                                                    id="total" name="total" readonly
                                                                    value="{{ old('total', numberFormat($salesRequisition->total) ?? 0) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">VAT(5)%</td>
                                                            {{-- @dd($salesRequisition) --}}
                                                            <td>
                                                                <input type="hidden" id="vat_percentage"
                                                                    value="">
                                                                <input type="text" class="form-control text-center"
                                                                    id="vat" name="vat" readonly
                                                                    value="{{ $salesRequisition->vat ?? old('vat', numberFormat($salesRequisition->vat)) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Net Amount
                                                            </td>
                                                            <td><input type="text" class="form-control text-center"
                                                                    id="net_amount" name="net_amount" readonly
                                                                    value="{{ old('net_amount', numberFormat($salesRequisition->net_amount)) }}">
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
                                                        value="{{ old('remarks', $salesRequisition->remarks) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row mt-3">
                                                <div class="col-md-12 mt-3 mb-3">
                                                    <h5 class="text-uppercase">Payment Information</h5>
                                                </div>
                                                <div class="col-md-12">
                                                    @include('Services::service-my-task.paymets', [
                                                        'payments' => $salesRequisition?->payments ?? null,
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="border p-2">
                                                    <legend class="float-none w-auto p-2">
                                                        Shipment Information
                                                        <input type="checkbox" name="is_shipment" value='1'
                                                            @if ($salesRequisition->is_shipment == 1) checked @endif
                                                            id="shipmentConfirm" tabindex="1015">
                                                    </legend>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="area_id" id="area_id"
                                                                class="form-select tom-select" disabled>
                                                                <option value="address" selected>New Address</option>
                                                                @foreach ($areas as $area)
                                                                    <option value="{{ $area->id }}"
                                                                        {{ optional($salesRequisition->shipment)->area_id == $area->id ? 'selected' : '' }}>
                                                                        {{ $area->area }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <input type="text" name="address"
                                                                    class="form-control" id="address"
                                                                    placeholder="Shipping Address"
                                                                    value="{{ old('address', optional($salesRequisition->shipment)->address) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <input type="text" name="contact_person_name"
                                                                    class="form-control" id="contact_person_name"
                                                                    placeholder="Contact Person Name"
                                                                    value="{{ old('contact_person_name', optional($salesRequisition->shipment)->contact_person_name) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <input type="text" name="contact_person_number"
                                                                    class="form-control" id="contact_person_phone"
                                                                    placeholder="Contact Person Phone"
                                                                    value="{{ old('contact_person_number', optional($salesRequisition->shipment)->contact_person_number) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="my-2">
                                                    <div class="form-group">
                                                        <label for="delivery_date">Delivery Date</label>
                                                        <input type="text" name="delivery_date"
                                                            class="form-control flatdate" id="delivery_date"
                                                            placeholder="Delivery Date"
                                                            value="{{ old('delivery_date', date('Y-m-d')) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <fieldset class="border p-2">
                                                    <legend class="float-none w-auto p-2">
                                                        Courier Information
                                                        {{-- @dd($salesRequisition->shipment) --}}
                                                        <input type="checkbox" name="is_courier" value='1'
                                                            @if ($salesRequisition->shipment?->is_courier == 1) checked @endif
                                                            id="courierConfirm" tabindex="1019">
                                                    </legend>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <select name="courier_id" id="courier_id"
                                                                class="form-select tom-select" disabled>
                                                                <option value="">Search Courier Name</option>
                                                                @foreach ($couriers as $courier)
                                                                    <option value="{{ $courier->id }}"
                                                                        {{ $salesRequisition->shipment?->courier_id == $courier->id ? 'selected' : '' }}>
                                                                        {{ $courier->courier_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="input-group align-items-center">
                                                                <label for="additional_amount"
                                                                    class="input-group-text">Additional Amount</label>
                                                                <input type="text" name="additional_amount"
                                                                    id="additional_amount" class="form-control"
                                                                    value="{{ $salesRequisition->shipment?->additional_amount }}"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="condition"
                                                                class="form-label">Condition</label>
                                                            <input type="checkbox" name="condition" id="condition"
                                                                tabindex="1020"
                                                                {{ $salesRequisition->shipment?->condition ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-danger">(Previous Due Adjust With Condition)</p>
                                                        <div>
                                                            <textarea name="condition_remarks" id="condition_remarks" class="form-control" placeholder="Remarks"
                                                                {{ $salesRequisition->shipment?->condition ? '' : 'disabled' }}>{{ $salesRequisition->shipment?->condition_remarks }}</textarea>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            @if ( hasPermission('sales.sales-requisitions.verify') &&
                                                    request()->input('edit_for') == 'verify' &&
                                                    $salesRequisition->status == 'pending' )
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="verify_remark">Verify Remark:</label>
                                                        <textarea name="verify_remark" id="verify_remark" class="form-control" placeholder="Verify Remark"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mt-4">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1" name="is_urgent_approval" id="is_urgent_approval"
                                                                {{ $salesRequisition->is_urgent_approval ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_urgent_approval">
                                                                Urgent Approval
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3  mt-4">
                                                    <div class="form-group">
                                                        <label for="created_by">Prepared By :</label>
                                                        <span class="badge bg-primary badge-round">{{ $salesRequisition->createdBy->name }}</span>
                                                    </div>
                                                </div>
                                
                                            @endif

                                            @if(hasPermission('sales.sales-requisitions.approve') && request()->input('edit_for') == 'approve' && $salesRequisition->status == 'verified')
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="approve_remark">Approve Remark:</label>
                                                        <textarea name="approve_remark" id="approve_remark" class="form-control" placeholder="Approve Remark"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-3  mt-4">
                                                    <div class="form-group">
                                                        <label for="created_by">Prepared By :</label>
                                                        <span class="badge bg-primary badge-round">{{ $salesRequisition->createdBy->name }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3  mt-4">
                                                    <div class="form-group">
                                                        <label for="verified_by">Verify By :</label>
                                                        <span class="badge bg-success badge-round">{{ $salesRequisition->updatedBy->name ?? '-' }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6  mt-4">
                                                    <div class="form-group">
                                                        <label for="verify_remark">Verify Remark:</label>
                                                    <p>
                                                       {{ $salesRequisition->verify_remark }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($salesRequisition->status == 'approved' )
                                                <div class="col-md-6  mt-4">
                                                    <div class="form-group">
                                                        <label for="approve_remark">Approve Remark:</label>
                                                        <p>
                                                            {{ $salesRequisition->approve_remark??$salesRequisition->verify_remark }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($salesRequisition->status == 'pending' && $salesRequisition->approve_remark)
                                                <div class="col-md-6  mt-4">
                                                    <div class="form-group">
                                                        <label for="approve_remark">Reject Remark:</label>
                                                        <p>
                                                            {{ $salesRequisition->approve_remark??"" }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="col-md-12">
                                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <input type="hidden" name="status" id="status" value="pending">
                                                @if(!request()->has('edit_for'))
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-save"></i>
                                                        Update
                                                    </button>
                                                @endif
                                                @if(hasPermission('sales.sales-requisitions.verify') &&
                                                        request()->input('edit_for') == 'verify' &&
                                                        $salesRequisition->status == 'pending')
                                                    <button type="submit" id="approve" class="btn btn-success">
                                                        <i class="fa fa-check"></i>
                                                        Accept
                                                    </button>

                                                    @if(hasPermission('sales.sales-requisitions.forward_to_approve') &&
                                                        request()->input('edit_for') == 'verify' &&
                                                        $salesRequisition->status == 'pending')
                                                        <button type="submit" id="forward_to_approve" class="btn btn-warning">
                                                            <i class="fa fa-forward"></i>
                                                            Forward to Approve
                                                        </button>
                                                    @endif
                                                    
                                                @endif
                                                @if(request()->has('edit_for'))
                                                    <button type="submit" id="reject" class="btn btn-danger">
                                                        <i class="fa fa-times"></i>
                                                        Reject
                                                    </button>
                                                @endif
                                                @if(hasPermission('sales.sales-orders.approve') &&
                                                        request()->input('edit_for') == 'approve' &&
                                                        $salesRequisition->status != 'approved')
                                                    <button type="submit" id="approve" class="btn btn-success">
                                                        <i class="fa fa-check"></i>
                                                        Update and Approve
                                                    </button>
                                                @endif
                                                @if(hasPermission('sales.sales-requisitions.approve') &&
                                                        $salesRequisition->status == 'approved')
                                                    <a href="{{ route('sales.sales-requisitions.save-to-sales-order', [$salesRequisition->id]) }}" id="send_to_sales_order" class="btn btn-outline-primary">
                                                        <i class="fa fa-file-invoice"></i>
                                                        Send to Sales Order
                                                    </a>
                                                @endif
                                            </div>
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
            $('#approve').click(function() {
                $("#status").val("approved");
                return true;
            });

            $('#forward_to_approve').click(function() {
                $("#status").val("verified");
                return true;
            });

            $('#reject').click(function() {
                if("{{$salesRequisition->status}}" == 'verified') {
                    $("#status").val("pending");
                }else{

                    $("#status").val("rejected");
                }
                return true;
            });


        });
    </script>
    <script>
        $(document).ready(function() {
            flatpickr("#commitment_date", {
                minDate: "today",
                dateFormat: "Y-m-d",
            });
        });
    </script>
    <script>
        var suppressAreaChange = false; // Flag to prevent recursive area_id change

        $(document).ready(function() {

            const customerSelect = $('#customer_id');
            const shipmentConfirmCheckbox = $('#shipmentConfirm');
            const courierConfirmCheckbox = $('#courierConfirm');
            const conditionCheckbox = $('#condition');

            const shipmentFields = [
                $('#area_id'),
                $('#address'),
                $('#contact_person_name'),
                $('#contact_person_phone')
            ];

            const courierFields = [
                $('#courier_id'),
                $('#condition')
            ];

            const conditionFields = [
                $('#additional_amount'),
                $('#condition_remarks')
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

            function clearFields() {
                $("#address").val("");
                $("#contact_person_name").val("");
                $("#contact_person_phone").val("");
                $("#company_address").val("");
                $("#person_phone").val("");
            }

            function handleCustomerSelection() {
                const customerSelected = customerSelect.val() !== "";
                shipmentConfirmCheckbox.prop('disabled', !customerSelected);
                courierConfirmCheckbox.prop('disabled', !customerSelected);

                if (!customerSelected) {
                    shipmentConfirmCheckbox.prop('checked', false);
                    courierConfirmCheckbox.prop('checked', false);
                    conditionCheckbox.prop('checked', false);
                    toggleFields(shipmentFields, false);
                    toggleFields(courierFields, false);
                    toggleFields(conditionFields, false);
                }
            }

            function handleShipmentConfirm() {
                const isChecked = shipmentConfirmCheckbox.is(':checked');
                toggleFields(shipmentFields, isChecked);

                if (!isChecked) {
                    if (courierConfirmCheckbox.is(':checked')) {
                        courierConfirmCheckbox.prop('checked', false).trigger('change');
                    }
                }
            }

            function handleCourierConfirm() {
                const isChecked = courierConfirmCheckbox.is(':checked');
                toggleFields(courierFields, isChecked);

                if (isChecked) {
                    if (!shipmentConfirmCheckbox.is(':checked')) {
                        shipmentConfirmCheckbox.prop('checked', true).trigger('change');
                    }
                }
            }

            function handleCondition() {
                toggleFields(conditionFields, conditionCheckbox.is(':checked'));
            }

            function getCustomerSettings() {
                const id = customerSelect.val();
                if (!id) return;

                $.ajax({
                    url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                    success: function(data) {
                        if (data && data.customers.customer) {
                            const customerData = data.customers;
                            const area = customerData.customer.area;
                            const area_id = area ? area.id : "address";
                            const area_name = area ? area.area : "New Address";
                            const credit_limit = customerData.credit_limit;
                            // console.log({customerData, credit_limit});
                            $("#credit_limit").val(credit_limit);

                            window.shipmentsOptions = [
                                {
                                    area: area_id,
                                    area_name: area_name,
                                    address: area_name,
                                    phone: data.customers.customer.phone,
                                    contact_person_name: data.customers.customer.company_name
                                },
                               
                            ];

                             if (area_id !== 'address') {
                                 window.shipmentsOptions = [...window.shipmentsOptions,
                                     {
                                        area: "address",
                                        area_name: "New Address",
                                        address: "",
                                        phone: "",
                                        contact_person_name: "",
                                    },
                                  ];
                             }

                             if(data.customers.customer && data.customers.customer.customer_shipping_address && data.customers.customer.customer_shipping_address.length > 0){
                                 window.shipmentsOptions = [...window.shipmentsOptions,
                                     ...data.customers.customer.customer_shipping_address.map(address => ({
                                        area: address.id,
                                        area_name: "(Shiping Address) "+address.ship_to,
                                        address: address.shipping_address,
                                        phone: address.shipping_phone,
                                        contact_person_name: address.ship_to
                                    }))
                                 ];
                             }


                             const areaVal =  "{{ $salesRequisition->shipment->area_id ?? 'address' }}";
                            //  console.log({areaVal});
                             
                            $("#area_id")[0].tomselect.clearOptions();
                            // Update the area_id select element with the new options
                            $("#area_id")[0].tomselect.addOptions(window.shipmentsOptions.map(option => ({
                                value: option.area,
                                text: option.area_name
                            })));

                            if (areaVal) {
                                $("#area_id")[0].tomselect.setValue(areaVal);
                            }

                           
                            // Update the fields if the area is not "New Address"
                            if(areaVal == 'address'){
                                $("#address").val("{{ $salesRequisition->shipment->address ??'' }}");
                                $("#address1").val("{{ $salesRequisition->shipment->address ??'' }}");
                                $("#contact_person_name").val("{{ $salesRequisition->shipment->contact_person_name ??'' }}");
                                $("#contact_person_phone").val("{{ $salesRequisition->shipment->contact_person_number ??'' }}");
                                $("#contact_person_phone1").val("{{ $salesRequisition->shipment->contact_person_number ??'' }}");
                            } else if (area_id != 'address') {
                                const selectedOption = window.shipmentsOptions.find(option => option.area === area_name);
                                if (selectedOption) {
                                    $("#address").val(selectedOption.address);
                                    $("#address1").val(selectedOption.address);
                                    $("#contact_person_name").val(selectedOption.contact_person_name);
                                    $("#contact_person_phone").val(selectedOption.phone);
                                    $("#contact_person_phone1").val(selectedOption.phone);
                                }
                            } else {
                                clearFields();
                            }

                            // VAT status
                            $('#vat_percentage').val(customerData.vat_status == 1 ? 0.05 : 0);

                            // Handle conditional billing visibility
                            if (customerData.is_condition_bill == 1) {
                                $(".is_condition_bill").show();
                            } else {
                                $(".is_condition_bill").hide();
                                conditionCheckbox.prop("checked", false);
                                $('#additional_amount, #condition_remarks').val('');
                                toggleFields(conditionFields, false);
                            }
                        }
                    }
                });
            }

            // Event bindings
            customerSelect.on('change', function() {
                handleCustomerSelection();
                getCustomerSettings();
            });

            shipmentConfirmCheckbox.on('change', handleShipmentConfirm);
            courierConfirmCheckbox.on('change', handleCourierConfirm);
            conditionCheckbox.on('change', handleCondition);

            $(document).on('change', '#area_id', function() {
                if (suppressAreaChange) return;
                const value = $(this).val();
                // if (value === 'address') {
                //     clearFields();
                // } else {
                //     // Removed redundant customer settings fetch
                // }
                
                const selectedOption = window.shipmentsOptions.find(option => option.area == value);
                console.log({ selectedOption, value, all: window.shipmentsOptions});
                
                if (selectedOption) {
                    $("#address").val(selectedOption.address);
                    $("#address1").val(selectedOption.address);
                    $("#contact_person_name").val(selectedOption.contact_person_name);
                    $("#contact_person_phone").val(selectedOption.phone);
                    $("#contact_person_phone1").val(selectedOption.phone);
                }
            });

            // Initial state setup
            handleCustomerSelection();
            getCustomerSettings();
            handleShipmentConfirm();
            handleCourierConfirm();
            handleCondition();

  
        });

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





    <script type="text/javascript">
        function clearRow(row) {
            $(row).find('input').val('');
            $(row).find('select').val('');
            $(row).find('select').each(function() {
                this.tomselect?.clear();
            })
        }
        $(document).ready(function() {
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
                const unitDiscount = parseFloat(row.find("#unit_discount").val()) || 0;

                const amount = qty * price;
                const totalDiscount = qty * unitDiscount;

                row.find("#amount").val(amount);
                row.find("#total_discount").val(totalDiscount);

                return {
                    amount,
                    totalDiscount
                };
            }

            function calculateTotals() {
                let totalAmount = 0;
                let totalDiscount = 0;
                let totalVat = 0;
                let vat = $('#vat_percentage').val();
                let flat_vat = vat ? (totalAmount - totalDiscount) * vat : (Number($('#vat').val()) || 0);

                $("#product_info_table tbody tr").each(function() {
                    const {
                        amount,
                        totalDiscount: rowDiscount
                    } = calculateRow($(this));
                    totalAmount += amount;
                    totalDiscount += rowDiscount;
                });

                $("#total_amount").val(totalAmount);
                $("#discount").val(totalDiscount);
                $("#total").val(totalAmount - totalDiscount);
                $("#vat").val(flat_vat);
                $("#net_amount").val(totalAmount - totalDiscount + flat_vat);
                updatePayable(totalAmount - totalDiscount + flat_vat);
            }

            function calculateTotalForPercentage() {
                let totalAmount = 0;
                let totalDiscount = 0;
                let totalUnitDiscount = 0;
                let additionalPercentage = parseFloat($("#additional_percentage").val()) || 0;

                $("#product_info_table tbody tr").each(function() {
                    const {
                        amount,
                        totalDiscount: rowDiscount
                    } = calculateRow($(this));
                    totalAmount += amount;
                    totalUnitDiscount += parseFloat($(this).find("#unit_discount").val()) || 0;
                });

                $("#total_amount").val(totalAmount);
                $("#total").val(totalAmount);
                $("#net_amount").val(totalAmount - totalDiscount - ((totalAmount - totalDiscount) *
                    additionalPercentage) / 100);
                updatePayable(totalAmount - totalDiscount - ((totalAmount - totalDiscount) * additionalPercentage) /
                    100);

                $("#total_unit_discount").val(totalUnitDiscount);
            }

            function updateUnitDiscounts() {
                let additionalPercentage = parseFloat($("#additional_percentage").val()) || 0;

                $("#product_info_table tbody tr").each(function() {
                    let price = parseFloat($(this).find("#price").val()) || 0;
                    let unitDiscount = (price * additionalPercentage) / 100;
                    $(this).find("#unit_discount").val(unitDiscount);
                });
                calculateTotalForPercentage();
            }
 
            $("#add_row").click(function () {
                const newRow = rowTemplate.clone();
                
                // Reset discount_type field in new row
                newRow.find('.discount_type_input').val('');
                $("#product_info_table tbody").append(newRow);

                prouctAutocompleteLoad(newRow);
            });


            $("#product_info_table").on("keyup change", "#quantity, #price, #unit_discount, #additional_percentage", function() {
                let additionalPercentage = parseFloat($("#additional_percentage").val()) || 0;
                if (additionalPercentage > 0) {
                    updateUnitDiscounts();
                } else {
                    calculateTotals();
                }
            });


            $("#product_info_table").on("click", "#remove_row", function() {
                if ($(this).closest('tbody').find('tr').length == 1) {
                    clearRow($(this).closest('tbody tr'));
                } else {
                    $(this).closest('tr').remove();
                }
                calculateTotals();
            });

            // Initial calculation for existing rows
            // calculateTotals();
            //calculateTotalForPercentage();
        updatePayable($("#net_amount").val());
            $(document).on('change', '.product_ids', async function() {
                const product_id = $(this).val();
                if (product_id == "") {
                    return false;
                }
                if (product_id != "" && $(".product_ids [value='" + product_id + "']:selected").length >
                    1) {
                    //warning and clear
                    clearRow($(this).closest('tr'))
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
                const customer_id = $('#customer_id').val();
                console.log({
                    customer_id,
                    product_id
                });
                await getSalesDiscount(customer_id, product_id, this);
                $(this).closest('tr').find('#quantity').trigger('change');
            });

            $(document).on('keyup', '.discount_range', function() {
                const discount_range = $(this).data('discount_range');
                const discount = $(this).val();
                if (discount < Number(discount_range.min) || discount > Number(discount_range.max)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>




    <script>
        var selectedProductIds = []; // Array to store selected product IDs

        async function getProductPrice(selectElement) {
            var productId = selectElement.value;
            var priceInput = selectElement.closest('tr').querySelector('input[name="price[]"]');
            if (productId.trim() !== '') {
                if (selectedProductIds.includes(productId)) {
                    // Same product selected again
                    showToast('warning', 'You have already selected this product.');
                    return;
                }
                try {
                    const response = await $.get('{{ route('purchase.get.product.list') }}?id=' + productId);
                    //  $.ajax({
                    //     url: '{{ route('purchase.get.product.list') }}',
                    //     method: 'GET',
                    //     data: {
                    //         id: productId
                    //     }
                    // });
                    var product = response[0];
                    if (!product) {
                        // Product not found
                        showToast('error', 'Price not found.');
                        priceInput.value = '';
                        salespriceInput.value = '';
                        return;
                    }
                    priceInput.value = product.mrp;
                    selectedProductIds.push(productId); // Add the selected product ID to the array
                } catch (error) {
                    console.error(error);
                    // Show error message
                    showToast('error', 'An error occurred while fetching product details.');
                }
            } else {
                // Clear inputs if no product is selected
                priceInput.value = '';
                salespriceInput.value = '';
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


        async function getSalesDiscount(customerId, productId, element = null) {
            try {
                const discounts = await $.get(
                    `{{ route('sales.get-sales-discount') }}?customer_id=${customerId}&product_id=${productId}`);
                console.log({
                    discount: discounts.discount
                });
                $(element).closest('tr').find("#unit_discount").val(0);
                $(element).closest('tr').find("#unit_discount").removeClass('discount_range');
                $(element).closest('tr').find("#unit_discount").data('discount_range', null);
                $(element).closest('tr').find("#unit_discount").removeAttr('readonly');
                if (discounts.discount) {
                    if (discounts.discount.percentage) {
                        console.log(discounts.discount.percentage);
                        if (discounts.discount.percentage.percentage > 0) {
                            if (element) {
                                const percentage = discounts.discount.percentage.percentage;
                                const price = $(element).closest('tr').find("#price").val();
                                console.log(element);
                                $(element).closest('tr').find("#unit_discount").val((percentage * price) / 100);
                                $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                            }
                        }
                    } else if (discounts.discount.productPrice) {
                        console.log(discounts.discount.productPrice);
                        const discountPrice = discounts.discount.productPrice.sales_amounts;
                        const price = $(element).closest('tr').find("#price").val();
                        if (discountPrice < price) {
                            $(element).closest('tr').find("#unit_discount").val(price - discountPrice);
                            $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                        }
                    } else if (discounts.discount.discountRange) {
                        $(element).closest('tr').find("#unit_discount").data('discount_range', discounts.discount
                            .discountRange);
                        $(element).closest('tr').find("#unit_discount").val(discounts.discount.discountRange.min);
                        $(element).closest('tr').find("#unit_discount").addClass('discount_range');
                    }
                }
            } catch (error) {
                console.error(error);
                // Show error message
                showToast('error', 'An error occurred while fetching sales discount.');
            }
        }
    </script>

    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
    @stack('script')
@endsection
