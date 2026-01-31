@section('title', 'Collection Receipt Detail')
@section('description', 'Collection Receipt Detail')
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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('account.collections.collections.index') }}">Collections</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Collection Receipt Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 80px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    margin-bottom: 20px;
                }

                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 40px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
                    margin: 5px 0;
                    font-size: 20px;
                }

                .title {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .requisition-info th,
                .requisition-info td {
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
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    font-size: 14px;
                    text-align: left;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 40px;
                }

                .signature-display {
                    height: 80px;
                    border: 1px solid #ddd;
                    margin-top: 10px;
                    background-color: white;
                }

                .signature-placeholder {
                    color: #999;
                    font-style: italic;
                }

                .signature-timestamp {
                    font-size: 12px;
                    color: #666;
                    text-align: center;
                    margin-top: 5px;
                }

                .qr-code-section {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                }

                .action-buttons {
                    display: flex;
                    gap: 10px;
                }

                .remarks-toggle {
                    position: absolute;
                    top: 20px;
                    left: 20px;
                }

                @media print {
                    .no-print {
                        display: none !important;
                    }
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">Collection Receipt Detail</h3>
                    
                    <div class="action-buttons no-print">
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}" target="_blank"
                            class="btn btn-primary btn-sm">
                            <i class="fa fa-file-excel"></i> Excel
                        </a>
                    </div>

                    <!-- QR Code -->
                    
                </div>

                <div class="col-md-12 print-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="header">
                                <img src="{{ $company_info->company_logo }}" alt="Company Logo">
                                <div>
                                    <h1>{{ $company_info->company_name }}</h1>
                                    <p>{{ $company_info->company_bio }}</p>
                                    <p>{{ $company_info->company_address }}</p>
                                    <p>Hotline : {{ $company_info->company_phone }}</p>
                                    <p>e-mail : {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                                    <h2 class="title">Money Receipt</h2>
                                </div>
                                <div class="qr-code-section no-print">
                        <div id="qrcode"></div>
                    </div>
                            </div>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Receipt No</th>
                                            <td>:</td>
                                            <td>{{ $collection->collection_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>:</td>
                                            <td>{{ $collection->collectionFrom->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>:</td>
                                            <td>{{ $collection->collectionFrom->address ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d-m-Y') ?? now()->format('d-M-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prepared By</th>
                                            <td>:</td>
                                            <td>{{ $collection->createdBy->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($collection->payments as $key => $payment)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $payment->pay_mode }} <br>
                                                {{ $payment->remarks }}</td>
                                                <td>{{ number_format($payment->amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <p>IN WORD : {{ convert_number($collection->total_amount) }} Taka Only</p>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr>
                                                <td style="border: none!important;">Grand Total</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ number_format($collection->total_amount) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>

                            <footer style="display: flex; justify-content: space-between; margin-top: 40px; align-items: flex-end;">
                                <div style="text-align: center; width: 48%;">
                                    @include('partials._seek_sign', [
                                        'model' => $collection,
                                        'field' => 'signature',
                                    ])
                                    <p class="text-center mt-2 mb-0 font-weight-bold">Receiver Signature</p>
                                </div>

                                <div style="text-align: center; width: 48%;">
                                    <p>___________________________</p>
                                    <p>Authorized Signature</p>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Generate QR Code
            const qrcode = new QRCode(document.getElementById("qrcode"), {
                text: window.location.href,
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

          
        });
    </script>
    @stack('script')
@endsection