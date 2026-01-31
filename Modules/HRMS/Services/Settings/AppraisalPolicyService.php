<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\AppraisalPolicy;

class AppraisalPolicyService
{
    
    public function getAll(int $limit = 20) {
        return AppraisalPolicy::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AppraisalPolicy::create($data);
    }

    public function update(AppraisalPolicy $appraisalPolicy, array $data)
    {
        $appraisalPolicy->update($data);
        return $appraisalPolicy;
    }

    public function delete(AppraisalPolicy $appraisalPolicy)
    {
        $appraisalPolicy->delete();
    }

    public function show($id)
    {
        return AppraisalPolicy::findOrFail($id);
    }
}
