<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer Balance Details Report</title>
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
                    CUSTOMER BALANCE DETAILS REPORT
                </th>
            </tr>

            <!-- Filter Information -->
            <tr>
                <th colspan="9" style="text-align:center;font-size:10px;background:#F2F2F2;">
                    Date Range: {{ $filters['start_date'] ?? date('Y-m-01') }} to {{ $filters['end_date'] ?? date('Y-m-d') }} |
                    Due Type: {{ strtoupper(str_replace('_', ' ', $filters['due_type'])) }} |
                    Generated on: {{ now()->format('d-M-Y h:i A') }}
                </th>
            </tr>

            <!-- Spacer Row -->
            <tr>
                <td colspan="9">&nbsp;</td>
            </tr>

            <!-- Column Headers -->
            <tr style="background:#4472C4;color:#fff;font-weight:bold;">
                <th>SL</th>
                <th>Customer Name</th>
                <th>Phone No</th>
                <th>Opening Balance</th>
                <th>Sales</th>
                <th>Sales Return</th>
                <th>Collection</th>
                <th>Charge</th>
                <th>Waiver</th>
                <th>Due</th>
                <th>Closing Balance</th>
                <th>Recovery %</th>
            </tr>
        </thead>

        <tbody>
            @forelse($reportData as $index => $customer)
                <tr>
                    <td style="text-align:center; vertical-align: middle;">{{ $index + 1 }}</td>
                    
                    <td style="vertical-align: middle;">
                        <strong>{{ $customer['customer_name'] }}</strong>
                        @if($customer['has_machine_code'])
                            [Machine Code]
                        @endif
                    </td>
                    
                    <td style="vertical-align: middle;">{{ $customer['phone'] ?? 'N/A' }}</td>
                    
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['opening_balance']) }}
                    </td>
                    
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['sales']) }}
                    </td>
                    
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['sales_return']) }}
                    </td>
                    
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['collection']-$customer['sales_return']-$customer['waiver']) }}
                    </td>
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['charge']) }}
                    </td>
                    <td style="text-align:right; vertical-align: middle;">
                        ৳{{ number_format($customer['waiver']) }}
                    </td>
                    <td style="text-align:right; vertical-align: middle;">
                        <span style="color:{{ $customer['due'] >= 0 ? '#dc3545' : '#28a745' }};">
                            ৳{{ number_format($customer['due']) }}
                        </span>
                    </td> 
                    <td style="text-align:right; vertical-align: middle;">
                        <strong style="color:{{ $customer['closing_balance'] >= 0 ? '#dc3545' : '#28a745' }};">
                            ৳{{ number_format($customer['closing_balance']) }}
                        </strong>
                    </td>
                    
                    <td style="text-align:center; vertical-align: middle;">
                        {{ number_format($customer['recovery_percentage']) }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;padding:20px;font-style:italic;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            <!-- Spacer Row -->
            <tr>
                <td colspan="10">&nbsp;</td>
            </tr>

            <!-- Total Row -->
            @if($reportData->count() > 0)
                <tr style="background:#D9E1F2;font-weight:bold;">
                    <td colspan="3" style="text-align:right;font-size:12px;">GRAND TOTAL:</td>
                    <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_opening_balance']) }}</strong>
                    </td>
                    <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_sales']) }}</strong>
                    </td>
                    <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_sales_return']) }}</strong>
                    </td>
                    <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_collection'] - $totals['total_sales_return']-$totals['total_waiver']) }}</strong>
                    </td>
                    <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_charge']) }}</strong>
                    </td>
                     <td style="text-align:right;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_waiver']) }}</strong>
                    </td>
                    <td style="text-align:right;color:#dc3545;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_due']) }}</strong>
                    </td>
                    <td style="text-align:right;color:#dc3545;font-size:12px;">
                        <strong>৳{{ number_format($totals['total_closing_balance']) }}</strong>
                    </td>
                    <td style="text-align:center;">-</td>
                </tr>
            @endif

            <!-- Spacer Row -->
            <tr>
                <td colspan="10">&nbsp;</td>
            </tr>

            <!-- Footer -->
            <tr style="background:#F2F2F2;">
                <td colspan="10" style="text-align:center;font-size:10px;font-style:italic;">
                    Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} |
                    Copyright © {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }} |
                    All rights reserved
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>