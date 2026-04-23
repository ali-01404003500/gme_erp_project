@section('title', 'Thana List')
@section('description', 'Thana List')
@extends('layout.app')
@section('content')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Thana') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('location_manager.thanas.create'))
                                <button class="btn btn-xs btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    Add New
                                </button>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Thana') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />

                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="text-left">
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ request('name') }}" autocomplete="off"
                                                        placeholder="Search by Thana Name">
                                                </td>
                                                {{-- <td class="text-left">
                                                    <select name="division_id" id="division_id" class="form-control tom-select"
                                                        data-placeholder="Search by Division Name">
                                                        <option value=""></option>
                                                        @foreach($divisions as $division)
                                                            <option {{ request('division_id') == $division->id ? 'selected' : '' }} value="{{ $division->id }}">{{ $division->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td> --}}
                                                <td class="text-left" style="width: 28%">
                                                    <select name="district_id" id="district_id" class="form-control tom-select"
                                                        data-placeholder="Search by District Name">
                                                        <option value=""></option>
                                                        @foreach($districts as $district)
                                                            <option {{ request('district_id') == $district->id ? 'selected' : '' }} value="{{ $district->id }}">{{ $district->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="5" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover table-bordered" data-page='@include('utils.table_paginate', ['data' => $thanas])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-left">Division</th>
                                        <th class="text-left">District</th>
                                        <th class="text-left">Thana Name</th>
                                        <th class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($thanas as $value)
                                        <tr>
                                        <td class="text-center">{{ ($thanas->currentPage() - 1) * $thanas->perPage() + $loop->iteration  }}</td>
                                            <td class="text-left">{{ optional(optional($value->parent)->parent)->name }}</td>
                                            <td class="text-left">{{ optional($value->parent)->name }}</td>
                                            <td class="text-left">{{ $value->name }}</td>
                                            <td class="text-left">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if(hasPermission('location_manager.thanas.update'))
                                                    <a href={{ $value->id }} class="btn btn-edit  btn-outline-warning"
                                                        data-name="{{ $value->name }}"
                                                        data-district="{{ optional($value->parent)->id }}"
                                                        data-division="{{ optional(optional($value->parent)->parent)->id }}"
                                                        data-action="{{ route('location_manager.thanas.update', $value->id) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    @endif
                                                    @if(hasPermission('location_manager.thanas.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('location_manager.thanas.destroy', $value->id) }}"
                                                        class="btn btn-outline-danger delete-confirm" title="Delete" title="Delete"><i
                                                            class="far fa-trash-alt"></i></button>
                                                    @endif

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Division</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('location_manager.thanas.store') }}" method="post">
                                @csrf
                                @php
                                    $prefix = $prefix ?? '';
                                    $division = '';
                                    $district = '';
                                    $thana =  '';
                                    $union =  '';
                                @endphp
                                <div class="modal-body">

                                    <div class="row mb-4">
                                        <label for="division" class="col-sm-12 col-form-label">Division<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="form-control geo-select required" data-type="division"
                                                data-defualt="{{ old($prefix . 'division', $division) }}"
                                                @if ($division && ($divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first())) data-default_name="{{ $divisionOption->name }}" @endif
                                                id="division" name={{ $prefix . 'division_id' }} required>
                                                <option value="">Select a Division</option>
                                            </select>
                                            @if ($errors->has('division_id'))
                                                <p class="text-danger">{{ $errors->first('division_id') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label" for="district">District<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="form-control geo-select required" data-type="district"
                                                data-defualt="{{ old($prefix . 'district', $district) }}"
                                                @if ($district && ($districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first())) data-default_name="{{ $districtOption->name }}" @endif
                                                data-parant="#division" name="parent_id"
                                                value="{{ $prefix . 'district_id' }}" id="district" required>
                                                <option value="">Select a District</option>
                                            </select>
                                            @if ($errors->has('district_id'))
                                                <p class="text-danger">{{ $errors->first('district_id') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Thana Name<span style="color: red">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control"
                                                placeholder=" Name *" required>
                                            <input type="text" name="type" class="form-control" value="Thana"
                                                hidden>
                                        </div>
                                    </div>

                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
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

                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="division" class="col-sm-12 col-form-label">Division<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control geo-select" data-type="division"
                                    data-defualt="{{ old($prefix . 'division', $division) }}"
                                    @if ($division && ($divisionOption = App\Models\GeoLocation::where('id', $division)->select('id', 'name')->first())) data-default_name="{{ $divisionOption->name }}" @endif
                                    id="division_edit" name={{ $prefix . 'division_id' }}>
                                    <option value="">Select a Division</option>
                                </select>
                                @if ($errors->has('division_id'))
                                    <p class="text-danger">{{ $errors->first('division_id') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label" for="district">District<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control geo-select" data-type="district"
                                    data-defualt="{{ old($prefix . 'district', $district) }}"
                                    @if ($district && ($districtOption = App\Models\GeoLocation::where('id', $district)->select('id', 'name')->first())) data-default_name="{{ $districtOption->name }}" @endif
                                    data-parant="#division_edit" name="parent_id" value="{{ $prefix . 'district_id' }}"
                                    id="district">
                                    <option value="">Select a District</option>
                                </select>
                                @if ($errors->has('district_id'))
                                    <p class="text-danger">{{ $errors->first('district_id') }}</p>
                                @endif
                            </div>
                        </div>


                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Thana Name<span style="color: red">*</span></label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    @include('utils.geo_locations.script')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', async function () {
                $('#name').val($(this).data('name'));
                console.log($(this).data('division'));
                // $('#editModel #division option[value="' + $(this).data('division') + '"]').attr('selected','selected');
                // $('#district option[value="' + $(this).data('district') + '"]').attr('selected','selected');
                $('#division_edit').prop("tomselect").setValue($(this).data('division'));
                // $('#editFrom #division').trigger('change');
                // $('#editFrom #district').prop("tomselect").load("")
               setTimeout(() => {
                $('#editFrom #district').prop("tomselect").setValue($(this).data('district'));
               }, 1000);
                // $('#editFrom #district').
                // $('#editModel #district')[0].tomselect.sync();
                $("#editFrom").attr("action", $(this).data('action'));

            });
        });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
@endsection
