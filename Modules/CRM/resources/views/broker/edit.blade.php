@section('title', 'Broker Edit')
@section('description', 'Broker Edit')
@extends('layout.app')
@section('content')

    <style>
        .card-body {
            margin-right: 8vh;
            margin-left: 8vh;
        }

        .row {
            padding-right: 2vh;
            padding-left: 2vh;
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
                                    {{ trans('menu.update-broker-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 row justify-content-between gap-0">
                        <div class="col-auto">
                            @if (hasPermission('crm.brokers.index'))
                            <a href="{{ route('crm.brokers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>

                        <div class="col-auto">
                            @if (hasPermission('crm.brokers.create'))
                            <a href="{{ route('crm.brokers.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h5 class="text-capitalize">{{ trans('broker update') }}</h5>
                    <x-error-alart />
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('crm.brokers.update', $broker->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                     
                            <div class="dm-tab tab-horizontal">
                                <h4> <div class="col-sm-14">
                                    <label class="col-sm-12 control-label">Broker Name :
                                        {{ $broker->broker_name  }}
                                       </label>

                                </div></h4>
                                <br>
                                <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-v-6-tab" data-bs-toggle="tab" href="#tab-v-6"
                                            role="tab" aria-selected="true">Basic Information</a>
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
                                                name="broker_id" value="{{ old('broker_id', $broker->broker_id) }}" id="broker_id"
                                                placeholder="Broker ID">
                                            @if ($errors->has('broker_id'))
                                                <p class="text-danger">{{ $errors->first('broker_id') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-4 form-group mb-25">
                                            <label for="broker_name" class="color-dark fs-14 fw-500 align-center">Broker
                                                Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="broker_name"
                                                id="broker_name" value="{{ old('broker_name', $broker->broker_name) }}"
                                                placeholder="Broker Name">
                                            @if ($errors->has('broker_name'))
                                                <p class="text-danger">{{ $errors->first('broker_name') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-4 form-group mb-25">
                                            <label for="mobile"
                                                class="color-dark fs-14 fw-500 align-center">Mobile<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="mobile" id="mobile"
                                                value="{{ old('mobile', $broker->mobile) }}" placeholder="Mobile">
                                            @if ($errors->has('mobile'))
                                                <p class="text-danger">{{ $errors->first('mobile') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-4 form-group mb-25">
                                            <label for="email"
                                                class="color-dark fs-14 fw-500 align-center">Email</label>
                                            <input type="text" name="email" class="form-control" id="email"
                                                value="{{ old('email', $broker->email) }}" placeholder="Email Address">
                                        </div>


                                        <div class="col-md-4 form-group mb-25">
                                            <label for="dob" class="color-dark fs-14 fw-500 align-center">Date of
                                                Birth</label>
                                            <input type="text" class="form-control form-control-default flatdate"
                                                value="{{ $broker->dob }}" name="dob" id="dob"
                                                placeholder="Date of Birth">
                                            @if ($errors->has('dob'))
                                                <p class="text-danger">{{ $errors->first('dob') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 form-group mb-25">
                                            <label for="gender"
                                                class="color-dark fs-14 fw-500 align-center">Gender</label>
                                            <select class="form-control" name="gender" id="gender" type="select">
                                                <option value="male" {{ old('gender', $broker->gender) == 'male' ? 'selected' : ''}}>Male</option>
                                                <option value="female" {{ old('gender', $broker->gender) == 'female' ? 'selected' : ''}}>Female</option>
                                                <option value="other" {{ old('gender', $broker->gender) == 'other' ? 'selected' : ''}}>Other</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <p class="text-danger">{{ $errors->first('gender') }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-4 form-group mb-25">
                                            <label for="nid"
                                                class="color-dark fs-14 fw-500 align-center">NID<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nid" id="nid"
                                                value="{{ old('nid', $broker->nid) }}" placeholder="NID">
                                            @if ($errors->has('nid'))
                                                <p class="text-danger">{{ $errors->first('nid') }}</p>
                                            @endif
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                         <div class="col-md-4 form-group mb-25">
                                            <label class="fs-15 ms-20 fw-500 text-capitalize">NID( Front Image)</label>
                                            <x-file-uploader :value="$broker->front_image" name="front_image"/>

                                            {{-- <input id="front-image" type="file" accept="image/*"
                                                name="front_image" class="file-control form-control"
                                                data-value="{{ $broker->front_image }}"> --}}
                                        </div>
                                        <div class="col-md-4 form-group mb-25">
                                            <label class="fs-15 ms-20 fw-500 text-capitalize">NID (Back Image)</label>
                                            <x-file-uploader :value="$broker->back_image" name="back_image"/>
                                            {{-- <input id="back-image" type="file" accept="image/*" name="back_image"
                                                class="file-control form-control"
                                                data-value="{{ $broker->back_image }}"> --}}
                                        </div>

                                        <div class="col-md-4 form-group mb-25">
                                            <div class="form-group">
                                                <label class="fs-15 ms-20 fw-500 text-capitalize">Photograph</label>
                                                <x-file-uploader :value="$broker->photograph" name="photograph"/>
                                                {{-- <input id="photograph" type="file" accept="image/*"
                                                    name="photograph" class="file-control form-control"
                                                    data-value="{{ $broker->photograph }}"> --}}
                                            </div>
                                        </div>
                                    </div>
                                    </div>


                                    <div class="tab-pane fade" id="tab-v-1" role="tabpanel"
                                        aria-labelledby="tab-v-1-tab">
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
                                                            <button class="btn btn-success btn-xs add-row"
                                                                onclick="addRow()" type="button">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    @if ($broker->brokerBank->count() > 0)
                                                        @foreach ($broker->brokerBank as $key => $item)
                                                            <tr>
                                                                <td>
                                                                    <select name="bank_type[]" id="type"
                                                                        class="form-control">
                                                                        <option value="">Select Type</option>
                                                                        <option value="1"
                                                                            {{ $item->bank_type == 1 ? 'selected' : '' }}>
                                                                            Bank</option>
                                                                        <option value="2"
                                                                            {{ $item->bank_type == 2 ? 'selected' : '' }}>
                                                                            Bkash</option>
                                                                        <option value="3"
                                                                            {{ $item->bank_type == 3 ? 'selected' : '' }}>
                                                                            Nagad</option>
                                                                        <option value="4"
                                                                            {{ $item->bank_type == 4 ? 'selected' : '' }}>
                                                                            Rocket</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control input-sm"
                                                                        name="bank_name[]" value="{{ $item->bank_name }}"
                                                                        placeholder="Bank Name">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control input-sm"
                                                                        name="branch_name[]"
                                                                        value="{{ $item->branch_name }}"
                                                                        placeholder="Branch Name">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="account_nos[]"
                                                                        value="{{ $item->account_nos }}"
                                                                        placeholder="A/C Number">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="e_tin_no[]" value="{{ $item->e_tin_no }}"
                                                                        placeholder="E-TIN No">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="routing_name[]"
                                                                        value="{{ $item->routing_name }}"
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
                                                                <input type="text" class="form-control"
                                                                    name="e_tin_no[]" value=""
                                                                    placeholder="E-TIN No">
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
                                                                        
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>


                                        </div>
                                    </div>

                                    
                                    <div class="tab-pane fade" id="tab-v-2" role="tabpanel"
                                        aria-labelledby="tab-v-2-tab">
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
                                                                <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="0" id="commission_n_a"
                                                                {{ old('commission_type', $broker->commission_type) == '0' ? 'checked' : '' }} >
                                                                <label class="form-check-label" for="commission_n_a">
                                                                    N/A
                                                                </label>

                                                                <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="1" id="commission_percentage"
                                                                {{ old('commission_type', $broker->commission_type) == '1' ? 'checked' : '' }} >
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
                                                    <div class="row col-sm-6">

                                                        <div class="col-sm-12"> 
                                                            <div class="col-sm-8 col-sm-8 @error('commission_type') has-error @enderror">  

                                                                <input class="form-check-input commission-checkbox" type="checkbox" name="commission_type[]" value="2" id="commission_fixed"
                                                                {{ old('commission_type', $broker->commission_type) == '1' ? 'checked' : '' }} >
                                                                <label class="form-check-label" for="commission_fixed">
                                                                    Fixed
                                                                </label> 

                                                                
                                                                @error('commission_type')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>

                                            <div class="row col-sm-6" style="border-right:1px solid">
                                                <div class="form-group" id="percentage">
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
                                                            @if ($broker->brokerCommission->count() > 0)
                                                                @foreach ($broker->brokerCommission->where('commission_type','1') as $key => $item)
                                                                    <tr>
                                                                        <td>
                                                                            <select name="percentage_type[]"
                                                                                class="form-control" onchange="getPercentage(this)" >
                                                                                <option value="">Select Type</option>
                                                                                @foreach ($percentageTypes as $percentageType)
                                                                                    <option value="{{ $percentageType->id }}"
                                                                                        {{ $item->percentage_type == $percentageType->id ? 'selected' : '' }}>
                                                                                        {{ $percentageType->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                class="form-control input-sm"
                                                                                name="percentage[]"
                                                                                value="{{ $item->percentage }}"
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
                                                                                onclick="deletePercentageRow(this)"
                                                                                type="button">
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
                                                <div class="form-group" id="fixed">

                                                    <table class="table table-bordered fixed-table" width="100%">
                                                        <thead>
                                                            <th width="60%">Product Name</th>
                                                            <th width="30%">Amount</th>
                                                            <th width="10%">
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
                                                                    <select name="fixed_type[]" class="form-control" onchange="getFixed(this)">
                                                                        <option value="">Select Type</option> 
                                                                        <option @if (optional($broker->brokerCommission->where('commission_type','3')->first())->fixed_type == 1)  selected @endif value="1">
                                                                            Invoice Wise
                                                                        </option>
                                                                        <option @if (optional($broker->brokerCommission->where('commission_type','3')->first())->fixed_type == 2)  selected @endif value="2">
                                                                            Monthly
                                                                        </option>
                                                                        <option @if (optional($broker->brokerCommission->where('commission_type','3')->first())->fixed_type == 3)  selected @endif  value="3">
                                                                            Yearly
                                                                        </option>
                                                                        <option @if (optional($broker->brokerCommission->where('commission_type','3')->first())->fixed_type == 4)  selected @endif value="4">
                                                                            Festival-Eid
                                                                        </option>
                                                                        <option @if (optional($broker->brokerCommission->where('commission_type','3')->first())->fixed_type == 5)  selected @endif  value="5">
                                                                            Festival-Durga Puja
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control input-sm"
                                                                        name="fixed[]" value="{{ optional($broker->brokerCommission->where('commission_type','3')->first())->fixed }}"
                                                                        placeholder="Amount">
                                                                </td>

                                                                <td>
                                                                    <div class="btn-group btn-corner">
                                                                        <button class="btn btn-danger btn-xs"
                                                                            onclick="deleteFixedRow(this)"
                                                                            type="button" >
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            @if ($broker->brokerCommission->count() > 0)
                                                                @foreach ($broker->brokerCommission->where('commission_type','2') as $key => $item)

                                                                    <tr>
                                                                        <td>
                                                                            <select name="fixed_type[]" class="form-control product_ids" onchange="getFixed(this)">
                                                                                <option value="{{ $item->fixed_type }}" >{{ $item->product->name }}</option> 
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control input-sm"
                                                                                name="fixed[]" value="{{ $item->fixed }}"
                                                                                placeholder="Amount">
                                                                        </td>

                                                                        <td>
                                                                            <div class="btn-group btn-corner">
                                                                                <button class="btn btn-danger btn-xs"
                                                                                    onclick="deleteFixedRow(this)"
                                                                                    type="button"  >
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <select name="fixed_type[]" class="form-control product_ids" onchange="getFixed(this)">
                                                                            <option value="">Select Type</option> 
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control input-sm"
                                                                            name="fixed[]" value="0"
                                                                            placeholder="Amount">
                                                                    </td>

                                                                    <td>
                                                                        <div class="btn-group btn-corner">
                                                                            <button class="btn btn-danger btn-xs"
                                                                                onclick="deleteFixedRow(this)"
                                                                                type="button"  >
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
                                          
                                           
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-v-3" role="tabpanel" aria-labelledby="tab-v-3-tab">
                                        <div class="row">
                                            <table class="table table-bordered customer-attached-table">
                                                <thead>
                                                    <th>Customer</th>
                                                    <th>Status</th>
                                                    <th>
                                                        <div class="btn-group btn-corner">
                                                            <button class="btn btn-success btn-xs add-row" onclick="addCustomerAttachedRow()" type="button">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    @if ($broker->customerAttached->count() > 0)
                                                        @foreach ($broker->customerAttached as $key => $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="customer_attached_id[]" value="{{ $item->id }}">
                                                                    <select name="customer_id[]" class="form-control to-select">
                                                                        <option value="">Select Customer</option>
                                                                        @foreach ($customers as $customer)
                                                                            <option value="{{ $customer->id }}" {{ $item->customer_id == $customer->id ? 'selected' : '' }}>
                                                                                {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null) ({{ $customer->area->area }}) @endif
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="status[]" class="form-control">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Active</option>
                                                                        <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Inactive</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-corner">
                                                                        <button class="btn btn-danger btn-xs" onclick="deleteCustomerAttachedRow(this)" type="button">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td>
                                                                <select name="customer_id[]" class="form-control to-select">
                                                                    <option value="">Select Customer</option>
                                                                    @foreach ($customers as $customer)
                                                                        <option value="{{ $customer->id }}">{{ $customer->company_name }} - {{ $customer->address}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="status[]" class="form-control">
                                                                    <option value="">Select Status</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="2">Inactive</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-corner">
                                                                    <button class="btn btn-danger btn-xs" onclick="deleteCustomerAttachedRow(this)" type="button">
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
                                    <div class="tab-pane fade" id="tab-v-4" role="tabpanel"
                                        aria-labelledby="tab-v-4-tab">
                                        <br>
                                        @php
                                            $prefix = $prefix ?? '';
                                            $division = $broker->division_id ?? '';
                                            $district = $broker->district_id ?? '';
                                            $thana = $broker->thana_id ?? '';
                                            $union = $union ?? '';
                                            $village = $village ?? '';
                                            $post_code = $post_code ?? '';
                                            $street = $street ?? '';
                                            $lat = $lat ?? '';
                                            $lng = $lng ?? '';
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-25">
                                                    <label for="division">Division<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control geo-select" data-type="division"
                                                        data-defualt="{{ old($prefix . 'division', $division) }}"
                                                        @if ($division && ($divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first())) data-default_name="{{ $divisionOption->name }}" @endif
                                                        id="division" name={{ $prefix . 'division_id' }} required>
                                                        <option value="">Select a Division</option>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-25">
                                                    <label for="district">District<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control geo-select" data-type="district"
                                                        data-defualt="{{ old($prefix . 'district', $district) }}"
                                                        @if ($district && ($districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first())) data-default_name="{{ $districtOption->name }}" @endif
                                                        data-parant="#division" name="{{ $prefix . 'district_id' }}"
                                                        id="district" required>
                                                        <option value="">Select a District</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-25">
                                                    <label for="thana">Thana<span class="text-danger">*</span></label>
                                                    <select class="form-control geo-select" data-type="thana"
                                                        data-defualt="{{ old($prefix . 'thana', $thana) }}"
                                                        @if ($thana && ($thanaOption = App\Models\GeoLocation::where('id', $thana)->select('id', 'name')->first())) data-default_name="{{ $thanaOption->name }}" @endif
                                                        data-parant="#district" name="{{ $prefix . 'thana_id' }}"
                                                        id="thana" required>
                                                        <option value="">Select a Thana</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-25">
                                                    <label for="present_address"
                                                        class="color-dark fs-14 fw-500 align-center">Present
                                                        Address</label>
                                                    <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" name="present_address"
                                                        style="height: 100px;" id="present_address" placeholder="Present Address">{{ old('present_address', $broker->present_address) }}</textarea>
                                                </div>
                                                <div class="form-group mb-25">
                                                    <label for="permanent_address"
                                                        class="color-dark fs-14 fw-500 align-center">Permanent
                                                        Address</label>

                                                    <textarea name="permanent_address" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                        id="permanent_address" style="height: 100px;">{{ old('permanent_address', $broker->permanent_address) }}</textarea>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                 

                                </div>
                            </div>
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                
                                <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
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

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add event listeners to existing rows
            updateCustomerStatusRequirement();
    
            // When a new row is added
            function addCustomerAttachedRow() {
                // Your existing code for adding a new row
    
                // Add event listeners to the new row
                updateCustomerStatusRequirement();
            }
    
            function updateCustomerStatusRequirement() {
                const customerSelects = document.querySelectorAll('select[name="customer_id[]"]');
                customerSelects.forEach(select => {
                    select.addEventListener('change', function () {
                        toggleStatusRequirement(this);
                    });
    
                    // Set initial status requirement based on current selection
                    toggleStatusRequirement(select);
                });
            }
    
            function toggleStatusRequirement(customerSelect) {
                const row = customerSelect.closest('tr');
                const statusSelect = row.querySelector('select[name="status[]"]');
    
                if (customerSelect.value) {
                    statusSelect.setAttribute('required', 'required');
                } else {
                    statusSelect.removeAttribute('required');
                }
            }
        });
    
        function deleteCustomerAttachedRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateCustomerStatusRequirement(); // Update the requirement for other rows
        }
    </script> --}}

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
            var table2 = table.clone().find('select option:selected').removeAttr('selected').end();
            table2.clone().find('input').val('').end().insertAfter(table);
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

        // commission checkbox click handle
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
        var rowIndex = row.index(); 
        var selectElement = row.find("select")[0];

        if (selectElement && selectElement.value !== "") {
            var index = selectedFixedIds.indexOf(selectElement.value);
            if (index !== -1) {
                selectedFixedIds.splice(index, 1);
            }
        }

        // first & second row remove hobe na
        if (rowIndex === 0 || rowIndex === 1) {

            row.find("input").val("");

            if (selectElement && selectElement.tomselect) {
                selectElement.tomselect.clear();
            } else {
                row.find("select").val("");
            }

        } else {
            row.remove();
        }

        refreshFixedOptions(); // jodi option refresh korte chao
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


    });

 

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
        var clone = originalCustomerAttachedRow.clone(true);
        // Clear input and textarea values.
        clone.find('input, textarea').val('');
        // Clear all select values and remove any previous selection data.
        clone.find('select').each(function() {
            $(this).val('');
            $(this).removeAttr("data-old-value");
            if (this.tomselect) {
                this.tomselect.clear();
            }
        });
        $(".customer-attached-table tbody").append(clone);

        // Initialize TomSelect on the new row.
        clone.find('.to-select').each(function() {
            new TomSelect(this, { autoclose: true });
        });
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
            // row.find("input").val('');
            row.find("select").each(function() {
                if (this.tomselect) {
                    this.tomselect.clear();
                }
            });
        }
    }
</script>
    
  
@endSection
