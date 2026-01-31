<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Wise Stock Report</title>
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
        .text-success { color: #28a745; font-weight: bold; }
        .text-info { color: #17a2b8; font-weight: bold; }
        
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

        .note-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 8px;
            margin-top: 10px;
            font-size: 7px;
            color: #0c5460;
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
    <div class="report-title">PRODUCT WISE STOCK REPORT</div>
    
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
                <th style="width: 15%;">Branch/Center</th>
                <th style="width: 25%;">Product Name</th>
                <th style="width: 8%;" class="text-right">Stock</th>
                <th style="width: 10%;" class="text-right">Physical Stock</th>
                <th style="width: 9%;" class="text-right">Avg. Price</th>
                <th style="width: 9%;" class="text-right">Last Price</th>
                <th style="width: 10%;" class="text-right">Avg. Amount</th>
                <th style="width: 11%;" class="text-right">Last Price Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->branch_name }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->model)
                            <br><span class="small-text">Model: {{ $item->model }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->current_stock) }}</td>
                    <td class="text-right">
                        <span class="badge">{{ number_format($item->physical_stock) }}</span>
                    </td>
                    <td class="text-right">{{ number_format($item->avg_price) }}</td>
                    <td class="text-right">{{ number_format($item->last_price) }}</td>
                    <td class="text-right text-success">{{ number_format($item->avg_amount) }}</td>
                    <td class="text-right text-info">{{ number_format($item->last_amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-right"><strong>{{ number_format($totals['total_stock']) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($totals['total_physical_stock']) }}</strong></td>
                <td colspan="2" class="text-right"><strong>-</strong></td>
                <td class="text-right text-success"><strong>{{ number_format($totals['total_avg_amount']) }}</strong></td>
                <td class="text-right text-info"><strong>{{ number_format($totals['total_last_amount']) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Note Box -->
    @if($reportData->count() > 0)
    <div class="note-box">
        <strong>Note:</strong> Physical Stock = Opening Stock + Product Received - Product Delivered (Sales Quantity)
        <br>
        Avg. Price is calculated from the last 5 sales transactions.
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>