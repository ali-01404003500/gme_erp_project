@section('title', 'Stock Balance Report with Costing and Sales Value')
@section('description', 'Stock Balance Report with Costing and Sales Value')
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
                                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                                <li class="breadcrumb-item active">Stock Balance Report</li>
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
                <h4 class="text-capitalize breadcrumb-title">Stock Balance Report with Costing & Sales Value</h4>
            </div>

            <!-- Search & Filter Section -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Search & Filter</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('inv.reports.stock-balance') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label>Product Name</label>
                                    <select name="product_id" class="tom-select" data-placeholder="Select Product">
                                        <option value=""></option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} {{ $product->model ? '(' . $product->model . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Brand Name</label>
                                    <select name="brand_id" class="tom-select" data-placeholder="Select Brand">
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
                                    <label>Branch/Center</label>
                                    <select name="branch_id" class="tom-select" data-placeholder="Select Branch">
                                        <option value="all">All Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Date Range (From - To)</label>
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control flatdate" name="from"
                                            value="{{ request('from') }}" autocomplete="off" placeholder="From" />
                                        <span class="input-group-text">
                                            <i class="fa fa-exchange-alt"></i>
                                        </span>
                                        <input type="text" class="form-control flatdate" name="to"
                                            value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>USD to BDT Rate (Optional)</label>
                                    <input type="number" step="0.01" class="form-control" name="usd_to_bdt_rate" 
                                        value="{{ request('usd_to_bdt_rate') }}" placeholder="110.00">
                                    <small class="text-muted">For conversion display only</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('inv.reports.stock-balance') }}" class="btn btn-warning btn-sm ml-2">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <style>
                                .unit-table-custom,
                                .unit-table-custom th,
                                .unit-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .unit-table-custom th,
                                .unit-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .unit-table-custom thead th {
                                    background-color: #f8f9fa;
                                    border-bottom-width: 2px !important;
                                }

                                .table thead th {
                                    background-color: #35526e !important;
                                    color: #ffffff !important;
                                    font-weight: 600 !important;
                                    text-transform: uppercase;
                                    font-size: 0.85rem !important;
                                    letter-spacing: 0.08em;
                                    border-bottom: 2px solid #2a4054 !important;
                                    padding: 14px 16px !important;
                                    vertical-align: middle;
                                    text-align: center;
                                }
                            </style>
                            <table class="table unit-table-custom dt-table-hover" id="stockBalanceTable" style="font-size: 11px;">
                                <thead class="bg-primary">
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle">SL</th>
                                        <th colspan="2" class="text-center">Product Info</th>
                                        <th rowspan="2" class="text-center align-middle">Current Stock</th>
                                        <th colspan="2" class="text-center">USD Price</th>
                                        <th colspan="2" class="text-center">MRP Price</th>
                                        <th colspan="2" class="text-center">Costing Price</th>
                                        <th colspan="2" class="text-center">Avg. Selling Price (Last 5 Sales)</th>
                                    </tr>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Brand Name</th>
                                        <th class="text-right">Unit Price (USD)</th>
                                        <th class="text-right">Total (USD)</th>
                                        <th class="text-right">MRP Price (BDT)</th>
                                        <th class="text-right">Total (BDT)</th>
                                        <th class="text-right">Costing Price (BDT)</th>
                                        <th class="text-right">Total (BDT)</th>
                                        <th class="text-right">Avg. Selling Price (BDT)</th>
                                        <th class="text-right">Total (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product_model)
                                                    <br><small class="text-muted">Model: {{ $item->product_model }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->brand_name ?? '-' }}</td>
                                            <td class="text-right"><span class="badge badge-round badge-primary">{{ number_format($item->current_stock) }}</span></td>
                                            <td class="text-right">${{ number_format($item->unit_price_usd ?? 0) }}</td>
                                            <td class="text-right font-weight-bold">${{ number_format($item->total_usd) }}</td>
                                            <td class="text-right">৳{{ number_format($item->mrp_price_bdt ?? 0) }}</td>
                                            <td class="text-right text-warning font-weight-bold">৳{{ number_format($item->total_mrp_bdt) }}</td>
                                            <td class="text-right">৳{{ number_format($item->costing_price_bdt ?? 0) }}</td>
                                            <td class="text-right text-secondary font-weight-bold">৳{{ number_format($item->total_costing_bdt) }}</td>
                                            <td class="text-right">৳{{ number_format($item->avg_selling_price_bdt) }}</td>
                                            <td class="text-right text-success font-weight-bold">৳{{ number_format($item->total_avg_sales_bdt) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mb-0">No records found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($reportData->count() > 0)
                                <tfoot>
                                    <tr class="font-weight-bold" style="font-size: 13px;">
                                        <td colspan="5" class="text-right"><strong>TOTALS:</strong></td>
                                        <td class="text-right"><strong>${{ number_format($totals['total_usd']) }}</strong></td>
                                        <td class="text-right">-</td>
                                        <td class="text-right text-warning"><strong>৳{{ number_format($totals['total_mrp_bdt']) }}</strong></td>
                                        <td class="text-right">-</td>
                                        <td class="text-right text-secondary"><strong>৳{{ number_format($totals['total_costing_bdt']) }}</strong></td>
                                        <td class="text-right">-</td>
                                        <td class="text-right text-success"><strong>৳{{ number_format($totals['total_avg_sales_bdt']) }}</strong></td>
                                    </tr>
                                    @if(request('usd_to_bdt_rate'))
                                    <tr class="font-weight-bold" style="font-size: 12px;">
                                        <td colspan="5" class="text-right">
                                            <strong>Total USD in BDT (Rate: {{ number_format(request('usd_to_bdt_rate')) }}):</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>৳{{ number_format($totals['total_usd'] * request('usd_to_bdt_rate')) }}</strong>
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                    @endif
                                </tfoot>
                                @endif
                            </table>
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
$(document).ready(function() {
    // Initialize Tom Select for dropdowns
    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('.tom-select').forEach((el) => {
            new TomSelect(el, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    }

    // Initialize Flatpickr for date inputs
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.flatdate', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }
});
</script>

@endsection