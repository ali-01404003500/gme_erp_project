@extends('layout.app')

@section('title', 'Create KPI Template')
@section('description', 'Designation-wise KPI Template Setup')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Create KPI Template</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.kpis.kpi-templates.index'))
                                <a href="{{ route('hrm.kpis.kpi-templates.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 m-2">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('Create KPI Template') }}</h4>
                <x-error-alart />
            </div>
            <div class="card mb-50">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="mt-40 mb-50 p-30">


                            <form action="{{ route('hrm.kpis.kpi-templates.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Department -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="department_id" class="color-dark fs-14 fw-500">Department <span
                                                    class="text-danger">*</span></label>
                                            <select name="department_id" id="department_id" class="form-control tom-select"
                                                required>
                                                <option value="">-- Select Department --</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <p class="text-danger mb-0">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Designation -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="designation_id" class="color-dark fs-14 fw-500">Designation <span
                                                    class="text-danger">*</span></label>
                                            <select name="designation_id" id="designation_id"
                                                class="form-control tom-select" required>
                                                <option value="">-- Select Designation --</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"
                                                        {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')
                                                <p class="text-danger mb-0">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="status" class="color-dark fs-14 fw-500">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control tom-select" required>
                                                <option value="Active"
                                                    {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="Inactive"
                                                    {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <p class="text-danger mb-0">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Responsibility Search -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="responsibility_search" class="color-dark fs-14 fw-500">Key
                                                Responsibilities <span class="text-danger">*</span></label>
                                            <select id="responsibility_search" class="form-control tom-select">
                                                <option value="">-- Select Responsibility --</option>
                                                @foreach ($responsibilities as $responsibility)
                                                    <option value="{{ $responsibility->id }}"
                                                        data-code="{{ $responsibility->code }}"
                                                        data-description="{{ $responsibility->description }}"
                                                        data-weight="{{ $responsibility->weight }}"
                                                        data-time="{{ $responsibility->time }}"
                                                        data-frequency="{{ $responsibility->frequency }}">
                                                        {{ $responsibility->code }} - {{ $responsibility->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Responsibilities Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="responsibilities-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>SL</th>
                                                <th>Key Areas of Responsibility (KAR)</th>
                                                <th>Weight (Out of 100)</th>
                                                <th>Target Days</th>
                                                <th>Frequency (times of doing the job)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows will appear here -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Submit Button -->
                                <div class="button-group d-flex justify-content-end pt-25">
                                    <button type="submit"
                                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                        Submit
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
    document.addEventListener('DOMContentLoaded', function() {
        const responsibilitySelect = document.getElementById('responsibility_search');
        const tableBody = document.querySelector('#responsibilities-table tbody');

        responsibilitySelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const id = selected.value;
            if (!id) return;

            // Prevent duplicate addition
            if (document.querySelector(`#responsibilities-table tr[data-id="${id}"]`)) {
                toastr.error('This responsibility is already added.');
                responsibilitySelect.tomselect?.clear(); // Clear selection if using TomSelect
                return;
            }

            const code = selected.dataset.code;
            const description = selected.dataset.description;
            const weight = selected.dataset.weight || 0;
            const time = selected.dataset.time || 0;
            const frequency = selected.dataset.frequency || '';

            const rowCount = tableBody.rows.length + 1;

            const newRow = `
                <tr data-id="${id}">
                    <td>${rowCount}</td>
                    <td>${code} - ${description}
                        <input type="hidden" name="responsibilities[${id}][id]" value="${id}">
                    </td>
                    <td>
                        <input type="number" name="responsibilities[${id}][weight]" class="form-control form-control-sm text-center" 
                            value="${weight}" min="0" max="100" required>
                    </td>
                    <td>
                        <input type="number" name="responsibilities[${id}][time]" class="form-control form-control-sm text-center" 
                            value="${time}" min="0" step="0.1" required>
                    </td>
                    <td>
                        <select name="responsibilities[${id}][frequency]" class="form-control form-control-sm text-center" required>
                            <option value="">-- Select Frequency --</option>
                            <option value="Day" ${frequency === 'Day' ? 'selected' : ''}>Day</option>
                            <option value="Month" ${frequency === 'Month' ? 'selected' : ''}>Month</option>
                            <option value="Year" ${frequency === 'Year' ? 'selected' : ''}>Year</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            tableBody.insertAdjacentHTML('beforeend', newRow);

            // ✅ Clear selected value (both for normal select and TomSelect)
            responsibilitySelect.value = '';
            if (responsibilitySelect.tomselect) {
                responsibilitySelect.tomselect.clear();
            }

            // Reset to first placeholder option for better UX
            responsibilitySelect.dispatchEvent(new Event('change'));
        });

        // Remove row handler
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                updateSL();
            }
        });

        // Update serial numbers
        function updateSL() {
            Array.from(tableBody.rows).forEach((row, index) => {
                row.cells[0].innerText = index + 1;
            });
        }
    });
</script>
@endsection

