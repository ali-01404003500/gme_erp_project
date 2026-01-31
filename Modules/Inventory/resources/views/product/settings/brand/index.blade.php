@section('title', 'Brand')
@section('description', 'Brand')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans(' Brand') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.brands.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                    Add New
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px" >
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Brand') }}</h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" style="width:100%" data-page='@include('utils.table_paginate', ['data' => $brands])'>
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Brand Company Name</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($brands as $key => $item)
                                        <tr>
                                            <td class="text-center">                                                {{ ($brands->currentPage() - 1) * $brands->perPage() + $loop->iteration  }}
</td>
                                            <td class="text-center">{{ $item->code }}</td>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">{{ optional($item->supplier)->company_name }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('inv.brands.update'))
                                                        <a href={{ $item->id }} class="btn btn-edit  btn-outline-warning"
                                                            data-name="{{ $item->name }}" data-code="{{ $item->code }}" data-supplier_id="{{ $item->supplier_id }}"
                                                            data-action="{{ route('inv.brands.update', $item->id) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('inv.brands.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.brands.destroy', $item->id) }}"
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
                                <h5 class="modal-title">Add Brands</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('inv.brands.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control" placeholder=" Code " value="{{ old('code') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name " value="{{ old('name') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Brand Company Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select name="supplier_id" id="supplier_id" class="form-control tom-select required" required>
                                                <option value="">Choose Company Name</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->company_name }}</option>
                                                @endforeach
                                            </select>
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
                            <label for="code" class="col-sm-12 col-form-label">Code<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input name="code" id="code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Name<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="company_name" class="col-sm-12 col-form-label">Brand Company Name<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <div class="col-sm-12"> 
                                    <select name="supplier_id" id="supplier_id" class="form-control tom-select" required>
                                        <option value="">Choose Company Name</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" >
                                                {{ $supplier->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>                            
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
                console.log($(this).data('name'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));

                var supplierId = $(this).data('supplier_id');
                $('#supplier_id option').prop('selected', false);

                $('#supplier_id option[value="' + supplierId + '"]').prop('selected', true);
                
                $("#editFrom").find(".tom-select").each(function() {
                    this.tomselect?.sync();
                });
                $("#editFrom").attr("action", $(this).data('action'));

            });
        });
    </script>
@endsection
