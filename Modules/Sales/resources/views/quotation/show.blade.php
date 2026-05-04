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
            margin-top: 50px; 
            margin-bottom: 70px;
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
            .left-sidebar {
                background-color: #2f5597 !important;
                -webkit-print-color-adjust: exact !important;
            }
        }

        header {
            position: fixed;
            top: -50px;
            left: -40px;
            right: -40px;
            height: 50px;
            background-color: #fff;
            text-align: center;
            line-height: 1.4;
            z-index: 1000;
        }

        .header {
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        footer {
            position: fixed;
            bottom: -80px; 
            left: -40px;
            right: -40px;
            height: 80px; 
            background-color: #fff;
            text-align: center;  
            z-index: 1000;
        }

        .content {
            margin-top: -50px;
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
            margin: 2px 0;
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
            margin: 5px 0;
            border: none;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .terms-table th,
        .terms-table td {
            border: none;
            padding: 5px !important;
            margin: 0;
            vertical-align: top;
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
        .sidebar {
            position: fixed;
            top: 110px;
            bottom: 80px;
            left: -40px;
            width: 120px;
            background: #fff;
        }

        .terms {
            page-break-before: always;
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
        @include('partials._for_quotation_footer')
    </footer>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        @include('partials._for_quotation_sidebar')
    </aside>


    <div class="content" style="padding-left:15px;">
        <div class="catalog-container">
            <div style="height: 800px;margin-top: -60px; ">
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
                    
                    <br><br><br>
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

            <table class="mt-20" style="border: 1px solid black; page-break-inside: avoid; width: 100%;  ">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th class="text-center" style="border: 1px solid black;" width="1%">SN</th>
                        <th class="text-center" style="border: 1px solid black;" width="45%">Description of Goods</th>
                        @if (!$withoutImage)
                            <th class="text-center" style="border: 1px solid black;" width="24%">Photo</th>
                        @endif
                        <th class="text-center" style="border: 1px solid black;" width="5%">Quantity</th>
                        <th class="text-center" style="border: 1px solid black;" width="9%">Price</th>
                        <th class="text-center" style="border: 1px solid black;" width="7%">Unit Discount</th>
                        <th class="text-center" style="border: 1px solid black;" width="9%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contentHightRem = 625;
                        $dd = [];

                    @endphp
                 
                    @foreach ($quotation->quotationDetails as $key => $quotationDetail) 

                        <tr style="border: 1px solid black; page-break-inside: avoid; width: 100%;s" >
                            <td style="border: 1px solid black; text-align: center;">{{ $key + 1 }}</td>

                            <td style="border: 1px solid black;">
                                <div style="font-weight: bold;font-size:11px;">
                                    {{-- {{ $quotationDetail->product->name   }}  --}}
                                    {{  $quotationDetail->product->withoutModelSuffix()->name }}
                                </div><br>
                                <div style="font-size:9px;"> 
                                    Model: {{ $quotationDetail->product->model}} 
                                </div>
                                <div style="font-size:9px;"> 
                                    Brand:{{ optional($quotationDetail->product->brand)->name  }} 
                                </div>
                                {{-- <div style="font-size:9px;"> 
                                    Manufacturer:{{ optional(optional($quotationDetail->product->brand)->supplier)->company_name }}
                                </div> --}}
                                <div style="font-size:9px;"> 
                                    Manufacturer:{{ $quotationDetail->product->product_origin }}
                                </div>

                                <div style="font-size:9px;">   
                                    @if(!empty($quotationDetail->qr))
                                        <img src="data:image/png;base64,{{ rtrim($quotationDetail->qr) }}" style="width:60px; height:60px;">
                                    @endif
                                </div>
                           

                                
                            </td>
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
                                {{ number_format($quotationDetail->quantity) }}
                                {{ $quotationDetail->product->unit->name }}
                            </td>

                            <td style="border: 1px solid black; text-align: right;">
                                {{ number_format($quotationDetail->price,2) }}
                            </td>
                            <td style="border: 1px solid black; text-align: right;">
                                {{ number_format($quotationDetail->unit_discount,2) }}
                            </td>
                            <td style="border: 1px solid black; text-align: right;">
                                {{ number_format($quotationDetail->amount,2) }}
                            </td>
                        </tr>

                        {{-- every 7 rows page break --}}
                        @if(($key + 1) % 7 == 0)
                            </tbody>
                            </table>

                            <div style="page-break-after: always;"></div>

                        <table   style="border: 1px solid black; page-break-inside: avoid; width: 100%;  ">
                                    <thead>
                                        <tr style="border: 1px solid black;">
                                            <th class="text-center" style="border: 1px solid black;" width="1%">SN</th>
                                            <th class="text-center" style="border: 1px solid black;" width="45%">Description of Goods</th>
                                            @if (!$withoutImage)
                                                <th class="text-center" style="border: 1px solid black;" width="24%">Photo</th>
                                            @endif
                                            <th class="text-center" style="border: 1px solid black;" width="5%">Quantity</th>
                                            <th class="text-center" style="border: 1px solid black;" width="9%">Price</th>
                                            <th class="text-center" style="border: 1px solid black;" width="7%">Unit Discount</th>
                                            <th class="text-center" style="border: 1px solid black;" width="9%">Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                        @endif
    
                     
                    @endforeach
                    {{-- @dd($dd) --}}
                </tbody>
                <tfoot style="border:none;"> 
                    <tr style="border:none;">
                        <td colspan="{{ $withoutImage ? 5 : 6 }}" 
                            class="bold text-right" 
                            style="border:none; padding:0; margin:0;">
                            Total Amount :
                        </td>
                        <td class="bold text-right" style="border:none; padding:0; margin:0;">
                            {{ number_format($quotation->total_amount,2) }}
                        </td>
                    </tr>

                    <tr style="border:none;">
                        <td colspan="{{ $withoutImage ? 5 : 6 }}" 
                            class="bold text-right" 
                            style="border:none; padding:0; margin:0;">
                            Discount :
                        </td>
                        <td class="bold text-right" style="border:none; padding:0; margin:0;">
                            {{ number_format($quotation->discount,2) }}
                        </td>
                    </tr>

                    <tr style="border:none;">
                        <td colspan="{{ $withoutImage ? 5 : 6 }}" 
                            class="bold text-right" 
                            style="border:none; padding:0; margin:0;">
                            Net Amount :
                        </td>
                        <td class="bold text-right" style="border:none; padding:0; margin:0;">
                            {{ number_format($quotation->total,2) }}
                        </td>
                    </tr>
                    
                </tfoot>
            </table>

            <div style="text-align: left" class="mt-20">
                @if(!empty($quotation->remarks))
                    <p>Note: {{ $quotation->remarks }}</p>
                @endif
            </div>

            <div class="terms ">
                <table class="terms-table" style="font-size:11px;margin-top: -60px; ">
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
                        <td width="15%"><strong>Buyer's Responsibility</strong></td>
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
    </div>
</body>

</html>
