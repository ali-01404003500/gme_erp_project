<!-- SUMMARY EXCEL EXPORT -->
<!-- File: export.summary.excel.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Monthly Service Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="5" style="text-align:center;font-size:16px;font-weight:bold;background:#667eea;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="5" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                MONTHLY SERVICE REPORT
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;font-size:11px;background:#F2F2F2;">
                Report Period: {{ \Carbon\Carbon::parse($filters['from'])->format('d-M-Y') }} to 
                {{ \Carbon\Carbon::parse($filters['to'])->format('d-M-Y') }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;font-size:10px;background:#F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>

        <!-- Spacer Row -->
        <tr><th colspan="5" style="background:#fff;">&nbsp;</th></tr>

        <!-- Column Headers -->
        <tr style="background:#667eea;color:#fff;font-weight:bold;">
            <th>SL</th>
            <th>Engineer Name</th>
            <th>Service Sales (৳)</th>
            <th>Spare Sales (৳)</th>
            <th>Total Amount (৳)</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalServiceSales = 0.0;
            $totalSpareSales = 0.0;
            $grandTotal = 0.0;
        @endphp

        @forelse($engineerReports as $index => $report)
            @php
                $totalServiceSales += $report['service_sales'];
                $totalSpareSales += $report['spare_sales'];
                $grandTotal += $report['total_amount'];
            @endphp
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{{ e($report['engineer']->full_name) }}</td>
                <td style="text-align:right;">{{ number_format($report['service_sales']) }}</td>
                <td style="text-align:right;">{{ number_format($report['spare_sales']) }}</td>
                <td style="text-align:right;">{{ number_format($report['total_amount']) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:20px;font-style:italic;">
                    No records found for the selected period
                </td>
            </tr>
        @endforelse

        <!-- Spacer Row -->
        <tr><td colspan="5" style="background:#fff;">&nbsp;</td></tr>

        <!-- Total Summary Row -->
        @if(count($engineerReports) > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="2" style="text-align:right;">TOTAL SUMMARY:</td>
            <td style="text-align:right;background:#D4EDDA;">{{ number_format($totalServiceSales) }}</td>
            <td style="text-align:right;background:#D1ECF1;">{{ number_format($totalSpareSales) }}</td>
            <td style="text-align:right;background:#F8D7DA;">{{ number_format($grandTotal) }}</td>
        </tr>
        @endif

        <!-- Spacer Row -->
        <tr><td colspan="6" style="background:#fff;">&nbsp;</td></tr>

       

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="6" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | 
                Copyright © {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }} | 
                All rights reserved
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>

