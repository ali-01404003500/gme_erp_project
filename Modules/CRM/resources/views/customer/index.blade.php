@section('title', 'Customer List')
@section('description', 'Customer List')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('customer list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('crm.customers.create'))
                            <a href="{{ route('crm.customers.create') }}" class="btn px-20 btn-primary btn-sm mr-5">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                            <button type="button" class="btn btn-xs btn-success btn-sm me-2 ml-5" data-bs-toggle="modal" style="margin-left: 5px;"
                            data-bs-target="#importModal">
                            <i class="las la-file-import fs-16"></i> Import CSV
                        </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('customer list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="row g-3">
                                        <div class="col-md-3 col-sm-6">
                                            <select name="company_name" id="company_name" class="form-control"
                                                data-placeholder="Select Customer">
                                                <option value=""></option> 
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ request('phone') }}" autocomplete="off"
                                                placeholder="Search by Phone">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <input type="text" class="form-control" name="email"
                                                value="{{ request('email') }}" autocomplete="off"
                                                placeholder="Search by Email">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="btn-group btn-corner w-100">
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 d-none d-md-block">
                        <div class="card-body">
                            <div class="d-none d-md-block">
                                <div class="table-responsive">
                                    <style>
                                .condition-table-custom {
                                    width: 100% !important;
                                    margin-bottom: 0 !important;
                                }

                                .condition-table-custom th,
                                .condition-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    padding: 10px 15px !important;
                                    vertical-align: middle !important;
                                    font-size: 0.875rem;
                                    /* Better for laptop density */
                                }

                                .condition-table-custom thead th {
                                    background-color: #f8f9fa;
                                    white-space: nowrap;
                                    font-weight: 700;
                                }
                                .text-wrap-column {
                                    min-width: 150px;
                                    max-width: 250px;
                                    white-space: normal !important;
                                    word-break: break-word;
                                }
                                .table-responsive::-webkit-scrollbar {
                                    height: 8px;
                                }

                                .table-responsive::-webkit-scrollbar-thumb {
                                    background: #ccc;
                                    border-radius: 4px;
                                }
                                .table thead th {
                                background-color: #35526e !important;
                                color: #ffffff !important;
                                font-weight: 600 !important;
                                text-transform: uppercase;
                                font-size: 0.85rem !important;
                                letter-spacing: 0.08em;
                                border-bottom: 2px solid #2a4054 !important;
                                padding: 14px 16px !important;
                                vertical-align: middle;
                                text-align: center;
                            }
                            </style>
                                    <table class="table condition-table-custom dt-table-hover" style="width:100%">
                                        <thead class="table-light text-center">
                                            <tr >
                                                <th width="5%">SL</th>
                                                {{-- <th width="10%">Customer ID</th> --}}
                                                <th width="15%">Customer Name</th>
                                                <th width="20%">Address</th>
                                                {{-- <th width="10%">Company Place</th> --}}
                                                <th width="12%">Phone Number</th>
                                                <th width="10%">Customer Type</th>
                                                <th width="8%">Status</th>
                                                <th width="10%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
                                                <tr>
                                                    <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                                    {{-- <td>
                                                        <a href="{{ route('crm.customers.show', $customer->id) }}">
                                                            {{ $customer->customer_id }}
                                                        </a>
                                                    </td> --}}
                                                    <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                        <a class="fw-bold text-primary fw-500"
                                                            href="{{ route('crm.customers.show', $customer->id) }}">
                                                            {{ $customer->company_name }}
                                                        </a>
                                                    </td>
                                                    <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                        {{ $customer->address }}
                                                    </td>
                                                    {{-- <td>{{ $customer->area?->area }}</td> --}}
                                                    <td>{{ $customer->phone }}</td>
                                                    <td>{{ optional($customer->customerType)->name }}</td>
                                                    <td>
                                                        @if ($customer->status == 1)
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif ($customer->status == 2)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Deny</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            @if(hasPermission('crm.customers.approve') && $customer->status == 1)
                                                                <a href="{{ route('crm.customers.approve', $customer->id) }}"
                                                                    class="btn btn-outline-success approval-confirm-customer"
                                                                    data-action="{{ route('crm.customers.approve', $customer->id) }}"
                                                                    data-confirm-title="Approve Customer?"
                                                                    data-confirm-message="Are you sure you want to approve this customer?"
                                                                    data-confirm-icon="success"
                                                                    data-confirm-text="Yes, Approve it!">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="{{ route('crm.customers.deny', $customer->id) }}"
                                                                    class="btn btn-outline-danger reject-confirm-customer"
                                                                    data-action="{{ route('crm.customers.deny', $customer->id) }}"
                                                                    data-confirm-title="Reject Customer?"
                                                                    data-confirm-message="Are you sure you want to reject this customer?"
                                                                    data-confirm-icon="warning"
                                                                    data-confirm-text="Yes, Reject it!">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            @endif
                                                            @if (hasPermission('crm.customers.update'))
                                                                <a class="btn btn-outline-warning"
                                                                    href="{{ route('crm.customers.edit', $customer->id) }}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                            @endif
                                                            @if (hasPermission('crm.customers.destroy'))
                                                                <button type="button"
                                                                    data-action="{{ route('crm.customers.destroy', $customer->id) }}"
                                                                    class="btn btn-outline-danger delete-confirm">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </button>
                                                            @endif
                                                            @if (hasPermission('crm.customers.show'))
                                                                <a class="btn btn-outline-primary"
                                                                    href="{{ route('crm.customers.show', $customer->id) }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endif
                                                            @if (hasPermission('crm.customers.settings'))
                                                                <a class="btn btn-outline-info"
                                                                    href="{{ route('crm.customers.settings', $customer->id) }}">
                                                                    <i class="fas fa-cog"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile: Card/Grid View using Row/Col -->
                            <div class="d-md-none">
                                <div class="row">
                                    @foreach ($customers as $customer)
                                        <div class="col-12 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">SL:</div>
                                                        <div class="col-8">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Customer ID:</div>
                                                        <div class="col-8">
                                                            <a href="{{ route('crm.customers.show', $customer->id) }}">{{ $customer->customer_id }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Name:</div>
                                                        <div class="col-8">
                                                            <a href="{{ route('crm.customers.show', $customer->id) }}">{{ $customer->company_name }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Address:</div>
                                                        <div class="col-8 text-break">{{ $customer->address }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Place:</div>
                                                        <div class="col-8">{{ $customer->area?->area }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Phone:</div>
                                                        <div class="col-8">{{ $customer->phone }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Type:</div>
                                                        <div class="col-8">{{ optional($customer->customerType)->name }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 fw-bold">Status:</div>
                                                        <div class="col-8">
                                                            @if ($customer->status == 1)
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif ($customer->status == 2)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-danger">Deny</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="btn-group btn-group-sm w-100">
                                                                @if(hasPermission('crm.customers.approve') && $customer->status == 1)
                                                                    <a href="{{ route('crm.customers.approve', $customer->id) }}"
                                                                        class="btn btn-outline-success approval-confirm-customer"
                                                                        data-action="{{ route('crm.customers.approve', $customer->id) }}">
                                                                        <i class="fas fa-check"></i> Approve
                                                                    </a>
                                                                    <a href="{{ route('crm.customers.deny', $customer->id) }}"
                                                                        class="btn btn-outline-danger reject-confirm-customer"
                                                                        data-action="{{ route('crm.customers.deny', $customer->id) }}">
                                                                        <i class="fas fa-times"></i> Deny
                                                                    </a>
                                                                @endif
                                                                @if (hasPermission('crm.customers.update'))
                                                                    <a class="btn btn-outline-warning" href="{{ route('crm.customers.edit', $customer->id) }}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                @endif
                                                                @if (hasPermission('crm.customers.destroy'))
                                                                    <button type="button" data-action="{{ route('crm.customers.destroy', $customer->id) }}"
                                                                        class="btn btn-outline-danger delete-confirm">
                                                                        <i class="far fa-trash-alt"></i>
                                                                    </button>
                                                                @endif
                                                                @if (hasPermission('crm.customers.show'))
                                                                    <a class="btn btn-outline-primary" href="{{ route('crm.customers.show', $customer->id) }}">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                                @if (hasPermission('crm.customers.settings'))
                                                                    <a class="btn btn-outline-info" href="{{ route('crm.customers.settings', $customer->id) }}">
                                                                        <i class="fas fa-cog"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Pagination row -->
                        <!-- Pagination row -->
                                <div class="row mt-4">
                                    <div class="col-12 d-flex justify-content-end">
                                        @include('utils.table_paginate', ['data' => $customers])
                                    </div>
                                </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade inputForm-modal" id="importModal" tabindex="-1" role="dialog"
        aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
    
                <div class="modal-header" id="importModalLabel">
                    <h5 class="modal-title">Import from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ route('crm.customers-insert') }}" method="post" id="importFrom"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="csv_file" class="col-sm-12 col-form-label">CSV File</label>
                            <div class="col-sm-12">
                                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <a href="{{ route('crm.customers-download') }}" class="btn btn-info">Download Sample CSV</a>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('page_scripts')
<script>
    function approvalConfirm(e) {
        e.preventDefault();
        e.stopPropagation();

        const el = $(this);
        const url = el.data("action");
        const confirmTitle = el.data("confirm-title") || "Are you sure?";
        const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
        const confirmIcon = el.data("confirm-icon") || "success";
        const confirmText = el.data("confirm-text") || "Yes, Approve it!";

        Swal.fire({
            title: confirmTitle,
            text: confirmMessage,
            icon: confirmIcon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: confirmText
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function rejectConfirm(e) {
        e.preventDefault();
        e.stopPropagation();

        const el = $(this);
        const url = el.data("action");
        const confirmTitle = el.data("confirm-title") || "Are you sure?";
        const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
        const confirmIcon = el.data("confirm-icon") || "warning";
        const confirmText = el.data("confirm-text") || "Yes, Reject it!";

        Swal.fire({
            title: confirmTitle,
            text: confirmMessage,
            icon: confirmIcon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: confirmText
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    $(document).ready(function () {
        $(".approval-confirm-customer").on("click", approvalConfirm);
        $(".reject-confirm-customer").on("click", rejectConfirm);
      
        const companySelect = new TomSelect("#company_name", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {
                if (!query.length || query.length < 2) return callback();
                $.ajax({
                    url: "{{ route('crm.autocomplete.customers') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        companySelect.clearOptions();
                        callback(res.map(item => ({ id: item.text, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        });

        @if(request('company_name'))
            companySelect.addOption({
                id: "{{ request('company_name') }}",
                text: "{{ request('company_name') }}"
            });
            companySelect.setValue("{{ request('company_name') }}");
        @endif
    });
</script>
@endsection