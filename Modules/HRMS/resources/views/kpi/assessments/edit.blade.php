@extends('layout.app')

@section('title', 'Edit KPI Assessment')
@section('description', 'Edit KPI assessment for an employee')

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
                                <li class="breadcrumb-item active" aria-current="page">Edit KPI Assessment</li>
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

                        <form action="{{ route('hrm.kpis.assessments.update', $assessment->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Employee --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-500">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control tom-select" required disabled>
                                        <option value="">-- Select Employee --</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ $assessment->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ @$employee->employementDetail->card_no }}: {{ $employee->full_name }} ({{ @$employee->employementDetail->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="employee_id" value="{{ $assessment->employee_id }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-500">Assessment Date <span class="text-danger">*</span></label>
                                    <input type="text" class="flatdaterange form-control" name="from_to_date" value="{{ $assessment->from_date }} to {{ $assessment->to_date }}">
                                </div>
                            </div>

                            {{-- Details --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Branch</label>
                                    <input type="text" class="form-control" value="{{ $assessment->employee->employementDetail->branch->name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Department</label>
                                    <input type="text" class="form-control" value="{{ $assessment->employee->employementDetail->department->name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-500">Designation</label>
                                    <input type="text" class="form-control" value="{{ $assessment->employee->employementDetail->designation->name ?? '' }}" readonly>
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
                                        @foreach ($assessment->details as $index => $kpi)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kpis[{{ $index }}][id]" value="{{ $kpi->kpi_setup_detail_id }}">
                                                    <input type="hidden" name="kpis[{{ $index }}][description]" value="{{ $kpi->description }}">
                                                    {{ $kpi->description }}
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control kpi-weight" name="kpis[{{ $index }}][weight]" value="{{ $kpi->weight }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="kpis[{{ $index }}][mark]" class="form-control kpi-mark" value="{{ $kpi->mark }}" min="0" max="{{ $kpi->weight }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="kpis[{{ $index }}][remarks]" class="form-control" value="{{ $kpi->remarks }}">
                                                </td>
                                            </tr>
                                        @endforeach
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
                                        <input type="hidden" name="total_mark" id="input-total-mark" value="{{ $assessment->total_mark }}">
                                        <input type="hidden" name="total_weight" id="input-total-weight" value="{{ $assessment->total_weight }}">
                                        <input type="hidden" name="overall_score" id="input-overall-score" value="{{ $assessment->overall_score }}">
                                    </tfoot>
                                </table>
                            </div>

                            <div class="button-group d-flex justify-content-end">
                                <button type="submit" name="status" value="draft" class="btn btn-secondary btn-squared radius-md shadow2 btn-sm me-2">Save as Draft</button>
                                <button type="submit" name="status" value="submitted" class="btn btn-primary btn-squared radius-md shadow2 btn-sm">Submit for Approval</button>
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

        $('#input-total-mark').val(totalMark.toFixed());
        $('#input-total-weight').val(totalWeight.toFixed());
        $('#input-overall-score').val(overallScore.toFixed());
    }

    $(document).ready(() => {
        calculateTotal();
    });
</script>
@endsection
