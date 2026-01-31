@extends('layout.app')
@section('title',"Supplier Payments")
@section('description',"Supplier Payments")
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
                                        {{ trans('Supplier Payments') }}</li>
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Supplier Payments') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center my-4">
                                    <h1> {{$supplierPayment->supplier->company_name}}</h1>
                                    <span>Phone: {{$supplierPayment->supplier->phone}}, Email: {{$supplierPayment->supplier->email}}</span><br>
                                </div>
                                {{-- @dd($supplierPayment) --}}
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        {{-- <li class="list-group-item"><strong>Customer ID:</strong> {{$supplierPayment->supplier->company_name}}</li> --}}
                                        <li class="list-group-item"><strong>Total VAT:</strong> {{$supplierPayment->total_vat}}</li>
                                        <li class="list-group-item"><strong>Due Amount:</strong> {{$supplierPayment->due_amount}}</li>
                                        <li class="list-group-item"><strong>Advance Amount:</strong> {{$supplierPayment->advance_amount}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Total Amount:</strong> {{$supplierPayment->total_amount}}</li>
                                        <li class="list-group-item"><strong>Total Invoice Amount:</strong> {{$supplierPayment->supplier->total_invoice_amount}}</li>
                                        <li class="list-group-item"><strong>Balance:</strong> {{$supplierPayment->supplier->advance_balance}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-12 my-2">
                                    <h2>Supplier Payment Details</h2>
                                </div>
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th >Invoice ID</th>
                                                <th >Invoice Net Amount</th>
                                                <th >Invoice VAT</th>
                                                <th >Paid VAT</th>
                                                <th >Pay Amount</th>
                                            </tr>
                                           
                                        </thead>
                                        <tbody>
                                            @foreach($supplierPayment->supplierPaymentDetails as $detail)
                                                   <tr>
                                                        <td >{{ $detail->receive->po_receive_number }}</td>
                                                        <td >{{ $detail->receive->net_landed_cost }}</td>
                                                        <td >{{ $detail->invoice_vat }}</td>
                                                        <td >{{ $detail->vat }}</td>
                                                        <td >{{ $detail->pay_amount }}</td>
                                                    </tr>
                                                
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- @dd($supplierP>supplier) --}}
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