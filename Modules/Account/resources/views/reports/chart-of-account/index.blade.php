@extends('layout.app')

@section('title', 'Chart Of Account')
@section('description', 'Chart Of Account')
@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        table,
        th,
        td,
        tr {
            border: none !important;
        }


        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .d-print {
                display: block !important;
            }

            tr {
                page-break-after: avoid !important;
            }

            thead {
                page-break-before: avoid !important;
            }

            .widget-box {
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .px-4 {
                padding: 0 !important;
            }
        }

        @page {
            margin: 0.5in;
            /*size: landscape;*/
        }

        .d-print {
            display: none;
        }


        .header-bg {
            background: #bce4e5 !important;
            padding: 10px !important;
        }

        .odd-bg {
            background: #cecece42 !important;
        }

        .even-bg {
            background: #dadada !important;
        }

    </style>
@endpush


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
                                        {{ trans('Chart Of Account') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="Date From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control flatdate"
                                                            name="to"
                                                            value="{{ request('to') }}" autocomplete="off"
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
                            <div class="row" style="width: 100%; margin: 0 !important;">
                                <div class="col-sm-12 px-4">
                                    <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $accounts])'
                                        style="width:100%">                                       
                                         <thead>
                                            <tr class="table-header-bg">
                                                <th class="text-center">Sl</th>
                                                <th class="text-center">Opening Date</th>
                                                <th class="pl-1">Name</th>
                                                <th class="text-right pr-1">Balance</th>
                                            </tr>
                                        </thead>
            
                                        <tbody>
                                            @foreach ($accounts as $account)
                                                <tr>
                                            <td class="text-center">{{ ($accounts->currentPage() - 1) * $accounts->perPage() + $loop->iteration  }}</td>
                                                    <td class="text-center">{{ $account->created_at->format('d F,Y') }}</td>
                                                    <td class="pl-1">{{ $account->name }}</td>     
                                                           
            
                                                    @if (@$account->transaction_items->first()->balance_type == 'debit' || @$account->transaction_items->first()->balance_type == 'Debit')
                                                        <td width="20%" class="text-right pr-1">
                                                            {{ number_format($account->debit - $account->credit ?? 0) }}
                                                        </td>
                                                    @else
                                                        <td width="20%" class="text-right pr-1">
                                                            {{ number_format($account->credit - $account->debit ?? 0) }}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
            
                                    {{-- @include('partials._paginate', ['data' => $accounts]) --}}
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


