@section('title', 'Edit Courier Information')
@section('description', 'Edit Courier Information')
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
                                        {{ trans('menu.update-courier-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('sales.couriers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>

                            <a href="{{ route('sales.couriers.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-courier-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('sales.couriers.update', $courier->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="courier_name">Courier Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="courier_name" id="courier_name"
                                                placeholder="Enter courier name" value="{{ old('courier_name', $courier->courier_name) }}" required>
                                                @if ($errors->has('courier_name'))
                                                    <span class="text-danger">{{ $errors->first('courier_name') }}</span>
                                                @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="courier_branch">Courier Branch <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="courier_branch" id="courier_branch"
                                                placeholder="Enter Courier Branch" value="{{ old('courier_branch', $courier->courier_branch) }}" required>
                                                @if ($errors->has('courier_branch'))
                                                    <span class="text-danger">{{ $errors->first('courier_branch') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="courier_phone">Courier Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="courier_phone" id="courier_phone"
                                                placeholder="Enter Courier Contact Number" value="{{ old('courier_phone', $courier->courier_phone) }}" required>
                                                @if ($errors->has('courier_phone'))
                                                    <span class="text-danger">{{ $errors->first('courier_phone') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="courier_address">Courier Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="courier_address" id="courier_address"
                                                placeholder="Enter Courier Address" value="{{ old('courier_address', $courier->courier_address) }}" required>
                                                @if ($errors->has('courier_address'))
                                                    <span class="text-danger">{{ $errors->first('courier_address') }}</span>
                                                @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="courier_email">Courier Mail</label>
                                            <input type="text" class="form-control" name="courier_email" id="courier_email"
                                                placeholder="Enter Courier Mail" value="{{ old('courier_email', $courier->courier_email) }}">
                                                @if ($errors->has('courier_email'))
                                                    <span class="text-danger">{{ $errors->first('courier_email') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_name">Contact Person Name</label>
                                            <input type="text" class="form-control" name="contact_person_name" id="contact_person_name"
                                                placeholder="Enter Contact Person Name" value="{{ old('contact_person_name', $courier->contact_person_name) }}">
                                                @if ($errors->has('contact_person_name'))
                                                    <span class="text-danger">{{ $errors->first('contact_person_name') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_designation">Contact Person Designation</label>
                                            <input type="text" class="form-control" name="contact_person_designation" id="contact_person_designation"
                                                placeholder="Enter Contact Person Designation" value="{{ old('contact_person_designation', $courier->contact_person_designation) }}">
                                                @if ($errors->has('contact_person_designation'))
                                                    <span class="text-danger">{{ $errors->first('contact_person_designation') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person_address">Contact Person Address</label>
                                            <input type="text" class="form-control" name="contact_person_address" id="contact_person_address"
                                                placeholder="Enter Contact Person Address" value="{{ old('contact_person_address', $courier->contact_person_address) }}">
                                                @if ($errors->has('contact_person_address'))
                                                    <span class="text-danger">{{ $errors->first('contact_person_address') }}</span>
                                                @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="web_link">Courier Tracking Address</label>
                                            <input type="text" class="form-control" name="web_link" id="web_link"
                                                placeholder="Courier Tracking Address" value="{{ old('web_link', $courier->web_link) }}">
                                                @if ($errors->has('web_link'))
                                                    <span class="text-danger">{{ $errors->first('web_link') }}</span>
                                                @endif
                                        </div>
                                    </div>

                                    

                                    <div class="col-md-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Status</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="radio">
                                                <label>
                                                    <input name="status" type="radio" value="1" class="ace" {{ old('status', $courier->status) == 1 ? 'checked' : ''}}>
                                                    <span class="lbl"> Active</span>
                                                </label>
                                                <label>
                                                    <input name="status" type="radio" value="0" class="ace" {{ old('status', $courier->status) == 0 ? 'checked' : ''}}>
                                                    <span class="lbl"> In active</span>
                                                </label>
                                            </div>

                                            @error('status')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                        </div>
                                    </div>

                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                                Update
                                            </button>
                                        </div>
                                    
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
@endsection