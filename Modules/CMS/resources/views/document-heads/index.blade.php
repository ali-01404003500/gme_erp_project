@section('title', 'Document Head')
@section('description', 'Document Head')
@extends('layout.app')

@section('content')
    <style>
         
    </style>

    <div class="container-fluid py-4">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-muted"><i class="las la-home"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                                {{ trans('Document Head') }}
                            </li>
                        </ol>
                    </nav>
                    <div class="action-btn">
                        @if (hasPermission('cms.document-heads.create'))
                            <button class="btn btn-primary btn-sm px-4 shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="las la-plus me-1"></i> Add New Head
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-error-alart />
                <div class="card border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive table-container">
                            <table id="zero-config" class="table condition-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $documentHeads])'>
                                <thead>
                                    <tr class="text-left">
                                        <th width="60">Sl</th>
                                        <th>Document Type</th>
                                        <th>Document Head</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th width="150">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentHeads as $item)
                                        <tr class="text-left">
                                            <td class="text-left  text-muted small">
                                                {{ ($documentHeads->currentPage() - 1) * $documentHeads->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="text-left">
                                                <span
                                                    class="text-dark  px-2 py-1 small ">{{ @$item->documentType->name }}</span>
                                            </td>
                                            <td class=" text-dark text-left">{{ $item->name }}</td>
                                            <td class="text-muted small">{{ Str::limit($item->description, 50) ?? '---' }}</td>
                                            <td class="text-center">
                                                @if($item->status == 1)
                                                    <span class="badge-active">ACTIVE</span>
                                                @else
                                                    <span class="badge-inactive">INACTIVE</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group action-btn-group shadow-sm">
                                                    @if (hasPermission('cms.document-heads.update'))
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-edit btn-outline-warning"
                                                            data-document_type_id="{{ $item->document_type_id }}"
                                                            data-name="{{ $item->name }}"
                                                            data-description="{{ $item->description }}"
                                                            data-status="{{ $item->status }}"
                                                            data-action="{{ route('cms.document-heads.update', $item->id) }}"
                                                            data-bs-toggle="modal" data-bs-target="#editModal" title="Edit">
                                                            <i class="lar la-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('cms.document-heads.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('cms.document-heads.destroy', $item->id) }}"
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
                            <form class="delete-form" action="" method="POST"> @csrf @method('DELETE') </form>
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
                    <h5 class="modal-title fw-bold text-dark">Add Document Head</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('cms.document-heads.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DOCUMENT TYPE</label>
                            <select name="document_type_id" class="form-control shadow-sm" required>
                                <option value="">Select Document Type</option>
                                @foreach ($documentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">HEAD NAME</label>
                            <input type="text" name="name" class="form-control shadow-sm" placeholder="Enter name *"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                            <textarea name="description" class="form-control shadow-sm" rows="3"
                                placeholder="Optional details..."></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted d-block">STATUS</label>
                            <div class="d-flex gap-4 mt-2">
                                <label class="d-flex align-items-center gap-2"><input type="radio" name="status" value="1"
                                        checked> <span class="small fw-600">Active</span></label>
                                <label class="d-flex align-items-center gap-2"><input type="radio" name="status" value="0">
                                    <span class="small fw-600">Inactive</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Save Head</button>
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
                    <h5 class="modal-title fw-bold text-dark">Edit Document Head</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf @method('put')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DOCUMENT TYPE</label>
                            <select name="document_type_id" class="form-control shadow-sm" required>
                                @foreach ($documentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">HEAD NAME</label>
                            <input name="name" id="name" class="form-control shadow-sm" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                            <textarea name="description" id="description" class="form-control shadow-sm"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted d-block">STATUS</label>
                            <div class="d-flex gap-4 mt-2">
                                <label class="d-flex align-items-center gap-2"><input type="radio" name="status" value="1">
                                    <span class="small fw-600">Active</span></label>
                                <label class="d-flex align-items-center gap-2"><input type="radio" name="status" value="0">
                                    <span class="small fw-600">Inactive</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Update Head</button>
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
                $('input[name=status][value=' + $(this).data('status') + ']').prop('checked', true);
                $("#editModal select[name=document_type_id]").val($(this).data('document_type_id'));
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>
@endsection