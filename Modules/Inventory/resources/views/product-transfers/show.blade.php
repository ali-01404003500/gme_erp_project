@section('title', 'Product Transfer List')
@section('description', 'Product Transfer List')
@extends('layout.app')
@section('page-head')

    <style>
        /* Print-specific styles - hide everything except the card */
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
                                        {{ trans('menu.product-transfer-list-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center gap-1">
                                @if (hasPermission('inv.product-transfers.index'))
                                    <a href="{{ route('inv.product-transfers.index') }}"
                                        class="btn btn-sm btn-primary btn-add"><i class="las la-plus"></i>
                                        {{ trans('menu.product-transfer-list-menu-title') }}</a>
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
                                {{ trans('menu.product-transfer-list-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4" id="printable-card">
                        <div class="card-header p-3 bg-white border-bottom d-flex align-items-start gap-4">
                            <!-- SOURCE BRANCH (LEFT) -->
                            <div class="flex-grow-1 d-flex align-items-start gap-3">
                                <!-- Logo -->
                                <div class="flex-shrink-0">
                                    <img src="{{ $productTransfer->sourceBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{ $productTransfer->sourceBranch->name ?? 'Source Branch' }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>

                                <!-- Text Content -->
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">
                                        {{ $productTransfer->sourceBranch->name ?? 'Main Office' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $productTransfer->sourceBranch->title ?? '' }}
                                    </p>

                                    <!-- Contact Info Row -->
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{ $productTransfer->sourceBranch->division ?? 'Chittagong' }}</span>
                                        <span><strong>District:</strong>
                                            {{ $productTransfer->sourceBranch->district ?? 'Dhaka' }}</span>
                                        <span><strong>Police Station:</strong>
                                            {{ $productTransfer->sourceBranch->police_station ?? 'Non' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{ $productTransfer->sourceBranch->contact_no ?? '+1 (542) 428-9409' }}</span>
                                        <span><strong>Size:</strong>
                                            {{ $productTransfer->sourceBranch->size ?? '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CENTER "TO" -->
                            <div class="d-flex align-items-center justify-content-center px-3">
                                <h6 class="text-dark fw-bold m-0" style="font-size: 1rem; white-space: nowrap;">TO</h6>
                            </div>

                            <!-- DESTINATION BRANCH (RIGHT) -->
                            <div class="flex-grow-1 d-flex align-items-start gap-3">
                                <!-- Text Content -->
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">
                                        {{ $productTransfer->destinationBranch->name ?? 'Global Medical Engineering (BD) Ltd.' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{ $productTransfer->destinationBranch->title ?? '' }}
                                    </p>

                                    <!-- Contact Info Row -->
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{ $productTransfer->destinationBranch->division ?? 'Chittagong' }}</span>
                                        <span><strong>District:</strong>
                                            {{ $productTransfer->destinationBranch->district ?? 'Dhaka' }}</span>
                                        <span><strong>Police Station:</strong>
                                            {{ $productTransfer->destinationBranch->police_station ?? 'Non' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{ $productTransfer->destinationBranch->contact_no ?? '+1 (542) 428-9409' }}</span>
                                        <span><strong>Size:</strong>
                                            {{ $productTransfer->destinationBranch->size ?? '' }}</span>
                                    </div>
                                </div>

                                <!-- Logo -->
                                <div class="flex-shrink-0">
                                    <img src="{{ $productTransfer->destinationBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{ $productTransfer->destinationBranch->name ?? 'Destination Branch' }}"
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
                                        <p>{{ $productTransfer->invoice_no }}</p>
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
                                                            <th>Date</th>
                                                            <td>{{ $productTransfer->transfer_date }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Source Branch</th>
                                                            <td>{{ $productTransfer->sourceBranch->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Destination Branch</th>
                                                            <td>{{ $productTransfer->destinationBranch->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Remarks</th>
                                                            <td>{{ $productTransfer->transfer_description }}</td>
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
                                        <h3>Product Information</h3>
                                        <table class="table table-bordered" id="product_info_table">
                                            <thead>
                                                <tr>
                                                    <th>Product Type</th>
                                                    <th>Product Name</th>
                                                    <th>Unit Type</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productTransfer->productTransferDetails as $productTransferDetail)
                                                {{-- @dd($productTransferDetail->productTransferStockDetails) --}}
                                                    <tr>
                                                        <td>{{ @$productTransferDetail->productCatalog->productType->name }}</td>
                                                        <td>{{ optional($productTransferDetail->productCatalog)->name }}</td>
                                                        <td>{{ @$productTransferDetail->productCatalog->unit->name }}</td>
                                                        <td>{{ $productTransferDetail->quantity }}</td>
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
            // Trigger the print dialog
            window.print();
        }
    </script>
@endsection