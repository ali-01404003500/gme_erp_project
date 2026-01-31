<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>{{ $vendor->company_name }}</title>
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
        th, td {
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
                <h3>{{ $vendor->company_name }}</h3>
                <p>
                    @if ($vendor->company_type_id == 1)
                        Private Limited
                    @elseif ($vendor->company_type_id == 2)
                        Proprietorship
                    @elseif ($vendor->company_type_id == 3)
                        Public Limited
                    @elseif ($vendor->company_type_id == 4)
                        Government Organization
                    @elseif ($vendor->company_type_id == 5)
                        None
                    @endif
                </p>
                <p>{{ $vendor->address }}</p>
                <p>{{ $vendor->email }}</p>
            </td>
            <td style="border: none; text-align: right; width: 30%;">
                @if ($vendor->profile_picture)
                    <img src="{{ s3FileToBase64($vendor->profile_picture) }}" style="width: 80px; border: 1px solid #ccc;">
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
                        <td style="border: none; text-align: left; font-weight: bold;">Company Name</td>
                        <td style="border: none; text-align: left;">{{ $vendor->company_name }}</td>
                    </tr>
                   
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Contact Number</td>
                        <td style="border: none; text-align: left;">{{ $vendor->phone }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Company Type</td>
                        <td style="border: none; text-align: left;">@if ($vendor->company_type_id == 1)
                        Private Limited
                    @elseif ($vendor->company_type_id == 2)
                        Proprietorship
                    @elseif ($vendor->company_type_id == 3)
                        Public Limited
                    @elseif ($vendor->company_type_id == 4)
                        Government Organization
                    @elseif ($vendor->company_type_id == 5)
                        None</td>
                    @endif
                    </tr>
                    
                </table>
            </td>
            <td class="info-column">
                <table>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Account Head</td>
                        <td style="border: none; text-align: left;">
                            @if($vendor->account_head_id == 1)
                                Cash
                            @elseif($vendor->account_head_id == 2)
                                Bank
                            @elseif($vendor->account_head_id == 3)
                                Purchase
                            @else
                                No Data
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Email Address</td>
                        <td style="border: none; text-align: left;">{{ $vendor->email }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Opening Balance</td>
                        <td style="border: none; text-align: left;">{{ $vendor->opening_balance }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h4 class="text-center">Owner Information</h4>
    <table class="heading-style">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Date of Birth</th>
                <th>Mobile</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $vendor->owner_name }}</td>
                <td>{{ $vendor->owner_email }}</td>
                <td>{{ $vendor->owner_designation }}</td>
                <td>{{ $vendor->owner_dob }}</td>
                <td>{{ $vendor->owner_mobile }}</td>
                <td>{{ $vendor->owner_address }}</td>
            </tr>
        </tbody>
    </table>

    @php
        function isImageFile($path) {
            if (empty($path)) return false;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
        }
    @endphp

    <h4 class="text-center">Vendor Identity Information</h4>
    <table class="heading-style">
        <tr>
            <th>National ID</th>
            <th>Front Image</th>
            <th>Back Image</th>
        </tr>
        <tr>
            <td>{{ $vendor->nid }}</td>
            <td>
                @if (!empty($vendor->front_image))
                    @if (isImageFile($vendor->front_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($vendor->front_image) }}">
                        </div>
                    @else
                        <a href="{{ $vendor->front_image }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($vendor->back_image))
                    @if (isImageFile($vendor->back_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($vendor->back_image) }}">
                        </div>
                    @else
                        <a href="{{ $vendor->back_image }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="heading-style">
        <tr>
            <th>Visiting Card (Front)</th>
                        <th>Trade License</th>

        </tr>
        <tr>
            <td>
                @if (!empty($vendor->visiting_card_front))
                    @if (isImageFile($vendor->visiting_card_front))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($vendor->visiting_card_front) }}">
                        </div>
                    @else
                        <a href="{{ $vendor->visiting_card_front }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($vendor->trade_license))
                    @if (isImageFile($vendor->trade_license))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($vendor->trade_license) }}">
                        </div>
                    @else
                        <a href="{{ $vendor->trade_license }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>

    

    <table class="heading-style">
        <tr>
            <th style="width: 30%;">Signature</th>
        </tr>
        <tr>
            <td>
                @if (!empty($vendor->signature))
                    @if (isImageFile($vendor->signature))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($vendor->signature) }}">
                        </div>
                    @else
                        <a href="{{ $vendor->signature }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>
</body>