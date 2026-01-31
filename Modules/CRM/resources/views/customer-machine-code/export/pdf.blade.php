<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Customer List (Machine Code) Report</title>
    <style>
        @page {
            margin: 10mm 8mm;
            size: A4 portrait;
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

        .total-due-box {
            background-color: #D9E1F2;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
            border: 2px solid #4472C4;
            font-size: 12px;
            font-weight: bold;
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
    <div class="report-title">CUSTOMER LIST (MACHINE CODE) REPORT</div>

    <div style="text-align: center; font-size: 8px; color: #666; margin: 5px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

  

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">SL</th>
                <th style="width: 25%;">Customer Name</th>
                <th style="width: 15%;">Phone No</th>
                <th style="width: 35%;">Address</th>
                <th style="width: 20%;" class="text-right">Due Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDue = 0;
            @endphp

            @forelse($reportData as $index => $customer)
                @php
                    $totalDue += $customer['due_balance'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $customer['customer_name'] }}</strong></td>
                    <td>{{ $customer['phone'] ?? 'N/A' }}</td>
                    <td>{{ $customer['address'] ?? 'N/A' }}</td>
                    <td class="text-right">
                        @if($customer['receivable_balance'] > 0)
                            <div class="text-danger small-text">
                                Receivable: {{ number_format($customer['receivable_balance']) }}
                            </div>
                        @endif
                        @if($customer['advance_balance'] > 0)
                            <div class="text-success small-text">
                                Advance: {{ number_format($customer['advance_balance']) }}
                            </div>
                        @endif
                        <div style="margin-top: 2px;">
                            <strong class="{{ $customer['due_balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                                Net: {{ number_format($customer['due_balance']) }}
                            </strong>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            @if($reportData->count() > 0)
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>TOTAL DUE BALANCE:</strong></td>
                    <td class="text-right text-danger">
                        <strong>{{ number_format($totalDue) }}</strong>
                    </td>
                </tr>
            @endif

            
        </tbody>
    </table>
      <!-- Total Due Balance Box -->
    <div class="total-due-box">
        TOTAL DUE BALANCE: {{ number_format($totalDueBalance) }}
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. |
            Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>

</html>