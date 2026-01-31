<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use Modules\HRMS\Models\Job;
use Modules\HRMS\Services\JobService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\JobTemplate;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class JobController extends Controller
{

    /**
     * Service variable
     *
     * @var JobService
     */
    private $service; 
    function __construct(JobService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['designations'] = Designation::where('status', 1)->get();
        $data['departments'] = Department::where('status', 1)->get();
        $data['branches'] = Branch::get();
        $data['jobs'] = $this->service->getAll();

        return view("HRMS::recruitment.jobs.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['designations'] = Designation::where('status', 1)->pluck('name', 'id');
        $data['departments'] = Department::where('status', 1)->get()->pluck('name', 'id');
        $data['branches'] = Branch::get()->pluck('name', 'id');
        return view('HRMS::recruitment.jobs.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'branch_id' => 'required|numeric|exists:branches,id',
            'department_id' => 'required|numeric|exists:departments,id',
            'designation_id' => 'required|numeric|exists:designations,id',
            'title' => 'required|string',
            'job_type' => 'required|string',
            'gender' => 'required|string',
            'office_hours' => 'nullable|string',
            'weekend' => 'nullable|string',
            'start_at' => 'nullable|date',
            'deadline_at' => 'nullable|date|after:start_at',
            'salary' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'required|string',
            'company_overview' => 'nullable|string',
            'experience' => 'nullable|string',
            'employee_centric_policy' => 'nullable|string',
            'educational_requirement' => 'nullable|string',
            'responsibility' => 'nullable|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.jobs.index')->with('success', 'Job created successfully.');
    }
    public function fetchJobTemplate(Request $request)
    {
        $branchId = $request->get('branch_id');
        $departmentId = $request->get('department_id');
        $designationId = $request->get('designation_id');
    
        // Fetch the job template based on the selected branch, department, and designation
        $jobTemplate = JobTemplate::where('branch_id', $branchId)
                                    ->where('department_id', $departmentId)
                                    ->where('designation_id', $designationId)
                                    ->first();
    
        // Return the data as JSON
        return response()->json([
            'title' => $jobTemplate->title,
            'salary' => $jobTemplate->salary,
            'office_hours' => $jobTemplate->office_hours,
            'weekend' => $jobTemplate->weekend,
            'location' => $jobTemplate->location,
            'company_overview' => $jobTemplate->company_overview,
            'description' => $jobTemplate->description,
            'experience' => $jobTemplate->experience,
            'employee_centric_policy' => $jobTemplate->employee_centric_policy,
            'educational_requirement' => $jobTemplate->educational_requirement,
            'responsibility' => $jobTemplate->responsibility,
        ]);
    }
    
    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['job'] = $this->service->show($id);

        return view("jobs.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        $data['job'] = $job;
        $data['designations'] = Designation::where('status', 1)->pluck('name', 'id');
        $data['departments'] = Department::where('status', 1)->get()->pluck('name', 'id');
        $data['branches'] = Branch::get()->pluck('name', 'id');
        return view("HRMS::recruitment.jobs.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validate = $request->validate([
            'branch_id' => 'required|numeric|exists:branches,id',
            'department_id' => 'required|numeric|exists:departments,id',
            'designation_id' => 'required|numeric|exists:designations,id',
            'title' => 'required|string',
            'job_type' => 'required|string',
            'gender' => 'required|string',
            'office_hours' => 'nullable|string',
            'weekend' => 'nullable|string',
            'start_at' => 'nullable|date',
            'deadline_at' => 'nullable|date|after:start_at',
            'salary' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'required|string',
            'company_overview' => 'nullable|string',
            'experience' => 'nullable|string',
            'employee_centric_policy' => 'nullable|string',
            'educational_requirement' => 'nullable|string',
            'responsibility' => 'nullable|string',
        ]);
        $this->service->update($job, $validate);

        return redirect()->route('hrm.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $this->service->delete($job);
        return redirect()->route('hrm.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
