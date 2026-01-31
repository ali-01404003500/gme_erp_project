@section('title', 'Document Type List')
@section('description', 'Document Type List')
@extends('layout.app')
@section('content')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Document Type') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('cms.document-types.create'))
                                <button class="btn btn-xs btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    Add New
                                </button>
                            @endif
                                {{-- <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Document Type') }}</h4>
                        </div>
                        <x-error-alart />

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ request('name') }}" autocomplete="off"
                                                        placeholder="Search by Document Type Name">
                                                </td>
                                                <td colspan="5" class="text-right">
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
                            <table id="zero-config" class="table dt-table-hover"  data-page='@include('utils.table_paginate', ['data' => $documentTypes])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($documentTypes as $value)
                                        <tr>
                                            <td>{{ ($documentTypes->currentPage() - 1) * $documentTypes->perPage() + $loop->iteration  }}</td>
                                          <td class="text-center">{{ $value->name }}</td>
                                            <td class="text-center">{{ $value->description }}</td>
                                            <td class="text-left">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('cms.document-types.update'))
                                                    <a href={{ $value->id }} class="btn btn-edit  btn-outline-warning"
                                                        data-name="{{ $value->name }}" 
                                                        data-description="{{ $value->description }}"
                                                        data-action="{{ route('cms.document-types.update', $value->id) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    @endif

                                                    @if (hasPermission('cms.document-types.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('cms.document-types.destroy', $value->id) }}"
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

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Document Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('cms.document-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                 
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Document Type Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name *"
                                                required>
                                                <input type="text" name="type" class="form-control" value="Document Type" hidden>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Description</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" name="description" class="form-control" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                   
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Document Type Name</label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="description" class="col-sm-12 col-form-label">Description</label>
                            <div class="col-sm-12">
                                <textarea name="description" id="description" class="form-control" type="text"></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                $('#name').val($(this).data('name'));
                $('#description').text($(this).data('description'));
                $("#editFrom").attr("action", $(this).data('action'));

            });
        });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
@endsection
