{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@php
    $prefix = $prefix ?? '';
    $division = $division ?? '';
    $district = $district ?? '';
    $thana = $thana ?? '';
    $union = $union ?? '';
    $village = $village ?? '';
    $post_code = $post_code ?? '';
    $street = $street ?? '';
    $lat = $lat ?? '';
    $lng = $lng ?? '';
@endphp
<div class="row mb-2">
    <div class="form-group col-md-6">
        <label for="division">Division*</label> 
        <select class="form-control geo-select" data-type="division" data-defualt="{{ old($prefix . 'division', $division) }}"
         @if($division && $divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first()) data-default_name="{{$divisionOption->name}}" @endif
            id="division" name={{ $prefix . 'division' }} required>
            <option value="">Select a Division</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="district">District*</label>
        <select class="form-control geo-select" data-type="district" data-defualt="{{ old($prefix . 'district', $district) }}"
            @if($district && $districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first()) data-default_name="{{$districtOption->name}}" @endif
            data-parant="#division" name="{{ $prefix . 'district' }}" id="district" required>
            <option value="">Select a District</option>
        </select>
    </div>
</div>
<div class="row mb-2">
    <div class="form-group col-md-6">
        <label for="thana">Thana*</label>
        <select class="form-control geo-select" data-type="thana" data-defualt="{{ old($prefix . 'thana', $thana) }}"
            @if($thana && $thanaOption = App\Models\GeoLocation::where('id', $thana)->select('id', 'name')->first()) data-default_name="{{$thanaOption->name}}" @endif
            data-parant="#district" name="{{ $prefix . 'thana' }}" id="thana" required>
            <option value="">Select a Thana</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="union">Union/ Ward*</label>
        <select class="form-control geo-select" data-type="union" data-defualt="{{ old($prefix . 'union', $union) }}"
            @if($union && $unionOption = App\Models\GeoLocation::where('id', $union)->select('id', 'name')->first()) data-default_name="{{$unionOption->name}}" @endif
            data-parant="#thana" name="{{ $prefix . 'union' }}" id="union" required>
            <option value="">Select a Union/ Ward</option>
        </select>
    </div>

</div>

<div class="row mb-2">
    <div class="form-group col-md-6">
        <label for="village">Village*</label>
        <select class="form-control geo-select" data-type="village" data-defualt="{{ old($prefix . 'village', $village) }}"
            @if($village && $villageOption = App\Models\GeoLocation::where('id', $village)->select('id', 'name')->first()) data-default_name="{{$villageOption->name}}" @endif
            data-parant="#union" name="{{ $prefix . 'village' }}" id="village" required>
            <option value="">Select a Village</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="postalCode">Postal Code*</label>
        <input type="text" class="form-control" name="{{ $prefix . 'post_code' }}"
            value="{{ old($prefix . 'post_code', $post_code) }}" id="postalCode" placeholder="Postal Code" required>
    </div>
</div>

<div class="row mb-2">
    <div class="form-group col-md-6">
        <label for="streetAddress">Street Address*</label>
        <input type="text" class="form-control" name="{{ $prefix . 'street' }}"
            value="{{ old($prefix . 'street', $street) }}" id="streetAddress" placeholder="Street Address" required>
    </div>

    {{-- latitude and longitude --}}
    <div class="form-group col-md-6">
        <label for="latitude">LOCATION</label>
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
