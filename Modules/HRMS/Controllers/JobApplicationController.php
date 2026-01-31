<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\JobApplication;
use Modules\HRMS\Services\JobApplicationService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Job;
use Modules\Inventory\Services\ExportService;

class JobApplicationController extends Controller
{

    /**
     * Service variable
     *
     * @var JobApplicationService
     */
    private $service; 
    function __construct(JobApplicationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['jobApplications'] = $this->service->getAll();
        $data['branchs']          = Branch::pluck('name', 'id');
        $data['jobs']               = Job::pluck('title', 'id');
        $data['company_info'] = CompanyInfo::first();

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $data['jobApplications'] = $this->service->getAll($data['jobApplications']->total());
            $filename = 'JobApplication_list_ ' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'HRMS::recruitment.applications.export-index.', $filename);
        }
        return view("HRMS::recruitment.applications.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobApplications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('jobApplications.index')->with('success', 'JobApplication created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {

        $data['jobApplication'] = JobApplication::with([
            'jobApplicationEducations',
            'jobApplicationExperiences',
            'job:id,title,branch_id,department_id,designation_id',
            'job.branch:id,name',
            'job.department:id,name',
            'job.designation:id,name',
            ])->findOrFail($id);

        // if ($request->filled('export_type')) {

        //     $pdf = \PDF::loadView('recruitments/applications/export/pdf', $data, [], [
        //         'format' => 'A4',
        //         'margin_header' => 10,
        //         'margin_footer' => 5,
        //     ]);

        //     return $pdf->stream('Employees Information-' . date('Y-m-d') . '.pdf');
        // }

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $filename = 'Employees Information_ ' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'HRMS::recruitment.applications.export.', $filename);
        }

        return view('HRMS::recruitment/applications/show',$data);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $jobApplication)
    {
        $data['jobApplication'] = $jobApplication;
        //
        return view("jobApplications.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($jobApplication, $validate);

        return redirect()->route('jobApplications.index')->with('success', 'JobApplication updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication)
    {
        $this->service->delete($jobApplication);
        return redirect()->route('hrm.job-applications.index')->with('success', 'JobApplication deleted successfully.');
    }

    
    public function updateStatus($id, Request $request)
    {

        $JobApplication = JobApplication::find($id);
        try {


            $JobApplication->update([
                'status' => $request->status,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => $th->getMessage()
            ]);
        }
        return response()->json([
            'status'    => 1,
            'message'   => 'Job have been updated.'
        ]);
    }
}
