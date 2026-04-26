@section('title',"Document Entry List")
@section('description',"Document Entry List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Document Entry List') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('services.document-entries.create'))
                            <a href="{{ route('services.document-entries.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            {{-- <a href="{{ route('services.document-entries.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('Document Entry list') }}</h4>
            </div>
            <div class="col-md-12">
                {{-- <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            
                                            <td width="30%">
                                                <select name="document_type_id" id="document_type_id" class="form-control tom-select"
                                                    data-placeholder="Search by Document Type">
                                                    <option value=""></option>
                                                    @foreach ($documentTypes as $value )
                                                        <option {{ request('document_type_id') == $value->id ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="70%">
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
                </div> --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config"class="table dt-table-hover table-bordered" data-page='@include('utils.table_paginate', ['data' => $serviceDocumentEntrys])' style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%";>Sl</th>
                                    <th style="width: 40%">Product</th>
                                    <th style="width: 10%">Model</th>
                                    <th style="width: 20%">Note</th>
                                    <th style="width: 10%">Date</th>
                                    <th class="no-content" style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceDocumentEntrys as $value )
                                {{-- @dd($value); --}}
                                    <tr>
                                        <td class="text-center">{{ ($serviceDocumentEntrys->currentPage() - 1) * $serviceDocumentEntrys->perPage() + $loop->iteration  }}</td>         
                                        <td class="text-wrap">{{ $value->product->withoutModelSuffix()->name }}</td>
                                        <td class="text-wrap">{{ $value->product->model }}</td>
                                        <td style="word-break: break-word; white-space: normal; min-width: 200px; max-width: 300px;">{{ $value->remarks }}</td>
                                        <td class="text-wrap">{{ $value->document_date }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">

                                                @if(hasPermission('services.document-entries.download'))
                                                    <a class="btn btn-outline-info" href="{{ $value->documents }}" download>
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @endif
                                                @if(hasPermission('services.document-entries.update'))
                                                    <a class="btn btn-outline-warning" href="{{ route('services.document-entries.edit', $value->id) }}"><i
                                                            class="far fa-edit"></i></a>
                                                @endif
                                                @if(hasPermission('services.document-entries.destroy'))
                                                    <button type="button" data-action="{{ route('services.document-entries.destroy', $value->id) }}"
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