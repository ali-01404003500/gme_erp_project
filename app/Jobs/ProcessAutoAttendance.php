<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveApplication;
use Modules\HRMS\Models\Settings\Holiday;

class ProcessAutoAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Set the timezone for all date-related operations
        $timezone = 'Asia/Dhaka';
        $today = Carbon::now($timezone)->toDateString();
        $employees = Employee::where('status', 1)->get();
    
        // Check if today is a holiday
        $isHoliday = Holiday::where('day_type', 1)
                            ->whereDate('start_date', '<=', $today)
                            ->whereDate('end_date', '>=', $today)
                            ->exists();
    
        // Check if today is a weekend
        $isWeekend = Holiday::where('day_type', 2)
                            ->get()
                            ->contains(function ($holiday) use ($timezone) {
                                $dayNames = explode(',', $holiday->day_name); // Split comma-separated day names
                                return in_array(Carbon::now($timezone)->format('l'), $dayNames); // Check if current day is in the list
                            });
    
        foreach ($employees as $employee) {
            // Check if employee is on leave
            $onLeave = LeaveApplication::where('employee_id', $employee->id)
                            ->where('status', 'Approved') // Ensure the status is 'Approved'
                            ->whereDate('from_date', '<=', $today)
                            ->whereDate('to_date', '>=', $today)
                            ->exists();
    
            // Set attendance type
            if ($isHoliday) {
                $attendanceType = 'Holiday';
            } elseif ($isWeekend) {
                $attendanceType = 'Weekend';
            } elseif ($onLeave) {
                $attendanceType = 'Leave';
            } else {
                $attendanceType = 'Absent';
            }
    
            // Create or update attendance record
            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $today], 
                ['attendance_type' => $attendanceType]
            );
        }
    }
}