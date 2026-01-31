<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use Modules\HRMS\Models\JobTemplate;
use Modules\HRMS\Services\JobTemplateService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class JobTemplateController extends Controller
{

    /**
     * Service variable
     *
     * @var JobTemplateService
     */
    private $service; 
    function __construct(JobTemplateService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['jobTemplates'] = $this->service->getAll();
        $data['designations'] = Designation::where('status', 1)->get();
        $data['departments'] = Department::where('status', 1)->get();
        $data['branches'] = Branch::get();
        return view("HRMS::recruitment.job-templates.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['designations'] = Designation::where('status', 1)->pluck('name', 'id');
        $data['departments'] = Department::where('status', 1)->get()->pluck('name', 'id');
        $data['branches'] = Branch::get()->pluck('name', 'id');
        return view('HRMS::recruitment.job-templates.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'branch_id' => 'required|numeric|unique:job_templates,branch_id,NULL,id,department_id,'.$request->department_id.',designation_id,'.$request->designation_id,
            'department_id' => 'required|numeric',
            'designation_id' => 'required|numeric',
            'salary' => 'nullable|string|max:255',
            'office_hours' => 'nullable|string|max:255',
            'weekend' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'company_overview' => 'nullable|string',
            'description' => 'nullable|string',
            'experience' => 'nullable|string',
            'employee_centric_policy' => 'nullable|string',
            'educational_requirement' => 'nullable|string',
            'responsibility' => 'nullable|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.job-templates.index')->with('success', 'JobTemplate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['jobTemplate'] = $this->service->show($id);

        return view("jobTemplates.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobTemplate $jobTemplate)
    {
        $data['jobTemplate'] = $jobTemplate;
        $data['designations'] = Designation::where('status', 1)->pluck('name', 'id');
        $data['departments'] = Department::where('status', 1)->get()->pluck('name', 'id');
        $data['branches'] = Branch::get()->pluck('name', 'id');
        return view("HRMS::recruitment.job-templates.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobTemplate $jobTemplate)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'branch_id' => 'required|numeric|unique:job_templates,branch_id,'.$jobTemplate->id.',id,department_id,'.$request->department_id.',designation_id,'.$request->designation_id,
            'department_id' => 'required|numeric',
            'designation_id' => 'required|numeric',
            'salary' => 'nullable|string|max:255',
            'office_hours' => 'nullable|string|max:255',
            'weekend' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'company_overview' => 'nullable|string',
            'description' => 'nullable|string',
            'experience' => 'nullable|string',
            'employee_centric_policy' => 'nullable|string',
            'educational_requirement' => 'nullable|string',
            'responsibility' => 'nullable|string', 
        ]);
        $this->service->update($jobTemplate, $validate);

        return redirect()->route('hrm.job-templates.index')->with('success', 'JobTemplate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTemplate $jobTemplate)
    {
        $this->service->delete($jobTemplate);
        return redirect()->route('hrm.job-templates.index')->with('success', 'JobTemplate deleted successfully.');
    }
}
