<!-- Column Filter Modal -->
<div class="modal fade" id="columnFilterModal" tabindex="-1" aria-labelledby="columnFilterModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="columnFilterModalLabel">Select Columns to Display</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="columnFilterForm">
                    <div class="row">
                        @php
                            $columns = [
                                'invoice-id' => 'Invoice ID',
                                'datetime' => 'Invoice Date & Time',
                                'customer' => 'Customer Name',
                                'courier' => 'Courier Name',
                                'status' => 'Status',
                                'shipment-type' => 'Shipment Type',
                                'amount' => 'Invoice Amount',
                                'additional' => 'Addition Cond Amt',
                                'conditional' => 'Conditional Amount',
                                'remarks' => 'Con-Additional Remarks',
                                'carton' => 'Carton No.',
                                'receipt-date' => 'Receipt Date',
                                'receipt-no' => 'Receipt No.',
                                'service-charge' => 'Service Charge',
                                'service-type' => 'Service Type',
                                'delivery-charge' => 'Delivery Charge',
                                'delivery-type' => 'Delivery Type',
                                'other-charge' => 'Other Charge',
                                'other-type' => 'Other Type',
                                'attachment' => 'Attachment',
                                'update-by' => 'Update By',
                                'collection-by' => 'Collection By',
                                'approved-by' => 'Approved By',
                                'user' => 'User',
                                'complete-date' => 'Complete Date',
                                'challan' => 'Challan No.',
                            ];
                        @endphp
                        @foreach ($columns as $key => $label)
                            <div class="col-md-6 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="col_{{ $key }}"
                                        name="columns[]" value="{{ $key }}" checked>
                                    <label class="custom-control-label" for="col_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyColumnFilter">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

<style>
    #shipmentExplorerTable {
        width: 100%;
        font-size: 11px;
    }

    #shipmentExplorerTable th {
        white-space: nowrap;
        vertical-align: middle;
        font-weight: 600;
        padding: 8px 6px;
    }

    #shipmentExplorerTable td {
        vertical-align: middle;
        padding: 6px;
    }

    .col-sl { width: 40px; }
    .col-invoice-id { width: 120px; }
    .col-datetime { width: 130px; }
    .col-customer { width: 150px; }
    .col-courier { width: 120px; }
    .col-status { width: 100px; }
    .col-shipment-type { width: 120px; }
    .col-amount { width: 100px; }
    .col-additional { width: 100px; }
    .col-conditional { width: 120px; }
    .col-remarks { width: 180px; }
    .col-carton { width: 100px; }
    .col-receipt-date { width: 100px; }
    .col-receipt-no { width: 100px; }
    .col-service-charge { width: 100px; }
    .col-service-type { width: 100px; }
    .col-delivery-charge { width: 100px; }
    .col-delivery-type { width: 100px; }
    .col-other-charge { width: 100px; }
    .col-other-type { width: 100px; }
    .col-attachment { width: 100px; }
    .col-update-by { width: 100px; }
    .col-collection-by { width: 100px; }
    .col-approved-by { width: 100px; }
    .col-user { width: 100px; }
    .col-complete-date { width: 110px; }
    .col-challan { width: 120px; }

    .badge {
        font-size: 10px;
        padding: 4px 8px;
    }

    .badge-round {
        border-radius: 999px !important;
        padding: 4px 10px !important;
        display: inline-block;
        line-height: 1.2;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }



    @media print {
        .breadcrumb-main,
        .card-header,
        .btn,
        .modal,
        .no-print {
            display: none !important;
        }

        #shipmentExplorerTable {
            font-size: 9px;
        }
    }
</style>