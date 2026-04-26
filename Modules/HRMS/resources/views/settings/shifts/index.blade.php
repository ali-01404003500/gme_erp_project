@section('title', 'Shifts List')
@section('description', 'Shifts List')
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.hrm-settings-shift-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.shifts.create'))
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.hrm-settings-shift-menu-title') }}
                            </h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <style>
                                    .shifts-table-custom,
                                    .shifts-table-custom th,
                                    .shifts-table-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .shifts-table-custom th,
                                    .shifts-table-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .shifts-table-custom thead th {
                                        background-color: #f8f9fa;
                                        border-bottom-width: 2px !important;
                                    }
                                </style>

                                <table id="zero-config" class="table shifts-table-custom dt-table-hover"
                                    data-page='@include('utils.table_paginate', ['data' => $shifts])' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            <th class="text-center">Shift Type Name</th>
                                            <th class="text-center">Grace Period</th>
                                            <th class="text-center">In</th>
                                            <th class="text-center">Out</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifts as $key => $shift)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($shifts->currentPage() - 1) * $shifts->perPage() + $loop->iteration  }}
                                                </td>
                                                <td class="text-center">{{ $shift->shift_name }}</td>
                                                <td class="text-center">{{ $shift->grace_time }}</td>
                                                <td class="text-center">{{ date('h:i A', strtotime($shift->in_time)) }}</td>
                                                <td class="text-center">{{ date('h:i A', strtotime($shift->out_time)) }}</td>
                                                <td class="text-center">
                                                    @if ($shift->status == 1)
                                                        <span class="badge badge-round badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-round badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group"
                                                        aria-label="Small button group">
                                                        @if (hasPermission('hrm.settings.shifts.update'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.settings.shifts.update', $shift->id) }}"
                                                                data-data="{{$shift}}" class="btn btn-outline-primary btn-edit"
                                                                data-toggle="tooltip" data-placement="top" title="Edit"
                                                                data-bs-toggle="modal" data-bs-target="#editModal">
                                                                <i class="far fa-edit"></i>
                                                            </button>
                                                        @endif

                                                        @if (hasPermission('hrm.settings.shifts.destroy'))
                                                            @if($shift->id != 10000)
                                                                <button type="button"
                                                                    data-action="{{ route('hrm.settings.shifts.destroy', $shift->id) }}"
                                                                    class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                        class="far fa-trash-alt"></i></button>
                                                            @endif
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
                                <h5 class="modal-title">{{ trans('Shift Create') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.shifts.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Shift Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="shift_name" class="form-control"
                                                placeholder=" Shift Name *" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Grace Period</label>
                                        <div class="col-sm-12">
                                            <input type="number" name="grace_time" class="form-control"
                                                placeholder=" Grace Period *" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">In</label>
                                        <div class="col-sm-12">
                                            <input type="time" name="in_time" class="form-control" placeholder=" In *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Out</label>
                                        <div class="col-sm-12">
                                            <input type="time" name="out_time" class="form-control" placeholder=" Out *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Status</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
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
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
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
                            <label class="col-sm-12 col-form-label">Shift Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="shift_name" class="form-control" placeholder=" Shift Name *"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Grace Period</label>
                            <div class="col-sm-12">
                                <input type="number" name="grace_time" class="form-control" placeholder=" Grace Period *"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">In</label>
                            <div class="col-sm-12">
                                <input type="time" name="in_time" class="form-control" placeholder=" In *"
                                    pattern="\d{2}:\d{2}" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Out</label>
                            <div class="col-sm-12">
                                <input type="time" name="out_time" class="form-control" placeholder=" Out *"
                                    pattern="\d{2}:\d{2}" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Status</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <script>
        $(document).ready(function (e) {
            $(document).on('click', '.btn-edit', function () {
                const data = $(this).data('data');
                $.each(data, function (key, value) {
                    if (key == 'in_time' || key == 'out_time') {
                        value = new Date('1970-01-01T' + value);
                        value = value.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }).replace(/[^0-9:]/g, '');
                    }
                    $('#editModal input[name="' + key + '"]').val(value);
                    $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop('selected', true);
                })
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>
@endsection 