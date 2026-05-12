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
                                    <div class="col-md-3 mb-3">
                                        <label>Search Customer</label>
                                        <select name="search" id="company_name" class="form-control"
                                            data-placeholder="Select Customer">
                                            <option value=""></option> 
                                        </select>
                                    </div> 
                                    <div class="col-md-3 mb-3">
                                        <label>Division</label>
                                        <select name="division_id" class="form-control tom-select" data-placeholder="Select Division">
                                            <option value="">Select Division</option>
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
                                        <select name="district_id" class="form-control tom-select" data-placeholder="Select District">
                                            <option value="">Select District</option>
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
                                <table class="table table-hover table-bordered table-sm" id="zero-config"
                                    style="font-size: 12px;" width="100%"> 
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%;">SL</th>
                                            <th style="width: 20%;">Customer</th>
                                            <th style="width: 10%;">Phone</th>
                                            <th style="width: 20%;">Reference</th>
                                            <th class="text-end" style="width: 10%;">Balance
                                                <br>৳{{ number_format($totals['total_opening_balance']) }}
                                            </th>
                                            <th class="text-center" style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @forelse($reportData as $index => $customer)
                                        @php
                                            $rowNumber = ($reportData->currentPage() - 1) * $reportData->perPage() + $loop->iteration;  
                                        @endphp
                                            <tr>
                                                <td class="text-center">{{ $rowNumber }}</td>
                                                <td>
                                                    <a target="_blank" 
                                                        href="{{ route('account.report.customer-ledger', [
                                                        'account_id' => $customer['account_id'],
                                                        'from' => '2021-10-05',
                                                        'to' => date('Y-m-d'),
                                                        ]) }}">
                                                        {{ $customer['customer_name'] }}
                                                    </a> <br>
                                                     <small class="text-muted"><i class="las la-map-marker me-1"></i> {!! wordwrap($customer['address'], 40, '<br>', true) !!}</small> 
                                                </td>
                                                <td class="text-left">{{ $customer['phone'] ?? 'N/A' }}  </td>  
                                                <td class="text-left">{{ $customer['user_reference'] ?? 'N/A' }}  </td>
                                                <td class="text-end">
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
                  

                                    @if($reportData->count() > 0)
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 14px">
                                                <td colspan="4" class="text-right"><strong>Due Balance:</strong></td>
                                                <td class="text-end">
                                                    <strong class="text-danger">৳{{ number_format($totals['total_opening_balance']) }}</strong>
                                                </td>
                                                <td class="text-center">-</td>
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

         $(document).ready(function () {

            const companySelect = new TomSelect("#company_name", {
                valueField: "id",
                labelField: "text",
                searchField: [],

                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('crm.autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },

                        success: function(res) {
                            callback(res.map(item => ({
                                id: item.id,
                                text: item.label
                            })));
                        },

                        error: function() {
                            callback();
                        }
                    });
                }
            });

            @if(request('search') && $selectedCustomer)

                companySelect.addOption({
                    id: "{{ $selectedCustomer->id }}",
                    text: "{{ $selectedCustomer->company_name }}"
                });

                companySelect.setValue("{{ $selectedCustomer->id }}");

            @endif

        });
    </script>

@endsection
 