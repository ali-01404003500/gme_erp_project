@section('title', 'Customer Create')
@section('description', 'Customer Create')
@extends('layout.app')

@section('content')
    <style>
        /* Modern Mesh Gradient Background */
        body {
            background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 212, 255, 0.12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(95, 99, 242, 0.08) 0px, transparent 50%),
                #f8fafc !important;
            min-height: 100vh;
        }

        /* Vertical Title Aesthetic */
        .vertical-title {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-weight: 800;
            color: #1e293b;
            border-left: 4px solid #5f63f2;
            padding-right: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 1.1rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
        }

        /* Glassmorphism Card Style */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.7) !important;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            border-radius: 20px !important;
        }

        /* Modern Tabs Design */
        .nav-tabs.vertical-tabs {
            border-bottom: 2px solid rgba(95, 99, 242, 0.1);
            gap: 10px;
            margin-bottom: 30px;
        }

        .nav-tabs.vertical-tabs .nav-item .nav-link {
            border: none;
            background: transparent;
            font-weight: 700;
            color: #64748b;
            padding: 12px 20px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: #5f63f2;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(95, 99, 242, 0.2);
        }

        /* BOLD & MEDIUM-BIG Table Headers */
        .table thead th {
            background-color: rgba(95, 99, 242, 0.08) !important;
            color: #0f172a !important;
            font-weight: 100 !important;
            text-transform: uppercase;
            font-size: 0.9rem !important;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #5f63f2 !important;
            padding: 15px !important;
            vertical-align: middle;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e2e8f0 !important;
        }

        /* Form Controls */
        .form-label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-control,
        .tom-select {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 10px 15px !important;
            background: #fff !important;
        }

        .btn-submit {
            background: linear-gradient(90deg, #5f63f2, #7928ca);
            border: none;
            padding: 12px 40px;
            font-weight: 700;
            border-radius: 12px;
            color: white;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(95, 99, 242, 0.3);
            color: white;
        }
    </style>

    <div class="container-fluid">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex justify-content-between align-items-center flex-wrap">
                    <div class="breadcrumb-action d-flex align-items-start gap-4">
                        <h4 class="vertical-title">{{ trans('Create') }}</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Customer Creation') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn">
                        @if(hasPermission('crm.customers.index'))
                            <a href="{{ route('crm.customers.index') }}" class="btn btn-warning btn-sm px-4 shadow-sm"
                                style="border-radius: 10px;">
                                <i class="fa fa-list me-1"></i> Back to List
                            </a>
                        @endif
                    </div>
                </div>
                <x-error-alart />
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="card mb-50">
            <div class="card-body p-lg-5">
                <form action="{{ route('crm.customers.store', app()->getLocale()) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="dm-tab tab-horizontal">
                        <ul class="nav nav-tabs vertical-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1" role="tab">
                                    <i class="las la-building me-1"></i> Company
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2" role="tab">
                                    <i class="las la-user-tie me-1"></i> Owners
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3" role="tab">
                                    <i class="las la-id-card me-1"></i> Identity
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-v-5-tab" data-bs-toggle="tab" href="#tab-v-5" role="tab">
                                    <i class="las la-shipping-fast me-1"></i> Shipping
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content border-0 p-0">
                            {{-- Tab 1: Company Information --}}
                            <div class="tab-pane fade show active" id="tab-v-1" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Customer ID</label>
                                        <input type="text" class="form-control" name="customer_id"
                                            value="{{ old('customer_id') }}" placeholder="Auto-generated if empty">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="{{ old('company_name') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Company Place <span class="text-danger">*</span></label>
                                        <select class="form-control tom-select" name="company_place_id" required>
                                            <option value="">Select Area</option>
                                            @foreach ($areas as $item)
                                                <option value="{{ $item->id }}" {{ old('company_place_id') == $item->id ? 'selected' : '' }}>{{ $item->area }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">SMS Contact</label>
                                        <input type="text" class="form-control" name="contact_for_sms"
                                            value="{{ old('contact_for_sms') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">User Reference <span class="text-danger">*</span></label>
                                        <select class="form-control tom-select" name="user_ref_id" required>
                                            <option value="">Select Reference</option>
                                            @foreach ($employees as $key => $item)
                                                <option value="{{ $key }}" {{ old('user_ref_id') == $key ? 'selected' : '' }}>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                                        <select class="form-control tom-select" name="customer_type" required>
                                            <option value="">Select Type</option>
                                            @foreach ($customerTypes as $key => $item)
                                                <option value="{{ $key }}" {{ old('customer_type') == $key ? 'selected' : '' }}>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Company Logo</label>
                                        <x-file-uploader name="logo" />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address"
                                            rows="3">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: Owner Information --}}
                            <div class="tab-pane fade" id="tab-v-2" role="tabpanel">
                                <div class="table-responsive table-container">
                                    <table class="table table-bordered mb-0" id="owner_info_table">
                                        <thead>
                                            <tr>
                                                <th>Owner Name</th>
                                                <th>Designation</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>DOB</th>
                                                <th width="50">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="owner_info_body">
                                            <tr class="owner-row">
                                                <td><input type="text" name="owner_name[]" class="form-control"
                                                        placeholder="Name"></td>
                                                <td>
                                                    <select name="owner_designation[]" class="form-control to-select">
                                                        <option value="">Select</option>
                                                        <option value="1">Director</option>
                                                        <option value="2">Managing Director</option>
                                                        <option value="3">Deputy Managing Director</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="owner_mobile[]" class="form-control"
                                                        placeholder="Mobile"></td>
                                                <td><input type="text" name="owner_email[]" class="form-control"
                                                        placeholder="Email"></td>
                                                <td><input type="text" name="owner_dob[]" class="form-control datePicker"
                                                        placeholder="YYYY-MM-DD" autocomplete="off"></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-xs"
                                                        onclick="deleteOwnerRow(this)"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-info btn-sm px-4 shadow-sm" onclick="addOwnerRow()"
                                        style="border-radius: 8px;"><i class="fa fa-plus me-1"></i> Add Owner</button>
                                </div>
                            </div>

                            {{-- Tab 3: Customer Identity --}}
                            <div class="tab-pane fade" id="tab-v-3" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">National ID Number</label>
                                        <input type="text" class="form-control" name="nid" value="{{ old('nid') }}"
                                            placeholder="NID Number">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NID Front Image</label>
                                        <x-file-uploader name="front_image" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NID Back Image</label>
                                        <x-file-uploader name="back_image" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Visiting Card (Front)</label>
                                        <x-file-uploader name="visiting_card_front" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Visiting Card (Back)</label>
                                        <x-file-uploader name="visiting_card_back" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Trade License</label>
                                        <x-file-uploader name="trade_license" />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Internal Remarks</label>
                                        <textarea class="form-control" name="remarks" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 5: Shipping Address --}}
                            <div class="tab-pane fade" id="tab-v-5" role="tabpanel">
                                <div class="table-responsive table-container">
                                    <table class="table table-bordered mb-0" id="shipping_info_table">
                                        <thead>
                                            <tr>
                                                <th>Ship to</th>
                                                <th>Mobile</th>
                                                <th>Full Address</th>
                                                <th width="50">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="shipping_info_body">
                                            <tr class="shipping-item">
                                                <td><input type="text" name="ship_to[]" class="form-control"
                                                        placeholder="Recipient Name"></td>
                                                <td><input type="text" name="shipping_phone[]" class="form-control"
                                                        placeholder="Mobile"></td>
                                                <td><input type="text" name="shipping_address[]" class="form-control"
                                                        placeholder="Address Details"></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-xs"
                                                        onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-info btn-sm px-4 shadow-sm" onclick="addRow()"
                                        style="border-radius: 8px;"><i class="fa fa-plus me-1"></i> Add Address</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 border-top pt-4">
                        <button type="submit" class="btn btn-primary btn-submit shadow-lg">
                            <i class="las la-check-circle me-2"></i> Create Customer Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    @include('utils.geo_locations.script')
    <script>
        $(document).ready(function () {
            // Initialize existing components
            $(".to-select").each(function () {
                new TomSelect(this, { autoclose: true });
            });

            $('.datePicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });

        function addRow() {
            const row = `<tr class="shipping-item">
                <td><input type="text" class="form-control" name="ship_to[]" placeholder="Ship to"></td>
                <td><input type="text" class="form-control" name="shipping_phone[]" placeholder="Mobile number"></td>
                <td><input type="text" class="form-control" name="shipping_address[]" placeholder="Shipping Address"></td>
                <td class="text-center"><button class="btn btn-outline-danger btn-xs" onclick="deleteRow(this)" type="button"><i class="fa fa-trash"></i></button></td>
            </tr>`;
            $('#shipping_info_table tbody').append(row);
        }

        function addOwnerRow() {
            const row = `<tr class="owner-row">
                <td><input type="text" name="owner_name[]" class="form-control" placeholder="Owner Name"></td>
                <td>
                    <select name="owner_designation[]" class="form-control to-select-dynamic">
                        <option value="">Choose</option>
                        <option value="1">Director</option>
                        <option value="2">Managing Director</option>
                        <option value="3">Deputy Managing Director</option>
                    </select>
                </td>
                <td><input type="text" name="owner_mobile[]" class="form-control" placeholder="Mobile"></td>
                <td><input type="text" name="owner_email[]" class="form-control" placeholder="Email"></td>
                <td><input type="text" name="owner_dob[]" class="form-control datePicker" placeholder="YYYY-MM-DD" autocomplete="off"></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-xs" onclick="deleteOwnerRow(this)"><i class="fa fa-times"></i></button></td>
            </tr>`;
            $('#owner_info_body').append(row);

            // Re-initialize for dynamic row
            new TomSelect($('#owner_info_body tr:last .to-select-dynamic')[0], { autoclose: true });
            $('#owner_info_body tr:last .datePicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true });
        }

        function deleteRow(btn) {
            if ($('#shipping_info_table tbody tr').length > 1) $(btn).closest('tr').remove();
        }

        function deleteOwnerRow(btn) {
            if ($('#owner_info_body tr').length > 1) $(btn).closest('tr').remove();
        }
    </script>
@endsection