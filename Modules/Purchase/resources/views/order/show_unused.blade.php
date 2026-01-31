@section('title', 'Purchase Order Details')
@section('description', 'Purchase Order Details')
@extends('layout.app')
@section('content')
@section('page-head')
<style>
    header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header-img {
        position: absolute;
        top: 15px;
        left: 30px;
        width: 100px; /* Adjust the width as needed */
        height: auto;
    }

    header h1 {
        margin: 0;
        font-size: 30px;
        font-weight: bold;
        color: rgb(0, 0, 187);
    }

    header p {
        margin: 5px 0;
        font-size: 12px;
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

                            <header>
                                <img class="header-img" src="{{$company_info->company_logo}}" alt="GME Logo">

                                <h1>{{$company_info->company_name}}</h1>
                                <p>{{$company_info->company_bio}}</p>
                            </header>

                                <div class="row mb-4">
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <b><label for="supplier_id">Supplier Name</label></b><br>
                                            {{ optional($purchaseOrder->supplier)->company_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <b><label for="po_date">PO Date</label></b><br>
                                           {{ date('Y-m-d', strtotime($purchaseOrder->po_date)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <b><label for="supplier_phone">Supplier Phone</label></b><br>
                                            {{  optional($purchaseOrder->supplier)->phone }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <b><label for="company_place">Company Place</label></b><br>
                                            {{  optional($purchaseOrder->supplier)->company_place }}
                                        </div>
                                    </div>
                                                                        
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <b><label for="supplier_address">Supplier Address</label></b><br>
                                            {{ optional($purchaseOrder->supplier)->address }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <b><label for="search_by_brand_id">Brand</label></b><br>
                                            {{ optional($purchaseOrder->brand)->name }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Model</th>
                                                            <th style="width: 15%">Description</th>
                                                            <th style="width: 15%">HS Code</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            {{-- <th style="width: 15%">Price</th>
                                                            <th style="width: 15%">Amount</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($purchaseOrder->detailes as $key => $purchaseOrderDetail)
                                                            <tr>
                                                                <td>
                                                                    {{ $purchaseOrderDetail->model }}
                                
                                                                </td>
                                                                <td> 
                                                                    {{ $purchaseOrderDetail->product_description }}
                                                                </td>
                                                                <td>
                                                                    {{ $purchaseOrderDetail->hs_code }}
                                                                </td>
                                                                <td>
                                                                    {{ $purchaseOrderDetail->quantity }}
                                                                </td>
                                                                {{-- <td>
                                                                    {{ $purchaseOrderDetail->price }}
                                                                </td>
                                                               
                                                                <td> 
                                                                    {{ $purchaseOrderDetail->amount }}
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">
                                                                Total Amount
                                                            </td>
                                                            <td>
                                                                {{ $purchaseOrder->total_amount }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;">Transport Title & Cost</td>
                                                            <td colspan="4" style="text-align: left;">{{ $purchaseOrder->transport_title }}</td>
                                                            <td>{{ $purchaseOrder->transport_cost }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Net Amount</td>
                                                            <td>{{ $purchaseOrder->net_amount }}</td>

                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <b><label for="remarks">Remarks</label></b><br>
                                                        {{ $purchaseOrder->remarks }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h2>Shippling Information</h2>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b><label for="shipping_method">Shipping Method</label></b><br>
                                            {{ $purchaseOrder->shipping_method }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <b> <label for="shipping_terms">Shipping Terms</label></b><br>
                                            {{ $purchaseOrder->shipping_terms }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b><label for="po_date">Delivery Date</label></b><br>
                                            {{ date('Y-m-d', strtotime($purchaseOrder->delivery_date)) }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <a type="submit" href="{{ route('purchase.orders.index') }}" class="btn btn-primary">Back</a>
                                        </div>
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
