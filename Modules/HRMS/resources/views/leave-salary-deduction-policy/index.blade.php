@extends('layout.app')
@section('content')

    <div class="container-fluid py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item text-primary"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active">Leave/Salary Deduction Policy</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-4 text-primary">Leave/Salary Deduction Policy</h4>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="d-flex align-items-start">

                    <!-- Left Navigation Menu -->
                    <div class="nav flex-column nav-pills border-end p-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical" style="min-width: 280px; background-color: #fcfcfc;">
                        <button class="nav-link active py-3 text-start fw-bold mb-2 shadow-none" data-tab="absent"
                            type="button">Absent Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="delay"
                            type="button">Delay Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="extreme"
                            type="button">Extreme Delay Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="early-out"
                            type="button">Early Out Deduction Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="underwork"
                            type="button">Underwork Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="unpaid"
                            type="button">Unpaid Leave Deduction Policy</button>
                        <button class="nav-link py-3 text-start fw-bold mb-2 shadow-none" data-tab="missed-out"
                            type="button">Missed Out Time Deduction Policy</button>
                    </div>

                    <!-- Content Area - Only ONE form will be loaded at a time -->
                    <div class="tab-content flex-grow-1 p-4" id="tab-content-container">
                        <!-- Content will be loaded dynamically via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-pills .nav-link {
            color: #555;
            border-radius: 8px;
            transition: 0.2s;
        }

        .nav-pills .nav-link.active {
            background-color: #6f42c1 !important;
            color: white !important;
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f1f1f1;
        }

        .italic {
            font-style: italic;
        }

        .form-check-input:checked {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
    </style>

    <script>
        const policies = @json($policies);
        const tabContents = {
            absent: `
                                                                        <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="policy_type" value="absent">
                                                                            <h5 class="fw-bold mb-4">Absent Policy</h5>
                                                                            <div class="row g-4 align-items-start">
                                                                                <div class="col-md-7">
                                                                                    <div class="form-check mb-3">
                                                                                        <input class="form-check-input" type="checkbox" name="absent_consider" ${policies.absent.consider_absent ? 'checked' : ''}>
                                                                                        <label class="form-check-label ms-2">Consider Absent Policy</label>
                                                                                    </div>
                                                                                    <div class="form-check mb-3">
                                                                                        <input class="form-check-input" type="checkbox" name="absent_deduct_salary" ${policies.absent.deduct_from_salary ? 'checked' : ''}>
                                                                                        <label class="form-check-label ms-2">Deduct from Salary? (Otherwise from leave)</label>
                                                                                    </div>
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="checkbox" name="absent_deduct_gross" ${policies.absent.deduct_from_gross ? 'checked' : ''}>
                                                                                        <label class="form-check-label ms-2">Deduct from Gross Salary? (Otherwise from basic)</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5">  
                                                                                    <label class="small fw-bold mb-2">Number of days adjust for each absent <span class="text-danger">*</span></label>
                                                                                    <input type="number" name="absent_days" class="form-control" value="${policies.absent.adjust_days}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-5 pt-4 border-top">
                                                                                <div class="text-end">
                                                                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    `,
            delay: `
                                                                    <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="policy_type" value="delay">
                                                                        <h5 class="fw-bold mb-4">Delay Policy</h5>
                                                                        <div class="row g-4 align-items-start">
                                                                            <div class="col-md-7">
                                                                                <div class="form-check mb-3">
                                                                                    <input class="form-check-input" type="checkbox" name="delay_consider" ${policies.delay.consider_delay ? 'checked' : ''}>
                                                                                    <label class="form-check-label ms-2">Consider Delay Deduction?</label>
                                                                                </div>
                                                                                <div class="form-check mb-3">
                                                                                    <input class="form-check-input" type="checkbox" name="delay_deduct_salary" ${policies.delay.deduct_from_salary ? 'checked' : ''}>
                                                                                    <label class="form-check-label ms-2">Deduct from Salary? (Otherwise from leave)</label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="delay_consecutive" ${policies.delay.consider_consecutive_delay ? 'checked' : ''}>
                                                                                    <label class="form-check-label ms-2">Consider Consecutive Delay?</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <label class="small fw-bold mb-2">Number of Delay to Consider <span class="text-danger">*</span></label>
                                                                                <input type="number" name="delay_limit" class="form-control mb-3" value="${policies.delay.delay_limit}">

                                                                                <label class="small fw-bold mb-2">Day(s) adjust for delay <span class="text-danger">*</span></label>
                                                                                <input type="number" name="delay_adjust" class="form-control" value="${policies.delay.adjust_days}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-5 pt-4 border-top">
                                                                            <div class="text-end">
                                                                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                `,
            extreme: `
                                                        <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="policy_type" value="extreme_delay">
                                                            <h5 class="fw-bold mb-4">Extreme Delay Policy <i class="fas fa-info-circle text-muted small"></i></h5>
                                                            <div class="row g-4 align-items-start">
                                                                <div class="col-md-7">
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" name="ext_consider" ${policies.extreme_delay.consider_extreme_delay ? 'checked' : ''}>
                                                                        <label class="form-check-label ms-2">Consider Extreme Delay Deduction?</label>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" name="ext_deduct_salary" ${policies.extreme_delay.deduct_from_salary ? 'checked' : ''}>
                                                                        <label class="form-check-label ms-2">Deduct from Salary? (Otherwise from leave)</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="ext_consecutive" ${policies.extreme_delay.consider_consecutive_extreme_delay ? 'checked' : ''}>
                                                                        <label class="form-check-label ms-2">Consider Consecutive Extreme Delay?</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label class="small fw-bold mb-2">Number of Extreme Delay to Consider <span class="text-danger">*</span></label>
                                                                    <input type="number" name="ext_limit" class="form-control mb-3" value="${policies.extreme_delay.extreme_delay_limit}">

                                                                    <label class="small fw-bold mb-2">Day(s) adjust for Extreme delay <span class="text-danger">*</span></label>
                                                                    <input type="number" name="ext_adjust" class="form-control" value="${policies.extreme_delay.adjust_days}">
                                                                </div>
                                                            </div>
                                                            <div class="mt-5 pt-4 border-top">
                                                                <div class="text-end">
                                                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    `,
            'early-out': `
                                    <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="policy_type" value="early_out">
                                        <h5 class="fw-bold mb-4">Early Out Deduction Policy</h5>
                                        <div class="row g-4 align-items-start">
                                            <div class="col-md-7">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="early_out_consider" ${policies.early_out.consider_early_out ? 'checked' : ''}>
                                                    <label class="form-check-label ms-2">Consider Early Out Deduction?</label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="early_out_deduct_gross" ${policies.early_out.deduct_from_gross ? 'checked' : ''}>
                                                    <label class="form-check-label ms-2">Deduct from Gross Salary? (Otherwise from basic)</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="early_out_consecutive" ${policies.early_out.consider_consecutive_early_out ? 'checked' : ''}>
                                                    <label class="form-check-label ms-2">Consider Consecutive Early Out?</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small fw-bold mb-2">Number of Early Out to Consider <span class="text-danger">*</span></label>
                                                <input type="number" name="early_out_limit" class="form-control mb-3" value="${policies.early_out.early_out_limit}">

                                                <label class="small fw-bold mb-2">Day(s) adjust for Early Out <span class="text-danger">*</span></label>
                                                <input type="number" name="early_out_adjust" class="form-control" value="${policies.early_out.adjust_days}">
                                            </div>
                                        </div>
                                        <div class="mt-5 pt-4 border-top">
                                            <p class="text-muted small italic mb-3">*** Consecutive Delay, Extreme Delay & Early Out & Missed Out Time deduction policy will not work for roster employees.</p>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                `,
            underwork: `
                            <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="policy_type" value="underwork">
                                <h5 class="fw-bold mb-4">Underwork Deduction Policy <i class="fas fa-info-circle text-muted small"></i></h5>
                                <div class="row g-4 align-items-start">
                                    <div class="col-md-7">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="under_consider" ${policies.underwork.consider_underwork ? 'checked' : ''}>
                                            <label class="form-check-label ms-2">Consider Underwork Deduction?</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="under_cumulative" ${policies.underwork.consider_cumulative ? 'checked' : ''}>
                                            <label class="form-check-label ms-2">Consider Cumulative Underwork Deduction?</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="under_deduct_salary" ${policies.underwork.deduct_from_salary ? 'checked' : ''}>
                                            <label class="form-check-label ms-2">Deduct from Salary? (Otherwise from leave)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-2">Leave Type <span class="text-danger">*</span></label>
                                        <select name="leave_type" class="form-select mb-3">
                                            <option value="">Select Leave Type</option>
                                            @foreach($leaveTypes as $type)
                                                <option value="{{ $type->id }}" ${policies.underwork.leave_type_id == {{ $type->id }} ? 'selected' : ''}>{{ $type->leave_type_name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="small fw-bold mb-2">Number of hours to consider <span class="text-danger">*</span></label>
                                        <input type="number" name="under_hours" class="form-control mb-3" value="${policies.underwork.hours_to_consider}">
                                        <label class="small fw-bold mb-2">Day(s) adjust for underwork <span class="text-danger">*</span></label>
                                        <input type="number" name="under_adjust" class="form-control" value="${policies.underwork.adjust_days}">
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-top">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                                    </div>
                                </div>
                            </form>
                        `,
            unpaid: `
                    <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="policy_type" value="unpaid_leave">
                        <h5 class="fw-bold mb-4">Unpaid Leave Deduction Policy</h5>
                        <div class="row g-4 align-items-start">
                            <div class="col-md-7">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="unpaid_consider" ${policies.unpaid_leave.unpaid_consider ? 'checked' : ''}>
                                    <label class="form-check-label ms-2">Consider Unpaid Leave Deduction?</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="unpaid_deduct_gross" ${policies.unpaid_leave.unpaid_deduct_gross ? 'checked' : ''}>
                                    <label class="form-check-label ms-2">Deduct from Gross Salary? (Otherwise from basic)</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 pt-4 border-top">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                            </div>
                        </div>
                    </form>
                `,
            'missed-out': `
            <form action="{{ route('hrm.settings.leave-salary-deduction-policies.store') }}" method="POST">
                @csrf
                <input type="hidden" name="policy_type" value="missed_out">
                <h5 class="fw-bold mb-4">Missed Out Time Deduction Policy</h5>
                <div class="row g-4 align-items-start">
                    <div class="col-md-7">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="missed_out_consider" ${policies.missed_out.consider_missed_out ? 'checked' : ''}>
                            <label class="form-check-label ms-2">Consider Missed Out Time Deduction?</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="missed_out_deduct_gross" ${policies.missed_out.deduct_from_gross ? 'checked' : ''}>
                            <label class="form-check-label ms-2">Deduct from Gross Salary? (Otherwise from basic)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="missed_out_consecutive" ${policies.missed_out.consider_consecutive ? 'checked' : ''}>
                            <label class="form-check-label ms-2">Consider Consecutive Missed Out Time?</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold mb-2">Number of Missed Out Time to Consider <span class="text-danger">*</span></label>
                        <input type="number" name="missed_out_limit" class="form-control mb-3" value="${policies.missed_out.missed_out_limit}">

                        <label class="small fw-bold mb-2">Day(s) adjust for Missed Out Time <span class="text-danger">*</span></label>
                        <input type="number" name="missed_out_adjust" class="form-control" value="${policies.missed_out.adjust_days}">
                    </div>
                </div>
                <div class="mt-5 pt-4 border-top">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">Save</button>
                    </div>
                </div>
            </form>
        `,
        };


        document.addEventListener('DOMContentLoaded', function () {
            loadTabContent('absent');
        });



        document.querySelectorAll('.nav-link').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();


                document.querySelectorAll('.nav-link').forEach(btn => {
                    btn.classList.remove('active');
                });


                this.classList.add('active');


                const tabId = this.getAttribute('data-tab');
                loadTabContent(tabId);
            });
        });


        function loadTabContent(tabId) {
            const container = document.getElementById('tab-content-container');

            if (tabContents[tabId]) {
                container.innerHTML = tabContents[tabId];
            }
        }
    </script>
@endsection