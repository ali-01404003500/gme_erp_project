<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Services\TargetService;

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
            'targets.*.employee_id' => 'required|exists:users,id',
            'targets.*.year' => 'required|digits:4',
            'targets.*.total_target' => 'required|numeric',
        ]);

        $this->targetService->storeMultipleTargets($request->targets);

        return redirect()->route('sales_target.settings.target.index')
            ->with('success', 'Target Matrix saved successfully!');
    }

    /**
     * Remove the specified target from storage.
     */
    public function destroy($id)
    {
        $this->targetService->deleteTarget($id);
        return redirect()->back()->with('success', 'Target record deleted successfully!');
    }
}
