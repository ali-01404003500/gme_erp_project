@section('title', 'Sales Order List')
@section('description', 'Sales Order List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Sales Order list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sales.sales-orders.create'))
                                <a href="{{ route('sales.sales-orders.create', app()->getLocale()) }}"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Order list') }}</h4>
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
                                                    <select name="customer_id" id="customer_id" class="input-sm"
                                                        data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Phone Number" name="additional_phone"
                                                        value="{{ request('additional_phone') }}">
                                                </td>
                                                <td>
                                                    <select name="sales_type" id="sales_type"
                                                        class="tom-select form-control"
                                                        data-placeholder="Select Sales Type">
                                                        <option value=""></option>
                                                        <option value="general_sales" @if (request('sales_type') == 'general_sales') selected @endif>
                                                            General Sales</option>
                                                        <option value="partial_sales" @if (request('sales_type') == 'partial_sales') selected @endif>
                                                            Partial Sales</option>
                                                        <option value="free_sales" @if (request('sales_type') == 'free_sales')
                                                        selected @endif>
                                                            Free Sales</option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <select name="status" id="status" class="tom-select form-control"
                                                        data-placeholder="Select Status">
                                                        <option value="">All Statuses</option>
                                                        <option value="pending" @if (request('status') == 'pending') selected
                                                        @endif>
                                                            Pending</option>
                                                        <option value="approved" @if (request('status') == 'approved')
                                                        selected @endif>
                                                            Approved</option>
                                                        <option value="delivered" @if (request('status') == 'delivered')
                                                        selected @endif>
                                                            Delivered</option>
                                                        <option value="partial" @if (request('status') == 'partial') selected
                                                        @endif>
                                                            Partial</option>
                                                    </select>
                                                </td>

                                                <td>
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
                                .salesorder-table-custom,
                                .salesorder-table-custom th,
                                .salesorder-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .salesorder-table-custom th,
                                .salesorder-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .salesorder-table-custom thead th {
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

                            <table id="zero-config" class="table salesorder-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $salesOrders])' style="width:100%">
                                <thead>
                                    <tr style="background-color: #35526e;">
                                        <th>SL</th>
                                        <th>Sales Order ID</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        {{-- <th>Prepared By</th> --}}
                                        <th>Image/Documents</th>
                                        <th class="no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesOrders as $salesOrder)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($salesOrders->currentPage() - 1) * $salesOrders->perPage() + $loop->iteration  }}
                                            </td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                {{ $salesOrder->sales_order_id }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <a class="fw-500 fw-bold text-primary""
                                                            href=" {{ route('sales.sales-orders.show', $salesOrder->id) }}">
                                                    {{ $salesOrder->customer->company_name }}
                                                </a>
                                            </td>
                                            <td class="text-right text-success fw-bold">
                                                {{ number_format($salesOrder->net_amount) }}</td>
                                            <td>
                                                @if ($salesOrder->paid_status == 'paid')
                                                    <span
                                                        class="badge badge-round badge-success text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @elseif($salesOrder->paid_status == 'due')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @elseif($salesOrder->paid_status == 'condition')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @else
                                                    <span class="badge badge-round badge-danger text-capitalize">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($salesOrder->status == 'pending')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->status }}</span>
                                                @elseif($salesOrder->status == 'approved')
                                                    <span class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                @elseif($salesOrder->status == 'delivered')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $salesOrder->status }}</span>
                                                @elseif($salesOrder->status == 'partial')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->status }}</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $salesOrder->createdBy->name }}</td> --}}
                                            <td>
                                                @foreach($salesOrder->payments as $payment)
                                                    @if($payment->attachments)
                                                        <a href="{{ asset($payment->attachments) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('sales.sales-orders.approve') && $salesOrder->status == 'pending')
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('sales.sales-orders.edit', $salesOrder->id) }}?approve=1"><i
                                                                class="fas fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if (
                                                            hasPermission('sales.sales-orders.update') &&
                                                            ($salesOrder->status == 'pending' || $salesOrder->status == 'approved')
                                                        )
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.sales-orders.edit', $salesOrder->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.destroy') && $salesOrder->status == 'pending')
                                                        <button type="button"
                                                            data-action="{{ route('sales.sales-orders.destroy', $salesOrder->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-orders.show', $salesOrder->id) }}"><i
                                                                class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.deliveries.create') && ($salesOrder->status == 'approved' || $salesOrder->status == 'partial'))
                                                        <a class="btn btn-outline-info" title="Make Delivery"
                                                            href="{{ route('sales.deliveries.create', ['delivery_id' => optional($salesOrder->delivery)->id]) }}"><i
                                                                class="fas fa-truck"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.product-free-sales-invoice') && $salesOrder->offers->where('offer_type', 'clearance')->count() > 0 && ($salesOrder->status == 'approved' || $salesOrder->status == 'partial'))
                                                        <a class="btn btn-outline-info" title="Free Sales Invoice"
                                                            href="{{ route('sales.sales-orders.product-free-sales-invoice', $salesOrder->id) }}"><i
                                                                class="fas fa-gift"></i>
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

        $(document).ready(function () {
            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [],
                load: function (query, callback) {
                    if (!query.length || query.length < 2) return callback();
                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function (res) {
                            companySelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function () {
                            callback();
                        }
                    });
                }
            });

            @if(request('customer_id'))
                companySelect.addOption({
                    id: "{{ request('customer_id') }}",
                    text: "{{ request('customer_id') }}"
                });
                companySelect.setValue("{{ request('customer_id') }}");
            @endif
            }); 
    </script>
@endsection