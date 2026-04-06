@section('title', 'Customer Create')
@section('description', 'Customer Create')
@extends('layout.app')
@section('content')

    <Style>
        #right-column {
            margin-bottom: 10px!importent;
        }

        .row {
                padding: 15px;
                margin-top: 10px;
            }

        .form-group label {
            margin-bottom: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            margin-top: 3px;
        }

        #title {
            padding: 0;
            margin-top: 0;
        }

        .fs-15 ms-20 fw-500 text-capitalize {
            margin-top:
        }

        #justify-content-center {
            margin-top: 10px !importent;
        }

        .card-body {
            margin-right: 7vh;
            margin-left: 7vh;
        }

        .row {
            padding: 2vh;
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

        /* .ts-control {
            height: 48px !important;
        } */
    </Style>
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('menu.customer-create') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                    @if(hasPermission('crm.customers.index'))
                        <a href="{{ route('crm.customers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                    @endif
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('customer create') }}</h3>
                </div>
                <x-error-alart />
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center" id="justify-content-center">
                        <form action="{{ route('crm.customers.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="dm-tab tab-horizontal">
                                <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1"
                                            role="tab" aria-selected="true">Company Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                            role="tab" aria-selected="false">Owner Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3"
                                            role="tab" aria-selected="false">Customer Identity Information</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" id="tab-v-5-tab" data-bs-toggle="tab"
                                            href="#tab-v-5" role="tab" aria-selected="false">Shipping Address</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-v-1" role="tabpanel"
                                        aria-labelledby="tab-v-1-tab">
                                        <div class="row" id="company-row">
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="customer_id"
                                                    class="color-dark fs-14 fw-500 align-center">Customer ID</label>
                                                <input type="text"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="customer_id" value="{{ old('customer_id') }}" id="customer_id"
                                                    placeholder="Customer ID">
                                                @if ($errors->has('customer_id'))
                                                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="company_name"
                                                    class="color-dark fs-14 fw-500 align-center">Company
                                                    Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="company_name" value="{{ old('company_name') }}" id="company_name"
                                                    placeholder="Company Name">
                                                @if ($errors->has('company_name'))
                                                    <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="phone" class="color-dark fs-14 fw-500 align-center">Contact
                                                    Number <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="phone" value="{{ old('phone') }}" id="phone"
                                                    placeholder="Phone">
                                                @if ($errors->has('phone'))
                                                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="email" class="color-dark fs-14 fw-500 align-center">Email
                                                    Address
                                                </label>
                                                <input type="email"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="email" id="email" value="{{ old('email') }}"
                                                    placeholder="Email Address">
                                                @if ($errors->has('email'))
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="company_place_id"
                                                    class="color-dark fs-14 fw-500 align-center">Company Place<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control tom-select" name="company_place_id">
                                                    <option value="">Choose Company Place</option>
                                                    @foreach ($areas as $key => $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('company_place_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->area }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('company_place_id'))
                                                    <p class="text-danger">{{ $errors->first('company_place_id') }}</p>
                                                @endif

                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="profession"
                                                    class="color-dark fs-14 fw-500 align-center">Contact Number (SMS)</label>
                                                <input type="text"
                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                    name="contact_for_sms" id="contact_for_sms"
                                                    value="{{ old('contact_for_sms') }}" placeholder="Contact For SMS">
                                                @if ($errors->has('contact_for_sms'))
                                                    <p class="text-danger">{{ $errors->first('contact_for_sms') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="user_ref_id" class="color-dark fs-14 fw-500 align-center">User
                                                    Reference <span class="text-danger">*</span></label>
                                                <select
                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select"
                                                    name="user_ref_id" id="user_ref_id">
                                                    <option value="">Choose User Reference</option>
                                                    @foreach ($employees as $key => $item)
                                                        <option value="{{ $key }}"
                                                            {{ old('user_ref_id') == $key ? 'selected' : '' }}>
                                                            {{ $item }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('user_ref_id'))
                                                    <p class="text-danger">{{ $errors->first('user_ref_id') }}</p>
                                                @endif

                                            </div>

                                            <div class="form-group col-md-4 mb-25">
                                                <label for="customer_type"
                                                    class="color-dark fs-14 fw-500 align-center">Customer Type <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select"
                                                    name="customer_type" id="customer_type">
                                                    <option value="">Choose Customer Type</option>
                                                    @foreach ($customerTypes as $key => $item)
                                                        <option value="{{ $key }}"
                                                            {{ old('customer_type') == $key ? 'selected' : '' }}>
                                                            {{ $item }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('customer_type'))
                                                    <p class="text-danger">{{ $errors->first('customer_type') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="customer_ref_id"
                                                    class="color-dark fs-14 fw-500 align-center">Customer Reference</label>
                                                <select name="customer_ref_id" id="customer_ref_id"
                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select">
                                                    <option value="">Choose Customer Reference</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('customer_ref_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->company_name }} @if ($item->area != null)
                                                                ({{ $item->area->area }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('customer_ref_id'))
                                                    <p class="text-danger">{{ $errors->first('customer_ref_id') }}</p>
                                                @endif

                                            </div>

                                          
                                            <div class="form-group  mb-25">
                                                <label for="address"
                                                    class="color-dark fs-14 fw-500 align-center">Address <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control ip-gray radius-xs b-light px-15" name="address" id="address"
                                                    placeholder="Address">{{ old('address') }}</textarea>
                                            </div>  
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="logo"
                                                    class="color-dark fs-14 fw-500 align-center">LOGO</label>
                                                    <x-file-uploader  name="logo"/>

                                                {{-- <input type="file" class="file-control form-control" id="logo"
                                                    name="logo"> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-v-2" role="tabpanel"
                                        aria-labelledby="tab-v-2-tab">
                                        <div class="col-md-12">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered" id="owner_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 25%">Owner Name</th>
                                                                <th style="width: 15%">Owner Designation</th>
                                                                <th style="width: 15%">Owner Mobile</th>
                                                                <th style="width: 15%">Owner Email</th>
                                                                <th style="width: 15%">Date of Birth</th>
                                                                <th style="width: 5%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="owner_info_body">
                                                            @if ($errors->any())
                                                                @php
                                                                    $oldData = old();
                                                                    $owner_names = $oldData['owner_name'];
                                                                    $owner_designations = $oldData['owner_designation'];
                                                                    $owner_mobiles = $oldData['owner_mobile'];
                                                                    $owner_emails = $oldData['owner_email'];
                                                                    $owner_dobs = $oldData['owner_dob'];
                                                                @endphp
                                                            @endif
                                                        @if (isset($owner_names))
                                                            @foreach ($owner_names as $index => $owner_name)
                                                            <tr class="owner-row">
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_name[]" id="owner_name" value="{{ $owner_name }}" placeholder="Owner Name">
                                                                    @if ($errors->has('owner_name'))
                                                                        <p class="text-danger">{{ $errors->first('owner_name') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <select name="owner_designation[]" id="owner_designation" class="form-control to-select">
                                                                        <option value="">Choose Owner Designation</option>
                                                                        <option value="1" @if($owner_designations[$index]==1) selected @endif>Director</option>
                                                                        <option value="2" @if($owner_designations[$index]==2) selected @endif>Managing Director</option>
                                                                        <option value="3" @if($owner_designations[$index]==3) selected @endif>Deputy Managing Director</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_mobile[]" value="{{$owner_mobiles[$index]}}" id="owner_mobile" value="" placeholder="Owner Mobile">
                                                                    @if ($errors->has('owner_mobile'))
                                                                        <p class="text-danger">{{ $errors->first('owner_mobile') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_email[]" value="{{$owner_emails[$index]}}" id="owner_email" value="" placeholder="Owner Email">
                                                                    @if ($errors->has('owner_email'))
                                                                        <p class="text-danger">{{ $errors->first('owner_email') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control form-control-default ip-gray radius-xs b-light px-15 datePicker"
                                                                        value="" name="owner_dob[]" value="{{$owner_dobs[$index]}}" placeholder="Date of Birth" autocomplete="off">
                                                                    @if ($errors->has('owner_dob'))
                                                                        <p class="text-danger">{{ $errors->first('owner_dob') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-xs disabled" disabled id="remove_row" onclick="deleteOwnerRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                                
                                                            </tr>
                                                            @endforeach
                                                            
                                                            @else
                                                            <tr class="owner-row">
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_name[]" id="owner_name"  placeholder="Owner Name">
                                                                    @if ($errors->has('owner_name'))
                                                                        <p class="text-danger">{{ $errors->first('owner_name') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <select name="owner_designation[]" id="owner_designation" class="form-control to-select">
                                                                        <option value="">Choose Owner Designation</option>
                                                                        <option value="1" >Director</option>
                                                                        <option value="2" >Managing Director</option>
                                                                        <option value="3" >Deputy Managing Director</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_mobile[]"  id="owner_mobile" value="" placeholder="Owner Mobile">
                                                                    @if ($errors->has('owner_mobile'))
                                                                        <p class="text-danger">{{ $errors->first('owner_mobile') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                        name="owner_email[]"  id="owner_email" value="" placeholder="Owner Email">
                                                                    @if ($errors->has('owner_email'))
                                                                        <p class="text-danger">{{ $errors->first('owner_email') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control form-control-default ip-gray radius-xs b-light px-15 datePicker"
                                                                        value="" name="owner_dob[]"  placeholder="Date of Birth" autocomplete="off">
                                                                    @if ($errors->has('owner_dob'))
                                                                        <p class="text-danger">{{ $errors->first('owner_dob') }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-xs disabled" disabled id="remove_row" onclick="deleteOwnerRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                                
                                                            </tr>
                                                        @endif  
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6" style="text-align: right;">
                                                                    <button type="button" class="btn btn-info btn-sm" onclick="addOwnerRow()">
                                                                        <i class="fa fa-plus"></i> Add</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        

                                    </div>
                                    <div class="tab-pane fade" id="tab-v-3" role="tabpanel"
                                        aria-labelledby="tab-v-3-tab">

                                        <div class="row">
                                            <div class="row">
                                                <div class="form-group mb-25">
                                                    <label for="nid"
                                                        class="color-dark fs-14 fw-500 align-center">National Id
                                                        no.</label>
                                                    <input type="text"
                                                        class="form-control ip-gray radius-xs b-light px-15"
                                                        name="nid" id="nid" value="{{ old('nid') }}"
                                                        placeholder="Identity Number">
                                                    @if ($errors->has('nid'))
                                                        <p class="text-danger">{{ $errors->first('nid') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="row">
                                                    <label for="front_image"
                                                        class="color-dark fs-14 fw-500 align-center">NID Front
                                                        Image</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="front_image"/>

                                                            {{-- <input id="front-image" type="logo" accept="image/*"
                                                                name="front_image" class="file-control"
                                                                data-preview-element="front-image-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="back_image"
                                                        class="color-dark fs-14 fw-500 align-center">NID Back
                                                        Image</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="back_image"/>

                                                            {{-- <input id="back-image" type="file" accept="image/*"
                                                                name="back_image" class="file-control"
                                                                data-preview-element="back-image-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="row">
                                                    <label for="visiting_card_front"
                                                        class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                        (Front)</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="visiting_card_front"/>

                                                            {{-- <input id="visiting-card-front" type="file"
                                                                accept="image/*" name="visiting_card_front"
                                                                class="file-control"
                                                                data-preview-element="visiting-card-front-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                           
                                                <div class="row">
                                                    <label for="visiting_card_back"
                                                        class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                        (Back)</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="visiting_card_back"/>

                                                            {{-- <input id="visiting-card-back" type="file"
                                                                accept="image/*" name="visiting_card_back"
                                                                class="file-control"
                                                                data-preview-element="visiting-card-back-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="row">
                                                    <label for="trade_license"
                                                        class="color-dark fs-14 fw-500 align-center">Trade License</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="trade_license"/>

                                                            {{-- <input id="trade-license" type="file" accept="image/*"
                                                                name="trade_license" class="file-control"
                                                                data-preview-element="trade-license-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                           
                                                <div class="row">
                                                    <label for="signature"
                                                        class="color-dark fs-14 fw-500 align-center">Signature</label>
                                                    <div class="account-profile d-flex align-items-center mb-4 ">
                                                        <div class="form-group">
                                                            <x-file-uploader  name="signature"/>
{{-- 
                                                            <input id="signature" type="file" accept="image/*"
                                                                name="signature" class="file-control"
                                                                data-preview-element="signature-preview"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="form-group mb-25">
                                            <label for="remarks"
                                                class="color-dark fs-14 fw-500 align-center">Remarks</label>
                                            <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-v-5" role="tabpanel"
                                        aria-labelledby="tab-v-5-tab">
                                        <div class="row">
                                            {{-- <div class="col-md-10"></div>
                                            <div class="col-md-2 text-right">
                                                <button class="btn btn-sm btn-success add-row" onclick="addRow()" type="button">+ Add</button>
                                            </div>
                                        </div> --}}
                                        <div class="row shipping-item-list">
                                            <div class="col-md-12">
                                                <table class="table table-bordered" id="shipping_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 30%">Ship to</th>
                                                            <th style="width: 20%">Mobile number</th>
                                                            <th style="width: 40%">Address</th>
                                                            <th style="width: 10%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="shipping_info_body">
                                                        @if ($errors->any())
                                                                @php
                                                                    $oldData = old();
                                                                    $ship_tos = $oldData['ship_to'];
                                                                    $shipping_phones = $oldData['shipping_phone'];
                                                                    $shipping_addresses = $oldData['shipping_address'];
                                                                @endphp
                                                            @endif
                                                        @if (isset($ship_tos))
                                                        @foreach ($ship_tos as $index => $ship_to)
                                                        <tr class="shipping-item">
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="ship_to[]" id="ship_to" placeholder="Ship to" value="{{ $ship_tos[$index] }}">
                                                                @if ($errors->has('ship_to'))
                                                                    <p class="text-danger">{{ $errors->first('ship_to') }}</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="shipping_phone[]" id="shipping_phone" placeholder="Mobile number" value="{{ $shipping_phones[$index] }}">
                                                                @if ($errors->has('shipping_phone'))
                                                                    <p class="text-danger">{{ $errors->first('shipping_phone') }}</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="shipping_address[]" id="shipping_address1"
                                                                    placeholder="Shipping Address 1" value="{{ $shipping_addresses[$index] }}">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-xs" onclick="deleteRow(this)" type="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                            
                                                        @else
                                                        <tr class="shipping-item">
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="ship_to[]" id="ship_to" placeholder="Ship to">
                                                                @if ($errors->has('ship_to'))
                                                                    <p class="text-danger">{{ $errors->first('ship_to') }}</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="shipping_phone[]" id="shipping_phone" placeholder="Mobile number">
                                                                @if ($errors->has('shipping_phone'))
                                                                    <p class="text-danger">{{ $errors->first('shipping_phone') }}</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input class="form-control ip-gray radius-xs b-light px-15"
                                                                    name="shipping_address[]" id="shipping_address1"
                                                                    placeholder="Shipping Address 1">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-xs disabled" disabled onclick="deleteRow(this)" type="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm" onclick="addRow()">
                                                                    <i class="fa fa-plus"></i> Add</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
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
@endsection

@section('page_scripts')
    {{-- <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script> --}}
    @include('utils.geo_locations.script')

    {{-- <script>
        document.getElementById('contact_for_sms').addEventListener('change', function() {
            var contactForSMS = this.value;
            var phoneNumber = document.getElementById('phone').value;
            if (phoneNumber.trim() === '') {
                document.getElementById('phone').value = contactForSMS;
            }
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            let counter = 0;
            $(document).on("click", ".add_billing_item", function() {
                let whole_extra_item_add = $(".billing-item").html();
                $(this).closest(".billing-item-list").append(whole_extra_item_add);
                counter++;
            });
            $(document).on("click", ".remove-billing-item", function(event) {
                $(this).closest(".billing-item-remove").remove();
                counter -= 1;
            });
            // tinymce.init({selector:'.textarea'});
        });
    </script>
    {{-- <script>
        function loadDistrictData(elem) {
            $(elem).find(".divition-select").on("change", function() {
                    	var division = $(this).find('option:selected').val();
                        var row = $(elem);
                         if (division) {
                            $.ajax({
                                url: "{{ route('locations') }}?type=district&division=" + division,
                                type: "GET",
                                success: function(data) {
                                    row.find("select.district-select option").remove();
                                    row.find("select.district-select").append('<option value="">Select District</option>');
                                    $.each(data, function(key, value) {
                                        row.find("select.district-select").append('<option value="' + value + '">' + value + '</option>');
                                    });
                                    row.find("select.district-select").prop('tomselect').sync()
                                }
                            });
                        }
            })
        }

        $(document).ready(function() {
            var row = $('.shipping-item-list').clone();
            $('.shipping-item-list .to-select').each(function() {
                    new TomSelect(this, {
                        autoclose: true
                    })
            })
            loadDistrictData($('.shipping-item-list').get(0));
            $(document).on("click", ".add_shipping_item", function() {
                let whole_extra_item_add = row.clone();
                whole_extra_item_add.find(".to-select").each(function() {
                    new TomSelect(this, {autoclose: true })
                });
                loadDistrictData(whole_extra_item_add);
                $(this).closest(".shipping-item-list").append(whole_extra_item_add);
                // counter++;
            });;
        });
    </script> --}}
    <script>
        function addRow() {
        var original = $("tr.shipping-item").first();
        var clone = $(`<tr class="shipping-item">
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15" name="ship_to[]" id="ship_to" placeholder="Ship to">
                                                            </td>
                                                            <td>
                                                                <input type="test" class="form-control ip-gray radius-xs b-light px-15" name="shipping_phone[]" id="shipping_phone" placeholder="Mobile number">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15" name="shipping_address[]" id="shipping_address1" placeholder="Shipping Address 1">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-xs" onclick="deleteRow(this)" type="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>`)
        clone.find('input, textarea').val(''); // Clear values in the cloned inputs and textareas
        // original.parent().append(clone);
        $('#shipping_info_table tbody').append(clone);
        console.log("Hello");
    }

        function deleteRow(object) {
            var rows = $(".shipping-item");
            if (rows.length > 1) {
                $(object).closest('.shipping-item').remove();
            }
        }
    </script>
    <script>
        var original = null;
        $(document).ready(function() {
            original = $(".owner-row").first().clone();
            $(".owner-row .to-select").each(function() {
                new TomSelect(this, {
                    autoclose: true
                })
            });
            $('.owner-row .datePicker').each(function() {
                $(this).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        })

        function addOwner() {
            var clone = original.clone(true);
            console.log("Load data", clone);
            clone.find('input, textarea').val(''); // Clear values in the cloned inputs and textareas
            $(".owner-row").first().parent().append(clone);
            clone.find('.to-select').each(function() {
                new TomSelect(this, {
                    autoclose: true
                })
            });
            clone.find('.datePicker').each(function() {
                $(this).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        }

        function deleteOwnerRow(object) {
            var rows = $(".owner-row");
            if (rows.length > 1) {
                $(object).closest('.owner-row').remove();
            }
        }
    </script>

<script>
    function addOwnerRow() {
        const table =$('#owner_info_body');
        const row = document.createElement('tr');
        row.innerHTML = `<tr>
            <td>
                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                    name="owner_name[]" id="owner_name" value="" placeholder="Owner Name">
                @if ($errors->has('owner_name'))
                    <p class="text-danger">{{ $errors->first('owner_name') }}</p>
                @endif
            </td>
            <td>
                <select name="owner_designation[]" id="owner_designation" class="form-control to-select">
                    <option value="">Choose Owner Designation</option>
                    <option value="1">Director</option>
                    <option value="2">Managing Director</option>
                    <option value="3">Deputy Managing Director</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                    name="owner_mobile[]" id="owner_mobile" value="" placeholder="Owner Mobile">
                @if ($errors->has('owner_mobile'))
                    <p class="text-danger">{{ $errors->first('owner_mobile') }}</p>
                @endif
            </td>
            <td>
                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                    name="owner_email[]" id="owner_email" value="" placeholder="Owner Email">
                @if ($errors->has('owner_email'))
                    <p class="text-danger">{{ $errors->first('owner_email') }}</p>
                @endif
            </td>
            <td>
                <input type="text" class="form-control form-control-default ip-gray radius-xs b-light px-15 datePicker"
                    value="" name="owner_dob[]" placeholder="Date of Birth" autocomplete="off">
                @if ($errors->has('owner_dob'))
                    <p class="text-danger">{{ $errors->first('owner_dob') }}</p>
                @endif
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-xs" id="remove_row" onclick="deleteOwnerRow(this)">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>`;
        table.append(row);
        table.find("tr:last").find('.datePicker').each(function() {
                $(this).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });

    }

    function deleteOwnerRow(button) {
        button.closest('tr').remove();
    }
</script>

@endSection