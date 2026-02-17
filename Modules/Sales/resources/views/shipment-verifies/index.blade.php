@section('title', 'Shipment Verifies')
@section('description', 'Shipment Verifies List')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
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
                                        {{ trans('menu.shipment-verifies-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.settings.tags.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    Add New
                                </button>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.shipment-verifies-menu-title') }}
                            </h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="all" value="" {{ request('status') == '' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="all">All</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="condition" value="condition" {{ request('status') == 'condition' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="condition">Condition</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="without_condition" value="without_condition" {{ request('status') == 'without_condition' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="without_condition">Without Condition</label>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <select name="courier_id" class="form-control" id="courier_id">
                                                        <option value="">All Couriers</option>
                                                        @foreach ($couriers as $courier)
                                                            <option value="{{ $courier->id }}" {{ request('courier_id') == $courier->id ? 'selected' : '' }}>{{ $courier->courier_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                               <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdate" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>
    
                                                        <input type="text" class="form-control flatdate" name="to"
                                                            value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                    </div>
                                                </td>
                                        

                                                <td colspan="4" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h5>Shipment Verifies</h5>
                                <button type="button" class="btn btn-primary" id="sendBulkSmsBtn" disabled>
                                    <i class="las la-sms"></i> Send SMS to Selected
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover"
                                    data-page='@include('utils.table_paginate', ['data' => $shipmentVerifies])'
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="no-content" style="width: 5%; text-align: center;"><input
                                                    type="checkbox" id="selectAll" class="form-check-input" /></th>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            {{-- <th>Shipment ID</th> --}}
                                            <th>Customer Name</th>
                                            {{-- <th>Customer Address</th> --}}
                                            <th>Courier</th>
                                            <th>Courier Date</th>
                                            <th>INV Amt</th>
                                            <th>CDL Amt</th>
                                            <th>Rcpt No</th>
                                            <th>Charges (Service | Delivery | Other)</th>
                                            {{-- <th>Source</th> --}}
                                            {{-- <th>Service Charge</th>
                                            <th>Delivery Charge</th>
                                            <th>Other Charge</th> --}}
                                            {{-- <th>Service Type</th>
                                            <th>Delivery Type</th>
                                            <th>Other Type</th>
                                            <th>Receipt No</th>
                                            <th>Cartoon No</th>
                                            <th>Receive Date</th>
                                            --}}
                                            <th>Status</th>
                                            <th class="no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shipmentVerifies as $key => $shipmentVerify)
                                            <tr>
                                                {{-- @dd($shipmentVerifies) --}}
                                                <td class="text-center"><input type="checkbox" class="form-check-input row-checkbox"
                                                        value="{{ $shipmentVerify->id }}" /></td>
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                {{-- <td>{{ $shipmentVerify->shipment_id }}</td> --}}
                                                <td>
                                                    {{ $shipmentVerify->customer->company_name }}
                                                    <br>
                                                    <small class="text-muted">{!! wordwrap($shipmentVerify->customer_address, 60, '<br>', true) !!}</small>

                                                </td>  
                                                {{-- <td>{{ $shipmentVerify->customer_address }}</td> --}}
                                                <td>{{ $shipmentVerify->courier?->courier_name }}</td>
                                                <td> {{ $shipmentVerify->courier_date }}  </td>
                                                {{-- @dd( ) --}}
                                                <td>  {{ numberFormat($shipmentVerify->source?->source?->net_amount ?? 0) }} </td>
                                                <td> {{ numberFormat(($shipmentVerify->source?->source?->shipment?->additional_amount ?? 0) + ($shipmentVerify->source?->source->due_amount ?? 0)) }}  </td>
                                                <td>{{$shipmentVerify->receipt_no}}</td>
                                                <td>{{ numberFormat($shipmentVerify->service_charge ?? 0, 2) }} (S) | {{ numberFormat($shipmentVerify->delivery_charge ?? 0, 2) }} (D) | {{ $shipmentVerify->other_charge !== null && $shipmentVerify->other_charge !== '' ? numberFormat($shipmentVerify->other_charge, 2) : '-' }} (O)</td>
                                                {{-- <td>{{ $shipmentVerify->source }}</td> --}}
                                                {{-- <td>{{ $shipmentVerify->service_charge }}</td>
                                                <td>{{ $shipmentVerify->delivery_charge }}</td>
                                                <td>{{ $shipmentVerify->other_charge }}</td>
                                                <td>{{ $shipmentVerify->service_type }}</td>
                                                <td>{{ $shipmentVerify->delivery_type }}</td>
                                                <td>{{ $shipmentVerify->other_type }}</td>
                                                <td>{{ $shipmentVerify->receipt_no }}</td>
                                                <td>{{ $shipmentVerify->cartoon_no }}</td>
                                                <td>{{ $shipmentVerify->receive_date }}</td>
                                                <td>
                                                    @if ($shipmentVerify->status == "pending")
                                                    <span class="badge badge-warning badge-round">Pending</span>
                                                    @elseif ($shipmentVerify->status == "verified")
                                                    <span class="badge badge-success badge-round">Entered</span>
                                                    @endif
                                                </td> --}}
                                                <td>{{ $shipmentVerify->status }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group"
                                                        aria-label="Small button group">
                                                        @if($shipmentVerify->receipt_no)
                                                        <button type="button" class="btn btn-outline-success send_message"
                                                            data-id="{{ $shipmentVerify->id }}">
                                                            <i class="fa fa-sms"></i>
                                                        </button>
                                                        @endif
                                                        {{-- @if(hasPermission('sales.shipment-verifies.update')) --}}
                                                        <button type="button"
                                                            data-action="{{ route('sales.shipment-verifies.update', $shipmentVerify->id) }}"
                                                            data-data="{{$shipmentVerify}}"
                                                            class="btn btn-outline-primary btn-edit">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                        {{-- @endif --}}
 
                                                        <a class="btn btn-outline-primary" href="{{ route('sales.shipment-verifies.show', $shipmentVerify->id) }}" target="_blank">
                                                            <i class="fa fa-truck"></i>
                                                        </a> 
                                                        <a class="btn btn-outline-primary" href="{{ route('sales.sales-orders.show', $shipmentVerify->source?->source?->id) }}" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>   
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    <tfoot>
                                        <tr class="fw-bold" style="background-color: #f8f9fa;">
                                            <td colspan="5" class="text-end">Grand Total:</td>
                                            <td>{{ number_format($grandTotalInvAmt ?? 0) }}</td>
                                            <td>{{ number_format($grandTotalCdlAmt ?? 0) }}</td>
                                            <td colspan="4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('menu.inventory-settings-tag-menu-title') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.transport-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control" placeholder="Code" required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Description</label>
                                        <div class="col-sm-12">
                                            <textarea name="description" class="form-control"
                                                placeholder="Description"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Status</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="status" required>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Invoice ID:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="invoice_id" value="" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Shipment ID:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="shipment_id" value="" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks:</label>
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Service Charge <span class="text-danger">*</span>
                                        </span>
                                        <input type="number" class="form-control" name="service_charge" value="0" required>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="service_type" id="selfService"
                                            value="self">
                                        <label class="form-check-label" for="selfService">Self</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="service_type" id="loanService"
                                            value="loan">
                                        <label class="form-check-label" for="loanService">Loan To Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="service_type"
                                            id="customerService" value="customer" checked>
                                        <label class="form-check-label" for="customerService">Customer</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Delivery Charge <span class="text-danger">*</span>
                                        </span>
                                        <input type="number" class="form-control" name="delivery_charge" value="0" required>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="selfDelivery"
                                            value="self">
                                        <label class="form-check-label" for="selfDelivery">Self</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="loanDelivery"
                                            value="loan">
                                        <label class="form-check-label" for="loanDelivery">Loan To Cus</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery_type"
                                            id="customerDelivery" value="customer" checked>
                                        <label class="form-check-label" for="customerDelivery">Customer</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Other Charge</span>
                                        <input type="number" class="form-control" name="other_charge" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="other_type" id="selfOther"
                                            value="self">
                                        <label class="form-check-label" for="selfOther">Self</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="other_type" id="loanOther"
                                            value="loan">
                                        <label class="form-check-label" for="loanOther">Loan To Cus</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="other_type" id="benefitOther"
                                            value="benefit">
                                        <label class="form-check-label" for="benefitOther">Benefit</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="other_type" id="customerOther"
                                            value="customer" checked>
                                        <label class="form-check-label" for="customerOther">Customer</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <select class="form-control form-select" name="courier_id" id="courier_id">
                                    <option value="">Select Courier Company</option>
                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->courier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="receipt_no" placeholder="Receipt No"
                                        required>
                                    <span class="input-group-text">Receipt No *</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cartoon_no" placeholder="Cartoon No" value=""
                                        required>
                                    <span class="input-group-text">Carton No *</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control flatdate" name="courier_date"
                                    placeholder="Courier Date">
                                    <span class="input-group-text">Courier Date</span>
                                </div>

                             
                            </div>
                            <div class="col-sm-4 d-none">
                                <input type="text" class="form-control flatdate" name="receive_date" value=""
                                    placeholder="Receipt Date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-12 col-form-label">Attachments:</label>
                            <div class="col-sm-12">
                                <input type="file" name="files[]" class="form-control file-control" id="fileInput" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                            </div>
                        </div>

                        <!-- Image Preview Container -->
                        <div class="row mb-3">
                            <label class="col-sm-12 col-form-label">Current Attachments:</label>
                            <div class="col-sm-12">
                                <div id="imagePreviewContainer" class="preview-container">
                                    <!-- Existing images will be loaded here -->
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_sms" value="1"
                            class="btn btn-secondary mt-2 mb-2 send-sms-from-modal">Send</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- CONTENT AREA -->
<style>
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
        min-height: 50px;
        padding: 10px;
        border: 1px dashed #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    .preview-item {
        position: relative;
        width: 120px;
        height: 120px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-item img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .preview-item .file-info {
        padding: 5px;
        text-align: center;
        font-size: 12px;
    }

    .preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .preview-item.pdf-preview {
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pdf-icon {
        font-size: 40px;
        color: #d32f2f;
    }
</style>

@section('page_scripts')

    <script>
        $(document).ready(function (e) {
            // Select All checkbox
            $('#selectAll').on('change', function () {
                $('.row-checkbox').prop('checked', $(this).is(':checked'));
                calculateTotal();
            });

            // Individual checkbox
            $('.row-checkbox').on('change', function () {
                calculateTotal();

                // Update Select All state
                const allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Edit button click
            $(document).on('click', '.btn-edit', function () {
                const data = $(this).data('data');
 
                console.log(data);

                // Clear previous previews
                $('#imagePreviewContainer').empty();

                // Display existing files if any
                if (data.files && Array.isArray(data.files) && data.files.length > 0) {
                    data.files.forEach(function(file, index) {
                        const fileExtension = file.split('.').pop().toLowerCase();
                        let previewElement;

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                            // Image file
                            previewElement = `
                                <div class="preview-item">
                                    <img src="${file}" alt="Attachment ${index + 1}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-image.png') }}';">
                                    <a href="${file}" target="_blank" class="remove-btn" title="View File">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            `;
                        } else {
                            // Non-image file (PDF, etc.)
                            let iconClass = 'fa-file';
                            if (fileExtension === 'pdf') {
                                iconClass = 'fa-file-pdf';
                            } else if (['doc', 'docx'].includes(fileExtension)) {
                                iconClass = 'fa-file-word';
                            } else if (['xls', 'xlsx'].includes(fileExtension)) {
                                iconClass = 'fa-file-excel';
                            }

                            previewElement = `
                                <div class="preview-item pdf-preview">
                                    <div>
                                        <i class="fa ${iconClass} pdf-icon"></i>
                                        <div class="file-info">${file.split('/').pop()}</div>
                                    </div>
                                    <a href="${file}" target="_blank" class="remove-btn" title="View File">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            `;
                        }

                        $('#imagePreviewContainer').append(previewElement);
                    });
                } else {
                    $('#imagePreviewContainer').html('<p class="text-muted">No attachments available</p>');
                }

                //loop through data object
                $.each(data, function (key, value) {
                    console.log("Data:", data);

                    // Handle other_charge specifically to allow empty values
                    if (key === 'other_charge' && (value === null || value === '')) {
                        $('#editModal input[name="other_charge"]').val('0');
                    } else {
                        $('#editModal input[name="' + key + '"]').not('[type="file"],[type="radio"],[type="checkbox"]').val(value);
                    } 
 
                    $('#editModal textarea[name="' + key + '"]').val(value);
                    $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop('selected', true);
                    $('#editModal input[type="radio"][name="' + key + '"][value="' + value + '"]').prop('checked', true);
                    $('#editModal input[name="invoice_id"]').val(data.source?.source?.sales_order_id ?? data.source?.source_id);
                    $('#editModal .send-sms-from-modal').data('id', data.id);

                });

                const fields = [
                    'cartoon_no',
                    'receipt_no',
                    'delivery_charge',
                    'service_charge'
                ];

                fields.forEach(function(name){

                    let $field = $(`#editModal input[name="${name}"]`);

                    if (!$field.val()) {
                        $field.val(0);
                    }

                });
                  

                $("#editFrom").attr("action", $(this).data('action'));

                $("#editModal").modal('show');
                $('#editModal .flatdate').each(function () {
                    // console.log(this, this._flatpickr);
                    this._flatpickr?.setDate(this.value);
                });
            });

            // Form validation before submission
            $('#editFrom').on('submit', function(e) {
                let isValid = true;
                let errorMessage = '';

                // Clear previous validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Validate required fields
                const requiredFields = [
                    { selector: 'input[name="service_charge"]', name: 'Service Charge' },
                    { selector: 'input[name="delivery_charge"]', name: 'Delivery Charge' },
                    { selector: 'input[name="receipt_no"]', name: 'Receipt No' },
                    { selector: 'input[name="courier_date"]', name: 'Courier Date' }
                ];

                requiredFields.forEach(function(field) {
                    const $field = $(field.selector);
                    const value = $field.val();

                    if (!value || value.trim() === '') {
                        $field.addClass('is-invalid');
                        isValid = false;

                        // Add error message below the field
                        if (!$field.next('.invalid-feedback').length) {
                            $field.after(`<div class="invalid-feedback">${field.name} is required</div>`);
                        }

                        if (errorMessage === '') {
                            errorMessage = `${field.name} is required`;
                        }
                    }
                });

                // Validate numeric fields
                const numericFields = [
                    { selector: 'input[name="service_charge"]', name: 'Service Charge' },
                    { selector: 'input[name="delivery_charge"]', name: 'Delivery Charge' }
                ];

                numericFields.forEach(function(field) {
                    const $field = $(field.selector);
                    const value = parseFloat($field.val());

                    if (value !== undefined && value !== null && isNaN(value)) {
                        $field.addClass('is-invalid');
                        isValid = false;

                        // Add error message below the field
                        if (!$field.next('.invalid-feedback').length) {
                            $field.after(`<div class="invalid-feedback">${field.name} must be a valid number</div>`);
                        }

                        if (errorMessage === '') {
                            errorMessage = `${field.name} must be a valid number`;
                        }
                    } else if (value < 0) {
                        $field.addClass('is-invalid');
                        isValid = false;

                        // Add error message below the field
                        if (!$field.next('.invalid-feedback').length) {
                            $field.after(`<div class="invalid-feedback">${field.name} cannot be negative</div>`);
                        }

                        if (errorMessage === '') {
                            errorMessage = `${field.name} cannot be negative`;
                        }
                    }
                });

                // Validate other_charge separately as it can be empty
                const $otherChargeField = $('input[name="other_charge"]');
                const otherChargeValue = $otherChargeField.val();

                if (otherChargeValue !== '' && otherChargeValue !== null && otherChargeValue !== undefined) {
                    const parsedValue = parseFloat(otherChargeValue);
                    if (isNaN(parsedValue)) {
                        $otherChargeField.addClass('is-invalid');
                        isValid = false;

                        // Add error message below the field
                        if (!$otherChargeField.next('.invalid-feedback').length) {
                            $otherChargeField.after('<div class="invalid-feedback">Other Charge must be a valid number</div>');
                        }

                        if (errorMessage === '') {
                            errorMessage = 'Other Charge must be a valid number';
                        }
                    } else if (parsedValue < 0) {
                        $otherChargeField.addClass('is-invalid');
                        isValid = false;

                        // Add error message below the field
                        if (!$otherChargeField.next('.invalid-feedback').length) {
                            $otherChargeField.after('<div class="invalid-feedback">Other Charge cannot be negative</div>');
                        }

                        if (errorMessage === '') {
                            errorMessage = 'Other Charge cannot be negative';
                        }
                    }
                }

                // Validate that at least one file is attached
                const $fileInput = $('input[name="files[]"]');
                const files = $fileInput[0].files;
                const existingFilesCount = $('#imagePreviewContainer .preview-item').length;
               
                if ((files.length === 0 && existingFilesCount === 0)) {
                    isValid = false;

                    // Add error message below the file input
                    if (!$fileInput.next('.invalid-feedback').length ) {
                        $fileInput.after('<div class="invalid-feedback">At least one attachment is required</div>');
                    }

                    $fileInput.addClass('is-invalid');

                    if (errorMessage === '') {
                        errorMessage = 'At least one attachment is required';
                    }
                }

                // Show toast if validation failed
                if (!isValid) {
                    e.preventDefault(); // Prevent form submission

                    // Use existing showToast function if available, otherwise use toastr directly
                    if (typeof showToast === 'function') {
                        showToast('error', errorMessage);
                    } else {
                        toastr.error(errorMessage);
                    }

                    return false;
                }
            });

            
        });
    </script>
    <script>
        // showToast function for displaying notifications
        function showToast(type, message) {
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            } else if (type === 'success') {
                toastr.success(message);
            } else {
                toastr.info(message);
            }
        }

        // Select All checkbox functionality
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkSmsButton();
        });

        // Individual checkbox change - update select all checkbox state
        $(document).on('change', '.row-checkbox', function() {
            const totalCheckboxes = $('.row-checkbox').length;
            const checkedCheckboxes = $('.row-checkbox:checked').length;

            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);

            // Enable/disable bulk SMS button based on selection
            updateBulkSmsButton();
        });

        // Update bulk SMS button state
        function updateBulkSmsButton() {
            const selectedCount = $('.row-checkbox:checked').length;
            $('#sendBulkSmsBtn').prop('disabled', selectedCount === 0);
        }

        // Bulk SMS button click handler
        $('#sendBulkSmsBtn').on('click', function() {
            const selectedItems = [];
            $('.row-checkbox:checked').each(function() {
                selectedItems.push($(this).val());
            });

            if (selectedItems.length === 0) {
                Swal.fire('No Selection', 'Please select at least one item to send SMS.', 'warning');
                return;
            }

            // Show confirmation modal
            Swal.fire({
                title: 'Send SMS to Selected Items',
                html: `
                    <div class="text-start">
                        <p>Selected items: ${selectedItems.length}</p>
                        <p>Are you sure you want to send SMS to these selected shipment verifications?</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send SMS',
                cancelButtonText: 'Cancel',
                icon: 'warning'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send SMS to selected items
                    sendBulkSmsToSelected(selectedItems);
                }
            });
        });

        // Function to send SMS to selected items
        function sendBulkSmsToSelected(selectedItems) {
            $.ajax({
                url: "{{ route('sales.shipment-verifies.send-sms') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    shipment_verify_ids: selectedItems
                },
                beforeSend: function() {
                    Swal.showLoading();
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message || 'SMS sent successfully to selected items!', 'success');
                    } else {
                        Swal.fire('Warning', response.message || 'Some SMS failed to send.', 'warning');
                    }
                    location.reload();
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to send SMS.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }

        $(document).ready(function () {
            $('.send_message').on('click', function () {
                let shipmentVerifyId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to send an SMS for this shipment verification?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('sales.shipment-verifies.send-sms') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                shipment_verify_id: shipmentVerifyId
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Sent!',
                                        response.message,
                                        'success'
                                    ).then((result) => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while sending the SMS.',
                                    'error'
                                );
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection