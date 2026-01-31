@section('title', 'Document Entry')
@section('description', 'Document Entry')
@extends('layout.app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Document Entry') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('cms.document-entries.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                class="fa fa-list"></i> List</a>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mb-2">
                    <h4 class="text-capitalize">{{ trans('Document Entry') }}</h4>
                </div>
                <x-error-alart />
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('cms.document-entries.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-2">
                                <div class="form-group col-md-6">
                                    <label for="document_type_id">Document Type<span class="text-danger">*</span></label>
                                    <select class="form-control tom-select" name="document_type_id"
                                        id="document_type_id" required>
                                        <option value="">Select a Document Type</option>
                                        @foreach ($documentTypes as $item)
                                            <option value="{{ $item->id }}" {{ old('document_type_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group col-md-6">
                                    <label for="document_head_id">Document Head<span class="text-danger">*</span></label>
                                    <select class="form-control tom-select" name="document_head_id"
                                        id="document_head_id" required>
                                        <option value="">Select a Document Head</option>
                                        @foreach ($documentHeads as $item)
                                            <option value="{{ $item->id }} {{ old('document_head_id') == $item->id ? 'selected' : '' }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate " name="date" id="date"
                                        placeholder="Date" value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <x-file-uploader name="attachment" />
                                    {{-- <input id="attachment" type="file" class="file-control form-control" name="attachment"
                                        class="file-control" data-preview-element="front-image-preview" required> --}}
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="remarks">Remarks </label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks">{{ old('remarks') }}</textarea>
                                </div>
                            </div>


                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
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
            $('#document_head_id').empty();
            $('#document_head_id').append('<option value="">Select a Document Head</option>');
            
            if(document_type_id) {
                $.ajax({
                    url: "{{ route('cms.document-heads.list') }}?id=" + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        
                        $.each(data, function(key, value) {
                            $('#document_head_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                        $('#document_head_id').prop('tomselect').clearOptions();

                        $('#document_head_id').prop('tomselect').sync();
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
