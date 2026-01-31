<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Employee Information</title>

    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }

        h3,
        h4,
        h5 {
            margin: 5px 0;
            font-weight: bold;
        }

        h3 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }

        h4 {
            font-size: 14px;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px 8px;
            font-size: 12px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .section-title {
            font-size: 14px;
            margin: 15px 0 8px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .basic-info td,
        .basic-info th {
            border: none !important;
            padding: 4px 6px;
        }

        .cv-profile {
            border: 2px solid #444;
            width: 120px;
            height: 150px;
            object-fit: cover;
        }

        .cv-placeholder {
            border: 2px solid #444;
            width: 120px;
            height: 150px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 10px;
            color: #777;
            background: #f9f9f9;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .left-info {
            width: 75%;
            vertical-align: top;
        }

        .right-photo {
            width: 25%;
            vertical-align: top;
            text-align: right;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>

    <!-- HEADING -->
    <h3>Employee Information</h3>
    <h4 style="text-align:center">Post Applied: {{ optional($jobApplication->job)->title }}</h4>

    <!-- BASIC INFORMATION -->
    <table class="info-table">
        <tr>
            <td class="left-info">
                <h4>Basic Information</h4>
                <table class="basic-info">
                    <tr>
                        <th width="30%">Applicant Name</th>
                        <td>: {{ $jobApplication->name }}</td>
                    </tr>
                    <tr>
                        <th>Applicant Email</th>
                        <td>: {{ $jobApplication->email }}</td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>: {{ $jobApplication->mobile }}</td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td>: {{ optional(optional($jobApplication->job)->designation)->name }}</td>
                    </tr>
                    <tr>
                        <th>Father/Husband Name</th>
                        <td>: {{ $jobApplication->father_or_husband_name }}</td>
                    </tr>
                    <tr>
                        <th>Permanent Address</th>
                        <td>: {{ $jobApplication->permanent_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>NID No.</th>
                        <td>: {{ $jobApplication->national_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th width="35%">Document/CV</th>
                                        <td>: 
                                            @if($jobApplication->cv)
                                                <a href="{{ $jobApplication->cv}}" data-toggle="modal" data-target="#documentView" role="button"
                                                   target="_blank"> View
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                    </tr>
                </table>
            </td>
            @if ($jobApplication->image)
                <td class="right-photo">
                    <img class="cv-profile" src="{{ s3FileToBase64($jobApplication->image) }}" alt="Profile Photo">

                </td>
            @else
                <td class="right-photo">No Photo</td>
            @endif
        </tr>
    </table>

    <!-- EDUCATION -->
    <h4 class="section-title">Education Information</h4>
    <table>
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
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->examination }}</td>
                    <td>{{ $value->result }}</td>
                    <td>{{ $value->passing_year }}</td>
                    <td>{{ $value->institute }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- JOB EXPERIENCE -->
    @if ($jobApplication->jobApplicationExperiences->count() > 0)
        <h4 class="section-title">Job Experience</h4>
        <table>
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
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->company_name }}</td>
                        <td>{{ $value->designations }}</td>
                        <td>{{ $value->duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>

</html>
