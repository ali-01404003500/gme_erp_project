@section('title', 'Vendor Bill Settings')
@section('description', 'Manage recurring vendor bill settings')

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
                                        {{ trans('menu.vendor-bill-settings') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">

                                <!-- Create Button -->
                                @if (hasPermission('account.vendor-bills.settings.create'))
                                    <a href="{{ route('account.vendor-bills.settings.create') }}"
                                        class="btn btn-primary btn-sm ml-5" style="margin-left: 5px;">
                                        <i class="las la-plus fs-16"></i> {{ trans('menu.create-vendor-bill-settings') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Page Title -->
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.vendor-bill-settings') }}</h4>
                </div>

                <!-- Data Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                data-page='@include("utils.table_paginate", ["data" => $vendorBillSettings])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Bill Settings Title</th>
                                        <th>Bill For</th>
                                        <th>Bill Amount</th>
                                        <th>Schedule</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendorBillSettings as $setting)
                                        <tr>
                                            <td>{{ ($vendorBillSettings->currentPage() - 1) * $vendorBillSettings->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $setting->title }}</td>
                                            <td>
                                                {{ $setting->billFor?->company_name ?? $setting->billFor?->title ?? '—' }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ ucfirst(str_replace('_', ' ', $setting->holder_type)) }}
                                                </small>
                                            </td>
                                            <td>৳{{ number_format($setting->amount) }}</td>
                                            <td>
                                                {{ $setting->schedule_type }}
                                                @if($setting->schedule_type !== 'Static')
                                                    (Every {{ $setting->schedule_value }})
                                                @endif
                                            </td>
                                            <td>{{ $setting->start_date}}</td>
                                            <td>
                                                <span
                                                    class="badge badge-round badge-{{ match ($setting->status) { 'Running' => 'success', 'Stop' => 'secondary', 'running' => 'success', 'stop' => 'secondary'} }} badge-lg">
                                                    {{ $setting->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if (hasPermission('account.vendor-bills.settings.show'))
                                                        <a href="{{ route('account.vendor-bills.settings.show', $setting) }}"
                                                            class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                            title="{{ trans('view') }}">
                                                            <i class="las la-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('account.vendor-bills.settings.update'))
                                                        <a href="{{ route('account.vendor-bills.settings.edit', $setting) }}"
                                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                                            title="{{ trans('edit') }}">
                                                            <i class="las la-pen"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('account.vendor-bills.settings.destroy'))
                                                        <button type="button" class="btn btn-outline-danger delete-confirm"
                                                            data-action="{{ route('account.vendor-bills.settings.destroy', $setting->id) }}"
                                                            data-bs-toggle="tooltip" title="{{ trans('delete') }}">
                                                            <i class="las la-trash"></i>
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