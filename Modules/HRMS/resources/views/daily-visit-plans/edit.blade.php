@extends('layout.app')
@section('title', 'Edit Daily Visit Plan')
@section('description', 'Edit Daily Visit Plan')
@section('content')

<style>
    .nav-tabs.vertical-tabs .nav-item .nav-link {
        background-color: #f7ecfd;
        color: #3d3d3d;
        border-radius: 5px 5px 0 0;
    }

    .nav-tabs.vertical-tabs .nav-item .nav-link.active {
        background-color: var(--color-primary);
        color: #ffffff;
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
                                    {{ trans('Edit Daily Visit Plan') }}</li>
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
                <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit Daily Visit Plan') }}</h4>
                <x-error-alart />
            </div>

            <div class="card mb-50">
                <div class="row justify-content-center" id="justify-content-center">
                    <div class="col-sm-11">
                        <div class="mt-40 mb-50">
                            <h2 class="mb-3">Daily Visit Plan</h2>
                            <form action="{{ route('hrm.daily-visit-plans.update', [$dailyVisitPlan->id, app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="company_name">Company's Name<span class="text-danger">*</span>:</label>
                                            <input type="text" name="company_name" id="company_name"
                                                class="form-control px-15"
                                                value="{{ old('company_name', $dailyVisitPlan->company_name) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone_no">Phone No<span class="text-danger">*</span>:</label>
                                            <input type="text" name="phone_no" id="phone_no"
                                                class="form-control px-15"
                                                value="{{ old('phone_no', $dailyVisitPlan->phone_no) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date">Visit Date<span class="text-danger">*</span>:</label>
                                            <input type="text" name="date" id="date"
                                                class="form-control px-15 flatdate"
                                                value="{{ old('date', $dailyVisitPlan->date) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="address">Address<span class="text-danger">*</span>:</label>
                                            <input type="text" name="address" id="address"
                                                class="form-control px-15"
                                                value="{{ old('address', $dailyVisitPlan->address) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person:</label>
                                            <input type="text" name="contact_person" id="contact_person"
                                                class="form-control px-15"
                                                value="{{ old('contact_person', $dailyVisitPlan->contact_person) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="business_type">Business Type<span class="text-danger">*</span>:</label>
                                            <select name="business_type" id="business_type"
                                                class="form-control tom-select px-15">
                                                <option value="New Client" {{ old('business_type', $dailyVisitPlan->business_type) == 'New Client' ? 'selected' : '' }}>New Client</option>
                                                <option value="Old Client" {{ old('business_type', $dailyVisitPlan->business_type) == 'Old Client' ? 'selected' : '' }}>Old Client</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="visit_purpose">Visit Purpose:</label>
                                            <input type="text" name="visit_purpose" id="visit_purpose"
                                                class="form-control px-15"
                                                value="{{ old('visit_purpose', $dailyVisitPlan->visit_purpose) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="file">File:</label>
                                            <x-file-uploader multiple :value="$dailyVisitPlan->attachment" name="attachment" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Product Requirements/Remarks<span class="text-danger">*</span>:</label>
                                            <textarea name="description" id="description" class="form-control px-15" rows="3">{{ old('description', $dailyVisitPlan->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
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
        $('.flatdate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>
@endsection
