@extends('layout.app')

@section('title', 'Account Payable')
@section('description', 'Account Payable')
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
                                        {{ trans('Account Payable') }}
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Account Payable Report') }}</h4>
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
                                                <td width="30%">
                                                    <select id="account_id" name="account_id" required=""
                                                        class="form-control tom-select required"
                                                        data-placeholder="- Select Account -">
                                                        <option value=""></option>
                                                        @foreach ($accounts as $value)
                                                            <option value="{{ $value->id }}"
                                                                {{ request('account_id') == $value->id ? 'selected' : '' }}>
                                                                {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
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
                                                            @if ($account->balance != 0)
                                                                <a target="_blank"
                                                                    href="{{ route('account.report.account-ledger') }}?company_id={{ request('company_id') }}&account_id={{ $account->id }}&from=2010-01-01">
                                                                    {{ number_format($account->balance) }}
                                                                </a>
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            @if (count($transactions) > 0)
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
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        @include('utils.table_paginate', ['data' => $transactions])

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
