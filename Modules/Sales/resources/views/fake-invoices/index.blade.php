@section('title', 'Fake Invoices List')
@section('description', 'Fake Invoices List')
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
                                        {{ trans('Fake Invoices List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('sales.fake-invoices.create'))
                                    <a href="{{ route('sales.fake-invoices.create') }}" class="btn px-20 btn-primary btn-sm">
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
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Fake Invoices List') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            
                                            <td width="30%">
                                                <select name="customer_id" id="customer_id" class="tom-select  input-sm"
                                                    data-placeholder="Select Customer">
                                                    <option value=""></option>
                                                    @foreach ($customers as $key => $value)
                                                        <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">{{ $value->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                          
                                            
                                            {{-- <td width="20%">
                                                <select name="status" id="status" class="tom-select  input-sm"
                                                    data-placeholder="Select Status">
                                                    <option value=""></option>
                                                    <option value="Pending"
                                                        {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Approved"
                                                        {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected"
                                                        {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </td> --}}
                                            <td width="30%">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control flatdate" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
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
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $fakeInvoices])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Invoice Id</th>
                                        <th>Invoice Date</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Reference Invoice</th>
                                        <th class="no-content text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($fakeInvoices as $value)
                                        <tr>
                                            <td class="text-center">{{ ($fakeInvoices->currentPage() - 1) * $fakeInvoices->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                <a href="{{ route('sales.fake-invoices.show', $value->id) }}">{{ $value->invoice_number }}</a>
                                            </td>
                                            <td>{{ $value->invoice_date }}</td>
                                            <td>{{ optional( $value->customer)->company_name }}</td>
                                            <td>
                                                  @if ($value->salesOrder->status == 'pending')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $value->salesOrder->status }}</span>
                                                @elseif($value->salesOrder->status == 'approved')
                                                    <span
                                                        class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                @elseif($value->salesOrder->status == 'delivered')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $value->salesOrder->status }}</span>
                                                @elseif($value->salesOrder->status == 'partial')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $value->salesOrder->status }}</span>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('sales.sales-orders.show', $value->salesOrder->id) }}">{{ $value->salesOrder->sales_order_id }}</a></td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                   
                                                    @if (hasPermission('sales.fake-invoices.show'))
                                                        @if($value->status == 'Returned')
                                                            <a class="btn btn-outline-primary"
                                                                href="{{ route('sales.fake-invoices.show', $value->id) }}" title="View"><i
                                                                    class="fas fa-eye"></i></a>
                                                        @else
                                                            <a class="btn btn-outline-primary"
                                                                href="{{ route('sales.fake-invoices.show', $value->id) }}" title="View"><i
                                                                    class="fas fa-eye"></i></a>
                                                        @endif
                                                    @endif


                                                    @if (hasPermission('sales.fake-invoices.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.fake-invoices.edit', $value->id) }}" title="Edit"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('sales.fake-invoices.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('sales.fake-invoices.destroy', $value->id) }}"
                                                        class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                            class="far fa-trash-alt"></i></button>
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
@endSection
