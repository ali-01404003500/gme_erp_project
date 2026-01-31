@section('title', 'View Vendor Bill Settings')
@section('description', 'View details of vendor bill settings')

@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.view-vendor-bill-settings') }}</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('account.vendor-bills.settings.index') }}">{{ trans('menu.vendor-bill-settings') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('menu.view-vendor-bill-settings') }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Vendor Bill Setting Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Title -->
                            <div class="col-md-6">
                                <label class="fw-bold">Bill Settings Title</label>
                                <p>{{ $vendorBillSetting->title }}</p>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <label class="fw-bold">Bill Amount (Tk)</label>
                                <p>৳{{ number_format($vendorBillSetting->amount) }}</p>
                            </div>

                            <!-- Holder Type -->
                            <div class="col-md-6">
                                <label class="fw-bold">Bill Holder Type</label>
                                <p>
                                    @switch($vendorBillSetting->holder_type)
                                        @case('vendor') Vendor Account @break
                                        @case('employee') Employee Account @break
                                        @case('client') Client Account @break
                                        @case('others') Others Account @break
                                        @default N/A
                                    @endswitch
                                </p>
                            </div>

                            <!-- Bill For -->
                            <div class="col-md-6">
                                <label class="fw-bold">Bill For</label>
                                <p>{{ $vendorBillSetting->billFor?->company_name ?? '—' }}</p>
                            </div>

                            <!-- Bill Type -->
                            <div class="col-md-6">
                                <label class="fw-bold">Bill Type</label>
                                <p>{{ $vendorBillSetting->bill_type }}</p>
                            </div>

                            <!-- Schedule Type -->
                            <div class="col-md-6">
                                <label class="fw-bold">Schedule Type</label>
                                <p>{{ $vendorBillSetting->schedule_type }}</p>
                            </div>

                            <!-- Schedule Value -->
                            <div class="col-md-6">
                                <label class="fw-bold">Schedule Value</label>
                                <p>
                                    @if($vendorBillSetting->schedule_type === 'Daily')
                                        Every {{ $vendorBillSetting->schedule_value }} day(s)
                                    @elseif($vendorBillSetting->schedule_type === 'Monthly')
                                        Every {{ $vendorBillSetting->schedule_value }} month(s)
                                    @elseif($vendorBillSetting->schedule_type === 'Yearly')
                                        Every {{ $vendorBillSetting->schedule_value }} year(s)
                                    @else
                                        One-Time (Static)
                                    @endif
                                </p>
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-6">
                                <label class="fw-bold">Schedule Start From</label>
                                <p>{{ $vendorBillSetting->start_date}}</p>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="fw-bold">Setting Status</label>
                                <p>
                                    <span class="badge badge-round bg-{{ $vendorBillSetting->status === 'Running' ? 'success' : 'secondary' }}">
                                        {{ $vendorBillSetting->status }}
                                    </span>
                                </p>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label class="fw-bold">Remarks</label>
                                <p>{{ $vendorBillSetting->remarks ?: '—' }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('account.vendor-bills.settings.index') }}" class="btn btn-outline-secondary">Back to List</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<!-- No JS needed for show page -->
@endsection