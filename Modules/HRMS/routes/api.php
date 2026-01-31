<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Controllers\Api\AttendanceController;
use Modules\HRMS\Controllers\Api\BillsAndAllowanceController;
use Modules\HRMS\Controllers\Api\DailyVisitPlanController;
use Modules\HRMS\Controllers\Api\EmployeeController;
use Modules\HRMS\Controllers\Api\LeaveApplicationController;
use Modules\HRMS\Controllers\Api\NoticeBoardController;
use Modules\HRMS\Controllers\Api\SalaryGenerateController;

use Modules\HRMS\Http\Controllers\LeaveEncashmentController;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'hrm', 'as' => 'hrm.'], function () {
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::post('attendances-check-in-out', [AttendanceController::class, 'markAttendance'])->name('attendances-check-in-out');
    Route::get('attendances-status', [AttendanceController::class, 'getTodayAttendanceStatus'])->name('attendances-status');
    Route::get('employee-attendances', [AttendanceController::class, 'getJobCardList'])->name('employee-attendances');


    Route::apiResource('leaves', LeaveApplicationController::class);
    Route::get('dashboard', [LeaveApplicationController::class, 'dashboard'])->name('dashboard');
    Route::get('get-leave-response', [LeaveApplicationController::class, 'getLeaveResponse'])->name('get-leave-response');
    Route::get('leave-types', [LeaveApplicationController::class, 'leaveTypes'])->name('leave-types');
    Route::put('leaves-recommended', [LeaveApplicationController::class, 'recommended'])->name('leaves-recommended');
    Route::put('leaves-approved', [LeaveApplicationController::class, 'approved'])->name('leaves-approved');


    Route::get('notice-types', [NoticeBoardController::class, 'noticeType'])->name('notice-types');
    Route::apiResource('noticeboards', NoticeBoardController::class);
    Route::apiResource('bills', controller: BillsAndAllowanceController::class);
    Route::get('expense-types', [BillsAndAllowanceController::class, 'expenseType'])->name('expense-types');
    Route::get('transport-types', [BillsAndAllowanceController::class, 'transportType'])->name('transport-types');

    Route::apiResource('salary-generates', controller: SalaryGenerateController::class);
    Route::get('get-my-payslip', [SalaryGenerateController::class, 'getMyPayslip']);
    Route::get('get-my-payslips', [SalaryGenerateController::class, 'myPayslips']);

    Route::post('employees/calculate-earned-leave/{employeeId}', [LeaveEncashmentController::class, 'calculate']);
    Route::apiResource('daily-visit-plans', DailyVisitPlanController::class);

});
