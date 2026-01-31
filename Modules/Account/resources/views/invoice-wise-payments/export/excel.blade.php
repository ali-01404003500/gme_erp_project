<table style="width: 1000px; border-collapse: collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="6" style="width: 1000px; text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="6" style="width: 1000px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="6" style="width: 1000px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="6" style="width: 1000px; text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                INVOICE WISE PAYMENT RECEIPT
            </th>
        </tr>
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>

        <!-- Payment Information -->
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Payment ID:</th>
            <td colspan="2" style="width: 350px;">{{ $payment->invoice_wise_payment_id }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Created Date:</th>
            <td colspan="2" style="width: 350px;">{{ \Carbon\Carbon::parse($payment->created_at)->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Payment To:</th>
            <td colspan="2" style="width: 350px;">{{ $payment->paymentTo->company_name ?? 'N/A' }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Created By:</th>
            <td colspan="2" style="width: 350px;">{{ $payment->createdBy->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Address:</th>
            <td colspan="2" style="width: 350px;">{{ $payment->paymentTo->address ?? 'N/A' }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Status:</th>
            <td colspan="2" style="width: 350px;">{{ ucfirst($payment->status) }}</td>
        </tr>
       
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>

        <!-- Invoice Details Section Header -->
        
    </thead>
    <tbody>
       

        <!-- Payment Methods Section Header -->
        <tr>
            <th colspan="6" style="width: 1000px; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                PAYMENT METHODS
            </th>
        </tr>
        <tr>
            <th style="width: 50px; text-align: center; background-color: #F2F2F2;">SN</th>
            <th style="width: 150px; background-color: #F2F2F2;">Payment Mode</th>
            <th style="width: 200px; background-color: #F2F2F2;">Bank/Account</th>
            <th style="width: 200px; background-color: #F2F2F2;">Transaction ID</th>
            <th style="width: 150px; background-color: #F2F2F2;">Date</th>
            <th style="width: 200px; text-align: right; background-color: #F2F2F2;">Amount</th>
        </tr>

        @forelse ($payment->payments as $key => $detail)
            <tr>
                <td style="width: 50px; text-align: center;">{{ $key + 1 }}</td>
                <td style="width: 150px;">
                    {{ $detail->pay_mode }}
                    @if($detail->remark) - {{ $detail->remark }}@endif
                </td>
                <td style="width: 200px;">{{ $detail->bank->account_name ?? 'N/A' }}</td>
                <td style="width: 200px;">{{ $detail->transaction_id ?? 'N/A' }}</td>
                <td style="width: 150px;">{{ \Carbon\Carbon::parse($detail->date)->format('d-M-Y') }}</td>
                <td style="width: 200px; text-align: right;">{{ number_format($detail->amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="width: 1000px; text-align: center;">No payment records found</td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>

        <!-- Total Section -->
        <tr>
            <th colspan="4" style="width: 700px; text-align: left; font-weight: bold; background-color: #F2F2F2;">
                IN WORD: {{ convert_number($payment->total_amount) }} Taka Only
            </th>
            <th style="width: 150px; text-align: right; font-weight: bold; background-color: #E7E6E6;">Grand Total:</th>
            <td style="width: 200px; text-align: right; font-weight: bold; background-color: #E7E6E6;">
                {{ number_format($payment->total_amount) }}
            </td>
        </tr>

        <!-- Empty Rows for Signatures -->
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>
        <tr>
            <td colspan="6" style="width: 1000px;"></td>
        </tr>

        <!-- Signature Section -->
        <tr>
            <td colspan="3" style="width: 500px; text-align: center; border-top: 1px solid black;">
                <strong>Receiver Signature</strong>
            </td>
            <td colspan="3" style="width: 500px; text-align: center; border-top: 1px solid black;">
                <strong>Authorized Signature</strong>
            </td>
        </tr>

        @if (@$payment->signature->signature)
            <tr>
                <td colspan="3" style="width: 500px; text-align: center; font-size: 10px; color: #666;">
                    Signed on: {{ @$payment->signature->updated_at->format('d-M-Y h:i A') }}
                </td>
                <td colspan="3" style="width: 500px;"></td>
            </tr>
        @endif
    </tbody>
</table>