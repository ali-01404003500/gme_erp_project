@section('title', 'Supplier Profile')
@section('description', 'Supplier Profile')
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
                                    {{ trans('supplier view') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center user-member__title">
                        <div class="row">
                            <a href="{{ route('purchase.suppliers.show', $supplier->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            @if (hasPermission('purchase.suppliers.index'))
                                <a href="{{ route('purchase.suppliers.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('supplier view') }}</h3>
                </div>
            </div>
        </div>
        <div class="card mb-4 mt-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="me-3">
                            <div class="border border-2 border-black rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                @if ($supplier->profile_picture)
                                    <img src="{{ s3FileToBase64($supplier->profile_picture) }}"
                                        alt="{{ $supplier->company_name }}" class="rounded-circle"
                                        style="width: 72px; height: 72px; object-fit: cover;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h1 class="h3 mb-1 text-dark">{{ $supplier->company_name }}</h1>
                            <p class="text-muted mb-0">{{ $supplier->email }} • {{ $supplier->company_place }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-12">
                <!-- Supplier Details -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h5 mb-0 text-dark">Supplier Details</h2>
                            <a class="btn btn-xs btn-outline-warning"
                                href="{{ route('purchase.suppliers.edit', $supplier->id) }}"><i class="far fa-edit"></i></a>
                        </div>
                        <div class="row g-3">
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Company Name</label>
                                <p class="mb-0 text-dark">{{ $supplier->company_name }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Contact For SMS</label>
                                <p class="mb-0 text-dark">{{ $supplier->contact_for_sms ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Company Place</label>
                                <p class="mb-0 text-dark">{{ $supplier->company_place ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Customer Reference</label>
                                <p class="mb-0 text-dark">{{ @$supplier->customer->company_name ?? '-' }}</p>
                            </div>
                            <div class="col-3">
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
                                    {{ $supplier->phone ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">TNT/Land Number</label>
                                <p class="mb-0 text-dark">{{ $supplier->tnt_number ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Email Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    {{ $supplier->email ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Country</label>
                                <p class="mb-0 text-dark">{{ $supplier->country ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Opening Balance</label>
                                <p class="mb-0 text-dark">{{ $supplier->opening_balance ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-medium">Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $supplier->address ?? '-' }}
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
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Name</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Designation</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_designation ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Email</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Mobile</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Date of Birth</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_dob ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Address</label>
                                <p class="mb-0 text-dark">{{ $supplier->owner_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Identity Information -->
                <div class="card p-10">
                    <div class="card-body">
                        <h3 class="h6 mb-3 text-dark">Supplier Identity Information</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-medium">NID</label>
                                <p class="mb-0 text-dark">{{ $supplier->nid ?? 'N/A' }}</p>
                            </div>
                            <div class="row g-2 text-muted small">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Front Image</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->front_image)
                                                <a href="{{ $supplier->front_image }}" target="_blank">View File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Back Image</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->back_image)
                                                <a href="{{ $supplier->back_image }}" target="_blank">View File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Visiting Card (Front)</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->visiting_card_front)
                                                <a href="{{ $supplier->visiting_card_front }}" target="_blank">View
                                                    File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Visiting Card (Back)</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->visiting_card_back)
                                                <a href="{{ $supplier->visiting_card_back }}" target="_blank">View
                                                    File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Trade License</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->trade_license)
                                                <a href="{{ $supplier->trade_license }}" target="_blank">View File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Signature</label>
                                        <p class="mb-0 text-dark">
                                            @if ($supplier->signature)
                                                <a href="{{ $supplier->signature }}" target="_blank">View File</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Remarks</label>
                                        <p class="mb-0 text-dark">{{ $supplier->remarks ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-12">
                <!-- Purchase Order History -->
                <div class="card mb-4 p-10">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-bottom" id="historyTabs">
                            <li class="nav-item">
                                <button class="nav-link active d-flex align-items-center" data-tab="purchase-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2"
                                        style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z">
                                        </path>
                                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                        <path d="M12 17.5v-11"></path>
                                    </svg>
                                    Purchase Order History
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="purchase-tab" class="tab-pane active">
                                <div class="purchase-order-history">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        SL
                                                    </th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Purchase Order ID</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Order Date</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Amount</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Status</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($purchaseOrders as $index => $order)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="fw-medium">{{ $order->requisition_no }}</td>
                                                        <td class="text-muted">{{ $order->invoice_date ?? 'N/A' }}</td>
                                                        <td>{{ number_format($order->net_amount) }}</td>
                                                        <td>
                                                            @if ($order->status == 0)
                                                                <span
                                                                    class="badge badge-round badge-warning">Pending</span>
                                                            @elseif($order->status == 1)
                                                                <span
                                                                    class="badge badge-round badge-success">Approved</span>
                                                            @elseif($order->status == 4)
                                                                <span
                                                                    class="badge badge-round badge-primary">Received</span>
                                                            @elseif($order->status == 2)
                                                                <span
                                                                    class="badge badge-round badge-danger">Rejected</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex gap-2">
                                                                <a class="" title="View Purchase Order"
                                                                    href="{{ route('purchase.requisitions.show', $order->id) }}"><i
                                                                        class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No purchase
                                                            orders
                                                            found.</td>
                                                    </tr>
                                                @endforelse
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
                // Remove active from all buttons
                $('#historyTabs button').removeClass('active');

                // Add active to clicked button
                $(this).addClass('active');

                // Hide all tab contents
                $('.tab-pane').addClass('d-none').removeClass('active');

                // Show selected tab
                const target = $(this).data('tab');
                $('#' + target).removeClass('d-none').addClass('active');
            });
        });
    </script>
@endsection
