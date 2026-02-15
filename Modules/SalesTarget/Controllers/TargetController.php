<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Services\TargetService;
use Modules\SalesTarget\Models\Target;
use Illuminate\Support\Facades\DB;


class TargetController extends Controller
{
    protected $targetService;


    // constructor injection
    public function __construct(TargetService $targetService)
    {
        $this->targetService = $targetService;
    }

    // Display a listing of the resource.
    public function index()
    {
        $targets = $this->targetService->getAllTargets();
        return view('SalesTarget::settings.target.index', compact('targets'));
    }


    // Show the form for creating a new resource.
    public function create()
    {
        $employees = $this->targetService->getAllEmployees();
        return view('SalesTarget::settings.target.create', compact('employees'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'targets' => 'required|array',
            'targets.*.employee_id' => 'required|exists:users,id',
            'targets.*.year' => 'required|digits:4',
            'targets.*.total_target' => 'required|numeric',
        ]);

        $this->targetService->storeMultipleTargets($request->targets);

        return redirect()->route('sales_target.settings.target.index')
            ->with('success', 'Target Matrix saved successfully!');
    }


    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $target = Target::with('employee')->findOrFail($id);
        return view('SalesTarget::settings.target.edit', compact('target'));
    }


    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|digits:4',
            'total_target' => 'required|numeric',
        ]);

        $data = $request->all();
        //Map the ID to target_id so the service finds it correctly
        $data['target_id'] = $id;

        $this->targetService->storeMultipleTargets([$data]);

        return redirect()->route('sales_target.settings.target.index')
            ->with('success', 'Target updated successfully!');
    }


    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $this->targetService->deleteTarget($id);
        return redirect()->back()->with('success', 'Target record deleted successfully!');
    }

    public function achievement(Request $request)
    {
        // সকল এমপ্লয়ি সংগ্রহ (সার্ভিস থেকে)
        $employees = $this->targetService->getAllEmployees();

        $selectedEmployeeId = $request->get('user_ref_id');
        $selectedYear = $request->get('year', date('Y'));

        $results = [];

        if ($selectedEmployeeId) {
            // সার্ভিসের মেথড কল করে ক্যালকুলেশন রেজাল্ট আনা
            $results = $this->targetService->getEmployeeAchievement($selectedEmployeeId, $selectedYear);
        }

        return view('SalesTarget::perfomence.achievement', compact('employees', 'results', 'selectedEmployeeId'));
    }
}
