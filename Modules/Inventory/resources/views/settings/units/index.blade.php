@section('title', 'Units')
@section('description', 'Units')
@extends('layout.app')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.inventory-settings-unit-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.settings.units.create'))
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.inventory-settings-unit-menu-title') }}
                            </h4>
                        </div>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $units])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($units as $key => $unit)
                                        <tr>
                                        <td class="text-center">{{ ($units->currentPage() - 1) * $units->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">{{ $unit->name }}</td>
                                            <td class="text-center">{{ $unit->description }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('inv.settings.units.update'))
                                                        <button type="button" data-action="{{ route('inv.settings.units.update', $unit->id) }}" data-data="{{$unit}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                    @endif



                                                    @if (hasPermission('inv.settings.units.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.settings.units.destroy', $unit->id) }}"
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
                                <h5 class="modal-title">{{ trans('menu.inventory-settings-unit-menu-title') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('inv.settings.units.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name *"
                                                required>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Description</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="description" class="form-control"
                                                placeholder="Description">
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
                            <label class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control" placeholder=" Name *"
                                    required>
                            </div>
                        </div>
                        
                        {{-- description --}}
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Description</label>
                            <div class="col-sm-12">
                                <input type="text" name="description" id="description" class="form-control"
                                    placeholder="Description">
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
                const data = $(this).data('data');
                //loop through data object
                $.each(data, function(key, value) {
                    $('#editModal input[name="' + key + '"]').val(value);
                    // console.log();
                })
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
