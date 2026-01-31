<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>{{ $supplier->company_name }}</title>
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
                <h3>{{ $supplier->company_name }}</h3>
                <p>
                    @if ($supplier->company_type_id == 1)
                        Private Limited
                    @elseif ($supplier->company_type_id == 2)
                        Proprietorship
                    @elseif ($supplier->company_type_id == 3)
                        Public Limited
                    @elseif ($supplier->company_type_id == 4)
                        Government Organization
                    @endif
                </p>
                <p>{{ $supplier->address }}</p>
                <p>{{ $supplier->email }}</p>
            </td>
            <td style="border: none; text-align: right; width: 30%;">
                @if ($supplier->profile_picture)
                    <img src="{{ s3FileToBase64($supplier->profile_picture) }}" style="width: 80px; border: 1px solid #ccc;">
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
                        <td style="border: none; text-align: left;">{{ $supplier->company_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Company Place</td>
                        <td style="border: none; text-align: left;">{{ $supplier->company_place }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Contact Number</td>
                        <td style="border: none; text-align: left;">{{ $supplier->phone }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Email Address</td>
                        <td style="border: none; text-align: left;">{{ $supplier->email }}</td>
                    </tr>
                </table>
            </td>
            <td class="info-column">
                <table>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Customer Reference</td>
                        <td style="border: none; text-align: left;">{{ optional($supplier->customer)->company_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Company Type</td>
                        <td style="border: none; text-align: left;">
                            @if ($supplier->company_type_id == 1)
                                Private Limited
                            @elseif ($supplier->company_type_id == 2)
                                Proprietorship
                            @elseif ($supplier->company_type_id == 3)
                                Public Limited
                            @elseif ($supplier->company_type_id == 4)
                                Government Organization
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Address</td>
                        <td style="border: none; text-align: left;">{{ $supplier->address }}</td>
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
                <th>Mobile</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $supplier->owner_name }}</td>
                <td>{{ $supplier->owner_email }}</td>
                <td>{{ $supplier->owner_designation }}</td>
                <td>{{ $supplier->owner_mobile }}</td>
                <td>{{ $supplier->owner_address }}</td>
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

    <h4 class="text-center">Supplier Identity Information</h4>
    <table class="heading-style">
        <tr>
            <th>National ID</th>
            <th>Front Image</th>
            <th>Back Image</th>
        </tr>
        <tr>
            <td>{{ $supplier->nid }}</td>
            <td>
                @if (!empty($supplier->front_image))
                    @if (isImageFile($supplier->front_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->front_image) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->front_image }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($supplier->back_image))
                    @if (isImageFile($supplier->back_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->back_image) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->back_image }}" class="file-download" target="_blank">View</a>
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
            <th>Visiting Card (Back)</th>
        </tr>
        <tr>
            <td>
                @if (!empty($supplier->visiting_card_front))
                    @if (isImageFile($supplier->visiting_card_front))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->visiting_card_front) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->visiting_card_front }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($supplier->visiting_card_back))
                    @if (isImageFile($supplier->visiting_card_back))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->visiting_card_back) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->visiting_card_back }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="heading-style">
        <tr>
            <th>Trade License</th>
            <th>Signature</th>

        </tr>
        <tr>
            <td>
                @if (!empty($supplier->trade_license))
                    @if (isImageFile($supplier->trade_license))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->trade_license) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->trade_license }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($supplier->signature))
                    @if (isImageFile($supplier->signature))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($supplier->signature) }}">
                        </div>
                    @else
                        <a href="{{ $supplier->signature }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="heading-style">
        <tr>
            <th >Remarks</th>
        </tr>
        <tr>
            
            <td>{{ $supplier->remarks }}</td>
        </tr>
    </table>
</body>