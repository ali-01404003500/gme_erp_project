@extends('layout.app')
@section('title', 'Legal Bill Invoice')
@section('description', 'Legal Bill Invoice')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Legal Bill Invoice') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
          <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 100px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }

                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 50px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
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

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info .left,
                .requisition-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
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
                    margin-top: 20px;
                }

                /* footer p {
                        margin: 10px 0;
                        font-size: 14px;
                        width: 45%;
                        text-align: center;
                    } */
            </style>
        <div class="row">
            <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                <h3 class="text-capitalize">{{ trans('Legal Bill Invoice') }}</h3>
                <div class="row">
                    <a href="{{ route('legal.legal-bill-entries.index') }}"
                        class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm" style="margin-right: 5px;">
                        <i class="fa fa-list"></i> List</a>
                    <a href="{{ route('legal.legal-bill-entries.show', $legalBillEntry->id) }}?export=pdf" target="_blank"
                        class="btn btn-primary ml-auto btn-sm">PDF</a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">

                    {{-- Header --}}
                    <div class="header">
                        <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo">

                        <div>
                            <table class="content-table" style="border: none;">
                                <tr>

                                    <td class="com-info" style="border: none; padding-top: 20px;">
                                        <div class="com">
                                            <div style="margin-right: 160px;line-height: 0.8;">
                                                <h1
                                                    style="color: rgb(13, 13, 92); font-size: 21px!important;margin-left: 45px;">
                                                    {{ $company_info->company_name }}</h1>
                                                <p style="font-size: 8px!important; color:black; text-align:center">
                                                    {{ $company_info->company_bio }}</p>
                                                <p style="font-size: 8px!important; color:black; text-align:center">
                                                    {{ $company_info->company_address }}</p>
                                                <p style="font-size: 8px!important; color:black; text-align:center">
                                                    Hotline : {{ $company_info->company_phone }}</p>
                                                <p style="font-size: 8px!important; color:black; text-align:center">
                                                    e-mail : {{ $company_info->company_email }} web:
                                                    {{ $company_info->website }}</p>
                                            </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                     <section class="title">
                            <h2>Legal Bill Invoice</h2>
                        </section>

                    {{-- Bill Info --}}
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="mb-1"><strong>Bill No :</strong> {{ $legalBillEntry->bill_no }}</p>
                            <p class="mb-1"><strong>Vendor Name :</strong>
                                {{ optional($legalBillEntry->vendor)->company_name }}</p>
                            <p class="mb-1"><strong>Vendor Address :</strong>
                                {{ optional($legalBillEntry->vendor)->address }}</p>
                            <p class="mb-1"><strong>Vendor Phone :</strong>
                                {{ optional($legalBillEntry->vendor)->phone }}</p>
                        </div>
                        <div>
                            <p class="mb-1"><strong>Date :</strong> {{ $legalBillEntry->date }}</p>
                            <p class="mb-1"><strong>Time :</strong> {{ $legalBillEntry->created_at->format('h:i A') }}
                            </p>
                            <p class="mb-1"><strong>Bill By :</strong> {{ optional($legalBillEntry->createdBy)->name }}
                            </p>
                        </div>
                    </div>

                    {{-- Bill Table --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50px;">SN</th>
                                <th>Bill No</th>
                                <th>Description</th>
                                <th style="text-align: right;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $legalBillEntry->bill_no }}</td>
                                <td>{{ $legalBillEntry->particular ?? 'N/A' }}</td>
                                <td style="text-align: right;">{{ number_format($legalBillEntry->amount) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right;">Total Amount :</th>
                                <th style="text-align: right;">{{ number_format($legalBillEntry->amount) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Amount in Words --}}
                    <p class="mt-3"><strong>IN WORD :</strong> {{ convert_number($legalBillEntry->amount) }} Taka Only
                    </p>

                    {{-- Signatures --}}
                    <footer>
                        <p>Received ___________________________</p>
                        <p>Authorized ___________________________</p>
                    </footer>


                </div>
            </div>
        </div>
    </div>
@endsection
