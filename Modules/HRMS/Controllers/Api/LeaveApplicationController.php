<?php

namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveApplication;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\LeaveApplicationService;
use App\Services\Notifications\GeneralNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Dompdf\Dompdf;
use Dompdf\Options;
class LeaveApplicationController extends Controller
{

    /**
     * Service variable
     *
     * @var LeaveApplicationService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(LeaveApplicationService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->middleware(middleware: 'permited')->except(methods: ['leaveTypes', 'getLeaveResponse', 'recommended', 'approved','dashboard']);
        $this->middleware(middleware: 'permitedSlug:hrm.leaves.recommended')->only(methods: ['recommended']);
        $this->middleware(middleware: 'permitedSlug:hrm.leaves.approved')->only(methods: ['approved']);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data['leaveApplications'] = LeaveApplication::where('employee_id', auth()->user()->employee->id)
                ->orderBy('created_at', 'desc')
                ->with(['employee', 'leaveType'])
                ->searchByFields(['status'])
                ->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
                    $fromDate = Carbon::parse($request->input('from'))->format('Y-m-d');
                    $toDate = Carbon::parse($request->input('to'))->format('Y-m-d');

                    $query->where(function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('from_date', [$fromDate, $toDate])
                            ->orWhereBetween('to_date', [$fromDate, $toDate])
                            ->orWhere(function ($query) use ($fromDate, $toDate) {
                                $query->where('from_date', '<=', $fromDate)
                                    ->where('to_date', '>=', $toDate);
                            });
                    });
                })
                ->paginate(10);

            return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ]);
        }
    }

    public function dashboard(Request $request)
    {
        try {
          

            $leave_types = LeaveType::all();
            foreach ($leave_types as $leave_type) {
                $companyLeaveType = LeaveType::query()->find($leave_type->id);
                $usedLeaves = LeaveApplication::query()->where('employee_id', auth()->user()->employee->id)->where('leave_type_id', $leave_type->id)->where('approved_by', '!=', null)->get()->sum('day_count');
                $data['leaveTypes'][] = [
                    'id' => $leave_type->id,
                    'name' => $leave_type->leave_type_name,
                    'total_day' => $companyLeaveType->total_day,
                    'used' => $usedLeaves ?? 0,
                    'remaining' => $companyLeaveType->total_day - $usedLeaves ?? 0,
                ];
            }

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

    public function leaveTypes(Request $request)
    {
        try {
            $data['leaveTypes'] = LeaveType::all();
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


    public function getLeaveResponse(Request $request)
    {
        try {

            $leave_type = $request->leave_type_id;

            $companyLeaveType = LeaveType::query()->find($leave_type);

            $balance = LeaveApplication::query()->where('employee_id', auth()->user()->employee->id)->where('leave_type_id', $leave_type)->where('approved_by', '!=', null)->get()->sum('day_count');

            $data['companyLeaveType'] = $companyLeaveType;

            $data['leaveBalance'] = $companyLeaveType->total_day - $balance ?? 0;

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

    public function recommended(Request $request)
    {
        try {
            $leaveApplication = LeaveApplication::find($request->leave_application_id);
            $leaveApplication->update([
                'recommended_by' => auth()->user()->id,
                'recommended_comments' => $request->recommended_comments,
                'status' => $request->status,
            ]);

            return response()->json([
                'data' => $leaveApplication,
                'status' => true,
                'message' => 'LeaveApplication updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function approved(Request $request)
    {
        try {
            $leaveApplication = LeaveApplication::find($request->leave_application_id);
            $leaveApplication->update([
                'approved_by' => auth()->user()->id,
                'approved_comments' => $request->approved_comments,
                'status' => $request->status,
            ]);

            return response()->json([
                'data' => $leaveApplication,
                'status' => true,
                'message' => 'LeaveApplication updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'from_date' => 'nullable',
                'from_date_leave_count' => 'required',
                'to_date' => 'nullable',
                'to_date_leave_count' => 'required',
                'day_count' => 'required|integer',
                'remarks' => 'required|string',
                'file_uploads' => 'nullable|array|min:1',
                'file_uploads.*' => 'string',
            ]);

            $employeeId = auth()->user()->employee->id;

            $leaveApplication = LeaveApplication::create([
                'employee_id' => $employeeId,
                'leave_type_id' => $validated['leave_type_id'],
                'from_date' => $validated['from_date'],
                'from_date_leave_count' => $validated['from_date_leave_count'],
                'to_date' => $validated['to_date'],
                'to_date_leave_count' => $validated['to_date_leave_count'],
                'day_count' => $validated['day_count'],
                'remarks' => $validated['remarks'],
                'file_uploads' => $request->file_uploads
            ]);

            return response()->json([
                'data' => $leaveApplication,
                'status' => true,
                'message' => 'LeaveApplication created successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['leaveApplication'] = $this->service->show($id);

        return response()->json([
            'data' => $data,
            'status' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $data['leave'] = $leaveApplication;
        $data['employees'] = Employee::all();
        $data['leaveTypes'] = LeaveType::all();
        return view("HRMS::leave.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
        $request->merge(['from_date' => $from_date]);
        $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
        $request->merge(['to_date' => $to_date]);

        $leaveApplication = LeaveApplication::find($id);
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'nullable|date_format:Y-m-d',
            'from_date_leave_count' => 'required',
            'to_date' => 'nullable|date_format:Y-m-d',
            'to_date_leave_count' => 'required',
            'day_count' => 'required|integer',
            'remarks' => 'required|string',
            'file_uploads' => 'nullable|array|min:1',
            'file_uploads.*' => 'nullable|mimes:doc,docx,pdf,jpg,jpeg,png|max:20480',
        ]);
        $result = $this->service->update($leaveApplication, $validate);

        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'LeaveApplication updated successfully'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveApplication $leaveApplication)
    {
        $this->service->delete($leaveApplication);
        return response()->json([
            'data' => [],
            'status' => true,
            'message' => 'LeaveApplication deleted successfully'
        ]);
    }
}
