<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\JobApplication;

class JobApplicationService
{
    
    public function getAll(int $limit = 20) {
        return JobApplication::query()
        ->searchByFields(['job_id','status'])
        ->filterByDateRange('created_at')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return JobApplication::create($data);
    }

    public function update(JobApplication $jobApplication, array $data)
    {
        $jobApplication->update($data);
        return $jobApplication;
    }

    public function delete(JobApplication $jobApplication)
    {
        $jobApplication->delete();
    }

    public function show($id)
    {
        return JobApplication::findOrFail($id);
    }
}
