<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveApplication;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\LeaveApplicationEmployeeService;
use App\Services\Notifications\GeneralNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\ApprovalRequest;
use Modules\HRMS\Models\LeaveStatus;
use Modules\HRMS\Models\LeaveYear;
use Modules\HRMS\Models\Settings\Holiday;

class LeaveApplicationEmployeeController extends Controller
{

    /**
     * Service variable
     *
     * @var LeaveApplicationEmployeeService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(LeaveApplicationEmployeeService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    { 
        $data['leaveTypes'] = LeaveType::all();
        $data['leaveApplications'] = $this->service->getAll(); 
        $data['company_info'] = CompanyInfo::first();
        
        
       

        return view("HRMS::leave-application-employee.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
                
        $employeeId = Auth::user()->employee->id; 
        $data['employees'] = Employee::where('id', $employeeId)->get();    
        $data['holidays'] = [];
        $data['leaveTypes'] = LeaveType::all();
        
        return view('HRMS::leave-application-employee.create', $data);	
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
        $leaveYearId = LeaveYear::where('year', date('Y'))->value('id');

        $leaveStatus = LeaveStatus::where('employee_id', $request->employee_id)
            ->where('leave_type', $request->leave_type_id)
            ->where('is_active', 1)
            ->where('leave_year_id', $leaveYearId)
            ->first();

        if (!$leaveStatus) {
            return redirect()->route('hrm.leave-application-employees.create')->with('error', 'Leave balance not configured for you.');
        }

        $leaveBalance = $leaveStatus->remaining_balance ?? 0;
        $continuous = $leaveStatus->continuous ?? 0;
        $sanction = $leaveStatus->continuous_sanction ?? 0;


       $leaveCount = LeaveApplication::where('employee_id', $request->employee_id)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->where('leave_year_id', $leaveYearId)
                    ->sum('day_count');

        $remainingLeaveBalance = ($leaveBalance ?? 0) - ($leaveCount ?? 0)  - ($request->total_days ?? 0);


        if( $remainingLeaveBalance < 0 )
            return redirect()->route('hrm.leave-application-employees.create')->with('error', 'Your leave balance exceeds limit of this leave type.');

        if ($continuous == 1 && $request->total_days > $sanction)
            return redirect()->route('hrm.leave-application-employees.create')->with('error', 'Your leave count exceeds continous sanction limit of this leave type.');


        $validate = $request->validate([  
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'nullable|date_format:Y-m-d',
            'from_date_leave_count' => 'required',
            'to_date' => 'nullable|date_format:Y-m-d',
            'to_date_leave_count' => 'required',
            'day_count' => 'required',
            'remarks' => 'required|string',
            'file_uploads' => 'nullable|array|min:1',
            'file_uploads.*' => 'nullable|mimes:doc,docx,pdf,jpg,jpeg,png|max:20480',
        ]);
        $validate['leave_year_id'] = $leaveYearId;
    
        $result = $this->service->store($validate);

        $this->generalNotificationService->store([
            'title' => 'New Leave Application',
            'description' => 'New Leave Application Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(LeaveApplicationEmployeeController::class, 'approve', [$result['employee']->id]),
         ],$this->generalNotificationService->getPermittedUsers('hrm.leave-application-employees.approve'));

        return redirect()->route('hrm.leave-application-employees.edit', $result['employee']->id)->with('success', 'LeaveApplication created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['leave'] = $this->service->show($id);

        return view("HRMS::leave-application-employee.show", $data);
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
        return view("HRMS::leave-application-employee.edit", $data);
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
        'employee_id' => 'required|exists:employees,id',
        'leave_type_id' => 'required|exists:leave_types,id',
        'from_date' => 'nullable|date_format:Y-m-d',
        'from_date_leave_count' => 'required',
        'to_date' => 'nullable|date_format:Y-m-d',
        'to_date_leave_count' => 'required',
        'day_count' => 'required',
        'remarks' => 'required|string',
        'file_uploads' => 'nullable|array|min:1',
        'file_uploads.*' => 'string',
        ]);
        $this->service->update($leaveApplication, $validate);

        return redirect()->route('hrm.leave-application-employees.edit', $leaveApplication->id)->with('success', 'LeaveApplication updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $this->service->delete($leaveApplication);
        return redirect()->route('hrm.leave-application-employees.index')->with('success', 'LeaveApplication deleted successfully.');
    }
}
