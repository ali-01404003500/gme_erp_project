<?php
namespace Modules\HRMS\Services;

use Modules\HRMS\Models\SalaryGenerationPolicy;

class SalaryGenerationPolicyService
{
    public function getPolicy()
    {
        return SalaryGenerationPolicy::first() ?? new SalaryGenerationPolicy([
            'calculation_type'             => 'actual_days',
            'fixed_days'                   => 30,
            'is_rounded_salary'            => false,
            'is_salary_end_date_different' => false,
        ]);
    }

    public function updatePolicy(array $data)
    {
        $policy = SalaryGenerationPolicy::first() ?? new SalaryGenerationPolicy();

        $policy->calculation_type             = $data['calculation_type'];
        $policy->fixed_days                   = ($data['calculation_type'] === 'fixed_days') ? $data['fixed_days'] : null;
        $policy->is_rounded_salary            = isset($data['is_rounded_salary']);
        $policy->is_salary_end_date_different = isset($data['is_salary_end_date_different']);

        return $policy->save();
    }
}
