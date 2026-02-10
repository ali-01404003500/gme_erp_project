<!-- resources/views/i-o-u-requisition/i-o-u-requisition-entries/create.blade.php -->
@section('title', 'Create IOU Requisition')
@section('description', 'Submit a new IOU requisition for expense or advance')
@extends('layout.app')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}">{{ trans('menu.iou-requisition-list') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.create-iou-requisition') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="las la-arrow-left fs-16"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-iou-requisition') }}</h4>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Date -->
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" name="date" id="date" class="form-control flatdate" value="{{ old('date', now()->format('Y-m-d')) }}" required readonly>
                                </div>

                                <!-- Type -->
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control tom-select" required>
                                        <option value="Expense">Expense</option>
                                        <option value="Advance">Advance</option>
                                    </select>
                                </div>

                                <!-- Employee Name -->
                                <div class="col-12">
                                    <label class="form-label">Employee Name</label>
                                    <select name="employee_id" id="employee_id" class="form-control tom-select" required>
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', auth()->user()?->employee?->id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Amount -->
                                <div class="col-4">
                                    <label for="request_amount" class="form-label">Request Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="request_amount" id="request_amount" class="form-control" value="{{ old('request_amount') }}" required>
                                </div>

                                <!-- Verified Amount -->
                                @if(hasPermission('account.collections.collections.verify'))
                                <div class="col-4">
                                    <label for="verify_amount" class="form-label">Verified Amount</label>
                                    <input type="number" step="0.01" name="verify_amount" id="verify_amount" class="form-control" value="{{ old('verify_amount') }}" >
                                </div>
                                @endif
                                    

                                <!-- Approved Amount -->
                                @if(hasPermission('account.collections.collections.approve'))
                                <div class="col-4">
                                    <label for="approved_amount" class="form-label">Approved Amount</label>
                                    <input type="number" step="0.01" name="approved_amount" id="approved_amount" class="form-control" value="{{ old('approved_amount') }}" >
                                </div>
                                @endif

                                <!-- Remarks -->
                                <div class="col-12">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks if needed">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <div class="btn-group">

                                    <input type="hidden" name="status" id="status" value="{{ $collection->status?? 'pending' }}">

                                    <button type="submit" class="btn btn-sm btn-success save-btn" >
                                        <i class="fa fa-save"></i> Create
                                    </button>
                                    @if(hasPermission('account.collections.collections.verify'))
                                        <button type="submit" class="btn btn-sm btn-warning save-btn" id="action_verify">
                                            <i class="fa fa-check"></i> Create & Verify
                                        </button>
                                    @endif
                                    @if(hasPermission('account.collections.collections.approve'))
                                        <button type="submit" class="btn btn-sm btn-success save-btn" id="action_approve">
                                            <i class="fa fa-check"></i> Create & Approve
                                        </button>
                                    @endif
                                    @if((hasPermission('account.collections.collections.verify') || hasPermission('account.collections.collections.approve') ) && request()->filled('for'))
                                        <button type="submit" class="btn btn-sm btn-danger save-btn" id="action_deny">
                                            <i class="fa fa-times"></i> Create & Deny
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
    $(document).ready(function () {
        $('#action_verify').click(function() {
            $("#status").val("verified");
        });

        $('#action_approve').click(function() {
            $("#status").val("approved");
        });

        $('#action_deny').click(function() {
            $("#status").val("denied");
        });

    });
</script>
@endsection