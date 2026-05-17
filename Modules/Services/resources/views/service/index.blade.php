@section('title', 'Service List')
@section('description', 'Service List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('service list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('services.service.create'))
                            <a href="{{ route('services.service.create') }}" class="btn px-20 btn-primary btn-sm mr-5">
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
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('service list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                {{-- <td class="text-center">
                                                    <select name="company_name" id="company_name" class="form-control tom-select"
                                                        data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                        @foreach ($customers as $key => $value)
                                                            <option {{ request('company_name') == $value->company_name ? 'selected' : '' }}
                                                                value="{{ $value->company_name }}">
                                                                {{ $value->company_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ request('phone') }}" autocomplete="off"
                                                        placeholder="Search by Phone">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="email"
                                                        value="{{ request('email') }}" autocomplete="off"
                                                        placeholder="Search by Email">
                                                </td>
                                                <td colspan="5" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td> --}}
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="service_unique_id"
                                                        value="{{ request('service_unique_id') }}" autocomplete="off"
                                                        placeholder="Search by Service Unique ID">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control flatdaterange" name="from_to"
                                                        value="{{ request('from_to') }}"
                                                        placeholder="From To Date">
                                                </td>
                                                <td class="text-center">
                                                    <select name="service_type" id="service_type" class="form-control tom-select"
                                                        data-placeholder="Search by service type">
                                                        <option value=""></option>
                                                        @foreach ($serviceTypes as $key => $value)
                                                            <option {{ request('service_type') == $value->name  ? 'selected' : '' }}
                                                                value="{{ $value->name }}">
                                                                {{ $value->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select name="action" id="status" class="form-control tom-select"
                                                        data-placeholder="Search by status">
                                                        <option value=""></option>
                                                        <option value="entry" {{ request('status') == 'Entry' ? 'selected' : '' }}>Entry</option>
                                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $service])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Customer</th> 
                                        <th>
                                            Service ID
                                        </th>
                                        <th>
                                            Service Date
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @foreach ($service as $services)
                                        <tr>
                                            <td class="text-center">{{ ($service->currentPage() - 1) * $service->perPage() + $loop->iteration  }}</td>
                                            <td>
                                               
                                                {{ $services->serviceTokens[0]->customer->company_name ?? '' }}<br>
                                            
                                                <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $services->serviceTokens[0]->customer->area?->area ?? '' }}</small> 
                                            </td>
                                            <td>
                                                {{ $services->service_unique_id }}
                                            </td>
                                            <td>
                                                {{optional($services->serviceTokens[0] ?? null)->token_date }}
                                            </td>
                                            <td>
                                                @if($services->action == "entry")
                                                    <span class="badge badge-round badge-warning text-capitalize">{{ $services->action }}</span>
                                                @elseif($services->action == "Pending" || $services->action == "pending")
                                                    <span class="badge badge-round badge-info text-capitalize">{{ $services->action }}</span>
                                                @elseif($services->action == "Failed")
                                                    <span class="badge badge-round badge-danger text-capitalize">{{ $services->action }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if (hasPermission('services.service.update'))
                                                    <a class="btn btn-xs btn-outline-warning"
                                                        href="{{ route('services.service.edit', $services->id) }}"><i
                                                            class="far fa-edit"></i></a>
                                                @endif

                                                @if (hasPermission('services.service.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('services.service.destroy', $services->id) }}"
                                                        class="btn btn-xs btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>
                                                @endif

                                                @if (hasPermission('services.service.show'))
                                                    <a class="btn btn-xs btn-outline-primary"
                                                        href="{{ route('services.service.show', $services->id) }}"><i
                                                            class="fas fa-eye"></i></a>
                                                @endif

                                                {{-- @if (hasPermission('services.service.settings'))
                                                    <a class="btn btn-xs btn-outline-info"
                                                        href="{{ route('services.service.settings', $services->id) }}"><i
                                                            class="fas fa-cog"></i></a>
                                                @endif --}}
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
