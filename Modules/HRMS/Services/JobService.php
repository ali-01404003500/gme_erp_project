<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\Job;

class JobService
{
    
    public function getAll(int $limit = 20) {
        return Job::query()
        ->searchByFields(['title', 'department_id', 'designation_id'])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Job::create($data);
    }

    public function update(Job $job, array $data)
    {
        $job->update($data);
        return $job;
    }

    public function delete(Job $job)
    {
        $job->delete();
    }

    public function show($id)
    {
        return Job::findOrFail($id);
    }
}
