<div class="product-ledger-container">
    <!-- Company Header -->
    <div class="header">
        <div class="header-content">
            @if(!empty($company_info->company_logo))
            <div class="company-logo">
                <img src="{{ asset($company_info->company_logo) }}" alt="Company Logo">
            </div>
            @endif
            <div class="company-info-text">
                <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
                <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
                <div class="company-details">
                    Phone: {{ $company_info->company_phone ?? '' }} | 
                    Email: {{ $company_info->company_email ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="mb-3">
        <h6 class="mb-2">
            <strong>Product:</strong> {{ $product->name }}
        </h6>
        @if($product->model)
            <p class="mb-1"><strong>Model:</strong> {{ $product->model }}</p>
        @endif
        @if($product->brand)
            <p class="mb-1"><strong>Brand:</strong> {{ $product->brand->name }}</p>
        @endif
        @if(request('from') || request('to'))
            <p class="mb-1">
                <strong>Period:</strong> 
                {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d-M-Y') : 'Start' }} 
                to 
                {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d-M-Y') : 'Present' }}
            </p>
        @endif
    </div>

    <div class="mb-3 d-flex justify-content-end">
        <button onclick="printProductLedger()" class="btn btn-info btn-sm">
            <i class="las la-print"></i> Print
        </button>
    </div>

    <div class="table-responsive" id="productLedgerTable">
        <table class="table table-bordered">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center" style="width: 5%;">SL</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 23%;">Reference</th>
                    <th style="width: 10%;">Status</th>
                    <th class="text-center" style="width: 12%;">Received<br>(Qty)</th>
                    <th class="text-center" style="width: 12%;">Deliver<br>(Qty)</th>
                    <th class="text-center" style="width: 12%;">Stock<br>(Qty)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerData as $index => $ledger)
                    <tr>
                        <td class="text-center">{{ $ledger->is_opening ? '' : $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($ledger->date)->format('d-M-Y') }}</td>
                        <td>
                            @if($ledger->is_opening)
                                <strong>{{ $ledger->reference }}</strong>
                            @else
                                {{-- @php
                                    $routeMap = [
                                        'Modules\Sales\Models\SalesOrderDeliveryStock' => 'sales.orders.show',
                                        'Modules\Purchase\Models\RequisitionReceiveBatch' => 'purchase.requisitions.show',
                                        'Modules\Sales\Models\SalesReturnStock' => 'sales.returns.show',
                                        'Modules\Purchase\Models\PurchaseReturnApproveStock' => 'purchase.returns.show',
                                    ];
                                    
                                    $route = $routeMap[$ledger->source_type] ?? null;
                                @endphp
                                
                                @if($route && isset($ledger->reference_id))
                                    <a href="{{ route($route, $ledger->reference_id) }}" target="_blank" class="text-primary">
                                        {{ $ledger->reference }}
                                    </a>
                                @else --}}
                                    {{ $ledger->reference }}
                                {{-- @endif --}}
                            @endif
                        </td>
                        <td>{{ $ledger->status != '-' ? $ledger->status : '' }}</td>
                        <td class="text-center text-success" style="font-weight: {{ $ledger->received > 0 ? 'bold' : 'normal' }};">
                            {{ $ledger->received > 0 ? number_format($ledger->received) : '0.00' }}
                        </td>
                        <td class="text-center text-danger" style="font-weight: {{ $ledger->delivered > 0 ? 'bold' : 'normal' }};">
                            {{ $ledger->delivered > 0 ? number_format($ledger->delivered) : '0.00' }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-round badge-info p-2">
                                {{ number_format($ledger->stock) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="font-weight-bold">
                <tr>
                    <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                    <td class="text-center text-success">
                        <strong>{{ number_format($totalReceived) }}</strong>
                    </td>
                    <td class="text-center text-danger">
                        <strong>{{ number_format($totalDelivered) }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-round badge-primary p-2" style="font-size: 14px;">
                            {{ number_format($closingStock) }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
function printProductLedger() {
    const printContent = document.getElementById('productLedgerTable').innerHTML;
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Product Ledger</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
    
    // Header styles
    printWindow.document.write('.header { text-align: center; margin-bottom: 12px; border-bottom: 2px solid #333; padding-bottom: 10px; }');
    printWindow.document.write('.header-content { display: flex; align-items: center; justify-content: center; gap: 15px; }');
    printWindow.document.write('.company-logo { flex-shrink: 0; }');
    printWindow.document.write('.company-logo img { height: 60px; width: auto; max-width: 100px; object-fit: contain; }');
    printWindow.document.write('.company-info-text { text-align: center; }');
    printWindow.document.write('.company-name { font-size: 20px; font-weight: bold; margin-bottom: 5px; }');
    printWindow.document.write('.company-details { font-size: 12px; margin-bottom: 3px; }');
    
    // Table styles
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #4472C4; color: white; font-weight: bold; }');
    printWindow.document.write('.text-right { text-align: right; }');
    printWindow.document.write('.text-center { text-align: center; }');
    printWindow.document.write('.text-success { color: #28a745; }');
    printWindow.document.write('.text-danger { color: #dc3545; }');
    printWindow.document.write('.badge { padding: 4px 8px; border-radius: 4px; }');
    printWindow.document.write('.badge-info { background-color: #17a2b8; color: white; }');
    printWindow.document.write('.badge-primary { background-color: #007bff; color: white; }');
    printWindow.document.write('tfoot { font-weight: bold; background-color: #f8f9fa; }');
    printWindow.document.write('.product-info { margin-bottom: 10px; font-size: 14px; }');
    printWindow.document.write('a { color: #007bff; text-decoration: none; }');
    
    printWindow.document.write('</style></head><body>');
    
    // Company Header in Print
    printWindow.document.write('<div class="header">');
    printWindow.document.write('<div class="header-content">');
    @if(!empty($company_info->company_logo))
    printWindow.document.write('<div class="company-logo"><img src="{{ asset($company_info->company_logo) }}" alt="Company Logo"></div>');
    @endif
    printWindow.document.write('<div class="company-info-text">');
    printWindow.document.write('<div class="company-name">{{ $company_info->company_name ?? "Company Name" }}</div>');
    printWindow.document.write('<div class="company-details">{{ $company_info->company_address ?? "" }}</div>');
    printWindow.document.write('<div class="company-details">Phone: {{ $company_info->company_phone ?? "" }} | Email: {{ $company_info->company_email ?? "" }}</div>');
    printWindow.document.write('</div></div></div>');
    
    printWindow.document.write('<h2 style="text-align: center; margin-top: 15px;">Product Ledger</h2>');
    printWindow.document.write('<div class="product-info"><strong>Product:</strong> {{ $product->name }}</div>');
    @if($product->model)
    printWindow.document.write('<div class="product-info"><strong>Model:</strong> {{ $product->model }}</div>');
    @endif
    @if($product->brand)
    printWindow.document.write('<div class="product-info"><strong>Brand:</strong> {{ $product->brand->name }}</div>');
    @endif
    @if(request('from') || request('to'))
    printWindow.document.write('<div class="product-info"><strong>Period:</strong> {{ request("from") ? \Carbon\Carbon::parse(request("from"))->format("d-M-Y") : "Start" }} to {{ request("to") ? \Carbon\Carbon::parse(request("to"))->format("d-M-Y") : "Present" }}</div>');
    @endif
    
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for images to load before printing
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.print();
        }, 250);
    };
}
</script>

<style>
    /* Header Styles */
    .header {
        text-align: center;
        margin-bottom: 12px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }
    
    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }
    
    .company-logo {
        flex-shrink: 0;
    }
    
    .company-logo img {
        height: 60px;
        width: auto;
        max-width: 100px;
        object-fit: contain;
    }
    
    .company-info-text {
        text-align: center;
    }
    
    .company-name {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .company-details {
        font-size: 12px;
        margin-bottom: 3px;
        color: #555;
    }

    /* Container Styles */
    .product-ledger-container {
        padding: 15px;
    }
    
    .product-ledger-container .table {
        margin-bottom: 0;
        font-size: 14px;
    }
    
    .product-ledger-container .table td,
    .product-ledger-container .table th {
        padding: 10px 8px;
        vertical-align: middle;
    }
    
    .product-ledger-container .table thead th {
        font-weight: 600;
    }
    
    .product-ledger-container .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 13px;
    }
</style>