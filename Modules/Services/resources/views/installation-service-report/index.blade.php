@section('title', 'Installation & Servicing Reports')
@section('description', 'Installation & Servicing Reports')
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
                                <li class="breadcrumb-item active" aria-current="page">Installation & Servicing Reports</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">Installation & Servicing Reports</h4>
            </div>

            <!-- Filters Section -->
            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="las la-filter"></i> Filter Options</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('services.reports.installation-reports') }}" id="filterForm">
                            <div class="row">
                                <!-- Date Range -->
                                <div class="col-md-5 mb-3">
                                    <label class="font-weight-bold">Date Range (Completion Date)</label>
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control flatdate" name="from"
                                            value="{{ request('from') }}" autocomplete="off" placeholder="From Date" />
                                        <span class="input-group-text">
                                            <i class="fa fa-exchange-alt"></i>
                                        </span>
                                        <input type="text" class="form-control flatdate" name="to"
                                            value="{{ request('to') }}" autocomplete="off" placeholder="To Date" />
                                    </div>
                                </div>

                                <!-- Type Filter -->
                                <div class="col-md-3 mb-3">
                                    <label class="font-weight-bold">Report Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="Installation" {{ request('type') == 'Installation' ? 'selected' : '' }}>Installation</option>
                                        <option value="Servicing" {{ request('type') == 'Servicing' ? 'selected' : '' }}>Servicing</option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-4 btn-group">
                                    <label class="font-weight-bold d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fa fa-search"></i> Go
                                    </button>
                                    <a href="{{ route('services.reports.installation-reports') }}" class="btn btn-warning">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                               
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="searchBox" class="form-control" 
                                    placeholder="Search by Customer Name or Report No..." 
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="las la-file-alt"></i>Installation & Servicing Reports</h6>
                            <span class="badge badge-round badge-primary badge-lg">
                                Total: {{ $reports->total() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th style="width: 5%;">SL</th>
                                        <th style="width: 15%;">Report No</th>
                                        <th style="width: 20%;">Customer Name</th>
                                        <th style="width: 15%;">Engineer Name</th>
                                        <th style="width: 10%;">Type</th>
                                        <th style="width: 20%;">Installation/Servicing Date & Time</th>
                                        <th style="width: 15%;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $index => $token)
                                        @php
                                            $rowNumber = ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration;
                                            $reportNo = 'GME-CER-' . $token->serviceMyTask->updated_at->format('Ym') . '-' . str_pad($token->id, 4, '0', STR_PAD_LEFT);

                                            $workType = $token->work_type;
                                            $type = in_array($workType, ['New Installation', 'Re Installation']) 
                                                ? 'Installation' 
                                                : 'Servicing';
                                            
                                            $typeClass = $type === 'Installation' ? 'badge-success' : 'badge-info';
                                            
                                            $engineerName = 'N/A';
                                            if ($token->engineerAssign && $token->engineerAssign->engineers) {
                                                $engineerName = $token->engineerAssign->engineers->pluck('full_name')->join(', ');
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $rowNumber }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $reportNo ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $token->customer->company_name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $token->customer->phone ?? '' }}</small>
                                            </td>
                                            <td>{{ $engineerName }}</td>
                                            <td>
                                                {{ $type }}
                                            </td>
                                            <td>
                                                {{ @$token->serviceMyTask->updated_at ? $token->serviceMyTask->updated_at->format('Y-m-d H:i:s') : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('services.reports.installation-report-details', $token->id) }}" 
                                                   class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="las la-file-pdf"></i> View PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                <p class="mb-0 mt-2">No service reports found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @include('partials._paginate', ['data' => $reports])

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
        // Search functionality with debounce
        let searchTimeout;
        $('#searchBox').on('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();
            
            searchTimeout = setTimeout(function() {
                const url = new URL(window.location.href);
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 500);
        });
    });
</script>
@endsection