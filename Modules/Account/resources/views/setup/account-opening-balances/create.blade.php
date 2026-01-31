@section('title', 'Opening Balances')
@section('description', 'Opening Balances')
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Opening Balances') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <a href="{{ route('account.account-setup.account-opening-balances.index') }}"
                                class="btn btn-xs btn-secondary me-1">
                                <i class="las la-list"></i>
                                {{ trans('List') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Opening Balances') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    {{-- <div class="col-md-12 my-4">
                        <h3>{{ trans('Opening Balances') }}</h3>

                    </div> --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('account.account-setup.account-opening-balances.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <h3 for="account_name" class="form-label">{{ trans('Account Name') }}</h3>
                                    <div class="row">
                                        {{-- <div class="col-md-6"> --}}
                                        <div class="col-md-6 mb-4">
                                            <label for="opening_balance_date" class="form-label">{{ trans('Opening Balance Date') }}</label>
                                            <input type="text" class="form-control" id="opening_balance_date" name="opening_balance_date" value="{{ old('opening_balance_date', date('Y-m-d')) }}" required readonly>
                                        </div>
                                        {{-- </div> --}}

                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">{{ trans('Account') }}</th>
                                                        <th>{{ trans('Debit') }}</th>
                                                        <th>{{ trans('Credit') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($accounts->groupBy('account_group_id') as $account_group_id => $accountsByGroup)
                                                        <tr data-bs-toggle="collapse" data-bs-target=".collapseExample{{ $account_group_id }}" class="clickable" aria-expanded="false" aria-controls="collapseExample{{ $account_group_id }}">
                                                            <th colspan="4">
                                                                 {{$accountsByGroup->first()->accountGroup->name }}
                                                            </th>
                                                        </tr>
                                                        @foreach ($accountsByGroup as $key => $account)
                                                            <tr class="collapse collapseExample{{$account_group_id}}">
                                                                <td colspan="2">{{ $account->name }} ({{ $account->accountSubsidiary->name }}) </td>
                                                                <td>
                                                                    <input type="number" class="form-control input-debit" id="debit_{{ $key }}"  name="debit[{{ $account->id }}]" placeholder="Debit">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control input-credit" id="credit_{{ $key }}"  name="credit[{{ $account->id }}]" placeholder="Credit">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        
                                                    @endforeach
                                                </tbody>
    
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2">{{ trans('Total') }}</th>
                                                        <th>
                                                            <span id="total_debit">0.00</span>
                                                        </th>
                                                        <th>
                                                            <span id="total_credit">0.00</span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
    
                                                @push('scripts')
                                                    <script>
                                                        $(document).ready(function(){
                                                            calculateTotalDebit();
                                                            calculateTotalCredit();
                                                            
                                                            $(".input-debit").keyup(function(){
                                                                calculateTotalDebit();
                                                            });
                                                            
                                                            $(".input-credit").keyup(function(){
                                                                calculateTotalCredit();
                                                            });
    
                                                            function updateSubmitButton() {
                                                                if(  parseFloat($("#total_debit").text()) == parseFloat($("#total_credit").text()) && parseFloat($("#total_debit").text()) != 0 ) {
                                                                    $("#submit").prop("disabled", false);
                                                                }else{
                                                                    $("#submit").prop("disabled", true);
                                                                }
                                                            }
                                                            
                                                            function calculateTotalDebit(){
                                                                var total_debit = 0;
                                                                $(".input-debit").each(function() {
                                                                    total_debit += +$(this).val();
                                                                });
                                                                $("#total_debit").text(total_debit.toFixed());
                                                                updateSubmitButton();
                                                            }
                                                            
                                                            function calculateTotalCredit(){
                                                                var total_credit = 0;
                                                                $(".input-credit").each(function() {
                                                                    total_credit += +$(this).val();
                                                                });
                                                                $("#total_credit").text(total_credit.toFixed());
                                                                updateSubmitButton();
    
                                                            }
                                                        });
                                                    </script>
                                                @endpush
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" id="submit" class="btn btn-primary">{{ trans('Save') }}</button>
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
@endSection
