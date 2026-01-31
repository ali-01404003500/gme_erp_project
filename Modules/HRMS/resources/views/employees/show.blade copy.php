@section('title', 'Employee Details')
@section('description', 'Employee Details')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30 mt-50">
                    <h3 class="text-capitalize">{{ trans('Employee Details') }}</h3>
                    <div class="row">
                        <a href="{{ route('hrm.employees.show', $employee->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            <a href="{{ route('hrm.employees.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
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
                    <title>{{ $employee->full_name }}</title>
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
                                <h3>{{ $employee->full_name }}</h3>
                                @foreach ($employee->employementDetails as $key => $employementDetail)
                                    <h3>{{ $employementDetail->designation->name }}</h3>
                                    <h3>{{ optional($employementDetail->branch)->name }}</h3>
                                @endforeach
                                <h3>{{ $employee->email_address }}</h3>
                            </th>
                            <td style="border: none; text-align: right;">
                                @if ($employee->photograph)
                                <img src="{{ s3FileToBase64($employee->photograph) }}"
                                style="width: 150px; display: block; border: 2px solid gray; padding: 5px; cursor: pointer;"
                                alt="{{ $employee->photograph }}"
                                id="employeeImage" class="open-image-modal">
                          
                                @else
                                    <img src="{{ asset('/assets/img/default-user.jpg') }}" style="width: 150px; display: block;">
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
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Name
                                        </td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->full_name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Father's Name</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->father_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Mother's Name</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->mother_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">gender
                                        </td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->office_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Office
                                            Phone</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->office_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Personal Mobile</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->personal_mobile }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Alternate Phone</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->alternate_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Email
                                            Address</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->email_address }}
                                        </td>
                                    </tr>

                                </table>
                            </td>

                            <td style="vertical-align: top; width: 50%;">
                                <table class="table basic-style" style="width: 100%; font-size: 10px !important;">

                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Country</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->country }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">City
                                        </td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">{{ $employee->city }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                                            Present Address</td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">
                                            {{ $employee->present_address }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                                            Permanent Address</td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">
                                            {{ $employee->permanent_address }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                                            Blood Group</td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">
                                            {{ $employee->blood_group }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="46%">
                                            Religion</td>
                                        <td style="text-align: left" width="4%">:</td>
                                        <td style="text-align: left; text-align: left" width="46%">
                                            {{ $employee->religion }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                                            Marital Status</td>
                                        <td style="text-align: left" width="2%">:</td>
                                        <td style="text-align: left; text-align: left" width="47%">
                                            {{ $employee->marital_status }}
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>

                        <!-- Personal Details Table -->
                        <h4 class="text-center mb-2">Personal Details</h4>
                        <h4 class="text-center mb-2">Personal Details</h4>
                        <table class="table" border="1">
                            <thead class="heading-style">
                                <tr>
                                    <th>Resume/CV</th>
                                    <th>National Id no.</th>
                                    <th>Front Image</th>
                                    <th>Back Image</th>
                                    <th>Signature</th>
                                    <th>Address Proof</th>
                                    <th>Other Documents</th>
                                </tr>
                            </thead>
                            <tbody class="body-style">
                                <tr>
                                    {{-- Resume/CV --}}
                                    <td>
                                        @php $ext = pathinfo($employee->resume, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp','bmp']))
                                            <img src="{{ $employee->resume }}" alt="" style="width: 100px; cursor: pointer;" class="open-image-modal">
                                        @else
                                            <a href="{{ $employee->resume }}" target="_blank">View File</a>
                                        @endif
                                    </td>
                        
                                    <td>{{ $employee->national_id }}</td>
                        
                                    {{-- Front Image --}}
                                    <td>
                                        @php $ext = pathinfo($employee->front_image, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp','bmp']))
                                            <img src="{{ $employee->front_image }}" alt="" style="width: 100px; cursor: pointer;" class="open-image-modal">
                                        @else
                                            <a href="{{ $employee->front_image }}" target="_blank">View File</a>
                                        @endif
                                    </td>
                        
                                    {{-- Back Image --}}
                                    <td>
                                        @php $ext = pathinfo($employee->back_image, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp','bmp']))
                                            <img src="{{ $employee->back_image }}" alt="" style="width: 100px; cursor: pointer;" class="open-image-modal">
                                        @else
                                            <a href="{{ $employee->back_image }}" target="_blank">View File</a>
                                        @endif
                                    </td>
                        
                                    {{-- Signature --}}
                                    <td>
                                        @php $ext = pathinfo($employee->signature, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp','bmp']))
                                            <img src="{{ $employee->signature }}" alt="" style="width: 100px; cursor: pointer;" class="open-image-modal">
                                        @else
                                            <a href="{{ $employee->signature }}" target="_blank">View File</a>
                                        @endif
                                    </td>
                        
                                    <td>{{ $employee->address_proof }}</td>
                                    <td>{{ $employee->other_documents }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        
                        <!-- Common Image Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <img src="" id="modalImage" class="img-fluid" alt="">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        


                    <h4 class="text-center" style="margin-bottom: 8;">Employment Details</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Sl</th>
                                <th>Employee ID/Number</th>
                                <th>Date of Joining</th>
                                <th>Employment Type</th>
                                <th>Department</th>
                                <th>Branch</th>
                                <th>Supervisor/Manager</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            @foreach ($employee->employementDetails as $key => $employementDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $employementDetail->employee_id }}</td>
                                    <td>{{ $employementDetail->date_of_joining }}</td>
                                    <td>{{ $employementDetail->employment_type_id }}</td>
                                    <td>{{ $employementDetail->department_id }}</td>
                                    <td>{{ optional($employementDetail->branch)->name }}</td>
                                    <td>{{ $employementDetail->supervisor }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Educational Information Table -->
                    <h4 class="text-center" style="margin-bottom: 8;">Educational Information</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Sl</th>
                                <th>Degree Title</th>
                                <th>Institute Name</th>
                                <th>Group</th>
                                <th>Duration</th>
                                <th>Passing Year</th>
                                <th>Result</th>
                                <th>Certificate Upload</th>
                            </tr>
                        </thead>
                        <tbody class="body-style">
                            @foreach ($employee->educationDetails as $key => $educationDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $educationDetail->degree_title }}</td>
                                    <td>{{ $educationDetail->institute_name }}</td>
                                    <td>{{ $educationDetail->group }}</td>
                                    <td>{{ $educationDetail->duration }}</td>
                                    <td>{{ $educationDetail->passing_year }}</td>
                                    <td>{{ $educationDetail->result }}</td>
                                    <td>
                                        <img src="{{ $educationDetail->certificate_upload }}" alt="" style="width: 100px;"
                                            data-toggle="modal" data-target="#imageModal{{ $key }}">
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="imageModal{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel{{ $key }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0 text-center">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                            
                                                <img src="" id="modalImage" class="img-fluid mb-3" alt="" style="display: none;">
                                                
                                                <a href="" id="modalFileLink" target="_blank" style="display: none; font-size: 18px;">View / Download File</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>



                    <h4 class="text-center" style="margin-bottom: 8;">Bank Account Details</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Bank Name</th>
                                <th>Account Holder Name</th>
                                <th>Account Number</th>
                                <th>Branch</th>
                                <th>Routing Number</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            <tr>
                                <td>{{ $employee->bank_name }}</td>
                                <td>{{ $employee->account_holder_name }}</td>
                                <td>{{ $employee->account_number }}</td>
                                <td>{{ $employee->branch }}</td>
                                <td>{{ $employee->routing_number }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4 class="text-center" style="margin-bottom: 8;">Tax and Legal Information</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>ETIN Number</th>
                                <th>EPF Number</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            <tr>
                                <td>{{ $employee->etin_number }}</td>
                                <td>{{ $employee->epf_number }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4 class="text-center" style="margin-bottom: 8;">Benefits and Compensation</h4>
                    <table class="table" border="1">
                        <thead class="heading-style">
                            <tr>
                                <th>Basic Salary</th>
                                <th>Gross Salary</th>
                                <th>Insurance Coverage</th>
                                <th>Leave Entitlement</th>
                                <th>Other Benefits</th>
                            </tr>
                        </thead>

                        <tbody class="body-style">
                            <tr>
                                <td>{{ $employee->basic_salary }}</td>
                                <td>{{ $employee->gross_salary }}</td>
                                <td>{{ $employee->insurance_coverage }}</td>
                                <td>{{ $employee->leave_entitlement }}</td>
                                <td>{{ $employee->other_benefits }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Modal HTML -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                            <img id="popupImage" src="" style="width: 100%; border-radius: 5px;">
                            </div>
                        </div>
                        </div>
                    </div>
                </body>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    <script>
     
     $(document).ready(function () {
    $('.open-image-modal').on('click', function () {
        var imgSrc = $(this).attr('src');

        var fileExtension = imgSrc.split('.').pop().toLowerCase();
        var imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

        if (imageExtensions.includes(fileExtension)) {
            $('#modalImage').attr('src', imgSrc).show();
            $('#modalFileLink').hide();
        } else {
            $('#modalImage').hide();
            $('#modalFileLink').attr('href', imgSrc).show();
        }

        $('#imageModal').modal('show');
    });
});


    </script>

    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        var employmentDetailsRow = $(".employement-details").clone();

        employmentDetailsRow.find("input").each(function() {
            $(this).val("");
        });

        employmentDetailsRow.find("select:selected").each(function() {
            $(this).prop('selected', false);
        });

        employmentDetailsRow.find("input:checked").each(function() {
            $(this).prop('checked', false);
        });

        $(document).on('click', '.add-employement-details', function() {
            const newRow = employmentDetailsRow.clone();
            newRow.find(".tom-select").each(function() {
                new TomSelect(this, {});
            })
            $(".employement-details").after(newRow);
        })

        var educationDetailsRow = $(".education-details").clone();
        educationDetailsRow.find("input").each(function() {
            $(this).val("");
        })

        $(document).on('click', '.add-education-details', function() {
            $(".education-details").after(educationDetailsRow.clone());
        })
    </script>

@endSection
