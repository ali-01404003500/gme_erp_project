@section('title', 'EMI Collection Receipt Detail')
@section('description', 'EMI Collection Receipt Detail')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        EMI Collection Receipt Detail
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 80px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    margin-bottom: 20px;
                }

                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 40px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
                    margin: 5px 0;
                    font-size: 20px;
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

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .requisition-info th,
                .requisition-info td {
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
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    font-size: 14px;
                    text-align: left;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 40px;
                }

                /* Signature Styles */
                .signature-display {
                    height: 80px;
                    border: 1px solid #ddd;
                    margin-top: 10px;
                    background-color: white;
                }

                .signature-placeholder {
                    color: #999;
                    font-style: italic;
                }

                /* Modal Styles */
                .signature-modal .modal-dialog {
                    max-width: 600px;
                }

                .signature-pad-container {
                    margin: 20px 0;
                }

                .signature-pad {
                    border: 1px solid #000;
                    background-color: #fff;
                    width: 100%;
                    height: 200px;
                }

                .signature-controls {
                    margin-top: 10px;
                    text-align: center;
                }

                .signature-timestamp {
                    font-size: 12px;
                    color: #666;
                    text-align: center;
                    margin-top: 5px;
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">EMI Collection Receipt Detail</h3>
                    <div class="row">
                        {{-- @dd($emiEntryDetail , $emiEntry) --}}
                        {{-- If generating PDF for EMI Entry --}}
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                            class="btn btn-primary btn-sm">PDF</a>

                    </div>
                </div>

                <div class="col-md-12 print-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="header">
                                <img src="{{ $company_info->company_logo }}" alt="Company Logo">
                                <div>
                                    <h1>{{ $company_info->company_name }}</h1>
                                    <p>{{ $company_info->company_bio }}</p>
                                    <p>{{ $company_info->company_address }}</p>
                                    <p>Hotline : {{ $company_info->company_phone }}</p>
                                    <p>e-mail : {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                                    <h2 class="title">EMI Collection Money Receipt</h2>
                                </div>
                            </div>
                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Receipt No</th>
                                            <td>:</td>
                                            <td>{{ $emiEntryDetail->receipt_no ?? $emiEntry->emiDetails->where('status', 'early_settlement_paid')->last()->receipt_no }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>:</td>
                                            <td>{{ $emiEntryDetail->emiEntry->customer->company_name ?? $emiEntry->customer->company_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>:</td>
                                            <td>{{ $emiEntryDetail->emiEntry->customer->address ?? $emiEntry->customer->address }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <td>
                                                @if ($emiEntryDetail)
                                                    {{ @$emiEntryDetail->payments->first()->date ?? @$emiEntryDetail->advanceChequeEntryDetail->chcqueVerification->cheque_date }}
                                                @else
                                                    {{ @$emiEntry->payments->first()->date }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Prepared By</th>
                                            <td>:</td>
                                            <td>{{ $emiEntryDetail->emiEntry->createdBy->name ?? $emiEntry->createdBy->name }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
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

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <p>IN WORD :
                                            {{ convert_number($emiEntryDetail->paid_amount ?? $emiEntry->emiDetails->where('status', 'early_settlement_paid')->sum('paid_amount')) }}
                                            Taka Only</p>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr>
                                                <td style="border: none!important;">Grand Total</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ number_format($emiEntryDetail->paid_amount ?? $emiEntry->emiDetails->where('status', 'early_settlement_paid')->sum('paid_amount'), 2) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>
                            {{-- @dd($emiEntryDetail); --}}

                            <footer
                                style="display: flex; justify-content: space-between; margin-top: 40px; align-items: flex-end;">
                                <div style="text-align: center; width: 48%;">

                                    @include('partials._seek_sign', [
                                        'model' => $emiEntryDetail ?? $emiEntry,
                                        'field' => 'signature',
                                    ])
                                    <p class="text-center mt-2 mb-0 font-weight-bold">Receiver Signature</p>
                                </div>

                                <!-- Authorized Signature -->
                                <div style="text-align: center; width: 48%;">
                                    <p>___________________________</p>
                                    <p>Authorized Signature</p>
                                </div>
                            </footer>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Include Signature Pad library -->

@endsection

@section('page_scripts')

    @stack('script')
@endsection
