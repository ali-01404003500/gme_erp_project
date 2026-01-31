@section('title', 'Edit Petty Cash ')
@section('description', 'Edit Petty Cash ')
@extends('layout.app')

@section('page-header')
    <style>
        .card-body {
            margin-right: 7vh;
            margin-left: 7vh;
        }

        .row {
            padding-right: 1vh;
            padding-left: 1vh;
        }

        /* Style for all <a> tags */
        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            /* Background color */
            color: #3d3d3d;
            /* Text color */
            border-radius: 5px 5px 0 0;
            /* 5px radius for top-left and top-right corners */
        }

        /* Style for active tab */
        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            /* Background color */
            color: #ffffff;
            /* Text color */
        }

        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            /* Background color */
            color: #3d3d3d;
            /* Text color */
            border-radius: 5px 5px 0 0;
            /* 5px radius for top-left and top-right corners */
        }

        /* Style for active tab */
        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            /* Background color */
            color: #ffffff;
            /* Text color */
        }

        /* .ts-control {
                                    height: 48px !important;
                                } */
        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding-right: 40px;
            /* Adjust to make space for the icon */
            box-sizing: border-box;
        }

        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            /* Optional: adjust the icon color */
        }

        .password-container .toggle-password:hover {
            color: #333;
            /* Optional: adjust the hover color */
        }
    </style>
