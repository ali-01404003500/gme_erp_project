<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock Balance Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="12" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="12" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="12" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="12" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                STOCK BALANCE REPORT WITH COSTING AND SALES VALUE
            </th>
        </tr>
        
        <!-- Filter Info -->
        <tr>
            <th colspan="12" style="text-align:center;font-size:10px;background:#F2F2F2;">
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
            </th>
        </tr>

        <!-- Spacer -->
        <tr><th colspan="12">&nbsp;</th></tr>

        <!-- Main Column Headers -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <th rowspan="2">SL</th>
            <th colspan="2">Product Info</th>
            <th rowspan="2">Current Stock</th>
            <th colspan="2" style="background:#17a2b8;">USD Price</th>
            <th colspan="2" style="background:#ffc107;color:#212529;">MRP Price</th>
            <th colspan="2" style="background:#6c757d;">Costing Price</th>
            <th colspan="2" style="background:#28a745;">Avg. Selling Price (Last 5 Sales)</th>
        </tr>

        <!-- Sub Column Headers -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <th>Product Name</th>
            <th>Brand Name</th>
            <th style="background:#17a2b8;">Unit Price (USD)</th>
            <th style="background:#17a2b8;">Total (USD)</th>
            <th style="background:#ffc107;color:#212529;">MRP Price (BDT)</th>
            <th style="background:#ffc107;color:#212529;">Total (BDT)</th>
            <th style="background:#6c757d;">Costing Price (BDT)</th>
            <th style="background:#6c757d;">Total (BDT)</th>
            <th style="background:#28a745;">Avg. Selling Price (BDT)</th>
            <th style="background:#28a745;">Total (BDT)</th>
        </tr>
    </thead>

    <tbody>
        @forelse($reportData as $index => $item)
            @php
                $productName = $item->product_name;
                if (!empty($item->product_model)) {
                    $productName .= "\nModel: " . $item->product_model;
                }
            @endphp
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{!! nl2br(e($productName)) !!}</td>
                <td>{{ $item->brand_name ?? '-' }}</td>
                <td style="text-align:right;background:#d1ecf1;font-weight:bold;">{{ number_format($item->current_stock) }}</td>
                <td style="text-align:right;">${{ number_format($item->unit_price_usd ?? 0) }}</td>
                <td style="text-align:right;font-weight:bold;">${{ number_format($item->total_usd) }}</td>
                <td style="text-align:right;">৳{{ number_format($item->mrp_price_bdt ?? 0) }}</td>
                <td style="text-align:right;color:#856404;font-weight:bold;">৳{{ number_format($item->total_mrp_bdt) }}</td>
                <td style="text-align:right;">৳{{ number_format($item->costing_price_bdt ?? 0) }}</td>
                <td style="text-align:right;color:#383d41;font-weight:bold;">৳{{ number_format($item->total_costing_bdt) }}</td>
                <td style="text-align:right;">৳{{ number_format($item->avg_selling_price_bdt) }}</td>
                <td style="text-align:right;color:#155724;font-weight:bold;">৳{{ number_format($item->total_avg_sales_bdt) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" style="text-align:center;padding:20px;font-style:italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer -->
        <tr><td colspan="12">&nbsp;</td></tr>

        <!-- Totals Row -->
        @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="5" style="text-align:right;">TOTALS:</td>
            <td style="text-align:right;">${{ number_format($totals['total_usd']) }}</td>
            <td style="text-align:right;">-</td>
            <td style="text-align:right;color:#856404;">৳{{ number_format($totals['total_mrp_bdt']) }}</td>
            <td style="text-align:right;">-</td>
            <td style="text-align:right;color:#383d41;">৳{{ number_format($totals['total_costing_bdt']) }}</td>
            <td style="text-align:right;">-</td>
            <td style="text-align:right;color:#155724;">৳{{ number_format($totals['total_avg_sales_bdt']) }}</td>
        </tr>

        <!-- USD to BDT Conversion Row -->
        @if(!empty($filters['usd_to_bdt_rate']))
        <tr style="background:#D1ECF1;font-weight:bold;">
            <td colspan="5" style="text-align:right;">
                Total USD in BDT (Conversion Rate: {{ number_format($filters['usd_to_bdt_rate']) }}):
            </td>
            <td style="text-align:right;">
                ৳{{ number_format($totals['total_usd'] * $filters['usd_to_bdt_rate']) }}
            </td>
            <td colspan="6"></td>
        </tr>
        @endif
        @endif

        <!-- Spacer -->
        <tr><td colspan="12">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="12" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} 
                | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>