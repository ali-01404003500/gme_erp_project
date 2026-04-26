@section('title', 'Broker Commission Report')
@section('description', 'Broker Commission Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">Broker Commission Report</li>
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
                    <h4 class="text-capitalize breadcrumb-title">Broker Commission Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('sales.reports.broker-commissions') }}">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Broker Name</label>
                                        <select name="broker_id" class="tom-select" data-placeholder="Select Broker">
                                            <option value=""></option>
                                            @foreach ($brokers as $broker)
                                                <option value="{{ $broker->id }}"
                                                    {{ request('broker_id') == $broker->id ? 'selected' : '' }}>
                                                    {{ $broker->broker_name }}
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
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('sales.reports.broker-commissions') }}" class="btn btn-warning">
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
                                <div class="d-flex justify-content-end mb-2">
                                    <input type="text" id="tableSearch" class="form-control" style="width: 150px"
                                        placeholder="Search..." style="font-size: 12px;">
                                </div>

                                <style>
                                    .broker-commission-table,
                                    .broker-commission-table th,
                                    .broker-commission-table td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
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

                                <table class="table broker-commission-table table-hover table-sm" id="brokerCommissionReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center col-sl">SL No</th>
                                            <th class="col-broker">Broker Name</th>
                                            <th class="col-customer">Customer Name</th>
                                            <th class="col-bank">Bank Info</th>
                                            <th class="col-commission">Commission Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalCommission = 0;
                                        @endphp
                                        @forelse($reportData as $index => $item)
                                            @php
                                                $commission = $item['data'];
                                                $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;
                                                $totalCommission += $commission->amount;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</div>
                                                <td>
                                                    <strong>{{ optional($commission->broker)->broker_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ optional($commission->broker)->broker_phone ?? '' }}</small>
                                                 </div>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                    @if($commission->salesOrder && $commission->salesOrder->customer)
                                                        <strong>{{ $commission->salesOrder->customer->company_name ?? 'N/A' }}</strong><br>
                                                        <small class="text-muted">{{ $commission->salesOrder->customer->address ?? '' }}</small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                 </div>
                                                <td>
                                                    @if($commission->broker && $commission->broker->brokerBank && $commission->broker->brokerBank->count() > 0)
                                                        @foreach($commission->broker->brokerBank as $bankDetail)
                                                            <div class="mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                                                                <strong>{{ $bankDetail->bank_name ?? 'N/A' }}</strong><br>
                                                                <small>A/C: {{ $bankDetail->account_nos ?? '' }}</small><br>
                                                                <small>Branch: {{ $bankDetail->branch_name ?? '' }}</small>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No bank info</span>
                                                    @endif
                                                 </div>
                                                <td class="text-right">
                                                    <strong>{{ numberFormat($commission->amount) }}</strong>
                                                 </div>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                 </div>
                                            </tr>
                                        @endforelse
                                        
                                        @if($reportData->count() > 0)
                                            <tr class="font-weight-bold">
                                                <td colspan="4" class="text-right">Total Commission Amount:</td>
                                                <td class="text-right">{{ numberFormat($totalCommission) }}</div>
                                            </tr>
                                        @endif
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="columnFilterModalLabel">Select Columns to Display</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="columnFilterForm">
                        <div class="row">
                            @php
                                $columns = [
                                    'broker' => 'Broker Name',
                                    'customer' => 'Customer Name',
                                    'bank' => 'Bank Info',
                                    'commission' => 'Commission Amount',
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
        $('#tableSearch').on('keyup', function() {
            let value = $(this).val().toLowerCase();

            $('#brokerCommissionReportTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(document).ready(function() {
            var allColumns = ['broker', 'customer', 'bank', 'commission'];

            var visibleColumns = sessionStorage.getItem('brokerCommissionVisibleColumns') ?
                JSON.parse(sessionStorage.getItem('brokerCommissionVisibleColumns')) :
                [...allColumns];

            function applyStoredFilters() {
                if (sessionStorage.getItem('brokerCommissionVisibleColumns')) {
                    allColumns.forEach(function(colKey) {
                        if (!visibleColumns.includes(colKey)) {
                            var header = $('#brokerCommissionReportTable thead th.col-' + colKey);
                            if (header.length) {
                                var idx = header.index();
                                header.hide();
                                $('#brokerCommissionReportTable tbody tr').each(function() {
                                    $(this).find('td').eq(idx).hide();
                                });
                            }
                        }
                    });
                }
            }

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
                $('#brokerCommissionReportTable thead th').show();
                $('#brokerCommissionReportTable tbody tr').each(function() {
                    $(this).find('td').show();
                });
            }

            $('#columnFilterModal').on('show.bs.modal', function() {
                allColumns.forEach(function(colKey) {
                    var header = $('#brokerCommissionReportTable thead th.col-' + colKey);
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

                visibleColumns = selectedColumns;
                sessionStorage.setItem('brokerCommissionVisibleColumns', JSON.stringify(visibleColumns));

                showAllColumns();

                allColumns.forEach(function(colKey) {
                    if (!selectedColumns.includes(colKey)) {
                        var header = $('#brokerCommissionReportTable thead th.col-' + colKey);
                        if (header.length) {
                            var idx = header.index();
                            header.hide();
                            $('#brokerCommissionReportTable tbody tr').each(function() {
                                $(this).find('td').eq(idx).hide();
                            });
                        }
                    }
                });

                $('#columnFilterModal').modal('hide');
                alert('Column filter applied successfully!');
            });

            $('#pdfExportBtn').on('click', function(e) {
                e.preventDefault();
                var url = new URL(window.location.href);
                url.searchParams.set('export_type', 'pdf');
                url.searchParams.set('columns', visibleColumns.join(','));
                window.open(url.toString(), '_blank');
            });

            $('#excelExportBtn').on('click', function(e) {
                e.preventDefault();
                var url = new URL(window.location.href);
                url.searchParams.set('export_type', 'excel');
                url.searchParams.set('columns', visibleColumns.join(','));

                var link = document.createElement('a');
                link.href = url.toString();
                link.download = 'Broker_Commission_Report.xlsx';
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
        #brokerCommissionReportTable {
            width: 100%;
            font-size: 11px;
        }

        #brokerCommissionReportTable th {
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            padding: 8px 6px;
        }

        #brokerCommissionReportTable td {
            vertical-align: middle;
            padding: 6px;
        }

        .col-sl { width: 60px; }
        .col-broker { width: 200px; }
        .col-customer { width: 250px; }
        .col-bank { width: 250px; }
        .col-commission { width: 150px; }

        .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #brokerCommissionReportTable tbody tr:hover {
            background-color: #f0f8ff;
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

            #brokerCommissionReportTable {
                font-size: 9px;
            }
        }
    </style>
@endsection