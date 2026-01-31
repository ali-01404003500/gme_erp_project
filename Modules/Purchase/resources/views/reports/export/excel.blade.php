<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Purchase Report</title>
</head>
<body>
    <table>
        <thead>
            <!-- Company Header -->
            <tr>
                <th colspan="22" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                    {{ $company_info->company_name ?? 'Company Name' }}
                </th>
            </tr>
            <tr>
                <th colspan="22" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                    {{ $company_info->company_address ?? '' }}
                </th>
            </tr>
            <tr>
                <th colspan="22" style="text-align: center; font-size: 12px; background-color: #D9E1F2;">
                    Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
                </th>
            </tr>
            <tr>
                <th colspan="22" style="text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                    PURCHASE REPORT
                </th>
            </tr>
            <tr>
                <th colspan="22" style="text-align: center; font-size: 10px; background-color: #F2F2F2;">
                    Generated on: {{ now()->format('d-M-Y h:i A') }}
                </th>
            </tr>

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
                if (request('from') && request('to')) {
                    $filtersList[] = 'Date: ' . request('from') . ' to ' . request('to');
                } elseif (request('from')) {
                    $filtersList[] = 'From: ' . request('from');
                } elseif (request('to')) {
                    $filtersList[] = 'To: ' . request('to');
                }
                if (request('min_price')) $filtersList[] = 'Min Amount: ' . request('min_price');
                if (request('max_price')) $filtersList[] = 'Max Amount: ' . request('max_price');
                if (request('requisition_no')) $filtersList[] = 'Invoice: ' . request('requisition_no');
            @endphp

            @if(!empty($filtersList))
                <tr>
                    <th colspan="22" style="background-color: #FFF3CD; padding: 8px; text-align: left;">
                        Applied Filters: {{ implode(' | ', $filtersList) }}
                    </th>
                </tr>
            @endif

            <tr>
                <td colspan="22">&nbsp;</td>
            </tr>

            <!-- Table Headers -->
            <tr style="background-color: #4472C4; color: white; font-weight: bold;">
                <th>SL No.</th>
                <th>Invoice ID</th>
                <th>Invoice Date &amp; Time</th>
                <th>Supplier Name</th>
                <th>Invoice To</th>
                <th>Invoice Status</th>
                <th>Descriptions</th>
                <th>User</th>
                <th>Reference Invoice</th>
                <th>Creation Date</th>
                <th>Invoice Type</th>
                <th>Discount</th>
                <th>Payment Status</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Sells Center</th>
                <th>Purchase Center</th>
                <th>Invoice Amount</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Files/Images</th>
                <th>Payment List</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                $totalPaid = 0;
                $totalDue = 0;
                $totalDiscount = 0;
                $purchaseCount = 0;
                $returnCount = 0;
                $purchaseAmount = 0;
                $returnAmount = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @if ($item['type'] === 'Purchase')
                    @php
                        $requisition = $item['data'];
                        $paidAmount = $requisition->paid_amount;
                        $dueAmount = $requisition->due_amount;

                        $totalAmount += $requisition->net_amount;
                        $totalPaid += $paidAmount;
                        $totalDue += $dueAmount;
                        $totalDiscount += $requisition->discount ?? 0;
                        $purchaseCount++;
                        $purchaseAmount += $requisition->net_amount;

                        // Prepare product details
                        $productDetails = [];
                        foreach ($requisition->requisitionDetails as $detail) {
                            $productName = optional($detail->product)->name ?? 'N/A';
                            $price = $detail->price;
                            $productDetails[] = $productName . ': ' . $price;
                        }
                        $productDetailsStr = implode(', ', $productDetails);

                        // Prepare quantities
                        $quantities = [];
                        foreach ($requisition->requisitionDetails as $detail) {
                            $quantities[] = $detail->quantity;
                        }
                        $quantitiesStr = implode(', ', $quantities);

                        // Prepare payment list - NEW: Support both payment types
                        $paymentIds = [];
                        
                        // Old payment system
                        foreach ($requisition->payment->where('status', 'approved') as $payment) {
                            $paymentIds[] = $payment->payment_id;
                        }
                        
                        // New invoice-wise payment system
                        foreach ($requisition->invoiceWisePaymentInvoices as $iwpi) {
                            if ($iwpi->invoiceWisePayment && $iwpi->invoiceWisePayment->status === 'approved') {
                                $paymentIds[] = $iwpi->invoiceWisePayment->invoice_wise_payment_id;
                            }
                        }

                        $paymentIdsStr = !empty($paymentIds) ? implode(', ', $paymentIds) : 'No Payment';

                        // Payment Status
                        if ($dueAmount <= 0) {
                            $paymentStatus = 'Paid';
                        } elseif ($paidAmount > 0) {
                            $paymentStatus = 'Partial';
                        } else {
                            $paymentStatus = 'Due';
                        }

                        // Invoice Status
                        $statusMap = [0 => 'Pending', 1 => 'Approved', 4 => 'Received'];
                        $invoiceStatus = $statusMap[$requisition->status] ?? 'Rejected';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $requisition->requisition_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($requisition->invoice_date)->format('d-M-Y') }} {{ $requisition->created_at->format('h:i A') }}</td>
                        <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($requisition->warehouse)->name ?? 'N/A' }}</td>
                        <td>{{ $invoiceStatus }}</td>
                        <td>{{ $requisition->description ?? '' }}</td>
                        <td>{{ optional($requisition->createdBy)->name ?? 'N/A' }}</td>
                        <td>{{ $requisition->purchase_invoice ?? '' }}</td>
                        <td>{{ $requisition->created_at->format('Y-m-d') }}</td>
                        <td>Purchase</td>
                        <td style="text-align: right;">{{ $requisition->discount ?? 0 }}</td>
                        <td>{{ $paymentStatus }}</td>
                        <td>{{ $productDetailsStr }}</td>
                        <td>{{ $quantitiesStr }}</td>
                        <td>{{ optional($requisition->customer)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                        <td style="text-align: right;">{{ $requisition->net_amount }}</td>
                        <td style="text-align: right;">{{ $paidAmount }}</td>
                        <td style="text-align: right;">{{ $dueAmount }}</td>
                        <td>{{ $requisition->file_uploads && count($requisition->file_uploads) > 0 ? count($requisition->file_uploads) . ' file(s)' : 'No Files' }}</td>
                        <td>{{ $paymentIdsStr }}</td>
                    </tr>
                @else
                    @php
                        $return = $item['data'];
                        $totalAmount -= $return->net_amount;
                        $totalDiscount += $return->discount ?? 0;
                        $returnCount++;
                        $returnAmount += $return->net_amount;

                        // Prepare product details
                        $productDetails = [];
                        foreach ($return->purchaseReturnDetails as $detail) {
                            $productName = optional($detail->product)->name ?? 'N/A';
                            $price = $detail->price;
                            $productDetails[] = $productName . ': ' . $price;
                        }
                        $productDetailsStr = implode(', ', $productDetails);

                        // Prepare quantities
                        $quantities = [];
                        foreach ($return->purchaseReturnDetails as $detail) {
                            $quantities[] = $detail->quantity;
                        }
                        $quantitiesStr = implode(', ', $quantities);
                    @endphp
                    <tr style="background-color: #FFF3CD;">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $return->invoice_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d-M-Y') }} {{ $return->created_at->format('h:i A') }}</td>
                        <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                        <td>{{ optional($return->requisition->warehouse)->name ?? 'N/A' }}</td>
                        <td>Return</td>
                        <td>{{ $return->remarks ?? '' }}</td>
                        <td>{{ optional($return->createdBy)->name ?? 'N/A' }}</td>
                        <td>{{ $return->reference_invoice ?? '' }}</td>
                        <td>{{ $return->created_at->format('Y-m-d') }}</td>
                        <td>Purchase Return</td>
                        <td style="text-align: right;">{{ $return->discount ?? 0 }}</td>
                        <td>Return</td>
                        <td>{{ $productDetailsStr }}</td>
                        <td>{{ $quantitiesStr }}</td>
                        <td>N/A</td>
                        <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                        <td style="text-align: right;">{{ $return->net_amount }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="22" style="text-align: center; padding: 20px; font-style: italic;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            <!-- Empty Row -->
            <tr>
                <td colspan="22">&nbsp;</td>
            </tr>

            <!-- Grand Total Summary -->
            <tr style="background-color: #D9E1F2; font-weight: bold;">
                <td colspan="11" style="text-align: right;">GRAND TOTALS:</td>
                <td style="text-align: right;">{{ $totalDiscount }}</td>
                <td colspan="5">&nbsp;</td>
                <td style="text-align: right;">{{ $totalAmount }}</td>
                <td style="text-align: right;">{{ $totalPaid }}</td>
                <td style="text-align: right;">{{ $totalDue }}</td>
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="22">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="22" style="background-color: #E7E6E6; font-weight: bold; text-align: center;">
                    DETAILED SUMMARY
                </td>
            </tr>

            <!-- Additional Summary -->
            <tr style="background-color: #F2F2F2;">
                <td colspan="5" style="font-weight: bold;">Total Records:</td>
                <td colspan="17">{{ $reportData->count() }}</td>
            </tr>
            <tr style="background-color: #F2F2F2;">
                <td colspan="5" style="font-weight: bold;">Total Purchase Transactions:</td>
                <td colspan="17">{{ $purchaseCount }}</td>
            </tr>
            <tr style="background-color: #F2F2F2;">
                <td colspan="5" style="font-weight: bold;">Total Purchase Return Transactions:</td>
                <td colspan="17">{{ $returnCount }}</td>
            </tr>
            <tr style="background-color: #D4EDDA;">
                <td colspan="5" style="font-weight: bold;">Total Purchase Amount:</td>
                <td colspan="17">{{ $purchaseAmount }}</td>
            </tr>
            <tr style="background-color: #F8D7DA;">
                <td colspan="5" style="font-weight: bold;">Total Return Amount:</td>
                <td colspan="17">{{ $returnAmount }}</td>
            </tr>
            <tr style="background-color: #E7E6E6; font-weight: bold;">
                <td colspan="5" style="font-weight: bold;">Net Purchase Amount:</td>
                <td colspan="17">{{ $totalAmount }}</td>
            </tr>
            <tr style="background-color: #D4EDDA; font-weight: bold;">
                <td colspan="5" style="font-weight: bold;">Total Paid Amount:</td>
                <td colspan="17">{{ $totalPaid }}</td>
            </tr>
            <tr style="background-color: #F8D7DA; font-weight: bold;">
                <td colspan="5" style="font-weight: bold;">Total Due Amount:</td>
                <td colspan="17" style="color: red;">{{ $totalDue }}</td>
            </tr>
            <tr style="background-color: #F2F2F2;">
                <td colspan="5" style="font-weight: bold;">Total Discount:</td>
                <td colspan="17">{{ $totalDiscount }}</td>
            </tr>

            <tr>
                <td colspan="22">&nbsp;</td>
            </tr>

            <tr style="background-color: #F2F2F2;">
                <td colspan="22" style="text-align: center; font-size: 10px; font-style: italic;">
                    Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>