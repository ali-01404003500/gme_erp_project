@section('title', 'Product Broker List')
@section('description', 'Product Broker List')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30 mt-50">
                    <h3 class="text-capitalize">{{ trans('broker Details') }}</h3>
                    <div class="row">
                        <a href="{{ route('crm.brokers.show', $broker->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px">PDF</a>
                        <a href="{{ route('crm.brokers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
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
                        font-size: 10px;
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
                                <h3>{{optional( $broker->division)->name }}</h3>
                            </th>
                            <td style="border: none; text-align: right;">
                                @if ($broker->photograph)
                                    <img src="{{ s3FileToBase64($broker->photograph) }}"
                                        style="width: 150px; display: block; border: 2px solid gray; padding: 5px;"
                                        alt="{{ $broker->photograph }}">
                                @else
                                    <img src="{{ asset('/assets/img/default-user.jpg') }}"
                                        style="width: 150px; display: block;">
                                @endif
                            </td>

                        </tr>

                        <td colspan="2">
                            <hr style="height: 4px; color: gray; background: #4d4c4e; " />
                        </td>

                        <table class="outer-table" style="width: 100%; font-size: 10px !important;">
                            <tr>
                                <td style="vertical-align: top; width: 50%;">
                                    <table class="table basic-style" style="width: 100%; font-size: 10px !important;">
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
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">Email
                                            Address
                                        </td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">{{ $broker->email }}
                                        </td>
                                    </tr>


                                </table>
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                <table class="table basic-style" style="width: 100%; font-size: 10px !important;">
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
                                <td><img src="{{ $broker->front_image }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $broker->front_image }}')"></td>
                                <td><img src="{{ $broker->back_image }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $broker->back_image }}')"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="" id="modalImage" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Function to show image modal
                        function showImageModal(imageSrc) {
                            $('#modalImage').attr('src', imageSrc); // Set the src attribute of modal image
                            $('#imageModal').modal('show'); // Show the modal
                        }
                    </script>

                    <h4 class="text-center" style="margin-bottom: 8;">Broker Location Information</h4>
                    <div class="table-responsive">
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
                    </div>

                    @php
                    $commissionType = $broker->commission_type ?? null;
                @endphp
                
                @if ($commissionType !== 0)  {{-- Only show the table if commission_type is not N/A --}}
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
                                                    5 => 'Festival-Durga Puja'
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
                            @foreach ($broker->customerAttached ?? [] as $key =>$value)
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
                            @foreach ($broker->brokerBank ?? [] as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if($value->bank_type == 1)
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
            </div>
        </div>
    @endsection
