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
                SUPPLIER LEDGER STATEMENT
            </th>
        </tr>

        <tr><td colspan="7"></td></tr>

        <!-- Vendor Information -->
        @if(request('account_id') && isset($selectedSupplier))
            <tr>
                <th style="width:150px; background:#F2F2F2;">Supplier Name:</th>
                <td colspan="2">{{ $selectedSupplier->company_name }}</td>

                <th style="width:150px; background:#F2F2F2;">Phone:</th>
                <td colspan="2">{{ $selectedSupplier->phone ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th style="background:#F2F2F2;">Supplier Address:</th>
                <td colspan="2">{{ $selectedSupplier->address ?? 'N/A' }}</td>
            </tr>

            <tr><td colspan="7"></td></tr>
        @endif

        <!-- Ledger Table Header -->
        <tr style="background:#F2F2F2; font-weight:bold;">
            <th style="width:60px; text-align:center;">SL</th>
            <th style="width:150px; text-align:center;">Date</th>
            <th style="width:150px; text-align:center;">Voucher No</th>
            <th style="width:300px;">Description</th>
            <th style="width:120px; text-align:right;">Debit</th>
            <th style="width:120px; text-align:right;">Credit</th>
            <th style="width:130px; text-align:right;">Balance</th>
        </tr>
    </thead>

    <tbody>

        @if(request('account_id'))

            @php
                // Opening Balance same like your PDF logic
                if ($selected_account->accountGroup->balance_type == 'Debit') {
                    $balance = $debit_balance + $paginate_debit_balance - ($credit_balance + $paginate_credit_balance);
                } else {
                    $balance = $credit_balance + $paginate_credit_balance - ($debit_balance + $paginate_debit_balance);
                }

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


        @foreach ($transactions as $transaction)

            @php
                // Update running balance like PDF
                if ($selected_account->accountGroup->balance_type == 'Debit') {
                    $balance += $transaction->debit_amount - $transaction->credit_amount;
                } else {
                    $balance += $transaction->credit_amount - $transaction->debit_amount;
                }

                $total_debit += $transaction->debit_amount;
                $total_credit += $transaction->credit_amount;
            @endphp

            <tr>
                <td style="text-align:center;">{{ $loop->iteration }}</td>
                <td style="text-align:center;">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}
                </td>

                <td style="text-align:center;">
                    {{ $transaction->voucher_no ?? strip_tags($transaction->getClickableVoucherNo()) }}
                </td>

                <td>{{ $transaction->description }}</td>

                <td style="text-align:right;">{{ number_format($transaction->debit_amount) }}</td>
                <td style="text-align:right;">{{ number_format($transaction->credit_amount) }}</td>

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
