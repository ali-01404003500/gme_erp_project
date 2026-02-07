@section('title', 'Customer Balance Details Report')
@section('description',
    'Comprehensive customer balance report with opening, sales, returns, collections, and closing
    balances')
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
                            <form method="GET" action="{{ route('crm.reports.customer-balance-details') }}">
                                <div class="row">
                                    <!-- Search Field -->
                                    <div class="col-md-3 mb-3">
                                        <label>Search Customer</label>
                                        <select name="search" id="company_name" class="form-control tom-select"
                                            data-placeholder="Select Customer">
                                            <option value=""></option>
                                            @foreach ($customersearch as $key => $value)
                                                <option {{ request('search') == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}">
                                                    {{ $value->company_name }} ({{ $value->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Due Type Filter -->
                                    <div class="col-md-2 mb-3">
                                        <label>Due Type</label>
                                        <select name="due_type" class="form-control">
                                            <option value="all" {{ $filters['due_type'] == 'all' ? 'selected' : '' }}>ALL
                                            </option>
                                            <option value="machine_code"
                                                {{ $filters['due_type'] == 'machine_code' ? 'selected' : '' }}>MACHINE CODE
                                            </option>
                                            <option value="old_due"
                                                {{ $filters['due_type'] == 'old_due' ? 'selected' : '' }}>OLD DUE</option>
                                        </select>
                                    </div>

                                    <!-- Date Range -->
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

                                    <!-- Recovery Percentage -->
                                    <div class="col-md-2 mb-3">
                                        <label>Recovery %</label>
                                        <select name="recovery_percentage" class="form-control">
                                            <option value="">All</option>
                                            <option value="below_10"
                                                {{ $filters['recovery_percentage'] == 'below_10' ? 'selected' : '' }}>Below
                                                10%</option>
                                            <option value="10_20"
                                                {{ $filters['recovery_percentage'] == '10_20' ? 'selected' : '' }}>10-20%
                                            </option>
                                            <option value="21_30"
                                                {{ $filters['recovery_percentage'] == '21_30' ? 'selected' : '' }}>21-30%
                                            </option>
                                            <option value="31_40"
                                                {{ $filters['recovery_percentage'] == '31_40' ? 'selected' : '' }}>31-40%
                                            </option>
                                            <option value="41_50"
                                                {{ $filters['recovery_percentage'] == '41_50' ? 'selected' : '' }}>41-50%
                                            </option>
                                            <option value="51_60"
                                                {{ $filters['recovery_percentage'] == '51_60' ? 'selected' : '' }}>51-60%
                                            </option>
                                            <option value="61_70"
                                                {{ $filters['recovery_percentage'] == '61_70' ? 'selected' : '' }}>61-70%
                                            </option>
                                            <option value="71_80"
                                                {{ $filters['recovery_percentage'] == '71_80' ? 'selected' : '' }}>71-80%
                                            </option>
                                            <option value="above_80"
                                                {{ $filters['recovery_percentage'] == 'above_80' ? 'selected' : '' }}>Above
                                                80%</option>
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

                                    <!-- Action Buttons -->
                                    <div class="col-md-12 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('crm.reports.customer-balance-details') }}"
                                                class="btn btn-warning">
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
                                From: {{ $filters['start_date'] ?? date('Y-m-01') }} To:
                                {{ $filters['end_date'] ?? date('Y-m-d') }}
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dt-table-hover" id="zero-config"
                                    style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%;">SL</th>
                                            <th style="width: 20%;">Customer</th>
                                            <th class="text-right" style="width: 10%;">Opening Balance
                                                <br>৳{{ number_format($totals['total_opening_balance']) }}
                                            </th>
                                            <th class="text-right" style="width: 10%;">Sales <br>
                                                ৳{{ number_format($totals['total_sales']) }}</td>
                                            </th>
                                            <th class="text-right" style="width: 10%;">Sales Return <br>
                                                ৳{{ number_format($totals['total_sales_return']) }}</td>
                                            </th>
                                            <th class="text-right" style="width: 10%;">Collection <br>
                                                ৳{{ number_format($totals['total_collection']) }}</td>
                                            </th>
                                            <th class="text-right" style="width: 10%;">Due <br>
                                                ৳{{ number_format($totals['total_due']) }}</td>
                                            </th>
                                            <th class="text-right" style="width: 10%;">Closing Balance <br>
                                                <strong>৳{{ number_format($totals['total_closing_balance']) }}</strong>
                                            </th>
                                            <th class="text-center" style="width: 10%;">Recovery %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $index => $customer)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <a target="_blank" 
                                                        href="{{ route('account.report.customer-ledger', [
                                                        'account_id' => $customer['account_id'],
                                                        'from' => '2021-10-05',
                                                        'to' => date('Y-m-d'),
                                                        ]) }}">
                                                        {{ $customer['customer_name'] }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{!! wordwrap($customer['address'], 60, '<br>', true) !!}</small>
                                                     <br>
                                                    <small class="text-muted">{{ $customer['phone'] ?? 'N/A' }}</small>
                                                    @if ($customer['has_machine_code'])
                                                        <span class="badge badge-round badge-success badge-sm ml-2">
                                                            <i class="las la-key"></i> Machine Code
                                                        </span>
                                                    @endif
                                                  
                                                </td>
                                                <td class="text-right">
                                                    ৳{{ number_format($customer['opening_balance']) }}</td>
                                                <td class="text-right">৳{{ number_format($customer['sales']) }}</td>
                                                <td class="text-right">৳{{ number_format($customer['sales_return']) }}
                                                </td>
                                                <td class="text-right">৳{{ number_format($customer['collection']) }}
                                                </td>
                                                <td class="text-right">
                                                    <span
                                                        class="{{ $customer['due'] >= 0 ? 'text-danger' : 'text-success' }}">
                                                        ৳{{ number_format($customer['due']) }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <strong
                                                        class="{{ $customer['closing_balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                                                        ৳{{ number_format($customer['closing_balance']) }}
                                                    </strong>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-round  badge-{{ $customer['recovery_percentage'] >= 70 ? 'success' : ($customer['recovery_percentage'] >= 40 ? 'warning' : 'danger') }}">
                                                        {{ number_format($customer['recovery_percentage']) }}%
                                                    </span>
                                                </td>
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
                                    @if ($reportData->count() > 0)
                                        <tfoot>
                                            <tr style="font-weight: bold; font-size: 14px;">
                                                <td colspan="2" class="text-right"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right text-primary">
                                                    ৳{{ number_format($totals['total_opening_balance']) }}</td>
                                                <td class="text-right text-success">
                                                    ৳{{ number_format($totals['total_sales']) }}</td>
                                                <td class="text-right text-warning">
                                                    ৳{{ number_format($totals['total_sales_return']) }}</td>
                                                <td class="text-right text-info">
                                                    ৳{{ number_format($totals['total_collection']) }}</td>
                                                <td class="text-right text-danger">
                                                    ৳{{ number_format($totals['total_due']) }}</td>
                                                <td class="text-right text-danger">
                                                    <strong>৳{{ number_format($totals['total_closing_balance']) }}</strong>
                                                </td>
                                                <td class="text-center">-</td>
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



@endsection
