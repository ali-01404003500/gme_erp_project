@extends('layout.app')
@section('title', 'Create Daily Visit Plan')
@section('description', 'Create Daily Visit Plan')
@section('content')

    <style>
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
        <div class="social-dash-wrap">

            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Create Daily Visit Plan') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.daily-visit-plans.index'))
                                <a href="{{ route('hrm.daily-visit-plans.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Create Daily Visit Plan') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Daily Visit Plan</h2>
                                <form action="{{ route('hrm.daily-visit-plans.store', app()->getLocale()) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="case_name">Company's Name<span class="text-danger">*</span>:</label>
                                                <input type="text" name="company_name" id="company_name"
                                                    class="form-control px-15" value="{{ old('company_name') }}">
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone_no">Phone No<span class="text-danger">*</span>:</label>
                                                <input type="text" name="phone_no" id="phone_no"
                                                    value="{{ old('phone_no') }}" class="form-control px-15">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date">Visit Date<span class="text-danger">*</span>:</label>
                                                <input type="test" name="date" id="date"
                                                    class="form-control px-15 flatdate"
                                                    value="{{ old('date', date('Y-m-d')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="address">Address<span class="text-danger">*</span>:</label>
                                                <input type="text" name="address" id="address"
                                                    class="form-control px-15" value="{{ old('address') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contact_person">Contact Person:</label>
                                                <input type="text" name="contact_person" id="contact_person"
                                                    class="form-control px-15" value="{{ old('contact_person') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="business_type">Business Type<span class="text-danger">*</span>:</label>
                                                <select name="business_type" id="business_type"
                                                    class="form-control tom-select px-15">
                                                    <option value="New Client" {{ old('business_type') == 'New Client' ? 'selected' : '' }}>New Client</option>
                                                    <option value="Old Client" {{ old('business_type') == 'Old Client' ? 'selected' : '' }}>Old Client</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="visit_purpose">Visit Purpose:</label>
                                                <input type="text" name="visit_purpose" id="visit_purpose"
                                                    class="form-control px-15" value="{{ old('visit_purpose') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="file">File:</label>
                                                <x-file-uploader multiple :value="old('attachment')" name="attachment" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Product Requirements/Remarks<span class="text-danger">*</span>:</label>
                                                <textarea name="description" id="description" class="form-control px-15" rows="3">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
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
            $(document).ready(function() {
                $('#case_name').on('change', function() {
                    var caseNo = $(this).find(':selected').data('case_no');
                    $('#particular').val(caseNo);
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
