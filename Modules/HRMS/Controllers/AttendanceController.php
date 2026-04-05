<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Holiday;
use Modules\HRMS\Models\Settings\Shift;
use Modules\HRMS\Services\AttendanceService;
use Modules\Inventory\Services\ExportService;

class AttendanceController extends Controller
{

    /**
     * Service variable
     *
     * @var AttendanceService
     */
    private $service;
    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['employees'] = Employee::query()
            ->when(request()->filled('employee_id'), function ($q) {
                $q->where('id', request('employee_id'));
            })
            ->when(request()->filled('department_id'), function ($q) {
                $q->whereHas('employementDetail', function ($qr) {
                    $qr->where('department_id', request('department_id'));
                });
            })
            ->when(request()->filled('branch_id'), function ($q) {
                $q->whereHas('employementDetail', function ($qr) {
                    $qr->where('branch_id', request('branch_id'));
                });
            })
            ->with('employementDetail')
            ->get();

        $data['departments']  = Department::whereIn('status', [1])->orderBy('code', 'asc')->get();
        $data['branches']     = Branch::whereIn('branch_type_id', [1, 2])->get();
        $data['company_info'] = CompanyInfo::first();

        $from = request('from') ? Carbon::createFromFormat('Y-m-d', request('from'))->format('Y-m-d') : date('Y-m-01');
        $to   = request('to') ? Carbon::createFromFormat('Y-m-d', request('to'))->format('Y-m-d') : date('Y-m-d');

        $data['period'] = CarbonPeriod::create($from, $to);

        // Get employees attendances from your service
        $data['attendances'] = $this->service->getAll($request->employee_id ?? null);

        // Optional: group attendances by employee and date for easier lookup in Blade
        $data['attendancesByEmployee'] = $data['attendances']
            ->groupBy('employee_id')
            ->map(function ($employeeAttendances) {
                return $employeeAttendances->keyBy('date');
            });

        // EXPORT
        if ($request->filled('export_type')) {

            // Get ALL filtered data (no pagination)
            $data['attendances'] = $this->service->getAllForExport(
                $request->employee_id ?? null
            );

            $filename = 'Attendance_List_' . now()->format('Y_m_d');

            return (new ExportService())
                ->exportData($data, 'HRMS::attendance.export.', $filename);
        }

        // Weekend check
        $data['isWeekend'] = Holiday::where('day_type', 2)
            ->get()
            ->contains(function ($holiday) {
                $dayNames = explode(',', $holiday->day_name);
                return in_array(Carbon::now()->format('l'), $dayNames);
            });

        return view("HRMS::attendance.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['shifts']    = Shift::where('status', 1)->get();
        $data['employees'] = Employee::all();
        return view('HRMS::attendance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'date'                => 'required',

            'check_in_date'       => 'nullable',
            'check_in_time'       => 'nullable',
            'check_in_remarks'    => 'nullable',
            'check_in_latitude'   => 'nullable',
            'check_in_longitude'  => 'nullable',

            'check_out_date'      => 'nullable',
            'check_out_time'      => 'nullable',
            'check_out_remarks'   => 'nullable',
            'check_out_latitude'  => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type'     => 'nullable',

        ]);

        // Convert 12-hour inputs to 24-hour TIME format
        if (! empty($validate['check_in_time'])) {
            $validate['check_in_time'] = Carbon::createFromFormat('h:i A', $validate['check_in_time'])
                ->format('H:i');
        }

        if (! empty($validate['check_out_time'])) {
            $validate['check_out_time'] = Carbon::createFromFormat('h:i A', $validate['check_out_time'])
                ->format('H:i');
        }

        $validate['attendance_type'] = 'Present';
        $validate['flag']            = $this->service->calculateAttendanceStatus($validate);

        try
        {
            $result = $this->service->store($validate);

            if (isset($result['attendance']) && $result['attendance']->id) {
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Attendance created successfully.',
                    'attendance_id' => $result['attendance']->id,
                    'flag'          => $result['attendance']->flag,
                ]);
            } else {
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Failed to create attendance. Please try again.',
                    'attendance_id' => $result['attendance']->id,
                    'flag'          => $result['attendance']->flag,
                ]);
            }

        } catch (\Exception $e) {
            // Catch any database or service errors
            return response()->json([
                'status'  => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['attendance'] = $this->service->show($id);

        return view("attendances.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $data['shifts']     = Shift::where('status', 1)->get();
        $data['attendance'] = $attendance;
        $data['employees']  = Employee::all();
        return view("HRMS::attendance.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //dd($request->all());
        $validate = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'date'                => 'required',

            'check_in_date'       => 'nullable',
            'check_in_time'       => 'nullable',
            'check_in_remarks'    => 'nullable',
            'check_in_latitude'   => 'nullable',
            'check_in_longitude'  => 'nullable',

            'check_out_date'      => 'nullable',
            'check_out_time'      => 'nullable',
            'check_out_remarks'   => 'nullable',
            'check_out_latitude'  => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type'     => 'nullable',

        ]);

        // Convert 12-hour inputs to 24-hour TIME format
        if (! empty($validate['check_in_time'])) {
            $validate['check_in_time'] = Carbon::createFromFormat('h:i A', $validate['check_in_time'])
                ->format('H:i');
        }

        if (! empty($validate['check_out_time'])) {
            $validate['check_out_time'] = Carbon::createFromFormat('h:i A', $validate['check_out_time'])
                ->format('H:i');
        }

        $validate['attendance_type'] = 'Present';
        $validate['flag']            = $this->service->calculateAttendanceStatus($validate);

        try
        {
            $result = $this->service->update($attendance, $validate);

            if (isset($result['attendance']) && $result->id) {
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Attendance Update successfully.',
                    'attendance_id' => $result->id,
                    'flag'          => $result->flag,
                ]);
            } else {
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Failed to Update attendance. Please try again.',
                    'attendance_id' => $result->id,
                    'flag'          => $result->flag,
                ]);
            }

        } catch (\Exception $e) {
            // Catch any database or service errors
            return response()->json([
                'status'  => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->service->delete($attendance);
        return redirect()->route('hrm.attendances.index')->with('success', 'Attendance deleted successfully.');
    }

}
