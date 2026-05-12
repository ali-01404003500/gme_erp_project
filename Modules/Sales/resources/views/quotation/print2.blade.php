<!-- views/sales/quotation/show.blade.php (Web View Blade) -->
@section('title', 'Quotation Information Details')
@section('description', 'Quotation Information Details')
@extends('layout.app')
@section('page-head')
    <style>
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
        }

        .header-skew-container {
            position: relative;
            width: 100%;
            height: 100px;
        }

        .header-skew {
            position: absolute;
            top: 10px;
            left: 0;
            transform: skewX(35deg);
        }

        .content-table {
            margin-top: 0px;
            width: 100%;
            border-collapse: collapse;
        }


        td {
            vertical-align: top;
        }

        .footer {
            text-align: center;
        }

        .contact-info,
        .terms,
        .signature {
            margin: 20px 0;
        }

     
        
        p {
            margin: 10px 0;
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


        h1 {
            font-size: 55px;
        }

        .signature {
            max-height: 80px;
        }

        .signature-display {
            height: 80px;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        .signature-display img {
            max-height: 50px;
            max-width: 200px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 5px;
        }

        .signature-timestamp {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        .signature-placeholder {
            color: #999;
            font-style: italic;
            font-size: 11px;
        }

        /* Fix product description spacing */
        .product-description {
            word-wrap: break-word;
            white-space: normal;
        } 
  

        .product-table,.terms {
            page-break-before: always;
        } 
 
        .blue-left {
            width: 20%;
            height: 100px;
            border-left: 1px solid white !important;
            border-bottom: 1px solid white !important;
            border-right: 2px solid rgb(21, 51, 133);
            border-top: 2px solid rgb(21, 51, 133);
        }

        .blue-bottom {
            width: 80%;
            height: 100px;
            border-right: 1px solid white !important;
            border-top: 1px solid white !important;
            border-left: 2px solid rgb(21, 51, 133);
            border-bottom: 2px solid rgb(21, 51, 133);
        }

        .com-logo img {
            max-width: 80px;
            max-height: 80px;
            margin-left: 15px; 
            margin-right: 15px; 
            margin-top: 15px; 
            margin-bottom: 20px; 
        }

        .com-info {
            text-align: left;
            padding-left: 10px;
        }

        .com h1 {
            margin: 0;
            font-size: 29px;
        }

        .com p {
            color: rgb(226, 35, 35);
            margin: 5px 0 0 5px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        td {
            vertical-align: top;
        }
        .contact-info, .terms, .signature {
            margin: 20px 0;
        }
      
  
        p {
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
       
        .terms-table th, .terms-table td {
            padding: 10px 0;
            border: none;
        }
        .terms h3 {
            margin: 20px 0 10px;
        }
        .terms p {
            margin: 10px 0 20px;
        }
        h1{
            font-size: 45px;
        }
        .signature{
            max-height: 100px;
        }
        .page-number {
            position: absolute;
            right: 40px;
            bottom: 100px;
            font-size: 11px;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
  
        .content {
            margin-top: 10px; /* Adjust based on header height */
            margin-bottom: 20px; /* Adjust based on footer height */
            line-height: 1.5;
            
        }

  
        @media print {

            
            @page { 
                counter-increment: page;
                margin: 150px 40px 120px 40px;
            }

            
            body * {
                visibility: hidden;
            }

            #printableArea, #printableArea * {
                visibility: visible; 
            }

        
            /* Header */
            .print-header {
                position: fixed;
                top: 130px;  
                left: 0;
                right: 0;
                width: 100%;
            }

            
            /* Footer */
            .print-footer {
                position: fixed;
                bottom: 100px;
                left: 0;
                right: 0;
            }
   

        }
    </style>
@endsection
@section('header')
    <!-- Header for print -->
    <div class="print-header ">
        <div class="header-skew-container">
            <table class="header-skew">
                <tr style="border: none;">
                    <td class="blue-left"></td>
                    <td class="blue-bottom"></td>
                </tr>
            </table>
        </div>
        <table class="content-table" style="border: none;"> 
            
            <tr>
                @php
                    $default_company_logo = 'assets/img/gme-logo.png';
                @endphp
                <td style="background:#2f5597;  vertical-align:middle; border:none;padding-left:0.371in;"  > </td>
                <td class="com-logo" style="border: none;">
                    <img src="{{ s3FileToBase64($company_info->company_logo) ?? url($default_company_logo) }}"
                        alt="{{ $company_info->company_logo }}">
                </td>
                <td class="com-info" style="border: none;">
                    <div class="com">
                    
                        <h1 class="pdf-title" style="margin:0; font-weight:bold; color: rgb(13, 13, 92);font-size:34px;  line-height: 1.2;  font-family: 'Times New Roman', serif;" >
                            Global Medical Engineering (BD) Ltd.
                        </h1>

                        <p class="pdf-subtitle" style="margin:3px 0 0 0;color: rgb(226, 150, 35); font-size: 13px!important; line-height: 1.2;  font-family: 'Times New Roman', serif;">
                            Provider of Medical Equipment and Solutions for Hospitals, Diagnostics, Clinics and Healthcare Institutes.
                        </p>

                        
                    </div>
                </td>
            </tr>
        </table>
        
    </div>
@endsection

@section('footer')
       <!-- Footer for print -->
        <div class="print-footer ">
                <div class="page-number"></div>
                
            <table width="100%" style="margin:0; padding:0;border-collapse:collapse; border:0; border-top:1px solid #052e86; font-size:12px; line-height:1.2; " cellspacing="0" cellpadding="0" >
                <tr>
                    <td style="background:#2f5597;  vertical-align:middle; border:none;padding-left:0.4in;"  > </td>
                    <!-- 60% -->
                    <td width="60%" valign="top" style="border-collapse:collapse; border:0;background:white; ">
                        <table width="100%" style="border-collapse:collapse; border:0; font-size:10px; line-height:1.2; border-right:1px solid #eba308;" cellspacing="0" cellpadding="0">
                            <tr>
                                <th style="border:0; padding:0; margin:0; text-align:left; font-size:12px; color:blue;">
                                    Corporate Office:
                                </th>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;font-size:11px">
                                    House # 17/2, Topkhana Road (2nd Floor), Dhaka-1000, Bangladesh
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;">
                                    Hotline : +88 09678 020555, +88 01404 003500, Order : +88 01404 003501
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;font-size:9.5px">
                                    Service : +88 01404 003535, Email : info@gmebd.com, Web : www.gmebd.com
                                </td>
                            </tr>
                        </table>
                    </td> 

                    <!-- 40% -->
                    <td width="40%" valign="top" style="border-collapse:collapse; border:0;background:white; ">
                        <table width="100%" style="border-collapse:collapse; border:0; font-size:10px; line-height:1.2; " cellspacing="0" cellpadding="0">
                            <tr>
                                <th style="border:0; padding:0; margin:0; text-align:left; font-size:12px; color:blue;">
                                    China Office:
                                </th>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;">
                                    190# longping Road, Huate Industrial Zone
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;">
                                    Longgang District, Shenzhen
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0; padding:0; margin:0;">
                                    GuangDong China 518116
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
        </div>
@endsection

@section('content')
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
                                        {{ trans('menu.quotation-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <button onclick="window.print()" class="btn btn-info btn-sm" style="margin-left: 5px;">
                                <i class="fa fa-print"></i> Print PDF with Description
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="printableArea"  style=" "> 
                <!-- Your original content starts here -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="catalog-container">

                                <div>
                                    <!-- resources/views/sales/quotation/show.blade.php -->

                                    {{-- ... your existing head, styles, etc ... --}}

                                    <div class="contact-info">
                                        <p>Ref:
                                            GMEL/Equip/Quotation/{{ date('Y') }}-{{ str_pad(date('m'), 2, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <p>Date: {{ \Carbon\Carbon::parse($quotation->date)->format('d F, Y') }}</p>

                                        <p>To,<br>
                                        <div contenteditable="true" id="editable-customer-info"
                                            style="display: inline-block; min-width: 300px; outline: none;">
                                            {{ $quotation->quotationTerms->quotation_to }}<br>
                                            {{ $quotation->customer_name }}<br>
                                            {{ $quotation->area }}, {{ $quotation->address }}<br> 
                                            <p>ATTN: {{ $quotation->quotationTerms->attn  }}<br>
                                            Cell: {{ $quotation->quotationTerms->attn_cell }}<br>
                                            E-mail: {{ $quotation->quotationTerms->email }}</p>

                                        </div>
                                        </p>

                                    </div>

                                    {{-- Rest of your content (subject, table, terms, etc.) remains the same --}}

                                    <div class="subject">
                                        <p><strong>Subject:</strong> Price Offer for Hospital/ Diagnostic Equipment</p>
                                    </div>

                                    <div class="content">
                                        <p>Dear Sir,</p>
                                        <p>I hope this letter finds you well. I am reaching out to present you with a
                                            price offer
                                            for our product/service. We are confident in our ability to deliver high
                                            quality, and
                                            therefore, we have tailored a competitive price proposal that promises not
                                            merely
                                            adequacy but the best.</p>
                                        <p>We wholeheartedly believe that our offer would be incredibly beneficial for
                                            you. If you
                                            are interested in our proposition, we would be delighted to furnish further
                                            details and
                                            discuss potential adjustments to suit your needs better.</p>
                                        <p>Once again, I appreciate your valuable time considering our offer. I am
                                            looking forward
                                            to your positive response. Please do not hesitate to raise any queries
                                            regarding our
                                            proposal.</p>
                                        <p>Best Regards,</p>

                                        @include('partials._seek_sign', [
                                            'model' => $quotation,
                                            'field' => 'signature',
                                        ])
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
                                {{-- ================= TABLE ================= --}}
                                <table class="product-table "   >
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Description</th>
                                            <th>Photo</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($quotation->quotationDetails as $key => $quotationDetail)
                                            <tr>
                                                <td style="text-align:center">{{ $key + 1 }}</td>

                                                <td class="product-description">
                                                    <b>{{ $quotationDetail->product->name }}</b><br>

                                                    {!! $quotationDetail->product->description !!}<br>

                                                    Model: {{ $quotationDetail->product->model }}<br>
                                                    Brand: {{ optional($quotationDetail->product->brand)->name }}<br>
                                                </td>

                                                <td style="text-align:center">
                                                    @if ($quotationDetail->product->profile_image_upload)
                                                        <img src="{{ $quotationDetail->product->profile_image_upload }}"
                                                            width="80" height="80">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td style="text-align:center">
                                                    {{ numberFormat($quotationDetail->quantity) }}
                                                </td>

                                                <td style="text-align:right">
                                                    {{ numberFormat($quotationDetail->price) }}
                                                </td>

                                                <td style="text-align:right">
                                                    {{ numberFormat($quotationDetail->unit_discount) }}
                                                </td>

                                                <td style="text-align:right">
                                                    {{ numberFormat($quotationDetail->amount) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                Note: {{ $quotation->remarks }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                {{-- ================= TOTAL ================= --}}
                                <div style="text-align:right; margin-top:10px;">
                                    <p>Total: {{ numberFormat($quotation->total_amount) }}</p>
                                    <p>Discount: {{ numberFormat($quotation->discount) }}</p>
                                    <p><b>Net: {{ numberFormat($quotation->total) }}</b></p>
                                </div> 

                                {{-- ================= TERMS ================= --}}
                                <div class="terms ">
                                    <table class="terms-table" style="font-size:11px; ">
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
 
                                {{-- ================= SIGNATURE ================= --}}
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
                    </div>
                </div>
            </div>
        </div>
    </div>


    
@endsection
 
