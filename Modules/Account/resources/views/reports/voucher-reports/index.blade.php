@extends('layout.app')

@section('title', 'Voucher Reports')
@section('description', 'Voucher Reports')
@section('page-head')
<style type="text/css">
    .rate-entry-table td,
    tr {
        border: none !important;
    }

    .bg-qty {
        background: #5759604a;
    }

    .bg-value {
        background: #33712e45;
    }

    .chosen-container>.chosen-single,
    [class*=chosen-container]>.chosen-single {
        height: 30px !important;
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
                                        {{ trans('Voucher Reports') }}
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Voucher Reports') }}</h4>
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
                                                <td width="400px">
                                                    @include('Account::includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' => request('account_id')])
                                                </td>
                                                <td width="200px">
                                                    <select class="form-control tom-select" name="voucher_type" data-placeholder="-All Type-">
                                                        <option></option>
                                                        @foreach ($voucherTypes as $name)
                                                            <option {{ request('voucher_type') == $name ? 'selected' : '' }}>{{ $name }}</option>
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
                                                <table class="table table-bordered table-striped" style="margin-bottom: 0">
                                                    <thead>
                                                        <tr class="table-header-bg">
                                                            <th class="text-center">Sl</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">Voucher No</th>
                                                            <th class="text-center">Voucher Type</th>
                                                            <th class="pl-3">Description</th>
                                                            <th class="text-right pr-1">Amount</th>
                                                        </tr>
                                                    </thead>
                        
                                                    <tbody>
                        
                                                        @foreach ($vouchers as $voucher)
                                                            @php 
                                                                $route = 'account.voucher-' . strtolower($voucher->voucher_type) . 's.show';
                                                            @endphp
                                                            <tr>
                                            <td class="text-center">{{ ($vouchers->currentPage() - 1) * $vouchers->perPage() + $loop->iteration  }}</td>
                                                                <td class="text-center">{{ $voucher->date }}</td>
                                                                <td class="text-center">{{ $voucher->invoice_no }}</td>
                                                                <td class="text-center">{{ $voucher->voucher_type }}</td>
                                                                <td class="pl-3">{{ $voucher->description }}</td>
                                                                <td class="text-right pr-1">
                                                                    <a href="{{ route($route, $voucher->id) }}" target="_blank">
                                                                        {{ number_format($voucher->amount) }}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                        
                                                        <tr>
                                                            <th class="text-right" colspan="5">Total In Page</th>
                                                            <th class="text-right pr-1">{{ number_format($vouchers->sum('amount')) }}</th>
                                                        </tr>
                        
                                                        @if($vouchers->currentPage() == $vouchers->lastPage())
                                                            <tr style="font-size: 18px">
                                                                <th class="text-right" colspan="5">Grand Total</th>
                                                                <th class="text-right pr-1">{{ number_format($grand_total) }}</th>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-end">
                                                    @include('utils.table_paginate', ['data' => $vouchers])
            
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

