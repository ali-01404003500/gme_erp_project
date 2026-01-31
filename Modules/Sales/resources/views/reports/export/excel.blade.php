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
            <th colspan="23" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                SALES REPORT
            </th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center; font-size: 10px; background-color: #F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>
        
        <tr>
            <td colspan="23">&nbsp;</td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <th>SN</th>
            <th>Invoice ID</th>
            <th>Invoice Date &amp; Time</th>
            <th>Branch Name</th>
            <th>Customer Name</th>
            <th>Customer Address</th>
            <th>Customer Phone No</th>
            <th>Customer Balance</th>
            <th>Invoice Status</th>
            <th>Remarks</th>
            <th>Prepared By</th>
            <th>Reference Invoice</th>
            <th>Creation Date</th>
            <th>Invoice Type</th>
            <th>Discounts</th>
            <th>Payment Status</th>
            <th>Commitment Date</th>
            <th>Quantity</th>
            <th>Sales Center</th>
            <th>Invoice Amount</th>
            <th>Invoice Amount (Only)</th>
            <th>Approved By</th>
            <th>Images/Files</th>
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
                
                // Get quantity
                $qty = 0;
                if($invoiceType == 'Sales Return') {
                    $qty = $data->salesReturnDetails->sum('quantity');
                } elseif($invoiceType == 'Backup/Challan') {
                    $qty = $data->backupChallanDetails->sum('quantity');
                } else {
                    $qty = $data->salesOrderDetails->sum('quantity');
                }
                $totalQuantity += $qty;
                
                // Get amounts
                $amount = $data->net_amount ?? $data->total_amount ?? 0;
                $amountOnly = $data->total_amount ?? 0;
                
                if($invoiceType == 'Sales Return') {
                    $totalInvoiceAmount -= $amount;
                    $totalInvoiceOnly -= $amountOnly;
                } else {
                    $totalInvoiceAmount += $amount;
                    $totalInvoiceOnly += $amountOnly;
                }
                
                // Get invoice ID label
                switch ($invoiceType) {
                    case 'Sales Return':
                        $invoiceLabel = $data->invoice_no;
                        break;
                    case 'Backup/Challan':
                        $invoiceLabel = $data->invoice_id;
                        break;
                    default:
                        $invoiceLabel = $data->sales_order_id;
                        break;
                }
                
                // Get payment status text
                if(isset($data->paid_status)) {
                    if($data->paid_status == 'paid') {
                        $paymentStatusText = 'Paid';
                    } elseif($data->paid_status == 'due') {
                        $paymentStatusText = 'Due';
                    } else {
                        $paymentStatusText = 'Unpaid';
                    }
                } else {
                    $paymentStatusText = 'N/A';
                }
                
                // Format customer balance
                $balanceDisplay = number_format(abs($customerBalance));
                if($customerBalance > 0) {
                    $balanceDisplay .= ' (Dr)';
                } elseif($customerBalance < 0) {
                    $balanceDisplay .= ' (Cr)';
                }
            @endphp
            <tr style="{{ $invoiceType == 'Sales Return' ? 'background-color: #FFF3CD;' : '' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $invoiceLabel }}</td>
                <td>{{ \Carbon\Carbon::parse($invoiceType == 'Sales Return' ? $data->return_date : $data->invoice_date)->format('d-M-Y') }} {{ $data->created_at->format('h:i A') }}</td>
                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                <td>{{ optional($data->customer)->company_name ?? 'N/A' }}</td>
                <td>{{ optional($data->customer)->address ?? 'N/A' }}</td>
                <td>{{ optional($data->customer)->phone ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ $balanceDisplay }}</td>
                <td>{{ $invoiceStatus }}</td>
                <td>{{ $data->remarks ?? '' }}</td>
                <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                <td>{{ $data->reference_invoice ?? (optional($data->reference)->sales_order_id ?? '') }}</td>
                <td>{{ $data->created_at->format('Y-m-d') }}</td>
                <td>{{ $invoiceType }}</td>
                <td style="text-align: right;">{{ number_format($data->discount ?? 0) }}</td>
                <td>{{ $paymentStatusText }}</td>
                <td>{{ $data->delivery_date ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $qty }}</td>
                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($amount) }}</td>
                <td style="text-align: right;">{{ number_format($amountOnly) }}</td>
                <td>{{ optional($data->approvedBy)->name ?? 'N/A' }}</td>
                <td>{{ isset($data->attachments) && $data->attachments ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="23" style="text-align: center; padding: 20px; font-style: italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="23">&nbsp;</td>
        </tr>

        <!-- Grand Total Summary -->
        <tr style="background-color: #D9E1F2; font-weight: bold;">
            <td colspan="17" style="text-align: right;">GRAND TOTALS:</td>
            <td style="text-align: center;">{{ number_format($totalQuantity) }}</td>
            <td>&nbsp;</td>
            <td style="text-align: right;">{{ number_format($totalInvoiceAmount) }}</td>
            <td style="text-align: right;">{{ number_format($totalInvoiceOnly) }}</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="23">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="23" style="background-color: #E7E6E6; font-weight: bold; text-align: center;">
                DETAILED SUMMARY
            </td>
        </tr>

        <!-- Additional Summary -->
        <tr style="background-color: #F2F2F2;">
            <td colspan="5" style="font-weight: bold;">Total Records:</td>
            <td colspan="18">{{ $reportData->count() }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="5" style="font-weight: bold;">Total Sales Transactions:</td>
            <td colspan="18">{{ $salesCount }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="5" style="font-weight: bold;">Total Sales Return Transactions:</td>
            <td colspan="18">{{ $returnCount }}</td>
        </tr>
        <tr style="background-color: #D4EDDA;">
            <td colspan="5" style="font-weight: bold;">Total Quantity Sold:</td>
            <td colspan="18">{{ number_format($totalQuantity) }}</td>
        </tr>
        <tr style="background-color: #E7E6E6; font-weight: bold;">
            <td colspan="5" style="font-weight: bold;">Total Invoice Amount:</td>
            <td colspan="18">{{ number_format($totalInvoiceAmount) }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="5" style="font-weight: bold;">Total Invoice Amount (Only):</td>
            <td colspan="18">{{ number_format($totalInvoiceOnly) }}</td>
        </tr>
        
        <tr>
            <td colspan="23">&nbsp;</td>
        </tr>
        
        <tr style="background-color: #F2F2F2;">
            <td colspan="23" style="text-align: center; font-size: 10px; font-style: italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>