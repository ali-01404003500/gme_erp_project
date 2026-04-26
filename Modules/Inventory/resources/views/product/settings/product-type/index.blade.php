@section('title', 'Product Type')
@section('description', 'Product Type')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('product type') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('inv.product-types.create'))
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
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('product type') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <style>
                                    .product-type-table-custom,
                                    .product-type-table-custom th,
                                    .product-type-table-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .product-type-table-custom th,
                                    .product-type-table-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .product-type-table-custom thead th {
                                        background-color: #f8f9fa;
                                        border-bottom-width: 2px !important;
                                    }

                                    .table thead th {
                                        background-color: #35526e !important;
                                        color: #ffffff !important;
                                        font-weight: 600 !important;
                                        text-transform: uppercase;
                                        font-size: 0.85rem !important;
                                        letter-spacing: 0.08em;
                                        border-bottom: 2px solid #2a4054 !important;
                                        padding: 14px 16px !important;
                                        vertical-align: middle;
                                        text-align: center;
                                    }
                                </style>
                            <table id="zero-config" class="table product-type-table-custom dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $productTypes])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width: 8%">Sl</th>
                                        <th class="text-left">Code</th>
                                        <th class="text-left">Name</th>
                                        <th class="text-left no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($productTypes as $key => $item)
                                        <tr>
                                        <td class="text-left">{{ ($productTypes->currentPage() - 1) * $productTypes->perPage() + $loop->iteration  }}</td>
                                            <td class="text-left">{{ $item->code }}</td>
                                            <td class="text-left">{{ $item->name }}</td>
                                            <td class="text-left">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('inv.product-types.update'))
                                                        <a href={{ $item->id }} class="btn btn-edit  btn-outline-warning"
                                                            data-name="{{ $item->name }}" data-code="{{ $item->code }}"
                                                            data-action="{{ route('inv.product-types.update', $item->id) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if (hasPermission('inv.product-types.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.product-types.destroy', $item->id) }}"
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
                                <h5 class="modal-title">Add Product Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('inv.product-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control" placeholder=" Code *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Status</label>
        
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="radio">
                                                <label>
                                                    <input name="status" type="radio" value="1" class="ace" checked>
                                                    <span class="lbl"> Active</span>
                                                </label>
                                                <label>
                                                    <input name="status" type="radio" value="0" class="ace">
                                                    <span class="lbl"> In active</span>
                                                </label>
                                            </div>
        
                                            @error('status')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
        
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
                            <label for="code" class="col-sm-12 col-form-label">Code</label>
                            <div class="col-sm-12">
                                <input name="code" id="code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
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
