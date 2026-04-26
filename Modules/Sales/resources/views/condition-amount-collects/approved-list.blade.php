@section('title', 'Condition Amount Approval')
@section('description', 'Condition Amount Approval List')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Condition Amount Approval</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('sales.condition-amount-collects.approve') }}" method="POST"
                            id="approvalForm">
                            @csrf
                            <div class="table-responsive">
                                <style>
                                    .condition-amount-collects-custom,
                                    .condition-amount-collects-custom th,
                                    .condition-amount-collects-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .condition-amount-collects-custom th,
                                    .condition-amount-collects-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .condition-amount-collects-custom thead th {
                                        background-color: #f8f9fa;
                                        border-bottom-width: 2px !important;
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
                                <table id="zero-config" class="table condition-amount-collects-custom dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="no-content" style="width: 3%; text-align: center;">
                                                <input type="checkbox" id="selectAll" class="form-check-input" />
                                            </th>
                                            <th class="text-center" style="width: 5%">SL</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Courier</th>
                                            <th>Inv Amt</th>
                                            <th>Rcpt No</th>
                                            <th>Rcpt Date</th>
                                            <th>Additional Amt</th>
                                            <th>Total Cond Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($conditionAmountCollects as $key => $item)
                                            @php
                                                $additionalAmount = $item->salesOrder->shipment->additional_amount ?? 0;
                                                $totalConditionalAmount = $item->condition_amount + $additionalAmount;
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                        class="form-check-input row-checkbox" />
                                                </td>
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('sales.sales-orders.show', $item->sales_order_id) }}"
                                                        target="_blank">
                                                        {{ $item->customer->company_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $item->customer->address }}</td>
                                                <td>
                                                    <a href="#" class="courier-info-link" data-id="{{ $item->id }}"
                                                        data-courier="{{ $item->courier->courier_name }}"
                                                        data-invoice-id="{{ $item->sales_order_id ?? '' }}"
                                                        data-shipment-id="{{ $item->shipmentVerify->shipment_id ?? '' }}"
                                                        data-service="{{ $item->shipmentVerify->service_charge ?? 0 }}"
                                                        data-service-type="{{ $item->shipmentVerify->service_type ?? 'customer' }}"
                                                        data-delivery="{{ $item->shipmentVerify->delivery_charge ?? 0 }}"
                                                        data-delivery-type="{{ $item->shipmentVerify->delivery_type ?? 'customer' }}"
                                                        data-other="{{ $item->shipmentVerify->other_charge ?? 0 }}"
                                                        data-other-type="{{ $item->shipmentVerify->other_type ?? 'customer' }}"
                                                        data-courier-id="{{$item->courier->courier_name ?? '' }}"
                                                        data-receipt="{{ $item->shipmentVerify->receipt_no ?? '' }}"
                                                        data-carton="{{ $item->shipmentVerify->cartoon_no ?? '' }}"
                                                        data-courier-date="{{ $item->shipmentVerify->courier_date ?? '' }}"
                                                        data-receive-date="{{ $item->shipmentVerify->receive_date ?? '' }}"
                                                        data-source="{{ $item->shipmentVerify->source ?? '' }}"
                                                        data-status="{{ $item->shipmentVerify->status ?? '' }}">
                                                        {{ $item->courier->courier_name }}
                                                    </a>
                                                </td>
                                                <td class="fw-bold text-success">{{ number_format($item->invoice_amount) }}</td>
                                                <td>{{ $item->shipmentVerify->receipt_no ?? '' }}</td>
                                                <td>{{ $item->shipmentVerify->receive_date ?? '' }}</td>
                                                <td class="fw-bold text-success">
                                                    <span data-bs-toggle="tooltip"
                                                        title="Remarks: {{ $item->salesOrder->shipment->condition_remarks ?? 'N/A' }}">
                                                        {{ number_format($additionalAmount) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-success">{{ number_format($item->condition_amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold" style="background-color: #f8f9fa;">
                                            <td colspan="5" class="text-end">Grand Total:</td>
                                            <td class="fw-bold text-success">{{ number_format($grandTotalInvAmt ?? 0) }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-bold text-success">{{ number_format($grandTotalAdditionalAmt ?? 0) }}</td>
                                            <td class="fw-bold text-success">{{ number_format($grandTotalCondAmt ?? 0) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $conditionAmountCollects->links() }}
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">Total Selected Amount:</span>
                                            <span class="badge badge-primary fs-16" id="totalSelected">0.00</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-success btn-approve">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courier Information Modal -->
    <div class="modal fade inputForm-modal" id="courierInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Courier Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Invoice ID:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="invoice_id" value="" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Shipment ID:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="shipment_id" value="" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remarks:</label>
                                <div class="row g-3 align-items-center">
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Service Charge</span>
                                            <input type="number" class="form-control" id="service_charge" value="0"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="service_type"
                                                id="selfService" value="self" readonly>
                                            <label class="form-check-label" for="selfService">Self</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="service_type"
                                                id="loanService" value="loan" readonly>
                                            <label class="form-check-label" for="loanService">Loan To Customer</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="service_type"
                                                id="customerService" value="customer" readonly>
                                            <label class="form-check-label" for="customerService">Customer</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Delivery Charge</span>
                                            <input type="number" class="form-control" id="delivery_charge" value="0"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery_type"
                                                id="selfDelivery" value="self" readonly>
                                            <label class="form-check-label" for="selfDelivery">Self</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery_type"
                                                id="loanDelivery" value="loan" readonly>
                                            <label class="form-check-label" for="loanDelivery">Loan To Cus</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery_type"
                                                id="customerDelivery" value="customer" readonly>
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
                                            <input type="number" class="form-control" id="other_charge" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="other_type" id="selfOther"
                                                value="self" readonly>
                                            <label class="form-check-label" for="selfOther">Self</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="other_type" id="loanOther"
                                                value="loan" readonly>
                                            <label class="form-check-label" for="loanOther">Loan To Cus</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="other_type" id="benefitOther"
                                                value="benefit" readonly>
                                            <label class="form-check-label" for="benefitOther">Benefit</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="other_type"
                                                id="customerOther" value="customer" readonly>
                                            <label class="form-check-label" for="customerOther">Customer</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <input type="text" class="form-control" id="courier_id" placeholder="Courier Name"
                                            readonly>
                                        <span class="input-group-text">Courier Name</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="receipt_no" placeholder="Receipt No"
                                            readonly>
                                        <span class="input-group-text">Receipt No</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cartoon_no" placeholder="Cartoon No"
                                            readonly>
                                        <span class="input-group-text">Carton No</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="courier_date" placeholder="Courier Date"
                                        readonly>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="receive_date" placeholder="Receipt Date"
                                        readonly>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

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

            // Calculate total selected amount
            function calculateTotal() {
                let total = 0;
                $('.row-checkbox:checked').each(function () {
                    const row = $(this).closest('tr');
                    const amount = parseFloat(row.find('td:last').text().replace(/,/g, ''));
                    total += amount;
                });
                $('#totalSelected').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }

            // Approve button with SweetAlert
            $('.btn-approve').on('click', function () {
                const checkedCount = $('.row-checkbox:checked').length;

                if (checkedCount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one item to approve.'
                    });
                    return;
                }

                const total = $('#totalSelected').text();

                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to approve <b>${checkedCount}</b> conditional payment(s)<br>Total Amount: <b>${total}</b>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#approvalForm').submit();
                    }
                });
            });

            // Courier Info Modal
            $('.courier-info-link').on('click', function (e) {
                e.preventDefault();

                // Get the data attributes from the link
                const shipmentId = $(this).data('shipment-id');
                const serviceCharge = $(this).data('service');
                const serviceType = $(this).data('service-type');
                const deliveryCharge = $(this).data('delivery');
                const deliveryType = $(this).data('delivery-type');
                const otherCharge = $(this).data('other');
                const otherType = $(this).data('other-type');
                const courierId = $(this).data('courier-id');
                const receiptNo = $(this).data('receipt');
                const cartonNo = $(this).data('carton');
                const courierDate = $(this).data('courier-date');
                const receiveDate = $(this).data('receive-date');

                // Clear previous previews
                $('#imagePreviewContainer').empty();
                $('#imagePreviewContainer').html('<p class="text-muted">Attachments not available in this view</p>');

                // Populate the form fields with data from data attributes
                $('#shipment_id').val(shipmentId);
                $('#service_charge').val(serviceCharge);
                $('#delivery_charge').val(deliveryCharge);
                $('#other_charge').val(otherCharge);
                $('#receipt_no').val(receiptNo);
                $('#cartoon_no').val(cartonNo);
                $('#courier_date').val(courierDate);
                $('#receive_date').val(receiveDate);

                // Set radio buttons based on data attributes
                $(`input[name="service_type"][value="${serviceType}"]`).prop('checked', true);
                $(`input[name="delivery_type"][value="${deliveryType}"]`).prop('checked', true);
                $(`input[name="other_type"][value="${otherType}"]`).prop('checked', true);

                // Set courier select
                $('#courier_id').val(courierId);

                // Set invoice ID from the shipment verify source
                const invoiceId = $(this).data('invoice-id');
                $('#invoice_id').val(invoiceId);

                $('#courierInfoModal').modal('show');
            });
        });
    </script>
@endsection

<!-- Add the CSS styles for image preview -->
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