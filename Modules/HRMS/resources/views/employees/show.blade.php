@section('title', 'Employee Details')
@section('description', 'Employee Details')
@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Employee view') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center user-member__title">

                        <div class="row">
                            <a href="{{ route('hrm.employees.show', $employee->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            @if (hasPermission('hrm.employees.index'))
                                <a href="{{ route('hrm.employees.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('Employee view') }}</h3>
                </div>
                <x-error-alart />
            </div>
        </div>

        <!-- Employee Header Section -->
        <div class="card mb-4 p-10 mt-3">
            <div class="card-body">
                <div class="employee-header d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="employee-info d-flex align-items-center">
                        <div class="employee-photo me-3">
                            <div class="border border-2 border-black rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @if ($employee->photograph)
                                    <img src="{{ s3FileToBase64($employee->photograph) }}" alt="{{ $employee->full_name }}" class="rounded-circle" style="width: 72px; height: 72px; object-fit: cover;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="employee-details">
                            <h1 class="h3 mb-1 text-dark">{{ $employee->full_name }}</h1>
                            <p class="text-muted mb-0">
                                <span>{{ optional($employee->employementDetails->first())->designation->name ?? 'N/A' }}</span> |
                                <span>{{ optional($employee->employementDetails->first())->branch->name ?? 'N/A' }}</span> |
                                <span>Joined: {{ $employee->employementDetails->first()->date_of_joining ?? 'N/A' }}</span>
                            </p>
                            <p class="text-muted mb-0">
                                <span>Employee ID: {{ $employee->employementDetails->first()->card_no ?? 'N/A' }}</span> |
                                <span>Email: {{ $employee->email_address ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-12">
                <!-- Employee Details -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h5 mb-0 text-dark">Employee Details</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted cursor-pointer" style="width: 20px; height: 20px;">
                          
                            </svg>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="row">
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Full Name</label>
                                <p class="mb-0 text-dark">{{ $employee->full_name }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Father's Name</label>
                                <p class="mb-0 text-dark">{{ $employee->father_name ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Mother's Name</label>
                                <p class="mb-0 text-dark">{{ $employee->mother_name ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Gender</label>
                                <p class="mb-0 text-dark">{{ $employee->gender ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Office Phone</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="width: 16px; height: 16px;">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    {{ $employee->office_phone ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Personal Mobile</label>
                                <p class="mb-0 text-dark">{{ $employee->personal_mobile ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Alternate Phone</label>
                                <p class="mb-0 text-dark">{{ $employee->alternate_phone ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Email Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="width: 16px; height: 16px;">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    {{ $employee->email_address ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Country</label>
                                <p class="mb-0 text-dark">{{ $employee->country ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">City</label>
                                <p class="mb-0 text-dark">{{ $employee->city ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Present Address</label>
                                <p class="mb-0 text-dark">{{ $employee->present_address ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Permanent Address</label>
                                <p class="mb-0 text-dark">{{ $employee->permanent_address ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Blood Group</label>
                                <p class="mb-0 text-dark">{{ $employee->blood_group ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Religion</label>
                                <p class="mb-0 text-dark">{{ $employee->religion ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Marital Status</label>
                                <p class="mb-0 text-dark">{{ $employee->marital_status ?? '-' }}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Identity Information -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <h3 class="h6 mb-3 text-dark">Employee Identity Information</h3>
                        <div class="row g-3 mt-2">
                            <div class="row">

                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">National ID</label>
                                <p class="mb-0 text-dark">{{ $employee->national_id ?? 'N/A' }}</p>
                            </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Front Image</label>
                                    <p class="mb-0 text-dark"> @php $ext = pathinfo($employee->front_image, PATHINFO_EXTENSION); @endphp
                                       
                                            <a href="{{ $employee->front_image }}" target="_blank">View File</a>
                                        </p>
                                </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Back Image</label>
                                    <p class="mb-0 text-dark">@php $ext = pathinfo($employee->back_image, PATHINFO_EXTENSION); @endphp
                                        
                                            <a href="{{ $employee->back_image }}" target="_blank">View File</a>
                                        </p>
                                </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Resume/CV</label>
                                    <p class="mb-0 text-dark">@php $ext = pathinfo($employee->resume, PATHINFO_EXTENSION); @endphp
                                       
                                            <a href="{{ $employee->resume }}" target="_blank">View File</a>
                                        </p>
                                </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Signature</label>
                                    <p class="mb-0 text-dark">@php $ext = pathinfo($employee->signature, PATHINFO_EXTENSION); @endphp
                                       
                                            <a href="{{ $employee->signature }}" target="_blank">View File</a>
                                </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Address Proof</label>
                                    <p class="mb-0 text-dark">
                                        @php $ext = pathinfo($employee->address_proof, PATHINFO_EXTENSION); @endphp
                                      
                                        <a href="{{ $employee->address_proof }}" target="_blank">View File</a>
                                    </p>
                                </div>
                                <div class="col-3">
                                    <label class="form-label text-muted small fw-medium">Other Documents</label>
                                    <p class="mb-0 text-dark">
                                        @php $ext = pathinfo($employee->other_documents, PATHINFO_EXTENSION); @endphp
                                       
                                            <a href="{{ $employee->other_documents }}" target="_blank">View File</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-12 mb-10">
                <!-- Employment History -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-bottom" id="historyTabs">
                            <li class="nav-item">
                                <button class="nav-link active d-flex align-items-center" data-tab="employment-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"></path>
                                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                        <path d="M12 17.5v-11"></path>
                                    </svg>
                                    Employment History
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="education-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M10 9H8"></path>
                                        <path d="M16 13H8"></path>
                                        <path d="M16 17H8"></path>
                                    </svg>
                                    Educational Information
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="bank-compensation-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 4.5A7.5 7.5 0 0 1 12 15.5a7.5 7.5 0 0 1-7.5 0A7.5 7.5 0 0 1 12 4.5Zm0 0a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13Z"></path>
                                    </svg>
                                    Bank History
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="salary-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Salary
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="loan-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 8c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"></path>
                                    </svg>
                                    Loan
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="tax-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tax and Legal Information
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="employment-tab" class="tab-pane active">
                                <div class="employment-history">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">SL</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Employee ID</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Date of Joining</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Department</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Branch</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Supervisor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employementDetails as $index => $detail)
                                                {{-- @dd($detail->supervisor) --}}
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="fw-medium">{{ $detail->card_no }}</td>
                                                        <td class="text-muted">{{ $detail->date_of_joining ?? 'N/A' }}</td>
                                                        <td class="text-muted">{{ $detail->department->name ?? 'N/A' }}</td>
                                                        <td class="text-muted">{{ optional($detail->branch)->name ?? 'N/A' }}</td>
                                                        <td class="text-muted">{{ @$detail->supervisorName->full_name }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No employment details found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="education-tab" class="tab-pane d-none">
                                <div class="educational-information">
                                    <div class="table-responsive">
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
                                    </div>
                                </div>
                            </div>
                            <div id="bank-compensation-tab" class="tab-pane d-none">
                                <div class="bank-compensation-details">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Bank Name</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Account Holder Name</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Account Number</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Branch</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">Routing Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted">{{ $employee->bank_name ?? 'N/A' }}</td>
                                                    <td class="text-muted">{{ $employee->account_holder_name ?? 'N/A' }}</td>
                                                    <td class="text-muted">{{ $employee->account_number ?? 'N/A' }}</td>
                                                    <td class="text-muted">{{ $employee->bank_branch ?? 'N/A' }}</td>
                                                    <td class="text-muted">{{ $employee->routing_number ?? 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="salary-tab" class="tab-pane d-none">
                                <div class="salary-details">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">Sl</th>
                                                    <th class="text-center">Effect From</th>
                                                    <th class="text-center">Basic</th>
                                                    <th class="text-center">House Rent</th>
                                                    <th class="text-center">Conveyance</th>
                                                    <th class="text-center">Medical</th>
                                                    <th class="text-center">Others</th>
                                                    <th class="text-center">Gross</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employeeSalaries as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-center">{{ $value->effective_date }}</td>
                                                        <td class="text-center">{{ number_format($value->basic) }}</td>
                                                        <td class="text-center">{{ number_format($value->house_rent) }}</td>
                                                        <td class="text-center">{{ number_format($value->conveyance) }}</td>
                                                        <td class="text-center">{{ number_format($value->medical) }}</td>
                                                        <td class="text-center">{{ number_format($value->others) }}</td>
                                                        <td class="text-center">{{ number_format($value->gross) }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-round badge-{{ $value->status == 1 ? 'success' : 'danger' }}">{{ $value->status == 1 ? 'Active' : 'Inactive' }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="{{ route('hrm.employee-salarys.create', ['employee_id' => $value->employee_id, 'salary_id' => $value->id]) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button type="button" data-action="{{ route('hrm.employee-salarys.destroy', $value->id) }}" class="btn btn-danger delete-confirm" title="Delete"><i class="far fa-trash-alt"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted">No salary details found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="loan-tab" class="tab-pane d-none">
                                <div class="loan-details">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Employee</th>
                                                    <th>Amount</th>
                                                    <th>Duration (Months)</th>
                                                    <th>Monthly Reduction</th>
                                                    <th>Remaining Balance</th>
                                                    <th>Start From</th>
                                                    <th>Payment Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($loans as $loan)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $loan->employee->full_name ?? 'N/A' }}</td>
                                                        <td>{{ number_format($loan->amount) }}</td>
                                                        <td>{{ $loan->duration }}</td>
                                                        <td>{{ number_format($loan->monthly_reduction) }}</td>
                                                        <td>{{ number_format($loan->remaining_balance) }}</td>
                                                        <td>{{ $loan->start_month }}</td>
                                                        <td>{{ $loan->payment_date ? \Carbon\Carbon::parse($loan->payment_date)->format('d M Y') : '-' }}</td>
                                                        <td>
                                                            @if ($loan->status == 'pending')
                                                                <span class="badge badge-round badge-warning">Pending</span>
                                                            @elseif ($loan->status == 'approved')
                                                                <span class="badge badge-round badge-success">Approved</span>
                                                            @elseif ($loan->status == 'deny')
                                                                <span class="badge badge-round badge-danger">Denied</span>
                                                            @else
                                                                <span class="badge badge-round badge-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                        
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted">No loan details found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>
                             <div id="tax-tab" class="tab-pane d-none">
                                <div class="tax-details">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>SL</th>
                                                    <th>eTIN Number</th>
                                                    <th>Employee Provident Fund (EPF) Number</th>
                                                    <th>Additional Notes:</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $employee->etin_number }}</td>
                                                    <td>{{ $employee->epf_number }}</td>
                                                    <td>{{ $employee->additional_notes }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#historyTabs button').on('click', function() {
                $('#historyTabs button').removeClass('active');
                $(this).addClass('active');
                $('.tab-pane').addClass('d-none').removeClass('active');
                const target = $(this).data('tab');
                $('#' + target).removeClass('d-none').addClass('active');
            });

            $('.open-image-modal').on('click', function() {
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

        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        var employmentDetailsRow = $(".employement-details").clone();
        employmentDetailsRow.find("input").val("");
        employmentDetailsRow.find("select:selected").prop('selected', false);
        employmentDetailsRow.find("input:checked").prop('checked', false);

        $(document).on('click', '.add-employement-details', function() {
            const newRow = employmentDetailsRow.clone();
            newRow.find(".tom-select").each(function() {
                new TomSelect(this, {});
            });
            $(".employement-details").after(newRow);
        });

        var educationDetailsRow = $(".education-details").clone();
        educationDetailsRow.find("input").val("");
        $(document).on('click', '.add-education-details', function() {
            $(".education-details").after(educationDetailsRow.clone());
        });
    </script>
@endsection

<!-- Modal for Images -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="modalImage" class="img-fluid mb-3" alt="" style="display: none;">
                <a href="" id="modalFileLink" target="_blank" style="display: none; font-size: 18px;">View / Download File</a>
            </div>
        </div>
    </div>
</div>