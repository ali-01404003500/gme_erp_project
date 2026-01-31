<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shipment Explorer Report</title>
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
        
        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 5px;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
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
    <div class="report-title">SHIPMENT EXPLORER REPORT</div>
    
    <div style="text-align: center; font-size: 7px; color: #666; margin: 3px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 2%;">SL</th>
                @if(in_array('invoice-id', $selectedColumns))
                <th style="width: 5%;">Invoice ID</th>
                @endif
                @if(in_array('datetime', $selectedColumns))
                <th style="width: 5%;">Date & Time</th>
                @endif
                @if(in_array('customer', $selectedColumns))
                <th style="width: 7%;">Customer</th>
                @endif
                @if(in_array('courier', $selectedColumns))
                <th style="width: 5%;">Courier</th>
                @endif
                @if(in_array('status', $selectedColumns))
                <th style="width: 4%;">Status</th>
                @endif
                @if(in_array('shipment-type', $selectedColumns))
                <th style="width: 4%;">Type</th>
                @endif
                @if(in_array('amount', $selectedColumns))
                <th style="width: 4%;">Inv Amt</th>
                @endif
                @if(in_array('additional', $selectedColumns))
                <th style="width: 4%;">Add Amt</th>
                @endif
                @if(in_array('conditional', $selectedColumns))
                <th style="width: 4%;">Cond Amt</th>
                @endif
                @if(in_array('remarks', $selectedColumns))
                <th style="width: 6%;">Remarks</th>
                @endif
                @if(in_array('carton', $selectedColumns))
                <th style="width: 4%;">Carton</th>
                @endif
                @if(in_array('receipt-date', $selectedColumns))
                <th style="width: 4%;">Receipt Date</th>
                @endif
                @if(in_array('receipt-no', $selectedColumns))
                <th style="width: 4%;">Receipt No</th>
                @endif
                @if(in_array('service-charge', $selectedColumns))
                <th style="width: 3%;">Svc Chg</th>
                @endif
                @if(in_array('service-type', $selectedColumns))
                <th style="width: 3%;">Svc Type</th>
                @endif
                @if(in_array('delivery-charge', $selectedColumns))
                <th style="width: 3%;">Del Chg</th>
                @endif
                @if(in_array('delivery-type', $selectedColumns))
                <th style="width: 3%;">Del Type</th>
                @endif
                @if(in_array('other-charge', $selectedColumns))
                <th style="width: 3%;">Oth Chg</th>
                @endif
                @if(in_array('other-type', $selectedColumns))
                <th style="width: 3%;">Oth Type</th>
                @endif
                @if(in_array('attachment', $selectedColumns))
                <th style="width: 3%;">Files</th>
                @endif
                @if(in_array('update-by', $selectedColumns))
                <th style="width: 4%;">Update By</th>
                @endif
                @if(in_array('collection-by', $selectedColumns))
                <th style="width: 4%;">Collection By</th>
                @endif
                @if(in_array('approved-by', $selectedColumns))
                <th style="width: 4%;">Approved By</th>
                @endif
                @if(in_array('user', $selectedColumns))
                <th style="width: 4%;">User</th>
                @endif
                @if(in_array('complete-date', $selectedColumns))
                <th style="width: 4%;">Complete</th>
                @endif
                @if(in_array('challan', $selectedColumns))
                <th style="width: 4%;">Challan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $totalInvoiceAmount = 0;
                $totalAdditionalAmount = 0;
                $totalConditionalAmount = 0;
                $totalServiceCharge = 0;
                $totalDeliveryCharge = 0;
                $totalOtherCharge = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @php
                    $totalInvoiceAmount += $item['invoice_amount'];
                    $totalAdditionalAmount += $item['additional_cond_amt'];
                    $totalConditionalAmount += $item['conditional_amount'] ?? 0;
                    $totalServiceCharge += $item['service_charge'];
                    $totalDeliveryCharge += $item['delivery_charge'];
                    $totalOtherCharge += $item['other_charge'];

                    $statusClass = match($item['status']) {
                        'Complete' => 'badge-success',
                        'Request' => 'badge-warning',
                        'Updated' => 'badge-info',
                        'Pending' => 'badge-secondary',
                        default => 'badge-secondary'
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @if(in_array('invoice-id', $selectedColumns))
                    <td>{{ $item['invoice_id'] }}</td>
                    @endif
                    @if(in_array('datetime', $selectedColumns))
                    <td>
                        {{ \Carbon\Carbon::parse($item['invoice_date'])->format('d-M-Y') }}<br>
                        <span class="small-text">{{ $item['invoice_time'] }}</span>
                    </td>
                    @endif
                    @if(in_array('customer', $selectedColumns))
                    <td>{{ $item['customer_name'] }}</td>
                    @endif
                    @if(in_array('courier', $selectedColumns))
                    <td>{{ $item['courier_name'] }}</td>
                    @endif
                    @if(in_array('status', $selectedColumns))
                    <td><span class="badge {{ $statusClass }}">{{ $item['status'] }}</span></td>
                    @endif
                    @if(in_array('shipment-type', $selectedColumns))
                    <td>{{ $item['shipment_type'] }}</td>
                    @endif
                    @if(in_array('amount', $selectedColumns))
                    <td class="text-right">{{ number_format($item['invoice_amount']) }}</td>
                    @endif
                    @if(in_array('additional', $selectedColumns))
                    <td class="text-right">{{ number_format($item['additional_cond_amt']) }}</td>
                    @endif
                    @if(in_array('conditional', $selectedColumns))
                    <td class="text-right">
                        {{ $item['conditional_amount'] !== null ? number_format($item['conditional_amount']) : '' }}
                    </td>
                    @endif
                    @if(in_array('remarks', $selectedColumns))
                    <td>{{ $item['con_additional_remarks'] }}</td>
                    @endif
                    @if(in_array('carton', $selectedColumns))
                    <td>{{ $item['carton_no'] }}</td>
                    @endif
                    @if(in_array('receipt-date', $selectedColumns))
                    <td>{{ $item['receipt_date'] }}</td>
                    @endif
                    @if(in_array('receipt-no', $selectedColumns))
                    <td>{{ $item['receipt_no'] }}</td>
                    @endif
                    @if(in_array('service-charge', $selectedColumns))
                    <td class="text-right">{{ number_format($item['service_charge']) }}</td>
                    @endif
                    @if(in_array('service-type', $selectedColumns))
                    <td>{{ $item['service_type'] }}</td>
                    @endif
                    @if(in_array('delivery-charge', $selectedColumns))
                    <td class="text-right">{{ number_format($item['delivery_charge']) }}</td>
                    @endif
                    @if(in_array('delivery-type', $selectedColumns))
                    <td>{{ $item['delivery_type'] }}</td>
                    @endif
                    @if(in_array('other-charge', $selectedColumns))
                    <td class="text-right">{{ number_format($item['other_charge']) }}</td>
                    @endif
                    @if(in_array('other-type', $selectedColumns))
                    <td>{{ $item['other_type'] }}</td>
                    @endif
                    @if(in_array('attachment', $selectedColumns))
                    <td class="text-center">{{ !empty($item['attachment']) ? 'Yes' : 'No' }}</td>
                    @endif
                    @if(in_array('update-by', $selectedColumns))
                    <td>{{ $item['update_by'] }}</td>
                    @endif
                    @if(in_array('collection-by', $selectedColumns))
                    <td>{{ $item['collection_by'] }}</td>
                    @endif
                    @if(in_array('approved-by', $selectedColumns))
                    <td>{{ $item['approved_by'] }}</td>
                    @endif
                    @if(in_array('user', $selectedColumns))
                    <td>{{ $item['user'] }}</td>
                    @endif
                    @if(in_array('complete-date', $selectedColumns))
                    <td>{{ $item['complete_date'] }}</td>
                    @endif
                    @if(in_array('challan', $selectedColumns))
                    <td>{{ $item['challan_no'] }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) + 1 }}" class="text-center" style="padding: 15px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="grand-total-row">
                <td colspan="{{ in_array('invoice-id', $selectedColumns) + in_array('datetime', $selectedColumns) + in_array('customer', $selectedColumns) + in_array('courier', $selectedColumns) + in_array('status', $selectedColumns) + in_array('shipment-type', $selectedColumns) + 1 }}" class="text-right"><strong>TOTALS:</strong></td>
                @if(in_array('amount', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalInvoiceAmount) }}</strong></td>
                @endif
                @if(in_array('additional', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalAdditionalAmount) }}</strong></td>
                @endif
                @if(in_array('conditional', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalConditionalAmount) }}</strong></td>
                @endif
                @if(in_array('remarks', $selectedColumns) || in_array('carton', $selectedColumns) || in_array('receipt-date', $selectedColumns) || in_array('receipt-no', $selectedColumns))
                <td colspan="{{ in_array('remarks', $selectedColumns) + in_array('carton', $selectedColumns) + in_array('receipt-date', $selectedColumns) + in_array('receipt-no', $selectedColumns) }}"></td>
                @endif
                @if(in_array('service-charge', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalServiceCharge) }}</strong></td>
                @endif
                @if(in_array('service-type', $selectedColumns))
                <td></td>
                @endif
                @if(in_array('delivery-charge', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalDeliveryCharge) }}</strong></td>
                @endif
                @if(in_array('delivery-type', $selectedColumns))
                <td></td>
                @endif
                @if(in_array('other-charge', $selectedColumns))
                <td class="text-right"><strong>{{ number_format($totalOtherCharge) }}</strong></td>
                @endif
                <td colspan="{{ count($selectedColumns) - (in_array('invoice-id', $selectedColumns) + in_array('datetime', $selectedColumns) + in_array('customer', $selectedColumns) + in_array('courier', $selectedColumns) + in_array('status', $selectedColumns) + in_array('shipment-type', $selectedColumns) + in_array('amount', $selectedColumns) + in_array('additional', $selectedColumns) + in_array('conditional', $selectedColumns) + in_array('remarks', $selectedColumns) + in_array('carton', $selectedColumns) + in_array('receipt-date', $selectedColumns) + in_array('receipt-no', $selectedColumns) + in_array('service-charge', $selectedColumns) + in_array('service-type', $selectedColumns) + in_array('delivery-charge', $selectedColumns) + in_array('delivery-type', $selectedColumns) + in_array('other-charge', $selectedColumns)) }}"></td>
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