@extends('layout.app')

@section('title', 'Account Receivable')
@section('description', 'Account Receivable')
@section('page-head')
    <style type="text/css"> table, td, tr {
        border: none !important;
        background-color: transparent !important;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 3px;
    }
    </style>
@ends


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
                                        {{ trans('Account Receivable') }}
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Account Receivable Report') }}</h4>
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
                                                    <select name="account_id" id="account_id" class="form-control" >
                                                        <option value="">Select Account</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }}</option>
                                                        @endforeach
                                                    </select>
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
                                                <table class="table table-bordered table-striped" style="margin-bottom: 0">
                                                    <thead>
                                                        <tr class="table-header-bg">
                                                            <th class="text-center">Sl</th>
                                                            <th>Account Name</th>
                                                            <th class="text-right pr-1">Balance</th>
                                                        </tr>
                                                    </thead>
                        
                                                    <tbody>
                                                        @foreach ($transactions as $account)
                                                            <tr>
                                            <td class="text-center">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration  }}</td>
                                                                <td>{{ $account->name }}</td>
                                                                <td class="text-right pr-1">
                                                                    @if($account->balance <> 0)
                                                                        <a target="_blank" href="{{ route('account.report.account-ledger') }}?company_id={{ request('company_id') }}&account_id={{ $account->id }}&from=2010-01-01">
                                                                            {{ number_format($account->balance) }}
                                                                        </a>
                                                                    @else 
                                                                        0
                                                                    @endif 
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                        
                                                    @if(count($transactions) > 0)
                                                        <tfoot>
                                                            <tr style="font-size: 18px">
                                                                <th class="text-right" colspan="2">
                                                                    <strong>Total=</strong>
                                                                </th>
                                                                <th class="text-right pr-1">
                                                                    <strong>{{ number_format($transactions->sum('balance')) }}</strong>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    @endif 
                                                </table>
                                                <div class="d-flex justify-content-end">
                                                    @include('utils.table_paginate', ['data' => $transactions])
            
                                                </div>
                                            </div>
                                        </div>
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


