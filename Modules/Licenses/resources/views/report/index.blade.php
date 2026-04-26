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
                                                <select name="customer_id" id="customer_id" class="form-control"  data-placeholder="Select Customer">
                                                    <option value=""></option> 
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
                                        <th>Sl</th>
                                        <th>Customer Name</th> 
                                        <th>SMS</th>
                                        <th>Status</th> 
                                        <th>Dongle Id</th>
                                        <th>License Key</th>
                                        <th>Activation Date</th>
                                        <th>License Info</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($reports as $value)
                                        <tr>
                                        <td class="text-center">{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration  }}</td>
                                            <td style="width: 400px;" >
                                                {{ $value->customer->company_name }}<br>
                                                <span class="text-muted">{{ $value->phone }}</span><br>
                                                <span class="text-muted text-wrap">{{ $value->address }}</span>
                                            </td>
                                           
                                            <td>
                                                <div style="width: 200px;" class="text-wrap" >
                                                {{ $value->sms }}
                                                </div>
                                            </td>
                                            <td class="vertical-align: top;">
                                                @if($value->status == 'Send') <span class="badge badge-round badge-success">SMS Send</span> @endif
                                            </td>                           
                                            <td>{{ $value->dongles->dongle_id }}</td>
                                            <td>{{ $value->license_key }}</td>
                                            <td>{{ $value->start_date}}</td>
                                            <td>{{ $value->valid_period }} {{ $value->valid_period_type }}</td>
                                            <td>{{ $value->expired_date }}</td>
                                    
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

    $(document).ready(function () {
        const companySelect = new TomSelect("#customer_id", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('licenses.license-report-autocomplete.customers') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        companySelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        $('#customer_id option:selected').text();
 
        @if(isset($customer) && $customer)
            companySelect.addOption({
                id: "{{ $customer->id }}",
                text: "{{ $customer->name }}"
            });
            companySelect.setValue("{{ $customer->id }}");
        @endif

    });
</script>

@endSection
