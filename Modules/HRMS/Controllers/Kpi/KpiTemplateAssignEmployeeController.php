<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\KpiTemplateAssignEmployee;
use Modules\HRMS\Services\Kpi\KpiTemplateAssignEmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Kpi\ResponsibilityEntry;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class KpiTemplateAssignEmployeeController extends Controller
{

    /**
     * Service variable
     *
     * @var KpiTemplateAssignEmployeeService
     */
    private $service; 
    function __construct(KpiTemplateAssignEmployeeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['kpiTemplateAssignEmployees'] = $this->service->getAll();

        return view("HRMS::kpi.kpi-assignments.index", $data);
    }

   
    public function getEmployeeDetails(Request $request)
    {
        $employeeId = $request->input('employee_id');
        return $this->service->getEmployeeAndTemplate($employeeId);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['employees'] = Employee::with('employementDetail')->get();
        $data['responsibilities'] = ResponsibilityEntry::where('status', 'Active')->get();
        return view('HRMS::kpi.kpi-assignments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'preparation_date' => 'required|date',
            'kpi_template_id' => 'nullable|exists:kpi_templates,id',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.responsibility_entry_id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.time' => 'required|numeric|min:0',
            'responsibilities.*.frequency' => 'required|string|in:Day,Month,Year',
        ]);

        try {
            $duplicate = $this->service->checkDuplicate($validated);
            if ($duplicate) {
                return back()->withInput()->with('error', 'This KPI Assignment already exists for the selected employee and period.');;
            }

            $this->service->store($validated);
            return redirect()->route('hrm.kpis.kpi-assignments.index')->with('success', 'KPI Template successfully assigned to employee.', 'Success');;
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error occurred while saving: ' . $e->getMessage(), 'Error');;
        }
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['kpiTemplateAssignEmployee'] = $this->service->show($id);

        return view("HRMS::kpi.kpi-assignments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kpiTemplateAssignEmployee = $this->service->show($id);
        $data['kpiTemplateAssignEmployee'] = $kpiTemplateAssignEmployee;
        $data['employees'] = Employee::with('employementDetail')->get();
        $data['departments'] = Department::all();
        $data['designations'] = Designation::all();
        $data['responsibilities'] = ResponsibilityEntry::where('status', 'Active')->get();

        return view("HRMS::kpi.kpi-assignments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $kpiTemplateAssignEmployee = $this->service->show($id);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'preparation_date' => 'required|date',
            'kpi_template_id' => 'nullable|exists:kpi_templates,id',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.responsibility_entry_id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.time' => 'required|numeric|min:0',
            'responsibilities.*.frequency' => 'required|string|in:Day,Month,Year',
        ]);
        $this->service->update($kpiTemplateAssignEmployee, $validated);

        return redirect()->route('hrm.kpis.kpi-assignments.index')->with('success', 'KpiTemplateAssignEmployee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kpiTemplateAssignEmployee = $this->service->show($id);
        $this->service->delete($kpiTemplateAssignEmployee);
        return redirect()->route('hrm.kpis.kpi-assignments.index')->with('success', 'KpiTemplateAssignEmployee deleted successfully.');
    }
}
