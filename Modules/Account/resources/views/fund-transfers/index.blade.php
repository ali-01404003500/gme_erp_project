<!-- resources/views/fund-transfer/index.blade.php -->
@section('title', 'Fund Transfer List')
@section('description', 'List of Fund Transfer')

@extends('layout.app')
@section('page-head')
    <style>
    .otp-input-container {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin: 20px 0;
    }

    .otp-input {
        width: 48px;
        height: 48px;
        padding: 4px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        transition: border-color 0.3s ease;
    }

    .otp-input:focus {
        outline: none;
        border-color: #007bff;
    }

    .otp-input-container .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.fund-transfers-list') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <a href="{{ route('account.fund-transfers.create') }}" class="btn btn-primary btn-sm px-3">
                                <i class="las la-plus"></i> {{ trans('menu.create-fund-transfer') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.fund-transfers-list') }}</h4>
            </div>

            <!-- Filters -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%">
                                            <input type="text" name="from_to" class="form-control flatdaterange" value="{{ request('from_to') }}" placeholder="Date Range">
                                        </td>
                                        <td width="20%">
                                            <select name="transfer_type" class="form-control tom-select" data-placeholder="Transfer Type">
                                                <option value=""></option>
                                                <option value="bank_to_bank" {{ request('transfer_type') == 'bank_to_bank' ? 'selected' : '' }}>Bank to Bank</option>
                                                <option value="bank_to_cash" {{ request('transfer_type') == 'bank_to_cash' ? 'selected' : '' }}>Bank to Cash</option>
                                                <option value="cash_to_bank" {{ request('transfer_type') == 'cash_to_bank' ? 'selected' : '' }}>Cash to Bank</option>
                                                <option value="bkash_to_bank" {{ request('transfer_type') == 'bkash_to_bank' ? 'selected' : '' }}>Bkash to Bank</option> 
                                            </select>
                                        </td>
                                        <td width="20%">
                                            <select name="status" class="form-control tom-select" data-placeholder="Status">
                                                <option value=""></option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verify</option>
                                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option> 
                                            </select>
                                        </td>
                                        <td width="40%" class="text-right">
                                            <div class="btn-group btn-corner">
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Reset</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        {{-- <table class="table dt-table-hover" data-page='@include("utils.table_paginate", ["data" => $iOURequisitionEntrys])' id="zero-config"> --}}
                        <table class="table dt-table-hover"  id="zero-config">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Attachmemt</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fundTransfers as $fund)
                                
                                    <tr>
                                        <td>{{ ($fundTransfers->currentPage() - 1) * $fundTransfers->perPage() + $loop->iteration }}</td> 
                                        <td> 
                                            @php
                                                if($fund->transfer_type=="bank_to_bank")
                                                    $transferType = "Bank to Bank";
                                                else if($fund->transfer_type=="bank_to_cash")
                                                    $transferType = "Bank to Cash";
                                                else if($fund->transfer_type=="cash_to_bank")
                                                    $transferType = "Cash to Bank";
                                                else if($fund->transfer_type=="bkash_to_bank")
                                                    $transferType = "Bkash to Bank";
                                                else
                                                    $transferType = "";
                                            @endphp
                                            {{ $transferType }}

                                        </td>
                                        <td>{{ $fund->transfer_date }}</td>
                                        <td>
                                            @php
                                                $fromAc = $fund->transferFromBankAccount->account_name;
                                                $fromAc = str_replace('#', "\n", $fund->transferFromBankAccount->account_name );
                                                $fromAc = str_replace('.(', ".\n(", $fromAc );
                                            @endphp
                                            
                                            {!!  nl2br($fromAc)  !!}
                                        </td> 
                                        <td>
                                            @php
                                                $toAc = $fund->transferToBankAccount->account_name;
                                                $toAc = str_replace('#', "\n", $fund->transferToBankAccount->account_name );
                                                $toAc = str_replace('.(', ".\n(", $toAc );
                                            @endphp
                                            
                                            {!!  nl2br($toAc)  !!}
                                        </td>
                                        <td>৳{{ number_format($fund->amount) }}</td>
                                        <td>{{ Str::limit($fund->remarks, 50) }}</td>
                                        <td>
                                            <a href="{{ asset($fund->attachments) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a> 
                                        </td>
                                        
                                        <td>     
                                            <span class="badge badge-round badge-{{
                                                match($fund->status){
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'verified' => 'info',
                                                    'denied' => 'danger',
                                                    default => 'secondary',
                                                } }} badge-lg">
                                                {{ $fund->status }}
                                            </span>
                                        </td> 
                                        {{-- Actions --}}
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Small button group">

                                            @if(hasPermission('account.fund-transfers.edit') && $fund->status == 'pending')  
                                                <a class="btn btn-outline-warning"
                                                    href="{{ route('account.fund-transfers.edit', ['fund_transfer'=>$fund->id,'action'=>'edit']) }}"
                                                    data-bs-toggle="tooltip" title="Update">
                                                    <i class="far fa-edit"></i>
                                                </a> 
                                            @endif
                                            @if(hasPermission('account.fund-transfers.destroy') && $fund->status == 'pending')
                                                <button type="button"
                                                    data-action="{{ route('account.fund-transfers.destroy', $fund->id) }}"
                                                    class="btn btn-outline-danger delete-confirm"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            @endif
                                            @if(hasPermission('account.fund-transfers.verify') && $fund->status == 'pending')   
                                                <a class="btn btn-outline-primary"
                                                    href="{{ route('account.fund-transfers.edit', ['fund_transfer'=>$fund->id,'action'=>'verify']) }}"
                                                    data-bs-toggle="tooltip" title="Verify"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                            @endif
                                            @if(hasPermission('account.fund-transfers.approve') && $fund->status == 'verified')   
                                                <a class="btn btn-outline-success"
                                                    href="{{ route('account.fund-transfers.edit', ['fund_transfer'=>$fund->id,'action'=>'approve']) }}"
                                                    data-bs-toggle="tooltip" title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            @endif
                                            @if(hasPermission('account.fund-transfers.show'))   
                                                <a class="btn btn-outline-info"
                                                    href="{{ route('account.fund-transfers.show', $fund->id) }}"
                                                    data-bs-toggle="tooltip" title="View">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @endif
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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