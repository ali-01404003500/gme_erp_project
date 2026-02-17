
@extends('layout.app')

@section('title', 'Cash Transfers')
@section('description', 'Manage Cash Transfers')
@section('page-header')
    <i class="fa fa-exchange-alt"></i> Cash Transfers
@stop

@section('content')
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cash Transfers</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.cash-transfers.create'))
                                <a href="{{ route('account.cash-transfers.create') }}" class="btn btn-primary btn-sm d-inline-block mr-2">
                                    <i class="las la-plus"></i> Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $cashTransfers])' style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">From</th>
                                    <th class="text-center">To</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cashTransfers as $key => $transfer)
                                    <tr>
                                        <td class="text-center">{{ ($cashTransfers->currentPage() - 1) * $cashTransfers->perPage() + $loop->iteration }}</td>
                                        <td class="text-center">{{ $transfer->transfer_date }}</td>
                                        <td class="text-center">{{ $transfer->fromEmployee->full_name ?? '' }}</td>
                                        <td class="text-center">{{ $transfer->toEmployee->full_name ?? '' }}</td>
                                        <td class="text-center">{{ number_format($transfer->amount, 2) }}</td>
                                        <td class="text-center">
                                            @if($transfer->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($transfer->status == 'confirmed')
                                                <span class="badge badge-success">Confirmed</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($transfer->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                <!-- Confirm Button -->
                                                @if ($transfer->status == 'pending' && hasPermission('account.cash-transfers.confirm'))
                                                    <a href="{{ route('account.cash-transfers.edit', $transfer->id) }}?for=confirm"
                                                        class="btn btn-outline-info"
                                                        data-bs-toggle="tooltip"
                                                        title="Confirm Transfer">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endif

                                                <!-- Edit Button -->
                                                @if ($transfer->status == 'pending' && hasPermission('account.cash-transfers.edit'))
                                                    <a href="{{ route('account.cash-transfers.edit', $transfer->id) }}"
                                                        class="btn btn-outline-warning"
                                                        data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="las la-edit"></i>
                                                    </a>
                                                @endif

                                                <!-- Show Button -->
                                                @if (hasPermission('account.cash-transfers.show'))
                                                    <a href="{{ route('account.cash-transfers.show', $transfer->id) }}"
                                                        class="btn btn-outline-primary"
                                                        data-bs-toggle="tooltip"
                                                        title="View">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                @endif

                                                <!-- Delete Button -->
                                                @if ($transfer->status == 'pending' && hasPermission('account.cash-transfers.destroy'))
                                                    <button type="button"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            data-action="{{ route('account.cash-transfers.destroy', $transfer->id) }}"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete">
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
@endsection
