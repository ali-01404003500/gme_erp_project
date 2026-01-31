@section('title', 'Cheque Collection List')
@section('description', 'Cheque Collection List')
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
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cheque Collection List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔍 Search Filters --}}
        <div class="row">
            <div class="col-md-12 my-3">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control tom-select" name="customer_id">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->company_name }} - {{ $customer->address}} - {{ $customer->address}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control datePicker" name="from"
                                            value="{{ request('from') }}" autocomplete="off" placeholder="From Date" />
                                        <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                        <input type="text" class="form-control datePicker" name="to"
                                            value="{{ request('to') }}" autocomplete="off" placeholder="To Date" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group">
                                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- 📋 Output Table --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="20%">Customer Info</th>
                                    <th width="20%">Bank Info</th>
                                    <th width="20%">Cheque Info</th>
                                    <th width="20%">Receive Info</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                               <strong>Bank:</strong> {{ $detail->bank->name ?? '-' }} <br>
                                               <strong>Branch:</strong> {{ $detail->branch->name ?? '-' }} <br>
                                               <strong>Cheque Image:</strong> @php
                                                    $documents = is_string($detail->document) ? json_decode($detail->document, true) : $detail->document;
                                                @endphp
                                                @if (!empty($documents) && is_array($documents))
                                                    @foreach ($documents as $doc)
                                                        <a href="{{ $doc }}" target="_blank"><i class="fa fa-image"></i></a>
                                                    @endforeach
                                                @endif
                                            </td>

                                            {{-- Cheque Info --}}
                                            <td>
                                                <strong>No:</strong> {{ $detail->cheque_no ?? '-' }} <br>
                                                <strong>Date:</strong> {{ $detail->cheque_date ?? '-' }} <br>
                                                <strong>Amount:</strong> {{ number_format($detail->amount) }}
                                            </td>

                                            {{-- Receive Info --}}
                                            <td>
                                                <strong>Received by:</strong> {{ $entry->createdBy->name }} <br>
                                                <strong>Entry Date:</strong> {{ $entry->created_at->format('d-m-Y') }}
                                            </td>

                                            {{-- Action --}}
                                            <td>
                                                <form action="{{ route('account.cheque-verifications.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $entry->customer_id }}">
                                                    <input type="hidden" name="bank_id" value="{{ $detail->bank_id }}">
                                                    <input type="hidden" name="branch_id" value="{{ $detail->branch_id }}">
                                                    <input type="hidden" name="cheque_no" value="{{ $detail->cheque_no }}">
                                                    <input type="hidden" name="cheque_date" value="{{ $detail->cheque_date }}">
                                                    <input type="hidden" name="amount" value="{{ $detail->amount }}">
                                                    <input type="hidden" name="source_id" value="{{ $detail->id }}">
                                                    <input type="hidden" name="source_type" value="{{ Modules\Account\Models\AdvanceChequeEntryDetail::class }}">

                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-university"></i> Deposit
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
    $(".datePicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
</script>
@endsection
