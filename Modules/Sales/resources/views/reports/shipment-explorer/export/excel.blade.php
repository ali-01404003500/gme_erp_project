<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                SHIPMENT EXPLORER REPORT
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 10px; background-color: #F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <th>SL No.</th>
            @if(in_array('invoice-id', $selectedColumns))
            <th>Invoice ID</th>
            @endif
            @if(in_array('datetime', $selectedColumns))
            <th>Invoice Date &amp; Time</th>
            @endif
            @if(in_array('customer', $selectedColumns))
            <th>Customer Name</th>
            @endif
            @if(in_array('courier', $selectedColumns))
            <th>Courier Name</th>
            @endif
            @if(in_array('status', $selectedColumns))
            <th>Status</th>
            @endif
            @if(in_array('shipment-type', $selectedColumns))
            <th>Shipment Type</th>
            @endif
            @if(in_array('amount', $selectedColumns))
            <th>Invoice Amount</th>
            @endif
            @if(in_array('additional', $selectedColumns))
            <th>Addition Cond Amt</th>
            @endif
            @if(in_array('conditional', $selectedColumns))
            <th>Conditional Amount</th>
            @endif
            @if(in_array('remarks', $selectedColumns))
            <th>Con-Additional Remarks</th>
            @endif
            @if(in_array('carton', $selectedColumns))
            <th>Carton No.</th>
            @endif
            @if(in_array('receipt-date', $selectedColumns))
            <th>Receipt Date</th>
            @endif
            @if(in_array('receipt-no', $selectedColumns))
            <th>Receipt No.</th>
            @endif
            @if(in_array('service-charge', $selectedColumns))
            <th>Service Charge</th>
            @endif
            @if(in_array('service-type', $selectedColumns))
            <th>Service Type</th>
            @endif
            @if(in_array('delivery-charge', $selectedColumns))
            <th>Delivery Charge</th>
            @endif
            @if(in_array('delivery-type', $selectedColumns))
            <th>Delivery Type</th>
            @endif
            @if(in_array('other-charge', $selectedColumns))
            <th>Other Charge</th>
            @endif
            @if(in_array('other-type', $selectedColumns))
            <th>Other Type</th>
            @endif
            @if(in_array('attachment', $selectedColumns))
            <th>Attachment</th>
            @endif
            @if(in_array('update-by', $selectedColumns))
            <th>Update By</th>
            @endif
            @if(in_array('collection-by', $selectedColumns))
            <th>Collection By</th>
            @endif
            @if(in_array('approved-by', $selectedColumns))
            <th>Approved By</th>
            @endif
            @if(in_array('user', $selectedColumns))
            <th>User</th>
            @endif
            @if(in_array('complete-date', $selectedColumns))
            <th>Complete Date</th>
            @endif
            @if(in_array('challan', $selectedColumns))
            <th>Challan No.</th>
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
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                @if(in_array('invoice-id', $selectedColumns))
                <td>{{ $item['invoice_id'] }}</td>
                @endif
                @if(in_array('datetime', $selectedColumns))
                <td>{{ \Carbon\Carbon::parse($item['invoice_date'])->format('d-M-Y') }} {{ $item['invoice_time'] }}</td>
                @endif
                @if(in_array('customer', $selectedColumns))
                <td>{{ $item['customer_name'] }}</td>
                @endif
                @if(in_array('courier', $selectedColumns))
                <td>{{ $item['courier_name'] }}</td>
                @endif
                @if(in_array('status', $selectedColumns))
                <td>{{ $item['status'] }}</td>
                @endif
                @if(in_array('shipment-type', $selectedColumns))
                <td>{{ $item['shipment_type'] }}</td>
                @endif
                @if(in_array('amount', $selectedColumns))
                <td style="text-align: right;">{{ number_format($item['invoice_amount']) }}</td>
                @endif
                @if(in_array('additional', $selectedColumns))
                <td style="text-align: right;">{{ number_format($item['additional_cond_amt']) }}</td>
                @endif
                @if(in_array('conditional', $selectedColumns))
                <td style="text-align: right;">
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
                <td style="text-align: right;">{{ number_format($item['service_charge']) }}</td>
                @endif
                @if(in_array('service-type', $selectedColumns))
                <td>{{ $item['service_type'] }}</td>
                @endif
                @if(in_array('delivery-charge', $selectedColumns))
                <td style="text-align: right;">{{ number_format($item['delivery_charge']) }}</td>
                @endif
                @if(in_array('delivery-type', $selectedColumns))
                <td>{{ $item['delivery_type'] }}</td>
                @endif
                @if(in_array('other-charge', $selectedColumns))
                <td style="text-align: right;">{{ number_format($item['other_charge']) }}</td>
                @endif
                @if(in_array('other-type', $selectedColumns))
                <td>{{ $item['other_type'] }}</td>
                @endif
                @if(in_array('attachment', $selectedColumns))
                <td>{{ !empty($item['attachment']) ? 'Yes' : 'No' }}</td>
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
                <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; padding: 20px; font-style: italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>

        <!-- Grand Total Summary -->
        <tr style="background-color: #D9E1F2; font-weight: bold;">
            <td colspan="{{ 
                1 + 
                (in_array('invoice-id', $selectedColumns) ? 1 : 0) + 
                (in_array('datetime', $selectedColumns) ? 1 : 0) + 
                (in_array('customer', $selectedColumns) ? 1 : 0) + 
                (in_array('courier', $selectedColumns) ? 1 : 0) + 
                (in_array('status', $selectedColumns) ? 1 : 0) + 
                (in_array('shipment-type', $selectedColumns) ? 1 : 0) 
            }}" style="text-align: right;">GRAND TOTALS:</td>
            @if(in_array('amount', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalInvoiceAmount) }}</td>
            @endif
            @if(in_array('additional', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalAdditionalAmount) }}</td>
            @endif
            @if(in_array('conditional', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalConditionalAmount) }}</td>
            @endif
            @if(in_array('remarks', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('carton', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('receipt-date', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('receipt-no', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('service-charge', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalServiceCharge) }}</td>
            @endif
            @if(in_array('service-type', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('delivery-charge', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalDeliveryCharge) }}</td>
            @endif
            @if(in_array('delivery-type', $selectedColumns))
            <td>&nbsp;</td>
            @endif
            @if(in_array('other-charge', $selectedColumns))
            <td style="text-align: right;">{{ number_format($totalOtherCharge) }}</td>
            @endif
            @php
                $remainingCols = count($selectedColumns) - (
                    (in_array('invoice-id', $selectedColumns) ? 1 : 0) + 
                    (in_array('datetime', $selectedColumns) ? 1 : 0) + 
                    (in_array('customer', $selectedColumns) ? 1 : 0) + 
                    (in_array('courier', $selectedColumns) ? 1 : 0) + 
                    (in_array('status', $selectedColumns) ? 1 : 0) + 
                    (in_array('shipment-type', $selectedColumns) ? 1 : 0) + 
                    (in_array('amount', $selectedColumns) ? 1 : 0) + 
                    (in_array('additional', $selectedColumns) ? 1 : 0) + 
                    (in_array('conditional', $selectedColumns) ? 1 : 0) + 
                    (in_array('remarks', $selectedColumns) ? 1 : 0) + 
                    (in_array('carton', $selectedColumns) ? 1 : 0) + 
                    (in_array('receipt-date', $selectedColumns) ? 1 : 0) + 
                    (in_array('receipt-no', $selectedColumns) ? 1 : 0) + 
                    (in_array('service-charge', $selectedColumns) ? 1 : 0) + 
                    (in_array('service-type', $selectedColumns) ? 1 : 0) + 
                    (in_array('delivery-charge', $selectedColumns) ? 1 : 0) + 
                    (in_array('delivery-type', $selectedColumns) ? 1 : 0) + 
                    (in_array('other-charge', $selectedColumns) ? 1 : 0)
                );
            @endphp
            @if($remainingCols > 0)
            <td colspan="{{ $remainingCols }}">&nbsp;</td>
            @endif
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}" style="background-color: #E7E6E6; font-weight: bold; text-align: center;">
                DETAILED SUMMARY
            </td>
        </tr>

        <!-- Additional Summary -->
        <tr style="background-color: #F2F2F2;">
            <td colspan="3" style="font-weight: bold;">Total Records:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ $reportData->count() }}</td>
        </tr>
        <tr style="background-color: #D4EDDA;">
            <td colspan="3" style="font-weight: bold;">Total Invoice Amount:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalInvoiceAmount) }}</td>
        </tr>
        <tr style="background-color: #E7E6E6; font-weight: bold;">
            <td colspan="3" style="font-weight: bold;">Total Additional Amount:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalAdditionalAmount) }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="3" style="font-weight: bold;">Total Conditional Amount:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalConditionalAmount) }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="3" style="font-weight: bold;">Total Service Charges:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalServiceCharge) }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="3" style="font-weight: bold;">Total Delivery Charges:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalDeliveryCharge) }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="3" style="font-weight: bold;">Total Other Charges:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ number_format($totalOtherCharge) }}</td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>
        
        <tr style="background-color: #F2F2F2;">
            <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 10px; font-style: italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>