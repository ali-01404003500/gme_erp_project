@section('title', 'Center Wise Stock Report')
@section('description', 'Center Wise Stock Report with detailed analysis')
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
                                <li class="breadcrumb-item active" aria-current="page">Center Wise Stock Report</li>
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
                <h4 class="text-capitalize breadcrumb-title">Center Wise Stock Report</h4>
            </div>

            <!-- Filter Section -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Search & Filter</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('inv.reports.center-stock') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label>Branch/Center Name</label>
                                    <select name="branch_id" class="tom-select" data-placeholder="Select Branch">
                                        <option value="all" {{ request('branch_id') == 'all' ? 'selected' : '' }}>ALL</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Choose Brand</label>
                                    <select name="brand" class="tom-select" data-placeholder="Select Brand">
                                        <option value=""></option>
                                        @foreach ($brands as $key => $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Product Name</label>
                                    <select name="product_id" class="tom-select" data-placeholder="Search Product">
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
                                        <a href="{{ route('inv.reports.center-stock') }}" class="btn btn-warning btn-sm ml-2">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Report Title -->
            @if(request()->has('from') || request()->has('to'))
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <strong>Date Range:</strong> 
                    {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d-M-Y') : 'Start' }} 
                    to 
                    {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d-M-Y') : 'Present' }}
                </div>
            </div>
            @endif

            <!-- Report Table -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="centerStockTable">
                                <thead >
                                    <tr>
                                        <th class="text-center" style="width: 5%;">SL</th>
                                        <th style="width: 30%;">Product Name</th>
                                        <th style="width: 15%;">Branch/Center Name</th>
                                        <th class="text-right" style="width: 10%;">Opening Stock</th>
                                        <th class="text-right" style="width: 10%;">Received</th>
                                        <th class="text-right" style="width: 10%;">Delivered</th>
                                        <th class="text-right" style="width: 10%;">Closing/Current Stock</th>
                                        <th class="text-center" style="width: 10%;">Expired Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @forelse($reportData as $item)
                                        @continue($item->current_stock <= 0)
                                        <tr>
                                            <td class="text-center">{{ ++$i }}</td>
                                            <td>
                                                <a href="javascript:void(0)" 
                                                   class="show-product-ledger text-primary font-weight-bold" 
                                                   data-product-id="{{ $item->product_id }}"
                                                   data-branch-id="{{ $item->branch_id }}"
                                                   data-from="{{ request('from') }}"
                                                   data-to="{{ request('to') }}">
                                                    {{ $item->product->name }}
                                                </a>
                                                @if($item->product->model)
                                                    <br><small class="text-muted">Model: {{ $item->product->model }}</small>
                                                @endif
                                                @if($item->product->brand)
                                                    <br><small class="text-muted">Brand: {{ $item->product->brand->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" 
                                                   class="show-center-detail text-info" 
                                                   data-product-id="{{ $item->product_id }}"
                                                   data-branch-id="{{ request('branch_id') }}"
                                                   data-from="{{ request('from') }}"
                                                   data-to="{{ request('to') }}">
                                                    {{ $item->branch->name }}
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-round badge-secondary">{{ number_format($item->opening_stock) }}</span>
                                            </td>
                                            <td class="text-right text-success font-weight-bold">
                                                {{ number_format($item->received) }}
                                            </td>
                                            <td class="text-right text-danger font-weight-bold">
                                                {{ number_format($item->delivered) }}
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-round {{ $item->current_stock > 0 ? 'badge-success' : 'badge-danger' }} p-2">
                                                    {{ number_format($item->current_stock) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                               
                                                <a href="javascript:void(0)" 
                                                class="show-expired-info text-danger" 
                                                        data-product-id="{{ $item->product_id }}"
                                                        data-branch-id="{{ $item->branch_id }}">
                                                    <i class="las la-calendar-times"></i> View
                                            </a>
                                            
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mb-0">No records found. Please select filters and generate report.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($reportData->count() > 0)
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="3" class="text-right"><strong>TOTALS:</strong></td>
                                        <td class="text-right"><strong>{{ number_format($reportData->sum('opening_stock')) }}</strong></td>
                                        <td class="text-right text-success"><strong>{{ number_format($reportData->sum('received')) }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ number_format($reportData->sum('delivered')) }}</strong></td>
                                        <td class="text-right"><strong>{{ number_format($reportData->sum('current_stock')) }}</strong></td>
                                        <td></td>
                                    </tr>
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

<!-- Product Ledger Modal -->
<div class="modal fade" id="productLedgerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Ledger Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Center Detail Modal -->
<div class="modal fade" id="centerDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Center Stock Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Expired Info Modal -->
<div class="modal fade" id="expiredInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Expired/Batch Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
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
    // Initialize Tom Select
    

    // Product Ledger
    $(document).on('click', '.show-product-ledger', function() {
        const productId = $(this).data('product-id');
        const branchId = $(this).data('branch-id');
        const from = $(this).data('from');
        const to = $(this).data('to');
        
        const url = "{{ route('inv.reports.center-stock.product-ledger', ':productId') }}"
            .replace(':productId', productId)
            + `?branch_id=${branchId}&from=${from}&to=${to}`;
        
        $('#productLedgerModal').modal('show');
        $('#productLedgerModal .modal-body').html('<div class="d-flex justify-content-center mt-4"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.get(url, function(data) {
            $('#productLedgerModal .modal-body').html(data);
        }).fail(function() {
            $('#productLedgerModal .modal-body').html('<div class="alert alert-danger">Failed to load data.</div>');
        });
    });

    // Center Detail
    $(document).on('click', '.show-center-detail', function() {
        const productId = $(this).data('product-id');
        const from = $(this).data('from');
        const to = $(this).data('to');
        const branchId = $(this).data('branch-id');
        
        const url = "{{ route('inv.reports.center-stock.center-detail', ':productId') }}"
    .replace(':productId', productId)
    + `?branch_id=${branchId}&from=${from}&to=${to}`;

        
        $('#centerDetailModal').modal('show');
        $('#centerDetailModal .modal-body').html('<div class="d-flex justify-content-center mt-4"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.get(url, function(data) {
            $('#centerDetailModal .modal-body').html(data);
        }).fail(function() {
            $('#centerDetailModal .modal-body').html('<div class="alert alert-danger">Failed to load data.</div>');
        });
    });

    // Expired Info
    $(document).on('click', '.show-expired-info', function() {
        const productId = $(this).data('product-id');
        const branchId = $(this).data('branch-id');
        
        const url = "{{ route('inv.reports.center-stock.expired-info', ':productId') }}"
    .replace(':productId', productId)
    + `?branch_id=${branchId}`;

        
        $('#expiredInfoModal').modal('show');
        $('#expiredInfoModal .modal-body').loadWithSpinner(url)
        
        // $.get(url, function(data) {
        //     $('#expiredInfoModal .modal-body').html(data);
        // }).fail(function() {
        //     $('#expiredInfoModal .modal-body').html('<div class="alert alert-danger">Failed to load data.</div>');
        // });
    });
});
</script>

@endsection