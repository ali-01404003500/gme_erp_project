@section('title', 'Sales Requisition Details')
@section('description', 'Sales Requisition Details')
@extends('layout.app')
@section('page-head')
            <style>
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
                    font-size: 12px;
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

                .sales-order-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .sales-order-info .left {
                    width: 55%;
                }
                
                .sales-order-info .right {
                    width: 45%;
                }

                .sales-order-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .sales-order-info th,
                .sales-order-info td {
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
                    margin-bottom: 10px;
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                .invoice-details .totals {
                    text-align: right;
                }

                .invoice-details .totals p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                     align-items: flex-end;
                    break-inside: avoid;
                }
            
                @media print {
                    footer {
                        display: flex;
                        justify-content: space-between;
                        align-items:flex-end;
                        break-inside: avoid;
                        margin-top: 20px;   
                    }
                }



                .bangla-text {
                    margin-top: 50px;
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
                                        {{ trans('menu.sales-requisition-view-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <button onclick="printWholePage()" class="btn btn-primary btn-sm"
                                style="margin-right: 5px;">Print</button>
                            @if (hasPermission('sales.sales-requisitions.index'))
                                <a href="{{ route('sales.sales-requisitions.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.sales-requisition-view-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-start">
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1">Title</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2" checked>
                                        <label class="custom-control-label" for="customCheck2">Payment Status</label>
                                    </div>

                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3">
                                        <label class="custom-control-label" for="customCheck3">VAT Status</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4">
                                        <label class="custom-control-label" for="customCheck4">AIT Status</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck5">
                                        <label class="custom-control-label" for="customCheck5">CW</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck6" checked>
                                        <label class="custom-control-label" for="customCheck6">Remarks</label>
                                    </div>

                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck8" checked>
                                        <label class="custom-control-label" for="customCheck8">Discount</label>
                                    </div>

                                </div>
                            </div>
                            <div id="print-content">
                                <header class="my-header">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <div style="width: 10%;">
                                            <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo">
                                        </div>
                                        <div style="width: 80%">
                                            <div style="text-align: center;">
                                                <h1>{{ $company_info->company_name }}</h1>
                                                <p>{{ $company_info->company_bio }}</p>
                                                <p>Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                                <p>Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                                                <p>e-mail : <a href="mailto:info@gmebd.com">info@gmebd.com</a> web: <a
                                                        href="http://www.gmebd.com">www.gmebd.com</a></p>
                                            </div>
                                        </div>
                                        <div style="width: 10%"></div>
                                    </div>
                                </header>

                                <section class="title">
                                    <h2>Sales Requisition Invoice Bill</h2>
                                </section>


                                <section class="sales-order-info">
                                    <div class="left">
                                        <table>
                                            <tr>
                                                <th>Requisition No</th>
                                                <td>:</td>
                                                <th>{{ $salesRequisition->invoice_id }}</th>
                                            </tr>
                                            <tr>
                                                <th>Customer name</th>
                                                <td>:</td>
                                                <th>{{ $salesRequisition->customer->company_name }}</th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td>:</td>
                                                <td>{{ $salesRequisition->customer->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>:</td>
                                                <td> {{ $salesRequisition->customer->phone }}</td>
                                            </tr>

                                        </table>
                                    </div>
                                    <div class="right">
                                        <table>

                                            <tr>
                                                <th>Date</th>
                                                <td>:</td>
                                                <th>{{ $salesRequisition->invoice_date }}</th>
                                            </tr>
                                            <tr>
                                                <th>Time</th>
                                                <td>:</td>
                                                {{-- Placeholder or actual time if available in created_at --}}
                                                <th>{{ date('h:i A', strtotime($salesRequisition->created_at)) }}</th>
                                            </tr>
                                            <tr>
                                                <th>Requisition By</th>
                                                <td>:</td>
                                                <th>{{ $salesRequisition->requisitionBy->name }}</th>
                                            </tr>
                                            <tr>
                                                <th>Print Date</th>
                                                <td>:</td>
                                                <th>{{ now()->format('d-M-Y h:i A') }}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </section>


                                <section class="invoice-details">
                                    <table>
                                        <tr>
                                            <th style="width: 15px;">SL</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th id="discount">Discount</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salesRequisition->salesRequisitionDetails as $detail)
                                                <tr>
                                                    <td style="width: 15px;">{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ $detail->product->name }}<br>
                                                        {{ $detail->product->model }}
                                                    </td>
                                                    <td>{{ numberFormat($detail->quantity) }}</td>
                                                    <td>{{ numberFormat($detail->price) }}</td>
                                                    <td class="discount">{{ numberFormat($detail->unit_discount) }}</td>
                                                    <td>{{ numberFormat($detail->amount) }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <section class="requisition-info"
                                        style="display: flex; justify-content: space-between;">
                                        <div class="left" style="width: 70%;">
                                            <table>
                                                <p>IN WORD : {{ convert_number($salesRequisition->total_amount) }} Taka Only
                                                </p>
                                            </table>
                                        </div>
                                        <div class="right" style="width: 30%;">
                                            <table style="border: none!important;">
                                                <tr id="total-amount-row" style="border: none!important;">
                                                    <td style="border: none!important;">Total Amount</td>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ numberFormat($salesRequisition->total_amount) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr style="border: none!important;" class="discount">
                                                    <td style="border: none!important;">Discount</td>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ numberFormat($salesRequisition->discount) }}</strong>
                                                    </td>
                                                </tr>

                                                {{-- Keeping strict structure of fake-invoices, but mapping percentage to
                                                VAT like original file seemed to want --}}
                                                <tr id="vat-row" style="border: none!important;">
                                                    <th style="border: none!important;">VAT</th>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ numberFormat($salesRequisition->vat) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr id="net-amount-row" style="border: none!important;">
                                                    <th style="border: none!important;">Net Amount</th>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;" id="net-amount">
                                                        <strong>{{ numberFormat($salesRequisition->net_amount) }}</strong>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </section>

                                    <section>
                                        <div id="remarks">
                                            {{-- Assuming remarks might not exist on salesRequisition, checking original
                                            file... it doesn't show a remarks field being outputted explicitly in the
                                            original view, but fake-invoices has it. I'll leave it empty or checking if
                                            property exists --}}
                                            <p style="font-size: 18px; font-weight: bold;">Remarks</p>
                                            <p style="text-align: justify;">{{ $salesRequisition->remarks ?? '' }}</p>
                                        </div>
                                    </section>


                                </section>

                                {{-- Payment info section from fake-invoices, but with 0 values as per original request to
                                flow structure. --}}


                                <div style="font-family: Arial; font-size: 10px; margin-top: 10px;" class="bangla-text">
                                    <section>
                                        <p style="margin-bottom: 0rem !important;">১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ
                                            বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ
                                            গ্রহণযোগ্য হবে না।</p>
                                        <p style="margin-bottom: 0rem !important;">২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ
                                            চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার
                                            সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না।
                                            আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
                                        <p style="margin-bottom: 0rem !important;"><strong>৩. খুচরা রিএজেন্টের রেজাল্টের মান
                                                নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত
                                                রিএজেন্ট অফেরতযোগ্য।</strong></p>
                                        <p style="margin-bottom: 0rem !important;">৪.যে কোন প্রয়োজনে যোগাযোগ করুন
                                            +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন
                                            প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ
                                            করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                                        <p style="margin-bottom: 0rem !important;">৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ,
                                            অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে
                                            মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।
                                        </p>
                                        <p style="margin-bottom: 0rem !important;"><strong>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ
                                                করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায়
                                                রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ
                                                গ্রহণযোগ্য নয় ও এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</strong></p>
                                    </section>
                                </div>
                                <footer>
                                    <div
                                        style="display: flex; flex-direction: column; align-items: center; text-align: center; width: 320px;">
                                        @include('partials._seek_sign', [
                                            'model' => $salesRequisition,
                                            'field' => 'signature',
                                        ])
                                        <p class="text-center mt-2 mb-0 font-weight-bold">Receiver Signature</p>
                                    </div>
                                    <p style="margin-bottom: 0;">Authorized ___________________________</p>
                                </footer>
                            </div>
                        </div>
                                </div>
                    </div>
                </div>
@endsection
    
            @section('page_scripts')
                     @stack('script')
                <script>
                        $(document).ready(function() {
                            // Event delegation for all checkboxes

                            // Remarks
                            $(document).on('change', '#customCheck6', function() {
                                if ($(this).is(':checked')) {
                                    $('#remarks').show();
                                } else {
                                $('#remarks').hide();
                                }
                            });

                            // Discount
                            $(document).on('change', '#customCheck8', function() {
                                if ($(this).is(':checked')) {
                                    $('#discount').show();
                                    $('.discount').show();
                                } else {
                                    $('#discount').hide();
                                $('.discount').hide();
                                }
                            });

                            // CW (Bangla Text)
                            $(document).on('change', '#customCheck5', function() {
                                if ($(this).is(':checked')) {
                                    $('.bangla-text').show();
                                } else {
                                $('.bangla-text').hide();
                                }
                            });

                            // VAT Status
                            $(document).on('change', '#customCheck3', function() {
                                if ($(this).is(':checked')) {
                                    $('#vat-row').show();
                                } else {
                                    $('#vat-row').hide();
                                }
                            });

                            // Payment Status
                            $(document).on('change', '#customCheck2', function() {
                                if ($(this).is(':checked')) {
                                    $('.payment-info').show();
                                } else {
                                $('.payment-info').hide();
                                }
                            });

                            // Title
                            $(document).on('change', '#customCheck1', function() {
                                if ($(this).is(':checked')) {
                                    $('.my-header').show();
                                    $('.title').show();
                                } else {
                                    $('.my-header').hide();
                                $('.title').hide();
                                }
                            });

                        // Initial State Logic
                            function setInitialState() {
                                if (!$('#customCheck6').is(':checked')) $('#remarks').hide();

                                if (!$('#customCheck8').is(':checked')) {
                                $('#discount').hide();
                                    $('.discount').hide();
                            }

                                if (!$('#customCheck5').is(':checked')) $('.bangla-text').hide();

                                if ($('#customCheck3').is(':checked')) {
                                    $('#vat-row').show();
                                } else {
                                    $('#vat-row').hide();
                                }
                            }

                            setInitialState();
                        });

                    function printWholePage() {
                            var printContents = document.getElementById('print-content').innerHTML;
                        var originalContents = document.body.innerHTML;

                            document.body.innerHTML = printContents;

                        // add style by scripting
                        var style = document.createElement('style');
                        style.textContent = `
                            .my-header img {
                                max-width: 80px;
                            }

                            .my-header h1 {
                                margin: 0;
                                font-size: 23px;
                                font-weight: bold;
                                color: rgb(0, 0, 187);
                            }

                            .my-header p {
                                margin: 5px 0;
                                font-size: 9px;
                            }

                            .title {
                                margin-top: 15px;
                                text-align: center;
                                margin-bottom: 5px;
                            }

                            .title h2 {
                                margin: 0;
                                font-size: 16px;
                                text-decoration: underline;
                            }

                            .sales-order-info {
                                justify-content: space-between;
                                display: flex;
                                margin-bottom: 20px;
                                font-size: 9px;
                            }

                            .sales-order-info .left {
                                width: 60%;
                            }
                            
                            .sales-order-info .right {
                                width: 40%;
                            }

                            .sales-order-info table {
                                width: 100%;
                                border-collapse: collapse;
                                border: none;
                                /* Removed border color */
                            }

                            .sales-order-info th,
                            .sales-order-info td {
                                padding: 1px;
                                text-align: left;
                                font-size: 12px;
                            }

                            .invoice-details {
                                margin-bottom: 20px;
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
                                padding: 4px;
                                text-align: left;
                                font-size: 12px;
                            }

                            .invoice-details p {
                                margin: 5px 0;
                                font-size: 14px;
                            }

                            .invoice-details .totals {
                                text-align: right;
                            }

                            .invoice-details .totals p {

                                font-size: 12px;
                            }

                            footer {
                                display: flex;
                                justify-content: space-between;
                                margin-top: 20px;
                            }

                            footer p {
                                margin: 6px 0;
                                font-size: 12px;
                                width: 45%;
                                text-align: center;
                            }
                            .payment-info{
                                font-size: 12px;
                            }
                            .bangla-text{
                                margin-top: 10px;
                                    font-size: 10px;
                            }
                            `;
                        document.head.appendChild(style);

                            window.print();

                                document.body.innerHTML = originalContents;
                    }
                </script>
            @endsection