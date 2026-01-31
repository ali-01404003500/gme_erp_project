@extends('layout.app')
@section('title',"Customer Sales")
@section('description',"Customer Sales")
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.customer-receive-payments-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ request()->url() . '/export' }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.customer-receive-payments-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center my-4">
                                    <h1> {{$customerPayment->customer->company_name}}</h1>
                                    <span>Phone: {{$customerPayment->customer->phone}}, Email: {{$customerPayment->customer->email}}</span><br>
                                </div>
                                {{-- @dd($customerPayment) --}}
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        {{-- <li class="list-group-item"><strong>Customer ID:</strong> {{$customerPayment->customer->company_name}}</li> --}}
                                        <li class="list-group-item"><strong>Total VAT:</strong> {{$customerPayment->total_vat}}</li>
                                        <li class="list-group-item"><strong>Due Amount:</strong> {{$customerPayment->due_amount}}</li>
                                        <li class="list-group-item"><strong>Advance Amount:</strong> {{$customerPayment->advance_amount}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Total Amount:</strong> {{$customerPayment->total_amount}}</li>
                                        <li class="list-group-item"><strong>Total Invoice Amount:</strong> {{$customerPayment->customer->total_invoice_amount}}</li>
                                        <li class="list-group-item"><strong>Balance:</strong> {{$customerPayment->customer->advance_balance}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-12 my-2">
                                    <h2>Customer Payment Details</h2>
                                </div>
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Invoice ID</th>
                                                <th rowspan="2">Invoice Net Amount</th>
                                                <th rowspan="2">Invoice VAT</th>
                                                <th rowspan="2">Paid VAT</th>
                                                <th rowspan="2">Pay Amount</th>
                                                <th colspan="4" class="text-center">Invoice Details</th>
                                            </tr>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Unit price</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customerPayment->customerPaymentDetails as $detail)
                                                @foreach ($detail->customerPaymentInvoices as  $customerPaymentInvoice)
                                                   <tr>
                                                    @if($loop->first)
                                                        <td rowspan="{{count($detail->customerPaymentInvoices)}}">{{ $detail->invoice->invoice_id }}</td>
                                                        <td rowspan="{{count($detail->customerPaymentInvoices)}}">{{ $detail->invoice->net_amount }}</td>
                                                        <td rowspan="{{count($detail->customerPaymentInvoices)}}">{{ $detail->invoice_vat }}</td>
                                                        <td rowspan="{{count($detail->customerPaymentInvoices)}}">{{ $detail->vat }}</td>
                                                        <td rowspan="{{count($detail->customerPaymentInvoices)}}">{{ $detail->pay_amount }}</td>
                                                    @endif
                                                        <td>{{$customerPaymentInvoice->product->name}}</td>
                                                        <td>{{$customerPaymentInvoice->quantity}}</td>
                                                        <td>{{$customerPaymentInvoice->unit_price}}</td>
                                                        <td>{{$customerPaymentInvoice->unit_price * $customerPaymentInvoice->quantity}}</td>
                                                    </tr>
                                               
                                                @endforeach
                                                
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- @dd($customerPayment->customerPaymentDetails) --}}
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