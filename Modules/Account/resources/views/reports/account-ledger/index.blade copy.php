@extends('layout.app')

@section('title', 'Ledger Journal')
@section('description', 'Ledger Journal')


@section('content')
    <div class="row">
        <div class="col-sm-12">

            <x-error-alart />

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>


                    <div class="widget-toolbar">
                        <a href="{{ request()->getRequestUri() }}&print=print"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>


                <div class="space"></div>

                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">

                        {{-- <div class="col-sm-3 mt-1">
                            <div class="input-group">
                                <label class="input-group-addon">Company</label>
                                <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                                    <option></option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-sm-3 mt-1">
                            @include('Account::includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' =>
                            request('account_id'), 'is_required' => true])
                        </div>

                        <div class="input-daterange input-group">
                            <input type="text" class="form-control flatdate"
                                name="from"
                                value="{{ request('from') }}" autocomplete="off"
                                placeholder="Date From" />
                            <span class="input-group-text">
                                <i class="fa fa-exchange-alt"></i>
                            </span>

                            <input type="text" class="form-control flatdate"
                                name="to"
                                value="{{ request('to') }}" autocomplete="off"
                                placeholder="Date To" />
                        </div>

                        <div class="col-sm-2 mt-1">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>
                                    Search</button>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4">
                        <table class="table table-bordered table-striped" style="margin-bottom: 0">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Voucher No</th>
                                    <th class="pl-3">Description</th>
                                    <th class="text-right pr-1">Dr.</th>
                                    <th class="text-right pr-1">Cr.</th>
                                    <th class="text-right pr-1">Balance</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if (request('account_id'))


                                    @php
                                        if (@$selected_account->transaction_items->first()->balance_type == 'debit' || $selected_account->accountGroup->balance_type == 'Debit') {


                                            $balance = ($debit_balance + $paginate_debit_balance) - ($credit_balance + $paginate_credit_balance);

                                        } else {

                                            $balance = ($credit_balance + $paginate_credit_balance) - ($debit_balance + $paginate_debit_balance);
                                        }

                                    @endphp
                                    <tr>
                                        <td class="text-left pl-3" colspan="6">Opening Balance</td>
                                        <td class="text-right pr-1">{{ number_format($balance) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                                            FOUND!</td>
                                    </tr>
                                @endif

                                @php
                                    $total_debit = 0;
                                    $total_credit = 0;
                                    $total_balance = 0;
                                @endphp

                                @foreach ($transactions as $transaction)
                                    @php
                                        
                                        if ($selected_account->accountGroup->balance_type == 'Debit') {
                                            $total_balance = $balance += ($transaction->debit_amount - $transaction->credit_amount);
                                        } else {
                                            $total_balance = $balance += ($transaction->credit_amount - $transaction->debit_amount);
                                        }
                                        
                                        $total_debit += $transaction->debit_amount;
                                        $total_credit += $transaction->credit_amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $transaction->date }}</td>
                                        <td class="text-center">{{ $transaction->invoice_no }}</td>
                                        <td class="pl-3">{!! $transaction->description !!}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->debit_amount) }}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->credit_amount) }}</td>
                                        <td class="text-right pr-1">{{ number_format($balance) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th class="text-center" colspan="4">Total In Page</th>
                                    <th class="text-right pr-1">{{ number_format($total_debit) }}</th>
                                    <th class="text-right pr-1">{{ number_format($total_credit) }}</th>
                                    <th></th>
                                </tr>
                                @if($transactions->currentPage() == $transactions->lastPage())
                                    <tr style="font-size: 18px">
                                        <th class="text-center" colspan="4">Grand Total</th>
                                        <th class="text-right pr-1">{{ number_format($grand_total_debit_balance) }}</th>
                                        <th class="text-right pr-1">{{ number_format($grand_total_credit_balance) }}</th>
                                        <th></th>
                                    </tr>
                                @endif

                                <tr>
                                    <tr style="font-size: 18px">
                                        <th class="text-center" colspan="4">Balance</th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right pr-1">{{ number_format($total_balance) }}</th>
                                    </tr>
                                </tr>
                            </tbody>
                        </table>

                        {{-- @include('partials._paginate', ['data' => $transactions]) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