@endsection
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
                                        {{ trans('menu.edit-bills-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.bills.create'))
                                <a href="{{ route('hrm.bills.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary ">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-bills-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title mt-4 mb-4">Personal Information</h2>
                            <form method="POST" action="{{ route('hrm.bills.update', $billsAndAllowance->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee Name:</label>
                                            <select name="employee_id" id="employee_id" class="form-control tom-select">
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        @if ($employee->id == $billsAndAllowance->employee_id) selected @endif>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date_of_bill_claim" class="form-label">Date of Bill Claim:</label>
                                            <input type="text" class="form-control flatdate" id="date_of_bill_claim"
                                                name="date_of_bill_claim"
                                                value="{{ old('date_of_bill_claim', $billsAndAllowance->date_of_bill_claim) }}">
                                        </div>
                                    </div>


                                    {{-- start  --}}

                                    <div class="dm-tab tab-horizontal">
                                        <ul class="nav nav-tabs mt-4" id="expenseTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link {{ old('tab', 'transport') === 'transport' ? 'active' : '' }}"
                                                    data-bs-toggle="tab" href="#transport-tab" role="tab">Transport
                                                    Expense</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ old('tab') === 'general' ? 'active' : '' }}"
                                                    data-bs-toggle="tab" href="#general-tab" role="tab">General
                                                    Expense</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade {{ old('tab', 'transport') === 'transport' ? 'show active' : '' }}"
                                                id="transport-tab">

                                                <div id="transport-container">
                                                    @foreach ($billsAndAllowance->transportExpenses as $key => $transportExpense)
                                                        <div class="form-group">
                                                            <div class="transport-items">
                                                                <h3>Transport Expense</h3>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-form-group"
                                                                    style="float: right;">-</button>
                                                                <div class="row">
                                                                    <input type="hidden" name="transport_expense_id[]"
                                                                        value="{{ $transportExpense->id }}">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="date_of_expense"
                                                                                class="form-label">Date of Expense:</label>
                                                                            <input type="text"
                                                                                class="form-control flatdate"
                                                                                id="date_of_expense"
                                                                                name="date_of_expense[]"
                                                                                value="{{ $transportExpense->date_of_expense }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="from_location"
                                                                                class="form-label">From location:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="from_location" name="from_location[]"
                                                                                value="{{ $transportExpense->from_location }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="to_location" class="form-label">To
                                                                                location:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="to_location" name="to_location[]"
                                                                                value="{{ $transportExpense->to_location }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="transport_by"
                                                                                class="form-label">Transport by:</label>
                                                                            <select class="form-select" id="transport_by"
                                                                                name="transport_by[]">
                                                                                <option value="">Select Transport
                                                                                </option>
                                                                                @foreach ($transport_types as $type)
                                                                                    <option value="{{ $type->id }}"
                                                                                        {{ $type->id == $transportExpense->transport_by ? 'selected' : '' }}>
                                                                                        {{ $type->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="distance"
                                                                                class="form-label">Distance(KM):</label>
                                                                            <input type="number" class="form-control"
                                                                                id="distance" name="distance[]"
                                                                                value="{{ $transportExpense->distance }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="mb-3">
                                                                            <label for="expense_description"
                                                                                class="form-label">Expense
                                                                                Description:</label>
                                                                            <textarea class="form-control" id="expense_description" name="expense_description[]">{{ $transportExpense->expense_description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="amount"
                                                                                class="form-label">Transport
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="amount" name="transport_amount[]"
                                                                                value="{{ $transportExpense->amount }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="settlement_amount"
                                                                                class="form-label">Settlement
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="settlement_amount"
                                                                                name="transport_settlement_amount[]"
                                                                                value="{{ $transportExpense->settlement_amount }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label
                                                                                for="receipts_invoices_{{ $key }}"
                                                                                class="form-label">Receipts/Invoices:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="receipts_invoices_{{ $key }}"
                                                                                name="receipts_invoices_{{ $key }}"
                                                                                :value="$transportExpense->receipts_invoices"
                                                                                id="receipts_invoices_{{ $key }}" />
                                                                        </div>
                                                                    </div>
                                                               
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="supporting_documents"
                                                                                class="form-label">Supporting
                                                                                Documents:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="supporting_documents_{{ $key }}"
                                                                                name="supporting_documents_{{ $key }}"
                                                                                :value="$transportExpense->supporting_documents"
                                                                                id="supporting_documents_{{ $key }}" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if ($billsAndAllowance->transportExpenses->isEmpty())
                                                        <div class="form-group">
                                                            <div class="transport-items">
                                                                <h3>Transport Expense</h3>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-form-group"
                                                                    style="float: right;">-</button>
                                                                <div class="row">
                                                                    <input type="hidden" name="transport_expense_id[]">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="date_of_expense"
                                                                                class="form-label">Date of Expense:</label>
                                                                            <input type="text"
                                                                                class="form-control flatdate"
                                                                                id="date_of_expense"
                                                                                name="date_of_expense[]">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="from_location"
                                                                                class="form-label">From location:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="from_location" name="from_location[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="to_location" class="form-label">To
                                                                                location:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="to_location" name="to_location[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="transport_by"
                                                                                class="form-label">Transport by:</label>
                                                                            <select class="form-select" id="transport_by"
                                                                                name="transport_by[]">
                                                                                <option value="">Select Transport
                                                                                </option>
                                                                                @foreach ($transport_types as $type)
                                                                                    <option value="{{ $type->id }}">
                                                                                        {{ $type->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="distance"
                                                                                class="form-label">Distance(KM):</label>
                                                                            <input type="number" class="form-control"
                                                                                id="distance" name="distance[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="mb-3">
                                                                            <label for="expense_description"
                                                                                class="form-label">Expense
                                                                                Description:</label>
                                                                            <textarea class="form-control" id="expense_description" name="expense_description[]"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="amount"
                                                                                class="form-label">Transport
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="amount" name="transport_amount[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="settlement_amount"
                                                                                class="form-label">Settlement
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="settlement_amount"
                                                                                name="transport_settlement_amount[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="receipts_invoices_0"
                                                                                class="form-label">Receipts/Invoices:</label>
                                                                            <x-file-uploader loadLater class="receipts_invoices_0" name="receipts_invoices_0" id="receipts_invoices_0" />
                                                                        </div>
                                                                    </div>
                                                               
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="supporting_documents"
                                                                                class="form-label">Supporting
                                                                                Documents:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="supporting_documents_0"
                                                                                name="supporting_documents_0"
                                                                                id="supporting_documents_0" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <button type="button" id="add-transport" class="btn btn-primary">Add
                                                    More</button>

                                            </div>


                                            <div class="tab-pane fade {{ old('tab') === 'general' ? 'show active' : '' }}"
                                                id="general-tab">
                                                <div id="general-container">
                                                    @foreach ($billsAndAllowance->generalExpenses as $keygri => $generalExpense)
                                                        <div class="form-group-2">
                                                            <div class="general-items">
                                                                <h3>Expense Details</h3>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-form-group-2"
                                                                    style="float: right;">-</button>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_date"
                                                                                class="form-label">Date of Expense:</label>
                                                                            <input type="text"
                                                                                class="form-control flatdate"
                                                                                id="expense_date" name="expense_date[]"
                                                                                value="{{ $generalExpense->expense_date }}">
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="general_expense_id[]"
                                                                        value="{{ $generalExpense->id }}">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_type"
                                                                                class="form-label">Expense Type:</label>
                                                                            <select class="form-select" id="expense_type"
                                                                                name="expense_type[]">
                                                                                <option value="">Select Expense Type
                                                                                </option>
                                                                                @foreach ($expense_types as $key => $type)
                                                                                    <option value="{{ $type->id }}"
                                                                                        {{ $type->id == $generalExpense->expense_type ? 'selected' : '' }}>
                                                                                        {{ $type->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_description"
                                                                                class="form-label">Expense
                                                                                Description:</label>
                                                                            <textarea class="form-control" id="expense_description" name="general_expense_description[]">{{ $generalExpense->expense_description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="amount"
                                                                                class="form-label">General Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="amount" name="general_amount[]"
                                                                                value="{{ $generalExpense->amount }}">
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="settlement_amount"
                                                                                class="form-label">Settlement
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="settlement_amount"
                                                                                name="general_settlement_amount[]"
                                                                                value="{{ $generalExpense->settlement_amount }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="receipts_invoices"
                                                                                class="form-label">Receipts/Invoices:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="general_receipts_invoices_{{ $keygri }}"
                                                                                name="general_receipts_invoices_{{ $keygri }}"
                                                                                :value="$generalExpense->receipts_invoices"
                                                                                id="general_receipts_invoices_{{ $keygri }}" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="supporting_documents"
                                                                                class="form-label">Supporting
                                                                                Documents:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="general_supporting_documents_{{ $keygri }}"
                                                                                name="general_supporting_documents_{{ $keygri }}"
                                                                                :value="$generalExpense->supporting_documents"
                                                                                id="general_supporting_documents_{{ $keygri }}" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    @if ($billsAndAllowance->generalExpenses->isEmpty())
                                                        <div class="form-group-2">
                                                            <div class="general-items">
                                                                <h3>Expense Details</h3>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-form-group-2"
                                                                    style="float: right;">-</button>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_date"
                                                                                class="form-label">Date of Expense:</label>
                                                                            <input type="text"
                                                                                class="form-control flatdate"
                                                                                id="expense_date" name="expense_date[]">
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="general_expense_id[]">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_type"
                                                                                class="form-label">Expense Type:</label>
                                                                            <select class="form-select" id="expense_type"
                                                                                name="expense_type[]">
                                                                                <option value="">Select Expense Type
                                                                                </option>
                                                                                @foreach ($expense_types as $type)
                                                                                    <option value="{{ $type->name }}">
                                                                                        {{ $type->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="expense_description"
                                                                                class="form-label">Expense
                                                                                Description:</label>
                                                                            <textarea class="form-control" id="expense_description" name="general_expense_description[]"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="amount"
                                                                                class="form-label">General Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="amount" name="general_amount[]">
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="settlement_amount"
                                                                                class="form-label">Settlement
                                                                                Amount:</label>
                                                                            <input type="number" class="form-control"
                                                                                id="settlement_amount"
                                                                                name="general_settlement_amount[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="receipts_invoices"
                                                                                class="form-label">Receipts/Invoices:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="general_receipts_invoices_0"
                                                                                name="general_receipts_invoices_0"
                                                                                id="general_receipts_invoices_0" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="supporting_documents"
                                                                                class="form-label">Supporting
                                                                                Documents:</label>
                                                                            <x-file-uploader loadLater
                                                                                class="general_supporting_documents_0"
                                                                                name="general_supporting_documents_0"
                                                                                id="general_supporting_documents_0" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <button type="button" id="add-general" class="btn btn-primary">Add
                                                    More</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="tab" id="tab"
                                        value="{{ old('tab', 'transport') }}">




                                    {{-- <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <a href="{{ route('hrm.billss.index') }}" class="btn btn-warning btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                            <button type="submit" class="btn btn-primary radius-md shadow2 btn-sm">
                                                Update
                                            </button>
                                        </div> --}}

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <a href="{{ route('hrm.bills.index') }}"
                                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                                class="fa fa-list"></i> List</a>
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                            Update
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


    <script>
        $(document).ready(function() {
    // Tab switch tracking
    $('.nav-link[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('#tab').val($(e.target).attr('href') === '#transport-tab' ? 'transport' : 'general');
    });

    // Separate counters for each tab
    let transportCounter = 0;
    let generalCounter = 0;
    
    const transportFormGroup = $('#transport-container .form-group').first().clone().prop('outerHTML');
    const generalFormGroup = $('#general-container .form-group-2').first().clone().prop('outerHTML');

    @foreach ($billsAndAllowance->transportExpenses as $key => $transportExpense)
        initializeFileUploader_receipts_invoices_{{ $key }}_receipts_invoices_{{ $key }}();
        initializeFileUploader_supporting_documents_{{ $key }}_supporting_documents_{{ $key }}();
        transportCounter++;
    @endforeach
    
    @if ($billsAndAllowance->transportExpenses->isEmpty())
        initializeFileUploader_receipts_invoices_0_receipts_invoices_0();
        initializeFileUploader_supporting_documents_0_supporting_documents_0();
    @endif
    
    @foreach ($billsAndAllowance->generalExpenses as $key => $generalExpense)
        initializeFileUploader_general_receipts_invoices_{{ $key }}_general_receipts_invoices_{{ $key }}();
        initializeFileUploader_general_supporting_documents_{{ $key }}_general_supporting_documents_{{ $key }}();
        generalCounter++
    @endforeach
    
    @if ($billsAndAllowance->generalExpenses->isEmpty())
        initializeFileUploader_general_receipts_invoices_0_general_receipts_invoices_0();
        initializeFileUploader_general_supporting_documents_0_general_supporting_documents_0();
    @endif

    // Add/Remove logic
    function handleClone(btnId, containerId) {
        $('#' + btnId).on('click', function() {
            const $clone = containerId === 'transport-container' ? $(transportFormGroup) : $(generalFormGroup);
            const $container = $('#' + containerId);
            let counter = 0;

            // Reset values
            $clone.find('input, textarea, select').each(function() {
                $(this).val($(this).attr('type') === 'file' ? '' : '');
            });

            $container.append($clone);

            // Determine which counter to use
            if (containerId === 'transport-container') {
                counter = transportCounter;
                transportCounter++;
            } else {
                counter = generalCounter;
                generalCounter++;
            }

            // Handle file uploaders based on tab
            if (containerId === 'transport-container') {
                // Transport tab elements
                const receiptClass = `receipts_invoices_${counter}`;
                $clone.find("#receipts_invoices_0")
                    .addClass(receiptClass);
                $clone.find("#hidden-input-receipts_invoices_0")
                    .attr('name', `receipts_invoices_${counter}`);

                initializeFileUploader_receipts_invoices_0_receipts_invoices_0(receiptClass);

                const supportingClass = `supporting_documents_${counter}`;
                $clone.find("#supporting_documents_0")
                    .addClass(supportingClass);
                $clone.find("#hidden-input-supporting_documents_0")
                    .attr('name', `supporting_documents_${counter}`);
                initializeFileUploader_supporting_documents_0_supporting_documents_0(supportingClass);
            } else {
                // General tab elements
                const generalReceiptClass = `general_receipts_invoices_${counter}`;
                $clone.find("#general_receipts_invoices_0")
                    .addClass(generalReceiptClass);
                $clone.find("#hidden-input-general_receipts_invoices_0")
                    .attr('name', `general_receipts_invoices_${counter}`);
                initializeFileUploader_general_receipts_invoices_0_general_receipts_invoices_0(generalReceiptClass);

                const generalSupportingClass = `general_supporting_documents_${counter}`;
                $clone.find("#general_supporting_documents_0")
                    .addClass(generalSupportingClass);
                $clone.find("[name='general_supporting_documents_0']")
                    .attr('name', `general_supporting_documents_${counter}`);
                initializeFileUploader_general_supporting_documents_0_general_supporting_documents_0(generalSupportingClass);
            }

            $clone.find('.flatdate').each(function() {
                flatpickr(this, {
                    altInput: true,
                    altFormat: "Y-m-d",
                    dateFormat: "Y-m-d",
                });
            });

            // Update remove button text
            $clone.find('.remove-form-group, .remove-form-group-2').text('Remove');
            
            bindRemove($clone.find('.remove-form-group'));
            bindRemove($clone.find('.remove-form-group-2'));
        });
    }

    function bindRemove($btn) {
        $btn.on('click', function() {
            $(this).closest('.form-group').remove();
            $(this).closest('.form-group-2').remove();
        });
    }

    // Set initial text for existing remove buttons
    $('.remove-form-group, .remove-form-group-2').text('Remove');
    
    $('.remove-form-group').each(function() {
        bindRemove($(this));
    });

    $('.remove-form-group-2').each(function() {
        bindRemove($(this));
    });

    handleClone('add-transport', 'transport-container');
    handleClone('add-general', 'general-container');
});
    </script>




@endSection
