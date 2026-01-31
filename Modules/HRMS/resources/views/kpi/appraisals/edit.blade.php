@extends('layout.app')

@section('title', 'Edit Appraisal')
@section('description', 'Edit Appraisal for Employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Edit Appraisal</li>
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

                        <form action="{{ route('hrm.kpis.appraisals.update', $appraisal->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-500">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control tom-select" disabled>
                                        <option value="">-- Select Eligible Employee --</option>
                                        @foreach ($eligibleEmployees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                {{ $appraisal->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ @$employee->employementDetail->card_no }}: {{ $employee->full_name }}
                                                ({{ @$employee->employementDetail->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="employee_id" value="{{ $appraisal->employee_id }}" hidden>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div id="assessment-history">
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
                                                <tbody id="assessment-rows">
                                                    @foreach ($appraisal->employee->recent_assessments ?? [] as $a)
                                                    <tr class="text-center">
                                                        <td>{{ $a->from_date }} to {{ $a->to_date }}</td>
                                                        <td>{{ $a->total_weight ?? '-' }}</td>
                                                        <td>{{ $a->total_mark ?? '-' }}</td>
                                                        <td>{{ $a->overall_score ?? '-' }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 mt-3">
                                    <label class="fw-500">Last 6 Assessments Avg. Score(%)</label>
                                    <input type="text" id="avg_score" class="form-control" readonly
                                           value="{{ number_format($appraisal->employee->avg_score ?? 0) }}">
                                </div>
                                <div class="col-md-6 mb-3 mt-3">
                                    <label>Current Gross Salary</label>
                                    <input type="text" id="current_salary" class="form-control" readonly
                                           value="{{ number_format($appraisal->employee->latestEmployeeSalary->gross ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Proposed Increment (%)</label>
                                    <input type="number" step="0.01" name="increment_percent" id="increment_percent"
                                           class="form-control" value="{{ $appraisal->increment_percent }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Proposed Increment (Amount)</label>
                                    <input type="number" step="0.01" name="increment_amount" id="increment_amount"
                                           class="form-control" value="{{ $appraisal->increment_amount }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>New Gross Salary</label>
                                    <input type="text" name="new_salary" id="new_salary" class="form-control"
                                           value="{{ $appraisal->new_salary }}" readonly>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks" class="form-control" value="{{ $appraisal->remarks }}">
                                </div>
                            </div>

                            <div class="button-group d-flex justify-content-end">
                                @if(request()->status == 'approved')
                                <button type="submit" name="status" value="rejected"
                                        class="btn btn-danger btn-squared radius-md shadow2 btn-sm me-2">Reject</button>
                                    <button type="submit" name="status" value="approved"
                                            class="btn btn-success btn-squared radius-md shadow2 btn-sm me-2">Approve</button>
                                @else
                                <button type="submit" name="status" value="draft"
                                        class="btn btn-secondary btn-squared radius-md shadow2 btn-sm me-2">Save as Draft</button>
                                <button type="submit" name="status" value="submitted"
                                        class="btn btn-primary btn-squared radius-md shadow2 btn-sm">Submit for Approval</button>
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
<script>
    const employees = @json($eligibleEmployees);

    $(document).ready(function () {
        // Handle employee change
        $('#employee_id').on('change', function () {
            const id = $(this).val();
            const employee = employees.find(e => e.id == id);

            if (employee && employee.employement_detail) {
                const salary = parseFloat(employee.latest_employee_salary?.gross || 0).toFixed();
                const avgScore = parseFloat(employee.avg_score || 0).toFixed();

                $('#current_salary').val(salary);
                $('#avg_score').val(avgScore);

                // Populate assessment history table
                const assessments = employee.recent_assessments || [];
                let html = '';

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
        });

        // Calculate increment logic
        $('#increment_percent, #increment_amount').on('input', function () {
            const current = parseFloat($('#current_salary').val()) || 0;
            const percent = parseFloat($('#increment_percent').val()) || 0;
            const amount = parseFloat($('#increment_amount').val()) || 0;

            let finalSalary = current;

            if ($(this).attr('id') === 'increment_percent') {
                const newAmount = (percent / 100) * current;
                finalSalary += newAmount;
                $('#increment_amount').val(newAmount.toFixed());
            } else {
                finalSalary += amount;
                const newPercent = (current > 0) ? (amount / current) * 100 : 0;
                $('#increment_percent').val(newPercent.toFixed());
            }

            $('#new_salary').val(finalSalary.toFixed());
        });

        // Trigger on page load if editing
        const preSelectedId = $('#employee_id').val();
        if (preSelectedId) {
            $('#employee_id').trigger('change');
        }
    });
</script>

@endsection
