@section('title', 'Dongle Or Serial Update')
@section('description', 'Dongle Or Serial Update')
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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('licenses.dongle-or-serial-entries.index') }}">{{ trans('menu.dongle-or-serial-menu-title') }}</a></li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                        @if (hasPermission('license.dongle-or-serial-entry.create'))
                        <a href="{{ route('licenses.dongle-or-serial-entries.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                class="fa fa-list"></i> List</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">
                        {{ trans('menu.update-dongle-or-serial-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>{{ $dongleOrSerialEntry->customer->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{ $dongleOrSerialEntry->address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Product Name</th>
                                            <td>{{ $dongleOrSerialEntry->product->name }} Model: {{ $dongleOrSerialEntry->product->model }} Brand: {{ $dongleOrSerialEntry->product->brand->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Product Type</th>
                                            <td>{{ $dongleOrSerialEntry->product_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dongle Id/Serial No</th>
                                            <td>{{ $dongleOrSerialEntry->dongle_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Software Version</th>
                                            <td>{{ optional($dongleOrSerialEntry)->software_version }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dongle Status</th>
                                            <td>{{ $dongleOrSerialEntry->status }}</td>
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
@endsection

