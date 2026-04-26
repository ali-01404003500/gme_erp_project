@section('title', "Daily Call List")
@section('description', "Daily Call List")
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
                                        {{ trans('menu.daily-call-list-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('crm.daily-calls.create'))
                                    <a href="{{ route('crm.daily-calls.create') }}" class="btn px-20 btn-primary btn-sm">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.daily-call-list-menu-title') }}</h4>
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
                                                    <select name="customer_id" id="customer_id"
                                                        class="form-control tom-select" data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                        @foreach ($customers as $key => $value)
                                                            <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                                value="{{ $value->id }}">
                                                                {{ $value->company_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdate" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control flatdate" name="to"
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
                    <div class="card mb-4 ">
                        <div class="card-body">
                            <style>
                                .dailycall-table-custom,
                                .dailycall-table-custom th,
                                .dailycall-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .dailycall-table-custom th,
                                .dailycall-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .dailycall-table-custom thead th {
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

                            <table id="zero-config" class="table dailycall-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $dailyCalls])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Account Complain</th>
                                        {{-- <th>Account Complain Details</th> --}}
                                        <th>Service Complain</th>
                                        {{-- <th>Service Complain Details</th> --}}
                                        <th>Sales Complain</th>
                                        {{-- <th>Sales Complain Details</th> --}}
                                        <th>Requirement of Product</th>
                                        {{-- <th>Requirement of Product Details</th> --}}
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($dailyCalls as $value)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($dailyCalls->currentPage() - 1) * $dailyCalls->perPage() + $loop->iteration  }}
                                            </td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <a class="fw-bold text-primary fw-500"
                                                    href="{{ route('crm.daily-calls.show', $value->id) }}">
                                                    {{ optional($value->customer)->company_name }}
                                                </a>
                                            </td>
                                            <td class="text-center">{{ date('m/d/Y', strtotime($value->call_date)) }}</td>
                                            <td>
                                                @if ($value->is_account_complain == 1)
                                                    Yes
                                                @elseif ($value->is_account_complain == 0)
                                                    No
                                                @endif
                                            </td>
                                            {{-- <td>{{ $value->complains_details }}</td> --}}
                                            <td>{{ $value->is_service_complain == 1 ? 'Yes' : 'No' }}</td>
                                            {{-- <td>{{ $value->service_complain_details }}</td> --}}
                                            <td>{{ $value->is_sales_complain == 1 ? 'Yes' : 'No' }}</td>
                                            {{-- <td>{{ $value->sales_complain_details }}</td> --}}
                                            <td>{{ $value->is_product_required == 1 ? 'Yes' : 'No' }}</td>
                                            {{-- <td>{{ $value->product_required_details }}</td> --}}
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('crm.daily-calls.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('crm.daily-calls.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif
                                                    @if (hasPermission('crm.daily-calls.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('crm.daily-calls.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('crm.daily-calls.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('crm.daily-calls.show', $value->id) }}"><i
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
@endsection