
@section('title', 'Create Petty Cash')
@section('description', 'Create Petty Cash')
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
                                        {{ trans('menu.create-bills-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <div class="d-flex gap-2">
                                @if (hasPermission('hrm.bills.index'))
                                <a href="{{ route('hrm.bills.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                                @endif
                                @if (hasPermission('hrm.bills.create'))
                                <a href="{{ route('hrm.bills.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-bills-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title mt-4 mb-4">Personal Information</h2>
                            <form method="POST" action="{{ route('hrm.bills.store') }}" enctype="multipart/form-data">
                                @csrf
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="employee_id">Employee Name:</label>
                                        <select name="employee_id" class="form-control tom-select">
                                            <option value="">Select</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label>Date of Bill Claim:</label>
                                        <input type="text" name="date_of_bill_claim" class="form-control flatdate" value="{{ old('date_of_bill_claim') }}">
                                    </div>
                                </div>
                            
                                <ul class="nav nav-tabs mt-4" id="expenseTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ old('tab', 'transport') === 'transport' ? 'active' : '' }}" data-bs-toggle="tab" href="#transport-tab" role="tab">Transport Expense</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ old('tab') === 'general' ? 'active' : '' }}" data-bs-toggle="tab" href="#general-tab" role="tab">General Expense</a>
                                    </li>
                                </ul>
                            
                                <div class="tab-content mt-3">
                                    {{-- Transport Tab --}}
                                    <div class="tab-pane fade {{ old('tab', 'transport') === 'transport' ? 'show active' : '' }}" id="transport-tab">
                                        <div id="transport-container">
                                            @php $transport_count = max(1, count(old('date_of_expense', []))); @endphp
                                            @for ($i = 0; $i < $transport_count; $i++)
                                                <div class="form-group border p-3 mb-3">
                                                    <h5>Transport Expense</h5>
                                                    <button type="button" class="btn btn-sm btn-danger float-end remove-form-group">Remove</button>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label>Date of Expense:</label>
                                                            <input type="text" name="date_of_expense[]" class="form-control flatdate" value="{{ old("date_of_expense.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>From Location:</label>
                                                            <input type="text" name="from_location[]" class="form-control" value="{{ old("from_location.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>To Location:</label>
                                                            <input type="text" name="to_location[]" class="form-control" value="{{ old("to_location.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Transport By:</label>
                                                            <select name="transport_by[]" class="form-control">
                                                                <option value="">Select</option>
                                                                @foreach ($transport_types as $type)
                                                                    <option value="{{ $type->id }}" {{ old("transport_by.$i") == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Distance (KM):</label>
                                                            <input type="number" name="distance[]" class="form-control" value="{{ old("distance.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Amount:</label>
                                                            <input type="number" name="transport_amount[]" class="form-control" value="{{ old("transport_amount.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Settlement Amount:</label>
                                                            <input type="number" name="transport_settlement_amount[]" class="form-control" value="{{ old("transport_settlement_amount.$i") }}">
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Description:</label>
                                                            <textarea name="expense_description[]" class="form-control">{{ old("expense_description.$i") }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Receipts/Invoices:</label>
                                                            <x-file-uploader loadLater name="receipts_invoices_0" id="receipts_invoices" />
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Supporting Documents:</label>
                                                            <x-file-uploader loadLater name="supporting_documents_0" id="supporting_documents" />
                                                            {{-- <input type="file" name="supporting_documents[]" class="form-control" multiple> --}}
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <button type="button" id="add-transport" class="btn btn-primary">Add More</button>
                                    </div>
                            
                                    {{-- General Tab --}}
                                    <div class="tab-pane fade {{ old('tab') === 'general' ? 'show active' : '' }}" id="general-tab">
                                        <div id="general-container">
                                            @php $general_count = max(1, count(old('expense_date', []))); @endphp
                                            @for ($i = 0; $i < $general_count; $i++)
                                                <div class="form-group border p-3 mb-3">
                                                    <h5>General Expense</h5>
                                                    <button type="button" class="btn btn-sm btn-danger float-end remove-form-group">Remove</button>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label>Date of Expense:</label>
                                                            <input type="text" name="expense_date[]" class="form-control flatdate" value="{{ old("expense_date.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Expense Type:</label>
                                                            <select name="expense_type[]" class="form-control">
                                                                <option value="">Select</option>
                                                                @foreach ($expense_types as $type)
                                                                    <option value="{{ $type->id }}" {{ old("expense_type.$i") == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Amount:</label>
                                                            <input type="number" name="general_amount[]" class="form-control" value="{{ old("general_amount.$i") }}">
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label>Settlement Amount:</label>
                                                            <input type="number" name="general_settlement_amount[]" class="form-control" value="{{ old("general_settlement_amount.$i") }}">
                                                        </div>
                                                      
                                                        <div class="col-md-12 mb-2">
                                                            <label>Description:</label>
                                                            <textarea name="general_expense_description[]" class="form-control">{{ old("general_expense_description.$i") }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Receipts/Invoices:</label>
                                                            <x-file-uploader loadLater name="general_receipts_invoices_0" id="general_receipts_invoices" />
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Supporting Documents:</label>
                                                            <x-file-uploader loadLater name="general_supporting_documents_0" id="general_supporting_documents" />
                                                            {{-- <input type="file" name="supporting_documents[]" class="form-control" multiple> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <button type="button" id="add-general" class="btn btn-primary">Add More</button>
                                    </div>
                                </div>
                            
                                <input type="hidden" name="tab" id="tab" value="{{ old('tab', 'transport') }}">
                            
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Submit</button>
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
    $(document).ready(function () {
        // Tab switch tracking
        $('.nav-link[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('#tab').val($(e.target).attr('href') === '#transport-tab' ? 'transport' : 'general');
        });

        // Separate counters for each tab
        let transportCounter = 0;
        let generalCounter = 0;
        // const $formGroup = $container.find('.form-group').first().clone();
        const transportFormGroup = $('#transport-container .form-group').first().clone().prop('outerHTML');
        const generalFormGroup = $('#general-container .form-group').first().clone().prop('outerHTML');

        initializeFileUploader_receipts_invoices_receipts_invoices_0();
        initializeFileUploader_supporting_documents_supporting_documents_0();

        initializeFileUploader_general_receipts_invoices_general_receipts_invoices_0();
        initializeFileUploader_general_supporting_documents_general_supporting_documents_0();
        // Add/Remove logic
        function handleClone(btnId, containerId) {
            $('#' + btnId).on('click', function () {
                
                const $clone = containerId === 'transport-container' ? $(transportFormGroup) : $(generalFormGroup);
                const $container = $('#' + containerId);
                let counter;

                // Reset values
                $clone.find('input, textarea, select').each(function () {
                    $(this).val($(this).attr('type') === 'file' ? '' : '');
                });

                $container.append($clone);

                // Determine which counter to use
                if (containerId === 'transport-container') {
                    transportCounter++;
                    counter = transportCounter;
                } else {
                    generalCounter++;
                    counter = generalCounter;
                }

                // Handle file uploaders based on tab
                if (containerId === 'transport-container') {
                    // Transport tab elements
                    const receiptClass = `receipts_invoices_${counter}`;
                    $clone.find("#receipts_invoices")
                        .addClass(receiptClass);
                    $clone.find("#hidden-input-receipts_invoices_0")
                        .attr('name', `receipts_invoices_${counter}`);
                        
                    initializeFileUploader_receipts_invoices_receipts_invoices_0(receiptClass);

                    const supportingClass = `supporting_documents_${counter}`;
                    $clone.find("#supporting_documents")
                        .addClass(supportingClass);
                    $clone.find("#hidden-input-supporting_documents_0")
                        .attr('name', `supporting_documents_${counter}`);
                    initializeFileUploader_supporting_documents_supporting_documents_0(supportingClass);
                } else {
                    // General tab elements
                    const generalReceiptClass = `general_receipts_invoices_${counter}`;
                    $clone.find("#general_receipts_invoices")
                        .addClass(generalReceiptClass);
                    $clone.find("#hidden-input-general_receipts_invoices_0")
                        .attr('name', `general_receipts_invoices_${counter}`);
                    initializeFileUploader_general_receipts_invoices_general_receipts_invoices_0(generalReceiptClass);

                    const generalSupportingClass = `general_supporting_documents_${counter}`;
                    $clone.find("#general_supporting_documents")
                        .addClass(generalSupportingClass);
                    $clone.find("[name='general_supporting_documents_0']")
                        .attr('name', `general_supporting_documents_${counter}`);
                    initializeFileUploader_general_supporting_documents_general_supporting_documents_0(generalSupportingClass);
                }

                $clone.find('.flatdate').each(function () {
                    flatpickr(this  , {
                        altInput: true,
                        altFormat: "Y-m-d",
                        dateFormat: "Y-m-d",
                    });
                });
                
                bindRemove($clone.find('.remove-form-group'));
            });
        }

        function bindRemove($btn) {
            // console.log($btn);
            $btn.on('click', function () {
                console.log(this);
                
                $(this).closest('.form-group').remove();
            });
        }

        $('.remove-form-group').each(function () {
            bindRemove($(this));
        });

        handleClone('add-transport', 'transport-container');
        handleClone('add-general', 'general-container');
    });
</script>
    
    
@endSection
