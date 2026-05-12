@section('title',"Vendor List")
@section('description',"Vendor List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.vendor-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('purchase.vendors.create'))
                            <a href="{{ route('purchase.vendors.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                            <button type="button" class="btn btn-xs btn-success btn-sm me-2 ml-5" data-bs-toggle="modal" style="margin-left: 5px;"
                            data-bs-target="#importModal">
                            <i class="las la-file-import fs-16"></i> Import CSV
                        </button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.vendor-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>

                                            <td>
                                                <select name="company_name" id="company_name" class="tom-select form-control"
                                                    data-placeholder="Select Supplier">
                                                    <option value=""></option>
                                                    @foreach ($vendorSearch as $value)
                                                        <option
                                                            {{ request('company_name') == $value->company_name ? 'selected' : '' }}
                                                            value="{{ $value->company_name }}">
                                                            {{ $value->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control" placeholder="Search Phone Number" name="phone" value="{{ request('phone') }}">
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control" placeholder="Search Address" name="address" value="{{ request('address') }}">
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
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $vendors])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Vendor Name</th>
                                    <th>Contact Phone</th>   

                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($vendors as $value)
                                    <tr>
                                        <td class="text-center">{{ ($vendors->currentPage() - 1) * $vendors->perPage() + $loop->iteration  }}</td>
                                        <td>
                                            <a href="{{ route('purchase.vendors.show', $value->id) }}">{{ $value->company_name }}</a><br>
                                            <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $value->address }}</small> 
                                        </td>
                                        <td>{{ $value->phone }}</td>  
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if (hasPermission('purchase.vendors.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('purchase.vendors.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif
            
                                                    @if (hasPermission('purchase.vendors.destroy'))
                                                        <button type="button" data-action="{{ route('purchase.vendors.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
            
                                                    @if (hasPermission('purchase.vendors.show'))
                                                        <a class="btn btn-outline-primary" href="{{ route('purchase.vendors.show', $value->id) }}"><i class="fas fa-eye"></i></a>
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
    <div class="modal fade inputForm-modal" id="importModal" tabindex="-1" role="dialog"
    aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" id="importModalLabel">
                <h5 class="modal-title">Import from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('purchase.vendors-insert') }}" method="post" id="importFrom"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <label for="csv_file" class="col-sm-12 col-form-label">CSV File</label>
                        <div class="col-sm-12">
                            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <a href="{{ route('purchase.vendors-download') }}" class="btn btn-info">Download Sample CSV</a>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection