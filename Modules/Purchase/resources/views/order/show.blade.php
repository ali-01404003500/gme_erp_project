@section('title', 'Purchase Order Details')
@section('description', 'Purchase Order Details')
@extends('layout.app')
@section('content')
@section('page-head')
    <style>
        .my-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .my-header .logo {
            width: 15%;
        }

        .my-header .logo img {
            max-width: 130px;
        }

        .my-header .company-info {
            width: 100%;
            text-align: center;
        }

        .my-header .company-info h1 {
            margin: 0;
            font-size: 35px;
            font-weight: bold;
            color: rgb(0, 0, 187);
        }

        .my-header .company-info p {
            margin: 5px 0;
            font-size: 12px;
        }

        .order-info {
            width: 100%;
            text-align: right;
            font-size: 12px;
        }

        .order-info p {
            margin: 2px 0;
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

        .supplier-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .supplier-info .supplier,
        .supplier-info .bill-ship {
            width: 48%;
        }

        .supplier-info h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-decoration: underline;
        }

        .supplier-info p {
            margin: 2px 0;
            font-size: 12px;
        }

        .shipping-info {
            margin-bottom: 20px;
        }

        .shipping-info table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .shipping-info th,
        .shipping-info td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .shipping-info th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .product-table {
            margin-bottom: 20px;
        }

        .product-table table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .product-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .notes {
            margin: 20px 0;
            font-size: 12px;
        }

        .notes h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .notes ul {
            margin: 0;
            padding-left: 20px;
        }

        .notes li {
            margin: 5px 0;
        }

        .footer-signatures {
            display: flex;
            justify-content: space-between;
            margin: 50px 0 30px 0;
        }

        .footer-signatures p {
            margin: 10px 0;
            font-size: 14px;
            width: 45%;
            text-align: center;
        }

        .bottom-info {
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
@endsection
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
                                    {{ trans('menu.purchase-order-view-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <a href="{{ route('purchase.orders.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm" style="margin-right: 5px;">
                        <i class="fa fa-list"></i> List</a>
                        <button onclick="printWholePage()" class="btn btn-primary btn-sm"
                                style="margin-right: 5px;">PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 m-2">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.purchase-order-view-menu-title') }}</h4>
                <x-error-alart />
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        {{-- view starts from here --}}
                            {{-- <div class="row">
                                <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-start">
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1">Header</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2" checked>
                                        <label class="custom-control-label" for="customCheck2">Shipping Info</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3" checked>
                                        <label class="custom-control-label" for="customCheck3">Notes</label>
                                    </div>
                                </div>
                            </div> --}}
                        <div id="print-content">
                            <!-- Header Section -->
                            <header class="my-header">
                                <div class="logo">
                                    <img src="{{ $company_info->company_logo }}" alt="GME Logo">
                                </div>
                                <div class="company-info">
                                    <h1>Global Medical Engineering (BD) Ltd.</h1>
                                    <p>Provider of Medical Equipment & Solutions for Hospitals, Clinics And HealthCare Institutes.</p>
                                </div>
                                
                            </header>

                            <!-- Title Section -->
                            <section class="title">
                                <h2>Purchase Order</h2>
                                <div class="order-info">
                                    <p><strong>Date:</strong> {{ $purchaseOrder->po_date }}</p>
                                    <p><strong>PO No:</strong> {{ $purchaseOrder->po_number }}</p>
                                </div>
                            </section>

                            <!-- Supplier and Bill To Information -->
                            <section class="supplier-info">
                                <div class="supplier">
                                    <h3>Supplier:</h3>
                                    <p><strong>{{ @$purchaseOrder->supplier->company_name }}</strong></p>
                                    <p>{{ @$purchaseOrder->supplier->company_place }}</p>
                                    <p>Phone: {{ @$purchaseOrder->supplier->phone }}</p>
                                </div>
                                <div class="bill-ship">
                                    <h3>Bill To & Ship To:</h3>
                                    <p><strong>Global Medical Engineering (BD) Ltd.</strong></p>
                                    <p>Address: 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                </div>
                            </section>

                            <!-- Shipping Information -->
                            <section class="shipping-info">
                                <table>
                                    <tr>
                                        <th>Shipping Method</th>
                                        <th>Shipping Terms</th>
                                        <th>Delivery Date</th>
                                    </tr>
                                    <tr>
                                        <td>{{  $purchaseOrder->shipping_method }}</td>
                                        <td>{{$purchaseOrder->shipping_terms }}</td>
                                        <td>{{ date('d-M-Y', strtotime($purchaseOrder->delivery_date)) }}</td>
                                    </tr>
                                </table>
                            </section>

                            <!-- Product Table -->
                            <section class="product-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">SL</th>
                                            <th style="width: 15%;">Model</th>
                                            <th style="width: 40%;">Product Description</th>
                                            <th style="width: 15%;">HS Code</th>
                                            <th style="width: 10%;">Qty.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($purchaseOrder->detailes as $key => $purchaseOrderDetail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $purchaseOrderDetail->product_model ?? 'N/A' }}</td>
                                            <td>{{ $purchaseOrderDetail->product_description }}</td>
                                            <td>{{ $purchaseOrderDetail->hs_code ?? 'N/A' }}</td>
                                            <td>{{ numberFormat($purchaseOrderDetail->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </section>

                            <!-- Notes Section -->
                            <section class="notes">
                                <h3>Note:</h3>
                                <ul>
                                    <li>1. Please send two copies of your invoice.</li>
                                    <li>2. Enter this order in accordance with the prices, terms, delivery method, and specifications listed above.</li>
                                    <li>3. Please notify us immediately if you are unable to ship as specified.</li>
                                    <li>4. Send all correspondence to: Global Medical Engineering (BD) Ltd 17/2 Topkhana Road (1st Floor), Dhaka-1000</li>
                                    <li>Mobile: +8801711020555 Fax: +88-29576881 e-mail: gmebd@hotmail.com</li>
                                </ul>
                            </section>

                            <!-- Footer Signatures -->
                            <footer class="footer-signatures">
                                <p>Authorized ___________________________</p>
                                <p>Date ___________________________</p>
                            </footer>

                            <!-- Bottom Information -->
                            <div class="bottom-info">
                                <p>Address: 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000, +88 09678 020555, info@gmebd.com web: www.gmebd.com</p>
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
<script>
    $('#customCheck1').change(function() {
        if ($(this).is(':checked')) {
            $('.my-header').show();
            $('.title').show();
        } else {
            $('.my-header').hide();
            $('.title').hide();
        }
    });

    $('#customCheck2').change(function() {
        if ($(this).is(':checked')) {
            $('.shipping-info').show();
        } else {
            $('.shipping-info').hide();
        }
    });

    $('#customCheck3').change(function() {
        if ($(this).is(':checked')) {
            $('.notes').show();
        } else {
            $('.notes').hide();
        }
    });
</script>

<script>
    function printWholePage() {
        var printContents = document.getElementById('print-content').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        // add style by scripting
        var style = document.createElement('style');
        style.textContent = `
        .my-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .my-header .logo {
            width: 15%;
        }

        .my-header .logo img {
            max-width: 80px;
        }

        .my-header .company-info {
            width: 100%;
            text-align: center;
        }

        .my-header .company-info h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: rgb(0, 0, 187);
        }

        .my-header .company-info p {
            margin: 3px 0;
            font-size: 10px;
        }

        .order-info {
            width: 100%;
            text-align: right;
            font-size: 10px;
        }

        .order-info p {
            margin: 1px 0;
        }

        .title {
            text-align: center;
            margin-bottom: 15px;
        }

        .title h2 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
        }

        .supplier-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .supplier-info .supplier,
        .supplier-info .bill-ship {
            width: 48%;
        }

        .supplier-info h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-decoration: underline;
        }

        .supplier-info p {
            margin: 1px 0;
            font-size: 10px;
        }

        .shipping-info {
            margin-bottom: 15px;
        }

        .shipping-info table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .shipping-info th,
        .shipping-info td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }

        .shipping-info th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .product-table {
            margin-bottom: 15px;
        }

        .product-table table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }

        .product-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .notes {
            margin: 15px 0;
            font-size: 9px;
        }

        .notes h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .notes ul {
            margin: 0;
            padding-left: 15px;
        }

        .notes li {
            margin: 2px 0;
        }

        .footer-signatures {
            display: flex;
            justify-content: space-between;
            margin: 40px 0 20px 0;
        }

        .footer-signatures p {
            margin: 5px 0;
            font-size: 12px;
            width: 45%;
            text-align: center;
        }

        .bottom-info {
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    `;
        document.head.appendChild(style);

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
@endsection