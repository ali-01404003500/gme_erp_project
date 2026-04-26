@section('title',"Supplier List")
@section('description',"Supplier List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.supplier-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('purchase.suppliers.create'))
                            <a href="{{ route('purchase.suppliers.create') }}" class="btn px-20 btn-primary btn-sm">
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
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.supplier-list-menu-title') }}</h4>
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
                                                <select name="company_name" id="company_name" class="tom-select input-sm"
                                                    data-placeholder="Select Supplier">
                                                    <option value=""></option>
                                                    @foreach ($supplierSearch as $value)
                                                        <option
                                                            {{ request('company_name') == $value->company_name ? 'selected' : '' }}
                                                            value="{{ $value->company_name }}">
                                                            {{ $value->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-left">
                                                <input type="text" class="form-control" placeholder="Search Phone Number" name="phone" value="{{ request('phone') }}">
                                            </td>
                                            <td class="text-left">
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
                        <style>
                            .supplier-table-custom,
                            .supplier-table-custom th,
                            .supplier-table-custom td {
                                border: 1px solid #dee2e6 !important;
                                border-collapse: collapse !important;
                            }
                            .supplier-table-custom th,
                            .supplier-table-custom td {
                                padding: 12px;
                                vertical-align: middle;
                            }
                            .supplier-table-custom thead th {
                                background-color: #f8f9fa;
                                border-bottom-width: 2px !important;
                            }
                            .table thead th {
                            background-color: #35526e !important;
                            color: #ffffff !important;
                            font-weight: 600 !important;
                            text-transform: uppercase;
                            font-size: 0.85rem !important;
                            letter-spacing: 0.08em;
                            border-bottom: 2px solid #2a4054 !important;
                            padding: 14px 16px !important;
                            vertical-align: middle;
                            text-align: center;
                        }
                        </style>
                        
                        <table id="zero-config" class="table supplier-table-custom dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $suppliers])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Supplier Name</th>
                                    <th>Contact Phone</th>
                                    <th>Email</th>
                                    <th style="width: 150px!important;">Address</th>
                                    <th>Opening Balance</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td class="text-left">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration  }}</td>
                                        <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                            <a href="{{ route('purchase.suppliers.show', $supplier->id) }}">{{ $supplier->company_name }}</a>
                                        </td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ $supplier->address }}</td>
                                        <td>{{ $supplier->opening_balance }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if (hasPermission('purchase.suppliers.update'))
                                                    <a class="btn btn-outline-warning" href="{{ route('purchase.suppliers.edit', $supplier->id) }}"><i
                                                            class="far fa-edit"></i></a>
                                                @endif
                                                    
                                                @if (hasPermission('purchase.suppliers.destroy'))
                                                    <button type="button" data-action="{{ route('purchase.suppliers.destroy', $supplier->id) }}"
                                                        class="btn btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>
                                                @endif
                                                
                                                @if (hasPermission('purchase.suppliers.show'))
                                                    <a class="btn btn-outline-primary" href="{{ route('purchase.suppliers.show', $supplier->id) }}"><i class="fas fa-eye"></i></a>
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
                <form action="{{ route('purchase.suppliers-insert') }}" method="post" id="importFrom"
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
                                <a href="{{ route('purchase.suppliers-download') }}" class="btn btn-info">Download Sample CSV</a>
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