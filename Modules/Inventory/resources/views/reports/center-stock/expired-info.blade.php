<div class="expired-info-container">
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
    
    <div class="mb-3">
        <h6 class="mb-2"><strong>Product:</strong> {{ $product->name }}</h6>
        @if($product->model)
            <p class="mb-1"><strong>Model:</strong> {{ $product->model }}</p>
        @endif
        @if($product->brand)
            <p class="mb-1"><strong>Brand:</strong> {{ $product->brand->name }}</p>
        @endif
    </div>

    <div class="mb-3 d-flex justify-content-end">
        <button onclick="printExpiredInfo()" class="btn btn-info btn-sm">
            <i class="las la-print"></i> Print
        </button>
    </div>

    <div class="table-responsive" id="expiredInfoTable">
        <table class="table table-bordered table-hover">
            <thead class="bg-warning text-dark">
                <tr>
                    <th style="width: 10%;">SL</th>
                    @if($product->is_serial == 'yes')
                        <th style="width: 30%;">Serial No</th>
                        <th style="width: 30%;">Manufacture Date</th>
                    @else
                        <th style="width: 30%;">Lot No</th>
                        <th style="width: 30%;">Expiry Date</th>
                    @endif
                    <th class="text-right" style="width: 20%;">Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiredData as $index => $lot)
                    @php
                        $isExpired = false;
                        $expiryDate = null;
                        if($product->is_serial != 'yes' && isset($lot->source->expired_date)) {
                            $expiryDate = \Carbon\Carbon::parse($lot->source->expired_date);
                            $isExpired = $expiryDate->isPast();
                        }
                        $stock = $lot->in_qty - $lot->out_qty;
                    @endphp

                    <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        @if($product->is_serial == 'yes')
                            <td>
                                <strong>{{ $lot->serial_no }}</strong>
                            </td>
                            <td>
                                {{ isset($lot->source->manufacture_date) ? \Carbon\Carbon::parse($lot->source->manufacture_date)->format('d M Y') : 'N/A' }}
                            </td>
                        @else
                            <td>
                                <strong>{{ $lot->lot_no }}</strong>
                            </td>
                            <td>
                                {{ $expiryDate ? $expiryDate->format('d M Y') : 'N/A' }}
                            </td>
                        @endif
                        <td class="text-right">
                            <span class="badge badge-round badge-{{ $stock > 0 ? 'info' : 'secondary' }} p-2">
                                {{ number_format($stock) }}
                            </span>
                        </td>
                      
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $product->is_serial == 'yes' ? '5' : '5' }}" class="text-center py-4">
                            <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                            <p class="mb-0">No batch/expiry information available</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if($expiredData->count() > 0)
                <tfoot class="font-weight-bold">
                    <tr>
                        <td colspan="{{ $product->is_serial == 'yes' ? '3' : '3' }}" class="text-right">
                            <strong>Grand Total Stock:</strong>
                        </td>
                        <td class="text-right">
                            <span class="badge badge-round badge-primary p-2" style="font-size: 14px;">
                                {{ number_format($totalStock) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

<script>
function printExpiredInfo() {
    const printContent = document.getElementById('expiredInfoTable').innerHTML;
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Expired/Batch Information</title>');
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
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 15px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #ffc107; color: #000; }');
    printWindow.document.write('.text-right { text-align: right; }');
    printWindow.document.write('.text-center { text-align: center; }');
    printWindow.document.write('.badge { padding: 4px 8px; border-radius: 4px; }');
    printWindow.document.write('.badge-success { background-color: #28a745; color: white; }');
    printWindow.document.write('.badge-danger { background-color: #dc3545; color: white; }');
    printWindow.document.write('.badge-info { background-color: #17a2b8; color: white; }');
    printWindow.document.write('.badge-primary { background-color: #007bff; color: white; }');
    printWindow.document.write('.badge-secondary { background-color: #6c757d; color: white; }');
    printWindow.document.write('.table-danger { background-color: #f8d7da; }');
    printWindow.document.write('tfoot { font-weight: bold; }');
    printWindow.document.write('.product-info { margin-bottom: 15px; font-size: 14px; }');
    
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
    
    printWindow.document.write('<h2 style="text-align: center; margin-top: 15px;">Expired/Batch Information</h2>');
    printWindow.document.write('<div class="product-info"><strong>Product:</strong> {{ $product->name }}</div>');
    @if($product->model)
    printWindow.document.write('<div class="product-info"><strong>Model:</strong> {{ $product->model }}</div>');
    @endif
    @if($product->brand)
    printWindow.document.write('<div class="product-info"><strong>Brand:</strong> {{ $product->brand->name }}</div>');
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

    /* Table Styles */
    .expired-info-container .table td,
    .expired-info-container .table th {
        padding: 10px;
        vertical-align: middle;
    }

    .table-danger {
        background-color: #f8d7da !important;
    }

    .table-warning {
        background-color: #fff3cd !important;
    }
    
    .badge {
        font-size: 13px;
    }
</style>