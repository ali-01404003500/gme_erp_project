@section('title', 'Customer Profile')
@section('description', 'Customer Profile')
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
                                    {{ trans('customer view') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center user-member__title">

                        <div class="row">
                            <a href="{{ route('crm.customers.show', $customer->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
                            @if (hasPermission('crm.customers.index'))
                                <a href="{{ route('crm.customers.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('customer view') }}</h3>
                </div>
                <x-error-alart />
            </div>
        </div>
        <div class="card mb-4 mt-3">
            <div class="card-body ">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="me-3">
                            <div class="border border-2 border-black rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @if ($customer->logo)
                                    <img src="{{ s3FileToBase64($customer->logo) }}" alt="{{ $customer->full_name }}" class="rounded-circle" style="width: 72px; height: 72px; object-fit: cover;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                         
                        </div>
                        <div>
                            <h1 class="h3 mb-1 text-dark">{{ $customer->company_name }}</h1>
                            <p class="text-muted mb-0">{{ $customer->customerType->name }} •
                                {{ $customer->area->area ?? 'N/A' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-12">
                <!-- Customer Details -->
                <div class="card mb-4 p-10">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h5 mb-0 text-dark">Customer Details</h2>
                            <a class="btn btn-xs btn-outline-warning"
                                href="{{ route('crm.customers.edit', $customer->id) }}"><i
                                    class="far fa-edit"></i></a>
                        </div>
                        <div class="row g-3">
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Company Name</label>
                                <p class="mb-0 text-dark">{{ $customer->company_name }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">User Reference</label>
                                <p class="mb-0 text-dark">{{ $customer->userRef->full_name ?? '-' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Company Place</label>
                                <p class="mb-0 text-dark">{{ $customer->area->area ?? 'N/A' }}</p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Customer Type</label>
                                <p class="mb-0 text-dark">{{ $customer->customerType->name ?? 'N/A' }}</p>
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
                                    {{ $customer->phone ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Customer Reference</label>
                                <p class="mb-0 text-dark">{{ @$customer->customer->company_name ?? '-' }}</p>
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
                                    {{ $customer->email ?? '-' }}
                                </p>
                            </div>
                            <div class="col-3">
                                <label class="form-label text-muted small fw-medium">Address</label>
                                <p class="mb-0 text-dark d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                        style="width: 16px; height: 16px;">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $customer->address ?? '-' }}
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-medium">Alternative Contact Number</label>
                                <p class="mb-0 text-dark">{{ $customer->contact_for_sms ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="card mb-4 p-10">
                    <div class="card-body ">
                        <h3 class="h6 mb-3 text-dark">Owner Information</h3>
                        @if ($customer->customerOwner->isNotEmpty())
                            @foreach ($customer->customerOwner as $owner)
                                <div class="row g-3">
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Name</label>
                                        <p class="mb-0 text-dark">{{ $owner->owner_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Designation</label>
                                        <p class="mb-0 text-dark">
                                            @if ($owner->owner_designation == 1)
                                                Director
                                            @elseif ($owner->owner_designation == 2)
                                                Managing Director
                                            @elseif ($owner->owner_designation == 3)
                                                Deputy Managing Director
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Email</label>
                                        <p class="mb-0 text-dark">{{ @$owner->owner_email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Mobile</label>
                                        <p class="mb-0 text-dark">{{ @$owner->owner_mobile ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-medium">Date of Birth</label>
                                        <p class="mb-0 text-dark">{{ @$owner->owner_dob ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No owner information available.</p>
                        @endif
                    </div>
                </div>

                <!-- Customer Identity Information -->
                <div class="card p-10">
                    <div class="card-body ">
                        <h3 class="h6 mb-3 text-dark">Customer Identity Information</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-medium">NID</label>
                                <p class="mb-0 text-dark">{{ $customer->nid ?? 'N/A' }}</p>
                            </div>
                            <div class="row g-2 text-muted small">
                                <div class="row">


                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Front Image</label>
                                        <p class="mb-0 text-dark"> 

                                            <a href="{{ $customer->front_image }}" target="_blank">View File</a>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Back Image</label>
                                        <p class="mb-0 text-dark">

                                            <a href="{{ $customer->back_image }}" target="_blank">View File</a>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Visiting Card (Front)</label>
                                        <p class="mb-0 text-dark">
                                            <a href="{{ $customer->visiting_card_front }}" target="_blank">View File</a>
                                        </p>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Visiting Card (Back)</label>
                                        <p class="mb-0 text-dark">

                                            <a href="{{ $customer->visiting_card_back }}" target="_blank">View File</a>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Trade License</label>
                                        <p class="mb-0 text-dark">

                                            <a href="{{ $customer->trade_license }}" target="_blank">View File</a>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Signature</label>
                                        <p class="mb-0 text-dark">
                                            <a href="{{ $customer->signature }}" target="_blank">View File</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-12">
                <!-- Sales Order History -->
                <div class="card mb-4 p-10">
                    <div class="card-body ">
                        <ul class="nav nav-tabs border-bottom" id="historyTabs">
                            <li class="nav-item">
                                <button class="nav-link active d-flex align-items-center" data-tab="sales-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2"
                                        style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z">
                                        </path>
                                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                        <path d="M12 17.5v-11"></path>
                                    </svg>
                                    Sales Order History
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-tab="quotation-tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2"
                                        style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M10 9H8"></path>
                                        <path d="M16 13H8"></path>
                                        <path d="M16 17H8"></path>
                                    </svg>
                                    Quotation History
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="sales-tab" class="tab-pane active">
                                <div class="sales-order-history">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        SL
                                                    </th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Sales
                                                        Order ID</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Invoice Date</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Sales
                                                        Type</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Amount</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Status</th>
                                                    <th scope="col" class="text-uppercase text-muted small fw-medium">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($salesOrders as $index => $sale)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="fw-medium">{{ $sale->sales_order_id }}</td>
                                                        <td class="text-muted">{{ $sale->invoice_date ?? 'N/A' }}</td>
                                                        <td class="text-muted">
                                                            @if ($sale->sales_type == 'free_sales')
                                                                Free Sales
                                                            @else
                                                                {{ $sale->sales_type == 'partial_sales' ? 'Partial Sales' : 'General Sales' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($sale->net_amount) }}</td>
                                                        <td>
                                                            @if ($sale->status == 'pending')
                                                                <span
                                                                    class="badge badge-round badge-warning text-capitalize">{{ $sale->status }}</span>
                                                            @elseif($sale->status == 'approved')
                                                                <span
                                                                    class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                            @elseif($sale->status == 'delivered')
                                                                <span
                                                                    class="badge badge-round badge-info text-capitalize">{{ $sale->status }}</span>
                                                            @elseif($sale->status == 'partial')
                                                                <span
                                                                    class="badge badge-round badge-warning text-capitalize">{{ $sale->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex gap-2">
                                                                <a class="" title="View Sales Order"
                                                                    href="{{ route('sales.sales-orders.show', $sale->id) }}"><i
                                                                        class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No sales orders
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <div id="quotation-tab" class="tab-pane d-none">
                                <div class="quotation-history">
                                    <div class="table-responsive">
                                        <table class="table dt-table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Quotation Date</th>
                                                    <th>Quotation ID</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Address</th>
                                                    <th>Prepared By</th>
                                                    <th>Approved By</th>
                                                    <th>Status</th>
                                                    <th>Expiry Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($quotations as $quotation)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $quotation->created_at->format('Y-m-d') }}</td>
                                                        <td>{{ $quotation->quotation_no }}</td>
                                                        <td>{{ $quotation->customer_name }}</i></td>
                                                        <td>{{ $quotation->address }}</td>
                                                        <td>{{ $quotation->user->name }}</td>
                                                        <td>{{ optional($quotation->approvedBy)->name }}</td>
                                                        <td>
                                                            @if ($quotation->status == 0)
                                                                <span
                                                                    class="badge badge-round badge-warning">Pending</span>
                                                            @elseif ($quotation->status == 1)
                                                                <span class="badge badge-round badge-info">Approved</span>
                                                            @elseif ($quotation->status == 2)
                                                                <span
                                                                    class="badge badge-round badge-success">Ordered</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $quotation->date }}</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm" role="group"
                                                                aria-label="Small button group">



                                                                <a class="btn btn-outline-primary"
                                                                    href="{{ route('sales.quotations.show', $quotation->id) }}"><i
                                                                        class="fas fa-eye"></i></a>
                                                                <a class="btn btn-outline-primary"
                                                                    href="{{ route('sales.quotations.print', $quotation->id) }}"><i
                                                                        class="fas fa-print"></i></a>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-3">
                    <div class="card-body ">
                        <h3 class="h6 mb-3 text-dark">Shipping Address</h3>
                        @if ($customer->customerShippingAddress->isNotEmpty())
                            @foreach ($customer->customerShippingAddress as $address)
                                <div class="row g-3">
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Ship To</label>
                                        <p class="mb-0 text-dark">{{ $address->ship_to ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Shipping Phone</label>
                                        <p class="mb-0 text-dark">{{ $address->shipping_phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label text-muted small fw-medium">Shipping
                                            Address</label>
                                        <p class="mb-0 text-dark">{{ $address->shipping_address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No shipping address available.</p>
                        @endif
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
