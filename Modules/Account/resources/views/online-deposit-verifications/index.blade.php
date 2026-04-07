@section('title', 'Online Deposit Verification')
@section('description', 'Online Deposit Verification')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Online Deposit Verification</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
            </div>

            {{-- 📋 Output Table --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="20%">
                                                    <select name="customer_id" class="form-control tom-select">
                                                        <option value="">Select Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                @if (request('customer_id') == $customer->id) selected @endif>
                                                                {{ $customer->company_name }} - {{ $customer->address}}</option>
                                                        @endforeach
                                                    </select>
                                                </td> 
                                                <td width="20%">
                                                    <select name="head_id" class="form-control tom-select">
                                                        <option value="">Select Account</option>
                                                        @foreach ($bankHeads as $bank)
                                                            <option value="{{ $bank->getAccount()->id }}"
                                                                @if (request('head_id') == $bank->getAccount()->id) selected @endif>
                                                                {{ $bank->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="30%">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control datePicker" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>
                                                        <input type="text" class="form-control datePicker" name="to"
                                                            value="{{ request('to') }}" autocomplete="off"
                                                            placeholder="To" />
                                                    </div>
                                                </td>
                                                <td width="10%">
                                                    <select name="status" class="form-control "> 
                                                        @if (hasPermission('account.online-deposit-verifications.check'))
                                                            <option value="pending" @if (request('status') == 'pending') selected @endif>Pending</option>
                                                        @endif
                                                        @if (hasPermission('account.online-deposit-verifications.check-verification'))
                                                            <option value="verified" @if (request('status') == 'verified') selected @endif>Verified</option> 
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="text-right" width="20%">
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-bordered table-hover align-middle" id="zero-config"  data-page='@include('utils.table_paginate', ['data' => $entries])' >
                                <thead>
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th width="25%">Sender & Bank Info</th>
                                        <th width="20%">Particulars</th>
                                        <th width="20%">Entry Info</th>
                                        <th width="10%">Attachment</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($entries as $entry)
                                        <tr>
                                            <td class="text-center">{{ ($entries->currentPage() - 1) * $entries->perPage() + $loop->iteration  }}</td>

                                            {{-- Sender & Bank Info --}}
                                            <td>
                                                <strong>Received From:</strong> {{ $entry->customer->company_name }} <br>
                                                <strong> Bank: </strong> {{ $entry->bankAccount->account_name ?? '-' }} <br>
                                               
                                            </td>

                                            {{-- Particulars --}}
                                            <td> 
                                                <strong>Date:</strong> {{ $entry->deposit_date ?? '-' }} <br>
                                                <strong>Amount:</strong> {{ number_format($entry->amount) }}
                                            </td>

                                            {{-- Entry Info --}}
                                            <td>
                                                <strong>Entry By:</strong> {{ $entry->createdBy->name }} <br>
                                                <strong>Entry Date:</strong> {{ $entry->created_at->format('d-m-Y') }} <br>
                                                <strong>Remarks:</strong> {{ $entry->remarks }}<br> 
                                            </td>

                                             <td> 
                                                @if (!empty($entry->document))
                                                    @php
                                                        $documents = is_string($entry->document)
                                                            ? json_decode($entry->document, true)
                                                            : $entry->document;
                                                    @endphp
                                                    @if (!empty($documents) && is_array($documents))
                                                        @foreach ($documents as $doc)
                                                            <a href="{{ $doc }}" target="_blank"><i
                                                                    class="fa fa-folder-open"></i></a>
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
 

                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group"> 
                                                 
                                                    @if($entry->status == 'pending')
                                                        @if (hasPermission('account.online-deposit-verifications.check'))
                                                            <button type="button" class="btn btn-sm btn-primary status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Checking" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                 data-status="verified">
                                                                <i class="fas fa-check-circle"></i> Checking
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-danger status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Deny" data-id="{{ $entry->id }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-status="denied">
                                                                <i class="fas fa-times-circle"></i> Deny
                                                            </button>
                                                    @endif
                                                    @elseif($entry->status == 'verified')
                                                        @if (hasPermission('account.online-deposit-verifications.check-verification'))
                                                            <button type="button" class="btn btn-sm btn-primary status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Approve Verification" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-status="approved">
                                                                <i class="fas fa-check-circle"></i> Verify
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-danger status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Deny Verification" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-status="denied">
                                                                <i class="fas fa-times-circle"></i> Deny
                                                            </button>
                                                        @endif
                                                    @endif

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- 🔄 Verify Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="statusForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="status_id">
                        <input type="hidden" name="status" id="status_value">

                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="status_remarks" class="form-control" rows="3" placeholder="Enter remarks"></textarea>
                        </div>

                        {{-- Additional charge will show  --}}
                        <div class="mb-3" id="chargeField">
                            <label class="form-label">Additional Charge</label>
                            <input type="number"  id="charge" name="charge" class="form-control"
                                placeholder="Enter charge">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="statusSubmitBtn"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    @section('page_scripts')
<script>
    // 🚨 validate Deposit Form (document required)
    $(document).on("submit", "#depositForm", function(e) {
        const uploader = document.getElementById('document');

        // case 1: uploader not initialized at all
        if (!uploader || !uploader.uploader) {
            e.preventDefault();
            toastr.error("Please attach at least one document before submitting.");
            return false;
        }

        // case 2: uploader initialized but no files
        if (uploader.uploader.getFiles().length === 0) {
            e.preventDefault();
            toastr.error("Please attach at least one document before submitting.");
            return false;
        }
    });

    

    //  Status Modal (verify/deny)
    $(document).on("click", ".status-btn", function() {
        let id = $(this).data("id");
        let status = $(this).data("status");
        let remarks = $(this).data("remarks");
        let charge = $(this).data("charge") || 0;

        $("#status_id").val(id);
        $("#status_value").val(status);
        $("#status_remarks").val(remarks || '');
        $("#charge").val(charge);

        // change modal title + button based on status
        if (status === "pending" || status === "approved" || status === "verified" ) {
            $("#statusModalTitle").html(
                '<i class="fas fa-check-circle text-primary"></i> Confirm Online Deposit'
            );
            $("#statusSubmitBtn").removeClass("btn-danger").addClass("btn-primary").html(
                '<i class="fas fa-check"></i> Confirm Online Deposit'
            );
        } else {
            $("#statusModalTitle").html(
                '<i class="fas fa-times-circle text-danger"></i> Confirm Online Deposit Deny'
            );

            $("#statusSubmitBtn").removeClass("btn-primary").addClass("btn-danger").html(
                '<i class="fas fa-times"></i> Confirm Online Deposit Deny'
            );
        }

        // dynamic form action
        $("#statusForm").attr("action", "{{ route('account.online-deposit-verifications.update-status', 'ID') }}".replace("ID", id));
    });

    // Datepicker
    $(".datePicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
</script>
@endsection

@endsection
