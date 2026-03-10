<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;

class LeaveEncashmentProcessController extends Controller
{
    public function index()
    {
        // Using a compact selection for performance
        $employees = Employee::select('id', 'name', 'employee_code')->get();
        return view('HRMS::leave-encashment-process.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'process_type' => 'required|in:employee_wise,group_wise',
            'employee_id'  => 'required_if:process_type,employee_wise',
        ]);

        // Logic placeholder:
        // 1. Check leave balance for the selected employee/group
        // 2. Calculate amount based on (Salary/Days * Leaves)
        // 3. Save to a 'leave_encashments' table

        return redirect()->route('leaveEncashmentProcess.index')
            ->with('success', 'Leave Encashment has been processed successfully.');
    }
}
