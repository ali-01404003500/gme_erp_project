<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Balance Report with Costing and Sales Value</title>
    <style>
        @page {
            margin: 8mm 5mm;
            size: A4 landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #333;
        }
        
        .company-details {
            font-size: 8px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
            background-color: #4472C4;
            color: white;
            padding: 6px;
            text-align: center;
        }
        
        .filter-info {
            font-size: 9px;
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 9px;
        }
        
        th {
            background-color: #D1ECF1;
            padding: 4px 2px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 9px;
        }
        
        th.bg-info {
            background-color: #17a2b8;
        }
        
        th.bg-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        th.bg-secondary {
            background-color: #6c757d;
        }
        
        th.bg-success {
            background-color: #28a745;
        }
        
        td {
            padding: 3px 2px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 9px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #28a745; font-weight: bold; }
        .text-warning { color: #ffc107; font-weight: bold; }
        .text-info { color: #17a2b8; font-weight: bold; }
        .text-secondary { color: #6c757d; font-weight: bold; }
        
        .total-row {
            background-color: #E7E6E6 !important;
            font-weight: bold;
            font-size: 7px;
        }
        
        .conversion-row {
            background-color: #D1ECF1 !important;
            font-weight: bold;
            font-size: 7px;
            color: #0c5460;
        }
        
        .footer {
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px solid #333;
            font-size: 9px;
            text-align: center;
            color: #666;
        }
        
        .small-text {
            font-size: 5px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 9px;
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }

        .summary-box {
            margin-top: 8px;
            padding: 6px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            font-size: 7px;
        }

        .summary-item {
            display: inline-block;
            width: 24%;
            margin-right: 1%;
            padding: 4px;
            text-align: center;
        }

        .summary-item.bg-info {
            background-color: #d1ecf1;
            border: 1px solid #17a2b8;
        }

        .summary-item.bg-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
        }

        .summary-item.bg-secondary {
            background-color: #e2e3e5;
            border: 1px solid #6c757d;
        }

        .summary-item.bg-success {
            background-color: #d4edda;
            border: 1px solid #28a745;
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
    <div class="report-title">STOCK BALANCE REPORT WITH COSTING AND SALES VALUE</div>
    
    <div class="filter-info">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
        @if(!empty($filters['branch_id']))
            @php
                $selectedBranch = collect($branches)->firstWhere('id', $filters['branch_id']);
            @endphp
            | Branch: {{ $selectedBranch->name ?? 'All' }}
        @endif
        @if(!empty($filters['from']) || !empty($filters['to']))
            | Date Range: 
            {{ !empty($filters['from']) ? \Carbon\Carbon::parse($filters['from'])->format('d-M-Y') : 'Start' }}
            to
            {{ !empty($filters['to']) ? \Carbon\Carbon::parse($filters['to'])->format('d-M-Y') : 'End' }}
        @endif
        @if(!empty($filters['usd_to_bdt_rate']))
            | USD to BDT Rate: {{ number_format($filters['usd_to_bdt_rate']) }}
        @endif
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 2%;">SL</th>
                <th colspan="2" style="width: 20%;">Product Info</th>
                <th rowspan="2" >Current Stock</th>
                <th colspan="2" style="width: 18%;">USD Price</th>
                <th colspan="2" style="width: 15%;">MRP Price</th>
                <th colspan="2"  style="width: 15%;">Costing Price</th>
                <th colspan="2"  style="width: 15%;">Avg. Selling Price<br>(Last 5 Sales)</th>
            </tr>
            <tr>
                <th style="width: 15%;">Product Name</th>
                <th style="width: 8%;">Brand Name</th>
                <th class="text-right " style="width: 6%;">Unit Price (USD)</th>
                <th class="text-right " style="width: 6%;">Total (USD)</th>
                <th class="text-right " style="width: 7%;">MRP Price (BDT)</th>
                <th class="text-right " style="width: 8%;">Total (BDT)</th>
                <th class="text-right " style="width: 7%;">Costing Price (BDT)</th>
                <th class="text-right " style="width: 8%;">Total (BDT)</th>
                <th class="text-right " style="width: 7%;">Avg. Selling Price (BDT)</th>
                <th class="text-right " style="width: 8%;">Total (BDT)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->product_model)
                            <br><span class="small-text">Model: {{ $item->product_model }}</span>
                        @endif
                    </td>
                    <td>{{ $item->brand_name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge">{{ number_format($item->current_stock) }}</span>
                    </td>
                    <td class="text-right">${{ number_format($item->unit_price_usd ?? 0) }}</td>
                    <td class="text-right" style="font-weight: bold;">${{ number_format($item->total_usd) }}</td>
                    <td class="text-right">{{ number_format($item->mrp_price_bdt ?? 0) }}</td>
                    <td class="text-right text-warning">{{ number_format($item->total_mrp_bdt) }}</td>
                    <td class="text-right">{{ number_format($item->costing_price_bdt ?? 0) }}</td>
                    <td class="text-right text-secondary">{{ number_format($item->total_costing_bdt) }}</td>
                    <td class="text-right">{{ number_format($item->avg_selling_price_bdt) }}</td>
                    <td class="text-right text-success">{{ number_format($item->total_avg_sales_bdt) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center" style="padding: 15px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-right"><strong>${{ number_format($totals['total_usd']) }}</strong></td>
                <td class="text-right"><strong>-</strong></td>
                <td class="text-right text-warning"><strong>{{ number_format($totals['total_mrp_bdt']) }}</strong></td>
                <td class="text-right"><strong>-</strong></td>
                <td class="text-right text-secondary"><strong>{{ number_format($totals['total_costing_bdt']) }}</strong></td>
                <td class="text-right"><strong>-</strong></td>
                <td class="text-right text-success"><strong>{{ number_format($totals['total_avg_sales_bdt']) }}</strong></td>
            </tr>
            @if(!empty($filters['usd_to_bdt_rate']))
            <tr class="conversion-row">
                <td colspan="5" class="text-right">
                    <strong>Total USD in BDT (Rate: {{ number_format($filters['usd_to_bdt_rate']) }}):</strong>
                </td>
                <td class="text-right">
                    <strong>{{ number_format($totals['total_usd'] * $filters['usd_to_bdt_rate']) }}</strong>
                </td>
                <td colspan="6"></td>
            </tr>
            @endif
            @endif
        </tbody>
    </table>

   
  
</body>
</html>