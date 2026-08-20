<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SalesTargetService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\SalesTarget\Models\SalesTarget;
use Modules\SalesTarget\Services\SalesTargetService as ServicesSalesTargetService;

class SalesTargetController extends Controller
{
    public function __construct(protected ServicesSalesTargetService $service) {}

    public function index()
    {
        $targets = SalesTarget::with(['employee', 'slab'])->latest()->paginate(20);
 
        $employees = Employee::where('status', '1') ->orderBy('full_name')->get();

        return view('SalesTarget::sales-targets.index', compact('targets', 'employees'));
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer',
            'salary_basis' => 'required|in:basic,gross,allexpenses',
        ]);

        try {
            $target = $this->service->assignTarget(
                $request->employee_id, $request->period_month, $request->period_year, $request->salary_basis
            );
            return back()->with('success', 'Target assign success: ' . $target->target_amount);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function lock(Request $request, SalesTarget $target)
    {
        $this->service->lockTarget($target->employee_id, $target->period_month, $target->period_year, auth()->id());
        return back()->with('success', 'Target lock sucess');
    }

    public function fullHonor(Request $request, SalesTarget $target)
    {
        $this->service->fullHonorOverride($target->employee_id, $target->period_month, $target->period_year, auth()->id());
        return back()->with('success', 'Full salary honor sucess');
    }
}