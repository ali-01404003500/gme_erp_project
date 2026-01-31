@extends('layout.app')
@section('title', 'Application Entry List')
@section('description', 'Application Entry List')

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
                                        {{ trans('Application Entry List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('cms.application-entries.create'))
                                    <a href="{{ route('cms.application-entries.create') }}"
                                        class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Application Entry list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="30%">
                                                    <select name="customer_id" id="customer_id"
                                                        class="form-control tom-select"
                                                        data-placeholder="Search by Customer">
                                                        <option value=""></option>
                                                        @foreach ($customers as $value)
                                                            <option
                                                                {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                                value="{{ $value->id }}">{{ $value->company_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="70%">
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $applicationEntrys])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Date</th>
                                        <th>Customer Info</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Request By</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applicationEntrys as $value)
                                        @php
                                            // Get all batch entries if this is a batch
                                            $batchEntries = $value->batch_id 
                                                ? \Modules\CMS\Models\ApplicationEntry::where('batch_id', $value->batch_id)->get() 
                                                : collect([$value]);
                                        @endphp
                                        <tr>
                                            <td>{{ ($applicationEntrys->currentPage() - 1) * $applicationEntrys->perPage() + $loop->iteration  }}</td>


                                            <td>{{ $value->date }}</td>
                                            <td>
                                                @if($value->type == 'Cheque')
                                                    @if($value->batch_id && $batchEntries->count() > 1)
                                                        @foreach($batchEntries as $batchEntry)
                                                            @if($batchEntry->advanceChequeEntryDetail)
                                                            <strong>Name: {{ $batchEntry->customer->company_name }}</strong><br>

                                                                C. No: {{ $batchEntry->advanceChequeEntryDetail->cheque_no ?? 'N/A' }}<br>
                                                                C. Date: {{ $batchEntry->advanceChequeEntryDetail->cheque_date ?? 'N/A' }}<br>
                                                                C. Amount: {{ number_format($batchEntry->advanceChequeEntryDetail->amount ?? 0) }}<br>
                                                                @php
                                                                    $documents = is_string($batchEntry->advanceChequeEntryDetail->document) 
                                                                        ? json_decode($batchEntry->advanceChequeEntryDetail->document, true) 
                                                                        : $batchEntry->advanceChequeEntryDetail->document;
                                                                @endphp
                                                                @if (!empty($documents) && is_array($documents))
                                                                    @foreach ($documents as $doc)
                                                                        <a href="{{ $doc }}" target="_blank"><i class="fa fa-image"></i></a>
                                                                    @endforeach
                                                                @endif
                                                                <hr style="margin: 5px 0;">
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    <strong>Name: {{ $value->customer->company_name }}</strong><br>

                                                        C. No: {{ $value->advanceChequeEntryDetail->cheque_no ?? 'N/A' }}<br>
                                                        C. Date: {{ $value->advanceChequeEntryDetail->cheque_date ?? 'N/A' }}<br>
                                                        C. Amount: {{ number_format($value->advanceChequeEntryDetail->amount ?? 0) }}<br>
                                                        @php
                                                            $documents = is_string($value->advanceChequeEntryDetail->document) 
                                                                ? json_decode($value->advanceChequeEntryDetail->document, true) 
                                                                : $value->advanceChequeEntryDetail->document;
                                                        @endphp
                                                        @if (!empty($documents) && is_array($documents))
                                                            @foreach ($documents as $doc)
                                                                <a href="{{ $doc }}" target="_blank"><i class="fa fa-image"></i></a>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @else
                                              <strong>Name: {{ $value->customer->company_name }}</strong><br>

                                                    Add: {{ $value->customer->address }}<br>
                                                    Phone: {{ $value->customer->phone }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $value->type }}
                                               
                                            </td>
                                            <td>
                                                <span style="display: block;width: 500px !important; white-space: wrap; overflow: hidden;">
                                                    {{ $value->description }}
                                                </span>
                                            </td>
                                            <td>
                                                <span style="display: block;width: 300px !important; white-space: wrap; overflow: hidden;">
                                                    Entry: {{ $value->createdBy->name }}<br>
                                                    @if ($value->approved_by != null)
                                                        Approved: {{ $value->approvedBy->name }}<br>
                                                    @endif
                                                    @if ($value->handover_by != null)
                                                        Handover: {{ $value->handoverBy->name }}<br>
                                                    @endif
                                                    @if ($value->received_by != null)
                                                        Received: {{ $value->receivedBy->name }}<br>
                                                    @endif
                                                    @if ($value->denied_by != null)
                                                       <span class="text-danger">Denied: {{ $value->deniedBy->name }}</span><br>
                                                       <span class="text-danger">Note: {{ $value->denied_note }}</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if ($value->status == 'pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif ($value->status == 'approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif ($value->status == 'handover')
                                                    <span class="badge badge-round badge-primary">Handover</span>
                                                @elseif ($value->status == 'received')
                                                    <span class="badge badge-round badge-info">Received</span>
                                                @elseif ($value->status == 'denied')
                                                    <span class="badge badge-round badge-danger">Denied</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if ($value->status == 'pending')
                                                        @if (hasPermission('cms.application-entries.approved'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.approved', $value->id) }}"
                                                                data-modal="approveModal"
                                                                class="btn btn-outline-success btn-approved btn-status-action"
                                                                data-toggle="tooltip" data-placement="top" title="Approve"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if ($value->status == 'approved')
                                                        @if (hasPermission('cms.application-entries.handover'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.handover', $value->id) }}"
                                                                data-modal="handoverModal"
                                                                class="btn btn-outline-success btn-handover btn-status-action"
                                                                data-toggle="tooltip" data-placement="top" title="Handover"
                                                                data-bs-toggle="modal" data-bs-target="#handoverModal">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if ($value->status == 'handover')
                                                        @if (hasPermission('cms.application-entries.received'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.received', $value->id) }}"
                                                                data-modal="receivedModal"
                                                                class="btn btn-outline-success btn-received btn-status-action"
                                                                data-toggle="tooltip" data-placement="top" title="Received"
                                                                data-bs-toggle="modal" data-bs-target="#receivedModal">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if ($value->status != 'denied' && $value->status != 'received')
                                                        @if (hasPermission('cms.application-entries.deny'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.deny', $value->id) }}"
                                                                data-modal="denyModal"
                                                                class="btn btn-outline-danger btn-deny btn-status-action"
                                                                data-toggle="tooltip" data-placement="top" title="Deny"
                                                                data-bs-toggle="modal" data-bs-target="#denyModal">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if ($value->status == 'pending' && $value->type != 'Cheque')
                                                        @if (hasPermission('cms.application-entries.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('cms.application-entries.edit', $value->id) }}"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif
                                                        @if (hasPermission('cms.application-entries.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.destroy', $value->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif
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

    <!-- Approve Modal -->
    <div class="modal fade inputForm-modal" id="approveModal" tabindex="-1" role="dialog"
        aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" id="approveModalLabel">
                    <h5 class="modal-title">Approve</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="POST" id="approveForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Approve Comments</label>
                            <div class="col-sm-12">
                                <textarea name="approved_comment" id="approved_comment" class="form-control"></textarea>
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

    <!-- Handover Modal -->
    <div class="modal fade inputForm-modal" id="handoverModal" tabindex="-1" role="dialog"
        aria-labelledby="handoverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" id="handoverModalLabel">
                    <h5 class="modal-title">Handover</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="POST" id="handoverForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Handover Comments</label>
                            <div class="col-sm-12">
                                <textarea name="handover_comment" id="handover_comment" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Handover</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Received Modal -->
    <div class="modal fade inputForm-modal" id="receivedModal" tabindex="-1" role="dialog"
        aria-labelledby="receivedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" id="receivedModalLabel">
                    <h5 class="modal-title">Received</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="POST" id="receivedForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Received Comments</label>
                            <div class="col-sm-12">
                                <textarea name="received_comment" id="received_comment" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Received</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deny Modal -->
    <div class="modal fade inputForm-modal" id="denyModal" tabindex="-1" role="dialog"
        aria-labelledby="denyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" id="denyModalLabel">
                    <h5 class="modal-title">Deny</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="POST" id="denyForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Deny Comments</label>
                            <div class="col-sm-12">
                                <textarea name="deny_comment" id="deny_comment" class="form-control"></textarea>
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

@endsection
@section('page_scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-status-action', function() {
                const action = $(this).data('action');
                const modalId = $(this).data('modal');
                $(`#${modalId} form`).attr('action', action);
            });
        });
    </script>
@endsection