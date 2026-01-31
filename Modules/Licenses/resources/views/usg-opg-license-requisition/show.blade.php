@section('title', 'USG/OPG License Requisition View')
@section('description', 'USG/OPG License Requisition View')
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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('licenses.usg-opg-license-requisitions.index') }}">
                                        {{ trans('menu.cbc-license-requisition-menu-title') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">View</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            @if (hasPermission('license.cbc-license-requisition.index'))
                                <a href="{{ route('licenses.usg-opg-license-requisitions.index') }}"
                                   class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">
                        {{ trans('USG/OPG License Requisition View') }}
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
                                            <td>{{ $uSGOrOPGLicenseRequisition->customer->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dongle ID</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->dongles->dongle_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Product Model</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->product_model }}</td>
                                        </tr>
                                        <tr>
                                            <th>Software Version</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->software_version }}</td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td>{{ \Carbon\Carbon::parse($uSGOrOPGLicenseRequisition->start_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Valid Period</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->valid_period }} {{ ucfirst($uSGOrOPGLicenseRequisition->valid_period_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Expired Date</th>
                                            <td>{{ \Carbon\Carbon::parse($uSGOrOPGLicenseRequisition->expired_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>{{ $uSGOrOPGLicenseRequisition->remarks }}</td>
                                        </tr>
                                        <tr>
                                            <th>Multiple Phone Nos</th>
                                            <td>
                                                @if($uSGOrOPGLicenseRequisition->phones && $uSGOrOPGLicenseRequisition->phones->count())
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($uSGOrOPGLicenseRequisition->phones as $phone)
                                                            <li>{{ $phone->multiple_phone_no }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
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
