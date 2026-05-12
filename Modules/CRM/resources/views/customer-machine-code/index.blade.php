@section('title', 'Customer List (Machine Code) Report')
@section('description', 'Customer List (Machine Code) Report with Sales and Payment Information')
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
                                    <li class="breadcrumb-item active" aria-current="page">Customer List (Machine Code) Report</li>
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
                    <h4 class="text-capitalize breadcrumb-title">Customer List (Machine Code) Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('crm.reports.customer-machine-code') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Product Type</label>
                                        <select name="product_type_id" class="tom-select" data-placeholder="Select Product Type">
                                            <option value="all" {{ request('product_type_id') == 'all' ? 'selected' : '' }}>All</option>
                                            @foreach ($productTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ request('product_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Division</label>
                                        <select name="division_id" class="tom-select" data-placeholder="Select Division">
                                            <option value=""></option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>District</label>
                                        <select name="district_id" class="tom-select" data-placeholder="Select District">
                                            <option value=""></option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('crm.reports.customer-machine-code') }}" class="btn btn-warning">
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
                            <!-- Total Due Balance at Top -->
                           

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm" id="customerMachineReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th>Customer Name</th>
                                            <th>Phone No</th> 
                                            <th class="screen-only">Last 6 Months Sales</th>
                                            <th class="screen-only">Last Payment Info</th>
                                            <th class="text-right">Due Balance ( ৳{{ number_format($totalDueBalance) }})</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalDue = 0;
                                        @endphp

                                        @forelse($reportData as $index => $customer)
                                            @php
                                                $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;
                                                $totalDue += $customer['due_balance'];
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</td>
                                                <td>
                                                    <a href="#" class="text-primary font-weight-bold"
                                                        data-toggle="modal" data-target="#customerLedgerModal"
                                                        data-customer-id="{{ $customer['customer_id'] }}">
                                                        {{ $customer['customer_name'] }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted"><i class="las la-map-marker me-1"></i> {!! wordwrap($customer['address'], 40, '<br>', true) !!}</small> 
                                                </td>
                                                <td>{{ $customer['phone'] ?? 'N/A' }}</td>
                                                <td class="screen-only">
                                                    @if($customer['last_6_months_sales']->count() > 0)
                                                        <ul class="list-unstyled mb-0" style="font-size: 10px;">
                                                            @foreach($customer['last_6_months_sales'] as $sale)
                                                                <li>
                                                                    <strong>{{ $sale['month'] }}</strong> – 
                                                                    ৳{{ number_format($sale['amount'], 0) }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="text-muted">No sales</span>
                                                    @endif
                                                </td>
                                                <td class="screen-only">
                                                    @if($customer['last_payments']->count() > 0)
                                                        <ul class="list-unstyled mb-0" style="font-size: 10px;">
                                                            @foreach($customer['last_payments'] as $payment)
                                                                <li>
                                                                    <strong>{{ $payment['month'] }}</strong> – 
                                                                    ৳{{ number_format($payment['amount'], 0) }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="text-muted">No payments</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if($customer['receivable_balance'] > 0)
                                                        <div class="text-danger" style="font-size: 10px;">
                                                            <strong>Receivable:</strong> ৳{{ number_format($customer['receivable_balance']) }}
                                                        </div>
                                                    @endif
                                                    @if($customer['advance_balance'] > 0)
                                                        <div class="text-success" style="font-size: 10px;">
                                                            <strong>Advance:</strong> ৳{{ number_format($customer['advance_balance']) }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-1">
                                                        <strong class="{{ $customer['due_balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                                                            Net Due: ৳{{ number_format($customer['due_balance']) }}
                                                        </strong>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($reportData->count() > 0)
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 14px">
                                                <td colspan="5" class="text-right"><strong>Due Balance:</strong></td>
                                                <td class="text-right">
                                                    <strong class="text-danger">৳{{ number_format($totalDue) }}</strong>
                                                </td>
                                            </tr>
                                            <tr class="font-weight-bold" style="font-size: 18px">
                                                <td colspan="5" class="text-right"><strong>Total Due Balance:</strong></td>
                                                <td class="text-right">
                                                    <strong class="text-danger">৳{{ number_format($totalDueBalance) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            
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

    <!-- Customer Ledger Modal -->
    <div class="modal fade" id="customerLedgerModal" tabindex="-1" aria-labelledby="customerLedgerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="customerLedgerModalLabel">Customer Ledger</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="customerLedgerContent">
                        <div class="text-center py-5">
                            <i class="las la-spinner la-spin" style="font-size: 48px;"></i>
                            <p>Loading customer ledger...</p>
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
            // Customer ledger modal
            $('#customerLedgerModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const customerId = button.data('customer-id');

                $('#customerLedgerContent').html(`
                    <div class="text-center py-5">
                        <i class="las la-spinner la-spin" style="font-size: 48px;"></i>
                        <p>Loading customer ledger...</p>
                    </div>
                `);

                $.ajax({
                    url: `/crm/customers/${customerId}/ledger`,
                    method: 'GET',
                    success: function(response) {
                        $('#customerLedgerContent').html(response);
                    },
                    error: function() {
                        $('#customerLedgerContent').html(`
                            <div class="alert alert-danger">
                                Failed to load customer ledger. Please try again.
                            </div>
                        `);
                    }
                });
            });
        });
    </script>

    <style>
        .screen-only {
            /* This class will be hidden in print/export */
        }

        #customerMachineReportTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        @media print {
            .screen-only {
                display: none !important;
            }
        }
    </style>
@endsection