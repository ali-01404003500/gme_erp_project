<table style="width: 800px; border-collapse: collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="5" style="width: 800px; text-align: center; font-size: 16px; font-weight: bold; background-color: #4472C4; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="width: 800px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="width: 800px; text-align: center; font-size: 12px; background-color: #D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="5" style="width: 800px; text-align: center; font-size: 14px; font-weight: bold; background-color: #E7E6E6;">
                MONEY RECEIPT
            </th>
        </tr>
        <tr>
            <td colspan="5" style="width: 800px;"></td>
        </tr>

        <!-- Receipt Information -->
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Receipt No:</th>
            <td colspan="2" style="width: 350px;">{{ $collection->collection_id }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Collection Date:</th>
            <td style="width: 150px;">{{ \Carbon\Carbon::parse($collection->collection_date)->format('d-m-Y') ?? now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Customer Name:</th>
            <td colspan="2" style="width: 350px;">
                {{ $collection->collectionFrom->company_name ?? ($collection->collectionFrom->name ?? 'N/A') }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Prepared By:</th>
            <td style="width: 150px;">{{ $collection->createdBy->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Address:</th>
            <td colspan="2" style="width: 350px;">{{ $collection->collectionFrom->address ?? 'N/A' }}</td>
            <th style="width: 150px; font-weight: bold; background-color: #F2F2F2;">Print Date:</th>
            <td style="width: 150px;">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="5" style="width: 800px;"></td>
        </tr>

        <!-- Payment Details Header -->
        <tr>
            <th style="width: 50px; text-align: center;">SN</th>
            <th style="width: 300px;">Payment Mode</th>
            <th style="width: 150px; text-align: right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($collection->payments as $key => $payment)
            <tr>
                <td style="width: 50px; text-align: center;">{{ $key + 1 }}</td>
                <td style="width: 300px;">
                    <strong>{{ $payment->pay_mode }}</strong>
                    <br>
                    @if ($payment->remarks)
                        <span style="font-size: 9px; color: #666;">{{ $payment->remarks }}</span>
                    @endif
                </td>
                <td style="width: 150px; text-align: right;">{{ number_format($payment->amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="width: 800px; text-align: center;">No payment records found</td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="3" style="width: 800px;"></td>
        </tr>

        <!-- Total Section -->
        <tr>
            <th colspan="3" style="width: 500px; text-align: left; font-weight: bold; background-color: #F2F2F2;">
                IN WORD: {{ convert_number($collection->total_amount) }} Taka Only
            </th>
            <th style="width: 150px; text-align: right; font-weight: bold; background-color: #E7E6E6;">Grand Total:</th>
            <td style="width: 150px; text-align: right; font-weight: bold; background-color: #E7E6E6;">
                {{ number_format($collection->total_amount) }}
            </td>
        </tr>

        <!-- Empty Rows for Signatures -->
        <tr>
            <td colspan="5" style="width: 800px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="width: 800px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="width: 800px;"></td>
        </tr>

        <!-- Signature Section -->
        <tr>
            <td colspan="2" style="width: 400px; text-align: center; border-top: 1px solid black;">
                <strong>Receiver Signature</strong>
            </td>
            <td style="width: 50px;"></td>
            <td colspan="2" style="width: 350px; text-align: center; border-top: 1px solid black;">
                <strong>Authorized Signature</strong>
            </td>
        </tr>

        @if (@$collection->signature->signature)
            <tr>
                <td colspan="2" style="width: 400px; text-align: center; font-size: 10px; color: #666;">
                    Signed on: {{ @$collection->signature->updated_at->format('d-M-Y h:i A') }}
                </td>
                <td style="width: 50px;"></td>
                <td colspan="2" style="width: 350px;"></td>
            </tr>
        @endif
    </tbody>
</table>