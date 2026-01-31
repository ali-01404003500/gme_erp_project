@section('title', 'Purchase Return Invoice Bill Details')
@section('description', 'Purchase Return Invoice Bill Details')
@extends('layout.app')
@section('page-head')
@endsection

@section('content')
    <div class="container-fluid">
        <style id="custom-style">
            body {
                font-size: smaller;
                margin-left: 15px;
                margin-right: 15px;
                margin-bottom: 15px;
                line-height: 1.6;
            }

            .catalog-container {
                width: 100%;
                background-color: #ffffff;
                padding-left: 40px;
                padding-right: 40px;
                padding-bottom: 40px;
                position: relative;
            }

            .my-header img {
                max-width: 130px;
            }

            .my-header h1 {
                margin: 0;
                font-size: 35px;
                font-weight: bold;
                color: rgb(0, 0, 187);
            }

            .my-header p {
                margin: 5px 0;
                font-size: 11px;
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

            .watermark {
                display: none;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 120px;
                font-weight: bold;
                color: rgba(0, 0, 0, 0.05);
                white-space: nowrap;
                z-index: 0;
                pointer-events: none;
            }

            .sales-order-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .sales-order-info .left,
            .sales-order-info .right {
                width: 48%;
            }

            .sales-order-info table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }

            .sales-order-info th,
            .sales-order-info td {
                padding: 5px;
                text-align: left;
                font-size: 14px;
            }

            .invoice-details table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid black;
            }

            .invoice-details th,
            .invoice-details td {
                border: 1px solid black;
                padding: 10px;
                text-align: left;
            }

            .amount-info {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .amount-info .left {
                width: 30%;
            }

            .amount-info .right {
                width: 30%;
            }

            .amount-info .right table {
                border: none !important;
            }

            .amount-info .right th,
            .amount-info .right td {
                border: none !important;
                padding: 5px;
            }

            .payment-box {
                border: 2px solid #000;
                padding: 15px;
                margin-top: 20px;
                width: 100%;
            }

            .payment-box table {
                width: 100%;
                border: none;
            }

            .payment-box th,
            .payment-box td {
                border: none;
                padding: 5px;
            }

            .signature-section {
                display: flex;
                justify-content: space-between;
                margin-top: 80px;
            }

            .signature-item {
                text-align: center;
                width: 45%;
            }

            .signature-line {
                border-top: 1px solid #000;
                margin-bottom: 5px;
                height: 10px;
            }

            .terms-section {
                margin-top: 30px;
                font-size: 11px;
            }

            .qr-code {
                position: absolute;
                top: 20px;
                right: 20px;
            }

            .checkbox-controls {
                margin: 20px 0;
            }

            .custom-control {
                display: inline-block;
                margin-right: 25px;
            }

            .remarks-section {
                display: none;
                margin: 20px 0;
            }

            @media print {
                body * {
                    visibility: hidden !important;
                }

                #printableArea,
                #printableArea * {
                    visibility: visible !important;
                }

                #printableArea {
                    position: absolute !important;
                    top: 0;
                    left: 0;
                    width: 100%;
                }

                .watermark {
                    display: block !important;
                    visibility: visible !important;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 120px;
                    font-weight: bold;
                    color: rgba(0, 0, 0, 0.08) !important;
                    white-space: nowrap;
                    z-index: 9999;
                    pointer-events: none;
                }

                .checkbox-controls {
                    display: none !important;
                }

                .action-btn {
                    display: none !important;
                }

                .breadcrumb-main {
                    display: none !important;
                }

                .catalog-container {
                    padding: 20px;
                }



                .sales-order-info th,
                .sales-order-info td {
                    font-size: 11px;
                }

                .invoice-details th,
                .invoice-details td {
                    font-size: 11px;
                }

                .amount-info th,
                .amount-info td {
                    font-size: 11px;
                }

                .payment-box th,
                .payment-box td {
                    font-size: 11px;
                }

                .terms-section {
                    font-size: 10px;
                }
            }
        </style>

        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Purchase Return Invoice Bill') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex justify-content-between">
                            @if (hasPermission('purchase.returns.index'))
                                <a href="{{ route('purchase.returns.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                            <div style="margin-left: 5px;"></div>
                            <button class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm"
                                onclick="printWholePage()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="width: 100%">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Purchase Return Invoice Bill') }}</h4>
                </div>
            </div>

            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-start checkbox-controls">
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="titleCheck">
                            <label class="custom-control-label" for="titleCheck">Title</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="paymentStatusCheck">
                            <label class="custom-control-label" for="paymentStatusCheck">Payment Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="vatCheck">
                            <label class="custom-control-label" for="vatCheck">VAT Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="aitCheck">
                            <label class="custom-control-label" for="aitCheck">AIT Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="cwCheck">
                            <label class="custom-control-label" for="cwCheck">CW</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="opCheck">
                            <label class="custom-control-label" for="opCheck">OP Show</label>
                        </div>
                    </div>
                </div>

                <div class="catalog-container" id="printableArea">
                    <!-- QR Code Canvas -->
                    <div class="qr-code">
                        <canvas id="qrcode" width="100" height="100"></canvas>
                    </div>

                    <!-- Dynamic Watermark -->
                    <div class="watermark" id="watermark">
                        {{ strtoupper($purchaseReturn->payment_status ?? 'DUE') }}
                    </div>

                    <!-- Header -->
                    <header class="my-header">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="width: 10%;">
                                <img class="header-img" src="{{ $company_info->company_logo ?? '' }}" alt="Company Logo">
                            </div>
                            <div style="width: 80%">
                                <div style="text-align: center;">
                                    <h1>{{ $company_info->company_name ?? 'Global Medical Engineering (BD) Ltd.' }}</h1>
                                    <p>{{ $company_info->company_bio ?? 'Medical Equipment & Laboratory Solution Provider' }}
                                    </p>
                                    <p>Address:
                                        {{ $company_info->address ?? '17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000' }}
                                    </p>
                                    <p>Hotline: {{ $company_info->hotline ?? '+88 09678 020555' }} Mobile:
                                        {{ $company_info->mobile ?? '+8801404003500' }}</p>
                                    <p>e-mail: <a
                                            href="mailto:{{ $company_info->email ?? 'info@gmebd.com' }}">{{ $company_info->email ?? 'info@gmebd.com' }}</a>
                                        web: <a
                                            href="{{ $company_info->website ?? 'http://www.gmebd.com' }}">{{ $company_info->website ?? 'www.gmebd.com' }}</a>
                                    </p>
                                </div>
                            </div>
                            <div style="width: 10%"></div>
                        </div>
                    </header>

                    <!-- Title -->
                    <section class="title">
                        <h2>Purchase Return Invoice Bill</h2>
                    </section>

                    <!-- Purchase Return Information -->
                    <section class="sales-order-info">
                        <div class="left">
                            <table>
                                <tr>
                                    <td width="30%">Return No</td>
                                    <td>:</td>
                                    <td>{{ $purchaseReturn->invoice_no }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $purchaseReturn->supplier->company_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>{{ $purchaseReturn->supplier->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>:</td>
                                    <td>{{ $purchaseReturn->supplier->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="right">
                            <table>
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($purchaseReturn->return_date)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td>Time</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($purchaseReturn->created_at)->format('h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td>Return By</td>
                                    <td>:</td>
                                    <td>{{ $purchaseReturn->createdBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td>Print Date</td>
                                    <td>:</td>
                                    <td>{{ now()->format('d-M-Y') }} {{ now()->format('h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </section>

                    <!-- Product Details -->
                    <section class="invoice-details">
                        <table>
                            <thead>
                                <tr>
                                    <th width="5%">SN</th>
                                    <th width="35%">Product Description</th>
                                    <th width="10%">Received Qty</th>
                                    <th width="10%">Return Qty</th>
                                    <th width="15%">Price</th>
                                    <th width="15%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseReturn->purchaseReturnDetails as $key => $detail)
                                    <tr>
                                        <td style="text-align: center;">{{ $key + 1 }}</td>
                                        <td>
                                            <div style="font-weight: bold;">{{ $detail->product->name }}</div>
                                            <div>
                                                Brand: {{ optional($detail->product->brand)->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            {{ numberFormat($detail->received_quantity ?? $detail->quantity) }}
                                            {{ $detail->product->unit->name ?? 'PCS' }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ numberFormat($detail->quantity) }}
                                            {{ $detail->product->unit->name ?? 'PCS' }}
                                        </td>
                                        <td style="text-align: right;">{{ numberFormat($detail->price) }}</td>
                                        <td style="text-align: right;">{{ numberFormat($detail->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>

                    <!-- Amount Information -->
                    <section class="amount-info">
                        <div class="left">
                            @php
                                $totalAmount = $purchaseReturn->total_amount ?? 0;
                                $discount = $purchaseReturn->discount ?? 0;
                                $baseAfterDisc = $totalAmount - $discount;
                                $netAmountForWords = $baseAfterDisc;
                            @endphp
                            <p><strong>IN WORD:</strong> <span
                                    id="amountInWords">{{ convert_number($netAmountForWords) ?? 'Zero' }} Taka Only</span>
                            </p>

                            <!-- Payment Status Box -->
                            <div class="payment-box">
                                <table>
                                    <tr>
                                        <th>Due with Amount</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            {{ numberFormat($purchaseReturn->due_amount ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Purchase Return with Amount</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            {{ numberFormat($purchaseReturn->net_amount ?? $purchaseReturn->total_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Paid with Amount</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            {{ numberFormat($purchaseReturn->paid_amount ?? 0) }}</td>
                                    </tr>
                                    <tr style="border-top: 1px solid #000;">
                                        <th><strong>Total Advance/Total Due</strong></th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <strong>{{ numberFormat(($purchaseReturn->net_amount ?? 0) - ($purchaseReturn->paid_amount ?? 0)) }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="right">
                            <table>
                                <tr>
                                    <th>Total</th>
                                    <td>:</td>
                                    <td style="text-align: right;">
                                        <strong>{{ numberFormat($purchaseReturn->total_amount) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>:</td>
                                    <td style="text-align: right;">
                                        <strong>{{ numberFormat($purchaseReturn->discount ?? 0) }}</strong></td>
                                </tr>
                                <tr class="vat-row" style="display: none;">
                                    <th>VAT (10%)</th>
                                    <td>:</td>
                                    <td class="vat-value" style="text-align: right;"><strong>0.00</strong></td>
                                </tr>
                                <tr class="ait-row" style="display: none;">
                                    <th>AIT (5%)</th>
                                    <td>:</td>
                                    <td class="ait-value" style="text-align: right;"><strong>0.00</strong></td>
                                </tr>
                                <tr>
                                    <th><strong>Grand Total</strong></th>
                                    <td>:</td>
                                    <td class="grand-total" style="text-align: right;">
                                        <strong>{{ numberFormat($purchaseReturn->net_amount ?? $purchaseReturn->total_amount) }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- Signature Section -->
                            <div class="signature-section">
                                <div class="signature-item">
                                    <div class="signature-line"></div>
                                    <div>Received</div>
                                </div>
                                <div class="signature-item">
                                    <div class="signature-line"></div>
                                    <div>Authorized</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Data for JavaScript -->
                    <div id="js-base-data" data-total="{{ $purchaseReturn->total_amount }}"
                        data-discount="{{ $purchaseReturn->discount ?? 0 }}"
                        data-payment-status="{{ $purchaseReturn->payment_status ?? 'paid' }}">
                    </div>

                    <!-- Remarks Section -->
                    <div class="remarks-section" style="display: none;">
                        <h3>Remarks</h3>
                        <p>{{ $purchaseReturn->remarks ?? 'No remarks available.' }}</p>
                    </div>

                    <!-- Terms and Conditions -->
                    <section class="terms-section">
                        <div id="termsContent">
                            <p><strong>১.</strong> সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম
                                অভিযোগ গ্রহণযোগ্য হবে না।</p>
                            <p><strong>২.</strong> প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল
                                পাওয়ার সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না৷
                                আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ০১৪০৪০০৩৫০১(বিকাশ পেমেন্ট)।</p>
                            <p><strong>৩.</strong> খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত
                                রিএজেন্ট অফেরতযোগ্য।</p>
                            <p><strong>৪.</strong> যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে।
                                যেকোন প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ করুন-
                                ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                            <p><strong>৫.</strong> কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো
                                কারনে মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং(বিডি) লিঃ কোনো ভাবে দায়ী নয়।</p>
                            <p><strong>৬.</strong> কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায়
                                রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ গ্রহণযোগ্য নয় ও
                                এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vatCheck = document.getElementById('vatCheck');
            const aitCheck = document.getElementById('aitCheck');
            const paymentStatusCheck = document.getElementById('paymentStatusCheck');
            const titleCheck = document.getElementById('titleCheck');
            const cwCheck = document.getElementById('cwCheck');
            const opCheck = document.getElementById('opCheck');

            const vatRow = document.querySelector('.vat-row');
            const aitRow = document.querySelector('.ait-row');
            const remarksSec = document.querySelector('.remarks-section');
            const watermark = document.getElementById('watermark');
            const myHeader = document.querySelector('.my-header');
            const paymentBox = document.querySelector('.payment-box');
            const termsSection = document.querySelector('.terms-section');

            const baseData = document.getElementById('js-base-data');
            const vatValueEl = document.querySelector('.vat-value strong');
            const aitValueEl = document.querySelector('.ait-value strong');
            const grandTotalEl = document.querySelector('.grand-total strong');
            const amountInWordsEl = document.getElementById('amountInWords');

            // Set default checked states
            if (titleCheck) titleCheck.checked = true;
            if (paymentStatusCheck) paymentStatusCheck.checked = true;

            // Generate QR Code using QRious
            function generateQRCode() {
                const canvas = document.getElementById('qrcode');
                const baseUrl = window.location.origin + window.location.pathname;
                const printUrl = baseUrl + '?print=1&auto_print=1';

                try {
                    if (typeof QRious !== 'undefined') {
                        const qr = new QRious({
                            element: canvas,
                            value: printUrl,
                            size: 100,
                            level: 'M'
                        });
                        // Ensure the QR code container is visible
                        const qrContainer = document.querySelector('.qr-code');
                        if (qrContainer) qrContainer.style.display = 'block';
                    } else {
                        console.warn('QRious library not loaded, skipping QR code generation');
                        const qrContainer = document.querySelector('.qr-code');
                        if (qrContainer) qrContainer.style.display = 'none';
                    }
                } catch (error) {
                    console.error('QR Code generation error:', error);
                    const qrContainer = document.querySelector('.qr-code');
                    if (qrContainer) qrContainer.style.display = 'none';
                }
            }

            // Number to words function (basic implementation)
            function numberToWords(number) {
                // Handle integer and fractional parts
                const integerPart = Math.floor(number);
                const fractionalPart = Math.round((number - integerPart) * 100); // Assuming two decimal places

                // Check if number is out of range
                if (integerPart < 0 || integerPart > 999999999) {
                    throw new Error("Number is out of range");
                }

                // Arrays for word conversion
                const ones = [
                    "", "One", "Two", "Three", "Four", "Five", "Six",
                    "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
                    "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"
                ];
                const tens = [
                    "", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty",
                    "Seventy", "Eighty", "Ninety"
                ];

                // Recursive function to convert numbers less than 1000
                function convertLessThanThousand(num) {
                    let res = "";
                    const hundreds = Math.floor(num / 100); // Hundreds
                    num %= 100;
                    const tensDigit = Math.floor(num / 10); // Tens
                    const onesDigit = num % 10; // Ones

                    if (hundreds) {
                        res += ones[hundreds] + " Hundred ";
                    }
                    if (num) {
                        if (res !== "") {
                            res += "and ";
                        }
                        if (tensDigit < 2) {
                            res += ones[tensDigit * 10 + onesDigit];
                        } else {
                            res += tens[tensDigit];
                            if (onesDigit) {
                                res += "-" + ones[onesDigit];
                            }
                        }
                    }
                    return res.trim();
                }

                // Main conversion logic
                let res = "";
                let num = integerPart;

                const crore = Math.floor(num / 10000000); // Crore
                num -= crore * 10000000;
                const lakh = Math.floor(num / 100000); // Lakh
                num -= lakh * 100000;
                const thousand = Math.floor(num / 1000); // Thousand
                num -= thousand * 1000;

                if (crore) {
                    res += convertLessThanThousand(crore) + " Crore ";
                }
                if (lakh) {
                    res += convertLessThanThousand(lakh) + " Lakh ";
                }
                if (thousand) {
                    res += convertLessThanThousand(thousand) + " Thousand ";
                }
                if (num) {
                    res += convertLessThanThousand(num);
                }

                if (res === "") {
                    res = "Zero";
                }

                // Handle fractional part
                if (fractionalPart) {
                    res += " point ";
                    if (fractionalPart < 20) {
                        res += ones[fractionalPart];
                    } else {
                        const fractionTens = Math.floor(fractionalPart / 10);
                        const fractionOnes = fractionalPart % 10;
                        res += tens[fractionTens];
                        if (fractionOnes) {
                            res += "-" + ones[fractionOnes];
                        }
                    }
                }

                return res.charAt(0).toUpperCase() + res.slice(1);
            }

            // Calculate amounts and update display
            window.recalcAndToggle = function() {
                if (myHeader) myHeader.style.display = titleCheck.checked ? '' : 'none';
                if (paymentBox) paymentBox.style.display = paymentStatusCheck.checked ? '' : 'none';
                if (termsSection) termsSection.style.display = cwCheck.checked ? '' : 'none';

                vatRow.style.display = vatCheck.checked ? '' : 'none';
                aitRow.style.display = aitCheck.checked ? '' : 'none';

                if (paymentStatusCheck.checked) {
                    watermark.style.display = 'block';
                } else {
                    watermark.style.display = 'none';
                }

                const totalAmount = parseFloat(baseData.dataset.total) || 0;
                const discount = parseFloat(baseData.dataset.discount) || 0;
                const baseAfterDisc = totalAmount - discount;

                const vat = vatCheck.checked ? baseAfterDisc * 0.10 : 0;
                const ait = aitCheck.checked ? baseAfterDisc * 0.05 : 0;
                const grandTotal = baseAfterDisc + vat - ait;

                if (vatValueEl) vatValueEl.textContent = vat.toFixed();
                if (aitValueEl) aitValueEl.textContent = ait.toFixed();
                if (grandTotalEl) grandTotalEl.textContent = grandTotal.toFixed();

                if (amountInWordsEl) {
                    amountInWordsEl.textContent = numberToWords(grandTotal) + " Taka Only";
                }
            };

            // Bind change events
            [vatCheck, aitCheck, paymentStatusCheck, titleCheck, cwCheck, opCheck].forEach(chk => {
                if (chk) {
                    chk.addEventListener('change', recalcAndToggle);
                }
            });

            // Initialize
            generateQRCode();
            recalcAndToggle();

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                if (titleCheck) titleCheck.checked = true;
                if (paymentStatusCheck) paymentStatusCheck.checked = true;
                if (cwCheck) cwCheck.checked = true;

                recalcAndToggle();

                if (urlParams.get('auto_print') === '1') {
                    setTimeout(function() {
                        printWholePage();
                    }, 1000);
                }
            }
        });

        // Updated Print function
        function printWholePage() {
            if (typeof window.recalcAndToggle === 'function') {
                window.recalcAndToggle();
            }

            // Get the QR code canvas and convert to image
            const qrCanvas = document.getElementById('qrcode');
            let qrCodeDataUrl = '';
            if (qrCanvas) {
                try {
                    qrCodeDataUrl = qrCanvas.toDataURL('image/png');
                } catch (error) {
                    console.error('Error converting QR code canvas to image:', error);
                }
            }

            const printContents = document.getElementById('printableArea').innerHTML;

            // Replace the canvas with an img tag in the print contents
            const printContentsWithImage = printContents.replace(
                '<canvas id="qrcode" width="100" height="100"></canvas>',
                `<img src="${qrCodeDataUrl}" width="80" height="80" style="border: 1px solid #888; border-radius: 4px;">`
            );

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Purchase Return Invoice Bill Print</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            font-size: smaller;
                            line-height: 1.6;
                            position: relative;
                        }
                        
                        .catalog-container {
                            width: 100%;
                            background-color: #ffffff;
                            padding: 20px;
                            position: relative;
                        }
                        
                        .watermark {
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            font-size: 120px;
                            font-weight: bold;
                            color: rgba(0, 0, 0, 0.08);
                            white-space: nowrap;
                            z-index: -1;
                            pointer-events: none;
                        }
                        
                        .my-header img {
                            max-width: 80px;
                        }
                        .my-header h1 {
                            margin: 0;
                            font-size: 24px;
                            font-weight: bold;
                            color: rgb(0, 0, 187);
                        }
                        .my-header p {
                            margin: 5px 0;
                            font-size: 10px;
                        }
                        .title {
                            text-align: center;
                            margin: 15px 0;
                        }
                        .title h2 {
                            margin: 0;
                            font-size: 18px;
                            text-decoration: underline;
                        }
                        .sales-order-info {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 20px;
                        }
                        .sales-order-info .left{
                            width: 60%;
                            }
                        .sales-order-info .right {
                            width: 40%;
                        }
                        .sales-order-info table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        .sales-order-info th,
                        .sales-order-info td {
                            font-size: 11px;
                        }
                        .invoice-details table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        .invoice-details th,
                        .invoice-details td {
                            border: 1px solid #000;
                            padding: 8px;
                            font-size: 11px;
                        }
                        .amount-info {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 20px;
                        }
                        .amount-info .left {
                            width: 65%;
                        }
                        .amount-info .right {
                            width: 30%;
                        }
                        .amount-info .right table {
                            border: none;
                        }
                        .amount-info .right th,
                        .amount-info .right td {
                            border: none;
                            padding: 3px;
                            font-size: 11px;
                        }
                        .payment-box {
                            border: 2px solid #000;
                            padding: 10px;
                            margin-top: 15px;
                        }
                        .payment-box table {
                            width: 100%;
                        }
                        .payment-box th,
                        .payment-box td {
                            padding: 3px;
                            font-size: 11px;
                        }
                        .signature-section {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 65px;
                        }
                        .signature-item {
                            text-align: center;
                            width: 45%;
                        }
                        .signature-line {
                            border-top: 1px solid #000;
                            margin-bottom: 5px;
                            height: 10px;
                        }
                        .terms-section {
                            margin-top: 20px;
                            font-size: 10px;
                        }
                        .qr-code {
                            position: absolute;
                            top: 10px;
                            right: 10px;
                            display: block !important;
                            visibility: visible !important;
                        }
                        
                        .qr-code img {
                            border: 1px solid #888;
                            border-radius: 4px;
                            display: block !important;
                            visibility: visible !important;
                            width: 80px !important;
                            height: 80px !important;
                        }
                    </style>
                </head>
                <body>
                    ${printContentsWithImage}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endsection
