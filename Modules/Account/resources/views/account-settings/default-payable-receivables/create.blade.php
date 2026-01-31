@extends('layout.app')
@section('title', 'Default Payable & Receivables')
@section('description', 'Default Payable & Receivables')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Default Payable & Receivables') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div>
                            <div class="card-header">
                                <h4 class="card-title">Default Payable & Receivables</h4>
                            </div>
                        </div>
                        <div class="card-body pt-4" >
                            <form action="{{ route('account.account-settings.default-payable-receivables.store', app()->getLocale()) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="default_payment_account">Default Payment Account</label>
                                            <input type="text" name="type[]" id="type" value="default_payment_account" class="form-control" hidden>
                                            <select name="account_id[default_payment_account]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}" 
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_payment_account'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_bill_expense_payable">Default Bill Expense Payable</label>
                                           <input type="text" name="type[]" id="type" value="default_bill_expense_payable" class="form-control" hidden>
                                            <select name="account_id[default_bill_expense_payable]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"\
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_bill_expense_payable'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_claim_bill">Default Claim Bill</label>
                                            <input type="text" name="type[]" id="type" value="default_claim_bill" class="form-control" hidden>
                                            <select name="account_id[default_claim_bill]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_claim_bill'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="default_invoice_income_receivable">Default Invoice Income Receivable</label>
                                            <input type="text" name="type[]" id="type" value="default_invoice_income_receivable" class="form-control" hidden>
                                            <select name="account_id[default_invoice_income_receivable]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_invoice_income_receivable'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_advance_salary">Default Advance Salary</label>
                                            <input type="text" name="type[]" id="type" value="default_advance_salary" class="form-control" hidden>
                                            <select name="account_id[default_advance_salary]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_advance_salary'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_employee_advance">Default Employee Advance</label>
                                            <input type="text" name="type[]" id="type" value="default_employee_advance" class="form-control" hidden>
                                            <select name="account_id[default_employee_advance]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_employee_advance'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="default_salary_payable">Default Salary Payable</label>
                                            <input type="text" name="type[]" id="type" value="default_salary_payable" class="form-control" hidden>
                                            <select name="account_id[default_salary_payable]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_salary_payable'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_owner_equity">Default Owner Equity</label>
                                            <input type="text" name="type[]" id="type" value="default_owner_equity" class="form-control" hidden>
                                            <select name="account_id[default_owner_equity]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_owner_equity'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_bank_charge">Default Bank Charge</label>
                                            <input type="text" name="type[]" id="type" value="default_bank_charge" class="form-control" hidden>
                                            <select name="account_id[default_bank_charge]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_bank_charge'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="default_tax_payable">Default Tax Payable</label>
                                            <input type="text" name="type[]" id="type" value="default_tax_payable" class="form-control" hidden>
                                            <select name="account_id[default_tax_payable]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_tax_payable'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_vendor_advance">Default Vendor Advance</label>
                                            <input type="text" name="type[]" id="type" value="default_vendor_advance" class="form-control" hidden>
                                            <select name="account_id[default_vendor_advance]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_vendor_advance'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default_customer_advance_payment">Default Customer Advance Payment</label>
                                            <input type="text" name="type[]" id="type" value="default_customer_advance_payment" class="form-control" hidden>
                                            <select name="account_id[default_customer_advance_payment]" id="account_id" class="form-control tom-select">
                                                <option value="" selected>Choose One</option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}"
                                                        @if(optional($defaultPayableReceivables->firstWhere('type', 'default_customer_advance_payment'))->account_id == $account->id) 
                                                        selected 
                                                    @endif>{{ $account->name }} ({{ $account->account_number }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
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

