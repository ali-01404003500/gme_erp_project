@section('title', 'My Task List')
@section('description', 'My Task List')
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
                                        {{ trans('My Task list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            {{-- @if (hasPermission('services.service-my-task.create'))
                                <a href="{{ route('services.service-my-task.create') }}"
                                    class="btn px-20 btn-primary btn-sm mr-5">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif --}}
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('My Task list') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="flatdaterange" value="{{ request('from_to_date') }}" name="from_to_date">
                                                    </div>
                                                </td>

                                             
                                                <td>
                                                    <select name="status" id="status" class="form-select tom-select">
                                                        <option value="">Select status</option>
                                                        <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                                                        <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Started</option>
                                                        <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                                                        <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>
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

                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 8%">SL</th>
                                                    <th class="text-center d-none">Token</th>
                                                    <th class="text-center">Customer </th>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Problem Type</th>
                                                    <th class="text-center">Service Date</th>
                                                    <th class="text-center ">Service Status</th>
                                                    <th class="text-center  d-none">Assign By</th>
                                                    <th class="text-center">Priority</th>
                                                    <th class="text-center">Service Type</th>
                                                    <th class="text-center no-content">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($myTasks as $key => $task)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-center d-none">{{ $task->service->service_unique_id }}</td>
                                                        <td>
                                                            {{ optional($task)->customer->company_name }} <br>
                                                            <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ optional($task)->customer->area?->area ?? '' }}</small> 

                                                        </td>
                                                        <td> 
                                                            {{ $task->product->withoutModelSuffix()->name }} <br>
                                                            Model: {{ $task->product->withoutModelSuffix()->model }}  
                                                        </td>
                                                      
                                                        <td>{{ $task->problem_type }}</td>
                                                        <td>{{ $task->token_date }}</td>
                                                        <td>
                                                            {{ $task->action }}
                                                        </td>
                                                        {{-- @dd($task->engineerAssign) --}}
                                                        <td class="d-none">{{ @$task->engineerAssign->createdBy->name }}</td>
                                                        <td>{{ @$task->engineerAssign->service_priority }}</td>
                                                        <td>{{ @$task->engineerAssign->serviceType->name??@$task->engineerAssign->service_type }}</td>
                                                        <td>
                                                         <div class="btn-group btn-group-sm" role="group"
                                                              aria-label="Small button group">
                                                            @if ((hasPermission('services.service-my-task.create') || hasPermission('services.service-my-task.update') || hasPermission('services.service-bills.create') ) && $task->action != 'Done')
                                                                <a href="{{ route('services.service-bills.create', ['token_id' => $task->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-eye"></i> Details</a>
                                                            @endif
                                                            @if (hasPermission('services.quotations.create'))
                                                                <a href="{{ route('services.quotations.create', ['service_id' => $task->service_id]) }}" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Send to Quotation</a>
                                                            @endif
                                                            @if (hasPermission('sales.sales-orders.create'))
                                                                <a href="{{ route('sales.sales-orders.create', ['service_id' => $task->service_id]) }}" class="btn btn-info btn-xs"><i class="fas fa-file-invoice"></i> Send to Sales</a>
                                                            @endif
                                                         </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @foreach ($services as $service)
                <div class="modal fade" id="exampleModal{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal({{ $service->id }})">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Token No</label>
                                                <input type="text" class="form-control" value="{{ $service->service_unique_id }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Token Date</label>
                                                <input type="text" class="form-control" value="{{ $service->serviceTokens->first()->token_date }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Status</label>
                                                <input type="text" class="form-control" value="{{ $service->status }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Product</label>
                                                <input type="text" class="form-control" value="{{ optional(optional($service->serviceTokens->first())->product)->name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Model</label>
                                                <input type="text" class="form-control" value="{{ optional(optional($service->serviceTokens->first())->product)->model }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Serial</label>
                                                <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->serial }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Customer</label>
                                                <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->customer->company_name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Contact No</label>
                                                <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->customer->phone }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Customer Address</label>
                                                <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->customer->address }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div>
                                                <label class="col-form-label">Assign By</label>
                                                <input type="text" class="form-control" value="{{ $service->createdBy->name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="text-uppercase">Add Pending Service Token</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="addPendingServiceToken">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product">Product</label>
                                            <input type="text" class="form-control" value="{{ optional(optional($service->serviceTokens->first())->product)->name }} Model: {{ optional(optional($service->serviceTokens->first())->product)->model }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="serial">Serial</label>
                                            <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->serial_number }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="problem_details">Problem Details</label>
                                            <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->problem_details }}" readonly>                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="problem_type">Problem Type</label>
                                            <input type="text" class="form-control" value="{{ optional($service->serviceTokens->first())->problem_type }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal({{ $service->id }})">Close</button>
                            </div>
                        </div>
                    </div>
                </div>  
            @endforeach--}}
        </div>
    </div> 
@endsection
{{-- @section('page_scripts')

    <script>
        function openModal(id) {
            $('#exampleModal' + id).modal('show');
        }
    </script>
    <script>
        function closeModal(id) {
            $('#exampleModal' + id).modal('hide');
        }
    </script>

@endSection --}}
