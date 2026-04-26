@section('title', 'Customer Balance Details Report')
@section(
    'description',
    'Comprehensive customer balance report with opening, sales, returns, collections, and closing
    balances'
)
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
                                    <li class="breadcrumb-item active" aria-current="page">Customer Balance Details Report
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
                    <h4 class="text-capitalize breadcrumb-title">Customer Balance Details Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('crm.reports.customer-balance-details') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Search Customer</label>
                                        <select name="search" id="company_name" class="form-control tom-select"
                                            data-placeholder="Select Customer">
                                            <option value=""></option>
                                            @foreach ($customersearch as $key => $value)
                                                <option {{ request('search') == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}">
                                                    {{ $value->company_name }} ({{ $value->area?->area }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>Due Type</label>
                                        <select name="due_type" class="form-control">
                                            <option value="all" {{ ($filters['due_type'] ?? '') == 'all' ? 'selected' : '' }}>ALL</option>
                                            <option value="machine_code" {{ ($filters['due_type'] ?? '') == 'machine_code' ? 'selected' : '' }}>MACHINE CODE</option>
                                            <option value="old_due" {{ ($filters['due_type'] ?? '') == 'old_due' ? 'selected' : '' }}>OLD DUE</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>Start Date</label>
                                        <input type="text" name="start_date" class="form-control flatdate"
                                            value="{{ $filters['start_date'] ?? date('Y-m-01') }}">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>End Date</label>
                                        <input type="text" name="end_date" class="form-control flatdate"
                                            value="{{ $filters['end_date'] ?? date('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>Recovery %</label>
                                        <select name="recovery_percentage" class="form-control">
                                            <option value="">All</option>
                                            <option value="below_10" {{ ($filters['recovery_percentage'] ?? '') == 'below_10' ? 'selected' : '' }}>Below 10%</option>
                                            <option value="10_20" {{ ($filters['recovery_percentage'] ?? '') == '10_20' ? 'selected' : '' }}>10-20%</option>
                                            <option value="21_30" {{ ($filters['recovery_percentage'] ?? '') == '21_30' ? 'selected' : '' }}>21-30%</option>
                                            <option value="31_40" {{ ($filters['recovery_percentage'] ?? '') == '31_40' ? 'selected' : '' }}>31-40%</option>
                                            <option value="41_50" {{ ($filters['recovery_percentage'] ?? '') == '41_50' ? 'selected' : '' }}>41-50%</option>
                                            <option value="51_60" {{ ($filters['recovery_percentage'] ?? '') == '51_60' ? 'selected' : '' }}>51-60%</option>
                                            <option value="61_70" {{ ($filters['recovery_percentage'] ?? '') == '61_70' ? 'selected' : '' }}>61-70%</option>
                                            <option value="71_80" {{ ($filters['recovery_percentage'] ?? '') == '71_80' ? 'selected' : '' }}>71-80%</option>
                                            <option value="above_80" {{ ($filters['recovery_percentage'] ?? '') == 'above_80' ? 'selected' : '' }}>Above 80%</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Division</label>
                                        <select name="division_id" class="tom-select" data-placeholder="Select Division">
                                            <option value=""></option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
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
                                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('crm.reports.customer-balance-details') }}" class="btn btn-warning">
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
                        <div class="card-header">
                            <h5 class="mb-0 text-center">Customer Balance Details Report</h5>
                            <p class="mb-0 text-center text-muted">
                                From: {{ $filters['start_date'] ?? date('Y-m-01') }} To: {{ $filters['end_date'] ?? date('Y-m-d') }}
                            </p>
                            @if(isset($reportData) && method_exists($reportData, 'total'))
                                <p class="mb-0 text-center text-info">
                                    Showing {{ $reportData->firstItem() }} to {{ $reportData->lastItem() }} of {{ $reportData->total() }} customers
                                </p>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <style>
                                .condition-table-custom {
                                    width: 100% !important;
                                    margin-bottom: 0 !important;
                                }
                                .condition-table-custom th,
                                .condition-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    padding: 10px 15px !important;
                                    vertical-align: middle !important;
                                    font-size: 0.875rem;
                                }
                                .condition-table-custom thead th {
                                    background-color: #f8f9fa;
                                    white-space: nowrap;
                                    font-weight: 700;
                                }
                                .table-responsive::-webkit-scrollbar {
                                    height: 8px;
                                }
                                .table-responsive::-webkit-scrollbar-thumb {
                                    background: #ccc;
                                    border-radius: 4px;
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
                                .custom-pagination-container {
                                    margin-top: 30px;
                                    display: flex;
                                    justify-content: center;
                                }

                                .custom-pagination {
                                    display: flex;
                                    flex-direction: row;
                                    list-style: none;
                                    padding: 0;
                                    margin: 0;
                                    gap: 8px;
                                    flex-wrap: wrap;
                                    justify-content: center;
                                }

                                .custom-pagination li {
                                    display: inline-block;
                                    list-style: none;
                                }

                                .custom-pagination li a,
                                .custom-pagination li span {
                                    display: block;
                                    padding: 8px 14px;
                                    background: #ffffff;
                                    border: 1px solid #e2e8f0;
                                    border-radius: 8px;
                                    color: #4a5568;
                                    text-decoration: none;
                                    font-size: 14px;
                                    font-weight: 500;
                                    transition: all 0.2s ease;
                                    cursor: pointer;
                                    min-width: 42px;
                                    text-align: center;
                                }

                                .custom-pagination li.active span {
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white;
                                    border-color: #667eea;
                                }

                                .custom-pagination li:not(.disabled):not(.active) a:hover {
                                    background: #edf2f7;
                                    border-color: #cbd5e0;
                                    transform: translateY(-1px);
                                }

                                .custom-pagination li.disabled span {
                                    background: #f7fafc;
                                    color: #a0aec0;
                                    cursor: not-allowed;
                                    opacity: 0.6;
                                }

                                /* ডটস স্টাইল */
                                .custom-pagination li.dots span {
                                    background: transparent;
                                    border: none;
                                    cursor: default;
                                }

                                @media (max-width: 640px) {
                                    .custom-pagination {
                                        gap: 5px;
                                    }
                                    .custom-pagination li a,
                                    .custom-pagination li span {
                                        padding: 5px 10px;
                                        font-size: 12px;
                                        min-width: 34px;
                                    }
                                }
                                </style>

                                <table class="table condition-table-custom dt-table-hover" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%;">SL</th>
                                            <th style="width: 20%;">Customer</th>
                                            <th class="text-right" style="width: 10%;">Opening Balance</th>
                                            <th class="text-right" style="width: 10%;">Sales</th>
                                            <th class="text-right" style="width: 10%;">Sales Return</th>
                                            <th class="text-right" style="width: 10%;">Collection</th>
                                            <th class="text-right" style="width: 10%;">Charge</th>
                                            <th class="text-right" style="width: 10%;">Waiver</th>
                                            <th class="text-right" style="width: 10%;">Due</th>
                                            <th class="text-right" style="width: 10%;">Closing Balance</th>
                                            <th class="text-center" style="width: 10%;">Recovery %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $index => $customer)
                                                                                    <tr>
                                                                                        <td class="text-center">
                                                                                            {{ isset($reportData) && method_exists($reportData, 'currentPage') ?
                                                ($reportData->currentPage() - 1) * $reportData->perPage() + $index + 1 :
                                                $index + 1 }}
                                                                                        </td>
                                                                                        <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                                                            <a target="_blank" href="{{ route('account.report.customer-ledger', [
                                                'account_id' => $customer['account_id'],
                                                'from' => '2021-10-05',
                                                'to' => date('Y-m-d'),
                                            ]) }}">
                                                                                                {{ $customer['customer_name'] }}
                                                                                            </a>
                                                                                            <br>
                                                                                            <small class="text-muted">{!! wordwrap($customer['address'] ?? '', 60, '<br>', true) !!}</small>
                                                                                            <br>
                                                                                            <small class="text-muted">{{ $customer['phone'] ?? 'N/A' }}</small>
                                                                                            @if ($customer['has_machine_code'] ?? false)
                                                                                                <span class="badge badge-round badge-success badge-sm ml-2">
                                                                                                    <i class="las la-key"></i> Machine Code
                                                                                                </span>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-right">৳{{ number_format($customer['opening_balance'] ?? 0) }}</td>
                                                                                        <td class="text-right">৳{{ number_format($customer['sales'] ?? 0) }}</td>
                                                                                        <td class="text-right">৳{{ number_format($customer['sales_return'] ?? 0) }}</td>
                                                                                        <td class="text-right">৳{{ number_format(($customer['collection'] ?? 0) - ($customer['sales_return'] ?? 0) - ($customer['waiver'] ?? 0)) }}</td>
                                                                                        <td class="text-right">৳{{ number_format($customer['charge'] ?? 0) }}</td>
                                                                                        <td class="text-right">৳{{ number_format($customer['waiver'] ?? 0) }}</td>
                                                                                        <td class="text-right">
                                                                                            <span class="{{ ($customer['due'] ?? 0) >= 0 ? 'text-danger' : 'text-success' }}">
                                                                                                ৳{{ number_format($customer['due'] ?? 0) }}
                                                                                            </span>
                                                                                        </td>
                                                                                        <td class="text-right">
                                                                                            <strong class="{{ ($customer['closing_balance'] ?? 0) >= 0 ? 'text-danger' : 'text-success' }}">
                                                                                                ৳{{ number_format($customer['closing_balance'] ?? 0) }}
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <span class="badge badge-round badge-{{ ($customer['recovery_percentage'] ?? 0) >= 70 ? 'success' : (($customer['recovery_percentage'] ?? 0) >= 40 ? 'warning' : 'danger') }}">
                                                                                                {{ number_format($customer['recovery_percentage'] ?? 0) }}%
                                                                                            </span>
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
                                    @if(isset($reportData) && $reportData->count() > 0 && isset($totals))
                                        <tfoot>
                                            <tr style="font-weight: bold; background-color: #f8f9fa;">
                                                <td colspan="2" class="text-right"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right text-primary"><strong>৳{{ number_format($totals['total_opening_balance'] ?? 0) }}</strong></td>
                                                <td class="text-right text-success"><strong>৳{{ number_format($totals['total_sales'] ?? 0) }}</strong></td>
                                                <td class="text-right text-warning"><strong>৳{{ number_format($totals['total_sales_return'] ?? 0) }}</strong></td>
                                                <td class="text-right text-info"><strong>৳{{ number_format(($totals['total_collection'] ?? 0) - ($totals['total_sales_return'] ?? 0) - ($totals['total_waiver'] ?? 0)) }}</strong></td>
                                                <td class="text-right text-info"><strong>৳{{ number_format($totals['total_charge'] ?? 0) }}</strong></td>
                                                <td class="text-right text-info"><strong>৳{{ number_format($totals['total_waiver'] ?? 0) }}</strong></td>
                                                <td class="text-right text-danger"><strong>৳{{ number_format($totals['total_due'] ?? 0) }}</strong></td>
                                                <td class="text-right text-danger"><strong>৳{{ number_format($totals['total_closing_balance'] ?? 0) }}</strong></td>
                                                <td class="text-center">-</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                            @if(isset($reportData) && method_exists($reportData, 'hasPages') && $reportData->hasPages())
                                <div class="custom-pagination-container">
                                    <ul class="custom-pagination">
                                        {{-- Previous Button --}}
                                        @if($reportData->onFirstPage())
                                            <li class="disabled"><span>«</span></li>
                                        @else
                                            <li><a href="{{ $reportData->previousPageUrl() }}">«</a></li>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @php
                                            $currentPage = $reportData->currentPage();
                                            $lastPage = $reportData->lastPage();
                                            $start = max(1, $currentPage - 2);
                                            $end = min($lastPage, $currentPage + 2);

                                            if ($start > 1) {
                                                echo '<li><a href="' . $reportData->url(1) . '">1</a></li>';
                                                if ($start > 2)
                                                    echo '<li class="dots"><span>...</span></li>';
                                            }

                                            for ($i = $start; $i <= $end; $i++) {
                                                if ($i == $currentPage) {
                                                    echo '<li class="active"><span>' . $i . '</span></li>';
                                                } else {
                                                    echo '<li><a href="' . $reportData->url($i) . '">' . $i . '</a></li>';
                                                }
                                            }

                                            if ($end < $lastPage) {
                                                if ($end < $lastPage - 1)
                                                    echo '<li class="dots"><span>...</span></li>';
                                                echo '<li><a href="' . $reportData->url($lastPage) . '">' . $lastPage . '</a></li>';
                                            }
                                        @endphp

                                        {{-- Next Button --}}
                                        @if($reportData->hasMorePages())
                                            <li><a href="{{ $reportData->nextPageUrl() }}">»</a></li>
                                        @else
                                            <li class="disabled"><span>»</span></li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Page {{ $reportData->currentPage() }} of {{ $reportData->lastPage() }} 
                                        | Total {{ $reportData->total() }} records
                                    </small>
                                </div>
                            @endif
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
            $(document).on('click', '.custom-pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection