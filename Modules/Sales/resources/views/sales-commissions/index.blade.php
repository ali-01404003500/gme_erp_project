@section('title', ' Sales Commiossions List')
@section('description', '   Sales Commiossions List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('   Sales Commiossions list') }}
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans(' Sales Commiossions list') }}</h4>
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
                                                    <select name="type" id=" type" class="form-control tom-select">
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
                            <form method="POST" action="{{ route('sales.sales-commissions.verify') }}">
                                @csrf

                                <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesCommissions])' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Reference</th>
                                            <th>Customer</th>
                                            <th>Broker</th>
                                            <th>Request By</th>
                                            <th>Date</th>
                                            <th>Invoice Amount</th>
                                            <th>Commission Amount</th>
                                            <th>Type</th>
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
                                                    <td>
                                                        <a href="{{ route('sales.sales-orders.show', [$commission->salesOrder->id, app()->getLocale()]) }}" >
                                                            {{ $commission->salesOrder->sales_order_id ?? '-' }}
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>{{ ucfirst(str_replace('_', ' ', $commission->type)) }}</td>
                                                @endif
                                                <td>{{ @$commission->salesOrder->customer->company_name ?? '-' }}</td>
                                                <td>{{ optional($commission->broker)->broker_name ?? '-' }}</td>
                                                <td>{{ optional($commission->createdBy)->name ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($commission->commission_date)->format('d-m-Y') }}</td>
                                                <td>{{ numberFormat($commission->commissionable_amount) }}</td>
                                                <td>{{ numberFormat($commission->amount) }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $commission->type)) }}</td>
                                                <td>{{ $commission->status }}</td>
                                                <td>
                                                    @if($commission->status == 'pending')
                                                        <input type="checkbox" name="ids[]" value="{{ $commission->id }}" class="row-check">
                                                    @endif
                                                </td>
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

@endSection
