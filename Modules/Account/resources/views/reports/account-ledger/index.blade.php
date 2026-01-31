@extends('layout.app')

@section('title', 'Account Ledger')
@section('description', 'Account Ledger')

@section('page-head')
    <style type="text/css">
        .bg-qty { background: #5759604a; }
        .bg-value { background: #33712e45; }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active">Account Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">Account Ledger Report</h4>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width: 400px">
                                        <select name="account_id" id="account_id" class="form-control tom-select">
                                            <option value="">Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                                    {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                   value="{{ request('from') ?? date('Y-m-d') }}" placeholder="Date From" autocomplete="off">
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                   value="{{ request('to') ?? date('Y-m-d') }}" placeholder="Date To" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                                    @if(request('account_id'))
                                        @php
                                            $isDebit = in_array(@$selected_account->accountGroup->balance_type, ['Debit']);
                                            $opening = (
    $isDebit
        ? ($debit_balance + $paginate_debit_balance) - ($credit_balance + $paginate_credit_balance)
        : ($credit_balance + $paginate_credit_balance) - ($debit_balance + $paginate_debit_balance)
) ?? 0;

                                        @endphp
                                        <tr>
                                            <td colspan="6" class="text-left pl-3">Opening Balance</td>
                                            <td class="text-right pr-1">{{ number_format($opening)  }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger" style="font-size:16px">
                                                NO RECORDS FOUND!
                                            </td>
                                        </tr>
                                    @endif

                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $runningBalance = $opening ?? 0;
                                    @endphp

                                    @foreach ($transactions as $transaction)
                                        @php
                                            $balanceChange = $isDebit
                                                ? ($transaction->debit_amount - $transaction->credit_amount)
                                                : ($transaction->credit_amount - $transaction->debit_amount);
                                            $runningBalance += $balanceChange;
                                            $totalDebit += $transaction->debit_amount;
                                            $totalCredit += $transaction->credit_amount;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                                            <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                                            <td class="pl-3">{{ $transaction->getDescription() }}</td>
                                            <td class="text-right pr-1">{{ number_format($transaction->debit_amount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($transaction->credit_amount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($runningBalance) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="4" class="text-center">Total In Page</th>
                                        <th class="text-right pr-1">{{ number_format($totalDebit) }}</th>
                                        <th class="text-right pr-1">{{ number_format($totalCredit) }}</th>
                                        <th></th>
                                    </tr>

                                    @if($transactions->currentPage() == $transactions->lastPage())
                                        <tr style="font-size:18px">
                                            <th colspan="4" class="text-center">Grand Total</th>
                                            <th class="text-right pr-1">{{ number_format($grand_total_debit_balance) }}</th>
                                            <th class="text-right pr-1">{{ number_format($grand_total_credit_balance) }}</th>
                                            <th></th>
                                        </tr>
                                    @endif

                                    <tr style="font-size:18px">
                                        <th colspan="4" class="text-center">Balance</th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right pr-1">{{ number_format($runningBalance) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            @include('utils.table_paginate', ['data' => $transactions])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection