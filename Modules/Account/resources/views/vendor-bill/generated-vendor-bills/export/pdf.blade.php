<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>Vendor Bill - {{ $generatedVendorBill->bill_id }}</title>
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
            /* no outer border */
        }

        .requisition-info th,
        .requisition-info td {
            vertical-align: top;
            padding: 3px;
            text-align: left;
            font-size: 10px;
            border: none !important;
            /* remove all inner borders */
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
            @include('partials._for_pdf_header_with_qr', ['qrCode' => true, 'qrCodeUrl' => route('account.vendor-bills.generated-vendor-bills.show', $generatedVendorBill->id)])
        </header>

        <!-- Title -->
        <section class="title">
            <h4>Vendor Bill</h4>
        </section>

        <!-- Bill Information -->
        <section class="requisition-info clearfix">
            <div class="left">
                <table>
                    <tr>
                        <th style="width: 30%;">Bill No</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 65%;">{{ $generatedVendorBill->bill_id }}</td>
                    </tr>
                    <tr>
                        <th>Bill For</th>
                        <td>:</td>
                        <td>{{ $generatedVendorBill->billFor?->company_name ?? $generatedVendorBill->billFor?->title ?? 'N/A' }}
                            <br><small>({{ class_basename($generatedVendorBill->bill_for_type) }})</small>
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
                        <th style="width: 40%;">Date</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 55%;">
                            {{ \Carbon\Carbon::parse($generatedVendorBill->bill_date)->format('d-M-Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Prepared By</th>
                        <td>:</td>
                        <td>{{ $generatedVendorBill->createdBy->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Print Date</th>
                        <td>:</td>
                        <td>{{ now()->format('d-M-Y') }}</td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Bill Details Table -->
        <section class="invoice-details">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-center">SN</th>
                        <th style="width: 60%;">Description</th>
                        <th style="width: 30%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            <strong>{{ $generatedVendorBill->title }}</strong>
                            @if ($generatedVendorBill->remarks)
                                <br><span style="font-size: 9px; color: #666;">{{ $generatedVendorBill->remarks }}</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($generatedVendorBill->amount) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Total Section -->
        <section class="total-section clearfix">
            <div class="left">
                <p><strong>IN WORD:</strong> {{ convert_number($generatedVendorBill->amount) }} Taka Only</p>
            </div>
            <div class="right">
                <table style="float: right; width: 60%;">
                    <tr>
                        <th style="text-align: left;">Grand Total</th>
                        <td style="text-align: center;">:</td>
                        <td class="text-right">
                            <strong>{{ number_format($generatedVendorBill->amount) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Notes Section -->
        @if($generatedVendorBill->remarks)
            <section class="notes-section clearfix">
                <p><strong>Remarks:</strong> {{ $generatedVendorBill->remarks }}</p>
            </section>
        @endif

        <!-- Signature Section -->
        <section class="signature-section clearfix">
            <div class="signature-container">
                <!-- Prepared By -->
                <div class="signature-box left">
                    <div class="signature-display">
                        <div class="signature-placeholder"><!-- Placeholder if we had a prepared by signature --></div>
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Prepared By</div>
                    <div style="font-size: 9px; margin-top: 3px;">{{ $generatedVendorBill->createdBy->name ?? 'N/A' }}
                    </div>
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