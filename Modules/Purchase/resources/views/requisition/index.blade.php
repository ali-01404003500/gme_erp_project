@section('title', 'Requisition List')
@section('description', 'Requisition List')
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
                                        {{ trans('menu.requisition-list-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                @if (hasPermission('purchase.requisitions.import'))
                                    <a href="{{ route('purchase.requisition.import') }}" class="btn px-20 btn-warning btn-sm mr-2">
                                        <i class="las la-file-import fs-16"></i>Import
                                    </a>
                                @endif
                                @if (hasPermission('purchase.requisitions.create'))
                                    <a href="{{ route('purchase.requisitions.create') }}" class="btn px-20 btn-primary btn-sm mr-2" style="margin-left: 5px;">
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

            <style>
                .nav-icon la la-cart-arrow-down{
                    font-size: 26px;
                }
                /* Grid line styles for table */
                .grid-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .grid-table th,
                .grid-table td {
                    border: 1px solid #dee2e6;
                    padding: 12px;
                    vertical-align: middle;
                }
                .grid-table thead th {
                    background-color: #f8f9fa;
                    border-bottom: 2px solid #dee2e6;
                }
                /* Card inner table border override */
                .card .table-bordered {
                    border-collapse: collapse;
                }
                .card .table-bordered td,
                .card .table-bordered th {
                    border: 1px solid #dee2e6;
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.requisition-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    {{-- Filter table with grid lines --}}
                                    <table class="table table-bordered grid-table">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="width: 25%">
                                                    <input type="text" name="requisition_no" id="requisition_no"
                                                        class="form-control" value="{{ request('requisition_no') }}"
                                                        placeholder="Requisition Id">
                                                </td>
                                                <td class="text-center" style="width: 25%">
                                                    <select name="supplier_id" id="supplier_id" class="  input-sm"
                                                        data-placeholder="Select Supplier">
                                                        <option value=""></option> 
                                                        <option  value="{{ request('supplier_id') }}"> {{ optional($suppliers)->company_name }}</option>
                                                       
                                                    </select>
                                                </td>
                                                <td class="text-center" style="width: 25%">
                                                    <select name="customer_id" id="customer_id" class="  input-sm"
                                                        data-placeholder="Select Customer">
                                                        <option value=""></option>
                                                        <option value="{{ optional($customers)->id }}">
                                                            {{ optional($customers)->company_name }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="text-center" style="width: 25%">
                                                    <select name="branch_id" id="branch_id" class="tom-select input-sm"
                                                        data-placeholder="Select Invoice To">
                                                        <option value=""></option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                {{ request('branch_id') == $warehouse->id ? 'selected' : '' }}>
                                                                {{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <select name="status" id="status" class="tom-select input-sm"
                                                        data-placeholder="Select Status">
                                                        <option value=""></option>
                                                        <option value="0"
                                                            {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                                                        <option value="1"
                                                            {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                                                        <option value="2"
                                                            {{ request('status') == '2' ? 'selected' : '' }}>Rejected</option>
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
                                                <td class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            {{-- Main data table with grid lines --}}
                            <table id="zero-config" class="grid-table" data-page='@include('utils.table_paginate', ['data' => $requisitions])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Requisition Id</th>
                                        <th>Requisition Date</th>
                                        <th>Suplier</th>
                                        <th>Customer</th>
                                        <th>Invoice To</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $value)
                                        <tr>
                                            <td>{{ ($requisitions->currentPage() - 1) * $requisitions->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('purchase.requisitions.show', $value->id) }}">{{ $value->requisition_no }}</a>
                                            </td>
                                            <td>{{ $value->invoice_date }}</td>
                                            <td>{{ optional($value->supplier)->company_name }}</td>
                                            <td>{{ optional($value->customer)->company_name }}</td>
                                            <td>{{ optional($value->warehouse)->name }}</td>
                                            <td>
                                                @if ($value->status == 0)
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($value->status == 1)
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($value->status == 4)
                                                    <span class="badge badge-round badge-primary">Received</span>
                                                @elseif($value->status == 2)
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ optional($value->createdBy)->name }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('purchase.requisitions.approve') && ($value->created_by != Auth::user()->id || Auth::user()->id == 1))
                                                        @if ($value->status == 0)
                                                            <a class="btn btn-outline-success"
                                                                href="{{ route('purchase.requisitions.approve', $value->id) }}"><i
                                                                    class="fas fa-check" type="button" title="Approve"></i></a>
                                                        @endif
                                                    @endif

                                                    @if (hasPermission('purchase.requisitions.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('purchase.requisitions.show', $value->id) }}" title="View"><i
                                                                class="fas fa-eye"></i></a>
                                                    @endif

                                                    @if (hasPermission('purchase.requisitions.receive') && ($value->created_by != Auth::user()->id || Auth::user()->id == 1))
                                                        @if ($value->status == 4)
                                                            <a class="btn btn-outline-secondary"
                                                                href="{{ route('purchase.requisitions.received', $value->id) }}" title="Received"><i
                                                                    class="fas fa-cart-plus"></i></a>
                                                        @endif
                                                        @if ($value->status == 1)
                                                            <a class="btn btn-outline-secondary"
                                                                href="{{ route('purchase.requisitions.receive', $value->id) }}" title="Receive"><i
                                                                    class="fas fa-truck"></i></a>
                                                        @endif
                                                    @endif

                                                    @if ($value->status == 0)
                                                        @if (hasPermission('purchase.requisitions.update') && ($value->created_by == Auth::user()->id || hasPermission('supper_admin')))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('purchase.requisitions.edit', $value->id) }}" title="Edit"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif

                                                        @if (hasPermission('purchase.requisitions.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('purchase.requisitions.destroy', $value->id) }}"
                                                                class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif
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

        $(document).ready(function() {

            const supplierSelect = new TomSelect("#supplier_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('purchase.purchase-autocomplete.suppliers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            supplierSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('supplier_id'))
                supplierSelect.addOption({
                    id: "{{ request('supplier_id') }}",
                    text: "{{ request('supplier_id') }}"
                });
                supplierSelect.setValue("{{ request('supplier_id') }}");
            @endif


            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('purchase.purchase-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            companySelect.clearOptions(); 
                            callback(res.map(item => ({ id: item.id, text: item.label, phone: item.phone, address: item.address    })));
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
@endsection