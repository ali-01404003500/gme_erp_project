@section('title', 'Daily Visit Plan')
@section('description', 'Daily Visit Plan')
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
                                        {{ trans('Daily Visit Plan list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('hrm.daily-visit-plans.create'))
                                <a href="{{ route('hrm.daily-visit-plans.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Daily Visit Plan list') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>


                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control datePicker" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control datePicker" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
                                            </td>
                                            <td colspan="3" class="text-right">
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
                <div class="col-md-12">

                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $dailyVisitPlans])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>

                                        <th>Visit Date
                                        </th>
                                        <th>
                                            Company Name
                                        </th> 
                                        <th>
                                            Phone
                                        </th>
                                        <th>
                                            Contact Person
                                        </th>
                                        <th>
                                            Requirement
                                        </th>
                                        <th>
                                            Attachment/Images/Document
                                        </th>
                                        <th>
                                            Status
                                        </th>

                                        <th class="no-content">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($dailyVisitPlans) --}}
                                    @foreach ($dailyVisitPlans as $plan)
                                        {{-- @dd($salesOrder->delivery) --}}
                                        <tr>
                                            <td class="text-center">{{ ($dailyVisitPlans->currentPage() - 1) * $dailyVisitPlans->perPage() + $loop->iteration  }}</td>

                                            <td>
                                                {{ $plan->date }}
                                            </td>
                                            <td>
                                                {{ $plan->company_name }}
                                                <br><small class="text-muted"> {{ $plan->address ?? 'N/A' }}</small>
                                            </td> 
                                            <td>
                                                {{ $plan->phone_no ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $plan->contact_person ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span
                                                    style="display: block;width: 500px !important; white-space: wrap; overflow: hidden;">
                                                    {{ $plan->description ?? 'N/A' }}

                                                </span>
                                            </td>
                                            <td>
                                                {{-- @dd($plan->attachment) --}}
                                               @php
                                                    $documents = is_string($plan->attachment)
                                                        ? json_decode($plan->attachment, true)
                                                        : $plan->attachment;

                                                    // Ensure it's an array and remove null/empty values
                                                    $documents = is_array($documents) ? array_filter($documents) : [];
                                                @endphp

                                                @if (!empty($documents))
                                                    @foreach ($documents as $doc)
                                                        <a href="{{ $doc }}" target="_blank"><i class="fa fa-download" style="font-size: 24px;"></i></a>
                                                    @endforeach
                                                @endif

                                            </td>

                                            <td>
                                                @if ($plan->status == 'pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif ($plan->status == 'approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-round badge-danger">Deny</span>
                                                @endif
                                            </td>


                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if ($plan->status == 'pending')
                                                        @if (hasPermission('hrm.daily-visit-plans.approve'))
                                                            <a href="{{ route('hrm.daily-visit-plans.approve', $plan->id) }}"
                                                                class="btn btn-xs btn-outline-success approval-confirm-plan"
                                                                data-action="{{ route('hrm.daily-visit-plans.approve', $plan->id) }}"
                                                                data-confirm-title="Approve Daily Visit Plan?"
                                                                data-confirm-message="Are you sure you want to approve this Daily Visit Plan?"
                                                                data-confirm-icon="success"
                                                                data-confirm-text="Yes, Approve it!">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @endif

                                                        @if (hasPermission('hrm.daily-visit-plans.deny'))

                                                            <a href="{{ route('hrm.daily-visit-plans.deny', $plan->id) }}"
                                                                class="btn btn-xs btn-outline-danger reject-confirm-plan"
                                                                data-action="{{ route('hrm.daily-visit-plans.deny', $plan->id) }}"
                                                                data-confirm-title="Reject Daily Visit Plan?"
                                                                data-confirm-message="Are you sure you want to reject this Daily Visit Plan?"
                                                                data-confirm-icon="warning"
                                                                data-confirm-text="Yes, Reject it!">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        @endif

                                                        @if (hasPermission('hrm.daily-visit-plans.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('hrm.daily-visit-plans.edit', $plan->id) }}"><i
                                                                    class="far fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if (hasPermission('hrm.daily-visit-plans.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.daily-visit-plans.destroy', $plan->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"><i
                                                                    class="far fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if(hasPermission('hrm.daily-visit-plans.show'))
                                                        <a class="btn btn-outline-info"
                                                            href="{{ route('hrm.daily-visit-plans.show', $plan->id) }}"><i
                                                                class="far fa-eye"></i>
                                                        </a>
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

        $(document).ready(function() {
            $(".approval-confirm-plan").on("click", approvalConfirm);
            $(".reject-confirm-plan").on("click", rejectConfirm);
        });
    </script>
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endSection
