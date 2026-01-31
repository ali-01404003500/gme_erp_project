@section('title', 'Sales Commissions Request')
@section('description', 'Sales Commissions Request')
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
                                        {{ trans('Sales Commissions Request') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('sales.sales-commissions.index'))
                                <a href="{{ route('sales.sales-commissions.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Commissions Request') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="broker_id">Broker<span class="text-danger">*</span></label>
                                        <select name="broker_id" id="broker_id" class="form-control tom-select">
                                            <option value="">Choose Broker</option>
                                            @foreach ($brokers as $borker)
                                                <option value="{{ $borker->id }}"
                                                    {{ old('broker_id', request()->broker_id) == $borker->id ? 'selected' : '' }}>
                                                    {{ optional($borker)->broker_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for=""></label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control datePicker" name="from"
                                                value="{{ request('from') }}" autocomplete="off" placeholder="From" />
                                            <span class="input-group-text">
                                                <i class="fa fa-exchange-alt"></i>
                                            </span>
                                            <input type="text" class="form-control datePicker" name="to"
                                                value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Show</button>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('sales.sales-commissions.store') }}" method="POST">
                                @csrf
                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Commission Request Table</h3>
                                            <div class="table-responsive">

                                                <input type="hidden" name="broker_id" value="{{ request('broker_id') }}">

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Reference</th>
                                                            <th>Date</th>
                                                            <th>Customer/Broker Info</th>
                                                            <th>Reference</th>
                                                            <th>Status</th>
                                                            <th>Bank Info</th>
                                                            <th>Comm Applicable On</th>
                                                            <th>Commission</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $sl = 1; @endphp

                                                        {{-- Invoice-wise Commission --}}
                                                        @if (!empty($invoiceCommissions))
                                                            @foreach ($invoiceCommissions as $invoice)
                                                                @php
                                                                    $alreadyStored = Modules\Sales\Models\SalesCommission::where(
                                                                        'broker_id',
                                                                        request('broker_id'),
                                                                    )
                                                                        ->where('type', 'invoice')
                                                                        ->where(
                                                                            'sales_order_id',
                                                                            $invoice['invoice_id'],
                                                                        )
                                                                        ->exists();
                                                                @endphp
                                                                @if (!$alreadyStored)
                                                                    <tr>
                                                                        <td>{{ $sl++ }}</td>
                                                                        <td>
                                                                            <a
                                                                                href="{{ route('sales.sales-orders.show', $invoice['invoice_id']) }}">
                                                                                {{ $invoice['sales_order_id'] }}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{ $invoice['invoice_date'] ?? '-' }}</td>
                                                                        <td>
                                                                            Customer:
                                                                            
                                                                            {{ $invoice['customer']->company_name ?? '-' }}<br>
                                                                                Product tag :
                                                                                @foreach ($invoice['customer']->customerSetting->pluck('customerSettingDiscounts')->flatten() as $customerSettingDiscount)
                                                                                    {{ $customerSettingDiscount->PercentageType->name ?? '' }}
                                                                                    {{ $customerSettingDiscount->percentage ?? '' }}%,
                                                                                @endforeach
                                                                            <br>
                                                                            Broker:
                                                                            {{ $invoice['broker']->broker_name ?? '-' }}
                                                                            <br>
                                                                            @if (is_iterable($invoice['broker_percentage']))
                                                                                Percentage type tag: @foreach ($invoice['broker_percentage'] as $broker_percentage)
                                                                                    {{ @$broker_percentage->PercentageType->name ?? '' }}
                                                                                    {{ $broker_percentage->percentage ?? '' }}%,
                                                                                @endforeach
                                                                            @else
                                                                                Fixed:{{ $invoice['broker_percentage']->fixed ?? '' }}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ optional($invoice['customer']->userRef)->full_name }}
                                                                        </td>
                                                                        <td>Pending</td>
                                                                        <td>
                                                                            <select
                                                                                name="broker_bank_id[{{ $invoice['invoice_id'] }}]"
                                                                                class="form-control tom-select">
                                                                                @foreach ($invoice['broker']->brokerBank as $account)
                                                                                    <option value="{{ $account->id }}">
                                                                                        {{ $account->bank_type == 1 ? 'Bank' : ($account->bank_type == 2 ? 'Bkash' : ($account->bank_type == 3 ? 'Nagad' : ($account->bank_type == 4 ? 'Rocket' : '-'))) }}
                                                                                        - {{ $account->account_nos }} -
                                                                                        {{ $account->bank_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>{{ numberFormat($invoice['total_amount']) }}
                                                                        </td>
                                                                        <td>{{ numberFormat($invoice['total_commission']) }}
                                                                        </td>
                                                                        <td>
                                                                            <input type="checkbox" name="id[]"
                                                                                value="{{ $invoice['invoice_id'] }}">
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        {{-- Monthly --}}
                                                        @if (!empty($monthlyCommission))
                                                            @foreach ($monthlyCommission as $monthly)
                                                                @php $key = "monthly_" . \Carbon\Carbon::parse($monthly['date'])->format('Y_m'); @endphp
                                                                <tr>
                                                                    <td>{{ $sl++ }}</td>
                                                                    <td>{{ $monthly['sales_order_id'] }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($monthly['date'])->format('F Y') }}
                                                                    </td>
                                                                    <td>Broker:
                                                                        {{ $monthly['broker']->broker_name ?? '-' }}</td>
                                                                    <td>-</td>
                                                                    <td>Pending</td>
                                                                    <td>
                                                                        <select name="broker_bank_id[{{ $key }}]"
                                                                            class="form-control tom-select">
                                                                            @foreach ($monthly['broker']->brokerBank as $account)
                                                                                <option value="{{ $account->id }}">
                                                                                    {{ $account->bank_type == 1 ? 'Bank' : ($account->bank_type == 2 ? 'Bkash' : ($account->bank_type == 3 ? 'Nagad' : ($account->bank_type == 4 ? 'Rocket' : '-'))) }}
                                                                                    - {{ $account->account_nos }} -
                                                                                    {{ $account->bank_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>Monthly</td>
                                                                    <td>{{ number_format($monthly['total_commission']) }}
                                                                    </td>
                                                                    <td><input type="checkbox" name="id[]"
                                                                            value="{{ $key }}"></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif

                                                        {{-- Yearly --}}
                                                        @if (!empty($yearlyCommission))
                                                            @foreach ($yearlyCommission as $yearly)
                                                                @php $key = "yearly_" . $yearly['commission_year']; @endphp
                                                                <tr>
                                                                    <td>{{ $sl++ }}</td>
                                                                    <td>{{ $yearly['sales_order_id'] }}</td>
                                                                    <td>{{ $yearly['commission_year'] }}</td>
                                                                    <td>Broker: {{ $yearly['broker']->broker_name ?? '-' }}
                                                                    </td>
                                                                    <td>-</td>
                                                                    <td>Pending</td>
                                                                    <td>
                                                                        <select name="broker_bank_id[{{ $key }}]"
                                                                            class="form-control tom-select">
                                                                            @foreach ($yearly['broker']->brokerBank as $account)
                                                                                <option value="{{ $account->id }}">
                                                                                    {{ $account->bank_type == 1 ? 'Bank' : ($account->bank_type == 2 ? 'Bkash' : ($account->bank_type == 3 ? 'Nagad' : ($account->bank_type == 4 ? 'Rocket' : '-'))) }}
                                                                                    - {{ $account->account_nos }} -
                                                                                    {{ $account->bank_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>Yearly</td>
                                                                    <td>{{ number_format($yearly['total_commission']) }}
                                                                    </td>
                                                                    <td><input type="checkbox" name="id[]"
                                                                            value="{{ $key }}"></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif

                                                        {{-- Festival --}}
                                                        @if (!empty($festivalCommission))
                                                            @foreach ($festivalCommission as $festival)
                                                                @php
                                                                    $festivalKey =
                                                                        strtolower(
                                                                            str_replace(
                                                                                [' ', '-'],
                                                                                '_',
                                                                                $festival['sales_order_id'],
                                                                            ),
                                                                        ) .
                                                                        '_' .
                                                                        $festival['commission_year'];
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $sl++ }}</td>
                                                                    <td>{{ $festival['sales_order_id'] }}</td>
                                                                    <td>{{ $festival['commission_year'] }}</td>
                                                                    <td>Broker:
                                                                        {{ $festival['broker']->broker_name ?? '-' }}</td>
                                                                    <td>-</td>
                                                                    <td>Pending</td>
                                                                    <td>
                                                                        <select name="broker_bank_id[{{ $festivalKey }}]"
                                                                            class="form-control tom-select">
                                                                            @foreach ($festival['broker']->brokerBank as $account)
                                                                                <option value="{{ $account->id }}">
                                                                                    {{ $account->bank_type == 1 ? 'Bank' : ($account->bank_type == 2 ? 'Bkash' : ($account->bank_type == 3 ? 'Nagad' : ($account->bank_type == 4 ? 'Rocket' : '-'))) }}
                                                                                    - {{ $account->account_nos }} -
                                                                                    {{ $account->bank_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>{{ $festival['sales_order_id'] }}</td>
                                                                    <td>{{ number_format($festival['total_commission']) }}
                                                                    </td>
                                                                    <td><input type="checkbox" name="id[]"
                                                                            value="{{ $festivalKey }}"></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">Commission Request</button>
                                    </div>
                                </div>
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
@endsection
