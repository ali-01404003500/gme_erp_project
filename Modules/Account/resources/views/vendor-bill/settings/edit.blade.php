@section('title', 'Edit Vendor Bill Settings')
@section('description', 'Edit existing vendor bill settings')

@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-vendor-bill-settings') }}</h4>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a
                                            href="{{ route('account.vendor-bills.settings.index') }}">{{ trans('menu.vendor-bill-settings') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.edit-vendor-bill-settings') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Vendor Bill Setting</h5>
                        </div>
                        {{-- @dd($vendorBillSetting) --}}
                        <div class="card-body">
                            <form action="{{ route('account.vendor-bills.settings.update', $vendorBillSetting) }}"
                                method="POST" id="autoBillSettingsForm">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Bill Settings Title -->
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Bill Settings Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title', $vendorBillSetting->title) }}" required>
                                    </div>

                                    <!-- Bill Amount -->
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Bill Amount (Tk) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                                            value="{{ old('amount', $vendorBillSetting->amount) }}" required>
                                    </div>

                                    <!-- Bill Holder Type -->
                                    <div class="col-md-6">
                                        <label for="holder_type" class="form-label">Bill Holder Type <span
                                                class="text-danger">*</span></label>
                                        <select name="holder_type" id="holder_type" class="form-control tom-select"
                                            required>
                                            <option value="">-- Select Holder Type --</option>
                                            <option value="vendor" {{ old('holder_type', $vendorBillSetting->holder_type) == 'vendor' ? 'selected' : '' }}>Vendor
                                                Account</option>
                                            <option value="employee" {{ old('holder_type', $vendorBillSetting->holder_type) == 'employee' ? 'selected' : '' }}>Employee
                                                Account</option>
                                            <option value="client" {{ old('holder_type', $vendorBillSetting->holder_type) == 'client' ? 'selected' : '' }}>Client
                                                Account</option>
                                            <option value="others" {{ old('holder_type', $vendorBillSetting->holder_type) == 'others' ? 'selected' : '' }}>Others
                                                Account</option>
                                        </select>
                                    </div>

                                    <!-- Bill For (Dynamic) -->
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
                                            <option value="Prepaid" {{ old('bill_type', $vendorBillSetting->bill_type) == 'Prepaid' ? 'selected' : '' }}>Prepaid
                                            </option>
                                            <option value="Postpaid" {{ old('bill_type', $vendorBillSetting->bill_type) == 'Postpaid' ? 'selected' : '' }}>Postpaid
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Schedule Type -->
                                    <div class="col-md-6">
                                        <label for="schedule_type" class="form-label">Schedule Type <span
                                                class="text-danger">*</span></label>
                                        <select name="schedule_type" id="schedule_type" class="form-control tom-select"
                                            required>
                                            <option value="Daily" {{ old('schedule_type', $vendorBillSetting->schedule_type) == 'Daily' ? 'selected' : '' }}>Daily
                                            </option>
                                            <option value="Monthly" {{ old('schedule_type', $vendorBillSetting->schedule_type) == 'Monthly' ? 'selected' : '' }}>Monthly
                                            </option>
                                            <option value="Yearly" {{ old('schedule_type', $vendorBillSetting->schedule_type) == 'Yearly' ? 'selected' : '' }}>Yearly
                                            </option>
                                            <option value="Yearly" {{ old('schedule_type', $vendorBillSetting->schedule_type) == 'Yearly' ? 'selected' : '' }}>Yearly
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Schedule Value -->
                                    <div class="col-md-6">
                                        <label for="schedule_value" class="form-label">Schedule Value <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="schedule_value" id="schedule_value" class="form-control"
                                            value="{{ old('schedule_value', $vendorBillSetting->schedule_value) }}" min="1"
                                            required>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Schedule Start From <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="start_date" id="start_date" class="form-control flatdate"
                                            value="{{ old('start_date', $vendorBillSetting->start_date) }}" required>
                                    </div>

                                    <!-- Setting Status -->
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Setting Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control tom-select" required>
                                            <option value="Running" {{ old('status', $vendorBillSetting->status) == 'Running' ? 'selected' : '' }}>Running</option>
                                            <option value="Stop" {{ old('status', $vendorBillSetting->status) == 'Stop' ? 'selected' : '' }}>Stop</option>
                                        </select>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="3"
                                            placeholder="Enter any additional notes...">{{ old('remarks', $vendorBillSetting->remarks) }}</textarea>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <a href="{{ route('account.vendor-bills.settings.index') }}"
                                        class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Setting
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
    <script>
        $(document).ready(function () {
            // Initialize TomSelect with maxOptions: null to allow searching all items
            const tomSelectElements = document.querySelectorAll('.tom-select');
            tomSelectElements.forEach(select => {
                if (!select.tomselect) {
                    new TomSelect(select, {
                        maxOptions: null,
                        allowEmptyOption: true
                    });
                }
            });

            
        })

        // Ensure jQuery is available and wait a bit more for everything to be ready
            $(document).ready(function() {
                const $holderType = $('#holder_type');
                const $relatedSelect = $('#vendorSearch');
                console.log({$holderType, $relatedSelect});
                
                // Load related entities via AJAX
                function loadRelatedEntities() {
                    const holderType = $holderType.val();

                    // Ensure TomSelect is initialized
                    if (!$relatedSelect[0].tomselect) {
                        // If TomSelect is not ready, wait a bit and try again
                        setTimeout(loadRelatedEntities, 100);
                        return;
                    }

                    const $tomSelect = $relatedSelect[0].tomselect;

                    if (!holderType || holderType === 'others') {
                        $tomSelect.clearOptions();
                        $tomSelect.addOption({ value: 'others', text: 'Manual / Others' });
                        $tomSelect.setValue('others');
                        return;
                    }

                    let url, label;

                    switch (holderType) {
                        case 'vendor':
                            url = "{{ route('purchase.get-vendors') }}";
                            label = 'name';
                            break;
                        case 'employee':
                            url = "{{ route('hrm.get-employees') }}";
                            label = 'name';
                            break;
                        case 'client':
                            url = "{{ route('crm.get-customers') }}";
                            label = 'name';
                            break;
                    }

                    // Show loading state
                    $tomSelect.clearOptions();
                    $tomSelect.addOption({ value: '', text: `-- Loading ${holderType}... --` });
                    $tomSelect.setValue('');

                    $.get(url, function (response) {
                        const data = response.data || response.vendors || response || [];

                        $tomSelect.clearOptions();
                        $tomSelect.addOption({ value: '', text: `-- Select ${holderType.charAt(0).toUpperCase() + holderType.slice(1)} --` });

                        data.forEach(item => {
                            $tomSelect.addOption({
                                value: item.id,
                                text: item[label] || 'N/A'
                            });
                        });

                        // Restore the selected value after options are loaded
                        const savedRelatedId = "{{ old('related_id', $vendorBillSetting->bill_for_id) }}";
                        const savedHolderType = "{{ old('holder_type', $vendorBillSetting->holder_type) }}";

                        // Only restore if this is the correct holder type and we have a saved value
                        if (holderType === savedHolderType && savedRelatedId) {
                            // Use a small delay to ensure options are fully loaded before setting the value
                            setTimeout(() => {
                                $tomSelect.setValue(savedRelatedId);
                            }, 100);
                        } else if (holderType === 'others' && savedHolderType === 'others') {
                            // For 'others' type, select the 'others' option
                            setTimeout(() => {
                                $tomSelect.setValue('others');
                            }, 100);
                        }
                    }).fail(function(xhr, status, error) {
                        console.error(`Failed to load ${holderType}s:`, error);
                        $tomSelect.clearOptions();
                        $tomSelect.addOption({ value: '', text: `-- Error loading ${holderType}s --` });
                        $tomSelect.setValue('');
                        alert(`Failed to load ${holderType}s. Please check console for details.`);
                    });
                }

                // Trigger load on change
                // $holderType.on('change', loadRelatedEntities);
                $(document).on('change', '#holder_type', loadRelatedEntities);

                // Load on page load
                // Wait for TomSelect to be fully initialized before loading related entities
                setTimeout(function() {
                    const savedHolderType = "{{ old('holder_type', $vendorBillSetting->holder_type) }}";
                    const savedRelatedId = "{{ old('related_id', $vendorBillSetting->bill_for_id) }}";

                    if (savedHolderType) {
                        // Set the saved holder type in the dropdown
                        $holderType.val(savedHolderType);

                        if (savedHolderType === 'others') {
                            // For 'others' type, set the 'others' option directly
                            const $tomSelect = $relatedSelect[0].tomselect;
                            if ($tomSelect) {
                                $tomSelect.clearOptions();
                                $tomSelect.addOption({ value: 'others', text: 'Manual / Others' });
                                $tomSelect.setValue('others');
                            }
                        } else {
                            // Load the related entities based on the saved holder type
                            loadRelatedEntities();
                        }
                    }
                }, 200); // Slightly longer delay to ensure TomSelect is fully ready

                // Schedule Type: Disable value for Static (Cleanup if needed, but option removed)
                $('#schedule_type').on('change', function () {
                    // Logic for Static removed as option is removed
                    $('#schedule_value').prop('disabled', false);
                }).trigger('change');
            });
    </script>
@endsection