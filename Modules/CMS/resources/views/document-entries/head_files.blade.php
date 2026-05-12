@section('title', "Document Report")
@section('description', "Document Report")
@extends('layout.app')

@section('content')
<style>
    .file-card {
        background: #fff;
        border-radius: 15px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
        border-color: #5f63f2;
    }

    .file-type-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
        margin-right: 15px;
    }

    .bg-light-pdf { background-color: #fff0f0; color: #ff4d4f; }
    .bg-light-image { background-color: #f0f7ff; color: #1890ff; }
    .bg-light-file { background-color: #f5f5f5; color: #595959; }

    .file-title {
        font-weight: 600;
        color: #272b41;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .btn-xs {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 6px;
    }
</style>

<div class="container-fluid">
    {{-- Header with Navigation --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            {{-- <h4 class="mb-1">Files: <span class="text-primary">{{ $head->name }}</span></h4> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('cms.document-entries.index') }}">Categories</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.document-entries.type-heads', $head->document_type_id) }}">
                            {{ $documentEntries->first()->documentType->name ?? 'Heads' }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $head->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 d-flex justify-content-end">
            <a href="javascript:history.back()" class="btn btn-sm btn-white border px-3 shadow-sm text-primary">
                <i class="las la-arrow-left me-1"></i> Back to Heads
            </a>
        </div>
    </div>

    {{-- File Grid --}}
    <div class="row g-4">
        @forelse ($documentEntries as $entry)
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card file-card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            @php
                                $ext = pathinfo($entry->attachment, PATHINFO_EXTENSION);
                                $iconClass = 'bg-light-file';
                                $icon = 'la-file-alt';
                                
                                if(in_array($ext, ['pdf'])) { $iconClass = 'bg-light-pdf'; $icon = 'la-file-pdf'; }
                                elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'svg'])) { $iconClass = 'bg-light-image'; $icon = 'la-file-image'; }
                            @endphp

                            <div class="file-type-icon {{ $iconClass }}">
                                <i class="las {{ $icon }}"></i>
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="file-title text-truncate" title="{{ $entry->title }}">
                                    {{ $entry->title ?? 'Untitled Document' }}
                                </div>
                                <div class="small text-muted">
                                    Start:{{ $entry->start_date }}, Expiry: {{ $entry->expiry_date }} 
                                </div>
                            </div>
                        </div> 

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="badge badge-light text-uppercase" style="font-size: 10px;">{{ $ext ?: 'FILE' }}</span>
                            <div class="btn-group">
                                @if($entry->attachment)
                                    <a href="{{ asset($entry->attachment) }}" target="_blank" 
                                       class="btn btn-xs btn-outline-info me-1" title="View">
                                        <i class="las la-eye"></i>
                                    </a>
                                    <a href="{{ asset($entry->attachment) }}" download 
                                       class="btn btn-xs btn-outline-primary" title="Download">
                                        <i class="las la-download"></i>
                                    </a>
                                @else
                                    <span class="text-danger small">No Attachment</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white rounded-20 p-5 shadow-sm">
                    <img src="{{ asset('assets/img/no-file.png') }}" width="100" style="opacity: 0.5;">
                    <h5 class="text-muted mt-3">No files attached in this head.</h5>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            {{ $documentEntries->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection