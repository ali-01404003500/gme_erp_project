@section('title', 'Customer Update')
@section('description', 'Customer Update')
@extends('layout.app')
@section('content')

    <Style>
        #right-column {
            margin-bottom: 10px !importent;
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
                                    {{ trans('menu.customer-update') }}</li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="button-group d-flex pt-25 justify-content-start btn-sm">
                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                            <div class="d-flex gap-2">
                                <a href="{{ route('crm.customers.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                <a href="{{ route('crm.customers.create', app()->getLocale()) }}" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="las la-plus fs-14"></i>Add New
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('customer update') }}</h3>

                </div>
                                                    <x-error-alart />

            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center" id="justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('crm.customers.update', $customer->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            {{-- <div class="account-profile d-flex align-items-center mb-4 ">
                                <div class="ap-img pro_img_wrapper">
                                    <input id="profile-picture" type="file" accept="image/*" name="profile-picture" class="d-none image-upload-field" data-preview-element="profile-picture-preview">
                                    <!-- Profile picture image-->
                                    <label for="profile-picture">
                                        <img src="{{ asset( 'assets/img/svg/user.svg' ) }}" alt="user" class="profile-picture-preview ap-img__main rounded-circle wh-120 bg-lighter d-flex">

                                        <span
                                            title="Pick an image"
                                            id="remove_pro_pic"
                                            class="cross clear-input-file-btn"
                                            data-input-has-file="0"
                                            data-pick-title="Pick an image"
                                            data-pick-icon="{{ asset( 'assets/img/svg/camera-white.svg' ) }}"
                                            data-clear-title="Remove"
                                            data-clear-icon="{{ asset( 'assets/img/svg/close-white.svg' ) }}"
                                            data-input-element-id="profile-picture"
                                            data-preview-element="profile-picture-preview"
                                            data-default-preview-image="{{ asset( 'assets/img/svg/user.svg' ) }}"
                                        >
                                            <img src="{{ asset( 'assets/img/svg/camera-white.svg' ) }}" alt="camera">
                                        </span>
                                    </label>
                                </div>
                                <div class="account-profile__title">
                                    <h6 class="fs-15 ms-20 fw-500 text-capitalize">profile photo</h6>
                                </div>
                            </div> --}}
                            <div class="dm-tab tab-horizontal">
                                <h4> <div class="col-sm-14">
                                    <label class="col-sm-12 control-label">Customer Name :
                                        {{ $customer->company_name . ' (' . optional($customer->area)->area . ') ' }}
                                       </label>

                                </div></h4>
                                <br>
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
                                                    name="customer_id" value="{{ old('customer_id', $customer->customer_id) }}" id="customer_id"
                                                    placeholder="Customer ID">
                                                @if ($errors->has('customer_id"'))
                                                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="company_name"
                                                    class="color-dark fs-14 fw-500 align-center">Company
                                                    Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                    name="company_name"
                                                    value="{{ old('company_name', $customer->company_name) }}"
                                                    id="company_name" placeholder="Company Name">
                                                @if ($errors->has('company_name'))
                                                    <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="phone" class="color-dark fs-14 fw-500 align-center">Contact
                                                    Number <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                    name="phone" value="{{ old('phone', $customer->phone) }}"
                                                    id="phone" placeholder="Phone">
                                                @if ($errors->has('phone'))
                                                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="email" class="color-dark fs-14 fw-500 align-center">Email
                                                    Address
                                                </label>
                                                <input type="email"
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                    name="email" id="email"
                                                    value="{{ old('email', $customer->email) }}"
                                                    placeholder="Email Address">
                                                @if ($errors->has('email'))
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="company_place_id"
                                                    class="color-dark fs-14 fw-500 align-center">Company Place <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control tom-select" name="company_place_id">
                                                    <option value="">Choose Company Place</option>
                                                    @foreach ($areas as $key => $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('company_place_id', $customer->company_place_id) == $item->id ? 'selected' : '' }}>
                                                            {{ $item->area }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('company_place'))
                                                    <p class="text-danger">{{ $errors->first('company_place') }}</p>
                                                @endif

                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="profession"
                                                    class="color-dark fs-14 fw-500 align-center">Contact Number (SMS)</label>
                                                <input type="text"
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                    name="contact_for_sms" id="contact_for_sms"
                                                    value="{{ old('contact_for_sms', $customer->contact_for_sms) }}"
                                                    placeholder="Contact For SMS">
                                                @if ($errors->has('contact_for_sms'))
                                                    <p class="text-danger">{{ $errors->first('contact_for_sms') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="user_ref_id" class="color-dark fs-14 fw-500 align-center">User
                                                    Reference <samp class="text-danger">*</samp></label>
                                                <select
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15 tom-select"
                                                    name="user_ref_id" id="user_ref_id">
                                                    <option value="">Choose User Reference</option>
                                                    @foreach ($employees as $key => $item)
                                                        <option value="{{ $key }}"
                                                            {{ old('user_ref_id', $customer->user_ref_id) == $key ? 'selected' : '' }}>
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
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15 tom-select"
                                                    name="customer_type" id="customer_type">
                                                    <option value="">Choose Customer Type</option>
                                                    @foreach ($customerTypes as $key => $item)
                                                        <option value="{{ $key }}"
                                                            {{ old('customer_type', $customer->customer_type) == $key ? 'selected' : '' }}>
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
                                                    class="form-control ih-medium ip-gray radius-xs b-light px-15 tom-select">
                                                    <option value="">Choose Customer Reference</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('customer_ref_id', $customer->customer_ref_id) == $item->id ? 'selected' : '' }}>
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

                                            <div class="form-group col-md-4 mb-25">
                                                <label for="logo"
                                                    class="color-dark fs-14 fw-500 align-center">LOGO</label>
                                                    <x-file-uploader :value="$customer->logo ?? old('logo')" name="logo"/>
                                                        {{-- <input type="file" class="file-control form-control" id="logo"
                                                    name="logo" data-value="{{ $customer->logo }}"> --}}
                                            </div>
                                            <div class="form-group  mb-25">
                                                <label for="address"
                                                    class="color-dark fs-14 fw-500 align-center">Address <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" name="address" id="address"
                                                    placeholder="Address">{{ old('address', $customer->address) }}</textarea>
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

                                                            @foreach ($customer->customerOwner as $key => $value)
                                                                <tr class="owner-row">
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control ip-gray radius-xs b-light px-15"
                                                                            name="owner_name[]" id="owner_name"
                                                                            value="{{ $value->owner_name }}"
                                                                            placeholder="Owner Name">

                                                                    </td>
                                                                    <td>
                                                                        <select name="owner_designation[]"
                                                                            id="owner_designation"
                                                                            class="form-control  to-select">
                                                                            <option value="">Choose Owner Designation
                                                                            </option>
                                                                            <option value="1"
                                                                                {{ $value->owner_designation == 1 ? 'selected' : '' }}>
                                                                                Director
                                                                            </option>
                                                                            <option value="2"
                                                                                {{ $value->owner_designation == 2 ? 'selected' : '' }}>
                                                                                Managing
                                                                                Director</option>
                                                                            <option value="3"
                                                                                {{ $value->owner_designation == 3 ? 'selected' : '' }}>
                                                                                Deputy
                                                                                Managing Director</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                                            name="owner_mobile[]" id="owner_mobile"
                                                                            value="{{ $value->owner_mobile }}"
                                                                            placeholder="Owner Mobile">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                                            name="owner_email[]" id="owner_email"
                                                                            value="{{ $value->owner_email }}"
                                                                            placeholder="Owner Email">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control form-control-default ih-medium ip-gray radius-xs b-light px-15 datePicker"
                                                                            value="{{ date('m/d/Y', strtotime($value->owner_dob)) }}"
                                                                            name="owner_dob[]"
                                                                            placeholder="Date of Birth">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-xs disabled" disabled
                                                                            id="remove_row"
                                                                            onclick="deleteOwnerRow(this)">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>

                                                                </tr>
                                                            @endforeach


                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6" style="text-align: right;">
                                                                    <button type="button" class="btn btn-info btn-sm"
                                                                        onclick="addOwnerRow()">
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
                                            <div class="col-md-6">
                                                <div class="col-md-4">
                                                    <label for="nid"
                                                        class="color-dark fs-14 fw-500 align-center">National Id
                                                        no.</label>
                                                    <input type="text"
                                                        class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                        name="nid" id="nid"
                                                        value="{{ old('nid', $customer->nid) }}"
                                                        placeholder="Identity Number">
                                                    @if ($errors->has('nid'))
                                                        <p class="text-danger">{{ $errors->first('nid') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="row">
                                                <label for="front_image" class="color-dark fs-14 fw-500 align-center">NID
                                                    Front
                                                    Image</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->front_image ?? old('front_image')"  name="front_image"/>

                                                        {{-- <input id="front-image" type="file" accept="image/*"
                                                            name="front_image" class="file-control"  data-value="{{ $customer->front_image }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="back_image" class="color-dark fs-14 fw-500 align-center">NID
                                                    Back
                                                    Image</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->back_image ?? old('back_image')" name="back_image"/>

                                                        {{-- <input id="back-image" type="file" accept="image/*"
                                                            name="back_image" class="file-control"
                                                            data-value="{{ $customer->back_image }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="visiting_card_front"
                                                    class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                    (Front)</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->visiting_card_front ?? old('visiting_card_front')"  name="visiting_card_front"/>

                                                        {{-- <input id="visiting-card-front" type="file" accept="image/*"
                                                            name="visiting_card_front" class="file-control"
                                                            data-value="{{ $customer->visiting_card_front }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="visiting_card_back"
                                                    class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                    (Back)</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->visiting_card_back ?? old('visiting_card_back')" name="visiting_card_back"/>

                                                        {{-- <input id="visiting-card-back" type="file" accept="image/*"
                                                            name="visiting_card_back" class="file-control"
                                                            data-value="{{ $customer->visiting_card_back }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="trade_license"
                                                    class="color-dark fs-14 fw-500 align-center">Trade
                                                    License</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->trade_license ?? old('trade_license')" name="trade_license"/>

                                                        {{-- <input id="trade-license" type="file" accept="image/*"
                                                            name="trade_license" class="file-control"
                                                            data-value="{{ $customer->trade_license }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="signature"
                                                    class="color-dark fs-14 fw-500 align-center">Signature</label>
                                                <div class="account-profile d-flex align-items-center mb-4 ">
                                                    <div class="form-group">
                                                        <x-file-uploader :value="$customer->signature ?? old('signature')" name="signature"/>

                                                        {{-- <input id="signature" type="file" accept="image/*"
                                                            name="signature" class="file-control"
                                                            data-value="{{ $customer->signature }}"> --}}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group mb-25">
                                            <label for="remarks"
                                                class="color-dark fs-14 fw-500 align-center">Remarks</label>
                                            <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="5">{{ old('remarks', $customer->remarks) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-v-5" role="tabpanel"
                                        aria-labelledby="tab-v-5-tab">

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
                                                        @foreach ($customer->customerShippingAddress as $key => $value)
                                                        <tr class="shipping-item">
                                                            <td>
                                                                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="ship_to[]" id="ship_to" value="{{ $value->ship_to }}" placeholder="Ship to">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="shipping_phone[]" id="shipping_phone" value="{{ $value->shipping_phone }}" placeholder="Mobile number">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="shipping_address[]" id="shipping_address1" value="{{ $value->shipping_address }}" placeholder="Shipping Address 1">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger btn-xs disabled" disabled onclick="deleteRow(this)" type="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" style="text-align: right;">
                                                                <button type="button" class="btn btn-info btn-sm" onclick="addRow()">
                                                                    <i class="fa fa-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                
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
    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>
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
