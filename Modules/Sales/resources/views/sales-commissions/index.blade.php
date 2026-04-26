@section('title', 'Sales Commissions List')
@section('description', 'Sales Commissions List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Sales Commissions list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sales.sales-commissions.create'))
                                <a href="{{ route('sales.sales-commissions.create', app()->getLocale()) }}"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Commissions list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="20%">
                                                    <select name="broker_id" id="broker_id" class="form-control tom-select">
                                                        <option value="">Choose Broker</option>
                                                        @foreach ($brokers as $borker)
                                                            <option value="{{ $borker->id }}"
                                                                {{ old('broker_id', request()->broker_id) == $borker->id ? 'selected' : '' }}>
                                                                {{ optional($borker)->broker_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td width="30%">
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
                                                <td width="20%">
                                                    <select name="type" id="type" class="form-control tom-select">
                                                        <option value="">Choose Type</option>
                                                        <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                                        <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                        <option value="yearly" {{ request('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                                        <option value="eid_ul_fitr" {{ request('type') == 'eid_ul_fitr' ? 'selected' : '' }}>Eid Ul Fitr</option>
                                                        <option value="eid_ul_adha" {{ request('type') == 'eid_ul_adha' ? 'selected' : '' }}>Eid Ul Adha</option>
                                                        <option value="durga_puja" {{ request('type') == 'durga_puja' ? 'selected' : '' }}>Durga Puja</option>
                                                    </select>
                                                </td>

                                                <td colspan="5" class="text-right" width="30%">
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
                                .commission-table-custom,
                                .commission-table-custom th,
                                .commission-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }
                                .commission-table-custom th,
                                .commission-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }
                                .commission-table-custom thead th {
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
                            
                            <form method="POST" action="{{ route('sales.sales-commissions.verify') }}">
                                @csrf

                                <table id="zero-config" class="table commission-table-custom dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesCommissions])' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Reference</th>
                                            <th>Customer</th>
                                            <th>Broker</th>
                                            {{-- <th>Request By</th> --}}
                                            {{-- <th>Date</th> --}}
                                            <th>Invoice Amount</th>
                                            <th>Commission Amount</th>
                                            {{-- <th>Type</th> --}}
                                            <th>Status</th>
                                            <th class="no-content">
                                                <input type="checkbox" class="check-all" id="check-all">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sl = 1; @endphp
                                        @foreach ($salesCommissions as $commission)
                                            <tr>
                                                <td class="text-center">{{ ($salesCommissions->currentPage() - 1) * $salesCommissions->perPage() + $loop->iteration  }}</td>
                                                @if ($commission->sales_order_id)
                                                    <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                        <a href="{{ route('sales.sales-orders.show', [$commission->salesOrder->id, app()->getLocale()]) }}" >
                                                            {{ $commission->salesOrder->sales_order_id ?? '-' }}
                                                        </a>
                                                    </div>
                                                @else
                                                    <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ ucfirst(str_replace('_', ' ', $commission->type)) }}</td>
                                                @endif
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ @$commission->salesOrder->customer->company_name ?? '-' }}</div>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ optional($commission->broker)->broker_name ?? '-' }}</div>
                                                {{-- <td>{{ optional($commission->createdBy)->name ?? '-' }}</div> --}}
                                                {{-- <td>{{ \Carbon\Carbon::parse($commission->commission_date)->format('d-m-Y') }}</div> --}}
                                                <td>{{ numberFormat($commission->commissionable_amount) }}</div>
                                                <td>{{ numberFormat($commission->amount) }}</div>
                                                {{-- <td>{{ ucfirst(str_replace('_', ' ', $commission->type)) }}</div>s --}}
                                                <td>{{ $commission->status }}</div>
                                                <td>
                                                    @if($commission->status == 'pending')
                                                        <input type="checkbox" name="ids[]" value="{{ $commission->id }}" class="row-check">
                                                    @endif
                                                </div>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if(hasPermission('sales.sales-commissions.verify'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" name="action" value="verify" class="btn btn-success">
                                            <i class="fas fa-check"></i> Verified
                                        </button>
                                        <button type="submit" name="action" value="deny" class="btn btn-danger ml-2">
                                            <i class="fas fa-times"></i> Deny
                                        </button>
                                    </div>
                                @endif
                            </form>
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
<script>
    $(document).ready(function () {
        $('#check-all').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.row-check').prop('checked', isChecked);
        });

        $('.row-check').on('change', function () {
            if (!$(this).is(':checked')) {
                $('#check-all').prop('checked', false);
            } else if ($('.row-check:checked').length === $('.row-check').length) {
                $('#check-all').prop('checked', true);
            }
        });
    });
</script>
@endsection