@section('title', 'License Requisition List')
@section('description', 'License Requisition List')
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
                                        {{ trans('menu.usg-opg-license-requisition-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('licenses.usg-opg-license-requisitions.create'))
                                    <a href="{{ route('licenses.usg-opg-license-requisitions.create') }}" class="btn px-20 btn-primary ">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.usg-opg-license-requisition-list-menu-title')}}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>

                                            <td>
                                                <select name="status" id="status" class="form-control"  data-placeholder="Select Type">
                                                   @if (hasPermission('licenses.usg-opg-license-requisitions.show'))
                                                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option> 
                                                    @endif
                                                    @if (hasPermission('licenses.usg-opg-license-requisitions.update'))
                                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    @endif  

                                                    @if (hasPermission('licenses.usg-opg-license-requisitions.approve'))
                                                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Verified</option>
                                                    @endif 
                                                
                                                    @if (hasPermission('licenses.usg-opg-sms.update'))
                                                        <option value="SMS Send" {{ request('status') == 'SMS Send' ? 'selected' : '' }}>Complete</option>
                                                    @endif 
                                                    
                                                </select>
                                            </td>  

                    
                                                      


                                            <td>
                                                <select name="customer_id" id="customer_id" class="form-control"
                                                    data-placeholder="Select Customer">
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
                            <table id="zero-config" class="table dt-table-hover table-bordered text-wrap" data-page='@include('utils.table_paginate', ['data' => $uSGOrOPGLicenseRequisitions])'
                                style="width:100%; ">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer Name</th> 
                                        <th>Product Name</th>
                                        <th>Dongle Id</th> 
                                        <th>Balance</th>
                                        <th>Valid Period</th>
                                        <th>Prepared By</th>
                                        <th>Status</th>
                                        <th class="no-content text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($uSGOrOPGLicenseRequisitions as $value)
                                        <tr>
                                        <td class="text-center">{{ ($uSGOrOPGLicenseRequisitions->currentPage() - 1) * $uSGOrOPGLicenseRequisitions->perPage() + $loop->iteration  }}</td> 
                                            <td>
                                                {{ $value->customer->company_name }}<br>
                                                <span class="text-muted">{{ $value->customer->address }}</span>
                                            </td>
                                           <td>
                                                {{ $value->product->withoutModelSuffix()->name }}<br>
                                                <span class="text-muted">{{ $value->product->model }}</span>
                                            </td>  
                                            <td>
                                                {{ $value->dongles->dongle_id }}<br>
                                                <span class="text-muted">{{ $value->software_version }}</span>

                                            </td>
                                            <td>{{ number_format(0,2) }}</td>
                                            <td> 
                                                {{ $value->valid_period.' '.ucfirst($value->valid_period_type) }}<br>
                                                ({{ $value->start_date }} to {{ $value->expired_date }})
                                            </td>
                                            <td>{{ $value->createdBy->name }}</td>
                                            <td>@if($value->status == 'Approved') <span class="badge badge-round badge-success">Approved</span>  @endif
                                                @if($value->status == 'Pending') <span class="badge badge-round badge-warning">Pending</span> @endif
                                                @if($value->status == 'Rejected') <span class="badge badge-round badge-danger">Rejected</span> @endif
                                                @if($value->status == 'SMS Send') <span class="badge badge-round badge-info">SMS Send</span> @endif
                                                @if($value->status == 'SMS Deny') <span class="badge badge-round badge-danger">SMS Deny</span> @endif
                                            </td>          
                                            
                                            
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if ($value->status == 'Pending')
                                                        @if (hasPermission('licenses.usg-opg-license-requisitions.approve'))
                                                            <a class="btn btn-outline-success"
                                                                href="{{ route('licenses.usg-opg-license-requisitions.approve', $value->id) }}"
                                                                title="Approve"><i class="fas fa-check"></i></a>
                                                        @endif
                                                    @endif      
                                                    @if ($value->status == 'Approved')
                                                        @if (hasPermission('licenses.usg-opg-sms.update'))
                                                            <a class="btn btn-outline-success"
                                                                href="{{ route('licenses.usg-opg-sms.edit', $value->id) }}"
                                                                title="SMS Send"><i class="fas fa-envelope"></i></a>
                                                        @endif
                                                    @endif      
                                                    @if( $value->status == 'Approved'|| $value->status == 'Pending')
                                                        @if (hasPermission('licenses.usg-opg-license-requisitions.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('licenses.usg-opg-license-requisitions.edit', $value->id) }}"
                                                                title="Edit"><i class="far fa-edit"></i></a>
                                                        @endif
                                                    
                                                        @if (hasPermission('licenses.usg-opg-license-requisitions.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('licenses.usg-opg-license-requisitions.destroy', $value->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"
                                                                title="Delete"><i class="far fa-trash-alt"></i></button>
                                                        @endif
                                                    @endif
                                                    @if (hasPermission('licenses.usg-opg-license-requisitions.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('licenses.usg-opg-license-requisitions.show', $value->id) }}"
                                                            title="View"><i class="fas fa-eye"></i></a>
                                                        
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

    <div class="modal fade inputForm-modal" id="recommendModal" tabindex="-1" role="dialog"
        aria-labelledby="recommendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="recommendModalLabel">
                    <h5 class="modal-title">Recommend </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="recommendForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Recomended Comments</label>
                            <div class="col-sm-12">
                                <textarea name="recommended_comments" id="recommended_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Recommend</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="approveModal" tabindex="-1" role="dialog"
        aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="approveModalLabel">
                    <h5 class="modal-title">Approve </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="approveForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Approve Comments</label>
                            <div class="col-sm-12">
                                <textarea name="approveed_comments" id="approveed_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Approve</button>
                    </div>
                </form>
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
