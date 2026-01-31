<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\MonthlyKpiAppraisal;
use Modules\HRMS\Services\Kpi\MonthlyKpiAppraisalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;

class MonthlyKpiAppraisalController extends Controller
{
    /**
     * Service variable
     *
     * @var MonthlyKpiAppraisalService
     */
    private $service;

    function __construct(MonthlyKpiAppraisalService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['appraisals'] = $this->service->getAll();
        return view("HRMS::kpi.monthly-kpi-appraisals.index", $data);
    }

    /**
     * Get employee details and KPI assignment for selected month
     */
    public function getEmployeeDetails(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $assessmentMonth = $request->input('assessment_month');
        
        return $this->service->getEmployeeDetails($employeeId, $assessmentMonth);
    }

    /**
     * Get remarks based on aggregate score
     */
    public function getRemarksByScore(Request $request)
    {
        $score = $request->input('score', 0);
        return $this->service->getRemarksByScore($score);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['employees'] = Employee::with('employementDetail')->get();
        return view('HRMS::kpi.monthly-kpi-appraisals.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'kpi_template_assign_employee_id' => 'required|exists:kpi_template_assign_employees,id',
            'assessment_month' => 'required|date',
            'achieved_performance_score' => 'nullable|numeric|min:0|max:100',
            'performance_score_note' => 'nullable|string',
            'succession_management_score' => 'nullable|numeric|min:0|max:20',
            'succession_management_note' => 'nullable|string',
            'behavioral_performance_score' => 'nullable|numeric|min:0|max:10',
            'behavioral_performance_note' => 'nullable|string',
            'status' => 'required|in:Draft,Submitted',
            'notes' => 'nullable|string',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.responsibility_entry_id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.target_days' => 'required|numeric|min:0',
            'responsibilities.*.actual_days' => 'nullable|numeric|min:0',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.kpi_score' => 'nullable|numeric|min:0',
            'responsibilities.*.performance_score' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->service->store($validated);
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.index')
                ->with('success', 'Monthly KPI Appraisal saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error occurred while saving: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['appraisal'] = $this->service->show($id);
        return view("HRMS::kpi.monthly-kpi-appraisals.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appraisal = $this->service->show($id);
        
        // Allow editing only for Draft or Submitted status
        if (!in_array($appraisal->status, ['Draft', 'Submitted'])) {
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.show', $id)
                ->with('error', 'Only draft or submitted appraisals can be edited.');
        }

        $data['appraisal'] = $appraisal;
        $data['employees'] = Employee::with('employementDetail')->get();
        
        return view("HRMS::kpi.monthly-kpi-appraisals.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appraisal = $this->service->show($id);
        
        // Validate based on status
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'kpi_template_assign_employee_id' => 'required|exists:kpi_template_assign_employees,id',
            'assessment_month' => 'required|date',
            'achieved_performance_score' => 'nullable|numeric|min:0|max:100',
            'performance_score_note' => 'nullable|string',
            'succession_management_score' => 'nullable|numeric|min:0|max:20',
            'succession_management_note' => 'nullable|string',
            'behavioral_performance_score' => 'nullable|numeric|min:0|max:10',
            'behavioral_performance_note' => 'nullable|string',
            'status' => 'required|in:Draft,Submitted',
            'notes' => 'nullable|string',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*.id' => 'required|exists:monthly_kpi_appraisal_details,id',
            'responsibilities.*.responsibility_entry_id' => 'required|exists:responsibility_entries,id',
            'responsibilities.*.target_days' => 'required|numeric|min:0',
            'responsibilities.*.actual_days' => 'nullable|numeric|min:0',
            'responsibilities.*.weight' => 'required|numeric|min:0|max:100',
            'responsibilities.*.kpi_score' => 'nullable|numeric|min:0',
            'responsibilities.*.performance_score' => 'nullable|numeric|min:0',
        ];

        $validated = $request->validate($rules);

        try {
            $this->service->update($appraisal, $validated);
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.index')
                ->with('success', 'Monthly KPI Appraisal updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error occurred while updating: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appraisal = $this->service->show($id);
        
        if ($appraisal->status !== 'Draft') {
            return back()->with('error', 'Only draft appraisals can be deleted.');
        }

        try {
            $this->service->delete($appraisal);
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.index')
                ->with('success', 'Monthly KPI Appraisal deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred while deleting: ' . $e->getMessage());
        }
    }

    /**
     * Approve appraisal
     */
    public function approve($id)
    {
        $appraisal = $this->service->show($id);
        
        if ($appraisal->status !== 'Submitted') {
            return back()->with('error', 'Only submitted appraisals can be approved.');
        }

        try {
            $this->service->approve($appraisal);
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.index')
                ->with('success', 'Appraisal approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred while approving: ' . $e->getMessage());
        }
    }

    /**
     * Reject appraisal
     */
    public function reject($id)
    {
        $appraisal = $this->service->show($id);
        
        if ($appraisal->status !== 'Submitted') {
            return back()->with('error', 'Only submitted appraisals can be rejected.');
        }

        try {
            $this->service->reject($appraisal);
            return redirect()->route('hrm.kpis.monthly-kpi-appraisals.index')
                ->with('success', 'Appraisal rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred while rejecting: ' . $e->getMessage());
        }
    }
}