@section('title', 'Create Vendor Bill Settings')
@section('description', 'Create vendor bill settings for recurring expenses like internet, telephone, utilities, etc.')

@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-vendor-bill-settings') }}</h4>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.create-vendor-bill-settings') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
            </div>

            <!-- Form Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Configure Auto Bill Settings</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('account.vendor-bills.settings.store') }}" method="POST"
                                id="autoBillSettingsForm">
                                @csrf

                                <div class="row g-3">
                                    <!-- Bill Settings Title -->
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Bill Settings Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="e.g. Monthly Internet Bill" value="{{ old('title') }}" required>
                                    </div>

                                    <!-- Bill Amount -->
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Bill Amount (Tk) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                                            placeholder="2000.00" value="{{ old('amount') }}" required>
                                    </div>

                                    <!-- Bill Holder Type -->
                                    <div class="col-md-6">
                                        <label for="holder_type" class="form-label">Bill Holder Type <span
                                                class="text-danger">*</span></label>
                                        <select name="holder_type" id="holder_type" class="form-control tom-select"
                                            required>
                                            <option value="">-- Select Holder Type --</option>
                                            <option value="vendor" {{ old('holder_type') == 'vendor' ? 'selected' : '' }}>
                                                Vendor Account</option>
                                            <option value="employee" {{ old('holder_type') == 'employee' ? 'selected' : '' }}>
                                                Employee Account</option>
                                            <option value="client" {{ old('holder_type') == 'client' ? 'selected' : '' }}>
                                                Client Account</option>
                                            <option value="others" {{ old('holder_type') == 'others' ? 'selected' : '' }}>
                                                Others Account</option>
                                        </select>
                                    </div>

                                    <!-- Bill For (Dynamic: Vendor/Employee/Client/Others) -->
                                    <div class="col-md-6">
                                        <label for="vendorSearch" class="form-label">Bill For <span
                                                class="text-danger">*</span></label>
                                        <select name="related_id" id="vendorSearch" class="form-control tom-select"
                                            required>
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>

                                    <!-- Bill Type -->
                                    <div class="col-md-6">
                                        <label for="bill_type" class="form-label">Bill Type <span
                                                class="text-danger">*</span></label>
                                        <select name="bill_type" id="bill_type" class="form-control tom-select" required>
                                            <option value="Prepaid" {{ old('bill_type') == 'Prepaid' ? 'selected' : '' }}>
                                                Prepaid</option>
                                            <option value="Postpaid" {{ old('bill_type') == 'Postpaid' ? 'selected' : '' }}>
                                                Postpaid</option>
                                        </select>
                                    </div>

                                    <!-- Schedule Type -->
                                    <div class="col-md-6">
                                        <label for="schedule_type" class="form-label">Schedule Type <span
                                                class="text-danger">*</span></label>
                                        <select name="schedule_type" id="schedule_type" class="form-control tom-select"
                                            required>
                                            <option value="Daily" {{ old('schedule_type') == 'Daily' ? 'selected' : '' }}>
                                                Daily</option>
                                            <option value="Monthly" {{ old('schedule_type') == 'Monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                            <option value="Yearly" {{ old('schedule_type') == 'Yearly' ? 'selected' : '' }}>
                                                Yearly</option>
                                            <option value="Yearly" {{ old('schedule_type') == 'Yearly' ? 'selected' : '' }}>
                                                Yearly</option>
                                        </select>
                                    </div>

                                    <!-- Schedule Value -->
                                    <div class="col-md-6">
                                        <label for="schedule_value" class="form-label">Schedule Value <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="schedule_value" id="schedule_value" class="form-control"
                                            value="{{ old('schedule_value', 1) }}" min="1" required>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Schedule Start From <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="start_date" id="start_date" class="form-control flatdate"
                                            value="{{ old('start_date', now()->format('Y-m-d')) }}" required readonly>
                                    </div>

                                    <!-- Setting Status -->
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Setting Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control tom-select" required>
                                            <option value="Running" {{ old('status', 'Running') == 'Running' ? 'selected' : '' }}>Running</option>
                                            <option value="Stop" {{ old('status') == 'Stop' ? 'selected' : '' }}>Stop</option>
                                        </select>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="3"
                                            placeholder="Enter any additional notes...">{{ old('remarks') }}</textarea>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <a href="{{ route('account.vendor-bills.settings.index') }}"
                                        class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Save Setting
                                    </button>
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
    <!-- Ensure jQuery and TomSelect are loaded -->
    {{--
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize Flatpickr for date field
            if (document.querySelector(".flatdate")) {
                flatpickr(".flatdate", {
                    dateFormat: "Y-m-d",
                    allowInput: false,
                    static: true
                });
            }

            // Initialize TomSelect with maxOptions: null to allow searching all items
            const tomSelectElements = document.querySelectorAll('select.tom-select, input.tom-select');

            tomSelectElements.forEach(element => {

                if (!element.tomselect) {

                    new TomSelect(element, {
                        maxOptions: null,
                        allowEmptyOption: true
                    });

                }

            });

            const holderSelect = document.querySelector('#holder_type');
            const relatedSelect = document.querySelector('#vendorSearch');

            const holderTomSelect = holderSelect.tomselect;
            const relatedTomSelect = relatedSelect.tomselect;

            function loadRelatedEntities() {

                const holderType = holderTomSelect.getValue();

                if (!holderType || holderType === 'others') {

                    relatedTomSelect.clear(true);
                    relatedTomSelect.clearOptions();

                    relatedTomSelect.addOption({
                        value: 'others',
                        text: 'Others (Manual Entry)'
                    });

                    relatedTomSelect.setValue('others');

                    return;
                }

                let url;
                let label = 'name';

                switch (holderType) {

                    case 'vendor':
                        url = "{{ route('purchase.get-vendors') }}";
                        break;

                    case 'employee':
                        url = "{{ route('hrm.get-employees') }}";
                        break;

                    case 'client':
                        url = "{{ route('crm.get-customers') }}";
                        break;

                    default:
                        return;
                }

                // Clear previous options
                relatedTomSelect.clear(true);
                relatedTomSelect.clearOptions();

                relatedTomSelect.addOption({
                    value: '',
                    text: `Loading ${holderType}...`
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {

                        console.log(response);

                        const data = response.data || response.vendors || response || [];

                        relatedTomSelect.clear(true);
                        relatedTomSelect.clearOptions();

                        relatedTomSelect.addOption({
                            value: '',
                            text: `-- Select ${holderType.charAt(0).toUpperCase() + holderType.slice(1)} --`
                        });

                        data.forEach(item => {

                            relatedTomSelect.addOption({
                                value: item.id,
                                text: item.name || 'N/A'
                            });

                        });

                        relatedTomSelect.refreshOptions(false);
                    },

                    error: function (xhr) {

                        console.error(xhr);

                        relatedTomSelect.clear(true);
                        relatedTomSelect.clearOptions();

                        relatedTomSelect.addOption({
                            value: '',
                            text: `Error loading ${holderType}s`
                        });

                        relatedTomSelect.refreshOptions(false);

                        alert(`Failed to load ${holderType}s. Please try again.`);
                    }
                });
            }

            // TomSelect event
            holderTomSelect.on('change', function () {
                loadRelatedEntities();
            });


            // Initial load
            if (holderTomSelect.getValue()) {
                loadRelatedEntities();
            }
            // Handle Schedule Type (disable value for Static)
            $('#schedule_type').on('change', function () {
                // Logic for Static removed
                $('#schedule_value').prop('disabled', false);
            }).trigger('change'); // Trigger once on load
        });
    </script>
@endsection