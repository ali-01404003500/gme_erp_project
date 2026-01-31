@extends('layout.app')

@section('title', 'Supplier Ledger')
@section('description', 'Supplier Ledger')
@section('page-head')
    <style type="text/css">
        .bg-qty { background: #5759604a; }
        .bg-value { background: #33712e45; }
        .vendor-info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .vendor-info-box .info-label {
            font-weight: 600;
            color: #495057;
        }
        .vendor-info-box .info-value {
            color: #212529;
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
                                        {{ trans('Supplier Ledger') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">Supplier Ledger Report</h3>
                    
                    <div class="btn-group">
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}" target="_blank"
                            class="btn btn-primary btn-sm">
                            <i class="fa fa-file-excel"></i> Excel
                        </a>
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
                                                    <select id="account_id" name="account_id" required="" class="form-control tom-select required" data-placeholder="- Select Account -">
                                                        <option value=""></option>
                                                        @foreach($supplier as $value)
                                                            <option value="{{ $value->getAccount()->id}}" {{ request('account_id') == $value->getAccount()->id ? 'selected' : '' }}>
                                                                {{ $value->getAccount()->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
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
                    @if(request('account_id') && isset($selectedSupplier))
                                 

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
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
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
                                                                if (@$selected_account->transaction_items->first()->balance_type == 'debit' || @$selected_account->transaction_items->first()->balance_type == 'Debit') {
                        
                        
                                                                    $balance = ($debit_balance + $paginate_debit_balance) - ($credit_balance + $paginate_credit_balance);
                        
                                                                } else {
                        
                                                                    $balance = ($credit_balance + $paginate_credit_balance) - ($debit_balance + $paginate_debit_balance);
                                                                }
                        
                                                            @endphp
                                                            <tr>
                                                                <td class="text-left pl-3" colspan="6">Opening Balance</td>
                                                                <td class="text-right pr-1">{{ number_format($balance) }}</td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                                                                    FOUND!</td>
                                                            </tr>
                                                        @endif
                        
                                                        @php
                                                            $total_debit = 0;
                                                            $total_credit = 0;
                                                        @endphp
                        
                                                        @foreach ($transactions as $transaction)
                                                            @php
                                                                
                                                                if ($selected_account->transaction_items->first()->balance_type == 'debit' || $selected_account->transaction_items->first()->balance_type == 'Debit') {
                                                                    $balance += ($transaction->debit_amount - $transaction->credit_amount);
                                                                } else {
                                                                    $balance += ($transaction->credit_amount - $transaction->debit_amount);
                                                                }
                                                                
                                                                $total_debit += $transaction->debit_amount;
                                                                $total_credit += $transaction->credit_amount;
                                                            @endphp
                                                            <tr>
                                            <td class="text-center">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration  }}</td>
                                                                <td class="text-center">{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                                                <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                                                                <td class="pl-3">{!! $transaction->getDescriptionWithLink() !!}</td>
                                                                <td class="text-right pr-1">{{ number_format($transaction->debit_amount) }}</td>
                                                                <td class="text-right pr-1">{{ number_format($transaction->credit_amount) }}</td>
                                                                <td class="text-right pr-1">{{ number_format($balance) }}</td>
                                                            </tr>
                                                        @endforeach
                        
                                                        <tr>
                                                            <th class="text-center" colspan="4">Total In Page</th>
                                                            <th class="text-right pr-1">{{ number_format($total_debit) }}</th>
                                                            <th class="text-right pr-1">{{ number_format($total_credit) }}</th>
                                                            <th></th>
                                                        </tr>
                                                        @if($transactions->currentPage() == $transactions->lastPage() && request('account_id'))
                                                            <tr style="font-size: 18px">
                                                                <th class="text-center" colspan="4">Grand Total</th>
                                                                <th class="text-right pr-1">{{ number_format($grand_total_debit_balance) }}</th>
                                                                <th class="text-right pr-1">{{ number_format($grand_total_credit_balance) }}</th>
                                                                <th class="text-right pr-1">{{ number_format($balance) }}</th>
                                                            </tr>
                                                        @endif
                                                    </tbody>
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


