<!-- resources/views/i-o-u-requisition/i-o-u-requisition-entries/edit.blade.php -->
@section('title', 'Edit IOU Requisition')
@section('description', 'Edit your IOU requisition')

@extends('layout.app')

@section('content')
<!-- Same as create.blade.php but with values filled and update method -->
<!-- Left as exercise — very similar to create.blade.php with minor changes -->

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}">{{ trans('menu.iou-requisition-list') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.update-iou-requisition') }}</li>
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
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-iou-requisition') }}</h4>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.update', $iOURequisitionEntry->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" name="date" class="form-control flatdate" value="{{ old('date', $iOURequisitionEntry->date->format('Y-m-d')) }}" required readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" class="form-control tom-select" required>
                                        <option value="Expense" {{ $iOURequisitionEntry->type === 'Expense' ? 'selected' : '' }}>Expense</option>
                                        <option value="Advance" {{ $iOURequisitionEntry->type === 'Advance' ? 'selected' : '' }}>Advance</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Employee Name</label>
                                    <p class="form-control-plaintext"><strong>{{ $iOURequisitionEntry->employee->full_name }}</strong></p>
                                </div>

                                <!--Request Amount -->
                                <div class="col-4">
                                    <label for="request_amount" class="form-label">Request Amount</label>
                                    <input type="number" step="0.01" name="request_amount" class="form-control" value="{{ old('request_amount', $iOURequisitionEntry->request_amount) }}" required>
                                </div>

                                <!-- Verified Amount -->
                                @if(hasPermission('account.collections.collections.verify') && $iOURequisitionEntry->status === 'pending')
                                <div class="col-4">
                                    <label for="verify_amount" class="form-label">Verified Amount</label>
                                    <input type="number" step="0.01" name="verify_amount" id="verify_amount" class="form-control" value="{{ old('verify_amount', $iOURequisitionEntry->verify_amount) }}" >
                                </div>
                                @endif
                                    

                                <!-- Approved Amount -->
                                @if(hasPermission('account.collections.collections.approve') && $iOURequisitionEntry->status === 'verified')
                                <div class="col-4">
                                    <label for="approved_amount" class="form-label">Approved Amount</label>
                                    <input type="number" step="0.01" name="approved_amount" id="approved_amount" class="form-control" value="{{ old('approved_amount', $iOURequisitionEntry->approved_amount) }}" >
                                </div>
                                @endif

                                <div class="col-12">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $iOURequisitionEntry->remarks) }}</textarea>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                             <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <div class="btn-group">

                                    <input type="hidden" name="status" id="status" value="{{ $iOURequisitionEntry->status?? 'pending' }}">
                                    @if($iOURequisitionEntry->status === 'pending' )
                                    <button type="submit" class="btn btn-sm btn-primary save-btn" >
                                        <i class="fa fa-save"></i>Update
                                    </button>
                                    @endif
                                    @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.verify') && $iOURequisitionEntry->status === 'pending' )
                                        <button type="submit" class="btn btn-sm btn-warning save-btn" id="action_verify">
                                            <i class="fa fa-check"></i>Update & Verify
                                        </button>
                                    @endif
                                    @if(hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.approve')  && $iOURequisitionEntry->status === 'verified' )
                                        <button type="submit" class="btn btn-sm btn-success save-btn" id="action_approve">
                                            <i class="fa fa-check"></i>Update & Approve
                                        </button>
                                    @endif
                                    @if((hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.verify') || hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.approve') ) && request()->filled('form_type'))
                                        <button type="submit" class="btn btn-sm btn-danger save-btn" id="action_deny">
                                            <i class="fa fa-times"></i>Update & Deny
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