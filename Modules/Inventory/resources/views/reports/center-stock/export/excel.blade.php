<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Center Wise Stock Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="7" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="7" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                CENTER WISE STOCK REPORT
            </th>
        </tr>
        
        <!-- Filter Info -->
        <tr>
            <th colspan="7" style="text-align:center;font-size:10px;background:#F2F2F2;">
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
            <th>Product Name</th>
            <th>Branch/Center</th>
            <th style="text-align:right;">Opening Stock</th>
            <th style="text-align:right;">Received</th>
            <th style="text-align:right;">Delivered</th>
            <th style="text-align:right;">Closing/Current Stock</th>
        </tr>
    </thead>

    <tbody>
        @forelse($reportData as $index => $item)
            @php
                $productName = $item->product->name;
                if (!empty($item->product->model)) {
                    $productName .= "\nModel: " . $item->product->model;
                }
                if (!empty($item->product->brand)) {
                    $productName .= "\nBrand: " . $item->product->brand->name;
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{!! nl2br(e($productName)) !!}</td>
                <td>{{ $item->branch->name }}</td>
                <td >{{ number_format($item->opening_stock) }}</td>
                <td>{{ number_format($item->received) }}</td>
                <td >{{ number_format($item->delivered) }}</td>
                <td>
                    {{ number_format($item->current_stock) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:20px;font-style:italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer -->
        <tr><td colspan="7">&nbsp;</td></tr>

        <!-- Totals Row -->
        @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="3" style="text-align:right;">TOTALS:</td>
            <td style="text-align:right;">{{ number_format($reportData->sum('opening_stock')) }}</td>
            <td style="text-align:right;color:#28a745;">{{ number_format($reportData->sum('received')) }}</td>
            <td style="text-align:right;color:#dc3545;">{{ number_format($reportData->sum('delivered')) }}</td>
            <td style="text-align:right;">{{ number_format($reportData->sum('current_stock')) }}</td>
        </tr>
        @endif

        <!-- Spacer -->
        

        <!-- Spacer -->

        <!-- Notes Section -->


        <!-- Spacer -->
        <tr><td colspan="7">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="7" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} 
                | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>