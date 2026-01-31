<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Services\LeaveEncashmentService;

class LeaveEncashmentController extends Controller
{
    protected $leaveEncashmentService;

    public function __construct(LeaveEncashmentService $leaveEncashmentService)
    {
        $this->leaveEncashmentService = $leaveEncashmentService;
    }

    public function calculate(Request $request, $employeeId)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $result = $this->leaveEncashmentService->calculateEarnedLeave($employee, $request->year);

        return response()->json($result);
    }
}
