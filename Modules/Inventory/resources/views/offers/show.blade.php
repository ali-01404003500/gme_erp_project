@extends('layout.app')
@section('title', 'Add Offer')
@section('description', 'Add Offer')
@section('page-head')
@endsection
{{-- @dd($offer) --}}
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
                                        {{ trans('menu.offer-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.offers.index'))
                                    <a href="{{ route('inv.offers.index') }}"
                                        class="btn btn-sm btn-primary btn-add">
                                        {{ trans('menu.offer-list-menu-title') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.offer-view-menu-title') }}</h4>
                            {{-- <x-error-alart /> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="row">

                                {{-- <h3>{{ trans('menu.offer-view-menu-title') }}</h3> --}}
                                
                                <div class="col-md-12 mb-4 p-5">
                                    <table class="outer-table" style="width: 100%; font-size: 10px !important;">
                                        <tr>
                                            <td style="vertical-align: top; width: 50%;">
                                                <table class="table basic-style" style="width: 100%; font-size: 10px !important;">
                                                    <tr>
                                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Offer</td>
                                                        <td style="text-align: left" width="2%">:</td>
                                                        <td style="text-align: left;" width="47%">{{ $offer->title }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Applied Date</td>
                                                        <td style="text-align: left" width="2%">:</td>
                                                        <td style="text-align: left;" width="47%">{{ $offer->applied_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Stop Date</td>
                                                        <td style="text-align: left" width="2%">:</td>
                                                        <td style="text-align: left;" width="47%">{{ $offer->stop_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Times</td>
                                                        <td style="text-align: left" width="2%">:</td>
                                                        <td style="text-align: left;" width="47%">{{ $offer->times }}</td>
                                                    </tr>
                                                    
                                                </table>
                                            </td>
                                            <td style="vertical-align: top; width: 50%;">
                                                <table class="table basic-style" style="width: 100%; font-size: 10px !important;">
                                                
                                                    <tr>
                                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Offer Type</td>
                                                        <td style="text-align: left" width="2%">:</td>
                                                        <td style="text-align: left;" width="47%">{{ $offer->offer_type }}</td>
                                                    </tr>
                                                <tr>
                                                    <td style="text-align: left; border: none; font-weight: bold;" width="47%">Invoice Type</td>
                                                    <td style="text-align: left" width="2%">:</td>
                                                    <td style="text-align: left; text-align: left" width="47%">
                                                        {{ $offer->invoice_type }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                                        Rule Status</td>
                                                    <td style="text-align: left" width="2%">:</td>
                                                    <td style="text-align: left; text-align: left" width="47%">
                                                        {{ $offer->rule_status }}</td>
                                                </tr>
            
                                                <tr>
                                                    <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                                        Rule Type</td>
                                                    <td style="text-align: left" width="2%">:</td>
                                                    <td style="text-align: left; text-align: left" width="47%">
                                                        {{ $offer->rule_type }}</td>
                                                </tr>
            
                                                {{-- <tr>
                                                    <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                                        Status</td>
                                                    <td style="text-align: left" width="2%">:</td>
                                                    <td style="text-align: left; text-align: left" width="47%">
                                                        {{ $offer->status }}</td>
                                                </tr> --}}
            
                                            </table>
                                        </td>
                                    </tr>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <label>Offers Details</label>
                                    {{-- @dd($offer) --}}
                                    {{-- @foreach ($offers as $offer) --}}
                                    @if ($offer->offer_type == 'gift')
                                        @foreach($offer->offerDetails as $offerDetail)
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Brand</th>
                                                    <th>Quantity</th>
                                                    <th>Offered Product</th>
                                                    <th>Offered Brand</th>
                                                    <th>Offer Quantity</th>
                                                </tr>
                                                @php
                                                    $giftSalesProducts = $offerDetail->giftSalesProducts;
                                                    $giftOfferProducts = $offerDetail->giftOfferProducts;
                                                    $totalProducts = count($giftSalesProducts) + count($giftOfferProducts);
                                                    $firstGiftSalesProduct = true;
                                                @endphp
                                                @foreach ($giftSalesProducts as $giftSalesProduct)
                                                {{-- @dd($giftSalesProduct) --}}
                                                    <tr>
                                                        <td>{{ $giftSalesProduct->product->name }}</td>
                                                        <td>{{ $giftSalesProduct->product->brand->name }}</td>
                                                        <td>{{ $giftSalesProduct->quantity }}</td>
                                                        @if ($firstGiftSalesProduct)
                                                            <td rowspan="{{ $totalProducts }}">
                                                                @foreach ($giftOfferProducts as $giftOfferProduct)
                                                                    {{ $giftOfferProduct->product->name }}<br>
                                                                @endforeach
                                                            </td>
                                                            <td rowspan="{{ $totalProducts }}">
                                                                @foreach ($giftOfferProducts as $giftOfferProduct)
                                                                    {{ $giftOfferProduct->product->brand->name }}<br>
                                                                @endforeach
                                                            </td>
                                                            <td rowspan="{{ $totalProducts }}">
                                                                @foreach ($giftOfferProducts as $giftOfferProduct)
                                                                    {{ $giftOfferProduct->quantity }}<br>
                                                                @endforeach
                                                            </td>
                                                            @php $firstGiftSalesProduct = false; @endphp
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                @if ($firstGiftSalesProduct)
                                                    @foreach ($offerDiscounts??[] as $giftOfferProduct)
                                                        <tr>
                                                            <td>{{ $giftOfferProduct->product->name }}</td>
                                                            <td>{{ $giftOfferProduct->product->brand->name }}</td>
                                                            <td>{{ $giftOfferProduct->quantity }}</td>
                                                            @if ($loop->first)
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->product->name }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->product->brand->name }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->quantity }}<br>
                                                                    @endforeach
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                    @endforeach
                                        @elseif ($offer->offer_type == 'discount')
                                            @foreach($offer->offerDetails as $offerDetail)
                                            {{-- @dd($offerDetail) --}}
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Brand</th>
                                                    <th>Quantity</th>
                                                    <th>Discount type</th>
                                                    <th>Amount</th>
                                                </tr>
                                                @php
                                                    $giftSalesProducts = $offerDetail->discountSalesProducts;
                                                    $offerDiscounts = $offerDetail->offerDiscounts;
                                                    $totalProducts =
                                                        count($giftSalesProducts) + count($offerDiscounts);
                                                    $firstGiftSalesProduct = true;
                                                @endphp
                                                @foreach ($giftSalesProducts as $giftSalesProduct)
                                                {{-- @dd($giftSalesProduct) --}}
                                                    <tr>
                                                        <td>{{ $giftSalesProduct->product->name }}</td>
                                                        <td>{{ $giftSalesProduct->product->brand->name }}</td>
                                                        <td>{{ $giftSalesProduct->sales_quentity }}</td>
                                                        @if ($firstGiftSalesProduct)
                                                            <td rowspan="{{ $totalProducts }}">
                                                                @foreach ($offerDiscounts as $giftOfferProduct)
                                                                    {{-- @dd($giftOfferProduct) --}}
                                                                    {{ $giftOfferProduct->discount_type}}<br>
                                                                @endforeach
                                                            </td>
                                                            <td rowspan="{{ $totalProducts }}">
                                                                @foreach ($offerDiscounts as $giftOfferProduct)
                                                                    {{ $giftOfferProduct->discount_quentity }}<br>
                                                                @endforeach
                                                            </td>
                                                            @php $firstGiftSalesProduct = false; @endphp
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                {{-- @if ($firstGiftSalesProduct)
                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                        <tr>
                                                            <td>{{ $giftOfferProduct->product->name }}</td>
                                                            <td>{{ $giftOfferProduct->product->brand->name }}</td>
                                                            <td>{{ $giftOfferProduct->quantity }}</td>
                                                            @if ($loop->first)
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->product->name }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->product->brand->name }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td rowspan="{{ $totalProducts }}">
                                                                    @foreach ($offerDiscounts as $giftOfferProduct)
                                                                        {{ $giftOfferProduct->quantity }}<br>
                                                                    @endforeach
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif --}}
                                            </table>
                                        @endforeach
                                    @endif
                                    {{-- @endforeach --}}
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
