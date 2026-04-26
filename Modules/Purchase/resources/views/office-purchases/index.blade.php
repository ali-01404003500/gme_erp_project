@section('title',"Office Purchase List")
@section('description',"Office Purchase List")
@extends('layout.app')
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.office-purchase-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('purchase.offices.create'))
                            <a href="{{ route('purchase.offices.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.office-purchase-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <select name="invoice_no" id="invoice_no" class="tom-select  form-control"
                                                    data-placeholder="Select Invoice Number">
                                                    <option value=""></option>
                                                    @foreach ($officePurchases as $value)
                                                        <option
                                                            {{ request('invoice_no') == $value->invoice_no ? 'selected' : '' }}
                                                            value="{{ $value->invoice_no }}">
                                                            {{ $value->invoice_no }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate"
                                                        name="delivery_date_from"
                                                        value="{{ request('delivery_date_from') }}" autocomplete="off"
                                                        placeholder="Delivery Date From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control flatdate"
                                                        name="delivery_date_to"
                                                        value="{{ request('delivery_date_to') }}" autocomplete="off"
                                                        placeholder="Delivery Date To" />
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
                        <style>
                            .table-bordered-custom,
                            .table-bordered-custom th,
                            .table-bordered-custom td {
                                border: 1px solid #dee2e6 !important;
                                border-collapse: collapse !important;
                            }
                            .table-bordered-custom th,
                            .table-bordered-custom td {
                                padding: 8px;
                                vertical-align: middle;
                            }
                            .table-bordered-custom thead th {
                                background-color: #f8f9fa;
                                border-bottom-width: 2px;
                            }
                                                            .table thead th {
                    background-color: #35526e !important;
                    color: #ffffff !important;
                    font-weight: 600 !important;
                    text-transform: uppercase;
                    font-size: 0.85rem !important;
                    letter-spacing: 0.08em;
                    border-bottom: 2px solid #2a4054 !important;
                    padding: 14px 16px !important;
                    vertical-align: middle;
                    text-align: center;
                }
                        </style>
                        
                        <table id="zero-config" class="table table-bordered-custom dt-table-hover" data-page='@include("utils.table_paginate", ["data" => $officePurchases])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($officePurchases as $value)
                                    <tr>
                                        <td class="text-center">{{ ($officePurchases->currentPage() - 1) * $officePurchases->perPage() + $loop->iteration  }}</td>
                                        <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                            
                                            <a @if (hasPermission('purchase.offices.show')) href="{{ route('purchase.offices.show', $value->id) }}" @endif>{{ $value->invoice_no}}</a>
                                        </td>
                                        <td>{{ $value->date}}</td>
                                        <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ $value->vendor->company_name }}</td>
                                        <td>{{ $value->bill_amount }}</td>
                                        <td>
                                            @if ($value->status == 0)
                                                <span class="badge badge-round badge-warning">Pending</span>
                                            @elseif($value->status == 1)
                                                <span class="badge badge-round badge-success">Approved</span>
                                            @elseif($value->status == 4)
                                                <span class="badge badge-round badge-primary">Received</span>
                                            @elseif($value->status == 2)
                                                <span class="badge badge-round badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if (hasPermission('purchase.offices.approve') && ( $value->created_by != Auth::user()->id || hasPermission('supper_admin')))
                                                        @if ($value->status == 0)
                                                            <a class="btn btn-outline-success"
                                                            href="{{ route('purchase.offices.approve', $value->id) }}"><i
                                                                class="fas fa-check" type="button" title="Approve"></i></a>
                                                        @endif
                                                    @endif
                                                @if (hasPermission('purchase.offices.update'))
                                                    <a class="btn btn-outline-warning" href="{{ route('purchase.offices.edit', $value->id) }}"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if (hasPermission('purchase.offices.destroy'))
                                                    <button type="button" data-action="{{ route('purchase.offices.destroy', $value->id) }}" class="btn btn-outline-danger delete-confirm"><i class="far fa-trash-alt"></i></button>
                                                @endif
                                                @if (hasPermission('purchase.offices.show'))
                                                    <a class="btn btn-outline-primary" href="{{ route('purchase.offices.show', $value->id) }}"><i class="fas fa-eye"></i></a>
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