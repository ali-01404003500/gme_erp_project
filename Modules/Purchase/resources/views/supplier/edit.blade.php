@section('title', 'Supplier Update')
@section('description', 'Supplier Update')
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
        .ts-control{
            flex-wrap: nowrap !important;
        }
    </style>
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
                                        {{ trans('menu.update-supplier-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 row">
                                @if (hasPermission('purchase.suppliers.index'))
                                <a href="{{ route('purchase.suppliers.index') }}"
                                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                                        class="fa fa-list"></i> List</a>
                                @endif
                                @if (hasPermission('purchase.suppliers.create'))
                                <a href="{{ route('purchase.suppliers.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-supplier-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-10">
                            <div class="mt-40 mb-50">
                                <form action="{{ route('purchase.suppliers.update', $supplier->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf



                                    <div class="dm-tab tab-horizontal">
                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">

                                            <li class="nav-item">
                                                <a class="nav-link active" id="tab-v-1-tab" data-bs-toggle="tab"
                                                    href="#tab-v-1" role="tab" aria-selected="true">Company
                                                    Information</a>
                                            </li>

                                            {{-- <li class="nav-item">
                                                <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                                    role="tab" aria-selected="true">Personal
                                                    Information</a>
                                            </li> --}}
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                                    role="tab" aria-selected="false">Owner Information</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3"
                                                    role="tab" aria-selected="false">Supplier Identity Information</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">



                                            <div class="tab-pane fade show active" id="tab-v-1" role="tabpanel"
                                                aria-labelledby="tab-v-1-tab">

                                                {{-- <h2 class="company-info"> Company Information</h2> --}}
                                                <div class="row" id="company-row">

                                                    {{-- <div class="col-sm-6"> --}}

                                                        {{-- <div class="edit-profile__body row"> --}}
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="company_name"
                                                                    class="color-dark fs-14 fw-500 align-center">Company
                                                                    Name <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="company_name"
                                                                    value="{{ old('company_name', $supplier->company_name) }}"
                                                                    id="company_name" placeholder="Company Name" required>
                                                                @if ($errors->has('company_name'))
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('company_name') }}</p>
                                                                @endif
                                                            </div>


                                                            <div class="form-group mb-25 col-sm-4">
                                                                <label for="phone" class="color-dark fs-14 fw-500 align-center">
                                                                    Contact Number <span class="text-danger">*</span>
                                                                </label>
                                                                <div class="input-group justify-content-between">
                                                                    <div class="input-group-text p-0 border-0">
                                                                        <select id="countryCode" name="country_code" class="form-control tom-select" style="width: 100px; ">
                                                                            <option value="">Code</option>
                                                                        </select>
                                                                    </div>
                                                                    <input type="number" class="form-control flex-grow" name="phone" value="{{ old('phone', $supplier->phone) }}" id="phoneNumber" placeholder="Phone" required>
                                                                </div>
                                                                @if ($errors->has('phone'))
                                                                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="email"
                                                                    class="color-dark fs-14 fw-500 align-center">Company
                                                                    Email
                                                                </label>
                                                                <input type="email"
                                                                    class="form-control"
                                                                    name="email" id="email"
                                                                    value="{{ old('email', $supplier->email) }}"
                                                                    placeholder="Email Address">
                                                                @if ($errors->has('email'))
                                                                    <p class="text-danger">{{ $errors->first('email') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="customer_id"
                                                                    class="color-dark fs-14 fw-500 align-center">Customer
                                                                    Reference
                                                                </label>
                                                                <select
                                                                    class="form-control"
                                                                    name="customer_id" id="customer_id">
                                                                    <option value="">Select Customer</option>
                                                                    @foreach ($customers as $customer)
                                                                        <option value="{{ $customer->id }}"
                                                                            {{ old('customer_id', $supplier->customer_id) == $customer->id ? 'selected' : '' }}>
                                                                            {{ $customer->company_name }} - {{ $customer->address}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @if ($errors->has('customer_id'))
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('customer_id') }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="opening_balance"
                                                                    class="color-dark fs-14 fw-500 align-center">Opening
                                                                    Balance
                                                                </label>
                                                                <input type="number"
                                                                    class="form-control"
                                                                    name="opening_balance" id="opening_balance"
                                                                    value="{{ old('opening_balance', $supplier->opening_balance) }}"
                                                                    placeholder="Opening Balance">
                                                                @if ($errors->has('opening_balance'))
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('opening_balance') }}</p>
                                                                @endif
                                                            </div>

                                                        {{-- </div> --}}
                                                    {{-- </div>
                                                    <div class="col-md-6" id="right-column"> --}}
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="profession"
                                                                    class="color-dark fs-14 fw-500 align-center">Contact For
                                                                    SMS </label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="contact_for_sms" id="Contact Number"
                                                                    value="{{ old('contact_for_sms', $supplier->contact_for_sms) }}"
                                                                    placeholder="Contact For SMS">
                                                                @if ($errors->has('contact_for_sms'))
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('contact_for_sms') }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="tnt_number"
                                                                    class="color-dark fs-14 fw-500 align-center">TNT/ Land
                                                                    Number
                                                                </label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="tnt_number" id="tnt_number"
                                                                    value="{{ old('tnt_number', $supplier->tnt_number) }}"
                                                                    placeholder="TNT/ Land Number">
                                                                @if ($errors->has('tnt_number'))
                                                                    <p class="text-danger">{{ $errors->first('tnt_number') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="company_address"
                                                                    class="color-dark fs-14 fw-500 align-center">Company Place
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="company_place"
                                                                    value="{{ old('company_place', $supplier->company_place) }}"
                                                                    id="company_place" placeholder="Company Place" required>
                                                                @if ($errors->has('company_place'))
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('company_place') }}</p>
                                                                @endif

                                                            </div>
                                                            <div class="form-group mb-25 col-md-4">
                                                                <label for="country"
                                                                    class="color-dark fs-14 fw-500 align-center">Country</label>
                                                                    <select class="form-control tom-select"
                                                                    id="country" name="country">
                                                                    <option value="">Select Country</option>
                                                                    @foreach (cuntriesNames() as $productOrigin)
                                                                        <option value="{{ $productOrigin }}"
                                                                            @if (old('country', $supplier->country) == $productOrigin) selected @endif>
                                                                            {{ $productOrigin }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                    {{-- </div> --}}
                                                            <div class="form-group mb-25 col-md-12">
                                                                <label for="address"
                                                                    class="color-dark fs-14 fw-500 align-center">Address<span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="address" style="height: 140px;"
                                                                    id="address" placeholder="Address">{{ old('address', $supplier->address) }}</textarea>
                                                            </div>
                                                </div>
                                            </div>

                                            {{-- <div class="tab-pane fade show" id="tab-v-2" role="tabpanel"
                                                aria-labelledby="tab-v-2-tab">

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-25">
                                                            <label for="contact_person_name"
                                                                class="color-dark fs-14 fw-500 align-center">Contact Person
                                                                Name<span class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="contact_person_name" id="contact_person_name"
                                                                value="{{ old('contact_person_name', $supplier->contact_person_name) }}"
                                                                placeholder="Contact Person Name" required>
                                                            @if ($errors->has('contact_person_name'))
                                                                <p class="text-danger">
                                                                    {{ $errors->first('contact_person_name') }}
                                                                </p>
                                                            @endif
                                                        </div>

                                                        <div class="form-group mb-25">
                                                            <label for="contact_person_mobile"
                                                                class="color-dark fs-14 fw-500 align-center">Contact Person
                                                                Mobile<span class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="contact_person_mobile" id="contact_person_mobile"
                                                                value="{{ old('contact_person_mobile', $supplier->contact_person_mobile) }}"
                                                                placeholder="Contact Person Mobile" required>
                                                            @if ($errors->has('contact_person_mobile'))
                                                                <p class="text-danger">
                                                                    {{ $errors->first('contact_person_mobile') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-25">
                                                            <label for="contact_person_email"
                                                                class="color-dark fs-14 fw-500 align-center">Contact Person
                                                                Email</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="contact_person_email" id="contact_person_email"
                                                                value="{{ old('contact_person_email', $supplier->contact_person_email) }}"
                                                                placeholder="Contact Person Email">
                                                            @if ($errors->has('contact_person_email'))
                                                                <p class="text-danger">
                                                                    {{ $errors->first('contact_person_email') }}</p>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-25">
                                                            <label for="contact_person_address"
                                                                class="color-dark fs-14 fw-500 align-center">Contact Person
                                                                Address<span class="text-danger">*</span></label>
                                                            <input type="text" name="contact_person_address"
                                                                class="form-control"
                                                                id="contact_person_address"
                                                                value="{{ old('contact_person_address', $supplier->contact_person_address) }}"
                                                                placeholder="Contact Person Address" required>
                                                        </div>
                                                        <div class="form-group mb-25">
                                                            <div class="form-group">
                                                                <label class="fs-15 ms-20 fw-500 text-capitalize">profile
                                                                    photo</label>
                                                                <input id="profile-picture" type="file"
                                                                    accept="image/*" name="profile_picture"
                                                                    class="file-control"
                                                                    data-preview-element="profile-picture-preview">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <div class="tab-pane fade" id="tab-v-2" role="tabpanel"
                                                aria-labelledby="tab-v-2-tab">

                                                <div class="row">

                                                    {{-- <div class="col-md-6"> --}}
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_name"
                                                                class="color-dark fs-14 fw-500 align-center">Owner
                                                                Name</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="owner_name" id="owner_name"
                                                                value="{{ old('owner_name', $supplier->owner_name) }}"
                                                                placeholder="Owner Name">
                                                            @if ($errors->has('owner_name'))
                                                                <p class="text-danger">{{ $errors->first('owner_name') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_designation"
                                                                class="color-dark fs-14 fw-500 align-center">Owner
                                                                Designation</label>
                                                            <select name="owner_designation" id="owner_designation"
                                                                class="form-control">
                                                                <option value="">Choose Owner Designation</option>
                                                                <option value="Director"
                                                                    @if (old('owner_designation', $supplier->owner_designation) == 'Director') selected @endif>
                                                                    Director</option>
                                                                <option value="Managing Director"
                                                                    @if (old('owner_designation', $supplier->owner_designation) == 'Managing Director') selected @endif>
                                                                    Managing Director</option>
                                                                <option value="Deputy Managing Director"
                                                                    @if (old('owner_designation', $supplier->owner_designation) == 'Deputy Managing Director') selected @endif>
                                                                    Deputy Managing
                                                                    Director</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_mobile"
                                                                class="color-dark fs-14 fw-500 align-center">Owner
                                                                Mobile</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="owner_mobile" id="owner_mobile"
                                                                value="{{ old('owner_mobile', $supplier->owner_mobile) }}"
                                                                placeholder="Owner Mobile">
                                                            @if ($errors->has('owner_mobile'))
                                                                <p class="text-danger">
                                                                    {{ $errors->first('owner_mobile') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    {{-- </div>

                                                    <div class="col-md-6"> --}}
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_email"
                                                                class="color-dark fs-14 fw-500 align-center">Owner
                                                                Email</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="owner_email" id="owner_email"
                                                                value="{{ old('owner_email', $supplier->owner_email) }}"
                                                                placeholder="Owner Email">
                                                            @if ($errors->has('owner_email'))
                                                                <p class="text-danger">{{ $errors->first('owner_email') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_dob"
                                                                class="color-dark fs-14 fw-500 align-center">Date of
                                                                Birth</label>
                                                            <input type="text"
                                                                class="form-control form-control-default datePicker"
                                                                value="{{ date('m/d/Y', strtotime($supplier->owner_dob)) }}"
                                                                name="owner_dob" id="owner_dob"
                                                                placeholder="Date of Birth">
                                                            @if ($errors->has('owner_dob'))
                                                                <p class="text-danger">{{ $errors->first('owner_dob') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="owner_address"
                                                                class="color-dark fs-14 fw-500 align-center">Owner
                                                                Address</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="owner_address" id="owner_address"
                                                                value="{{ old('owner_address', $supplier->owner_address) }}"
                                                                placeholder="Owner Address">
                                                            @if ($errors->has('owner_address'))
                                                                <p class="text-danger">
                                                                    {{ $errors->first('owner_address') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    {{-- </div> --}}
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tab-v-3" role="tabpanel"
                                                aria-labelledby="tab-v-3-tab">

                                                <div class="row">
                                                    {{-- <div class="col-md-6"> --}}
                                                        <div class="form-group mb-25 col-md-4">
                                                            <label for="nid"
                                                                class="color-dark fs-14 fw-500 align-center">National Id
                                                                no.</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="nid" id="nid"
                                                                value="{{ old('nid', $supplier->nid) }}"
                                                                placeholder="Identity Number">
                                                            @if ($errors->has('nid'))
                                                                <p class="text-danger">{{ $errors->first('nid') }}</p>
                                                            @endif
                                                        </div>
                                                    {{-- </div>
                                                    </div>


                                                    <div class="row">
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="front_image"
                                                                class="color-dark fs-14 fw-500 align-center">Front
                                                                Image</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->front_image" name="front_image"/>

                                                                    {{-- <input id="front-image" type="file"
                                                                        accept="image/*" name="front_image"
                                                                        class="file-control" data-value="{{ $supplier->front_image }}"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="back_image"
                                                                class="color-dark fs-14 fw-500 align-center">Back
                                                                Image</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->back_image" name="back_image"/>
                                                                    {{-- <input id="back-image" type="file"
                                                                        accept="image/*" name="back_image"
                                                                        class="file-control"
                                                                        data-value="{{ $supplier->back_image }}">"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="visiting_card_front"
                                                                class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                                (Front)</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->visiting_card_front" name="visiting_card_front"/>
                                                                    {{-- <input id="visiting-card-front" type="file"
                                                                        accept="image/*" name="visiting_card_front"
                                                                        class="file-control"
                                                                        data-value="{{ $supplier->visiting_card_front }}"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="visiting_card_back"
                                                                class="color-dark fs-14 fw-500 align-center">Visiting Card
                                                                (Back)</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->visiting_card_back" name="visiting_card_back"/>
                                                                    {{-- <input id="visiting-card-back" type="file"
                                                                        accept="image/*" name="visiting_card_back"
                                                                        class="file-control"
                                                                        data-value="{{ $supplier->visiting_card_back }}"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="trade_license"
                                                                class="color-dark fs-14 fw-500 align-center">Trade
                                                                License</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->trade_license" name="trade_license"/>
                                                                    {{-- <input id="trade-license" type="file"
                                                                        accept="image/*" name="trade_license"
                                                                        class="file-control"
                                                                        data-value="{{ $supplier->trade_license }}"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                    <div class="col-md-6"> --}}
                                                        <div class="row">
                                                            <label for="signature"
                                                                class="color-dark fs-14 fw-500 align-center">Signature</label>
                                                            <div class="account-profile d-flex align-items-center mb-4 ">
                                                                <div class="form-group">
                                                                    <x-file-uploader :value="$supplier->signature" name="signature"/>
                                                                    {{-- <input id="signature" type="file" accept="image/*"
                                                                        name="signature" class="file-control"
                                                                        data-value="{{ $supplier->signature }}"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- </div>
                                                        </div> --}}
                                                        <div class="form-group mb-25 col-md-12">
                                                            <label for="remarks"
                                                                class="color-dark fs-14 fw-500 align-center">Remarks</label>
                                                            <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="5">{{ $supplier->remarks }}</textarea>
                                                        </div>
                                                </div>
                                            </div>




                                            <div
                                                class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                
                                                <button type="submit"
                                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/js/country-codes.json')
            .then(response => response.json())
            .then(data => {
                const countryCodeSelect = document.getElementById('countryCode');
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.dial_code;
                    option.textContent = `${country.code} (${country.dial_code})`;
                    if (country.dial_code === "{{ $supplier->country_code }}") {
                        option.selected = true; // Select the currently saved country code
                    }
                    countryCodeSelect.appendChild(option);
                });
                countryCodeSelect.tomselect.sync();            
            });
    });
</script>
    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>

@endSection
