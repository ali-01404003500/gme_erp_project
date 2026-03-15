@section('title', 'Leave Types')
@section('description', 'Leave Types')
@extends('layout.app')

@section('content')
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
                                        {{ trans('menu.hrm-settings-leave-types-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.leave-types.create'))
                                    <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">Add New</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl</th>
                                            <th class="text-center">Leave Type Name</th>
                                            <th class="text-center">Flag</th>
                                            <th class="text-center">Total Days</th>
                                            <th class="text-center">Count Type</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveTypes as $leaveType)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $leaveType->leave_type_name }}</td>
                                                <td class="text-center"><span class="">{{ $leaveType->flag }}</span></td>
                                                <td class="text-center">{{ $leaveType->total_day ?? 0 }}</td>
                                                <td class="text-center">{{ ucfirst($leaveType->leave_count_type) }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        @if (hasPermission('hrm.settings.leave-types.update'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.settings.leave-types.update', $leaveType->id) }}"
                                                                data-data="{{ json_encode($leaveType) }}"
                                                                class="btn btn-outline-primary btn-edit" data-bs-toggle="modal"
                                                                data-bs-target="#editModal">
                                                                <i class="far fa-edit"></i>
                                                            </button>
                                                        @endif
                                                        @if (hasPermission('hrm.settings.leave-types.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.settings.leave-types.destroy', $leaveType->id) }}"
                                                                class="btn btn-outline-danger delete-confirm">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $leaveTypes->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none">
        <form class="delete-form" action="" method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>
    

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('hrm.settings.leave-types.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Leave Name <span class="text-danger">*</span></label>
                                <input type="text" name="leave_type_name" class="form-control"
                                    placeholder="Enter leave name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Flag <span class="text-danger">*</span></label>
                                <input type="text" name="flag" class="form-control" placeholder="CL, SL" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Half Flag <span class="text-danger">*</span></label>
                                <input type="text" name="half_flag" class="form-control" placeholder="H-CL" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Days <span class="text-danger">*</span></label>
                                <input type="number" name="total_day" class="form-control" value="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Simultaneously Limit <span class="text-danger">*</span></label>
                                <input type="number" name="simultaneously_limit" class="form-control" value="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_maternity" value="1" id="mat1">
                                    <label class="form-check-label" for="mat1">Is Maternity Leave</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_unpaid" value="1" id="unp1">
                                    <label class="form-check-label" for="unp1">Is Unpaid Leave</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_partially_balance" value="1"
                                        id="par1">
                                    <label class="form-check-label" for="par1">Is Partially Balance Applicable?</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="d-block mb-2"><b>Leave Count Type</b></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leave_count_type" value="day"
                                        id="day1" checked>
                                    <label class="form-check-label" for="day1">Day Wise</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leave_count_type" value="hour"
                                        id="hour1">
                                    <label class="form-check-label" for="hour1">Hour Wise</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="editForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Leave Name <span class="text-danger">*</span></label>
                                <input type="text" name="leave_type_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Flag <span class="text-danger">*</span></label>
                                <input type="text" name="flag" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Half Flag <span class="text-danger">*</span></label>
                                <input type="text" name="half_flag" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Days <span class="text-danger">*</span></label>
                                <input type="number" name="total_day" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Simultaneously Limit <span class="text-danger">*</span></label>
                                <input type="number" name="simultaneously_limit" class="form-control" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_maternity" value="1" id="mat2">
                                    <label class="form-check-label" for="mat2">Is Maternity Leave</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_unpaid" value="1" id="unp2">
                                    <label class="form-check-label" for="unp2">Is Unpaid Leave</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_partially_balance" value="1"
                                        id="par2">
                                    <label class="form-check-label" for="par2">Is Partially Balance Applicable?</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="d-block mb-2"><b>Leave Count Type</b></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leave_count_type" value="day"
                                        id="day2">
                                    <label class="form-check-label" for="day2">Day Wise</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leave_count_type" value="hour"
                                        id="hour2">
                                    <label class="form-check-label" for="hour2">Hour Wise</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).on('click', '.btn-edit', function () {
            const data = $(this).data('data');
            const action = $(this).data('action');

            let form = $('#editForm');
            form.attr('action', action);


            form.find('input[name="leave_type_name"]').val(data.leave_type_name);
            form.find('input[name="flag"]').val(data.flag);
            form.find('input[name="half_flag"]').val(data.half_flag);
            form.find('input[name="total_day"]').val(data.total_day);
            form.find('input[name="simultaneously_limit"]').val(data.simultaneously_limit);


            form.find('input[type="checkbox"]').prop('checked', false);


            if (parseInt(data.is_maternity) === 1) {
                form.find('#mat2').prop('checked', true);
            }
            if (parseInt(data.is_unpaid) === 1) {
                form.find('#unp2').prop('checked', true);
            }
            if (parseInt(data.is_partially_balance) === 1) {
                form.find('#par2').prop('checked', true);
            }

            form.find('input[name="leave_count_type"][value="' + data.leave_count_type + '"]').prop('checked', true);
        });
    </script>
@endsection