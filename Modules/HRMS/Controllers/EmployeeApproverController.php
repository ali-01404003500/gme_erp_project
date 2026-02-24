<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $employeeId = $request->get('employee_id');
        $employee = null;
        $currentApprovers = collect();
        $availableApprovers = collect();

        $employees = Employee::where('status', 1)->orderBy('full_name')->get(['id', 'full_name', 'epf_number']);

        if ($employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee) {
                $currentApprovers = $this->approverService->getCurrentApprovers($employeeId);
                $availableApprovers = $this->approverService->getAvailableApprovers($employeeId);
            }
        }

        return view('HRMS::settings.approver-setup.index', compact(
            'employees', 'employee', 'currentApprovers', 'availableApprovers'
        ));
    }

    public function store(Request $request)
    {

        // ১. Remove Action

        $request->validate([
            'employee_id'=> 'required',
            'approver_ids.*'=> 'required',  //* ta hocche array. 
        ]);
       

    

        $result = $this->approverService->addApprovers($request->employee_id, (array)$request->approver_id);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
