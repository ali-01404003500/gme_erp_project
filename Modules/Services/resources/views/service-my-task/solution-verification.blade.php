@section('title', 'Service Solution Verification')
@section('description', 'Service Solution Verification')
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
                                        {{ trans('Service Solution Verifications') }}</li>
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Service Solution Verifications') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 2%">Sl</th>
                                                <th class="text-center" style="width: 15%">Customer</th>
                                                <th class="text-center" style="width: 15%">Product info</th>
                                                <th class="text-center" style="width: 15%">Problem Type</th>
                                                <th class="text-center" style="width: 15%">Date</th>
                                                <th class="text-center no-content" style="width: 20%">Old Solution
                                                </th>
                                                <th class="text-center no-content" style="width: 23%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($serviceMyTasks as $key => $task)
                                                <tr data-action="{{ $task->action }}">
                                                    <td class="text-center" style="vertical-align: top; width: 2%;">
                                                        {{ $key + 1 }}</td>
                                                    <td style="vertical-align: top;">{{ optional($task->serviceToken->customer)->company_name }}</td>
                                                    <td style="vertical-align: top;">
                                                        Invoice ID: {{ @$task->serviceToken->service->service_unique_id }} <br>
                                                        Product Name: {{ @$task->serviceToken->product->name }} <br>
                                                        Serial No: {{ @$task->serviceToken->serial_number }}
                                                    </td>
                                                    <td style="vertical-align: top; width: 15%;">{{ $task->serviceToken->problem_type }}</td>
                                                    <td style="vertical-align: top; width: 15%;">
                                                        Invoice Date: {{ @$task->serviceToken->invoice_date }} <br>
                                                        Expire Date: {{ @$task->serviceToken->expire_date }}
                                                    </td>
                                                    <td style="vertical-align: top; width: 20%;">
                                                        <div style="overflow: hidden; white-space: wrap;">
                                                            {{ $task->description ?? '' }}
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: top; width: 20%;">
                                                        <form id="service-form-{{ $key + 1 }}"
                                                            action="{{ route('services.service-my-task.solution-verification-store', $task->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-2">
                                                                <textarea name="description" class="form-control description-field" placeholder="Descriptions" required></textarea>
                                                            </div>
                                                           
                                                             <button type="submit"
                                                                    class="btn btn-sm btn-primary small-btn d-inline-block me-2 submit-btn"
                                                                    name="status"
                                                                    value="Verified"
                                                                    data-requires-description="true">Update</button>

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger small-btn d-inline-block me-2 submit-btn"
                                                                    name="status"
                                                                    value="Unchanged"
                                                                    data-requires-description="false">Unchanged</button>
                                                           
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
    document.querySelectorAll('form[id^="service-form-"]').forEach(form => {
        const description = form.querySelector('.description-field');
        const buttons = form.querySelectorAll('.submit-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const needsDescription = this.getAttribute('data-requires-description') === 'true';
                if (needsDescription) {
                    description.setAttribute('required', 'required');
                } else {
                    description.removeAttribute('required');
                }
            });
        });
    });
</script>

@endsection
