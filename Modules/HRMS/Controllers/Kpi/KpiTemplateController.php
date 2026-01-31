<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\KpiTemplate;
use Modules\HRMS\Services\Kpi\KpiTemplateService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Kpi\ResponsibilityEntry;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class KpiTemplateController extends Controller
{
    /**
     * Service variable
     *
     * @var KpiTemplateService
     */
    private $service;
    function __construct(KpiTemplateService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['kpiTemplates'] = $this->service->getAll();

        return view('HRMS::kpi.kpi-templates.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['departments'] = Department::all();
        $data['designations'] = Designation::all();
        $data['responsibilities'] = ResponsibilityEntry::where('status', 'Active')->get();
        return view('HRMS::kpi.kpi-templates.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'status' => 'required|string|in:Active,Inactive',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.time' => 'required|numeric|min:0',
            'responsibilities.*.frequency' => 'required|string|in:Day,Month,Year',
        ]);

        // ✅ Custom duplicate check
        $exists = KpiTemplate::where('department_id', $validate['department_id'])
            ->where('designation_id', $validate['designation_id'])
            ->where('status', 'Active')
            ->exists();

        if ($exists && $validate['status'] === 'Active') {
            return back()
                ->withErrors(['status' => 'An active KPI Template already exists for this department and designation.'])
                ->withInput();
        }

        $this->service->store($validate);

        return redirect()->route('hrm.kpis.kpi-templates.index')
            ->with('success', 'KPI Template created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['kpiTemplate'] = $this->service->show($id);

        return view('HRMS::kpi.kpi-templates.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KpiTemplate $kpiTemplate)
    {
        $data['kpiTemplate'] = $kpiTemplate;
        $data['departments'] = Department::all();
        $data['designations'] = Designation::all();
        $data['responsibilities'] = ResponsibilityEntry::where('status', 'Active')->get();

        return view('HRMS::kpi.kpi-templates.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KpiTemplate $kpiTemplate)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'status' => 'required|string|in:Active,Inactive',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.time' => 'required|numeric|min:0',
            'responsibilities.*.frequency' => 'required|string|in:Day,Month,Year',
        ]);

        $this->service->update($kpiTemplate, $validated);

        return redirect()->route('hrm.kpis.kpi-templates.index')->with('success', 'KPI Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiTemplate $kpiTemplate)
    {
        $this->service->delete($kpiTemplate);
        return redirect()->route('hrm.kpis.kpi-templates.index')->with('success', 'KpiTemplate deleted successfully.');
    }
}
