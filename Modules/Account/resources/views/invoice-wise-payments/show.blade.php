@section('title', 'Invoice Wise Payment Receipt')
@section('description', 'Invoice Wise Payment Receipt')
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
                                    <li class="breadcrumb-item"><a href="{{ route('account.payments.invoice-wise-payments.index') }}">Invoice Wise Payments</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Payment Receipt</li>
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

                @media print {
                    .no-print {
                        display: none !important;
                    }
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">Invoice Wise Payment Receipt</h3>
                    
                    <div class="action-buttons no-print">
                        <a href="{{ route('account.payments.invoice-wise-payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="las la-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}" target="_blank"
                            class="btn btn-primary btn-sm">
                            <i class="fa fa-file-excel"></i> Excel
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
                                    <h2 class="title">Invoice Wise Payment Receipt</h2>
                                </div>
                                <div class="qr-code-section no-print">
                                    <div id="qrcode"></div>
                                </div>
                            </div>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Payment ID</th>
                                            <td>:</td>
                                            <td>{{ @$payment->invoice_wise_payment_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment To</th>
                                            <td>:</td>
                                            <td>{{ @$payment->paymentTo->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>:</td>
                                            <td>{{ @$payment->paymentTo->address ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>:</td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'verified' ? 'warning' : ($payment->status === 'denied' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Created Date</th>
                                            <td>:</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-M-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>:</td>
                                            <td>{{ $payment->createdBy->name ?? 'N/A' }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Print Date</th>
                                            <td>:</td>
                                            <td>{{ now()->format('d-M-Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <!-- Invoice Details -->
                            {{-- <section class="invoice-details">
                                <h5>Invoice Details</h5>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Amount</th>
                                            <th>Payment Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = 1; @endphp
                                        @foreach ($payment->invoices as $invoiceRecord)
                                            @php
                                                $invoice = $invoiceRecord->invoice;
                                            @endphp
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td>{{ $invoice->date ?? $invoice->invoice_date ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice_no ?? $invoice->requisition_no ?? 'N/A' }}</td>
                                                <td>৳ {{ number_format($invoice->net_amount ?? $invoice->bill_amount ?? 0) }}</td>
                                                <td>৳ {{ number_format($invoiceRecord->amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </section> --}}

                            <!-- Payment Method Details -->
                            <section class="invoice-details">
                                <h5>Payment Methods</h5>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Payment Mode</th>
                                            <th>Bank/Account</th>
                                            <th>Transaction ID</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            @if($payment->payments->some(fn($p) => $p->attachments))
                                            <th>Attachment</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = 1; @endphp
                                        @foreach ($payment->payments as $detail)
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td>
                                                    <strong>{{ $detail->pay_mode }}</strong>
                                                    @if ($detail->remark)
                                                        <br><span style="font-size: 12px; color: #666;">{{ $detail->remark }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $detail->bank->account_name ?? 'N/A' }}</td>
                                                <td>{{ $detail->transaction_id ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($detail->date)->format('d-M-Y') }}</td>
                                                <td>৳ {{ number_format($detail->amount) }}</td>
                                                @if($payment->payments->some(fn($p) => $p->attachments))
                                                <td>
                                                    @if($detail->attachments)
                                                        <a href="{{ $detail->attachments }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fa fa-file"></i> View
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <section class="requisition-info" style="display: flex; justify-content: space-between; margin-top: 20px;">
                                    <div class="left" style="width: 70%;">
                                        <p>IN WORD : {{ convert_number($payment->total_amount) }} Taka Only</p>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr>
                                                <td style="border: none!important;"><strong>Grand Total</strong></td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>৳ {{ number_format($payment->total_amount) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>

                            <footer style="display: flex; justify-content: space-between; margin-top: 40px; align-items: flex-end;">
                                <div style="text-align: center; width: 48%;">
                                    @include('partials._seek_sign', [
                                        'model' => $payment,
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