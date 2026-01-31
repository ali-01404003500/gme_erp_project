@extends('layout.app')

@section('title', 'Edit KPI Assignment')
@section('description', 'Update KPI Template assigned to Employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Edit KPI Assignment</li>
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
            <h4 class="text-capitalize breadcrumb-title">Edit KPI Template Assign to Employee</h4>
            <x-error-alart />
        </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">
                        <form action="{{ route('hrm.kpis.kpi-assignments.update', $kpiTemplateAssignEmployee->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Employee -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Employee Name</label>
                                        <input type="text" class="form-control" value="{{ $kpiTemplateAssignEmployee->employee->full_name }}" readonly>
                                        <input type="hidden" name="employee_id" value="{{ $kpiTemplateAssignEmployee->employee_id }}">
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Department</label>
                                        <input type="text" class="form-control" value="{{ $kpiTemplateAssignEmployee->employee->employementDetail->department->name ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <!-- Designation -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Designation</label>
                                        <input type="text" class="form-control" value="{{ $kpiTemplateAssignEmployee->employee->employementDetail->designation->name ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <!-- Supervisor -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Supervisor Name</label>
                                        <input type="text" class="form-control" value="{{ $kpiTemplateAssignEmployee->employee->employementDetail->supervisorName->full_name ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Executing Duration <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="start_date" class="form-control flatdate"
                                                value="{{ old('start_date', $kpiTemplateAssignEmployee->start_date) }}" required>
                                            <span class="input-group-text">-</span>
                                            <input type="text" name="end_date" class="form-control flatdate"
                                                value="{{ old('end_date', $kpiTemplateAssignEmployee->end_date) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preparation Date -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="color-dark fs-14 fw-500">Date of Preparation <span class="text-danger">*</span></label>
                                        <input type="text" name="preparation_date" class="form-control flatdate"
                                            value="{{ old('preparation_date', $kpiTemplateAssignEmployee->preparation_date) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top my-4"></div>

                            <!-- Responsibility select -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="color-dark fs-14 fw-500">Add Responsibility</label>
                                    <select id="responsibility_search" class="form-control tom-select">
                                        <option value="">-- Select Responsibility --</option>
                                        @foreach ($responsibilities as $r)
                                            <option value="{{ $r->id }}"
                                                data-description="{{ $r->description }}"
                                                data-weight="{{ $r->weight }}"
                                                data-time="{{ $r->time }}"
                                                data-frequency="{{ $r->frequency }}">
                                                {{ $r->code }} - {{ $r->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="responsibilityTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SL</th>
                                            <th>Responsibility</th>
                                            <th>Weight</th>
                                            <th>Target Days</th>
                                            <th>Frequency</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kpiTemplateAssignEmployee->details as $i => $detail)
                                            <tr data-id="{{ $detail->responsibility_entry_id }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    {{ $detail->responsibility->description }}
                                                    <input type="hidden" name="responsibilities[{{ $i }}][responsibility_entry_id]" value="{{ $detail->responsibility_entry_id }}">
                                                </td>
                                                <td><input type="number" name="responsibilities[{{ $i }}][weight]" value="{{ $detail->weight }}" class="form-control form-control-sm"></td>
                                                <td><input type="number" name="responsibilities[{{ $i }}][time]" value="{{ $detail->time }}" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="responsibilities[{{ $i }}][frequency]" class="form-control form-control-sm">
                                                        <option value="Day" {{ $detail->frequency == 'Day' ? 'selected' : '' }}>Day</option>
                                                        <option value="Month" {{ $detail->frequency == 'Month' ? 'selected' : '' }}>Month</option>
                                                        <option value="Year" {{ $detail->frequency == 'Year' ? 'selected' : '' }}>Year</option>
                                                    </select>
                                                </td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-save"></i> Update
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
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#responsibilityTable tbody');
    const select = document.getElementById('responsibility_search');

    function updateSL() {
        Array.from(tableBody.rows).forEach((r, i) => r.cells[0].innerText = i + 1);
    }

    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const id = opt.value;
        if (!id) return;

        // check duplicate
        if (tableBody.querySelector(`tr[data-id="${id}"]`)) {
            toastr.error('Responsibility already exists!');
            if (this.tomselect) this.tomselect.clear();
            return;
        }

        const i = tableBody.rows.length + 1;
        const row = `
            <tr data-id="${id}">
                <td>${i}</td>
                <td>${opt.dataset.description}
                    <input type="hidden" name="responsibilities[${i}][responsibility_entry_id]" value="${id}">
                </td>
                <td><input type="number" name="responsibilities[${i}][weight]" class="form-control form-control-sm" value="${opt.dataset.weight || ''}"></td>
                <td><input type="number" name="responsibilities[${i}][time]" class="form-control form-control-sm" value="${opt.dataset.time || ''}"></td>
                <td>
                    <select name="responsibilities[${i}][frequency]" class="form-control form-control-sm">
                        <option value="Day" ${opt.dataset.frequency === 'Day' ? 'selected' : ''}>Day</option>
                        <option value="Month" ${opt.dataset.frequency === 'Month' ? 'selected' : ''}>Month</option>
                        <option value="Year" ${opt.dataset.frequency === 'Year' ? 'selected' : ''}>Year</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', row);
        if (this.tomselect) this.tomselect.clear();
    });

    tableBody.addEventListener('click', e => {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            updateSL();
        }
    });
});
</script>
@endsection
