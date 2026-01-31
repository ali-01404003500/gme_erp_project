<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Service Explorer Report</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="15" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="15" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                SERVICE EXPLORER REPORT
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align:center;font-size:10px;background:#F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>

        <!-- Spacer Row -->
        <tr><th colspan="15" style="background:#fff;">&nbsp;</th></tr>

        <!-- Column Headers -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <th>SL</th>
            <th>Token ID</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Product Name</th>
            <th>Serial Number</th>
            <th>Problem Type</th>
            <th>Solution Description</th>
            <th>Service Status</th>
            <th>Service Date</th>
            <th>Service Type</th>
            <th>Assign By</th>
            <th>Complete Date</th>
            <th>Engineer Name</th>
            <th>Service Bill (৳)</th>
            <th>Product Bill (৳)</th>
            <th>Total Amount (৳)</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalServiceBill = 0.0;
            $totalProductBill = 0.0;
            $grandTotal = 0.0;
        @endphp

        @forelse($reportData as $index => $token)
            @php
                // Calculate bills
                $serviceBill = 0.0;
                $productBill = 0.0;
                if (!empty($token->serviceMyTask) && !empty($token->serviceMyTask->bills)) {
                    foreach ($token->serviceMyTask->bills as $bill) {
                        $amount = floatval($bill->amount ?? 0);
                        if (!empty($bill->product) && 
                            !empty($bill->product->tag) && 
                            stripos($bill->product->tag->name, 'service') !== false) {
                            $serviceBill += $amount;
                        } else {
                            $productBill += $amount;
                        }
                    }
                }
                $totalAmount = $serviceBill + $productBill;
                $totalServiceBill += $serviceBill;
                $totalProductBill += $productBill;
                $grandTotal += $totalAmount;

                // Get status
                $status = $token->action ?? 'N/A';

                // Get engineer name
                $engineerName = 'N/A';
                if (!empty($token->engineerAssign) && !empty($token->engineerAssign->engineers)) {
                    $engineerName = $token->engineerAssign->engineers->pluck('full_name')->join(', ');
                }

                // Build solution text
                $solutionText = '';
                if (!empty($token->serviceMyTask)) {
                    if (!empty($token->serviceMyTask->pendingServiceTokens) && 
                        $token->serviceMyTask->pendingServiceTokens->count() > 0) {
                        
                        $solutions = [];
                        foreach ($token->serviceMyTask->pendingServiceTokens as $idx => $pendingToken) {
                            $solutions[] = ($idx + 1) . ". " . ($pendingToken->description ?? 'N/A');
                        }
                        $solutionText = implode("\n", $solutions);
                    } else {
                        $solutionText = $token->serviceMyTask->description ?? 'N/A';
                    }
                } else {
                    $solutionText = 'N/A';
                }

                // Customer info
                $customerName = $token->customer->company_name ?? 'N/A';
                $customerPhone = $token->customer->phone ?? 'N/A';

                // Product info
                $productName = $token->product->name ?? 'N/A';
                $productModel = $token->product->model_no ?? 'N/A';

                // Token info
                $tokenId = $token->service->service_unique_id ?? 'N/A';

                // Dates
                $serviceDate = $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A';
                $completeDate = 'Pending';
                if (!empty($token->serviceMyTask) && !empty($token->serviceMyTask->updated_at)) {
                    $completeDate = $token->serviceMyTask->updated_at->format('d-M-Y h:i A');
                }

                // Assign by
                $assignBy = $token->service->createdBy->name ?? 'N/A';

                // Service type
                $serviceType = $token->service_type ?? 'N/A';

                // Problem type
                $problemType = $token->problem_type ?? 'N/A';

                // Serial number
                $serialNumber = $token->serial_number ?? 'N/A';
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ e($tokenId) }}</td>
                <td>{{ e($customerName) }}</td>
                <td>{{ e($customerPhone) }}</td>
                <td>{{ e($productName) }}</td>
                <td>{{ e($serialNumber) }}</td>
                <td>{{ e($problemType) }}</td>
                <td>{!! nl2br(e($solutionText)) !!}</td>
                <td>{{ e($status) }}</td>
                <td>{{ e($serviceDate) }}</td>
                <td>{{ e($serviceType) }}</td>
                <td>{{ e($assignBy) }}</td>
                <td>{{ e($completeDate) }}</td>
                <td>{{ e($engineerName) }}</td>
                <td style="text-align:right;">{{ number_format($serviceBill) }}</td>
                <td style="text-align:right;">{{ number_format($productBill) }}</td>
                <td style="text-align:right;">{{ number_format($totalAmount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="18" style="text-align:center;padding:20px;font-style:italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer Row -->
        <tr><td colspan="18" style="background:#fff;">&nbsp;</td></tr>

        <!-- Total Summary Row -->
        @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="15" style="text-align:right;">TOTAL SUMMARY:</td>
            <td style="text-align:right;background:#D4EDDA;">{{ number_format($totalServiceBill) }}</td>
            <td style="text-align:right;background:#D1ECF1;">{{ number_format($totalProductBill) }}</td>
            <td style="text-align:right;">&nbsp;</td>
        </tr>

        <!-- Grand Total Row -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <td colspan="15" style="text-align:right;">GRAND TOTAL:</td>
            <td colspan="3" style="text-align:right;font-size:14px;">৳{{ number_format($grandTotal) }}</td>
        </tr>
        @endif

        <!-- Spacer Row -->
        <tr><td colspan="18" style="background:#fff;">&nbsp;</td></tr>

        <!-- Summary Section -->
        <tr style="background:#E7E6E6;font-weight:bold;text-align:center;">
            <td colspan="18">REPORT SUMMARY</td>
        </tr>
        
        <tr style="background:#F2F2F2;">
            <td colspan="6" style="font-weight:bold;">Total Records:</td>
            <td colspan="12">{{ $reportData->count() }}</td>
        </tr>
        
        <tr style="background:#D4EDDA;">
            <td colspan="6" style="font-weight:bold;">Total Service Fee:</td>
            <td colspan="12">৳{{ number_format($totalServiceBill) }}</td>
        </tr>
        
        <tr style="background:#D1ECF1;">
            <td colspan="6" style="font-weight:bold;">Total Spare Parts Fee:</td>
            <td colspan="12">৳{{ number_format($totalProductBill) }}</td>
        </tr>
        
        <tr style="background:#E7E6E6;font-weight:bold;">
            <td colspan="6" style="font-weight:bold;">Grand Total Amount:</td>
            <td colspan="12" style="color:#0066CC;font-size:14px;">৳{{ number_format($grandTotal) }}</td>
        </tr>

        <!-- Spacer Row -->
        <tr><td colspan="18" style="background:#fff;">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="18" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | 
                Copyright © {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }} | 
                All rights reserved
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>