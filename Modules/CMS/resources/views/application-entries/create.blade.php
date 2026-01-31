@extends('layout.app')

@section('title', 'Application Entry')
@section('description', 'Application Entry')

@section('content')
    <style>
        #right-column {
            margin-bottom: 10px !important;
        }

        .row {
            padding: 15px;
            margin-top: 10px;
        }

        .form-group label {
            margin-bottom: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            margin-top: 3px;
        }

        #title {
            padding: 0;
            margin-top: 0;
        }

        #justify-content-center {
            margin-top: 10px !important;
        }

        .card-body {
            margin-right: 7vh;
            margin-left: 7vh;
        }

        .row {
            padding: 2vh;
        }

        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            color: #3d3d3d;
            border-radius: 5px 5px 0 0;
        }

        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            color: #ffffff;
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .search-form {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                {{-- Breadcrumb --}}
                <div class="breadcrumb-main d-flex justify-content-between align-items-center">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Application Entry') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('cms.application-entries.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                            <i class="fa fa-list"></i> List
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center user-member__title mb-2">
                    <h4 class="text-capitalize">{{ trans('Application Entry') }}</h4>
                </div>
                <x-error-alart />
            </div>
        </div>

        {{-- Tabs --}}
        <div class="card mb-50">
            <div class="row justify-content-center" id="justify-content-center">

                <div class="dm-tab tab-horizontal">
                    {{-- Tab Menu --}}
                    <ul class="nav nav-tabs vertical-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ !request()->hasAny(['customer_id', 'from', 'to']) && request()->query('tab') !== 'cheque' ? 'active' : '' }}"
                                id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1" role="tab"
                                aria-selected="{{ !request()->hasAny(['customer_id', 'from', 'to']) && request()->query('tab') !== 'cheque' ? 'true' : 'false' }}">
                                Deed Document / NOC
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->hasAny(['customer_id', 'from', 'to']) || request()->query('tab') === 'cheque' ? 'active' : '' }}"
                                id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2" role="tab"
                                aria-selected="{{ request()->hasAny(['customer_id', 'from', 'to']) || request()->query('tab') === 'cheque' ? 'true' : 'false' }}">
                                Cheque
                            </a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content">

                        {{-- Tab 1: Deed Document / NOC --}}
                        <div class="tab-pane fade {{ !request()->hasAny(['customer_id', 'from', 'to']) && request()->query('tab') !== 'cheque' ? 'show active' : '' }}"
                            id="tab-v-1" role="tabpanel" aria-labelledby="tab-v-1-tab">

                            <form action="{{ route('cms.application-entries.store', app()->getLocale()) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="form_type" value="deed_noc">

                                <div class="row mb-2 mt-3">
                                    <div class="form-group col-md-6 mb-25">
                                        <label for="date" class="color-dark fs-14 fw-500 align-center">Date <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatdate ip-gray radius-xs b-light px-15"
                                            name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                                            placeholder="Date">
                                    </div>

                                    <div class="form-group col-md-6 mb-25">
                                        <label for="type" class="color-dark fs-14 fw-500 align-center">Application Type
                                            <span class="text-danger">*</span></label>
                                        <select class="form-control tom-select" name="type" id="type">
                                            <option value="Deed Document" @if (old('type') == 'Deed Document') selected @endif>
                                                Deed Document</option>
                                            <option value="NOC" @if (old('type') == 'NOC') selected @endif>NOC
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 mb-25">
                                        <label for="customer_id" class="color-dark fs-14 fw-500 align-center">Customer <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control tom-select" name="customer_id" id="customer_id"
                                            required>
                                            <option value="">Select a Customer</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('customer_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 mb-25">
                                        <label for="description" class="color-dark fs-14 fw-500 align-center">Description
                                            <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control ip-gray radius-xs b-light px-15"
                                            placeholder="Remarks">{{ old('description') }}</textarea>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Tab 2: Cheque --}}
                        <div class="tab-pane fade {{ request()->hasAny(['customer_id', 'from', 'to']) || request()->query('tab') === 'cheque' ? 'show active' : '' }}"
                            id="tab-v-2" role="tabpanel" aria-labelledby="tab-v-2-tab">

                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('cms.application-entries.create', app()->getLocale()) }}"
                                class="search-form mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control tom-select" name="customer_id">
                                            <option value="">Select Customer</option>
                                            @foreach ($customerSearch as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }} - {{ $customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control datePicker" name="from"
                                                value="{{ request('from') }}" placeholder="From Date"
                                                autocomplete="off" />
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control datePicker" name="to"
                                                value="{{ request('to') }}" placeholder="To Date" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-sm btn-primary"><i
                                                    class="fa fa-search"></i> Search</button>
                                            <a href="{{ route('cms.application-entries.create', app()->getLocale()) }}?tab=cheque"
                                                class="btn btn-sm btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('cms.application-entries.store', app()->getLocale()) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="form_type" value="cheque">

                                <div class="form-group col-md-12 mb-25">
                                    <label for="cheque_description"
                                        class="color-dark fs-14 fw-500 align-center">Description
                                        <span class="text-danger">*</span></label>
                                    <textarea name="descriptions" id="cheque_description" class="form-control ip-gray radius-xs b-light px-15"
                                        placeholder="Remarks">{{ old('description') }}</textarea>
                                    <input type="hidden" name="type" value="Cheque">
                                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                                </div>

                                {{-- Cheque Table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">SL</th>
                                                <th width="20%">Customer Info</th>
                                                <th width="20%">Bank Info</th>
                                                <th width="20%">Cheque Info</th>
                                                <th width="20%">Receive Info</th>
                                                <th width="15%"><input type="checkbox" id="checkAll"> Select All</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($entries) > 0)
                                                @foreach ($entries as $entry)
                                                    @foreach ($entry->details as $detail)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>

                                                            {{-- Customer Info --}}
                                                            <td>
                                                                <strong>{{ $entry->customer->company_name }}</strong><br>
                                                                {{ $entry->customer->address }}<br>
                                                                {{ $entry->customer->phone ?? '' }}
                                                            </td>

                                                            {{-- Bank Info --}}
                                                            <td>
                                                                <strong>Bank:</strong> {{ $detail->bank->name ?? '-' }}
                                                                <br>
                                                                <strong>Branch:</strong>
                                                                {{ $detail->branch->name ?? '-' }}<br>
                                                                @php
                                                                    $documents = is_string($detail->document)
                                                                        ? json_decode($detail->document, true)
                                                                        : $detail->document;
                                                                @endphp
                                                                @if (!empty($documents) && is_array($documents))
                                                                    @foreach ($documents as $doc)
                                                                        <a href="{{ $doc }}" target="_blank"><i
                                                                                class="fa fa-image"></i></a>
                                                                    @endforeach
                                                                @endif
                                                            </td>

                                                            {{-- Cheque Info --}}
                                                            <td>
                                                                <strong>No:</strong> {{ $detail->cheque_no ?? '-' }} <br>
                                                                <strong>Date:</strong> {{ $detail->cheque_date ?? '-' }}
                                                                <br>
                                                                <strong>Amount:</strong>
                                                                {{ number_format($detail->amount) }}
                                                            </td>

                                                            {{-- Receive Info --}}
                                                            <td>
                                                                <strong>Received by:</strong> {{ $entry->createdBy->name }}
                                                                <br>
                                                                <strong>Entry Date:</strong>
                                                                {{ $entry->created_at->format('d-m-Y') }}
                                                            </td>

                                                            {{-- Action --}}
                                                            <td>
                                                                <input type="checkbox" class="form-check-input checkbox"
                                                                    name="advance_cheque_entry_detail_id[]"
                                                                    value="{{ $detail->id }}">
                                                                <input type="hidden"
                                                                    name="customer_id[{{ $detail->id }}]"
                                                                    value="{{ $entry->customer->id }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center">No cheque entries found. Please
                                                        adjust your search criteria.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if (count($entries) > 0)
                                    {{-- Submit --}}
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                            Submit Selected Cheques
                                        </button>
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
        // ✅ Check All functionality
        document.getElementById('checkAll').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.checkbox').forEach(cb => cb.checked = checked);
        });

        // ✅ If all checked manually, update master checkbox
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
    </script>
@endsection
