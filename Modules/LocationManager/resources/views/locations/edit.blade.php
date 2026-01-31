@section('title', 'Location Update')
@section('description', 'Create Update ')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('menu.update-location-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('location_manager.locations.create', app()->getLocale()) }}" class="btn px-20 btn-primary ">
                            <i class="las la-plus fs-16"></i>Add New
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center user-member__title mb-2">
                    <h4 class="text-capitalize">{{ trans('Location Update') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('location_manager.locations.update', $location->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            

                            <h2> Basic Information</h2>
                            <div class="row">

                                <div class="col-sm-6">

                                    <div class="edit-profile__body">
                                        <div class="form-group mb-25">
                                            <label for="location_type_id" class="color-dark fs-14 fw-500 align-center">Location Type<span class="text-danger">*</span></label>
                                            <select name="location_type_id" id="location_type_id"
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15">
                                                <option value="">Select Location Type</option>
                                                @foreach ($locationTypes as $locationType)
                                                    <option value="{{ $locationType->id }}" {{ $locationType->id == $location->location_type_id ? 'selected' : '' }}>{{ $locationType->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('location_type_id'))
                                                <p class="text-danger">{{ $errors->first('location_type_id') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group mb-25">
                                            <label for="name"
                                                class="color-dark fs-14 fw-500 align-center">Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                name="name" value="{{ old('name', $location->name) }}" id="name"
                                                placeholder="Company Place">
                                            @if ($errors->has('name'))
                                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="address" class="color-dark fs-14 fw-500 align-center">Address</label>
                                        <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" name="address" style="height: 100px;"
                                        id="address" placeholder="Address">{{ old('address', $location->address) }}</textarea>  
                                        @if ($errors->has('address'))
                                            <p class="text-danger">{{ $errors->first('address') }}</p>
                                        @endif                                      
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="operational_hours" class="color-dark fs-14 fw-500 align-center">Operational Hours</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="operational_hours" id="operational_hours"
                                            value="{{ old('operational_hours', $location->operational_hours) }}" placeholder="Operational Hours">
                                        
                                        @if ($errors->has('operational_hours'))
                                            <p class="text-danger">{{ $errors->first('operational_hours') }}</p>
                                        @endif
                                    </div>
                                    
                                </div>

                            </div>
                            <h2>Contact Person Information</h2>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="contact_person_name"
                                            class="color-dark fs-14 fw-500 align-center">Contact Person Name</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_name" id="contact_person_name"
                                            value="{{ old('contact_person_name', $location->contact_person_name) }}" placeholder="Contact Person Name">
                                        @if ($errors->has('contact_person_name'))
                                            <p class="text-danger">{{ $errors->first('contact_person_name') }}</p>
                                        @endif
                                    </div>

                                    <div class="form-group mb-25">
                                        <label for="contact_person_mobile"
                                            class="color-dark fs-14 fw-500 align-center">Contact Person Mobile</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_mobile" id="contact_person_mobile"
                                            value="{{ old('contact_person_mobile', $location->contact_person_mobile) }}"
                                            placeholder="Contact Person Mobile">
                                        @if ($errors->has('contact_person_mobile'))
                                            <p class="text-danger">{{ $errors->first('contact_person_mobile') }}</p>
                                        @endif
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="contact_person_email"
                                            class="color-dark fs-14 fw-500 align-center">Contact Person Email</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_email" id="contact_person_email"
                                            value="{{ old('contact_person_email', $location->contact_person_email) }}" placeholder="Contact Person Email">
                                        @if ($errors->has('contact_person_email'))
                                            <p class="text-danger">{{ $errors->first('contact_person_email') }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>

                            <h2>Map Information</h2>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="lat" class="color-dark fs-14 fw-500 align-center">Latitude</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="lat" id="lat" value="{{ old('lat', $location->lat) }}"    
                                            placeholder="Latitude">
                                        @if ($errors->has('lat'))
                                            <p class="text-danger">{{ $errors->first('lat') }}</p>
                                        @endif
                                    </div>

                                    <div class="form-group mb-25">
                                        <label for="long" class="color-dark fs-14 fw-500 align-center">Longitude</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="long" id="long" value="{{ old('long', $location->long) }}"    
                                            placeholder="Longitude">
                                        @if ($errors->has('long'))
                                            <p class="text-danger">{{ $errors->first('long') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="map_link" class="color-dark fs-14 fw-500 align-center">Map Link</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="map_link" id="map_link" value="{{ old('map_link', $location->map_link) }}"    
                                            placeholder="map_link">
                                        @if ($errors->has('map_link'))
                                            <p class="text-danger">{{ $errors->first('map_link') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="map_iframe" class="color-dark fs-14 fw-500 align-center">Map IFrame</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="map_iframe" id="map_iframe" value="{{ old('map_iframe', $location->map_iframe) }}"    
                                            placeholder="map_iframe">
                                        @if ($errors->has('map_iframe'))
                                            <p class="text-danger">{{ $errors->first('map_iframe') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <a href="{{ route('location_manager.locations.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
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
