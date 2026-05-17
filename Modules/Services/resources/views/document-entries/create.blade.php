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
                        <a href="{{ route('services.document-entries.index') }}"
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
                        <form action="{{ route('services.document-entries.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-2">
                               

                                <div class="form-group col-md-6">
                                    <label for="document_date">Date<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate " name="document_date" id="document_date"
                                        placeholder="Date" value="{{ old('document_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="product_id">Product<span class="text-danger">*</span></label>
                                    <select name="product_id" id="product_id" class="form-control" required>
                                        <option value="">Select a Product</option> 
                                    </select>
                                   
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="remarks">Remarks </label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks">{{ old('remarks') }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <x-file-uploader name="documents" />
                                    {{-- <input id="attachment" type="file" class="file-control form-control" name="attachment"
                                        class="file-control" data-preview-element="front-image-preview" required> --}}
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
<script>
    $(document).ready(function () {
        const productSelect = new TomSelect("#product_id", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        productSelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 
    }); 
 </script>
 @endsection
