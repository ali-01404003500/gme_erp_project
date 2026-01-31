@section('title', 'Job Application List')
@section('description', 'Job Application List')
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
                                        {{ trans('Job Application List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                {{-- @if (hasPermission('hrm.leaves.create'))
                                    <a href="{{ route('hrm.leaves.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif --}}
                                <a href="{{ route('hrm.job-applications.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                {{-- <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a> --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Job Application List') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <!-- SEARCHING FORM -->
                            @include('HRMS::recruitment/applications/includes/filter')
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $jobApplications])'
                                style="width:100%">
                                <thead>
                                    <tr class="table-header-bg">
                                        <th width="5%">SL</th>
                                        <th width="15%">Branch</th>
                                        <th width="20%">Job Title</th>
                                        <th width="20%">Applicant</th>
                                        <th width="20%">Address</th>
                                        <th class="text-center" width="10%">Status</th>
                                        <th class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse ($jobApplications ?? [] as $key => $jobApplication)
                                        <tr>
                                        <td class="text-center">{{ ($jobApplications->currentPage() - 1) * $jobApplications->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                {{ optional(optional($jobApplication->job)->branch)->name ?? '' }}
                                            </td>
                                            <td>
                                                <p>{{ optional($jobApplication->job)->title }}</p>
                                                <p><b>Submitted At:</b> <span
                                                        class="text-success">{{ $jobApplication->created_at->format('Y-m-d') }}</span>
                                                </p>
                                            </td>

                                            <td>
                                                <b>{{ $jobApplication->name }}</b>
                                                <p>{{ $jobApplication->email }}</p>
                                                <p>{{ $jobApplication->mobile }}</p>
                                            </td>

                                            <td>
                                                <p><strong>Present:
                                                    </strong>{{ $jobApplication->present_address ?? 'N/A' }}</p>
                                                <p><strong>Permanent: </strong>{{ $jobApplication->permanent_address }}</p>
                                            </td>
                                            <td class="text-center">

                                                @if ($jobApplication->status == 0)
                                                    Pending
                                                @elseif($jobApplication->status == 1)
                                                    Select For Interview
                                                @elseif($jobApplication->status == 2)
                                                    Attended
                                                @elseif($jobApplication->status == 3)
                                                    Selected
                                                @elseif($jobApplication->status == 4)
                                                    Hired
                                                @endif

                                            </td>
                                            <td class="text-center">

                                                @include('HRMS::recruitment/applications/includes/action-buttons')

                                            </td>
                                        </tr>
                                    @empty
                                        @noTableRecordsFound
                                    @endforelse
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
    </div>
@endsection

@section('page_scripts')

<script>

    function updateStatus(id, status)
    {
       
        let url = "{{ route('hrm.job-applications.update-status',':id') }}";
        url = url.replace(':id', id);

        let html = `<div style='margin: 10px 0'><b>You will update this Application !</b></div>`;
        html    += `<select id="swal-status" class="form-control tom-select">
                        <option value="0">Pending</option>
                        <option value="1">Select For Interview</option>
                        <option value="2">Attended</option>
                        <option value="3">Selected</option>
                        <option value="4">Hired</option>
                    </select>`

        let confirmButtonText = 'Select For Interview';

        if (status==2) {

            confirmButtonText = 'Attended';

        } else if(status==3) {

            html += `<br><textarea class="form-control" id="remarks" cols="3" placeholder="Add Remarks"></textarea>`

            confirmButtonText = 'Selected';

        } else if(status==4) {

            confirmButtonText = 'Hired';
        }
        
        
        Swal.fire({
            title: 'Are you sure ?',
            html: html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            width:400,
        }).then((result) =>{
        
            if(result.value){

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}',
                        status: $('#swal-status').val(),
                        remarks: $('#remarks').val()
                    },
                    success: function(response){
                        if(response.status){
                            location.reload();
                        }
                    }
                });
            }
        })

        $("#swal-status").val(status)
    }

    function AddToEmployee(id)
    {
       
        let url = "{{ route('hrm.employees.create') }}?source=recruitment&job_id="+id;
        

        let html = `<div style='margin: 10px 0'><b>You will update this Application !</b></div>`;
       
        
        Swal.fire({
            title: 'Are you sure ?',
            html: html,
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Add To Employee',
            width:400,
        }).then((result) =>{
        
            if(result.value){

                window.location.href=url;
            }
        })

    }

    </script>

@stop
