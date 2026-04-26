<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Services\Notifications\GeneralNotificationService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Modules\HRMS\Models\ApprovalRequest;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveApplication;
use Modules\HRMS\Models\LeaveStatus;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\LeaveApplicationService;

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

    public function __construct(LeaveApplicationService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service                    = $service;
        $this->generalNotificationService = $generalNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // OPTIMIZED: select only needed columns instead of all
        $data['employees']         = Employee::select('id', 'full_name')->get();
        $data['leaveTypes']        = LeaveType::select('id', 'leave_type_name')->get();
        $data['leaveApplications'] = $this->service->getAll();
        $data['company_info']      = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('HRMS::leave.indexView', $data)->render();

            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('leave_application_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("HRMS::leave.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // OPTIMIZED: select only needed columns
        $data['employees']  = Employee::where('status', 1)->select('id', 'full_name')->get();
        $data['holidays']   = [];
        $data['leaveTypes'] = LeaveType::select('id', 'leave_type_name')->get();

        return view('HRMS::leave.create', $data);
    }

    public function getLeaveResponse(Request $request)
    {
        $employee   = $request->employee;
        $leave_type = $request->leave_type;

        // OPTIMIZED: use select to get only needed columns
        $leaveTypeWiseBalance = LeaveStatus::where('employee_id', $employee)
            ->where('leave_type', $leave_type)
            ->where('is_active', 1)
            ->select('id', 'remaining_balance')
            ->first();

        // OPTIMIZED: use sum() directly instead of get()->sum()
        $leaveTaken = LeaveApplication::where('employee_id', $employee)
            ->where('leave_type_id', $leave_type)
            ->where('approved_by', '!=', null)
            ->sum('day_count');

        $data['leaveTypeWiseBalance'] = $leaveTypeWiseBalance;
        $data['leaveBalance']         = ($leaveTypeWiseBalance->remaining_balance ?? 0) - ($leaveTaken ?? 0);

        return response()->json($data);
    }

    public function recommended(Request $request, $id)
    {
        $approval = ApprovalRequest::find($id);
        $leave    = LeaveApplication::find($approval->reference_id);

        if ($leave->current_level != $approval->level) {
            return redirect()->route('hrm.leaves.index')->with('success', 'Invalid approval step.');
        }

        $approval->update([
            'status'      => 'approved',
            'remarks'     => $request->remarks,
            'approved_at' => now(),
        ]);

        $nextStep = ApprovalRequest::where('reference_id', $leave->id)
            ->where('reference_type', LeaveApplication::class)
            ->where('level', '>', $approval->level)
            ->orderBy('level')
            ->first();

        if ($nextStep) {
            $leave->update([
                'current_level' => $nextStep->level,
                'status'        => 'recomended',
            ]);
        } else {
            $leave->update([
                'status' => 'approved',
            ]);
        }

        return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication updated successfully.');
    }

    public function reject(Request $request, $id)
    {
        $approval = ApprovalRequest::findOrFail($id);

        $approval->update([
            'status' => 'rejected',
        ]);

        $approval->reference->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication updated successfully.');
    }

    public function approved(Request $request, $id)
    {
        LeaveApplication::find($id)->update([
            'approved_by'       => auth()->user()->id,
            'approved_comments' => $request->approved_comments,
            'status'            => 'approved',
        ]);

        return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        $request->merge(['from_date' => $from_date]);

        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d');
        $request->merge(['to_date' => $to_date]);

        $validate = $request->validate([
            'employee_id'           => 'required|exists:employees,id',
            'leave_type_id'         => 'required|exists:leave_types,id',
            'from_date'             => 'nullable|date_format:Y-m-d',
            'from_date_leave_count' => 'required',
            'to_date'               => 'nullable|date_format:Y-m-d',
            'to_date_leave_count'   => 'required',
            'day_count'             => 'required',
            'remarks'               => 'required|string',
            'file_uploads'          => 'nullable|array|min:1',
            'file_uploads.*'        => 'nullable|mimes:doc,docx,pdf,jpg,jpeg,png|max:20480',
        ]);

        $result = $this->service->store($validate);

        $this->generalNotificationService->store([
            'title'       => 'New Leave Application',
            'description' => 'New Leave Application Added needed approval',
            'action'      => $this->generalNotificationService->actionBuilder(LeaveApplicationController::class, 'approve', [$result['employee']->id]),
        ], $this->generalNotificationService->getPermittedUsers('hrm.leaves.approve'));

        return redirect()->route('hrm.leaves.edit', $result['employee']->id)->with('success', 'LeaveApplication created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['leave'] = $this->service->show($id);
        return view("HRMS::leave.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // OPTIMIZED: eager load relationships and select only needed columns
        $leaveApplication = LeaveApplication::with(['employee', 'leaveType'])->find($id);

        $data['leave']      = $leaveApplication;
        $data['employees']  = Employee::select('id', 'full_name')->get();
        $data['leaveTypes'] = LeaveType::select('id', 'leave_type_name')->get();

        return view("HRMS::leave.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        $request->merge(['from_date' => $from_date]);

        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d');
        $request->merge(['to_date' => $to_date]);

        $leaveApplication = LeaveApplication::find($id);

        $validate = $request->validate([
            'employee_id'           => 'required|exists:employees,id',
            'leave_type_id'         => 'required|exists:leave_types,id',
            'from_date'             => 'nullable|date_format:Y-m-d',
            'from_date_leave_count' => 'required',
            'to_date'               => 'nullable|date_format:Y-m-d',
            'to_date_leave_count'   => 'required',
            'day_count'             => 'required',
            'remarks'               => 'required|string',
            'file_uploads'          => 'nullable|array|min:1',
            'file_uploads.*'        => 'string',
        ]);

        $this->service->update($leaveApplication, $validate);

        return redirect()->route('hrm.leaves.edit', $leaveApplication->id)->with('success', 'LeaveApplication updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $this->service->delete($leaveApplication);

        return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication deleted successfully.');
    }
}
