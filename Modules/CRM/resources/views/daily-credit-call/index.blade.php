@section('title', 'Daily Credit Call')
@section('description',
    'Daily Credit Call Entry using all customer')
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
                                    <li class="breadcrumb-item active" aria-current="page">Daily Credit Call Entry
                                    </li>
                                </ol>
                            </nav>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">Daily Credit Call Entry</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">

                        <div class="card-body">
                            <form method="GET" action="{{ route('crm.daily-credit-calls.index') }}">
                                <div class="row">
                                    <!-- Search Field -->
                                    <div class="col-md-3 mb-3" >
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
                                    <div class="col-md-3 mb-3">
                                        <div class="button-group d-flex pt-25 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('crm.daily-credit-calls.index') }}"
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
                            <h5 class="mb-0 text-center">Daily Credit Call Report</h5>
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
                                .text-wrap-column {
                                    min-width: 150px;
                                    max-width: 250px;
                                    white-space: normal !important;
                                    word-break: break-word;
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
                                </style>
                                <table class="table condition-table-custom dt-table-hover" id="zero-config"
                                    style="font-size: 12px;"> 
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%;">SL</th>
                                            <th style="width: 20%;">Customer</th>
                                            <th style="width: 10%;">Phone</th>
                                            <th style="width: 20%;">Address</th>
                                            <th style="width: 20%;">Reference</th>
                                            <th class="text-right fw-bold text-success" style="width: 10%;">Opening Balance
                                                <br>৳{{ number_format($totals['total_opening_balance']) }}
                                            </th>
                                            <th style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $index => $customer)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                    <a target="_blank" 
                                                        href="{{ route('account.report.customer-ledger', [
                                                        'account_id' => $customer['account_id'],
                                                        'from' => '2021-10-05',
                                                        'to' => date('Y-m-d'),
                                                        ]) }}">
                                                        {{ $customer['customer_name'] }}
                                                    </a> 
                                                </td>
                                                <td class="text-left">{{ $customer['phone'] ?? 'N/A' }}  </td> 
                                                <td class="text-left">{!! wordwrap($customer['address'], 40, '<br>', true) !!}</td>
                                                <td class="text-left" style="word-wrap: break-word; white-space: normal; min-width: 200px;">{{  $customer['user_reference'] ?? 'N/A' }}  </td>
                                                <td class="text-right fw-bold text-success">
                                                    ৳{{ number_format($customer['opening_balance']) }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                        @if (hasPermission('crm.daily-credit-calls.create')) 
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary btn-create-details"
                                                                data-id='{{$customer['customer_id']}}'
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#creditCallCreateModal">
                                                                <i class="las la-plus"></i>
                                                            </button> 
                                                        @endif

                                                        @if (hasPermission('crm.daily-credit-calls.legal'))  
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success btn-legal-details"
                                                                data-id='{{$customer['customer_id']}}'
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#creditCallLegalModal">
                                                            <i class="fas fa-balance-scale"></i>
                                                            </button> 
                                                        @endif
 

                                                        @if (hasPermission('crm.daily-credit-calls.show')) 
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-info btn-view-details"
                                                                data-id='{{$customer['customer_id']}}'
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#creditCallDetailsModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button> 
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($reportData->count() > 0)
                                        <tfoot>
                                            <tr style="font-weight: bold; font-size: 14px;">
                                                <td colspan="5" class="text-right"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right text-primary">
                                                    ৳{{ number_format($totals['total_opening_balance']) }}</td> 
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
    <!-- Modal -->
    <div class="modal fade" id="creditCallDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Credit Call List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="creditCallDetailsBody">
                    <div class="text-center py-5"> 

                    </div>
                </div>
            </div>
        </div>
    </div>  

    <!-- Modal -->
    <div class="modal fade" id="creditCallLegalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task assign for legal action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="creditCallLegalBody">
                    <div class="text-center py-5"> 

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="creditCallCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Credit Call Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="creditCallCreateBody">
                    <div class="text-center py-5">
                                                  
                    </div>
                </div>
            </div>
        </div>
    </div> 

@endsection

@section('page_scripts')

    <script> 
        $(document).ready(function () { 
            // Load modal
            $(document).on('click', '.btn-create-details', function () { 
                let cusId = $(this).data('id'); 
                const url = `{{ route('crm.daily-credit-calls.create') }}?id=`+cusId;
 
                $('#creditCallCreateBody').loadWithSpinner(url,function (){
                    $('.flatdate').flatpickr({
                        dateFormat: 'Y-m-d',
                        allowInput: true,
                        minDate: "today"
                    });
                });
 
            });

            $(document).on('click', '.btn-view-details', function () { 
                let cusId = $(this).data('id');     
                const url = "{{ route('crm.daily-credit-calls.show', ['daily_credit_call' => 'REPLACE_ID']) }}".replace('REPLACE_ID', cusId);

                $('#creditCallDetailsBody').loadWithSpinner(url);
 
            });


            $(document).on('click', '.btn-legal-details', function () {  
                let cusId = $(this).data('id');     
                const url = `{{ route('crm.daily-credit-calls.legal') }}?id=`+cusId;

                $('#creditCallLegalBody').loadWithSpinner(url);
 
            });

             
        });
    </script>

@endsection
 