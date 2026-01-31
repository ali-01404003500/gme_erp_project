@extends('layout.app')

@section('title', 'Monthly KPI Appraisal')
@section('description', 'Create Monthly KPI Appraisal for Employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Monthly KPI Appraisal</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.index'))
                            <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <style>
            #kpi-table td:nth-child(2) {
    max-width: 200px; /* Adjust as needed */
    word-wrap: break-word; /* Ensure long words break */
    white-space: normal; /* Allow text to wrap to the next line */
}
        </style>

        <div class="col-md-12 m-2">
            <h4 class="text-capitalize breadcrumb-title">Monthly KPI Appraisal</h4>
            <x-error-alart />
        </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">
                        <form action="{{ route('hrm.kpis.monthly-kpi-appraisals.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Employee -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="employee_id" class="color-dark fs-14 fw-500">Employee <span class="text-danger">*</span></label>
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

                                <!-- Assessment Month -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="assessment_month" class="color-dark fs-14 fw-500">Assessment Month <span class="text-danger">*</span></label>
                                        <input type="month" name="assessment_month" id="assessment_month" class="form-control" value="{{ old('assessment_month') }}" required>
                                        @error('assessment_month') <p class="text-danger mb-0">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Branch (Auto-fill) -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="branch" class="color-dark fs-14 fw-500">Branch</label>
                                        <input type="text" id="branch" class="form-control" value="{{ old('branch') }}" readonly>
                                        <input type="hidden" name="branch_id" id="branch_id" value="{{ old('branch_id') }}">
                                    </div>
                                </div>

                                <!-- Department (Auto-fill) -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="department" class="color-dark fs-14 fw-500">Department</label>
                                        <input type="text" id="department" class="form-control" value="{{ old('department') }}" readonly>
                                        <input type="hidden" name="department_id" id="department_id" value="{{ old('department_id') }}">
                                    </div>
                                </div>

                                <!-- Designation (Auto-fill) -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="designation" class="color-dark fs-14 fw-500">Designation</label>
                                        <input type="text" id="designation" class="form-control" value="{{ old('designation') }}" readonly>
                                        <input type="hidden" name="designation_id" id="designation_id" value="{{ old('designation_id') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- KPI Appraisal Table -->
                            <div class="table-responsive mt-4">
                                <h5>KPI Appraisal Details</h5>
                                <table class="table table-bordered" id="kpi-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">SL</th>
                                            <th width="30%">Key Responsibilities</th>
                                            <th width="10%">Target Days (T)</th>
                                            <th width="10%">Actual Days (A)</th>
                                            <th width="15%">KPI Score (%) K=(T/A)*100</th>
                                                                                        <th width="10%">Weight</th>

                                            <th width="15%">Performance Score (%) (K*Weight)/100</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(old('responsibilities'))
                                            @foreach(old('responsibilities') as $index => $responsibility)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>
                                                        {{ $responsibility['key_responsibility'] ?? '' }}
                                                        <input type="hidden" name="responsibilities[{{ $index }}][responsibility_entry_id]" value="{{ $responsibility['responsibility_entry_id'] ?? '' }}">
                                                        <input type="hidden" name="responsibilities[{{ $index }}][key_responsibility]" value="{{ $responsibility['key_responsibility'] ?? '' }}">
                                                        <input type="hidden" name="responsibilities[{{ $index }}][target_days]" value="{{ $responsibility['target_days'] ?? '' }}">
                                                        <input type="hidden" name="responsibilities[{{ $index }}][weight]" value="{{ $responsibility['weight'] ?? '' }}">
                                                    </td>
                                                    <td><input type="number" step="0.01" class="form-control form-control-sm" value="{{ $responsibility['target_days'] ?? '' }}" readonly></td>
                                                    <td><input type="number" step="0.01" name="responsibilities[{{ $index }}][actual_days]" class="form-control form-control-sm" min="0" value="{{ $responsibility['actual_days'] ?? '' }}"></td>
                                                    <td><input type="number" step="0.01" name="responsibilities[{{ $index }}][kpi_score]" value="{{ $responsibility['kpi_score'] ?? '0.00' }}" class="form-control form-control-sm" readonly></td>
                                                                                                        <td>{{ $responsibility['weight'] ?? '' }}</td>

                                                    <td><input type="number" step="0.01" name="responsibilities[{{ $index }}][performance_score]" value="{{ $responsibility['performance_score'] ?? '0.00' }}" class="form-control form-control-sm" readonly></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <!-- Rows will be loaded dynamically -->
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <th colspan="6" class="text-end">Total Performance Score:</th>
                                            <th><span id="total-performance-score">0.00</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Score Management -->
                            <div class="table-responsive mt-4">
                                <h5>Score Management on Performance Appraisal</h5>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Score On</th>
                                            <th>Score (Weight)</th>
                                            <th>Achieved Score</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Performance Score</td>
                                            <td>70</td>
                                            <td><input type="text" id="performance_score_display" name="achieved_performance_score" class="form-control" value="{{ old('achieved_performance_score', '0.00') }}" readonly></td>
                                            <td><input type="text" name="performance_score_note" id="performance_score_note" class="form-control" value="{{ old('performance_score_note') }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Succession Management</td>
                                            <td>20</td>
                                            <td><input type="number" step="0.01" name="succession_management_score" id="succession_management_score" class="form-control" min="0" max="20" value="{{ old('succession_management_score', 0) }}"></td>
                                            <td><input type="text" name="succession_management_note" id="succession_management_note" class="form-control" value="{{ old('succession_management_note') }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Behavioral Performance</td>
                                            <td>10</td>
                                            <td><input type="number" step="0.01" name="behavioral_performance_score" id="behavioral_performance_score" class="form-control" min="0" max="10" value="{{ old('behavioral_performance_score', 0) }}"></td>
                                            <td><input type="text" name="behavioral_performance_note" id="behavioral_performance_note" class="form-control" value="{{ old('behavioral_performance_note') }}"></td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td><strong>Aggregate Score</strong></td>
                                            <td><strong>100</strong></td>
                                            <td><strong><span id="aggregate_score_display">0.00</span></strong></td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                </table>

                                <div class="alert alert-info">
                                    <strong>Remarks:</strong> <span id="remarks_display">Enter scores to see grade and training suggestions.</span>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="notes" class="color-dark fs-14 fw-500">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="submit" name="status" value="Draft" class="btn btn-secondary btn-default btn-squared radius-md shadow2 btn-sm me-2">
                                    <i class="fa fa-save"></i> Save as Draft
                                </button>
                                <button type="submit" name="status" value="Submitted" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-paper-plane"></i> Submit for Approval
                                </button>
                            </div>

                            <input type="hidden" name="kpi_template_assign_employee_id" id="kpi_template_assign_employee_id" value="{{ old('kpi_template_assign_employee_id') }}">
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
    const tableBody = document.querySelector('#kpi-table tbody');
    const employeeSelect = document.getElementById('employee_id');
    const assessmentMonthInput = document.getElementById('assessment_month');
    const kpiAssignmentIdInput = document.getElementById('kpi_template_assign_employee_id');

    // Store old data flag
    const hasOldData = {{ old('responsibilities') ? 'true' : 'false' }};

    // Calculate KPI Score and Performance Score for a single row
    function calculateScores(row) {
        const targetDays = parseFloat(row.querySelector('[name*="target_days"]').value) || 0;
        const actualDays = parseFloat(row.querySelector('[name*="actual_days"]').value) || 0;
        const weight = parseFloat(row.querySelector('[name*="weight"]').value) || 0;

        let kpiScore = 0;
        if (targetDays > 0) {
            kpiScore = (targetDays / actualDays) * 100;
        }

        const performanceScore = (kpiScore * weight) / 100;

        row.querySelector('[name*="kpi_score"]').value = kpiScore.toFixed();
        row.querySelector('[name*="performance_score"]').value = performanceScore.toFixed();

        calculateTotalScores();
    }

    // Calculate total performance score (sum of all weighted performance scores)
    function calculateTotalScores() {
        let totalPerformance = 0;
        let count = 0;
        
        // Sum all individual performance scores (which are already weighted)
        document.querySelectorAll('[name*="[performance_score]"]').forEach(input => {
            if (input.name.includes('responsibilities')) {
                const value = parseFloat(input.value) || 0;
                totalPerformance += value;
                count++;
            }
        });
        // const averagePerformance = count > 0 ? totalPerformance / count : 0;

        // Display the total performance score (this should be out of 100%)
        document.getElementById('total-performance-score').textContent = totalPerformance.toFixed();

        // Scale it to 70 for the score management section
        const performanceScoreIn70Scale = (totalPerformance * 70) / 100;
        document.getElementById('performance_score_display').value = performanceScoreIn70Scale.toFixed();

        calculateAggregateScore();
    }

    // Calculate aggregate score and generate remarks
    function calculateAggregateScore() {
        const performanceScore = parseFloat(document.getElementById('performance_score_display').value) || 0;
        const successionScore = parseFloat(document.getElementById('succession_management_score').value) || 0;
        const behavioralScore = parseFloat(document.getElementById('behavioral_performance_score').value) || 0;

        const aggregateScore = performanceScore + successionScore + behavioralScore;
        document.getElementById('aggregate_score_display').textContent = aggregateScore.toFixed();

        generateRemarks(aggregateScore);
    }

    // Generate remarks based on aggregate score from API
    function generateRemarks(score) {
        if (score <= 0) {
            document.getElementById('remarks_display').textContent = 'Enter scores to see grade and training suggestions.';
            return;
        }

        fetch('{{ route("hrm.kpis.get-remarks-by-score") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ score: score })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('remarks_display').textContent = data.formatted_remarks;
        })
        .catch(err => {
            console.error('Error fetching remarks:', err);
            // Fallback to default remarks
            let remarks = '';
            if (score >= 90) {
                remarks = "Grade: A+ (Excellent). Outstanding performance.";
            } else if (score >= 80) {
                remarks = "Grade: A (Very Good). Strong performance.";
            } else if (score >= 70) {
                remarks = "Grade: B (Good). Satisfactory performance.";
            } else if (score >= 60) {
                remarks = "Grade: C (Average). Meets basic expectations.";
            } else {
                remarks = "Grade: D (Below Average). Needs improvement.";
            }
            document.getElementById('remarks_display').textContent = remarks;
        });
    }

    // Event listeners for score calculations
    document.getElementById('succession_management_score').addEventListener('input', calculateAggregateScore);
    document.getElementById('behavioral_performance_score').addEventListener('input', calculateAggregateScore);

    // Event delegation for actual days input
    tableBody.addEventListener('input', function (e) {
        if (e.target.name && e.target.name.includes('actual_days')) {
            const row = e.target.closest('tr');
            calculateScores(row);
        }
    });

    // Load employee details and KPI assignment
    function loadEmployeeData() {
        const employeeId = employeeSelect.value;
        const assessmentMonth = assessmentMonthInput.value;

        if (!employeeId || !assessmentMonth) {
            // Don't clear if we have old data
            if (!hasOldData) {
                tableBody.innerHTML = '';
                document.getElementById('branch').value = '';
                document.getElementById('department').value = '';
                document.getElementById('designation').value = '';
                kpiAssignmentIdInput.value = '';
            }
            return;
        }

        fetch('{{ route("hrm.kpis.get-monthly-kpi-employee-details") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                employee_id: employeeId,
                assessment_month: assessmentMonth
            })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.error || 'Failed to load employee details');
                });
            }
            return res.json();
        })
        .then(data => {
            // Populate employee fields
            document.getElementById('branch').value = data.employee.branch_name;
            document.getElementById('branch_id').value = data.employee.branch_id || '';
            document.getElementById('department').value = data.employee.department_name;
            document.getElementById('department_id').value = data.employee.department_id || '';
            document.getElementById('designation').value = data.employee.designation_name;
            document.getElementById('designation_id').value = data.employee.designation_id || '';

            kpiAssignmentIdInput.value = data.kpi_assignment.id;

            // Clear and load responsibilities only if no old data
            if (!hasOldData) {
                tableBody.innerHTML = '';
                data.kpi_assignment.responsibilities.forEach((resp, index) => {
                    const i = index + 1;
                    const row = `
                    <tr>
                        <td>${i}</td>
                        <td>
                            ${escapeHtml(resp.key_responsibility)}
                            <input type="hidden" name="responsibilities[${i}][responsibility_entry_id]" value="${resp.responsibility_entry_id}">
                            <input type="hidden" name="responsibilities[${i}][key_responsibility]" value="${escapeHtml(resp.key_responsibility)}">
                            <input type="hidden" name="responsibilities[${i}][target_days]" value="${resp.target_days}">
                            <input type="hidden" name="responsibilities[${i}][weight]" value="${resp.weight}">
                        </td>
                        <td><input type="number" step="0.01" class="form-control form-control-sm" value="${resp.target_days}" readonly></td>
                        <td><input type="number" step="0.01" name="responsibilities[${i}][actual_days]" class="form-control form-control-sm" min="0" value=""></td>
                        <td><input type="number" step="0.01" name="responsibilities[${i}][kpi_score]" value="0.00" class="form-control form-control-sm" readonly></td>
                                                <td>${resp.weight}</td>

                        <td><input type="number" step="0.01" name="responsibilities[${i}][performance_score]" value="0.00" class="form-control form-control-sm" readonly></td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            }

            calculateTotalScores();
        })
        .catch(err => {
            toastr.error(err.message);
            // Don't clear if we have old data
            if (!hasOldData) {
                tableBody.innerHTML = '';
                kpiAssignmentIdInput.value = '';
            }
        });
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Load employee data on change if no old data exists
    if (!hasOldData) {
        employeeSelect.addEventListener('change', loadEmployeeData);
        assessmentMonthInput.addEventListener('change', loadEmployeeData);
    } else {
        // If old data exists, recalculate scores on page load
        document.querySelectorAll('#kpi-table tbody tr').forEach(row => {
            const actualDaysInput = row.querySelector('[name*="actual_days"]');
            if (actualDaysInput && actualDaysInput.value) {
                calculateScores(row);
            }
        });
        
        // Add event listeners for future changes
        employeeSelect.addEventListener('change', loadEmployeeData);
        assessmentMonthInput.addEventListener('change', loadEmployeeData);
    }
});
</script>
@endsection