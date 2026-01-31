<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Service Report</title>
    <style>
         @page {
            margin: 10mm 8mm 20mm 8mm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
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
            font-size: 7px;
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
            font-size: 7px;
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

        .text-info {
            color: #17a2b8;
        }

        .text-warning {
            color: #ffc107;
        }

        .total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 8px;
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

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .problem-solution {
            margin-bottom: 5px;
        }

        .problem-solution strong {
            display: block;
            margin-bottom: 2px;
        }

        .emergency-note {
            border-left: 2px solid #ffc107;
            padding-left: 5px;
            margin: 3px 0;
        }

        /* Page number styling */
        .page-number {
            position: fixed;
            bottom: .01mm;
            right: 8mm;
            font-size: 9px;
            color: #666;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    <!-- Page Number -->
    <div class="page-number"></div>

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
        @if ($reportType == 'product')
            SERVICE REPORT - PRODUCT WISE
        @elseif($reportType == 'customer')
            SERVICE REPORT - CUSTOMER WISE
        @else
            SERVICE REPORT
        @endif
    </div>

    <div style="text-align: center; font-size: 8px; color: #666; margin: 5px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">SL</th>
                <th style="width: 8%;">Service Date</th>
                <th style="width: 15%;">Customer Name & Address</th>
                <th style="width: 7%;">Service Status</th>
                <th style="width: 8%;">Service Type</th>
                <th style="width: 12%;">Problematic Product</th>
                <th style="width: 32%;">Problem & Solution Details</th>
                <th style="width: 15%;">Completion Info</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalServiceFee = 0;
                $totalSparePartsFee = 0;
                $grandTotal = 0;
            @endphp

            @forelse($reportData as $index => $token)
                @php
                    // Calculate fees
                    $serviceFee = 0;
                    $sparePartsFee = 0;
                    if ($token->serviceMyTask) {
                        foreach ($token->serviceMyTask->bills as $bill) {
                            if ($bill->product && stripos($bill->product->tag->name, 'service') !== false) {
                                $serviceFee += $bill->amount;
                            } else {
                                $sparePartsFee += $bill->amount;
                            }
                        }
                    }
                    $totalAmount = $serviceFee + $sparePartsFee;

                    $totalServiceFee += $serviceFee;
                    $totalSparePartsFee += $sparePartsFee;
                    $grandTotal += $totalAmount;

                    // Get status
                    $status = $token->action;
                    $statusClass = match ($status) {
                        'Live' => 'badge-primary',
                        'Started' => 'badge-info',
                        'Done' => 'badge-success',
                        'Cancelled', 'Failed' => 'badge-danger',
                        'Pending' => 'badge-warning',
                        'Junk' => 'badge-secondary',
                        default => 'badge-secondary',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A' }}
                    </td>
                    <td>
                        <strong>{{ $token->customer->company_name ?? 'N/A' }}</strong><br>
                        <span class="small-text">{{ $token->customer->address ?? '' }}</span><br>
                        <span class="text-info small-text">Service ID:
                            {{ $token->service->service_unique_id ?? 'N/A' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $statusClass }}">{{ $status }}</span>
                    </td>
                    <td>{{ $token->service_type ?? 'N/A' }}</td>
                    <td>
                        <strong>{{ $token->product->name ?? 'N/A' }}</strong>
                        @if ($token->product && $token->product->model_no)
                            <br><span class="small-text">Model: {{ $token->product->model_no }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="problem-solution">
                            <strong class="text-danger">Problem:</strong>
                            {{ $token->problem_details ?? 'N/A' }}
                        </div>

                        @if ($token->serviceMyTask)
                            {{-- Multiple Solutions --}}
                            @if ($token->serviceMyTask->pendingServiceTokens && $token->serviceMyTask->pendingServiceTokens->count() > 0)
                                <div class="problem-solution">
                                    <strong class="text-success">Solutions:</strong>
                                    @foreach ($token->serviceMyTask->pendingServiceTokens as $index => $pendingToken)
                                        <div style="margin-left: 10px; margin-top: 3px;">
                                            <strong>{{ $index + 1 }}.</strong>
                                            {{ $pendingToken->description ?? 'N/A' }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="problem-solution">
                                    <strong class="text-success">Solution:</strong>
                                    {{ $token->serviceMyTask->description ?? 'N/A' }}
                                </div>
                            @endif

                            <div class="problem-solution">
                                <strong>Service Fee:</strong> <span
                                    class="text-success">{{ number_format($serviceFee) }}</span>
                            </div>

                            <div class="problem-solution">
                                <strong>Spare Parts Fee:</strong> <span
                                    class="text-info">{{ number_format($sparePartsFee) }}</span>
                            </div>

                            @if ($token->serviceMyTask->bill_description)
                                <div class="problem-solution">
                                    <strong>Remarks:</strong> {{ $token->serviceMyTask->bill_description }}
                                </div>
                            @endif
                        @endif

                        @if ($token->service && $token->service->emergencyNotes->count() > 0)
                         <strong class="text-warning">Emergency Notes:</strong>

                            @foreach ($token->service->emergencyNotes as $note)
                                <div class="emergency-note">
                                    <span class="small-text">
                                        <strong>Call By:</strong> {{ $note->createdBy->name ?? 'N/A' }}<br>
                                        <strong>Date:</strong> {{ $note->created_at->format('d-M-Y h:i A') }}<br>
                                        <strong>Note:</strong> {{ $note->note }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($token->service)
                            <div class="small-text">
                                <strong>Entry By:</strong><br>
                                {{ $token->service->createdBy->name ?? 'N/A' }}<br>
                                {{ $token->service->created_at->format('d-M-Y h:i A') }}
                            </div>
                        @endif

                        @if ($token->serviceMyTask)
                            <div class="small-text" style="margin-top: 5px;">
                                <strong>Complete By:</strong><br>
                                {{ $token->serviceMyTask->createdBy->name ?? 'N/A' }}<br>
                                {{ $token->serviceMyTask->created_at->format('d-M-Y h:i A') }}
                            </div>

                            @if ($token->serviceMyTask->updated_at)
                                <div class="small-text" style="margin-top: 5px;">
                                    <strong>Completion Date:</strong><br>
                                    {{ $token->serviceMyTask->updated_at->format('d-M-Y h:i A') }}
                                </div>
                            @endif
                        @endif

                        @if ($token->service && $token->service->emergencyNotes->count() > 0)
                            <div class="small-text" style="margin-top: 5px;">
                                <strong>Note:</strong><br>
                                {{ $token->service->emergencyNotes->last()->note ?? '' }}
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            @if ($reportData->count() > 0)
                <tr class="total-row">
                    <td colspan="6" class="text-right"><strong>TOTAL AMOUNT:</strong></td>
                    <td colspan="2">
                        <div><strong>Service Fee:</strong> {{ number_format($totalServiceFee) }}</div>
                        <div><strong>Spare Parts Fee:</strong> {{ number_format($totalSparePartsFee) }}</div>
                        <div class="text-primary"><strong>Grand Total:</strong> {{ number_format($grandTotal) }}
                        </div>
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