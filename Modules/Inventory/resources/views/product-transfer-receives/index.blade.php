@section('title', 'Product Transfer Receives')
@section('description', 'Product Transfer Receives')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
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
                                        {{ trans('menu.product-transfer-receives-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('inv.product-transfer-receives.create'))
                                    <a class="btn btn-xs btn-primary btn-sm" href="{{ route('inv.product-transfer-receives.create') }}">
                                        Add New
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
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-transfer-receives-list-menu-title') }}</h4>

                        </div>
                    </div>
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
                                                    <select name="source_warehouse_id" id="source_warehouse_id" class="form-control tom-select"
                                                        data-placeholder="Select Source Branch">
                                                        <option value=""></option>
                                                        @foreach ($productTransferReceives as $productTransferReceive)
                                                            <option {{ request('source_warehouse_id') == $productTransferReceive->source_warehouse_id ? 'selected' : '' }}
                                                                value="{{ $productTransferReceive->source_warehouse_id }}">
                                                                {{ $productTransferReceive->sourceBranch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <select name="destination_warehouse_id" id="destination_warehouse_id" class="form-control tom-select"
                                                        data-placeholder="Select Destination Branch">
                                                        <option value=""></option>
                                                        @foreach ($productTransferReceives as $productTransferReceive)
                                                            <option {{ request('destination_warehouse_id') == $productTransferReceive->destination_warehouse_id ? 'selected' : '' }}
                                                                value="{{ $productTransferReceive->destination_warehouse_id }}">
                                                                {{ $productTransferReceive->destinationBranch->name }}</option>
                                                        @endforeach
                                                    </select>
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
                            <style>
                                .product-transfer-receives-custom,
                                .product-transfer-receives-custom th,
                                .product-transfer-receives-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .product-transfer-receives-custom th,
                                .product-transfer-receives-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .product-transfer-receives-custom thead th {
                                    background-color: #f8f9fa;
                                    border-bottom-width: 2px !important;
                                }

                                .table thead th {
                                    background-color: #35526e !important;
                                    color: #ffffff !important;
                                    font-weight: 600 !important;
                                    text-transform: uppercase;
                                    font-size: 0.85rem !important;
                                    letter-spacing: 0.08em;
                                    border-bottom: 2px solid #2a4054 !important;
                                    padding: 14px 16px !important;
                                    vertical-align: middle;
                                    text-align: center;
                                }
                            </style>
                            <table id="zero-config" class="table product-transfer-receives-custom dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $productTransferReceives])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Invoice No</th>
                                        <th class="text-center">Source branch</th>
                                        <th class="text-center">Destination branch</th>
                                        <th class="text-center">Product Quantity</th>
                                        <th class="text-center">Receive Date</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($productTransferReceives as $key => $productTransferReceive)
                                        <tr>
                                            <td class="text-center">{{ ($productTransferReceives->currentPage() - 1) * $productTransferReceives->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('inv.product-transfer-receives.show', $productTransferReceive->id) }}">{{ $productTransferReceive->invoice_no }}</a>
                                            </td>
                                            <td class="text-center">{{ optional($productTransferReceive->sourceBranch)->name }}</td>
                                            <td class="text-center">{{ optional($productTransferReceive->destinationBranch)->name }}</td>
                                            <td class="text-center">{{ $productTransferReceive->productTransferReceiveDetails->sum('received_quantity') }} </td>
                                            <td class="text-center">{{ $productTransferReceive->receive_date }}</td>
                                            <td class="text-center">
                                                @if ($productTransferReceive->status == "approved")
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif ($productTransferReceive->status == "pending")
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif ($productTransferReceive->status == "rejected")
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-round badge-info">{{ $productTransferReceive->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('inv.product-transfer-receives.update') && $productTransferReceive->status == "pending")
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('inv.product-transfer-receives.edit', $productTransferReceive->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('inv.product-transfer-receives.approve') && $productTransferReceive->status == "pending")
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('inv.product-transfer-receives.approve', $productTransferReceive->id) }}"><i
                                                                class="fa fa-check"></i></a>
                                                    @endif

                                                    @if (hasPermission('inv.product-transfer-receives.destroy') && $productTransferReceive->status == "pending")
                                                        <button type="button"
                                                            data-action="{{ route('inv.product-transfer-receives.destroy', $productTransferReceive->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('inv.product-transfer-receives.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('inv.product-transfer-receives.show', $productTransferReceive->id) }}"><i
                                                                class="fas fa-eye"></i></a>
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

                <!-- Create Modal -->

            </div>
        </div>
    </div>

    <!-- Edit Modal -->

    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                console.log($(this).data('name'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>
@endsection
