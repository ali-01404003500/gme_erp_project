@section('title', 'Commiossions Payment')
@section('description', 'Commiossions Payment')
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
                                        {{ trans('Commiossions Payment') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.payments.broker-payments.index'))
                                <a href="{{ route('account.payments.broker-payments.index') }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-list fs-16"></i>List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Commiossions Payment') }}</h4>
                    <x-error-alart />
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
                                                        <option value="invoice"
                                                            {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoice
                                                        </option>
                                                        <option value="monthly"
                                                            {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly
                                                        </option>
                                                        <option value="yearly"
                                                            {{ request('type') == 'yearly' ? 'selected' : '' }}>Yearly
                                                        </option>
                                                        <option value="eid_ul_fitr"
                                                            {{ request('type') == 'eid_ul_fitr' ? 'selected' : '' }}>Eid Ul
                                                            Fitr</option>
                                                        <option value="eid_ul_adha"
                                                            {{ request('type') == 'eid_ul_adha' ? 'selected' : '' }}>Eid Ul
                                                            Adha</option>
                                                        <option value="durga_puja"
                                                            {{ request('type') == 'durga_puja' ? 'selected' : '' }}>Durga
                                                            Puja</option>
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

                    {{-- Payment List --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('account.payments.broker-payments.store') }}" enctype="multipart/form-data">
                                @csrf
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Basic Info</th>
                                            <th>Request & Approved Info</th>
                                            <th>Transaction Info</th>
                                            <th>Payment</th>
                                            <th>Attachment</th>
                                            <th class="no-content">
                                                <input type="checkbox" class="check-all" id="check-all">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sl = 1; @endphp
                                        @foreach ($brokerPayments as $commission)
                                            @php
                                                $remaining = $commission->amount - $commission->approved_paid_amount;
                                            @endphp
                                            @if ($remaining > 0)
                                                <tr>
                                                    <td>{{ $sl++ }}</td>
                                                    <td>
                                                        @if ($commission->sales_order_id)
                                                            Invoice: {{ $commission->salesOrder->sales_order_id ?? '-' }}
                                                            <br>
                                                            Customer:
                                                            {{ @$commission->salesOrder->customer->company_name ?? '-' }}
                                                            <br>
                                                            Cus. Commission:
                                                            @foreach ($commission->salesOrder->customer->customerSetting->pluck('customerSettingDiscounts')->flatten() as $customerSettingDiscount)
                                                                {{ $customerSettingDiscount->PercentageType->name ?? '' }}
                                                                {{ $customerSettingDiscount->percentage ?? '' }}%,
                                                            @endforeach

                                                            <br>
                                                            Broker: {{ optional($commission->broker)->broker_name ?? '-' }}
                                                            <br>
                                                            Bro. Commission: @if ($commission->broker->commission_type == 1)
                                                                @foreach ($commission->broker->brokerCommission as $detail)
                                                                    {{ $detail->PercentageType->name }}:{{ numberFormat($detail->percentage) }}%,
                                                                @endforeach
                                                            @elseif ($commission->broker->commission_type == 2)
                                                                @foreach ($commission->broker->brokerCommission as $detail)
                                                                    {{ $fixedTypeDescriptions[$detail->fixed_type] ?? 'Unknown' }}
                                                                    {{ numberFormat($detail->fixed) }}
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            <strong>Commission Type:</strong>{{ ucfirst(str_replace('_', ' ', $commission->type)) }} <br>
                                                             Broker: {{ optional($commission->broker)->broker_name ?? '-' }}
                                                            <br>
                                                            Bro. Commission: @if ($commission->broker->commission_type == 1)
                                                                @foreach ($commission->broker->brokerCommission as $detail)
                                                                    {{ $detail->PercentageType->name }}:{{ numberFormat($detail->percentage) }}%,
                                                                @endforeach
                                                            @elseif ($commission->broker->commission_type == 2)
                                                                @foreach ($commission->broker->brokerCommission as $detail)
                                                                    {{ numberFormat($detail->fixed) }}
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        Request: {{ $commission->created_at->format('d-m-Y') }} <br>
                                                        Approved: {{ $commission->updated_at->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                        Pay Mode: Cash <br>
                                                        Invoice Amt:
                                                        {{ numberFormat($commission->commissionable_amount) }} <br>
                                                        Com Amt: {{ numberFormat($commission->amount) }} <br>
                                                        Already Pay Amt:
                                                        {{ numberFormat($commission->approved_paid_amount) }} <br>
                                                    </td>
                                                    <td>
                                                        Bank Account & Bank Name:
                                                        <select name="broker_payment_bank_id[]"
                                                            class="form-control tom-select bank-select">
                                                            @foreach ($commission->broker->brokerBank as $account)
                                                                <option value="{{ $account->id }}"
                                                                    @if ($commission->broker_bank_id == $account->id) selected @endif>
                                                                    @if ($account->bank_type == 1)
                                                                        Bank
                                                                    @elseif($account->bank_type == 2)
                                                                        Bkash
                                                                    @elseif($account->bank_type == 3)
                                                                        Nagad
                                                                    @elseif($account->bank_type == 4)
                                                                        Rocket
                                                                    @else
                                                                        -
                                                                    @endif
                                                                    - {{ optional($account)->account_nos }} -
                                                                    {{ @$account->bank_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        Remaining Amt:
                                                        <input type="text" name="remaining_amount[]"
                                                            value="{{ numberFormat($remaining) }}"
                                                            class="form-control remaining-input"
                                                            data-max="{{ $remaining }}" disabled>
                                                    </td>
                                                    <td> 
                                                    <x-file-uploader :value="old('attachment_name')"  name="attachment_name_{{ $commission->id }}" id="attachment_name_{{ $commission->id }}"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="ids[]" value="{{ $commission->id }}"
                                                            class="row-check">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="remarks">Remarks:</label>
                                            <input type="text" name="remarks" id="remarks" class="form-control"
                                                placeholder="Enter remarks">
                                        </div>
                                    </div>
                                    <div class="col-md-2"><br>
                                        <h5><strong>Total Commission: </strong>
                                            <span id="total-commission">0.00</span>
                                        </h5>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-money-bill"></i> Commission Payment
                                        </button>
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
    {{-- Toastr --}}


    <script>
        $(document).ready(function() {
            function calculateTotal() {
                let total = 0;
                $('tbody tr').each(function() {
                    const checkbox = $(this).find('.row-check');
                    const amountInput = $(this).find('.remaining-input');

                    if (checkbox.is(':checked')) {
                        let val = parseFloat(amountInput.val().replace(/,/g, '')) || 0;
                        total += val;

                        // Enable inputs for selected row
                        amountInput.prop('disabled', false);
                        $(this).find('.bank-select').prop('disabled', false);
                    } else {
                        // Disable inputs for unselected row
                        amountInput.prop('disabled', true);
                        $(this).find('.bank-select').prop('disabled', true);
                    }
                });
                $('#total-commission').text(total.toFixed());
            }

            // Validate input
            $(document).on('input', '.remaining-input', function() {
                let max = parseFloat($(this).data('max')) || 0;
                let val = parseFloat($(this).val().replace(/,/g, '')) || 0;

                if (val > max) {
                    toastr.error('Payment cannot be greater than remaining commission amount (max: ' + max
                        .toFixed() + ').');
                    $(this).val(max.toFixed());
                }
                if (val < 0) {
                    toastr.error('Payment amount cannot be negative.');
                    $(this).val(0);
                }

                calculateTotal();
            });

            // Row checkbox change
            $('.row-check').on('change', function() {
                calculateTotal();
            });

            // Check all
            $('#check-all').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.row-check').prop('checked', isChecked);
                calculateTotal();
            });

            // Initialize
            calculateTotal();
        });
    </script>


    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection
