@section('title', 'Leave Types')
@section('description', 'Leave Types')
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
                                        {{ trans('menu.hrm-settings-leave-types-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.leave-types.create'))
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.hrm-settings-leave-types-menu-title') }}
                            </h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $leaveTypes])'
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            <th class="text-center">Leave Type Name</th>
                                            <th class="text-center">Payment Mode</th>
                                            <th class="text-center">Total Days</th>

                                            <th class="text-center">Simultaneously Limit</th>
                                            <th class="text-center no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveTypes as $key => $leaveType)
                                            <tr>
                                        <td class="text-center">{{ ($leaveTypes->currentPage() - 1) * $leaveTypes->perPage() + $loop->iteration  }}</td>
                                                <td class="text-center">{{ $leaveType->leave_type_name }}</td>
                                                <td class="text-center">{{ $leaveType->payment_mode }}</td>
                                                <td class="text-center">{{ $leaveType->total_day }}</td>
                                                <td class="text-center">{{ $leaveType->simultaneously_limit }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group"
                                                        aria-label="Small button group">

                                                        @if (hasPermission('hrm.settings.leave-types.update'))
                                                            <button type="button" data-action="{{ route('hrm.settings.leave-types.update', $leaveType->id) }}" data-data="{{$leaveType}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                                                <i class="far fa-edit"></i>
                                                            </button>
                                                        @endif



                                                        @if (hasPermission('hrm.settings.leave-types.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.settings.leave-types.destroy', $leaveType->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"><i
                                                                    class="far fa-trash-alt"></i></button>
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
                                <h5 class="modal-title">{{ trans('Leave Type') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.leave-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Leave Type Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="leave_type_name" class="form-control" placeholder=" Leave Type Name *"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Payment Mode</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="payment_mode" required>
                                                <option value="">Select Payment Mode</option>
                                                <option value="with_pay">With Pay</option>
                                                <option value="without_pay">Without Pay</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Total Day</label>
                                        <div class="col-sm-12">
                                            <input type="number" name="total_day" class="form-control" placeholder=" Total Day *"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Simultaneously Limit</label>
                                        <div class="col-sm-12">
                                            <input type="number" name="simultaneously_limit" class="form-control" placeholder=" Simultaneously Limit *"
                                                required>
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
                            <label class="col-sm-12 col-form-label">Leave Type Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="leave_type_name" class="form-control" placeholder=" Leave Type Name *"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Payment Mode</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="payment_mode" required>
                                    <option value="">Select Payment Mode</option>
                                    <option value="with_pay">With Pay</option>
                                    <option value="without_pay">Without Pay</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Total Day</label>
                            <div class="col-sm-12">
                                <input type="number" name="total_day" class="form-control" placeholder=" Total Day *"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Simultaneously Limit</label>
                            <div class="col-sm-12">
                                <input type="number" name="simultaneously_limit" class="form-control" placeholder=" Simultaneously Limit *"
                                    required>
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
                    $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop('selected', true);

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
