@section('title', 'Leave Application Details')
@section('description', 'Leave Application Details')
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
                                        {{ trans('menu.create-leaves-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.leaves.index'))
                            <a href="{{ route('hrm.leaves.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-leaves-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Employee Name</label>
                                            <div class="form-control">{{ $leaveApplication->employee->full_name ?? '' }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Leave Type</label>
                                            <div class="form-control">{{ $leaveApplication->leaveType->leave_type_name ?? '' }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">From Date</label>
                                            <div class="form-control">{{ $leaveApplication->from_date }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">From date leave count for</label>
                                            <div class="form  -control">{{ ucfirst(str_replace('_', ' ', $leaveApplication->from_date_leave_count)) }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">To Date</label>
                                            <div class="form-control">{{ $leaveApplication->to_date }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">To date leave count for</label>
                                            <div class="form-control">{{ ucfirst(str_replace('_', ' ', $leaveApplication->to_date_leave_count)) }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Total Days</label>
                                            <div class="form-control text-center">{{ $leaveApplication->day_count }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Total Leave</label>
                                            <div class="form-control text-center">{{ $leaveApplication->companyTotalLeave }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Leave Balance</label>
                                            <div class="form-control text-center">{{ $leaveApplication->leave_balance }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">Simultaneously Limit</label>
                                            <div class="form-control text-center">{{ $leaveApplication->simultaneouslyLimit }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Remarks</label>
                                        <div class="form-control">{{ $leaveApplication->remarks }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Remarks</label>
                                        <div class="form-control">{{ $leaveApplication->remarks }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="color-dark fs-14 fw-500 align-center">File Uploads</label>
                                            @if($leaveApplication->file_uploads)
                                                <div class="form-control">
                                                    @foreach($leaveApplication->file_uploads as $file)
                                                        <a href="{{ $file }}" target="_blank">View File {{ $loop->iteration }}</a><br>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="form-control">No files uploaded</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Status</label>
                                        <div class="form-control">{{ $leaveApplication->status }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Recommended By</label>
                                        <div class="form-control">{{ $leaveApplication->recommended_by }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Recommended Comments</label>
                                        <div class="form-control">{{ $leaveApplication->recommended_comments }}</div>
                                    </div>
                                </div>
                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                    <a href="{{ route('hrm.leaves.index') }}"
                                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection