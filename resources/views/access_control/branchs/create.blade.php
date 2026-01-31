@section('title', 'Create Branch')
@section('description', 'Create Branch')
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
                                        {{ trans('menu.branch-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('access_control.branchs.index'))
                            <a href="{{ route('access_control.branchs.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.branch-create-menu-title') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('access_control.branchs.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    @php
                                        $prefix = $prefix ?? '';
                                        $division = old("division") ?? '';
                                        $district = old("district") ?? '';
                                        $thana = $thana ?? '';
                                        $union = $union ?? '';
                                        $village = $village ?? '';
                                        $post_code = $post_code ?? '';
                                        $street = $street ?? '';
                                        $lat = $lat ?? '';
                                        $lng = $lng ?? '';
                                    @endphp
                                  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Enter name" value="{{ old('name') }}">                                           
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" id="title"
                                                placeholder="Enter title" value="{{ old('title') }}">                                           
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="branch_type_id">Branch Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="branch_type_id" id="branch_type_id">
                                                @foreach($branchTypes as $branchType)
                                                    <option value="{{ $branchType->id }}" {{ old('branch_type_id') == $branchType->id ? 'selected' : '' }}>{{ $branchType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="division">Division<span class="text-danger">*</span> </label>
                                            <select class="form-control geo-select" data-type="division"
                                                    data-defualt="{{ old($prefix . 'division', $division) }}"
                                                    @if ($division && ($divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first())) data-default_name="{{ $divisionOption->name }}" @endif
                                                    id="division" name="division">
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="district">District<span class="text-danger">*</span></label>
                                            <select class="form-control geo-select" data-type="district"
                                                    data-defualt="{{ old($prefix . 'district', $district) }}"
                                                    @if ($district && ($districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first())) data-default_name="{{ $districtOption->name }}" @endif
                                                    data-parant="#division" name="district"
                                                    id="district">
                                                </select>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_no">Contact No.<span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="contact_no" value="{{ old('contact_no') }}" id="contact_no"
                                                placeholder="Enter contact no.">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" value="{{ old('address') }}" name="address" id="address"
                                                placeholder="Enter address">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <h3>Contact Person</h3>
                                        <table class="table ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Name</th>
                                                    <th>Mobile<span class="text-danger">*</span></th>
                                                    <th>Designation<span class="text-danger">*</span></th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $names = old('names');
                                                @endphp
                                                @if(isset($names))
                                                    @foreach ($names as $key => $name)
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control" name="names[]"
                                                                    value="{{ $name }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="mobiles[]"
                                                                    value="{{ old('mobiles')[$key]??'' }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="designations[]"
                                                                    value="{{ old('designations')[$key]??''  }}">
                                                            </td>
                                                            <td>
                                                                <button type="button" onclick="removeContactPerson(this)" class="btn btn-danger btn-xs remove-contact-person">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="names[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="mobiles[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="designations[]">
                                                        </td>
                                                        <td>
                                                            <button type="button" disabled class="btn btn-danger btn-xs remove-contact-person disabled">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" >
                                                       <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-info btn-sm add-contact-person">
                                                                <i class="fa fa-plus"></i> Add</button>
                                                       </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">Picture</label>
                                            <input type="file" class="form-control file-control" name="image" id="image">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Submit') }}
                                            </button>
                                        </div>
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
<script>
    $('.add-contact-person').click(function() {
        var tr = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="names[]">
                </td>
                <td>
                    <input type="text" class="form-control" name="mobiles[]">
                </td>
                <td>
                    <input type="text" class="form-control" name="designations[]">
                </td>
                <td>
                    <button type="button" onclick="removeContactPerson(this)" class="btn btn-danger btn-xs remove-contact-person">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('.table tbody').append(tr);
    });

    function removeContactPerson(el) {
        $(el).closest('tr').remove();
    }

    
  
</script>
    @include('utils.geo_locations.script')
@endsection