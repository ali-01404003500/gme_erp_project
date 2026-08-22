@section('title', 'Payment List')
@section('description', 'Payment List')
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
                                        {{ trans('Payments List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('account.payments.make-payments.create'))
                                    <a href="{{ route('account.payments.make-payments.create') }}"
                                        class="btn px-20 btn-primary btn-sm">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Payments List') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr> 
                                        <td class="text-center" width="20%">
                                            <input type="text" class="form-control flatdaterange" name="from_to" value="{{ request('from_to') }}"
                                                autocomplete="off" placeholder="Search by Date">
                                        </td>
                                        {{-- <td class="text-center" width="20%">
                                            <input type="text" class="form-control" name="amount" value="{{ request('amount') }}"
                                                autocomplete="off" placeholder="Search by Amount">
                                        </td> --}}
                                        <td colspan="5" class="text-right" width="30%">
                                            <div class="btn-group btn-corner">
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
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
                    <div class="card mb-4">
                        <div class="card-body">
                            {{-- @dd($makePayments); --}}
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $makePayments])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Payment Type</th>
                                        <th>Payment To</th>  
                                        <th>Amount</th>
                                        <th>Attachments</th>
                                        <th>Prepared By</th>
                                        <th>Verified By</th>
                                        <th>Approved By</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($makePayments as $payment)
                                        <tr>
                                            {{-- Serial number --}}
                                            <td>{{ ($makePayments->currentPage() - 1) * $makePayments->perPage() + $loop->iteration }} </td>

                                            {{-- Date --}}
                                            <td>{{ $payment->date }}</td>

                                            {{-- Payment Type --}}
                                            <td>
                                                @php
                                                    $paymentTypeLabel = match ($payment->payment_to_type) {
                                                        "Modules\Purchase\Models\Supplier", "Modules\Account\Models\Supplier" => 'Supplier',
                                                        "Modules\Purchase\Models\Vendor" => 'Vendor',
                                                        "Modules\CRM\Models\Customer\Broker" => 'Broker',
                                                        "Modules\Account\Models\Account" => 'Petty Cash Expense',
                                                        default => ucfirst(str_replace(['Modules\\Account\\Models\\', 'Modules\\Purchase\\Models\\', 'Modules\\CRM\\Models\\Customer\\'], '', $payment->payment_to_type))
                                                    }
                                                @endphp
                                                {{ $paymentTypeLabel }}
                                            </td>
                                           
                                            {{-- Payment To (clickable) --}}
                                            <td>
                                                <a class="text-dark fw-500"
                                                    href="{{ route('account.payments.make-payments.show', $payment->id) }}">
                                                    {{ $payment->paymentTo->company_name??$payment->paymentTo->broker_name?? $payment->paymentTo->name }}
                                                   
                                                </a>
                                            </td>

                                            {{-- Amount --}}
                                            <td>{{ number_format($payment->amount) }}</td>

                                            {{-- Document/Images --}}
                                            <td>
                                                {{-- @foreach($payment->paymentDetails as $detail)
                                                    @if($detail->attachments)
                                                        <a href="{{ url($detail->attachments) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                                    @endif
                                                @endforeach --}}
                                                @foreach($payment->paymentDetails as $detail)
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


                                            {{-- Prepared By --}}
                                            <td>{{ $payment->createdBy?->name ?? 'N/A' }}</td>

                                            {{-- Verified By --}}
                                            <td>
                                                @if($payment->status != 'pending' && $payment->verifiedBy)
                                                    {{ $payment->verifiedBy->name }}
                                                @endif
                                            </td>

                                            {{-- Approved By --}}
                                            <td>
                                                @if($payment->status == 'approved' && $payment->approvedBy)
                                                    {{ $payment->approvedBy->name }}
                                                @endif
                                            </td>
 

                                            {{-- Status --}}
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

                                            {{-- Actions --}}
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if ($payment->status == 'pending' && hasPermission('account.payments.make-payments.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('account.payments.make-payments.edit', $payment->id) }}">
                                                            <i class="las la-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if ($payment->status == 'pending' && hasPermission('account.payments.make-payments.verify'))
                                                        <a class="btn btn-outline-info" title="Verify"
                                                            href="{{ route('account.payments.make-payments.edit', $payment->id) }}?form_type=verify">
                                                            <i class="fas fa-user-check"></i>
                                                        </a>
                                                    @endif

                                                    @if ($payment->status == 'verified' && hasPermission('account.payments.make-payments.approve'))
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('account.payments.make-payments.edit', $payment->id) }}?form_type=approve">
                                                            <i class="fas fa-check-circle"></i>
                                                        </a>
                                                    @endif

 
                                                    @if (hasPermission('account.payments.make-payments.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('account.payments.make-payments.show', $payment->id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if ($payment->status == 'pending' && hasPermission('account.payments.make-payments.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('account.payments.make-payments.destroy', $payment->id) }}"
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
