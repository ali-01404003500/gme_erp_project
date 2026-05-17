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
                position: relative; /* Added for watermark positioning */
            }

            .product-card {
                display: flex;
                flex-wrap: wrap;
                margin-top: 28px;
                padding: 20px;
                border-radius: 10px;

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

            .sales-order-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .sales-order-info .left,
            .sales-order-info .right {
                width: 70%;
                /* Adjusted width */
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
            
            .watermark {
                display: none; /* hidden on screen */
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
                            <a href="{{ route('services.quotations.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            <div style="margin-left: 5px;"></div>
                            <button class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm"
                                onclick="printWholePage()"><i class="fa fa-print"></i> Print</button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="width: 100%">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Quotation') }}</h4>
                </div>
            </div>


            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-start">


                        <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                            <input type="checkbox" class="custom-control-input" id="customCheck3">
                            <label class="custom-control-label" for="customCheck3">VAT Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                            <input type="checkbox" class="custom-control-input" id="customCheck4">
                            <label class="custom-control-label" for="customCheck4">AIT Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                            <input type="checkbox" class="custom-control-input" id="customCheck6">
                            <label class="custom-control-label" for="customCheck6">Remarks</label>
                        </div>

                    </div>
                </div>
                <div class="catalog-container" id="printableArea">
                    <div class="watermark">QUOTATION</div>

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
                        <h2>Service Quotation</h2>
                    </section>
                    <section class="sales-order-info">
                        <div class="left">
                            <table>
                                <tr>
                                    <th>Quotation No</th>
                                    <td>:</td>
                                    <th>{{ $quotation->quotation_no }}</th>
                                </tr>
                                <tr>
                                    <th>Customer name</th>
                                    <td>:</td>
                                    <th>{{ $quotation->customer->company_name }}</th>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>:</td>
                                    <td>{{ $quotation->customer->address }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>:</td>
                                    <td> {{ $quotation->customer->phone }}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="right">
                            <table>
                                <tr>
                                    <th>Date</th>
                                    <td>:</td>
                                    <th>{{ $quotation->created_at->format('Y-m-d') }}</th>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>:</td>
                                    <th>{{ date('h:i A', strtotime($quotation->created_at)) }}</th>
                                </tr>
                                <tr>
                                    <th>Quotation By</th>
                                    <td>:</td>
                                    <th>{{ $quotation->createdBy->name }}</th>
                                </tr>
                                <tr>
                                    <th>Print Date</th>
                                    <td>:</td>
                                    <th>{{ now()->format('d-M-Y') }} {{ now()->format('h:i A') }}</th>
                                </tr>
                            </table>
                        </div>
                    </section>
                    <section class="invoice-details">
                        <table style="border: 1px solid black; page-break-inside: avoid; width: 100%;">
                            <thead>
                                <tr style="border: 1px solid black;">
                                    <th style="border: 1px solid black;" width="1%">SN</th>
                                    <th style="border: 1px solid black;" width="45%">Product Description</th>
                                    <th style="border: 1px solid black;" width="5%">Quantity</th>
                                    <th style="border: 1px solid black;" width="5%">Price</th>
                                    <th style="border: 1px solid black;" width="5%">Unit Discount</th>
                                    <th style="border: 1px solid black;" width="5%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotation->quotationDetails as $key => $quotationDetail)
                                    <tr style="page-break-inside: avoid;">
                                        <td style="border: 1px solid black; text-align: center;">
                                            {{ $key + 1 }}</td>
                                        <td style="border: 1px solid black;">
                                            <div style="font-weight: bold;">{{ $quotationDetail->product->name }}
                                            </div>
                                            <div>
                                                Model: {{ $quotationDetail->product->model }}<br>
                                                Brand: {{ optional($quotationDetail->product->brand)->name }}<br>
                                            </div>
                                        </td>

                                        <td style="border: 1px solid black; text-align: center;">
                                            {{ numberFormat($quotationDetail->quantity) }}
                                            {{ $quotationDetail->product->unit->name }}
                                        </td>
                                        <td style="border: 1px solid black; text-align: right;">
                                            {{ numberFormat($quotationDetail->price) }}
                                        </td>
                                        <td style="border: 1px solid black; text-align: right;">
                                            {{ numberFormat($quotationDetail->unit_discount) }}</td>
                                        </td>
                                        <td style="border: 1px solid black; text-align: right;">
                                            {{ numberFormat($quotationDetail->amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                    <section class="amount-info" style="display: flex; justify-content: space-between;">
                        <div class="left" style="width: 70%;">
                            <table>
                                @php
                                    $vat = $quotation->total * 0.1;
                                    $ait = $quotation->total * 0.05;
                                    $netAmount = $quotation->total - $vat - $ait;
                                @endphp
                                <p>IN WORD : {{ convert_number($netAmount) }} Taka Only</p>
                            </table>
                        </div>
                        <div class="right">
                            <table style="border: none!important;">
                                <tr style="border: none!important;">
                                    <th style="border: none!important;">Total</th>
                                    <td style="border: none!important;">:</td>
                                    <td style="border: none!important; text-align: end; padding-left: 20px;">
                                        <b>{{ numberFormat($quotation->total_amount) }}</b>
                                    </td>
                                </tr>
                                <tr style="border: none!important;">
                                    <th style="border: none!important;">Discount</th>
                                    <td style="border: none!important;">:</td>
                                    <td style="border: none!important; text-align: end;">
                                        <b>{{ numberFormat($quotation->discount) }}</b>
                                    </td>
                                </tr>
                               
                                <tr class="vat-row" style="border: none!important;">
                                    <th style="border: none!important;">VAT (10)%</th>
                                    <td style="border: none!important;">:</td>
                                    <td class="vat-value" style="border: none!important; text-align: end;">
                                        <b>{{ numberFormat($vat) }}</b>
                                    </td>
                                </tr>
                                <tr class="ait-row" style="border: none!important;">
                                    <th style="border: none!important;">AIT (5)%</th>
                                    <td style="border: none!important;">:</td>
                                    <td class="ait-value" style="border: none!important; text-align: end;">
                                        <b>{{ numberFormat($ait) }}</b>
                                    </td>
                                </tr>
                                <tr class="net-row" style="border: none!important;">
                                    <th style="border: none!important;">Net Amount</th>
                                    <td style="border: none!important;">:</td>
                                    <td class="net-value" style="border: none!important; text-align: end;">
                                        <b>{{ numberFormat($netAmount) }}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </section>
                    <section>
                        <div id="js-base-data" data-total="{{ $quotation->total_amount }}"
                            data-discount="{{ $quotation->discount }}">
                        </div>

                        <div class="remarks-section" style="display: none;">
                            <h3>Remarks</h3>
                            <p>{{ $quotation->remarks }}</p>
                        </div>
                        <div class="terms">
                            <p>
                                1. This price Excluding Vat & Tax <br>
                                2. This Service & Spair parts have no Warranty <br>
                                3. Offer Validity 15 Days <br>
                                4. Mode of Delivery & Payment:- Product will delivered within 30 international
                                working day after order confirmation with 100% advance payment in our bank account.
                                <br>
                                5. Payment through Bank is convenient for us.
                            </p>

                        </div>
                    </section>
                    <div class="row" style="margin-top: 80px; text-align: center;">
                        <div class="col-6 d-flex flex-column align-items-center">
                            <div style="border-top: 1px solid #888; width: 60%; margin: 0 auto 2px auto;"></div>
                            <div style="font-size: 13px; color: #222;">Received</div>
                        </div>
                        <div class="col-6 d-flex flex-column align-items-center">
                            <div style="border-top: 1px solid #888; width: 60%; margin: 0 auto 2px auto;"></div>
                            <div style="font-size: 13px; color: #222;">Authorized</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vatCheck = document.getElementById('customCheck3');
            const aitCheck = document.getElementById('customCheck4');
            const remarksCheck = document.getElementById('customCheck6');
            const vatRow = document.querySelector('.vat-row');
            const aitRow = document.querySelector('.ait-row');
            const remarksSec = document.querySelector('.remarks-section');
            const baseData = document.getElementById('js-base-data');
            const vatValueEl = document.querySelector('.vat-value b');
            const aitValueEl = document.querySelector('.ait-value b');
            const netValueEl = document.querySelector('.net-value b');

            window.recalcAndToggle = function() { // made global for print function to access
                // Show/Hide rows
                vatRow.style.display = vatCheck.checked ? '' : 'none';
                aitRow.style.display = aitCheck.checked ? '' : 'none';
                remarksSec.style.display = remarksCheck.checked ? '' : 'none';

                // Calculation
                const totalAmount = parseFloat(baseData.dataset.total);
                const discount = parseFloat(baseData.dataset.discount);
                const baseAfterDisc = totalAmount - discount;

                const vat = vatCheck.checked ? baseAfterDisc * 0.10 : 0;
                const ait = aitCheck.checked ? baseAfterDisc * 0.05 : 0;
                const net = baseAfterDisc - vat - ait;

                // Update values
                vatValueEl.textContent = vat.toFixed();
                aitValueEl.textContent = ait.toFixed();
                netValueEl.textContent = net.toFixed();
            }

            // Bind change events
            [vatCheck, aitCheck, remarksCheck].forEach(chk => {
                chk.addEventListener('change', recalcAndToggle);
            });

            recalcAndToggle(); // initialize on page load
        });

        function printWholePage() {
            // Store original content
            var originalContents = document.body.innerHTML;
            var printContents = document.getElementById('printableArea').innerHTML;
            
            // Create a new window for printing
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Quotation Print</title>
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
                        .sales-order-info .left,
                        .sales-order-info .right {
                            width: 50%;
                        }
                        .sales-order-info table {
                            width: 100%;
                            border-collapse: collapse;
                            border: none;
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
                        .amount-info {
                            display: flex;
                            justify-content: space-between;
                        }
                        .amount-info .left {
                            width: 70%;
                        }
                        .amount-info .right table {
                            border: none;
                        }
                        .amount-info .right th,
                        .amount-info .right td {
                            border: none;
                            padding: 2px;
                            font-size: 12px;
                        }
                        .terms h3 {
                            margin: 20px 0 10px;
                            font-size: 14px;
                        }
                        .terms p {
                            margin: 10px 0 20px;
                            font-size: 12px;
                        }
                        .row {
                            display: flex;
                            width: 100%;
                        }
                        .col-6 {
                            width: 50%;
                            text-align: center;
                        }
                        .remarks-section {
                            margin: 20px 0;
                        }
                        .remarks-section h3 {
                            font-size: 14px;
                        }
                        .remarks-section p {
                            font-size: 12px;
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            
            // Wait for the content to load then print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vatCheck = document.getElementById('customCheck3');
            const aitCheck = document.getElementById('customCheck4');
            const remarksCheck = document.getElementById('customCheck6');
            const vatRow = document.querySelector('.vat-row');
            const aitRow = document.querySelector('.ait-row');
            const netRow = document.querySelector('.net-row');
            const remarksSec = document.querySelector('.remarks-section');
            const baseData = document.getElementById('js-base-data');
            const vatValueEl = document.querySelector('.vat-value b');
            const aitValueEl = document.querySelector('.ait-value b');
            const netValueEl = document.querySelector('.net-value b');

            function recalcAndToggle() {
                // 1) Show/hide rows
                vatRow.style.display = vatCheck.checked ? '' : 'none';
                aitRow.style.display = aitCheck.checked ? '' : 'none';
                remarksSec.style.display = remarksCheck.checked ? '' : 'none';

                // 2) Recompute
                const totalAmount = parseFloat(baseData.dataset.total);
                const discount = parseFloat(baseData.dataset.discount);
                // base after discount
                const baseAfterDisc = totalAmount - discount;

                const vat = vatCheck.checked ? baseAfterDisc * 0.10 : 0;
                const ait = aitCheck.checked ? baseAfterDisc * 0.05 : 0;
                const net = baseAfterDisc - vat - ait;

                // 3) Update display (2 decimals)
                vatValueEl.textContent = vat.toFixed();
                aitValueEl.textContent = ait.toFixed();
                netValueEl.textContent = net.toFixed();
            }

            // Bind on-change
            [vatCheck, aitCheck, remarksCheck].forEach(chk => {
                chk.addEventListener('change', recalcAndToggle);
            });

            // Wire up print button
            // document.getElementById('printButton').addEventListener('click', function() {
            //   recalcAndToggle();
            //   window.print();
            // });

            // Initialize on page load
            recalcAndToggle();
        });
    </script>


@endsection