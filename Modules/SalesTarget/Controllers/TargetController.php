<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Services\TargetService;
use Modules\SalesTarget\Models\Target;

class TargetController extends Controller
{
    protected $targetService;

    public function __construct(TargetService $targetService)
    {
        $this->targetService = $targetService;
    }



    public function index()
    {
        $targets = $this->targetService->getAllTargets();
        return view('SalesTarget::settings.target.index', compact('targets'));
    }



    public function create()
    {
        $employees = $this->targetService->getAllEmployees();
        return view('SalesTarget::settings.target.create', compact('employees'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'targets' => 'required|array',
            'targets.*.employee_id' => 'required|distinct|exists:employees,id',
            'targets.*.year' => 'required|digits:4',
            'targets.*.total_target' => 'required|numeric',
        ]);

        $this->targetService->storeMultipleTargets($request->targets);

        return redirect()->route('sales_target.settings.target.index')
            ->with('success', 'Target Matrix saved successfully!');
    }



    public function edit($id)
    {
        $target = Target::with('employee')->findOrFail($id);
        return view('SalesTarget::settings.target.edit', compact('target'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|digits:4',
            'total_target' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['target_id'] = $id;

        $this->targetService->storeMultipleTargets([$data]);

        return redirect()->route('sales_target.settings.target.index')
            ->with('success', 'Target updated successfully!');
    }



    public function destroy($id)
    {
        $this->targetService->deleteTarget($id);
        return redirect()->back()->with('success', 'Target record deleted successfully!');
    }




    public function achievement(Request $request)
    {
        $employees = $this->targetService->getAllEmployees();
        $selectedEmployeeId = $request->get('user_ref_id');
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $results = $this->targetService->getYearlyPerformanceSummary($startDate, $endDate, $selectedEmployeeId);

        // dd($results); 
        return view('SalesTarget::perfomence.achievement', compact(
            'employees',
            'results',
            'selectedEmployeeId',
            'startDate',
            'endDate'
        ));
    }
}
