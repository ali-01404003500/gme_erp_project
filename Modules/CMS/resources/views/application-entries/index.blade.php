@extends('layout.app')
@section('title', 'Application Entry List')
@section('description', 'Application Entry List')

@section('content')
    <style>
        /* Modern Mesh Gradient Background */
        
 
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
                                    <label class="form-label  text-muted small">FILTER BY CUSTOMER</label>
                                    <select name="customer_id" id="customer_id" class="form-control"
                                        data-placeholder="Choose a customer..." style="x-index:-10000000000;">
                                        <option value=""></option> 
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
                                            <th width="60" class="text-center" >Sl</th>
                                            <th class="text-center" >Date</th>
                                            <th  class="text-center" >Customer Info</th>
                                            <th class="text-center" >Type</th>
                                            <th class="text-center" >Description</th>
                                            <th class="text-center" >Request By</th>
                                            <th class="text-center" >Status</th>
                                            <th class="text-center"  width="180">Action</th>
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
                                                <td class="text-center  text-muted small">
                                                    {{ ($applicationEntrys->currentPage() - 1) * $applicationEntrys->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center small "> {{ $value->date }}</td>
                                                <td class="text-left" >
                                                    <div class="customer-info-box">
                                                        <span class="text-dark ">{{ $value->customer->company_name }}</span><br>
                                                        <small class="text-muted"><i class="las la-map-marker me-1"></i>{{ $value->customer->address }}</small> 
                                                    </div>
                                                </td>
                                                <td class="text-left">
                                                    <span class="text-primary  small ">{{ $value->type }}</span><br>
                                                    @if($value->type == 'Cheque')
                                                        @foreach($batchEntries as $batchEntry)
                                                            @if($batchEntry->advanceChequeEntryDetail)
                                                                <small class="text-muted">
                                                                    Cheque No:  {{ $batchEntry->advanceChequeEntryDetail->cheque_no ?? 'N/A' }} |
                                                                    Amt: {{ number_format($batchEntry->advanceChequeEntryDetail->amount ?? 0) }}
                                                                </small>
                                                                @if(!$loop->last)
                                                                <hr class="my-1 opacity-25"> 
                                                                @endif
                                                            @endif
                                                        @endforeach 
                                                    @endif 
                                                </td>
                                                <td>
                                                    <div style="max-width: 250px; white-space: normal;" class="text-muted small">{{ $value->description }}</div>
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
                                                                class="btn btn-sm   btn-status-action btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#approveModal"
                                                                title="Approve"><i class="fas fa-check"></i></button>
                                                        @endif
                                                        @if ($value->status == 'approved' && hasPermission('cms.application-entries.handover'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.handover', $value->id) }}"
                                                                data-modal="handoverModal"
                                                                class="btn btn-sm   btn-status-action btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#handoverModal"
                                                                title="Handover"><i class="fas fa-shipping-fast"></i></button>
                                                        @endif
                                                        @if ($value->status == 'handover' && hasPermission('cms.application-entries.received'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.received', $value->id) }}"
                                                                data-modal="receivedModal"
                                                                class="btn btn-sm   btn-status-action btn-success"
                                                                data-bs-toggle="modal" data-bs-target="#receivedModal"
                                                                title="Receive"><i class="fas fa-box-open"></i></button>
                                                        @endif
                                                        @if ($value->status != 'denied' && $value->status != 'received' && hasPermission('cms.application-entries.deny'))
                                                            <button type="button"
                                                                data-action="{{ route('cms.application-entries.deny', $value->id) }}"
                                                                data-modal="denyModal"
                                                                class="btn btn-sm   btn-status-action  btn-danger"
                                                                data-bs-toggle="modal" data-bs-target="#denyModal" title="Deny"><i
                                                                    class="fas fa-times"></i></button>
                                                        @endif
                                                        @if ($value->status == 'pending' && $value->type != 'Cheque')
                                                            @if (hasPermission('cms.application-entries.update')) <a
                                                                class="btn btn-sm   btn-warning"
                                                                href="{{ route('cms.application-entries.edit', $value->id) }}"
                                                            title="Edit"><i class="lar la-edit"></i></a> @endif
                                                            @if (hasPermission('cms.application-entries.destroy')) <button
                                                                type="button"
                                                                data-action="{{ route('cms.application-entries.destroy', $value->id) }}"
                                                                class="btn btn-sm  delete-confirm btn-danger" title="Delete"><i
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
                            <label class="form-label  small text-muted">{{$title}} Comments</label>
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

            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            companySelect.clearOptions(); 
                            callback(res.map(item => ({ id: item.id, text: item.label   })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
     
            @if(isset($customer))
                companySelect.addOption({
                    id: "{{ $customer->id }}",
                    text: "{{ $customer->company_name }}"
                });
                companySelect.setValue("{{ $customer->id }}");
            @endif

            
        });
    </script>
@endsection