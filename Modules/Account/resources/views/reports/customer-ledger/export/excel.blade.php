<table style="width: 1000px; border-collapse: collapse;">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="7" style="text-align:center; font-size:16px; font-weight:bold; background:#4472C4; color:white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align:center; font-size:12px; background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align:center; font-size:12px; background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>

        <!-- Report Title -->
        <tr>
            <th colspan="7" style="text-align:center; font-size:14px; font-weight:bold; background:#E7E6E6;">
                CUSTOMER LEDGER STATEMENT
            </th>
        </tr>

        <tr>
            <th colspan="7" style="text-align:center; font-size:12px; background:#F2F2F2;">
                Date From {{ request('from') }} To {{ request('to') }}
            </th>
        </tr>

        <tr><td colspan="7"></td></tr>

        <!-- Customer Information -->
        @if(request('account_id') && isset($selectedCustomer))
            <tr>
                <th style="width:150px; background:#F2F2F2;">Customer Name:</th>
                <td colspan="2">{{ $selectedCustomer->company_name }}</td>
                <th style="width:150px; background:#F2F2F2;">Phone:</th>
                <td colspan="3">{{ $selectedCustomer->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th style="background:#F2F2F2;">Customer Address:</th>
                <td colspan="6">{{ $selectedCustomer->address ?? 'N/A' }}</td>
            </tr>
            <tr><td colspan="7"></td></tr>
        @endif

        <!-- Ledger Table Header -->
        <tr style="background:#F2F2F2; font-weight:bold;">
            <th style="width:60px; text-align:center;">SL</th>
            <th style="width:150px; text-align:center;">Date</th>
           <th class="text-left">Particulars</th>
                                        <th class="text-center">Reference</th>
            <th style="width:120px; text-align:right;">Debit</th>
            <th style="width:120px; text-align:right;">Credit</th>
            <th style="width:130px; text-align:right;">Balance</th>
        </tr>
    </thead>

    <tbody>
        @if(request('account_id'))
            @php
                $balance = $balance ?? 0;
                $total_debit = 0;
                $total_credit = 0;
            @endphp

            <tr>
                <td colspan="6" style="text-align:left; padding-left:10px;"><strong>Opening Balance</strong></td>
                <td style="text-align:right;">{{ number_format($balance) }}</td>
            </tr>
        @else
            <tr>
                <td colspan="7" style="font-size:16px; text-align:center; color:red;">
                    NO RECORDS FOUND!
                </td>
            </tr>
        @endif

        @foreach($transactions as $transaction)
            @php
                $debitAmount = $transaction->debit_amount;
                $creditAmount = $transaction->credit_amount;
                
                $balance += ($debitAmount - $creditAmount);
                
                $total_debit += $debitAmount;
                $total_credit += $creditAmount;
                 $particulars = 'N/A';
                                            if ($transaction->transactionable) {
                                                $particulars = class_basename($transaction->transactionable_type);
                                            }
            @endphp

            <tr>
                <td style="text-align:center;">{{ $loop->iteration }}</td>
                <td style="text-align:center;">
                    {{ $transaction->transaction_date }}
                </td>
                <td class="text-left">{{ $particulars }}</td>
                                            <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                <td style="text-align:right;">{{ number_format($debitAmount) }}</td>
                <td style="text-align:right;">{{ number_format($creditAmount) }}</td>
                <td style="text-align:right;">{{ number_format($balance) }}</td>
            </tr>
        @endforeach

        <!-- Total Row -->
        @if(request('account_id'))
            <tr style="font-weight:bold; background:#E7E6E6;">
                <td colspan="4" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;">{{ number_format($total_debit) }}</td>
                <td style="text-align:right;">{{ number_format($total_credit) }}</td>
                <td style="text-align:right;">{{ number_format($balance) }}</td>
            </tr>

            <tr style="font-weight:bold;">
                <td colspan="6" style="text-align:right;">Closing Balance:</td>
                <td style="text-align:right;">{{ number_format($balance) }}</td>
            </tr>
        @endif
    </tbody>
</table>