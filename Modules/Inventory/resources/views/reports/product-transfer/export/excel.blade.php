<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Product Transfer Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="11" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="11" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                PRODUCT TRANSFER REPORT
            </th>
        </tr>
        
        <!-- Filter Info -->
        <tr>
            <th colspan="11" style="text-align:center;font-size:10px;background:#F2F2F2;">
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
            <th>Invoice No</th>
            <th>Inv Date / Time</th>
            <th>Transfer Date</th>
            <th>Quantity</th>
            <th>Transfer From</th>
            <th>Transfer To</th>
            <th>Transfer By</th>
            <th>Received By</th>
            <th>Request By</th>
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
                <td style="text-align:center;">{{ $item->invoice_no }}</td>
                <td style="text-align:center;">{{ \Carbon\Carbon::parse($item->inv_date_time)->format('d-M-Y h:i A') }}</td>
                <td style="text-align:center;">{{ \Carbon\Carbon::parse($item->transfer_date)->format('d-M-Y h:i A') }}</td>
                <td style="text-align:center;background:#d1ecf1;font-weight:bold;">{{ number_format($item->quantity) }}</td>
                <td>{{ $item->source_branch_name }}</td>
                <td>{{ $item->destination_branch_name }}</td>
                <td>{{ $item->transferred_by_name ?? '-' }}</td>
                <td>{{ $item->received_by_name ?? '-' }}</td>
                <td>{{ $item->requested_by_name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:20px;font-style:italic;">
                    No transfer records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer -->
        <tr><td colspan="11">&nbsp;</td></tr>

        <!-- Totals Row -->
        @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="5" style="text-align:right;">TOTALS:</td>
            <td style="text-align:center;">{{ number_format($totals['total_quantity']) }}</td>
            <td colspan="5"></td>
        </tr>
        @endif

        <!-- Spacer -->
        <tr><td colspan="11">&nbsp;</td></tr>

        <!-- Summary Section -->
        <tr style="background:#E7E6E6;font-weight:bold;text-align:center;">
            <td colspan="11">SUMMARY</td>
        </tr>
        <tr style="background:#F2F2F2;">
            <td colspan="5" style="font-weight:bold;">Total Records:</td>
            <td colspan="6">{{ $totals['total_records'] }}</td>
        </tr>

        <tr style="background:#D4EDDA;">
            <td colspan="5" style="font-weight:bold;">Total Quantity Transferred:</td>
            <td colspan="6">{{ number_format($totals['total_quantity']) }}</td>
        </tr>

        <!-- Spacer -->
        <tr><td colspan="11">&nbsp;</td></tr>

        <!-- Notes Section -->
        <tr style="background:#FFF3CD;">
            <td colspan="11" style="font-size:10px;padding:8px;">
                <strong>Note:</strong> Quantity is counted based on completed transfer transactions.
                <br>
                Product model information is included where applicable.
            </td>
        </tr>

        <!-- Spacer -->
        <tr><td colspan="11">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="11" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} 
                | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
