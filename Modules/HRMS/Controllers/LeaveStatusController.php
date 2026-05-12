<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch as AccessControlBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveGroup;
use Modules\HRMS\Models\LeaveStatus;
use Modules\HRMS\Models\LeaveYear;
use Modules\HRMS\Services\LeaveStatusService;

class LeaveStatusController extends Controller
{
    protected $leaveStatusService;

    public function __construct(LeaveStatusService $service)
    {
        $this->leaveStatusService = $service;
    }

    public function index()
    {
        $employees   = Employee::where('status', 1)->select('id', 'full_name', 'account_number')->get();
        $leaveGroups = LeaveGroup::with(['leaveTypes'])->get();
        $activeLeaveYear = LeaveYear::where('is_closed', false)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        $leaveYearId = $activeLeaveYear?->id;

        $branches    = AccessControlBranch::select('id', 'name')->get();

        $jobBaseColumn = Schema::hasColumn('employees', 'job_base') ? 'job_base' :
        (Schema::hasColumn('employees', 'employment_type') ? 'employment_type' : null);

        $jobBases = collect();
        if ($jobBaseColumn) {
            $jobBases = Employee::distinct()
                ->whereNotNull($jobBaseColumn)
                ->pluck($jobBaseColumn)
                ->map(fn($item) => ['id' => $item, 'name' => $item]);
        }

        $genders = collect();
        if (Schema::hasColumn('employees', 'gender')) {
            $genders = Employee::distinct()
                ->whereNotNull('gender')
                ->pluck('gender')
                ->map(fn($item) => ['id' => $item, 'name' => ucfirst($item)]);
        }

        return view('HRMS::leave-status.index', compact(
            'employees',
            'leaveGroups',
            'leaveYearId',
            'jobBases',
            'branches',
            'genders'
        ));
    }
    public function getEmployeeBalance(Request $request){
       
        $activeLeaveStatuses = LeaveStatus::with('LeaveType')->where('employee_id', $request->employee_id)
            ->where('is_active', 1) 
            ->get();

        return response()->json($activeLeaveStatuses);
    }

    public function store(Request $request)
    {
       

        $validate = $request->validate([
            'type'           => 'required|in:employee_wise,job_wise',
            'leave_group_id' => 'required|exists:leave_groups,id',
            'leave_year_id' => 'required',
            'effective_date' => 'required|date',
            'employee_id'    => 'required_if:type,employee_wise',
            'join_date'      => 'required_if:type,employee_wise|date|before:effective_date',
        ]); 

        

        $leaveBalanceDetails = $request->validate([
            'leave_type.*' => 'required',
            'groupwise_balance.*' => 'required', 
            'remaining_balance.*' => 'required',
            'balance_forwarded.*' => 'required', 
            'max_forward_balance.*' => 'required',
            'continuous.*' => 'required', 
            'continuous_sanction.*' => 'required',
            'half_day.*' => 'required', 
            'max_sanction_per_year.*' => 'required', 
        ]); 
  

        try {
            $this->leaveStatusService->storeLeaveStatus($validate, $leaveBalanceDetails);
            return redirect()->back()->with('success', 'Leave status assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }
}
