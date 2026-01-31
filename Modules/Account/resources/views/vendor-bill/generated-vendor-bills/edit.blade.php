@section('title', 'Manage Vendor Bill')
@section('description', 'Edit amount and update verification status of generated vendor bill')

@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                                class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}">{{ trans('menu.generated-vendor-bills') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.edit-vendor-bill') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                <a href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="las la-arrow-left fs-16"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
            </div>


            <div class="row">

                <!-- Page Title -->
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-vendor-bill') }}</h4>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form
                                action="{{ route('account.vendor-bills.generated-vendor-bills.update', $generatedVendorBill) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Bill ID -->
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Bill ID</strong></label>
                                        <p class="form-control-plaintext">{{ $generatedVendorBill->bill_id }}</p>
                                    </div>

                                    <!-- Bill For -->
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Bill For</strong></label>
                                        <p class="form-control-plaintext">
                                            {{ $generatedVendorBill->billFor?->company_name ?? $generatedVendorBill->billFor?->name ?? '—' }}
                                            <br>
                                            <small
                                                class="text-muted">{{ class_basename($generatedVendorBill->bill_for_type) }}</small>
                                        </p>
                                    </div>

                                    <!-- Bill Date -->
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Bill Date</strong></label>
                                        <p class="form-control-plaintext">
                                            {{ \Carbon\Carbon::parse($generatedVendorBill->bill_date)->format('d M, Y') }}
                                        </p>
                                    </div>

                                    <!-- Source Setting -->
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Source Setting</strong></label>
                                        <p class="form-control-plaintext">
                                            <a href="{{ route('account.vendor-bills.settings.show', $generatedVendorBill->id) }}"
                                                class="text-primary">
                                                {{ @$generatedVendorBill->setting->title }}
                                            </a>
                                        </p>
                                    </div>

                                    <!-- Editable Amount -->
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Bill Amount <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                                            value="{{ old('amount', $generatedVendorBill->amount) }}" required>
                                    </div>

                                    <!-- Current Status Badge -->
                                    <div class="col-md-6">
                                        <label class="form-label">Current Status</label>
                                        <div>
                                            <span class="badge badge-round badge-danger badge-lg">
                                                {{ $generatedVendorBill->status }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- <!-- Status Update -->
                                    <div class="col-12">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select name="status" id="status" class="form-control tom-select"
                                            data-placeholder="Select status action">
                                            <option value="">No Change</option>

                                            @if($generatedVendorBill->status === 'Generated')
                                            <option value="Sent for Verification">Send for Verification</option>
                                            @endif

                                            @if($generatedVendorBill->status === 'Sent for Verification')
                                            <option value="Approved">Approve</option>
                                            <option value="Denied">Deny</option>
                                            @endif

                                            @if($generatedVendorBill->status === 'Denied')
                                            <option value="Sent for Verification">Resend for Verification</option>
                                            @endif
                                        </select>
                                        <small class="text-muted">* Update status with care, it will change the status of
                                            generated vendor bill.</small>
                                    </div> --}}

                                    <!-- Remarks (Optional) -->
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="3"
                                            placeholder="Enter notes if needed">{{ old('remarks', $generatedVendorBill->remarks) }}</textarea>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <input type="hidden" name="status" id="status"
                                        value="{{ old('status', $generatedVendorBill->status) }}">
                                    <a href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}"
                                        class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="save">
                                        Update Bill
                                    </button>
                                    @if (hasPermission('account.vendor-bills.generated-vendor-bills.verify') && request('for') == 'verify')
                                        <button type="submit" class="btn btn-warning" id="save-verify">
                                            Update and Verify
                                        </button>
                                    @endif

                                    @if (hasPermission('account.vendor-bills.generated-vendor-bills.approve') && request('for') == 'approve')
                                        <button type="submit" class="btn btn-success" id="save-approve">
                                            Update and Approve
                                        </button>
                                    @endif

                                    @if (request('for') == 'approve' || request('for') == 'verify')
                                        <button type="submit" class="btn btn-danger" id="save-deny">
                                            Update and Deny
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <!-- No inline JS required — handled globally via layout.app -->

    <script>
        $(document).ready(function () {
            $('#save').click(function () {
                $("#status").val("pending");
            });

            $('#save-verify').click(function () {
                $("#status").val("verified");
            });

            $('#save-approve').click(function () {
                $("#status").val("approved");
            });

            $('#save-deny').click(function () {
                $("#status").val("denied");
            });

        });
    </script>
@endsection