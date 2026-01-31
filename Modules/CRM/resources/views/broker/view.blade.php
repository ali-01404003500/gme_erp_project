<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $broker->broker_name }}</title>
</head>
<style>
    @page {
        header: page-header;
        footer: page-footer;
        sheet-size: A4;
        margin: 50px;
    }

    table,
    td,
    th {
        font-size: 11px;
        font-family: Arial, sans-serif;
    }

    table {
        border-top: none;
        border-left: none;
        border-right: none;
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        padding-left: 2px !important;
    }

    th.head {
        background-color: rgba(143, 175, 170, 0.35);
        height: 40px;
        font-size: 12px;
    }

    td.loop_td {
        height: 58px;
        font-size: 12px;
    }

    .text-center {
        text-align: center;
        color: rgb(101, 101, 101)
    }

    .heading-style th {
        background-color: rgb(240, 236, 236);
        padding: 7px 0;
        border: 1px solid rgb(240, 236, 236);
        color: rgb(101, 101, 101)
    }

    .heading-style2 th {
        color: rgb(101, 101, 101)
    }

    .body-style td {
        padding: 5px 4px;
        border: 1px solid rgb(240, 236, 236);
        color: rgb(101, 101, 101)
    }

    .basic-style th,
    .basic-style td {
        color: rgb(67, 67, 67)
    }
</style>

<body>
    <table class="table" style="font-size: 10px !important">
        <tr class="heading-style2">
            <th style="border: none; text-align: left;">
                <h3>{{ $broker->broker_name }}</h3>
                <h3>{{ $broker->email }}</h3>
                <h3>{{ optional($broker->division)->name }}</h3>
            </th>
            <td style="border: none; text-align: right;">
                @if ($broker->photograph)
                    <img src="{{ s3FileToBase64($broker->photograph) }}"
                        style="width: 90px; display: block; border: 2px solid gray; padding: 5px;"
                        alt="{{ $broker->photograph }}">
                @else
                    <img src="{{ asset('/assets/img/default-user.jpg') }}" style="width: 90px; display: block;">
                @endif
            </td>

        </tr>

        <td colspan="2">
            <hr style="height: 2px; color: #a0a0a0; background: #a0a0a0; " />
        </td>

        <tr>
            <td>
                <table class="table basic-style" style="font-size: 10px !important">
                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Broker
                            Name
                        </td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $broker->broker_name }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Mobile</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $broker->mobile }}</td>
                    </tr>
                    {{-- <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Alternative Phone</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $broker->alternative_phone }}</td>
                    </tr> --}}


                </table>
            </td>

            <td>
                <table class="table basic-style" style="font-size: 10px !important">


                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">Email
                            Address
                        </td>
                        <td style="text-align: left" width="4%">:</td>
                        <td style="text-align: left; text-align: left" width="46%">{{ $broker->email }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                            Date of Birth</td>
                        <td style="text-align: left" width="4%">:</td>
                        <td style="text-align: left; text-align: left" width="46%">
                            {{ $broker->dob }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                            Gender</td>
                        <td style="text-align: left" width="4%">:</td>
                        <td style="text-align: left; text-align: left" width="46%">
                            {{ $broker->gender }}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

    <h4 class="text-center" style="margin-bottom: 8;">Broker Identity Information</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>NID</th>
                                <th>NID( Front Image)</th>
                                <th>NID (Back Image)</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            <tr>
                                <td>{{ $broker->nid }}</td>
                                <td><img src="{{ s3FileToBase64($broker->front_image)}}" alt="" style="width: 280px;"></td>
                                <td><img src="{{ s3FileToBase64($broker->back_image)}}" alt="" style="width: 280px;"></td>
                            </tr>
                        </tbody>
                    </table>

    <h4 class="text-center" style="margin-bottom: 8;">Broker Location Information</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Division</th>
                <th>District</th>
                <th>Thana</th>
                <th>Present Address</th>
                <th>Permanent Address</th>
            </tr>
        </thead>

        <tbody class="body-style">
            <tr>
                <td>{{ optional($broker->division)->name }}</td>
                <td>{{ optional($broker->district)->name }}</td>
                <td>{{ optional($broker->thana)->name }}</td>
                <td>{{ $broker->present_address }}</td>
                <td>{{ $broker->permanent_address }}</td>

            </tr>
        </tbody>
    </table>

    @php
        $commissionType = $broker->commission_type ?? null;
    @endphp

    @if ($commissionType !== 0) {{-- Only show the table if commission_type is not N/A --}}
        <h4 class="text-center" style="margin-bottom: 8;">Commission Information</h4>
        <table class="table" border="1">
            <thead class="heading-style">
                <tr>
                    @if ($commissionType == 1)
                        <th>Commission Type</th>
                        <th>Percentage Type</th>
                        <th>Percentage %</th>
                    @elseif ($commissionType == 2)
                        <th>Fixed Type</th>
                        <th>Amount</th>
                    @endif
                </tr>
            </thead>
            <tbody class="body-style">
                @foreach ($broker->brokerCommission ?? [] as $value)
                    <tr>
                        @if ($commissionType == 1)
                            <td>Percentage</td>
                            <td>{{ old('percentage_type', optional($value->PercentageType)->name) }}</td>
                            <td>{{ old('percentage', $value->percentage) }}</td>
                        @elseif ($commissionType == 2)
                            <td>
                                @php
                                    $fixedType = old('fixed_type', $value->fixed_type);
                                    $fixedTypeDescriptions = [
                                        1 => 'Invoice Wise',
                                        2 => 'Monthly',
                                        3 => 'Yearly',
                                        4 => 'Festival-Eid',
                                        5 => 'Festival-Durga Puja',
                                    ];
                                @endphp
                                {{ $fixedTypeDescriptions[$fixedType] ?? 'Unknown' }}
                            </td>
                            <td>{{ old('fixed', $value->fixed) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h4 class="text-center" style="margin-bottom: 8;">Customer Attached Information</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Sl</th>
                <th>Customer</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody class="body-style">
            @foreach ($broker->customerAttached ?? [] as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ old('customer_id', optional($value->customer)->company_name) }}</td>
                    <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <h4 class="text-center" style="margin-bottom: 8;">Bank Information</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Sl</th>
                <th>Type</th>
                <th>Bank Name</th>
                <th>Branch Name</th>
                <th>A/C Number</th>
                <th>E-TIN No</th>
                <th>Routing Number</th>
            </tr>
        </thead>

        <tbody class="body-style">
            @foreach ($broker->brokerBank ?? [] as $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        @if ($value->bank_type == 1)
                            Bank
                        @elseif($value->bank_type == 2)
                            Bkash
                        @elseif($value->bank_type == 3)
                            Nagad
                        @elseif($value->bank_type == 4)
                            Rocket
                        @endif
                    </td>
                    <td>{{ old('bank_name', $value->bank_name) }}</td>

                    <td>{{ old('branch_name', $value->branch_name) }}</td>

                    <td>{{ old('account_nos', $value->account_nos) }}</td>

                    <td>{{ old('e_tin_no', $value->e_tin_no) }}</td>

                    <td>{{ old('routing_name', $value->routing_name) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
