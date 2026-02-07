@section('title', "Document Entry List")
@section('description', "Document Entry List")
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
                                        {{ trans('Document Entry List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('cms.document-entries.create'))
                                    <a href="{{ route('cms.document-entries.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                                <a href="{{ route('cms.document-entries.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                    target="_blank" class="btn btn-danger btn-sm d-inline-block mr-2"
                                    style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
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
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td width="30%">
                                                    <select name="document_type_id" id="document_type_id"
                                                        class="form-control tom-select"
                                                        data-placeholder="Search by Document Type">
                                                        <option value=""></option>
                                                        @foreach ($documentTypes as $value)
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
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $documentEntries])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th>Document Type</th>
                                        <th>Document Head</th>
                                        <th>Note</th>
                                        <th>Date</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentEntries as $value)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($documentEntries->currentPage() - 1) * $documentEntries->perPage() + $loop->iteration  }}
                                            </td>
                                            <td>{{ @$value->documentType->name }}</td>
                                            <td>{{ $value->documentHead->name }}</td>
                                            <td>{{ $value->remarks }}</td>
                                            <td>{{ $value->date }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    <a class="btn btn-outline-info" href="{{ $value->attachment }}" download>
                                                        <i class="fa fa-download"></i>
                                                    </a>


                                                    @if(hasPermission('cms.document-entries.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('cms.document-entries.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif
                                                    @if(hasPermission('cms.document-entries.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('cms.document-entries.destroy', $value->id) }}"
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