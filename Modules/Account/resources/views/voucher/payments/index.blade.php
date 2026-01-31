@extends('layout.app')

@section('title', 'Payment Vouchers')
@section('description', 'Payment Vouchers')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Payment Vouchers') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('account.voucher-payments.create'))
                                    <a href="{{ route('account.voucher-payments.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                              
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Payment Vouchers List') }}</h4>
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
                                                <td style="width: 400px">
                                                    <input type="text" name="invoice_no" value="{{ request('invoice_no') }}" class="form-control" placeholder="Invoice">

                                                </td>
                                                <td>
                                                    <input type="text" name="reference" value="{{ request('reference') }}" class="form-control" placeholder="Reference"> 
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="8%">Sl</th>
                                                    <th class="pl-3" width="20%">Invoice No</th>
                                                    <th class="pl-3">Date</th>
                                                    <th class="pl-3" width="15%">Reference</th>
                                                    <th class="pr-3 text-right">Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                
                                            <tbody>
                                                @forelse($vouchers as $item)
                                                    <tr>
<td class="text-center">{{ ($vouchers->currentPage() - 1) * $vouchers->perPage() + $loop->iteration  }}</td>
                                                        <td class="pl-3">{{ $item->invoice_no }}</td>
                                                        <td class="pl-3">{{ $item->date }}</td>
                                                        <td class="pl-3">{{ $item->reference }}</td>
                                                        <td class="pr-3 text-right">{{ number_format($item->amount) }}</td>
                                                        <td class="text-center">
                                                            {!! $item->is_approved == 1 ? '<span class="label label-info">Approved</span>' : '<span class="label label-warning">Unapproved</span>' !!}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-corner">
                                                                @include('Account::partials._user-log', ['data' => $item])
                                                        
                                                                <a href="{{ route('account.voucher-payments.show', $item->id) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-success btn-xs" 
                                                                   title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                        
                                                                @if(!$item->is_approved)
                                                                    {{-- Uncomment this section if approval permissions are required --}}
                                                                    
                                                                    {{-- @if(hasPermission("account.voucher-payments.approve"))
                                                                        <a href="{{ route('account.voucher-payments.show', $item->id) }}?type=approve" 
                                                                           target="_blank" 
                                                                           class="btn btn-purple btn-xs" 
                                                                           title="Approve Voucher">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    @endif  --}}
                                                                   
                                                                    @if (hasPermission("account.voucher-payments.approve"))
                                                                        <a href="{{ route('account.voucher-payments.edit', $item->id) }}?type=approve"
                                                                        class="btn btn-purple btn-xs"
                                                                        title="Approve Voucher">
                                                                            <i class="fa fa-check"></i>
                                                                        </a>
                                                                    @endif
                                                                
                                                                    @if(hasPermission("account.voucher-payments.update"))
                                                                        <a href="{{ route('account.voucher-payments.edit', $item->id) }}" 
                                                                           class="btn btn-primary btn-xs" 
                                                                           title="Edit">
                                                                           <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                        
                                                                @if(hasPermission("account.voucher-payments.destroy"))
                                                                    <button type="button" 
                                                                            data-action="{{ route('account.voucher-payments.destroy', $item->id) }}" 
                                                                            class="btn btn-danger btn-xs delete-confirm" 
                                                                            title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                
                                                @empty 
                
                                                    <tr>
                                                        <th colspan="50" class="text-center py-4">
                                                            <strong class="text-danger">No Records Found!</strong>
                                                        </th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="d-none">
                                            <form class="delete-form" action="" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        @include('utils.table_paginate', ['data' => $vouchers])

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

@section('page_scripts')

@endsection

