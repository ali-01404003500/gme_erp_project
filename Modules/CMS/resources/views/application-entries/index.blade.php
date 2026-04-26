@extends('layout.app')
@section('title', 'Application Entry List')
@section('description', 'Application Entry List')

@section('content')
    <style>
        /* Modern Mesh Gradient Background */
        body {
            background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 212, 255, 0.12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(95, 99, 242, 0.08) 0px, transparent 50%),
                #f8fafc !important;
            min-height: 100vh;
        }

        /* Glassmorphism Card Style */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.7) !important;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            border-radius: 16px !important;
        }

        /* FULL TABLE BORDER STYLING */
        .table-container {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table-bordered {
            border: 1px solid #e2e8f0 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e2e8f0 !important;
        }

        /* BOLD & MEDIUM-BIG Table Headers */
        .table thead th {
            background-color: rgba(95, 99, 242, 0.08) !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            font-size: 0.95rem !important;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #5f63f2 !important;
            padding: 18px 15px !important;
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td {
            padding: 15px !important;
            vertical-align: middle !important;
            color: #334155;
            background: transparent;
        }

        .table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Floating Action Buttons (Document Entry Style) */
        .action-btn-group .btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            margin: 0 3px;
            border-radius: 8px !important;
            transition: all 0.2s;
            padding: 5px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn-group .btn:hover {
            background: #5f63f2;
            color: white !important;
            border-color: #5f63f2;
            transform: translateY(-2px);
        }

        .action-btn-group .btn-outline-danger:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        /* Badge Styling */
        .badge-round {
            padding: 6px 12px !important;
            border-radius: 8px !important;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .customer-info-box {
            background: rgba(255, 255, 255, 0.5);
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgba(95, 99, 242, 0.1);
        }

        .breadcrumb-main {
            background: transparent;
        }

        .table thead th {
            background-color: #35526e !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 0.85rem !important;
            letter-spacing: 0.08em;
            border-bottom: 2px solid #2a4054 !important;
            padding: 14px 16px !important;
            vertical-align: middle;
            text-align: center;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="social-dash-wrap">
            {{-- Header Section --}}
            <div class="row align-items-center mb-4">
                <div class="col-lg-12">
                    <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Application Entry List') }}
                                </li>
                            </ol>
                        </nav>
                        <div class="action-btn">
                            @if (hasPermission('cms.application-entries.create'))
                                <a href="{{ route('cms.application-entries.create') }}"
                                    class="btn btn-primary btn-sm px-4 shadow-sm border-0"
                                    style="border-radius: 10px; background: linear-gradient(90deg, #5f63f2, #7928ca);">
                                    <i class="las la-plus fs-16 me-1"></i> Add New Entry
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-0">
                        <div class="card-body">
                            <form class="row align-items-end g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small">FILTER BY CUSTOMER</label>
                                    <select name="customer_id" id="customer_id" class="form-control tom-select"
                                        data-placeholder="Choose a customer...">
                                        <option value=""></option>
                                        @foreach ($customers as $value)
                                            <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                value="{{ $value->id }}">{{ $value->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex gap-2">
                                    <button class="btn btn-primary px-4" style="border-radius: 10px; height: 44px;"><i
                                            class="fa fa-search me-2"></i>Search</button>
                                    <a href="{{ request()->url() }}" class="btn btn-light border px-4"
                                        style="border-radius: 10px; height: 44px; display: flex; align-items: center;"><i
                                            class="fa fa-refresh me-2"></i>Refresh</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="table-responsive table-container">
                                <table id="zero-config" class="table table-bordered mb-0"
                                    data-page='@include('utils.table_paginate', ['data' => $applicationEntrys])'>
                                    <thead>
                                        <tr>
                                            <th width="60">Sl</th>
                                            <th>Date</th>
                                            <th>Customer Info</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Request By</th>
                                            <th>Status</th>
                                            <th width="180">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applicationEntrys as $value)
                                            @php
                                                $batchEntries = $value->batch_id
                                                    ? \Modules\CMS\Models\ApplicationEntry::where('batch_id', $value->batch_id)->get()
                                                    : collect([$value]);
                                            @endphp
                                            <tr>
                                                <td class="text-center fw-bold text-muted small">
                                                    {{ ($applicationEntrys->currentPage() - 1) * $applicationEntrys->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center small fw-bold"><i
                                                        class="lar la-calendar me-1 text-primary"></i> {{ $value->date }}</td>
                                                <td>
                                                    <div class="customer-info-box">
                                                        @if($value->type == 'Cheque')
                                                            @foreach($batchEntries as $batchEntry)
                                                                @if($batchEntry->advanceChequeEntryDetail)
                                                                    <span
                                                                        class="text-dark fw-bold">{{ $batchEntry->customer->company_name }}</span><br>
                                                                    <small class="text-muted">No:
                                                                        {{ $batchEntry->advanceChequeEntryDetail->cheque_no ?? 'N/A' }} |
                                                                        Amt:
                                                                        {{ number_format($batchEntry->advanceChequeEntryDetail->amount ?? 0) }}</small>
                                                                    @if(!$loop->last)
                                                                    <hr class="my-1 opacity-25"> @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <span
                                                                class="text-dark fw-bold">{{ $value->customer->company_name }}</span><br>
                                                            <small class="text-muted"><i
                                                                    class="las la-map-marker me-1"></i>{{ $value->customer->address }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center"><span
                                                        class="text-primary border px-2 py-1 small fw-bold">{{ $value->type }}</span>
                                                </td>
                                                <td>
                                                    <div style="max-width: 250px; white-space: normal;"
                                                        class="text-muted small">{{ $value->description }}</div>
                                                </td>
                                                <td class="small">
                                                    <span class="text-muted">By:</span>
                                                    <strong>{{ $value->createdBy->name }}</strong><br>
                                                    @if ($value->approved_by) <span class="text-success">Approved:
                                                    {{ $value->approvedBy->name }}</span> @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClasses = ['pending' => 'warning', 'approved' => 'success', 'handover' => 'primary', 'received' => 'info', 'denied' => 'danger'];
                                                        $class = $statusClasses[$value->status] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge badge-round badge-{{ $class }}">{{ strtoupper($value->status) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group action-btn-group">
                                                        @if ($value->status == 'pending' && hasPermission('cms.application-entries.approved'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.approved', $value->id) }}"
                                                                data-modal="approveModal"
                                                                class="btn btn-sm text-success btn-status-action"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal"
                                                                title="Approve"><i class="fas fa-check"></i></button>
                                                        @endif
                                                        @if ($value->status == 'approved' && hasPermission('cms.application-entries.handover'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.handover', $value->id) }}"
                                                                data-modal="handoverModal"
                                                                class="btn btn-sm text-primary btn-status-action"
                                                                data-bs-toggle="modal" data-bs-target="#handoverModal"
                                                                title="Handover"><i class="fas fa-shipping-fast"></i></button>
                                                        @endif
                                                        @if ($value->status == 'handover' && hasPermission('cms.application-entries.received'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.received', $value->id) }}"
                                                                data-modal="receivedModal"
                                                                class="btn btn-sm text-info btn-status-action"
                                                                data-bs-toggle="modal" data-bs-target="#receivedModal"
                                                                title="Receive"><i class="fas fa-box-open"></i></button>
                                                        @endif
                                                        @if ($value->status != 'denied' && $value->status != 'received' && hasPermission('cms.application-entries.deny'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.deny', $value->id) }}"
                                                                data-modal="denyModal"
                                                                class="btn btn-sm text-danger btn-status-action"
                                                                data-bs-toggle="modal" data-bs-target="#denyModal" title="Deny"><i
                                                                    class="fas fa-times"></i></button>
                                                        @endif
                                                        @if ($value->status == 'pending' && $value->type != 'Cheque')
                                                            @if (hasPermission('cms.application-entries.update')) <a
                                                                class="btn btn-sm text-warning"
                                                                href="{{ route('cms.application-entries.edit', $value->id) }}"
                                                            title="Edit"><i class="lar la-edit"></i></a> @endif
                                                            @if (hasPermission('cms.application-entries.destroy')) <button
                                                                type="button"
                                                                data-action="{{ route('cms.application-entries.destroy', $value->id) }}"
                                                                class="btn btn-sm text-danger delete-confirm" title="Delete"><i
                                                            class="lar la-trash-alt"></i></button> @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST"> @csrf @method('DELETE') </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals Updated to Match Style --}}
    @foreach(['approve' => 'Approve', 'handover' => 'Handover', 'received' => 'Received', 'deny' => 'Deny'] as $id => $title)
        <div class="modal fade" id="{{$id}}Modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-800">{{$title}} Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <label class="form-label fw-bold small text-muted">{{$title}} Comments</label>
                            <textarea
                                name="{{$id == 'approve' ? 'approved_comment' : ($id == 'handover' ? 'handover_comment' : ($id == 'received' ? 'received_comment' : 'deny_comment'))}}"
                                class="form-control" rows="3" style="border-radius: 12px;"></textarea>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-{{$id == 'deny' ? 'danger' : 'primary'}} px-4"
                                style="border-radius: 10px;">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-status-action', function () {
                const action = $(this).data('action');
                const modalId = $(this).data('modal');
                $(`#${modalId} form`).attr('action', action);
            });
        });
    </script>
@endsection