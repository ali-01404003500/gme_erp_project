@extends('layout.app')

@section('content')
    <div class="container-fluid mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item text-primary"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Salary Generation Policy</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-4 mt-4">Salary Generation Policy</h4>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <form action="{{ route('hrm.settings.salary-generation-policies.store') }}" method="POST">
                    @csrf
                    <div class="row g-5">
                        <div class="col-md-5">
                            <div class="mb-4 d-flex align-items-center">
                                <div class="form-check me-2">
                                    <input class="form-check-input" type="radio" name="calculation_type" id="actualDays"
                                        value="actual_days" {{ $policy->calculation_type == 'actual_days' ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="actualDays">Consider Actual
                                        Days</label>
                                </div>
                                <i class="fas fa-info-circle text-secondary small cursor-pointer"
                                    title="Calculate based on total days in month"></i>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <div class="form-check me-2">
                                    <input class="form-check-input" type="radio" name="calculation_type" id="workingDays"
                                        value="working_days" {{ $policy->calculation_type == 'working_days' ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="workingDays">Consider Working
                                        Days</label>
                                </div>
                                <i class="fas fa-info-circle text-secondary small cursor-pointer"
                                    title="Calculate based on working days only"></i>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <div class="form-check me-2">
                                    <input class="form-check-input" type="radio" name="calculation_type" id="fixedDays"
                                        value="fixed_days" {{ $policy->calculation_type == 'fixed_days' ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="fixedDays">Consider Fixed
                                        Days</label>
                                </div>
                                <i class="fas fa-info-circle text-secondary small cursor-pointer"
                                    title="Calculate based on a set number of days"></i>
                            </div>

                            <div class="ms-4 ps-2 mt-2" id="fixedDaysInput"
                                style="{{ $policy->calculation_type == 'fixed_days' ? '' : 'display:none;' }}">
                                <div class="input-group mb-3" style="max-width: 300px;">
                                    <span class="input-group-text border-end-0 text-secondary small fw-bold">Fixed
                                        Days</span>
                                    <input type="number" name="fixed_days" class="form-control border-start-0"
                                        value="{{ $policy->fixed_days }}" min="1" max="31">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-4 d-flex align-items-center">
                                <div class="form-check me-2">
                                    <input class="form-check-input rounded-0" type="checkbox" name="is_rounded_salary"
                                        id="roundedSalary" value="1" {{ $policy->is_rounded_salary ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="roundedSalary">Rounded
                                        Salary</label>
                                </div>
                                <i class="fas fa-info-circle text-secondary small cursor-pointer"
                                    title="Round the final salary amount to the nearest whole number"></i>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <div class="form-check me-2">
                                    <input class="form-check-input rounded-0" type="checkbox"
                                        name="is_salary_end_date_different" id="diffEndDate" value="1" {{ $policy->is_salary_end_date_different ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="diffEndDate">Is Salary End Date
                                        Different From Month End Date?</label>
                                </div>
                                <i class="fas fa-info-circle text-secondary small cursor-pointer"
                                    title="Enable custom salary period end dates"></i>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('input[name="calculation_type"]').on('change', function () {
                    if ($(this).val() === 'fixed_days') {
                        $('#fixedDaysInput').fadeIn();
                    } else {
                        $('#fixedDaysInput').fadeOut();
                    }
                });
            });
        </script>
        <style>
            .cursor-pointer {
                cursor: help;
            }

            .form-check-input:checked {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .input-group-text {
                font-size: 0.85rem;
            }
        </style>
    @endpush
@endsection