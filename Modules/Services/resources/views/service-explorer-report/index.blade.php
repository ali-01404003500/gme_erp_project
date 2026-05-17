@section('title', 'Service Explorer Report')
@section('description', 'Comprehensive Service Activity Report with Advanced Filters')
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
                                    <li class="breadcrumb-item active" aria-current="page">Service Explorer Report</li>
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
                         Service Explorer Report
                    </h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="las la-filter"></i> Search & Filter Options</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('services.reports.service-explorer-reports') }}" id="explorerForm">
                                <div class="row">
                                    <!-- Customer Name -->
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Customer Name</label>
                                        <select id="customer_id" name="customer_id" class="form-control" data-placeholder="Select Customer">
                                            <option value="">All Customers</option> 
                                        </select>
                                    </div>

                                    <!-- Product Name -->
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Product Name</label>
                                        <select id="product_id" name="product_id" class="form-control" data-placeholder="Select Product">
                                            <option value="">All Products</option> 
                                        </select>
                                    </div>

                                    <!-- Token ID -->
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Token ID</label>
                                        <select id="token_id" name="token_id" class="form-control" data-placeholder="Select Token">
                                            <option value="">All Tokens</option> 
                                        </select>
                                    </div>

                                    <!-- Serial ID -->
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Serial Number</label>
                                        <input type="text" class="form-control" name="serial_no" 
                                            value="{{ request('serial_no') }}" 
                                            placeholder="Search by Serial No">
                                    </div>

                                    <!-- Engineer -->
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Engineer</label>
                                        <select name="engineer_id" class="tom-select" data-placeholder="Select Engineer">
                                            <option value="">All Engineers</option>
                                            @foreach ($engineers as $engineer)
                                                <option value="{{ $engineer->id }}"
                                                    {{ request('engineer_id') == $engineer->id ? 'selected' : '' }}>
                                                    {{ $engineer->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Service Status -->
                                    <div class="col-md-2 mb-3">
                                        <label class="font-weight-bold">Service Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="Live" {{ request('status') == 'Live' ? 'selected' : '' }}>Live</option>
                                            <option value="Started" {{ request('status') == 'Started' ? 'selected' : '' }}>Started</option>
                                            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>

                                    <!-- Service Type -->
                                    <div class="col-md-2 mb-3">
                                        <label class="font-weight-bold">Service Type</label>
                                        <select name="service_type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="ON SPOT" {{ request('service_type') == 'ON SPOT' ? 'selected' : '' }}>ON SPOT</option>
                                            <option value="IN HOUSE" {{ request('service_type') == 'IN HOUSE' ? 'selected' : '' }}>IN HOUSE</option>
                                            <option value="ON CALL" {{ request('service_type') == 'ON CALL' ? 'selected' : '' }}>ON CALL</option>
                                        </select>
                                    </div>

                                    <!-- Date Range -->
                                    <div class="col-md-5 mb-3">
                                        <label class="font-weight-bold">Date Range (Service Date)</label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                value="{{ request('from') }}" autocomplete="off" placeholder="Start Date" />
                                            <span class="input-group-text">
                                                <i class="fa fa-exchange-alt"></i>
                                            </span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                value="{{ request('to') }}" autocomplete="off" placeholder="End Date" />
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-12 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('services.reports.service-explorer-reports') }}" class="btn btn-warning">
                                                <i class="fa fa-refresh"></i> Clear Filters
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Applied Filters Info -->
                {{-- @if(request()->hasAny(['customer_id', 'product_id', 'token_id', 'serial_no', 'engineer_id', 'status', 'service_type', 'from', 'to']))
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info">
                        <strong><i class="las la-info-circle"></i> Applied Filters:</strong>
                        <div class="mt-2">
                            @if(request('customer_id'))
                                <span class="badge badge-round badge-primary mr-2">Customer: {{ $customers->find(request('customer_id'))->company_name ?? 'N/A' }}</span>
                            @endif
                            @if(request('product_id'))
                                <span class="badge badge-round badge-primary mr-2">Product: {{ $products->find(request('product_id'))->name ?? 'N/A' }}</span>
                            @endif
                            @if(request('token_id'))
                                <span class="badge badge-round badge-primary mr-2">Token ID: {{ request('token_id') }}</span>
                            @endif
                            @if(request('serial_no'))
                                <span class="badge badge-round badge-primary mr-2">Serial: {{ request('serial_no') }}</span>
                            @endif
                            @if(request('engineer_id'))
                                <span class="badge badge-round badge-primary mr-2">Engineer: {{ $engineers->find(request('engineer_id'))->name ?? 'N/A' }}</span>
                            @endif
                            @if(request('status'))
                                <span class="badge badge-round badge-success mr-2">Status: {{ request('status') }}</span>
                            @endif
                            @if(request('service_type'))
                                <span class="badge badge-round badge-info mr-2">Type: {{ request('service_type') }}</span>
                            @endif
                            @if(request('from') || request('to'))
                                <span class="badge badge-round badge-warning mr-2">
                                    Date: {{ request('from') ?: 'Start' }} to {{ request('to') ?: 'End' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif --}}

                <!-- Report Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="las la-table"></i> Service Activity Details</h6>
                                <span class="badge badge-round badge-primary badge-lg">Total Records: {{ $reportData->total() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm" id="sericeExplorerTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center" style="width: 3%;">SL</th> 
                                            <th style="width: 7%;">Service Date</th>
                                            <th style="width: 12%;">Customer</th>
                                            <th style="width: 10%;">Service Product</th> 
                                            <th style="width: 10%;">Problem & Solution Details</th> 
                                            <th style="width: 7%;">Service Status</th>
                                          
                                            <th style="width: 7%;">Service Type</th> 
                                            <th style="width: 7%;">Complete Date</th>
                                            <th style="width: 8%;">Engineer</th>
                                            <th class="text-right" style="width: 7%;">Bill Amount</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalServiceBill = 0;
                                            $totalProductBill = 0;
                                        @endphp

                                        @forelse($reportData as $index => $token)
                                            @php
                                                $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;

                                                // Calculate bills
                                                $serviceBill = 0;
                                                $productBill = 0;
                                                if ($token->serviceMyTask) {
                                                    foreach ($token->serviceMyTask->bills as $bill) {
                                                        if ($bill->product && stripos($bill->product->tag->name, 'service') !== false) {
                                                            $serviceBill += $bill->amount;
                                                        } else {
                                                            $productBill += $bill->amount;
                                                        }
                                                    }
                                                }

                                                $totalServiceBill += $serviceBill;
                                                $totalProductBill += $productBill;

                                                // Status badge
                                                $status = $token->action;
                                                $statusClass = match ($status) {
                                                    'Live' => 'badge-primary',
                                                    'Started' => 'badge-info',
                                                    'Done' => 'badge-success',
                                                    'Cancelled' => 'badge-danger',
                                                    default => 'badge-secondary',
                                                };

                                                // Get engineer name
                                                $engineerName = 'N/A';
                                                if ($token->engineerAssign && $token->engineerAssign->engineers) {
                                                    $engineerName = $token->engineerAssign->engineers->pluck('full_name')->join(', ');
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</td> 
                                                <td>
                                                    {{ $token->token_date ? \Carbon\Carbon::parse($token->token_date)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <strong>{{ $token->customer->company_name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $token->customer->area?->area ?? '' }}</small> 
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <strong>{{ $token->product->withoutModelSuffix()->name ?? 'N/A' }}</strong>
                                                  
                                                    <br>Model: <small class="text-muted">{{ $token->product->withoutModelSuffix()->model }}</small> 
                                                   
                                                    <br>Serial:<span class="badge badge-round badge-light">{{ $token->serial_number ?? 'N/A' }}</span>

                                                </td> 
                                                <td style="vertical-align: top;">
                                                    <strong>Problem:</strong> {!! wordwrap(e($token->problem_type), 50, '<br>') !!} <br>
                                                    <strong>Solution:</strong> <br>
                                                    @if ($token->serviceMyTask && $token->serviceMyTask->pendingServiceTokens->count() > 0)
                                                        @foreach ($token->serviceMyTask->pendingServiceTokens as $idx => $pendingToken)
                                                            <div class="mb-1">
                                                                <small style="
                                                                    display: inline-block;
                                                                    width: 350px;
                                                                    word-wrap: break-word;
                                                                    white-space: normal;
                                                                ">
                                                                    {{ $pendingToken->description ?? 'N/A' }}
                                                                </small>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <small class="text-break">
                                                            {{ $token->serviceMyTask->description ?? 'N/A' }}
                                                        </small>
                                                    @endif

                                                </td> 
                                                <td class="text-center">
                                                    <span class="badge badge-round {{ $statusClass }}">{{ $status }}</span>
                                                </td> 
                                                <td>
                                                    <span class="badge badge-round badge-info">{{ $token->service_type ?? 'N/A' }}</span>
                                                </td> 
                                                <td>
                                                    @if ($token->serviceMyTask && $token->serviceMyTask->updated_at)
                                                        {{ $token->serviceMyTask->updated_at->format('d-M-Y') }}
                                                        <br>
                                                        <small class="text-muted">{{ $token->serviceMyTask->updated_at->format('h:i A') }}</small>
                                                    @else
                                                        <span class="text-muted">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $engineerName }}</td>
                                                <td class="text-right">
                                                    Service Bill: <strong class="text-success">৳{{ number_format($serviceBill) }}</strong><br>
                                                    Product Bill: <strong class="text-info">৳{{ number_format($productBill) }}</strong>
                                                </td> 
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0 mt-2">No service records found matching your criteria</p>
                                                    <small class="text-muted">Try adjusting your filters</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($reportData->count() > 0)
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 13px;">
                                                <td colspan="7" class="text-right">
                                                    <strong class="text-primary">TOTAL SUMMARY:</strong>
                                                </td>
                                                <td  class="text-end bg-success text-white">
                                                    Service Bill:<br>
                                                    Product Bill:
                                                </td> 
                                                <td colspan="2" class="text-end bg-success text-white">
                                                    <strong>৳{{ number_format($totalServiceBill) }}</strong><br>
                                                    <strong>৳{{ number_format($totalProductBill) }}</strong>
                                                </td> 
                                            </tr>
                                            <tr class="font-weight-bold" style="font-size: 14px;">
                                                <td colspan="8" class="text-right">
                                                    <strong>GRAND TOTAL:</strong>
                                                </td>
                                                <td colspan="2" class="text-end">
                                                    <strong>৳{{ number_format($totalServiceBill + $totalProductBill) }}</strong>
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
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            // Initialize flatpickr for date inputs
            $('.flatdate').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // Form validation
            $('#explorerForm').on('submit', function(e) {
                const from = $('input[name="from"]').val();
                const to = $('input[name="to"]').val();

                if (from && to && from > to) {
                    e.preventDefault();
                    toastr.error('Start date cannot be later than end date');
                    return false;
                }
            });

             const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            companySelect.clearOptions(); 
                            callback(res.map(item => ({ id: item.id, text: item.label, phone: item.phone, address: item.address    })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if($customer)
                companySelect.addOption({
                    id: "{{ $customer->id }}",
                    text: "{{ $customer->company_name }}" 
                });
                companySelect.setValue("{{ $customer->id }}");
            @endif

 


            const productSelect = new TomSelect("#product_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if($product)
                productSelect.addOption({
                    id: "{{ $product->id }}",
                    text: "{{ $product->name }}"
                });
                productSelect.setValue("{{ $product->id }}");
            @endif


            const serviceSelect = new TomSelect("#token_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('services.service-autocomplete.service-id') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            serviceSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
       
            @if($serviceToken)
                serviceSelect.addOption({ 
                    id: "{{ $serviceToken->id }}",
                    text: "{{ $serviceToken->service_unique_id }}"
                });
                serviceSelect.setValue("{{ $serviceToken->id }}");
            @endif


        });
    </script>


@endsection