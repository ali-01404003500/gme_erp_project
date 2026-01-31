@section('title', 'Purchase Order List')
@section('description', 'Purchase Order List')
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
                                        {{ trans('menu.purchase-order-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('purchase.orders.create'))
                                    <a href="{{ route('purchase.orders.create') }}" class="btn px-20 btn-primary btn-sm">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.purchase-order-list-menu-title') }}</h4>
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
                                                    <select name="po_number" id="po_number" class="tom-select  form-control"
                                                        data-placeholder="Select PO Number">
                                                        <option value=""></option>
                                                        @foreach ($purchaseOrders as $value)
                                                            <option
                                                                {{ request('po_number') == $value->po_number ? 'selected' : '' }}
                                                                value="{{ $value->po_number }}">
                                                                {{ $value->po_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdaterange"
                                                            name="from_to" value="{{ request('from_to') }}"
                                                            autocomplete="off" placeholder="From - To" />
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $purchaseOrders])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>PO Id</th>
                                        <th>PO Date</th>
                                        <th>Supplier</th>
                                        <th>Amount</th>
                                        <th>Transport Cost</th>
                                        <th>Net Amount</th>
                                        <th>Delivery Date</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($purchaseOrders as $value)
                                        <tr>
                                        <td>{{ ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('purchase.orders.show', $value->id) }}">{{ $value->po_number }}</a>
                                            </td>
                                            <td>{{ $value->po_date }}</td>
                                            <td>
                                                {{ @$value->supplier->company_name }}
                                            </td>
                                            <td>{{ $value->total_amount }}</td>
                                            <td>{{ $value->transport_cost }}</td>
                                            <td>{{ $value->net_amount }}</td>
                                            <td>{{ $value->delivery_date }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('purchase.orders.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('purchase.orders.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif
                                                    @if (hasPermission('purchase.orders.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('purchase.orders.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('purchase.orders.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('purchase.orders.show', $value->id) }}"><i
                                                                class="fas fa-eye"></i></a>
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
