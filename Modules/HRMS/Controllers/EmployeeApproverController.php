<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Approver;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Services\ApproverService;

class EmployeeApproverController extends Controller
{
    protected $approverService;

    public function __construct(ApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::find($employeeId);


        $employees = Employee::where('status', 1)->orderBy('full_name')->get(['id', 'full_name', 'epf_number']);
        $approvers = Approver::where('employee_id', $employeeId)->orderBy('hierarchy_level')->get();

        return view('HRMS::settings.approver-setup.index', compact(
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
        $result = $this->approverService->addApprovers( $validate);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
