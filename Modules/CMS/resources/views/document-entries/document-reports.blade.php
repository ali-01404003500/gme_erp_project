@section('title', "Document Report")
@section('description', "Document Report")
@extends('layout.app')

@section('content')
    <style>
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

        /* Folder Link Wrapper */
        .folder-link {
            text-decoration: none !important;
            color: inherit;
            display: block;
            height: 100%;
        }

        .folder-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 24px 15px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }

        .folder-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(95, 99, 242, 0.1);
            border-color: #5f63f2;
            background: #fff;
        }

        .folder-icon-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .folder-icon {
            font-size: 70px;
            color: #ffca28;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
        }

        .file-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.05rem;
            margin-bottom: 4px;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 10px;
        }

        .document-type-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5f63f2;
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
        }

        .file-info {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 15px;
        }

        .folder-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px dashed #e2e8f0;
            position: relative;
            z-index: 10;
        }

        .action-link {
            font-size: 1.2rem;
            transition: 0.2s;
            padding: 5px;
            text-decoration: none !important;
        }

        .action-link:hover {
            transform: scale(1.2);
        }
    </style>

    <div class="container-fluid">
        <div class="social-dash-wrap">
            {{-- Header --}}
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold text-dark mb-1">Document Repository</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i
                                        class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active text-primary fw-600" aria-current="page">Folder View</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-md-end mt-sm-0 mt-3">
                    
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body">
                    <form method="GET" action="{{ route('cms.document-entries.document-reports') }}">
                        <div class="row mb-4 ">
                            <div class="col-lg-4 col-md-6">
                                <label class="small fw-bold text-muted mb-1">Filter by Type</label>
                            
                                <select class="form-control tom-select input-styled" name="document_type_id" id="document_type_id">
                                    <option value="">All Categories</option>
                                    @foreach ($documentTypes as $type) 
                                        <option {{ request('document_type_id') == $type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                            <div class="col-lg-4 col-md-6 pt-4 d-flex  gap-2">
                                <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px; height: 42px;">
                                    <i class="las la-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('cms.document-entries.index') }}" class="btn btn-light px-4 border"
                                    style="border-radius: 10px; height: 42px;">
                                    <i class="las la-undo-alt me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Grid View --}}
            <div class="row g-4 " style="margin-bottom: 100px;">
                
                @forelse($documentEntries as $item)
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6">
                        <div class="folder-card">
                            {{-- এখানে $type->id পাঠাতে হবে --}}
                            <a href="{{ route('cms.document-entries.type-heads', $item->document_type_id) }}" class="folder-link">
                                <div class="folder-icon-wrapper">
                                    <i class="las la-folder folder-icon"></i>
                                </div>
                                <span class="document-type-label">{{ $item->documentType->name }}</span>
                             
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No Categories Found</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

     
@endsection