@section('title', 'Product Transfer Receive Details')
@section('description', 'Product Transfer Receive Details')
@extends('layout.app')
@section('page-head')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-card,
            #printable-card * {
                visibility: visible;
            }
            #printable-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0 !important;
                padding: 0px !important;
                box-shadow: none !important;
                border: none !important;
            }
            .breadcrumb-title {
                display: none !important;
            }
        }
    </style>
@endsection
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
                                        {{ trans('menu.product-transfer-receives-list-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center gap-1">
                                @if (hasPermission('inv.product-transfer-receives.index'))
                                    <a href="{{ route('inv.product-transfer-receives.index') }}"
                                        class="btn btn-sm btn-primary btn-add"><i class="las la-plus"></i>
                                        {{ trans('menu.product-transfer-receives-list-menu-title') }}</a>
                                @endif
                                <button type="button" class="btn btn-sm btn-success btn-print" onclick="printCard()">
                                    <i class="las la-print"></i> Print Card
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">
                                {{ trans('menu.product-transfer-receives-list-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4" id="printable-card">
                        <div class="card-header p-3 bg-white border-bottom d-flex align-items-start gap-4">
                            <div class="flex-grow-1 d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ $productTransferReceive->sourceBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{ $productTransferReceive->sourceBranch->name ?? 'Source Branch' }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">
                                        {{ $productTransferReceive->sourceBranch->name ?? 'Source Branch' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $productTransferReceive->sourceBranch->title ?? '' }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{ $productTransferReceive->sourceBranch->division ?? 'N/A' }}</span>
                                        <span><strong>District:</strong>
                                            {{ $productTransferReceive->sourceBranch->district ?? 'N/A' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{ $productTransferReceive->sourceBranch->contact_no ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center px-3">
                                <h6 class="text-dark fw-bold m-0" style="font-size: 1rem; white-space: nowrap;">TO</h6>
                            </div>

                            <div class="flex-grow-1 d-flex align-items-start gap-3">
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">
                                        {{ $productTransferReceive->destinationBranch->name ?? 'Destination Branch' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $productTransferReceive->destinationBranch->title ?? '' }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{ $productTransferReceive->destinationBranch->division ?? 'N/A' }}</span>
                                        <span><strong>District:</strong>
                                            {{ $productTransferReceive->destinationBranch->district ?? 'N/A' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{ $productTransferReceive->destinationBranch->contact_no ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <img src="{{ $productTransferReceive->destinationBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{ $productTransferReceive->destinationBranch->name ?? 'Destination Branch' }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <div style="padding: 20px;">
                                        <h6>Invoice No</h6>
                                        <p>{{ $productTransferReceive->invoice_no }}</p>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="card-body p-0">
                                        <div class="ap-product">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Receive Date</th>
                                                            <td>{{ $productTransferReceive->receive_date }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Source Branch</th>
                                                            <td>{{ $productTransferReceive->sourceBranch->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Destination Branch</th>
                                                            <td>{{ $productTransferReceive->destinationBranch->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <td>{{ $productTransferReceive->receive_description }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td>
                                                                @if ($productTransferReceive->status == "approved")
                                                                    <span class="badge badge-round badge-success">Approved</span>
                                                                @elseif ($productTransferReceive->status == "pending")
                                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                                @elseif ($productTransferReceive->status == "rejected")
                                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h3>Received Product Information</h3>
                                        <table class="table table-bordered" id="product_info_table">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Ordered Quantity</th>
                                                    <th>Received Quantity</th>
                                                    <th>Lot/Serial Info</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productTransferReceive->productTransferReceiveDetails as $detail)
                                                    <tr>
                                                        <td>{{ optional($detail->productCatalog)->name }}</td>
                                                        <td>{{ $detail->quantity }}</td>
                                                        <td>{{ $detail->received_quantity }}</td>
                                                        <td>
                                                            @foreach($detail->productTransferReceiveStockDetails as $stockDetail)
                                                                @if($stockDetail->lot_no)
                                                                    <span class="badge badge-info">Lot: {{ $stockDetail->lot_no }} ({{ $stockDetail->quantity }})</span>
                                                                @endif
                                                                @if($stockDetail->serial_no)
                                                                    <span class="badge badge-info">Serial: {{ $stockDetail->serial_no }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        function printCard() {
            window.print();
        }
    </script>
@endsection
