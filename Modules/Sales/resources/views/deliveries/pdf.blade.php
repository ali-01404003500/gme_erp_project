<title>Delivery Invoice</title>
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

            .delivery-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .delivery-info .left,
            .delivery-info .right {
                width: 70%;
            }

            .delivery-info table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }

            .delivery-info th,
            .delivery-info td {
                padding: 2px;
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

            .delivery-details p {
                margin: 5px 0;
                font-size: 12px;
            }

            .delivery-details .totals {
                text-align: right;
            }

            .delivery-details .totals p {
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
                            <h4>Delivery Invoice Bill</h4>
                        </section>

                          <section class="delivery-info">
                            <div class="left" style="width: 70%; float: left;">
                                <table style="border: 1px solid white;">
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Invoice No</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $source->invoice_id ?? $source->id }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Name</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $source->customer->company_name }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Address</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ optional($source->customer->area)->area ?? $source->shipment?->address }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Phone</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $source->shipment?->contact_person_number ?? $source->customer?->phone }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Arranged By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $delivery->arranged_by ?? $delivery->createdBy->name ?? '' }}</th>
                                    </tr>
                                </table>
                            </div>
                            <div class="right" style="width: 30%; float: right;">
                                <table style="border: 1px solid white;">
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Date</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $source->invoice_date }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Time</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $delivery->created_at->format('h:i A') ?? '' }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Sold By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $source->createdBy->name ?? $source->created_by ?? '' }}</th>
                                    </tr>
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Print Date & Time</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ now()->format('d-M-Y') }}
                                            {{ now()->format('h:i A') }}</th>
                                    </tr>
                                    
                                    <tr style="border: 1px solid white;">
                                        <th style="border: 1px solid white; width: 80px;">Checked By</th>
                                        <td style="border: 1px solid white;">:</td>
                                        <th style="border: 1px solid white;">{{ $delivery->updated_by ?? '' }}</th>
                                    </tr>
                                </table>
                            </div>
                            <div style="clear: both;"></div>
                        </section>

                        <section class="delivery-details">
                            <h4 style="font-size: 14px; margin-bottom: 10px;">Products</h4>
                            <table>
                                <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Model</th>
                                            <th>Sales Quantity</th>
                                            <th>Remaining Quantity</th>
                                            <th>Delivered Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($source->details as $key => $detail)
                                            @php
                                                $deliveredQty = $previousDeliveries
                                                    ->flatMap(function ($delivery) {
                                                        return $delivery->deliveryDetails;
                                                    })
                                                    ->where('product_id', $detail->product_id)
                                                    ->sum('quantity');

                                                $remainingQty = $detail->quantity - $deliveredQty;
                                            @endphp
                                            <tr>
                                                <td>{{ $detail->product->name }}</td>
                                                <td>{{ $detail->product->model }}</td>
                                                <td>{{ numberFormat($detail->quantity) }}</td>
                                                <td>{{ numberFormat($remainingQty) }}</td>
                                                <td>{{ numberFormat($detail->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        </section>
                        
                       
                        
                        <div class="row" style="margin-top: 50px;">
                            <p><strong>Description :</strong> {{ $delivery->description ?? '' }}</p>
                            @if($delivery->file_uploads)
                                <p><strong>Files :</strong>
                                    @foreach(json_decode($delivery->file_uploads) as $file)
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a>
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            @endif
                        </div>

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