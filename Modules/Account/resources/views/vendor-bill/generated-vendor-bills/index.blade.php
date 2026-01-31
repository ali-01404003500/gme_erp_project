@section('title', 'Generated Vendor Bills')
@section('description', 'List of auto-generated vendor bills awaiting verification')

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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                                class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.generated-vendor-bills') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <!-- Removed PDF export button per user request -->
                    </div>
                </div>

                <div class="row">
                    <!-- Page Title -->
                    <div class="col-md-12">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.generated-vendor-bills') }}</h4>
                    </div>

                    <!-- Search Filters -->
                    {{-- <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET"
                                    action="{{ route('account.vendor-bills.generated-vendor-bills.index') }}">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <!-- Bill ID or Title -->
                                                <td width="25%">
                                                    <input type="text" name="bill_id" class="form-control"
                                                        value="{{ request('bill_id') }}"
                                                        placeholder="{{ trans('form.search-by-bill-id-or-title') }}">
                                                </td>

                                                <!-- Bill For -->
                                                <td width="25%">
                                                    <input type="text" name="bill_for" class="form-control"
                                                        value="{{ request('bill_for') }}"
                                                        placeholder="{{ trans('form.search-by-bill-for') }}">
                                                </td>

                                                <!-- Bill Date -->
                                                <td width="20%">
                                                    <input type="date" name="bill_date" class="form-control"
                                                        value="{{ request('bill_date') }}">
                                                </td>

                                                <!-- Status -->
                                                <td width="15%">
                                                    <select name="status" class="form-control tom-select"
                                                        data-placeholder="{{ trans('form.status') }}">
                                                        <option value=""></option>
                                                        <option value="Generated" {{ request('status')=='Generated'
                                                            ? 'selected' : '' }}>
                                                            {{ trans('form.generated') }}
                                                        </option>
                                                        <option value="Denied" {{ request('status')=='Denied' ? 'selected'
                                                            : '' }}>
                                                            {{ trans('form.denied') }}
                                                        </option>
                                                    </select>
                                                </td>

                                                <!-- Action Buttons -->
                                                <td width="15%" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button type="submit" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-search"></i> {{ trans('form.search') }}
                                                        </button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning">
                                                            <i class="fa fa-refresh"></i> {{ trans('form.reset') }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Data Table -->
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <table id="zero-config" class="table dt-table-hover"
                                    data-page='@include("utils.table_paginate", ["data" => $generatedVendorBills])'
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Bill ID</th>
                                            <th>Bill For</th>
                                            <th>Bill Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th class="no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($generatedVendorBills as $bill)
                                            <tr>
                                                <td>{{ ($generatedVendorBills->currentPage() - 1) * $generatedVendorBills->perPage() + $loop->iteration }}
                                                </td>
                                                <td><strong>{{ $bill->bill_id }}</strong></td>
                                                <td>
                                                    {{ $bill->billFor?->company_name ?? $bill->billFor?->title ?? '—' }}
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ class_basename($bill->bill_for_type) }}
                                                    </small>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</td>
                                                <td>৳{{ number_format($bill->amount) }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-round badge-{{ match ($bill->status) { 'Pending' => 'warning', 'approved' => 'success', 'verified' => 'info', 'denied' => 'danger', default => 'secondary'} }} badge-lg">
                                                        {{ $bill->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- View Button - Always visible -->
                                                        @if (hasPermission('account.vendor-bills.generated-vendor-bills.show'))
                                                            <a href="{{ route('account.vendor-bills.generated-vendor-bills.show', $bill->id) }}"
                                                                class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                                                title="View Bill">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Edit Button - Show for pending and denied -->
                                                        @if (hasPermission('account.vendor-bills.generated-vendor-bills.update') && in_array($bill->status, ['pending', 'denied']))
                                                            <a href="{{ route('account.vendor-bills.generated-vendor-bills.edit', $bill->id) }}"
                                                                class="btn btn-outline-warning" data-bs-toggle="tooltip"
                                                                title="Edit Bill">
                                                                <i class="far fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Deny Button - Show only for pending -->
                                                        @if (hasPermission('account.vendor-bills.generated-vendor-bills.update') && $bill->status == 'pending')
                                                            <a href="{{ route('account.vendor-bills.generated-vendor-bills.edit', $bill->id) }}?for=deny"
                                                                class="btn btn-outline-danger" data-bs-toggle="tooltip"
                                                                title="Deny Bill">
                                                                <i class="fas fa-times-circle"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Verify Button - Show only for pending -->
                                                        @if ($bill->status == 'pending' && hasPermission('account.vendor-bills.generated-vendor-bills.verify'))
                                                            <a href="{{ route('account.vendor-bills.generated-vendor-bills.edit', $bill->id) }}?for=verify"
                                                                class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                                title="Verify Bill">
                                                                <i class="fas fa-user-check"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Approve Button - Show only for verified -->
                                                        @if ($bill->status == 'verified' && hasPermission('account.vendor-bills.generated-vendor-bills.approve'))
                                                            <a href="{{ route('account.vendor-bills.generated-vendor-bills.edit', $bill->id) }}?for=approve"
                                                                class="btn btn-outline-success" data-bs-toggle="tooltip"
                                                                title="Approve Bill">
                                                                <i class="fas fa-check-circle"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Delete Button - Show only for pending or verified -->
                                                        @if (hasPermission('account.vendor-bills.generated-vendor-bills.destroy') && in_array($bill->status, ['pending', 'verified']))
                                                            <button type="button" class="btn btn-outline-danger delete-confirm"
                                                                data-action="{{ route('account.vendor-bills.generated-vendor-bills.destroy', $bill->id) }}"
                                                                data-bs-toggle="tooltip" title="Delete Bill">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Hidden Delete Form (Global) -->
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
        <!-- No inline JS needed – all handled globally in layout.app -->
    @endsection