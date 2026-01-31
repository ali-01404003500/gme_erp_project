<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Controllers\AttendanceController;
use Modules\HRMS\Controllers\AttendanceReportController;
use Modules\HRMS\Controllers\BillsAndAllowanceController;
use Modules\HRMS\Controllers\CareerController;
use Modules\HRMS\Controllers\DailyVisitPlanController;
use Modules\HRMS\Controllers\EmployeeController;
use Modules\HRMS\Controllers\EmployeeSalaryController;
use Modules\HRMS\Controllers\JobApplicationController;
use Modules\HRMS\Controllers\JobController;
use Modules\HRMS\Controllers\JobTemplateController;
use Modules\HRMS\Controllers\Kpi\AppraisalController;
use Modules\HRMS\Controllers\Kpi\AssessmentController;
use Modules\HRMS\Controllers\Kpi\KpiSetupController;
use Modules\HRMS\Controllers\Kpi\KpiTemplateAssignEmployeeController;
use Modules\HRMS\Controllers\Kpi\KpiTemplateController;
use Modules\HRMS\Controllers\Kpi\MonthlyKpiAppraisalController;
use Modules\HRMS\Controllers\Kpi\ResponsibilityEntryController;
use Modules\HRMS\Controllers\Kpi\ScoreWiseSuggestionController;
use Modules\HRMS\Controllers\LeaveApplicationController;
use Modules\HRMS\Controllers\LoanController;
use Modules\HRMS\Controllers\NoticeBoardController;
use Modules\HRMS\Controllers\SalaryGenerateController;
use Modules\HRMS\Controllers\Settings\AppraisalPolicyController;
use Modules\HRMS\Controllers\Settings\DepartmentController;
use Modules\HRMS\Controllers\Settings\DesignationController;
use Modules\HRMS\Controllers\Settings\ExpenseTypeController;
use Modules\HRMS\Controllers\Settings\HolidayController;
use Modules\HRMS\Controllers\Settings\LeaveTypeController;
use Modules\HRMS\Controllers\Settings\NoticeTypeController;
use Modules\HRMS\Controllers\Settings\SalarySetupController;
use Modules\HRMS\Controllers\Settings\ShiftController;
use Modules\HRMS\Controllers\Settings\TransportTypeController;
use Modules\HRMS\Models\Loan;

