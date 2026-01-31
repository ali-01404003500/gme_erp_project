<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\Assessment;
use Modules\HRMS\Services\Kpi\AssessmentService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Kpi\KpiSetup;

class AssessmentController extends Controller
{

    /**
     * Service variable
     *
     * @var AssessmentService
     */
    private $service; 
    function __construct(AssessmentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['assessments'] = $this->service->getAll();
        $data['employees'] = Employee::with('employementDetail.designation')->where('status', 1)->get();

        return view("HRMS::kpi.assessments.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        $employees = Employee::with('employementDetail.designation')->where('status', 1)->get();

        // Preload all KPI setups grouped by designation_id
        $designationKpis = KPISetup::with('details')
        ->get()
        ->mapWithKeys(function ($setup) {
            return [$setup->designation_id => $setup->details];
        });

        return view('HRMS::kpi.assessments.create', [
            'employees' => $employees,
            'designationKpis' => $designationKpis,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'from_to_date'    => 'required|string',
            'status'          => 'required',
            'kpis'            => 'required|array',
            'kpis.*.id'       => 'required|exists:kpi_setup_details,id',
            'kpis.*.mark'     => 'required|numeric|min:0',
            'kpis.*.weight'   => 'required|numeric|min:0',
            'kpis.*.remarks'  => 'nullable|string',
            'kpis.*.description' => 'nullable|string',
            'total_mark'      => 'required|numeric',
            'total_weight'    => 'required|numeric',
            'overall_score'   => 'required|numeric',
        ]);

        $this->service->store($validated);

        return redirect()->route('hrm.kpis.assessments.index')->with('success', 'Assessment created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['assessment'] = $this->service->show($id);

        return view("assessments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment)
    {
        $data['assessment'] = $assessment;
        $data['employees'] = Employee::with('employementDetail.designation')->where('status', 1)->get();
        // Preload all KPI setups grouped by designation_id
        $data['designationKpis'] = KpiSetup::with('details')
            ->get()
            ->mapWithKeys(function ($setup) {
                return [$setup->designation_id => $setup->details];
            });
        return view("HRMS::kpi.assessments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        // dd($request->all());
        $validate = $request->validate([
           'employee_id'     => 'required|exists:employees,id',
            'from_to_date'    => 'required|string',
            'status'          => 'required',
            'kpis'            => 'required|array',
            'kpis.*.id'       => 'required',
            'kpis.*.mark'     => 'required|numeric|min:0',
            'kpis.*.weight'   => 'required|numeric|min:0',
            'kpis.*.remarks'  => 'nullable|string',
            'kpis.*.description' => 'nullable|string',
            'total_mark'      => 'required|numeric',
            'total_weight'    => 'required|numeric',
            'overall_score'   => 'required|numeric',
        ]);

        $this->service->update($assessment, $validate);

        return redirect()->route('hrm.kpis.assessments.index')->with('success', 'Assessment updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assessment $assessment)
    {
        $this->service->delete($assessment);
        return redirect()->route('hrm.kpis.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}
