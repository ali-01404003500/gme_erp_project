<div class="container-fluid">
    <div class="social-dash-wrap">

        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
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

            .info {
                margin-bottom: 10px;

            }

            .info .right {
                text-align: left;
            }

            .info .DP-right {
                text-align: right;
            }

            .info .left {
                float: left;
                width: 48%;
            }

            .info .right {
                float: right;
                width: 48%;
            }

            .info .clear {
                clear: both;
            }

            .details table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .details th,
            .details td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            .details th {
                background-color: #f2f2f2;
            }

            .note-title {
                margin-bottom: 50px;
                margin-top: 50px;
            }

            .note {
                margin-bottom: 70px;
            }

            .note p {
                margin: 5px 0;
            }

            .footer {
                text-align: center;
                margin-top: 50px;
            }

            .footer p {
                margin: 5px 0;
                font-size: 8px;
            }

            

            .authorized .left {
                float: left;
                border-top: 1px solid #000;
                width: 150px;
            }

            .authorized .right {
                float: right;
                border-top: 1px solid #000;
                width: 150px;
            }

            .section-title {
                font-size: 18px;
                text-decoration: underline;
                margin-bottom: 10px;
                text-align: center;

            }

            .shipping-info {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                margin-top: 30px;
            }

            .shipping-info td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            .shipping-info th {
                background-color: none;
                text-align: left;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
                position: relative;
            }

            .footer p {
                font-size: 13px;
            }
        </style>

        <body>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">

                            <header  class="my-header">
                                @include('partials._for_pdf_header_2nd')
                            </header>
                        <div class="info">
                            <div class="DP-right">
                                <p>Date: {{ $purchaseOrder->po_date }}</p>
                                <p>PO No: {{ $purchaseOrder->po_number }}</p>
                            </div>
                            <div class="clear"></div>
                            <h1 class="section-title">PURCHASE ORDER</h1>
                            <div class="left">
                                <p><strong>Supplier:</strong><br>{{ $purchaseOrder->supplier->company_name }}<br>
                                    Address: {{ $purchaseOrder->supplier->company_place }}</p>
                            </div>
                            <div class="right">
                                <p><strong>Bill To & Ship
                                        To:</strong><br>{{ $company_info->company_name }}<br>{{ $company_info->company_address }}
                                </p>
                            </div>
                            <div class="clear"></div>
                            <table class="shipping-info">
                                <tr>
                                    <th>Shipping Method</th>
                                    <th>Shipping Terms</th>
                                    <th>Delivery Date</th>
                                </tr>
                                <tr>
                                    <td>{{ $purchaseOrder->shipping_method }}</td>
                                    <td>{{ $purchaseOrder->shipping_terms }}</td>
                                    <td>{{ $purchaseOrder->delivery_date }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="details">
                            <table>
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Product Model</th>
                                        <th>Product Description</th>
                                        <th>H.S. Code</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->detailes as $key => $purchaseOrderDetail)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $purchaseOrderDetail->product_model }}

                                            </td>
                                            <td>
                                                {{ $purchaseOrderDetail->product_description }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ $purchaseOrderDetail->hs_code }}
                                            </td>
                                            <td style="text-align:  right;">
                                                {{ $purchaseOrderDetail->quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="note">
                            <div class="note-title">
                                <p><strong>Note:</strong></p>
                            </div>
                            <p>1. Please send two copies of your invoice.</p>
                            <p>2. Enter this order in accordance with the prices, terms, delivery method, and
                                specifications listed above.</p>
                            <p>3. Please notify us immediately if you are unable to ship as specified.</p>
                            <p>4. Send all correspondence to: {{ $company_info->company_name }},
                                {{ $company_info->company_address }}</p>
                            <p>Mobile: {{ $company_info->company_phone }} Fax: {{ $company_info->company_fav }}
                                Email: {{ $company_info->company_email }}</p>
                        </div>
                        <div class="authorized">
                            <div class="left">
                                <p>Authorized</p>
                            </div>
                            <div class="right">
                                <p>Date</p>
                            </div>
                        </div>
                        <div class="footer">
                            <p style="font-size: 10px;">Address : {{ $company_info->company_address }},{{ $company_info->company_phone }},
                                {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

</body>
