<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Balance Details Report</title>
    <style>
        @page {
            margin: 10mm 8mm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
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
            text-align: center;
            font-size: 9px;
            color: #666;
            margin: 5px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
        }

        th {
            background-color: #4472C4;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 8px;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .text-success {
            color: #28a745;
            font-weight: bold;
        }

        .total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 9px;
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
            font-size: 7px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 7px;
            border-radius: 3px;
            background-color: #28a745;
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
    <div class="report-title">CUSTOMER BALANCE DETAILS REPORT</div>

    <!-- Filter Information -->
    <div class="filter-info">
        <strong>Date Range:</strong> {{ $filters['start_date'] ?? date('Y-m-01') }} to {{ $filters['end_date'] ?? date('Y-m-d') }} |
        <strong>Due Type:</strong> {{ strtoupper(str_replace('_', ' ', $filters['due_type'])) }} |
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">SL</th>
                <th style="width: 18%;">Customer</th>
                <th style="width: 11%;" class="text-right">Opening Balance</th>
                <th style="width: 11%;" class="text-right">Sales</th>
                <th style="width: 11%;" class="text-right">Sales Return</th>
                <th style="width: 11%;" class="text-right">Collection</th>
                <th style="width: 11%;" class="text-right">Charge</th>
                <th style="width: 11%;" class="text-right">Waiver</th>
                <th style="width: 11%;" class="text-right">Due</th>
                <th style="width: 11%;" class="text-right">Closing Balance</th>
                <th style="width: 8%;" class="text-center">Recovery %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $customer)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $customer['customer_name'] }}</strong>
                        <br>
                        <span class="small-text">{{ $customer['phone'] ?? 'N/A' }}</span>
                        @if($customer['has_machine_code'])
                            <span class="badge">MC</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($customer['opening_balance']) }}</td>
                    <td class="text-right">{{ number_format($customer['sales']) }}</td>
                    <td class="text-right">{{ number_format($customer['sales_return']) }}</td>
                    <td class="text-right">{{ number_format($customer['collection']-$customer['sales_return']) }}</td>
                    <td class="text-right">{{ number_format($customer['charge']) }}</td>
                    <td class="text-right">{{ number_format($customer['waiver']) }}</td>
                    <td class="text-right">
                        <span class="{{ $customer['due'] >= 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($customer['due']) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span class="{{ $customer['closing_balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($customer['closing_balance']) }}
                        </span>
                    </td>
                    <td class="text-center">{{ number_format($customer['recovery_percentage']) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            @if($reportData->count() > 0)
                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>GRAND TOTAL:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totals['total_opening_balance']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totals['total_sales']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totals['total_sales_return']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totals['total_collection']- $totals['total_sales_return']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totals['total_charge']) }}</strong></td>
                     <td class="text-right"><strong>{{ number_format($totals['total_waiver']) }}</strong></td>
                    <td class="text-right text-danger"><strong>{{ number_format($totals['total_due']) }}</strong></td>
                    <td class="text-right text-danger"><strong>{{ number_format($totals['total_closing_balance']) }}</strong></td>
                    <td class="text-center">-</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. |
            Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>