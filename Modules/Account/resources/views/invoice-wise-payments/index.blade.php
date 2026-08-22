@section('title', 'Invoice Wise Payment List')
@section('description', 'Invoice Wise Payment List')
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
                                        {{ trans('Invoice Wise Payments List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('account.payments.invoice-wise-payments.create'))
                                    <a href="{{ route('account.payments.invoice-wise-payments.create') }}"
                                        class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                                {{-- <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Invoice Wise Payments List') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $payments])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Payment ID</th>
                                        <th>Payment To</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Total Invoices</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Prepared By</th>
                                        <th>Verified By</th>
                                        <th>Approved By</th>
                                        <th>Documents</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 
                                    @foreach ($payments as $payment)   
                                        <tr>
                                            <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $payment->invoice_wise_payment_id }}</td>
                                            <td>{{ @$payment->paymentTo->company_name }}</td>
                                            <td>
                                                @if ($payment->payment_to_type === 'Modules\Purchase\Models\Supplier')
                                                    <span class="badge badge-round badge-info">Supplier</span>
                                                @else
                                                    <span class="badge badge-round badge-warning">Vendor</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $payment->invoices->count() }}</td>
                                            <td>৳ {{ number_format($payment->total_amount) }}</td>
                                            <td>
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <span class="badge badge-round badge-warning badge-lg">Pending</span>
                                                    @break

                                                    @case('verified')
                                                        <span class="badge badge-round badge-info badge-lg">Verified</span>
                                                    @break

                                                    @case('approved')
                                                        <span class="badge badge-round badge-success badge-lg">Approved</span>
                                                    @break

                                                    @case('denied')
                                                        <span class="badge badge-round badge-danger badge-lg">Denied</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>{{ $payment->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $payment->verifiedBy->name ?? 'N/A' }}</td>
                                            <td>{{ $payment->approvedBy->name ?? 'N/A' }}</td>
                                            <td>
                                        
                                              
                                                @foreach($payment->payments as $detail)
                                                    @if(!empty($detail->attachments))
                                                        @php
                                                            $files = $detail->attachments;

                                                            // First decode
                                                            if (is_string($files)) {
                                                                $files = json_decode($files, true);
                                                            }

                                                            // Double encoded হলে second decode
                                                            if (is_string($files)) {
                                                                $files = json_decode($files, true);
                                                            }

                                                            // Single file হলে array বানানো
                                                            if (!is_array($files)) {
                                                                $files = [$files];
                                                            }
                                                        @endphp

                                                        @foreach($files as $file)
                                                            @if(!empty($file))
                                                                <a href="{{ url($file) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-info mb-1">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach

 
                                            </td>
                                            <td>
                                                
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if (
                                                        !in_array($payment->status, ['approved', 'denied']) &&
                                                            hasPermission('account.payments.invoice-wise-payments.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('account.payments.invoice-wise-payments.edit', $payment->id) }}">
                                                            <i class="las la-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if ($payment->status == 'pending' && hasPermission('account.payments.invoice-wise-payments.verify'))
                                                        <a class="btn btn-outline-info"
                                                            href="{{ route('account.payments.invoice-wise-payments.edit', $payment->id) }}?form_type=verify">
                                                            <i class="fas fa-user-check"></i>
                                                        </a>
                                                    @endif

                                                    @if ($payment->status == 'verified' && hasPermission('account.payments.invoice-wise-payments.approve'))
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('account.payments.invoice-wise-payments.edit', $payment->id) }}?form_type=approve">
                                                            <i class="fas fa-check-circle"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('account.payments.invoice-wise-payments.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('account.payments.invoice-wise-payments.show', $payment->id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (
                                                        !in_array($payment->status, ['approved', 'denied']) &&
                                                            hasPermission('account.payments.invoice-wise-payments.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('account.payments.invoice-wise-payments.destroy', $payment->id) }}"
                                                            class="btn btn-outline-danger delete-confirm">
                                                            <i class="far fa-trash-alt"></i>
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
