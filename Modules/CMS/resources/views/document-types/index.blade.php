@section('title', 'Document Type List')
@section('description', 'Document Type List')
@extends('layout.app')

@section('content')
    <style>
         
        
    </style>

    <div class="container-fluid">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-muted"><i class="las la-home"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                                {{ trans('Document Type') }}
                            </li>
                        </ol>
                    </nav>
                    <div class="action-btn">
                        @if (hasPermission('cms.document-types.create'))
                            <button class="btn btn-primary btn-sm px-4 shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="las la-plus me-1"></i> Add New Type
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter/Search Section --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-body py-3">
                        <form>
                            <div class="row align-items-center g-3">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                        placeholder="Search by Document Type Name..." autocomplete="off">
                                </div>
                                <div class="col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4 w-100" style="height: 44px;"><i
                                            class="fa fa-search me-2"></i>Search</button>
                                    <a href="{{ request()->url() }}" class="btn btn-light border px-4 w-100"
                                        style="height: 44px; display: flex; align-items: center; justify-content: center;"><i
                                            class="fa fa-refresh me-2"></i>Reset</a>
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
                <x-error-alart />
                <div class="card border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive table-container">
                            <table id="zero-config" class="table condition-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $documentTypes])'>
                                <thead>
                                    <tr>
                                        <th width="60">Sl</th>
                                        <th>Document Name</th>
                                        <th>Description</th>
                                        <th width="150">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentTypes as $value)
                                        <tr class="text-left">
                                            <td class="text-left  text-muted small">
                                                {{ ($documentTypes->currentPage() - 1) * $documentTypes->perPage() + $loop->iteration }}
                                            </td>
                                            <td class=" text-dark text-left">{{ $value->name }}</td>
                                            <td class="text-muted">{{ $value->description ?? '---' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group action-btn-group shadow-sm">
                                                    @if (hasPermission('cms.document-types.update'))
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning btn-edit"
                                                            data-name="{{ $value->name }}"
                                                            data-description="{{ $value->description }}"
                                                            data-action="{{ route('cms.document-types.update', $value->id) }}"
                                                            data-bs-toggle="modal" data-bs-target="#editModal" title="Edit">
                                                            <i class="lar la-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('cms.document-types.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('cms.document-types.destroy', $value->id) }}"
                                                            class="btn btn-sm delete-confirm btn-outline-danger" title="Delete">
                                                            <i class="lar la-trash-alt"></i>
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
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">Create Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('cms.document-types.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DOCUMENT TYPE NAME</label>
                            <input type="text" name="name" class="form-control shadow-sm" placeholder="Enter name *"
                                required>
                            <input type="hidden" name="type" value="Document Type">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                            <textarea name="description" class="form-control shadow-sm" rows="3"
                                placeholder="Optional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Save Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">Update Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf @method('put')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DOCUMENT TYPE NAME</label>
                            <input name="name" id="name" class="form-control shadow-sm" type="text" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                            <textarea name="description" id="description" class="form-control shadow-sm"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Update Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-edit', function () {
                $('#name').val($(this).data('name'));
                $('#description').val($(this).data('description'));
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>
@endsection