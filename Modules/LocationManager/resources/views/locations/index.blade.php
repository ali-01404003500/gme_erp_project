@section('title',"Location List")
@section('description',"Location List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Location list') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('location_manager.locations.create'))
                            <a href="{{ route('location_manager.locations.create') }}" class="btn px-20 btn-primary ">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('Location list') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $locations])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Location Name</th>
                                    <th>Location Type</th>
                                    <th>Contact Person name</th>
                                    <th>Contact Person Mobile</th>
                                    <th>Address</th>
                                    <th>Lattitude</th>
                                    <th>Longitude</th>
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
                                @foreach ($locations as $location)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="">{{ $location->name }}</a>
                                        </td>
                                        <td>{{ $location->locationType->name }}</td>
                                        <td>{{ $location->contact_person_name }}</td>
                                        <td>{{ $location->contact_person_mobile }}</td>
                                        <td>{{ $location->address }}</td>
                                        <td>{{ $location->lat }}</td>
                                        <td>{{ $location->long }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    <a class="btn btn-outline-warning" href="{{ route('location_manager.locations.edit', $location->id) }}"><i
                                                            class="far fa-edit"></i></a>
            
                                               
            
                                                    <button type="button" data-action="{{ route('location_manager.locations.destroy', $location->id) }}"
                                                        class="btn btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>
            
                                                    <a class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
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