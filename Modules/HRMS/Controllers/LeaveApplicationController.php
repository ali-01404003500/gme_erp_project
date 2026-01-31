<?php

namespace Modules\HRMS\Controllers;

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
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['employees'] = Employee::all();
        $data['leaveTypes'] = LeaveType::all();
        $data['leaveApplications'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('HRMS::leave.indexView', $data)->render();

            // Set Dompdf options
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
        $data['employees'] = Employee::all();

        $data['leaveTypes'] = LeaveType::all();
        
        return view('HRMS::leave.create', $data);	
    }

    public function getLeaveResponse(Request $request){
        $employee   = $request->employee;
        $leave_type = $request->leave_type;

        $companyLeaveType   = LeaveType::query()->find($leave_type);

        $balance =  LeaveApplication::query()->where('employee_id', $employee)->where('leave_type_id', $leave_type)->where('approved_by', '!=', null)->get()->sum('day_count');

        $data['companyLeaveType'] =  $companyLeaveType;

        $data['leaveBalance'] = $companyLeaveType->total_day - $balance??0;

        return response()->json($data);
    }

    public function recommended(Request $request, $id){
       LeaveApplication::find($id)->update([
        'recommended_by' => auth()->user()->id,
        'recommended_comments' => $request->recommended_comments,
        'status' => 'recommended',
    ]);

    return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication updated successfully.');
    }

    public function approved(Request $request, $id){
        LeaveApplication::find($id)->update([
            'approved_by' => auth()->user()->id,
            'approved_comments' => $request->approved_comments,
            'status' => 'approved',
        ]);
 
        return redirect()->route('hrm.leaves.index')->with('success', 'LeaveApplication updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
        $request->merge(['from_date' => $from_date]);
        $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
        $request->merge(['to_date' => $to_date]);
           
    
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
    
        $result = $this->service->store($validate);

        $this->generalNotificationService->store([
            'title' => 'New Leave Application',
            'description' => 'New Leave Application Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(LeaveApplicationController::class, 'approve', [$result['employee']->id]),
         ],$this->generalNotificationService->getPermittedUsers('hrm.leaves.approve'));

        return redirect()->route('hrm.leaves.edit', $result['employee']->id)->with('success', 'LeaveApplication created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['leaveApplication'] = $this->service->show($id);

        return view("HRMS::leave.show", $data);
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
        'file_uploads.*' => 'string',
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
