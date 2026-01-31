<?php

namespace Modules\HRMS\Services;

use Carbon\Carbon;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EarnedLeave;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\Settings\Holiday;

class LeaveEncashmentService
{
    public function calculateEarnedLeave(Employee $employee, int $year)
    {
        // dd(Attendance::where('employee_id', $employee->id)
        //     ->whereYear('date', $year)
        //     ->get());
        
        // Get employee's joining date from employment details
        $employmentDetail = $employee->employementDetail;
        if (!$employmentDetail || !$employmentDetail->date_of_joining) {
            return ['message' => 'Employee joining date not found.'];
        }
        
        if (Carbon::parse($employmentDetail->date_of_joining)->diffInYears(Carbon::now()) < 1) {
            return ['message' => 'Employee must have at least 1 year of tenure.'];
        }

        $totalYearDays = Carbon::createFromDate($year)->isLeapYear() ? 366 : 365;

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();

        // Since there's no holiday_type column in holidays table, we'll need to adjust this logic
        // For now, we'll count all holidays
        $holidays = \Modules\HRMS\Models\Settings\Holiday::all();
        $weeklyHolidays = $holidays->where('day_type', 1)->count(); // Assuming day_type 1 is for weekly holidays
        $festivalHolidays = $holidays->where('day_type', 2)->where('every_year', 1)->count(); // Assuming day_type 2 is for festival holidays

        // Use day_count instead of total_days for leave applications
        $sickLeave = $employee->leaves()->whereYear('from_date', $year)->where('leave_type_id', 1)->sum('day_count');
        $casualLeave = $employee->leaves()->whereYear('from_date', $year)->where('leave_type_id', 2)->sum('day_count');

        $totalNonPresentDays = $weeklyHolidays + $festivalHolidays + $sickLeave + $casualLeave + $absentDays;
        $presentDays = $totalYearDays - $totalNonPresentDays;

        $earnedLeaveDays = round($presentDays / 18, 2);

        $employeeSalary = EmployeeSalary::where('employee_id', $employee->id)->first();
        if (!$employeeSalary) {
            return ['message' => 'Employee salary not found.'];
        }

        // Calculate total salary from individual components since there's no total_salary column
        $monthlySalary = $employeeSalary->basic + $employeeSalary->house_rent + $employeeSalary->medical + $employeeSalary->conveyance + $employeeSalary->others;
        $dailySalary = round($monthlySalary / 30, 2);
        $encashmentValue = round($dailySalary * $earnedLeaveDays, 2);

        $encashableLeaveDays = round($earnedLeaveDays / 2, 2);
        $encashableAmount = round($encashmentValue / 2, 2);

        $totalEncashedDays = EarnedLeave::where('employee_id', $employee->id)->sum('encashed_leave_days');

        if ($totalEncashedDays >= 60) {
            return ['message' => 'Employee has already encashed the maximum limit of 60 days.'];
        }

        if (($totalEncashedDays + $encashableLeaveDays) > 60) {
            $encashableLeaveDays = 60 - $totalEncashedDays;
            $encashableAmount = round($dailySalary * $encashableLeaveDays, 2);
        }

        $earnedLeave = EarnedLeave::updateOrCreate(
            ['employee_id' => $employee->id, 'year' => $year],
            [
                'total_present_days' => $presentDays,
                'earned_leave_days' => $earnedLeaveDays,
                'encashed_leave_days' => $encashableLeaveDays,
                'encashed_amount' => $encashableAmount,
            ]
        );

        return [
            'earned_leave' => $earnedLeave,
            'message' => 'Earned leave calculated and updated successfully.'
        ];
    }
}
