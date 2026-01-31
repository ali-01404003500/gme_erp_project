@section('title', 'Backup/Challan List')
@section('description', 'Backup/Challan List')
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
                                        {{ trans('menu.backup-challan-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('sales.backup-challans.create'))
                                    <a href="{{ route('sales.backup-challans.create') }}" class="btn px-20 btn-primary btn-sm">
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.backup-challan-list-menu-title') }}</h4>
                </div>
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
                                                            {{ optional($value)->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="status" id="status" class="form-control tom-select"
                                                    data-placeholder="Select Status">
                                                    <option value=""></option>
                                                    <option value="pendding" {{ request('status') == 'pendding' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control flatdate" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
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

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $backupChallans])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Id</th>
                                        <th>Customer</th>
                                        <th>Customer Address</th>
                                        <th>Type</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Remaining Date</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($backupChallans as $value)
                                        <tr>
                                            <td class="text-center">{{ ($backupChallans->currentPage() - 1) * $backupChallans->perPage() + $loop->iteration  }}</td>
                                            <td>{{ $value->invoice_date }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('sales.backup-challans.show', $value->id) }}">{{ $value->invoice_id }}</a>
                                            </td>
                                            <td>{{ $value->customer->company_name }}</td>
                                            <td>{{ $value->customer->address }}</td>
                                            <td>{{ $value->type }}</td>
                                            <td>{{ $value->total_amount }}</td>
                                            <td>
                                                @if ($value->status == 'pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($value->status == 'approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($value->status == 'delivered')
                                                    <span class="badge badge-round badge-primary">Delivered</span>
                                                @elseif($value->status == 'rejected')
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @elseif ($value->status == 'Sales')
                                                    <span class="badge badge-round badge-info">Sales Order Created</span>
                                                
                                                @else
                                                    <span class="badge badge-round badge-info">{{ $value->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $value->remaining_date }}</td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('sales.backup-challans.update') && $value->status == 'pending')
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.backup-challans.edit', $value->id) }}"
                                                            title="Edit"><i class="far fa-edit"></i></a>
                                                    @endif

                                                    @if ($value->status == 'pending')
                                                        @if (hasPermission('sales.backup-challans.approve'))
                                                            <a class="btn btn-outline-success"
                                                                href="{{ route('sales.backup-challans.approve', $value->id) }}"
                                                                title="Approve"><i class="fas fa-check"></i></a>
                                                        @endif
                                                    @endif
                                                    @if(hasPermission('sales.backup-challans.save-to-sales-order') && $value->type == "Challan" && $value->status == "approved")
                                                        <a class="btn btn-outline-info" title="Save to Sales Order"
                                                            href="{{ route('sales.backup-challans.save-to-sales-order', $value->id) }}"><i
                                                                class="fas fa-file"></i>
                                                        </a>
                                                    @endif
                                                    @if(hasPermission('sales.backup-challans.send-to-delivery') && $value->type == "Backup" && $value->status == "approved")
                                                        <a class="btn btn-outline-primary" title="Send to Delivery"
                                                            href="{{ route('sales.backup-challans.send-to-delivery', $value->id) }}"><i
                                                                class="fas fa-truck"></i>
                                                        </a>
                                                    @endif
                                                    @if ($value->status == "delivered" && $value->type == "Challan")
                                                        @if (hasPermission('sales.backup-challan.sales.order'))
                                                                <a class="btn btn-outline-secondary"
                                                                    href="{{ route('sales.backup-challan.sales.order', $value->id) }}"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                        @endif
                                                    @endif
                                                    @if (hasPermission('sales.backup-challans.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('sales.backup-challans.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('sales.backup-challans.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.backup-challans.show', $value->id) }}"
                                                            title="View"><i class="fas fa-eye"></i></a>
                                                        
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

    <div class="modal fade inputForm-modal" id="recommendModal" tabindex="-1" role="dialog"
        aria-labelledby="recommendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="recommendModalLabel">
                    <h5 class="modal-title">Recommend </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="recommendForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Recomended Comments</label>
                            <div class="col-sm-12">
                                <textarea name="recommended_comments" id="recommended_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Recommend</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="approveModal" tabindex="-1" role="dialog"
        aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="approveModalLabel">
                    <h5 class="modal-title">Approve </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="approveForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Approve Comments</label>
                            <div class="col-sm-12">
                                <textarea name="approveed_comments" id="approveed_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')


@endSection
