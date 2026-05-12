@section('title', 'Broker Create')
@section('description', 'Broker Create')
@extends('layout.app')
@section('content')

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

        .ts-control {
            height: 48px !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('menu.broker-create-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if(hasPermission('crm.brokers.index'))
                        <a href="{{ route('crm.brokers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h5 class="text-capitalize">{{ trans('broker create') }}</h5>

                </div>
                <x-error-alart />

            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('crm.brokers.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf



                            <div class="dm-tab tab-horizontal">
                                <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-v-6-tab" data-bs-toggle="tab" href="#tab-v-6"
                                            role="tab" aria-selected="true">Basic Information
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-4-tab" data-bs-toggle="tab" href="#tab-v-4"
                                            role="tab" aria-selected="false">Broker Location Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                            role="tab" aria-selected="false">Commission Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3"
                                            role="tab" aria-selected="false">Customer Attached Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1"
                                            role="tab" aria-selected="false">Bank Information</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-v-6" role="tabpanel"
                                        aria-labelledby="tab-v-6-tab">
                                        <br>
                                        <div class="row">

                                            <div class="form-group col-md-4 mb-25">
                                                <label for="broker_id"
                                                    class="color-dark fs-14 fw-500 align-center">Broker ID</label>
                                                <input type="text"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="broker_id" value="{{ old('broker_id') }}" id="broker_id"
                                                    placeholder="Broker ID">
                                                @if ($errors->has('broker_id'))
                                                    <p class="text-danger">{{ $errors->first('broker_id') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4 form-group mb-25">
                                                <label for="broker_name" class="color-dark fs-14 fw-500 align-center">Broker
                                                    Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="broker_name"
                                                    id="broker_name" value="{{ old('broker_name') }}"
                                                    placeholder="Broker Name" required>
                                                @if ($errors->has('broker_name'))
                                                    <p class="text-danger">{{ $errors->first('broker_name') }}</p>
                                                @endif
                                            </div>

                                            <div class="col-md-4 form-group mb-25">
                                                <label for="mobile"
                                                    class="color-dark fs-14 fw-500 align-center">Mobile<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="mobile" id="mobile"
                                                    value="{{ old('mobile') }}" placeholder="Mobile" required>
                                                @if ($errors->has('mobile'))
                                                    <p class="text-danger">{{ $errors->first('mobile') }}</p>
                                                @endif
                                            </div>

                                            <div class="col-md-4 form-group mb-25">
                                                <label for="email"
                                                    class="color-dark fs-14 fw-500 align-center">Email</label>
                                                <input type="text" name="email" class="form-control" id="email"
                                                    value="{{ old('email') }}" placeholder="Email Address">
                                            </div>


                                            <div class="col-md-4 form-group mb-25">
                                                <label for="dob" class="color-dark fs-14 fw-500 align-center">Date of
                                                    Birth <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-default flatdate"
                                                    value="{{ old('dob') }}" name="dob" id="dob"
                                                    placeholder="Date of Birth" required>
                                                @if ($errors->has('dob'))
                                                    <p class="text-danger">{{ $errors->first('dob') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4 form-group mb-25">
                                                <label for="gender"
                                                    class="color-dark fs-14 fw-500 align-center">Gender</label>
                                                <select class="form-control" name="gender" id="gender" type="select">
                                                    <option value="male" @if(old('gender') == 'male') selected @endif @if(old('gender') == null) selected @endif>Male</option>
                                                    <option value="female" @if(old('gender') == 'female') selected @endif>Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <p class="text-danger">{{ $errors->first('gender') }}</p>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-4 form-group mb-25">
                                                <label for="nid"
                                                    class="color-dark fs-14 fw-500 align-center">NID<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nid" id="nid"
                                                    value="{{ old('nid') }}" placeholder="NID">
                                                @if ($errors->has('nid'))
                                                    <p class="text-danger">{{ $errors->first('nid') }}</p>
                                                @endif
                                            </div>

                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group mb-25">
                                                <label class="fs-15 ms-20 fw-500 text-capitalize">NID( Front Image)</label>
                                                <x-file-uploader  name="front_image"/>

                                                {{-- <input id="front-image" type="file" accept="image/*"
                                                    name="front_image" class="file-control form-control"
                                                    data-preview-element="front-image-preview"> --}}
                                            </div>
                                            <div class="col-md-4 form-group mb-25">
                                                <label class="fs-15 ms-20 fw-500 text-capitalize">NID (Back Image)</label>
                                                <x-file-uploader  name="back_image"/>
                                                {{-- <input id="back-image" type="file" accept="image/*" name="back_image"
                                                    class="file-control form-control"
                                                    data-preview-element="back-image-preview"> --}}
                                            </div>

                                            <div class="col-md-4 form-group mb-25">
                                                <div class="form-group">
                                                    <label class="fs-15 ms-20 fw-500 text-capitalize">Photograph</label>
                                                    <x-file-uploader  name="photograph"/>
                                                    {{-- <input id="photograph" type="file" accept="image/*"
                                                        name="photograph" class="file-control form-control"
                                                        data-preview-element="photograph-preview"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="tab-pane fade" id="tab-v-1" role="tabpanel" aria-labelledby="tab-v-1-tab">
                                    <br>
                                    <div class="row">

                                        <table class="table table-bordered bank-info-table">
                                            <thead>
                                                <th style="width: 15%">Type</th>
                                                <th>Bank Name</th>
                                                <th>Branch Name</th>
                                                <th>Account/Phone Number</th>
                                                <th>E-TIN No</th>
                                                <th>Routing Number</th>
                                                <th>
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-success btn-xs add-row" onclick="addRow()"
                                                            type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody>
                                                @if (count(old('bank_name', [])) > 0)
                                                    @foreach (old('bank_name') as $key => $bank)
                                                        <tr>
                                                            <td>
                                                                <select name="bank_type[]" id="type"
                                                                    class="form-control">
                                                                    <option value="1">Bank</option>
                                                                    <option value="2">Bkash</option>
                                                                    <option value="3">Nagad</option>
                                                                    <option value="4">Rocket</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="bank_name[]" value="{{ $bank }}"
                                                                    placeholder="Bank Name">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="branch_name[]"
                                                                    value="{{ old('branch_name')[$key] }}"
                                                                    placeholder="Branch Name">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="account_nos[]"
                                                                    value="{{ old('account_nos')[$key] }}"
                                                                    placeholder="A/C Number">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="e_tin_no[]" value="{{ old('e_tin_no')[$key] }}"
                                                                    placeholder="E-TIN No">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="routing_name[]"
                                                                    value="{{ old('routing_name')[$key] }}"
                                                                    placeholder="Routing Number">
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-corner">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        onclick="deleteRow(this)" type="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            <select name="bank_type[]" id="type"
                                                                class="form-control">
                                                                <option value="">Select Type</option>
                                                                <option value="1">Bank</option>
                                                                <option value="2">Bkash</option>
                                                                <option value="3">Nagad</option>
                                                                <option value="4">Rocket</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm"
                                                                name="bank_name[]" value=""
                                                                placeholder="Bank Name">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm"
                                                                name="branch_name[]" value=""
                                                                placeholder="Branch Name">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="account_nos[]" value=""
                                                                placeholder="A/C Number">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="e_tin_no[]"
                                                                value="" placeholder="E-TIN No">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="routing_name[]" value=""
                                                                placeholder="Routing Number">
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-corner">
                                                                <button class="btn btn-danger btn-xs"
                                                                    onclick="deleteRow(this)" type="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-v-2" role="tabpanel" aria-labelledby="tab-v-2-tab">
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="row col-sm-6" style="border-right:1px solid"> 
                                                    <div class="col-sm-5" style="text-align: right">
                                                        <label class="col-sm-12 control-label"> Commission Type </label>
                                                    </div>
                                                    <div class="col-sm-7">

                                                        <div class="col-sm-8 col-sm-8 @error('commission_type') has-error @enderror">

                                                            <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="0" id="commission_n_a">
                                                            <label class="form-check-label" for="commission_n_a">
                                                                N/A
                                                            </label>

                                                            <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="1" id="commission_percentage">
                                                            <label class="form-check-label" for="commission_percentage">
                                                               Percentage
                                                            </label>
                                                            @error('commission_type')
                                                                <span class="text-danger">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row col-sm-6"  > 
                                                    <div class="col-sm-8 col-sm-8 @error('commission_type') has-error @enderror">  
                                                        <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="2" id="commission_fixed">
                                                        <label class="form-check-label" for="commission_fixed">
                                                            Fixed
                                                        </label> 
                                                        @error('commission_fixed')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-sm-6" style="border-right:1px solid">
                                            <div class="form-group" id="percentage" style="">
                                                <table class="table table-bordered percentage-table">
                                                    <thead>
                                                        <th>Percentage Type</th>
                                                        <th>Percentage %</th>
                                                        <th>
                                                            <div class="btn-group btn-corner">
                                                                <button class="btn btn-success btn-xs add-row"
                                                                    onclick="addPercentageRow()" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        @if (old('percentage'))
                                                            @foreach (old('percentage') as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        <select name="percentage_type[]" onchange="getPercentage(this)"
                                                                            class="form-control">
                                                                            <option value="">Select Type</option>
                                                                            @foreach ($percentageTypes as $percentageType)
                                                                                <option
                                                                                    value="{{ $percentageType->id }}" {{ old('percentage_type.'.$key) == $percentageType->id ? 'selected' : ''}}>
                                                                                    {{ $percentageType->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control input-sm"
                                                                            name="percentage[]" value="{{ $value }}"
                                                                            placeholder="percentage">
                                                                    </td>   

                                                                    <td>
                                                                        <div class="btn-group btn-corner">
                                                                            <button class="btn btn-danger btn-xs"
                                                                                onclick="deletePercentageRow(this)"
                                                                                type="button">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select name="percentage_type[]" class="form-control" onchange="getPercentage(this)">
                                                                        <option value="">Select Type</option>
                                                                        @foreach ($percentageTypes as $percentageType)
                                                                            <option value="{{ $percentageType->id }}">
                                                                                {{ $percentageType->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control input-sm"
                                                                        name="percentage[]" value=""
                                                                        placeholder="percentage">
                                                                </td>
        
                                                                <td>
                                                                    <div class="btn-group btn-corner">
                                                                        <button class="btn btn-danger btn-xs"
                                                                            onclick="deletePercentageRow(this)" type="button">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div>
                                        <div class="row col-sm-6">
                                            <div class="form-group" id="fixed" style="">
                                                <table class="table table-bordered fixed-table">
                                                    <thead>
                                                        <th>Product Name</th>
                                                        <th>Amount</th>
                                                        <th>
                                                            <div class="btn-group btn-corner">
                                                                <button class="btn btn-success btn-xs add-row"
                                                                    onclick="addFixedRow()" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </th>
                                                    </thead>
                                                    <tbody> 
                                                        <tr>
                                                            <td>
                                                                <select name="fixed_type[]" class="form-control " onchange="getFixed(this)">
                                                                    <option value="">Select Type</option> 
                                                                    <option value="1">Invoice Wise</option>
                                                                    <option value="2">Monthly</option>
                                                                    <option value="3">Yearly</option>
                                                                    <option value="4">Festival-Eid</option>
                                                                    <option value="5">Festival-Durga Puja</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="fixed_amount[]" value=""
                                                                    placeholder="Amount">
                                                            </td>

                                                            <td>
                                                                <div class="btn-group btn-corner">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        onclick="deleteFixedRow(this)"
                                                                        type="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <select name="fixed_type[]" class="form-control input-sm product_ids" onchange="getFixed(this)">
                                                                    <option value="">Select Product</option> 
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="fixed_amount[]" value=""
                                                                    placeholder="Amount">
                                                            </td>

                                                            <td>
                                                                <div class="btn-group btn-corner">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        onclick="deleteFixedRow(this)"
                                                                        type="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                    </tbody>
                                                </table>  
                                            </div>
                                        </div> 
                                        
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-v-3" role="tabpanel" aria-labelledby="tab-v-3-tab">
                                      <div class="row col-sm-8 offset-2">
                                        <table class="table table-bordered customer-attached-table">
                                            <thead>
                                                <th  width="70%">Customer</th>
                                                <th  width="20%">Status</th>
                                                <th  width="10%">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-success btn-xs add-row"
                                                            onclick="addCustomerAttachedRow()" type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody>
                                                @if (old('customer_id'))
                                                    @foreach (old('customer_id') as $key => $value)
                                                        <tr class="customer-attached-item">
                                                            <td  width="70%">
                                                                <select name="customer_id[]"
                                                                    class="form-control input-sm customer_id ">
                                                                    <option value="">Select Customer</option>
                                                                    
                                                                </select>
                                                            </td>
                                                            <td  width="20%">
                                                                <select name="status[]"
                                                                    class="form-control ">
                                                                    <option value="">Select Status</option>
                                                                    <option value="1" {{ old('status')[$key] == 1 ? 'selected' : '' }}>Active</option>
                                                                    <option value="2" {{ old('status')[$key] == 2 ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </td>

                                                            <td  width="10%">
                                                                <div class="btn-group btn-corner">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        onclick="deleteCustomerAttachedRow(this)"
                                                                        type="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td  width="70%">
                                                        <select name="customer_id[]" class="form-control customer_id">
                                                            <option value="">Select Customer</option>
                                                           

                                                        </select>
                                                    </td>
                                                    <td  width="20%">
                                                        <select name="status[]" class="form-control ">
                                                            <option value="">Select Status</option>
                                                            <option value="1" selected>Active</option>
                                                            <option value="2">Inactive</option>
                                                        </select>
                                                    </td>

                                                    <td  width="10%">
                                                        <div class="btn-group btn-corner">
                                                            <button class="btn btn-danger btn-xs"
                                                                onclick="deleteCustomerAttachedRow(this)" type="button">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                    
                                                @endif
                                              
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-v-4" role="tabpanel" aria-labelledby="tab-v-4-tab">
                                    <br>
                                    @php
                                        $prefix = $prefix ?? '';
                                        $division = old("division_id") ?? '';
                                        $district = old("district_id") ?? '';
                                        $thana = old("thana_id") ?? '';
                                    @endphp
                                    <div class="row">
                                        
                                            <div class="col-md-4">
                                                <label for="division">Division<span class="text-danger">*</span></label>
                                                <select class="form-control geo-select" data-type="division"
                                                    data-defualt="{{ old($prefix . 'division', $division) }}"
                                                    @if ($division && ($divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first())) data-default_name="{{ $divisionOption->name }}" @endif
                                                    id="division" name={{ $prefix . 'division_id' }}>
                                                    <option value="">Select a Division</option>
                                                </select>
                                                @if ($errors->has('division_id'))
                                                    <p class="text-danger">{{ $errors->first('division_id') }}</p>
                                                @endif
                                            </div>

                                            <div class="col-md-4">
                                                <label for="district">District<span class="text-danger">*</span></label>
                                                <select class="form-control geo-select" data-type="district"
                                                    data-defualt="{{ old($prefix . 'district', $district) }}"
                                                    @if ($district && ($districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first())) data-default_name="{{ $districtOption->name }}" @endif
                                                    data-parant="#division" name="{{ $prefix . 'district_id' }}"
                                                    id="district">
                                                    <option value="">Select a District</option>
                                                </select>
                                                @if ($errors->has('district_id'))
                                                    <p class="text-danger">{{ $errors->first('district_id') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="thana">Thana<span class="text-danger">*</span></label>
                                                <select class="form-control geo-select" data-type="thana"
                                                    data-defualt="{{ old($prefix . 'thana', $thana) }}"
                                                    @if ($thana && ($thanaOption = App\Models\GeoLocation::where('id', $thana)->select('id', 'name')->first())) data-default_name="{{ $thanaOption->name }}" @endif
                                                    data-parant="#district" name="{{ $prefix . 'thana_id' }}"
                                                    id="thana">
                                                    <option value="">Select a Thana</option>
                                                </select>
                                                @if ($errors->has('thana_id'))
                                                    <p class="text-danger">{{ $errors->first('thana_id') }}</p>
                                                @endif
                                            </div>

                                            <div class="col-md-4 mt-25">
                                                <label for="present_address"
                                                    class="color-dark fs-14 fw-500 align-center">Present
                                                    Address</label>
                                                <textarea class="form-control" name="present_address" style="height: 100px;" id="present_address"
                                                    placeholder="Present Address">{{ old('present_address') }}</textarea>
                                            </div>
                                            <div class="col-md-4 mt-25">
                                                <label for="permanent_address"
                                                    class="color-dark fs-14 fw-500 align-center">Permanent
                                                    Address</label>

                                                <textarea name="permanent_address" class="form-control" id="permanent_address" style="height: 100px;">{{ old('permanent_address') }}</textarea>

                                            </div>
                                    </div>
                                </div>


                            </div>
                    </div>
                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                        <button type="submit"
                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('page_scripts')
    @include('utils.geo_locations.script')

    <script>
        $(document).ready(function() {
            
        });
    </script>


    </script>
    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>

    <script>
        function addRow() {

            var table = $(".bank-info-table tbody tr:last")
            table.clone().find('input').val('').end().insertAfter(table);
        }

        function deleteRow(object) {

            var table = $(".bank-info-table tbody tr")

            if (table.length > 1) {
                $(object).closest('tr').remove()
            }
        }
    </script>
    <script>
        var selectedPercentageIds = []; // Array to store selected percentage type IDs
        var selectedFixedIds = [];

      // Initialize selected IDs for existing rows
      $(document).ready(function () {

            $(".commission-checkbox").on("click", function(){

                if($(this).val() == "0" && $(this).is(":checked")){
                    // hide sections
                    $("#commission_percentage, #commission_fixed").prop("checked", false);
                    $(".percentage-table, .fixed-table").hide();
                } else { 
                    // show sections
                    $("#commission_percentage, #commission_fixed").prop("checked", true); 
                    $(".percentage-table, .fixed-table").show();
                }

            });
 
            $(".percentage-table tbody tr").each(function () {
                var selectedType = $(this).find("select").val();
                if (selectedType) {
                    selectedPercentageIds.push(selectedType);
                }
            });
            $(".fixed-table tbody tr").each(function () {
                var selectedType = $(this).find("select").val();
                if (selectedType) {
                    selectedFixedIds.push(selectedType);
                }
            });
            
      });
  
      function getPercentage(selectElement) {
          var percentageId = selectElement.value;
  
          if (percentageId === "") {
              return; // Do nothing if no option is selected
          }
  
          if (selectedPercentageIds.includes(percentageId)) {
              showToast("warning", "You have already selected this Percentage Type.");
              selectElement.value = ""; // Reset selection
              return;
          }
  
          // Add the selected type to the list
          selectedPercentageIds.push(percentageId);
      }
  
      function addPercentageRow() {
          var table = $(".percentage-table tbody tr:last");
          var newRow = table.clone();
  
          // Reset inputs and selects
          newRow.find("input").val("");
          newRow.find("select").val("");
  
          // Append the new row after the last row
          newRow.insertAfter(table);
      }
  
      function deletePercentageRow(object) {
          var row = $(object).closest("tr");
          var selectElement = row.find("select")[0];
  
          if (selectElement && selectElement.value !== "") {
              var index = selectedPercentageIds.indexOf(selectElement.value);
              if (index !== -1) {
                  selectedPercentageIds.splice(index, 1); // Remove from selected list
              }
          }
  
          var table = $(".percentage-table tbody tr");
  
          if (table.length > 1) {
              row.remove();
          } else {
              row.find("input").val("");
              row.find("select").val("");
          }
      }
  
      function showToast(type, message) {
          if (type === "warning") {
              toastr.warning(message);
          } else if (type === "error") {
              toastr.error(message);
          }
      }
  
        function addFixedRow() {
            var table = $(".fixed-table tbody tr:last");
            var newRow = table.clone();

            // Reset inputs
            newRow.find("input").val("").prop("disabled", false);

            // Reset selects
            newRow.find("select").val("");

            // Enable buttons
            newRow.find("button").prop("disabled", false);

            // Remove old TomSelect wrapper
            newRow.find(".ts-wrapper").remove();
            newRow.find("select").removeClass("tomselected ts-hidden-accessible");

            // Append row
            newRow.insertAfter(table);

            // Reinitialize TomSelect
            prouctAutocompleteLoad(newRow);
        }

        function deleteFixedRow(object) {
            var row = $(object).closest("tr"); 

            var selectElement = row.find("select")[0];

            if (selectElement && selectElement.value !== "") {
                var index = selectedFixedIds.indexOf(selectElement.value);
                if (index !== -1) {
                    selectedFixedIds.splice(index, 1); // Remove from selected list
                }
            }

            var table = $(".fixed-table tbody tr");

            if (table.length > 1) {
                row.remove();
            } else {  
                row.find("input").val("");

                if(selectElement.tomselect){
                    selectElement.tomselect.clear();
                }else{
                    row.find("select").val("");
                }
                
            } 

            // Optional: refresh other selects
            refreshFixedOptions();



        }

        function refreshFixedOptions(){
            $(".fixed-table select").each(function(){
                if(this.tomselect){
                    this.tomselect.refreshOptions(false);
                }
            });
        }
        function getFixed(selectElement) {
            var percentageId = selectElement.value;

            if (percentageId === "") {
                return; // Do nothing if no option is selected
            }

            if (selectedPercentageIds.includes(percentageId)) {
                showToast("warning", "You have already selected this Product or Fixed Type.");
                selectElement.value = ""; // Reset selection
                return;
            }

            // Add the selected type to the list
            selectedPercentageIds.push(percentageId);
        }

        
      </script>
    <script>
      // Global variables for the original row template and tracking customer selections.
      var originalCustomerAttachedRow = null;
      var selectedCustomerIds = []; // Array to store selected customer IDs
  
      // Function to display toast messages
      function showToast(type, message) {
          if (type === "warning") {
              toastr.warning(message);
          } else if (type === "error") {
              toastr.error(message);
          }
      }
  
      // Handler for change events on customer select elements.
      // It uses a data attribute to track the previous value and prevents duplicate selections.
      function onCustomerChange() {
          var selectElement = this;
          var newVal = selectElement.value;
          var oldVal = $(selectElement).attr("data-old-value") || "";
  
          // If selection is cleared, remove the previous value from the global array.
          if (newVal === "") {
              if (oldVal !== "") {
                  var index = selectedCustomerIds.indexOf(oldVal);
                  if (index > -1) {
                      selectedCustomerIds.splice(index, 1);
                  }
              }
              $(selectElement).attr("data-old-value", "");
              return;
          }
  
          // If the new value is the same as the old, do nothing.
          if (newVal === oldVal) {
              return;
          }
  
          // If the customer is already attached elsewhere, warn and clear the selection.
          if (selectedCustomerIds.includes(newVal)) {
              showToast("warning", "This customer is already attached.");
              if (selectElement.tomselect) {
                  selectElement.tomselect.clear();
              } else {
                  $(selectElement).val("");
              }
              return;
          }
  
          // Remove the old value from the array if present.
          if (oldVal !== "") {
              var index = selectedCustomerIds.indexOf(oldVal);
              if (index > -1) {
                  selectedCustomerIds.splice(index, 1);
              }
          }
  
          // Add the new value to the global array and update the data attribute.
          selectedCustomerIds.push(newVal);
          $(selectElement).attr("data-old-value", newVal);
      }
  
      $(document).ready(function() {
          // Clone the first row as the template for new rows.
          originalCustomerAttachedRow = $(".customer-attached-table tbody tr").first().clone();
  
          // Initialize TomSelect and set up the change event for existing customer select elements.
          $(".customer-attached-table tbody tr .to-select").each(function() {
              var ts = new TomSelect(this, { autoclose: true });
              var currentVal = this.value;
              if (currentVal) {
                  $(this).attr("data-old-value", currentVal);
                  if (!selectedCustomerIds.includes(currentVal)) {
                      selectedCustomerIds.push(currentVal);
                  }
              }
          });
  
          // Delegate the change event so that it also applies to newly added rows.
          $(document).on('change', '.customer-attached-table .to-select', onCustomerChange);
  
          // Initialize date pickers if necessary.
          $('.customer-attached-table tbody tr .datePicker').each(function() {
              $(this).datepicker({
                  format: 'yyyy-mm-dd',
                  autoclose: true
              });
          });


        const productSelect = new TomSelect(".product_ids", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('crm.autocomplete.products') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        productSelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        @if(request('product_ids'))
            productSelect.addOption({
                id: "{{ request('product_ids') }}",
                text: "{{ request('product_ids') }}"
            });
            productSelect.setValue("{{ request('product_ids') }}");
        @endif


        const customerSelect = new TomSelect(".customer_id", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('crm.autocomplete.customers') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        customerSelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 


      });

      
    
        function customerAutocompleteLoad(row)
        {
            const p = $(row).find(".customer_id");
            const customerSelect = new TomSelect(p[0], {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('crm.autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            customerSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
    
        }
        
        function prouctAutocompleteLoad(row){
            const p = $(row).find(".product_ids");
            const productSelect = new TomSelect(p[0], {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('product_ids'))
                productSelect.addOption({
                    id: "{{ request('product_ids') }}",
                    text: "{{ request('product_ids') }}"
                });
                productSelect.setValue("{{ request('product_ids') }}");
            @endif
        }



  
        // Adds a new row with a cleared Customer select (so it shows no preselected data).
        function addCustomerAttachedRow() {
            var table = $(".customer-attached-table tbody tr:last");
            var newRow = table.clone();

            // Reset inputs
            newRow.find("input").val("").prop("disabled", false);

            // Reset selects
            newRow.find("select").val("");

            // Enable buttons
            newRow.find("button").prop("disabled", false);

            // Remove old TomSelect wrapper
            newRow.find(".ts-wrapper").remove();
            newRow.find("select").removeClass("tomselected ts-hidden-accessible");

            // Append row
            newRow.insertAfter(table);

            // Reinitialize TomSelect
            customerAutocompleteLoad(newRow);
        } 
        
      // Deletes a customer row and removes its selected customer from the global tracking array.
      function deleteCustomerAttachedRow(object) {
          var row = $(object).closest('tr');
          var selectElement = row.find("select.to-select")[0];
          if (selectElement && selectElement.value !== "") {
              var selectedId = selectElement.value;
              var index = selectedCustomerIds.indexOf(selectedId);
              if (index !== -1) {
                  selectedCustomerIds.splice(index, 1);
              }
          }
          var table = $(".customer-attached-table tbody tr");
          if (table.length > 1) {
              row.remove();
          } else {
              row.find("input").val('');
              row.find("select").each(function() {
                  if (this.tomselect) {
                      this.tomselect.clear();
                  }
              });
          }
      }
  </script>
    
@endSection
