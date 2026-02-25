@section('title', "Document Entry List")
@section('description', "Document Entry List")
@extends('layout.app')

@section('content')
<style>
    /* Modern Mesh Gradient Background */
    body {
        background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.15) 0px, transparent 50%),
                    radial-gradient(at 50% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(0, 212, 255, 0.15) 0px, transparent 50%),
                    #f4f7fe !important;
        min-height: 100vh;
    }

    .container-fluid {
        padding-top: 25px;
        padding-bottom: 50px;
    }

    /* Glassmorphism Card Style */
    .card {
        border: 1px solid rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border-radius: 16px;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }

    .filter-card {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(95, 99, 242, 0.1);
    }

    /* NEW: Vertical Title Style */
    /* .vertical-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 800;
        color: #1e293b;
        border-left: 4px solid #5f63f2; /* Left accent border */
        padding-right: 15px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        margin-top: 10px;
    } */

    /* FULL TABLE BORDER REFINEMENT */
    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0; /* Outer boundary */
    }

    .table-bordered {
        border: 1px solid #e2e8f0 !important;
    }

    .table-bordered th, 
    .table-bordered td {
        border: 1px solid #e2e8f0 !important; /* Full grid visible */
    }

    /* UPDATED: Table Header - Medium Big & Bold */
    .table thead th {
        background-color: rgba(95, 99, 242, 0.08) !important;
        color: #0f172a !important; /* Deep dark for contrast */
        font-weight: 800 !important; /* Extra Bold */
        text-transform: uppercase;
        font-size: 0.95rem !important; /* Medium-Big size */
        letter-spacing: 0.05em;
        border-bottom: 2px solid #5f63f2 !important; 
        padding: 18px 15px !important;
        vertical-align: middle;
        text-align: center;
    }

    .table tbody td {
        padding: 15px !important;
        vertical-align: middle !important;
        background: transparent;
        color: #334155;
    }

    .table tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.5);
    }

    /* Floating Action Buttons */
    .action-btn-group .btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        margin: 0 3px;
        border-radius: 8px !important;
        transition: all 0.2s;
    }

    .action-btn-group .btn:hover {
        background: #5f63f2;
        color: white !important;
        border-color: #5f63f2;
        transform: translateY(-2px);
    }

    /* Badges */
    .badge-soft-info {
        background-color: rgba(3, 105, 161, 0.1);
        color: #0369a1;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .breadcrumb-main {
        background: transparent;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <div class="social-dash-wrap">
        {{-- Header Section --}}
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                    <div class="breadcrumb-action d-flex align-items-start gap-4">
                        {{-- <h4 class="vertical-title">{{ trans('Document Entry List') }}</h4> --}}

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="#" class="text-muted"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active text-primary" aria-current="page fw-bold">Document Entry List</li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="breadcrumb-main__wrapper mt-sm-0 mt-3">
                        <div class="action-btn d-flex align-items-center gap-2">
                            @if (hasPermission('cms.document-entries.create'))
                                <a href="{{ route('cms.document-entries.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm border-0" style="border-radius: 10px; background: linear-gradient(90deg, #5f63f2, #7928ca);">
                                    <i class="las la-plus fs-16 me-1"></i> Add New Entry
                                </a>
                            @endif
                            <a href="{{ route('cms.document-entries.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                               target="_blank" class="btn btn-white btn-sm d-flex align-items-center border shadow-sm px-3" style="border-radius: 10px; background: white;">
                                <i class="las la-file-pdf fs-18 mr-1 text-danger"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card filter-card">
                    <div class="card-body py-4">
                        <form action="" method="GET">
                            <div class="row align-items-end g-3">
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label text-dark small fw-bold mb-2">FILTER BY CATEGORY</label>
                                    <div class="search-input-group">
                                        <select name="document_type_id" id="document_type_id" class="form-control tom-select tom-select-modern" data-placeholder="Choose type...">
                                            <option value=""></option>
                                            @foreach ($documentTypes as $value)
                                                <option {{ request('document_type_id') == $value->id ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 10px; height: 44px;">
                                        <i class="fa fa-search me-2"></i> Filter
                                    </button>
                                    <a href="{{ request()->url() }}" class="btn btn-light px-4" style="border-radius: 10px; border: 1px solid #e2e8f0; height: 44px; display: flex; align-items: center;">
                                        <i class="fa fa-refresh me-2"></i> Refresh
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive table-container">
                            <table id="zero-config" class="table table-bordered mb-0" data-page='@include('utils.table_paginate', ['data' => $documentEntries])'>
                                <thead>
                                    <tr>
                                        <th class="text-center" width="60">Sl</th>
                                        <th>Document Type</th>
                                        <th>Document Head</th>
                                        <th>Remarks</th>
                                        <th>Entry Date</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentEntries as $value)
                                        <tr class="text-center">
                                            <td class="text-center fw-bold text-muted small">
                                                {{ ($documentEntries->currentPage() - 1) * $documentEntries->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <span class="badge-soft-info">{{ @$value->documentType->name }}</span>
                                            </td>
                                            <td class="fw-bold text-dark">{{ $value->documentHead->name }}</td>
                                            <td class="text-muted" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $value->remarks ?? '---' }}
                                            </td>
                                            <td>
                                                <span class="text-dark small fw-600">
                                                    <i class="lar la-calendar me-1 text-primary"></i> {{ $value->date }}
                                                </span>
                                            </td>
                                            <td class="text-end px-4">
                                                <div class="btn-group action-btn-group">
                                                    <a class="btn btn-sm text-info" href="{{ $value->attachment }}" download title="Download">
                                                        <i class="las la-download fs-16"></i>
                                                    </a>

                                                    @if(hasPermission('cms.document-entries.update'))
                                                        <a class="btn btn-sm text-warning" href="{{ route('cms.document-entries.edit', $value->id) }}" title="Edit">
                                                            <i class="lar la-edit fs-16"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('cms.document-entries.destroy'))
                                                        <button type="button" data-action="{{ route('cms.document-entries.destroy', $value->id) }}" 
                                                                class="btn btn-sm text-danger delete-confirm" title="Delete">
                                                            <i class="lar la-trash-alt fs-16"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
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