@extends('layout.app')

@section('title', 'Assign KPI Template to Employee')
@section('description', 'Assign KPI Template to Individual Employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Assign KPI Template</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.kpi-assignments.index'))
                            <a href="{{ route('hrm.kpis.kpi-assignments.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 m-2">
            <h4 class="text-capitalize breadcrumb-title">Assign KPI Template to Employee</h4>
            <x-error-alart />
        </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">
                        <form action="{{ route('hrm.kpis.kpi-assignments.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Employee -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="employee_id" class="color-dark fs-14 fw-500">Employee Name <span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-control tom-select" required>
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id') <p class="text-danger mb-0">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="department" class="color-dark fs-14 fw-500">Department</label>
                                        <input type="text" id="department" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Designation -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="designation" class="color-dark fs-14 fw-500">Designation</label>
                                        <input type="text" id="designation" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Supervisor -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="supervisor" class="color-dark fs-14 fw-500">Supervisor Name</label>
                                        <input type="text" id="supervisor" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Joining Date -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="joining_date" class="color-dark fs-14 fw-500">Joining Date</label>
                                        <input type="text" id="joining_date" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Executing Duration -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Executing Duration <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="start_date" id="start_date" class="form-control flatdate" value="{{ old('start_date') }}" required>
                                            <span class="input-group-text">-</span>
                                            <input type="text" name="end_date" id="end_date" class="form-control flatdate" value="{{ old('end_date') }}" required>
                                        </div>
                                        @error('start_date') <p class="text-danger mb-0">{{ $message }}</p> @enderror
                                        @error('end_date') <p class="text-danger mb-0">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Date of Preparation -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="preparation_date" class="color-dark fs-14 fw-500">Date of Preparation <span class="text-danger">*</span></label>
                                        <input type="text" name="preparation_date" id="preparation_date" class="form-control flatdate" value="{{ old('preparation_date', now()->format('Y-m-d')) }}" required>
                                        @error('preparation_date') <p class="text-danger mb-0">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Responsibility Select -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="responsibility_search" class="color-dark fs-14 fw-500">Key Responsibilities <span class="text-danger">*</span></label>
                                        <select id="responsibility_search" class="form-control tom-select">
                                            <option value="">-- Select Responsibility --</option>
                                            @foreach ($responsibilities as $responsibility)
                                                <option value="{{ $responsibility->id }}"
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
                                            <th>Frequency</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Render old inputs (if any) so validation failures keep data --}}
                                        @if(old('responsibilities') && is_array(old('responsibilities')))
                                            @foreach(old('responsibilities') as $idx => $r)
                                                @php
                                                    $entry = $responsibilities->firstWhere('id', $r['responsibility_entry_id']);
                                                    $description = $entry->description ?? ($r['description'] ?? '');
                                                @endphp
                                                <tr data-id="{{ $r['responsibility_entry_id'] }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ $description }}
                                                        <input type="hidden" name="responsibilities[{{ $loop->iteration }}][responsibility_entry_id]" value="{{ $r['responsibility_entry_id'] }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="responsibilities[{{ $loop->iteration }}][weight]" class="form-control form-control-sm" value="{{ $r['weight'] ?? '' }}" min="0" max="100" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.1" name="responsibilities[{{ $loop->iteration }}][time]" class="form-control form-control-sm" value="{{ $r['time'] ?? '' }}" min="0" required>
                                                    </td>
                                                    <td>
                                                        <select name="responsibilities[{{ $loop->iteration }}][frequency]" class="form-control form-control-sm" required>
                                                            <option value="">-- Select Frequency --</option>
                                                            <option value="Day" {{ (isset($r['frequency']) && $r['frequency']=='Day') ? 'selected' : '' }}>Day</option>
                                                            <option value="Month" {{ (isset($r['frequency']) && $r['frequency']=='Month') ? 'selected' : '' }}>Month</option>
                                                            <option value="Year" {{ (isset($r['frequency']) && $r['frequency']=='Year') ? 'selected' : '' }}>Year</option>
                                                        </select>
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>

                            <input type="hidden" name="kpi_template_id" id="kpi_template_id" value="{{ old('kpi_template_id') }}">
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
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#responsibilities-table tbody');
    const responsibilitySelect = document.getElementById('responsibility_search');
    const employeeSelect = document.getElementById('employee_id');
    const kpiTemplateInput = document.getElementById('kpi_template_id');

    // helper: update SL numbers
    function updateSL() {
        Array.from(tableBody.rows).forEach((row, i) => row.cells[0].innerText = i + 1);
    }

    // Add row helper (used for template load and manual add)
    function addRow(resp) {
        // prevent duplicates by responsibility_entry_id
        if (!resp || !resp.responsibility_entry_id) return;
        if (tableBody.querySelector(`tr[data-id="${resp.responsibility_entry_id}"]`)) {
            // already exists, skip
            return;
        }

        const i = tableBody.rows.length + 1;
        const escapedDesc = (resp.description ?? '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const row = `
        <tr data-id="${resp.responsibility_entry_id}">
            <td>${i}</td>
            <td>${escapedDesc}
                <input type="hidden" name="responsibilities[${i}][responsibility_entry_id]" value="${resp.responsibility_entry_id}">
            </td>
            <td><input type="number" step="0.01" name="responsibilities[${i}][weight]" class="form-control form-control-sm" value="${resp.weight ?? ''}" min="0" max="100" required></td>
            <td><input type="number" step="0.1" name="responsibilities[${i}][time]" class="form-control form-control-sm" value="${resp.time ?? ''}" min="0" required></td>
            <td>
                <select name="responsibilities[${i}][frequency]" class="form-control form-control-sm" required>
                    <option value="">-- Select Frequency --</option>
                    <option value="Day" ${resp.frequency === 'Day' ? 'selected' : ''}>Day</option>
                    <option value="Month" ${resp.frequency === 'Month' ? 'selected' : ''}>Month</option>
                    <option value="Year" ${resp.frequency === 'Year' ? 'selected' : ''}>Year</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
        </tr>`;
        tableBody.insertAdjacentHTML('beforeend', row);
    }

    // Remove row handler
    tableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row');
        if (!btn) return;
        btn.closest('tr').remove();
        updateSL();
    });

    // Manual add via responsibility select
    responsibilitySelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const id = selected.value;
        if (!id) return;

        // duplicate check across all rows (preloaded + manual)
        if (tableBody.querySelector(`tr[data-id="${id}"]`)) {
            toastr.error('Responsibility already added!');
            this.value = '';
            if (this.tomselect) this.tomselect.clear();
            return;
        }

        const resp = {
            responsibility_entry_id: id,
            description: selected.dataset.description || '',
            weight: selected.dataset.weight || '',
            time: selected.dataset.time || '',
            frequency: selected.dataset.frequency || ''
        };
        addRow(resp);

        // reset select
        this.value = '';
        if (this.tomselect) this.tomselect.clear();
    });

    // Fetch employee details & (conditionally) load template responsibilities
    employeeSelect.addEventListener('change', function () {
        const employeeId = this.value;
        // always clear table
    tableBody.innerHTML = '';
    kpiTemplateInput.value = '';

        if (!employeeId) {
            // clear fields only, keep existing table rows (user may want to keep)
            document.getElementById('department').value = '';
            document.getElementById('designation').value = '';
            document.getElementById('supervisor').value = '';
            document.getElementById('joining_date').value = '';
            kpiTemplateInput.value = '';
            return;
        }

        fetch('{{ route("hrm.kpis.get-employee-details") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ employee_id: employeeId })
        })
        .then(res => res.json())
        .then(data => {
            // populate basic fields
            document.getElementById('department').value = data.employee?.department_name ?? '';
            document.getElementById('designation').value = data.employee?.designation_name ?? '';
            document.getElementById('supervisor').value = data.employee?.supervisor_name ?? '';
            document.getElementById('joining_date').value = data.employee?.joining_date ?? '';

            // If the table is empty (no old inputs), load template responsibilities.
            // If the table already has rows (user had old input or manually added), DO NOT overwrite them.
            const hasRows = tableBody.rows.length > 0;
            if (!hasRows && data.kpi_template) {
                kpiTemplateInput.value = data.kpi_template.id;
                // load responsibilities (each resp is expected to have responsibility_entry_id, description, weight, time, frequency)
                data.kpi_template.responsibilities.forEach(resp => {
                    // ensure resp has responsibility_entry_id property name used by your API
                    const normalized = {
                        responsibility_entry_id: resp.responsibility_entry_id ?? resp.responsibility_entriy_id ?? resp.id ?? '',
                        description: resp.description ?? resp.responsibilityEntry?.description ?? '',
                        weight: resp.weight ?? '',
                        time: resp.time ?? '',
                        frequency: resp.frequency ?? ''
                    };
                    addRow(normalized);
                });
            } else if (!hasRows) {
                // no rows and no template -> clear kpi_template id
                kpiTemplateInput.value = '';
            }
        })
        .catch(err => {
            console.error('Error fetching employee details:', err);
        });
    });

    // On page load: if employee is preselected (old value) and there are NO old rows, trigger change to pull template and populate fields.
    (function initOnLoad() {
        const oldEmployeeId = @json(old('employee_id'));
        const hasRows = tableBody.rows.length > 0;
        if (oldEmployeeId) {
            // set employee select (option already has selected attribute because of Blade old() in option tag);
            // but we dispatch change to populate department/designation. Only fetch template when no old rows.
            if (!hasRows) {
                // delay a tick so tom-select/DOM are ready
                setTimeout(() => {
                    const ev = new Event('change', { bubbles: true });
                    employeeSelect.dispatchEvent(ev);
                }, 50);
            } else {
                // we still want to fetch employee fields (department, designation, supervisor) without clearing table
                setTimeout(() => {
                    const ev = new Event('change', { bubbles: true });
                    employeeSelect.dispatchEvent(ev);
                }, 50);
            }
        }
    })();

});
</script>
@endsection
