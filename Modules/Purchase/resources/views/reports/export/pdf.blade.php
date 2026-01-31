<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Report</title>
    <style>
        @page {
            margin: 10mm 5mm;
            size: landscape;
        }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 7px; margin: 0; padding: 0; line-height: 1.2; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .company-name { font-size: 16px; font-weight: bold; margin-bottom: 3px; color: #333; }
        .company-details { font-size: 8px; color: #666; margin-bottom: 2px; }
        .report-title { font-size: 12px; font-weight: bold; margin: 8px 0; background-color: #4472C4; color: white; padding: 6px; text-align: center; }
        .filter-section { background-color: #FFF3CD; padding: 5px; margin: 8px 0; border: 1px solid #FFC107; border-radius: 2px; font-size: 7px; }
        .filter-title { font-weight: bold; font-size: 8px; margin-bottom: 3px; color: #333; }
        .filter-item { font-size: 7px; color: #555; display: inline-block; margin-right: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 6px; }
        th { background-color: #4472C4; color: white; padding: 4px 2px; text-align: left; font-weight: bold; border: 1px solid #333; vertical-align: middle; font-size: 6px; }
        td { padding: 3px 2px; border: 1px solid #ddd; vertical-align: top; font-size: 6px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .return-row { background-color: #FFF3CD !important; }
        .badge { display: inline-block; padding: 2px 4px; border-radius: 2px; font-size: 5px; font-weight: bold; white-space: nowrap; }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .grand-total-row { background-color: #D9E1F2 !important; font-weight: bold; font-size: 7px; }
        .footer { margin-top: 10px; padding-top: 5px; border-top: 1px solid #333; font-size: 6px; text-align: center; color: #666; }
        .product-list { font-size: 6px; line-height: 1.3; }
        .product-item { margin-bottom: 1px; }
        .small-text { font-size: 5px; color: #666; }
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
    <div class="report-title">PURCHASE REPORT</div>

    <div style="text-align: center; font-size: 7px; color: #666; margin: 3px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Applied Filters -->
    @php
        $filtersList = [];
        if (request('supplier_id')) {
            $supplier = $suppliers->find(request('supplier_id'));
            if ($supplier) $filtersList[] = 'Supplier: ' . $supplier->company_name;
        }
        if (request('branch_id')) {
            $branch = $branches->find(request('branch_id'));
            if ($branch) $filtersList[] = 'Branch: ' . $branch->name;
        }
        if (request('user_id')) {
            $user = $users->find(request('user_id'));
            if ($user) $filtersList[] = 'User: ' . $user->name;
        }
        if (request('product_id')) {
            $product = $products->find(request('product_id'));
            if ($product) $filtersList[] = 'Product: ' . $product->name;
        }
        if (request('invoice_type')) $filtersList[] = 'Type: ' . ucfirst(request('invoice_type'));
        if (request('from') && request('to')) $filtersList[] = 'Date: ' . request('from') . ' to ' . request('to');
        elseif (request('from')) $filtersList[] = 'From: ' . request('from');
        elseif (request('to')) $filtersList[] = 'To: ' . request('to');
        if (request('min_price')) $filtersList[] = 'Min Amount: ' . number_format(request('min_price'));
        if (request('max_price')) $filtersList[] = 'Max Amount: ' . number_format(request('max_price'));
        if (request('requisition_no')) $filtersList[] = 'Invoice: ' . request('requisition_no');
    @endphp

    @if(!empty($filtersList))
        <div class="filter-section">
            <div class="filter-title">Applied Filters:</div>
            @foreach($filtersList as $filter)
                <span class="filter-item">{{ $filter }}</span>
            @endforeach
        </div>
    @endif

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 2%;">SL</th>
                <th style="width: 5%;">Invoice ID</th>
                <th style="width: 5%;">Invoice Date & Time</th>
                <th style="width: 7%;">Supplier Name</th>
                <th style="width: 5%;">Invoice To</th>
                <th style="width: 4%;">Invoice Status</th>
                <th style="width: 8%;">Descriptions</th>
                <th style="width: 4%;">User</th>
                <th style="width: 5%;">Reference Invoice</th>
                <th style="width: 4%;">Creation Date</th>
                <th style="width: 4%;">Invoice Type</th>
                <th style="width: 3%;">Discount</th>
                <th style="width: 4%;">Payment Status</th>
                <th style="width: 10%;">Product Price</th>
                <th style="width: 3%;">Quantity</th>
                <th style="width: 5%;">Sells Center</th>
                <th style="width: 6%;">Purchase Center</th>
                <th style="width: 4%;">Invoice Amount</th>
                <th style="width: 4%;">Paid Amount</th>
                <th style="width: 4%;">Due Amount</th>
                <th style="width: 4%;">Files/Images</th>
                <th style="width: 4%;">Payment List</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0; $totalPaid = 0; $totalDue = 0;
                $purchaseCount = 0; $returnCount = 0;
                $purchaseAmount = 0; $returnAmount = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @if ($item['type'] === 'Purchase')
                    @php
                        $requisition = $item['data'];
                        $paidAmount = $requisition->paid_amount;   // accessor
                        $dueAmount = $requisition->due_amount;

                        $totalAmount += $requisition->net_amount;
                        $totalPaid += $paidAmount;
                        $totalDue += $dueAmount;
                        $purchaseCount++;
                        $purchaseAmount += $requisition->net_amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $requisition->requisition_no }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($requisition->invoice_date)->format('d-M-Y') }}<br>
                            <span class="small-text">{{ $requisition->created_at->format('h:i A') }}</span>
                        </td>
                        <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($requisition->warehouse)->name ?? 'N/A' }}</td>
                        <td>
                            @if ($requisition->status == 0) <span class="badge badge-warning">Pending</span>
                            @elseif($requisition->status == 1) <span class="badge badge-success">Approved</span>
                            @elseif($requisition->status == 4) <span class="badge badge-primary">Received</span>
                            @else <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $requisition->description ?? '' }}</td>
                        <td>{{ optional($requisition->createdBy)->name ?? 'N/A' }}</td>
                        <td>{{ $requisition->purchase_invoice ?? '' }}</td>
                        <td>{{ $requisition->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge badge-info">Purchase</span></td>
                        <td class="text-right">{{ number_format($requisition->discount ?? 0) }}</td>
                        <td class="text-center">
                            @if ($dueAmount <= 0) <span class="badge badge-success">Paid</span>
                            @elseif($paidAmount > 0) <span class="badge badge-warning">Partial</span>
                            @else <span class="badge badge-danger">Due</span>
                            @endif
                        </td>
                        <td class="product-list">
                            @foreach ($requisition->requisitionDetails as $detail)
                                <div class="product-item">
                                    {{ optional($detail->product)->name ?? 'N/A' }}:
                                    <strong>{{ number_format($detail->price) }}</strong>
                                </div>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @foreach ($requisition->requisitionDetails as $detail)
                                <div>{{ $detail->quantity }}</div>
                            @endforeach
                        </td>
                        <td>{{ optional($requisition->customer)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                        <td class="text-right"><strong>{{ number_format($requisition->net_amount) }}</strong></td>
                        <td class="text-right text-success">{{ number_format($paidAmount) }}</td>
                        <td class="text-right text-danger">{{ number_format($dueAmount) }}</td>
                        <td class="text-center">
                                                        @php
                                                            $hasFiles = false;
                                                            $fileCount = 0;
                                                        @endphp

                                                        {{-- Check for requisition file uploads --}}
                                                        @php $files = array_filter($requisition->file_uploads ?? []); @endphp
                                                        @if (!empty($files))
                                                            @php $hasFiles = true; @endphp
                                                            @foreach ($files as $file)
                                                                @php $fileCount++; @endphp
                                                                <a href="{{ asset($file) }}" target="_blank"
                                                                    class="badge badge-secondary badge-round mb-1">
                                                                    <i class="fa fa-file"></i> Doc {{ $loop->iteration }}
                                                                </a><br>
                                                            @endforeach
                                                        @endif

                                                        {{-- Check for payment attachments from old payment system --}}
                                                        @if ($requisition->payment->isNotEmpty())
                                                            @foreach ($requisition->payment->where('status', 'approved') as $payment)
                                                                @foreach ($payment->paymentDetails as $detail)
                                                                    @if ($detail->attachments)
                                                                        @php
                                                                            $hasFiles = true;
                                                                            $fileCount++;
                                                                        @endphp
                                                                        <a href="{{ asset($detail->attachments) }}"
                                                                            target="_blank"
                                                                            class="badge badge-info badge-round mb-1">
                                                                            <i class="fa fa-file-invoice"></i> Payment
                                                                            {{ $loop->iteration }}
                                                                        </a><br>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        @endif

                                                        {{-- Check for payment attachments from invoice-wise payment system --}}
                                                        @if ($requisition->invoiceWisePaymentInvoices->isNotEmpty())
                                                            @foreach ($requisition->invoiceWisePaymentInvoices as $iwpi)
                                                                @if ($iwpi->invoiceWisePayment && $iwpi->invoiceWisePayment->status === 'approved')
                                                                    @if ($iwpi->invoiceWisePayment->payments->isNotEmpty())
                                                                        @foreach ($iwpi->invoiceWisePayment->payments as $pay)
                                                                            @if ($pay->attachments)
                                                                                @php
                                                                                    $hasFiles = true;
                                                                                    $fileCount++;
                                                                                @endphp

                                                                                <a href="{{ asset($pay->attachments) }}" target="_blank" class="badge badge-success badge-round mb-1">
                                                                                                        <i class="fa fa-receipt"></i> Invoice Pay {{ $loop->iteration }}
                                                                                                    </a><br>

                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if (!$hasFiles)
                                                            <span class="text-muted">No Files</span>
                                                        @else
                                                            <small class="text-muted d-block mt-1">({{ $fileCount }}
                                                                file(s))</small>
                                                        @endif
                                                    </td>
                        <td class="text-center">
                            @if ($requisition->payment->isNotEmpty())
                                                        @foreach ($requisition->payment->where('status', 'approved') as $payment)
                                                            @foreach ($payment->paymentDetails as $detail)
                                                                <a href="{{ route('account.payments.make-payments.show', $payment->id) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Old receipt">
                                                                    <i class="fa fa-receipt"></i> {{ $payment->payment_id }}
                                                                </a><br>
                                                            @endforeach
                                                        @endforeach
                                                    @endif

                                                    @if ($requisition->invoiceWisePaymentInvoices->isNotEmpty())
                                                        @foreach ($requisition->invoiceWisePaymentInvoices as $iwpi)
                                                            @if ($iwpi->invoiceWisePayment && $iwpi->invoiceWisePayment->status === 'approved')
                                                                <a href="{{ route('account.payments.invoice-wise-payments.show', $iwpi->invoiceWisePayment->id) }}" target="_blank" class="btn btn-sm btn-outline-success mb-1" title="Invoice-wise receipt">
                                                                    <i class="fa fa-file-invoice-dollar"></i> {{ $iwpi->invoiceWisePayment->invoice_wise_payment_id }}
                                                                </a><br>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if ($requisition->payment->isEmpty() && $requisition->invoiceWisePaymentInvoices->isEmpty())
                                                        <span class="text-muted">No Payment</span>
                                                    @endif
                        </td>
                    </tr>
                @else
                    @php
                        $return = $item['data'];
                        $totalAmount -= $return->net_amount;
                        $returnCount++;
                        $returnAmount += $return->net_amount;
                    @endphp
                    <tr class="return-row">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $return->invoice_no }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($return->return_date)->format('d-M-Y') }}<br>
                            <span class="small-text">{{ $return->created_at->format('h:i A') }}</span>
                        </td>
                        <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($return->requisition->warehouse)->name ?? 'N/A' }}</td>
                        <td><span class="badge badge-danger">Return</span></td>
                        <td>{{ $return->remarks ?? '' }}</td>
                        <td>{{ optional($return->createdBy)->name ?? 'N/A' }}</td>
                        <td>{{ $return->reference_invoice ?? '' }}</td>
                        <td>{{ $return->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge badge-danger">Return</span></td>
                        <td class="text-right">{{ number_format($return->discount ?? 0) }}</td>
                        <td class="text-center"><span class="badge badge-secondary">Return</span></td>
                        <td class="product-list">
                            @foreach ($return->purchaseReturnDetails as $detail)
                                <div class="product-item">
                                    {{ optional($detail->product)->name ?? 'N/A' }}:
                                    <strong>{{ number_format($detail->price) }}</strong>
                                </div>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @foreach ($return->purchaseReturnDetails as $detail)
                                <div>{{ $detail->quantity }}</div>
                            @endforeach
                        </td>
                        <td>N/A</td>
                        <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                        <td class="text-right text-danger"><strong>{{ number_format($return->net_amount) }}</strong></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-center">N/A</td>
                        <td class="text-center">N/A</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="22" class="text-center" style="padding: 15px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            <!-- Grand Total -->
            @if ($reportData->count() > 0)
                <tr class="grand-total-row">
                    <td colspan="17" class="text-right"><strong>GRAND TOTAL:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalAmount) }}</strong></td>
                    <td class="text-right text-success"><strong>{{ number_format($totalPaid) }}</strong></td>
                    <td class="text-right text-danger"><strong>{{ number_format($totalDue) }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong> |
            Purchases: {{ $purchaseCount }} |
            Returns: {{ $returnCount }} |
            Purchase Amount: {{ number_format($purchaseAmount) }} |
            Return Amount: {{ number_format($returnAmount) }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. |
            Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>