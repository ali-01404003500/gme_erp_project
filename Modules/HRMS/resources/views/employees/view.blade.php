<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>{{ $employee->full_name }}</title>
    <style>
        @page {
            margin: 30px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        th,
        td {
            padding: 3px;
            border: 1px solid #ddd;
        }

        .heading-style th {
            background-color: #f0f0f0;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .info-column {
            width: 50%;
            vertical-align: top;
        }

        .img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2px;
        }

        .img-container img {
            max-width: 100%;
            max-height: 100px;
            object-fit: contain;
        }

        .file-download {
            display: inline-block;
            padding: 3px 6px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
        }

        hr {
            margin: 5px 0;
            border: 0;
            border-top: 1px solid #aaa;
        }

        .na-text {
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="border: none; text-align: left; width: 70%;">
                <h3>{{ $employee->full_name }}</h3>
                @foreach ($employee->employementDetails as $key => $employementDetail)
                    <p>{{ $employementDetail->designation->name }}</p>
                    <p>{{ optional($employementDetail->branch)->name }}</p>
                @endforeach
                <p>{{ $employee->email_address }}</p>
            </td>
            <td style="border: none; text-align: right; width: 30%;">
                @if ($employee->photograph)
                    <img src="{{ s3FileToBase64($employee->photograph) }}" style="width: 80px; border: 1px solid #ccc;">
                @endif
            </td>
        </tr>
    </table>

    <hr>

    <table>
        <tr>
            <td class="info-column">
                <table>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Name</td>
                        <td style="border: none; text-align: left;">{{ $employee->full_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Father's Name</td>
                        <td style="border: none; text-align: left;">{{ $employee->father_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Mother's Name</td>
                        <td style="border: none; text-align: left;">{{ $employee->mother_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Gender</td>
                        <td style="border: none; text-align: left;">{{ $employee->gender }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Office Phone</td>
                        <td style="border: none; text-align: left;">{{ $employee->office_phone }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Personal Mobile</td>
                        <td style="border: none; text-align: left;">{{ $employee->personal_mobile }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Alternate Phone</td>
                        <td style="border: none; text-align: left;">{{ $employee->alternate_phone }}</td>
                    </tr>
                </table>
            </td>
            <td class="info-column">
                <table>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Email Address</td>
                        <td style="border: none; text-align: left;">{{ $employee->email_address }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Country</td>
                        <td style="border: none; text-align: left;">{{ $employee->country }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">City</td>
                        <td style="border: none; text-align: left;">{{ $employee->city }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Present Address</td>
                        <td style="border: none; text-align: left;">{{ $employee->present_address }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Permanent Address</td>
                        <td style="border: none; text-align: left;">{{ $employee->permanent_address }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Blood Group</td>
                        <td style="border: none; text-align: left;">{{ $employee->blood_group }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Religion</td>
                        <td style="border: none; text-align: left;">{{ $employee->religion }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h4 class="text-center">Employment Details</h4>
    <table class="heading-style">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Employee ID</th>
                <th>Date of Joining</th>
                <th>Department</th>
                <th>Branch</th>
                <th>Supervisor/Manager</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->employementDetails as $key => $employementDetail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $employementDetail->employee_id }}</td>
                    <td>{{ $employementDetail->date_of_joining }}</td>
                    <td>{{ $employementDetail->department->name }}</td>
                    <td>{{ optional($employementDetail->branch)->name }}</td>
                    <td>{{ $employementDetail->supervisorName->full_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="text-center">Educational Information</h4>
    <table class="table dt-table-hover" style="width:100%">
        <thead class="table-light">
            <tr>
                <th scope="col" class="text-uppercase text-muted small fw-medium">SL</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Degree Title</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Institute Name</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Group</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Duration</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Passing Year</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Result</th>
                <th scope="col" class="text-uppercase text-muted small fw-medium">Certificate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->educationDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-muted">{{ $detail->degree_title ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $detail->institute_name ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $detail->group ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $detail->duration ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $detail->passing_year ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $detail->result ?? 'N/A' }}</td>
                    <td class="text-muted">
                        
                        <a href="{{ $detail->certificate_upload }}" target="_blank">View File</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No educational details found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h4 class="text-center">Bank Account Details</h4>
    <table class="heading-style">
        <tr>
            <th>Bank Name</th>
            <th>Account Holder Name</th>
            <th>Account Number</th>
            <th>Branch</th>
            <th>Routing Number</th>
        </tr>
        <tr>
            <td>{{ $employee->bank_name }}</td>
            <td>{{ $employee->account_holder_name }}</td>
            <td>{{ $employee->account_number }}</td>
            <td>{{ $employee->branch }}</td>
            <td>{{ $employee->routing_number }}</td>
        </tr>
    </table>

    <h4 class="text-center">Tax and Legal Information</h4>
    <table class="heading-style">
        <tr>
            <th>ETIN Number</th>
            <th>EPF Number</th>
        </tr>
        <tr>
            <td>{{ $employee->etin_number }}</td>
            <td>{{ $employee->epf_number }}</td>
        </tr>
    </table>

    <h4 class="text-center">Personal Documents</h4>
    <table class="heading-style">
        <tr>
            <th>National ID</th>
            <th>Front Image</th>
            <th>Back Image</th>
        </tr>
        <tr>
            <td>{{ $employee->national_id }}</td>
            <td>
                @if (!empty($employee->front_image))
                    <div class="img-container">
                        <img src="{{ s3FileToBase64($employee->front_image) }}">
                    </div>
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($employee->back_image))
                    <div class="img-container">
                        <img src="{{ s3FileToBase64($employee->back_image) }}">
                    </div>
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="heading-style">
        <tr>
            <th>Signature</th>
        </tr>
        <tr>
            <td>
                @if (!empty($employee->signature))
                    <div class="img-container">
                        <img src="{{ s3FileToBase64($employee->signature) }}">
                    </div>
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>
</body>
