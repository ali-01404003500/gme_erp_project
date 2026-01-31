@extends('layout.app')

@section('title', 'Balance Sheet')
@section('description', 'Balance Sheet')

@section('page-head')
    <style type="text/css"> table, td, tr {
        border: none !important;
        background-color: transparent !important;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 3px;
    }
    table.table{
        width: 100%;
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
                                        {{ trans('Balance Sheet') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                              
                                <a href="{{ route('account.report.balance-sheet') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('account.report.balance-sheet') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Balance Sheet Report') }}</h4>
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
                                                            name="from"
                                                            value="{{ request('from')??date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control flatdate"
                                                            name="to"
                                                            value="{{ request('to')??date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date To" />
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
                                        <div class="row" style="width: 100%; margin: 0 !important;">
                                            <div class="col-sm-12 px-4">
                    
                                                @foreach($accountGroups->where('id', 1) as $key => $accountGroup)
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                                                            <table class="table" style="margin-bottom: 0; margin-left: 10%; width: 85%">
                                                                <tbody>
                                                                    @php 
                                                                        $totalBalance = 0;
                                                                    @endphp 
                    
                                                                    @foreach($accountGroup->accountControls as $accountControl)
                                                                        <tr class="control-row" data-id="{{ $accountControl->id }}" style="cursor: pointer;">
                                                                            <td width="80%"><strong>{{ $accountControl->name }}</strong></td>
                    
                                                                            @php 
                                                                                $totalBalance += $balance = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
                                                                            @endphp 
                    
                                                                            <td class="text-right" width="20%"><strong>{{ number_format($balance ?? 0) }}</strong></td>
                                                                        </tr>
                    
                                                                        <!-- Hidden Account Details -->
                                                                        <tr class="account-details-row" data-id="{{ $accountControl->id }}" style="display: none;" >
                                                                            <td colspan="2">
                                                                                <table class="table table-sm">
                                                                                    <tbody>
                                                                                        @foreach($accountControl->accounts as $account)
                                                                                            <tr>
                                                                                                <td width="80%">{{ $account->name }}</td>
                                                                                                <td class="text-right" width="20%">{{ number_format($account->debit_balance - $account->credit_balance) }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                    
                                                                    <tr>
                                                                        <td class="text-right">
                                                                            <strong style="font-size: 18px; font-weight: bolder;">
                                                                                Total {{ $accountGroup->name }}
                                                                            </strong>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <strong style="font-size: 18px; font-weight: bolder;">
                                                                                {{ number_format($totalBalance) }}
                                                                            </strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                    
                                                <div class="pb-20"></div>
                    
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h4 style="margin-left: 5%"><strong>Owners Equity</strong></h4>
                                                        <table class="table" style="margin-bottom: 0; margin-left: 10%; width: 85%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="80%"> 
                                                                        <strong style="font-size: 14px; font-weight: bolder;">
                                                                            Total Equity Balance
                                                                        </strong>
                                                                    </td>
                                                                    <td class="text-right" width="20%">
                                                                        <strong style="font-size: 14px; font-weight: bolder;">
                                                                            {{ number_format($equity_balance) }}
                                                                        </strong>    
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                    
                                                <div class="pb-20"></div>
                    
                                                @php
                                                    $totalBalance = 0;
                                                @endphp
                                                @foreach($accountGroups->whereIn('id', [2, 10]) as $key => $accountGroup)
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            @if($loop->first)
                                                                <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                                                            @endif
                                                            <table class="table" style="margin-bottom: 0; margin-left: 10%; width: 85%">
                                                                <tbody>
                    
                                                                    @foreach($accountGroup->accountControls as $accountControl)
                                                                        <tr class="control-row" data-id="{{ $accountControl->id }}" style="cursor: pointer;">
                                                                            <td width="80%"><strong>
                                                                                {{ $accountControl->name == 'None' && $accountGroup->id == 10 ? 'Accumulated Depreciation' : $accountControl->name }}
                                                                            </td></strong>
                    
                                                                            @php 
                                                                                $totalBalance += $balance = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
                                                                            @endphp 
                    
                                                                            <td class="text-right" width="20%"><strong>{{ number_format($balance ?? 0) }}</strong></td>
                                                                        </tr>
                    
                                                                        <!-- Hidden Account Details -->
                                                                        <tr class="account-details-row" data-id="{{ $accountControl->id }}" style="display: none;">
                                                                            <td colspan="2">
                                                                                <table class="table table-sm">
                                                                                    <tbody>
                                                                                        @foreach($accountControl->accounts as $account)
                                                                                            <tr>
                                                                                                <td width="80%">{{ $account->name }}</td>
                                                                                                <td class="text-right" width="20%">{{ number_format($account->credit_balance - $account->debit_balance) }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                    
                                                                    @if($loop->last)
                                                                        <tr>
                                                                            <td class="text-right" style="border-bottom: 1px solid black !important;">
                                                                                <strong style="font-size: 14px; font-weight: bolder;">
                                                                                    Total Liabilities
                                                                                </strong>
                                                                            </td>
                                                                            <td class="text-right pr-1" style="border-bottom: 1px solid black !important;">
                                                                                <strong style="font-size: 14px; font-weight: bolder;">
                                                                                    {{ number_format($totalBalance) }}
                                                                                </strong>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-right">
                                                                                <strong style="font-size: 18px; font-weight: bolder;">
                                                                                    Total Liabilities & Owners Equity
                                                                                </strong>
                                                                            </td>
                                                                            <td class="text-right pr-1">
                                                                                <strong style="font-size: 18px; font-weight: bolder;">
                                                                                    {{ number_format($totalBalance + $equity_balance) }}
                                                                                </strong>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
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
@section('page_scripts')
 
    <script>
        $(document).ready(function() {
            $(".control-row").click(function() {
                var controlId = $(this).data("id");
                $(".account-details-row[data-id='" + controlId + "']").toggle();
            });
        });
    </script>
@endsection

