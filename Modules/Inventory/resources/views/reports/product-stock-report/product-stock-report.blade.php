@section('title', 'Product Wise Stock Report')
@section('description', 'Product Wise Stock Report')
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
                                <li class="breadcrumb-item active" aria-current="page">Product Wise Stock Report</li>
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
                <h4 class="text-capitalize breadcrumb-title">Product Wise Stock Report</h4>
            </div>

            <!-- Search & Filter Section -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Search & Filter</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('inv.reports.product-stock') }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
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

                                <div class="col-md-4 mb-3">
                                    <label>Branch/Center</label>
                                    <select name="branch_id" class="tom-select" data-placeholder="Select Branch">
                                        <option value=""></option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
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

                               <div class="col-md-12 mb-3">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                    <a href="{{ route('inv.reports.product-stock') }}" class="btn btn-warning btn-sm">
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
                                    .product-type-table-custom,
                                    .product-type-table-custom th,
                                    .product-type-table-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .product-type-table-custom th,
                                    .product-type-table-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .product-type-table-custom thead th {
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
                            <table class="table product-type-table-custom dt-table-hover" id="productStockTable" style="font-size: 12px;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th>Branch/Center</th>
                                        <th>Product Name</th>
                                        <th class="text-right">Stock</th>
                                        <th class="text-right">Physical Stock</th>
                                        <th class="text-right">Avg. Price</th>
                                        <th class="text-right">Last Price</th>
                                        <th class="text-right">Avg. Amount</th>
                                        <th class="text-right">Last Price Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{ $item->branch_name }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->model)
                                                    <br><small class="text-muted">Model: {{ $item->model }}</small>
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($item->current_stock) }}</td>
                                            <td class="text-right">
                                                <span class="badge badge-info">{{ number_format($item->physical_stock) }}</span>
                                            </td>
                                            <td class="text-right">৳{{ number_format($item->avg_price) }}</td>
                                            <td class="text-right">৳{{ number_format($item->last_price) }}</td>
                                            <td class="text-right text-success">৳{{ number_format($item->avg_amount) }}</td>
                                            <td class="text-right text-info">৳{{ number_format($item->last_amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mb-0">No records found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($reportData->count() > 0)
                                <tfoot>
                                    <tr class="font-weight-bold" style="font-size: 14px;">
                                        <td colspan="3" class="text-right"><strong>TOTALS:</strong></td>
                                        <td class="text-right"><strong>{{ number_format($totals['total_stock']) }}</strong></td>
                                        <td class="text-right"><strong>{{ number_format($totals['total_physical_stock']) }}</strong></td>
                                        <td colspan="2" class="text-right"><strong>-</strong></td>
                                        <td class="text-right text-success"><strong>৳{{ number_format($totals['total_avg_amount']) }}</strong></td>
                                        <td class="text-right text-info"><strong>৳{{ number_format($totals['total_last_amount']) }}</strong></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>

                        <!-- Summary Info -->
                        {{-- @if($reportData->count() > 0)
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <strong>Note:</strong> Physical Stock = Opening Stock + Product Received - Product Delivered (Sales Quantity)
                            </div>
                        </div>
                        @endif --}}
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

<style>
    #productStockTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }

    .text-success {
        font-weight: 600;
    }

    .text-info {
        font-weight: 600;
    }
</style>
@endsection