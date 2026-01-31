<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Invoice Wise Payment Receipt - {{ $payment->invoice_wise_payment_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        .title {
            text-align: center;
            margin: 15px 0;
        }

        .title h4 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
        }

        .requisition-info {
            width: 100%;
            margin-bottom: 15px;
        }

        .requisition-info table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .requisition-info th,
        .requisition-info td {
            vertical-align: top;
            padding: 3px;
            text-align: left;
            font-size: 10px;
            border: none !important;
        }

        .requisition-info .left {
            width: 60%;
            float: left;
        }

        .requisition-info .right {
            width: 40%;
            float: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .invoice-details {
            margin-bottom: 15px;
        }

        .invoice-details h5 {
            margin: 10px 0 5px 0;
            font-size: 12px;
            font-weight: bold;
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
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        .invoice-details th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .total-section {
            width: 100%;
            margin-top: 10px;
        }

        .total-section .left {
            width: 60%;
            float: left;
            font-size: 9px;
        }

        .total-section .right {
            width: 40%;
            float: right;
        }

        .total-section table {
            width: 100%;
            border: none;
        }

        .total-section td,
        .total-section th {
            border: none;
            padding: 3px;
            font-size: 10px;
        }

        .signature-section {
            margin-top: 60px;
            width: 100%;
        }

        .signature-container {
            width: 100%;
        }

        .signature-box {
            width: 48%;
            text-align: center;
            display: inline-block;
            vertical-align: bottom;
        }

        .signature-box.left {
            float: left;
            margin-top: 20px;
        }

        .signature-box.right {
            float: right;
            margin-top: 50px;
        }

        .signature-display {
            height: 50px;
            margin-bottom: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .signature-display img {
            max-height: 40px;
            max-width: 150px;
            margin-bottom: 3px;
        }

        .signature-timestamp {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }

        .signature-placeholder {
            color: #999;
            font-style: italic;
            font-size: 9px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 70%;
            margin: 5px auto;
        }

        .signature-label {
            margin-top: 5px;
            font-size: 10px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        strong {
            font-weight: bold;
        }

        .badge {
            padding: 2px 6px;
            font-size: 9px;
            border-radius: 3px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header with QR Code -->
        <header class="my-header">
            @include('partials._for_pdf_header_with_qr', ['qrCode' => true, 'qrCodeUrl' => route('account.payments.invoice-wise-payments.show', $payment->id)])
        </header>

        <!-- Title -->
        <section class="title">
            <h4>Invoice Wise Payment Receipt</h4>
        </section>

        <!-- Payment Information -->
        <section class="requisition-info clearfix">
            <div class="left">
                <table>
                    <tr>
                        <th style="width: 30%;">Payment ID</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 65%;">{{ $payment->invoice_wise_payment_id }}</td>
                    </tr>
                    <tr>
                        <th>Payment To</th>
                        <td>:</td>
                        <td>{{ $payment->paymentTo->company_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>:</td>
                        <td>{{ $payment->paymentTo->address ?? 'N/A' }}</td>
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
                        <th style="width: 40%;">Created Date</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 55%;">{{ \Carbon\Carbon::parse($payment->created_at)->format('d-M-Y') }}</td>
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
                        <th style="width: 5%;" class="text-center">SN</th>
                        <th style="width: 15%;">Invoice Date</th>
                        <th style="width: 25%;">Invoice No</th>
                        <th style="width: 25%;" class="text-right">Invoice Amount</th>
                        <th style="width: 30%;" class="text-right">Payment Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @forelse ($payment->invoices as $invoiceRecord)
                        @php
                            $invoice = $invoiceRecord->invoice;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $sn++ }}</td>
                            <td>{{ $invoice->date ?? $invoice->invoice_date ?? 'N/A' }}</td>
                            <td>{{ $invoice->invoice_no ?? $invoice->requisition_no ?? 'N/A' }}</td>
                            <td class="text-right"> {{ number_format($invoice->net_amount ?? $invoice->bill_amount ?? 0) }}</td>
                            <td class="text-right"> {{ number_format($invoiceRecord->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No invoice records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section> --}}

        <!-- Payment Method Details -->
        <section class="invoice-details">
            <h5>Payment Methods</h5>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">SN</th>
                        <th style="width: 20%;">Payment Mode</th>
                        <th style="width: 20%;">Bank/Account</th>
                        <th style="width: 20%;">Transaction ID</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 20%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @forelse ($payment->payments as $detail)
                        <tr>
                            <td class="text-center">{{ $sn++ }}</td>
                            <td>
                                <strong>{{ $detail->pay_mode }}</strong>
                                @if($detail->remark)
                                    <br>
                                    <span style="font-size: 9px; color: #666;">{{ $detail->remark }}</span>
                                @endif
                            </td>
                            <td>{{ $detail->bank->account_name ?? 'N/A' }}</td>
                            <td>{{ $detail->transaction_id ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($detail->date)->format('d-M-Y') }}</td>
                            <td class="text-right"> {{ number_format($detail->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No payment records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Total Section -->
        <section class="total-section clearfix">
            <div class="left">
                <p><strong>IN WORD:</strong> {{ convert_number($payment->total_amount) }} Taka Only</p>
            </div>
            <div class="right">
                <table style="float: right; width: 60%;">
                    <tr>
                        <th style="text-align: left;">Grand Total</th>
                        <td style="text-align: center;">:</td>
                        <td class="text-right">
                            <strong> {{ number_format($payment->total_amount) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Signature Section -->
        <section class="signature-section clearfix">
            <div class="signature-container">
                <!-- Receiver Signature -->
                <div class="signature-box left">
                    <div class="signature-display">
                        @if (@$payment->signature->signature)
                            <img src="{{ @$payment->signature->signature }}" alt="Receiver Signature">
                            <div class="signature-timestamp">
                                Signed on: {{ @$payment->signature->updated_at->format('d-M-Y h:i A') }}
                            </div>
                        @else
                            <div class="signature-placeholder">No signature captured</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Receiver Signature</div>
                </div>

                <!-- Authorized Signature -->
                <div class="signature-box right">
                    <div class="signature-line" style="margin-top: 20px;"></div>
                    <div class="signature-label">Authorized Signature</div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>