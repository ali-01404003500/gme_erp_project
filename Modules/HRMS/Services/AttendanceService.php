<?php
namespace Modules\HRMS\Services;

use Carbon\Carbon;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\AttendancePolicy;
use Modules\HRMS\Models\Settings\Shift;

class AttendanceService
{

    public function getAll(?int $employeeId = null, int $limit = 20)
    {
        return Attendance::query()
            ->when($employeeId, function ($qr) use ($employeeId) {
                $qr->where('employee_id', $employeeId);
            })
            ->searchByFields(['employee_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
            ->paginate($limit);
    }
    public function getAllForExport(?int $employeeId = null)
    {
        return Attendance::query()
            ->when($employeeId, function ($qr) use ($employeeId) {
                $qr->where('employee_id', $employeeId);
            })
            ->searchByFields(['employee_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->whereDate('date', '>=', request('from'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->whereDate('date', '<=', request('to'));
            })
            ->get(); // IMPORTANT: get(), not paginate()
    }

    public function delete(Attendance $attendance)
    {
        $attendance->delete();
    }

    public function show($id)
    {
        return Attendance::findOrFail($id);
    }

    /**
     * Get attendance status and metrics for a given employee within a date range.
     *
     * @param int $employeeId
     * @param string $yearStart
     * @param string $today
     * @return array
     */
    public function getAttendanceMetrics(int $employeeId, string $yearStart, string $today): array
    {
        // Fetch attendance records for the current year until today
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$yearStart, $today])
            ->get();

        // Fetch leave applications for the current year until today
        $leaveApplications = \Modules\HRMS\Models\LeaveApplication::where('employee_id', $employeeId)
            ->where(function ($query) use ($yearStart, $today) {
                $query->whereBetween('from_date', [$yearStart, $today])
                    ->orWhereBetween('to_date', [$yearStart, $today])
                    ->orWhere(function ($query) use ($yearStart, $today) {
                        $query->where('from_date', '<', $yearStart)
                            ->where('to_date', '>', $today);
                    });
            })
            ->get();

        // Calculate attendance metrics
        $presentDays       = $attendances->where('attendance_type', 'Present')->count();
        $absentDays        = $attendances->where('attendance_type', 'Absent')->count();
        $holidayAttendance = $attendances->where('attendance_type', 'Holiday')->count();
        $weekendAttendance = $attendances->where('attendance_type', 'Weekend')->count();

        // Calculate late entries (needs shift information, assuming shift relationship is available on Attendance model)
        $lateEntries = $attendances->filter(function ($rec) {
            if (! $rec->check_in_time || ! $rec->shift) {
                return false;
            }

            $graceTime   = $rec->shift->grace_time ?? 0;
            $checkIn     = Carbon::parse($rec->check_in_time);
            $shiftInTime = Carbon::parse($rec->shift->in_time);
            return $checkIn->greaterThan($shiftInTime->addMinutes($graceTime));
        })->count();

        // Calculate on-time in and out (needs shift information)
        $onTimeIn = $attendances->filter(function ($rec) {
            if (! $rec->check_in_time || ! $rec->shift) {
                return false;
            }

            $graceTime   = $rec->shift->grace_time ?? 0;
            $checkIn     = Carbon::parse($rec->check_in_time);
            $shiftInTime = Carbon::parse($rec->shift->in_time);
            return $checkIn->lessThanOrEqualTo($shiftInTime->addMinutes($graceTime));
        })->count();

        $onTimeOut = $attendances->filter(function ($rec) {
            if (! $rec->check_out_time || ! $rec->shift || ! $rec->shift->out_time) {
                return false;
            }

            $checkOut     = Carbon::parse($rec->check_out_time);
            $shiftOutTime = Carbon::parse($rec->shift->out_time);
            // Assuming on-time out is checking out at or before shift out time (might need adjustment)
            return $checkOut->lessThanOrEqualTo($shiftOutTime);
        })->count();

        // Calculate workings days (total days in range minus weekends and holidays)
        $workings_days = Carbon::parse($yearStart)->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday(); // Excludes weekends
        }, Carbon::parse($today));

        $leaveDaysTaken = $leaveApplications->sum('number_of_days');

        // Calculate remaining leave (requires leave allocation data)
        $remaining_leave = [];
        $employee        = auth()->user()->employee;

        $leave_types = \Modules\HRMS\Models\Settings\LeaveType::all();
        foreach ($leave_types as $leave_type) {
            $companyLeaveType  = \Modules\HRMS\Models\Settings\LeaveType::query()->find($leave_type->id);
            $usedLeaves        = \Modules\HRMS\Models\LeaveApplication::query()->where('employee_id', auth()->user()->employee->id)->where('leave_type_id', $leave_type->id)->where('approved_by', '!=', null)->get()->sum('day_count');
            $remaining_leave[] = [
                'id'                    => $leave_type->id,
                'leave_name'            => $leave_type->leave_type_name,
                'no_of_days_allocation' => $companyLeaveType->total_day,
                'leave_taken'           => $usedLeaves ?? 0,
                'leave_remaining'       => $companyLeaveType->total_day - $usedLeaves ?? 0,
            ];
        }

        $attendanceToday = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        $timezone         = new \DateTimeZone('Asia/Dhaka');
        $today_attendance = [
            'check_in'  => $attendanceToday && $attendanceToday->check_in_time ? Carbon::parse($attendanceToday->check_in_date . ' ' . $attendanceToday->check_in_time)->setTimezone($timezone) : null,
            'check_out' => $attendanceToday && $attendanceToday->check_out_time ? Carbon::parse($attendanceToday->check_out_date . ' ' . $attendanceToday->check_out_time)->setTimezone($timezone) : null,
        ];

        return [
            'today_attendance'   => $today_attendance,
            'workings_days'      => $workings_days,
            'late_entry'         => $lateEntries,
            'present'            => $presentDays,
            'absent'             => $absentDays,
            'on_time_in'         => $onTimeIn,
            'on_time_out'        => $onTimeOut,
            'leave'              => $leaveDaysTaken,
            'remaining_leave'    => $remaining_leave,
            'holiday_attendance' => $holidayAttendance,
            'weekend_attendance' => $weekendAttendance,
        ];
    }
    public function getJobCardAttendanceList(int $employeeId, string $fromDate, string $toDate): array
    {
        // dd($fromDate, $toDate, $employeeId);
        $attendances = Attendance::with('shift')
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'asc')
            ->get();
        // dd($attendances);

        $jobCardData = $attendances->map(function ($attendance) {
            $inTime          = null;
            $outTime         = null;
            $overTime        = 0;
            $lateTimeMinutes = 0;

            // Calculate In Time
            if ($attendance->check_in_date && $attendance->check_in_time) {
                $inTime = Carbon::parse($attendance->check_in_date . ' ' . $attendance->check_in_time)
                    ->format('Y-m-d H:i:s');
            }

            // Calculate Out Time
            if ($attendance->check_out_date && $attendance->check_out_time) {
                $outTime = Carbon::parse($attendance->check_out_date . ' ' . $attendance->check_out_time)
                    ->format('Y-m-d H:i:s');
            }

            // Calculate Late Time in minutes
            if ($attendance->check_in_time && $attendance->shift) {
                $graceTime   = $attendance->shift->grace_time ?? 0;
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $shiftInTime = Carbon::parse($attendance->shift->in_time);
                $allowedTime = $shiftInTime->copy()->addMinutes($graceTime);

                if ($checkInTime->greaterThan($allowedTime)) {
                    $lateTimeMinutes = $checkInTime->diffInMinutes($shiftInTime);
                }
            }

            // Calculate Over Time in minutes
            if ($attendance->check_out_time && $attendance->shift && $attendance->shift->out_time) {
                $checkOutTime = Carbon::parse($attendance->check_out_time);
                $shiftOutTime = Carbon::parse($attendance->shift->out_time);

                if ($checkOutTime->greaterThan($shiftOutTime)) {
                    $overTime = $checkOutTime->diffInMinutes($shiftOutTime);
                }
            }

            return [
                'date'            => $attendance->date,
                'in_time'         => $inTime,
                'out_time'        => $outTime,
                'over_time'       => $overTime,
                'late_time'       => $lateTimeMinutes,
                'attendance_type' => $attendance->attendance_type,
                'remarks'         => $attendance->remarks,
            ];
        });

        // Calculate summary
        $totalOverTime = $jobCardData->sum('over_time');
        $totalLateTime = $jobCardData->sum('late_time');
        $totalPresent  = $jobCardData->where('attendance_type', 'Present')->count();
        $totalAbsent   = $jobCardData->where('attendance_type', 'Absent')->count();

        return [
            'employee_id'     => $employeeId,
            'from_date'       => $fromDate,
            'to_date'         => $toDate,
            'attendance_list' => $jobCardData,
            'summary'         => [
                'total_days'              => $jobCardData->count(),
                'total_present'           => $totalPresent,
                'total_absent'            => $totalAbsent,
                'total_over_time_minutes' => $totalOverTime,
                'total_over_time_hours'   => round($totalOverTime / 60, 2),
                'total_late_time_minutes' => $totalLateTime,
                'total_late_time_hours'   => round($totalLateTime / 60, 2),
            ],
        ];
    }
    public function calculateAttendanceStatus(array $data)
    {
        $policy = AttendancePolicy::where('effective_from', '<=', $data['date'])
            ->orderBy('effective_from', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $dayOfWeek      = Carbon::parse($data['date'])->format('l');
        $policySettings = is_array($policy->day_wise_settings) ? $policy->day_wise_settings : json_decode($policy->day_wise_settings, true);

        $todaySetting  = $policySettings[$dayOfWeek] ?? null;
        $policyInTime  = $todaySetting['in_time'] ?? $policy->in_time;
        $policyOutTime = $todaySetting['out_time'] ?? $policy->out_time;
        $flag          = "";

        $policyTime        = strtotime($policyInTime);
        $delayBufferTime   = strtotime("+{$todaySetting['delay_buffer']} minutes", $policyTime);
        $exDelayBufferTime = strtotime("+{$todaySetting['ex_delay_buffer']} minutes", $policyTime);

        $checkIn = $data['check_in_time'];

        if ($checkIn <= $policyInTime) {
            $flag = "P";
        } else if ($checkIn > $policyInTime && $checkIn <= $delayBufferTime) {
            $flag = "D";
        } else if ($checkIn > $policyInTime && $checkIn <= $exDelayBufferTime) {
            $flag = "E";
        } else {
            $flag = "E";
        }

        return $flag;
    }

    public function store(array $data)
    {

        $data['attendance_type'] = $this->calculateAttendanceStatus($data);
        $result['attendance']    = Attendance::create($data);
        return $result;
    }

    public function update(Attendance $attendance, array $data)
    {

        $data['attendance_type'] = $this->calculateAttendanceStatus($data);
        $attendance->update($data);
        return $attendance;
    }

}
