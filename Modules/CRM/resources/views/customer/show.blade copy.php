@section('title', 'Customer Update')
@section('description', 'Customer Update')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30 mt-50">
                    <h3 class="text-capitalize">{{ trans('customer Details') }}</h3>
                    <div class="row">
                        <a href="{{ route('crm.customers.show', $customer->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            @if(hasPermission('crm.customers.index'))
                            <a href="{{ route('crm.customers.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                        @endif
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
                    <title>{{ $customer->company_name }}</title>
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
                        <tr class="heading-style2" >
                            <th style="border: none; text-align: left;">
                                <h2>{{ $customer->company_name }}</h2>
                                <h2>{{ @$customer->customerType->name }}</h2>
                                <h2>{{ $customer->address }}</h2>
                                <h2>{{ $customer->email_address }}</h2>
                            </th>
                            <td style="border: none; text-align: right;">
                                @if ($customer->logo)
                                    <img src="{{ s3FileToBase64($customer->logo) }}"
                                        style="width: 150px; height: 150px; display: block; border: 2px solid gray; padding: 5px;"
                                        alt="{{ $customer->logo }}">
                                @else
                                    <img src="{{ asset('/assets/img/default-user.jpg') }}" style="width: 150px; height: 150px; display: block;">
                                @endif
                            </td>
                            {{-- <td style="border: none; text-align: right;">
                                <img src="{{ s3FileToBase64($customer->logo) }}" alt="" style="width: 150px; height: 150px; display: block; border: 2px solid gray; padding: 5px;">
                            </td> --}}

                        </tr>

                        <td colspan="2">
                            <hr style="height: 4px; color: gray; background: #4d4c4e; " />
                        </td>

                        <table class="outer-table" style="width: 100%; font-size: 10px !important;">
                            <tr>
                                <td style="vertical-align: top; width: 50%;">
                                    <table class="table basic-style" style="width: 100%; font-size: 10px !important;">
                                        <tr>
                                            <td style="text-align: left; border: none; font-weight: bold;" width="47%">Company Name</td>
                                            <td style="text-align: left" width="2%">:</td>
                                            <td style="text-align: left;" width="47%">{{ $customer->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: none; font-weight: bold;" width="47%">Company Place</td>
                                            <td style="text-align: left" width="2%">:</td>
                                            <td style="text-align: left;" width="47%">{{ optional($customer->area)->area }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: none; font-weight: bold;" width="47%">Contact Number</td>
                                            <td style="text-align: left" width="2%">:</td>
                                            <td style="text-align: left;" width="47%">{{ $customer->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: none; font-weight: bold;" width="47%">Email Address</td>
                                            <td style="text-align: left" width="2%">:</td>
                                            <td style="text-align: left;" width="47%">{{ @$customer->customerOwner->first()->owner_email }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: none; font-weight: bold;" width="47%">Alternative Contact Number</td>
                                            <td style="text-align: left" width="2%">:</td>
                                            <td style="text-align: left;" width="47%">{{ $customer->contact_for_sms }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="vertical-align: top; width: 50%;">
                                    <table class="table basic-style" style="width: 100%; font-size: 10px !important;">

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">User
                                            Reference</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ optional($customer->employee)->full_name}}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Customer Type</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ @$customer->customerType->name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Customer Reference</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ optional($customer->customer)->company_name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Address</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $customer->address }}</td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                        </table>
                    </table>

                    <h4 class="text-center" style="margin-bottom: 8;">Owner Information</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Designation</th>
                                <th>Date of Birth</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            @foreach ($customer->customerOwner as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->owner_name }}</td>
                                <td>{{ $value->owner_email }}</td>
                                <td>
                                    @if ($value->owner_designation == 1)
                                        Director
                                    @elseif ($value->owner_designation == 2)
                                        Managing Director
                                    @elseif ($value->owner_designation == 3)
                                        Deputy Managing Director
                                    @endif
                                </td>
                                <td>{{ $value->owner_dob }}</td>
                                <td>{{ $value->owner_mobile }}</td>
                            <tr>
                            @endforeach
                        </tbody>
                    </table>


                    <h4 class="text-center" style="margin-bottom: 8;">Customer Identity Information</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                {{-- <th>Sl</th> --}}
                                <th>NID</th>
                                <th>Front Image</th>
                                <th>Back Image</th>
                                <th>Visiting Card (Front)</th>
                                <th>Visiting Card (Back)</th>
                                <th>Trade License</th>
                                <th>Signature</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="body-style">
                            <tr>
                                {{-- <td>{{ $key + 1 }}</td> --}}
                                <td>{{ $customer->nid }}</td>
                                <td><img src="{{ $customer->front_image }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->front_image }}')"></td>
                                <td><img src="{{ $customer->back_image }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->back_image }}')"></td>
                                <td><img src="{{ $customer->visiting_card_front }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->visiting_card_front }}')"></td>
                                <td><img src="{{ $customer->visiting_card_back }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->visiting_card_back }}')"></td>
                                <td><img src="{{ $customer->trade_license }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->trade_license }}')"></td>
                                <td><img src="{{ $customer->signature }}" alt="" style="width: 100px;" onclick="showImageModal('{{ $customer->signature }}')"></td>
                                <td>{{ $customer->remarks }}</td>
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


                    <h4 class="text-center" style="margin-bottom: 8;">Shipping Address</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Sl</th>
                                <th>Ship To</th>
                                <th>Shipping Phone</th>
                                <th>Shipping Address</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            @foreach ($customer->customerShippingAddress as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ old('ship_to', $value->ship_to) }}</td>
                                <td>{{ old('shipping_address1', $value->shipping_address) }}</td>
                                <td>{{ old('shipping_phone', $value->shipping_phone) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                    
                </body>
            </div>
        </div>
    </div>


    {{-- @php
function is_url($path) {
    return filter_var($path, FILTER_VALIDATE_URL);
}
@endphp --}}

    </div>
    </div>
    </div>
@endsection
