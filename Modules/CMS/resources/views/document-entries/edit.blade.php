@section('title', 'Document Entry')
@section('description', 'Edit Document Entry')
@extends('layout.app')

@section('content')
<style>
    .form-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        border-radius: 16px;
        background: #fff;
    }
    .vertical-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 800;
        color: #1e293b;
        border-left: 3px solid #5f63f2;
        padding-right: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 1.1rem;
    }
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .input-styled {
        border-radius: 10px !important;
        padding: 12px 15px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }
    .input-styled:focus {
        background-color: #fff;
        border-color: #5f63f2 !important;
        box-shadow: 0 0 0 4px rgba(95, 99, 242, 0.1) !important;
    }
    .btn-update {
        padding: 12px 40px;
        font-weight: 700;
        border-radius: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(95, 99, 242, 0.3);
        transition: all 0.3s ease;
    }
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(95, 99, 242, 0.4);
    }
</style>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main d-flex justify-content-between align-items-center py-4">
                <div class="d-flex align-items-start gap-3">
                    {{-- <h4 class="vertical-title mt-4">
                        {{ trans('Edit Document') }}
                    </h4> --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Document</li>
                        </ol>
                    </nav>
                </div>
                <div class="action-btn">
                    <a href="{{ route('cms.document-entries.index') }}"
                        class="btn btn-outline-warning btn-sm radius-md px-3">
                        <i class="fa fa-list me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <x-error-alart />
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="card form-card">
                <div class="card-body p-40">
                    <form action="{{ route('cms.document-entries.update', $documentEntry->id ) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        <div class="row g-4">
                            {{-- Document Type --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_type_id" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="document_type_id" id="document_type_id" required>
                                        <option value="">Select a Document Type</option>
                                        @foreach ($documentTypes as $item)
                                            <option value="{{ $item->id }}" @if (old('document_type_id', $documentEntry->document_type_id) == $item->id) selected @endif>
                                                {{ $item->name }}
                                            </option>
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
                                            <option value="{{ $item->id }}" {{ old('document_head_id', $documentEntry->document_head_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control flatdate input-styled" name="date" id="date"
                                            value="{{ old('date', $documentEntry->date) }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- File Attachment --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span></label>
                                    <x-file-uploader name="attachment" :value="$documentEntry->attachment" />
                                </div>
                            </div>

                            {{-- Remarks --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Remarks / Notes</label>
                                    <textarea name="remarks" id="remarks" class="form-control input-styled" rows="4" 
                                        placeholder="Enter additional details regarding this document...">{{ old('remarks', $documentEntry->remarks) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-40">
                            <button type="submit" class="btn btn-primary btn-update">
                                <i class="las la-sync-alt me-1"></i> Update Record
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
                        
                        // Sync TomSelect if it exists
                        if ($headSelect[0].tomselect) {
                            $headSelect[0].tomselect.clearOptions();
                            $headSelect[0].tomselect.sync();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
</script>
@endSection