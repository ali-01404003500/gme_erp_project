@extends('layout.app')

@section('title', 'Application Entry')
@section('description', 'Application Entry')

@section('content')
<style>
    
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main d-flex justify-content-between align-items-center py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ trans('Application Entry') }}</li>
                    </ol>
                </nav>
                <div class="action-btn">
                    <a href="{{ route('cms.application-entries.index') }}"
                        class="btn btn-outline-warning btn-sm radius-md px-3 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-list me-1"></i> View List
                    </a>
                </div>
            </div>
            <x-error-alart />
        </div>
    </div>

    <div class="card mb-50">
        <div class="card-body py-4 px-lg-5">
            <div class="dm-tab tab-horizontal">
                <ul class="nav nav-tabs vertical-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ !request()->hasAny(['customer_id', 'from', 'to']) && request()->query('tab') !== 'cheque' ? 'active' : '' }}"
                            id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1" role="tab">
                            Deed Document / NOC
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->hasAny(['customer_id', 'from', 'to']) || request()->query('tab') === 'cheque' ? 'active' : '' }}"
                            id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2" role="tab">
                            Cheque Application
                        </a>
                    </li>
                </ul>

                <div class="tab-content border-0 p-0">
                    {{-- Tab 1: Deed Document / NOC --}}
                    <div class="tab-pane fade {{ !request()->hasAny(['customer_id', 'from', 'to']) && request()->query('tab') !== 'cheque' ? 'show active' : '' }}"
                        id="tab-v-1" role="tabpanel">
                        
                        <form action="{{ route('cms.application-entries.store', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="form_type" value="deed_noc">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-700 text-dark">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control" id="customer_id" name="customer_id" required  >
                                        <option value="">Select a Customer</option>
                                    </select>
                                </div>

                              
                                <div class="col-md-3">
                                    <label class="form-label fw-700 text-dark">Application Type <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select" name="type">
                                        <option value="Deed Document" @if (old('type') == 'Deed Document') selected @endif>Deed Document</option>
                                        <option value="NOC" @if (old('type') == 'NOC') selected @endif>NOC</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-700 text-dark">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate shadow-sm" name="date" value="{{ old('date', date('Y-m-d')) }}">
                                </div>
                               
                                <div class="col-md-12">
                                    <label class="form-label fw-700 text-dark">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control shadow-sm" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-submit shadow-lg">Submit Entry</button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 2: Cheque --}}
                    <div class="tab-pane fade {{ request()->hasAny(['customer_id', 'from', 'to']) || request()->query('tab') === 'cheque' ? 'show active' : '' }}"
                        id="tab-v-2" role="tabpanel">
                        {{-- Search section --}}
                        <div class="search-form mb-4 shadow-sm">
                            <form method="GET" action="{{ route('cms.application-entries.create', app()->getLocale()) }}" class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <select class="form-control"  id="customer_id2" name="customer_id"  >
                                        <option value="">Select Customer</option> 
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control datePicker" name="from" value="{{ request('from') }}" placeholder="From Date">
                                        <span class="input-group-text bg-white border-0"><i class="las la-arrow-right"></i></span>
                                        <input type="text" class="form-control datePicker" name="to" value="{{ request('to') }}" placeholder="To Date">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                   <button type="submit" class="btn btn-primary w-100 radius-xl shadow-sm"
                                            style="height: 30px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    <a href="{{ route('cms.application-entries.create', app()->getLocale()) }}?tab=cheque" class="btn btn-light border w-100 radius-md">Reset</a>
                                </div>
                            </form>
                        </div>

                        <form action="{{ route('cms.application-entries.store', app()->getLocale()) }}" method="POST">
                            @csrf
                            <input type="hidden" name="form_type" value="cheque">
                            <input type="hidden" name="type" value="Cheque">
                            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

                            <div class="mb-4">
                                <label class="form-label fw-700 text-dark">Description <span class="text-danger">*</span></label>
                                <textarea name="descriptions" class="form-control shadow-sm" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="table-responsive table-container border rounded">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th>Customer</th>
                                            <th>Bank & Branch</th>
                                            <th>Cheque Details</th>
                                            <th>Receiver</th>
                                            <th class="text-center">
                                               Select All <input type="checkbox" class="form-check-input" id="checkAll">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($entries as $entry)
                                            @foreach ($entry->details as $detail)
                                                <tr>
                                                    <td class="text-center  text-muted">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class=" text-dark">{{ $entry->customer->company_name }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="small  text-dark">{{ $detail->bank->name ?? '-' }}</div>
                                                        <div class="text-muted small">{{ $detail->branch->name ?? '-' }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="badge  text-dark border me-1">#{{ $detail->cheque_no ?? '-' }}</span>
                                                        <span class="text-primary ">{{ number_format($detail->amount) }}</span>
                                                    </td>
                                                    <td><div class="">{{ $entry->createdBy->name }}</div><div class="text-muted small">{{ $entry->created_at->format('d M, Y') }}</div></td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input checkbox" name="advance_cheque_entry_detail_id[]" value="{{ $detail->id }}">
                                                        <input type="hidden" name="customer_id[{{ $detail->id }}]" value="{{ $entry->customer->id }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr><td colspan="6" class="text-center py-4 text-muted">No cheque entries found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if (count($entries) > 0)
                               <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-submit btn-primary">Submit Entry</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.checkbox').forEach(cb => cb.checked = checked);
        });

        document.querySelectorAll('.checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const all = document.querySelectorAll('.checkbox').length;
                const checked = document.querySelectorAll('.checkbox:checked').length;
                document.getElementById('checkAll').checked = (all === checked);
            });
        });

        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $(document).ready(function () {
     

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
                            callback(res.map(item => ({ id: item.id, text: item.label   })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            const companySelect2 = new TomSelect("#customer_id2", {
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
                            companySelect2.clearOptions(); 
                            callback(res.map(item => ({ id: item.id, text: item.label   })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
     
     
  
            
        });


    </script>
@endsection