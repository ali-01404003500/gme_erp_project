<?php
namespace Modules\HRMS\Services;

use Modules\HRMS\Models\SalaryGenerationPolicy;

class SalaryGenerationPolicyService
{
    public function getPolicy()
    {
        return SalaryGenerationPolicy::first();
    }

    public function updatePolicy(array $data)
    { 
        return $policy = SalaryGenerationPolicy::where('id', 1)->update($data);
         
    }
}
