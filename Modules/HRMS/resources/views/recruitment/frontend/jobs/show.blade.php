<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>Job Opportunity - {{ $job->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f8f9fa;
        }

        .job-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .job-header {
            background: linear-gradient(135deg, rgb(36, 124, 124) 0%, rgb(36, 124, 124) 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
        }

        .job-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .company-name {
            font-size: 18px;
            opacity: 0.9;
        }

        .job-content {
            padding: 30px;
        }

        .section-title {
            color: rgb(36, 124, 124);
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
            font-size: 20px;
        }

        .job-info-card {
            background-color: #f8f9fa;
            border-left: 4px solid rgb(36, 124, 124);
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            margin-bottom: 10px;
            display: flex;
        }

        .info-label {
            font-weight: 600;
            min-width: 160px;
        }

        .apply-btn {
            background: rgb(36, 124, 124);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .apply-btn:hover {
            background: rgb(36, 124, 124);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(33, 33, 33, 0.2);
        }

        .sidebar-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .sidebar-card:hover {
            box-shadow: 0 0 11px rgba(33, 33, 33, 0.2);
        }

        .sidebar-header {
            background: rgb(36, 124, 124);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }

        .sidebar-body {
            padding: 20px;
        }


        .action-list a {
            color: #495057;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .action-list a:hover {
            background-color: #e9ecef;
            color: rgb(36, 124, 124);
            box-shadow: 0 2px 5px rgba(33, 33, 33, 0.1);
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                width: 100%;
                position: absolute;
                left: 0;
                top: 0;
            }

            .no-print {
                display: none !important;
            }

            .job-header {
                background: rgb(36, 124, 124) !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
            }

            .section-title {
                color: rgb(36, 124, 124) !important;
                -webkit-print-color-adjust: exact;
            }

            .job-info-card {
                border-left: 4px solid rgb(36, 124, 124) !important;
                -webkit-print-color-adjust: exact;
            }

            .sidebar-card {
                box-shadow: none;
                border: 1px solid #dee2e6 !important;
            }

            .sidebar-header {
                background: rgb(36, 124, 124) !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
            }

            a[href]:after {
                content: none !important;
            }
        }

        /* HARD RESET for section bodies */
    </style>
    <style>
        /* 1) Make all text align the same */
        .job-content,
        .job-content p,
        .job-content div,
        .job-content li {
            text-align: left;
            /* change to justify if you prefer */
            line-height: 1.6;
        }

        /* 2) Make lists start at the same left edge as paragraphs/headings */
        
        .job-content p,
        .job-content ol,
        .job-content ul {
            margin-left: 0 !important;
            padding-left: 0 !important;
            /* override Bootstrap */
            list-style-position: inside;
            /* numbers/bullets align to the left edge */
        }

        .job-content li {
            margin: 0 0 6px 0;
        }

        /* 3) Remove blank paragraphs the editor adds around lists */
        .job-content p:empty {
            display: none;
        }

        @supports(selector(:has(*))) {
            .job-content p:has(br:only-child) {
                display: none;
            }
        }

        /* (Optional) do the same in print so it matches screen */
        @media print {

            .job-content ol,
            .job-content ul {
                margin-left: 0 !important;
                padding-left: 0 !important;
                list-style-position: inside;
            }

            .job-content p:empty {
                display: none;
            }
        }
    </style>

</head>

<body>
    @extends('HRMS::recruitment.frontend.layout.master')

    @section('title', $job->title)

    @section('page-header')
        <i class="fa fa-tachometer"></i> {{ $job->title }}
    @endsection
    @section('content')
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="job-container print-container">
                        <div class="job-header">
                            <h1 class="job-title">{{ $job->title }}</h1>
                            <p class="company-name">{{ $company_info->company_name }} - {{ optional($job->branch)->name }}
                            </p>
                        </div>

                        <div class="job-content">
                            <div class="job-info-card">
                                <div class="info-item">
                                    <span class="info-label">Published On:</span>
                                    <span>{{ $job->created_at->format('d F, Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Employment Type:</span>
                                    <span>{{ $job->job_type }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Job Location:</span>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Salary:</span>
                                    <span>{{ $job->salary ?: 'Negotiable' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Application Deadline:</span>
                                    <span>{{ \Carbon\Carbon::parse($job->start_at)->format('d F') }} -
                                        {{ \Carbon\Carbon::parse($job->deadline_at)->format('d F, Y') }}</span>
                                </div>
                            </div>

                            <h4 class="section-title">Company Overview</h4>
                            {{ $job->company_overview }}

                            <h4 class="section-title">Job Description</h4>
                            {!! $job->description !!}

                            <h4 class="section-title">Key Responsibilities</h4>
                            {!! $job->responsibility !!}

                            <h4 class="section-title">Educational Requirements</h4>
                            {!! $job->educational_requirement !!}

                            <h4 class="section-title">Skills & Experience</h4>
                            {!! $job->experience !!}

                            <h4 class="section-title">Employee Centric Policies</h4>
                            {!! $job->employee_centric_policy !!}

                            <div class="d-flex justify-content-center mt-5 no-print">
                                @if ($job->status == '1' && $job->start_at <= date('Y-m-d') && $job->deadline_at >= date('Y-m-d'))
                                    <a href="{{ route('carrier.apply', $job->id) }}" target="_blank"
                                        class="btn apply-btn btn-lg">
                                        <i class="fa fa-paper-plane me-2"></i> Apply Now
                                    </a>
                                @elseif($job->deadline_at < date('Y-m-d'))
                                    <span class="btn btn-danger btn-lg">Application Closed</span>
                                @else
                                    <span class="btn btn-warning btn-lg">Not Yet Published</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 no-print">
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-building me-2"></i> Company Information
                        </div>
                        <div class="sidebar-body">
                            <div class="info-item">
                                <span class="info-label">Name:</span>
                                <span>{{ $company_info->company_name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Branch:</span>
                                <span>{{ optional($job->branch)->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Department:</span>
                                <span>{{ optional($job->department)->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Designation:</span>
                                <span>{{ optional($job->designation)->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-info-circle me-2"></i> Job Summary
                        </div>
                        <div class="sidebar-body">
                            <div class="info-item">
                                <span class="info-label">Published On:</span>
                                <span>{{ $job->created_at->format('d F, Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Employment Type:</span>
                                <span>{{ $job->job_type }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Job Location:</span>
                                <span>{{ $job->location }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Salary:</span>
                                <span>{{ $job->salary ?: 'Negotiable' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Deadline:</span>
                                <span>{{ \Carbon\Carbon::parse($job->deadline_at)->format('d F, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-cog me-2"></i> Actions
                        </div>
                        <div class="sidebar-body">
                            <div class="action-list">
                                <a href="javascript:void(0)" onclick="window.print()">
                                    <i class="fa fa-print me-2"></i> Print This Job
                                </a>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('page_scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Function to handle printing
            function printJob() {
                window.print();
            }

            // Add event listener when the DOM is fully loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Add any additional interactive functionality here
            });
        </script>
    @endsection
</body>

</html>
