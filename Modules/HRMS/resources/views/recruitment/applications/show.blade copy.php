@section('title', 'Leave Application List')
@section('description', 'Leave Application List')
@extends('layout.app')

@section('page-header')
    <i class="fa fa-empire"></i> <strong
        style="color: black !important">{{ optional($jobApplication->job)->title }}</strong>
@endsection

@push('style')
    <style>
        #print {
            display: none !important;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            #show {
                display: none !important;
            }

            #print {
                display: block !important;
            }

            .widget-box {
                border: none !important;
            }

            .page-break-avoid {
                page-break-inside: avoid !important;
            }
        }

        .basic-information tr td {
            border: none !important;
            padding: 1px;
        }

        .basic-information tr th {
            border: none !important;
            padding: 1px;
        }

        .table>tbody>tr>td {
            padding: 5px !important;
        }

        .cv-profile {
            border: 3px solid #4a4242;
            width: 120px;
            height: 150px;
        }

    </style>
@endpush



@section('content')

    <div class="widget-box">
        <div class="widget-header widget-header-flat no-print">
            <h4 class="widget-title lighter">
                <strong style="color: black !important">
                    {{ optional($jobApplication->job)->title }}
                </strong>
            </h4>

            <span class="widget-toolbar">
                <a href="{{ route('hrm.job-applications.index') }}">
                    <i class="fa fa-list-alt"></i> Application List
                </a>
            </span>
            <span class="widget-toolbar">

                <div class="inline dropdown-hover">
                    <a href="#">
                        Export
                        <i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
                    </a>

                    <ul
                        class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
                        <li class="active">
                            <a href="{{ request()->url }}?export_type=pdf" class="blue">
                                <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
                                PDF
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" onclick="print()">
                                <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                Print
                            </a>
                        </li>

                    </ul>
                </div>
            </span>
        </div>


        <div class="page-header px-1" style="min-height: 10px !important;">


            <div class="row">

                <div style="margin-top:20px"></div>

                @include('HRMS::recruitment/applications/document-view-modal')

                <!-- HEADING -->
                <div class="col-sm-12" style="width: 100%">

                    <h4 style="text-align: center; margin: 0; margin-top: 10px; margin-bottom: 10px">
                        Post: <strong>{{ optional($jobApplication->job)->title }}</strong>
                    </h4>
                </div>



                <!-- JOB APPLICANT TABLE -->
                <div class="col-sm-10 col-sm-offset-1">
                    <table class="table basic-information">

                        <tr>
                            <td colspan="2"
                                style="margin: 0; padding: 0; padding-top: 20px; padding-bottom: 8px; border: none">
                                <h5
                                    style="font-size: 15px; width: 96%; margin: 0; background-color: #cac2c26e !important; padding: 10px;">
                                    <strong>Basic Information</strong>
                                </h5>
                            </td>
                        </tr>

                        <tr>

                            <!-- LEFT SIDE TR -->
                            <td width="52%" style="border: none !important">
                                <table class="table" style="border: none !important">

                                    <!-- APPLICANT NAME -->
                                    <tr>
                                        <th width="35%" class="text-left">Applicant Name</th>
                                        <td>: {{ $jobApplication->name }}</td>
                                    </tr>

                                    <!-- APPLICANT EMAIL -->
                                    <tr>
                                        <th width="35%" class="text-left">Applicant Email</th>
                                        <td>: {{ $jobApplication->email }}</td>
                                    </tr>


                                    <!-- PERSONAL MOBILE NO. -->
                                    <tr>
                                        <th width="35%">Personal Mobile No.</th>
                                        <td>: <span>{{ $jobApplication->phone }}</span>
                                        </td>
                                    </tr>


                                    <!-- DESIGNATION -->
                                    <tr>
                                        <th width="35%">Designation</th>
                                        <td>: {{ optional(optional($jobApplication->job)->designation)->name }}
                                        </td>
                                    </tr>




                                    <!-- FATHER'S NAME -->
                                    <tr>
                                        <th width="35%">Father/Husband Name</th>
                                        <td>: {{ $jobApplication->father_or_husband_name }}
                                        </td>
                                    </tr>

                                    <!-- PRESENT ADDRESS -->
                                    <tr>
                                        <th width="35%">Present Address</th>
                                        <td>: <span>{{ $jobApplication->present_address ?? 'N/A' }}</span>
                                        </td>
                                    </tr>

                                    <!-- PERMAMENT ADDRESS -->
                                    <tr>
                                        <th width="35%">Permanent Address</th>
                                        <td>: <span>{{ $jobApplication->permanent_address ?? 'N/A' }}</span>
                                        </td>
                                    </tr>

                                    <!-- NID NO. -->
                                    <tr>
                                        <th width="35%">NID No.</th>
                                        <td>: <span>{{ $jobApplication->nid_number }}</span>
                                        </td>
                                    </tr>

                                    <!-- NID NO. -->
                                    <tr class="hidden-print">
                                        <th width="35%">Document/CV</th>
                                        <td>: 
                                            @if (file_exists($jobApplication->cv_file))
                                                <a href="#documentView" data-toggle="modal" data-target="#documentView" role="button"
                                                   target="_blank">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                
                                            @endif
                                        </td>
                                    </tr>

                                </table>
                            </td>

                            <!-- RIGHT SIDE TR -->
                            <td width="12%" style="vertical-align: top">
                                @if (request('export_type') == 'pdf')
                                    @if (file_exists($jobApplication->image))
                                        <img class="cv-profile" src="{{ $jobApplication->image }}" alt="">
                                    @endif
                                @else
                                    @if (file_exists($jobApplication->image))
                                        <img class="cv-profile" src="{{ asset($jobApplication->image) }}" alt="">
                                    @endif
                                @endif

                            </td>
                        </tr>



                    </table>


                    <!-- EDUCATIONAL INFORMATION -->
                    <div class="col-sm-12 page-break-avoid" style="margin-top: 10px !important">
                        <h4 class="bolder" style="color: black">Education Information </h4>

                        <table id="myTable" class="table table-bordered edu1">
                            <thead>
                                <tr>
                                    <td width="5%">SL</td>
                                    <td>Examination</td>
                                    <th>CGPA / Number</th>
                                    <td>Passing Year</td>
                                    <td>Institute / Board</td>
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
                    </div>


                    <!-- JOB EXPERIENCE -->
                    <div class="col-sm-12 page-break-avoid" style="margin-top: 10px !important">
                        <h4 class="bolder" style="color: black">Job Experience </h4>

                        <table id="myTable" class="table table-bordered edu1">
                            <thead>
                                <tr>
                                    <td width="5%">SL</td>
                                    <td>Company Name</td>
                                    <th>Designation</th>
                                    <td>Duration</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobApplication->jobApplicationExperiences as $key => $value)
                                    <tr id="education">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            {{ $value->company }}
                                        </td>
                                        <td>
                                            {{ $value->designation }}
                                        </td>
                                        <td>
                                            {{ $value->duration }}
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
@endsection


@section('page_scripts')

    <script src="{{ asset('assets/custom_js/fileViewer.js') }}"></script>
    <script>


        function printWithoutImg() {

            $('.cv-profile').css('display', 'none')

            window.print()

            $('.cv-profile').css('display', 'block')

        }
    </script>
@endsection
