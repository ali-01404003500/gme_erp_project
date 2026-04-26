<!DOCTYPE html>
<html>
<head>
    <title>Delivery Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container-fluid {
            width: 100%;
            padding: 20px;
        }
        .my-header {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 30px;
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
        .delivery-info {
            margin-bottom: 20px;
            overflow: hidden;
        }
        .delivery-info .left,
        .delivery-info .right {
            width: 48%;
            float: left;
        }
        .delivery-info .right {
            float: right;
        }
        .delivery-info table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .delivery-info th,
        .delivery-info td {
            padding: 5px;
            text-align: left;
            font-size: 11px;
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
            font-size: 12px;
        }
        .delivery-details th,
        .delivery-details td {
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .clearfix {
            clear: both;
        }
        .my-5 {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="social-dash-wrap">

        @foreach($deliveries as $delivery)
            @php
                $source = $delivery->source;
            @endphp
            
            @if($source)
                <div style="page-break-after: always;">
                    <header class="my-header">
                        @include('partials._for_pdf_header_2nd')
                    </header>

                    <section class="title">
                        <h4>Courier Challan</h4>
                    </section>

                    <section class="delivery-info">
                        <div class="left">
                            <table style="border: 1px solid white;">
                                <tr>
                                    <th style="width: 100px;">Invoice No</th>
                                    <td>: </td>
                                    <td>{{ $source->invoice_id ?? $source->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>: </td>
                                    <td>{{ $source->customer->company_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>: </td>
                                    <td>{{ optional($source->customer->area)->area ?? $source->shipment?->address ?? '' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="right">
                            <table style="border: 1px solid white;">
                                <tr>
                                    <th style="width: 100px;">Date</th>
                                    <td>: </td>
                                    <td>{{ $source->invoice_date ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>: </td>
                                    <td>{{ $delivery->created_at->format('h:i A') ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Print Date & Time</th>
                                    <td>: </td>
                                    <td>{{ now()->format('d-M-Y') }} {{ now()->format('h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
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
                                    <td></td>
                                    <td>Cartoon Medical Goods</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <table style="width: 100%; border:1px solid #000; border-collapse: collapse;" class="my-5">
                        <tbody>
                            <tr>
                                <th colspan="3" style="font-size: 16px"><u>Shipment Address</u></th>
                            </tr>
                            <tr>
                                <th width="30%">Contact Person</th>
                                <th width="5%">:</th>
                                <th width="65%">{{ $delivery->contact_person ?? '' }}</th>
                            </tr>
                            <tr>
                                <th width="30%">Contact Number</th>
                                <th width="5%">:</th>
                                <th width="65%">{{ $delivery->contact_number ?? '' }}</th>
                            </tr>
                            <tr>
                                <th width="30%">Address</th>
                                <th width="5%">:</th>
                                <th width="65%">{{ $delivery->address ?? '' }}</th>
                            </tr>
                            <tr>
                                <th width="30%">Courier Name</th>
                                <th width="5%">:</th>
                                <th width="65%"></th>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top: 30px;">
                        <p><strong>Description :</strong> {{ $delivery->description ?? '' }}</p>
                        @if($delivery->file_uploads ?? false)
                            <p><strong>Files :</strong>
                                @foreach(json_decode($delivery->file_uploads) as $file)
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a>
                                    @if(!$loop->last), @endif
                                @endforeach
                            </p>
                        @endif
                    </div>

                    <table style="width:100%; margin-top:50px; border:none;">
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
            @endif
        @endforeach

    </div>
</div>
</body>
</html>