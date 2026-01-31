<div class="modal fade" id="centerDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Center Stock Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="center-detail-container">
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
    </div>

    <div class="mb-3 d-flex justify-content-end">
        <button onclick="printCenterDetail()" class="btn btn-info btn-sm">
            <i class="las la-print"></i> Print
        </button>
    </div>

    <div class="table-responsive" id="centerDetailTable">
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width: 10%;">SL</th>
                    <th style="width: 60%;">Center Name</th>
                    <th class="text-right" style="width: 30%;">Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centerData as $index => $center)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $center->branch_name }}</td>
                        <td class="text-right">
                            <span class="badge badge-round {{ $center->stock > 0 ? 'badge-success' : 'badge-danger' }} p-2">
                                {{ number_format($center->stock) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No stock data available</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="font-weight-bold">
                <tr>
                    <td colspan="2" class="text-right"><strong>Grand Total Stock:</strong></td>
                    <td class="text-right">
                        <span class="badge badge-round badge-primary p-2" style="font-size: 14px;">
                            {{ number_format($totalStock) }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
function printCenterDetail() {
    const printContent = document.getElementById('centerDetailTable').innerHTML;
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Center Stock Detail</title>');
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
    printWindow.document.write('th { background-color: #4472C4; color: white; }');
    printWindow.document.write('.text-right { text-align: right; }');
    printWindow.document.write('.text-center { text-align: center; }');
    printWindow.document.write('.badge { padding: 4px 8px; border-radius: 4px; }');
    printWindow.document.write('.badge-success { background-color: #28a745; color: white; }');
    printWindow.document.write('.badge-danger { background-color: #dc3545; color: white; }');
    printWindow.document.write('.badge-primary { background-color: #007bff; color: white; }');
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
    
    printWindow.document.write('<h2 style="text-align: center; margin-top: 15px;">Center Stock Detail</h2>');
    printWindow.document.write('<div class="product-info"><strong>Product:</strong> {{ $product->name }}</div>');
    @if($product->model)
    printWindow.document.write('<div class="product-info"><strong>Model:</strong> {{ $product->model }}</div>');
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

function exportCenterToExcel() {
    // You can implement Excel export functionality here
    alert('Excel export functionality can be implemented using a backend route');
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
    .center-detail-container .table td,
    .center-detail-container .table th {
        padding: 10px;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 13px;
    }
</style>