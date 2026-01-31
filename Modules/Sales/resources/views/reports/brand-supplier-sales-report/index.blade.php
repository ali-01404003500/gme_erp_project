@section('title', 'Brand/Supplier Wise Sales Report')
@section('description', 'Brand/Supplier Wise Sales Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">Brand/Supplier Wise Sales Report
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">Brand/Supplier Wise Sales Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Brand Name <span class="text-danger">*</span></label>
                                        <select name="brand_id" class="tom-select" data-placeholder="Select Brand" required>
                                            <option value=""></option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Product Tag</label>
                                        <select name="product_tag_id" class="tom-select"
                                            data-placeholder="Select Product Tag">
                                            <option value="">All</option>
                                            @foreach ($productTags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    {{ request('product_tag_id') == $tag->id ? 'selected' : '' }}>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Top Range</label>
                                        <select name="top_range" class="form-control">
                                            <option value="all" {{ request('top_range') == 'all' ? 'selected' : '' }}>ALL
                                            </option>
                                            <option value="5" {{ request('top_range') == '5' ? 'selected' : '' }}>Top
                                                Five</option>
                                            <option value="10" {{ request('top_range') == '10' ? 'selected' : '' }}>Top
                                                Ten</option>
                                            <option value="20" {{ request('top_range') == '20' ? 'selected' : '' }}>Top
                                                Twenty</option>
                                            <option value="30" {{ request('top_range') == '30' ? 'selected' : '' }}>Top
                                                Thirty</option>
                                            <option value="50" {{ request('top_range') == '50' ? 'selected' : '' }}>Top
                                                Fifty</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Date Range (From - To) <span class="text-danger">*</span></label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                value="{{ request('from') ?? today() }}" autocomplete="off" placeholder="From"
                                                required />
                                            <span class="input-group-text">
                                                <i class="fa fa-exchange-alt"></i>
                                            </span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                value="{{ request('to') ?? today() }}" autocomplete="off" placeholder="To" required />
                                        </div>
                                    </div>

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('sales.reports.brand-supplier-sales-report') }}"
                                            class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                @if (isset($reportData) && count($reportData) > 0)
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    Brand/Supplier Wise Sales Report
                                    @if (request('brand_id'))
                                        - {{ $brands->find(request('brand_id'))->name ?? '' }}
                                    @endif
                                    @if (request('from') && request('to'))
                                        ({{ request('from') }} to {{ request('to') }})
                                    @endif
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="brandSalesReportTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;">SL</th>
                                                <th style="width: 250px;">Customer Name & Address</th>
                                                <th style="width: 120px;">Phone</th>
                                                <th style="width: 300px;">Total Quantity</th>
                                                <th class="text-right" style="width: 150px;">Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grandTotalQuantity = 0;
                                                $grandTotalAmount = 0;
                                            @endphp

                                            @foreach ($reportData as $index => $customer)
                                                @php
                                                    $totalQuantity = 0;
                                                    $totalKits = 0;
                                                    $isDrawray =
                                                        strtolower($customer['brand_name']) === 'drawray' ||
                                                        strtolower($customer['brand_name']) === 'DRAWRAY';

                                                    // Calculate total quantity
                                                    foreach ($customer['products'] as $product) {
                                                        $totalQuantity += $product['quantity'];
                                                        if ($isDrawray) {
                                                            $totalKits += $product['quantity'] / 20;
                                                        }
                                                    }

                                                    $grandTotalQuantity += $totalQuantity;
                                                    $grandTotalAmount += $customer['total_amount'];
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $customer['customer_name'] }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $customer['customer_address'] }}</small>
                                                    </td>
                                                    <td>{{ $customer['customer_phone'] }}</td>
                                                    <td>
                                                        {{-- Quantity Summary (Right aligned) --}}
                                                        <div class="text-end fw-bold">
                                                            @if ($isDrawray)
                                                                Quantity: {{ number_format($totalQuantity) }} Test ⇔
                                                                {{ number_format($totalKits) }} Kit
                                                            @else
                                                                Quantity: {{ number_format($totalQuantity) }}
                                                            @endif
                                                        </div>

                                                        {{-- Product Quantity Details (Left aligned) --}}
                                                        <div class="text-start mt-2">
                                                            @foreach ($customer['products'] as $product)
                                                                <div class="mb-1">
                                                                    {{ $product['product_name'] }}:
                                                                    @if ($isDrawray)
                                                                        <strong>
                                                                            {{ number_format($product['quantity']) }} Test
                                                                            ⇔
                                                                            {{ number_format($product['quantity'] / 20) }}
                                                                            Kit
                                                                        </strong>
                                                                    @else
                                                                        <strong>
                                                                            {{ number_format($product['quantity']) }}
                                                                            {{ $product['unit_type'] }}
                                                                        </strong>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                    <td>
                                                        {{-- Sales Summary (Right aligned) --}}
                                                        <div class="text-end fw-bold text-primary">
                                                            Sales: {{ number_format($customer['total_amount']) }}
                                                        </div>

                                                        {{-- Product Sales Details (Left aligned) --}}
                                                        <div class="text-start mt-2">
                                                            @foreach ($customer['products'] as $product)
                                                                <div class="mb-1">
                                                                    {{ $product['product_name'] }}:
                                                                    <strong>{{ number_format($product['total_price']) }}</strong>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 18px;">
                                                <th colspan="3" class="text-right">Grand Total:</th>
                                                <th class="text-center">
                                                    @if (isset($isDrawray) && $isDrawray)
                                                        {{ number_format($grandTotalQuantity) }} Test ⇔
                                                        {{ number_format($grandTotalQuantity / 20) }} Kit
                                                    @else
                                                        {{ number_format($grandTotalQuantity) }}
                                                    @endif
                                                </th>
                                                <th class="text-right text-primary">
                                                    {{ number_format($grandTotalAmount) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(request()->has('brand_id'))
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body text-center py-5">
                                <i class="las la-inbox" style="font-size: 64px; color: #ddd;"></i>
                                <p class="mb-0 text-muted">No sales records found for the selected filters</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <style>
        #brandSalesReportTable {
            font-size: 13px;
        }

        #brandSalesReportTable th {
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            padding: 10px;
        }

        #brandSalesReportTable td {
            vertical-align: top;
            padding: 10px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }


        @media print {

            .breadcrumb-main,
            .card-header,
            .btn,
            .no-print {
                display: none !important;
            }

            #brandSalesReportTable {
                font-size: 11px;
            }
        }
    </style>
@endsection
