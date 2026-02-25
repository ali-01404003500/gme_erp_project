
@section('title', 'My Task List')
@section('description', 'My Task List')
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
                                        {{ trans('My Task list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn d-flex align-items-center">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12" >
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('My Task list') }}</h4>
                    <x-error-alart />
                </div>
            </div>
        
            <div class="row mb-4">
                <form action="{{ route('services.service-my-task.store') }}" id="serviceMyTaskForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="service_token_id" value="{{ $serviceToken->id }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Token No</label>
                                                    <input type="text" class="form-control" value="{{ $serviceToken->service->service_unique_id ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Token Date</label>
                                                    <input type="text" class="form-control" value="{{ optional($serviceToken)->token_date }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Status</label>
                                                    <input type="text" class="form-control" value="{{ $serviceToken->action }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @if($serviceToken->action == 'Live')
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <div>
                                                        <label class="col-form-label">Update Status</label>
                                                        <button type="submit" id="go-live" class="btn btn-info btn-sm">Go Live</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Product</label>
                                                    <input type="text" class="form-control" value="{{ optional(optional($serviceToken)->product)->name }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Model</label>
                                                    <input type="text" class="form-control" value="{{ optional(optional($serviceToken)->product)->model }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Serial</label>
                                                    <input type="text" class="form-control" value="{{ optional($serviceToken)->serial_number }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <div>
                                                    {{-- @dd() --}}
                                                    <label class="col-form-label">Customer</label>
                                                    <input type="text" id="customer_name" class="form-control" value="{{ optional($serviceToken)->customer->company_name }}" readonly>
                                                    <input type="hidden" name="customer_id" id="customer_id" value="{{ optional($serviceToken)->customer_id }}">
                                                    <input type="hidden"  id="credit_limit" value="{{ optional($serviceToken)->customer->customerSetting->first()->credit_limit }}">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Contact No</label>
                                                    <input type="text" class="form-control" value="{{ optional($serviceToken)->customer->phone }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Customer Address</label>
                                                    <input type="text" class="form-control" value="{{ optional($serviceToken)->customer->address }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <div>
                                                    <label class="col-form-label">Assign By</label>
                                                    <input type="text" class="form-control" value="{{$serviceToken->service->createdBy->name }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                        <div class="row mt-3">
                                            <div class="col-md-12 mt-3">
                                                <h5 class="text-uppercase">Add Pending Service Token</h5>
                                            </div>
                                            <div class="col-md-12">
                                                @include('Services::service-my-task.pending-services')
                                            </div>
                                        </div>
                                    </div>

                                    @include('Services::service-my-task.service-bill')

                                    <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                        <div class="row mt-3">
                                            <div class="col-md-12 mt-3 mb-3">
                                                <h5 class="text-uppercase">Payment Information</h5>
                                            </div>
                                            {{-- @dd($serviceMyTask?->payments) --}}
                                            <div class="col-md-12">
                                                @include("Services::service-my-task.paymets", ['payments' => $serviceMyTask?->payments??null])
                                            </div>
                                        </div>
                                    </div>

                                    <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h5 class="text-uppercase mt-3 mb-3">Basic Info</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="basic_info_supply_voltage">Supply Voltage</label>
                                                    <input type="text" class="form-control" id="basic_info_supply_voltage" name="basic_info_supply_voltage" placeholder="Supply Voltage" value="{{ old('basic_info_supply_voltage', $serviceMyTask?->basic_info_supply_voltage) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Generator Backup</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="custom-control custom-radio mr-3">
                                                            <input type="radio" id="basic_info_generatorBackupYes" name="basic_info_generator_backup" class="custom-control-input" value="1" {{ old('basic_info_generator_backup', $serviceMyTask?->basic_info_generator_backup) == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="basic_info_generatorBackupYes">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="basic_info_generatorBackupNo" name="basic_info_generator_backup" class="custom-control-input" value="0" {{ old('basic_info_generator_backup', $serviceMyTask?->basic_info_generator_backup) == 0 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="basic_info_generatorBackupNo">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="basic_info_ground_voltage">Ground Voltage</label>
                                                    <input type="text" class="form-control" id="basic_info_ground_voltage" name="basic_info_ground_voltage" placeholder="Ground Voltage" value="{{ old('basic_info_ground_voltage', $serviceMyTask?->basic_info_ground_voltage) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>UPS Backup</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="custom-control custom-radio mr-3">
                                                            <input type="radio" id="basic_info_upsBackupOnline" name="basic_info_ups_backup" class="custom-control-input" value="online" {{ old('basic_info_ups_backup', $serviceMyTask?->basic_info_ups_backup) == 'online' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="basic_info_upsBackupOnline">Online</label>
                                                        </div>
                                                        <div class="custom-control custom-radio mr-3">
                                                            <input type="radio" id="basic_info_upsBackupOffline" name="basic_info_ups_backup" class="custom-control-input" value="offline" {{ old('basic_info_ups_backup', $serviceMyTask?->basic_info_ups_backup) == 'offline' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="basic_info_upsBackupOffline">Offline</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="basic_info_upsBackupNo" name="basic_info_ups_backup" class="custom-control-input" value="no" {{ old('basic_info_ups_backup', $serviceMyTask?->basic_info_ups_backup) == 'no' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="basic_info_upsBackupNo">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h5 class="text-uppercase mt-3 mb-3">Handover Info</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_name">Name</label>
                                                    <input type="text" class="form-control" id="handover_info_name" name="handover_info_name" placeholder="Name" value="{{ old('handover_info_name', $serviceMyTask?->handover_info_name) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_department">Department</label>
                                                    <input type="text" class="form-control" id="handover_info_department" name="handover_info_department" placeholder="Department" value="{{ old('handover_info_department', $serviceMyTask?->handover_info_department) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_designation">Designation</label>
                                                    <input type="text" class="form-control" id="handover_info_designation" name="handover_info_designation" placeholder="Designation" value="{{ old('handover_info_designation', $serviceMyTask?->handover_info_designation) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_contact_no">Contact No</label>
                                                    <input type="text" class="form-control" id="handover_info_contact_no" name="handover_info_contact_no" placeholder="Contact No" value="{{ old('handover_info_contact_no', $serviceMyTask?->handover_info_contact_no) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    @if($serviceToken->work_type=='New Installation' || $serviceToken->work_type=='Re Installation' || $serviceToken->work_type=='Operating Training')
                                    
                                    <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h5 class="text-uppercase mt-3 mb-3">Operator Info</h5>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label>Operator Training Status</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="operator_info_training_statusYes" name="operator_info_training_status" class="custom-control-input" value="yes" {{ old('operator_info_training_status', $serviceMyTask?->operator_info_training_status) == 'yes' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="operator_info_training_statusYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="operator_info_training_statusNo" name="operator_info_training_status" class="custom-control-input" value="no" {{ old('operator_info_training_status', $serviceMyTask?->operator_info_training_status) == 'no' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="operator_info_training_statusNo">No</label>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_name">Operator Name</label>
                                                    <input type="text" class="form-control" id="operator_info_name" name="operator_info_name" placeholder="Name" value="{{ old('operator_info_name', $serviceMyTask?->operator_info_name) }}">
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="handover_info_designation">Operator Designation</label>
                                                    <input type="text" class="form-control" id="operator_info_designation" name="operator_info_designation" placeholder="Designation" value="{{ old('operator_info_designation', $serviceMyTask?->operator_info_designation) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="handover_info_contact_no">Operator Contact No</label>
                                                    <input type="text" class="form-control" id="operator_info_contact_no" name="operator_info_contact_no" placeholder="Contact No" value="{{ old('operator_info_contact_no', $serviceMyTask?->operator_info_contact_no) }}">
                                                </div>
                                            </div>
                                               <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="handover_info_contact_no">Operator Comments</label> 
                                                    <input type="text" class="form-control" id="operator_comments" name="operator_comments" placeholder="Enter Operator Comments" value="{{ old('operator_info_contact_no', $serviceMyTask?->operator_comments) }}">
                                                </div>
                                            </div>
                                             
                                        </div>
                                    </div>
                                    @endif 

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                                <div class="row my-3">
                                                    <h5 class="text-uppercase mt-3 mb-3">Attachments</h5>
                                                    <div class="form-group">
                                                        <label for="attachments" class="form-label">Upload Attachments</label>
                                                        {{-- @dd($serviceMyTask?->attachments) --}}
                                                        <x-file-uploader name="attachments" multiple :value="$serviceMyTask?->attachments"/>

                                                        <small class="text-muted">You can upload multiple files. Allowed formats: jpg, jpeg, png, pdf, docx, xlsx.</small>
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                                <label for="tips_amount" class="col-form-label">Tips Amount</label>
                                                <input type="number" class="form-control" id="tips_amount" name="tips_amount" placeholder="Tips Amount" value="{{ old('tips_amount', $serviceMyTask?->tips_amount ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="remarks" class="col-form-label">Remark</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter your remark here">{{ old('remarks', $serviceMyTask?->remarks) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipment Information Section -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div style="padding-right: 20px; padding-left: 20px; outline: 1px solid #e4e4e4;">
                                                <div class="row my-3">
                                                    <h5 class="text-uppercase mt-3 mb-3">Shipment Information</h5>
                                                    <div class="col-md-6">
                                                        <fieldset class="border p-2">
                                                            <legend class="float-none w-auto p-2">
                                                                Shipment Information
                                                                <input type="checkbox" name="is_shipment" value="1"
                                                                    @if (old('is_shipment', $serviceMyTask?->shipment?->is_shipment)) checked @endif
                                                                    id="shipmentConfirm" tabindex="1015">
                                                            </legend>
                                                            <div class="row" id="shipment_info_div">
                                                                <div class="col-md-6">
                                                                    <select name="area_id" id="area_id" class="form-select tom-select" disabled>
                                                                    <option value="address" selected>New Address</option>
                                                                    @foreach ($areas as $area)
                                                                        <option value="{{ $area->id }}" {{ optional($serviceMyTask?->shipment)->area_id == $area->id ? 'selected' : '' }}>
                                                                            {{ $area->area }}</option>
                                                                    @endforeach
                                                                </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <input type="text" name="address"
                                                                            class="form-control" id="address"
                                                                            placeholder="Shipping Address"
                                                                            @if(!$serviceMyTask?->shipment) disabled @endif
                                                                            value="{{ old('address', $serviceMyTask?->shipment?->address) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <input type="text" name="contact_person_name"
                                                                            class="form-control" id="contact_person_name"
                                                                            placeholder="Contact Person Name"
                                                                            @if(!$serviceMyTask?->shipment) disabled @endif
                                                                            value="{{ old('contact_person_name', $serviceMyTask?->shipment?->contact_person_name) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <input type="text" name="contact_person_number"
                                                                            class="form-control" id="contact_person_phone"
                                                                            placeholder="Contact Person Phone"
                                                                            @if(!$serviceMyTask?->shipment) disabled @endif
                                                                            value="{{ old('contact_person_number', $serviceMyTask?->shipment?->contact_person_number) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <fieldset class="border p-2">
                                                            <legend class="float-none w-auto p-2">
                                                                Courier Information
                                                                <input type="checkbox" name="is_courier" value="1"
                                                                    @if (old('is_courier', $serviceMyTask?->shipment?->is_courier)) checked @endif
                                                                    id="courierConfirm" tabindex="1019">
                                                            </legend>
                                                            <div class="col-md-12" id="courier_info_div">
                                                                <div class="mb-3">
                                                                    <select name="courier_id" id="courier_id"
                                                                        class="form-select tom-select"
                                                                        @if(!$serviceMyTask?->shipment) disabled @endif>
                                                                        <option value="">Search Courier Name</option>
                                                                        @foreach ($couriers ?? [] as $courier)
                                                                            <option value="{{ $courier->id }}"
                                                                                @if (old('courier_id', $serviceMyTask?->shipment?->courier_id) == $courier->id) selected @endif>
                                                                                {{ $courier->courier_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row condition_div">
                                                                <div class="col-md-6">
                                                                    <div class="input-group align-items-center">
                                                                        <label for="additional_amount"
                                                                            class="input-group-text">Additional Amount</label>
                                                                        <input type="text" name="additional_amount"
                                                                            id="additional_amount" class="form-control"
                                                                            @if(!$serviceMyTask?->shipment) disabled @endif
                                                                            value="{{ old('additional_amount', $serviceMyTask?->shipment?->additional_amount) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="condition"
                                                                        class="form-label">Condition</label>
                                                                    <input type="checkbox" name="condition" id="condition"
                                                                        tabindex="1020"
                                                                        @if(!$serviceMyTask?->shipment) disabled @endif
                                                                        @if(old('condition', $serviceMyTask?->shipment?->condition)) checked @endif>
                                                                </div>

                                                            </div>
                                                            <div class="row condition_div">
                                                                <p class="text-danger">(Previous Due Adjust With Condition)</p>
                                                                <div>
                                                                    <textarea name="condition_remarks" id="condition_remarks" class="form-control" placeholder="Remarks"
                                                                        @if(!$serviceMyTask?->shipment) disabled @endif>{{ old('condition_remarks', $serviceMyTask?->shipment?->condition_remarks) }}</textarea>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <input type="hidden" name="status" id="status" value="pending">
                                            <input type="hidden" name="is_sms_verified" id="is_sms_verified" value="0">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Temporary Save
                                            </button>
                                            @if (hasPermission('sales.sales-orders.approve'))
                                                <button type="submit" id="approve" class="btn btn-success">
                                                    <i class="fa fa-check"></i> Save and bill
                                                </button>
                                            @endif
                                            <button type="submit" id="reject" class="btn btn-danger">
                                                <i class="fa fa-times"></i> Quit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('Sales::sales-order.opt-verification')
                    {{-- @dd($serviceMyTask->otpVerifications ) --}}
                    @foreach ($serviceMyTask?->otpVerifications??[] as  $otpVerification)
                        <input type="hidden" name="otp_verifications[]" value="{{ json_encode($otpVerification) }}">
                    @endforeach
                </form>
            </div>

            <!-- OTP Verification Modal -->
            <div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="otpVerificationModalLabel">OTP Verification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>An OTP will be sent to the customer's phone number: <strong>{{ optional($serviceToken)->customer->phone }}</strong></p>
                            <div id="otp-error" class="alert alert-danger" style="display: none;"></div>
                            <div id="otp-success" class="alert alert-success" style="display: none;"></div>
                            
                            <div class="d-grid  text-end">
                                <button class="btn btn-primary" id="sendOtpBtn">Send OTP</button>
                            </div>

                            <div id="otp-input-group" class="mt-3" style="display: none;">
                                <div class="form-group ">
                                    <label for="otp-input">Enter OTP</label>
                                    <input type="text" class="form-control" id="otp-input" placeholder="Enter the 6-digit OTP">
                                </div>
                                <div class="d-grid text-end">
                                    <button class="btn btn-success" id="verifyOtpBtn">Verify and Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page_scripts')
<script>
    function additionalSubmit(form) {
        if($("#is_sms_verified").val() == "1") {
            form.submit();
            return true;
        }
        $('#otpVerificationModal').modal('show');
    }
    $(document).ready(function() {
        $('#approve').click(function(e) {
            // e.preventDefault();
            $("#status").val("approved");
        });

        $('#reject').click(function() {
            $("#status").val("cancelled");
            return true;
        });

        $('#go-live').click(function() {
            $("#status").val("live");
            return true;
        });

        $('#sendOtpBtn').click(function() {
            const btn = $(this);
            btn.prop('disabled', true).text('Sending...');
            $('#otp-error').hide();
            $('#otp-success').hide();

            $.ajax({
                url: '{{ route("services.send-otp") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: '{{ optional($serviceToken)->customer_id }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#otp-success').text('OTP sent successfully!').show();
                        $('#otp-input-group').show();
                        btn.hide();
                    } else {
                        $('#otp-error').text(response.message || 'Failed to send OTP.').show();
                        btn.prop('disabled', false).text('Send OTP');
                    }
                },
                error: function(xhr) {
                    $('#otp-error').text(xhr.responseJSON?.message || 'An error occurred. Please try again.').show();
                    btn.prop('disabled', false).text('Send OTP');
                }
            });
        });

        $('#verifyOtpBtn').click(function() {
            const btn = $(this);
            const otp = $('#otp-input').val();
            btn.prop('disabled', true).text('Verifying...');
            $('#otp-error').hide();

            if (!otp || otp.length !== 6) {
                $('#otp-error').text('Please enter a valid 6-digit OTP.').show();
                btn.prop('disabled', false).text('Verify and Submit');
                return;
            }

            $.ajax({
                url: '{{ route("services.verify-otp") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: '{{ optional($serviceToken)->customer_id }}',
                    otp: otp
                },
                success: function(response) {
                    if (response.success) {
                        $('#otpVerificationModal').modal('hide');
                        $("#is_sms_verified").val("1");
                        $('#serviceMyTaskForm').submit(); // Submit the main form
                    } else {
                        $('#otp-error').text(response.message || 'Invalid OTP. Please try again.').show();
                        $('#status').val('pending');
                        $('#otpVerificationModal').modal('hide');
                        $("#is_sms_verified").val("0");
                        setTimeout(() => {
                            $('#serviceMyTaskForm').submit(); // Submit the main form
                        }, 1000);
                        btn.prop('disabled', false).text('Verify and Submit');
                    }
                },
                error: function(xhr) {
                    $('#otp-error').text(xhr.responseJSON?.message || 'An error occurred during verification.').show();
                    btn.prop('disabled', false).text('Verify and Submit');
                }
            });
        });
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/modern-screenshot@4.6.7/dist/index.min.js"></script>

<script>
        $(document).ready(function() {


            getCustomerSettings();

            $(document).on('change', '#area_id', function() {
                var value = $(this).val();
                if (!window.shipmentsOptions) return;
                // if (value === 'address') {
                //     clearFields();
                // } else {
                //     // Removed redundant customer settings fetch
                // }
                
                const selectedOption = window.shipmentsOptions.find(option => option.area == value);
                console.log({ selectedOption, value, all: window.shipmentsOptions});
                
                if (selectedOption) {
                    $("#address").val(selectedOption.address);
                    $("#address1").val(selectedOption.address);
                    $("#contact_person_name").val(selectedOption.contact_person_name);
                    $("#contact_person_phone").val(selectedOption.phone);
                    $("#contact_person_phone1").val(selectedOption.phone);
                }
            }) 



            const customerSelect = $('#customer_id');
            const shipmentConfirmCheckbox = $('#shipmentConfirm');
            const courierConfirmCheckbox = $('#courierConfirm');
            const conditionCheckbox = $('#condition');



            const shipmentFields = [
                $('#area_id'),
                $('#address'),
                $('#contact_person_name'),
                $('#contact_person_phone')
            ];

            const courierFields = [
                $('#courier_id'),
                $('#condition')
            ];

            const conditionFields = [
                $('#additional_amount'),
                $('#condition_remarks')
            ];

            function toggleFields(fields, enable) {
                fields.forEach(field => {
                    if( enable ) {
                        field.removeAttr('disabled');
                        if(field.prop('tomselect')){
                            field.prop('tomselect').enable();
                        }
                    } else {
                        field.attr('disabled', true);
                        if(field.prop('tomselect')){
                            field.prop('tomselect').disable();
                        }
                    }
                });
            }

            function handleCustomerSelection() {
                const customerSelected = customerSelect.val() !== "";
                shipmentConfirmCheckbox.prop('disabled', !customerSelected);
                courierConfirmCheckbox.prop('disabled', !customerSelected);

                if (!customerSelected) {
                    shipmentConfirmCheckbox.prop('checked', false);
                    courierConfirmCheckbox.prop('checked', false);
                    conditionCheckbox.prop('checked', false);
                    toggleFields(shipmentFields, false);
                    toggleFields(courierFields, false);
                    toggleFields(conditionFields, false);
                }
            }

            function handleShipmentConfirm() {
                const isChecked = shipmentConfirmCheckbox.is(':checked');
                if (courierConfirmCheckbox.is(':checked') !== isChecked) {
                    courierConfirmCheckbox.prop('checked', isChecked);
                    handleCourierConfirm(); // Manually call to update fields
                }
                toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
            }

            function handleCourierConfirm() {
                const isChecked = courierConfirmCheckbox.is(':checked');
                if (shipmentConfirmCheckbox.is(':checked') !== isChecked) {
                    shipmentConfirmCheckbox.prop('checked', isChecked);
                    handleShipmentConfirm(); // Manually call to update fields
                }

                toggleFields(courierFields, courierConfirmCheckbox.is(':checked'));
                if (!courierConfirmCheckbox.is(':checked')) {
                    conditionCheckbox.prop('checked', false);
                    toggleFields(conditionFields, false);
                }
            }

            function handleCondition() {
                toggleFields(conditionFields, conditionCheckbox.is(':checked'));
            }

            customerSelect.on('change', handleCustomerSelection);
            shipmentConfirmCheckbox.on('change', handleShipmentConfirm);
            courierConfirmCheckbox.on('change', handleCourierConfirm);
            conditionCheckbox.on('change', handleCondition);

            // Initial state
            handleCustomerSelection();
            handleShipmentConfirm();
            handleCourierConfirm();
            handleCondition();
            
        });

    function getCustomerSettings() {
        var id = $("#customer_id").val();
        if (id) {
            $.ajax({
                url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                success: function(data) {
                    console.log(data);

                    if (data && data.customers && data.customers.customer) {
                        const customerData = data.customers;

                        var area = data.customers.customer.area;
                        var area_id = area ? area.id : "address";
                        var area_name = area ? area.area : "New Address";

                        const credit_limit = customerData.credit_limit;
                        // console.log({customerData, credit_limit});
                        $("#credit_limit").val(credit_limit);

                        window.shipmentsOptions = [
                            {
                                area: area_id,
                                area_name: area_name,
                                address: area_name,
                                phone: data.customers.customer.phone,
                                contact_person_name: data.customers.customer.company_name
                            },
                        
                        ];

                        if (area_id !== 'address') {
                            window.shipmentsOptions = [...window.shipmentsOptions,
                                {
                                    area: "address",
                                    area_name: "New Address",
                                    address: "",
                                    phone: "",
                                    contact_person_name: "",
                                },
                            ];
                        }

                        if(data.customers.customer && data.customers.customer.customer_shipping_address && data.customers.customer.customer_shipping_address.length > 0){
                            window.shipmentsOptions = [...window.shipmentsOptions,
                                ...data.customers.customer.customer_shipping_address.map(address => ({
                                    area: address.id,
                                    area_name: "(Shiping Address) "+address.ship_to,
                                    address: address.shipping_address,
                                    phone: address.shipping_phone,
                                    contact_person_name: address.ship_to
                                }))
                            ];
                        }


                        const areaVal =  "{{ $serviceMyTask?->shipment->area_id ?? 'address' }}";

                        // Update the area_id select element with the new options
                        $("#area_id")[0].tomselect.clearOptions();
                        $("#area_id")[0].tomselect.addOptions(window.shipmentsOptions.map (option => ({
                            value: option.area,
                            text: option.area_name
                        })));
                        $("#area_id")[0].tomselect.setValue(areaVal);

                        // Update the fields if the area is not "New Address"
                    if(areaVal == 'address'){
                            $("#address").val("{{ $serviceMyTask?->shipment->address??'' }}");
                            $("#address1").val("{{ $serviceMyTask?->shipment->address??'' }}");
                            $("#contact_person_name").val("{{ $serviceMyTask?->shipment->contact_person_name??'' }}");
                            $("#contact_person_phone").val("{{ $serviceMyTask?->shipment->contact_person_number??'' }}");
                            $("#contact_person_phone1").val("{{ $serviceMyTask?->shipment->contact_person_number??'' }}");
                        } else if (area_id != 'address') {
                            const selectedOption = window.shipmentsOptions.find(option => option.area === area_name);
                            if (selectedOption) {
                                $("#address").val(selectedOption.address);
                                $("#address1").val(selectedOption.address);
                                $("#contact_person_name").val(selectedOption.contact_person_name);
                                $("#contact_person_phone").val(selectedOption.phone);
                                $("#contact_person_phone1").val(selectedOption.phone);
                            }
                        } else  {
                            clearFields();
                        }

                        if (data.customers.vat_status == 1) {
                            $('#vat_percentage').val(.05);
                        } else {
                            $('#vat_percentage').val(0);
                        }
                        $(".condition_div").hide();


                        // if(data.customers.is_condition_bill){
                        //     //show the condition checkbox && codition remarks
                        //     $(".condition_div").show();
                        //     $('#condition').data('condition', data.customers.minimum_condition_bill); // full 2 , half 1
                        // }else{
                        //     //hide the condition checkbox && codition remarks
                        // }
                    }
                }
            });
        }
    }
    </script>
    <script>
            window.pendingCall = [];

        function checkExistingOtpVerifications() {
            const existingVerification = $('input[name="otp_verifications[]"]');
            for (let i = 0; i < existingVerification.length; i++) {
                const verificationData = JSON.parse(existingVerification[i].value);
                if(verificationData.status == "pending"){
                    if(verificationData.title === 'Discount Changed'){
                        $('.unit_discount_input').trigger('change');
                    }
                }

            }
        }

        async function getOtpAdditionalData() {
            const tableElement = document.getElementById('product_info_table');
            const image = await modernScreenshot.domToPng(tableElement, { quality: 0.95 });
            const credit=$('#otpTableBody').find("#credit").val();
            const payment_mode=$('#otpTableBody').find("#payment_mode").val();
            const payment_date=$('#otpTableBody').find("#payment_date").val();

            const data = {
                image: [image],
                credit:credit,
                payment_mode:payment_mode,
                payment_date:payment_date,
                customer_name: $('#customer_name').val(),
            };
            console.log({data});

            return data;
        }

        // Function to check if OTP verification exists for a specific title
        function checkOtpVerificationExists(title) {
            const existingVerification = $('input[name="otp_verifications[]"]').filter(function() {
                const existingData = JSON.parse($(this).val());
                return existingData.title === title;
            });
            return existingVerification.length > 0;
        }

        // Shipment Information JavaScript
        $(document).ready(function () {

            //discount change detection
            $(document).on('change', '.unit_discount_input,#quantity', function () {
                const input = $("#discount");
                const unit_discount = $(this).closest('tr').find('.unit_discount_input').first();
                const isQuantityChange = $(this).attr('id') === 'quantity';

                console.log("unit discount class", unit_discount);

                if (unit_discount.hasClass('discount_range')) {
                    console.log("discount range");

                    const discount_range = unit_discount.data('discount_range');
                    const product_id = unit_discount.closest('tr').find('select.product-select option:selected').text();
                    const otp_title = " Discount Range Exceeded for "+product_id;

                    // If it's a quantity change and no existing OTP verification for this title, return early
                    if (isQuantityChange && !checkOtpVerificationExists(otp_title)) {
                        return;
                    }

                    if (Number(unit_discount.val()) < Number(discount_range.min) || Number(unit_discount.val()) > Number(discount_range.max)) {
                        unit_discount.addClass('is-invalid');
                        unit_discount.closest('td').attr("title", 'OPT required');

                        const data = {
                            title: otp_title,
                            request_value: unit_discount.val(),
                            details_data: {
                                product_id: product_id,
                                quantity: unit_discount.closest('tr').find('#quantity').val(),
                                price: unit_discount.closest('tr').find('#price').val(),
                                min_discount: discount_range.min,
                                max_discount: discount_range.max,
                                ...discount_range
                            }
                        };
                        // captureProductInfoTable();
                        updateOtpVerification(data);

                    } else {
                        deleteOtpVerification(otp_title);
                        // deleteOtpVerification('Discount Changed');
                        unit_discount.removeClass('is-invalid');
                        // unit_discount.removeClass('opt-required');
                        unit_discount.closest('td').attr("title", '');
                    }
                    return;
                }else{
                    const product_id = unit_discount.closest('tr').find('select.product-select option:selected').text();
                    const otp_title = "Discount Changed for "+product_id;

                    // If it's a quantity change and no existing OTP verification for this title, return early
                    if (isQuantityChange && !checkOtpVerificationExists(otp_title)) {
                        return;
                    }

                    if (unit_discount.val() != 0) {
                        // console.log("discount changed", input.val());
                    unit_discount.addClass('opt-required');
                    unit_discount.closest('td').attr("title", 'OPT required');
                        const data = {
                            title: otp_title,
                            request_value: unit_discount.val(),
                            details_data: {
                                product_id: product_id,
                                quantity: unit_discount.closest('tr').find('.quantity').val(),
                                price: unit_discount.closest('tr').find('.price').val(),
                            }
                        };
                        // captureProductInfoTable();
                        updateOtpVerification(data);
                    } else {
                        deleteOtpVerification(otp_title);
                    unit_discount.removeClass('opt-required');
                    unit_discount.closest('td').attr("title", '');
                    }
                }


            });


            $('input[name="payments_due_amount"]').on('change', async function () {
                const dueAmount = $(this).val();
                console.log("changed due amount: ", dueAmount);
                console.log("changed: ", Number($('#credit_limit').val()));

                if (Number(dueAmount) > Number($('#credit_limit').val())) {

                    window.pendingCall.push(async function creditLimit() {
                        const productsTableElement = document.getElementById('serviceBillDiv');
                        const imageProducts = await modernScreenshot.domToPng(productsTableElement, { quality: 0.95 });
                        const prementsTableElement = document.getElementById('payment-table');
                        const imagePayments = await modernScreenshot.domToPng(prementsTableElement, { quality: 0.95 });
                        const data = {
                            title: 'Credit Limit Exceeded',
                            request_value: dueAmount,
                            details_data:{
                                credit_limit: dueAmount,
                                due_amount: dueAmount,
                                customer_info:{
                                    customer_id: $('#customer_id').val(),
                                    customer_name: $('#customer_name').val(),
                                    current_balance: dueAmount,
                                    credit_limit: $('#credit_limit').val(),
                                    ad_limit: $('#net_amount').val(),
                                    images: [imageProducts, imagePayments]
                                }
                            }
                        };
                        // captureProductInfoTable();
                    await updateOtpVerification(data);
                    });
                }else{
                    window.pendingCall.push(async function creditLimit() {
                        await deleteOtpVerification('Credit Limit Exceeded');
                        console.log("Credit limit is not exceeded");
                    });
                }
                // dueAmountChanged();
            });

            checkExistingOtpVerifications();
        });
    </script>

    @stack('script')
@endsection