Route::group(['middleware' => 'auth', 'prefix' => 'hrm', 'as' => 'hrm.'], function () {
    /* Employee */
    Route::resource('employees', EmployeeController::class);
    Route::post('employees-import', [EmployeeController::class, 'importFromCSV'])->name('employees.import');
    Route::get('employees-download-template', [EmployeeController::class, 'downloadTemplate'])->name('employees.download-template');
    Route::get('get-employees', [EmployeeController::class, 'getEmployees'])->name('get-employees');
    Route::resource('employee-salarys', EmployeeSalaryController::class);
    Route::resource('daily-visit-plans', DailyVisitPlanController::class);
    Route::get('daily-visit-plans-approve/{id}', [DailyVisitPlanController::class, 'approve'])->name('daily-visit-plans.approve');
    Route::get('daily-visit-plans-deny/{id}', [DailyVisitPlanController::class, 'deny'])->name('daily-visit-plans.deny');
    Route::resource('loans', LoanController::class);
    Route::get('loans-approve/{id}', [LoanController::class, 'approve'])->name('loans.approve');
    Route::get('loans-deny/{id}', [LoanController::class, 'deny'])->name('loans.deny');
    Route::get('loans/ajax-details/{id}', [LoanController::class, 'ajaxDetails'])->name('loans.ajax-details');

    Route::resource('salary-generates', SalaryGenerateController::class);
    Route::get('payrolls', [SalaryGenerateController::class, 'payrolls'])->name('payrolls');
    Route::post('salary-generates/paid/{id}', [SalaryGenerateController::class, 'paid'])->name('salary-generates.paid');
    Route::post('salary-generates/partially-paid/{id}', [SalaryGenerateController::class, 'partiallyPaid'])->name('salary-generates.partially-paid');
    Route::post('salary-generates/paid-all', [SalaryGenerateController::class, 'paidAll'])->name('salary-generates.paid-all');
    Route::post('salary-generates/partially-paid-all', [SalaryGenerateController::class, 'partiallyPaidAll'])->name('salary-generates.partially-paid-all');

    Route::resource('attendances', AttendanceController::class);

    Route::resource('leaves', LeaveApplicationController::class);
    Route::resource('noticeboards', NoticeBoardController::class);

    Route::get('get-leave-response', [LeaveApplicationController::class, 'getLeaveResponse'])->name('get.leave.response');
    Route::put('leaves-recommended/{id}', [LeaveApplicationController::class, 'recommended'])->name('leaves.recommended');
    Route::put('leaves-approved/{id}', [LeaveApplicationController::class, 'approved'])->name('leaves.approved');

    Route::resource('bills', BillsAndAllowanceController::class);

    Route::get('bills/{id}/verify-details', [BillsAndAllowanceController::class, 'verifyDetails'])->name('bills.verify-details');
    Route::put('bills/{id}/team-leader-verify', [BillsAndAllowanceController::class, 'teamLeaderVerify'])->name('bills.team-leader-verify');
    Route::put('bills/{id}/accounts-verify', [BillsAndAllowanceController::class, 'accountsVerify'])->name('bills.accounts-verify');
    Route::put('bills/{id}/final-approve', [BillsAndAllowanceController::class, 'finalApprove'])->name('bills.final-approve');
   

    Route::get('get-bill-response', [BillsAndAllowanceController::class, 'getLeaveResponse'])->name('get.bill.response');
    Route::put('bills-recommended/{id}', [BillsAndAllowanceController::class, 'recommended'])->name('bills.recommended');
    Route::put('bills-approved/{id}', [BillsAndAllowanceController::class, 'approved'])->name('bills.approved');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('leave-types', LeaveTypeController::class)->except(['show', 'edit', 'create']);
        Route::resource('shifts', ShiftController::class)->except(['show', 'edit', 'create']);
        Route::resource('holidays', HolidayController::class);
        Route::resource('notice-types', NoticeTypeController::class)->except(['show', 'edit', 'create']);
        Route::resource('expense-types', ExpenseTypeController::class)->except(['show', 'edit', 'create']);
        Route::resource('transport-types', TransportTypeController::class)->except(['show', 'edit', 'create']);
        Route::resource('departments', DepartmentController::class)->except(['show', 'edit', 'create']);
        Route::resource('designations', DesignationController::class)->except(['show', 'edit', 'create']);
        Route::resource('salary-setups', SalarySetupController::class);
        Route::resource('appraisal-policies', AppraisalPolicyController::class)->except(['show', 'edit', 'create']);
    });
    Route::group(['prefix' => 'kpis', 'as' => 'kpis.'], function () {
        Route::resource('kpi-setups', KpiSetupController::class);
        Route::resource('assessments', AssessmentController::class);
        Route::resource('appraisals', AppraisalController::class);
        Route::resource('score-wise-suggestions', ScoreWiseSuggestionController::class);
        Route::resource('responsibility-entries', ResponsibilityEntryController::class);
        Route::resource('kpi-templates', KpiTemplateController::class);

        Route::resource('kpi-assignments', KpiTemplateAssignEmployeeController::class);
        Route::post('get-employee-details', [KpiTemplateAssignEmployeeController::class, 'getEmployeeDetails'])->name('get-employee-details');

        Route::resource('monthly-kpi-appraisals', MonthlyKpiAppraisalController::class);
        Route::post('get-monthly-kpi-employee-details', [MonthlyKpiAppraisalController::class, 'getEmployeeDetails'])->name('get-monthly-kpi-employee-details');
        Route::post('get-remarks-by-score', [MonthlyKpiAppraisalController::class, 'getRemarksByScore'])->name('get-remarks-by-score');
        Route::get('monthly-kpi-appraisals/{id}/approve', [MonthlyKpiAppraisalController::class, 'approve'])->name('monthly-kpi-appraisals.approve');
        Route::get('monthly-kpi-appraisals/{id}/reject', [MonthlyKpiAppraisalController::class, 'reject'])->name('monthly-kpi-appraisals.reject');

    });
    Route::resource('jobs', controller: JobController::class);
    Route::get('job/job-templates', [JobController::class, 'fetchJobTemplate'])->name('job-templates.fetch');

    Route::resource('job-templates', controller: JobTemplateController::class);
    Route::resource('job-applications', JobApplicationController::class);
    Route::put('job-applications/{id}/update-status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('daily-attendance-report', [AttendanceReportController::class, 'dailyReport'])->name('daily-attendance-report');
        Route::get('monthly-attendance-report', [AttendanceReportController::class, 'monthlyReport'])->name('monthly-attendance-report');
    });
});

Route::group(['prefix' => 'carrier', 'as' => 'carrier.'], function () {
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('{id}/apply', [CareerController::class, 'jobApply'])->name('apply');
    Route::post('{id}/apply', [CareerController::class, 'jobApplicationStore'])->name('apply.store');
});
