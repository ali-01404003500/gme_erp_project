@section('title', 'Applicant Details')
@section('description', 'Applicant Details')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30 mt-50">
                    <h3 class="text-capitalize">{{ trans('Applicant Details') }}</h3>
                    <div class="row">
                        <a href="{{ route('hrm.job-applications.show', $jobApplication->id) }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                            target="_blank" class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                            <i class="las la-file-pdf fs-16"></i> PDF
                        </a>
                        {{-- <a href="{{ route('hrm.job-applications.show', $jobApplication->id) }}?export=pdf"
                            target="_blank" class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a> --}}
                        <a href="{{ route('hrm.job-applications.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                class="fa fa-list"></i> List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-50" style="padding-left: 10vh; padding-right: 10vh; padding-top: 5vh; padding-bottom: 5vh">
            <div class="row justify-content-center" id="justify-content-center">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport"
                        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title>{{ $jobApplication->name }}</title>
                </head>

                <style>
                    .table-bordered {
                        border-collapse: collapse;
                        width: 100%;
                        font-family: Arial, sans-serif;
                        font-size: 14px;
                    }

                    .table-bordered th,
                    .table-bordered td {
                        padding: 8px 10px;
                        border: 1px solid #ddd;
                    }

                    .table-bordered th {
                        background-color: #f8f8f8;
                        text-align: left;
                        font-weight: bold;
                    }

                    .outer-table th,
                    .outer-table td {
                        padding: 6px 10px;
                        vertical-align: top;
                    }

                    .outer-table th {
                        background-color: #f2f2f2;
                        color: #333;
                    }

                    .outer-table td {
                        background-color: #fff;
                        color: #555;
                    }

                    .cv-profile {
                        width: 150px;
                        height: auto;
                        border-radius: 8px;
                        box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
                        object-fit: cover;
                    }

                    .hidden-print {
                        display: none;
                    }

                    .table-title {
                        font-size: 14px;
                        font-weight: bold;
                        color: #333;
                        padding-bottom: 10px;
                        border-bottom: 2px solid #ddd;
                        margin-bottom: 10px;
                    }

                    h5 {
                        font-size: 14px;
                        margin: 0;
                        font-weight: bold;
                        color: #444;
                    }

                    td span {
                        color: #666;
                    }

                    /* Styling the document view link */
                    a {
                        color: #007bff;
                        text-decoration: none;
                    }

                    a:hover {
                        text-decoration: underline;
                    }
                </style>

                <body>
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2">
                                <h5><strong>Basic Information</strong></h5>
                            </td>
                        </tr>

                        <tr>
                            <!-- LEFT SIDE -->
                            <td width="52%" style="border: none !important">
                                <table class="outer-table" style="width: 100%; font-size: 12px !important;">
                                    <tr>
                                        <th width="35%">Applicant Name</th>
                                        <td>: {{ $jobApplication->name }}</td>
                                    </tr>

                                    <tr>
                                        <th width="35%">Applicant Email</th>
                                        <td>: {{ $jobApplication->email }}</td>
                                    </tr>

                                    <tr>
                                        <th width="35%">Personal Mobile No.</th>
                                        <td>: <span>{{ $jobApplication->mobile}}
                                    </tr>

                                    <tr>
                                        <th width="35%">Designation</th>
                                        <td>: {{ optional(optional($jobApplication->job)->designation)->name }}</td>
                                    </tr>

                                    {{-- <tr>
                                        <th width="35%">Father/Husband Name</th>
                                        <td>: {{ $jobApplication->father_or_husband_name }}</td>
                                    </tr> --}}

                                    <tr>
                                        <th width="35%">Present Address</th>
                                        <td>: <span>{{ $jobApplication->present_address ?? 'N/A' }}</span></td>
                                    </tr>

                                    <tr>
                                        <th width="35%">Permanent Address</th>
                                        <td>: <span>{{ $jobApplication->permanent_address ?? 'N/A' }}</span></td>
                                    </tr>

                                    {{-- <tr>
                                        <th width="35%">NID No.</th>
                                        <td>: <span>{{ $jobApplication->national_id ?? 'N/A' }}</span></td>
                                    </tr> --}}

                                    <tr>
                                        <th width="35%">Document/CV</th>
                                        <td>:
                                            @if($jobApplication->cv)
                                                {{-- <a href="{{ $jobApplication->cv}}" data-toggle="modal"
                                                    data-target="#documentView" role="button" target="_blank"> --}}
                                                    <a href="{{ asset('storage/' . $jobApplication->cv) }}" target="_blank">
                                                        <i class="fa fa-eye"></i> View Document
                                                    </a>
                                            @else
                                                    N/A
                                                @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            {{-- @dd($jobApplication); --}}
                            <!-- RIGHT SIDE -->
                            <td width="12%" style="vertical-align: top">
                                <img class="cv-profile" src="{{ s3FileToBase64($jobApplication->image) }}"
                                    alt="Profile Photo">
                            </td>
                        </tr>
                    </table>

                    @if(!empty($experience))
                        <div class="col-sm-12 mt-3">
                            <h4 class="bolder" style="color: black">Job Experience</h4>

                            <div style="padding:10px; border:1px solid #ddd;">
                                {!! nl2br(e($experience)) !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($education))
                        <div class="col-sm-12 mt-3">
                            <h4 class="bolder" style="color: black">Education</h4>

                            <div style="padding:10px; border:1px solid #ddd;">
                                {!! nl2br(e($education)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- <div class="col-sm-12 page-break-avoid" style="margin-top: 10px !important">
                        <h4 class="bolder" style="color: black">Education Information </h4>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Examination</th>
                                    <th>CGPA / Number</th>
                                    <th>Passing Year</th>
                                    <th>Institute / Board</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobApplication->jobApplicationEducations as $key => $value)
                                <tr id="education">
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        {{ $value->examination }}
                                    </td>
                                    <td>
                                        {{ $value->result }}
                                    </td>
                                    <td>
                                        {{ $value->passing_year }}
                                    </td>
                                    <td>
                                        {{ $value->institute }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div> --}}


                    <!-- JOB EXPERIENCE -->
                    {{-- <div class="col-sm-12 page-break-avoid" style="margin-top: 10px !important">
                        <h4 class="bolder" style="color: black">Job Experience </h4>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Company Name</th>
                                    <th>Designation</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobApplication->jobApplicationExperiences as $key => $value)
                                <tr id="education">
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        {{ $value->company_name }}
                                    </td>
                                    <td>
                                        {{ $value->designations }}
                                    </td>
                                    <td>
                                        {{ $value->duration }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div> --}}

                </body>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')



@endSection