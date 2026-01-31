<title>Legal Bill Invoice</title>
<div class="container-fluid">
    <div class="social-dash-wrap">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                width: 100%;
                margin: 10px auto;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ccc;
            }

            .my-header {
                text-align: center;
                margin-bottom: 10px;
            }

            .my-header img {
                max-width: 80px;
                margin-bottom: 5px;
            }

            .my-header h1 {
                margin: 0;
                font-size: 18px;
                font-weight: bold;
                color: rgb(0, 0, 187);
            }

            .my-header p {
                margin: 2px 0;
                font-size: 10px;
            }

            .title {
                text-align: center;
                margin-bottom: 10px;
            }

            .title h4 {
                margin: 0;
                font-size: 14px;
                text-decoration: underline;
            }

            .info-table {
                width: 100%;
                border: none;
                font-size: 11px;
                margin-bottom: 10px;
            }

            .info-table th,
            .info-table td {
                padding: 2px;
                text-align: left;
                border: none;
            }

            .invoice-details {
                margin-top: 10px;
            }

            .invoice-details table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .invoice-details th,
            .invoice-details td {
                border: 1px solid #000;
                padding: 5px;
                font-size: 11px;
            }

            .totals-table {
                width: 50%;
                float: right;
                border: none;
                font-size: 11px;
            }

            .totals-table td {
                border: none;
                padding: 2px;
            }

            .signature-table {
                width: 100%;
                margin-top: 80px;
                text-align: center;
            }

            .signature-table td {
                border: none;
                width: 50%;
                font-size: 12px;
            }
        </style>

        <body>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">

                        {{-- Header --}}
                      <header class="my-header">
                            @include('partials._for_pdf_header_2nd')
                        </header>

                        {{-- Title --}}
                        <section class="title">
                            <h4>Legal Bill Invoice</h4>
                        </section>

                        {{-- Bill Info --}}
                        <section class="requisition-info">
                            <table class="info-table" style="width:70%; float:left;">
                                <tr>
                                    <th style="width: 90px;">Bill No</th>
                                    <td>:</td>
                                    <td>{{ $legalBillEntry->bill_no }}</td>
                                </tr>
                                <tr>
                                    <th>Vendor Name</th>
                                    <td>:</td>
                                    <td>{{ optional($legalBillEntry->vendor)->company_name }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>:</td>
                                    <td>{{ optional($legalBillEntry->vendor)->address }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>:</td>
                                    <td>{{ optional($legalBillEntry->vendor)->phone }}</td>
                                </tr>
                            </table>

                            <table class="info-table" style="width:30%; float:right;">
                                <tr>
                                    <th style="width: 70px;">Date</th>
                                    <td>:</td>
                                    <td>{{ date('d F, Y', strtotime($legalBillEntry->date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>:</td>
                                    <td>{{ $legalBillEntry->created_at->format('h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Bill By</th>
                                    <td>:</td>
                                    <td>{{ optional($legalBillEntry->createdBy)->name }}</td>
                                </tr>
                            </table>
                            <div style="clear:both;"></div>
                        </section>

                        {{-- Bill Table --}}
                        <section class="invoice-details">
                            <table>
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Bill No</th>
                                        <th>Description</th>
                                        <th style="text-align:right;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $legalBillEntry->bill_no }}</td>
                                        <td>{{ $legalBillEntry->particular ?? 'N/A' }}</td>
                                        <td style="text-align:right;">{{ number_format($legalBillEntry->amount) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right;">Total Amount</th>
                                        <th style="text-align:right;">{{ number_format($legalBillEntry->amount) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </section>

                        {{-- Amount in Words --}}
                        <p style="font-size:11px; margin-top:10px;">
                            <strong>IN WORD :</strong> {{ convert_number($legalBillEntry->amount) }} Taka Only
                        </p>

                        {{-- Signature --}}
                       <table style="width:100%; margin-top:200px; border:none;">
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
