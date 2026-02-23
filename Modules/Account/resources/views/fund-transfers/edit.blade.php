 <!-- resources/views/i-o-u-requisition/i-o-u-requisition-entries/create.blade.php -->
@section('title', 'Edit Fund Transfer')
@section('description', 'Submit a Edit Fund Transfer')
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
                                <li class="breadcrumb-item"><a href="{{ route('account.fund-transfers.index') }}">{{ trans('menu.fund-transfer-list') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.edit-fund-transfers') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <a href="{{ route('account.fund-transfers.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="las la-arrow-left fs-16"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-fund-transfers') }}</h4>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <form method="POST" action="{{ route('account.fund-transfers.update', $fundTransfer->id) }}" enctype="multipart/form-data">
                            @csrf 
                            @method('PUT')
                            <div class="row g-3">

                                <!-- Type -->
                                <div class="col-md-4">
                                    <label for="transfer_type" class="form-label">Transfer Type <span class="text-danger">*</span></label>
                                    <select name="transfer_type" id="transfer_type" class="form-control tom-select" required>
                                        <option value="">Select Type</option>
                                        <option value="bank_to_bank" {{ $fundTransfer->transfer_type == 'bank_to_bank' ? 'selected' : '' }} >Bank to Bank</option>
                                        <option value="bank_to_cash" {{ $fundTransfer->transfer_type == 'bank_to_cash' ? 'selected' : '' }}>Bank to Cash</option>
                                        <option value="cash_to_bank" {{ $fundTransfer->transfer_type == 'cash_to_bank' ? 'selected' : '' }}>Cash to Bank</option>
                                        <option value="bkash_to_bank" {{ $fundTransfer->transfer_type == 'bkash_to_bank' ? 'selected' : '' }}>Bkash to Bank</option> 
                                    </select>
                                </div>

                                <!-- status -->
                                <input type="hidden" name="status" id="status" value="{{ $fundTransfer->status}}" > 

                                <!-- Date -->
                                <div class="col-md-4">
                                    <label for="transfer_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" name="transfer_date" id="transfer_date" class="form-control flatdate" value="{{ $fundTransfer->transfer_date }}" required readonly>
                                </div>
                                

                                <!-- Sender A/C -->
                                <div class="col-4">
                                    <label class="form-label">Sender A/C</label>
                                    <select name="transfer_from" id="transfer_from" class="form-control tom-select" required>
                                        <option value="">Sender A/C</option>
                                  
                                    </select>
                                </div>

                                <!-- Receiver A/C -->
                                <div class="col-4">
                                    <label class="form-label">Receiver A/C</label>
                                    <select name="transfer_to" id="transfer_to" class="form-control tom-select" required>
                                        <option value="">Receiver A/C</option>
                                    
                                    </select>
                                </div>

                                <!-- Cheque Date -->
                                <div class="col-md-4 cheque">
                                    <label for="cheque_date" class="form-label">Cheque Date</label>
                                    <input type="text" name="cheque_date" id="cheque_date" class="form-control flatdate" value="{{ $fundTransfer->cheque_date }}" readonly>
                                </div>

                                <!-- Cheque No -->
                                <div class="col-md-4 cheque">
                                    <label for="cheque_no" class="form-label">Cheque No</label>
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="{{ $fundTransfer->cheque_no }}">
                                </div> 

                                <!-- Amount -->
                                <div class="col-md-4">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="1000" name="amount" id="amount" class="form-control" value="{{ $fundTransfer->amount }}" required>
                                </div>

                                <!-- Remarks -->
                                <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                                    <input type="text" name="remarks" id="remarks" class="form-control" value="{{ $fundTransfer->remarks }}" required>
                                </div>
                                
                                <!-- Remarks -->
                                <div class="col-md-4">
                                    <label for="attachments" class="form-label">Attachment</label> 
                                    <x-file-uploader  name="attachments" :value="$fundTransfer->attachments"
                                            id="attachments" />
                                </div>
 
                            </div>

                            <!-- Action Buttons -->
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <div class="btn-group">

                                    @if(!request()->filled('action'))
                                        <button type="submit" class="btn btn-sm btn-success save-btn" >
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                    @endif
                                    @if(hasPermission('account.fund-transfers.verify') && request()->input('action')=='verify')
                                        <button type="submit" class="btn btn-sm btn-warning save-btn" id="action_verify">
                                            <i class="fa fa-check"></i>Update & Verify
                                        </button>
                                    @endif
                                    @if(hasPermission('account.fund-transfers.approve')&& request()->input('action')=='approve')
                                        <button type="submit" class="btn btn-sm btn-success save-btn" id="action_approve">
                                            <i class="fa fa-check"></i>Update & Approve
                                        </button>
                                    @endif
                                    @if((hasPermission('account.fund-transfers.verify') || hasPermission('account.fund-transfers.approve') ) && request()->filled('action'))
                                        <button type="submit" class="btn btn-sm btn-danger save-btn" id="action_deny">
                                            <i class="fa fa-times"></i>Deny
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
$(document).ready(function() {

    $('#transfer_type').on('change', function() {

        let transferType = $(this).val();
        
        const transferFromTomSelect = $("#transfer_from").prop('tomselect');
        const transferToTomSelect = $("#transfer_to").prop('tomselect');

        if(transferType != '') {
          
            if(transferType=="bkash_to_bank" || transferType=="cash_to_bank") 
                $(".cheque").addClass('d-none');
            else
                $(".cheque").removeClass('d-none');

            $.ajax({
                url: "{{ route('account.fund-transfers.getAccounts') }}",
                type: "GET",
                data: {
                    transfer_type: transferType
                },
                success: function(response) {
                    transferFromTomSelect.clear();
                    transferFromTomSelect.clearOptions();
                    if (response.sender_accounts && response.sender_accounts.length > 0) {
                        // The controller should return an array of objects with 'id' and 'name'
                        response.sender_accounts.forEach(function(item) {
                            transferFromTomSelect.addOption({
                                value: item.id,
                                text: item.account_name
                            });
                        });
                        let transferFrom = @json($fundTransfer->transfer_from);

                        if (transferFrom) {
                            transferFromTomSelect.setValue(transferFrom);
                        }
                    }

                    if (response.receiver_accounts && response.receiver_accounts.length > 0) {
                        transferToTomSelect.clear();
                        transferToTomSelect.clearOptions();
                        // The controller should return an array of objects with 'id' and 'name'
                        response.receiver_accounts.forEach(function(item) {
                            transferToTomSelect.addOption({
                                value: item.id,
                                text: item.account_name
                            });
                        });
                        let transferTo = @json($fundTransfer->transfer_to);

                        if (transferTo) {
                            transferToTomSelect.setValue(transferTo);
                        }
                    }
                     
                     
                }
            });
        }
   

    });
    $('#transfer_type').trigger('change');
 
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