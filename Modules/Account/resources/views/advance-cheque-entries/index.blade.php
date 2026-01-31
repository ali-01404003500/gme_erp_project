@section('title', 'Advance Cheque Entry List')
@section('description', 'Advance Cheque Entry List')
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
                                        {{ trans('Advance Cheque Entry list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.advance-cheque-entries.create'))
                                <a href="{{ route('account.advance-cheque-entries.create') }}"
                                    class="btn px-20 btn-primary btn-sm mr-5">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Advance Cheque Entry list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="text" name="receipt_no" class="form-control"
                                                        placeholder="Receipt No" value="{{ request('receipt_no') }}">
                                                </td>
                                                <td style="width: 25%">
                                                    <select name="customer_id" class="form-control tom-select">
                                                        <option value="">Select Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                @if (request('customer_id') == $customer->id) selected @endif>
                                                                {{ $customer->company_name }} - {{ $customer->address}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="width: 35%">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdaterange"
                                                            name="from_to" value="{{ request('from_to') }}"
                                                            placeholder="From - To" />
                                                    </div>
                                                </td>
                                                <td class="text-right" style="width: 15%">
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $advanceChequeEntrys])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Address</th>
                                        <th>Prepared by</th>
                                        <th>Document</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($advanceChequeEntrys as $value)
                                        <tr>
                                            <td>{{ ($advanceChequeEntrys->currentPage() - 1) * $advanceChequeEntrys->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $value->receipt_no }}</td>
                                            <td>
                                                @if (hasPermission('account.advance-cheque-entries.show'))
                                                    <a href="{{ route('account.advance-cheque-entries.show', $value->id) }}"
                                                        class="text-primary">
                                                        {{ $value->customer->company_name }}
                                                    </a>
                                                @else
                                                    {{ $value->customer->company_name }}
                                                @endif
                                            </td>
                                            <td>{{ $value->customer->address }}</td>
                                            <td>{{ $value->createdBy->name }}</td>
                                            <td>
                                                @if ($value->document)
                                                    <a href="{{ $value->document }}" target="_blank"><i
                                                            class="fa fa-download"></i></a>
                                                @endif
                                                @foreach ($value->details as $key => $detail)
                                                    @php
                                                        $documents = is_string($detail->document)
                                                            ? json_decode($detail->document, true)
                                                            : $detail->document;
                                                    @endphp
                                                    @if (!empty($documents) && is_array($documents))
                                                        @foreach ($documents as $doc)
                                                            <a href="{{ $doc }}" target="_blank"><i
                                                                    class="fa fa-image"></i></a>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $value->total_amount }}</td>
                                            <td>{{ $value->collection_date }}</td>
                                            <td>
                                                @if ($value->status == 'Pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($value->status == 'Approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($value->status == 'Checked')
                                                    <span class="badge badge-round badge-info">Checked</span>
                                                @elseif($value->status == 'Denied')
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if (hasPermission('account.advance-cheque-entries.check') && $value->status == 'Pending')
                                                        <a href="{{ route('account.advance-cheque-entries.check', $value->id) }}"
                                                            title="Checker"
                                                            class="btn btn-xs btn-outline-success checker-confirm-cheque"
                                                            data-action="{{ route('account.advance-cheque-entries.check', $value->id) }}"
                                                            data-confirm-title="Checker Approval?"
                                                            data-confirm-message="Are you sure you want to Checked this?"
                                                            data-confirm-icon="success"
                                                            data-confirm-text="Yes, Approve it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="{{ route('account.advance-cheque-entries.deny', $value->id) }}"
                                                            class="btn btn-xs btn-outline-danger reject-confirm-cheque"
                                                            title="Reject"
                                                            data-action="{{ route('account.advance-cheque-entries.deny', $value->id) }}"
                                                            data-confirm-title="Reject Customer?"
                                                            data-confirm-message="Are you sure you want to reject this?"
                                                            data-confirm-icon="warning" data-confirm-text="Yes, Reject it!">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                        @if (hasPermission('account.advance-cheque-entries.update'))
                                                            <a class="btn btn-xs btn-outline-warning" title="Edit"
                                                                href="{{ route('account.advance-cheque-entries.edit', $value->id) }}"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif
                                                        @if (hasPermission('account.advance-cheque-entries.destroy'))
                                                            <button type="button" title="Delete"
                                                                data-action="{{ route('account.advance-cheque-entries.destroy', $value->id) }}"
                                                                class="btn btn-xs btn-outline-danger delete-confirm"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif
                                                    @endif
                                                    @if (hasPermission('account.advance-cheque-entries.approve') && $value->status == 'Checked')
                                                        <a href="{{ route('account.advance-cheque-entries.approve', $value->id) }}"
                                                            title="Approver"
                                                            class="btn btn-xs btn-outline-success approval-confirm-cheque"
                                                            data-action="{{ route('account.advance-cheque-entries.approve', $value->id) }}"
                                                            data-confirm-title="Approve Approval?"
                                                            data-confirm-message="Are you sure you want to approve this?"
                                                            data-confirm-icon="success"
                                                            data-confirm-text="Yes, Approve it!">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="{{ route('account.advance-cheque-entries.deny', $value->id) }}"
                                                            class="btn btn-xs btn-outline-danger reject-confirm-cheque"
                                                            title="Reject"
                                                            data-action="{{ route('account.advance-cheque-entries.deny', $value->id) }}"
                                                            data-confirm-title="Reject Customer?"
                                                            data-confirm-message="Are you sure you want to reject this?"
                                                            data-confirm-icon="warning"
                                                            data-confirm-text="Yes, Reject it!">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                        @if (hasPermission('account.advance-cheque-entries.update'))
                                                            <a class="btn btn-xs btn-outline-warning" title="Edit"
                                                                href="{{ route('account.advance-cheque-entries.edit', $value->id) }}"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif
                                                        @if (hasPermission('account.advance-cheque-entries.destroy'))
                                                            <button type="button" title="Delete"
                                                                data-action="{{ route('account.advance-cheque-entries.destroy', $value->id) }}"
                                                                class="btn btn-xs btn-outline-danger delete-confirm"><i
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
@endsection

@section('page_scripts')
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
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

        function checkerConfirm(e) {
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

        $(document).ready(function() {
            $(".checker-confirm-cheque").on("click", checkerConfirm);
            $(".approval-confirm-cheque").on("click", approvalConfirm);
            $(".reject-confirm-cheque").on("click", rejectConfirm);
        });
    </script>
@endsection
