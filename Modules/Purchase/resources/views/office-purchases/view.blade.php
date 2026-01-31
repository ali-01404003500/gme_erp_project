<title>Office Purchase</title>
<div class="container-fluid">
    <div class="social-dash-wrap">

        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                width: 80%;
                margin: 20px auto;
                padding: 100px;
                background-color: #fff;
                border: 1px solid #ccc;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .my-header {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .my-header img {
                max-width: 100px;
                margin-right: 20px;
            }

            .my-header h1 {
                margin: 0;
                font-size: 25px;
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

            .title h4 {
                margin: 0;
                font-size: 18px;
                text-decoration: underline;
            }

            .requisition-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .requisition-info .left,
            .requisition-info .right {
                width: 70%;
            }

            .requisition-info table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }

            .requisition-info th,
            .requisition-info td {
                padding: 2px;
                text-align: left;
                font-size: 11px;
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
                font-size: 12px;
            }

            .invoice-details th,
            .invoice-details td {
                padding: 8px;
                text-align: left;
                font-size: 11px;
            }

            .invoice-details p {
                margin: 5px 0;
                font-size: 12px;
            }

            .invoice-details .totals {
                text-align: right;
            }

            .invoice-details .totals p {
                margin: 5px 0;
                font-size: 12px;
            }
        </style>

        <body>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">

                        <header class="my-header">
                            @include('partials._for_pdf_header_2nd')
                        </header>

                        <section class="title">
                            <h4>Office Purchase Invoice Bill</h4>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 70%; float: left;">
                                <table style="border: 1px solid white;">
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Bill No</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $officePurchase->invoice_no }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Vendor Name</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $officePurchase->vendor->company_name }}</th>
                                    </tr>
                                   
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Vendor Address</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">
                                            {{ $officePurchase->vendor->address }}</td>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Vendor Phone</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">
                                            {{ $officePurchase->vendor->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="right" style="width: 30%; float: right;">
                                <table style="border: 1px solid white;">
                                     <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Date</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ date('d-M-Y', strtotime($officePurchase->date)) }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Bill By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $officePurchase->createdBy->name ?? 'N/A' }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 100px;">Print Date & Time</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ now()->format('d-M-Y') }}
                                            {{ now()->format('h:i A') }}</th>
                                    </tr>
                                </table>
                            </div>
                            <div style="clear: both;"></div>
                        </section>

                        <section class="invoice-details">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">SN</th>
                                        <th style="width: 25%;">Reference Bill No</th>
                                        <th style="width: 47%;">Remarks</th>
                                        <th style="width: 20%; text-align: right;">Bill Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $officePurchase->reference_bill }}</td>
                                        <td>{{ $officePurchase->remarks }}</td>
                                        <td style="text-align: right;">{{ number_format($officePurchase->bill_amount) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 50%; float: left;">
                                <table style="border: none!important;">
                                    <p style="font-size: 10px;">IN WORD :
                                        {{ convert_number($officePurchase->bill_amount) }} Taka Only</p>
                                </table>
                            </div>
                            <div class="right" style="width: 50%; float: right;">
                                <table style="border: none!important; width: 50%; float: right;">
                                    <tr style="border: none!important;">
                                        <th style="border: none!important;">Grand Total</th>
                                        <td style="border: none!important;">:</td>
                                        <td style="border: none!important; text-align: right;">
                                            <strong>{{ number_format($officePurchase->bill_amount) }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="clear: both;"></div>
                        </section>

                        <table style="width:100%; margin-top:100px; border:none;">
                            <tr>
                                <td style="width:50%; text-align:center; border:none;">
                                    ___________________________ <br>
                                    Received
                                </td>
                                <td style="width:50%; text-align:center; border:none;">
                                    ___________________________ <br>
                                    Authorized
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

        </body>
    </div>
</div>