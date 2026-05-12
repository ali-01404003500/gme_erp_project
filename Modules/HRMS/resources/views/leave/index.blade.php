@section('title', 'Leave Application List')
@section('description', 'Leave Application List')
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
                                        {{ trans('menu.leaves-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('hrm.leaves.create'))
                                    <a href="{{ route('hrm.leaves.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <style>
                .nav-icon la la-cart-arrow-down {
                    font-size: 26px;
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.leaves-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <select name="employee_id" id="employee_id" class="tom-select  input-sm"
                                                    data-placeholder="Select Employee">
                                                    <option value=""></option>
                                                    @foreach ($employees as $key => $value)
                                                        <option {{ request('employee_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control datePicker" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control datePicker" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
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

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $leaveApplications])'
                                style="width:100%; table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Leave Days</th>
                                        <th>Leave Type</th>
                                        <th>Purpose</th>
                                        <th>Documents</th>
                                        <th>Status</th> 
                                        <th>Approval Layers</th> 
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($leaveApplications as $value)
                                        <tr>
                                            <td class="text-center">{{ ($leaveApplications->currentPage() - 1) * $leaveApplications->perPage() + $loop->iteration  }}</td>
                                            <td>{{ optional($value->employee)->full_name }} </td>
                                            <td>{{ $value->from_date }}</td>
                                            <td>{{ $value->to_date }}</td>
                                            <td>{{ $value->day_count }}</td>
                                            <td><span class="badge badge-round badge-warning">
                                                    {{ $value->leaveType?->leave_type_name }}
                                                </span></td>
                                            <td>{{ $value->remarks }}</td>

                                            <td>
                                                @if(!empty($value->file_uploads))
                                                    @php
                                                        $files = is_array($value->file_uploads) ? $value->file_uploads : json_decode($value->file_uploads, true);
                                                    @endphp

                                                    @foreach($files as $file)
                                                        @if(!empty($file))
                                                            <a href="{{ asset($file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No files</span>
                                                @endif
                                            </td>

                                            
                                            <td>
                                                @if ($value->status == 'pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($value->status == 'approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($value->status == 'rejected')
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @elseif($value->status == 'recommended')
                                                    <span class="badge badge-round badge-primary">Recommended</span>
                                                @endif
                                            </td>
                                        
                                            <td>
                                                <ul>
                                                    @foreach($value->approvals as $approval)
                                                        <li>
                                                            Level {{ $approval->level }}: 
                                                            {{ $approval->approver->full_name ?? 'N/A' }} - 
                                                            <strong>{{ ucfirst($approval->status) }}</strong>
                                                             
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group"> 
                                                    @if (hasPermission('hrm.leaves.update') && $value->status == 'pending')
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('hrm.leaves.edit', $value->id) }}"
                                                            title="Edit"><i class="far fa-edit"></i></a>
                                                    @endif
                                                    @foreach($value->approvals as $approval)
                                                        @if($value->current_level == $approval->level && $approval->status == 'pending' &&   $approval->approver_id == auth()->user()->employee?->id) 
                                                            <!-- Approve / Reject buttons -->
                                                            <button type="button"
                                                                data-action="{{ route('hrm.leaves.recommended', $approval->id) }}"
                                                                data-data="{{ $approval->id }}"
                                                                class="btn btn-outline-success btn-recommend"
                                                                data-toggle="tooltip" data-placement="top" title="Recommend"
                                                                data-bs-toggle="modal" data-bs-target="#recommendModal">
                                                                <i class="fas fa-check"></i>
                                                            </button>

                                                            <button type="button"
                                                                data-action="{{ route('hrm.leaves.reject', $approval->id) }}"
                                                                data-data="{{ $approval->id }}"
                                                                class="btn btn-outline-danger btn-deny"
                                                                data-toggle="tooltip" data-placement="top" title="Deny"
                                                                data-bs-toggle="modal" data-bs-target="#denyModal">
                                                                <i class="fas fa-times"></i>
                                                            </button> 
                                                        @endif
                                                      
                                                    @endforeach

                                                    @if (hasPermission('hrm.leaves.destroy') && $value->status == 'pending')
                                                        <button type="button"
                                                            data-action="{{ route('hrm.leaves.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('hrm.leaves.show'))
                                                        <button type="button"
                                                            data-action="{{ route('hrm.leaves.show', $value->id) }}"
                                                            data-data="{{ $value->id }}"
                                                            class="btn btn-outline-primary btn-show"
                                                            data-toggle="tooltip" data-placement="top" title="Show"
                                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                                            <i class="fas fa-eye"></i> 
                                                        </button>
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
            </div>
        </div>
    </div>

    <div class="modal fade inputForm-modal" id="recommendModal" tabindex="-1" role="dialog"
        aria-labelledby="recommendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="recommendModalLabel">
                    <h5 class="modal-title">Recommend </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="recommendForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Recomended Comments</label>
                            <div class="col-sm-12">
                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Recommend</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="denyModal" tabindex="-1" role="dialog"
        aria-labelledby="denyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="denyModalLabel">
                    <h5 class="modal-title">Deny </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="denyForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Deny Comments</label>
                            <div class="col-sm-12">
                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger mt-2 mb-2 btn-no-effect">Deny</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="approveModal" tabindex="-1" role="dialog"
        aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="approveModalLabel">
                    <h5 class="modal-title">Approve </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="approveForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Approve Comments</label>
                            <div class="col-sm-12">
                                <textarea name="approveed_comments" id="approveed_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="showModal" tabindex="-1" role="dialog"
        aria-labelledby="showModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">  
                <div class="modal-body"> 
                    <div id="modalContent"> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Cancel</button> 
                </div>
                
            </div>
        </div>
    </div>

<style>
/* Apply to all large modals */
.modal-lg {
    max-width: 60%;
} 
</style>
@endsection
@section('page_scripts')

    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $(document).ready(function(e) {
            $('.btn-recommend').click(function() {
                // Get dynamic route
                var actionUrl = $(this).data('action'); 

                // Set form action
                $('#recommendForm').attr('action', actionUrl);  
                $('#remarks').val('');
            });

            $('.btn-deny').click(function() {
                var actionUrl = $(this).data('action');
                
                $('#denyForm').attr('action', actionUrl); 
                $('#denyRemarks').val('');
            });

            $('.btn-show').click(function() {
                var actionUrl = $(this).data('action');

                // Open modal
                $('#modalShow').modal('show');

                // Load Blade page via AJAX
                $('#modalContent').html('<p class="text-center">Loading...</p>'); // show loader
                $.ajax({
                    url: actionUrl,
                    type: 'GET',
                    success: function(res) {
                        $('#modalContent').html(res);
                    },
                    error: function(err) {
                        $('#modalContent').html('<p class="text-danger text-center">Failed to load data.</p>');
                    }
                });
            });

        
        });

        $(document).ready(function(e) {
            $(document).on('click', '.btn-approved', function() {
                console.log($(this).data('action'));
                $("#approveForm").attr("action", $(this).data('action'));
            });
        });
    </script>
@endSection
