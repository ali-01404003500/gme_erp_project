<?php

namespace Modules\HRMS\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\AppraisalPolicy;
use Modules\HRMS\Services\Settings\AppraisalPolicyService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class AppraisalPolicyController extends Controller
{

    /**
     * Service variable
     *
     * @var AppraisalPolicyService
     */
    private $service; 
    function __construct(AppraisalPolicyService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['appraisalPolicys'] = $this->service->getAll();
        $data['designations'] = Designation::where('status', 1)->get();

        return view("HRMS::settings.appraisal-policies.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appraisalPolicys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'designation_id' => 'required|integer|exists:designations,id',
            'period' => 'required|integer|min:1',
            'period_type' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.appraisal-policies.index')->with('success', 'AppraisalPolicy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['appraisalPolicy'] = $this->service->show($id);

        return view("appraisalPolicys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppraisalPolicy $appraisalPolicy)
    {
        $data['appraisalPolicy'] = $appraisalPolicy;
        //
        return view("appraisalPolicys.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppraisalPolicy $appraisalPolicy)
    {
        $validate = $request->validate([
            'designation_id' => 'required|integer|exists:designations,id',
            'period' => 'required|integer|min:1',
            'period_type' => 'required',
        ]);
        $this->service->update($appraisalPolicy, $validate);

        return redirect()->route('hrm.settings.appraisal-policies.index')->with('success', 'AppraisalPolicy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppraisalPolicy $appraisalPolicy)
    {
        $this->service->delete($appraisalPolicy);
        return redirect()->route('hrm.settings.appraisal-policies.index')->with('success', 'AppraisalPolicy deleted successfully.');
    }
}
