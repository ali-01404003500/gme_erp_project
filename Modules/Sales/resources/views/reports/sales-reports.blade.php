@section('title', 'Sales Report')
@section('description', 'Sales Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                    data-target="#columnFilterModal">
                                    <i class="las la-filter"></i> Column Filter
                                </button>
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
                    <h4 class="text-capitalize breadcrumb-title">Sales Report</h4>
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
                                        <label>Customer Name</label>
                                        <select name="customer_id" class="tom-select" data-placeholder="Select Customer">
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }} - {{ $customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Invoice ID</label>
                                        <select name="invoice_id" class="tom-select" data-placeholder="Select Invoice ID">
                                            <option value=""></option>
                                            @foreach ($salesOrders as $order)
                                                <option value="{{ $order->sales_order_id }}"
                                                    {{ request('invoice_id') == $order->sales_order_id ? 'selected' : '' }}>
                                                    {{ $order->sales_order_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Invoice Type</label>
                                        <select name="invoice_type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="delivered"
                                                {{ request('invoice_type') == 'delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="undelivered"
                                                {{ request('invoice_type') == 'undelivered' ? 'selected' : '' }}>
                                                Undelivered</option>
                                            <option value="pending"
                                                {{ request('invoice_type') == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="partial_sales"
                                                {{ request('invoice_type') == 'partial_sales' ? 'selected' : '' }}>Partial
                                                Sales</option>
                                            <option value="free_sales"
                                                {{ request('invoice_type') == 'free_sales' ? 'selected' : '' }}>Free Sales
                                            </option>
                                            <option value="sales_return"
                                                {{ request('invoice_type') == 'sales_return' ? 'selected' : '' }}>Sales
                                                Return</option>
                                            <option value="backup_challan"
                                                {{ request('invoice_type') == 'backup_challan' ? 'selected' : '' }}>
                                                Backup/Challan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Sales Type</label>
                                        <select name="sales_type" class="form-control">
                                            <option value="">All</option>
                                            <option value="general_sales"
                                                {{ request('sales_type') == 'general_sales' ? 'selected' : '' }}>Sales
                                            </option>
                                            <option value="partial_sales"
                                                {{ request('sales_type') == 'partial_sales' ? 'selected' : '' }}>Partial
                                                Sales</option>
                                            <option value="free_sales"
                                                {{ request('sales_type') == 'free_sales' ? 'selected' : '' }}>Free Sales
                                            </option>
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
                                        <label>Product Name</label>
                                        <select name="product_id" class="tom-select" data-placeholder="Select Product">
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Branch Name</label>
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

                                    <div class="col-md-3 mb-3">
                                        <label>Prepared By</label>
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

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('sales.reports.sales-report') }}" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
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
                                <table class="table table-hover table-bordered table-sm" id="salesReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center col-sl">SN</th>
                                            <th class="col-invoice-id">Invoice ID</th>
                                            <th class="col-invoice-datetime">Invoice Date & Time</th>
                                            <th class="col-branch">Branch Name</th>
                                            <th class="col-customer">Customer Name</th>
                                            <th class="col-address">Customer Address</th>
                                            <th class="col-phone">Customer Phone</th>
                                            <th class="col-balance">Customer Balance</th>
                                            <th class="col-status">Invoice Status</th>
                                            <th class="col-remarks">Remarks</th>
                                            <th class="col-prepared">Prepared By</th>
                                            <th class="col-reference">Reference Invoice</th>
                                            <th class="col-creation">Creation Date</th>
                                            <th class="col-type">Invoice Type</th>
                                            <th class="col-discount">Discounts</th>
                                            <th class="col-payment-status">Payment Status</th>
                                            <th class="col-commitment">Commitment Date</th>
                                            <th class="col-quantity">Quantity</th>
                                            <th class="col-center">Sales Center</th>
                                            <th class="col-invoice-amount">Invoice Amount</th>
                                            <th class="col-invoice-only">Invoice Amount (Only)</th>
                                            <th class="col-approved">Approved By</th>
                                            <th class="col-files">Images/Files</th>
                                        </tr>
                                    </thead>
                                    <!-- Replace the tbody section in your blade file with this updated version -->

                                    <tbody>
                                        @php
                                            $totalQuantity = 0;
                                            $totalInvoiceAmount = 0;
                                            $totalInvoiceOnly = 0;
                                        @endphp

                                        @forelse($reportData as $index => $item)
                                            @php
                                                $data = $item['data'];
                                                $invoiceType = $item['invoice_type'];
                                                $invoiceStatus = $item['invoice_status'];
                                                // Calculate the actual row number considering pagination
                                                $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;
                                            @endphp
                                            <tr class="{{ $invoiceType == 'Sales Return' ? 'table-warning' : '' }}">
                                                <td class="text-center">{{ $rowNumber }}</td>
                                                <td>
                                                    @php
                                                        switch ($invoiceType) {
                                                            case 'Sales Return':
                                                                $route = route('sales.sales-returns.show', $data->id);
                                                                $label = $data->invoice_no;
                                                                break;

                                                            case 'Backup/Challan':
                                                                $route = route('sales.backup-challans.show', $data->id);
                                                                $label = $data->invoice_id;
                                                                break;

                                                            default:
                                                                $route = route('sales.sales-orders.show', $data->id);
                                                                $label = $data->sales_order_id;
                                                                break;
                                                        }
                                                    @endphp

                                                    <a href="{{ $route }}" target="_blank"
                                                        class="text-primary font-weight-bold">
                                                        {{ $label }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $invoiceType == 'Sales Return' ? $data->return_date : $data->invoice_date }}<br>
                                                    <small
                                                        class="text-muted">{{ $data->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="#" class="text-primary">
                                                        {{ optional($data->customer)->company_name ?? 'N/A' }}
                                                    </a>
                                                </td>
                                                <td>{{ optional($data->customer)->address ?? 'N/A' }}</td>
                                                <td>{{ optional($data->customer)->phone ?? 'N/A' }}</td>
                                                <!-- Updated Customer Balance Column -->
                                                <td class="text-right">
                                                    @php
                                                        $balance = $item['customer_balance'] ?? 0;
                                                        $balanceClass =
                                                            $balance > 0
                                                                ? 'text-danger'
                                                                : ($balance < 0
                                                                    ? 'text-success'
                                                                    : '');
                                                    @endphp
                                                    <span class="{{ $balanceClass }} font-weight-bold">
                                                        {{ number_format(abs($balance)) }}
                                                        @if ($balance > 0)
                                                            <small class="text-muted">(Dr)</small>
                                                        @elseif($balance < 0)
                                                            <small class="text-muted">(Cr)</small>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusBadgeClass = match ($invoiceStatus) {
                                                            'Delivered' => 'badge-success',
                                                            'Pending' => 'badge-warning',
                                                            'Return' => 'badge-danger',
                                                            'Undelivered' => 'badge-info',
                                                            'Cancelled' => 'badge-dark',
                                                            default => 'badge-secondary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusBadgeClass }} badge-round">{{ $invoiceStatus }}</span>
                                                </td>
                                                <td>{{ $data->remarks ?? '' }}</td>
                                                <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                                                <td>{{ $data->reference_invoice ?? (optional($data->reference)->sales_order_id  ?? '') }} @if($data->reference)({{$data->reference->invoice_date ?? ''}}) @endif
                                                </td>
                                                <td>{{ $data->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @php
                                                        $typeBadgeClass = match ($invoiceType) {
                                                            'General Sales' => 'badge-primary',
                                                            'Partial Sales' => 'badge-warning',
                                                            'Free Sales' => 'badge-success',
                                                            'Sales Return' => 'badge-danger',
                                                            'Backup/Challan' => 'badge-secondary',
                                                            default => 'badge-info',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $typeBadgeClass }} badge-round">{{ $invoiceType }}</span>
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($data->discount ?? 0) }}
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($data->paid_status))
                                                        @if ($data->paid_status == 'paid')
                                                            <span class="badge badge-success badge-round">Paid</span>
                                                        @elseif($data->paid_status == 'due')
                                                            <span class="badge badge-warning badge-round">Due</span>
                                                        @else
                                                            <span class="badge badge-danger badge-round">Unpaid</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-secondary badge-round">N/A</span>
                                                    @endif
                                                </td>
                                                <td><!-- Updated commitment date column in the table body -->

                                                    @if(isset($item['commitment_date']) && $item['commitment_date'])
                                                        {{ \Carbon\Carbon::parse($item['commitment_date'])->format('Y-m-d') }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $qty = 0;
                                                        if ($invoiceType == 'Sales Return') {
                                                            $qty = $data->salesReturnDetails->sum('quantity');
                                                        } elseif ($invoiceType == 'Backup/Challan') {
                                                            $qty = $data->backupChallanDetails->sum('quantity');
                                                        } else {
                                                            $qty = $data->salesOrderDetails->sum('quantity');
                                                        }
                                                        $totalQuantity += $qty;
                                                    @endphp
                                                    {{ $qty }}
                                                </td>
                                                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                                                <td class="text-right font-weight-bold">
                                                    @php
                                                        $amount = $data->net_amount ?? ($data->total_amount ?? 0);
                                                        if ($invoiceType == 'Sales Return') {
                                                            $totalInvoiceAmount -= $amount;
                                                        } else {
                                                            $totalInvoiceAmount += $amount;
                                                        }
                                                    @endphp
                                                    {{ number_format($amount) }}
                                                </td>
                                                <td class="text-right">
                                                    @php
                                                        $amountOnly = $data->total_amount ?? 0;
                                                        if ($invoiceType == 'Sales Return') {
                                                            $totalInvoiceOnly -= $amountOnly;
                                                        } else {
                                                            $totalInvoiceOnly += $amountOnly;
                                                        }
                                                    @endphp
                                                    {{ number_format($amountOnly) }}
                                                </td>
                                                <td>{{ optional($data->approvedBy)->name ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    @if (isset($data->attachments) && $data->attachments)
                                                        <a href="{{ asset($data->attachments) }}" target="_blank"
                                                            class="badge badge-secondary badge-round">
                                                            <i class="fa fa-file"></i> View
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No Files</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="23" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold" style="font-size: 14px;">
                                            <th colspan="17" class="text-right">Summary:</th>
                                            <th class="text-center">{{ number_format($totalQuantity) }}</th>
                                            <th></th>
                                            <th class="text-right">{{ number_format($totalInvoiceAmount) }}</th>
                                            <th class="text-right">{{ number_format($totalInvoiceOnly) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if(method_exists($reportData, 'links'))
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $reportData->firstItem() ?? 0 }} to {{ $reportData->lastItem() ?? 0 }} of
                                    {{ $reportData->total() ?? 0 }} entries
                                </div>
                                <div>
                                    {{ $reportData->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column Filter Modal -->
    <div class="modal fade" id="columnFilterModal" tabindex="-1" aria-labelledby="columnFilterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="columnFilterModalLabel">Select Columns to Display</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="columnFilterForm">
                        <div class="row">
                            @php
                                $columns = [
                                    'invoice-id' => 'Invoice ID',
                                    'invoice-datetime' => 'Invoice Date & Time',
                                    'branch' => 'Branch Name',
                                    'customer' => 'Customer Name',
                                    'address' => 'Customer Address',
                                    'phone' => 'Customer Phone',
                                    'balance' => 'Customer Balance',
                                    'status' => 'Invoice Status',
                                    'remarks' => 'Remarks',
                                    'prepared' => 'Prepared By',
                                    'reference' => 'Reference Invoice',
                                    'creation' => 'Creation Date',
                                    'type' => 'Invoice Type',
                                    'discount' => 'Discounts',
                                    'payment-status' => 'Payment Status',
                                    'commitment' => 'Commitment Date',
                                    'quantity' => 'Quantity',
                                    'center' => 'Sales Center',
                                    'invoice-amount' => 'Invoice Amount',
                                    'invoice-only' => 'Invoice Amount (Only)',
                                    'approved' => 'Approved By',
                                    'files' => 'Images/Files',
                                ];
                            @endphp
                            @foreach ($columns as $key => $label)
                                <div class="col-md-6 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="col_{{ $key }}"
                                            name="columns[]" value="{{ $key }}" checked>
                                        <label class="custom-control-label" for="col_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="applyColumnFilter">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            var allColumns = [
                'invoice-id', 'invoice-datetime', 'branch', 'customer', 'address', 'phone',
                'balance', 'status', 'remarks', 'prepared', 'reference', 'creation',
                'type', 'discount', 'payment-status', 'commitment', 'quantity', 'center',
                'invoice-amount', 'invoice-only', 'approved', 'files'
            ];

            $('#columnFilterModal').modal({
                show: false,
                backdrop: true,
                keyboard: true
            });

            $('button[data-target="#columnFilterModal"]').on('click', function(e) {
                e.preventDefault();
                $('#columnFilterModal').modal('show');
            });

            function showAllColumns() {
                $('#salesReportTable thead th').show();
                $('#salesReportTable tbody tr').each(function() {
                    $(this).find('td').show();
                });
                $('#salesReportTable tfoot th, #salesReportTable tfoot td').show();
            }

            $('#columnFilterModal').on('show.bs.modal', function() {
                allColumns.forEach(function(colKey) {
                    var header = $('#salesReportTable thead th.col-' + colKey);
                    var checkbox = $('#col_' + colKey);
                    if (header.length && header.is(':visible')) {
                        checkbox.prop('checked', true);
                    } else {
                        checkbox.prop('checked', false);
                    }
                });
            });

            $('#applyColumnFilter').on('click', function() {
                var selectedColumns = [];
                $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
                    selectedColumns.push($(this).val());
                });

                showAllColumns();

                allColumns.forEach(function(colKey) {
                    if (!selectedColumns.includes(colKey)) {
                        var header = $('#salesReportTable thead th.col-' + colKey);
                        if (header.length) {
                            var idx = header.index();
                            header.hide();
                            $('#salesReportTable tbody tr').each(function() {
                                $(this).find('td').eq(idx).hide();
                            });
                            $('#salesReportTable tfoot tr').each(function() {
                                $(this).find('th, td').eq(idx).hide();
                            });
                        }
                    }
                });

                $('#columnFilterModal').modal('hide');
                alert('Column filter applied successfully!');
            });

            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>

    <style>
        #salesReportTable {
            width: 100%;
            font-size: 11px;
        }

        #salesReportTable th {
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            padding: 8px 6px;
        }

        #salesReportTable td {
            vertical-align: middle;
            padding: 6px;
        }

        .col-sl {
            width: 40px;
        }

        .col-invoice-id {
            width: 120px;
        }

        .col-invoice-datetime {
            width: 130px;
        }

        .col-branch {
            width: 120px;
        }

        .col-customer {
            width: 150px;
        }

        .col-address {
            width: 180px;
        }

        .col-phone {
            width: 120px;
        }

        .col-balance {
            width: 100px;
        }

        .col-status {
            width: 100px;
        }

        .col-remarks {
            width: 200px;
        }

        .col-prepared {
            width: 100px;
        }

        .col-reference {
            width: 120px;
        }

        .col-creation {
            width: 110px;
        }

        .col-type {
            width: 100px;
        }

        .col-discount {
            width: 80px;
        }

        .col-payment-status {
            width: 100px;
        }

        .col-commitment {
            width: 110px;
        }

        .col-quantity {
            width: 80px;
        }

        .col-center {
            width: 120px;
        }

        .col-invoice-amount {
            width: 120px;
        }

        .col-invoice-only {
            width: 120px;
        }

        .col-approved {
            width: 100px;
        }

        .col-files {
            width: 100px;
        }

        .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        .badge-round {
            border-radius: 999px !important;
            padding: 4px 10px !important;
            display: inline-block;
            line-height: 1.2;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #salesReportTable tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        @media print {

            .breadcrumb-main,
            .card-header,
            .btn,
            .modal,
            .no-print {
                display: none !important;
            }

            #salesReportTable {
                font-size: 9px;
            }
        }
    </style>
@endsection
