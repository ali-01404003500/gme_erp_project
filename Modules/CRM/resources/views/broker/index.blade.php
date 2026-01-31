@section('title',"Broker List")
@section('description',"Broker List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('brokers list') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('crm.brokers.create'))
                        <a href="{{ route('crm.brokers.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm">
                            <i class="las la-plus fs-16"></i>Add New
                        </a>
                        @endif
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                            <i class="las la-file-pdf fs-16"></i> PDF
                        </a>
                        <button type="button" class="btn btn-xs btn-success btn-sm me-2 ml-5" data-bs-toggle="modal" style="margin-left: 5px;"
                        data-bs-target="#importModal">
                        <i class="las la-file-import fs-16"></i> Import CSV
                    </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('brokers list') }}</h4>
            </div>
            
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <select name="broker_name" id="broker_name" class="form-control tom-select"
                                                    data-placeholder="Select Broker">
                                                    <option value=""></option>
                                                    @foreach ($brokers as $key => $value)
                                                        <option {{ request('broker_name') == $value->broker_name ? 'selected' : '' }}
                                                            value="{{ $value->broker_name }}">
                                                            {{ $value->broker_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control" name="mobile"
                                                    value="{{ request('mobile') }}" autocomplete="off"
                                                    placeholder="Search by Phone">
                                            </td>
                                            <td class="text-center">
                                                    <select name="customer_id" id="customer_id" class="form-control tom-select"
                                                        data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                        @foreach ($customers as $key => $value)
                                                            <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                                value="{{ $value->id }}">
                                                                {{ $value->company_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
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
                <div class="card mb-4">
                    <div class="card-body">
                        
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $brokers])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Broker Name</th>
                                    <th>Address</th>
                                    <th>Phone No</th>
                                    <th>Email</th>
                                    <th>Customer Name</th>
                                    <th>
                                            Status
                                        </th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brokers as $broker)
                                    <tr>
                                        <td>{{ ($brokers->currentPage() - 1) * $brokers->perPage() + $loop->iteration  }}</td>
                                        <td>
                                            <a href="{{ route('crm.brokers.show', $broker->id) }}">{{ $broker->broker_name }}</i></a>
                                        </td>
                                        <td>{{ $broker->present_address }}</td>
                                        <td>{{ $broker->mobile }}</td>
                                        <td>{{ $broker->email }}</td>
                                        <td>
                                            @foreach ($broker->customerAttached ?? [] as $customerAttached)
                                            {{ optional($customerAttached->customer)->company_name }} <br>
                                            @endforeach
                                        </td>
                                         <td>
                                                @if ($broker->status == 1)
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif ($broker->status == 2)
                                                    <span class="badge badge-round badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-round badge-danger">Deny</span>
                                                @endif
                                            </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if(hasPermission('crm.brokers.approve') && $broker->status == 1)
                                                        <a href="{{ route('crm.brokers.approve', $broker->id) }}"
                                                        class="btn btn-xs btn-outline-success approval-confirm-broker"
                                                        data-action="{{ route('crm.brokers.approve', $broker->id) }}"
                                                        data-confirm-title="Approve Broker?"
                                                        data-confirm-message="Are you sure you want to approve this broker?"
                                                        data-confirm-icon="success"
                                                        data-confirm-text="Yes, Approve it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>

                                                        <a href="{{ route('crm.brokers.deny', $broker->id) }}"
                                                        class="btn btn-xs btn-outline-danger reject-confirm-broker"
                                                        data-action="{{ route('crm.brokers.deny', $broker->id) }}"
                                                        data-confirm-title="Reject Broker?"
                                                        data-confirm-message="Are you sure you want to reject this broker?"
                                                        data-confirm-icon="warning"
                                                        data-confirm-text="Yes, Reject it!">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('crm.brokers.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('crm.brokers.edit', $broker->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif



                                                    @if (hasPermission('crm.brokers.destroy'))
                                                        <button type="button" data-action="{{ route('crm.brokers.destroy', $broker->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('crm.brokers.show'))
                                                        <a class="btn btn-outline-primary" href="{{ route('crm.brokers.show', $broker->id) }}"><i class="fas fa-eye"></i></a>
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
    <div class="modal fade inputForm-modal" id="importModal" tabindex="-1" role="dialog"
    aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" id="importModalLabel">
                <h5 class="modal-title">Import from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('crm.brokers-insert') }}" method="post" id="importFrom"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <label for="csv_file" class="col-sm-12 col-form-label">CSV File</label>
                        <div class="col-sm-12">
                            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <a href="{{ route('crm.brokers-download') }}" class="btn btn-info">Download Sample CSV</a>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('page_scripts')
<script>
    function approvalConfirm(e) {
    e.preventDefault();
    e.stopPropagation();

    const el = $(this);
    const url = el.data("action");
    const confirmTitle = el.data("confirm-title") || "Are you sure?";
    const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
    const confirmIcon = el.data("confirm-icon") || "success";
    const confirmText = el.data("confirm-text") || "Yes, Approve it!";

    Swal.fire({
        title: confirmTitle,
        text: confirmMessage,
        icon: confirmIcon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function rejectConfirm(e) {
    e.preventDefault();
    e.stopPropagation();

    const el = $(this);
    const url = el.data("action");
    const confirmTitle = el.data("confirm-title") || "Are you sure?";
    const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
    const confirmIcon = el.data("confirm-icon") || "warning";
    const confirmText = el.data("confirm-text") || "Yes, Reject it!";

    Swal.fire({
        title: confirmTitle,
        text: confirmMessage,
        icon: confirmIcon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

$(document).ready(function () {
    $(".approval-confirm-broker").on("click", approvalConfirm);
    $(".reject-confirm-broker").on("click", rejectConfirm);
});

</script>

@endsection