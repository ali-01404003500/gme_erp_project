@section('title', 'Sales Requisition list')
@section('description', 'Sales Requisition list')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Sales Requisition list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sales.sales-requisitions.create'))
                            <a href="{{ route('sales.sales-requisitions.create', app()->getLocale()) }}" class="btn px-20 btn-primary btn-sm">
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
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Requisition list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
    
                                                <td class="text-start">
                                                    <select name="customer_id" id="customer_id" class="input-sm"  data-placeholder="Select Customer">
                                                        <option value="">Select Customer</option> 
                                                    </select>
                                                </td>
    
                                                <td class="text-center">
                                                    <input type="text" class="form-control" placeholder="Search Phone Number" name="additional_phone" value="{{ old('additional_phone', request('additional_phone')) }}">
                                                </td>
    
                                                <td colspan="2">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdaterange"
                                                            name="from_to" value="{{ request('from_to') }}"
                                                            placeholder="From - To" />
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesRequisitions])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Requisition ID</th>
                                        <th>
                                            Customer Name
                                        </th>
                                        <th>
                                            Phone No
                                        </th>
                                        <th>
                                            Sales Date
                                        </th>
                                        <th>
                                            Discount
                                        </th>
                                        
                                        <th>
                                            Net Amount
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesRequisitions as $salesRequisition)
                                        <tr>
                                            <td class="text-center">{{ ($salesRequisitions->currentPage() - 1) * $salesRequisitions->perPage() + $loop->iteration  }}</td>

                                            <td><a href="{{ route('sales.sales-requisitions.show', $salesRequisition->id) }}">
                                                {{$salesRequisition->invoice_id}}
                                            </a></td>
                                            <td>
                                                <a class="text-dark fw-500"
                                                    href="{{ route('sales.sales-requisitions.show', $salesRequisition->id) }}">
                                                    {{ optional($salesRequisition->customer)->company_name }}</i>
                                                </a>

                                            </td>
                                            <td>
                                                {{ optional($salesRequisition->customer)->phone }}
                                            </td>
                                            <td>
                                                {{ $salesRequisition->invoice_date }}
                                            </td>
                                            <td>
                                                {{ number_format($salesRequisition->discount) }}
                                            </td>
                                           
                                            <td>
                                                {{ number_format($salesRequisition->net_amount) }}
                                            </td>
                                            <td>
                                                {{-- {{ number_format($salesOrder->saleOrderDeliveries->pluck('salesOrderDeliveryDetails')->flatten()->sum('quantity')) }} --}}
                                                @if($salesRequisition->status == "pending")
                                                    <span class="badge badge-round badge-warning text-capitalize">{{ $salesRequisition->status }}</span>
                                                @elseif($salesRequisition->status == "approved")
                                                    <span class="badge badge-round badge-success text-capitalize">Approved</span>
                                                @elseif($salesRequisition->status == "sended_to_sales_order")
                                                    <span class="badge badge-round badge-info text-capitalize">Sended To Sales Order</span>
                                                @elseif($salesRequisition->status == "verified")
                                                    <span class="badge badge-round badge-primary text-capitalize">{{ $salesRequisition->status }}</span>
                                                @elseif($salesRequisition->status == "rejected")
                                                    <span class="badge badge-round badge-danger text-capitalize">{{ $salesRequisition->status }}</span>
                                                @else
                                                    <span class="badge badge-round badge-danger text-capitalize">{{ $salesRequisition->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if(hasPermission('sales.sales-requisitions.verify') && $salesRequisition->status == "pending")
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('sales.sales-requisitions.edit',  ["sales_requisition"=>$salesRequisition->id, 'edit_for' => "verify"]) }}"><i
                                                                class="fas fa-user-check"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('sales.sales-requisitions.approve') && $salesRequisition->status == "verified")
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-requisitions.edit',  ["sales_requisition"=>$salesRequisition->id, 'edit_for' => "approve"]) }}"><i
                                                                class="fas fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('sales.sales-requisitions.update') && ($salesRequisition->status == "pending" || $salesRequisition->status == "approved")  )
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.sales-requisitions.edit', $salesRequisition->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.sales-requisitions.destroy') && $salesRequisition->status !== "approved" && $salesRequisition->status !== "sended_to_sales_order")
                                                        <button type="button"
                                                            data-action="{{ route('sales.sales-requisitions.destroy', $salesRequisition->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('sales.sales-requisitions.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-requisitions.show', $salesRequisition->id) }}"><i
                                                                class="fas fa-eye"></i></a>
                                                    @endif

                                                    @if(hasPermission('sales.sales-orders.create') && $salesRequisition->status == "approved")
                                                        <a class="btn btn-outline-info create-order-btn"
                                                            href="{{ route('sales.sales-requisitions.save-to-sales-order', [$salesRequisition->id]) }}"><i
                                                                class="fas fa-file-invoice"></i>
                                                        </a>
                                                    @endif

                                                    {{-- @if(hasPermission('sales.deliveries.create') && $salesRequisition->status == "approved")
                                                        <a class="btn btn-outline-info"
                                                            href="{{ route('sales.deliveries.create', ['delivery_id' => optional($salesRequisition->delivery)->id]) }}"><i
                                                                class="fas fa-truck"></i>
                                                        </a>
                                                    @endif --}}
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

            $('.create-order-btn').on('click', function(e){

                e.preventDefault();

                let url = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are going to create a Sales Order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Create it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {

                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Created!',
                            text: 'Sales Order is being generated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        setTimeout(function(){
                            window.location.href = url;
                        }, 1200);
                    }

                });

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
                            callback(res.map(item => ({ id: item.text, text: item.label })));
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
                    text: "{{ request('customer_id') }}"
                });
                companySelect.setValue("{{ request('customer_id') }}");
            @endif
        }); 
    </script>
@endSection
