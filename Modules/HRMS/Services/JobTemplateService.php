<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\JobTemplate;

class JobTemplateService
{
    
    public function getAll(int $limit = 20) {
        return JobTemplate::query()
        ->searchByFields(['title', 'department_id', 'designation_id','branch_id'])->paginate($limit);
    }
    
    public function store(array $data)
    {
        return JobTemplate::create($data);
    }

    public function update(JobTemplate $jobTemplate, array $data)
    {
        $jobTemplate->update($data);
        return $jobTemplate;
    }

    public function delete(JobTemplate $jobTemplate)
    {
        $jobTemplate->delete();
    }

    public function show($id)
    {
        return JobTemplate::findOrFail($id);
    }
}
