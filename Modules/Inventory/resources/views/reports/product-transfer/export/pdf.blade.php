<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Transfer Report</title>
    <style>
        @page {
            margin: 10mm 8mm;
            size: A4 landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }
        
        .company-details {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: center;
        }
        
        .filter-info {
            font-size: 8px;
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 7px;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 7px;
        }
        
        td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 7px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-primary { color: #007bff; font-weight: bold; }
        
        .total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 8px;
        }
        
        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }
        
        .small-text {
            font-size: 6px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6px;
            font-weight: bold;
            background-color: #17a2b8;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
        <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
        <div class="company-details">
            Phone: {{ $company_info->company_phone ?? '' }} | 
            Email: {{ $company_info->company_email ?? '' }}
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">PRODUCT TRANSFER REPORT</div>
    
    <div class="filter-info">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
        @if(!empty($filters['from']) || !empty($filters['to']))
            | Date Range: 
            {{ !empty($filters['from']) ? \Carbon\Carbon::parse($filters['from'])->format('d-M-Y') : 'Start' }}
            to
            {{ !empty($filters['to']) ? \Carbon\Carbon::parse($filters['to'])->format('d-M-Y') : 'End' }}
        @endif
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">SL</th>
                <th style="width: 15%;">Product Name</th>
                <th style="width: 10%;" class="text-center">Inv Date & Time</th>
                <th style="width: 10%;" class="text-center">Transfer Date</th>
                <th style="width: 7%;" class="text-center">Quantity</th>
                <th style="width: 13%;">Transfer From</th>
                <th style="width: 13%;">Transfer To</th>
                <th style="width: 10%;">Transfer By</th>
                <th style="width: 10%;">Received By</th>
                <th style="width: 9%;">Request By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        
                    </td>
                    <td class="text-center">
                        <strong class="text-primary">{{ $item->invoice_no }}</strong><br>
                        <span class="small-text">{{ \Carbon\Carbon::parse($item->inv_date_time)->format('d-M-Y h:i A') }}</span>
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->transfer_date)->format('d-M-Y') }}<br>
                        <span class="small-text">{{ \Carbon\Carbon::parse($item->transfer_date)->format('h:i A') }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge">{{ number_format($item->quantity) }}</span>
                    </td>
                    <td>{{ $item->source_branch_name }}</td>
                    <td>{{ $item->destination_branch_name }}</td>
                    <td>{{ $item->transferred_by_name ?? '-' }}</td>
                    <td>{{ $item->received_by_name ?? '-' }}</td>
                    <td>{{ $item->requested_by_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">
                        No transfer records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-center"><strong>{{ number_format($totals['total_quantity']) }}</strong></td>
                <td colspan="5"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $totals['total_records'] }}</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>