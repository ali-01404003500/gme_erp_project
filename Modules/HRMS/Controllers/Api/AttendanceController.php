<?php

namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Services\AttendanceService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use Modules\HRMS\Models\LeaveApplication;
use Modules\HRMS\Models\LeaveAllocation; // Assuming this model exists
use Modules\HRMS\Models\Settings\LeaveType;

class AttendanceController extends Controller
{

    /**
     * Service variable
     *
     * @var AttendanceService
     */
    protected $service;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->service = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data['attendances'] = $this->service->getAll(auth()->user()->employee->id);

            return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required',
            'remarks' => 'nullable',

            'check_in_date' => 'nullable|date_format:Y-m-d',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_in_latitude' => 'nullable',
            'check_in_longitude' => 'nullable',
            'check_out_date' => 'nullable|date_format:Y-m-d',
            'check_out_time' => 'nullable|date_format:H:i',
            'check_out_latitude' => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type' => 'nullable',
        ]);
        try {
            // dd($validate);
            $result = $this->service->store($validate);
            return response()->json([
                'data' => $result,
                'status' => true,
                'message' => 'Attendance created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
    /**
     * Handle check-in and check-out requests from the mobile app.
     * 
     * This endpoint expects the following parameters:
     *  - latitude: The latitude of the employee's current location.
     *  - longitude: The longitude of the employee's current location.
     *  - remarks: An optional string containing any additional remarks.
     * 
     * The endpoint will return a JSON response with the following structure:
     *  - data: The Attendance object that was created or updated.
     *  - status: A boolean indicating whether the request was successful.
     *  - message: A string containing a message about the result of the request.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAttendance(Request $request)
    {
        $employeeId = auth()->user()->employee->id;
        if (!$employeeId) {
            return response()->json(['status' => false, 'message' => 'Employee ID is required.'], 422);
        }

        $today = date('Y-m-d');
        $currentTime = date('H:i');
        $yearStart = Carbon::now()->startOfYear()->format('Y-m-d');

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            // First request of the day: record check-in
            $attendance = Attendance::create([
                'employee_id'        => $employeeId,
                'date'               => $today,
                'check_in_date'      => $today,
                'check_in_time'      => $currentTime,
                'check_in_latitude'  => $request->input('latitude'),
                'check_in_longitude' => $request->input('longitude'),
                'remarks'            => $request->input('remarks', 'Check-in recorded'),
                'shift_id'           => $request->input('shift_id')?? 10000,
            ]);
            return response()->json([
                'data'    => $this->service->getAttendanceMetrics($employeeId, $yearStart, $today),
                'status'  => 201,
                'message' => 'Check-in recorded.'
            ]);
        } else {
            if(!$attendance->check_in_time) {
                // First request of the day: record check-in
                $attendance->update([
                    'check_in_date'      => $today,
                    'check_in_time'      => $currentTime,
                    'check_in_latitude'  => $request->input('latitude'),
                    'check_in_longitude' => $request->input('longitude'),
                    'remarks'            => $request->input('remarks', $attendance->remarks . ' | Check-in recorded'),
                    'attendance_type'    => 'Present',
                    'shift_id'           => $request->input('shift_id')?? 10000,

                ]);
                return response()->json([
                    'data'    => $this->service->getAttendanceMetrics($employeeId, $yearStart, $today),
                    'status'  => 200,
                    'message' => 'Check-in recorded.'
                ]);
            }else if (!$attendance->check_out_time) {
                // Last request of the day: record check-out
                $attendance->update([
                    'check_out_date'      => $today,
                    'check_out_time'      => $currentTime,
                    'check_out_latitude'  => $request->input('latitude'),
                    'check_out_longitude' => $request->input('longitude'),
                    'remarks'             => $request->input('remarks', $attendance->remarks . ' | Check-out recorded'),
                    'shift_id'           => $request->input('shift_id')?? 10000,

                ]);
                return response()->json([
                    'data'    => $this->service->getAttendanceMetrics($employeeId, $yearStart, $today),
                    'status'  => 200,
                    'message' => 'Check-out recorded.'
                ]);
            }
            return response()->json([
                'data'    => $this->service->getAttendanceMetrics($employeeId, $yearStart, $today),
                'status'  => 200,
                'message' => 'Both check-in and check-out have already been recorded for today.'
            ]);
        }
    }




    /**
     * Get the two day attendance status with check-in and check-out flags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodayAttendanceStatus()
    {
        $employeeId = auth()->user()->employee->id;
        if (!$employeeId) {
            return response()->json([
                'status'  => 200,
                'message' => 'Employee ID is required.'
            ], 422);
        }

        $today = date('Y-m-d');
        $yearStart = Carbon::now()->startOfYear()->format('Y-m-d');

        $data = $this->service->getAttendanceMetrics($employeeId, $yearStart, $today);

        return response()->json([
            'status' => 200,
            'message' => 'Success.',
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data['attendance'] = $this->service->show($id);

            return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required',
            'remarks' => 'nullable',

            'check_in_date' => 'nullable',
            'check_in_time' => 'nullable',
            'check_in_latitude' => 'nullable',
            'check_in_longitude' => 'nullable',
            'check_out_date' => 'nullable',
            'check_out_time' => 'nullable',
            'check_out_latitude' => 'nullable',
            'check_out_longitude' => 'nullable',
            'attendance_type' => 'nullable',
        ]);
        $validate['attendance_type'] = 'Present';

        $result = $this->service->update($attendance, $validate);

        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'Attendance updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->service->delete($attendance);
        return response()->json(['success' => true, 'message' => 'Attendance deleted successfully.']);
    }

    public function getJobCardList(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        try {
            $data = $this->service->getJobCardAttendanceList(
                $request->employee_id,
                $request->from_date,
                $request->to_date
            );

            return response()->json([
                'status' => true,
                'message' => 'Job card attendance list retrieved successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving job card list: ' . $e->getMessage()
            ], 500);
        }
    }

}
