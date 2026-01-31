@extends('layout.app')

@section('title', '  Cash Flow Report')
@section('description', '  Cash Flow Report')
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
                                        {{ trans('  Cash Flow Report') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                              
                                <a href="{{ route('account.report.cash.flow') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('account.report.cash.flow') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block" style="margin-left: 5px;">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('  Cash Flow Report Report') }}</h4>
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
                                                        <input type="text" class="form-control flatdate"
                                                            name="date"
                                                            value="{{ request('from')??date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date From" />
                                                      
                                                    </div>
                                                </td>
                                                <td colspan="5" class="text-right">
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
                                        <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%;">
                                            <tbody>
                                                <tr>
                                                    <td>Sl.</td>
                                                    <td><strong>Particular</strong></td>
                                                    <td width="150px" class="text-center pr-1">Tk.</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td><strong>Cash flows from operating activities:</strong></td>
                                                    <td width="150px" class="text-right pr-1"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Net Profit/Loss</td>
                                                    <td width="150px" class="text-right pr-1">{{ number_format($equity_balance, 0) }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Adjustment to reconcile net profit to net cash:</td>
                                                    <td width="150px" class="text-right pr-1"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Depreciation Expense</td>
                                                    <td width="150px" class="text-right pr-1">{{ number_format($depreciations, 0) }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Current Asset Increase/Decrease</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        {{ $asset[0] >= 0 ? '(' . number_format($asset[0], 0) . ')' : number_format($asset[0], 0) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Current Liabilities Increase/Decrease</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        {{ $liabilities[0] >= 0 ? number_format($liabilities[0], 0) : '(' . number_format(abs($liabilities[0]), 0) . ')' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Net cash provided/used by Operating Activities</td>
                                                    @php
                                                        $operating_activities = $equity_balance + $depreciations - $asset[0] + $liabilities[0];
                                                    @endphp
                                                    <td width="150px" class="text-right pr-1">
                                                        <strong>{{ $operating_activities >= 0 ? number_format($operating_activities, 0) : '(' . number_format(abs($operating_activities), 0) . ')' }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td><strong>Cash flows from investing activities:</strong></td>
                                                    <td width="150px" class="text-right pr-1"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Fixed Assets Increase/Decrease</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        {{ $asset[1] >= 0 ? '(' . number_format($asset[1], 0) . ')' : number_format($asset[1], 0) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Net cash provided/used by Investing Activities</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        <strong>{{ $asset[1] >= 0 ? '(' . number_format($asset[1], 0) . ')' : number_format($asset[1], 0) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td><strong>Cash flows from financing activities:</strong></td>
                                                    <td width="150px" class="text-right pr-1"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Long-term Liabilities Increase/Decrease</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        {{ $liabilities[1] >= 0 ? number_format($liabilities[1], 0) : '(' . number_format(abs($liabilities[1]), 0) . ')' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Net cash provided/used by Financing Activities</td>
                                                    <td width="150px" class="text-right pr-1">
                                                        <strong>{{ $liabilities[1] >= 0 ? number_format($liabilities[1], 0) : '(' . number_format(abs($liabilities[1]), 0) . ')' }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        Net Cash Change <br>
                                                        Add Opening Balance <br>
                                                        <strong>Closing Balance</strong>
                                                    </td>
                                                    @php
                                                        $net_cash_change = $operating_activities + $asset[1] + $liabilities[1];
                                                        $opening_balance = 0; // Example opening balance, replace with actual data
                                                        $closing_balance = $net_cash_change + $opening_balance;
                                                    @endphp
                                                    <td width="150px" class="text-right pr-1">
                                                        {{ number_format($net_cash_change, 0) }} <br>
                                                        {{ number_format($opening_balance, 0) }} <br>
                                                        <strong>{{ number_format($closing_balance, 0) }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                  
                                    <br>
                                </div>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


