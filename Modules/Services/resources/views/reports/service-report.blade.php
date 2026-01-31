@section('title', 'Service Report')
@section('description', 'Service Report - Product/Customer Wise')
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
                                    <li class="breadcrumb-item active" aria-current="page">Service Report</li>
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
                    <h4 class="text-capitalize breadcrumb-title">
                        @if (request('product_id'))
                            Service Report Product
                        @elseif(request('customer_id'))
                            Service Report Customer
                        @else
                            Service Report
                        @endif
                    </h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('services.reports.service-reports') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Product Name</label>
                                        <select name="product_id" class="tom-select" data-placeholder="Select Product">
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                    {{ $product->model_no ? '(' . $product->model_no . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

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

                                    <div class="col-md-2 mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="Junk" {{ request('status') == 'Junk' ? 'selected' : '' }}>Junk
                                            </option>
                                            <option value="Live" {{ request('status') == 'Live' ? 'selected' : '' }}>Live
                                            </option>
                                            <option value="Started" {{ request('status') == 'Started' ? 'selected' : '' }}>
                                                Started</option>
                                            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done
                                            </option>
                                            <option value="Cancelled"
                                                {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>
                                                Failed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
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

                                    <div class="col-md-2 mb-3">
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

                                    <div class="col-md-9 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('services.reports.service-reports') }}" class="btn btn-warning">
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
                                <table class="table table-hover table-bordered table-sm" id="serviceReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th>Service Date</th>
                                            <th>Customer Name</th>
                                            <th>Service Status</th>
                                            <th>Service Type</th>
                                            <th>Name of Problematic Product</th>
                                            <th style="min-width: 300px;">Problem & Solution Details</th>
                                            <th>Completion Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalServiceFee = 0;
                                            $totalSparePartsFee = 0;
                                            $grandTotal = 0;
                                        @endphp

                                        @forelse($reportData as $index => $token)
                                            @php
                                                $rowNumber =
                                                    ($reportData->currentPage() - 1) * $reportData->perPage() +
                                                    $loop->iteration;

                                                // Calculate fees
                                                $serviceFee = 0;
                                                $sparePartsFee = 0;
                                                if ($token->serviceMyTask) {
                                                    foreach ($token->serviceMyTask->bills as $bill) {
                                                        if (
                                                            $bill->product &&
                                                            stripos($bill->product->tag->name, 'service') !== false
                                                        ) {
                                                            $serviceFee += $bill->amount;
                                                        } else {
                                                            $sparePartsFee += $bill->amount;
                                                        }
                                                    }
                                                }
                                                $totalAmount = $serviceFee + $sparePartsFee;

                                                $totalServiceFee += $serviceFee;
                                                $totalSparePartsFee += $sparePartsFee;
                                                $grandTotal += $totalAmount;

                                                // Get status
                                                $status = $token->action;
                                                $statusClass = match ($status) {
                                                    'Live' => 'badge-primary',
                                                    'Started' => 'badge-info',
                                                    'Done' => 'badge-success',
                                                    'Cancelled', 'Failed' => 'badge-danger',
                                                    'Pending' => 'badge-warning',
                                                    'Junk' => 'badge-secondary',
                                                    default => 'badge-secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</td>
                                                <td>{{ $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <a href="#" class="text-primary font-weight-bold"
                                                        data-toggle="modal" data-target="#customerLedgerModal"
                                                        data-customer-id="{{ $token->customer_id }}">
                                                        {{ $token->customer->company_name ?? 'N/A' }}
                                                    </a>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $token->customer->address ?? '' }}</small>
                                                    <br>
                                                    <small class="text-info">Service ID:
                                                        {{ $token->service->service_unique_id ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $statusClass }} badge-round">{{ $status }}</span>
                                                </td>
                                                <td>{{ $token->service_type ?? 'N/A' }}</td>
                                                <td>
                                                    <strong>{{ $token->product->name ?? 'N/A' }}</strong>
                                                    @if ($token->product && $token->product->model_no)
                                                        <br><small class="text-muted">Model:
                                                            {{ $token->product->model_no }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="problem-solution-details">
                                                        <div class="mb-2">
                                                            <strong class="text-danger">Problem:</strong>
                                                            <p class="mb-1">{{ $token->problem_details ?? 'N/A' }}</p>
                                                        </div>

                                                        @if ($token->serviceMyTask)
                                                            {{-- Multiple Solutions Section --}}
                                                            @if ($token->serviceMyTask->pendingServiceTokens && $token->serviceMyTask->pendingServiceTokens->count() > 0)
                                                                <div class="mb-2">
                                                                    <strong class="text-success">Solutions:</strong>
                                                                    @foreach ($token->serviceMyTask->pendingServiceTokens as $pendingToken)
                                                                        <div
                                                                            class="border-left border-success pl-2 ml-2 mb-2">
                                                                            @if ($reportType == 'customer')
                                                                                <textarea class="form-control form-control-sm solution-field" data-pending-token-id="{{ $pendingToken->id }}"
                                                                                    rows="2">{{ $pendingToken->description ?? '' }}</textarea>
                                                                                <button type="button"
                                                                                    class="btn btn-xs btn-primary mt-1 save-solution"
                                                                                    data-pending-token-id="{{ $pendingToken->id }}">
                                                                                    <i class="fa fa-save"></i> Update
                                                                                </button>
                                                                            @else
                                                                                <p class="mb-1">
                                                                                    {{ $pendingToken->description ?? 'N/A' }}
                                                                                </p>
                                                                            @endif
                                                                            {{-- <small class="text-muted d-block mt-1">
                                                                                Status: <span
                                                                                    class="badge badge-{{ $pendingToken->status == 'Verified' ? 'success' : ($pendingToken->status == 'pending' ? 'warning' : 'secondary') }}">
                                                                                    {{ $pendingToken->status }}
                                                                                </span>
                                                                            </small> --}}
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                {{-- Fallback to main description --}}
                                                                <div class="mb-2">
                                                                    <strong class="text-success">Solution:</strong>
                                                                    <p class="mb-1">
                                                                        {{ $token->serviceMyTask->description ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                            @endif

                                                            <div class="mb-2">
                                                                <strong>Service Fee:</strong>
                                                                <span
                                                                    class="text-success">৳{{ number_format($serviceFee) }}</span>
                                                            </div>

                                                            <div class="mb-2">
                                                                <strong>Spare Parts Fee:</strong>
                                                                <span
                                                                    class="text-info">৳{{ number_format($sparePartsFee) }}</span>
                                                            </div>

                                                            @if ($token->serviceMyTask->bill_description)
                                                                <div class="mb-2">
                                                                    <strong>Remarks:</strong>
                                                                    <p class="mb-1">{{ $token->serviceMyTask->bill_description }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        @if ($token->service && $token->service->emergencyNotes->count() > 0)
                                                            <div class="mb-2">
                                                                <strong class="text-warning">Emergency Notes:</strong>
                                                                @foreach ($token->service->emergencyNotes as $note)
                                                                    <div class="border-left border-warning pl-2 ml-2 mb-1">
                                                                        <small>
                                                                            <strong>Call By:</strong>
                                                                            {{ $note->createdBy->name ?? 'N/A' }}<br>
                                                                            <strong>Date:</strong>
                                                                            {{ $note->created_at->format('d-M-Y h:i A') }}<br>
                                                                            <strong>Note:</strong> {{ $note->note }}
                                                                        </small>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($token->service)
                                                        <div class="mb-1">
                                                            <strong>Entry By:</strong><br>
                                                            {{ $token->service->createdBy->name ?? 'N/A' }}<br>
                                                            <small
                                                                class="text-muted">{{ $token->service->created_at->format('d-M-Y h:i A') }}</small>
                                                        </div>
                                                    @endif

                                                    @if ($token->serviceMyTask)
                                                        <div class="mb-1">
                                                            <strong>Complete By:</strong><br>
                                                            {{ $token->serviceMyTask->createdBy->name ?? 'N/A' }}<br>
                                                            <small
                                                                class="text-muted">{{ $token->serviceMyTask->created_at->format('d-M-Y h:i A') }}</small>
                                                        </div>

                                                        @if ($token->serviceMyTask->updated_at)
                                                            <div class="mb-1">
                                                                <strong>Completion Date:</strong><br>
                                                                {{ $token->serviceMyTask->updated_at->format('d-M-Y h:i A') }}
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if ($token->service && $token->service->emergencyNotes->count() > 0)
                                                        <div class="mt-2">
                                                            <strong>Note:</strong><br>
                                                            <small>{{ $token->service->emergencyNotes->last()->note ?? '' }}</small>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($reportData->count() > 0)
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 14px">
                                                <td colspan="6" class="text-right"><strong>Total Amount:</strong></td>
                                                <td colspan="2">
                                                    <div><strong>Service Fee:</strong>
                                                        ৳{{ number_format($totalServiceFee) }}</div>
                                                    <div><strong>Spare Parts Fee:</strong>
                                                        ৳{{ number_format($totalSparePartsFee) }}</div>
                                                    <div class="text-primary"><strong>Grand Total:</strong>
                                                        ৳{{ number_format($grandTotal) }}</div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
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
            // Save solution button click - Updated for pending tokens
            $('.save-solution').on('click', function() {
                const pendingTokenId = $(this).data('pending-token-id');
                const solution = $(`.solution-field[data-pending-token-id="${pendingTokenId}"]`).val();
                const button = $(this);

                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: '{{ route('services.reports.update-solution', ':id') }}'
                        .replace(':id', pendingTokenId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        description: solution,
                        status: 'pending' // Keep as pending after update
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Solution updated successfully');
                            button.html('<i class="fa fa-check"></i> Updated');
                            setTimeout(() => {
                                button.prop('disabled', false).html(
                                    '<i class="fa fa-save"></i> Update');
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to update solution');
                        button.prop('disabled', false).html(
                        '<i class="fa fa-save"></i> Update');
                    }
                });
            });

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
        .badge-round {
            border-radius: 999px !important;
            padding: 4px 10px !important;
        }

        .problem-solution-details {
            font-size: 11px;
        }

        .problem-solution-details strong {
            display: inline-block;
            margin-bottom: 3px;
        }

        .solution-field {
            font-size: 11px;
            resize: vertical;
        }

        #serviceReportTable tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
