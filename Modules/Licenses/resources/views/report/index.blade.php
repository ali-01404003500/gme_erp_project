@section('title', 'License Report')
@section('description', 'License Report')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('License Report') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('License Report')}}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <select name="customer_id" id="customer_id" class="form-control tom-select"
                                                    data-placeholder="Select Customer">
                                                    <option value=""></option>
                                                    @foreach ($customers as $key => $value)
                                                        <option {{ request('customer_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                          
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control datePicker" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control datePicker" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
                                            </td>
                                            <td colspan="3" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                        Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover table-bordered " data-page='@include('utils.table_paginate', ['data' => $reports])'
                                style="width:100%">
                                <thead>
                                    <tr>

                                        <th style="width: 3%;">Sl</th>
                                        <th style="width: 12%;">Customer Name</th> 
                                        <th style="width: 12%;">Address</th>      
                                         <th style="width: 18%;">SMS</th>          
                                        <th style="width: 7%;">Status</th>
                                        <th style="width: 8%;">Phone</th>
                                        <th style="width: 8%;">Dongle Id</th>
                                        <th style="width: 10%;">License Key</th>
                                        <th style="width: 8%;">Activation Date</th>
                                        <th style="width: 7%;">License Info</th>
                                        <th style="width: 7%;">Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($reports as $value)
                                        <tr>
                                        <td class="text-center" style="vertical-align: top;">{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration  }}</td>
                                            <td class="text-wrap" style="vertical-align: top; width: 12%; min-width: 150px; word-break: break-word; white-space: normal;">
                                                {{ $value->customer->company_name }}
                                            </td>
                                            <td class="text-wrap" style="vertical-align: top;">{{ $value->address }}</td>
                                            <td style="width: 250px; vertical-align: top;" class="text-wrap">
                                                <div >
                                                {{ $value->sms }}
                                                </div>
                                            </td>
                                            <td class="vertical-align: top;">
                                                @if($value->status == 'Send') <span class="badge badge-round badge-success">SMS Send</span> @endif
                                            </td>                                            
                                            <td style="vertical-align: top;">{{ $value->phone }}</td>
                                            <td style="vertical-align: top;">{{ $value->dongles->dongle_id }}</td>
                                            <td style="width: 15%; min-width: 150px; word-break: break-word; white-space: normal; vertical-align: top;">{{ $value->license_key }}</td>
                                            <td style="vertical-align: top;">{{ $value->start_date}}</td>
                                            <td style="vertical-align: top;">{{ $value->valid_period }} {{ $value->valid_period_type }}</td>
                                            <td style="vertical-align: top;">{{ $value->expired_date }}</td>
                                    
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
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
    $(".datePicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
</script>

@endSection
