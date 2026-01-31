@section('title', 'View Product Transfer Request')
@section('description', 'View Product Transfer Request')
@extends('layout.app')
@section('page-head')
    <style>
        /* Print-specific styles - hide everything except the card */
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-card, #printable-card * {
                visibility: visible;
            }
            #printable-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
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
                                        View Product Transfer Request</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center gap-1">
                                @if(hasPermission('inv.product-transfer-requests.index'))
                                    <a href="{{ route('inv.product-transfer-requests.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                @endif
                                <button type="button" class="btn btn-success btn-sm" onclick="printCard()">
                                    <i class="las la-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .my-header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }

                
                .header-img {
                    margin-top: 0;
                    max-width: 100px;
                    margin-left: 20px;
                }

                .my-header h1 {
                    margin: 0;
                    font-size: 50px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .my-header p {
                    margin: 5px 0;
                    font-size: 16px;
                }

                .title {
                    text-align: center;
                    margin-bottom: 20px!important;
                }

                .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .sales-order-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .sales-order-info .left,
                .sales-order-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .sales-order-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .sales-order-info th,
                .sales-order-info td {
                    padding: 5px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details {
                    margin-bottom: 20px;
                }

                .invoice-details table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                .invoice-details .totals {
                    text-align: right;
                }

                .invoice-details .totals p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }

                footer p {
                    margin: 10px 0;
                    font-size: 14px;
                    width: 45%;
                    text-align: center;
                }
            </style>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">View Product Transfer Request</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4" id="printable-card">
                        <div class="card-header p-3 bg-white border-bottom d-flex align-items-start gap-4">
                            <!-- SOURCE BRANCH (LEFT) -->
                            <div class="flex-grow-1 d-flex align-items-start gap-3">
                                <!-- Logo -->
                                <div class="flex-shrink-0">
                                    <img src="{{  $productTransferRequest->sourceBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{  $productTransferRequest->sourceBranch->name ?? 'Source Branch' }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>

                                <!-- Text Content -->
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">
                                        {{  $productTransferRequest->sourceBranch->name ?? 'Main Office' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{  $productTransferRequest->sourceBranch->title ?? '' }}
                                    </p>

                                    <!-- Contact Info Row -->
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{  $productTransferRequest->sourceBranch->division ?? 'Chittagong' }}</span>
                                        <span><strong>District:</strong>
                                            {{  $productTransferRequest->sourceBranch->district ?? 'Dhaka' }}</span>
                                        <span><strong>Police Station:</strong>
                                            {{  $productTransferRequest->sourceBranch->police_station ?? 'Non' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{  $productTransferRequest->sourceBranch->contact_no ?? '+1 (542) 428-9409' }}</span>
                                        <span><strong>Size:</strong>
                                            {{  $productTransferRequest->sourceBranch->size ?? '' }}</span>
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
                                        {{  $productTransferRequest->destinationBranch->name ?? 'Global Medical Engineering (BD) Ltd.' }}
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        {{  $productTransferRequest->destinationBranch->title ?? '' }} </p>

                                    <!-- Contact Info Row -->
                                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.75rem;">
                                        <span><strong>Division:</strong>
                                            {{  $productTransferRequest->destinationBranch->division ?? 'Chittagong' }}</span>
                                        <span><strong>District:</strong>
                                            {{  $productTransferRequest->destinationBranch->district ?? 'Dhaka' }}</span>
                                        <span><strong>Police Station:</strong>
                                            {{  $productTransferRequest->destinationBranch->police_station ?? 'Non' }}</span>
                                        <span><strong>Contact:</strong>
                                            {{  $productTransferRequest->destinationBranch->contact_no ?? '+1 (542) 428-9409' }}</span>
                                        <span><strong>Size:</strong>
                                            {{  $productTransferRequest->destinationBranch->size ?? '' }}</span>
                                    </div>
                                </div>

                                <!-- Logo -->
                                <div class="flex-shrink-0">
                                    <img src="{{  $productTransferRequest->destinationBranch->image ?? asset('images/default-logo.png') }}"
                                        alt="{{  $productTransferRequest->destinationBranch->name ?? 'Destination Branch' }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <section class="title" style="margin-top: 40px;">
                                <h2>Product Transfer Request Invoice</h2>
                            </section>

                            <section class="sales-order-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Request date</th>
                                            <td>:</td>
                                            <td>{{$productTransferRequest->request_date}}</td>
                                        </tr>
                                        <tr>
                                            <th>Source Branch</th>
                                            <td>:</td>
                                            <td>{{ $productTransferRequest->sourceBranch->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Destination Branch</th>
                                            <td>:</td>
                                            <td>{{ $productTransferRequest->destinationBranch->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>:</td>
                                            <td>{{ $productTransferRequest->remarks }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productTransferRequest->productTransferRequestDetails as $productTransferRequestDetail)
                                        <tr>
                                            <td>{{ $productTransferRequestDetail->productCatalog->name }}</td>
                                            <td>{{ $productTransferRequestDetail->quantity }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </section>

                            <footer style="margin-top: 100px">
                                <p>Received ___________________________</p>
                                <p>Authorized ___________________________</p>
                            </footer>
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