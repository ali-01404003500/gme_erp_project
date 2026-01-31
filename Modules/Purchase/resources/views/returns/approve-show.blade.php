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
                                            <th>{{ $purchaseReturn->paurchaseReturn->invoice_no }}</th>
                                        </tr>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <td>:</td>
                                            <th>{{ optional($purchaseReturn->paurchaseReturn->supplier)->company_name }}</th>
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
                                            @foreach ($purchaseReturn->paurchaseReturnApproveDetails as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ $item->product->name }}
                                                </td>
                                                <td>
                                                    {{numberFormat($item->quantity)}}
                                                </td>
                                                
                                                <td>
                                                    {{-- @dd($item) --}}
                                                    @if($purchaseReturn->paurchaseReturnApproveDetails->count() == 0)
                                                        <button class="btn btn-xs btn-primary me-1" disabled type="button" >
                                                            <i class="fa fa-list"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-xs btn-primary me-1" type="button" onclick="showDetails(this)" data-modal_url="{{ route('purchase.returns.approve.details', ['p_r_approve_detail_id' => $purchaseReturn->paurchaseReturnApproveDetails->where('product_id', $item->product_id)->first()->id]) }}">
                                                            <i class="fa fa-list"></i>
                                                        </button>
                                                    @endif
                                                    {{-- @if ($item->product->is_expire_date == 'yes')
                                                        <button class="btn btn-xs btn-primary me-1" type="button"
                                                            data-bs-toggle="modal"
                                                            data-product_name="{{ $item->product->name }} {{ $item->product->tag->name }} Model:{{ $item->product->model }} Brand: {{ $item->product->brand->name }}"
                                                            data-product_id="{{ $item->product_id }}"
                                                            data-requisition_id="{{ $receive->requisition->id }}"
                                                            data-bs-target="#createModal">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    @endif
                                                    @if ($item->product->is_serial == 'yes')
                                                        <button type="button"
                                                            class="btn btn-xs btn-primary me-1 createModalSerial"
                                                            data-bs-toggle="modal"
                                                            data-product_name=" {{ $item->product->name }} {{ $item->product->tag->name }} Model:{{ $item->product->model }} Brand: {{ $item->product->brand->name }}"
                                                            data-product_id="{{ $item->product_id }}"
                                                            data-quantity="{{ $item->quantity }}"
                                                            data-index="{{ $loop->index }}"
                                                            data-requisition_id="{{ $receive->requisition->id }}"
                                                            data-bs-target="#createModalSerial">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    @endif --}}
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                                <!-- <p><strong>IN WORD : Taka Twenty Eight Lac Only</strong></p>
                                                    <table>
                                                        <div class="totals">
                                                            <p>Total : <strong>2,800,000.00</strong></p>
                                                            <p>Discount : <strong>0.00</strong></p>
                                                            <p><strong>Grand Total : 2,800,000.00</strong></p>
                                                        </div>
                                                    </table> -->



                            </section>

                            <footer>
                                {{-- <p>Received : {{ $receive->aceptedBy->name }} </p> --}}
                                <p>Authorized ___________________________</p>
                            </footer>

                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('Batch Entry') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="mt-4">
                                        <div class="form-group">
                                            <label for="product_name">Product Name:</label><span id="product_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Batch No</th>
                                                <th>Manufacture No</th>
                                                <th>Lot No</th>
                                                <th>Expired Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="batch_table_body">
                                            <!-- Batch data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade inputForm-modal" id="createModalSerial" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('Serial Entry') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="mt-4">
                                        <div class="form-group">
                                            <label for="product_name">Product Name:</label><span id="product_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Serial No</th>
                                                <th>Dongle ID</th>
                                                <th>Manufacture Date</th>
                                                <th>Image/File</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="serial_table_body">
                                            <!-- Serial data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}


            </div>
            <div class="modal fade inputForm-modal" id="show-stock-details-modal" tabindex="-1" role="dialog"
            aria-labelledby="show-stock-details-modal" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="editModalLabel">
                            <h5 class="modal-title">Detail Stocks </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="save" data-bs-dismiss="modal" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @section('page_scripts')
            <script>
                function showDetails(elem){
                    const url = $(elem).data('modal_url');
                    console.log(url);
                    
                    $('#show-stock-details-modal').find('.modal-body').loadWithSpinner(url);
                    $("#show-stock-details-modal").modal('show');
                }
            </script>
        @endsection
