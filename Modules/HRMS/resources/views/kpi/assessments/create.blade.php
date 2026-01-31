@extends('layout.app')

@section('title', 'Create KPI Assessment')
@section('description', 'Create a new KPI assessment for an employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Create KPI Assessment</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.assessments.index'))
                        <a href="{{ route('hrm.kpis.assessments.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                            <i class="fa fa-list"></i> List
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">
                        <x-error-alart />

                        <form action="{{ route('hrm.kpis.assessments.store') }}" method="POST">
                            @csrf

                            {{-- Employee --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                      <div class="form-group mb-3">
                                        <label class="fw-500">Employee <span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-control tom-select" required>
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                 {{ @$employee->employementDetail->card_no }}: {{ $employee->full_name }} ({{ @$employee->employementDetail->designation->name }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                   <div class="input-daterange mb-3">
                                    <label class="fw-500">Assessment Date <span class="text-danger">*</span></label>
                                        <input type="text" class="flatdaterange form-control" value="{{ request('from_to_date') }}" name="from_to_date">

                                        @error('from_to_date')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                          
                           
                            </div>
                            {{-- Branch/Department/Designation --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Branch</label>
                                    <input type="text" id="branch" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Department</label>
                                    <input type="text" id="department" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Designation</label>
                                    <input type="text" id="designation" class="form-control" readonly>
                                </div>
                            </div>

                            {{-- KPI Table --}}
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered" id="kpi-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Job Description</th>
                                            <th>Weight</th>
                                            <th>Mark</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Dynamically filled --}}
                                    </tbody>
                                     <tfoot>
                                        <tr class="bg-soft">
                                            <th class="text-end">Total</th>
                                            <th id="total-weight">0</th>
                                            <th id="total-mark">0</th>
                                            <th></th>

                                        </tr>
                                            <tr class="fw-bold">
                                                <td colspan="2" class="text-end">Overall Score (%):</td>
                                                <td><span id="overall-score">0.00%</span></td>
                                            </tr>
<input type="hidden" name="total_mark" id="input-total-mark" value="0">
<input type="hidden" name="total_weight" id="input-total-weight" value="0">
<input type="hidden" name="overall_score" id="input-overall-score" value="0">

                                    </tfoot>
                                </table>
                            </div>

                            {{-- Submission Buttons --}}
                            <div class="button-group d-flex justify-content-end">
                                <button type="submit" name="status" value="draft"
                                    class="btn btn-secondary btn-squared radius-md shadow2 btn-sm me-2">Save as
                                    Draft</button>
                                <button type="submit" name="status" value="submitted"
                                    class="btn btn-primary btn-squared radius-md shadow2 btn-sm">Submit for
                                    Approval</button>
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
    const employees = @json($employees->load('employementDetail.branch', 'employementDetail.department', 'employementDetail.designation'));
    const designationKpis = @json($designationKpis);
    
    // Store old input data from Laravel
    const oldInput = @json(old('kpis', []));

    $('#employee_id').on('change', function () {
        const selectedId = $(this).val();
        const employee = employees.find(emp => emp.id == selectedId);

        if (employee && employee.employement_detail) {
            const details = employee.employement_detail;

            $('#branch').val(details.branch?.name || '');
            $('#department').val(details.department?.name || '');
            $('#designation').val(details.designation?.name || '');

            const designationId = details.designation_id;
            const kpis = designationKpis[designationId] || [];

            const tbody = $('#kpi-table tbody');
            tbody.empty();

            if (kpis.length) {
                kpis.forEach((kpi, index) => {
                    // Retrieve old values for this KPI, if available
                    const oldKpi = oldInput[index] || {};
                    const oldMark = oldKpi.mark || '';
                    const oldRemarks = oldKpi.remarks || '';

                    const row = `
                    <tr>
                        <td>
                            <input type="hidden" name="kpis[${index}][id]" value="${kpi.id}">
                            <input type="hidden" name="kpis[${index}][description]" value="${kpi.description}">
                            ${kpi.description}
                        </td>
                        <td>
                            <input type="number" class="form-control kpi-weight" name="kpis[${index}][weight]" value="${kpi.weight}" readonly>
                        </td>
                        <td>
                            <input type="number" name="kpis[${index}][mark]" class="form-control kpi-mark" placeholder="Mark" min="0" max="${kpi.weight}" value="${oldMark}" required>
                        </td>
                        <td>
                            <input type="text" name="kpis[${index}][remarks]" class="form-control" placeholder="Remarks" value="${oldRemarks}">
                        </td>
                    </tr>`;
                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="4" class="text-center text-danger">No KPIs found for this designation</td></tr>');
            }

            calculateTotal(); // Recalculate totals
        } else {
            $('#branch, #department, #designation').val('');
            $('#kpi-table tbody').empty();
        }
    });

    // Trigger change event on page load to populate the form if old employee_id exists
    @if (old('employee_id'))
        $('#employee_id').val('{{ old('employee_id') }}').trigger('change');
    @endif

    $(document).on('input', '.kpi-mark', function () {
        const mark = parseFloat($(this).val()) || 0;
        const max = parseFloat($(this).attr('max')) || 0;

        if (mark > max) {
            alert('Mark cannot exceed KPI weight.');
            $(this).val(max);
        }

        calculateTotal();
    });

    function calculateTotal() {
        let totalMark = 0;
        let totalWeight = 0;

        $('.kpi-mark').each(function () {
            totalMark += parseFloat($(this).val()) || 0;
        });

        $('.kpi-weight').each(function () {
            totalWeight += parseFloat($(this).val()) || 0;
        });

        $('#total-mark').text(totalMark.toFixed());
        $('#total-weight').text(totalWeight.toFixed());

        const overallScore = totalWeight > 0 ? (totalMark / totalWeight) * 100 : 0;
        $('#overall-score').text(overallScore.toFixed() + '%');

        // Set hidden input values
        $('#input-total-mark').val(totalMark.toFixed());
        $('#input-total-weight').val(totalWeight.toFixed());
        $('#input-overall-score').val(overallScore.toFixed());
    }
</script>
@endsection

