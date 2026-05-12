@section('title', 'Edit TA/DA ')
@section('description', 'Edit TA/DA ')
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
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <a href="{{ route('hrm.bills.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List
                                </a> 
                            </div>
                            <form method="POST" action="{{ route('hrm.bills.update', $billsAndAllowance->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee Name</label>
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
                                            <label for="date_of_bill_claim" class="form-label">Date of Bill Claim</label>
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
                                                    <div class="form-group border p-3 mb-3">
                                                        <h5>Transport Expense</h5> 
                                                        <div class="row expense-item">
                                                            <div class="col-md-2 mb-2">
                                                                <label>Date</label>
                                                                <input type="text" id="date_of_expense" name="date_of_expense[]" class="form-control flatdate clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>From Location</label>
                                                                <input type="text" id="from_location" name="from_location[]" class="form-control clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>To Location</label>
                                                                <input type="text" id="to_location"  name="to_location[]" class="form-control clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-2 mb-2"> 
                                                                <label>Transport By</label>
                                                                <select  id="transport_by_name" name="transport_by_name[]" class="form-control clearInputField">
                                                                    @foreach ($transport_types as $type)
                                                                        <option value="{{ $type->id }}">
                                                                            {{ $type->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" id="transport_by"  name="transport_by[]" class="form-control clearInputField" value="">
                                                                <input type="hidden" id="transport_by_label"  name="transport_by_label[]" class="form-control clearInputField" value="">
                                                            

                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>Distance (KM)</label>
                                                                <input type="number"  id="distance" name="distance[]" class="form-control text-end clearInputField" value="0">
                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>Amount</label>
                                                                <input type="number"  id="transport_amount" name="transport_amount[]" class="form-control ta_amt text-end clearInputField" value="0">
                                                            </div>
                                                            
                                                            <div class="col-md-12 mb-2">
                                                                <label>Description</label>
                                                                <input type="text"  id="expense_description" name="expense_description[]" class="form-control clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-6 mb-2 d-none">
                                                                <label>Receipts/Invoices</label>
                                                                {{-- <x-file-uploader loadLater name="receipts_invoices_0" id="receipts_invoices" /> --}}
                                                                <input type="file" id="receipts_invoices" name="receipts_invoices[]" class="form-control">
                                                                <input type="hidden" name="old_receipts_invoices[]"  value=""> 
                                                            </div>
                                                            <div class="col-md-6 mb-2 d-none">
                                                                <label>Supporting Documents</label>
                                                                {{-- <x-file-uploader loadLater name="supporting_documents_0" id="supporting_documents" /> --}}
                                                                <input type="file" id="supporting_documents" name="supporting_documents[]" class="form-control" multiple>
                                                                
                                                                <input type="hidden" name="old_supporting_documents[]"  value=""> 
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                <button type="button" id="add-transport" class="btn btn-primary">Add More</button> 
                                        
                                                <div class=" bg-primary text-white shadow-sm py-2 my-2 fw-semibold rounded text-center">
                                                    <div class="row col-sm-12">
                                                        <div class="col-sm-1">SL</div>
                                                        <div class="col-sm-2">Date</div>
                                                        <div class="col-sm-2">From Location</div>
                                                        <div class="col-sm-2">To Location</div>
                                                        <div class="col-sm-1">Transport By</div>
                                                        <div class="col-sm-1">Distance (KM)</div> 
                                                        <div class="col-sm-2">Amount</div> 
                                                        <div class="col-sm-1">Attach</div> 
                                                    </div>
                                                </div>
                                                <div id="transport-details-container">
                                                     @php
                                                        $totalTransportAmount = 0;
                                                    @endphp  
                                                    @foreach ($billsAndAllowance->transportExpenses as $key => $transportExpense) 
                                                        @php
                                                            $totalTransportAmount += $transportExpense->amount;
                                                        @endphp
                                                        <div class="row col-sm-12">
                                                            <div class="col-sm-1">
                                                                <button class="btn btn-danger btn-sm" type="button" onclick="removeTaRow(this)"><span class="ta-data">{{++$key}}</span> 
                                                                <i class="fa fa-trash" aria-hidden="true"></i></button> 
                                                            </div>
                                                            <input type="text" name="date_of_expense[]" class="form-control col-sm-2" value="{{ $transportExpense->date_of_expense }}">
                                                            <input type="text" name="from_location[]" class="form-control col-sm-2" value="{{ $transportExpense->from_location }}">
                                                            <input type="text" name="to_location[]" class="form-control col-sm-2" value="{{ $transportExpense->to_location }}">
                                                            <input type="text" name="transport_by_label[]" class="form-control col-sm-1" value="{{ $transportExpense->transportType->name  }}">
                                                            <input type="hidden" name="transport_by[]" class="form-control col-sm-1" value="{{ $transportExpense->transport_by }}"> 
                                                            <input type="text" name="distance[]" class="form-control col-sm-1 text-end" value="{{ $transportExpense->distance }}">
                                                            <input type="text" name="transport_amount[]" class="form-control ta_amt  col-sm-2 text-end" value="{{ $transportExpense->amount }}">
                                                            <div class="col-sm-1 d-flex gap-1 justify-content-start">
                                                           
                                                                <input type="hidden" name="old_receipts_invoices[]"  value="{{ $transportExpense->receipts_invoices }}"> 
                                                                <input type="hidden" name="old_supporting_documents[]"  value="{{ $transportExpense->supporting_documents }}"> 
                                                                
                                                              
                                                                @if(empty($transportExpense->receipts_invoices))
                                                                    <input type="file" name="receipts_invoices[]" class="form-control">
                                                                @else
                                                                    <input type="file" name="receipts_invoices[]" value="" class="d-none">
                                                                @endif


                                                                @if(empty($transportExpense->supporting_documents))
                                                                    <input type="file" name="supporting_documents[]" class="form-control" multiple>
                                                                @else
                                                                    <input type="file" name="supporting_documents[]" value="" class="d-none">
                                                                @endif


                                                                @if(!empty($transportExpense->receipts_invoices))
                                                                <a href="{{ $transportExpense->receipts_invoices }}" target="_blank"
                                                                    class="btn btn-outline-primary"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="View Attachment">
                                                                    <i class="las la-eye"></i>
                                                                </a> 
                                                                @endif
                                                                @if(!empty($transportExpense->supporting_documents))
                                                                <a href="{{ $transportExpense->supporting_documents }}" target="_blank"
                                                                    class="btn btn-outline-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    title="View Attachment">
                                                                    <i class="las la-eye"></i>
                                                                </a>
                                                                
                                                                @endif
                                                            </div>
                                                            <input type="text" name="expense_description[]" class="form-control col-sm-11" value="{{ $transportExpense->expense_description }}"> 
                                                            
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="col-sm-11 px-0 justify-content-sm-center"> 
                                                    <div class="row col-sm-12 justify-content-sm-end"> 
                                                        <label class="col-sm-2 col-form-label col-form-label-sm">Total Amount</label>                   
                                                        <input type="text" class="form-control form-control-sm clr_field col-sm-2 text-end " readonly id="ta_total" name="ta_total" value="{{$totalTransportAmount}}"> 
                                                    </div>
                                                </div>   
                                    
                                            </div>


                                            <div class="tab-pane fade {{ old('tab') === 'general' ? 'show active' : '' }}"
                                                id="general-tab">
                                                <div id="general-container"> 
                                                    <div class="form-group border p-3 mb-3">
                                                        <h5>General Expense</h5>
                                                        <div class="row">
                                                            <div class="col-md-2 mb-2">
                                                                <label>Date</label>
                                                                <input type="text" id="expense_date" name="expense_date[]" class="form-control flatdate clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>Expense Type</label>
                                                                <select id="expense_type_name" name="expense_type_name[]" class="form-control clearInputField">
                                                                    @foreach ($expense_types as $type)
                                                                        <option value="{{ $type->id }}">
                                                                            {{ $type->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <input type="hidden" id="expense_type"  name="expense_type[]" class="form-control clearInputField" value="">
                                                                <input type="hidden" id="expense_type_label"  name="expense_type_label[]" class="form-control clearInputField" value=""> 
                                                            </div>
                                                            <div class="col-md-2 mb-2">
                                                                <label>Amount</label>
                                                                <input type="number" id="general_amount" name="general_amount[]" class="form-control da_amt clearInputField" value="">
                                                            </div>
                                                        
                                                            <div class="col-md-6 mb-2">
                                                                <label>Description</label>
                                                                <input type="text" id="general_expense_description" name="general_expense_description[]" class="form-control clearInputField" value="">
                                                            </div>
                                                            <div class="col-md-6 mb-2 d-none">
                                                                <label>Receipts/Invoices</label>
                                                                {{-- <x-file-uploader loadLater name="general_receipts_invoices_0" id="general_receipts_invoices" /> --}}
                                                                <input type="file" id="general_receipts_invoices" name="general_receipts_invoices[]" class="form-control"> 
                                                                <input type="hidden" name="old_general_receipts_invoices[]"  value=""> 
                                                            </div>
                                                            <div class="col-md-6 mb-2 d-none">
                                                                <label>Supporting Documents</label>
                                                                {{-- <x-file-uploader loadLater name="general_supporting_documents_0" id="general_supporting_documents" /> --}}
                                                                <input type="file" id="general_supporting_documents" name="general_supporting_documents[]" class="form-control" multiple>
                                                             
                                                                <input type="hidden" name="old_general_supporting_documents[]"  value=""> 
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" id="add-general" class="btn btn-primary">
                                                    Add More
                                                </button>
                                                  
                                                <div class=" bg-primary text-white shadow-sm py-2 my-2 fw-semibold rounded text-center">
                                                    <div class="row col-sm-12">
                                                        <div class="col-sm-1">SL</div>
                                                        <div class="col-sm-2">Date</div>
                                                        <div class="col-sm-1">Expense Type</div>
                                                        <div class="col-sm-3">Description</div> 
                                                        <div class="col-sm-2">Amount</div>
                                                        <div class="col-sm-2">Attach</div>
                                                    </div>
                                                </div> 


                                                <div id="general-expense-container">
                                                    @php
                                                        $totalGeneralAmount = 0;
                                                    @endphp
                                                    @foreach ($billsAndAllowance->generalExpenses as $keygri => $generalExpense)
                                                        @php
                                                            $totalGeneralAmount += $generalExpense->amount;
                                                        @endphp
                                                        <div class="row col-sm-12">
                                                            <div class="col-sm-1">
                                                                <button class="btn btn-danger btn-sm" type="button" onclick="removeDaRow(this)"><span class="da-data">{{++$keygri}}</span> 
                                                                <i class="fa fa-trash" aria-hidden="true"></i></button> 
                                                            </div>
                                                            <input type="text" name="expense_date[]" class="form-control col-sm-2" value="{{ $generalExpense->expense_date }}">
                                                            <input type="text" name="expense_type_label[]" class="form-control col-sm-1" value="{{ $generalExpense->expenseType->name  }}">
                                                            <input type="hidden" name="expense_type[]" class="form-control col-sm-1" value="{{ $generalExpense->expense_type }}">  
                                                            <input type="text" name="general_expense_description[]" class="form-control col-sm-3" value="{{ $generalExpense->expense_description }}">
                                                            <input type="text" name="general_amount[]" class="form-control da_amt col-sm-2 text-end" value="{{ $generalExpense->amount }}"> 
                                                            
                                                            
                                                            <div class="col-sm-2 d-flex gap-1 justify-content-start">
                                                                <input type="hidden" name="old_general_receipts_invoices[]"  value="{{ $generalExpense->receipts_invoices }}"> 
                                                                <input type="hidden" name="old_general_supporting_documents[]"  value="{{ $generalExpense->supporting_documents }}"> 
                                                        
                                                                  
                                                                @if(empty($generalExpense->receipts_invoices))
                                                                    <input type="file" name="general_receipts_invoices[]" class="form-control">
                                                                @else
                                                                    <input type="file" name="general_receipts_invoices[]" value="" class="d-none">
                                                                @endif


                                                                @if(empty($generalExpense->supporting_documents))
                                                                    <input type="file" name="general_supporting_documents[]" class="form-control" multiple>
                                                                @else
                                                                    <input type="file" name="general_supporting_documents[]" value="" class="d-none">
                                                                @endif

                                                                @if(!empty($generalExpense->receipts_invoices))
                                                                <a href="{{ $generalExpense->receipts_invoices }}" target="_blank"
                                                                    class="btn btn-outline-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    title="View Attachment">
                                                                    <i class="las la-eye"></i>
                                                                </a>
                                                               
                                                                @endif
                                                                @if(!empty($generalExpense->supporting_documents))
                                                                <a href="{{ $generalExpense->supporting_documents }}" target="_blank"
                                                                    class="btn btn-outline-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    title="View Attachment">
                                                                    <i class="las la-eye"></i>
                                                                </a>
                                                                
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach  
                                                </div>
                                                
                                                <div class="col-sm-10 px-0 justify-content-sm-center"> 
                                                    <div class="row col-sm-12 justify-content-sm-end"> 
                                                        <label class="col-sm-2 col-form-label col-form-label-sm">Total Amount</label>                   
                                                        <input type="text" class="form-control form-control-sm clr_field col-sm-2 text-end " readonly id="da_total" name="da_total" value="{{$totalGeneralAmount}}"> 
                                                    </div>
                                                </div> 
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

        

        addTransportExpense('add-transport', 'transport-container'); 
        addGeneralExpense('add-general', 'general-container');
        
        let srNo = 0;
        function addTransportExpense(btnId, containerId)
        {

            
            $('#' + btnId).on('click', function () {

                let valid = true;

                if($('#date_of_expense').val() === '') {
                    alert('Please enter Date of Expense!'); 
                    valid = false;
                    return false; 
                }

                if($('#from_location').val() === '') {
                    alert('Please enter From Location!'); 
                    valid = false;
                    return false;
                }

                if($('#to_location').val() === '') {
                    alert('Please enter To Location!'); 
                    valid = false;
                    return false;
                }
                if($('#transport_by_name').val() === '') {
                    alert('Please enter Transport By!'); 
                    valid = false;
                    return false;
                }
                if($('#transport_amount').val() === '') {
                    alert('Please enter Transport Amount!'); 
                    valid = false;
                    return false;
                }

                let selectedText = $('#transport_by_name option:selected').text().trim();
                srNo++;
				let div = $('<div></div>').addClass('row col-sm-12');
				$('#date_of_expense').clone().removeAttr('id').removeClass('clearInputField').attr('type', 'text').addClass('col-sm-2').appendTo(div); 
				$('#from_location').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div); 
                $('#to_location').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div);  
                $('#transport_by_label').clone().removeAttr('id').removeClass('clearInputField').attr('type', 'text').addClass('col-sm-1').val(selectedText).appendTo(div);  
                $('#transport_by').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').val($('#transport_by_name').val()).appendTo(div);  
				$('#distance').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').appendTo(div);
                $('#transport_amount').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div);
                $('#receipts_invoices').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').appendTo(div);
                $('#expense_description').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-11').appendTo(div);
                $('#supporting_documents').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').appendTo(div);
				  
            

				$('<div class="col-sm-1 px-0"><button class="btn btn-danger btn-sm" type="button" onClick="removeTaRow(this)"><span class="ta-data">'+srNo+'</span> <i class="fa fa-trash" aria-hidden="true"></i></button></div>').prependTo(div);
				$(div).appendTo($('#transport-details-container')); 

                $('.clearInputField').val('');
                $('#distance,#transport_amount').val(0);
                 
                serialAndTotalTa(); 
                 
            });
            
        }


        let slNo = 0;
        function addGeneralExpense(btnId, containerId)
        {
  
            $('#' + btnId).on('click', function () {

                let valid = true;

                if($('#expense_date').val() === '') {
                    alert('Please enter Date of Expense!'); 
                    valid = false;
                    return false; 
                }

                if($('#expense_type_name').val() === '') {
                    alert('Please enter expense type!'); 
                    valid = false;
                    return false;
                }
               
                if($('#general_amount').val() === '') {
                    alert('Please enter Transport Amount!'); 
                    valid = false;
                    return false;
                }

                let selectedText = $('#expense_type_name option:selected').text().trim();
                slNo++;
				let div = $('<div></div>').addClass('row col-sm-12');
				$('#expense_date').clone().removeAttr('id').removeClass('clearInputField').attr('type', 'text').addClass('col-sm-2').appendTo(div); 
				$('#expense_type').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div); 
                $('#expense_type_label').clone().removeAttr('id').removeClass('clearInputField').attr('type', 'text').addClass('col-sm-1').val(selectedText).appendTo(div);  
                $('#expense_type').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').val($('#expense_type_name').val()).appendTo(div);    
                $('#general_expense_description').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-3').appendTo(div); 
                $('#general_amount').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div); 
				 
                $('#general_receipts_invoices').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').appendTo(div);
                $('#general_supporting_documents').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-1').appendTo(div);

            

				$('<div class="col-sm-1 px-0"><button class="btn btn-danger btn-sm" type="button" onClick="removeDaRow(this)"><span class="da-data">'+slNo+'</span> <i class="fa fa-trash" aria-hidden="true"></i></button></div>').prependTo(div);
				$(div).appendTo($('#general-expense-container')); 

                $('.clearInputField').val('');
                $('#general_amount').val(0);
                serialAndTotalDa(); 
 
                
            });
            
        }


    });

    function totalTa() {   
        let taTotal = 0; 

        $('.ta_amt').each(function() { 
            let val = $(this).val().replace(/,/g, '').trim();

            if(val !== '' && !isNaN(val)){
                taTotal += parseFloat(val);
            }
        });
  
        $('#ta_total').val(taTotal.toFixed(2));
    }

    function serialAndTotalTa() 
    { 
        let sl=0; 
        $('.ta-data').each(function(index, element) { 
            $(this).html(++sl); 
        }); 
        totalTa(); 
    }
    function removeTaRow(delId) 
    { 
        $(delId).parent().parent().remove(); 
        serialAndTotalTa(); 
    }


    function totalDa() {   
        let daTotal = 0; 

        $('.da_amt').each(function() { 
            let val = $(this).val().replace(/,/g, '').trim();

            if(val !== '' && !isNaN(val)){
                daTotal += parseFloat(val);
            }
        });
  
        $('#da_total').val(daTotal.toFixed(2));
    }

    function serialAndTotalDa() 
    { 
        let sl=0; 
        $('.da-data').each(function(index, element) { 
            $(this).html(++sl); 
        }); 
        totalDa(); 
    }
    function removeDaRow(delId) 
    { 
        $(delId).parent().parent().remove(); 
        serialAndTotalDa(); 
    }
    </script>




@endSection
