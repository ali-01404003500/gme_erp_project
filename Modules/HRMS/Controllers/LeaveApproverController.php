<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\ApproverStep;
use Modules\HRMS\Models\Employee; 
use Modules\HRMS\Services\LeaveApproverService;

class LeaveApproverController extends Controller
{
    protected $leaveapproverService;

    public function __construct(LeaveApproverService $leaveapproverService)
    {
        $this->leaveapproverService = $leaveapproverService;
    }

    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::find($employeeId);


        $employees = Employee::where('status', 1)->orderBy('full_name')->get(['id', 'full_name', 'epf_number']);

        $approvers = ApproverStep::where('employee_id', $employeeId)->orderBy('hierarchy_level')->get();

        return view('HRMS::settings.leave-approver-setup.index', compact(
            'employees', 'employee','approvers'
        ));
    }

    public function store(Request $request)
    {

        // ১. Remove Action

       $validate = $request->validate([
            'employee_id'=> 'required',
            'approver_ids.*'=> 'required',  //* ta hocche array. 
            'approver_update_id.*'=>'nullable'
        ]);
        $result = $this->leaveapproverService->addApprovers( $validate);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
