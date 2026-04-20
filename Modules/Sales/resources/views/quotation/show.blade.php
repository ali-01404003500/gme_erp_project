<!-- views/sales/quotation/pdf.blade.php (PDF Blade) -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quotation - {{ $quotation->reference_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            position: relative;
        }

        @page {
            margin-top: 110px;
            margin-bottom: 80px;
            margin-left: 40px;
            margin-right: 40px;
            /* ওয়াটারমার্ক ব্যাকগ্রাউন্ড হিসেবে যোগ করুন */
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><text x="50%" y="50%" transform="rotate(-45, 50%, 50%)" font-size="120" font-family="Arial, sans-serif" fill="rgba(0,0,0,0.08)" text-anchor="middle" dominant-baseline="middle" font-weight="bold">QUOTATION</text></svg>');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 80% auto;
        }

        /* প্রিন্ট সেটিংস */
        @media print {
            .watermark {
                display: block !important;
            }
        }

        header {
            position: fixed;
            top: -110px;
            left: -40px;
            right: -40px;
            height: 110px;
            background-color: #fff;
            text-align: center;
            line-height: 1.4;
            z-index: 1000;
        }

        footer {
            position: fixed;
            bottom: -80px;
            left: -40px;
            right: -40px;
            height: 80px;
            background-color: #fff;
            text-align: center;
            line-height: 1.3;
            border-top: 1px solid #ccc;
            z-index: 1000;
        }

        .content {
            margin-top: 10px;
            margin-bottom: 20px;
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }

        /* সকল পেজে প্রদর্শনযোগ্য ওয়াটারমার্ক */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.08);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
            opacity: 1;
            width: 100%;
            text-align: center;
            /* সকল পেজে প্রদর্শন নিশ্চিত করুন */
            page-break-inside: avoid;
            break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }

        .header-skew {
            width: 100%;
            transform: skewX(35deg);
            position: absolute;
            top: 0;
            left: 0;
            z-index: -99;
        }

        .header-skew {
            position: absolute;
            top: 5px;
            left: 0;
            transform: skewX(33deg);
        }

        .blue-left {
            width: 17%;
            height: 55px;
            border-left: 1px solid white !important;
            border-bottom: 1px solid white !important;
            border-right: 4px solid rgb(0, 0, 179);
            border-top: 4px solid rgb(0, 0, 179);
        }

        .blue-bottom {
            width: 83%;
            height: 55px;
            border-right: 1px solid white !important;
            border-top: 1px solid white !important;
            border-left: 4px solid rgb(0, 0, 179);
            border-bottom: 4px solid rgb(0, 0, 179);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        td {
            vertical-align: top;
        }

        .contact-info,
        .terms,
        .signature {
            margin: 20px 0;
        }

        .office-details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .office {
            width: 45%;
        }

        p {
            margin: 10px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .terms-table {
            width: 100%;
            margin: 20px 0;
            border: none;
        }

        .terms-table th,
        .terms-table td {
            padding: 10px 0;
            border: none;
        }

        .terms h3 {
            margin: 20px 0 10px;
        }

        .terms p {
            margin: 10px 0 20px;
        }

        h1 {
            font-size: 45px;
        }

        .signature {
            max-height: 100px;
        }

        /* পেজ ব্রেক স্টাইল */
        .page-break {
            page-break-before: always;
            position: relative;
        }

        /* টেবিলের জন্য পেজ ব্রেক নিয়ন্ত্রণ */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .signature-timestamp {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <!-- সকল পেজে প্রদর্শিত হবে এমন ওয়াটারমার্ক -->
    <div class="watermark">QUOTATION</div>

    <header>
        @include('partials._for_pdf_header', ['company_info' => $company_info])
    </header>
    <footer>
        @include('partials._for_pdf_footer')
    </footer>

    <div class="content">
        <div class="catalog-container">
            <div style="height: 800px;">
                <div class="contact-info">
                    <p>Ref: GMEL/Equip/Quotation/{{ date('Y') }}-{{ str_pad(date('m'), 2, '0', STR_PAD_LEFT) }}</p>
                    <p>Date: {{ \Carbon\Carbon::parse($quotation->date)->format('d F, Y') }}</p>
                    <p><strong>To,</strong><br>
                        {!! $editedCustomerInfo !!}
                    </p>
                </div>

                <div class="subject">
                    <p><strong>Subject:</strong> Price Offer for Hospital/ Diagnostic Equipment</p>
                </div>

                <div class="content">
                    <p>Dear Sir,</p>
                    <p>I hope this letter finds you well. I am reaching out to present you with a price offer
                        for our product/service. We are confident in our ability to deliver high quality, and
                        therefore, we have tailored a competitive price proposal that promises not merely
                        adequacy but the best.</p>
                    <p>We wholeheartedly believe that our offer would be incredibly beneficial for you. If you
                        are interested in our proposition, we would be delighted to furnish further details and
                        discuss potential adjustments to suit your needs better.</p>
                    <p>Once again, I appreciate your valuable time considering our offer. I am looking forward
                        to your positive response. Please do not hesitate to raise any queries regarding our
                        proposal.</p>
                    <p>Best Regards,</p>

                    @if (@$quotation->signature->signature)
                        <img src="{{ @$quotation->signature->signature }}" alt="Receiver Signature"
                            style="max-height: 60px;">
                        <div class="signature-timestamp">
                            Signed on: {{ @$quotation->signature->updated_at }}
                        </div>
                    @endif
                    <br>
                    @if ($quotation->status == 1)
                        {{ @$quotation->approvedBy->name }}<br>
                        {{ @$quotation->approvedBy->employee->employementDetail->designation->name }}<br>
                        Cell: {{ @$quotation->approvedBy->employee->office_phone }}
                    @else
                        {{ @$quotation->createdBy->name }}<br>
                        {{ @$quotation->createdBy->employee->employementDetail->designation->name }}<br>
                        Cell: {{ @$quotation->createdBy->employee->office_phone }}
                    @endif
                </div>
            </div>

            <table class="mt-20" style="border: 1px solid black; page-break-inside: avoid; width: 100%;">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;" width="1%">SN</th>
                        <th style="border: 1px solid black;" width="45%">Product Description</th>
                        @if (!$withoutImage)
                            <th style="border: 1px solid black;" width="5%">Photo</th>
                        @endif
                        <th style="border: 1px solid black;" width="5%">Quantity</th>
                        <th style="border: 1px solid black;" width="5%">Price</th>
                        <th style="border: 1px solid black;" width="5%">Unit Discount</th>
                        <th style="border: 1px solid black;" width="5%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contentHightRem = 625;
                        $dd = [];
                    @endphp
                    @foreach ($quotation->quotationDetails as $key => $quotationDetail)
                        @php
                            $paginator = new Modules\Sales\Services\HtmlPaginatorService($contentHightRem); // 1000px height per page
                            $description =
                                '<div style="font-weight: bold;">' .
                                $quotationDetail->product->name .
                                '</div>
                                    <div>
                                        <div style="max-width: 272px;overflow: hidden;">Features: ' .
                                            $paginator->removeStyleAttributesRegex($quotationDetail->product?->description??"") .
                                '</div>
                                        <div>Model: ' .
                                $quotationDetail->product->model .
                                '</div>
                                        <div>Brand: ' .
                                optional($quotationDetail->product->brand)->name .
                                '</div>
                                        <div>Manufacturer: ' .
                                optional(optional($quotationDetail->product->brand)->supplier)->company_name .
                                '</div>
                                    </div>';
                            // 2. Initialize Paginator
                       

                             


                        // 3. Split Content
                       $result =  $paginator->paginate($description);
// dd($result);
                        $pages = $result['pages'];
                        $remainingHeight = $result['remainingHeight']; // Available height 
                        if($remainingHeight > 50){
                            $contentHightRem = $remainingHeight;
                        }else{
                            $contentHightRem = 625;
                        }

                        $dd[] = $result;
                        // dd($pages );
                        // 4. Loop through pages
                        $parted = [];
                        foreach ($pages as $page) {
                            $parted[] = $page;
                            }
                            @endphp

                        <tr style="border: 1px solid black; page-break-inside: avoid; width: 100%;s" >
                            <td style="border: 1px solid black; text-align: center;">{{ $key + 1 }}</td>
                            <td style="border: 1px solid black;">{!!$pages[0]!!}</td>
                            @if(!$withoutImage)
                                <td style="border: 1px solid black; text-align: center;">
                                    @if ($quotationDetail->product->profile_image_upload)
                                        <img src="{{ s3FileToBase64($quotationDetail->product->profile_image_upload) }}"
                                            alt="Product Image" class="product-image">
                                    @else
                                        N/A
                                    @endif
                                </td>
                            @endif

                            <td style="border: 1px solid black; text-align: center;">
                                {{ numberFormat($quotationDetail->quantity) }}
                                {{ $quotationDetail->product->unit->name }}
                            </td>

                            <td style="border: 1px solid black; text-align: right;">
                                {{ numberFormat($quotationDetail->price) }}
                            </td>
                            <td style="border: 1px solid black; text-align: right;">
                                {{ numberFormat($quotationDetail->unit_discount) }}
                            </td>
                            <td style="border: 1px solid black; text-align: right;">
                                {{ numberFormat($quotationDetail->amount) }}
                            </td>
                        </tr>
                        @if (count($parted) > 1)
                            @for ($i = 1; $i < count($parted); $i++)
                                <tr >
                                    <td style="border: 1px solid black; text-align: center;"></td>
                                    <td style="border: 1px solid black ;">{!! $parted[$i] !!}</td>
                                    @if (!$withoutImage)
                                        <td style="border: 1px solid black; text-align: center;"></td>
                                    @endif
                                    <td style="border: 1px solid black;"></td>
                                    <td style="border: 1px solid black; text-align: right;"></td>
                                    <td style="border: 1px solid black; text-align: right;"></td>
                                    <td style="border: 1px solid black; text-align: right;"></td>
                                </tr>
                            @endfor
                        @endif
                    @endforeach
                    {{-- @dd($dd) --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="{{ $withoutImage ? 6 : 7 }}" class="bold">Note: {{ $quotation->remarks }}</td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align: right" class="mt-20">
                <p>Total Amount: <strong>{{ numberFormat($quotation->total_amount) }}</strong></p>
                <p>Discount: <strong>{{ numberFormat($quotation->discount) }}</strong></p>
                <p><strong>Net Amount: {{ numberFormat($quotation->total) }}</strong></p>
            </div>

            <div class="terms ">
                <table class="terms-table">
                    <tr>
                        <td colspan="3"><h3>TERMS & CONDITIONS</h3></td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Payment</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->payment !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Payment Method</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->payment_method !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>TAX & VAT</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->tax_vat !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Installation</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->installation !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Training</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->training !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Warranty</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->warranty !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Buyer's responsibility</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->buyers_responsibility !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Validity</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->validity !!}</td>
                    </tr>
                    <tr>
                        <td width="15%"><strong>Delivery Info</strong></td>
                        <td width="5%">:</td>
                        <td>{!! $quotation->quotationTerms->delivery_info !!}</td>
                    </tr>
                </table>
            </div>

            <div class="signature mt-20">
                <p>Thanks & Regards,</p>
                <p>Authorized<br>
                    Mohammad Ali<br>
                    Administrative Manager(Admin)<br>
                    Cell: 01404003500</p>
            </div>
        </div>
    </div>
</body>

</html>
