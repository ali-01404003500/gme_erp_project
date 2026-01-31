<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Service Report - Excel Export</title>
</head>
<body>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="8" style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align:center;font-size:12px;background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align:center;font-size:12px;background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        
        <!-- Report Title -->
        <tr>
            <th colspan="8" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                @if($reportType == 'product')
                    SERVICE REPORT - PRODUCT WISE
                @elseif($reportType == 'customer')
                    SERVICE REPORT - CUSTOMER WISE
                @else
                    SERVICE REPORT
                @endif
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align:center;font-size:10px;background:#F2F2F2;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>

        <!-- Column Headers -->
        <tr style="background:#4472C4;color:#fff;font-weight:bold;">
            <th>SL</th>
            <th>Service Date</th>
            <th>Customer Name &amp; Address</th>
            <th>Service Status</th>
            <th>Service Type</th>
            <th>Problematic Product</th>
            <th>Problem &amp; Solution Details</th>
            <th>Completion Info</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalServiceFee = 0.0;
            $totalSparePartsFee = 0.0;
            $grandTotal = 0.0;
        @endphp

        @forelse($reportData as $index => $token)
            @php
                // Calculate fees
                $serviceFee = 0.0;
                $sparePartsFee = 0.0;
                if (!empty($token->serviceMyTask) && !empty($token->serviceMyTask->bills)) {
                    foreach ($token->serviceMyTask->bills as $bill) {
                        $amount = floatval($bill->amount ?? 0);
                        if (!empty($bill->product) && stripos($bill->product->tag->name ?? '', 'service') !== false) {
                            $serviceFee += $amount;
                        } else {
                            $sparePartsFee += $amount;
                        }
                    }
                }
                $totalAmount = $serviceFee + $sparePartsFee;
                $totalServiceFee += $serviceFee;
                $totalSparePartsFee += $sparePartsFee;
                $grandTotal += $totalAmount;

                // Get status
                $status = $token->action ?? 'N/A';

                // Build problem & solution text
                $problemText = $token->problem_details ?? 'N/A';
                $problemSolutionText = "Problem:\n{$problemText}\n\n";

                // Multiple Solutions
                if (!empty($token->serviceMyTask)) {
                    if (!empty($token->serviceMyTask->pendingServiceTokens) && 
                        $token->serviceMyTask->pendingServiceTokens->count() > 0) {
                        
                        $problemSolutionText .= "Solutions:\n";
                        foreach ($token->serviceMyTask->pendingServiceTokens as $idx => $pendingToken) {
                            $solutionNum = $idx + 1;
                            $solutionText = $pendingToken->description ?? 'N/A';
                            $solutionStatus = $pendingToken->status ?? 'pending';
                            $problemSolutionText .= "{$solutionNum}. {$solutionText} (Status: {$solutionStatus})\n";
                        }
                        $problemSolutionText .= "\n";
                    } else {
                        // Fallback to main description
                        $solutionText = $token->serviceMyTask->description ?? 'N/A';
                        $problemSolutionText .= "Solution:\n{$solutionText}\n\n";
                    }

                    // Add fees
                    $problemSolutionText .= "Service Fee: ৳" . number_format($serviceFee) . "\n";
                    $problemSolutionText .= "Spare Parts Fee: ৳" . number_format($sparePartsFee) . "\n";
                    $problemSolutionText .= "Total Amount: ৳" . number_format($totalAmount) . "\n\n";
                    
                    // Add remarks
                    if (!empty($token->serviceMyTask->bill_description)) {
                        $problemSolutionText .= "Remarks:\n{$token->serviceMyTask->bill_description}\n\n";
                    }
                }

                // Add emergency notes with proper labels
                if (!empty($token->service) && 
                    !empty($token->service->emergencyNotes) && 
                    $token->service->emergencyNotes->count() > 0) {
                    
                    $problemSolutionText .= "Emergency Notes:\n";
                    foreach ($token->service->emergencyNotes as $noteIndex => $note) {
                        $noteNum = $noteIndex + 1;
                        $creator = $note->createdBy->name ?? 'N/A';
                        $time = optional($note->created_at)->format('d-M-Y h:i A') ?? 'N/A';
                        $noteText = $note->note ?? 'N/A';
                        
                        $problemSolutionText .= "{$noteNum}. Call By: {$creator}\n";
                        $problemSolutionText .= "   Date: {$time}\n";
                        $problemSolutionText .= "   Note: {$noteText}\n\n";
                    }
                }

                // Build completion info
                $completionArr = [];
                if (!empty($token->service)) {
                    $entryBy = $token->service->createdBy->name ?? 'N/A';
                    $entryDate = optional($token->service->created_at)->format('d-M-Y h:i A') ?? 'N/A';
                    $completionArr[] = "Entry By:\n{$entryBy}\n{$entryDate}";
                }
                
                if (!empty($token->serviceMyTask)) {
                    $completeBy = $token->serviceMyTask->createdBy->name ?? 'N/A';
                    $completeDate = optional($token->serviceMyTask->created_at)->format('d-M-Y h:i A') ?? 'N/A';
                    $completionArr[] = "Complete By:\n{$completeBy}\n{$completeDate}";
                    
                    if (!empty($token->serviceMyTask->updated_at)) {
                        $completionDate = $token->serviceMyTask->updated_at->format('d-M-Y h:i A');
                        $completionArr[] = "Completion Date:\n{$completionDate}";
                    }
                }
                
                if (!empty($token->service) && 
                    !empty($token->service->emergencyNotes) && 
                    $token->service->emergencyNotes->count() > 0) {
                    $lastNote = $token->service->emergencyNotes->last()->note ?? '';
                    if (!empty($lastNote)) {
                        $completionArr[] = "Last Note:\n{$lastNote}";
                    }
                }
                
                $completionInfo = implode("\n\n", $completionArr);

                // Build customer info
                $customerName = $token->customer->company_name ?? 'N/A';
                $customerAddress = $token->customer->address ?? '';
                $serviceId = $token->service->service_unique_id ?? 'N/A';
                $customerInfo = "{$customerName}\n{$customerAddress}\nService ID: {$serviceId}";

                // Build product info
                $productName = $token->product->name ?? 'N/A';
                $productModel = $token->product->model_no ?? '';
                $productInfo = $productName;
                if (!empty($productModel)) {
                    $productInfo .= "\nModel: {$productModel}";
                }

                // Service type
                $serviceType = $token->service_type ?? 'N/A';
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A' }}</td>
                <td>{!! nl2br(e($customerInfo)) !!}</td>
                <td>{{ e($status) }}</td>
                <td>{{ e($serviceType) }}</td>
                <td>{!! nl2br(e($productInfo)) !!}</td>
                <td>{!! nl2br(e($problemSolutionText)) !!}</td>
                <td>{!! nl2br(e($completionInfo)) !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:20px;font-style:italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Spacer Row -->
        <tr><td colspan="8">&nbsp;</td></tr>

        <!-- Total Row -->
        {{-- @if($reportData->count() > 0)
        <tr style="background:#D9E1F2;font-weight:bold;">
            <td colspan="6" style="text-align:right;">TOTAL AMOUNT:</td>
            <td colspan="2">
                Service Fee: ৳{{ number_format($totalServiceFee) }}<br>
                Spare Parts Fee: ৳{{ number_format($totalSparePartsFee) }}<br>
                <span style="color:#0066CC;">Grand Total: ৳{{ number_format($grandTotal) }}</span>
            </td>
        </tr>
        @endif --}}

        <!-- Spacer Row -->
        <tr><td colspan="8">&nbsp;</td></tr>

        <!-- Summary Section -->
        <tr style="background:#E7E6E6;font-weight:bold;text-align:center;">
            <td colspan="8">SUMMARY</td>
        </tr>
        
        <tr style="background:#F2F2F2;">
            <td colspan="3" style="font-weight:bold;">Total Records:</td>
            <td colspan="5">{{ $reportData->count() }}</td>
        </tr>
        
        <tr style="background:#D4EDDA;">
            <td colspan="3" style="font-weight:bold;">Total Service Fee:</td>
            <td colspan="5">৳{{ number_format($totalServiceFee) }}</td>
        </tr>
        
        <tr style="background:#D4EDDA;">
            <td colspan="3" style="font-weight:bold;">Total Spare Parts Fee:</td>
            <td colspan="5">৳{{ number_format($totalSparePartsFee) }}</td>
        </tr>
        
        <tr style="background:#E7E6E6;font-weight:bold;">
            <td colspan="3" style="font-weight:bold;">Grand Total:</td>
            <td colspan="5" style="color:#0066CC;">৳{{ number_format($grandTotal) }}</td>
        </tr>

        <!-- Spacer Row -->
        <tr><td colspan="8">&nbsp;</td></tr>

        <!-- Footer -->
        <tr style="background:#F2F2F2;">
            <td colspan="8" style="text-align:center;font-size:10px;font-style:italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | 
                Copyright © {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }} | 
                All rights reserved
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>