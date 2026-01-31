<?php

namespace Modules\HRMS\Services;

use App\Traits\S3FileHandler;
use Modules\HRMS\Models\Career;
use Modules\HRMS\Models\Job;
use Modules\HRMS\Models\JobApplication;

class CareerService
{

    use S3FileHandler;

    public $jobApplication;
    public function getAll(int $limit = 20) {
        return Job::query()
        ->where('start_at', '<=', date('Y-m-d'))
        ->where('deadline_at', '>=', date('Y-m-d'))
        ->paginate($limit);
    }
    
    public function show($id)
    {
        return Job::findOrFail($id);
    }

   
    public function applicantInfo($job_id, $request)
    {
        $data = [
            'name'                      => $request->name,
            'mobile'                    => $request->mobile,
            'present_address'           => $request->filled('present_address') ? $request->present_address : null,
            'permanent_address'         => $request->filled('permanent_address') ? $request->permanent_address : null,
            'father_or_husband_name'     => $request->father_or_husband_name,
            'mother_name'               => $request->mother_name,
            'national_id'             => $request->filled('national_id') ? $request->national_id : null,
        ];
    
        // Handle file upload for image
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'Careers/Images');
        }
    
        // Handle file upload for CV
        if ($request->hasFile('cv')) {
            $data['cv'] = $this->uploadFile($request->file('cv'), 'Careers/CV');
        }
    
        // Store or update job application record
        $this->jobApplication = JobApplication::firstOrCreate(
            [
                'job_id' => $job_id,
                'email'  => $request->email,
            ],
            $data
        );
    }
    
    public function applicatantJobExperience($request)
    {   
        if (!$request->filled('company_name')) {
            return;
        }
    
        foreach ($request->company_name as $key => $company_name) {
            // Check if the company name is empty
            if (empty($company_name)) {
                continue; // Skip this iteration if company name is empty
            }
            $duration = $request->from_dates[$key] . " to " . $request->to_dates[$key] ;
            $this->jobApplication->jobApplicationExperiences()->create([
                'company_name'         => $company_name,
                'designations'     => $request->designations[$key],
                'duration'        => $duration,
            ]);
        }
    }
    
    public function applicatantEducation($request)
    {
        if (!$request->filled('examination')) {
            return;
        }
    
        foreach ($request->examination as $key => $examination) {
            $this->jobApplication->jobApplicationEducations()->create([
                'examination' => $examination,
                'institute'   => $request->institute[$key],
                'result'      => $request->result[$key],
                'passing_year' => $request->passing_year[$key],
            ]);
        }
    }
}
