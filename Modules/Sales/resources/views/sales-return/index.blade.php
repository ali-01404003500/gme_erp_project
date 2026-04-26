@section('title', 'Sales Return List')
@section('description', 'Sales Return List')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Sales Return List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('sales.sales-returns.create'))
                                    <a href="{{ route('sales.sales-returns.create') }}" class="btn px-20 btn-primary btn-sm">
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

            <style>
                .nav-icon la la-cart-arrow-down{
                    font-size: 26px;
                }
                .salesreturn-table-custom,
                .salesreturn-table-custom th,
                .salesreturn-table-custom td {
                    border: 1px solid #dee2e6 !important;
                    border-collapse: collapse !important;
                }
                .salesreturn-table-custom th,
                .salesreturn-table-custom td {
                    padding: 12px;
                    vertical-align: middle;
                }
                .salesreturn-table-custom thead th {
                    background-color: #f8f9fa;
                    border-bottom-width: 2px !important;
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

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Return List') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="30%">
                                                <select name="customer_id" id="customer_id" class="tom-select input-sm"
                                                    data-placeholder="Select Customer">
                                                    <option value=""></option>
                                                    @foreach ($customers as $key => $value)
                                                        <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">{{ $value->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="status" id="status" class="tom-select input-sm"
                                                    data-placeholder="Select Status">
                                                    <option value=""></option>
                                                    <option value="Pending"
                                                        {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Approved"
                                                        {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected"
                                                        {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </td>
                                            <td width="30%">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdaterange"
                                                        name="from_to" value="{{ request('from_to') }}"
                                                        placeholder="From - To" />
                                                </div>
                                            </td>
                                            <td class="text-right" width="20%">
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
                
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table salesreturn-table-custom dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesReturns])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Invoice Id</th>
                                        <th>Invoice Date</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Prepare By</th>
                                        <th>Reference Invoice</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesReturns as $value)
                                        <tr>
                                            <td class="text-center">{{ ($salesReturns->currentPage() - 1) * $salesReturns->perPage() + $loop->iteration  }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <a href="{{ route('sales.sales-returns.show', $value->id) }}">{{ $value->invoice_no }}</a>
                                            </td>
                                            <td>{{ $value->return_date }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ optional($value->customer)->company_name }}</td>
                                            <td>
                                                @if ($value->status == 'Pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($value->status == 'Approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($value->status == 'Rejected')
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @elseif ($value->status == 'Returned')
                                                    <span class="badge badge-round badge-danger">Returned</span>
                                                @endif
                                            </td>
                                            <td >{{ optional($value->createdBy)->name }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <a href="{{ route('sales.sales-orders.show', $value->sales_order_id) }}" target="_blank">{{ $value->reference_invoice }}</a>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('sales.sales-returns.approve'))
                                                        @if ($value->status == 'Pending')
                                                            <a class="btn btn-outline-success"
                                                            href="{{ route('sales.sales-returns.approve', $value->id) }}"><i
                                                                class="fas fa-check" type="button" title="Approve"></i></a>
                                                        @endif
                                                    @endif
                                                    @if (hasPermission('sales.sales-returns.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-returns.show', $value->id) }}" title="View"><i
                                                                class="fas fa-eye"></i></a>
                                                    @endif

                                                    @if ($value->status == 'Pending')
                                                        @if (hasPermission('sales.sales-returns.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('sales.sales-returns.edit', $value->id) }}" title="Edit"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif

                                                        @if (hasPermission('sales.sales-returns.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('sales.sales-returns.destroy', $value->id) }}"
                                                                class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif
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
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection