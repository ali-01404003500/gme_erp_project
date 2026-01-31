@section('title', 'Quotation Information Details')
@section('description', 'Quotation Information Details')
@extends('layout.app')
@section('page-head')


@endsection
@section('content')
    <div class="container-fluid">
        <style id ="custom-style">
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

            /* .com-logo {
                    width: 13%;
                }

                .com-info {
                    text-align: left;
                    width: 87%;
                }

                .com h1 {
                    margin-left: 100px;
                    font-size: 46px;
                }

                .com p {
                    color: rgb(226, 35, 35);
                    margin: 5px 0 0 5px;
                    margin-left: 105px;
                    font-size: 16px;
                } */

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
                                        {{ trans('menu.quotation-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex justify-content-between">
                            <a href="{{ route('sales.quotations.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            <div style="margin-left: 5px;"></div>
                            <button onclick="generatePDF()"
                                class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-print"></i> Print PDF</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="width: 100%">
                <div class="col-md-6">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.quotation-menu-title') }}
                    </h4>
                </div>
            </div>
            <div style="background: white;">
                <div class="row pdf-printer">
                    <div class="col-md-12" style="padding-bottom: 1px">

                    </div>
                    <div class="card-header">
                        <div class="catalog-container">

                            {{-- Company title Start --}}

                            {{-- <header>
                                <div class="header">
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
                                            <td class="com-logo" style="border: none;">
                                                <img src="{{ s3FileToBase64($company_info->company_logo) }}"
                                                    alt="{{ $company_info->company_logo }}">
                                            </td>
                                            <td class="com-info" style="border: none;">
                                                <div class="com">
                                                    <h1>{{ $company_info->company_name }}</h1>
                                                    <p>{{ $company_info->company_bio }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </header> --}}
                            {{-- Company title End --}}

                            <div style="height: 800px;">
                                <div class="contact-info">
                                    <p>GMEL/Equip/Quotation/{{ date('Y') }}-{{ str_pad(date('m'), 2, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p>Date: 25 June, 2024</p>
                                    <p>To,<br>
                                    <div contenteditable="true">
                                        {{ $quotation->quotationTerms->quotation_to }}<br>
                                        {{ $quotation->customer_name }}<br>
                                        {{ $quotation->area }},{{ $quotation->address }}<br>
                                        E-mail:<br>
                                        Cell: </p>
                                        <p>ATTN:<br>
                                            Cell:</p>
                                    </div>
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
                                    <img class="signature"
                                        src="{{ s3FileToBase64(optional(optional($quotation->approvedBy)->employee)->signature) }}"
                                        alt=""><br>
                                    <p>{{ optional($quotation->approvedBy)->name }}<br>
                                        {{ optional(optional(optional($quotation->approvedBy)->employee)->employementDetail)->designation }}<br>
                                        Cell: {{ optional(optional($quotation->approvedBy)->employee)->office_phone }}</p>
                                </div>
                            </div>

                            {{-- <table style="width: 100%; padding: 0; border-spacing: 0; border: none; margin-top: 40px">
                                <tr style="padding: 0; border: none;">
                                    <td style="width:40%; padding: 0; border: none;">
                                        <table style="padding: 0; border: none;">
                                            <tr style="padding: 0; border: none;">
                                                <th style="padding: 0; border: none;"><strong>China Office:</strong></th>
                                            </tr>
                                            <tr style="padding: 0; border: none;">
                                                <td style="padding: 0; border: none;">190#longping Road, Huate Industrial
                                                    Zone<br>
                                                    Longgang District, Shenzhen<br>
                                                    GuangDong China 518116</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width:60%; padding: 0; border: none;">
                                        <table style="padding: 0; border: none;">
                                            <tr style="padding: 0; border: none;">
                                                <th style="padding: 0; border: none;"><strong>Corporate Office:</strong>
                                                </th>
                                            </tr>
                                            <tr style="padding: 0; border: none;">
                                                <td style="padding: 0; border: none;">17/2 Topkhana Road│2nd
                                                    Floor│Dhaka-1000│Bangladesh<br>
                                                    Tel: +88 02 9564225, Fax: +88 02 9576881, Cell: +88 01852 278200<br>
                                                    Email: info@gmebd.com│gmebd@hotmail.com│www.gmebd.com</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table> --}}
                            
                            <table style="border: 1px solid black; page-break-inside: avoid;">
                                <tr style="border: 1px solid black;"  >
                                    <th style="border: 1px solid black;" width="1%">SN</th>
                                    <th style="border: 1px solid black;" width="70%">Product Description</th>
                                    <th style="border: 1px solid black;">Quantity</th>
                                    <th style="border: 1px solid black;">Price</th>
                                    <th style="border: 1px solid black;">Unit Discount</th>
                                    <th style="border: 1px solid black;">Amount</th>
                                </tr>
                                <tbody>

                                    @foreach ($quotation->quotationDetails as $key => $quotationDetail)

                                        
                                            <tr style="page-break-inside: avoid;">
                                                <td>{{ $key + 1 }}</td>
                                                <td style="border: 1px solid black; page-break-inside: avoid;" colspan="5">
                                                    {{ $quotationDetail->product->name }}</td>
                                            </tr>
                                            <tr style="border: 1px solid black;page-break-inside: avoid;">
    
                                                <td>
                                                    
                                                </td>
                                             
                                                <td style="border: 1px solid black;" width="70%">
                     
                                                    
                                               <div>
                                                <div class="product-description">
                                                    {!! $quotationDetail->product->description !!}
                                                </div>
                                                <br>
                                                Model: {{ $quotationDetail->product->model }}
                                                <br>

                                                Brand: {{ optional($quotationDetail->product->brand)->name }}
                                                <br>
                                                Manufacturer:
                                                {{ optional(optional($quotationDetail->product->brand)->supplier)->company_name }}
                                               </div>
    
                                                </td>
                                                <td style="border: 1px solid black;">
                                                    {{ numberFormat($quotationDetail->quantity) }}
                                                    {{ $quotationDetail->product->unit->name }}</td>
                                                <td style="border: 1px solid black;">
                                                    {{ numberFormat($quotationDetail->price) }}</td>
                                                <td style="border: 1px solid black;">
                                                    {{ numberFormat($quotationDetail->unit_discount) }}</td>
                                                </td>
                                                <td style="border: 1px solid black;">
                                                    {{ numberFormat($quotationDetail->amount) }}</td>
                                            </tr>
                                        
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">Note: {{ $quotation->remarks }}</td>
                                    </tr>
                                </tfoot>

                            </table>

                            <div style="text-align: right">
                                <p>Total Amount: <strong>{{ numberFormat($quotation->total_amount) }}</strong></p>
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
                            </div>

                            <div class="signature">
                                <p>Thanks & Regards,</p>
                                <p>Authorized<br>
                                    Mohammad Ali<br>
                                    Administrative Manager(Admin)<br>
                                    Cell: 01404003500</p>
                            </div>

                            {{-- <footer>
                                <div class="footer">
                                    <table style="width: 100%; padding: 0; border-spacing: 0; border: none;">
                                        <tr style="padding: 0; border: none;">
                                            <td style="width:40%; padding: 0; border: none;">
                                                <table style="padding: 0; border: none;">
                                                    <tr style="padding: 0; border: none;">
                                                        <th style="padding: 0; border: none;"><strong>China Office:</strong>
                                                        </th>
                                                    </tr>
                                                    <tr style="padding: 0; border: none;">
                                                        <td style="padding: 0; border: none;">190#longping Road, Huate
                                                            Industrial Zone<br>
                                                            Longgang District, Shenzhen<br>
                                                            GuangDong China 518116</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="width:60%; padding: 0; border: none;">
                                                <table style="padding: 0; border: none;">
                                                    <tr style="padding: 0; border: none;">
                                                        <th style="padding: 0; border: none;"><strong>Corporate Office:</strong>
                                                        </th>
                                                    </tr>
                                                    <tr style="padding: 0; border: none;">
                                                        <td style="padding: 0; border: none;">17/2 Topkhana Road│2nd
                                                            Floor│Dhaka-1000│Bangladesh<br>
                                                            Tel: +88 02 9564225, Fax: +88 02 9576881, Cell: +88 01852 278200<br>
                                                            Email: info@gmebd.com│gmebd@hotmail.com│www.gmebd.com</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </footer> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('sales.quotations.pdf') }}" method="POST" class="d-none" id="pdf-form">
        @csrf
        <input type="hidden" name="html" id="html">
    </form>
    
@endsection

@section('page_scripts')
<script>

function contentMax(node, max){
    let countLength = 0;
    const removed = $(node).clone().empty();
    const newNode = $(node);
    newNode.find(">*").each(function(){
        const len= $(this).html().length;
        if((len + countLength) > max ){
            if(len > max+countLength){
                const {part1, part2}= contentMax($(this).clone(), max-countLength);
                newNode.append(part1);
                removed.append(part2);
            }else{
                removed.append($(this).clone());
            }
            $(this).remove();
        }
        countLength += len;
    });

    if(removed[0].tagName.toLowerCase() == "ol"){
        const firstCount = newNode.find("li").length;
        removed.attr("start", firstCount+1);
    }

    return {part1: newNode, part2: removed};
}
    function indexOfNextClosingTag(str, startPos) {
        const regex = /<\/|>/g; // Regular expression to match '</' or '>'
        regex.lastIndex = startPos; // Set the starting position for the search

        const match = regex.exec(str); // Execute the regex search

        if (match) {
            return match.index; // Return the index of the match
        } else {
            return -1; // Return -1 if no match is found
        }
    }
    function indexOfLastOpeningTag(str, endPos) {
        const regex = /<(div|p|li)[^>]*>/g; // Regular expression to match any opening tag of div, p, or li
        let lastIndex = -1;
        let match;

        // Loop through all matches to find the last occurrence
        while ((match = regex.exec(str.substring(0, endPos))) !== null) {
            lastIndex = match.index - match[0].length;
        }

        return lastIndex;
    }

    function splitContent(selector) {
            var content = $(selector).html(); // Get the HTML content as a string

            // Calculate the midpoint
            var midPoint = Math.floor(content.length / 2);
            var midPoint = indexOfLastOpeningTag(content, midPoint)-1;
            // Split the content into two halves
            var firstHalf = content.substring(0, midPoint);
            var secondHalf = content.substring(midPoint);
            console.log({firstHalf, secondHalf});
            // Ensure all tags in the first half are properly closed
            var openTags = [];
            var closedTags = [];
            var openingTagsWithAttributes = []; // To store tags with styles and classes

            // Regex to find all tags
            var tagRegex = /<[^>]+>/g;

            // Find all tags in the first half
            var tags = firstHalf.match(tagRegex);

            if (tags) {
                tags.forEach(function(tag) {
                    if (tag.startsWith("</")) {
                        // This is a closing tag
                        var tagName = tag.match(/<\/(\w+)/)[1];
                        closedTags.push(tagName);
                    } else if (!tag.endsWith("/>")) {
                        // This is an opening tag
                        var tagName = tag.match(/<(\w+)/)[1];
                        openTags.push(tagName);
                        openingTagsWithAttributes.push(tag); // Save the whole tag with attributes
                    }
                });
            }

            // Balance tags in the first half
            while (openTags.length && closedTags.length && openTags[openTags.length - 1] === closedTags[closedTags.length - 1]) {
                openTags.pop();
                closedTags.pop();
                openingTagsWithAttributes.pop(); // Remove the corresponding opening tag with attributes
            }

            // Close remaining open tags in the first half and add opening tags with styles to the second half
            openTags.reverse().forEach(function(tag) {
                firstHalf += `</${tag}>`;
                // Find the opening tag with styles or classes for this tag
                var openingTagWithAttributes = openingTagsWithAttributes.pop();
                secondHalf = openingTagWithAttributes + secondHalf;
            });
            // Insert the balanced halves into their respective containers
            return [firstHalf, secondHalf];
        }
    async function  generatePDF() {
        const html = $(".pdf-printer").clone()
       
        html.find('table:first .product-description').each(function() {
            const description = $(this);
            let secondHalf = "";
            if (description.html().length > 2000) {
                // const splited = splitContent(description);
                // secondHalf = $(`<div>${splited[1]}</div>`);
                // description.html(splited[0]);
                const {part1, part2}=contentMax(description.clone(), 1500);
                description.html(part1);
                secondHalf = part2;
            }
            if (secondHalf !== ""){
                console.log("Second Half",secondHalf);
                // let secountHtml = $("<div></div>");
                // secondHalf.each(function() {
                //     secountHtml.append($(this).clone())
                // });
                $(this).closest('tr').after(`<tr>
                            <td style="border: 1px solid black;page-break-inside: avoid;"></td>
                            <td style="border: 1px solid black;page-break-inside: avoid;">${secondHalf.html()}</td>
                            <td style="border: 1px solid black;page-break-inside: avoid;"></td>
                            <td style="border: 1px solid black;page-break-inside: avoid;"></td>
                            <td style="border: 1px solid black;page-break-inside: avoid;"></td>
                            <td style="border: 1px solid black;page-break-inside: avoid;"></td>
                        </tr>`);
            }
                
        });
        // return
        const footer = `@include('partials._for_pdf_footer')`;
        const header = `@include('partials._for_pdf_header', ['company_info' => $company_info])`;
        const customStyle = `
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

            .com-logo img {
                max-width: 80px;
                max-height: 80px;
                margin-left: 30px;
            }

            .com-info {
                text-align: left;
                padding-left: 25px;
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
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            .terms-table {
                width: 100%;
                margin: 20px 0;
                border: none;
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
        `;
        var val = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>PDF Document</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        font-size: 12px;
                    }
                    @page {
                        margin-top: 110px;
                        margin-bottom: 80px;
                        margin-left: 40px;
                        margin-right: 40px;
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
                    }
                    .content {
                        margin-top: 10px; /* Adjust based on header height */
                        margin-bottom: 20px; /* Adjust based on footer height */
                        line-height: 1.5;
                        
                    }

                    ${customStyle}
        
                </style>
            </head>
            <body>
                <header>
                    ${header}
                </header>
                <footer>
                    ${footer}
                    </footer>
                <div class="content" style="max-height: 1000px;">
                    ${html.html()}
                </div>
            </body>
            </html>
        `;
        $("#pdf-form #html").val(val);

        $("#pdf-form").submit()
    }

</script>
@endsection
