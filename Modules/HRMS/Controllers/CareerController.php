<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Traits\S3FileHandler;
use Modules\HRMS\Models\Career;
use Modules\HRMS\Services\CareerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Job;
use Modules\HRMS\Models\JobApplication;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class CareerController extends Controller
{
    use S3FileHandler;

    public $jobApplication;

    /**
     * Service variable
     *
     * @var CareerService
     */
    private $service; 
    function __construct(CareerService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['jobs'] = $this->service->getAll();

        return view("HRMS::recruitment.frontend.jobs.index", $data);
    }



    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $id = explode('-', $slug)[0];
        $data['job'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();
        return view("HRMS::recruitment.frontend.jobs.show", $data);
    }


    public function jobApply($id, Request $request)
    {

        $data['job'] = Job::findOrFail($id);

        $data['branches']      =   Branch::query()->select('id', 'name')->get();
        $data['departments']    =   Department::select('id', 'name')->where('status', '1')->get();
        $data['designations']   =  Designation::select('id', 'name')->get();


        return view('HRMS::recruitment.frontend.apply.create', $data);
    }
    
    public function jobApplicationStore($id, Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'                   => 'required',
            'mobile'                 => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/', 'unique:job_applications,mobile,NULL,id,deleted_at,NULL'],
            'national_id'            => 'nullable',
            'email'                  => 'required|email|unique:job_applications,email,NULL,id,deleted_at,NULL',
            'image'                  => 'nullable', // Validation for image file
            'cv'                     => 'nullable', // Validation for CV file
            'father_or_husband_name' => 'required',
            'mother_name'            => 'required',
            'company_name'           => 'filled',
            'examination'            => 'filled',
        ]);

        $this->service->applicantInfo($id, $request);
        $this->service->applicatantJobExperience($request);
        $this->service->applicatantEducation($request);
        
        return redirect()->route('carrier.index', $id)->with('success', 'Job Application Successfully Submitted, We will contact you soon.');
    }
  
}
