<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>Payment Receipt - {{ $makePayment->payment_id }}</title>
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
            border: none; /* no outer border */
        }

        .requisition-info th,
        .requisition-info td {
            vertical-align: top;
            padding: 3px;
            text-align: left;
            font-size: 10px;
            border: none !important; /* remove all inner borders */
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

        .notes-section {
            margin: 15px 0;
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
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header with QR Code -->
        <header class="my-header">
            @include('partials._for_pdf_header_with_qr', ['qrCode' => true, 'qrCodeUrl' => route('account.payments.make-payments.show', $makePayment->id)])
        </header>

        <!-- Title -->
        <section class="title">
            <h4>Money Receipt</h4>
        </section>

        <!-- Receipt Information -->
        <section class="requisition-info clearfix">
            <div class="left">
                <table>
                    <tr>
                        <th style="width: 30%;">Receipt No</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 65%;">{{ $makePayment->payment_id }}</td>
                    </tr>
                    <tr>
                        <th>Supplier/Vendor Name</th>
                        <td>:</td>
                        <td>{{ $makePayment->paymentTo->company_name ?? $makePayment->paymentTo->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>:</td>
                        <td>{{ $makePayment->paymentTo->address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="right">
                <table>
                    <tr>
                        <th style="width: 40%;">Payment Date</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 55%;">{{ \Carbon\Carbon::parse($makePayment->date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Prepared By</th>
                        <td>:</td>
                        <td>{{ $makePayment->createdBy->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Print Date</th>
                        <td>:</td>
                        <td>{{ now()->format('d-M-Y') }}</td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Payment Details Table -->
        <section class="invoice-details">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">SN</th>
                        <th style="width: 55%;">Payment Mode</th>
                        <th style="width: 40%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @forelse ($makePayment->paymentDetails as $detail)
                        <tr>
                            <td class="text-center">{{ $sn++ }}</td>
                            <td>
                                <strong>{{ $detail->pay_mode }}</strong>
                                @if($detail->remark)
                                    <br>
                                    <span style="font-size: 9px; color: #666;">{{ $detail->remark }}</span>
                                @endif
                                @if($detail->transaction_id)
                                    <br>
                                    <span style="font-size: 9px;">TXN: {{ $detail->transaction_id }}</span>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($detail->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No payment records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Total Section -->
        <section class="total-section clearfix">
            <div class="left">
                <p><strong>IN WORD:</strong> {{ $makePayment->amount_in_words ?? convert_number($makePayment->amount) }} Taka Only</p>
            </div>
            <div class="right">
                <table style="float: right; width: 60%;">
                    <tr>
                        <th style="text-align: left;">Grand Total</th>
                        <td style="text-align: center;">:</td>
                        <td class="text-right">
                            <strong>{{ number_format($makePayment->amount) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Notes Section -->
        @if($makePayment->notes)
        <section class="notes-section clearfix">
            <p><strong>Notes:</strong> {{ $makePayment->notes }}</p>
        </section>
        @endif

        <!-- Signature Section -->
        <section class="signature-section clearfix">
            <div class="signature-container">
                <!-- Receiver Signature -->
                <div class="signature-box left">
                    <div class="signature-display">
                        @if (@$makePayment->signature->signature)
                            <img src="{{ @$makePayment->signature->signature }}" alt="Receiver Signature">
                            <div class="signature-timestamp">
                                Signed on: {{ @$makePayment->signature->updated_at->format('d-M-Y h:i A') }}
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