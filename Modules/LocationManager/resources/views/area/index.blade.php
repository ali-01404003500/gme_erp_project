@section('title',"Area List")
@section('description',"Area List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Area list') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('location_manager.areas.create'))
                            <a href="{{ route('location_manager.areas.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
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
                <h4 class="text-capitalize breadcrumb-title">{{ trans('Area list') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" class="form-control" name="area"
                                                    value="{{ request('area') }}" autocomplete="off"
                                                    placeholder="Search by Area Name">
                                            </td>
                                            <td class="text-center" style="width: 28%">
                                                <select name="thana_id" id="thana_id" class="form-control tom-select"
                                                    data-placeholder="Search by Thana Name">
                                                    <option value=""></option>
                                                    @foreach ($areas as $value )
                                                        <option {{ request('thana_id') == $value->thana_id ? 'selected' : '' }} value="{{ $value->thana_id }}">{{ @$value->thana->name }}</option>
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
                        <table id="zero-config"class="table dt-table-hover table-bordered" data-page='@include('utils.table_paginate', ['data' => $areas])' style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">Sl</th>
                                    <th>Area Name</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Thana</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>name</td>
                                    <td>email</td>
                                    <td class="no-content">Action</td>
                                </tr> --}}
                                {{-- @dd($employees) --}}
                                @foreach ($areas as $value )
                                    <tr>
                                        <td class="text-center">{{ ($areas->currentPage() - 1) * $areas->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $value->area }}</td>
                                        <td>{{ optional($value->division)->name }}</td>
                                        <td>{{ optional($value->district)->name }}</td>
                                        <td>{{ optional($value->thana)->name }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if(hasPermission('location_manager.areas.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('location_manager.areas.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif
                                                    @if(hasPermission('location_manager.areas.destroy'))
                                                        <button type="button" data-action="{{ route('location_manager.areas.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
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
        </div>
    </div>
</div>
@endsection