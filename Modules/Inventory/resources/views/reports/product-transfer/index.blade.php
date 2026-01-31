@section('title', 'Product Transfer Report')
@section('description', 'Product Transfer Report')
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
                                <li class="breadcrumb-item active" aria-current="page">Product Transfer Report</li>
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
                <h4 class="text-capitalize breadcrumb-title">Product Transfer Report</h4>
            </div>

            <!-- Search & Filter Section -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Search & Filter</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('inv.reports.product-transfer') }}">
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
                                    <label>User</label>
                                    <select name="user_id" class="tom-select" data-placeholder="Select User">
                                        <option value=""></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Branch/Center</label>
                                    <select name="branch_id" class="tom-select" data-placeholder="Select Branch">
                                        <option value="">All</option>
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
                                    <label>Stock Type</label>
                                    <select name="stock_type" class="form-control">
                                        <option value="all" {{ request('stock_type') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="in" {{ request('stock_type') == 'in' ? 'selected' : '' }}>In</option>
                                        <option value="out" {{ request('stock_type') == 'out' ? 'selected' : '' }}>Out</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Transfer Type</label>
                                    <select name="transfer_type" class="form-control">
                                        <option value="all" {{ request('transfer_type') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="transfer_by" {{ request('transfer_type') == 'transfer_by' ? 'selected' : '' }}>Transfer By</option>
                                        <option value="received_by" {{ request('transfer_type') == 'received_by' ? 'selected' : '' }}>Received By</option>
                                        <option value="request_by" {{ request('transfer_type') == 'request_by' ? 'selected' : '' }}>Request By</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Transfer Status</label>
                                    <select name="transfer_status" class="form-control">
                                        <option value="all" {{ request('transfer_status') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="transferred" {{ request('transfer_status') == 'transferred' ? 'selected' : '' }}>Transfer Approved</option>
                                        <option value="pending" {{ request('transfer_status') == 'pending' ? 'selected' : '' }}>Transfer Pending</option>
                                        <option value="received" {{ request('transfer_status') == 'received' ? 'selected' : '' }}>Transfer Received</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="button-group d-flex w-100">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fa fa-search"></i> Generate
                                        </button>
                                        <a href="{{ route('inv.reports.product-transfer') }}" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Refresh
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
                            <table class="table" id="zero-config" style="font-size: 12px;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="text-center" style="width: 3%;">SL</th>
                                        <th style="width: 15%;">Product Name</th>
                                        <th class="text-center" style="width: 10%;">Inv Date & Time</th>
                                        <th class="text-center" style="width: 10%;">Transfer Date & Time</th>
                                        <th class="text-center" style="width: 8%;">Quantity</th>
                                        <th style="width: 12%;">Transfer From</th>
                                        <th style="width: 12%;">Transfer To</th>
                                        <th style="width: 10%;">Transfer By</th>
                                        <th style="width: 10%;">Received By</th>
                                        <th style="width: 10%;">Request By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                               
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('inv.product-transfers.show', $item->transfer_id) }}" 
                                                   class="text-primary" target="_blank" title="View Transfer Invoice">
                                                    <strong>{{ $item->invoice_no }}</strong><br>
                                                    <small>{{ \Carbon\Carbon::parse($item->inv_date_time)->format('d-M-Y h:i A') }}</small>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($item->transfer_date)->format('d-M-Y') }}<br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->transfer_date)->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ number_format($item->quantity) }}</span>
                                            </td>
                                            <td>{{ $item->source_branch_name }}</td>
                                            <td>{{ $item->destination_branch_name }}</td>
                                            <td>{{ $item->transferred_by_name ?? '-' }}</td>
                                            <td>{{ $item->received_by_name ?? '-' }}</td>
                                            <td>{{ $item->requested_by_name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mb-0">No transfer records found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($reportData->count() > 0)
                                <tfoot>
                                    <tr class="font-weight-bold" style="font-size: 14px;">
                                        <td colspan="4" class="text-right"><strong>TOTALS:</strong></td>
                                        <td class="text-center">
                                            <strong class="text-primary">{{ number_format($totals['total_quantity']) }}</strong>
                                        </td>
                                        <td colspan="5"></td>
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
  

    .badge-info {
        background-color: #17a2b8;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }

    a.text-primary:hover {
        text-decoration: underline;
    }
</style>
@endsection