<div class="table-responsive">
    <table class="table table-bordered table-striped" id="received-courier-table">
        <thead>
            <tr>
                <th>SL</th> 
                <th>Courier</th>
                <th>Conditional Amount</th>
                <th>Receipt No</th>  
                <th>Invoice</th>  
                <th class="no-print">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                @php
                    $salesOrder = $item->salesOrder;
                    $shipmentVerify = $item->shipmentVerify;
                @endphp
                <tr data-id="{{ $item->id }}">
                    <td>{{ $index + 1 }}</td> 
                    <td>{{ $item->courier->courier_name ?? '' }}</td>
                    <td>{{ number_format($item->condition_amount) }}</td>
                    <td>{{ $shipmentVerify->receipt_no ?? '' }}</td>
                    <td>
                        <a href="{{ route('sales.sales-orders.show', $salesOrder->id) }}" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                    <td class="no-print">
                        <button type="button" class="btn btn-danger btn-sm btn-received-back" data-id="{{ $item->id }}">
                            Received Back
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No received items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-footer d-flex justify-content-end gap-2 no-print">
    <a href="{{ route('sales.condition-amount-collects.received-details', ["export" => "pdf"]) }}" target="_blank" class="btn btn-primary">Print</a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>