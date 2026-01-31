@extends('layout.app')
@section('title', 'Invoice Wise Collections')

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
                                        {{ trans('menu.collection-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('account.collections.invoice-wise-collections.create'))
                                    <a href="{{ route('account.collections.invoice-wise-collections.create') }}"
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
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.collection-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $collections])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Collection ID</th>
                                        <th>Customer Name</th>
                                        <th>Collection Type</th>
                                        <th class="text-right">Total Amount</th>
                                        <th>Status</th>
                                        <th>Prepared By</th>
                                        <th>Verified By</th>
                                        {{-- <th>Approved By</th> --}}
                                        <th>Documents</th>
                                        <th>Date</th>
                                        <th class="text-center no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($collections as $collection)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($collections->currentPage() - 1) * $collections->perPage() + $loop->iteration  }}
                                            </td>
                                            <td>{{ $collection->invoice_wise_collection_id }}</td>
                                            <td>
                                                <a href="{{ route('account.collections.invoice-wise-collections.show', $collection->id) }}" >
                                                    {{ optional($collection->customer)->company_name }}
                                                </a>
                                            </td>
                                            <td>{{ $collection->payments->pluck('pay_mode')->unique()->join(', ') }}</td>
                                            <td class="text-right">{{ number_format($collection->total_amount) }}</td>
                                            <td>
                                                @if ($collection->status == 'pending')
                                                    <span class="badge badge-round text-xs badge-warning">Pending</span>
                                                @elseif($collection->status == 'verified')
                                                    <span class="badge badge-round text-xs badge-primary">Verified</span>
                                                @elseif($collection->status == 'approved')
                                                    <span class="badge badge-round text-xs badge-success">Approved</span>
                                                @elseif($collection->status == 'denied')
                                                    <span class="badge badge-round text-xs badge-danger">denied</span>
                                                @endif
                                            </td>
                                            <td>{{ optional($collection->createdBy)->name ?? '' }}</td>
                                            <td>{{ optional($collection->verifiedBy)->name ?? '' }}</td>
                                            {{-- <td>{{ optional($collection->approvedBy)->name ?? '' }}</td> --}}
                                            <td>
                                                @foreach($collection->payments as $payment)
                                                    @if($payment->attachments)
                                                        <a href="{{ asset($payment->attachments) }}" target="_blank" class="text-info"><i class="las la-paperclip"></i> View</a><br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $collection->created_at->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    <!-- Verify and Approve Buttons -->
                                                    @if ($collection->status == 'pending' && hasPermission('account.collections.invoice-wise-collections.verify'))
                                                        <a href="{{ route('account.collections.invoice-wise-collections.edit', $collection->id) }}?for=verify"
                                                            class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                            title="Verify Bill">
                                                            <i class="fas fa-user-check"></i>
                                                        </a>
                                                    @elseif ($collection->status == 'verified' && hasPermission('account.collections.invoice-wise-collections.approve'))
                                                        <a href="{{ route('account.collections.invoice-wise-collections.edit', $collection->id) }}?for=approve"
                                                            class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                            title="Approve Bill">
                                                            <i class="fas fa-check-circle"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('account.collections.invoice-wise-collections.update') && $collection->status == 'pending')
                                                        <a href="{{ route('account.collections.invoice-wise-collections.edit', $collection->id) }}"
                                                            class="btn btn-outline-warning" data-bs-toggle="tooltip"
                                                            title="Edit Bill">
                                                            <i class="las la-edit"></i>
                                                        </a>
                                                    @endif
                                                    <!-- Show Button -->
                                                    @if (hasPermission('account.collections.invoice-wise-collections.show'))
                                                        <a href="{{ route('account.collections.invoice-wise-collections.show', $collection->id) }}"
                                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                                            title="View Bill">
                                                            <i class="las la-eye"></i>
                                                        </a>
                                                    @endif

                                                    <!-- Delete Button -->
                                                    @if (hasPermission('account.collections.invoice-wise-collections.destroy') && ($collection->status == 'pending' || $collection->status == 'verified'))
                                                        <button type="button" class="btn btn-outline-danger delete-confirm"
                                                            data-action="{{ route('account.collections.invoice-wise-collections.destroy', $collection->id) }}"
                                                            data-bs-toggle="tooltip" title="Delete Bill">
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

@push('script')
    <script>
        // Add any specific JavaScript for this page here
    </script>
@endpush