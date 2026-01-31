@section('title', 'Vendor Details')
@section('description', 'Vendor Details')
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('vendor view') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center user-member__title">
                        <div class="row">
                            <a href="{{ route('purchase.vendors.show', $vendor->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            @if (hasPermission('purchase.vendors.index'))
                                <a href="{{ route('purchase.vendors.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('vendor view') }}</h3>
                </div>
            </div>
        </div>
        
        <div class="card mb-4 mt-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="me-3">
                            <div class="border border-2 border-black rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @if ($vendor->profile_picture)
                                    <img src="{{ s3FileToBase64($vendor->profile_picture) }}" alt="{{ $vendor->company_name }}" class="rounded-circle" style="width: 72px; height: 72px; object-fit: cover;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h1 class="h3 mb-1 text-dark">{{ $vendor->company_name }}</h1>
                            <p class="text-muted mb-0">
                                @if ($vendor->company_type_id == 1)
                                    Private Limited
                                @elseif ($vendor->company_type_id == 2)
                                    Proprietorship
                                @elseif ($vendor->company_type_id == 3)
                                    Public Limited
                                @elseif ($vendor->company_type_id == 4)
                                    Government Organization
                                @else
                                    Other
                                @endif
                                • {{ $vendor->email }}
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
                <!-- Vendor Details -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h5 mb-0 text-dark">Vendor Details</h2>
                            <a class="btn btn-xs btn-outline-warning"
                                href="{{ route('purchase.vendors.edit', $vendor->id) }}"><i
                                    class="far fa-edit"></i></a>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Company Name</label>
                                <p class="mb-0 text-dark">{{ $vendor->company_name }}</p>
                            </div>
                           
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Company Type</label>
                                <p class="mb-0 text-dark">
                                    @if ($vendor->company_type_id == 1)
                                        Private Limited
                                    @elseif ($vendor->company_type_id == 2)
                                        Proprietorship
                                    @elseif ($vendor->company_type_id == 3)
                                        Public Limited
                                    @elseif ($vendor->company_type_id == 4)
                                        Government Organization
                                    @else
                                        Other
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Account Head</label>
                                <p class="mb-0 text-dark">
                                    @if($vendor->account_head_id == 1)
                                        Cash
                                    @elseif($vendor->account_head_id == 2)
                                        Bank
                                    @elseif($vendor->account_head_id == 3)
                                        Purchase
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Contact Number</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                    {{ $vendor->phone ?? '-' }}
                                </p>
                            </div>
                           
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Email Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    {{ $vendor->email ?? '-' }}
                                </p>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Opening Balance</label>
                                <p class="mb-0 text-dark">{{ number_format($vendor->opening_balance) ?? '0.00' }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-medium">Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $vendor->address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <h3 class="h6 mb-3 text-dark">Owner Information</h3>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Name</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Designation</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_designation ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Email</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Mobile</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-medium">Date of Birth</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_dob ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-9">
                                <label class="form-label text-muted small fw-medium">Address</label>
                                <p class="mb-0 text-dark">{{ $vendor->owner_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Identity Information -->
                <div class="card p-10 mb-10">
                    <div class="card-body">
                        <h3 class="h6 mb-3 text-dark">Vendor Identity Information</h3>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-medium">NID</label>
                                <p class="mb-0 text-dark">{{ $vendor->nid ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Front Image</th>
                                                <th>Visiting Card (Front)</th>
                                                <th>Trade License</th>
                                                <th>Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @if($vendor->front_image)
                                                        <a href="{{ $vendor->front_image }}" target="_blank">
                                                            <img src="{{ $vendor->front_image }}" class="img-fluid" style="max-height: 150px;" alt="Front Image">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">N/A</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($vendor->visiting_card_front)
                                                        <a href="{{ $vendor->visiting_card_front }}" target="_blank">
                                                            <img src="{{ $vendor->visiting_card_front }}" class="img-fluid" style="max-height: 150px;" alt="Visiting Card Front">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">N/A</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($vendor->trade_license)
                                                        <a href="{{ $vendor->trade_license }}" target="_blank">
                                                            <img src="{{ $vendor->trade_license }}" class="img-fluid" style="max-height: 150px;" alt="Trade License">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">N/A</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($vendor->signature)
                                                        <a href="{{ $vendor->signature }}" target="_blank">
                                                            <img src="{{ $vendor->signature }}" class="img-fluid" style="max-height: 150px;" alt="Signature">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">N/A</p>
                                                    @endif
                                                </td>
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
@endsection