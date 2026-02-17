@section('title', 'Delivery Detail')
@section('description', 'Delivery Detail')
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
                                        {{ trans('menu.sales-order-delivery-view-menu-title') }}</li>
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

                .delivery-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .delivery-info .left,
                .delivery-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .delivery-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .delivery-info th,
                .delivery-info td {
                    padding: 5px;
                    text-align: left;
                    font-size: 14px;
                }

                .delivery-details {
                    margin-bottom: 20px;
                }

                .delivery-details table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }

                .delivery-details table,
                .delivery-details th,
                .delivery-details td {
                    border: 1px solid #000;
                }

                .delivery-details th,
                .delivery-details td {
                    padding: 8px;
                    text-align: left;
                    font-size: 14px;
                }

                .delivery-details p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                .delivery-details .totals {
                    text-align: right;
                }

                .delivery-details .totals p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                    align-items: flex-end;
                }
            </style>
            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">{{ trans('menu.sales-order-delivery-view-menu-title') }}</h3>
                    <div class="row">
                        <a href="{{ route('sales.shipment-verifies.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"
                            style="margin-right: 5px;">
                            <i class="fa fa-list"></i> List</a>
                        <a href="{{ route('sales.shipment-verifies.show', $shipmentVerify->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm">PDF</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="header">
                                @php
                                    $company_info = DB::table('company_infos')->first();
                                @endphp
                                <img class="header-img" src="{{ $company_info->company_logo }}" alt="Company Logo">
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
                                <h4>Courier Challan</h4>
                            </section>

                            <section class="delivery-info">
                                <div class="left" style="width: 70%; float: left;">
                                    <table style="border: 1px solid white;">
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Invoice No</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ $shipmentVerify->source?->invoice_id ?? $shipmentVerify->source?->id }}</th>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Name</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ $shipmentVerify->customer->company_name }}</th>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Address</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ optional($shipmentVerify->customer->area)->area ?? $shipmentVerify->customer->address }}</th>
                                        </tr>
                                        
                                    </table>
                                </div>
                                <div class="right" style="width: 30%; float: right;">
                                    <table style="border: 1px solid white;">
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Date</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ $shipmentVerify->source->source->invoice_date }}</th>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Time</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ $shipmentVerify->created_at->format('h:i A') ?? '' }}</th>
                                        </tr>
                                        
                                        <tr style="border: 1px solid white;">
                                            <th style="border: 1px solid white; width: 80px;">Print Date & Time</th>
                                            <td style="border: 1px solid white;">:</td>
                                            <th style="border: 1px solid white;">{{ now()->format('d-M-Y') }}
                                                {{ now()->format('h:i A') }}</th>
                                        </tr>
                                        
                                        
                                    </table>
                                </div>
                                <div style="clear: both;"></div>
                            </section>

                            <section class="delivery-details"> 
                                <table>
                                    <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Cartoon No</th> 
                                                <th>Product Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $shipmentVerify->cartoon_no ?? '' }}</td> 
                                                <td>Cartoon Medical Goods</td>
                                            </tr>
                                        </tbody>
                                </table>
                            </section>
                            <table style="width: 100%; border:1px solid #000; border-collapse: collapse;" class="my-5">
                                <tbody>
                                    <tr>
                                        <th colspan="3" width="100%" style="font-size: 20px"><u>Shipment Address</u></th> 
                                    </tr>
                                    <tr>
                                        <th  width="30%">Contact Person</th>
                                        <th  width="5%">:</th>
                                        <th  width="65%">{{ $shipmentVerify->source->source->shipment->contact_person_name ?? '' }}</th> 
                                    </tr>
                                    <tr>
                                        <th width="30%">Contact Number</th>
                                        <th width="5%">:</th>
                                        <th width="65%">{{ $shipmentVerify->source->source->shipment->contact_person_number ?? '' }}</th> 
                                    </tr>
                                    <tr>
                                        <th width="30%">Address</th>
                                        <th width="5%">:</th>
                                        <th  width="65%">{{ $shipmentVerify->source->source->shipment->address ?? '' }}</th> 
                                    </tr>
                                        <tr>
                                        <th width="30%">Courier Name</th>
                                        <th width="5%">:</th>
                                        <th width="65%">{{ $shipmentVerify->source->source->shipment->courier->courier_name ?? '' }}</th> 
                                    </tr>
                                </tbody> 
                            </table>
                        
                            
                            <footer style="margin-top: 100px">
                                <div style="display: flex; flex-direction: column; align-items: center; text-align: center; width: 320px;">
                                    @include('partials._seek_sign', [
                                        'model' => $shipmentVerify,
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
    </div>
@endsection

@section('page_scripts')
    @stack('script')
    <script type="text/javascript">
        // Add any specific delivery-related JavaScript here if needed
    </script>
@endsection