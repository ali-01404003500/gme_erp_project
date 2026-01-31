@section('title', 'Area Create')
@section('description', 'Area Create ')
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
                                    {{ trans('menu.create-area-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('location_manager.areas.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mb-2">
                    <h4 class="text-capitalize">{{ trans('Area create') }}</h4>
                </div>
                <x-error-alart />
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('location_manager.areas.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @php
                            $prefix = $prefix ?? '';
                            $division = old("division") ?? '';
                            $district = old("district") ?? '';
                            $thana = old("thana") ?? '';
                            $union = $union ?? '';
                            $village = $village ?? '';
                            $post_code = $post_code ?? '';
                            $street = $street ?? '';
                            $lat = $lat ?? '';
                            $lng = $lng ?? '';
                        @endphp
                        <div class="row mb-2">
                            <div class="form-group col-md-6">
                                <label for="division">Division<span class="text-danger">*</span></label> 
                                <select class="form-control geo-select" data-type="division" data-defualt="{{ old($prefix . 'division', $division) }}"
                                 @if($division && $divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first()) data-default_name="{{$divisionOption->name}}" @endif
                                    id="division" name={{ $prefix . 'division' }} required>
                                    <option value="">Select a Division</option>
                                </select>
                            </div>
                        
                            <div class="form-group col-md-6">
                                <label for="district">District<span class="text-danger">*</span></label>
                                <select class="form-control geo-select" data-type="district" data-defualt="{{ old($prefix . 'district', $district) }}"
                                    @if($district && $districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first()) data-default_name="{{$districtOption->name}}" @endif
                                    data-parant="#division" name="{{ $prefix . 'district' }}" id="district" required>
                                    <option value="">Select a District</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-group col-md-6">
                                <label for="thana">Thana<span class="text-danger">*</span></label>
                                <select class="form-control geo-select" data-type="thana" data-defualt="{{ old($prefix . 'thana', $thana) }}"
                                    @if($thana && $thanaOption = App\Models\GeoLocation::where('id', $thana)->select('id', 'name')->first()) data-default_name="{{$thanaOption->name}}" @endif
                                    data-parant="#district" name="{{ $prefix . 'thana' }}" id="thana" required>
                                    <option value="">Select a Thana</option>
                                </select>
                            </div>
                        
                            <div class="form-group col-md-6">
                                <label for="union">Union/ Ward</label>
                                <select class="form-control geo-select" data-type="union" data-defualt="{{ old($prefix . 'union', $union) }}"
                                    @if($union && $unionOption = App\Models\GeoLocation::where('id', $union)->select('id', 'name')->first()) data-default_name="{{$unionOption->name}}" @endif
                                    data-parant="#thana" name="{{ $prefix . 'union' }}" id="union">
                                    <option value="">Select a Union/ Ward</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="union">Area <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="area" id="union" placeholder="Area" value="{{ old('area') }}" required>
                            </div>
                        
                            <div class="form-group col-md-6">
                                <label for="village">Village</label>
                                <select class="form-control geo-select" data-type="village" data-defualt="{{ old($prefix . 'village', $village) }}"
                                    @if($village && $villageOption = App\Models\GeoLocation::where('id', $village)->select('id', 'name')->first()) data-default_name="{{$villageOption->name}}" @endif
                                    data-parant="#union" name="{{ $prefix . 'village' }}" id="village" >
                                    <option value="">Select a Village</option>
                                </select>
                            </div>
                        
                            <div class="form-group col-md-6">
                                <label for="postalCode">Postal Code</label>
                                <input type="text" class="form-control" name="{{ $prefix . 'post_code' }}"
                                    value="{{ old($prefix . 'post_code', $post_code) }}" id="postalCode" placeholder="Postal Code" >
                            </div>
                      
                            <div class="form-group col-md-6">
                                <label for="streetAddress">Street Address</label>
                                <input type="text" class="form-control" name="{{ $prefix . 'street' }}"
                                    value="{{ old($prefix . 'street', $street) }}" id="streetAddress" placeholder="Street Address" >
                            </div>
                        
                            {{-- latitude and longitude --}}
                            <div class="form-group col-md-6">
                                <label for="latitude">Location</label>
                                {{-- inline latitude and longitude --}}
                                <div class="input-group">
                                    <input type="text" class="form-control "  value="{{ old($prefix . 'lat', $lat) }}"
                                        name="{{ $prefix . 'lat' }}" id="latitude" placeholder="Latitude">
                                    <input type="text" class="form-control "  value="{{ old($prefix . 'lon', $lng) }}"
                                        name="{{ $prefix . 'lon' }}" id="longitude" placeholder="Longitude">
                                        {{-- <span class="input-group-text px-0 py-0"> --}}
                                            <button class="btn btn-secondary py-2" type="button"  id="geolocate" title="Get current location">
                                                <i class="fas fa-crosshairs"></i>
                                            </button>
                                        {{-- </span> --}}
                                   
                                </div>
                            </div>
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
@endsection
@section('page_scripts')
@include('utils.geo_locations.script')
@endSection
