@extends('layout.app')
@section('title', 'Default Payable Receivables')
@section('description', 'Default Payable Receivables')
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
                                        {{ trans('menu.accounting-menu-title') }}
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
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $defaultPayableReceivables])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Default Payment Account</th>
                                        <th>Default Bill Expense Payable</th>
                                        <th>Default Claim Bill</th>
                                        <th>Default Invoice Income Receivable</th>
                                        <th>Default Advance Salary</th>
                                        <th>Default Employee Advance</th>
                                        <th>Default Salary Payable</th>
                                        <th>Default Owner Equity</th>
                                        <th>Default Bank Charge</th>
                                        <th>Default Tax Payable</th>
                                        <th>Default Vendor Advance</th>
                                        <th>Default Customer Advance Payment</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($defaultPayableReceivables as $defaultPayableReceivable)
                                        <tr>
                                            <td>{{ ($defaultPayableReceivables->currentPage() - 1) * $defaultPayableReceivables->perPage() + $loop->iteration  }}</td>
                                            <td>{{ $defaultPayableReceivable->default_payment_account }}</td>
                                            <td>{{ $defaultPayableReceivable->default_bill_expense_payable }}</td>
                                            <td>{{ $defaultPayableReceivable->default_claim_bill }}</td>
                                            <td>{{ $defaultPayableReceivable->default_invoice_income_receivable }}</td>
                                            <td>{{ $defaultPayableReceivable->default_advance_salary }}</td>
                                            <td>{{ $defaultPayableReceivable->default_employee_advance }}</td>
                                            <td>{{ $defaultPayableReceivable->default_salary_payable }}</td>
                                            <td>{{ $defaultPayableReceivable->default_owner_equity }}</td>
                                            <td>{{ $defaultPayableReceivable->default_bank_charge }}</td>
                                            <td>{{ $defaultPayableReceivable->default_tax_payable }}</td>
                                            <td>{{ $defaultPayableReceivable->default_vendor_advance }}</td>
                                            <td>{{ $defaultPayableReceivable->default_customer_advance_payment }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if (hasPermission('account.account-settings.default-payable-receivables.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('account.account-settings.default-payable-receivables.edit', $defaultPayableReceivable->id) }}"><i class="far fa-edit"></i></a>
                                                    @endif
                                                    @if (hasPermission('account.account-settings.default-payable-receivables.destroy'))
                                                        <button type="button" data-action="{{ route('account.account-settings.default-payable-receivables.destroy', $defaultPayableReceivable->id) }}" class="btn btn-outline-danger delete-confirm" title="Delete"><i class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('account.account-settings.default-payable-receivables.show'))
                                                        <a class="btn btn-outline-primary" href="{{ route('account.account-settings.default-payable-receivables.show', $defaultPayableReceivable->id) }}" title="View"><i class="fas fa-eye"></i></a>
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

