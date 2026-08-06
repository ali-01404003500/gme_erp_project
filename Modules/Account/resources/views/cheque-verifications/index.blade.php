@section('title', 'Cheque Deposit Verification')
@section('description', 'Cheque Deposit Verification')
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

                                    <li class="breadcrumb-item active" aria-current="page">Cheque Deposit Verification</li>
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
                                                    <select name="bank_id" class="form-control tom-select">
                                                        <option value="">Select bank</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}"
                                                                @if (request('bank_id') == $bank->id) selected @endif>
                                                                {{ $bank->name }}</option>
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
                                                <td width="20%">
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
                                        <th width="30%">Entry Info & Remarks</th>
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
                                                @if($entry->head_id != null)
                                                {{-- @dd($entry->account->accountable); --}}
                                                <strong> Bank: </strong> {{ $entry->account->accountable->account_name ?? '-' }} <br>
                                                @else
                                                <strong>Bank Name:</strong> {{ $entry->bank->name ?? '-' }} <br>
                                                <strong>Branch Name:</strong> {{ $entry->branch->name ?? '-' }}
                                                @endif
                                            </td>

                                            {{-- Particulars --}}
                                            <td>
                                                <strong>Cheque No:</strong> {{ $entry->cheque_no ?? '-' }} <br>
                                                <strong>Cheque Date:</strong> {{ $entry->cheque_date ?? '-' }} <br>
                                                <strong>Amount:</strong> {{ number_format($entry->amount) }} <br><br>  
                                                @foreach ( $entry->chequeDishonorSummaries as $index => $chequeDishonorSummary )
                                                    <span class="text-danger">{{ ordinal($index + 1) }} Dishonor Date : {{ $chequeDishonorSummary->dishonor_date}}<span><br>
                                                @endforeach
                                            </td>

                                            {{-- Entry Info --}}
                                            <td>
                                                <strong>Entry By:</strong> {{ $entry->createdBy->name }} <br>
                                                <strong>Entry Date:</strong> {{ $entry->created_at->format('d-m-Y') }} <br>
                                                <strong>Remarks:</strong> {{ $entry->remarks }}<br>
                                                <strong>Attachment:</strong>
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

                                                    @if ($entry->status == 'pending')
                                                        @if (hasPermission('account.cheque-verifications.deposit'))
                                                            <button type="button"
                                                                class="btn btn-sm btn-success deposit-btn"
                                                                data-bs-toggle="modal" data-bs-target="#depositModal"
                                                                data-id="{{ $entry->id }}"
                                                                data-customer="{{ $entry->customer_id }}"
                                                                data-bank="{{ $entry->bank_id }}"
                                                                data-branch="{{ $entry->branch_id }}"
                                                                data-chequeno="{{ $entry->cheque_no }}"
                                                                data-chequedate="{{ $entry->cheque_date }}"
                                                                data-amount="{{ $entry->amount }}"
                                                                data-document='@json($entry->document)'>
                                                                <i class="fas fa-university"></i> Deposit
                                                            </button>
                                                     
                                                            <form action="{{ route('account.cheque-verifications.cash', $entry->id) }}" method="POST" class="cash-form d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-info"
                                                                        title="Collect as Cash">
                                                                    <i class="fas fa-money-bill-wave"></i> Cash
                                                                </button>
                                                            </form>

                                                            <a href="{{ route('account.cheque-verifications.return', $entry->id) }}"  class="btn btn-danger return-btn">
                                                                <i class="fa fa-undo"></i> Return
                                                            </a>


                                                        @endif
                                                    @elseif($entry->status == 'deposited')
                                                        @if (hasPermission('account.cheque-verifications.check'))
                                                            <button type="button" class="btn btn-sm btn-primary status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Checking" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                 data-status="honored">
                                                                <i class="fas fa-check-circle"></i> Checking
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-danger status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Reject" data-id="{{ $entry->id }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-status="dishonored">
                                                                <i class="fas fa-times-circle"></i> Reject
                                                            </button>
                                                        @endif
                                                    @elseif($entry->status == 'honored')
                                                        @if (hasPermission('account.cheque-verifications.check-verification'))
                                                            <button type="button" class="btn btn-sm btn-primary status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Approve Honor" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-status="honor-verified">
                                                                <i class="fas fa-check-circle"></i> Honor
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-danger status-btn"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                title="Approve Dishonor" data-id="{{ $entry->id }}"
                                                                data-remarks="{{ $entry->remarks }}"
                                                                data-charge="{{ $entry->charge }}"
                                                                data-status="dishonored">
                                                                <i class="fas fa-times-circle"></i> Dishonor
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

    {{-- 🏦 Deposit Modal --}}
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="depositForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-university"></i> Cheque Deposit Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Hidden fields --}}
                        <input type="hidden" name="detail_id" id="detail_id">
                        <input type="hidden" name="customer_id" id="customer_id">
                        <input type="hidden" name="bank_id" id="bank_id">
                        <input type="hidden" name="branch_id" id="branch_id">
                        <input type="hidden" name="cheque_no" id="cheque_no">
                        <input type="hidden" name="cheque_date" id="cheque_date">
                        <input type="hidden" name="amount" id="amount">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Deposit Date</label>
                                <input type="text" name="deposit_date" class="form-control flatdate"
                                    value="{{ today() }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bank Head (COA)</label>
                                <select name="head_id" class="form-control tom-select" required>
                                    <option value="">-- Select Bank Account --</option>
                                    @foreach ($bankHeads as $head)
                                        <option value="{{ $head->id }}">{{ $head->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" id="remarks" rows="2" placeholder="Enter remarks"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Attachment <span class="text-danger">*</span></label>
                                <x-file-uploader loadLater multiple :value="old('document')" name="document" id="document" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirm
                            Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- 🔄 Honor / Dishonor Modal --}}
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

                        {{-- Additional charge will show only if dishonor --}}
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

    // Cash Confirmation
    $(document).on('submit', '.cash-form', function (e) {
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Collect as Cash?',
            text: "Are you sure you want to collect this cheque to cash?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Continue',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Return Confirmation
    $(document).on('click', '.return-btn', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');

        Swal.fire({
            title: 'Return Cheque?',
            text: "Are you sure you want to return this cheque?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Return',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });



 $('#imagePreviewModal-document').on('hidden.bs.modal', function () {

    // Body scrolling বন্ধ রাখুন
    $('body').addClass('modal-open');

        // Parent modal যদি hide হয়ে যায় তাহলে আবার show করুন
        const depositModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('depositModal')
        );

        depositModal.show();
    });


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

    // 📌 Attach cheque details to Deposit Modal
    $(document).on("click", ".deposit-btn", function() {
        let id = $(this).data("id");

        // ✅ set action URL dynamically
        $("#depositForm").attr("action", "/account/cheque-verifications/deposit/" + id);

        // hidden inputs
        $("#detail_id").val(id);
        $("#customer_id").val($(this).data("customer"));
        $("#bank_id").val($(this).data("bank"));
        $("#branch_id").val($(this).data("branch"));
        $("#cheque_no").val($(this).data("chequeno"));
        $("#cheque_date").val($(this).data("chequedate"));
        $("#amount").val($(this).data("amount"));

        // documents preview handling
        const dcuments = document.getElementById('document');
        if (dcuments.uploader) {
            dcuments.uploader.removeAllFiles();
            // const documents = JSON.parse($(this).data("document") || "[]");
            if (documents && documents.length) {
                documents.forEach(document => {
                    if (document) dcuments.uploader.addExistingFile(document);
                });
            }
        } else {
            initializeFileUploader_document_document(undefined, true);
        }
    });

    // 📌 Status Modal (Honor / Dishonor)
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
        if (status === "honored" || status === "honor-verified") {
            $("#statusModalTitle").html(
                '<i class="fas fa-check-circle text-primary"></i> Confirm Cheque Honor'
            );
            $("#statusSubmitBtn").removeClass("btn-danger").addClass("btn-primary").html(
                '<i class="fas fa-check"></i> Confirm Honor'
            );
        } else {
            $("#statusModalTitle").html(
                '<i class="fas fa-times-circle text-danger"></i> Confirm Cheque Dishonor'
            );
            $("#statusSubmitBtn").removeClass("btn-primary").addClass("btn-danger").html(
                '<i class="fas fa-times"></i> Confirm Dishonor'
            );
        }

        // dynamic form action
        $("#statusForm").attr("action", "/account/cheque-verifications/status/" + id);
    });

    // 📌 Datepicker
    $(".datePicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
</script>
@endsection

@endsection
