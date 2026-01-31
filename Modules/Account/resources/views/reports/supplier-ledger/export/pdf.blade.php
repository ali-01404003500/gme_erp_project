<!doctype html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
</head>


<style>
    body {
        font-family: 'Helvetica Neue, Helvetica, Arial,sans-serif, nikosh';
        font-size: 80.25%;
    }


    @page {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        header: page-header;

    }

    table,
    td,
    th {
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
        <h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Supplier Ledger Report
        </h2>
        <h5 style="text-align: center;">Date From {{ request('from'), 'd/m/Y' }} To {{ request('to'), 'd/m/Y' }}</h5>
    </div>

    @if (request('account_id') && isset($selectedSupplier))
        <div class="card mb-4">
            <div class="card-body">
                <div class="vendor-info-box">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <span class="info-label">Supplier Name:</span>
                                <span class="info-value">{{ $selectedSupplier->company_name }}</span>
                            </p>
                            <p class="mb-2">
                                <span class="info-label">Supplier Address:</span>
                                <span class="info-value">{{ $selectedSupplier->address ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">

                            <p class="mb-2">
                                <span class="info-label">Supplier Phone:</span>
                                <span class="info-value">{{ $selectedSupplier->phone ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <table class="table table-bordered table-striped" style="margin-bottom: 0">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="text-center">Date</th>
                <th class="text-center">Voucher No</th>
                <th class="pl-3">Description</th>
                <th class="text-right pr-1">Dr.</th>
                <th class="text-right pr-1">Cr.</th>
                <th class="text-right pr-1">Balance</th>
            </tr>
        </thead>

        <tbody>

            @if (request('account_id'))
                @php
                    if ($selected_account->accountGroup->balance_type == 'Debit') {
                        $balance =
                            $debit_balance + $paginate_debit_balance - ($credit_balance + $paginate_credit_balance);
                    } else {
                        $balance =
                            $credit_balance + $paginate_credit_balance - ($debit_balance + $paginate_debit_balance);
                    }

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

            @foreach ($transactions as $transaction)
                @php

                    if ($selected_account->accountGroup->balance_type == 'Debit') {
                        $balance += $transaction->debit_amount - $transaction->credit_amount;
                    } else {
                        $balance += $transaction->credit_amount - $transaction->debit_amount;
                    }

                    $total_debit += $transaction->debit_amount;
                    $total_credit += $transaction->credit_amount;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $transaction->date }}</td>
                    <td class="text-center">{{ $transaction->invoice_no }}</td>
                    <td class="pl-3">{{ $transaction->description }}</td>
                    <td class="text-right pr-1">{{ number_format($transaction->debit_amount) }}</td>
                    <td class="text-right pr-1">{{ number_format($transaction->credit_amount) }}</td>
                    <td class="text-right pr-1">{{ number_format($balance) }}</td>
                </tr>
            @endforeach

            <tr>
                <th class="text-center" colspan="4">Total In Page</th>
                <th class="text-right pr-1">{{ number_format($total_debit) }}</th>
                <th class="text-right pr-1">{{ number_format($total_credit) }}</th>
                <th class="text-right pr-1"> {{ number_format(@$balance)  }}</th>
            </tr>
        </tbody>
    </table>


</body>

</html>
