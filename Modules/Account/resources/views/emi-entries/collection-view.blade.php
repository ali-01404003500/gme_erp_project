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
                            <h4>EMI Collection Money Receipt</h4>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 55%; float: left;">
                                <table style="border: 1px solid white;">
                                    <tr>
                                        <th style="border: 1px solid white;">Receipt No</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $emiEntryDetail->receipt_no ?? $emiEntry->emiDetails->first()->receipt_no }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Customer Name</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $emiEntryDetail->emiEntry->customer->company_name ?? $emiEntry->customer->company_name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Address</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $emiEntryDetail->emiEntry->customer->address ?? $emiEntry->customer->address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="right" style="width: 45%; float: right;">
                                <table style="border: 1px solid white;">
                                    <tr>
                                        <th style="border: 1px solid white;">Collection Date</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;"> @if($emiEntryDetail)
                                                {{ @$emiEntryDetail->payments->first()->date ?? @$emiEntryDetail->advanceChequeEntryDetail->chcqueVerification->cheque_date }}
                                            @elseif($emiEntry)
                                                {{ @$emiEntry->payments->first()->date  }}
                                            @else
                                                {{ now()->format('d-M-Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid white;">Prepared By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <td style="border: 1px solid white;">{{ $emiEntryDetail->emiEntry->createdBy->name ?? $emiEntry->createdBy->name }}</td>
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
                                        <th>Account Name</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if ($emiEntryDetail && $emiEntryDetail->payments->isNotEmpty())
                                            {{-- Installment-level receipt: Show direct payments --}}
                                            @foreach ($emiEntryDetail->payments as $key => $detail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->pay_mode }}</td>
                                                    <td>{{ $detail->bank->account_name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($detail->amount) }}</td>
                                                    <td>{{ $detail->remarks }}</td>
                                                </tr>
                                            @endforeach
                                        @elseif (
                                            $emiEntryDetail &&
                                                $emiEntryDetail->advanceChequeEntryDetail &&
                                                $emiEntryDetail->advanceChequeEntryDetail->chcqueVerification)
                                            {{-- Installment paid via advance cheque --}}
                                            @php
                                                $verification =
                                                    $emiEntryDetail->advanceChequeEntryDetail->chcqueVerification;
                                                    // dd($verification);
                                            @endphp
                                            @if (in_array($verification->status, ['cash', 'honor-verified']))
                                                <tr>
                                                    <td>1</td>
                                                    <td>@if($verification->status == 'cash') Cash @else Bank @endif</td>
                                                    <td>{{ $verification->transactions->where('balance_type', 'debit')->first()->account->name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($verification->amount) }}</td>
                                                    <td>{{ $verification->remarks }}</td>
                                                </tr>
                                            @endif
                                        @elseif ($emiEntry && $emiEntry->payments->isNotEmpty())
                                            {{-- Entry-level receipt: Show all payments from EMI entry --}}
                                            @foreach ($emiEntry->payments as $key => $detail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->pay_mode }}</td>
                                                    <td>{{ $detail->bank->account_name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($detail->amount) }}</td>
                                                    <td>{{ $detail->remarks }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                            </table>
                        </section>

                        <section class="requisition-info">
                            <div class="left" style="width: 50%; float: left;">
                                <p style="font-size: 10px;">IN WORD : {{ convert_number($emiEntryDetail->emi_amount ?? $emiEntry->emiDetails->where('status', 'early_settlement_paid')->sum('emi_amount')) }} Taka Only</p>
                            </div>
                            <div class="right" style="width: 50%; float: right;">
                                <table style="border: none!important; width: 50%; float: right;">
                                    <tr>
                                        <th style="border: none!important;">Grand Total</th>
                                        <td style="border: none!important;">:</td>
                                        <td style="border: none!important; text-align: right;">
                                            <strong>{{ number_format($emiEntryDetail->emi_amount ?? $emiEntry->emiDetails->where('status', 'early_settlement_paid')->sum('emi_amount'), 2) }}</strong>
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
                                        @if (@$emiEntryDetail->signature->signature ?? @$emiEntry->signature->signature)
                                            <img src="{{ @$emiEntryDetail->signature->signature ?? @$emiEntry->signature->signature }}" 
                                                alt="Receiver Signature">
                                            <div class="signature-timestamp">
                                                Signed on: {{ @$emiEntryDetail->signature->updated_at ?? @$emiEntry->signature->updated_at }}
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
        </body>
    </div>
</div>