@section('title', 'Quotation List')
@section('description', 'Quotation List')
@extends('layout.app')
@section('page-head')
    <style>
        .filter td {
            width: 200px
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Quotation list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sales.quotations.create'))
                                <a href="{{ route('sales.quotations.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary btn-sm">
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
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Quotation list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12 filter">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td class="text-center">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Quotation No" name="quotation_no"
                                                        value="{{ request('quotation_no') }}">
                                                </td>

                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control datePicker" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control datePicker" name="to"
                                                            value="{{ request('to') }}" autocomplete="off"
                                                            placeholder="To" />
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <select name="customer_name" id="customer_name"
                                                        class="tom-select  input-sm" data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                        @foreach ($quotations as $quotation)
                                                            <option
                                                                {{ request('customer_name') == $quotation->customer_name ? 'selected' : '' }}
                                                                value="{{ $quotation->customer_name }}">
                                                                {{ $quotation->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>



                                                <td class="text-center">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Phone Number" name="phone"
                                                        value="{{ request('phone') }}">
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $quotations])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Quotation Date</th>
                                        <th>Quotation ID</th>
                                        <th>Customer Name</th>
                                        <th>Address</th>
                                        <th>Prepared By</th>
                                        <th>Approved By</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Expiry Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        <tr>
                                            <td class="text-center">{{ ($quotations->currentPage() - 1) * $quotations->perPage() + $loop->iteration  }}</td>
                                            <td>{{ $quotation->created_at->format('Y-m-d h:i A') }}</td>

                                            <td>
                                                <a href="{{ route('sales.quotations.print', $quotation->id) }}"
                                                    title="Print Quotation">
                                                    {{ $quotation->quotation_no }}
                                                </a>
                                            </td>


                                            <td>{{ $quotation->customer_name }}</i></td>
                                            <td>{{ $quotation->address }}</td>
                                            <td>{{ $quotation->user->name }}</td>
                                            <td>{{ optional($quotation->approvedBy)->name }}</td>
                                            <td>
                                                @if ($quotation->status == 0)
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif ($quotation->status == 1)
                                                    <span class="badge badge-round badge-info">Approved</span>
                                                @elseif ($quotation->status == 2)
                                                    <span class="badge badge-round badge-success">Ordered</span>
                                                @endif
                                            </td>
                                            <td>{{ numberFormat($quotation->total) }}</td>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($quotation->date)->format('Y-m-d h:i A') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if ($quotation->status == 0)
                                                        @if (hasPermission('sales.quotations.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('sales.quotations.edit', $quotation->id) }}"
                                                                title="Edit Quotation"><i class="far fa-edit"></i></a>
                                                        @endif

                                                        @if (hasPermission('sales.quotations.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('sales.quotations.destroy', $quotation->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"
                                                                title="Delete Quotation"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif


                                                        @if (hasPermission('sales.quotations.approve'))
                                                            @if ($quotation->date >= date('Y-m-d'))
                                                                <a class="btn btn-outline-success"
                                                                    href="{{ route('sales.quotations.approve', $quotation->id) }}"
                                                                    title="Approve Quotation"><i
                                                                        class="fas fa-check"></i></a>
                                                            @endif
                                                        @endif
                                                    @endif

                                                    @if ($quotation->status == 1)
                                                        @if (hasPermission('sales.quotations.sales.order'))
                                                            @if ($quotation->status == 1)
                                                                <a class="btn btn-outline-secondary"
                                                                    href="{{ route('sales.quotations.sales.order', $quotation->id) }}"
                                                                    title="Create Sales Order"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                    {{-- @if (hasPermission('sales.quotations.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.quotations.show', $quotation->id) }}"
                                                            title="View Quotation"><i class="fas fa-eye"></i></a>
                                                    @endif --}}
                                                    @if (hasPermission('sales.quotations.print'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.quotations.print', $quotation->id) }}"
                                                            title="Print Quotation"><i class="fas fa-print"></i></a>
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
