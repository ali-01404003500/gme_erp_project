@section('title', 'Add Global Setting')
@section('description', 'Add Global Setting')
@extends('layout.app')
@section('page-head')
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Global Setting') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center user-member__title mb-30">
                        <h4 class="text-capitalize">{{ trans('Global Setting') }}</h4>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <div class="mt-40 mb-50">
                                        <form action="{{ route('access_control.global-settings.update', $setting->id) }}"
                                            enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dm-tab tab-horizontal">
                                                        <ul class="nav nav-tabs vertical-tabs" role="tablist">

                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="product-information-tab"
                                                                    data-bs-toggle="tab" href="#tab-product-information"
                                                                    role="tab" aria-selected="true">Company
                                                                    Information</a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a class="nav-link" id="image-files-tab"
                                                                    data-bs-toggle="tab" href="#tab-image-files"
                                                                    role="tab" aria-selected="true">Commercial
                                                                    Information</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">

                                                            <div class="tab-pane fade active show"
                                                                id="tab-product-information" role="tabpanel"
                                                                aria-labelledby="product-information-tab">
                                                                <div class="row">
                                                                    <div class="col-md-12 my-4">
                                                                        <h4>Company Information</h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="company_name"
                                                                                class="col-form-label">Company Name <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="company_name"
                                                                                class="form-control" id="company_name"
                                                                                placeholder="Company Name"
                                                                                value="{{ old('company_name', $setting->company_name) }}">
                                                                        </div>
                                                                    </div>
                                                                     <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="software_title"
                                                                                class="col-form-label">Software Title</label>
                                                                            <input type="text" name="software_title"
                                                                                class="form-control" id="software_title"
                                                                                placeholder="Software Title"
                                                                                value="{{ old('software_title', $setting->software_title) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="company_email"
                                                                                class="col-form-label">Company Email</label>
                                                                            <input type="email" name="company_email"
                                                                                class="form-control" id="company_email"
                                                                                placeholder="Company Email"
                                                                                value="{{ old('company_email', $setting->company_email) }}">

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="company_phone"
                                                                                class="col-form-label">Company Phone
                                                                                Number</label>
                                                                            <input type="text" name="company_phone"
                                                                                class="form-control" id="company_phone"
                                                                                placeholder="Company Phone Number"
                                                                                value="{{ old('company_phone', $setting->company_phone) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="website"
                                                                                class="col-form-label">Company
                                                                                Website</label>
                                                                            <input type="text"
                                                                                class="file-control form-control"
                                                                                id="website" name="website"
                                                                                value="{{ old('website', $setting->website) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="model"
                                                                                class="col-form-label">Company
                                                                                Address</label>
                                                                            <textarea name="company_address" id="company_address" class="form-control">{{ old('company_address', $setting->company_address) }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label for="model"
                                                                                class="col-form-label">Company Bio</label>
                                                                            <textarea name="company_bio" id="company_bio" class="form-control">{{ old('company_bio', $setting->company_bio) }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label
                                                                                class="col-form-label">Company Logo</label>
                                                                        {{-- <div class="form-group mb-3">
                                                                            
                                                                            <input type="file"
                                                                                class="file-control form-control"
                                                                                id="company_logo" name="company_logo" value="{{ old('company_logo', $setting->company_logo) }}">
                                                                        </div> --}}
                                                                        <x-file-uploader id="company_logo" name="company_logo" value="{{$setting->company_logo}}" />
                                                                    </div>
                                                                    <div class="col-md-4"><label 
                                                                                class="col-form-label">Fav Icon</label>
                                                                        {{-- <div class="form-group mb-3">
                                                                            
                                                                            <input type="file"
                                                                                class="file-control form-control"
                                                                                id="company_fav" name="company_fav" value="{{ old('company_fav', $setting->company_fav) }}">
                                                                        </div> --}}
                                                                        <x-file-uploader id="company_fav" name="company_fav" value="{{$setting->company_fav}}" />
                                                                    </div>
                                                                    <div class="col-md-4"><label
                                                                                class="col-form-label">Report Logo</label>
                                                                        {{-- <div class="form-group mb-3">
                                                                            
                                                                            <input type="file"
                                                                                class="file-control form-control"
                                                                                id="report_logo" name="report_logo" value="{{ old('report_logo', $setting->report_logo) }}">
                                                                        </div> --}}
                                                                        <x-file-uploader id="report_logo" name="report_logo" value="{{$setting->report_logo}}" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab-image-files"
                                                                role="tabpanel" aria-labelledby="image-files-tab">
                                                                <div class="col-md-12 my-4">
                                                                    <h4>Commercial Infomation</h4>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="vat_percentage"
                                                                                class="col-form-label">VAT%</label>
                                                                            <input type="text" name="vat_percentage"
                                                                                class="form-control" id="vat_percentage"
                                                                                placeholder="Vat Percentage"
                                                                                value="{{ old('vat_percentage', optional($setting->commercialInfo)->vat_percentage) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="ait_percentage"
                                                                                class="col-form-label">AIT%</label>
                                                                            <input type="text" name="ait_percentage"
                                                                                class="form-control" id="ait_percentage"
                                                                                placeholder="AIT Percentage"
                                                                                value="{{ old('ait_percentage', optional($setting->commercialInfo)->ait_percentage) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group mb-3">
                                                                            <label for="remarks"
                                                                                class="col-form-label">Remarks</label>
                                                                            <input type="text" name="remarks"
                                                                                class="form-control" id="remarks"
                                                                                placeholder="Vat Remarks"
                                                                                value="{{ old('remarks', optional($setting->commercialInfo)->remarks) }}">
                                                                        </div>
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
                                        <div class="app-name">
                                            <span>{{ config('app.name') }}</span>
                                        </div>

                                    </div>
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

    <script></script>
@endsection
