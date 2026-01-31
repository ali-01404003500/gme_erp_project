<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Service Explorer Report</title>
    <style>
        @page {
            margin: 10mm 8mm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 6.5px;
        }

        th {
            background-color: #4472C4;
            color: white;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 7px;
        }

        td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 6.5px;
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
        }

        .text-danger {
            color: #dc3545;
        }

        .total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 8px;
        }

        .grand-total-row {
            background-color: #4472C4 !important;
            color: white !important;
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
            font-size: 6px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-light {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }

        .solution-item {
            margin-bottom: 3px;
            padding-left: 5px;
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
    <div class="report-title">
        SERVICE EXPLORER REPORT
    </div>

    <div style="text-align: center; font-size: 8px; color: #666; margin: 5px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 2%;">SL</th>
                <th style="width: 6%;">Token ID</th>
                <th style="width: 10%;">Customer</th>
                <th style="width: 8%;">Product</th>
                <th style="width: 6%;">Serial No</th>
                <th style="width: 8%;">Problem Type</th>
                <th style="width: 20%;">Solution</th>
                <th style="width: 5%;">Status</th>
                <th style="width: 6%;">Service Date</th>
                <th style="width: 5%;">Type</th>
                <th style="width: 6%;">Assign By</th>
                <th style="width: 6%;">Complete Date</th>
                <th style="width: 6%;">Engineer</th>
                <th style="width: 5%;">Service Bill</th>
                <th style="width: 5%;">Product Bill</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalServiceBill = 0;
                $totalProductBill = 0;
            @endphp

            @forelse($reportData as $index => $token)
                @php
                    // Calculate bills
                    $serviceBill = 0;
                    $productBill = 0;
                    if ($token->serviceMyTask) {
                        foreach ($token->serviceMyTask->bills as $bill) {
                            if ($bill->product && $bill->product->tag && stripos($bill->product->tag->name, 'service') !== false) {
                                $serviceBill += $bill->amount;
                            } else {
                                $productBill += $bill->amount;
                            }
                        }
                    }

                    $totalServiceBill += $serviceBill;
                    $totalProductBill += $productBill;

                    // Get status
                    $status = $token->action ?? 'N/A';
                    $statusClass = match ($status) {
                        'Live' => 'badge-primary',
                        'Started' => 'badge-info',
                        'Done' => 'badge-success',
                        'Cancelled' => 'badge-danger',
                        default => 'badge-secondary',
                    };

                    // Get engineer name
                    $engineerName = 'N/A';
                    if ($token->engineerAssign && $token->engineerAssign->engineers) {
                        $engineerName = $token->engineerAssign->engineers->pluck('full_name')->join(', ');
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong class="text-primary">{{ $token->service->service_unique_id ?? 'N/A' }}</strong>
                    </td>
                    <td>
                        <strong>{{ $token->customer->company_name ?? 'N/A' }}</strong><br>
                        <span class="small-text">{{ $token->customer->phone ?? '' }}</span>
                    </td>
                    <td>
                        <strong>{{ $token->product->name ?? 'N/A' }}</strong>
                        @if ($token->product && $token->product->model_no)
                            <br><span class="small-text">{{ $token->product->model_no }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-light">{{ $token->serial_number ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $token->problem_type ?? 'N/A' }}</td>
                    <td>
                        @if ($token->serviceMyTask)
                            @if ($token->serviceMyTask->pendingServiceTokens && $token->serviceMyTask->pendingServiceTokens->count() > 0)
                                @foreach ($token->serviceMyTask->pendingServiceTokens as $idx => $pendingToken)
                                    <div class="solution-item">
                                        <strong>{{ $idx + 1 }}.</strong> {{ $pendingToken->description ?? 'N/A' }}
                                    </div>
                                @endforeach
                            @else
                                {{ $token->serviceMyTask->description ?? 'N/A' }}
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $statusClass }}">{{ $status }}</span>
                    </td>
                    <td class="text-center">
                        {{ $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A' }}
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $token->service_type ?? 'N/A' }}</span>
                    </td>
                    <td>
                        {{ $token->service->createdBy->name ?? 'N/A' }}
                    </td>
                    <td class="text-center">
                        @if ($token->serviceMyTask && $token->serviceMyTask->updated_at)
                            {{ $token->serviceMyTask->updated_at->format('d-M-Y') }}<br>
                            <span class="small-text">{{ $token->serviceMyTask->updated_at->format('h:i A') }}</span>
                        @else
                            <span class="text-danger">Pending</span>
                        @endif
                    </td>
                    <td>{{ $engineerName }}</td>
                    <td class="text-right">
                        <strong class="text-success">{{ number_format($serviceBill) }}</strong>
                    </td>
                    <td class="text-right">
                        <strong class="text-info">{{ number_format($productBill) }}</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            @if ($reportData->count() > 0)
                <tr class="total-row">
                    <td colspan="13" class="text-right"><strong>TOTAL SUMMARY:</strong></td>
                    <td class="text-right">
                        <strong>{{ number_format($totalServiceBill) }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($totalProductBill) }}</strong>
                    </td>
                </tr>
                <tr class="grand-total-row">
                    <td colspan="13" class="text-right"><strong>GRAND TOTAL:</strong></td>
                    <td colspan="2" class="text-right">
                        <strong>{{ number_format($totalServiceBill + $totalProductBill) }}</strong>
                    </td>
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