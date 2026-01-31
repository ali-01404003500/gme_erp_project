<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>{{ $customer->company_name }}</title>
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
                <h3>{{ $customer->company_name }}</h3>
                <p>{{ $customer->customerType->name }}</p>
                <p>{{ $customer->address }}</p>
                <p>{{ $customer->emailAddress }}</p>
            </td>
            <td style="border: none; text-align: right; width: 30%;">
                @if (!empty($customer->logo))
                    <img src="{{ s3FileToBase64($customer->logo) }}" style="width: 80px; border: 1px solid #ccc;">
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
                        <td style="border: none; text-align: left;">{{ $customer->company_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Company Place</td>
                        <td style="border: none; text-align: left;">{{ optional($customer->area)->area }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Contact Number</td>
                        <td style="border: none; text-align: left;">{{ $customer->phone }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Email Address</td>
                        <td style="border: none; text-align: left;">{{ @$customer->email }}</td>
                    </tr>
                </table>
            </td>
            <td class="info-column">
                <table>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">User Reference</td>
                        <td style="border: none; text-align: left;">{{ @$customer->userRef->full_name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Customer Type</td>
                        <td style="border: none; text-align: left;">{{ @$customer->customerType->name }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: bold;">Address</td>
                        <td style="border: none; text-align: left;">{{ $customer->address }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h4 class="text-center">Owner Information</h4>
    <table class="heading-style">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Mobile</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customer->customerOwner as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ @$value->owner_name }}</td>
                <td>{{ @$value->owner_email }}</td>
                <td>
                    @if ($value->owner_designation == 1) Director
                    @elseif ($value->owner_designation == 2) Managing Director
                    @elseif ($value->owner_designation == 3) Deputy Managing Director
                    @endif
                </td>
                <td>{{ @$value->owner_mobile }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="text-center">Shipping Address</h4>
    <table class="heading-style">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Ship To</th>
                <th>Shipping Address</th>
                <th>Shipping Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customer->customerShippingAddress as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->ship_to }}</td>
                <td>{{ $value->shipping_address }}</td>
                <td>{{ $value->shipping_phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        function isImageFile($path) {
            if (empty($path)) return false;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
        }
    @endphp

    <h4 class="text-center">Customer Identity Information</h4>
    <table class="heading-style">
        <tr>
            <th>National ID</th>
            <th>Front Image</th>
            <th>Back Image</th>
        </tr>
        <tr>
            <td>{{ $customer->nid }}</td>
            <td>
                @if (!empty($customer->front_image))
                    @if (isImageFile($customer->front_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->front_image) }}">
                        </div>
                    @else
                        <a href="{{ $customer->front_image }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($customer->back_image))
                    @if (isImageFile($customer->back_image))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->back_image) }}">
                        </div>
                    @else
                        <a href="{{ $customer->back_image }}" class="file-download" target="_blank">View</a>
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
                @if (!empty($customer->visiting_card_front))
                    @if (isImageFile($customer->visiting_card_front))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->visiting_card_front) }}">
                        </div>
                    @else
                        <a href="{{ $customer->visiting_card_front }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>
                @if (!empty($customer->visiting_card_back))
                    @if (isImageFile($customer->visiting_card_back))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->visiting_card_back) }}">
                        </div>
                    @else
                        <a href="{{ $customer->visiting_card_back }}" class="file-download" target="_blank">View</a>
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
        </tr>
        <tr>
            <td>
                @if (!empty($customer->trade_license))
                    @if (isImageFile($customer->trade_license))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->trade_license) }}">
                        </div>
                    @else
                        <a href="{{ $customer->trade_license }}" class="file-download" target="_blank">View</a>
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
            <th style="width: 70%;">Remarks</th>
        </tr>
        <tr>
            <td>
                @if (!empty($customer->signature))
                    @if (isImageFile($customer->signature))
                        <div class="img-container">
                            <img src="{{ s3FileToBase64($customer->signature) }}">
                        </div>
                    @else
                        <a href="{{ $customer->signature }}" class="file-download" target="_blank">View</a>
                    @endif
                @else
                    <span class="na-text">N/A</span>
                @endif
            </td>
            <td>{{ $customer->remarks }}</td>
        </tr>
    </table>
</body>