@section('title', 'Vendor Bill')
@section('description', 'View and print generated vendor bill details')
@extends('layout.app')

@section('content')
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
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}">{{ trans('menu.generated-vendor-bills') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.view-vendor-bill') }}</li>
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
                    align-items: flex-end;
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

                @media print {
                    .no-print {
                        display: none !important;
                    }
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">{{ trans('menu.view-vendor-bill') }}</h3>

                    <div class="action-buttons no-print">
                        <a href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}"
                            class="btn btn-secondary btn-sm">
                            <i class="las la-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> PDF
                        </a>
                    </div>
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
                                    <h2 class="title">Vendor Bill</h2>
                                </div>
                                <div class="qr-code-section no-print">
                                    <div id="qrcode"></div>
                                </div>
                            </div>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Bill No</th>
                                            <td>:</td>
                                            <td>{{ $generatedVendorBill->bill_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bill For</th>
                                            <td>:</td>
                                            <td>{{ $generatedVendorBill->billFor?->company_name ?? $generatedVendorBill->billFor?->title ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>:</td>
                                            <td>{{ $generatedVendorBill->billFor?->address ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <td>{{ \Carbon\Carbon::parse($generatedVendorBill->bill_date)->format('d-M-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Print Date</th>
                                            <td>:</td>
                                            <td>{{ now()->format('d-M-Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <strong>{{ $generatedVendorBill->title }}</strong>
                                                @if ($generatedVendorBill->remarks)
                                                    <br><span
                                                        style="font-size: 12px; color: #666;">{{ $generatedVendorBill->remarks }}</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($generatedVendorBill->amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <p>IN WORD : {{ convert_number($generatedVendorBill->amount) }} Taka Only</p>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr>
                                                <td style="border: none!important;">Grand Total</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ number_format($generatedVendorBill->amount) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>

                            @if($generatedVendorBill->remarks)
                                <section class="notes-section" style="margin-bottom: 20px;">
                                    <p><strong>Notes:</strong> {{ $generatedVendorBill->remarks }}</p>
                                </section>
                            @endif

                            <footer
                                style="display: flex; justify-content: space-between; margin-top: 40px; align-items: flex-end;">
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
        $(document).ready(function () {
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