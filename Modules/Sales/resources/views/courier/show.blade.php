@section('title', 'Courier Information Details')
@section('description', 'Courier Information Details')
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
                                        {{ trans('menu.courier-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('sales.couriers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>


                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.courier-view-menu-title') }}</h4>
                    <br>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title">Courier Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Courier Name </th>
                                                <td>
                                                    {{ $courier->courier_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Courier Branch</th>
                                                <td>{{ $courier->courier_branch }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Courier Contact Numbe</th>
                                                <td>
                                                    {{ $courier->courier_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Courier Address </th>
                                                <td>
                                                    {{ $courier->courier_address }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Courier Mail</th>
                                                <td>{{ $courier->courier_email }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Contact Person Name</th>
                                                <td>{{ $courier->contact_person_name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Contact Person Designation</th>
                                                <td>{{ $courier->contact_person_designation }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Contact Person Address</th>
                                                <td>
                                                    {{ $courier->contact_person_address }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td>{{ $courier->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
