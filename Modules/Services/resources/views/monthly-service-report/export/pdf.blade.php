<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Service Report</title>
    <style>
        @page {
            margin: 15mm 10mm;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
        }

       
        .report-title {
            font-size: 16px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
            border-radius: 5px;
            padding: 15px 0;
        }

        .period-info {
            text-align: center;
            font-size: 11px;
            color: #666;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: middle;
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

        .text-primary {
            color: #007bff;
            font-weight: bold;
        }

        .text-success {
            color: #28a745;
            font-weight: bold;
        }

        .text-info {
            color: #17a2b8;
            font-weight: bold;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .total-row {
            background-color: #f8f9fa !important;
            font-weight: bold;
            font-size: 11px;
        }

        .total-service {
            background-color: #d4edda !important;
        }

        .total-spare {
            background-color: #d1ecf1 !important;
        }

        .total-grand {
            background-color: #f8d7da !important;
        }

       
        .small-text {
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
   <header class="my-header">
                            @include('partials._for_pdf_header_2nd')
                        </header>

    <!-- Report Title -->
    <div class="report-title">
        MONTHLY SERVICE REPORT
    </div>

    <div class="period-info">
        Report Period: {{ \Carbon\Carbon::parse($filters['from'])->format('d-M-Y') }} to 
        {{ \Carbon\Carbon::parse($filters['to'])->format('d-M-Y') }}
    </div>

    <div style="text-align: center; font-size: 9px; color: #666; margin: 5px 0 15px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">SL</th>
                <th style="width: 42%;">Engineer Name</th>
                <th style="width: 20%;" class="text-right">Service Sales</th>
                <th style="width: 20%;" class="text-right">Spare Sales</th>
                <th style="width: 20%;" class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalServiceSales = 0;
                $totalSpareSales = 0;
                $grandTotal = 0;
            @endphp

            @forelse($engineerReports as $index => $report)
                @php
                    $totalServiceSales += $report['service_sales'];
                    $totalSpareSales += $report['spare_sales'];
                    $grandTotal += $report['total_amount'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong class="text-primary">{{ $report['engineer']->full_name }}</strong><br>
                    </td>
                    <td class="text-right">
                        <strong class="text-success">{{ number_format($report['service_sales']) }}</strong>
                    </td>
                    <td class="text-right">
                        <strong class="text-info">{{ number_format($report['spare_sales']) }}</strong>
                    </td>
                    <td class="text-right">
                        <strong class="text-danger">{{ number_format($report['total_amount']) }}</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">
                        No records found for the selected period
                    </td>
                </tr>
            @endforelse

            @if(count($engineerReports) > 0)
                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>TOTAL SUMMARY:</strong></td>
                    <td class="text-right total-service">
                        <strong>{{ number_format($totalServiceSales) }}</strong>
                    </td>
                    <td class="text-right total-spare">
                        <strong>{{ number_format($totalSpareSales) }}</strong>
                    </td>
                    <td class="text-right total-grand">
                        <strong>{{ number_format($grandTotal) }}</strong>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <footer style="margin-top: 100px">
                        @include('partials._for_pdf_footer')
                    </footer>
</body>
</html>