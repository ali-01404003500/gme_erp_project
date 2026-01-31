<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\KpiSetup;
use Modules\HRMS\Services\Kpi\KpiSetupService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Settings\Designation;

class KpiSetupController extends Controller
{

    /**
     * Service variable
     *
     * @var KpiSetupService
     */
    private $service; 
    function __construct(KpiSetupService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['kpiSetups'] = $this->service->getAll();

        return view("HRMS::kpi.kpi-setups.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['designations'] = Designation::where('status', 1)->get();
        return view('HRMS::kpi.kpi-setups.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'designation_id' => 'required|exists:designations,id|unique:kpi_setups,designation_id,NULL,id,deleted_at,NULL',
            'kpis.*.description' => 'nullable|string',
            'kpis.*.weight' => 'required|numeric|min:0',
        ]);

        $this->service->store($validated);

        return redirect()->route('hrm.kpis.kpi-setups.index')->with('success', 'KPI Setup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['kpiSetup'] = $this->service->show($id);

        return view("kpiSetups.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KpiSetup $kpiSetup)
    {
        $data['kpiSetup'] = $kpiSetup;
        $data['designations'] = Designation::where('status', 1)->get();
        return view("HRMS::kpi.kpi-setups.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    // KpiSetupController.php

    public function update(Request $request, KpiSetup $kpiSetup)
    {
        // dd($request->all()); // Debugging line to check the request data
        $validated = $request->validate([
            'designation_id' => 'required|exists:designations,id|unique:kpi_setups,designation_id,' . $kpiSetup->id . ',id,deleted_at,NULL',
            'kpis_kpi-setups.*.description' => 'nullable|string',
            'kpis_kpi-setups.*.weight' => 'required|numeric|min:0',
        ]);

        $this->service->update($kpiSetup, $validated);

        return redirect()->route('hrm.kpis.kpi-setups.index')->with('success', 'KPI Setup updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiSetup $kpiSetup)
    {
        $this->service->delete($kpiSetup);
        return redirect()->route('hrm.kpis.kpi-setups.index')->with('success', 'KpiSetup deleted successfully.');
    }
}
