<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Product Wise Stock Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="9" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="9" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="9" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="9" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                PRODUCT WISE STOCK REPORT
            </th>
        </tr>
        
        <!-- Filter Info -->
        <tr>
            <th colspan="9" style="text-align:center;font-size:10px;background:#F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
                @if(!empty($filters['from']) || !empty($filters['to']))
                    | Date Range: 
                    {{ !empty($filters['from']) ? \Carbon\Carbon::parse($filters['from'])->format('d-M-Y') : 'Start' }}
                    to
                    {{ !empty($filters['to']) ? \Carbon\Carbon::parse($filters['to'])->format('d-M-Y') : 'End' }}
                @endif
            </th>
        </tr>

        <!-- Column Headers -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <th>SL</th>
            <th>Branch/Center</th>
            <th>Product Name</th>
            <th style="text-align:right;">Stock</th>
            <th style="text-align:right;">Physical Stock</th>
            <th style="text-align:right;">Avg. Price</th>
            <th style="text-align:right;">Last Price</th>
            <th style="text-align:right;">Avg. Amount</th>
            <th style="text-align:right;">Last Price Amount</th>
        </tr>
    </thead>

    <tbody>
        @forelse($reportData as $index => $item)
            @php
                $productName = $item->product_name;
                if (!empty($item->model)) {
                    $productName .= "\nModel: " . $item->model;
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->branch_name }}</td>
                <td>{!! nl2br(e($productName)) !!}</td>
                <td style="text-align:right;">{{ number_format($item->current_stock) }}</td>
                <td style="text-align:right;background:#d1ecf1;">{{ number_format($item->physical_stock) }}</td>
                <td style="text-align:right;">৳{{ number_format($item->avg_price) }}</td>
                <td style="text-align:right;">৳{{ number_format($item->last_price) }}</td>
                <td style="text-align:right;color:#28a745;font-weight:bold;">৳{{ number_format($item->avg_amount) }}</td>
                <td style="text-align:right;color:#17a2b8;font-weight:bold;">৳{{ number_format($item->last_amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:20px;font-style:italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer -->
        <tr><td colspan="9">&nbsp;</td></tr>

        <!-- Totals Row -->
        @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="3" style="text-align:right;">TOTALS:</td>
            <td style="text-align:right;">{{ number_format($totals['total_stock']) }}</td>
            <td style="text-align:right;">{{ number_format($totals['total_physical_stock']) }}</td>
            <td colspan="2" style="text-align:right;">-</td>
            <td style="text-align:right;color:#28a745;">৳{{ number_format($totals['total_avg_amount']) }}</td>
            <td style="text-align:right;color:#17a2b8;">৳{{ number_format($totals['total_last_amount']) }}</td>
        </tr>
        @endif

        <!-- Spacer -->
        <tr><td colspan="9">&nbsp;</td></tr>

        <!-- Summary Section -->
        <tr style="background:#E7E6E6;font-weight:bold;text-align:center;">
            <td colspan="9">SUMMARY</td>
        </tr>
        <tr style="background:#F2F2F2;">
            <td colspan="3" style="font-weight:bold;">Total Records:</td>
            <td colspan="6">{{ $reportData->count() }}</td>
        </tr>
        <tr style="background:#D4EDDA;">
            <td colspan="3" style="font-weight:bold;">Total Stock Quantity:</td>
            <td colspan="6">{{ number_format($totals['total_stock']) }}</td>
        </tr>
        <tr style="background:#D4EDDA;">
            <td colspan="3" style="font-weight:bold;">Total Physical Stock:</td>
            <td colspan="6">{{ number_format($totals['total_physical_stock']) }}</td>
        </tr>
        <tr style="background:#D1ECF1;">
            <td colspan="3" style="font-weight:bold;">Total Average Amount:</td>
            <td colspan="6">৳{{ number_format($totals['total_avg_amount']) }}</td>
        </tr>
        <tr style="background:#D1ECF1;">
            <td colspan="3" style="font-weight:bold;">Total Last Price Amount:</td>
            <td colspan="6">৳{{ number_format($totals['total_last_amount']) }}</td>
        </tr>

        <!-- Spacer -->
        <tr><td colspan="9">&nbsp;</td></tr>

        <!-- Notes Section -->
        <tr style="background:#FFF3CD;">
            <td colspan="9" style="font-size:10px;padding:8px;">
                <strong>Note:</strong> Physical Stock = Opening Stock + Product Received - Product Delivered (Sales Quantity)
                <br>
                Avg. Price is calculated from the last 5 sales transactions.
            </td>
        </tr>

        <!-- Spacer -->
        <tr><td colspan="9">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="9" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} 
                | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>