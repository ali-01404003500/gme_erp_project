@section('title', 'Deliveries')
@section('description', 'Deliveries List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.deliveries-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                            <i class="las la-file-pdf fs-16"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.deliveries-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <select name="status" id="status" class="tom-select input-sm">
                                                        <option value="">Select Status</option>
                                                        <option value="pending" @if(request()->status=="pending") selected @endif>Pending</option>
                                                        <option value="delivered" @if(request()->status=="delivered") selected @endif>Delivered</option>
                                                        <option value="partial" @if(request()->status=="partial") selected @endif>Partial</option>
                                                    </select>
                                                </td>

                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdaterange" name="from_to" value="{{ request('from_to') }}" placeholder="From - To" />
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
                            <table id="zero-config"class="table  dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $deliveries])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Invoice Id</th>
                                        <th>Invoice Date</th>
                                        <th>Customer</th>
                                        {{-- <th>Invoice Type</th> --}}
                                        <th>Address</th>
                                        <th>Total Quantity</th>
                                        {{-- <th>Prepared By</th> --}}
                                        <th>Delivered Qty</th>
                                        <th class="no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveries as $deliverie)
                                        <tr>
                                            <td style="border: 1px solid #989090 !important;" class="text-center">{{ ($deliveries->currentPage() - 1) * $deliveries->perPage() + $loop->iteration  }}</td>

                                            <td style="border: 1px solid #989090 !important;">
                                                @if ($deliverie->source->sales_order_id??null)
                                                    <a href="{{ route('sales.sales-orders.show',$deliverie->source->id) }}">
                                                        {{ $deliverie->source->sales_order_id }}
                                                    </a>
                                                @else
                                                    {{ $deliverie->source->invoice_id ??$deliverie->source->id }}
                                                @endif
                                            </td>

                                            <td style="border: 1px solid #989090 !important;">
                                                {{ $deliverie->delivery_date}}  
                                            </td>

                                            <td style="border: 1px solid #989090 !important;">
                                                @if (@$deliverie->source->customer??null)
                                                    <a href="{{ route('crm.customers.show', $deliverie->source->customer->id) }}">{{ @$deliverie->source->customer->company_name }} </a>
                                                @endif
                                            </td>

                                            <td style="border: 1px solid #989090 !important;">
                                                @if (class_basename($deliverie->source_type) == SalesOrder::class)
                                                    Sales Order
                                                @elseif (class_basename($deliverie->source_type) == 'Quotation')
                                                    Quotation
                                                @else
                                                    {{ class_basename($deliverie->source_type) }}
                                                @endif
                                            </td>
                                            <td style="border: 1px solid #989090 !important;">
                                                {{ @$deliverie->source->customer->address }}
                                            </td>
                                            <td style="border: 1px solid #989090 !important;">
                                                {{ $deliverie->source->details->sum('quantity') }}
                                            </td>
                                            <td style="border: 1px solid #989090 !important;">
                                                {{ @$deliverie->source->createdBy->name }}
                                            </td>
                                            <td style="border: 1px solid #989090 !important;">
                                                {{ $deliverie->deliveryDetails->sum('quantity')  }}
                                            </td>

                                            <td style="border: 1px solid #989090 !important;">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if(hasPermission('sales.deliveries.create') && $deliverie->status == "pending" || $deliverie->status == "partial")
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.deliveries.create', ['delivery_id' => $deliverie->id]) }}">
                                                            <i class="fa fa-truck"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('sales.deliveries.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.deliveries.show', $deliverie->id) }}"><i class="fas fa-eye"></i>
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
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection