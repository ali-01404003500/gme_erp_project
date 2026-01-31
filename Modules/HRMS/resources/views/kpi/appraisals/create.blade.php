@extends('layout.app')

@section('title', 'Create Appraisal')
@section('description', 'Initiate an appraisal for eligible employees')

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
                                <li class="breadcrumb-item active" aria-current="page">Create Appraisal</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.appraisals.index'))
                        <a href="{{ route('hrm.kpis.appraisals.index') }}"
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

                        <form action="{{ route('hrm.kpis.appraisals.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-500">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control tom-select" required>
                                        <option value="">-- Select Eligible Employee --</option>
                                        @foreach ($eligibleEmployees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ @$employee->employementDetail->card_no }}: {{ $employee->full_name }}
                                                ({{ @$employee->employementDetail->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div id="assessment-history" style="display: none;">
                                        <label class="fw-500">Recent Assessments</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Period</th>
                                                        <th>Total Weight</th>
                                                        <th>Total Mark</th>
                                                        <th>Overall Score (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="assessment-rows"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 mt-3">
                                    <label class="fw-500">Last 6 Assessments Avg. Score(%)</label>
                                    <input type="text" id="avg_score" class="form-control" value="{{ old('avg_score') }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3 mt-3">
                                    <label>Current Gross Salary</label>
                                    <input type="text" id="current_salary" class="form-control" value="{{ old('current_salary') }}" readonly>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Proposed Increment (%)</label>
                                    <input type="number" step="0.01" name="increment_percent" id="increment_percent" class="form-control" value="{{ old('increment_percent') }}" placeholder="e.g. 5">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Proposed Increment (Amount)</label>
                                    <input type="number" step="0.01" name="increment_amount" id="increment_amount" class="form-control" value="{{ old('increment_amount') }}" placeholder="e.g. 2000">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>New Gross Salary</label>
                                    <input type="text" name="new_salary" id="new_salary" class="form-control" value="{{ old('new_salary') }}" readonly>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                                </div>
                            </div>

                            <div class="button-group d-flex justify-content-end">
                                <button type="submit" name="status" value="draft"
                                    class="btn btn-secondary btn-squared radius-md shadow2 btn-sm me-2">Save as Draft</button>
                                <button type="submit" name="status" value="submitted"
                                    class="btn btn-primary btn-squared radius-md shadow2 btn-sm">Submit for Approval</button>
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
    const employees = @json($eligibleEmployees);

    function populateEmployeeData(id) {
        const employee = employees.find(e => e.id == id);

        if (employee && employee.employement_detail) {
            const gross = parseFloat(employee.latest_employee_salary?.gross || 0);
            const avg = parseFloat(employee.avg_score || 0);

            $('#current_salary').val(gross.toFixed());
            $('#avg_score').val(avg.toFixed());

            let html = '';
            const assessments = employee.recent_assessments || [];

            assessments.forEach(a => {
                html += `
                    <tr class="text-center">
                        <td>${a.from_date} to ${a.to_date}</td>
                        <td>${a.total_weight ?? '-'}</td>
                        <td>${a.total_mark ?? '-'}</td>
                        <td>${a.overall_score ?? '-'}%</td>
                    </tr>`;
            });

            $('#assessment-rows').html(html);
            $('#assessment-history').toggle(assessments.length > 0);
        } else {
            $('#current_salary').val('');
            $('#avg_score').val('0');
            $('#assessment-rows').empty();
            $('#assessment-history').hide();
        }
    }

    $('#employee_id').on('change', function () {
        populateEmployeeData($(this).val());
    });

    $('#increment_percent, #increment_amount').on('input', function () {
        const current = parseFloat($('#current_salary').val()) || 0;
        const percent = parseFloat($('#increment_percent').val()) || 0;
        const amount = parseFloat($('#increment_amount').val()) || 0;

        let finalSalary = current;

        if ($(this).attr('id') === 'increment_percent') {
            const calcAmount = (percent / 100) * current;
            $('#increment_amount').val(calcAmount.toFixed());
            finalSalary += calcAmount;
        } else {
            const calcPercent = (amount / current) * 100;
            $('#increment_percent').val(calcPercent.toFixed());
            finalSalary += amount;
        }

        $('#new_salary').val(finalSalary.toFixed());
    });

    @if (old('employee_id'))
        $(document).ready(function () {
            populateEmployeeData("{{ old('employee_id') }}");
        });
    @endif
</script>
@endsection
