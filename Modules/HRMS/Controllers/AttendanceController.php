<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
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
        $data['employees']    = Employee::all();
        $data['company_info'] = CompanyInfo::first();

        // Normal paginated list
        $data['attendances'] = $this->service->getAll(
            $request->employee_id ?? null
        );

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
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validate = $request->validate([
    //         'employee_id'         => 'required|exists:employees,id',
    //         'date'                => 'required',
    //         'remarks'             => 'nullable',
    //         'shift_id'            => 'nullable|exists:shifts,id',
    //         'check_in_date'       => 'nullable',
    //         'check_in_time'       => 'nullable',
    //         'check_in_latitude'   => 'nullable',
    //         'check_in_longitude'  => 'nullable',
    //         'check_out_date'      => 'nullable',
    //         'check_out_time'      => 'nullable',
    //         'check_out_latitude'  => 'nullable',
    //         'check_out_longitude' => 'nullable',
    //         'attendance_type'     => 'nullable',
    //     ]);
    //     $validate['attendance_type'] = 'Present';
    //     $result                      = $this->service->store($validate);
    //     return redirect()->route('hrm.attendances.edit', $result['attendance']->id)->with('success', 'Attendance created successfully.');
    // }

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
    // public function update(Request $request, Attendance $attendance)
    // {
    //     $validate = $request->validate([
    //         'employee_id'         => 'required|exists:employees,id',
    //         'date'                => 'required',
    //         'remarks'             => 'nullable',
    //         'shift_id'            => 'nullable|exists:shifts,id',
    //         'check_in_date'       => 'nullable',
    //         'check_in_time'       => 'nullable',
    //         'check_in_latitude'   => 'nullable',
    //         'check_in_longitude'  => 'nullable',
    //         'check_out_date'      => 'nullable',
    //         'check_out_time'      => 'nullable',
    //         'check_out_latitude'  => 'nullable',
    //         'check_out_longitude' => 'nullable',
    //         'attendance_type'     => 'nullable',
    //     ]);
    //     $validate['attendance_type'] = 'Present';

    //     $this->service->update($attendance, $validate);

    //     return redirect()->route('hrm.attendances.edit', $attendance->id)->with('success', 'Attendance updated successfully.');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->service->delete($attendance);
        return redirect()->route('hrm.attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'date'                => 'required',
            'remarks'             => 'nullable',
            'shift_id'            => 'nullable|exists:shifts,id',
            'check_in_date'       => 'nullable',
            'check_in_time'       => 'nullable',
            'check_in_latitude'   => 'nullable',
            'check_in_longitude'  => 'nullable',
            'check_out_date'      => 'nullable',
            'check_out_time'      => 'nullable',
            'check_out_latitude'  => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type'     => 'nullable',
            // ..
        ]);

        // REMOVE THIS: $validate['attendance_type'] = 'Present';

        $result = $this->service->store($validate);
        return redirect()->route('hrm.attendances.edit', $result['attendance']->id)
            ->with('success', 'Attendance recorded as ' . $result['attendance']->attendance_type);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validate = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'date'                => 'required',
            'remarks'             => 'nullable',
            'shift_id'            => 'nullable|exists:shifts,id',
            'check_in_date'       => 'nullable',
            'check_in_time'       => 'nullable',
            'check_in_latitude'   => 'nullable',
            'check_in_longitude'  => 'nullable',
            'check_out_date'      => 'nullable',
            'check_out_time'      => 'nullable',
            'check_out_latitude'  => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type'     => 'nullable',
        ]);

        // REMOVE THIS: $validate['attendance_type'] = 'Present';

        $this->service->update($attendance, $validate);

        return redirect()->route('hrm.attendances.edit', $attendance->id)
            ->with('success', 'Attendance updated to ' . $attendance->attendance_type);
    }
}
