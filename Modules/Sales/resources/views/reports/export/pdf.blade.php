<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        @page {
            margin: 10mm 5mm;
            size: landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }
        
        .company-details {
            font-size: 8px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
            background-color: #4472C4;
            color: white;
            padding: 6px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 6px;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            padding: 4px 2px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 6px;
        }
        
        td {
            padding: 3px 2px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 6px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .return-row {
            background-color: #FFF3CD !important;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 5px;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .badge-primary { background-color: #007bff; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .badge-dark { background-color: #343a40; color: white; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-success { color: #28a745; font-weight: bold; }
        
        .grand-total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 7px;
        }
        
        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #333;
            font-size: 6px;
            text-align: center;
            color: #666;
        }
        
        .small-text {
            font-size: 5px;
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
    <div class="report-title">SALES REPORT</div>
    
    <div style="text-align: center; font-size: 7px; color: #666; margin: 3px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 2%;">SN</th>
                <th style="width: 5%;">Invoice ID</th>
                <th style="width: 5%;">Invoice Date & Time</th>
                <th style="width: 5%;">Branch Name</th>
                <th style="width: 7%;">Customer Name</th>
                <th style="width: 8%;">Customer Address</th>
                <th style="width: 5%;">Customer Phone</th>
                <th style="width: 4%;">Customer Balance</th>
                <th style="width: 4%;">Invoice Status</th>
                <th style="width: 8%;">Remarks</th>
                <th style="width: 4%;">Prepared By</th>
                <th style="width: 5%;">Reference Invoice</th>
                <th style="width: 4%;">Creation Date</th>
                <th style="width: 4%;">Invoice Type</th>
                <th style="width: 3%;">Discounts</th>
                <th style="width: 4%;">Payment Status</th>
                <th style="width: 4%;">Commitment Date</th>
                <th style="width: 3%;">Quantity</th>
                <th style="width: 5%;">Sales Center</th>
                <th style="width: 4%;">Invoice Amount</th>
                <th style="width: 4%;">Invoice Amount (Only)</th>
                <th style="width: 4%;">Approved By</th>
                <th style="width: 3%;">Files</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantity = 0;
                $totalInvoiceAmount = 0;
                $totalInvoiceOnly = 0;
                $salesCount = 0;
                $returnCount = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @php
                    $data = $item['data'];
                    $invoiceType = $item['invoice_type'];
                    $invoiceStatus = $item['invoice_status'];
                    $customerBalance = $item['customer_balance'] ?? 0;
                    
                    if($invoiceType == 'Sales Return') {
                        $returnCount++;
                    } else {
                        $salesCount++;
                    }
                @endphp
                <tr class="{{ $invoiceType == 'Sales Return' ? 'return-row' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        @php
                            switch ($invoiceType) {
                                case 'Sales Return':
                                    $label = $data->invoice_no;
                                    break;
                                case 'Backup/Challan':
                                    $label = $data->invoice_id;
                                    break;
                                default:
                                    $label = $data->sales_order_id;
                                    break;
                            }
                        @endphp
                        {{ $label }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($invoiceType == 'Sales Return' ? $data->return_date : $data->invoice_date)->format('d-M-Y') }}<br>
                        <span class="small-text">{{ $data->created_at->format('h:i A') }}</span>
                    </td>
                    <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                    <td>{{ optional($data->customer)->company_name ?? 'N/A' }}</td>
                    <td>{{ optional($data->customer)->address ?? 'N/A' }}</td>
                    <td>{{ optional($data->customer)->phone ?? 'N/A' }}</td>
                    <td class="text-right">
                        @php
                            $balanceClass = $customerBalance > 0 ? 'text-danger' : ($customerBalance < 0 ? 'text-success' : '');
                        @endphp
                        <span class="{{ $balanceClass }}">
                            {{ number_format(abs($customerBalance)) }}
                            @if($customerBalance > 0)
                                (Dr)
                            @elseif($customerBalance < 0)
                                (Cr)
                            @endif
                        </span>
                    </td>
                    <td>
                        @php
                            $statusBadgeClass = match ($invoiceStatus) {
                                'Delivered' => 'badge-success',
                                'Pending' => 'badge-warning',
                                'Return' => 'badge-danger',
                                'Undelivered' => 'badge-info',
                                'Cancelled' => 'badge-dark',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <span class="badge {{ $statusBadgeClass }}">{{ $invoiceStatus }}</span>
                    </td>
                    <td>{{ $data->remarks ?? '' }}</td>
                    <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                    <td>{{ $data->reference_invoice ?? (optional($data->reference)->sales_order_id ?? '') }}</td>
                    <td>{{ $data->created_at->format('Y-m-d') }}</td>
                    <td>
                        @php
                            $typeBadgeClass = match ($invoiceType) {
                                'General Sales' => 'badge-primary',
                                'Partial Sales' => 'badge-warning',
                                'Free Sales' => 'badge-success',
                                'Sales Return' => 'badge-danger',
                                'Backup/Challan' => 'badge-secondary',
                                default => 'badge-info',
                            };
                        @endphp
                        <span class="badge {{ $typeBadgeClass }}">{{ $invoiceType }}</span>
                    </td>
                    <td class="text-right">{{ number_format($data->discount ?? 0) }}</td>
                    <td class="text-center">
                        @if(isset($data->paid_status))
                            @if($data->paid_status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @elseif($data->paid_status == 'due')
                                <span class="badge badge-warning">Due</span>
                            @else
                                <span class="badge badge-danger">Unpaid</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $data->delivery_date ?? 'N/A' }}</td>
                    <td class="text-center">
                        @php
                            $qty = 0;
                            if($invoiceType == 'Sales Return') {
                                $qty = $data->salesReturnDetails->sum('quantity');
                            } elseif($invoiceType == 'Backup/Challan') {
                                $qty = $data->backupChallanDetails->sum('quantity');
                            } else {
                                $qty = $data->salesOrderDetails->sum('quantity');
                            }
                            $totalQuantity += $qty;
                        @endphp
                        {{ $qty }}
                    </td>
                    <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                    <td class="text-right">
                        @php
                            $amount = $data->net_amount ?? $data->total_amount ?? 0;
                            if($invoiceType == 'Sales Return') {
                                $totalInvoiceAmount -= $amount;
                            } else {
                                $totalInvoiceAmount += $amount;
                            }
                        @endphp
                        <strong>{{ number_format($amount) }}</strong>
                    </td>
                    <td class="text-right">
                        @php
                            $amountOnly = $data->total_amount ?? 0;
                            if($invoiceType == 'Sales Return') {
                                $totalInvoiceOnly -= $amountOnly;
                            } else {
                                $totalInvoiceOnly += $amountOnly;
                            }
                        @endphp
                        {{ number_format($amountOnly) }}
                    </td>
                    <td>{{ optional($data->approvedBy)->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        {{ isset($data->attachments) && $data->attachments ? 'Yes' : 'No' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="23" class="text-center" style="padding: 15px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="grand-total-row">
                <td colspan="17" class="text-right"><strong>GRAND TOTAL:</strong></td>
                <td class="text-center"><strong>{{ number_format($totalQuantity) }}</strong></td>
                <td></td>
                <td class="text-right"><strong>{{ number_format($totalInvoiceAmount) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($totalInvoiceOnly) }}</strong></td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong> | 
        Sales: {{ $salesCount }} | 
        Returns: {{ $returnCount }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>