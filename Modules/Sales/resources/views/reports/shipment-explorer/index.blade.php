@section('title', 'Shipment Explorer Report')
@section('description', 'Shipment Explorer Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">Shipment Explorer Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                    data-target="#columnFilterModal">
                                    <i class="las la-filter"></i> Column Filter
                                </button>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">Shipment Explorer Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Shipment Type</label>
                                        <select name="shipment_type" class="form-control">
                                            <option value="">All</option>
                                            <option value="condition" {{ request('shipment_type') == 'condition' ? 'selected' : '' }}>
                                                Condition
                                            </option>
                                            <option value="without_condition" {{ request('shipment_type') == 'without_condition' ? 'selected' : '' }}>
                                                Without Condition
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Courier</label>
                                        <select name="courier_id" class="tom-select" data-placeholder="Select Courier">
                                            <option value="">All</option>
                                            @foreach ($couriers as $courier)
                                                <option value="{{ $courier->id }}"
                                                    {{ request('courier_id') == $courier->id ? 'selected' : '' }}>
                                                    {{ $courier->courier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Customer Name</label>
                                        <select name="customer_id" class="tom-select" data-placeholder="Select Customer">
                                            <option value="">All</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }} - {{ $customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Invoice ID</label>
                                        <select name="invoice_id" class="tom-select" data-placeholder="Select Invoice">
                                            <option value="">All</option>
                                            @foreach ($salesOrders as $order)
                                                <option value="{{ $order->id }}"
                                                    {{ request('invoice_id') == $order->id ? 'selected' : '' }}>
                                                    {{ $order->sales_order_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>User Name</label>
                                        <select name="user_id" class="tom-select" data-placeholder="Select User">
                                            <option value="">All</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Filter By Date</label>
                                        <select name="date_filter_type" class="form-control">
                                            <option value="">Select Date Type</option>
                                            <option value="inv_date" {{ request('date_filter_type') == 'inv_date' ? 'selected' : '' }}>
                                                Inv Date
                                            </option>
                                            <option value="update_date" {{ request('date_filter_type') == 'update_date' ? 'selected' : '' }}>
                                                Update Date
                                            </option>
                                            <option value="complete_date" {{ request('date_filter_type') == 'complete_date' ? 'selected' : '' }}>
                                                Complete Date
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Date Range (From - To)</label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                value="{{ request('from') }}" autocomplete="off" placeholder="From" />
                                            <span class="input-group-text">
                                                <i class="fa fa-exchange-alt"></i>
                                            </span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                        </div>
                                    </div>

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('sales.reports.shipment-explorer') }}" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <style>
                                    .shipment-explorer-table,
                                    .shipment-explorer-table th,
                                    .shipment-explorer-table td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }
                                                                                                    .table thead th {
                                    background-color: #35526e !important;
                                    color: #ffffff !important;
                                    font-weight: 600 !important;
                                    text-transform: uppercase;
                                    font-size: 0.85rem !important;
                                    letter-spacing: 0.08em;
                                    border-bottom: 2px solid #2a4054 !important;
                                    padding: 14px 16px !important;
                                    vertical-align: middle;
                                    text-align: center;
                                }
                                </style>
                                
                                <table class="table shipment-explorer-table table-hover table-sm" id="shipmentExplorerTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center col-sl">SL No.</th>
                                            <th class="col-invoice-id">Invoice ID</th>
                                            <th class="col-datetime">Invoice Date & Time</th>
                                            <th class="col-customer">Customer Name</th>
                                            <th class="col-courier">Courier Name</th>
                                            <th class="col-status">Status</th>
                                            <th class="col-shipment-type">Shipment Type</th>
                                            <th class="col-amount">Invoice Amount</th>
                                            <th class="col-additional">Addition Cond Amt</th>
                                            <th class="col-conditional">Conditional Amount</th>
                                            <th class="col-remarks">Con-Additional Remarks</th>
                                            <th class="col-carton">Carton No.</th>
                                            <th class="col-receipt-date">Receipt Date</th>
                                            <th class="col-receipt-no">Receipt No.</th>
                                            <th class="col-service-charge">Service Charge</th>
                                            <th class="col-service-type">Service Type</th>
                                            <th class="col-delivery-charge">Delivery Charge</th>
                                            <th class="col-delivery-type">Delivery Type</th>
                                            <th class="col-other-charge">Other Charge</th>
                                            <th class="col-other-type">Other Type</th>
                                            <th class="col-attachment">Attachment</th>
                                            <th class="col-update-by">Update By</th>
                                            <th class="col-collection-by">Collection By</th>
                                            <th class="col-approved-by">Approved By</th>
                                            <th class="col-user">User</th>
                                            <th class="col-complete-date">Complete Date</th>
                                            <th class="col-challan">Challan No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalInvoiceAmount = 0;
                                            $totalAdditionalAmount = 0;
                                            $totalConditionalAmount = 0;
                                            $totalServiceCharge = 0;
                                            $totalDeliveryCharge = 0;
                                            $totalOtherCharge = 0;
                                        @endphp

                                        @forelse($reportData as $index => $item)
                                            @php
                                                $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;
                                                $totalInvoiceAmount += $item['invoice_amount'];
                                                $totalAdditionalAmount += $item['additional_cond_amt'];
                                                $totalConditionalAmount += $item['conditional_amount'] ?? 0;
                                                $totalServiceCharge += $item['service_charge'];
                                                $totalDeliveryCharge += $item['delivery_charge'];
                                                $totalOtherCharge += $item['other_charge'];

                                                $statusClass = match($item['status']) {
                                                    'Complete' => 'badge-success',
                                                    'Request' => 'badge-warning',
                                                    'Updated' => 'badge-info',
                                                    'Pending' => 'badge-secondary',
                                                    default => 'badge-secondary'
                                                };
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</div>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                    <a href="{{ route('sales.sales-orders.show', $item['sales_order']->id) }}" 
                                                       target="_blank" class="text-primary font-weight-bold">
                                                        {{ $item['invoice_id'] }}
                                                    </a>
                                                 </div>
                                                <td>
                                                    {{ $item['invoice_date'] }}<br>
                                                    <small class="text-muted">{{ $item['invoice_time'] }}</small>
                                                 </div>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                    <a href="{{ route('crm.customers.show', $item['customer_id']) }}" 
                                                       target="_blank" class="text-primary">
                                                        {{ $item['customer_name'] }}
                                                    </a>
                                                 </div>
                                                <td>
                                                    @if($item['courier_id'])
                                                        <a href="javascript:void(0);" 
                                                           class="text-primary courier-link" 
                                                           data-shipment-data="{{ json_encode($item['shipment']) }}"
                                                           data-action="{{ route('sales.shipment-verifies.update', $item['shipment_verify_id']) }}"
                                                           style="cursor: pointer; text-decoration: underline;">
                                                            {{ $item['courier_name'] }}
                                                        </a>
                                                    @else
                                                        {{ $item['courier_name'] }}
                                                    @endif
                                                 </div>
                                                <td>
                                                    <span class="badge {{ $statusClass }} badge-round">{{ $item['status'] }}</span>
                                                 </div>
                                                <td>{{ $item['shipment_type'] }}</div>
                                                <td class="text-right">{{ number_format($item['invoice_amount']) }}</div>
                                                <td class="text-right">{{ number_format($item['additional_cond_amt']) }}</div>
                                                <td class="text-right">
                                                    {{ $item['conditional_amount'] !== null ? number_format($item['conditional_amount']) : '' }}
                                                 </div>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ $item['con_additional_remarks'] }}</div>
                                                <td>{{ $item['carton_no'] }}</div>
                                                <td>{{ $item['receipt_date'] }}</div>
                                                <td>{{ $item['receipt_no'] }}</div>
                                                <td class="text-right">{{ number_format($item['service_charge']) }}</div>
                                                <td>{{ $item['service_type'] }}</div>
                                                <td class="text-right">{{ number_format($item['delivery_charge']) }}</div>
                                                <td>{{ $item['delivery_type'] }}</div>
                                                <td class="text-right">{{ number_format($item['other_charge']) }}</div>
                                                <td>{{ $item['other_type'] }}</div>
                                                <td class="text-center">
                                                    @if(!empty($item['attachment']))
                                                        @foreach($item['attachment'] as $file)
                                                            <a href="{{ asset($file) }}" target="_blank" 
                                                               class="badge badge-secondary badge-round">
                                                                <i class="fa fa-file"></i>
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No Files</span>
                                                    @endif
                                                 </div>
                                                <td>{{ $item['update_by'] }}</div>
                                                <td>{{ $item['collection_by'] }}</div>
                                                <td>{{ $item['approved_by'] }}</div>
                                                <td>{{ $item['user'] }}</div>
                                                <td>{{ $item['complete_date'] }}</div>
                                                <td>{{ $item['challan_no'] }}</div>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="27" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                 </div>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold" style="font-size: 14px;">
                                            <th colspan="7" class="text-right">Summary:</th>
                                            <th class="text-right">{{ number_format($totalInvoiceAmount) }}</th>
                                            <th class="text-right">{{ number_format($totalAdditionalAmount) }}</th>
                                            <th class="text-right">{{ number_format($totalConditionalAmount) }}</th>
                                            <th colspan="4"></th>
                                            <th class="text-right">{{ number_format($totalServiceCharge) }}</th>
                                            <th></th>
                                            <th class="text-right">{{ number_format($totalDeliveryCharge) }}</th>
                                            <th></th>
                                            <th class="text-right">{{ number_format($totalOtherCharge) }}</th>
                                            <th colspan="9"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $reportData->firstItem() ?? 0 }} to {{ $reportData->lastItem() ?? 0 }} of
                                    {{ $reportData->total() }} entries
                                </div>
                                <div>
                                    {{ $reportData->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column Filter Modal -->
    @include('Sales::reports.shipment-explorer.column-filter-modal')

    <!-- Edit Modal (Same as shipment-verifies index) -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Verification Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="redirect_to" value="{{ route('sales.reports.shipment-explorer', request()->all()) }}">
                    
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
                                <select class="form-control form-select" name="courier_id">
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
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cartoon_no" placeholder="Cartoon No"
                                        required>
                                    <span class="input-group-text">Carton No *</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control flatdate" name="courier_date"
                                    placeholder="Courier Date">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control flatdate" name="receive_date"
                                    placeholder="Receipt Date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <input type="file" name="files[]" class="form-control file-control" id="fileInput" multiple>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_sms" value="1"
                            class="btn btn-secondary mt-2 mb-2 send-sms-from-modal">Send SMS</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect" id="updateBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    @include('Sales::reports.shipment-explorer.scripts')
    
    <script>
        $(document).ready(function (e) {
            // Handle courier link click to open edit modal
            $(document).on('click', '.courier-link', function () {
                const data = $(this).data('shipment-data');
                const action = $(this).data('action');

                console.log("Shipment Data:", data);

                // Populate form fields
                $.each(data, function (key, value) {
                    $('#editModal input[name="' + key + '"]').not('[type="file"],[type="radio"],[type="checkbox"]').val(value);
                    $('#editModal textarea[name="' + key + '"]').val(value);
                    $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop('selected', true);
                    $('#editModal input[type="radio"][name="' + key + '"][value="' + value + '"]').prop('checked', true);
                });

                // Set invoice ID from source
                const invoiceId = data.source?.source?.sales_order_id || data.source?.source_id || 'N/A';
                $('#editModal input[name="invoice_id"]').val(invoiceId);
                
                // Set shipment ID
                $('#editModal input[name="shipment_id"]').val(data.shipment_id || 'N/A');

                // Set form action
                $("#editFrom").attr("action", action);

                // Show modal
                $("#editModal").modal('show');
                
                // Reinitialize flatpickr for date fields
                setTimeout(function() {
                    $('#editModal .flatdate').each(function () {
                        if (this._flatpickr) {
                            this._flatpickr.setDate(this.value);
                        }
                    });
                }, 100);
            });

            // Handle SMS button click from modal
            $(document).on('click', '.send-sms-from-modal', function(e) {
                e.preventDefault();
                const form = $('#editFrom');
                const formData = new FormData(form[0]);
                formData.set('send_sms', '1');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'SMS sent successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "{{ route('sales.reports.shipment-explorer', request()->all()) }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to send SMS'
                        });
                    }
                });
            });

            // Form validation before submission
            $('#editFrom').on('submit', function(e) {
                let isValid = true;
                let errorMessage = '';

                // Clear previous error states
                $('.form-control').removeClass('is-invalid');
                $('.error-message').remove();

                // Validate Receipt Date
                const receiveDate = $('input[name="receive_date"]').val();
                if (!receiveDate) {
                    $('input[name="receive_date"]').addClass('is-invalid');
                    errorMessage += 'Receipt Date is required. ';
                    isValid = false;
                } else {
                    // Check if date is valid format (YYYY-MM-DD or DD/MM/YYYY)
                    const dateRegex = /^\d{4}-\d{2}-\d{2}$|^\d{2}\/\d{2}\/\d{4}$/;
                    if (!dateRegex.test(receiveDate)) {
                        $('input[name="receive_date"]').addClass('is-invalid');
                        errorMessage += 'Receipt Date format is invalid. ';
                        isValid = false;
                    }
                }

                // Validate file upload
                const fileInput = document.getElementById('fileInput');
                if (fileInput && fileInput.files.length > 0) {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    const maxSize = 10 * 1024 * 1024; // 10MB in bytes

                    for (let i = 0; i < fileInput.files.length; i++) {
                        const file = fileInput.files[i];

                        // Check file type
                        if (!allowedTypes.includes(file.type)) {
                            $(fileInput).addClass('is-invalid');
                            errorMessage += 'File type not allowed. Only JPG, PNG, and PDF files are allowed. ';
                            isValid = false;
                            break;
                        }

                        // Check file size
                        if (file.size > maxSize) {
                            $(fileInput).addClass('is-invalid');
                            errorMessage += 'File size exceeds 10MB limit. ';
                            isValid = false;
                            break;
                        }
                    }
                }

                // Show error message if validation fails
                if (!isValid) {
                    e.preventDefault();
                    toastr.error(errorMessage.trim());
                }
            });

            // Add real-time validation for receipt date
            $('input[name="receive_date"]').on('blur', function() {
                const dateValue = $(this).val();
                if (dateValue) {
                    const dateRegex = /^\d{4}-\d{2}-\d{2}$|^\d{2}\/\d{2}\/\d{4}$/;
                    if (!dateRegex.test(dateValue)) {
                        $(this).addClass('is-invalid');
                        if ($(this).next('.error-message').length === 0) {
                            $(this).after('<div class="error-message text-danger mt-1">Invalid date format.</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.error-message').remove();
                    }
                } else {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.error-message').length === 0) {
                        $(this).after('<div class="error-message text-danger mt-1">Receipt Date is required.</div>');
                    }
                }
            });

            // Add real-time validation for file input
            $('#fileInput').on('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                    let hasError = false;

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        // Check file type
                        if (!allowedTypes.includes(file.type)) {
                            hasError = true;
                            break;
                        }

                        // Check file size
                        if (file.size > maxSize) {
                            hasError = true;
                            break;
                        }
                    }

                    if (hasError) {
                        $(this).addClass('is-invalid');
                        if ($(this).next('.error-message').length === 0) {
                            $(this).after('<div class="error-message text-danger mt-1">Invalid file type or size. Only JPG, PNG, PDF files up to 10MB allowed.</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.error-message').remove();
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-message').remove();
                }
            });
        });
    </script>
@endsection