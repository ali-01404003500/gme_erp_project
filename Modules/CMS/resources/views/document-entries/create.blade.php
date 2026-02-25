@section('title', 'Document Entry')
@section('description', 'Document Entry')
@extends('layout.app')

@section('content')
<style>
    /* Modern Mesh Gradient Background */
    body {
        background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.12) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(0, 212, 255, 0.12) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(95, 99, 242, 0.08) 0px, transparent 50%),
                    #f8fafc !important;
        min-height: 100vh;
    }

    /* Vertical Title Aesthetic (Consistent with List View) */
    /* .vertical-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 800;
        color: #1e293b;
        border-left: 4px solid #5f63f2;
        padding-right: 15px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        margin-top: 10px;
    } */

    /* Glassmorphism Form Card */
    .form-card {
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        border-radius: 20px !important;
    }

    /* Bold Form Labels */
    .form-label {
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-styled {
        border-radius: 12px !important;
        padding: 12px 15px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease;
    }

    .input-styled:focus {
        border-color: #5f63f2 !important;
        box-shadow: 0 0 0 4px rgba(95, 99, 242, 0.1) !important;
    }

    .section-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .btn-submit {
        background: linear-gradient(90deg, #5f63f2, #7928ca);
        border: none;
        padding: 12px 40px;
        font-weight: 700;
        border-radius: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
        box-shadow: 0 4px 15px rgba(95, 99, 242, 0.3);
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(95, 99, 242, 0.4);
        color: white;
    }

    .breadcrumb-main {
        background: transparent;
    }
</style>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-lg-12">
            {{-- Header Section --}}
            <div class="breadcrumb-main d-flex justify-content-between align-items-center py-4">
                <div class="d-flex align-items-start gap-4">
                    {{-- <h4 class="vertical-title">{{ trans('Document Entry') }}</h4> --}}
                    
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-muted"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Create New</li>
                        </ol>
                    </nav>
                </div>
                <div class="action-btn">
                    <a href="{{ route('cms.document-entries.index') }}" class="btn btn-outline-warning btn-sm px-4 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-list me-1"></i> View List
                    </a>
                </div>
            </div>
            <x-error-alart />
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="card form-card">
                <div class="card-body p-40 p-lg-5">
                    <div class="section-header">
                        <h4 class="fw-800 text-dark mb-1">{{ trans('Archive New Document') }}</h4>
                        <p class="text-muted small">Enter the metadata and upload the file to secure it in the registry.</p>
                    </div>

                    <form action="{{ route('cms.document-entries.store', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            {{-- Document Type --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_type_id" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="document_type_id" id="document_type_id" required>
                                        <option value="">Select a Document Type</option>
                                        @foreach ($documentTypes as $item)
                                            <option value="{{ $item->id }}" {{ old('document_type_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Document Head --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_head_id" class="form-label">Document Head <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="document_head_id" id="document_head_id" required>
                                        <option value="">Select a Document Head</option>
                                        @foreach ($documentHeads as $item)
                                            <option value="{{ $item->id }}" {{ old('document_head_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate input-styled shadow-sm" name="date" id="date" 
                                        value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            {{-- File Upload --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="attachment" class="form-label">Upload Attachment <span class="text-danger">*</span></label>
                                    <div class="custom-file-wrapper shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <x-file-uploader name="attachment" />
                                    </div>
                                </div>
                            </div>

                            {{-- Remarks --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Remarks / Description</label>
                                    <textarea name="remarks" id="remarks" class="form-control input-styled shadow-sm" rows="4" placeholder="Add any additional context or reference details here...">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit" class="btn btn-primary btn-submit shadow-lg">
                                <i class="las la-save me-2"></i> Save Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#document_type_id').on('change', function() {
            var id = $(this).val();
            var $headSelect = $('#document_head_id');
            
            $headSelect.empty();
            $headSelect.append('<option value="">Select a Document Head</option>');
            
            if(id) {
                $.ajax({
                    url: "{{ route('cms.document-heads.list') }}?id=" + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $headSelect.append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                        
                        // Sync TomSelect if initialized
                        if ($headSelect[0].tomselect) {
                            $headSelect[0].tomselect.clearOptions();
                            $headSelect[0].tomselect.sync();
                        }
                    },
                    error: function(data) {
                        console.error('Error fetching heads:', data);
                    }
                });
            }
        });
    });
</script>
@endSection