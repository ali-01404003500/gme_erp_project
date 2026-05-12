@section('title', "Document Report")
@section('description', "Document Report")

@extends('layout.app')

@section('content')
<style>
    .folder-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #eee;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .folder-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #ffca28;
    }

    .folder-icon-wrapper {
        margin-bottom: 15px;
    }

    .folder-icon {
        font-size: 60px;
        color: #ffca28; /* Classic folder yellow */
    }

    .file-name {
        font-weight: 600;
        color: #333;
        font-size: 15px;
        display: block;
        margin-top: 10px;
        line-height: 1.4;
    }

    .folder-link {
        text-decoration: none !important;
        width: 100%;
    }

    .head-count {
        font-size: 12px;
        color: #888;
        background: #f8f9fa;
        padding: 2px 8px;
        border-radius: 10px;
        margin-top: 5px;
    }
</style>

<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 ">
        <div class="col-md-8">
            {{-- <h4 class="mb-1">Category: <span class="text-primary">{{ $type->name }}</span></h4> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('cms.document-entries.index') }}">All Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $type->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 d-flex justify-content-end">
            <a href="{{ route('cms.document-entries.document-reports') }}" class="btn btn-sm btn-light border px-3 text-primary">
                <i class="las la-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Folder Grid --}}
    <div class="row g-4">
        @forelse ($documentHeads as $entry)
            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6">
                <div class="folder-card">
                    <a href="{{ route('cms.document-entries.head-files', $entry->document_head_id) }}" class="folder-link">
                        <div class="folder-icon-wrapper">
                            {{-- এখানে folder-open আইকনটি দেখতে বেশি ভালো লাগে --}}
                            <i class="las la-folder-open folder-icon"></i>
                        </div>
                        <span class="file-name text-truncate" title="{{ $entry->documentHead->name ?? 'N/A' }}">
                            {{ $entry->documentHead->name ?? 'N/A' }}
                        </span> 
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="las la-folder-plus text-muted" style="font-size: 80px; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-3">No document heads found in this category</h5>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection