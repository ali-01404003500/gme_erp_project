@section('title', 'Daily Call Details')
@section('description', 'Daily Call Details')
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
                                        {{ trans('menu.daily-call-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <a href="{{ route('crm.daily-calls.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Daily Call Details') }}</h4>
                    <br>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-header d-flex justify-content-between">
                                {{-- <h5 class="card-title">Supplier Details</h5> --}}
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Call Date</th>
                                                <td>{{ date('Y-m-d', strtotime($dailyCall->call_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Customer Name</th>
                                                <td>
                                                    {{ optional($dailyCall->customer)->company_name }}<br>
                                                    <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $dailyCall->customer->area?->area }}</small> <br>
                                                </td>
                                            </tr>
                                      
                                            <tr>
                                                <th scope="row">Call Type</th>
                                                <td>
                                                    {{ $dailyCall->call_type_id == 1 ? 'Regular Call' : 'Service Call' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Account Complain</th>
                                                <td >
                                                    @if($dailyCall->is_account_complain == 1) Yes @else No @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Account Complain Details</th>
                                                <td  class="text-wrap">{{ $dailyCall->complains_details }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Service Complain</th>
                                                <td>{{ $dailyCall->is_service_complain == 1 ? 'Yes' : 'No' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Service Complain Details</th>
                                                <td  class="text-wrap">{{ $dailyCall->service_complain_details }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Sales Complain</th>
                                                <td>{{ $dailyCall->is_sales_complain == 1 ? 'Yes' : 'No' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Sales Complain Details</th>
                                                <td  class="text-wrap">{{ $dailyCall->sales_complain_details }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Requirement of Product</th>
                                                <td >
                                                    {{ $dailyCall->is_product_required == 1 ? 'Yes' : 'No' }}
                                                </td>
                                            </tr>
                                            <tr >
                                                <th scope="row">Requirement of Product Details</th>
                                                <td   class="text-wrap">{{ $dailyCall->product_required_details }}</td>
                                            </tr>
                                            <tr >
                                                <th scope="row">About Of Company/Remarks</th>
                                                <td  class="text-wrap">{{ $dailyCall->remarks }}</td>
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
