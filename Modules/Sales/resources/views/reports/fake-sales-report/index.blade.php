@section('title', 'Fake Sales Report')
@section('description', 'Fake Sales Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">Fake Sales Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                    data-target="#columnFilterModal">
                                    <i class="las la-filter"></i> Column Filter
                                </button>
                                <a href="#" id="pdfExportBtn" class="btn btn-danger btn-sm mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="#" id="excelExportBtn" class="btn btn-success btn-sm">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">Fake Sales Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('sales.reports.fake-sales') }}">
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
                                            @foreach ($fakeInvoices as $invoice)
                                                <option value="{{ $invoice->invoice_number }}"
                                                    {{ request('invoice_id') == $invoice->invoice_number ? 'selected' : '' }}>
                                                    {{ $invoice->invoice_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Username</label>
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
                                        <label>Branch/Center Name</label>
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
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="delivered"
                                                {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>
                                            <option value="undelivered"
                                                {{ request('status') == 'undelivered' ? 'selected' : '' }}>
                                                Undelivered
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Invoice Type</label>
                                        <select name="invoice_type" class="form-control" disabled>
                                            <option value="sales" selected>Sales</option>
                                        </select>
                                    </div>

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('sales.reports.fake-sales') }}" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Refresh
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
                                <div class="d-flex justify-content-end mb-2">
                                    <input type="text" id="tableSearch" class="form-control" style="width: 150px"
                                        placeholder="Search..." style="font-size: 12px;">
                                </div>

                                <table class="table table-hover table-bordered table-sm" id="fakeSalesReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-danger text-white">
                                        <tr>
                                            <th class="text-center col-sl">SL No</th>
                                            <th class="col-invoice-id">Invoice ID</th>
                                            <th class="col-invoice-datetime">Invoice Date & Time</th>
                                            <th class="col-branch">Branch/Center Name</th>
                                            <th class="col-customer">Customer Name</th>
                                            <th class="col-status">Invoice Status</th>
                                            <th class="col-remarks">Remarks</th>
                                            <th class="col-username">Username</th>
                                            <th class="col-reference">Reference Invoice</th>
                                            <th class="col-creation">Creation Date</th>
                                            <th class="col-type">Invoice Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $index => $item)
                                            @php
                                                $data = $item['data'];
                                                $rowNumber =
                                                    ($reportData->currentPage() - 1) * $reportData->perPage() +
                                                    $loop->iteration;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</td>
                                                <td>
                                                    <a href="{{ route('sales.fake-invoices.show', $data->id) }}"
                                                        target="_blank" class="text-danger font-weight-bold">
                                                        {{ $data->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $data->invoice_date }}<br>
                                                    <small
                                                        class="text-muted">{{ $data->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="#" class="text-primary">
                                                        {{ optional($data->customer)->company_name ?? 'N/A' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($data->salesOrder->status == 'pending')
                                                        <span
                                                            class="badge badge-round badge-warning text-capitalize">{{ $data->salesOrder->status }}</span>
                                                    @elseif($data->salesOrder->status == 'approved')
                                                        <span
                                                            class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                    @elseif($data->salesOrder->status == 'delivered')
                                                        <span
                                                            class="badge badge-round badge-info text-capitalize">{{ $data->salesOrder->status }}</span>
                                                    @elseif($data->salesOrder->status == 'partial')
                                                        <span
                                                            class="badge badge-round badge-warning text-capitalize">{{ $data->salesOrder->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $data->remarks ?? '' }}</td>
                                                <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($data->salesOrder)
                                                        <a href="{{ route('sales.sales-orders.show', $data->sales_order_id) }}"
                                                            target="_blank" class="text-primary">
                                                            {{ $data->salesOrder->sales_order_id }}
                                                        </a>
                                                        <br>
                                                        <small
                                                            class="text-muted">({{ $data->salesOrder->invoice_date }})</small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $data->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge badge-danger badge-round">Sales</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $reportData->firstItem() ?? 0 }} to {{ $reportData->lastItem() ?? 0 }} of
                                    {{ $reportData->total() }} entries
                                </div>
                                <div>
                                    {{ $reportData->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
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
                <div class="modal-header bg-danger text-white">
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
                                    'branch' => 'Branch/Center Name',
                                    'customer' => 'Customer Name',
                                    'status' => 'Invoice Status',
                                    'remarks' => 'Remarks',
                                    'username' => 'Username',
                                    'reference' => 'Reference Invoice',
                                    'creation' => 'Creation Date',
                                    'type' => 'Invoice Type',
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
                    <button type="button" class="btn btn-danger" id="applyColumnFilter">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    <script>
        $('#tableSearch').on('keyup', function() {
            let value = $(this).val().toLowerCase();

            $('#fakeSalesReportTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(document).ready(function() {
            var allColumns = [
                'invoice-id', 'invoice-datetime', 'branch', 'customer', 'status',
                'remarks', 'username', 'reference', 'creation', 'type'
            ];

            // Store visible columns - initialize from sessionStorage or default to all columns
            var visibleColumns = sessionStorage.getItem('fakeSalesVisibleColumns') ?
                JSON.parse(sessionStorage.getItem('fakeSalesVisibleColumns')) :
                [...allColumns];

            // Apply stored column filters on page load
            function applyStoredFilters() {
                if (sessionStorage.getItem('fakeSalesVisibleColumns')) {
                    allColumns.forEach(function(colKey) {
                        if (!visibleColumns.includes(colKey)) {
                            var header = $('#fakeSalesReportTable thead th.col-' + colKey);
                            if (header.length) {
                                var idx = header.index();
                                header.hide();
                                $('#fakeSalesReportTable tbody tr').each(function() {
                                    $(this).find('td').eq(idx).hide();
                                });
                            }
                        }
                    });
                }
            }

            // Apply on page load
            applyStoredFilters();

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
                $('#fakeSalesReportTable thead th').show();
                $('#fakeSalesReportTable tbody tr').each(function() {
                    $(this).find('td').show();
                });
            }

            $('#columnFilterModal').on('show.bs.modal', function() {
                allColumns.forEach(function(colKey) {
                    var header = $('#fakeSalesReportTable thead th.col-' + colKey);
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

                // Update visible columns and store in sessionStorage
                visibleColumns = selectedColumns;
                sessionStorage.setItem('fakeSalesVisibleColumns', JSON.stringify(visibleColumns));

                showAllColumns();

                allColumns.forEach(function(colKey) {
                    if (!selectedColumns.includes(colKey)) {
                        var header = $('#fakeSalesReportTable thead th.col-' + colKey);
                        if (header.length) {
                            var idx = header.index();
                            header.hide();
                            $('#fakeSalesReportTable tbody tr').each(function() {
                                $(this).find('td').eq(idx).hide();
                            });
                        }
                    }
                });

                $('#columnFilterModal').modal('hide');
                alert('Column filter applied successfully!');
            });

            // PDF Export with selected columns
            $('#pdfExportBtn').on('click', function(e) {
                e.preventDefault();
                console.log('PDF Export clicked. Visible columns:', visibleColumns);
                var url = new URL(window.location.href);
                url.searchParams.set('export_type', 'pdf');
                url.searchParams.set('columns', visibleColumns.join(','));
                console.log('Opening PDF URL:', url.toString());
                window.open(url.toString(), '_blank');
            });

            // Excel Export with selected columns (opens in new tab to avoid losing state)
            $('#excelExportBtn').on('click', function(e) {
                e.preventDefault();
                console.log('Excel Export clicked. Visible columns:', visibleColumns);
                var url = new URL(window.location.href);
                url.searchParams.set('export_type', 'excel');
                url.searchParams.set('columns', visibleColumns.join(','));
                console.log('Opening Excel URL:', url.toString());

                // Create a temporary link and click it
                var link = document.createElement('a');
                link.href = url.toString();
                link.download = 'Fake_Sales_Report.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>

    <style>
        #fakeSalesReportTable {
            width: 100%;
            font-size: 11px;
        }

        #fakeSalesReportTable th {
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            padding: 8px 6px;
        }

        #fakeSalesReportTable td {
            vertical-align: middle;
            padding: 6px;
        }

        .col-sl {
            width: 50px;
        }

        .col-invoice-id {
            width: 180px;
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

        .col-status {
            width: 100px;
        }

        .col-remarks {
            width: 200px;
        }

        .col-username {
            width: 120px;
        }

        .col-reference {
            width: 150px;
        }

        .col-creation {
            width: 110px;
        }

        .col-type {
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

        #fakeSalesReportTable tbody tr:hover {
            background-color: #fff5f5;
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

            #fakeSalesReportTable {
                font-size: 9px;
            }
        }
    </style>
@endsection
