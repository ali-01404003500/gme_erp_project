<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Customer Ledger Report' }}</title>
</head>

<style>
    body {
        font-family: 'Helvetica Neue, Helvetica, Arial, sans-serif, nikosh';
        font-size: 80.25%;
    }

    @page {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        header: page-header;
    }

    table, td, th {
        font-size: 10px;
        border: 1px solid black;
    }

    table {
        border-top: none;
        border-left: none;
        border-right: none;
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        width: 100%;
    }

    th.head {
        background-color: rgba(143, 175, 170, 0.35);
    }
</style>

<body>
    <header class="my-header">
        @include('partials._for_pdf_header_2nd')
    </header>
    
    <div style="text-align: center;">
        <h2 style="line-height: 3px; 40px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Customer Ledger Report
        </h2>
        <h5 style="text-align: center;">
            Date From {{ request('from') }} To {{ request('to') }}
        </h5>
    </div>

    @if(request('account_id') && isset($selectedCustomer))
        <div style="margin-bottom: 20px; padding: 10px; background: #f8f9fa;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p><strong>Customer Name:</strong> {{ $selectedCustomer->company_name }}</p>
                    <p><strong>Customer Address:</strong> {{ $selectedCustomer->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Customer Phone:</strong> {{ $selectedCustomer->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endif

    <table class="table table-bordered table-striped" style="margin-bottom: 0">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="text-center">Date</th>
                                        <th class="text-left">Particulars</th>
                                        <th class="text-center">Reference</th>
                <th class="text-right pr-1">Dr.</th>
                <th class="text-right pr-1">Cr.</th>
                <th class="text-right pr-1">Balance</th>
            </tr>
        </thead>

        <tbody>
            @if(request('account_id'))
                @php
                    $balance = $balance ?? 0;
                @endphp
                <tr>
                    <td class="text-left pl-3" colspan="6">Opening Balance</td>
                    <td class="text-right pr-1">{{ number_format($balance) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="7" style="font-size: 16px" class="text-center text-danger">
                        NO RECORDS FOUND!
                    </td>
                </tr>
            @endif

            @php
                $total_debit = 0;
                $total_credit = 0;
            @endphp

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
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $transaction->transaction_date }}</td>
                   <td class="text-left">{{ $particulars }}</td>
                                            <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                    <t d class="text-right pr-1">{{ number_format($debitAmount) }}</t>
                    <td class="text-right pr-1">{{ number_format($creditAmount) }}</td>
                    <td class="text-right pr-1">{{ number_format($balance) }}</td>
                </tr>
            @endforeach

            <tr>
                <th class="text-center" colspan="4">Total In Page</th>
                <th class="text-right pr-1">{{ number_format($total_debit) }}</th>
                <th class="text-right pr-1">{{ number_format($total_credit) }}</th>
                <th class="text-right pr-1">{{ number_format($balance) }}</th>
            </tr>
        </tbody>
    </table>

    <footer class="my-footer">
        @include('partials._for_pdf_footer')
    </footer>

</body>
</html>
