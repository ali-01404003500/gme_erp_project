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

        .product-card {
            display: flex;
            flex-wrap: wrap;
            margin-top: 28px;
            padding: 20px;
            border-radius: 10px;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
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
            margin-top: -80px;
            width: 100%;
            border-collapse: collapse;
        }

        .com-logo img {
            max-width: 100px;
            max-height: 100px;
            margin-left: 40px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .blue-left {
            width: 13%;
            height: 100px;
            border-left: 1px solid white !important;
            border-bottom: 1px solid white !important;
            border-right: 4px solid rgb(0, 0, 179);
            border-top: 4px solid rgb(0, 0, 179);
        }

        .blue-bottom {
            width: 87%;
            height: 100px;
            border-right: 1px solid white !important;
            border-top: 1px solid white !important;
            border-left: 4px solid rgb(0, 0, 179);
            border-bottom: 4px solid rgb(0, 0, 179);
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
    </style>
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
                            <a href="{{ route('sales.quotations.index') }}" class="btn px-20 btn-warning btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>
                            <button onclick="generatePDF(false)" class="btn btn-primary btn-sm mr-5"
                                style="margin-left: 5px;">
                                <i class="fa fa-print"></i> Print PDF (With Image)
                            </button>
                            <button onclick="generatePDF(true)" class="btn btn-info btn-sm" style="margin-left: 5px;">
                                <i class="fa fa-print"></i> Print PDF (Without Image)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.quotation-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="pdf-printer">
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

                                    <table style="border: 1px solid black; page-break-inside: avoid; width: 100%;">
                                        <thead>
                                            <tr style="border: 1px solid black;">
                                                <th style="border: 1px solid black;" width="5%">SN</th>
                                                <th style="border: 1px solid black;" width="35%">Product Description</th>
                                                <th style="border: 1px solid black;" width="10%">Photo</th>
                                                <th style="border: 1px solid black;" width="10%">Quantity</th>
                                                <th style="border: 1px solid black;" width="10%">Price</th>
                                                <th style="border: 1px solid black;" width="10%">Unit Discount</th>
                                                <th style="border: 1px solid black;" width="10%">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            @foreach ($quotation->quotationDetails->sortBy('id') as $key => $quotationDetail)
                                                <tr style="page-break-inside: avoid;">
                                                    <td
                                                        style="border: 1px solid black; text-align: center; vertical-align: top;">
                                                        {{ $key + 1 }}</td>
                                                    <td style="border: 1px solid black; vertical-align: top;"
                                                        class="product-description">
                                                        <div style="font-weight: bold;">
                                                            {{ $quotationDetail->product->name }}</div>
                                                        <div>
                                                            Features: {!! $quotationDetail->product->description !!}
                                                        </div>
                                                        <div>
                                                            Model: {{ $quotationDetail->product->model }}<br>
                                                            Brand:
                                                            {{ optional($quotationDetail->product->brand)->name }}<br>
                                                            Manufacturer:
                                                            {{ optional(optional($quotationDetail->product->brand)->supplier)->company_name }}<br>
                                                        </div>
                                                        
                                                    </td>
                                                    <td
                                                        style="border: 1px solid black; text-align: center; vertical-align: top;">
                                                        @if ($quotationDetail->product->profile_image_upload != null)
                                                            <img src="{{ $quotationDetail->product->profile_image_upload }}"
                                                                alt="Product Image" width="100" height="100">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td
                                                        style="border: 1px solid black; text-align: center; vertical-align: top;">
                                                        {{ numberFormat($quotationDetail->quantity) }}
                                                        {{ $quotationDetail->product->unit->name }}
                                                    </td>
                                                    <td
                                                        style="border: 1px solid black; text-align: right; vertical-align: top;">
                                                        {{ numberFormat($quotationDetail->price) }}
                                                    </td>
                                                    <td
                                                        style="border: 1px solid black; text-align: right; vertical-align: top;">
                                                        {{ numberFormat($quotationDetail->unit_discount) }}
                                                    </td>
                                                    <td
                                                        style="border: 1px solid black; text-align: right; vertical-align: top;">
                                                        {{ numberFormat($quotationDetail->amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="border: 1px solid black;">Note:
                                                    {{ $quotation->remarks }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div style="text-align: right">
                                        <p>Total Amount: <strong>{{ numberFormat($quotation->total_amount) }}</strong>
                                        </p>
                                        <p>Discount: <strong>{{ numberFormat($quotation->discount) }}</strong></p>
                                        <p><strong>Net Amount: {{ numberFormat($quotation->total) }}</strong></p>
                                    </div>

                                    <div class="terms" style="page-break-before: always;">
                                        <table class="terms-table">
                                            <tr>
                                                <h3>TERMS & CONDITIONS</h3>
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

                                        <div class="signature" style="margin-top: 40px;">
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
        </div>
    </div>

    <form action="{{ route('sales.quotations.pdf') }}" method="POST" class="d-none" id="pdf-form" target="_blank">
        @if (!request()->has('debug'))
            style="display:none"
        @endif>
        @csrf
        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
        <input type="hidden" name="without_image" id="without_image" value="0">
        <input type="hidden" name="edited_content" id="edited_content" value="">
    </form>
@endsection

@section('page_scripts')
    @stack('script')
    <script>
        function generatePDF(withoutImage = false) {
            // Get the edited customer info
            const editable = document.getElementById('editable-customer-info');
            const editedHtml = editable ? editable.innerHTML.trim() : '';

            // Set without_image flag
            document.getElementById('without_image').value = withoutImage ? '1' : '0';

            // Set edited content
            let editedInput = document.getElementById('edited_content');
            if (!editedInput) {
                editedInput = document.createElement('input');
                editedInput.type = 'hidden';
                editedInput.name = 'edited_content';
                editedInput.id = 'edited_content';
                document.getElementById('pdf-form').appendChild(editedInput);
            }
            editedInput.value = editedHtml;

            // Submit form
            document.getElementById('pdf-form').submit();
        }
    </script>
@endsection
