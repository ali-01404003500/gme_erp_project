<table style="width: 1200px; border-collapse: collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="7" style="width: 1200px; text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="width: 1200px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="width: 1200px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="width: 1200px; text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                INVOICE WISE MONEY RECEIPT
            </th>
        </tr>
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>

        <!-- Collection Information -->
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Receipt No:</th>
            <td colspan="2" style="width: 400px;">{{ $collection->invoice_wise_collection_id }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;"> Date:</th>
            <td colspan="3" style="width: 500px;">{{ \Carbon\Carbon::parse($collection->created_at)->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Customer Name:</th>
            <td colspan="2" style="width: 400px;">{{ $collection->customer->company_name ?? 'N/A' }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Created By:</th>
            <td colspan="3" style="width: 500px;">{{ $collection->createdBy->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Address:</th>
            <td colspan="2" style="width: 400px;">{{ $collection->customer->address ?? 'N/A' }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Status:</th>
            <td colspan="3" style="width: 500px;">{{ ucfirst($collection->status) }}</td>
        </tr>
       
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>

        <!-- Invoice/Sales Order Details Section Header -->

    </thead>
    <tbody>
        


        <!-- Collection Methods Section Header -->
        <tr>
            <th colspan="7" style="width: 1200px; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                COLLECTION METHODS
            </th>
        </tr>
        <tr>
            <th style="width: 50px; text-align: center; background-color: #F2F2F2;">SN</th>
            <th style="width: 180px; background-color: #F2F2F2;">Payment Mode</th>
            <th style="width: 220px; background-color: #F2F2F2;">Bank/Account</th>
            <th style="width: 200px; background-color: #F2F2F2;">Transaction ID</th>
            <th style="width: 150px; background-color: #F2F2F2;">Date</th>
            <th colspan="2" style="width: 400px; text-align: right; background-color: #F2F2F2;">Amount</th>
        </tr>

        @forelse ($collection->payments as $key => $payment)
            <tr>
                <td style="width: 50px; text-align: center;">{{ $key + 1 }}</td>
                <td style="width: 180px;">
                    {{ $payment->pay_mode }}
                    @if($payment->remarks) - {{ $payment->remarks }}@endif
                </td>
                <td style="width: 220px;">{{ $payment->bank->account_name ?? $payment->bank->emi_number ?? $payment->bank->name ?? 'N/A' }}</td>
                <td style="width: 200px;">{{ $payment->transaction_id ?? 'N/A' }}</td>
                <td style="width: 150px;">{{ \Carbon\Carbon::parse($payment->date)->format('d-M-Y') }}</td>
                <td colspan="2" style="width: 400px; text-align: right;">{{ number_format($payment->amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="width: 1200px; text-align: center;">No payment records found</td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>

        <!-- Total Section -->
        <tr>
            <th colspan="5" style="width: 800px; text-align: left; font-weight: bold; background-color: #F2F2F2;">
                IN WORD: {{ convert_number($collection->total_amount) }} Taka Only
            </th>
            <th style="width: 200px; text-align: right; font-weight: bold; background-color: #E7E6E6;">Grand Total:</th>
            <td style="width: 200px; text-align: right; font-weight: bold; background-color: #E7E6E6;">
                {{ number_format($collection->total_amount) }}
            </td>
        </tr>

        <!-- Empty Rows for Signatures -->
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>
        <tr>
            <td colspan="7" style="width: 1200px;"></td>
        </tr>

        <!-- Signature Section -->
        <tr>
            <td colspan="3" style="width: 600px; text-align: center; border-top: 1px solid black;">
                <strong>Receiver Signature</strong>
            </td>
            <td colspan="4" style="width: 600px; text-align: center; border-top: 1px solid black;">
                <strong>Authorized Signature</strong>
            </td>
        </tr>

        @if (@$collection->signature->signature)
            <tr>
                <td colspan="3" style="width: 600px; text-align: center; font-size: 10px; color: #666;">
                    Signed on: {{ @$collection->signature->updated_at->format('d-M-Y h:i A') }}
                </td>
                <td colspan="4" style="width: 600px;"></td>
            </tr>
        @endif
    </tbody>
</table>