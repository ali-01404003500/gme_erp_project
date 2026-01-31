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
                font-size: 14px;
            }

              footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 40px;
                }
        </style>
        <style>
    .signature-section {
        margin-top: 40px;
    }
    
    .signature-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    
    .signature-box {
        text-align: center;
        width: 48%;
        position: relative;
    }
    
    .signature-line {
        border-top: 1px solid #000;
        width: 80%;
        margin: 0 auto;
        padding-top: 5px;
    }
    
    .signature-label {
        margin-top: 5px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .signature-display {
        height: 60px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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
</style>

        <body>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">

                        <header class="my-header">
                            @include('partials._for_pdf_header_2nd')
                        </header>

                        <section class="title">
                            <h4>Advance Cheque Money Receipt</h4>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 55%; float: left;">
                                <table style="border: 1px solid white;">
                                    <tr>
                                        <th style="border: 1px solid white;">Receipt No</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $advanceChequeEntry->receipt_no }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Customer Name</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ @$advanceChequeEntry->customer->company_name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Address</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ @$advanceChequeEntry->customer->address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="right" style="width: 45%; float: right;">
                                <table style="border: 1px solid white;">
                                    <tr>
                                        <th style="border: 1px solid white;">Collection Date</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $advanceChequeEntry->collection_date }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Prepared By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $advanceChequeEntry->createdBy->name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Print Date</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ now()->format('d-M-Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="clear: both;"></div>
                        </section>

                        <section class="invoice-details">
                            <table>
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($advanceChequeEntry->details as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>Cheque ({{ $detail->bank->name }} -{{ $detail->branch->name }}- {{ $detail->cheque_no }} -
                                                    {{ $detail->cheque_date}})
                                                </td>
                                            <td>{{ number_format($detail->amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 50%; float: left;">
                                <p style="font-size: 10px;">IN WORD : {{ convert_number($advanceChequeEntry->total_amount) }} Taka Only</p>
                            </div>
                            <div class="right" style="width: 50%; float: right;">
                                <table style="border: none!important; width: 50%; float: right;">
                                    <tr>
                                        <th style="border: none!important;">Grand Total</th>
                                        <td style="border: none!important;">:</td>
                                        <td style="border: none!important; text-align: right;">
                                            <strong>{{ number_format($advanceChequeEntry->total_amount) }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </section>

                        <section class="signature-section">
                        <div class="signature-container">
                            <!-- Receiver Signature -->
                            <div class="signature-box" style="width: 50%; float: left; margin-top: 50px;">
                                <div id="signature-display" class="signature-display">
                                    @if ($advanceChequeEntry->signature)
                                        <img src="{{ $advanceChequeEntry->signature }}" 
                                            alt="Receiver Signature">
                                        <div class="signature-timestamp">
                                            Signed on: {{ $advanceChequeEntry->signature_timestamp }}
                                        </div>
                                    @else
                                        <div class="signature-placeholder">No signature captured</div>
                                    @endif
                                </div>
                                <div class="signature-line"></div>
                                <div class="signature-label">Receiver Signature</div>
                            </div>

                            <!-- Authorized Signature -->
                            <div class="signature-box" style="width: 50%; float: right; margin-top: 100px;">
                                <div class="signature-line" style="margin-top: 30px;"></div>
                                <div class="signature-label">Authorized Signature</div>
                            </div>
                        </div>
                    </section>

                       

                    </div>
                </div>
            </div>
    </div>
</div>

</body>
