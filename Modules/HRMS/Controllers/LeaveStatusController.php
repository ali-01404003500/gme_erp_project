<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch as AccessControlBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveGroup;
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
        $employees   = Employee::select('id', 'full_name', 'account_number')->get();
        $leaveGroups = LeaveGroup::with(['leaveTypes'])->get();
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
            'jobBases',
            'branches',
            'genders'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:employee_wise,job_wise',
            'leave_group_id' => 'required|exists:leave_groups,id',
            'effective_date' => 'required|date',
            'employee_id'    => 'required_if:type,employee_wise',
            'join_date'      => 'required_if:type,employee_wise|date|before:effective_date',
        ]);

        try {
            $this->leaveStatusService->storeLeaveStatus($request->all());
            return redirect()->back()->with('success', 'Leave status assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }
}
