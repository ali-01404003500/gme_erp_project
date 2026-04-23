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

                                             
                                                <td class="w-25">
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
                                        <table id="zero-config" class="table dt-table-hover table-bordered align-top" style="width: 100%;">
                                            <thead class="align-top">
                                                <tr>
                                                    <th class="text-center" style="width: 8%">SL</th>
                                                    <th class="text-left" style="width: 8%">Token</th>
                                                    <th class="text-left" style="width: 10%">Customer</th>
                                                    <th class="text-left" style="width: 24%">Product</th>
                                                    <th class="text-left" style="width: 10%">Problem Type</th>
                                                    <th class="text-left" style="width: 6%">Service Date</th>
                                                    <th class="text-left" style="width: 6%">Service Status</th>
                                                    <th class="text-left" style="width: 8%">Assign By</th>
                                                    {{-- <th class="text-left" style="width: 6%">Priority</th> --}}
                                                    <th class="text-left" style="width: 6%">Service Type</th>
                                                    <th class="text-center no-content" style="width: 8%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="align-top">
                                                @foreach ($myTasks as $key => $task)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-left">{{ $task->service->service_unique_id }}</td>
                                                         <td class="text-wrap" style="word-break: break-word; min-width: 180px; white-space: normal;">
                                                            {{ optional($task)->customer->company_name }} <br>
                                                            <span class="text-muted">Address: {{ optional($task)->customer->address }}</span> 

                                                        </td>
                                                        <td class="text-wrap" style="word-break: break-word; min-width: 200px; white-space: normal;">
                                                          Product Name:   {{ optional(optional($task)->product)->getRawOriginal('name') }} <br>
                                                        Brand: <span class="text-muted">{{ optional(optional(optional($task)->product)->brand)->getRawOriginal('name') ?? 'N/A' }}</span> 


                                                        </td>
                                                       
                                                        <td>{{ $task->problem_type }}</td>
                                                        <td>{{ $task->token_date }}</td>
                                                        <td class="text-wrap text-center" style="word-break: break-word; min-width: 80px; vertical-align: middle;">
                                                                @php
                                                                    $status = strtolower($task->action); 
                                                                    $badgeClass = 'badge-secondary'; 

                                                                    if($status == 'live') $badgeClass = 'badge-success';
                                                                    elseif($status == 'started') $badgeClass = 'badge-info';
                                                                    elseif($status == 'done') $badgeClass = 'badge-primary';
                                                                    elseif($status == 'cancelled') $badgeClass = 'badge-danger';
                                                                @endphp

                                                                <span class="badge {{ $badgeClass }} p-2" style="text-transform: capitalize;">
                                                                    {{ $task->action }}
                                                                </span>
                                                            </td>
                                                        {{-- @dd($task->engineerAssign) --}}
                                                        <td>{{ @$task->engineerAssign->createdBy->name }}</td>
                                                        {{-- <td>{{ @$task->engineerAssign->service_priority }}</td> --}}
                                                        <td>{{ @$task->engineerAssign->serviceType->name??@$task->engineerAssign->service_type }}</td>
                                                        <td>
                                                         <div class="btn-group btn-group-sm" role="group"
                                                              aria-label="Small button group">
                                                            @if ((hasPermission('services.service-my-task.create') || hasPermission('services.service-my-task.update') || hasPermission('services.service-bills.create') ) && $task->action != 'Done')
                                                                <a href="{{ route('services.service-bills.create', ['token_id' => $task->id]) }}" class="btn btn-danger btn-xs" title="Details"><i class="fas fa-info-circle"></i>
                                                            @endif
                                                            @if (hasPermission('services.quotations.create'))
                                                                <a href="{{ route('services.quotations.create', ['service_id' => $task->service_id]) }}" class="btn btn-success btn-xs" title="Send to quotations"><i class="fas fa-file-invoice"></i></a>
                                                            @endif
                                                            @if (hasPermission('sales.sales-orders.create'))
                                                                <a href="{{ route('sales.sales-orders.create', ['service_id' => $task->service_id]) }}" class="btn btn-info btn-xs " title=" Send to Sales"><i class="fas fa-bars" ></i></a>
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
