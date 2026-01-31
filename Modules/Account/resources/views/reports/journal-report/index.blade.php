@extends('layout.app')
@section('title', 'Ledger/Journal')
@section('description', 'Ledger/Journal')
@section('page-head')
    <style type="text/css">
        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Ledger/Journal') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Ledger/Journal Report') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdate" name="from"
                                                            value="{{ request('from') ?? date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>
                                                        <input type="text" class="form-control flatdate" name="to"
                                                            value="{{ request('to') ?? date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date To" />
                                                    </div>
                                                </td>
                                                <td colspan="5" class="text-end">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered"
                                            style="margin-bottom: 0; width: 100% !important;">

                                            <!-- table header -->
                                            <thead>
                                                <tr style="color: black !important; font-weight: bolder; font-size: 15px">
                                                    <th class="header-bg text-center" colspan="4">Date</th>
                                                    <th class="header-bg text">Account</th>
                                                    <th class="header-bg text">Description</th>
                                                    <th class="header-bg text-end pr-1">Dr.</th>
                                                    <th class="header-bg text-end pr-1">Cr.</th>
                                                </tr>
                                            </thead>
                                            <!-- body -->
                                            <tbody>

                                                @php
                                                    $totalDebit = 0;
                                                    $totalCredit = 0;
                                                    $sl = 1;
                                                @endphp

                                                @forelse ($transactions->groupBy(['transaction_date','transactionable_type', 'transactionable_id','invoice_no']) as $dates)
                                                    @foreach ($dates as $transactionableTypes)
                                                        @foreach ($transactionableTypes as $transactionable_id => $invoices)
                                                            @foreach ($invoices as $invoice_no => $items)
                                                                @php
                                                                    $rowCount = $items->count();
                                                                    // ISSUE #1: Sort items - DR first, then CR
                                                                    $sortedItems = $items->sortByDesc(function ($item) {
                                                                        return $item->debit_amount > 0 ? 1 : 0;
                                                                    });
                                                                @endphp
                                                                @foreach ($sortedItems as $index => $item)
                                                                    @php
                                                                        $totalDebit += $item->debit_amount;
                                                                        $totalCredit += $item->credit_amount;
                                                                    @endphp

                                                                    <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                                                                        @if ($index === 0)
                                                                            <td class="text-center"
                                                                                rowspan="{{ $rowCount }}">
                                                                                {{ $item->transaction_date->format('Y-m-d') }}
                                                                            </td>
                                                                        @endif
                                                                        <td colspan="4">
                                                                            {{ optional($item->account)->account_with_group }}
                                                                        </td>
                                                                        @if ($index === 0)
                                                                            {{-- ISSUE #7 FIX: Clickable description --}}
                                                                            <td class="text-left"
                                                                                rowspan="{{ $rowCount }}">
                                                                                {!! $item->getDescriptionWithLink() !!}</td>
                                                                        @endif
                                                                        <td class="text-end pr-1 font-weight-bold">
                                                                            <strong>{{ number_format($item->debit_amount ?? 0) }}</strong>
                                                                        </td>
                                                                        <td class="text-end pr-1 font-weight-bold">
                                                                            <strong>{{ number_format($item->credit_amount ?? 0) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @php
                                                                    $sl++;
                                                                @endphp
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach

                                                @empty
                                                    <tr>
                                                        <td colspan="30" style="font-size: 16px"
                                                            class="text-center text-danger">
                                                            NO RECORDS FOUND!
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <!-- table footer -->
                                            @if (count($transactions) > 0)
                                                <tfoot>
                                                    <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                                                        <th colspan="4"></th>
                                                        <th class="text"></th>
                                                        <th class="text-end h4"><strong
                                                                style="font-size: 16px">Total</strong></th>
                                                        <th class="text-end h4"><strong
                                                                style="font-size: 16px">{{ number_format($totalDebit) }}</strong>
                                                        </th>
                                                        <th class="text-end h4"><strong
                                                                style="font-size: 16px">{{ number_format($totalCredit) }}</strong>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        @include('utils.table_paginate', ['data' => $transactions])
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
