@section('title', 'Edit Employee ')
@section('description', 'Edit Employee ')
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
                                        {{ trans('menu.edit-employee-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.employees.index'))
                                <a href="{{ route('hrm.employees.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-employee-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title mt-4 mb-4">Employee Information</h2> 
                                <div class="row"> 
                                    {{-- start  --}}

                                    <div class="dm-tab tab-horizontal">
                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="employee_information-tab" data-bs-toggle="tab"
                                                    href="#employee_information" role="tab" aria-selected="true">Employee Information </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " id="job_status-tab" data-bs-toggle="tab"
                                                    href="#job_status" role="tab" aria-selected="false">Job Status</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                    href="#contact" role="tab" aria-selected="false">Contact</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                                    href="#documents" role="tab" aria-selected="false">Document</a>
                                            </li> 
                                            <li class="nav-item">
                                                <a class="nav-link" id="educational-tab" data-bs-toggle="tab"
                                                    href="#educational" role="tab" aria-selected="false">Education</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " id="employeement_experience-tab" data-bs-toggle="tab"
                                                    href="#employeement_experience" role="tab" aria-selected="false">Employeement</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " id="family_contact-tab" data-bs-toggle="tab"
                                                    href="#family_contact" role="tab" aria-selected="false">Family Contact</a>
                                            </li>
                                              
                                            <li class="nav-item">
                                                <a class="nav-link" id="bank-tab" data-bs-toggle="tab"
                                                    href="#bank" role="tab" aria-selected="false">Bank</a>
                                            </li> 
                                            <li class="nav-item">
                                                <a class="nav-link" id="tax-tab" data-bs-toggle="tab"
                                                    href="#tax" role="tab" aria-selected="false">Tax and
                                                    Legal</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="system-tab" data-bs-toggle="tab"
                                                    href="#system" role="tab" aria-selected="false">System</a>
                                            </li>
                                   

                                        </ul>
                                        <div class="tab-content">

                                            <div class="tab-pane fade show active" id="employee_information" role="tabpanel" aria-labelledby="employee_information-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="employee_information">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="user_branch_id"
                                                                    class="color-dark fs-14 fw-500 align-center">Branch<span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="user_branch_id" id="user_branch_id"
                                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select">
                                                                    <option value="">Select</option>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch->id }}"
                                                                            {{ old('user_branch_id', optional($employee->employementDetail)->branch_id) == $branch->id ? 'selected' : '' }}>
                                                                            {{ $branch->name }} ({{ @$branch->branchType->name }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="full_name" class="color-dark fs-14 fw-500 align-center">Full
                                                                    Name<span class="text-danger">*</span> </label>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="full_name" name="full_name"
                                                                    value="{{ old('full_name', $employee->full_name) }}">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="father_name" class="color-dark fs-14 fw-500 align-center">Father's
                                                                    Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="father_name" name="father_name"
                                                                    value="{{ old('father_name', $employee->father_name) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="mother_name" class="color-dark fs-14 fw-500 align-center">Mother's
                                                                    Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="mother_name" name="mother_name"
                                                                    value="{{ old('mother_name', $employee->mother_name) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label class="color-dark fs-14 fw-500 align-center">Gender<span
                                                                        class="text-danger">*</span></label><br>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="gender"
                                                                        @if (old('gender', $employee->gender) == 'male') checked @endif id="gender_male"
                                                                        value="male">
                                                                    <label class="form-check-label" for="gender_male">Male</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="gender"
                                                                        @if (old('gender', $employee->gender) == 'female') checked @endif id="gender_female"
                                                                        value="female">
                                                                    <label class="form-check-label" for="gender_female">Female</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">


                                                            <div class="form-group mb-0 form-group-calender">
                                                                <label for="date_of_birth" class="color-dark fs-14 fw-500 align-center">Date of
                                                                    Birth</label>
                                                                <div class="position-relative">
                                                                    <input type="text"
                                                                        class="form-control form-control-default ip-gray radius-xs b-light px-15 flatdate"
                                                                        value="{{ old('dob', $employee->date_of_birth) }}" name="date_of_birth"
                                                                        id="date_of_birth" placeholder="dd/mm/yyyy">
                                                                    <a href="#"><img src="{{ asset('assets/img/svg/calendar.svg') }}"
                                                                            alt="calendar" class="svg"></a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="email_address" class="color-dark fs-14 fw-500 align-center">Email
                                                                    Address<span class="text-danger">*</span></label>
                                                                <input type="email" class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="email_address" name="email_address"
                                                                    value="{{ old('email_address', $employee->email_address) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="country"
                                                                    class="color-dark fs-14 fw-500 align-center">Country<span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-control ip-gray radius-xs b-light px-15 tom-select"
                                                                    id="country" name="country">

                                                                    {{-- @php
                                                                        $ff = Faker\Factory::create()->unique();
                                                                    @endphp
                                                                    @for ($i = 0; $i < 195; $i++)
                                                                        @php
                                                                            $country = $ff->country();
                                                                        @endphp
                                                                        <option value="{{ $country }}"
                                                                            @if (old('country') == $country) selected @endif>
                                                                            {{ $country }}</option>
                                                                    @endfor --}}
                                                                    <option value="">Select Country</option>
                                                                    @foreach (cuntriesNames() as $country)
                                                                        <option value="{{ $country }}"
                                                                            @if (old('country', $employee->country) == $country) selected @endif>
                                                                            {{ $country }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="city" class="color-dark fs-14 fw-500 align-center">City<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="city" name="city"
                                                                    value="{{ old('city', $employee->city) }}">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="blood_group" class="color-dark fs-14 fw-500 align-center">Blood
                                                                    Group<span class="text-danger">*</span></label> 
                                                                <select  id="blood_group"  name="blood_group" class="form-control">
                                                                    <option value="">Select Blood Group</option>
                                                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                                                                        <option value="{{ $group }}" 
                                                                            {{ old('blood_group', $employee->blood_group ?? '') == $group ? 'selected' : '' }}>
                                                                            {{ $group }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="religion"
                                                                    class="color-dark fs-14 fw-500 align-center">Religion<span
                                                                        class="text-danger">*</span></label>
                                                                <select
                                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select tom-select"
                                                                    id="religion" name="religion">
                                                                    <option value="">Select</option>
                                                                    <option value="islam" @if (old('religion', $employee->religion) == 'islam') selected @endif>
                                                                        Islam</option>
                                                                    <option value="hindu" @if (old('religion', $employee->religion) == 'hindu') selected @endif>
                                                                        Hindu</option>
                                                                    <option value="christian"
                                                                        @if (old('religion', $employee->religion) == 'christian') selected @endif>Christian</option>
                                                                    <option value="buddhist" @if (old('religion', $employee->religion) == 'buddhist') selected @endif>
                                                                        Buddhist</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="marital_status"
                                                                    class="color-dark fs-14 fw-500 align-center">Marital Status</label>
                                                                <select class="form-control ip-gray radius-xs b-light px-15 tom-select"
                                                                    id="marital_status" name="marital_status">
                                                                    <option value="">Select</option>
                                                                    <option value="single" @if (old('marital_status', $employee->marital_status) == 'single') selected @endif>
                                                                        Single</option>
                                                                    <option value="married" @if (old('marital_status', $employee->marital_status) == 'married') selected @endif>
                                                                        Married</option>
                                                                    <option value="divorced"
                                                                        @if (old('marital_status', $employee->marital_status) == 'divorced') selected @endif>Divorced</option>
                                                                    <!-- Add other marital statuses -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="national_id"
                                                                    class="color-dark fs-14 fw-500 align-center">NID <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15 file-control"
                                                                    id="national_id" name="national_id"
                                                                    value="{{ old('national_id', $employee->national_id) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="card_no"
                                                                    class="color-dark fs-14 fw-500 align-center">Employee
                                                                    ID/Number <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="card_no" name="card_no"
                                                                    value="{{ old('card_no', optional($employee->employementDetail)->card_no) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="software_access"
                                                                    class="color-dark fs-14 fw-500 align-center">Software/Tool
                                                                    Access</label>
                                                                <textarea class="form-control ip-gray radius-xs b-light px-15" id="software_access" name="software_access"
                                                                    rows="3">{{ old('software_access', $employee->software_access) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="additional_notes"
                                                                    class="color-dark fs-14 fw-500 align-center">Additional
                                                                    Notes</label>
                                                                <textarea class="form-control ip-gray radius-xs b-light px-15" id="additional_notes" name="additional_notes"
                                                                    rows="3">{{ old('additional_notes, $employee->additional_notes') }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">

                                                            <div class="form-group">
                                                                <label for="photograph"
                                                                    class="color-dark fs-14 fw-500 align-center">Photograph<span
                                                                        class="text-danger">*</span></label>
                                                                <x-file-uploader :value="$employee->photograph" name="photograph" />
                                                                {{-- <input type="file" accept="image/*" id="photograph" name="photograph"
                                                                    value="{{ old('photograph', $employee->photograph) }}" class="file-control"> --}}
                                                            </div>
                                                        </div>
                                                        
                                                       
                                                        
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="documents">
                                                    <div class="row">
                                                       
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="resume"
                                                                    class="color-dark fs-14 fw-500 align-center">Resume/CV
                                                                    </label>
                                                                <x-file-uploader :value="$employee->resume" name="resume" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="resume" name="resume"
                                                                    value="{{ old('resume') }}"> --}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="front_image"
                                                                    class="color-dark fs-14 fw-500 align-center">NID Front
                                                                    Image </label>
                                                                <x-file-uploader :value="$employee->front_image" name="front_image" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="front_image" name="front_image"
                                                                    value="{{ old('front_image') }}" class="file-control"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="back_image"
                                                                    class="color-dark fs-14 fw-500 align-center">NID Back
                                                                    Image </label>
                                                                <x-file-uploader :value="$employee->back_image" name="back_image" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="back_image" name="back_image"
                                                                    value="{{ old('back_image') }}" class="file-control"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="signature"
                                                                    class="color-dark fs-14 fw-500 align-center">Signature
                                                                    </label>
                                                                <x-file-uploader :value="$employee->signature" name="signature" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="signature" name="signature"
                                                                    value="{{ old('signature') }}"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="address_proof"
                                                                    class="color-dark fs-14 fw-500 align-center">Address
                                                                    Proof</label>
                                                                <x-file-uploader :value="$employee->address_proof" name="address_proof" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="address_proof" name="address_proof"
                                                                    value="{{ old('address_proof') }}"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="other_documents"
                                                                    class="color-dark fs-14 fw-500 align-center">Other
                                                                    Documents</label>
                                                                <x-file-uploader :value="$employee->other_documents" name="other_documents" />
                                                                {{-- <input type="file" class="form-control-file file-control"
                                                                    id="other_documents" name="other_documents"
                                                                    value="{{ old('other_documents') }}"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="contact">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-4">

                                                                <div class="form-group">
                                                                    <label for="office_phone" class="color-dark fs-14 fw-500 align-center">Office
                                                                        Phone</label>
                                                                    <input type="tel" class="form-control ip-gray radius-xs b-light px-15"
                                                                        id="office_phone" name="office_phone"
                                                                        value="{{ old('office_phone', $employee->office_phone) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-4">

                                                                <div class="form-group">
                                                                    <label for="personal_mobile"
                                                                        class="color-dark fs-14 fw-500 align-center">Personal Mobile<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="tel" class="form-control ip-gray radius-xs b-light px-15"
                                                                        id="personal_mobile" name="personal_mobile"
                                                                        value="{{ old('personal_mobile', $employee->personal_mobile) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-4">

                                                                <div class="form-group">
                                                                    <label for="alternate_phone"
                                                                        class="color-dark fs-14 fw-500 align-center">Alternate Phone</label>
                                                                    <input type="tel" class="form-control ip-gray radius-xs b-light px-15"
                                                                        id="alternate_phone" name="alternate_phone"
                                                                        value="{{ old('alternate_phone', $employee->alternate_phone) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-4">

                                                                <div class="form-group">
                                                                    <label for="present_address"
                                                                        class="color-dark fs-14 fw-500 align-center">Present Address<span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea class="form-control ip-gray radius-xs b-light px-15" id="present_address" name="present_address"
                                                                        rows="3">{{ old('present_address', $employee->present_address) }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-4">

                                                                <div class="form-group">
                                                                    <label for="permanent_address"
                                                                        class="color-dark fs-14 fw-500 align-center">Permanent Address<span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea class="form-control ip-gray radius-xs b-light px-15" id="permanent_address" name="permanent_address"
                                                                        rows="3">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-4">
                                                                <div class="form-group">
                                                                    <label for="email_accounts"
                                                                        class="color-dark fs-14 fw-500 align-center">Personal Email</label>
                                                                    <input type="text"
                                                                        class="form-control ip-gray radius-xs b-light px-15"
                                                                        id="email_accounts" name="email_accounts"
                                                                        value="{{ old('email_accounts', $employee->email_accounts) }}"  autocomplete="off" >
                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div> 

                                            <div class="tab-pane fade" id="job_status" role="tabpanel" aria-labelledby="job_status-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="job_status">
                                                    <div class="row mb-4 employement-details"
                                                        style="border-bottom: 1px solid #dee2e6">
                                                        
                                                        <div class="col-md-4 mb-4 d-none">
                                                            <div class="form-group">
                                                                <label for="user_branch_id"
                                                                    class="color-dark fs-14 fw-500 align-center">Branch<span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="user_branch_id" id="user_branch_id"
                                                                    class="form-control ip-gray radius-xs b-light px-15 tom-select">
                                                                    <option value="">Select</option>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch->id }}"
                                                                            {{ old('user_branch_id', optional($employee->employementDetail)->branch_id) == $branch->id ? 'selected' : '' }}>
                                                                            {{ $branch->name }} ({{ @$branch->branchType->name }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="date_of_joining"
                                                                    class="color-dark fs-14 fw-500 align-center">Date of
                                                                    Joining <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control flatdate ip-gray radius-xs b-light px-15"
                                                                    id="date_of_joining" name="date_of_joining"
                                                                    value="{{ old('date_of_joining', optional($employee->employementDetail)->date_of_joining) }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="department"
                                                                    class="color-dark fs-14 fw-500 align-center">Department<span
                                                                        class="text-danger">*</span></label>
                                                                <select name="department_id" id="department"
                                                                    class="form-control tom-select">
                                                                    <option value="">Select Department</option>
                                                                    @foreach ($departments as $department)
                                                                        <option value="{{ $department->id }}"
                                                                            {{ old('department_id', optional($employee->employementDetail)->department_id) == $department->id ? 'selected' : '' }}>
                                                                            {{ $department->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="designation"
                                                                    class="color-dark fs-14 fw-500 align-center">Designation/Job
                                                                    Title <span class="text-danger">*</span></label>
                                                                <select name="designation_id" id="designation_id"
                                                                    class="form-control tom-select">
                                                                    <option value="">Select Designation</option>
                                                                    @foreach ($designations as $designation)
                                                                        <option value="{{ $designation->id }}"
                                                                            {{ old('designation_id', optional($employee->employementDetail)->designation_id) == $designation->id ? 'selected' : '' }}>
                                                                            {{ $designation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="employment_type"
                                                                    class="color-dark fs-14 fw-500 align-center">Employment
                                                                    Type <span class="text-danger">*</span></label>
                                                                <select class="form-control tom-select" id="employment_type"
                                                                    name="employment_type_id">
                                                                    <option value="">Select</option>
                                                                    <option value="1"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '1') selected @endif>
                                                                        Casual</option>
                                                                    <option value="2"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '2') selected @endif>
                                                                        Contractual</option>
                                                                    <option value="3"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '3') selected @endif>
                                                                        Not Defined</option>
                                                                    <option value="4"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '4') selected @endif>
                                                                        Permanent</option>
                                                                    <option value="5"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '5') selected @endif>
                                                                        Probationary</option>
                                                                    <option value="6"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '6') selected @endif>
                                                                        Suspended</option>
                                                                    <option value="7"
                                                                        @if (old('employment_type_id', optional($employee->employementDetail)->employment_type_id) == '7') selected @endif>
                                                                        Trainee</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="supervisor"
                                                                    class="color-dark fs-14 fw-500 align-center">Supervisor/Manager
                                                                    </label>
                                                                <select name="supervisor" id="supervisor"
                                                                    class="form-control tom-select">
                                                                    <option value="">Select Supervisor</option>
                                                                    @foreach ($employees as $value)
                                                                        <option value="{{ $value->id }}"
                                                                            {{ old('supervisor', $employee->id) == $value->id ? 'selected' : '' }}>
                                                                            {{ $value->full_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="supervisor"
                                                                    class="color-dark fs-14 fw-500 align-center">Job Status
                                                                    </label>
                                                                <select name="status" id="status" class="form-control tom-select">
                                                                    <option value=""></option>
                                                                    <option value="1"
                                                                        @if (old('status', $employee->status) == '1') selected @endif>
                                                                        Active
                                                                    </option>
                                                                    <option value="0"
                                                                        @if (old('status', $employee->status) == '0') selected @endif>
                                                                        Inactive
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>

                                                {{-- <div class="row mb-4 d-flex justify-content-end">
                                                    <button class="btn btn-primary btn-xs add-employement-details"
                                                        type="button">
                                                        <i class="fa fa-plus">Add More</i>
                                                    </button>
                                                </div> --}}
                                            </div>

                                            <div class="tab-pane fade" id="educational" role="tabpanel" aria-labelledby="educational-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="educational">
                                                    <div id="education-container">
                                                        @forelse ($employee->educationDetails as $key => $educationDetail)
                                                            <div class="row education-details mb-4"
                                                                style="border-bottom: 1px solid #dee2e6">
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Degree Title</label>
                                                                        <input type="text" class="form-control"
                                                                            name="degree_title[]"
                                                                            value="{{ $educationDetail->degree_title }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Institute Name</label>
                                                                        <input type="text" class="form-control"
                                                                            name="institute_name[]"
                                                                            value="{{ $educationDetail->institute_name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Group</label>
                                                                        <input type="text" class="form-control"
                                                                            name="group[]"
                                                                            value="{{ $educationDetail->group }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Duration</label>
                                                                        <input type="text" class="form-control"
                                                                            name="duration[]"
                                                                            value="{{ $educationDetail->duration }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label>Passing Year</label>
                                                                        <input type="text" class="form-control"
                                                                            name="passing_year[]"
                                                                            value="{{ $educationDetail->passing_year }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label>Result</label>
                                                                        <input type="text" class="form-control"
                                                                            name="result[]"
                                                                            value="{{ $educationDetail->result }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Upload Certificate</label>
                                                                        <x-file-uploader loadLater
                                                                            name="certificate_upload_{{ $key }}" :value="$educationDetail->certificate_upload"
                                                                            id="certificate_upload_{{ $key }}" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 d-flex align-items-end">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-xs remove-education-details">
                                                                        <i class="fa fa-trash"></i> Remove
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            {{-- If no education exists, we’ll add one via JS --}}
                                                        @endforelse
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-sm btn-primary add-education-details mt-2">
                                                        + Add Education
                                                    </button> 
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                                {{-- HIDDEN TEMPLATE (won’t submit because <template>) --}}
                                                <template id="education-template">
                                                    <div class="row education-details mb-4"
                                                        style="border-bottom: 1px solid #dee2e6">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Degree Title</label>
                                                                <input type="text" class="form-control"
                                                                    name="degree_title[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Institute Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="institute_name[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Group</label>
                                                                <input type="text" class="form-control"
                                                                    name="group[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Duration</label>
                                                                <input type="text" class="form-control"
                                                                    name="duration[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Passing Year</label>
                                                                <input type="text" class="form-control"
                                                                    name="passing_year[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Result</label>
                                                                <input type="text" class="form-control"
                                                                    name="result[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Upload Certificate</label>
                                                                <x-file-uploader loadLater name="certificate_upload_0"
                                                                    id="certificate_upload_0" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="button"
                                                                class="btn btn-danger btn-xs remove-education-details">
                                                                <i class="fa fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>

                                            </div>



                                            <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="bank">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="bank_name"
                                                                    class="color-dark fs-14 fw-500 align-center">Bank
                                                                    Name </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="bank_name" name="bank_name"
                                                                    value="{{ old('bank_name', $employee->bank_name) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="account_holder_name"
                                                                    class="color-dark fs-14 fw-500 align-center">Account Holder
                                                                    Name </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="account_holder_name" name="account_holder_name"
                                                                    value="{{ old('account_holder_name', $employee->account_holder_name) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="account_number"
                                                                    class="color-dark fs-14 fw-500 align-center">Account
                                                                    Number </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="account_number" name="account_number"
                                                                    value="{{ old('account_number', $employee->account_number) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="branch"
                                                                    class="color-dark fs-14 fw-500 align-center">Branch</label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="branch" name="bank_branch"
                                                                    value="{{ old('branch', $employee->bank_branch) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="routing_number"
                                                                    class="color-dark fs-14 fw-500 align-center">Routing
                                                                    Number </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="routing_number" name="routing_number"
                                                                    value="{{ old('routing_number', $employee->routing_number) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div>

                                           

                                            <div class="tab-pane fade" id="tax" role="tabpanel" aria-labelledby="tax-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="tax">
                                                    {{-- <h2>Tax and Legal Information</h2> --}}
                                                    <div class="row">
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="etin_number"
                                                                    class="color-dark fs-14 fw-500 align-center">eTIN
                                                                    Number </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="etin_number" name="etin_number"
                                                                    value="{{ old('etin_number', $employee->etin_number) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-group">
                                                                <label for="epf_number"
                                                                    class="color-dark fs-14 fw-500 align-center">Employee
                                                                    Provident Fund (EPF) Number</label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="epf_number" name="epf_number"
                                                                    value="{{ old('epf_number', $employee->epf_number) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="employeement_experience" role="tabpanel" aria-labelledby="employeement_experience-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="employeement_experience">
                                                    <div id="employeement-experience-container">
                                                        @forelse ($employee->employeementExperience as $key => $employeementExperience)
                                                        <div class="row employeement-experience-details mb-4"
                                                            style="border-bottom: 1px solid #dee2e6">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Company Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="company_name[]" value="{{ $employeementExperience->company_name }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Address</label>
                                                                    <input type="text" class="form-control"
                                                                        name="address[]" value="{{ $employeementExperience->address }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Designation</label>
                                                                    <input type="text" class="form-control"
                                                                        name="designation[]" value="{{ $employeementExperience->designation }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Start Date</label>
                                                                    <input type="text" class="form-control flatdate"
                                                                        name="start_date[]" value="{{ $employeementExperience->start_date }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>End Date</label>
                                                                    <input type="text" class="form-control flatdate"
                                                                        name="end_date[]" value="{{ $employeementExperience->end_date }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Salary</label>
                                                                    <input type="text" class="form-control"
                                                                        name="salary[]" value="{{ $employeementExperience->salary }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Remarks</label>
                                                                    <input type="text" class="form-control"
                                                                        name="remarks[]" value="{{ $employeementExperience->remarks }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-xs remove-employeement-experience-details">
                                                                    <i class="fa fa-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @empty
                                                            {{-- If no education exists, we’ll add one via JS --}}
                                                        @endforelse
                                                    </div>

                                                    
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary add-employeement-experience-details mt-2">
                                                        + Add Employeement
                                                    </button>
                                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                                {{-- HIDDEN TEMPLATE (won’t submit because <template>) --}}
                                                <template id="employeement-experience-template">
                                                    <div class="row employeement-experience-details mb-4"
                                                        style="border-bottom: 1px solid #dee2e6">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Company Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="company_name[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Address</label>
                                                                <input type="text" class="form-control"
                                                                    name="address[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Designation</label>
                                                                <input type="text" class="form-control"
                                                                    name="designation[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Start Date</label>
                                                                <input type="text" class="form-control flatdate"
                                                                    name="start_date[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>End Date</label>
                                                                <input type="text" class="form-control flatdate"
                                                                    name="end_date[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Salary</label>
                                                                <input type="text" class="form-control"
                                                                    name="salary[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Remarks</label>
                                                                <input type="text" class="form-control"
                                                                    name="remarks[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="button"
                                                                class="btn btn-danger btn-xs remove-employeement-experience-details">
                                                                <i class="fa fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template> 
                                            </div>
                                            <div class="tab-pane fade" id="family_contact" role="tabpanel" aria-labelledby="family_contact-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <input type="hidden" name="tab_type" value="family_contact">
                                                    <div id="employee-family-contact-container">
                                                        @forelse ($employee->employeeFamilyContact as $key => $employeeFamilyContact)
                                                        <div class="row employee-family-contact-details mb-4"
                                                            style="border-bottom: 1px solid #dee2e6">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name[]" value="{{ $employeeFamilyContact->name }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Relationship</label> 
                                                                    <select name="relationship[]" id="relationship" class="form-control">
                                                                        
                                                                        <option value="father" @if (old('relationship', $employeeFamilyContact->relationship) == 'father') selected @endif  >Father</option>
                                                                        <option value="mother" @if (old('relationship', $employeeFamilyContact->relationship) == 'mother') selected @endif >Mother</option>
                                                                        <option value="brother" @if (old('relationship', $employeeFamilyContact->relationship) == 'brother') selected @endif >Brother</option>
                                                                        <option value="sister" @if (old('relationship', $employeeFamilyContact->relationship) == 'sister') selected @endif >Sister</option>
    
                                                                        <option value="son" @if (old('relationship', $employeeFamilyContact->relationship) == 'son') selected @endif >Son</option>
                                                                        <option value="daughter" @if (old('relationship', $employeeFamilyContact->relationship) == 'daughter') selected @endif >Daughter</option>
                                                                        <option value="husband" @if (old('relationship', $employeeFamilyContact->relationship) == 'husband') selected @endif >Husband</option>
                                                                        <option value="wife" @if (old('relationship', $employeeFamilyContact->relationship) == 'wife') selected @endif >Wife</option>

                                                                        <option value="nephew" @if (old('relationship', $employeeFamilyContact->relationship) == 'nephew') selected @endif >Nephew</option>
                                                                        <option value="niece" @if (old('relationship', $employeeFamilyContact->relationship) == 'niece') selected @endif >Niece</option>
                                                                        <option value="uncle" @if (old('relationship', $employeeFamilyContact->relationship) == 'uncle') selected @endif >Uncle</option>
                                                                        <option value="aunt" @if (old('relationship', $employeeFamilyContact->relationship) == 'aunt') selected @endif >Aunt</option>

                                                                        <option value="cousin" @if (old('relationship', $employeeFamilyContact->relationship) == 'cousin') selected @endif >Cousin</option>
                                                                        <option value="friend" @if (old('relationship', $employeeFamilyContact->relationship) == 'friend') selected @endif >Friend</option>
                                                                        <option value="others" @if (old('relationship', $employeeFamilyContact->relationship) == 'others') selected @endif >Others</option>
                                                                        <option value="nominee" @if (old('relationship', $employeeFamilyContact->relationship) == 'nominee') selected @endif >Nominee</option>
                                                                </select>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Gender</label> 
                                                                    <select name="gender[]" id="gender" class="form-control"> 
                                                                        <option value="male"  @if (old('gender', $employeeFamilyContact->gender) == 'male') selected @endif >Male</option>
                                                                        <option value="female" @if (old('gender', $employeeFamilyContact->gender) == 'female') selected @endif >Female</option>
                                                                        <option value="other" @if (old('gender', $employeeFamilyContact->gender) == 'other') selected @endif >Other</option>
                                                                    </select>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>NID</label>
                                                                    <input type="text" class="form-control"
                                                                        name="nid[]" value="{{ $employeeFamilyContact->nid }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Profession</label>
                                                                    <input type="text" class="form-control"
                                                                        name="profession[]" value="{{ $employeeFamilyContact->profession }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Contact No</label>
                                                                    <input type="text" class="form-control"
                                                                        name="contact_no[]" value="{{ $employeeFamilyContact->contact_no }}">
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-2 d-flex align-items-end">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-xs remove-employee-family-contact-details">
                                                                    <i class="fa fa-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @empty
                                                            {{-- If no education exists, we’ll add one via JS --}}
                                                        @endforelse
                                                    </div>

                                                    
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary add-employee-family-contact-details mt-2">
                                                        + Add Contact
                                                    </button>
                                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                               
                                                </form>

                                                {{-- HIDDEN TEMPLATE (won’t submit because <template>) --}}
                                                <template id="employee-family-contact-template">
                                                    <div class="row employee-family-contact-details mb-4"
                                                        style="border-bottom: 1px solid #dee2e6">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="name[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Relationship</label> 

                                                                <select name="relationship[]" id="relationship" class="form-control">
                                                                    <option value="">Select Relation</option>
                                                                    <option value="father" {{ old('relationship') == 'father' ? 'selected' : '' }}>Father</option>
                                                                    <option value="mother" {{ old('relationship') == 'mother' ? 'selected' : '' }}>Mother</option>
                                                                    <option value="brother" {{ old('relationship') == 'brother' ? 'selected' : '' }}>Brother</option>
                                                                    <option value="sister" {{ old('relationship') == 'sister' ? 'selected' : '' }}>Sister</option>
 
                                                                    <option value="son" {{ old('relationship') == 'son' ? 'selected' : '' }}>Son</option>
                                                                    <option value="daughter" {{ old('relationship') == 'daughter' ? 'selected' : '' }}>Daughter</option>
                                                                    <option value="husband" {{ old('relationship') == 'husband' ? 'selected' : '' }}>Husband</option>
                                                                    <option value="wife" {{ old('relationship') == 'wife' ? 'selected' : '' }}>Wife</option>

                                                                    <option value="nephew" {{ old('relationship') == 'nephew' ? 'selected' : '' }}>Nephew</option>
                                                                    <option value="niece" {{ old('relationship') == 'niece' ? 'selected' : '' }}>Niece</option>
                                                                    <option value="uncle" {{ old('relationship') == 'uncle' ? 'selected' : '' }}>Uncle</option>
                                                                    <option value="aunt" {{ old('relationship') == 'aunt' ? 'selected' : '' }}>Aunt</option>

                                                                    <option value="cousin" {{ old('relationship') == 'cousin' ? 'selected' : '' }}>Cousin</option>
                                                                    <option value="friend" {{ old('friend') == 'friend' ? 'selected' : '' }}>Friend</option>
                                                                    <option value="others" {{ old('relationship') == 'others' ? 'selected' : '' }}>Others</option>
                                                                    <option value="nominee" {{ old('relationship') == 'nominee' ? 'selected' : '' }}>Nominee</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Gender</label>
                                                                <select name="gender[]" id="gender" class="form-control">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>NID</label>
                                                                <input type="text" class="form-control"
                                                                    name="nid[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Profession</label>
                                                                <input type="text" class="form-control"
                                                                    name="profession[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Contact No</label>
                                                                <input type="text" class="form-control"
                                                                    name="contact_no[]">
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="button"
                                                                class="btn btn-danger btn-xs remove-employee-family-contact-details">
                                                                <i class="fa fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template> 
                                            </div>
                                            

                                            <div class="tab-pane fade" id="system" role="tabpanel" aria-labelledby="system-tab">
                                                <form method="POST" action="{{ route('hrm.employees.update', $employee->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT') 
                                                    <input type="hidden" name="tab_type" value="system">
                                                    <div class="row">
                                                        <div class="col-md-3 mb-3">
                                                            <div class="form-group">
                                                                <label for="system_username"
                                                                    class="color-dark fs-14 fw-500 align-center">System
                                                                    Username </label>
                                                                <input type="text"
                                                                    class="form-control ip-gray radius-xs b-light px-15"
                                                                    id="system_username" name="system_username"
                                                                    value="{{ old('system_username', $employee->user->email ?? '') }}"  autocomplete="off" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <div class="form-group">
                                                                <label for="system_password" class="color-dark fs-14 fw-500 align-center">
                                                                    System Password <span class="text-danger">*</span>
                                                                </label>
                                                                <div class="password-container" style="position: relative;">
                                                                    <input type="password"  autocomplete="new-password"  class="form-control ip-gray radius-xs b-light px-15" id="system_password" name="system_password" value="{{ old('system_password') }}">
                                                                    <span class="toggle-password" style="top: 50%; transform: translateY(-50%); right: 10px; position: absolute; ">
                                                                        <i class="fas fa-eye"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <script>
                                                            document.querySelector('.toggle-password').addEventListener('click', function () {
                                                                const passwordInput = document.getElementById('system_password');
                                                                const passwordIcon = this.querySelector('i');
                                                        
                                                                if (passwordInput.type === 'password') {
                                                                    passwordInput.type = 'text';
                                                                    passwordIcon.classList.remove('fa-eye');
                                                                    passwordIcon.classList.add('fa-eye-slash');
                                                                } else {
                                                                    passwordInput.type = 'password';
                                                                    passwordIcon.classList.remove('fa-eye-slash');
                                                                    passwordIcon.classList.add('fa-eye');
                                                                }
                                                            });
                                                        </script>
                                                         
                                                        <div class="col-md-3 mb-3">
                                                            <div class="form-group">
                                                                <label for="user_status"
                                                                    class="color-dark fs-14 fw-500 align-center">Status</label>
                                                                    <select name="user_status" id="user_status" class="form-control tom-select">
                                                                        <option value=""></option>
                                                                        <option value="active"
                                                                            @if (old('user_status', $employee->user->user_status) == 'active') selected @endif>
                                                                            Active
                                                                        </option>
                                                                        <option value="inactive"
                                                                            @if (old('user_status', $employee->user->user_status) == 'inactive') selected @endif>
                                                                            Inactive
                                                                        </option> 
                                                                    </select>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                   <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start" style="padding: 40px;">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
 

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
 
        var employmentDetailsRow = $(".employement-details").clone();

        employmentDetailsRow.find("input").each(function() {
            $(this).val("");
        });

        employmentDetailsRow.find("select:selected").each(function() {
            $(this).prop('selected', false);
        });

        employmentDetailsRow.find("input:checked").each(function() {
            $(this).prop('checked', false);
        });

        $(document).on('click', '.add-employement-details', function() {
            const newRow = employmentDetailsRow.clone();
            newRow.find(".tom-select").each(function() {
                new TomSelect(this, {});
            })
            $(".employement-details").after(newRow);
        })
    </script>
    <script>
        $(document).ready(function() {

            
            var activeTab = "{{ session('tab') }}";

            if(activeTab){
                $('#'+activeTab+'-tab').tab('show');
            }
            
            let educationCounter = 0;

            // Init existing uploaders
            @foreach ($employee->educationDetails as $key => $educationDetail)
                initializeFileUploader_certificate_upload_{{ $key }}_certificate_upload_{{ $key }}
                    ();
                educationCounter++;
            @endforeach

            // If none exists, add one blank
            @if ($employee->educationDetails->isEmpty())
                $(".add-education-details").trigger("click");
            @endif

            
            // Add new education row
            $(document).on("click", ".add-education-details", function() {
                const template = document.querySelector("#education-template");
                const $template = $(template.content.cloneNode(true));
                const counter = educationCounter++;

                // Update file uploader id/name
                const newUploaderId = `certificate_upload_${counter}`;
                $template.find("#certificate_upload_0")
                    .attr("id", newUploaderId)
                    .attr("name", newUploaderId)
                    .removeClass()
                    .addClass(newUploaderId);

                $template.find("#hidden-input-certificate_upload_0")
                    .attr("name", newUploaderId);

                // Append
                $("#education-container").append($template);

                // Init uploader for new row
                initializeFileUploader_certificate_upload_0_certificate_upload_0(newUploaderId);
            });

            // Remove row
            $(document).on("click", ".remove-education-details", function() {
                $(this).closest(".education-details").remove();
            });


            let employeementcounter = 0;

            // Init existing uploaders
            @foreach ($employee->employeementExperience as $key => $employeementExperience) 
                employeementcounter++;
            @endforeach

            // If none exists, add one blank
            @if ($employee->employeementExperience->isEmpty())
                $(".add-employeement-experience-details").trigger("click");
            @endif
      
 

            // Add new employeement-experience- row
            $(document).on("click", ".add-employeement-experience-details", function() {
                const template = document.querySelector("#employeement-experience-template");
                const $template = $(template.content.cloneNode(true));
                const counter = employeementcounter++;
  
                // Append
                $("#employeement-experience-container").append($template);
  
                
            });

            // Remove row
            $(document).on("click", ".remove-employeement-experience-details", function() {
                $(this).closest(".employeement-experience-details").remove();
            });


            let employeecontactcounter = 0;

            // Init existing uploaders
            @foreach ($employee->employeeFamilyContact as $key => $employeeFamilyContact) 
                employeecontactcounter++;
            @endforeach

            // If none exists, add one blank
            @if ($employee->employeeFamilyContact->isEmpty())
                $(".add-employee-family-contact-details ").trigger("click");
            @endif
      
 

            // Add new employee family contact- row
            $(document).on("click", ".add-employee-family-contact-details ", function() {
                const template = document.querySelector("#employee-family-contact-template");
                const $template = $(template.content.cloneNode(true));
                const counter = employeecontactcounter++;
  
                // Append
                $("#employee-family-contact-container").append($template);
  
                
            });

            // Remove row
            $(document).on("click", ".remove-employee-family-contact-details", function() {
                $(this).closest(".employee-family-contact-details").remove();
            });

            
        });


        
    </script>

@endSection
