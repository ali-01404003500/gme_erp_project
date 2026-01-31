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
            Vendor Ledger Report
        </h2>
        <h5 style="text-align: center;">Date From {{ request('from'), 'd/m/Y' }} To {{ request('to'), 'd/m/Y' }}</h5>
    </div>
    @if (request('account_id') && isset($selectedVendor))
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

        <div class="card mb-4">
            <div class="card-body">
                <div class="vendor-info-box">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <span class="info-label">Vendor Name:</span>
                                <span class="info-value">{{ $selectedVendor->company_name }}</span>
                            </p>
                            <p class="mb-2">
                                <span class="info-label">Address:</span>
                                <span class="info-value">{{ $selectedVendor->address ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <span class="info-label">Vendor Type:</span>
                                <span class="info-value">{{ $vendorType ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <span class="info-label">Phone:</span>
                                <span class="info-value">{{ $selectedVendor->phone ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="text-center">Date</th>
                <th class="text-center">Particulars</th>
                <th class="text-center">Reference</th>
                <th class="text-right pr-1">Debit</th>
                <th class="text-right pr-1">Credit</th>
                <th class="text-right pr-1">Balance</th>
            </tr>
        </thead>
        <tbody>
            @if (request('account_id'))
                <tr>
                    <td colspan="6" class="text-left pl-3">Opening Balance</td>
                    <td class="text-right pr-1">{{ number_format($balance) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="7" class="text-center text-danger" style="font-size:16px">
                        NO RECORDS FOUND! Please select a vendor to view ledger.
                    </td>
                </tr>
            @endif

            @php
                $totalDebit = 0;
                $totalCredit = 0;
                $runningBalance = $balance;
            @endphp

            @foreach ($transactions as $transaction)
                @php
                    $debitAmount = $transaction->debit_amount;
                    $creditAmount = $transaction->credit_amount;

                    $runningBalance += $creditAmount - $debitAmount;
                    $totalDebit += $debitAmount;
                    $totalCredit += $creditAmount;

                    // Get particulars based on transaction type
                    $particulars = 'N/A';
                    if ($transaction->transactionable) {
                        $particulars = class_basename($transaction->transactionable_type);
                    }
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $particulars }}</td>
                    <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                    <td class="text-right pr-1">{{ number_format($debitAmount) }}</td>
                    <td class="text-right pr-1">{{ number_format($creditAmount) }}</td>
                    <td class="text-right pr-1">{{ number_format($runningBalance) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if (request('account_id'))
                <tr class="table-header-bg">
                    <th colspan="4" class="text-right pr-3">Total:</th>
                    <th class="text-right pr-1">{{ number_format($totalDebit) }}</th>
                    <th class="text-right pr-1">{{ number_format($totalCredit) }}</th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="6" class="text-right pr-3">Closing Balance:</th>
                    <th class="text-right pr-1">{{ number_format($runningBalance) }}</th>
                </tr>
            @endif
        </tfoot>
    </table>





</body>

</html>
