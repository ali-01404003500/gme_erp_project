@section('title', 'Daily Calls')
@section('description', 'Daily Calls')
@extends('layout.app')
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
                                        {{ trans('menu.create-daily-call-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('crm.daily-calls.index'))
                                <a href="{{ route('crm.daily-calls.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-daily-call-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-10">
                            <div class="mt-40 mb-50">
                                <form action="{{ route('crm.daily-calls.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-25">
                                                <label for="customer_id" class="text-capitalize">Customer Name<span
                                                        class="text-danger">*</span></label>
                                                <select name="customer_id" id="customer_id" class="form-control tom-select">
                                                    <option value="">Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                            {{ $customer->company_name }} - {{ $customer->address}}
                                                            @if ($customer->area != null) ({{ $customer->area->area }}) @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('customer_id'))
                                                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="call_type_id" class="text-capitalize">Call Type</label>
                                                <select name="call_type_id" id="call_type_id"
                                                    class="form-control tom-select">
                                                    <option value="">Select Call Type</option>
                                                    <option value="1" {{ old('call_type_id') == 1 ? 'selected' : '' }}>Regular
                                                        Call</option>
                                                    <option value="2" {{ old('call_type_id') == 2 ? 'selected' : '' }}>Service
                                                        Call</option>
                                                </select>
                                                @if ($errors->has('call_type_id'))
                                                    <p class="text-danger">{{ $errors->first('call_type_id') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="call_date" class="text-capitalize">Call Date</label>
                                                <input type="text" class="form-control flatdate"
                                                    value="{{ old('call_date', date('Y-m-d')) }}" name="call_date"
                                                    id="call_date" placeholder="Call Date">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Account Complain --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="is_account_complain" class="text-capitalize">Account Complain<span
                                                    class="text-danger">*</span></label>
                                            <select name="is_account_complain" id="is_account_complain"
                                                class="form-control tom-select">
                                                <option value="">Select Type</option>
                                                <option value="1" {{ old('is_account_complain') == 1 ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="0" {{ old('is_account_complain') == 0 ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                            @if ($errors->has('is_account_complain'))
                                                <p class="text-danger">{{ $errors->first('is_account_complain') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6" id="account_complain_details_wrapper">
                                            <label for="complains_details" class="text-capitalize">Account Complain
                                                Details</label>
                                            <input type="text" name="complains_details" id="complains_details"
                                                class="form-control" placeholder="Complain Details"
                                                value="{{ old('complains_details') }}">
                                            @if ($errors->has('complains_details'))
                                                <p class="text-danger">{{ $errors->first('complains_details') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Service Complain --}}
                                    <div class="row">
                                        <div class="col-md-6 mt-25">
                                            <label for="is_service_complain" class="text-capitalize">Service Complain<span
                                                    class="text-danger">*</span></label>
                                            <select name="is_service_complain" id="is_service_complain"
                                                class="form-control tom-select">
                                                <option value="">Select Type</option>
                                                <option value="1" {{ old('is_service_complain') == 1 ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="0" {{ old('is_service_complain') == 0 ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                            @if ($errors->has('is_service_complain'))
                                                <p class="text-danger">{{ $errors->first('is_service_complain') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-25" id="service_complain_details_wrapper">
                                            <label for="service_complain_details" class="text-capitalize">Service Complain
                                                Details</label>
                                            <input type="text" name="service_complain_details" id="service_complain_details"
                                                class="form-control" placeholder="Service Complain Details"
                                                value="{{ old('service_complain_details') }}">
                                            @if ($errors->has('service_complain_details'))
                                                <p class="text-danger">{{ $errors->first('service_complain_details') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Sales Complain --}}
                                    <div class="row">
                                        <div class="col-md-6 mt-25">
                                            <label for="is_sales_complain" class="text-capitalize">Sales Complain<span
                                                    class="text-danger">*</span></label>
                                            <select name="is_sales_complain" id="is_sales_complain"
                                                class="form-control tom-select">
                                                <option value="">Select Type</option>
                                                <option value="1" {{ old('is_sales_complain') == 1 ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="0" {{ old('is_sales_complain') == 0 ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                            @if ($errors->has('is_sales_complain'))
                                                <p class="text-danger">{{ $errors->first('is_sales_complain') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-25" id="sales_complain_details_wrapper">
                                            <label for="sales_complain_details" class="text-capitalize">Sales Complain
                                                Details</label>
                                            <input type="text" name="sales_complain_details" id="sales_complain_details"
                                                class="form-control" placeholder="Sales Complain Details"
                                                value="{{ old('sales_complain_details') }}">
                                            @if ($errors->has('sales_complain_details'))
                                                <p class="text-danger">{{ $errors->first('sales_complain_details') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Requirement of Product --}}
                                    <div class="row">
                                        <div class="col-md-6 mt-25">
                                            <label for="is_product_required" class="text-capitalize">Requirement of
                                                Product<span class="text-danger">*</span></label>
                                            <select name="is_product_required" id="is_product_required"
                                                class="form-control tom-select">
                                                <option value="">Select Type</option>
                                                <option value="1" {{ old('is_product_required') == 1 ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="0" {{ old('is_product_required') == 0 ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                            @if ($errors->has('is_product_required'))
                                                <p class="text-danger">{{ $errors->first('is_product_required') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-25" id="product_required_details_wrapper">
                                            <label for="product_required_details" class="text-capitalize">Requirement of
                                                Product Details</label>
                                            <input type="text" name="product_required_details" id="product_required_details"
                                                class="form-control" placeholder="Requirement of Product Details"
                                                value="{{ old('product_required_details') }}">
                                            @if ($errors->has('product_required_details'))
                                                <p class="text-danger">{{ $errors->first('product_required_details') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-25">
                                        <label for="remarks" class="text-capitalize">About of Company/Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" cols="30"
                                            rows="5">{{ old('remarks') }}</textarea>
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
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            function toggleField(selectId, fieldWrapper) {
                let selectElement = $(selectId);
                let fieldWrapperElement = $(fieldWrapper);

                if (selectElement.val() == "1") {
                    fieldWrapperElement.show();
                } else {
                    fieldWrapperElement.hide();
                    fieldWrapperElement.find('input').val(''); // Clear field when hidden
                }
            }

            // Initial check on page load
            toggleField("#is_account_complain", "#account_complain_details_wrapper");
            toggleField("#is_service_complain", "#service_complain_details_wrapper");
            toggleField("#is_sales_complain", "#sales_complain_details_wrapper");
            toggleField("#is_product_required", "#product_required_details_wrapper");

            // Event listeners
            $("#is_account_complain").change(function () {
                toggleField("#is_account_complain", "#account_complain_details_wrapper");
            });
            $("#is_service_complain").change(function () {
                toggleField("#is_service_complain", "#service_complain_details_wrapper");
            });
            $("#is_sales_complain").change(function () {
                toggleField("#is_sales_complain", "#sales_complain_details_wrapper");
            });
            $("#is_product_required").change(function () {
                toggleField("#is_product_required", "#product_required_details_wrapper");
            });
        });
    </script>
@endsection