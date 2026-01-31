<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;
use Modules\HRMS\Models\Settings\Holiday;
use Modules\HRMS\Models\Settings\Shift;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\AttendanceReportService;
use Illuminate\Http\Request;
use Modules\Inventory\Services\ExportService;

class AttendanceReportController extends Controller
{

    /**
     * Service variable
     *
     * @var AttendanceReportService
     */
    private $service; 
    function __construct(AttendanceReportService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
   
    public function dailyReport(Request $request)
    {
        // Default to today's date if not provided
        $date = $request->date ?? \Carbon\Carbon::now()->format('Y-m-d');

        $query = Attendance::with([
            'employee.employementDetail.branch',
            'employee.employementDetail.department',
            'employee.employementDetail.designation',
            'shift'
        ])->whereDate('date', $date); // Apply date filter

        // Filter by branch
        if ($request->branch) {
            $query->whereHas('employee.employementDetail', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        // Filter by department
        if ($request->department) {
            $query->whereHas('employee.employementDetail', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        // Filter by designation
        if ($request->designation) {
            $query->whereHas('employee.employementDetail', function($q) use ($request) {
                $q->where('designation_id', $request->designation);
            });
        }

        // Filter by specific employee
        if ($request->employee_id) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('id', $request->employee_id);
            });
        }

        // Filter by attendance_type
        if ($request->attendance_type === 'Late') {
            // Get all results first, then filter manually
            $attendances = $query->get()->filter(function($attendance) {
                $shift = $attendance->shift ?? \Modules\HRMS\Models\Settings\Shift::find(10000);

                if (!$attendance->check_in_time || !$shift) {
                    return false;
                }

                $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                $shiftInTime = \Carbon\Carbon::parse($shift->in_time);
                $graceTime = $shift->grace_time ?? 0;

                return $checkIn->diffInMinutes($shiftInTime) > $graceTime;
            });

            $data['attendanceReports'] = $attendances;
        } elseif ($request->attendance_type) {
            // Filter directly for other types like Present, Absent, etc.
            $query->where('attendance_type', $request->attendance_type);
            $data['attendanceReports'] = $query->get();
        } else {
            // No attendance_type filter, just fetch all
            $data['attendanceReports'] = $query->get();
        }
        

        // Load other necessary data
        $data['employees'] = Employee::all();
        $data['company_info'] = CompanyInfo::first();
        $data['departments'] = Department::all();
        $data['designations'] = Designation::all();
        $data['branches'] = Branch::all();
        $data['shifts'] = Shift::where('status', 1)->get();
        $data['holidays'] = Holiday::get();
        if ($request->filled('export_type')) {
            $filename = 'Attendance_Report_ ' . today()->format(date('Y-m-d'), 'Y_m_d');
            return (new ExportService())->exportData($data, 'HRMS::attendance.reports.daily-report-export.', $filename);
        }
        return view("HRMS::attendance.reports.daily-report", $data);
    }
   public function monthlyReport(Request $request)
{
    $from =  Carbon::parse(request('from') ?? Carbon::now()->startOfMonth())->format('Y-m-d');
    $to =  Carbon::parse(request('to') ?? Carbon::now()->endOfMonth())->format('Y-m-d');

    $query = Attendance::with([
        'employee.employementDetail.branch',
        'employee.employementDetail.department',
        'employee.employementDetail.designation',
        'entryBy',
        'shift'
    ])->whereBetween('date', [$from, $to]);

    // Apply filters
    if ($request->branch) {
        $query->whereHas('employee.employementDetail', function ($q) use ($request) {
            $q->whereIn('branch_id', $request->branch);
        });
    }

    if ($request->department) {
        $query->whereHas('employee.employementDetail', function ($q) use ($request) {
            $q->whereIn('department_id', $request->department);
        });
    }

    if ($request->designation) {
        $query->whereHas('employee.employementDetail', function ($q) use ($request) {
            $q->whereIn('designation_id', $request->designation);
        });
    }

    if ($request->employee_id) {
        $query->whereIn('employee_id', $request->employee_id);
    }

     if ($request->attendance_type && $request->attendance_type !== 'Late') {
        $query->where('attendance_type', $request->attendance_type);
    }

    $attendances = $query->get();

    // Group and process data
    $grouped = $attendances->groupBy('employee_id')->map(function ($records) {
        $employee = $records->first()->employee;
        // dd($employee);
        return [
            'employee' => $employee,
            'present_days' => $records->where('attendance_type', 'Present')->count(),
            'absent_days' => $records->where('attendance_type', 'Absent')->count(),
            'late_days' => $records->filter(function ($rec) {
                if (!$rec->check_in_time) return false;
                $shift = $rec->shift;
                if (!$shift) return false;
                $graceTime = $shift->grace_time ?? 0;
                $checkIn = \Carbon\Carbon::parse($rec->check_in_time);
                $shiftInTime = \Carbon\Carbon::parse($shift->in_time);
                return $checkIn->diffInMinutes($shiftInTime) > $graceTime;
            })->count(),
            'leave_days' => $records->where('attendance_type', 'Leave')->count(),
            'holy_days' => $records->whereIn('attendance_type', ['Holiday', 'Weekend'])->count(),
            'total_days' => $records->count()
        ];
    })->when($request->attendance_type == 'Late', function ($collection) {
        return $collection->filter(function ($item) {
            return $item['late_days'] > 0;
        });
    });

    // Data to view
    $data['groupedStats'] = $grouped;

    $data['employees'] = Employee::all();
    $data['company_info'] = CompanyInfo::first();
    $data['departments'] = Department::all();
    $data['designations'] = Designation::all();
    $data['branches'] = Branch::all();

    if ($request->filled('export_type')) {
        $filename = 'Monthly_Attendance_Report_' . now()->format('Y_m');
        return (new ExportService())->exportData($data, 'HRMS::attendance.reports.monthly-report-export.', $filename);
    }

    return view("HRMS::attendance.reports.monthly-report", $data);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendanceReports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('attendanceReports.index')->with('success', 'AttendanceReport created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['attendanceReport'] = $this->service->show($id);

        return view("attendanceReports.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceReport $attendanceReport)
    {
        $data['attendanceReport'] = $attendanceReport;
        //
        return view("attendanceReports.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceReport $attendanceReport)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($attendanceReport, $validate);

        return redirect()->route('attendanceReports.index')->with('success', 'AttendanceReport updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceReport $attendanceReport)
    {
        $this->service->delete($attendanceReport);
        return redirect()->route('attendanceReports.index')->with('success', 'AttendanceReport deleted successfully.');
    }
}
