
@section('title', 'Create TA/DA')
@section('description', 'Create TA/DA')
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
                                        <label for="employee_id">Employee Name</label>
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
                                        <label>Date of Bill Claim</label>
                                        <input type="text" name="date_of_bill_claim" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
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
                                                            {{-- <input type="text" id="transport_by"  name="transport_by[]" class="form-control clearInputField" value=""> --}}
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Distance (KM)</label>
                                                            <input type="number"  id="distance" name="distance[]" class="form-control clearInputField" value="">
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Amount</label>
                                                            <input type="number"  id="transport_amount" name="transport_amount[]" class="form-control ta_amt clearInputField" value="">
                                                        </div>
                                                        
                                                        <div class="col-md-12 mb-2">
                                                            <label>Description</label>
                                                            <input type="text"  id="expense_description" name="expense_description[]" class="form-control clearInputField" value="">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Receipts/Invoices</label>
                                                            <x-file-uploader loadLater name="receipts_invoices_0" id="receipts_invoices" />
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Supporting Documents</label>
                                                            <x-file-uploader loadLater name="supporting_documents_0" id="supporting_documents" />
                                                            {{-- <input type="file" name="supporting_documents[]" class="form-control" multiple> --}}
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
                                            </div>
                                        </div>
                                        <div id="transport-details-container">
                                        </div>
                                        
                                        <div class="col-sm-11 px-0 justify-content-sm-center"> 
                                            <div class="row col-sm-12 justify-content-sm-end"> 
                                                <label class="col-sm-2 col-form-label col-form-label-sm">Total Amount</label>                   
                                                <input type="text" class="form-control form-control-sm clr_field col-sm-2 text-right " readonly id="ta_total" name="ta_total" value="0.00"> 
                                            </div>
                                        </div>  
                                    </div>
                            
                                    {{-- General Tab --}}
                                    <div class="tab-pane fade {{ old('tab') === 'general' ? 'show active' : '' }}" id="general-tab">
                                        <div id="general-container"> 
                                                <div class="form-group border p-3 mb-3">
                                                    <h5>General Expense</h5>
                                                    <button type="button" class="btn btn-sm btn-danger float-end remove-form-group">Remove</button>
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
                                                        <div class="col-md-6 mb-2">
                                                            <label>Receipts/Invoices</label>
                                                            <x-file-uploader loadLater name="general_receipts_invoices_0" id="general_receipts_invoices" />
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                            {{-- <input type="file" name="receipts_invoices[]" class="form-control"> --}}
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Supporting Documents</label>
                                                            <x-file-uploader loadLater name="general_supporting_documents_0" id="general_supporting_documents" />
                                                            {{-- <input type="file" name="supporting_documents[]" class="form-control" multiple> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <button type="button" id="add-general" class="btn btn-primary">Add More</button>
                                        <div class="row">
                                        <div class=" bg-primary text-white shadow-sm py-2 my-2 fw-semibold rounded text-center">
                                            <div class="row col-sm-12">
                                                <div class="col-sm-1">SL</div>
                                                <div class="col-sm-2">Date</div>
                                                <div class="col-sm-2">Expense Type</div>
                                                <div class="col-sm-3">Description</div> 
                                                <div class="col-sm-2">Amount</div>
                                            </div>
                                        </div>
                                        <div id="general-expense-container">
                                        </div>
                                        
                                        <div class="col-sm-10 px-0 justify-content-sm-center"> 
                                            <div class="row col-sm-12 justify-content-sm-end"> 
                                                <label class="col-sm-2 col-form-label col-form-label-sm">Total Amount</label>                   
                                                <input type="text" class="form-control form-control-sm clr_field col-sm-2 text-right " readonly id="da_total" name="da_total" value="0.00"> 
                                            </div>
                                        </div> 
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

        //handleClone('add-transport', 'transport-container'); 
        //handleClone('add-general', 'general-container');

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
                 
                $('#expense_description').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-11').appendTo(div);
				  
				$('<div class="col-sm-1 px-0"><button class="btn btn-danger btn-sm" type="button" onClick="removeTaRow(this)"><span class="ta-data">'+srNo+'</span> <i class="fa fa-trash" aria-hidden="true"></i></button></div>').prependTo(div);
				$(div).appendTo($('#transport-details-container')); 
 

                $('.clearInputField').val('');
                serialAndTotalTa(); 
                
                // Transport tab elements
                const receiptClass = `receipts_invoices_${srNo}`;
                $("#receipts_invoices")
                    .addClass(receiptClass);
                $("#hidden-input-receipts_invoices_0")
                    .attr('name', `receipts_invoices_${srNo}`);
                    
                initializeFileUploader_receipts_invoices_receipts_invoices_0(receiptClass);

                const supportingClass = `supporting_documents_${srNo}`;
                $("#supporting_documents")
                    .addClass(supportingClass);
                $("#hidden-input-supporting_documents_0")
                    .attr('name', `supporting_documents_${srNo}`);
                initializeFileUploader_supporting_documents_supporting_documents_0(supportingClass);
 
                
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
				$('#expense_type_label').clone().removeAttr('id').removeClass('clearInputField').attr('type', 'text').addClass('col-sm-2').val(selectedText).appendTo(div);  
                $('#expense_type').clone().removeAttr('id').removeClass('clearInputField').val($('#expense_type_name').val()).appendTo(div);     
                $('#general_expense_description').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-3').appendTo(div); 
                $('#general_amount').clone().removeAttr('id').removeClass('clearInputField').addClass('col-sm-2').appendTo(div); 
				  
				$('<div class="col-sm-1 px-0"><button class="btn btn-danger btn-sm" type="button" onClick="removeDaRow(this)"><span class="da-data">'+slNo+'</span> <i class="fa fa-trash" aria-hidden="true"></i></button></div>').prependTo(div);
				$(div).appendTo($('#general-expense-container')); 

                $('.clearInputField').val('');
                serialAndTotalDa(); 

                // General tab elements
                const $clone = containerId === 'transport-container' ? $(transportFormGroup) : $(generalFormGroup); 
                const generalReceiptClass = `general_receipts_invoices_${slNo}`;
                $("#general_receipts_invoices")
                    .addClass(generalReceiptClass);
                $("#hidden-input-general_receipts_invoices_0")
                    .attr('name', `general_receipts_invoices_${slNo}`);
                initializeFileUploader_general_receipts_invoices_general_receipts_invoices_0(generalReceiptClass);

                const generalSupportingClass = `general_supporting_documents_${slNo}`;
                $("#general_supporting_documents")
                    .addClass(generalSupportingClass);
                $("[name='general_supporting_documents_0']")
                    .attr('name', `general_supporting_documents_${slNo}`);
                initializeFileUploader_general_supporting_documents_general_supporting_documents_0(generalSupportingClass);
                
                
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
