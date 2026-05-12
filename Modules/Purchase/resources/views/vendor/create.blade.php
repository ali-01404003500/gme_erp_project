@section('title', 'Vendor Create')
@section('description', 'Vendor Create')
@extends('layout.app')
@section('content')
    <style>
        <Style>#right-column {
            margin-bottom: 10px !importent;
        }

        /* .row {
                    padding: 15px;
                    margin-top: 10px;
                } */

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

        #company-row {
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

        .ts-control {
            height: 48px !important;
        }
    </Style>
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
                                        {{ trans('menu.create-vendor-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('purchase.vendors.index'))
                            <a href="{{ route('purchase.vendors.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-vendor-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-10">
                            <div class="mt-40 mb-50">
                                <form action="{{ route('purchase.vendors.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="dm-tab tab-horizontal">
                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tab-v-1-tab" data-bs-toggle="tab"
                                                    href="#tab-v-1" role="tab" aria-selected="true">Basic
                                                    Information</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                                    role="tab" aria-selected="false">Owner Information</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3"
                                                    role="tab" aria-selected="false">Vendor Identity Information</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="tab-v-1" role="tabpanel"
                                                aria-labelledby="tab-v-1-tab">
                                                <div class="row" id="company-row">
                                                    <div class="form-group  col-md-4 mb-25">
                                                        <label for="company_name"
                                                            class="color-dark fs-14 fw-500 align-center">Company
                                                            Name <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="company_name" value="{{ old('company_name') }}"
                                                            id="company_name" placeholder="Company Name" required>
                                                        @if ($errors->has('company_name'))
                                                            <p class="text-danger">{{ $errors->first('company_name') }}
                                                            </p>
                                                        @endif
                                                    </div>


                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="phone"
                                                            class="color-dark fs-14 fw-500 align-center">Contact
                                                            Number <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="phone" value="{{ old('phone') }}" id="phone"
                                                            placeholder="Phone" required>
                                                        @if ($errors->has('phone'))
                                                            <p class="text-danger">{{ $errors->first('phone') }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="email"
                                                            class="color-dark fs-14 fw-500 align-center">Company
                                                            Email
                                                        </label>
                                                        <input type="email"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="email" id="email" value="{{ old('email') }}"
                                                            placeholder="Email Address">
                                                        @if ($errors->has('email'))
                                                            <p class="text-danger">{{ $errors->first('email') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="company_type_id"
                                                            class="color-dark fs-14 fw-500 align-center">Company Type
                                                            <span class="text-danger">*</span></label> 

                                                        <select name="company_type_id" id="company_type_id" class="form-control to-select">
                                                            <option value="">Select Company Type</option>
                                                            <option value="1" @if(old('company_type_id')==1) selected @endif>Private Limited</option>
                                                            <option value="2" @if(old('company_type_id')==2) selected @endif>Proprietorship</option>
                                                            <option value="3" @if(old('company_type_id')==3) selected @endif>Public Limited</option>
                                                            <option value="4" @if(old('company_type_id')==4) selected @endif>Government Organisation</option>
                                                            <option value="5" @if(old('company_type_id')==5) selected @endif>None</option>
                                                        </select>

                                                        @if ($errors->has('company_place'))
                                                            <p class="text-danger">
                                                                {{ $errors->first('company_place') }}</p>
                                                        @endif

                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="account_head_id"
                                                            class="color-dark fs-14 fw-500 align-center">
                                                            Account Head
                                                        </label> 


                                                        <select name="account_head_id" id="account_head_id" class="form-control to-select">
                                                            <option value="">Select Account Head</option>
                                                            <option value="1" @if(old('account_head_id')==1) selected @endif> Cash</option>
                                                            <option value="2" @if(old('account_head_id')==2) selected @endif> Bank</option>
                                                            <option value="3" @if(old('account_head_id')==3) selected @endif> Purchase</option>
                                                        </select>
                                                        @if ($errors->has('account_head_id'))
                                                            <p class="text-danger">
                                                                {{ $errors->first('account_head_id') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="opening_balance"
                                                            class="color-dark fs-14 fw-500 align-center">Opening
                                                            Balance
                                                        </label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="opening_balance" id="opening_balance"
                                                            value="{{ old('opening_balance') }}"
                                                            placeholder="Opening Balance">
                                                        @if ($errors->has('opening_balance'))
                                                            <p class="text-danger">
                                                                {{ $errors->first('opening_balance') }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="form-group  mb-25">
                                                        <label for="address"
                                                            class="color-dark fs-14 fw-500 align-center">Address<span class="text-danger">*</span></label>
                                                        <textarea class="form-control   ip-gray radius-xs b-light" name="address" style="height: 140px;"
                                                            id="address" placeholder="Address">{{ old('address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab-v-2" role="tabpanel"
                                                aria-labelledby="tab-v-2-tab">

                                                <div class="row">

                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_name"
                                                            class="color-dark fs-14 fw-500 align-center">Owner
                                                            Name</label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="owner_name" id="owner_name"
                                                            value="{{ old('owner_name') }}" placeholder="Owner Name">
                                                        @if ($errors->has('owner_name'))
                                                            <p class="text-danger">{{ $errors->first('owner_name') }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_mobile"
                                                            class="color-dark fs-14 fw-500 align-center">Owner
                                                            Mobile</label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="owner_mobile" id="owner_mobile"
                                                            value="{{ old('owner_mobile') }}" placeholder="Owner Mobile">
                                                        @if ($errors->has('owner_mobile'))
                                                            <p class="text-danger">
                                                                {{ $errors->first('owner_mobile') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_email"
                                                            class="color-dark fs-14 fw-500 align-center">Owner
                                                            Email</label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="owner_email" id="owner_email"
                                                            value="{{ old('owner_email') }}" placeholder="Owner Email">
                                                        @if ($errors->has('owner_email'))
                                                            <p class="text-danger">{{ $errors->first('owner_email') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_designation"
                                                            class="color-dark fs-14 fw-500 align-center">Owner
                                                            Designation</label>
                                                            
                                                            <select name="owner_designation" id="owner_designation" class="form-control to-select">
                                                                <option value="">Choose Owner Designation</option>
                                                                <option value="Director" {{ old('owner_designation') == 'Director' ? 'selected' : '' }}>
                                                                    Director</option>
                                                                <option value="Managing Director"
                                                                    {{ old('owner_designation') == 'Managing Director' ? 'selected' : '' }}>
                                                                    Managing Director</option>
                                                                <option value="Deputy Managing Director"
                                                                    {{ old('owner_designation') == 'Deputy Managing Director' ? 'selected' : '' }}>
                                                                    Deputy Managing Director</option>
                                                            </select>
                                                            
                                                     
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_dob"
                                                            class="color-dark fs-14 fw-500 align-center">Date of
                                                            Birth</label>
                                                        <input type="text"
                                                            class="form-control form-control-default   ip-gray radius-xs b-light px-15 datePicker"
                                                            value="{{ old('owner_dob') }}" name="owner_dob"
                                                            id="owner_dob" placeholder="Date of Birth">
                                                        @if ($errors->has('owner_dob'))
                                                            <p class="text-danger">{{ $errors->first('owner_dob') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="owner_address"
                                                            class="color-dark fs-14 fw-500 align-center">Owner
                                                            Address</label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="owner_address" id="owner_address"
                                                            value="{{ old('owner_address') }}"
                                                            placeholder="Owner Address">
                                                        @if ($errors->has('owner_address'))
                                                            <p class="text-danger">
                                                                {{ $errors->first('owner_address') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab-v-3" role="tabpanel"
                                                aria-labelledby="tab-v-3-tab">

                                                <div class="row">
                                                    <div class="form-group col-md-4 mb-25">
                                                        <label for="nid"
                                                            class="color-dark fs-14 fw-500 align-center">National Id
                                                            no.</label>
                                                        <input type="text"
                                                            class="form-control   ip-gray radius-xs b-light px-15"
                                                            name="nid" id="nid" value="{{ old('nid') }}"
                                                            placeholder="Identity Number">
                                                        @if ($errors->has('nid'))
                                                            <p class="text-danger">{{ $errors->first('nid') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4 ">
                                                        <label for="front_image"
                                                            class="color-dark fs-14 fw-500 align-center">Nid Front
                                                            Image</label>
                                                        <div class="account-profile d-flex align-items-center mb-4 ">
                                                            <div class="form-group">
                                                                <x-file-uploader name="front_image"/>

                                                                {{-- <input id="front-image" type="file" accept="image/*"
                                                                    name="front_image" class="file-control"
                                                                    data-preview-element="front-image-preview"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4 ">
                                                        <label for="back_image"
                                                            class="color-dark fs-14 fw-500 align-center"> Nid Back
                                                            Image</label>
                                                        <div class="account-profile d-flex align-items-center mb-4 ">
                                                            <div class="form-group">
                                                                <x-file-uploader name="back_image"/>

                                                                {{-- <input id="back-image" type="file" accept="image/*"
                                                                    name="back_image" class="file-control"
                                                                    data-preview-element="back-image-preview"> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4 ">
                                                        <label for="visiting_card_front"
                                                            class="color-dark fs-14 fw-500 align-center">Visiting
                                                            Card</label>
                                                        <div class="account-profile d-flex align-items-center mb-4 ">
                                                            <div class="form-group">
                                                                <x-file-uploader name="visiting_card_front"/>

                                                                {{-- <input id="visiting-card-front" type="file"
                                                                    accept="image/*" name="visiting_card_front"
                                                                    class="file-control"
                                                                    data-preview-element="visiting-card-front-preview"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4 ">
                                                        <label for="trade_license"
                                                            class="color-dark fs-14 fw-500 align-center">Trade
                                                            License</label>
                                                        <div class="account-profile d-flex align-items-center mb-4 ">
                                                            <div class="form-group">
                                                                <x-file-uploader name="trade_license"/>
                                                                {{-- <input id="trade-license" type="file" accept="image/*"
                                                                    name="trade_license" class="file-control"
                                                                    data-preview-element="trade-license-preview"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4 ">
                                                        <label for="signature"
                                                            class="color-dark fs-14 fw-500 align-center">Signature</label>
                                                        <div class="account-profile d-flex align-items-center mb-4 ">
                                                            <div class="form-group">
                                                                <x-file-uploader name="signature"/>
                                                                {{-- <input id="signature" type="file" accept="image/*"
                                                                    name="signature" class="file-control"
                                                                    data-preview-element="signature-preview"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit"
                                                class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
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
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>

@endSection
