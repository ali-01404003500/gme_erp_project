@section('title', 'Purchase Return Details')
@section('description', 'Purchase Return Details')
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
                                        {{ trans('Purchase Return Details') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                /* body {
                    
                } */

                .invoice-container {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .invoice-container header {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .invoice-container header h1 {
                    margin: 0;
                    font-size: 30px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .invoice-container header p {
                    margin: 5px 0;
                    font-size: 12px;
                }

                .invoice-container .title {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .invoice-container .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .invoice-container .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .invoice-container .requisition-info .left,
                .invoice-container .requisition-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .invoice-container .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .invoice-container .requisition-info th,
                .invoice-container .requisition-info td {
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
            </style>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Purchase Return Details') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4  invoice-container">
                        <div class="card-body">

                            <header>
                                <h1>Global Medical Engineering (BD) Ltd.</h1>
                                <p>Provider of Medical Equipment & Solutions for Hospitals, Clinics And HealthCare
                                    Institutes.</p>
                                <p>Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                <p>Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                                <p>e-mail : <a href="mailto:info@gmebd.com">info@gmebd.com</a> web: <a
                                        href="http://www.gmebd.com">www.gmebd.com</a></p>
                            </header>

                            <section class="title">
                                <h2>Purchase Return Details</h2>
                            </section>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Purchase Return No</th>
                                            <td>:</td>
                                            <th>{{ $purchaseReturn->invoice_no }}</th>
                                        </tr>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <td>:</td>
                                            <th>{{ optional($purchaseReturn->supplier)->company_name }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Return Date</th>
                                            <td>:</td>
                                            <td>{{ $purchaseReturn->created_at->format('d F, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Name</th>
                                            <th>Purchase Return Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($purchaseReturn->purchaseReturnDetails as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ $item->product->name }}
                                                </td>
                                                <td>
                                                    {{numberFormat($item->quantity)}}
                                                </td>
                                                
                                                <td>
                                                        <button class="btn btn-xs btn-primary me-1" disabled type="button" >
                                                            <i class="fa fa-list"></i>
                                                        </button>
                                                    
                                                   
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                              

                            </section>

                            <footer>
                                {{-- <p>Received : {{ $receive->aceptedBy->name }} </p> --}}
                                <p>Authorized ___________________________</p>
                            </footer>

                        </div>
                    </div>
                </div>
              
            </div>
           
        @endsection

        @section('page_scripts')
          
        @endsection
