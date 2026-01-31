@section('title', 'Office Purchase Show')
@section('description', 'Office Purchase Show')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.office-purchase-view-menu-title') }}</li>
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
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
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

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">{{ trans('menu.office-purchase-view-menu-title') }}</h3>
                    <div class="row">
                        <a href="{{ route('purchase.offices.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"
                            style="margin-right: 5px;">
                            <i class="fa fa-list"></i> List</a>
                        <a href="{{ route('purchase.offices.show', $officePurchase->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm">PDF</a>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="header">
                                <img class="header-img" src="{{ $company_info->company_logo }}" alt="Company Logo">

                                <div>
                                    <table class="content-table" style="border: none;">
                                        <tr>
                                            <td class="com-info" style="border: none; padding-top: 20px;">
                                                <div class="com">
                                                    <div style="margin-right: 160px;line-height: 0.8;">
                                                        <h1 style="color: rgb(13, 13, 92); font-size: 21px!important;margin-left: 45px;">
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
                                <h2>Office Purchase Invoice Bill</h2>
                            </section>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th style="width:150px;">Bill No</th>
                                            <td>:</td>
                                            <th>{{ $officePurchase->invoice_no }}</th>
                                        </tr>
                                        <tr>
                                            <th style="width:150px;">Vendor Name</th>
                                            <td>:</td>
                                            <th>{{ $officePurchase->vendor->company_name }}</th>
                                        </tr>
                                       
                                        <tr>
                                            <th style="width:150px;">Vendor Address</th>
                                            <td>:</td>
                                            <th>{{ $officePurchase->vendor->address}}</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 150px">Vendor Phone</th><td>:</td><th>{{ $officePurchase->vendor->phone }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th style="width:150px;">Date</th>
                                            <td>:</td>
                                            <th>{{ date('d F, Y', strtotime($officePurchase->date)) }}</th>
                                        </tr>
                                        <tr>
                                            <th>Bill By</th>
                                            <td>:</td>
                                            <td>{{ $officePurchase->createdBy->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Print Date & Time</th>
                                            <td>:</td>
                                            <td>{{ now()->format('d-M-Y h:i A') }}</td>
                                        </tr>
                                        @php
                                            $file = $officePurchase->file_upload;
                                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            $iconUrl = asset('assets/img/pdf.png');
                                            if(in_array($extension, ['jpg', 'jpeg', 'png'])) {
                                                $iconUrl = $file;
                                            }
                                        @endphp
                                        <tr>
                                            <th>Attachment</th>
                                            <td>:</td>
                                            <td>
                                                <a href="{{ $file }}" target="_blank">
                                                    <img src="{{ $iconUrl }}" alt="File" style="width:30px; height:30px;">
                                                    View File
                                                </a>
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
                                            <th>Reference Bill No</th>
                                            <th>Remarks</th>
                                            <th>Bill Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>{{ $officePurchase->reference_bill }}</td>
                                            <td>{{ $officePurchase->remarks }}</td>
                                            <td>{{ number_format($officePurchase->bill_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <table>
                                            <p>IN WORD : {{ convert_number($officePurchase->bill_amount) }} Taka Only</p>
                                        </table>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr style="border: none!important;">
                                                <th style="border: none!important;">Grand Total : <strong>{{ number_format($officePurchase->bill_amount) }}</strong></th>

                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>

                            <footer>
                                <p>Received ___________________________</p>
                                <p>Authorized ___________________________</p>
                            </footer>

                            <div class="col-md-12 mt-4">
                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                    <a type="submit" href="{{ route('purchase.offices.index') }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')

@endsection