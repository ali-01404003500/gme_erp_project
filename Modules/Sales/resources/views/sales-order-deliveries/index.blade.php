@section('title', 'Sales Order Deliveries')
@section('description', 'Sales Order Deliveri')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.sales-order-delivery-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('sales.sales-order-deliveries.create'))
                                <a href="{{ route('sales.sales-order-deliveries.create', app()->getLocale()) }}" class="btn px-20 btn-primary ">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div> --}}
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                            <i class="las la-file-pdf fs-16"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.sales-order-delivery-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control datePicker" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>
    
                                                        <input type="text" class="form-control datePicker" name="to"
                                                            value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                    </div>
                                                </td>
    
                                                <td class="text-center">
                                                    <select name="sales_order_id" id="sales_order_id" class="tom-select  input-sm"
                                                        data-placeholder="Select Sales Order">
                                                        <option value=""></option>
                                                        @foreach ($salesOrderDeliveries as $salesOrderDelivery)
                                                            <option
                                                                @if(request('sales_order_id') == $salesOrderDelivery->sales_order_id)
                                                                    selected
                                                                @endif
                                                                value="{{ $salesOrderDelivery->salesOrder->customer->company_name }}">
                                                                {{ $salesOrderDelivery->salesOrder->customer->company_name }}
                                                            </option>
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesOrderDeliveries])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Invoice Date</th>
                                        <th>Customer</th>
                                        <th>Total Delivery Quantity</th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesOrderDeliveries as $salesOrderDelivery)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                {{ $salesOrderDelivery->salesOrder->invoice_date}}
                                            </td>

                                            <td>
                                                {{ $salesOrderDelivery->salesOrder->customer->company_name }}
                                            </td>

                                            <td>
                                                {{ $salesOrderDelivery->salesOrderDeliveryDetails->sum('quantity') }}
                                            </td>

                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if(hasPermission('sales.sales-order-deliveries.approve') && $salesOrderDelivery->status == "pending")
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('sales.sales-order-deliveries.edit', $salesOrderDelivery->id) }}?approve=1"><i
                                                                class="fas fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('sales.sales-order-deliveries.update') && $salesOrderDelivery->status == "pending" )
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.sales-order-deliveries.edit', $salesOrderDelivery->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('sales.sales-order-deliveries.destroy') && $salesOrderDelivery->status == "pending")
                                                        <button type="button"
                                                            data-action="{{ route('sales.sales-order-deliveries.destroy', $salesOrderDelivery->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endif

                                                    @if(hasPermission('sales.sales-order-deliveries.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-order-deliveries.show', $salesOrderDelivery->id) }}"><i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    {{-- @if(hasPermission('sales.sales-order-deliveries.create'))
                                                        <a class="btn btn-outline-info"
                                                            href="{{ route('sales.sales-order-deliveries.create', ['sales_order_id' => $salesOrderDelivery->id]) }}"><i
                                                                class="fas fa-truck"></i>
                                                        </a>
                                                    @endif --}}
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