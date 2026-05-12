@section('title',"Daily Call List")
@section('description',"Daily Call List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.daily-call-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('crm.daily-calls.create'))
                            <a href="{{ route('crm.daily-calls.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.daily-call-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <select name="customer_id" id="customer_id" class="form-control "
                                                    data-placeholder="Select Customer">
                                                    <option value=""></option>
                                                  
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control flatdate" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
                                            </td>
                                            <td colspan="5" class="text-right">
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
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $dailyCalls])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Account Complain</th> 
                                    <th>Service Complain</th> 
                                    <th>Sales Complain</th> 
                                    <th>Requirement of Product</th> 
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($dailyCalls as $value)
                                    <tr>
                                        <td class="text-center">{{ ($dailyCalls->currentPage() - 1) * $dailyCalls->perPage() + $loop->iteration  }}</td>
                                        <td>
                                            <a class="text-dark fw-500"
                                                href="{{ route('crm.daily-calls.show', $value->id) }}">
                                                {{ optional($value->customer)->company_name }}</i>
                                            </a><br>
                                            <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $value->customer->area?->area }}</small> <br>
                                        </td>
                                        {{-- <td>{{ optional($value->customer)->company_name }}</td> --}}
                                        <td>{{ date('Y-m-d', strtotime($value->call_date)) }}</td>
                                        <td class="text-wrap">
                                            @if ($value->is_account_complain == 1)
                                                Yes <br>
                                                Note: {{ $value->complains_details }}
                                            @elseif ($value->is_account_complain == 0)
                                                No
                                            @endif

                                        </td> 
                                        <td class="text-wrap">
                                            @if ($value->is_service_complain == 1)
                                                Yes <br>
                                                Note: {{ $value->service_complain_details }}
                                            @elseif ($value->is_service_complain == 0)
                                                No
                                            @endif

                                        </td> 

                                        <td class="text-wrap">
                                            @if ($value->is_sales_complain == 1)
                                                Yes <br>
                                                Note: {{ $value->sales_complain_details }}
                                            @elseif ($value->is_sales_complain == 0)
                                                No
                                            @endif

                                        </td> 

                                        <td class="text-wrap">
                                            @if ($value->is_product_required == 1)
                                                Yes <br>
                                                Note: {{ $value->product_required_details }}
                                            @elseif ($value->is_product_required == 0)
                                                No
                                            @endif

                                        </td> 
                          
                                       
                                      
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if (hasPermission('crm.daily-calls.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('crm.daily-calls.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('crm.daily-calls.destroy'))
                                                        <button type="button" data-action="{{ route('crm.daily-calls.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('crm.daily-calls.show'))
                                                        <a class="btn btn-outline-primary" href="{{ route('crm.daily-calls.show', $value->id) }}"><i class="fas fa-eye"></i></a>
                                                    @endif
                                            </div>
                                        </td>
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
                        url: "{{ route('crm.autocomplete.customers') }}",
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


            @if(request('customer_id'))
                companySelect.addOption({
                    id: "{{ request('customer_id') }}",
                    text: "{{ $customer }}"
                });
                companySelect.setValue("{{ request('customer_id') }}");
            @endif

        }); 
    </script>
@endSection