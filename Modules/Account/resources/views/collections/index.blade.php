@section('title',"Collection List")
@section('description',"Collection List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.collection-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.collections.collections.create'))
                            <a href="{{ route('account.collections.collections.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.collection-list-menu-title') }}</h4>
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
                                                <select name="customer_id" id="customer_id" class="form-control tom-select"
                                                    data-placeholder="Select Customer">
                                                    <option value=""></option>
                                                    @foreach ($customers as $key => $value)
                                                        <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control" name="collection_id"
                                                    value="{{ request('collection_id') }}" autocomplete="off"
                                                    placeholder="Search by Voucher No">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control flatdaterange" name="from_to"
                                                    value="{{ request('from_to') }}" autocomplete="off"
                                                    placeholder="Search by Date">
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
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $collections])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Collection Type</th>
                                    <th>Voucher No</th>
                                    <th>Date</th>
                                    <th>Collection from</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                    <th>Prepared By</th>
                                    <th>Verified By</th>
                                    <th>Documents</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collections as $collection)
                                {{-- @dd( $collection) --}}
                                    <tr>
                                            <td class="text-center">{{ ($collections->currentPage() - 1) * $collections->perPage() + $loop->iteration  }}</td>
                                        {{-- @dd($collection) --}}
                                        <td>{{ Str::of($collection->collection_from_type)->afterLast('\\') }}</td>
                                        <td>{{ $collection->collection_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d-m-Y') }}</td>
                                        <td>
                                            <a class="text-dark fw-500"
                                                href="{{ route('account.collections.collections.show', $collection->id) }}">
                                                {{ $collection->collectionFrom->company_name ?? 'N/A' }}</i>
                                            </a>
                                        </td>
                                        <td>{{ $collection->payments->pluck('pay_mode')->unique()->join(', ') }}</td>
                                        <td>{{ number_format($collection->total_amount) }}</td>
                                        <td>{{ $collection->createdBy?->name }}</td>
                                        <td>{{ $collection->verifiedBy?->name }}</td>
                                        {{-- @dd($collection) --}}
                                        <td>
                                            @foreach($collection->payments as $payment)
                                                @if($payment->attachments)
                                                    <a href="{{ asset($payment->attachments) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="badge badge-round badge-{{ match($collection->status){
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'verified' => 'info',
                                                'denied' => 'danger',
                                                default => 'secondary',
                                            } }} badge-lg">
                                                {{ $collection->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                 <!-- Verify and Approve Buttons -->
                                                @if ($collection->status == 'pending' && hasPermission('account.collections.collections.verify'))
                                                    <a href="{{ route('account.collections.collections.edit', $collection->id) }}?for=verify"
                                                        class="btn btn-outline-info"
                                                        data-bs-toggle="tooltip"
                                                        title="Verify Bill">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                @elseif ($collection->status == 'verified' && hasPermission('account.collections.collections.approve'))
                                                    <a href="{{ route('account.collections.collections.edit', $collection->id) }}?for=approve"
                                                        class="btn btn-outline-info"
                                                        data-bs-toggle="tooltip"
                                                        title="Approve Bill">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('account.collections.collections.update') && $collection->status != 'approved')
                                                    <a href="{{ route('account.collections.collections.edit', $collection->id) }}"
                                                        class="btn btn-outline-warning"
                                                        data-bs-toggle="tooltip"
                                                        title="Edit Bill">
                                                        <i class="las la-edit"></i>
                                                    </a>
                                                @endif
                                                <!-- Show Button -->
                                                @if (hasPermission('account.collections.collections.show'))
                                                    <a href="{{ route('account.collections.collections.show', $collection->id) }}"
                                                        class="btn btn-outline-primary"
                                                        data-bs-toggle="tooltip"
                                                        title="View Bill">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                @endif

                                                <!-- Delete Button -->
                                                @if (hasPermission('account.collections.collections.destroy') && ($collection->status == 'pending' || $collection->status == 'verified'))
                                                    <button type="button"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            data-action="{{ route('account.collections.collections.destroy', $collection->id) }}"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Bill">
                                                        <i class="las la-trash"></i>
                                                    </button>
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