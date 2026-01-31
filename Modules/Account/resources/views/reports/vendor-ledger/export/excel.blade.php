<table style="width: 1000px; border-collapse: collapse;">

    <thead>

        <!-- Company Header -->
        <tr>
            <th colspan="7"
                style="text-align:center; font-size:16px; font-weight:bold; background:#4472C4; color:white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>

        <tr>
            <th colspan="7"
                style="text-align:center; font-size:12px; background:#D9E1F2;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>

        <tr>
            <th colspan="7"
                style="text-align:center; font-size:12px; background:#D9E1F2;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>

        <!-- Report Title -->
        <tr>
            <th colspan="7"
                style="text-align:center; font-size:14px; font-weight:bold; background:#E7E6E6;">
                VENDOR LEDGER STATEMENT
            </th>
        </tr>

        <tr><td colspan="7"></td></tr>

        <!-- Vendor Information -->
        @if(request('account_id') && isset($selectedVendor))
         @php
            $companyTypes = [
                1 => 'Private Limited',
                2 => 'Proprietorship',
                3 => 'Public Limited',
                4 => 'Government Organisation',
                5 => 'None',
            ];
            $vendorType = $companyTypes[$selectedVendor->company_type_id] ?? 'N/A';

            
        @endphp

            <tr>
                <th style="width:150px; background:#F2F2F2;">Vendor Name:</th>
                <td colspan="2">{{ $selectedVendor->company_name }}</td>

                <th style="width:150px; background:#F2F2F2;">Phone:</th>
                <td colspan="2">{{ $selectedVendor->phone ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th style="background:#F2F2F2;">Vendor Address:</th>
                <td colspan="2">{{ $selectedVendor->address ?? 'N/A' }}</td>
                <th style="background:#F2F2F2;">Vendor Type:</th>
                <td colspan="2">{{ $vendorType }} </td>
            </tr>

            <tr><td colspan="7"></td></tr>

        @endif

        <!-- Ledger Table Header -->
        <tr style="background:#F2F2F2; font-weight:bold;">
            <th style="width:60px; text-align:center;">SL</th>
            <th style="width:150px; text-align:center;">Date</th>
            <th style="width:150px; text-align:center;">Particulars</th>
            <th style="width:300px;">Reference</th>
            <th style="width:120px; text-align:right;">Debit</th>
            <th style="width:120px; text-align:right;">Credit</th>
            <th style="width:130px; text-align:right;">Balance</th>
        </tr>

    </thead>


    <tbody>

        <!-- Opening Balance -->
        @if(request('account_id'))
        
            <tr>
                <td colspan="6" style="text-align:left; padding-left:10px;">
                    <strong>Opening Balance</strong>
                </td>
                <td style="text-align:right;">
                    {{ number_format($balance) }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="7" style="font-size:16px; text-align:center; color:red;">
                    NO RECORDS FOUND!
                </td>
            </tr>
        @endif

        @php
            $runningBalance = $balance;
            $totalDebit = 0;
            $totalCredit = 0;
        @endphp

        <!-- Transactions -->
        @foreach ($transactions as $transaction)

            @php
                $debit = $transaction->debit_amount;
                $credit = $transaction->credit_amount;
                $runningBalance += ($credit - $debit);

                $totalDebit += $debit;
                $totalCredit += $credit;
                $particulars = 'N/A';
                    if ($transaction->transactionable) {
                        $particulars = class_basename($transaction->transactionable_type);
                    }

                $description = $transaction->transactionable
                    ? class_basename($transaction->transactionable_type)
                    : 'N/A';
            @endphp

            <tr>
                <td style="text-align:center;">{{ $loop->iteration }}</td>

                <td style="text-align:center;">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}
                </td>

                <td style="text-align:center;">
                    {{ $particulars }}
                </td>

                <td>{{ strip_tags($transaction->getClickableVoucherNo()) }}</td>

                <td style="text-align:right;">
                    {{ number_format($debit) }}
                </td>

                <td style="text-align:right;">
                    {{ number_format($credit) }}
                </td>

                <td style="text-align:right;">
                    {{ number_format($runningBalance) }}
                </td>
            </tr>

        @endforeach

        <!-- Totals -->
        @if(request('account_id'))

            <tr style="font-weight:bold; background:#E7E6E6;">
                <td colspan="4" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;">
                    {{ number_format($totalDebit) }}
                </td>
                <td style="text-align:right;">
                    {{ number_format($totalCredit) }}
                </td>
                <td></td>
            </tr>

            <tr style="font-weight:bold;">
                <td colspan="6" style="text-align:right;">Closing Balance:</td>
                <td style="text-align:right;">
                    {{ number_format($runningBalance) }}
                </td>
            </tr>

        @endif

    </tbody>

</table>
